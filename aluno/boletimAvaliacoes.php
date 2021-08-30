 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'aluno')
    header('location:../index.php');
  if(!isset($_GET['turma']))
    header('location:alunoBoletimAvaliacoes.php');
  if(!isset($_GET['etapa']))
    header('location:alunoBoletimAvaliacoes.php');

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
                <a class="nav-link active" href="alunoBoletimAvaliacoes.php"><b><i class="far fa-file-alt"></i> Boletim de Avaliações</b></a>
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
          <a class="nav-link text-dark" href="alunoPlanoEnsino.php"><i class="fas fa-chalkboard"></i> Plano de Ensino</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active text-primary" id="nav-active" href="alunoBoletimAvaliacoes.php"><b><i class="far fa-file-alt"></i> Boletim de Avaliações</b></a>
        </li>
        <!--li class="nav-item">
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li-->
      </ul>
    </div>
    <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="alunoBoletimAvaliacoes.php">Boletim de Avaliações</a></li>
        <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeTurma . "</li> "; ?>
        <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $_GET["etapa"] . "º Semestre</li> "; ?>
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
                  echo "<script>document.getElementById('disc').submit(disciplina.value);</script>";
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
        if($_SERVER["REQUEST_METHOD"] == "POST") {
          echo "<div class='table-responsive mt-4 mb-4'>
                  <table id='tableConsulta' class='table table-sm table-striped table-hover'>
                    <thead>
                      <th>Avaliação</th>
                      <th>Data</th>
                      <th>Peso</th>
                      <th>Nota</th>
                      <th>Turma</th>
                      </thead>
                    <tbody>";
          include("../conexaoBD.php");
          try {
            $stmt = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join DisciplinaTurmaAtividadeTCC dta inner join AtividadesTCC a where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = :etapa");
            $stmt->bindParam(":raAluno", $_SESSION["login"]);
            $stmt->bindParam(":codTurma", $_GET["turma"]);
            $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
            $stmt->bindParam(":etapa", $_GET["etapa"]);
            $stmt->execute();
            while($row = $stmt->fetch()) {
              echo "<tr>";
              echo "<td>" . $row["descricao"] . "</td>";
              echo "<td>" . date("m/d/Y", strtotime($row["data"])) . "</td>";
              echo "<td>" . (int) $row["peso"] . "</td>";
              if($row["nota"] != null)
                echo "<td>" . number_format(bcdiv($row["nota"], 1, 1), 1, ",") . "</td>";
              else
                echo "<td></td>";
              $stmt2 = $pdo->prepare("select a.codAtividade, avg(atda.nota) from AlunoTurmaDisciplinaAtividadeTCC atda inner join AtividadesTCC a on atda.codAtividade = a.codAtividade where a.codAtividade = :codAtividade and a.etapa = :etapa group by a.codAtividade;");
              $stmt2->bindParam(":codAtividade", $row["codAtividade"]);
              $stmt2->bindParam(":etapa", $_GET["etapa"]);
              $stmt2->execute();
              if($stmt2->rowCount() > 0) {
                $row2 = $stmt2->fetch();
                if($row2["avg(atda.nota)"] != null)
                  echo "<td>" . number_format(bcdiv($row2["avg(atda.nota)"], 1, 1), 1, ",") . "</td>";
                else
                  echo "<td></td>";
              }
              else
                echo "<td></td>";
              echo "</tr>";
            }
            echo "<tr><td colspan='5'><div class='mb-2'></div></td></tr>";
            echo "<tr>";
            echo "<thead><th>Aulas Dadas</th>";
            echo "<th colspan='2'>Faltas</th>";
            echo "<th colspan='2'>Média na Disciplina</th></thead>";;
            echo "</tr>";
            echo "<tr>";
            echo "<td>0</td>";
            echo "<td colspan='2'>0(0.0%)</td>";

            $stmt = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join DisciplinaTurmaAtividadeTCC dta inner join AtividadesTCC a where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = :etapa");
            $stmt->bindParam(":raAluno", $_SESSION["login"]);
            $stmt->bindParam(":codTurma", $_GET["turma"]);
            $stmt->bindParam(":codDisciplina", $_POST["disciplina"]);
            $stmt->bindParam(":etapa", $_GET["etapa"]);
            $stmt->execute();
            $nota = null;
            $pesos = 0;
            $nota2 = null;
            $pesos2 = 0;
            while($row = $stmt->fetch()) {
              if($row["nota"] != null)  {
                $nota += $row["nota"] * $row["peso"];
                $pesos += $row["peso"];
              }
              $stmt2 = $pdo->prepare("select a.codAtividade, avg(atda.nota) from AlunoTurmaDisciplinaAtividadeTCC atda inner join AtividadesTCC a on atda.codAtividade = a.codAtividade where a.codAtividade = :codAtividade and a.etapa = :etapa group by a.codAtividade;");
              $stmt2->bindParam(":codAtividade", $row["codAtividade"]);
              $stmt2->bindParam(":etapa", $_GET["etapa"]);
              $stmt2->execute();
              if($stmt2->rowCount() > 0) {
                $row2 = $stmt2->fetch();
                if($row2["avg(atda.nota)"] != null) {
                  $nota2 += $row2["avg(atda.nota)"] * $row["peso"];
                  $pesos2 += $row["peso"];
                }
              }
            }
            if($pesos != 0) $nota = $nota/$pesos;
            if($pesos2 != 0) $nota2 = $nota2/$pesos2;
            if($nota != null)
              echo "<td>" . number_format(bcdiv($nota, 1, 1), 1, ",") . "</td>";
            else
              echo "<td></td>";
            if($nota2 != null)
              echo "<td>" . number_format(bcdiv($nota2, 1, 1), 1, ",") . "</td>";
            else
              echo "<td></td>";
            echo "</tr>";
            echo "</tbody></table></div>";
          }
          catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
          }
          finally {
            $pdo = null;
          }
        }
      ?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>