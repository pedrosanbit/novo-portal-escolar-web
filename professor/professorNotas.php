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
                <a class="nav-link" href="#"><i class="fas fa-search"></i> Consultas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="professorFrequencia.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
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
          <a class="nav-link text-dark" href="#"><i class="fas fa-search"></i> Consultas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorFrequencia.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
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
        <form method="post" id="campos">
	        <div class="row">
	          <div class="col-md-4">
	            <label for="codTurma" class="form-label">Turma:</label>
	            <select class="form-select" type="text" id="codTurma" name="codTurma" maxlength="6" onchange="submit(codTurma.value);">
	            <?php
                  if($_SERVER["REQUEST_METHOD"] != "POST") {
                    if(isset($_GET["turma"])) {
                      include("../conexaoBD.php");
                      try {
                        $stmt = $pdo->prepare("select * from TurmasTCC where codTurma = :codTurma");
                        $stmt->bindParam(":codTurma", $_GET["turma"]);
                        $stmt->execute();
                        $row = $stmt->fetch();
                        echo "<option value='" . $row["codTurma"] . "'>" . $row["nomeTurma"] . "</option>";
                        echo "<script>document.getElementById('campos').submit(codTurma.value);</script>";
                      }
                      catch(PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                      }
                      finally{
                        $pdo = null;
                      }
                    }
                  }
	                try{
                    include("../conexaoBD.php");
	                	if(isset($_POST["codTurma"]) && $_POST["codTurma"] != 'null') {
	                		$codTurma = $_POST["codTurma"];
                        $stmt= $pdo->prepare("select t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma where l.rfProfessor= :rf and t.codTurma = :codTurma and t.periodo = :periodo");
                        $stmt->bindParam(":rf", $_SESSION["login"]);
                        $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                        $stmt->bindParam(":periodo", date("Y"));
                        $stmt->execute();
                        $row = $stmt->fetch();
			                  $stmt= $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma where l.rfProfessor= :rf and t.codTurma != :codTurma and t.periodo = :periodo");
			                  $stmt->bindParam(":rf", $_SESSION["login"]);
			                  $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                        $stmt->bindParam(":periodo", date("Y"));
			                  $stmt->execute();
			                  echo "<option value='" . $_POST["codTurma"] . "'>" . $row["nomeTurma"] . "</option>";
	                  	}
	                  	else{
	                  		 $stmt= $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma where l.rfProfessor= :rf and t.periodo = :periodo");
			                   $stmt->bindParam(":rf", $_SESSION["login"]);
                         $stmt->bindParam(":periodo", date("Y"));
			                   $stmt->execute();
			                echo "<option value='null'></option>";
	                  	}

	                  	while($row= $stmt->fetch()){
			                   echo "<option value='". $row["codTurma"] ."'>".$row["nomeTurma"]."</option>";
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
	            <label for="codDisciplina" class="form-label">Disciplina:</label>
	            <select class="form-select" type="text" id="codDisciplina" name="codDisciplina" onchange="submit(codTurma.value, codDisciplina.value)";>
	            	<?php
	            		if(isset($_POST["codTurma"]) && $_POST["codTurma"] != 'null'){
	            			include("../conexaoBD.php");
			                try{
			                	if(isset($_POST["codDisciplina"]) && $_POST["codDisciplina"] != 'null') {
                          $stmt = $pdo->prepare("select * from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where d.codDisciplina = :codDisciplina and l.codTurma = :codTurma");
                          $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
                          $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                          $stmt->execute();
                          $rows = $stmt->rowCount();
                          if($rows > 0) {
                            $stmt = $pdo->prepare("select * from DisciplinasTCC where codDisciplina = :codDisciplina");
                            $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
                            $stmt->execute();
                            $row = $stmt->fetch();
                            $stmt= $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.rfProfessor= :rf and l.codTurma= :codTurma and d.codDisciplina != :codDisciplina");
                            $stmt->bindParam(":rf", $_SESSION["login"]);
                            $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                            $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
                            $stmt->execute();
                            echo "<option value='" . $_POST["codDisciplina"] . "'>" . $row["nomeDisciplina"] . "</option>";
                          }
                          else {
                            unset($_POST["codDisciplina"]);
                            $stmt= $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.rfProfessor= :rf and l.codTurma= :codTurma");
                            $stmt->bindParam(":rf", $_SESSION["login"]);
                            $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                            $stmt->execute();
                            echo "<option value='null'></option>";
                          }
				                }
				                else{
				                	$stmt= $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.rfProfessor= :rf and l.codTurma= :codTurma");
				                  	$stmt->bindParam(":rf", $_SESSION["login"]);
				                  	$stmt->bindParam(":codTurma", $_POST["codTurma"]);
				                  	$stmt->execute();
				                  	echo "<option value='null'></option>";
				                }
				                  while($row= $stmt->fetch()){
				                    echo "<option value='". $row["codDisciplina"] ."'>".$row["nomeDisciplina"]."</option>";
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
	            <label for="codAtividade" class="form-label">Nome da Avaliação:</label>
	            <select class='form-select' id='codAtividade' name='codAtividade' aria-label='Default select example'>
	            	<?php

	            		if(isset($_POST["codTurma"]) && $_POST["codTurma"] != 'null' && isset($_POST["codDisciplina"]) && $_POST["codDisciplina"] != 'null'){
	            			include("../conexaoBD.php");
			                try{
			                	if(isset($_POST["codAtividade"]) && $_POST["codAtividade"] != 'null') {
                          $stmt = $pdo->prepare("select a.codAtividade from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC dta on a.codAtividade = dta.codAtividade where dta.codTurma = :codTurma and a.codAtividade = :codAtividade");
                          $stmt->bindParam(":codAtividade", $_POST["codAtividade"]);
                          $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                          $stmt->execute();
                          $rows = $stmt->rowCount();
                          if($rows > 0) {
                            $stmt = $pdo->prepare("select a.codAtividade from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC dta on a.codAtividade = dta.codAtividade where dta.codDisciplina = :codDisciplina and a.codAtividade = :codAtividade");
                            $stmt->bindParam(":codAtividade", $_POST["codAtividade"]);
                            $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                            $stmt->execute();
                            $rows = $stmt->rowCount();
                            if($rows > 0) {
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
                            }
                            else {
                              unset($_POST["codAtividade"]);
                              $stmt= $pdo->prepare("select a.descricao, a.codAtividade from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC s on a.codAtividade=s.codAtividade where s.codTurma= :codTurma and s.codDisciplina= :codDisciplina");
                              $stmt->bindParam(":codTurma", $_POST["codTurma"]);
                              $stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
                              $stmt->execute(); 
                            }
                          }
                          else {
                            unset($_POST["codAtividade"]);
                          }
				                }
				                else{
				                	$stmt= $pdo->prepare("select a.descricao, a.codAtividade from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC s on a.codAtividade=s.codAtividade where s.codTurma= :codTurma and s.codDisciplina= :codDisciplina");
				                  	$stmt->bindParam(":codTurma", $_POST["codTurma"]);
				                  	$stmt->bindParam(":codDisciplina", $_POST["codDisciplina"]);
				                  	$stmt->execute();
				                  	//echo "<option value='null'></option>";                            
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
          <div class="mt-4 text-center">
             <button type="submit" class="btn btn-primary rounded-pill text-white w-25"><b>Consultar</b></button>  
          </div>
	    </form>
      <?php
        if($_SERVER["REQUEST_METHOD"] === "POST") {
          if(isset($_POST["codTurma"]) && isset($_POST["codDisciplina"]) && isset($_POST["codAtividade"])) {
            echo "<script>window.location.replace('editNotas.php?turma=".$_POST["codTurma"]."&disc=".$_POST["codDisciplina"]."&ativ=".$_POST["codAtividade"]."')</script>";
          }
        }
        //include('consultaTurmaAtividade.php')
      ?>

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