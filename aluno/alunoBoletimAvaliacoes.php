 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'aluno')
    header('location:../index.php');

  include("../conexaoBD.php");
  try {
    $stmt = $pdo->prepare("select raAluno, nomeAluno from AlunosTCC p where raAluno = " . $_SESSION['login']);
    $stmt->execute();
    $row = $stmt->fetch();
    if(strpos($row['nomeAluno']," ")) {
      $nome = substr($row['nomeAluno'], 0, strpos($row['nomeAluno']," "));
    }
    else
      $nome = $row['nomeAluno'];
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
                <a class="nav-link" aria-current="page" href="aluno.php"><i class="fas fa-home"></i> Início</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoPlanoEnsino.php"><i class="fas fa-chalkboard"></i> Plano de Ensino</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="alunoBoletimAvaliacoes.php"><b><i class="far fa-file-alt"></i> Boletim de Avaliações</b></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoBoletimEscolar.php"><i class="fas fa-file-invoice"></i> Boletim Escolar</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoMateriaLecionada.php"><i class="fas fa-list"></i> Matéria Lecionada</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoFrequencia.php"><i class="fas fa-calendar-check"></i> Frequência</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
              </li>
              <!--li class="nav-item">
                <a class="nav-link" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
              </li-->
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
          <a class="nav-link text-dark" aria-current="page" href="aluno.php"><i class="fas fa-home"></i> Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoPlanoEnsino.php"><i class="fas fa-chalkboard"></i> Plano de Ensino</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="alunoBoletimAvaliacoes.php"><b><i class="far fa-file-alt"></i> Boletim de Avaliações</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoBoletimEscolar.php"><i class="fas fa-file-invoice"></i> Boletim Escolar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoMateriaLecionada.php"><i class="fas fa-list"></i> Matéria Lecionada</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoFrequencia.php"><i class="fas fa-calendar-check"></i> Frequência</a>
        </li>
        <!--li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li-->
      </ul>
    </div>
    <div class="container mt-3">
      <form method="post" id="campos">
        <div class="row mb-3">
          <div class="col-md-4 col-sm-12">
            <label for="periodo" class="form-label">Período: </label>
            <select class="form-select" id="periodo" name="periodo" aria-label="Período" onchange="submit(periodo.value);">
              <?php
                include("../conexaoBD.php");
                if($_SERVER["REQUEST_METHOD"] != "POST") {
                  if(isset($_GET["turma"])) {
                    try {
                      $stmt = $pdo->prepare("select periodo from TurmasTCC where codTurma = :codTurma");
                      $stmt->bindParam(":codTurma", $_GET["turma"]);
                      $stmt->execute();
                      $row = $stmt->fetch();
                      echo "<option value='" . $row["periodo"] . "'>" . $row["periodo"] . "</option>";
                    }
                    catch(PDOException $e) {
                      echo 'Error: ' . $e->getMessage();
                    }
                  }
                }
                try {
                  if(isset($_POST["periodo"]) && $_POST["periodo"] != 'null') {
                    $stmt = $pdo->prepare("select distinct t.periodo from TurmasTCC t inner join AlunoTurmaTCC alt on t.codTurma = alt.codTurma inner join AlunosTCC a on alt.raAluno = a.raAluno where a.raAluno = " . $_SESSION['login'] . " and t.periodo != " . $_POST["periodo"]);
                    $stmt->execute();
                    echo "<option value='" . $_POST["periodo"] . "'>" . $_POST["periodo"] . "</option>";
                  }
                  else {
                    $stmt = $pdo->prepare("select distinct t.periodo from TurmasTCC t inner join AlunoTurmaTCC alt on t.codTurma = alt.codTurma inner join AlunosTCC a on alt.raAluno = a.raAluno where a.raAluno = " . $_SESSION['login'] . " and t.periodo != " . date("Y"));
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
            <label for="periodo" class="form-label">Turma: </label>
            <select class="form-select" id="turma" name="turma" aria-label="Turma" onchange="submit(periodo.value,turma.value);">
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
                      echo "<script>document.getElementById('campos').submit(periodo.value, turma.value);</script>";
                    }
                    catch(PDOException $e) {
                      echo 'Error: ' . $e->getMessage();
                    }
                    finally{
                      $pdo = null;
                    }
                  }
                }
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
                        $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join AlunoTurmaTCC alt on t.codTurma = alt.codTurma inner join AlunosTCC a on alt.raAluno = a.raAluno where a.raAluno = :login and t.periodo = :periodo and t.codTurma != :turma");
                        $stmt->bindParam(":login",$_SESSION["login"]);
                        $stmt->bindParam(":periodo",$periodo);
                        $stmt->bindParam(":turma",$_POST["turma"]);
                        $stmt->execute();
                      }
                      else {
                        unset($_POST["turma"]);
                        $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join AlunoTurmaTCC alt on t.codTurma = alt.codTurma inner join AlunosTCC a on alt.raAluno = a.raAluno where a.raProfessor = " .  $_SESSION["login"] . " and t.periodo = " . $periodo);
                        $stmt->execute();
                        echo "<option value='null'></option>";
                      }
                    }
                    else {
                      $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join AlunoTurmaTCC alt on t.codTurma = alt.codTurma inner join AlunoTurmaTCC a on alt.raAluno = a.raAluno where a.raAluno = " .  $_SESSION["login"] . " and t.periodo = " . $periodo);
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
            <label for="etapa" class="form-label">Etapa: </label>
            <select class="form-select" id="etapa" name="etapa" aria-label="Etapa">
              <?php
                if(isset($_POST["periodo"]) && $_POST["periodo"] != 'null' && isset($_POST["turma"]) && $_POST["turma"]) {
                  echo "<option value='1'>1º Semestre</option>
                        <option value='2'>2º Semestre</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="mt-4 text-center">
          <button type="submit" class="btn btn-primary rounded-pill text-white w-25"><b>Buscar</b></button>
        </div>
        <?php
          if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST["periodo"]) && $_POST["periodo"] != 'null' && isset($_POST["turma"]) && $_POST["turma"] != 'null' && isset($_POST["etapa"])) {
              echo "<script>window.location.replace('boletimAvaliacoes.php?turma=".$_POST["turma"]."&etapa=".$_POST["etapa"]."');</script>";
            }
          }
        ?>
      </form>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>