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
                <a class="nav-link active" href="professorFrequencia.php"><b><i class="fas fa-calendar-alt"></i> Frequência</b></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="professorNotas.php"><i class="fas fa-file-alt"></i> Notas</a>
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
          <a class="nav-link text-dark" aria-current="page" href="professor.php"><i class="fas fa-home"></i> Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="professorFrequencia.php"><b><i class="fas fa-calendar-alt"></i> Frequência</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorNotas.php"><i class="fas fa-file-alt"></i> Notas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorPlanejamento.php"><i class="fas fa-chalkboard"></i> Planejamento</a>
        </li>
      </ul>
    </div>

    <div class="container mt-3">
      <form method="post" id="campos">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <label for="periodo" class="form-label">Período:</label>
            <select class="form-select" id="periodo" name="periodo" onchange="submit(periodo.value)">
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
                  while($row= $stmt->fetch()) {
                    echo "<option value='". $row["periodo"] ."'>".$row["periodo"]."</option>";
                  }
                }
                catch(PDOException $e) {
                  echo 'Error: ' . $e->getMessage();
                }
                finally {
                  $pdo=null;
                }
              ?>
            </select>
          </div>
          <div class="col-md-6 col-sm-12">
            <label for="turma" class="form-label">Turma:</label>
            <select class="form-select" id="turma" name="turma" onchange="submit(periodo.value, turma.value)">
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
                    finally {
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
        </div>
        <div class="row mt-3">
          <div class="col-md-6 col-sm-12">
            <label for="disciplina" class="form-label">Disciplina:</label>
            <select class="form-select" id="disciplina" name="disciplina">
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
          <div class="col-md-6 col-sm-12">
            <label for="data" class="form-label">Data:</label>
            <?php echo "<input class='form-control' type='date' name='data' value='".date("Y-m-d")."'>"?>
          </div>
        </div>
        <div class="mt-4 text-center">
              <button type="submit" class="btn btn-primary rounded-pill text-white w-25"><b>Buscar</b></button>
          </div>
      </form>
      <hr>
      <?php
        if($_SERVER["REQUEST_METHOD"] === "POST") {
          if(isset($_POST["turma"]) && isset($_POST["disciplina"]) && isset($_POST["data"])) {
            echo "<script>window.location.replace('editFrequencia.php?turma=".$_POST["turma"]."&disc=".$_POST["disciplina"]."&data=".$_POST["data"]."')</script>";
          }
          /*if(isset($_POST["periodo"]) && isset($_POST["turma"]) && isset($_POST["disciplina"])) {
            try {
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on alt.codTurma = t.codTurma where t.codTurma = :codTurma");
              $stmt->bindParam(":codTurma", $_POST["turma"]);
              $stmt->execute();
              echo "<div class='table-responsive mt-4 mb-4'>
                  <table id='tableFrequencia' class='table table-sm table-striped table-hover'>
                    <thead>
                      <tr>
                        <th style='text-align: center; vertical-align: middle;' rowspan='3' class='border-bottom border-dark sbeb'>Código</th>
                            <th style='text-align: center; vertical-align: middle;' rowspan='3' class='border-bottom border-dark sbeb'>Nome</th>
                            <th style='text-align: center; vertical-align: middle;'><input class='form-control' type='date' name='data' value='".date("Y-m-d")."'></th>
                          </tr>
                          <tr>
                            <th style='text-align: center; vertical-align: middle;'><button type='submit' class='btn btn-primary rounded-pill text-white w-50'><b>Processar</b></button></th>
                          </tr>
                          <tr>
                            <th style='text-align: center; vertical-align: middle;'>F1</th>
                          </tr>
                    </thead>
                    <tbody>";
              while($row = $stmt->fetch()) {
                echo "<tr>";
                      echo "<td style='text-align: center; vertical-align: middle;'>" . $row['raAluno'] . "</td>";
                      echo "<td style='text-align: center; vertical-align: middle;'>" . $row['nomeAluno'] . "</td>";
                      echo "<td style='text-align: center; vertical-align: middle;'>" . "<input type='checkbox'>" . "</td>";
                      echo "</tr>";
              }
              echo "</tbody></table></div>";
            }
            catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                finally {
                    $pdo = null;
                }
          }*/
        }
      ?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>