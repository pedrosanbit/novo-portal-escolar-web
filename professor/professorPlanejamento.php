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
                <a class="nav-link" aria-current="page" href="professor.php"><i class="fas fa-home"></i> Início</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="professorNotas.php"><i class="fas fa-file-alt"></i> Notas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="professorPlanejamento.php"><b><i class="fas fa-chalkboard"></i> Planejamento</b></a>
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
          <a class="nav-link text-dark" aria-current="page" href="professor.php"><i class="fas fa-home"></i> Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorNotas.php"><i class="fas fa-file-alt"></i> Notas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="professorPlanejamento.php"><b><i class="fas fa-chalkboard"></i> Planejamento</b></a>
        </li>
      </ul>
    </div>

    <div class="container mt-3">
      <form method="post">
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <label for="periodo" class="form-label">Período:</label>
            <select class="form-select" id="periodo" name="periodo" aria-label="Default select example" onchange="submit(periodo.value);">
              <?php
                include("../conexaoBD.php");
                try {
                  if(isset($_POST["periodo"]) && $_POST["periodo"] != 'null') {
                    $stmt = $pdo->prepare("select distinct t.periodo from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on l.rfProfessor = p.rfProfessor where p.rfProfessor = " . $_SESSION['login'] . " and t.periodo != " . $_POST["periodo"]);
                    $stmt->execute();
                    echo "<option value='" . $_POST["periodo"] . "'>" . $_POST["periodo"] . "</option>";
                  }
                  else {
                    $stmt = $pdo->prepare("select distinct t.periodo from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on l.rfProfessor = p.rfProfessor where p.rfProfessor = " . $_SESSION['login'] . " and t.periodo != " . date("Y"));
                    $stmt->execute();
                    echo "<option value='null'></option>";
                    echo "<option value='" . date("Y") ."'>" . date("Y") ."</option>";
                  }
                  while($row= $stmt->fetch()){
                    echo "<option value='". $row["periodo"] ."'>".$row["periodo"]."</option>";
                  }
                }
                catch(PDOException $e) {
                  echo 'Error: ' . $e->getMessage();
                }
                finally{
                  $pdo=null;
                }
              ?>
            </select>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="turma" class="form-label">Turma:</label>
            <select class="form-select" id="turma" name="turma" aria-label="Default select example" onchange="submit(periodo.value, turma.value);">
              <?php
                if(isset($_POST["periodo"]) && $_POST["periodo"] != 'null') {
                  $periodo = $_POST["periodo"];
                  include("../conexaoBD.php");
                  try {
                    if(isset($_POST["turma"]) && $_POST["turma"] != 'null') {
                      $stmt = $pdo->prepare("select * from TurmasTCC where codTurma = :codTurma and periodo = :periodo");
                      $stmt->bindParam(":codTurma", $_POST["turma"]);
                      $stmt->bindParam(":periodo", $_POST["periodo"]);
                      $stmt->execute();
                      $rows = $stmt->rowCount();
                      if($rows > 0) {
                        $stmt = $pdo->prepare("select nomeTurma from TurmasTCC where codTurma = :codTurma");
                        $stmt->bindParam(":codTurma",$_POST["turma"]);
                        $stmt->execute();
                        if ($row = $stmt->fetch()) {
                          echo "<option value='".$_POST["turma"]."'>".$row["nomeTurma"]."</option>";
                        }
                        $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on l.rfProfessor = p.rfProfessor where p.rfProfessor = :login and t.periodo = :periodo and t.codTurma != :turma");
                        $stmt->bindParam(":login",$_SESSION["login"]);
                        $stmt->bindParam(":periodo",$periodo);
                        $stmt->bindParam(":turma",$_POST["turma"]);
                        $stmt->execute();
                      }
                      else {
                        unset($_POST["turma"]);
                        $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on l.rfProfessor = p.rfProfessor where p.rfProfessor = " .  $_SESSION["login"] . " and t.periodo = " . $periodo);
                        $stmt->execute();
                        echo "<option value='null'></option>";
                      }
                    }
                    else {
                      $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on l.rfProfessor = p.rfProfessor where p.rfProfessor = " .  $_SESSION["login"] . " and t.periodo = " . $periodo);
                      $stmt->execute();
                      echo "<option value='null'></option>";
                    }
                    while($row = $stmt->fetch()) {
                      echo "<option value='" . $row['codTurma'] . "'>" . $row['nomeTurma'] . "</option>";
                    }
                  }
                  catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                  }
                  finally{
                    $pdo=null;
                  }
                }
              ?>
            </select>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="disciplina" class="form-label">Disciplina:</label>
            <select class="form-select" id="disciplina" name="disciplina" aria-label="Default select example">
              <?php
                if(isset($_POST["periodo"]) && isset($_POST["turma"])) {
                  $periodo = $_POST["periodo"];
                  $turma = $_POST["turma"];
                  include("../conexaoBD.php");
                  try {
                    if(isset($_POST["disciplina"]) && $_POST["disciplina"] != 'null') {
                      $stmt = $pdo->prepare("select l.codDisciplina from LecionaTCC l inner join TurmasTCC t on l.codTurma = t.codTurma where t.periodo = :periodo and l.codDisciplina = :codDisciplina and l.rfProfessor = :login");
                      $stmt->bindParam(":login", $_SESSION["login"]);
                      $stmt->bindParam(":periodo", $_POST["periodo"]);
                      $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
                      $stmt->execute();
                      $rows = $stmt->rowCount();
                      if($rows > 0) {
                        $stmt = $pdo->prepare("select codDisciplina from LecionaTCC where codTurma = :codTurma and codDisciplina = :codDisciplina and rfProfessor = :login");
                        $stmt->bindParam(":login", $_SESSION["login"]);
                        $stmt->bindParam(":codTurma", $turma);
                        $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
                        $stmt->execute();
                        $rows = $stmt->rowCount();
                        if($rows > 0) {
                          $stmt = $pdo->prepare("select codDisciplina, nomeDisciplina from DisciplinasTCC where codDisciplina = :codDisciplina");
                          $stmt->bindParam(":codDisciplina",$_POST["disciplina"]);
                          $stmt->execute();
                          if ($row = $stmt->fetch()) {
                            echo "<option value='".$row["codDisciplina"]."'>".$row["nomeDisciplina"]."</option>";
                          }
                          $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.rfProfessor = :login and l.codTurma = :turma and d.codDisciplina != :codDisciplina");
                          $stmt->bindParam(":login",$_SESSION["login"]);
                          $stmt->bindParam(":turma",$turma);
                          $stmt->bindParam(":codDisciplina",$_POST["disciplina"]);
                          $stmt->execute();
                        }
                        else {
                          unset($_POST["disciplina"]);
                          $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.rfProfessor = :login and l.codTurma = :turma");
                          $stmt->bindParam(":login",$_SESSION["login"]);
                          $stmt->bindParam(":turma",$turma);
                          $stmt->execute();
                        }
                      }
                      else {
                        unset($_POST["disciplina"]);
                      }
                    }
                    else {
                      $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.rfProfessor = :login and l.codTurma = :turma");
                      $stmt->bindParam(":login",$_SESSION["login"]);
                      $stmt->bindParam(":turma",$turma);
                      $stmt->execute();
                    }
                    while($row = $stmt->fetch()) {
                      echo "<option value='" . $row['codDisciplina'] . "'>" . $row['nomeDisciplina'] . "</option>";
                    }
                  }
                  catch(PDOException $e) {
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
          <button type="submit" class="btn btn-primary rounded-pill text-white w-25"><b>Buscar</b></button>
        </div>
      </form>
      <hr>
      <?php
        if($_SERVER["REQUEST_METHOD"] === "POST") {
          if(isset($_POST["periodo"]) && isset($_POST["turma"]) && isset($_POST["disciplina"])) {
            echo "<ul class='nav nav-pills mb-3 justify-content-center' id='pills-tab' role='tablist'>
                    <li class='nav-item' role='presentation'>
                      <button class='nav-link active' id='pills-avaliacao-tab' data-bs-toggle='pill' data-bs-target='#pills-avaliacao' type='button' role='tab' aria-controls='pills-avaliacao' aria-selected='true'><i class='fas fa-file-signature'></i> Avaliações</button>
                    </li>
                    <li class='nav-item' role='presentation'>
                      <button class='nav-link' id='pills-plano-tab' data-bs-toggle='pill' data-bs-target='#pills-plano' type='button' role='tab' aria-controls='pills-plano' aria-selected='false'><i class='fas fa-chalkboard-teacher'></i> Plano de Ensino</button>
                    </li>
                  </ul>";
            echo "<div class='tab-content' id='pills-tabContent'>";
            echo "<div class='tab-pane fade show active' id='pills-avaliacao' role='tabpanel' aria-labelledby='pills-avaliacao-tab'>
                    <h4>Avaliações</h4>
                      <ul class='nav nav-pills mb-3' id='pills-tab' role='tablist'>
                        <li class='nav-item' role='presentation'>
                          <button class='nav-link active' id='pills-sem1-tab' data-bs-toggle='pill' data-bs-target='#pills-sem1' type='button' role='tab' aria-controls='pills-sem1' aria-selected='true'>1º Semestre</button>
                        </li>
                        <li class='nav-item' role='presentation'>
                          <button class='nav-link' id='pills-sem2-tab' data-bs-toggle='pill' data-bs-target='#pills-sem2' type='button' role='tab' aria-controls='pills-sem2' aria-selected='false'>2º Semestre</button>
                        </li>
                      </ul>
                      <div class='tab-content' id='pills-tabContent'>
                        <div class='tab-pane fade show active' id='pills-sem1' role='tabpanel' aria-labelledby='pills-sem1-tab'>";
            try {
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("select a.descricao, a.data, a.peso from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC dta on a.codAtividade = dta.codAtividade where dta.codTurma = :codTurma and dta.codDisciplina = :codDisciplina and a.etapa = 1");
              $stmt->bindParam(":codTurma", $_POST["turma"]);
              $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
              $stmt->execute();
              if($stmt->rowCount() > 0) {
                echo "<div class='table-responsive mt-4 mb-4'>
                        <table id='tableConsulta' class='table table-sm table-striped table-hover'>
                          <thead>
                            <th>Avaliação</th>
                            <th>Data</th>
                            <th>Peso</th>
                          </thead>
                        <tbody>";
                while($row = $stmt->fetch()) {
                  echo "<tr>";
                  echo "<td>" . $row['descricao'] . "</td>";
                  echo "<td>" . date("d/m/Y", strtotime($row['data'])) . "</td>";
                  echo "<td>" . (int)$row['peso'] . "</td>";
                  echo "</tr>";
                }
                echo "</tbody></table></div>";
              }
              else {
                echo "Nenhuma atividade adicionada ainda.";
              }
            }
            catch(PDOException $e) {
              echo 'Error: ' . $e->getMessage();
            }
            finally {
              $pdo = null;
            }
            echo "<div class='mt-4 text-center'>
                    <a href='editAtividades.php?turma=".$_POST["turma"]."&disciplina=".$_POST["disciplina"]."&etapa=1' class='btn btn-primary rounded-pill text-white' role='button'>
                      <b><i class='fas fa-edit'></i> Editar Avaliações</b>
                    </a>
                  </div>";
                  echo "</div>
                        <div class='tab-pane fade' id='pills-sem2' role='tabpanel' aria-labelledby='pills-sem2-tab'>";
            try {
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("select a.descricao, a.data, a.peso from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC dta on a.codAtividade = dta.codAtividade where dta.codTurma = :codTurma and dta.codDisciplina = :codDisciplina and a.etapa = 2");
              $stmt->bindParam(":codTurma", $_POST["turma"]);
              $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
              $stmt->execute();
              if($stmt->rowCount() > 0) {
                echo "<div class='table-responsive mt-4 mb-4'>
                        <table id='tableConsulta' class='table table-sm table-striped table-hover'>
                          <thead>
                            <th>Avaliação</th>
                            <th>Data</th>
                            <th>Peso</th>
                          </thead>
                        <tbody>";
                while($row = $stmt->fetch()) {
                  echo "<tr>";
                  echo "<td>" . $row['descricao'] . "</td>";
                  echo "<td>" . date("d/m/Y", strtotime($row['data'])) . "</td>";
                  echo "<td>" . (int)$row['peso'] . "</td>";
                  echo "</tr>";
                }
                echo "</tbody></table></div>";
              }
              else {
                echo "Nenhuma atividade adicionada ainda.";
              }
            }
            catch(PDOException $e) {
              echo 'Error: ' . $e->getMessage();
            }
            finally {
              $pdo = null;
            }
                  echo "<div class='mt-4 text-center'>
                          <a href='editAtividades.php?turma=".$_POST["turma"]."&disciplina=".$_POST["disciplina"]."&etapa=2' class='btn btn-primary rounded-pill text-white' role='button'>
                            <b><i class='fas fa-edit'></i> Editar Avaliações</b>
                          </a>
                        </div>
                        </div>
                      </div>
                    <hr>
                  </div>";

            //Plano de Ensino
            $ementa = "";
            $objetivosGerais = "";
            $objetivosEspecificos = "";
            $modeloAvaliacao = "";
            $conteudoProgramatico = "";
            $procedimentosDidaticos = "";
            $bibliografiaBasica = "";
            $bibliografiaComplementar = "";
            try {
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("select * from LecionaTCC where codTurma = :codTurma and codDisciplina = :codDisciplina");
              $stmt->bindParam(":codTurma", $_POST["turma"]);
              $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
              $stmt->execute();
              $row = $stmt->fetch();
              $planoEnsino = $row["planoEnsino"] != NULL;
              if($row["planoEnsino"] != NULL) {
                $arquivo = $row["planoEnsino"];
                $arquivo = fopen($arquivo, "r");
                while(!feof($arquivo)) {
                  $linha = fgets($arquivo);
                  if($linha == false) break;
                  if(str_contains($linha,"EMENTA")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"OBJETIVOS GERAIS") && !feof($arquivo)) {
                      $ementa .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"OBJETIVOS GERAIS")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"OBJETIVOS ESPECÍFICOS") && !feof($arquivo)) {
                      $objetivosGerais .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"OBJETIVOS ESPECÍFICOS")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"MODELO DE AVALIAÇÃO") && !feof($arquivo)) {
                      $objetivosEspecificos .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"MODELO DE AVALIAÇÃO")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"CONTEÚDO PROGRAMÁTICO") && !feof($arquivo)) {
                      $modeloAvaliacao .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"CONTEÚDO PROGRAMÁTICO")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"PROCEDIMENTOS DIDÁTICOS") && !feof($arquivo)) {
                      $conteudoProgramatico .= $linha . "<br>";
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"PROCEDIMENTOS DIDÁTICOS")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"BIBLIOGRAFIA BÁSICA") && !feof($arquivo)) {
                      $procedimentosDidaticos .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"BIBLIOGRAFIA BÁSICA")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"BIBLIOGRAFIA COMPLEMENTAR") && !feof($arquivo)) {
                      $bibliografiaBasica .= $linha . "<br>";
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"BIBLIOGRAFIA COMPLEMENTAR")) {
                    $linha = fgets($arquivo);
                    while($linha != null && !feof($arquivo)) {
                      $bibliografiaComplementar .= $linha . "<br>";
                      $linha = fgets($arquivo);
                    }
                  }
                }
                fclose($arquivo);
              }
            }
            catch(PDOException $e) {
              echo 'Error: ' . $e->getMessage();
            }
            finally {
              $pdo = null;
            }
            if($planoEnsino) {
              echo "<div class='tab-pane fade' id='pills-plano' role='tabpanel' aria-labelledby='pillsplano-tab'>
                      <div class='mb-4'>
                        <h6><label for='ementa' class='form-label'>Ementa</label></h6>
                        $ementa
                      </div>
                      <div class='row mb-4'>
                        <div class='col-md-6 col-sm-12'>
                          <h6><label for='objetivosGerais' class='form-label'>Objetivos Gerais</label></h6>
                          $objetivosGerais
                        </div>
                        <div class='col-md-6 col-sm-12'>
                          <h6><label for='objetivosEspecificos' class='form-label'>Objetivos Específicos</label></h6>
                          $objetivosEspecificos
                        </div>
                      </div>
                      <div class='mb-4'>
                        <h6><label for='modeloAvaliacao' class='form-label'>Modelo de Avaliação</label></h6>
                        $modeloAvaliacao
                      </div>
                      <div class='mb-4'>
                        <h6><label for='conteudoProgramatico' class='form-label'>Conteúdo Programático</label></h6>
                        $conteudoProgramatico
                      </div>
                      <div class='mb-4'>
                        <h6><label for='procedimentosDidaticos' class='form-label'>Procedimentos Didáticos</label></h6>
                        $procedimentosDidaticos
                      </div>
                      <div class='row mb-4'>
                        <div class='col-md-6 col-sm-12'>
                          <h6><label for='bibliografiaBasica' class='form-label'>Bibliografia Básica</label></h6>
                          $bibliografiaBasica
                        </div>
                        <div class='col-md-6 col-sm-12'>
                          <h6><label for='bibliografiaComplementar' class='form-label'>Bibliografia Complementar</label></h6>
                          $bibliografiaComplementar
                        </div>
                      </div>";
            }
            else {
              echo "<div class='tab-pane fade' id='pills-plano' role='tabpanel' aria-labelledby='pillsplano-tab'>Plano de Ensino não adicionado ainda.";
            }
            echo   "<div class='mt-4 text-center'>
                      <a href='editPlanoEnsino.php?turma=".$_POST["turma"]."&disciplina=".$_POST["disciplina"]."' class='btn btn-primary rounded-pill text-white' role='button'>
                        <b><i class='fas fa-edit'></i> Editar Plano de Ensino</b>
                      </a>
                    </div><hr>
                  </div>";
            echo "</div>";
            /*$ementa = "";
            $objetivosGerais = "";
            $objetivosEspecificos = "";
            $modeloAvaliacao = "";
            $conteudoProgramatico = "";
            $procedimentosDidaticos = "";
            $bibliografiaBasica = "";
            $bibliografiaComplementar = "";
            try {
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("select * from LecionaTCC where codTurma = :codTurma and codDisciplina = :codDisciplina");
              $stmt->bindParam(":codTurma", $_POST["turma"]);
              $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
              $stmt->execute();
              $row = $stmt->fetch();
              if($row["planoEnsino"] ??= NULL) {
                $arquivo = $row["planoEnsino"];
                $arquivo = fopen($arquivo, "r");
                while(!feof($arquivo)) {
                  $linha = fgets($arquivo);
                  if($linha == false) break;
                  if(str_contains($linha,"EMENTA")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"OBJETIVOS GERAIS") && !feof($arquivo)) {
                      $ementa .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"OBJETIVOS GERAIS")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"OBJETIVOS ESPECÍFICOS") && !feof($arquivo)) {
                      $objetivosGerais .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"OBJETIVOS ESPECÍFICOS")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"MODELO DE AVALIAÇÃO") && !feof($arquivo)) {
                      $objetivosEspecificos .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"MODELO DE AVALIAÇÃO")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"CONTEÚDO PROGRAMÁTICO") && !feof($arquivo)) {
                      $modeloAvaliacao .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"CONTEÚDO PROGRAMÁTICO")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"PROCEDIMENTOS DIDÁTICOS") && !feof($arquivo)) {
                      $conteudoProgramatico .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"PROCEDIMENTOS DIDÁTICOS")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"BIBLIOGRAFIA BÁSICA") && !feof($arquivo)) {
                      $procedimentosDidaticos .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"BIBLIOGRAFIA BÁSICA")) {
                    $linha = fgets($arquivo);
                    while(!str_contains($linha,"BIBLIOGRAFIA COMPLEMENTAR") && !feof($arquivo)) {
                      $bibliografiaBasica .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                  if(str_contains($linha,"BIBLIOGRAFIA COMPLEMENTAR")) {
                    $linha = fgets($arquivo);
                    while($linha != null && !feof($arquivo)) {
                      $bibliografiaComplementar .= $linha;
                      $linha = fgets($arquivo);
                    }
                  }
                }
                fclose($arquivo);
              }
            }
            catch(PDOException $e) {
              echo 'Error: ' . $e->getMessage();
            }
            finally {
              $pdo = null;
            }
            echo "<div class='tab-pane fade' id='pills-plano' role='tabpanel' aria-labelledby='pills-plano-tab'>
                    <h4>Plano de Ensino</h4>
                      <div class='mb-3'>
                        <h6><label for='ementa' class='form-label'>Ementa</label></h6>
                        <textarea class='form-control' id='ementa' name='ementa' rows='4'>".$ementa."</textarea>
                      </div>
                      <div class='row mb-3'>
                        <div class='col-md-6 col-sm-12 mb-3'>
                           <h6><label for='objetivosGerais' class='form-label'>Objetivos Gerais</label></h6>
                          <textarea class='form-control' id='objetivosGerais' name='objetivosGerais' rows='8'>".$objetivosGerais."</textarea>
                        </div>
                        <div class='col-md-6 col-sm-12 mb-3'>
                          <h6><label for='objetivosEspecificos' class='form-label'>Objetivos Específicos</label></h6>
                          <textarea class='form-control' id='objetivosEspecificos' name='objetivosEspecificos' rows='8'>".$objetivosEspecificos."</textarea>
                        </div>
                      </div>
                      <div class='mb-3'>
                        <h6><label for='modeloAvaliacao' class='form-label'>Modelo de Avaliação</label></h6>
                        <textarea class='form-control' id='modeloAvaliacao' name='modeloAvaliacao' rows='2'>".$modeloAvaliacao."</textarea>
                      </div>
                      <div class='mb-3'>
                        <h6><label for='conteudoProgramatico' class='form-label'>Conteúdo Programático</label></h6>
                        <textarea class='form-control' id='conteudoProgramatico' name='conteudoProgramatico' rows='10'>".$conteudoProgramatico."</textarea>
                      </div>
                      <div class='mb-3'>
                        <h6><label for='procedimentosDidaticos' class='form-label'>Procedimentos Didáticos</label></h6>
                        <textarea class='form-control' id='procedimentosDidaticos' name='procedimentosDidaticos' rows='6'>".$procedimentosDidaticos."</textarea>
                      </div>
                      <div class='row mb-3'>
                        <div class='col-md-6 col-sm-12 mb-3'>
                          <h6><label for='bibliografiaBasica' class='form-label'>Bibliografia Básica</label></h6>
                          <textarea class='form-control' id='bibliografiaBasica' name='bibliografiaBasica' rows='4'>".$bibliografiaBasica."</textarea>
                        </div>
                        <div class='col-md-6 col-sm-12 mb-3'>
                          <h6><label for='bibliografiaComplementar' class='form-label'>Bibliografia Complementar</label></h6>
                          <textarea class='form-control' id='bibliografiaComplementar' name='bibliografiaComplementar' rows='4'>".$bibliografiaComplementar."</textarea>
                        </div>
                      </div>
                      <div class='mt-4 text-center'>
                        <button type='submit' class='btn btn-primary rounded-pill text-white'><b><i class='fas fa-edit'></i> Salvar alterações</b></button>
                      </div>
                      </form>
                    <hr>
                  </div>";
            echo "</div>";

            $arquivo = "../planoEnsino/".$_POST["turma"].$_POST["disciplina"].".txt";
            $arquivo = fopen($arquivo, "w");
            fwrite($arquivo, "EMENTA\r\n");
            if(isset($_POST["ementa"])) fwrite($arquivo, $_POST["ementa"]."\r\n");
            fwrite($arquivo, "OBJETIVOS GERAIS\r\n");
            if(isset($_POST["objetivosGerais"])) fwrite($arquivo, $_POST["objetivosGerais"]."\r\n");
            fwrite($arquivo, "OBJETIVOS ESPECÍFICOS\r\n");
            if(isset($_POST["objetivosEspecificos"])) fwrite($arquivo, $_POST["objetivosEspecificos"]."\r\n");
            fwrite($arquivo, "MODELO DE AVALIAÇÃO\r\n");
            if(isset($_POST["modeloAvaliacao"])) fwrite($arquivo, $_POST["modeloAvaliacao"]."\r\n");
            fwrite($arquivo, "CONTEÚDO PROGRAMÁTICO\r\n");
            if(isset($_POST["conteudoProgramatico"])) fwrite($arquivo, $_POST["conteudoProgramatico"]."\r\n");
            fwrite($arquivo, "PROCEDIMENTOS DIDÁTICOS\r\n");
            if(isset($_POST["procedimentosDidaticos"])) fwrite($arquivo, $_POST["procedimentosDidaticos"]."\r\n");
            fwrite($arquivo, "BIBLIOGRAFIA BÁSICA\r\n");
            if(isset($_POST["bibliografiaBasica"])) fwrite($arquivo, $_POST["bibliografiaBasica"]."\r\n");
            fwrite($arquivo, "BIBLIOGRAFIA COMPLEMENTAR\r\n");
            if(isset($_POST["bibliografiaComplementar"])) fwrite($arquivo, $_POST["bibliografiaComplementar"]."\r\n");
            fclose($arquivo);
            $arquivo = "../planoEnsino/".$_POST["turma"].$_POST["disciplina"].".txt";

            try{
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("update LecionaTCC set planoEnsino = :planoEnsino where codTurma = :codTurma and codDisciplina = :codDisciplina");
              $stmt->bindParam(":planoEnsino", $arquivo);
              $stmt->bindParam(":codTurma", $_POST["turma"]);
              $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
              $stmt->execute();
            }
            catch(PDOException $e) {
              echo 'Error: ' . $e->getMessage();
            }
            finally {
              $pdo = null;
            }*/
          }
        }
      ?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>