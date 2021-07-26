 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'prof')
    header('location:../index.php');

  include("../conexaoBD.php");
  try {
    $stmt = $pdo->prepare("select p.rfProfessor, p.nomeProfessor from ProfessoresTCC p where p.rfProfessor = " . $_SESSION['login']);
    $stmt->execute();
    $row = $stmt->fetch();
    if(strpos($row['nomeProfessor']," ")) {
      $nome = substr($row['nomeProfessor'], 0, strpos($row['nomeProfessor']," "));
    }
    else
      $nome = $row['nomeProfessor'];
  }
  catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
  finally {
    $pdo = null;
  }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=0.5, maximum-scale=3, shrink-to-fit=no">
		<title>Portal Escolar</title>
		<link rel="icon" href="../logoUnicampAzul.png">
		<!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"-->
		<link rel="stylesheet" href="../custom.css">
    <style type="text/css">
      th, td {
        padding-left: 1rem;
      }

      #navsanduiche {
        visibility: hidden;
        position: absolute;
        top: -9999px;
      }

      @media (max-width: 600px) {
        #btn-logout {
          visibility: hidden;
          position: absolute;
          top: -9999px;
        }

        #navbar {
          visibility: hidden;
          position: absolute;
          top: -9999px;
        }

        #navtabs {
          visibility: hidden;
          position: absolute;
          top: -9999px;
        }

        #navsanduiche {
          visibility: visible;
          position: static;
        }
      }
    </style>
		<script src="https://kit.fontawesome.com/ebb5206ba7.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<nav class="navbar navbar-dark bg-primary" id="navbar">
  			<div class="container-fluid">
    			<a class="navbar-brand" href="#">
    				<img src="../logoUnicamp.png" width="32" class="d-inline-block align-text-top">
    				Portal Escolar
    			</a>
		    	<form class="d-flex text-white">
		    		<div class="form-check form-switch">
		  				<input class="form-check-input" type="checkbox" id="darkSwitch" onchange="setTema();">
		  				<i id='lua' class="far fa-moon"></i>
					</div>
		    	</form>
  			</div>
		</nav>
    <div id="navsanduiche">
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="navbar2">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="../logoUnicamp.png" width="32" class="d-inline-block align-text-top">
              Portal Escolar
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="professor.php"><i class="fas fa-home"></i> Início</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="professorNotas.php"><b><i class="fas fa-file-alt"></i> Notas</b></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="professorPlanejamento.php"><i class="fas fa-chalkboard"></i> Planejamento</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
              </li>
            </ul>
            <form class="d-flex text-white">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="darkSwitch2" onchange="toggleDarkSwitch();">
                <i id='lua2' class="far fa-moon"></i>
              </div>
            </form>
          </div>
        </div>
      </nav>
    </div>
    <div id="navtabs">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link text-dark" href="professor.php"><i class="fas fa-home"></i> Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" aria-current="page" href="professorNotas.php"><b><i class="fas fa-file-alt"></i> Notas</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorPlanejamento.php"><i class="fas fa-chalkboard"></i> Planejamento</a>
        </li>
      </ul>
    </div>

    <div class="container mt-3">
      <div class="mb-3">
        Período: <?php echo date("Y");?>
      </div>
      <!--?php
        include("../conexaoBD.php");
        try {
          /*$stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on l.rfProfessor = p.rfProfessor where p.rfProfessor = " .  $_SESSION["login"] . " and t.periodo = " . date("Y"));
          $stmt->execute();
          while($row = $stmt->fetch()) {
            echo "<div class='mb-3'>
                    <div class='accordion' id='accordionExample".$row['codTurma']."'>
                      <div class='accordion-item'>
                        <h2 class='accordion-header' id='headingTwo'>
                          <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseTwo".$row['codTurma']."' aria-expanded='false' aria-controls='collapseTwo'><b>Turma ".
                            $row['nomeTurma'].
                          "</b></button>
                        </h2>
                        <div id='collapseTwo".$row['codTurma']."' class='accordion-collapse collapse' aria-labelledby='headingTwo' data-bs-parent='#accordionExample".$row['codTurma']."'>
                          <div class='accordion-body'>
                            <ul>
                              <li>
                                <a style='text-decoration: none;' href='#'><i class='fas fa-file-excel'></i> 1° Trimestre</a>
                              </li>
                              <li>
                                <a style='text-decoration: none;' href='#'><i class='fas fa-file-excel'></i> 2° Trimestre</a>
                              </li>
                              <li>
                                <a style='text-decoration: none;' href='#'><i class='fas fa-file-excel'></i> 3° Trimestre</a>
                              </li>
                              <li>
                                <a style='text-decoration: none;' href='#'><i class='fas fa-file-excel'></i> 4° Trimestre</a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>";-->

        <form method="post">
	        <div class="row">
	          <div class="col-md-4">
	            <label for="codTurma" class="form-label">Código da Turma:</label>
	            <select class="form-select" type="text" id="codTurma" name="codTurma" maxlength="6" onchange="submit(codTurma.value)";>
	            <?php
	                include("../conexaoBD.php");
	                try{
	                	if(isset($_POST["codTurma"]) && $_POST["codTurma"] != 'null') {
	                		$codTurma = $_POST["codTurma"];
			                  $stmt= $pdo->prepare("select distinct codTurma from LecionaTCC where rfProfessor= :rf and codTurma != :codTurma ");
			                  $stmt->bindParam(":rf", $_SESSION["login"]);
			                  $stmt->bindParam(":codTurma", $_POST["codTurma"]);
			                  $stmt->execute();
			                  echo "<option value='" . $_POST["codTurma"] . "'>" . $_POST["codTurma"] . "</option>";
	                  	}
	                  	else{
	                  		 $stmt= $pdo->prepare("select distinct codTurma from LecionaTCC where rfProfessor= :rf");
			                   $stmt->bindParam(":rf", $_SESSION["login"]);
			                   $stmt->execute();
			                echo "<option value='null'></option>";
	                  	}

	                  	while($row= $stmt->fetch()){
			                   echo "<option value='". $row["codTurma"] ."'>".$row["codTurma"]."</option>";
	                  	}

	                }
	                catch(PDOException $e){
	                  echo 'Error: ' . $e->getMessage();
	                }
	                finally{
	                  $pdo=null;
	                }  
              	?>
	            </select>
	          </div>

	          <div class="col-md-4">
	            <label for="codDisciplina" class="form-label">Código da Disciplina:</label>
	            <select class="form-select" type="text" id="codDisciplina" name="codDisciplina" onchange="submit(codTurma.value, codDisciplina.value)";>
	            	<?php
	            		if(isset($_POST["codTurma"]) && $_POST["codTurma"] != 'null'){
	            			include("../conexaoBD.php");
			                try{
			                	if(isset($_POST["codDisciplina"]) && $_POST["codDisciplina"] != 'null') {
				                  $stmt= $pdo->prepare("select codDisciplina from LecionaTCC where rfProfessor= :rf and codTurma= :codTurma and codDisciplina!= :codDisciplina");
				                  $stmt->bindParam(":rf", $_SESSION["login"]);
				                  $stmt->bindParam(":codTurma", $_POST["codTurma"]);
				                  $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
				                  $stmt->execute();
				                  echo "<option value='" . $_POST["codDisciplina"] . "'>" . $_POST["codDisciplina"] . "</option>"; 
				                }
				                else{
				                	$stmt= $pdo->prepare("select codDisciplina from LecionaTCC where rfProfessor= :rf and codTurma= :codTurma");
				                  	$stmt->bindParam(":rf", $_SESSION["login"]);
				                  	$stmt->bindParam(":codTurma", $_POST["codTurma"]);
				                  	$stmt->execute();
				                  	echo "<option value='null'></option>";
				                }
				                  while($row= $stmt->fetch()){
				                    echo "<option value='". $row["codDisciplina"] ."'>".$row["codDisciplina"]."</option>";
				                  }

			                }
			                catch(PDOException $e){
			                  echo 'Error: ' . $e->getMessage();
			                }

		                	finally{
		                  		$pdo=null;
		                	}
		            	}
		                
              	?>
	            </select>
	          </div>

	          <div class="col-md-4">
	            <label for="codAtividade" class="form-label">Nome da Atividade:</label>
	            <select class='form-select' id='codAtividade' name='codAtividade' aria-label='Default select example' onchange="submit(codTurma.value, codDisciplina.value , codAtividade.value)";>
	            	<?php

	            		if(isset($_POST["codTurma"]) && $_POST["codTurma"] != 'null' && isset($_POST["codDisciplina"]) && $_POST["codDisciplina"] != 'null'){
	            			include("../conexaoBD.php");
			                try{
			                	if(isset($_POST["codAtividade"]) && $_POST["codAtividade"] != 'null') {

                          $stmt= $pdo->prepare("select a.descricao from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC s on a.codAtividade=s.codAtividade where a.codAtividade= :codAtividade and s.codTurma= :codTurma and s.codDisciplina= :codDisciplina");
                          $stmt->bindParam(":codAtividade", $_POST["codAtividade"]);
                          $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                          $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
                          $stmt->execute();
                          $nomeAtiv=$stmt->fetch();
                          echo "<option value='" . $_POST["codAtividade"] . "'>" . $nomeAtiv["descricao"] . "</option>"; 

                          $stmt= $pdo->prepare("select distinct a.descricao, a.codAtividade from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC s on a.codAtividade=s.codAtividade where s.codTurma= :codTurma and s.codDisciplina= :codDisciplina and a.codAtividade != :codAtividade");
                          $stmt->bindParam(":codAtividade", $_POST["codAtividade"]);
                          $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                          $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
                          $stmt->execute();

                          //echo "<script>window.location.replace('professorNotas.php?codTurma=".$_POST["codTurma"]."&codDisciplina=".$_POST["codDisciplina"]."&codAtividade=".$_POST["codAtividade"]."');</script>";
				                 
				                }
				                else{
				                	$stmt= $pdo->prepare("select a.descricao, a.codAtividade from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC s on a.codAtividade=s.codAtividade where s.codTurma= :codTurma and s.codDisciplina= :codDisciplina");
				                  	$stmt->bindParam(":codTurma", $_POST["codTurma"]);
				                  	$stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
				                  	$stmt->execute();
				                  	echo "<option value='null'></option>";                            
				                }
				                  while($row= $stmt->fetch()){
				                    echo "<option value='". $row["codAtividade"] ."'>".$row["descricao"]."</option>";
				                  } 
			                }
			                catch(PDOException $e){
			                  echo 'Error: ' . $e->getMessage();
			                }

		                	finally{
		                  		$pdo=null;
		                	}
		            	}	
	            	?>
	            </select>
	          </div>
	        </div>
          <!--div class="mt-4 text-center">
             <button type="submit" class="btn btn-primary rounded-pill text-white w-25"><b>Consultar</b></button>  
          </div-->
	    </form>

      <?php include('consultaTurmaAtividade.php')?>

          <!--}
        }
        catch(PDOException $e) {
          echo 'Error: ' . $e->getMessage();
        }
        finally {
          $pdo = null;
        }
      ?-->
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>