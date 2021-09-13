 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'aluno')
    header('location:../index.php');
  if(!isset($_GET['turma']))
    header('location:alunoPlanoEnsino.php');

  try {
    include("../conexaoBD.php");
    $stmt = $pdo->prepare("select raAluno, nomeAluno from AlunosTCC p where raAluno = " . $_SESSION['login']);
    $stmt->execute();
    $row = $stmt->fetch();
    if(strpos($row['nomeAluno']," ")) {
      $nome = substr($row['nomeAluno'], 0, strpos($row['nomeAluno']," "));
    }
    else
      $nome = $row['nomeAluno'];
    $nomeCompleto = $row['nomeAluno'];

    $stmt = $pdo->prepare("select codTurma, nomeTurma from TurmasTCC where codTurma = :codTurma");
    $stmt->bindParam(":codTurma", $_GET["turma"]);
    $stmt->execute();
    $row = $stmt->fetch();
    $nomeTurma = $row['nomeTurma'];
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
                <a class="nav-link" href="alunoBoletimAvaliacoes.php"><i class="far fa-file-alt"></i> Boletim de Avaliações</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="alunoBoletimEscolar.php"><b><i class="fas fa-file-invoice"></i> Boletim Escolar</b></a>
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
          <a class="nav-link text-dark" aria-current="page" href="aluno.php"><i class="fas fa-home"></i> Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoPlanoEnsino.php"><i class="fas fa-chalkboard"></i> Plano de Ensino</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoBoletimAvaliacoes.php"><i class="far fa-file-alt"></i> Boletim de Avaliações</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="alunoBoletimEscolar.php"><b><i class="fas fa-file-invoice"></i> Boletim Escolar</b></a>
        </li>
      </ul>
    </div>
    <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="alunoBoletimEscolar.php">Boletim Escolar</a></li>
        <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeTurma . "</li> "; ?>
      </ol>
    </nav>
    <div class="container mt-3">
      <?php
        include("../conexaoBD.php");
        try {
          $stmt = $pdo->prepare("select periodo from TurmasTCC where codTurma = :codTurma");
          $stmt->bindParam(":codTurma", $_GET["turma"]);
          $stmt->execute();
          if($row = $stmt->fetch())
            echo "<h4>Boletim Escolar " . $row["periodo"] ."</h4>";
        }
        catch(PDOException $e) {
          echo 'Error: ' . $e->getMessage();
        }
        finally {
          $pdo = null;
        }
      ?>
      <div class="table-responsive mt-4">
        <table id='tableConsulta' class='table table-sm table-striped table-hover'>
          <tbody>
            <tr>
              <th colspan="2">
                <?php 
                  include("../conexaoBD.php");
                  try {
                    $stmt = $pdo->prepare("select c.nomeCurso from CursosTCC c inner join TurmasTCC t on c.codCurso = t.curso where t.codTurma = :codTurma");
                    $stmt->bindParam(":codTurma", $_GET["turma"]);
                    $stmt->execute();
                    if($row = $stmt->fetch())
                      echo $row["nomeCurso"];
                  }
                  catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                  }
                  finally {
                    $pdo = null;
                  }
                ?>     
              </th>
            </tr>
            <tr>
              <td><b>RA:</b> <?php echo $_SESSION["login"]; ?></td>
              <td><b>Nome:</b> <?php echo $nomeCompleto; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
        include("geraBoletimEscolar.php");
      ?>
      <b>Obs.:</b> Os dados estão sujeitos à confirmação, até o término do ano letivo.
      <div class="mt-3 mb-4">
        <b>Legenda:</b><br>
        <div class="border">
          <div class="d-flex flex-row flex-wrap">
            <div class="p-2">MS - Média Semestral<br>MT - Média da Turma</div>
            <div class="p-2">AD - Aulas Dadas<br>F - Faltas no Semestre</div>
            <div class="p-2">%F - Porcentagem de Faltas Semestrais<br>MA - Média Anual</div>
            <div class="p-2">Rec. - Recuperação Final<br>MF - Média Anual Final após Recuperação</div>
          </div>
        </div>
      </div>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>