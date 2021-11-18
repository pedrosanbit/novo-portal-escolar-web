 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'prof')
    header('location:../index.php');
  if(!isset($_GET['turma']) || !isset($_GET['disc']) || !isset($_GET["data"]))
        header('location:professorFrequencia.php');
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
    <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="professorFrequencia.php">Frequência</a></li>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeTurma . "</li> "; ?>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeDisciplina . "</li> "; ?>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". date("d/m/Y", strtotime($_GET["data"]))."</li> "; ?>  
        </ol>
    </nav>
    <div class="container mt-3">
      <form method="post">
        <?php
          try {
            include("../conexaoBD.php");
            $stmt = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on alt.codTurma = t.codTurma where t.codTurma = :codTurma order by a.nomeAluno");
            $stmt->bindParam(":codTurma", $_GET["turma"]);
            $stmt->execute();
            echo "<div class='table-responsive mt-4 mb-4'>
                    <table id='tableFrequencia' class='table table-sm table-striped table-hover'>
                      <thead>
                        <tr>
                          <th style='text-align: center; vertical-align: middle;' rowspan='3' class='border-bottom border-dark sbeb'>Código</th>
                          <th style='text-align: center; vertical-align: middle;' rowspan='3' class='border-bottom border-dark sbeb'>Nome</th>
                          <th style='text-align: center; vertical-align: middle;'><input class='form-control' type='date' name='data' value='".$_GET["data"]."' disabled></th>
                        </tr>
                        <tr>
                          <th style='text-align: center; vertical-align: middle;'><button type='submit' class='btn btn-primary rounded-pill text-white w-50'><b>Processar</b></button></th>
                        </tr>
                            <tr>
                              <th style='text-align: center; vertical-align: middle;'>F1</th>
                            </tr>
                      </thead>
                      <tbody>";
            while($row = $stmt->fetch()) {
              echo "<tr>";
              echo "<td style='text-align: center; vertical-align: middle;'>" . $row['raAluno'] . "</td>";
              echo "<td style='text-align: center; vertical-align: middle;'>" . $row['nomeAluno'] . "</td>";

              $stmt2 = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma and codDisc = :codDisc and data = :data");
              $stmt2->bindParam(":codTurma", $_GET["turma"]);
              $stmt2->bindParam(":codDisc", $_GET["disc"]);
              $stmt2->bindParam(":data", $_GET["data"]);
              $stmt2->execute();
              $rows = $stmt2->rowCount();
              if($rows > 0) {
                $stmt2 = $pdo->prepare("select * from PresencasTCC where raAluno = :raAluno and codTurma = :codTurma and codDisciplina = :codDisciplina and data = :data");
                $stmt2->bindParam(":raAluno", $row['raAluno']);
                $stmt2->bindParam(":codTurma", $_GET["turma"]);
                $stmt2->bindParam(":codDisciplina", $_GET["disc"]);
                $stmt2->bindParam(":data", $_GET["data"]);
                $stmt2->execute();
                if($row2 = $stmt2->fetch()) {
                  if($row2['presenca'] == 1) {
                    echo "<td style='text-align: center; vertical-align: middle;'>" . "<input type='checkbox' name='aluno[]' value='" . $row['raAluno'] . "'>" . "</td>";
                  }
                  else {
                    echo "<td style='text-align: center; vertical-align: middle;'>" . "<input type='checkbox' name='aluno[]' value='" . $row['raAluno'] . "' checked>" . "</td>";
                  }
                }
              }
              else {
                echo "<td style='text-align: center; vertical-align: middle;'>" . "<input type='checkbox' name='aluno[]' value='" . $row['raAluno'] . "'>" . "</td>";
              }

              echo "</tr>";
            }
            echo "</tbody></table></div>";
            echo "<label for='materia' class='form-label'>Matéria Lecionada</label>";
            $stmt = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma and codDisc = :codDisc and data = :data");
            $stmt->bindParam(":codTurma", $_GET["turma"]);
            $stmt->bindParam(":codDisc", $_GET["disc"]);
            $stmt->bindParam(":data", $_GET["data"]);
            $stmt->execute();
            $rows = $stmt->rowCount();
            if($rows > 0) {
              $row = $stmt->fetch();
              echo "<textarea class='form-control' id='materia' name='materia' rows='3'>".$row["materia"]."</textarea>";
            }
            else {
              echo "<textarea class='form-control' id='materia' name='materia' rows='3' maxlength='200'></textarea>";
            }
          }
          catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
          }
          finally {
            $pdo = null;
          }
        ?>
      </form>
      <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
          try {
            include("../conexaoBD.php");
            $stmt = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma and codDisc = :codDisc and data = :data");
            $stmt->bindParam(":codTurma", $_GET["turma"]);
            $stmt->bindParam(":codDisc", $_GET["disc"]);
            $stmt->bindParam(":data", $_GET["data"]);
            $stmt->execute();
            $rows = $stmt->rowCount();
            if($rows > 0) {
              $stmt = $pdo->prepare("update AulasTCC set materia = :materia where codTurma = :codTurma and codDisc = :codDisc and data = :data");
              $stmt->bindParam(":materia", $_POST["materia"]);
              $stmt->bindParam(":codTurma", $_GET["turma"]);
              $stmt->bindParam(":codDisc", $_GET["disc"]);
              $stmt->bindParam(":data", $_GET["data"]);
              $stmt->execute();

              $stmt = $pdo->prepare("delete from PresencasTCC where codTurma = :codTurma and codDisciplina = :codDisciplina and data = :data");
              $stmt->bindParam(":codTurma", $_GET["turma"]);
              $stmt->bindParam(":codDisciplina", $_GET["disc"]);
              $stmt->bindParam(":data", $_GET["data"]);
              $stmt->execute();

              if(isset($_POST["aluno"])) {
                foreach($_POST["aluno"] as $aluno) {
                  $stmt = $pdo->prepare("insert into PresencasTCC(raAluno, codTurma, codDisciplina, data, presenca) values (:raAluno, :codTurma, :codDisciplina, :data, 0)");
                  $stmt->bindParam(":raAluno", $aluno);
                  $stmt->bindParam(":codTurma", $_GET["turma"]);
                  $stmt->bindParam(":codDisciplina", $_GET["disc"]);
                  $stmt->bindParam(":data", $_GET["data"]);
                  $stmt->execute();
                }
                unset($aluno);

                $stmt = $pdo->prepare("select a.raAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on alt.codTurma = t.codTurma where t.codTurma = :codTurma order by a.nomeAluno");
                $stmt->bindParam(":codTurma", $_GET["turma"]);
                $stmt->execute();
                while($row = $stmt->fetch()) {
                  if(!in_array($row["raAluno"], $_POST["aluno"])) {
                    $stmt2 = $pdo->prepare("insert into PresencasTCC(raAluno, codTurma, codDisciplina, data, presenca) values (:raAluno, :codTurma, :codDisciplina, :data, 1)");
                    $stmt2->bindParam(":raAluno", $row["raAluno"]);
                    $stmt2->bindParam(":codTurma", $_GET["turma"]);
                    $stmt2->bindParam(":codDisciplina", $_GET["disc"]);
                    $stmt2->bindParam(":data", $_GET["data"]);
                    $stmt2->execute();
                  }
                }
              }
              else {
                $stmt = $pdo->prepare("select a.raAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on alt.codTurma = t.codTurma where t.codTurma = :codTurma order by a.nomeAluno");
                $stmt->bindParam(":codTurma", $_GET["turma"]);
                $stmt->execute();
                while($row = $stmt->fetch()) {
                  $stmt2 = $pdo->prepare("insert into PresencasTCC(raAluno, codTurma, codDisciplina, data, presenca) values (:raAluno, :codTurma, :codDisciplina, :data, 1)");
                  $stmt2->bindParam(":raAluno", $row["raAluno"]);
                  $stmt2->bindParam(":codTurma", $_GET["turma"]);
                  $stmt2->bindParam(":codDisciplina", $_GET["disc"]);
                  $stmt2->bindParam(":data", $_GET["data"]);
                  $stmt2->execute();
                }
              }
            }
            else {
              $stmt = $pdo->prepare("insert into AulasTCC(codTurma, codDisc, data, materia) values (:codTurma, :codDisc, :data, :materia)");
              $stmt->bindParam(":codTurma", $_GET["turma"]);
              $stmt->bindParam(":codDisc", $_GET["disc"]);
              $stmt->bindParam(":data", $_GET["data"]);
              $stmt->bindParam(":materia", $_POST["materia"]);
              $stmt->execute();

              if(isset($_POST["aluno"])) {
                foreach($_POST["aluno"] as $aluno) {
                  $stmt = $pdo->prepare("insert into PresencasTCC(raAluno, codTurma, codDisciplina, data, presenca) values (:raAluno, :codTurma, :codDisciplina, :data, 0)");
                  $stmt->bindParam(":raAluno", $aluno);
                  $stmt->bindParam(":codTurma", $_GET["turma"]);
                  $stmt->bindParam(":codDisciplina", $_GET["disc"]);
                  $stmt->bindParam(":data", $_GET["data"]);
                  $stmt->execute();
                }
                unset($aluno);

                $stmt = $pdo->prepare("select a.raAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on alt.codTurma = t.codTurma where t.codTurma = :codTurma order by a.nomeAluno");
                $stmt->bindParam(":codTurma", $_GET["turma"]);
                $stmt->execute();
                while($row = $stmt->fetch()) {
                  if(!in_array($row["raAluno"], $_POST["aluno"])) {
                    $stmt2 = $pdo->prepare("insert into PresencasTCC(raAluno, codTurma, codDisciplina, data, presenca) values (:raAluno, :codTurma, :codDisciplina, :data, 1)");
                    $stmt2->bindParam(":raAluno", $row["raAluno"]);
                    $stmt2->bindParam(":codTurma", $_GET["turma"]);
                    $stmt2->bindParam(":codDisciplina", $_GET["disc"]);
                    $stmt2->bindParam(":data", $_GET["data"]);
                    $stmt2->execute();
                  }
                }
              }
              else {
                $stmt = $pdo->prepare("select a.raAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on alt.codTurma = t.codTurma where t.codTurma = :codTurma order by a.nomeAluno");
                $stmt->bindParam(":codTurma", $_GET["turma"]);
                $stmt->execute();
                while($row = $stmt->fetch()) {
                  $stmt2 = $pdo->prepare("insert into PresencasTCC(raAluno, codTurma, codDisciplina, data, presenca) values (:raAluno, :codTurma, :codDisciplina, :data, 1)");
                  $stmt2->bindParam(":raAluno", $row["raAluno"]);
                  $stmt2->bindParam(":codTurma", $_GET["turma"]);
                  $stmt2->bindParam(":codDisciplina", $_GET["disc"]);
                  $stmt2->bindParam(":data", $_GET["data"]);
                  $stmt2->execute();
                }
              }
            }
          }
          catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
          }
          finally {
            $pdo = null;
            echo "<script>alert('Alterações salvas com sucesso!')</script>";
            echo "<script>window.location.replace('editFrequencia.php?turma=" . $_GET["turma"] ."&disc=" . $_GET["disc"] . "&data=" . $_GET["data"] . "')</script>";
          }
        }
      ?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>