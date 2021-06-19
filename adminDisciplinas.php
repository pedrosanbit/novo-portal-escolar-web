<?php
  session_start();
  if(!isset($_SESSION['login']))
      header('location:index.php');
  else if($_SESSION['tipo'] != 'admin')
    header('location:index.php');
  if($_SERVER["REQUEST_METHOD"] !== "POST") {
    if(isset($_GET['msg'])) {
      $msg = $_GET['msg'];
      if($msg == 1) {
        echo "<script type='text/javascript'>
            alert('Disciplina excluída.');
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
    <link rel="icon" href="logoUnicampAzul.png">
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"-->
    <link rel="stylesheet" href="custom.css">
    <style type="text/css">
      th, td {
        padding-left: 1rem;
      }
    </style>
    <script src="https://kit.fontawesome.com/ebb5206ba7.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-primary" id="navbar">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="logoUnicamp.png" width="32" class="d-inline-block align-text-top">
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
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link text-dark" aria-current="page" href="admin.php"><i class="fas fa-home"></i> Início</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active text-primary" id="nav-active" href="adminDisciplinas.php"><b><i class="fas fa-book"></i> Disciplinas</b></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="adminProfessores.php"><i class="fas fa-chalkboard-teacher"></i> Professores</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
      </li>
    </ul>
    <div class="container mt-3">
      <form method="post">
        <div class="row">
          <div class="col-md-2">
            <label for="codDisciplina" class="form-label">Código da Disciplina:</label>
            <input class="form-control" type="text" id="codDisciplina" name="codDisciplina" maxlength="6">
          </div>
          <div class="col-md-6">
            <label for="nomeDisciplina" class="form-label">Nome da Disciplina:</label>
            <input class="form-control" type="text" id="nomeDisciplina" name="nomeDisciplina">
          </div>
          <div class="col-md-2">
            <label for="turmaDisciplina" class="form-label">Turma:</label>
            <select class='form-select' id='turmaDisciplina' name='turmaDisciplina' aria-label='Default select example'>
              <?php include("selectTurmas.php"); ?>
            </select>
          </div>
          <div class="col-md-2">
            <label for="professorDisciplina" class="form-label">Professor:</label>
            <select class="form-select" id="professorDisciplina" name="professorDisciplina" aria-label='Default select example'>
              <?php
                include("conexaoBD.php");
                try{
                  $stmt= $pdo->prepare("select * from ProfessoresTCC");
                  $stmt->execute();
                        
                  echo "<option value='null'></option>";
                  while($row= $stmt->fetch()){
                    echo "<option value='". $row["rfProfessor"] ."'>".$row["nomeProfessor"]."</option>";
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
          <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Consultar Disciplinas</b></button>
        </div>
        </form>
        <?php include("consultaDisciplina.php"); ?>
        <hr>
      <a href="CadastroDisciplina.php" class="btn btn-primary btn-lg rounded-pill text-white" role="button">
        <i class="fas fa-plus-square"></i> Cadastrar Disciplinas
      </a>
    </div>
    <script src="javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>
