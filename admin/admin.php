 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'admin')
    header('location:../index.php');
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
                <a class="nav-link active" aria-current="page" href="admin.php"><b><i class="fas fa-home"></i> Início</b></a>
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
                <a class="nav-link" href="adminProfessores.php"><i class="fas fa-chalkboard-teacher"></i> Professores</a>
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
          <a class="nav-link active text-primary" id="nav-active" aria-current="page" href="admin.php"><b><i class="fas fa-home"></i> Início</b></a>
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
          <a class="nav-link text-dark" href="adminProfessores.php"><i class="fas fa-chalkboard-teacher"></i> Professores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
        </li>
      </ul>
    </div>
    <div class="container mt-3">
      <h1> Bem vindo ao Portal!</h1>
      Ir para:
      <br>
      <a href="adminCursos.php" style="text-decoration: none;">→ <i class="fas fa-graduation-cap"></i> Cursos</a>
      <br><br>
      <a href="adminDisciplinas.php" style="text-decoration: none;">→ <i class="fas fa-book"></i> Disciplinas</a>
      <br><br>
      <a href="adminTurmas.php" style="text-decoration: none;">→ <i class="fas fa-users"></i> Turmas</a>
      <br><br>
      <a href="adminProfessores.php" style="text-decoration: none;">→ <i class="fas fa-chalkboard-teacher"></i> Professores</a>
      <br><br>
      <a href="adminAlunos.php" style="text-decoration: none;">→ <i class="fas fa-user"></i> Alunos</a>
      <br><br><br>
      <a href="../logout.php" class="btn btn-primary rounded-pill text-white" role="button">
        <b><i class="fas fa-sign-out-alt"></i> Logout</b>
      </a>
    </div>

    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>