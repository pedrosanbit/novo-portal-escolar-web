<?php
  session_start();
  if(!isset($_SESSION['login']))
      header('location:../index.php');
  else if($_SESSION['tipo'] != 'admin')
    header('location:../index.php');
  
  if($_SERVER["REQUEST_METHOD"] !== "POST") {
    if(isset($_GET['msg'])) {
      $msg = $_GET['msg'];
      if($msg == 1) {
        echo "<script type='text/javascript'>
            alert('Professor excluído.');
          </script>";
        $msg = 0;
      }
    }
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

      @media (max-width: 680px) {
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
                <a class="nav-link" aria-current="page" href="admin.php"><i class="fas fa-home"></i> Início</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="adminProfessores.php"><b><i class="fas fa-chalkboard-teacher"></i> Professores</b></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
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
          <a class="nav-link text-dark" aria-current="page" href="admin.php"><i class="fas fa-home"></i> Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="adminProfessores.php"><b><i class="fas fa-chalkboard-teacher"></i> Professores</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
        </li>
      </ul>
    </div>
    <div class="container mt-3">
      <form method="post">
        <div class="row">
          <div class="col-md-2">
            <label for="rfProfessor" class="form-label">RF:</label>
            <input class="form-control" type="text" id="rfProfessor" name="rfProfessor" maxlength="6">
          </div>
          <div class="col-md-6">
            <label for="nomeProfessor" class="form-label">Nome:</label>
            <input class="form-control" type="text" id="nomeProfessor" name="nomeProfessor">
          </div>
          <div class="col-md-2">
            <label for="turmaProfessor" class="form-label">Turma:</label>
            <select class='form-select' id='turmaProfessor' name='turmaProfessor' aria-label='Default select example'>
              <?php include("../selectTurmas.php"); ?>
            </select>
          </div>
          <div class="col-md-2">
            <label for="disciplinaProfessor" class="form-label">Disciplina:</label>
            <select class="form-select" id="disciplinaProfessor" name="disciplinaProfessor" aria-label='Default select example'>
              <?php
                include("../conexaoBD.php");
                try{
                  $stmt= $pdo->prepare("select * from DisciplinasTCC");
                  $stmt->execute();
                        
                  echo "<option value='null'></option>";
                  while($row= $stmt->fetch()){
                    echo "<option value='". $row["codDisciplina"] ."'>".$row["nomeDisciplina"]."</option>";
                  }
                  echo "</select>";       
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
        </div>
        <div class="mt-4 text-center">
          <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Consultar Professores</b></button>
        </div>
      </form>
        <?php include("consultaProfessor.php"); ?>
        <hr>
      <a href="CadastroProfessor.php" class="btn btn-primary btn-lg rounded-pill text-white" role="button">
        <i class="fas fa-user-plus"></i> Cadastrar Professores
      </a>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>
