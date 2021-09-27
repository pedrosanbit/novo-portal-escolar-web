 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'prof')
    header('location:../index.php');
  if(!isset($_GET['turma']) || !isset($_GET['disciplina']))
        header('location:professorPlanejamento.php');
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

    $stmt = $pdo->prepare("select codTurma, nomeTurma from TurmasTCC where codTurma = :codTurma");
    $stmt->bindParam(":codTurma", $_GET["turma"]);
    $stmt->execute();
    $row = $stmt->fetch();
    $nomeTurma = $row['nomeTurma'];

    $stmt = $pdo->prepare("select codDisciplina, nomeDisciplina from DisciplinasTCC where codDisciplina = :codDisciplina");
    $stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
    $stmt->execute();
    $row = $stmt->fetch();
    $nomeDisciplina = $row["nomeDisciplina"];
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
          <a class="navbar-brand" href="professor.php">
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
                <a class="nav-link" href="professorFrequencia.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="professorNotas.php"><i class="fas fa-file-alt"></i> Notas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="professorPlanejamento.php"><b><i class="fas fa-chalkboard"></i> Planejamento</b></a>
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
          <a class="nav-link text-dark" href="professorFrequencia.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorNotas.php"><i class="fas fa-file-alt"></i> Notas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="professorPlanejamento.php"><b><i class="fas fa-chalkboard"></i> Planejamento</b></a>
        </li>
      </ul>
    </div>
    <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="professorPlanejamento.php">Planejamento</a></li>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeTurma . "</li> "; ?>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeDisciplina . "</li> "; ?>
            <li class="breadcrumb-item active" aria-current='page'>Plano de Ensino</li> 
        </ol>
    </nav>
    <?php
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
        $stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
        $stmt->execute();
        $row = $stmt->fetch();
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
                $conteudoProgramatico .= $linha;
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
                $bibliografiaBasica .= $linha;
                $linha = fgets($arquivo);
              }
            }
            if(str_contains($linha,"BIBLIOGRAFIA COMPLEMENTAR")) {
              $linha = fgets($arquivo);
              while($linha != null && !feof($arquivo)) {
                $bibliografiaComplementar .= $linha;
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
    ?>
    <div class="container mt-3">
      <form method="post">
        <div class='mb-3'>
          <h6><label for='ementa' class='form-label'>Ementa</label></h6>
          <textarea class='form-control' id='ementa' name='ementa' rows='4'><?php echo $ementa;?></textarea>
        </div>
        <div class='row mb-3'>
          <div class='col-md-6 col-sm-12 mb-3'>
            <h6><label for='objetivosGerais' class='form-label'>Objetivos Gerais</label></h6>
            <textarea class='form-control' id='objetivosGerais' name='objetivosGerais' rows='8'><?php echo $objetivosGerais;?></textarea>
          </div>
          <div class='col-md-6 col-sm-12 mb-3'>
            <h6><label for='objetivosEspecificos' class='form-label'>Objetivos Específicos</label></h6>
            <textarea class='form-control' id='objetivosEspecificos' name='objetivosEspecificos' rows='8'><?php echo $objetivosEspecificos;?></textarea>
          </div>
        </div>
        <div class='mb-3'>
          <h6><label for='modeloAvaliacao' class='form-label'>Modelo de Avaliação</label></h6>
          <textarea class='form-control' id='modeloAvaliacao' name='modeloAvaliacao' rows='2'><?php echo $modeloAvaliacao;?></textarea>
        </div>
        <div class='mb-3'>
          <h6><label for='conteudoProgramatico' class='form-label'>Conteúdo Programático</label></h6>
          <textarea class='form-control' id='conteudoProgramatico' name='conteudoProgramatico' rows='10'><?php echo $conteudoProgramatico;?></textarea>
        </div>
        <div class='mb-3'>
          <h6><label for='procedimentosDidaticos' class='form-label'>Procedimentos Didáticos</label></h6>
          <textarea class='form-control' id='procedimentosDidaticos' name='procedimentosDidaticos' rows='6'><?php echo $procedimentosDidaticos;?></textarea>
        </div>
        <div class='row mb-3'>
          <div class='col-md-6 col-sm-12 mb-3'>
            <h6><label for='bibliografiaBasica' class='form-label'>Bibliografia Básica</label></h6>
            <textarea class='form-control' id='bibliografiaBasica' name='bibliografiaBasica' rows='4'><?php echo $bibliografiaBasica;?></textarea>
          </div>
          <div class='col-md-6 col-sm-12 mb-3'>
            <h6><label for='bibliografiaComplementar' class='form-label'>Bibliografia Complementar</label></h6>
            <textarea class='form-control' id='bibliografiaComplementar' name='bibliografiaComplementar' rows='4'><?php echo $bibliografiaComplementar;?></textarea>
          </div>
        </div>
        <div class='mt-4 text-center'>
          <button type='submit' class='btn btn-primary rounded-pill text-white'><b><i class='fas fa-edit'></i> Salvar alterações</b></button>
        </div>
      </form>
      <hr>
    </div>
    <?php 
      if($_SERVER["REQUEST_METHOD"] === "POST") {
        $arquivo = "../planoEnsino/".$_GET["turma"].$_GET["disciplina"].".txt";
        $arquivo = fopen($arquivo, "w");
        fwrite($arquivo, "EMENTA\r\n");
        if(isset($_POST["ementa"])) fwrite($arquivo, $_POST["ementa"]."\r\n");
        fwrite($arquivo, "OBJETIVOS GERAIS\r\n");
        if(isset($_POST["objetivosGerais"])) fwrite($arquivo, $_POST["objetivosGerais"]."\r\n");
        fwrite($arquivo, "OBJETIVOS ESPECÍFICOS\r\n");
        if(isset($_POST["objetivosEspecificos"])) fwrite($arquivo, $_POST["objetivosEspecificos"]."\r\n");
        fwrite($arquivo, "MODELO DE AVALIAÇÃO\r\n");
        if(isset($_POST["modeloAvaliacao"])) fwrite($arquivo, $_POST["modeloAvaliacao"]."\r\n");
        fwrite($arquivo, "CONTEÚDO PROGRAMÁTICO\r\n");
        if(isset($_POST["conteudoProgramatico"])) fwrite($arquivo, $_POST["conteudoProgramatico"]."\r\n");
        fwrite($arquivo, "PROCEDIMENTOS DIDÁTICOS\r\n");
        if(isset($_POST["procedimentosDidaticos"])) fwrite($arquivo, $_POST["procedimentosDidaticos"]."\r\n");
        fwrite($arquivo, "BIBLIOGRAFIA BÁSICA\r\n");
        if(isset($_POST["bibliografiaBasica"])) fwrite($arquivo, $_POST["bibliografiaBasica"]."\r\n");
        fwrite($arquivo, "BIBLIOGRAFIA COMPLEMENTAR\r\n");
        if(isset($_POST["bibliografiaComplementar"])) fwrite($arquivo, $_POST["bibliografiaComplementar"]."\r\n");
        fclose($arquivo);
        $arquivo = "../planoEnsino/".$_GET["turma"].$_GET["disciplina"].".txt";

        try{
          include("../conexaoBD.php");
          $stmt = $pdo->prepare("update LecionaTCC set planoEnsino = :planoEnsino where codTurma = :codTurma and codDisciplina = :codDisciplina");
          $stmt->bindParam(":planoEnsino", $arquivo);
          $stmt->bindParam(":codTurma", $_GET["turma"]);
          $stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
          $stmt->execute();
        }
        catch(PDOException $e) {
          echo 'Error: ' . $e->getMessage();
        }
        finally {
          $pdo = null;
        }

        echo "<script>alert('Alterações salvas com sucesso!');</script>";
        echo "<script>window.location.replace('editPlanoEnsino.php?turma=".$_GET["turma"]."&disciplina=".$_GET["disciplina"]."');</script>";
      }
    ?>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>