 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'prof')
    header('location:../index.php');
  if(!isset($_GET['turma']) || !isset($_GET['disc']) || !isset($_GET["ativ"]))
        header('location:professorNotas.php');
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
    $stmt->bindParam(":codDisciplina", $_GET["disc"]);
    $stmt->execute();
    $row = $stmt->fetch();
    $nomeDisciplina = $row["nomeDisciplina"];

    $stmt = $pdo->prepare("select codAtividade, descricao from AtividadesTCC where codAtividade = :codAtividade");
    $stmt->bindParam(":codAtividade", $_GET["ativ"]);
    $stmt->execute();
    $row = $stmt->fetch();
    $nomeAtividade = $row["descricao"];

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
                <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> Frequência</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="professorNotas.php"><b><i class="fas fa-file-alt"></i> Notas</b></a>
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
          <a class="nav-link text-dark" href="#"><i class="fas fa-calendar-alt"></i> Frequência</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="professorNotas.php"><b><i class="fas fa-file-alt"></i> Notas</b></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="professorPlanejamento.php"><i class="fas fa-chalkboard"></i> Planejamento</a>
        </li>
      </ul>
    </div>
    <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="professorNotas.php">Notas</a></li>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeTurma . "</li> "; ?>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeDisciplina . "</li> "; ?>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>Avaliação: ". $nomeAtividade ."</li> "; ?>  
        </ol>
    </nav>
    <div class="container mt-3">
      <form method='post'>
        <div class="table-responsive mt-4 mb-4">
          <table id='tableAv' class='table table-sm table-striped table-hover'>
            <thead>
              <th>RA</th>
              <th>Nome</th>
              <th>Nota</th>
            </thead>
            <tbody>
              <?php
                try {
                  include("../conexaoBD.php");
                  $stmt = $pdo->prepare("select etapa, rec from AtividadesTCC where codAtividade = :codAtividade");
                  $stmt->bindParam(":codAtividade", $_GET["ativ"]);
                  $stmt->execute();
                  $row = $stmt->fetch();
                  if($row["rec"] == 1) {
                    $stmt2 = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno where alt.codTurma = :codTurma");
                    $stmt2->bindParam(":codTurma", $_GET["turma"]);
                    $stmt2->execute();
                    while($row2 = $stmt2->fetch()) {
                      $stmt3 = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 1 and a.rec = 0");
                      $stmt3->bindParam(":raAluno", $row2["raAluno"]);
                      $stmt3->bindParam(":codTurma", $_GET["turma"]);
                      $stmt3->bindParam(":codDisciplina", $_GET["disc"]);
                      $stmt3->execute();
                      $nota = null;
                      $pesos = 0;
                      while($row3 = $stmt3->fetch()) {
                        if($row3["nota"] != null) {
                          $nota += $row3["nota"] * $row3["peso"];
                          $pesos += $row3["peso"];
                        }
                      }
                      if($pesos != 0) $nota = $nota/$pesos;
                      if($row["etapa"] == 2) {
                        $stmt4 = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 1 and a.rec = 1");
                        $stmt4->bindParam(":raAluno", $row2["raAluno"]);
                        $stmt4->bindParam(":codTurma", $_GET["turma"]);
                        $stmt4->bindParam(":codDisciplina", $_GET["disc"]);
                        $stmt4->execute();
                        $rows = $stmt4->rowCount();
                        if($rows > 0) {
                          $row4 = $stmt4->fetch();
                          if($row4["nota"] != null) $nota = ($nota + $row4["nota"])/2;
                        }
                        $stmt4 = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 2 and a.rec = 0");
                        $stmt4->bindParam(":raAluno", $row2["raAluno"]);
                        $stmt4->bindParam(":codTurma", $_GET["turma"]);
                        $stmt4->bindParam(":codDisciplina", $_GET["disc"]);
                        $stmt4->execute();
                        $nota2 = null;
                        $pesos2 = 0;
                        while($row4 = $stmt4->fetch()) {
                          if($row4["nota"] != null) {
                            $nota2 += $row4["nota"] * $row4["peso"];
                            $pesos2 += $row4["peso"];
                          }
                        }
                        if($pesos2 != 0) $nota2 = $nota2/$pesos2;
                        if($nota2 != null) $nota = ($nota + $nota2)/2;
                      }
                      //echo "<script>alert('".$nota."')</script>";
                      if($nota != null && $nota < 6) {
                        $stmt4 = $pdo->prepare("select a.raAluno, a.nomeAluno, atda.nota from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join AlunoTurmaDisciplinaAtividadeTCC atda on alt.raAluno = atda.raAluno where a.raAluno = :ra and atda.codAtividade = :ativ");
                        $stmt4->bindParam(":ra", $row2["raAluno"]);
                        $stmt4->bindParam(":ativ", $_GET["ativ"]);
                        $stmt4->execute();
                        $rows = $stmt4->rowCount();
                        if($rows > 0) {
                          $row4 = $stmt4->fetch();
                          echo "<tr>
                              <td>
                                ".$row2['raAluno']."
                              </td>
                              <td>
                                ".$row2['nomeAluno']."
                              </td>
                              <td>
                                <input class='form-control' type='number' min='0' max='10' step='0.1' name='".$row2["raAluno"]."' value='".$row4["nota"]."'>
                              </td>
                            </tr>";
                        }
                        else {
                          echo "<tr>
                              <td>
                                ".$row2['raAluno']."
                              </td>
                              <td>
                                ".$row2['nomeAluno']."
                              </td>
                              <td>
                                <input class='form-control' type='number' min='0' max='10' step='0.1' name='".$row2["raAluno"]."'>
                              </td>
                            </tr>";
                        }
                      }
                    }
                  }
                  else {
                    $stmt = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno where alt.codTurma = :codTurma");
                    $stmt->bindParam(":codTurma", $_GET["turma"]);
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                      $stmt2 = $pdo->prepare("select a.raAluno, a.nomeAluno, atda.nota from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join AlunoTurmaDisciplinaAtividadeTCC atda on alt.raAluno = atda.raAluno where a.raAluno = :ra and atda.codAtividade = :ativ");
                      $stmt2->bindParam(":ra", $row["raAluno"]);
                      $stmt2->bindParam(":ativ", $_GET["ativ"]);
                      $stmt2->execute();
                      $rows = $stmt2->rowCount();
                      if($rows > 0) {
                        $row2 = $stmt2->fetch();
                        echo "<tr>
                                <td>
                                  ".$row2['raAluno']."
                                </td>
                                <td>
                                  ".$row2['nomeAluno']."
                                </td>
                                <td>
                                  <input class='form-control' type='number' min='0' max='10' step='0.1' name='".$row2["raAluno"]."' value='".$row2["nota"]."'>
                                </td>
                              </tr>";
                      }
                      else {
                        echo "<tr>
                                <td>
                                  ".$row['raAluno']."
                                </td>
                                <td>
                                  ".$row['nomeAluno']."
                                </td>
                                <td>
                                  <input class='form-control' type='number' min='0' max='10' step='0.1' name='".$row["raAluno"]."'>
                                </td>
                              </tr>";
                      }
                    }
                  }
                  /*$stmt = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno where alt.codTurma = :codTurma");
                  $stmt->bindParam(":codTurma", $_GET["turma"]);
                  $stmt->execute();
                  while($row = $stmt->fetch()) {
                    $stmt2 = $pdo->prepare("select a.raAluno, a.nomeAluno, atda.nota from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join AlunoTurmaDisciplinaAtividadeTCC atda on alt.raAluno = atda.raAluno where a.raAluno = :ra and atda.codAtividade = :ativ");
                    $stmt2->bindParam(":ra", $row["raAluno"]);
                    $stmt2->bindParam(":ativ", $_GET["ativ"]);
                    $stmt2->execute();
                    $rows = $stmt2->rowCount();
                    if($rows > 0) {
                      $row2 = $stmt2->fetch();
                      echo "<tr>
                              <td>
                                ".$row2['raAluno']."
                              </td>
                              <td>
                                ".$row2['nomeAluno']."
                              </td>
                              <td>
                                <input class='form-control' type='number' min='0' max='10' step='0.1' name='".$row2["raAluno"]."' value='".$row2["nota"]."'>
                              </td>
                            </tr>";
                    }
                    else {
                      echo "<tr>
                              <td>
                                ".$row['raAluno']."
                              </td>
                              <td>
                                ".$row['nomeAluno']."
                              </td>
                              <td>
                                <input class='form-control' type='number' min='0' max='10' step='0.1' name='".$row["raAluno"]."'>
                              </td>
                            </tr>";
                    }
                  }*/
                }
                catch(PDOException $e) {
                  echo 'Error: ' . $e->getMessage();
                }
                finally {
                  $pdo = null;
                }
              ?>
            </tbody>
          </table>
        </div>
        <div class='mt-4 text-center'>
          <button type='submit' class='btn btn-primary rounded-pill text-white'><b><i class='fas fa-save'></i> Salvar Notas</b></button>
        </div>
        <?php
          if($_SERVER["REQUEST_METHOD"] === "POST") {
            $msg = 1;
            try {
              include("../conexaoBD.php");
              $stmt = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno where alt.codTurma = :codTurma");
              $stmt->bindParam(":codTurma", $_GET["turma"]);
              $stmt->execute();
              while($row = $stmt->fetch()) {
                $stmt2 = $pdo->prepare("select a.raAluno, a.nomeAluno, atda.nota from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join AlunoTurmaDisciplinaAtividadeTCC atda on alt.raAluno = atda.raAluno where a.raAluno = :ra and atda.codAtividade = :ativ");
                $stmt2->bindParam(":ra", $row["raAluno"]);
                $stmt2->bindParam(":ativ", $_GET["ativ"]);
                $stmt2->execute();
                $rows = $stmt2->rowCount();
                if($rows > 0) {
                  if(isset($_POST[$row["raAluno"]])) {
                    if($_POST[$row["raAluno"]] == "") {
                      $stmt3 = $pdo->prepare("update AlunoTurmaDisciplinaAtividadeTCC set nota = null where raAluno = :raAluno and codAtividade = :codAtividade");
                    }
                    else {
                      $stmt3 = $pdo->prepare("update AlunoTurmaDisciplinaAtividadeTCC set nota = :nota where raAluno = :raAluno and codAtividade = :codAtividade");
                      $stmt3->bindParam(":nota", $_POST[$row["raAluno"]]);
                    }
                    $stmt3->bindParam(":raAluno", $row["raAluno"]);
                    $stmt3->bindParam(":codAtividade", $_GET["ativ"]);
                    $stmt3->execute();
                  }
                  $msg = 0;
                }
                else {
                  if(isset($_POST[$row["raAluno"]])) {
                    if($_POST[$row["raAluno"]] == "") {
                      $stmt3 = $pdo->prepare("insert into AlunoTurmaDisciplinaAtividadeTCC (raAluno, codTurma, codDisciplina, codAtividade, nota) values (:raAluno, :codTurma, :codDisciplina, :codAtividade, null)");
                    }
                    else {
                      $stmt3 = $pdo->prepare("insert into AlunoTurmaDisciplinaAtividadeTCC (raAluno, codTurma, codDisciplina, codAtividade, nota) values (:raAluno, :codTurma, :codDisciplina, :codAtividade, :nota)");
                      $stmt3->bindParam(":nota", $_POST[$row["raAluno"]]);
                    }    
                    $stmt3->bindParam(":raAluno", $row["raAluno"]);
                    $stmt3->bindParam(":codTurma", $_GET["turma"]);
                    $stmt3->bindParam(":codDisciplina", $_GET["disc"]);
                    $stmt3->bindParam(":codAtividade", $_GET["ativ"]);
                    $stmt3->execute();
                  }
                  $msg = 0;
                }
              }
            }
            catch(PDOException $e) {
              echo 'Error: ' . $e->getMessage();
            }
            finally {
              $pdo = null;
            }
            if(isset($msg)) {
              switch ($msg) {
                case 0:
                  echo "<script>alert('Alterações salvas com sucesso!');</script>";
                  echo "<script>window.location.replace('editNotas.php?turma=".$_GET["turma"]."&disc=".$_GET["disc"]."&ativ=".$_GET["ativ"]."');</script>";
                  break;  
                case 1:
                  echo "<span class='text-warning'>Não foi possível fazer as alterações.</span>";
                  break;
              }
            }
          }
        ?>
      </form>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>