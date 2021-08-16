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
                <a class="nav-link active" aria-current="page" href="aluno.php"><b><i class="fas fa-home"></i> Início</b></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoPlanoEnsino.php"><i class="fas fa-chalkboard"></i> Plano de Ensino</a>
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
          <a class="nav-link active text-primary" id="nav-active" aria-current="page" href="aluno.php"><b><i class="fas fa-home"></i> Início</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoPlanoEnsino.php"><i class="fas fa-chalkboard"></i> Plano de Ensino</a>
        </li>
        <!--li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li-->
      </ul>
    </div>

    <div class="container mt-3">
      <div class="row mb-3">
        <div class="col-9">
          <?php echo "<h4>Bem-vindo(a), " . $nome ."!</h4>" ?>
        </div>
        <div class="col-3 text-end" id="btn-logout">
          <a href="../logout.php" class="btn btn-primary rounded-pill text-white" role="button">
            <b><i class="fas fa-sign-out-alt"></i> Logout</b>
          </a>
        </div>
      </div>
      <div class="mb-3">
        Período: <?php echo date("Y");?>
      </div>
      <?php
        include("../conexaoBD.php");
        try {
          $stmt = $pdo->prepare("select distinct t.codTurma, t.nomeTurma from TurmasTCC t inner join AlunoTurmaTCC alt on t.codTurma = alt.codTurma inner join AlunosTCC a on alt.raAluno = a.raAluno where a.raAluno = " .  $_SESSION["login"] . " and t.periodo = " . date("Y"));
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
                              <li><a style='text-decoration: none;' href='planoEnsino.php?turma=".$row["codTurma"]."'><i class='fas fa-chalkboard'></i> Plano de Ensino</li></a>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>";
          }
        }
        catch(PDOException $e) {
          echo 'Error: ' . $e->getMessage();
        }
        finally {
          $pdo = null;
        }
      ?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>