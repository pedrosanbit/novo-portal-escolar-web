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
                <a class="nav-link active" href="alunoPlanoEnsino.php"><b><i class="fas fa-chalkboard"></i> Plano de Ensino</b></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoBoletimAvaliacoes.php"><i class="far fa-file-alt"></i> Boletim de Avaliações</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="alunoBoletimEscolar.php"><i class="fas fa-file-invoice"></i> Boletim Escolar</a>
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
          <a class="nav-link active text-primary" id="nav-active" href="alunoPlanoEnsino.php"><b><i class="fas fa-chalkboard"></i> Plano de Ensino</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoBoletimAvaliacoes.php"><i class="far fa-file-alt"></i> Boletim de Avaliações</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="alunoBoletimEscolar.php"><i class="fas fa-file-invoice"></i> Boletim Escolar</a>
        </li>
        <!--li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li-->
      </ul>
    </div>
    <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="alunoPlanoEnsino.php">Plano de Ensino</a></li>
        <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeTurma . "</li> "; ?>
      </ol>
    </nav>
    <div class="container mt-3">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <form method="post" id="disc">
            <label for="disciplina" class="form-label">Disciplina: </label>
            <select class="form-select" id="disciplina" name="disciplina" aria-label="Turma" onchange="submit(disciplina.value);">
              <?php
                try {
                  include("../conexaoBD.php");
                  if(isset($_POST["disciplina"])) {
                    $stmt = $pdo->prepare("select codDisciplina, nomeDisciplina from DisciplinasTCC where codDisciplina = :codDisciplina");
                    $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                      echo "<option value='".$row["codDisciplina"]."'>".$row["nomeDisciplina"]."</option>";
                    }
                    $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.codTurma = :codTurma and l.codDisciplina != :codDisciplina");
                    $stmt->bindParam(":codTurma", $_GET["turma"]);
                    $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                      echo "<option value='".$row["codDisciplina"]."'>".$row["nomeDisciplina"]."</option>";
                    }
                  }
                  else {
                    $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina where l.codTurma = :codTurma");
                    $stmt->bindParam(":codTurma", $_GET["turma"]);
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                       echo "<option value='".$row["codDisciplina"]."'>".$row["nomeDisciplina"]."</option>";
                    }
                  }
                  if($_SERVER["REQUEST_METHOD"] != "POST") {
                    echo "<script>submit(disciplina.value);</script>";
                  }
                  else {

                  }
                }
                catch(PDOException $e) {
                  echo 'Error: ' . $e->getMessage();
                }
                finally {
                  $pdo = null;
                }
              ?>
            </select>
          </form>
        </div>
      </div>
      <?php
        if($_SERVER["REQUEST_METHOD"] != "POST") {
          echo "<script>document.getElementById('disc').submit(disciplina.value);</script>";
        }
        else {
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
              $stmt->bindParam(":codTurma", $_GET["turma"]);
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
              echo "<div class='mt-4'>
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
                      </div></div>";
            }
            else {
              echo "<div class='mt-5'>Plano de Ensino indisponível.</div>";
            }
        }
      ?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>