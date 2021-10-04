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
                <a class="nav-link" href="#"><i class="fas fa-search"></i> Consultas</a>
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
          <a class="nav-link text-dark" href="#"><i class="fas fa-search"></i> Consultas</a>
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
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="periodo" class="form-label">Período:</label>
            					<select class="form-select" id="periodo" name="periodo" aria-label="Default select example">

            					</select>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="unidade" class="form-label">Unidade:</label>
            					<select class="form-select" id="unidade" name="unidade" aria-label="Default select example">

            					</select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="turma" class="form-label">Turma:</label>
            					<select class="form-select" id="turma" name="turma" aria-label="Default select example">

            					</select>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="disciplina" class="form-label">Disciplina</label>
            					<select class="form-select" id="disciplina" name="disciplina" aria-label="Default select example">

            					</select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <label for="dataIni" class="form-label">Data inicial:</label>
            					<input class="form-control" type="date" name="dataIni[]">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="dataFin" class="form-label">Data final:</label>
            					<input class="form-control" type="date" name="dataFin[]">
                            </div>
                            <div class="col-md-4 col-sm-12">
                            	<label for="btn" class="form-label"></label>
                                <button type="submit" class="btn btn-primary rounded-pill text-white w-100"><b>Buscar</b></button>
                            </div>
                        </div>
                        <br>
                        <hr>
                    </form>
                    <br>
        <div class="table-responsive mt-4">
        <table id='tableFrequencia' class='table table-sm table-striped table-hover'>
          <thead>
          	<tr>
          		<th style="text-align: center; vertical-align: middle;" rowspan="3">Código</th>
          		<th style="text-align: center; vertical-align: middle;" rowspan="3">Nome</th>
          		<th style="text-align: center; vertical-align: middle;" rowspan="3">Número</th>
          		<th style="text-align: center; vertical-align: middle;"><input class="form-control" type="date" name="data[]"></tr></th>
          	</tr>
          	<tr>
          		<th style="text-align: center; vertical-align: middle;"><input type="checkbox"> Processar</th>
          	</tr>
          	<tr>
          		<th style="text-align: center; vertical-align: middle;">F1</th>
          	</tr>
          </thead>
          <tbody>
          	<tr>
          		<td style="text-align: center; vertical-align: middle;">19116</td>
          		<td style="text-align: center; vertical-align: middle;">Ana Lys Rodrigues Barbosa Dias</td>
          		<td style="text-align: center; vertical-align: middle;">1</td>
          		<td style="text-align: center; vertical-align: middle;"><input type="checkbox"></td>
          	</tr>
          	<tr>
          		<td style="text-align: center; vertical-align: middle;">19137</td>
          		<td style="text-align: center; vertical-align: middle;">Mateus Henrique Beltran</td>
          		<td style="text-align: center; vertical-align: middle;">89</td>
          		<td style="text-align: center; vertical-align: middle;"><input type="checkbox"></td>
          	</tr>
          </tbody>
        </table>
      	</div>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>