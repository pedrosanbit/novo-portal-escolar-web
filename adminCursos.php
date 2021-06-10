<?php
  session_start();
  if(!isset($_SESSION['login']))
      header('location:index.php');
  else if($_SESSION['tipo'] != 'admin')
    header('location:index.php');
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
        <a class="nav-link active text-primary" id="nav-active" href="adminCursos.php"><b><i class="fas fa-graduation-cap"></i> Cursos</b></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
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
          <div class="col-md-5">
            <label for="codCurso" class="form-label">Código do Curso:</label>
            <input class="form-control" type="text" id="codCurso" name="codCurso" maxlength="6">
          </div>
          <div class="col-md-5">
            <label for="nomeCurso" class="form-label">Nome do Curso:</label>
            <input class="form-control" type="text" id="nomeCurso" name="nomeCurso">
          </div>
          <div class="col-md-2">
            <label for="cursoProfessor" class="form-label">Professor:</label>
            <select class="form-select" id="cursoProfessor" name="cursoProfessor" aria-label='Default select example'>
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
          <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Consultar Cursos</b></button>
        </div>
        <?php include("consultaCurso.php"); ?>
        <hr>
      </form>
      <a href="CadastroCurso.php" class="btn btn-primary btn-lg rounded-pill text-white" role="button">
        <i class="fas fa-user-plus"></i> Cadastrar Cursos
      </a>
    </div>
    <script src="javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>
