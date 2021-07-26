 <?php
  session_start();
  if(!isset($_SESSION['login']))
    header('location:../index.php');
  else if($_SESSION['tipo'] != 'prof')
    header('location:../index.php');
  if(!isset($_GET['turma']) || !isset($_GET['disciplina']) || !isset($_GET["etapa"]))
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
                <a class="nav-link" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminTurmas.php"><i class="fas fa-file-alt"></i> Notas</a>
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
          <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-search"></i> Consultas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-calendar-alt"></i> Frequência</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="adminTurmas.php"><i class="fas fa-file-alt"></i> Notas</a>
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
            <li class="breadcrumb-item active" aria-current='page'>Avaliações</li>
            <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $_GET["etapa"] . "º Semestre</li> "; ?>  
        </ol>
    </nav>
    <div class="container mt-3">
    	<div class="table-responsive mt-4 mb-4">
    		<form method='post'>
	    		<table id='tableAv' class='table table-sm table-striped table-hover'>
	    			<thead>
	    				<th>Avaliação</th>
	    				<th>Data</th>
	    				<th>Peso</th>
	    				<th>Excluir</th>
	    			</thead>
	    			<tbody>
	    				<?php
	    					include("../conexaoBD.php");
	    					try {
	    						$stmt = $pdo->prepare("select dta.codAtividade from DisciplinaTurmaAtividadeTCC dta inner join AtividadesTCC a where dta.codTurma = :codTurma and dta.codDisciplina = :codDisciplina and a.etapa = :etapa");
  								$stmt->bindParam(":codTurma", $_GET["turma"]);
  								$stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
  								$stmt->bindParam(":etapa", $_GET["etapa"]);
  								$stmt->execute();
  								if($stmt->rowCount() > 0) {
  									$atividadesArray = array();
  									$stmt = $pdo->prepare("select a.codAtividade, a.descricao, a.data, a.peso from AtividadesTCC a inner join DisciplinaTurmaAtividadeTCC dta on a.codAtividade = dta.codAtividade where dta.codTurma = :codTurma and dta.codDisciplina = :codDisciplina and a.etapa = :etapa");
  									$stmt->bindParam(":codTurma", $_GET["turma"]);
  									$stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
  									$stmt->bindParam(":etapa", $_GET["etapa"]);
  									$stmt->execute();
  									while($row = $stmt->fetch()) {
  										$atividadesArray[count($atividadesArray)] = $row["codAtividade"];
  										echo "<tr>
  												<td>
  													<input type='hidden' name='nomeAnt[]' value='".$row["codAtividade"]."'>
  													<input class='form-control' type='text' name='nome[]' value='".$row["descricao"]."'>
  												</td>
  												<td>
  													<input class='form-control' type='date' name='data[]' value='".$row["data"]."'>
  												</td>
  												<td>
  													<input class='form-control' type='number' name='peso[]' value='".(int)$row["peso"]."' min='1' max='8'>
  												</td>
  												<td class='text-md-start text-center'>
		    										<i class='fas fa-minus-circle text-danger ms-md-4 deleteBtn'></i>
		    									</td>
  											  </tr>";
  									}
  								}
  								else {
  									for($i = 0; $i < 3; $i++) {
	  									echo "<tr>
		    								  	<td>
		    										<input class='form-control' type='text' name='nome[]'>
		    									</td>
		    									<td>
		    										<input class='form-control' type='date' name='data[]'>
		    									</td>
		    									<td>
		    										<input class='form-control' type='number' name='peso[]' min='1' max='8'>
		    									</td>
		    									<td class='text-md-start text-center'>
		    										<i class='fas fa-minus-circle text-danger ms-md-4 deleteBtn'></i>
		    									</td>
		    								  </tr>";
		    						}
  								}
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
	    		<div>
		            <button type="button" class='btn btn-primary rounded-pill text-white' onclick="addRow();"><i class='fas fa-plus-square'></i> Adicionar Avaliação</button>
		        </div>
    	</div>
    	<div class='mt-4 text-center'>
            <button type='submit' class='btn btn-primary rounded-pill text-white'><b><i class='fas fa-save'></i> Salvar alterações</b></button>
        </div>
	   	</form>
	   	<?php
	   		if($_SERVER["REQUEST_METHOD"] === "POST") {
  				$msg = 0;
  				if(count($_POST["nome"]) < 3)
  					$msg = 1;
  				else {
  					for($i = 0; $i < count($_POST["nome"]); $i++) {
  						if(trim($_POST["nome"][$i]) == "" || trim($_POST["data"][$i]) == "" || trim($_POST["peso"][$i]) == "")
  							$msg = 2;
  					}
  					$soma = 0;
  					if($msg != 2) {
	  					foreach($_POST["peso"] as $peso)
	  						$soma += (int)$peso;
	  					unset($peso);
	  					if($soma != 10)
	  						$msg = 3;
  					}
  					if($msg != 2 && $msg != 3) {
  						include("../conexaoBD.php");
  						try {
  							if(isset($atividadesArray)) {
	  							foreach($atividadesArray as $atividade) {
	  								$found = 0;
	  								for($i = 0; $i < count($_POST["nomeAnt"]); $i++) {
	  									if($atividade == $_POST["nomeAnt"][$i]) {
	  										$stmt = $pdo->prepare("select * from AtividadesTCC where codAtividade = :codAtividade");
		  									$stmt->bindParam(":codAtividade", $atividade);
		  									$stmt->execute();
		  									$row = $stmt->rowCount();
			  								if($row == 1) {
			  									$row = $stmt->fetch();
			  									if($_POST["nome"][$i] != $row["descricao"]) {
			  										$stmt= $pdo->prepare("update AtividadesTCC set descricao = :nome where codAtividade= :codAtividade");
			                						$stmt->bindParam(':nome', $_POST["nome"][$i]);
			                						$stmt->bindParam(':codAtividade', $atividade);
			               							$stmt->execute();
			  									}
			  									if($_POST["data"][$i] != $row["data"]) {
			  										$stmt= $pdo->prepare("update AtividadesTCC set data = :data where codAtividade= :codAtividade");
			                						$stmt->bindParam(':data', $_POST["data"][$i]);
			                						$stmt->bindParam(':codAtividade', $atividade);
			               							$stmt->execute();
			  									}
			  									if($_POST["peso"][$i] != $row["peso"]) {
			  										$stmt= $pdo->prepare("update AtividadesTCC set peso = :peso where codAtividade= :codAtividade");
			  										$peso = (int)$_POST["peso"][$i];
			                						$stmt->bindParam(':peso', $peso);
			                						$stmt->bindParam(':codAtividade', $atividade);
			               							$stmt->execute();
			  									}
			  								}
			  								$found = 1;
	  									}
	  								}
	  								if($found == 0) {
	  										$stmt = $pdo->prepare("delete from AtividadesTCC where codAtividade = :codAtividade");
		  									$stmt->bindParam(":codAtividade", $atividade);
		  									$stmt->execute();
	  								}
	  							}
	  						}
  							$stmt = $pdo->prepare("select codAtividade from DisciplinaTurmaAtividadeTCC where codTurma = :codTurma and codDisciplina = :codDisciplina");
  							$stmt->bindParam(":codTurma", $_GET["turma"]);
  							$stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
  							$stmt->execute();
  							$j = $stmt->rowCount();
  							for($i = isset($_POST["nomeAnt"]) ? count($_POST["nomeAnt"]) : 0; $i < count($_POST["nome"]); $i++) {
  								$codAtividade = $_GET["turma"] . $_GET["disciplina"] . $j;
  								$peso = (int)$_POST["peso"][$i];
  								$stmt = $pdo->prepare("insert into AtividadesTCC (codAtividade, descricao, peso, data, etapa) values (:codAtividade, :nome, :peso, :data, :etapa)");
  								$stmt->bindParam(":codAtividade", $codAtividade);
  								$stmt->bindParam(":nome", $_POST["nome"][$i]);
  								$stmt->bindParam(":peso", $peso);
  								$stmt->bindParam(":data", $_POST["data"][$i]);
  								$stmt->bindParam(":etapa", $_GET["etapa"]);
  								$stmt->execute();

  								$stmt = $pdo->prepare("insert into DisciplinaTurmaAtividadeTCC (codTurma, codDisciplina, codAtividade) values (:codTurma, :codDisciplina, :codAtividade)");
  								$stmt->bindParam(":codTurma", $_GET["turma"]);
  								$stmt->bindParam(":codDisciplina", $_GET["disciplina"]);
  								$stmt->bindParam(":codAtividade", $codAtividade);
  								$stmt->execute();

  								$j++;
  							}
  						}
  						catch(PDOException $e) {
    						echo 'Error: ' . $e->getMessage();
  						}
  						finally {
    						$pdo = null;
  						}
  					}
  				}
  				if(isset($msg)) {
			  		switch ($msg) {
			  			case 0:
			  				echo "<script>alert('Alterações salvas com sucesso!');</script>";
			  				echo "<script>window.location.replace('editAtividades.php?turma=".$_GET["turma"]."&disciplina=".$_GET["disciplina"]."&etapa=".$_GET["etapa"]."');</script>";
			  				break;	
			  			case 1:
			  				echo "<span class='text-warning'>Devem haver pelo menos 3 atividades por etapa.</span>";
			  				break;
			  			case 2:
			  				echo "<span class='text-danger'>Preencha todos os campos.</span>";
			  				break;
			  			case 3:
			  				echo "<span class='text-warning'>A soma dos pesos das avaliações deve ser 10.</span>";
			  				break;
			  		}
		  		}
  			}
  			//a
	   	?>
    </div>
    <script src="../javascript/admin.js"></script>
    <script type="text/javascript">
    	const tbodyEl = document.querySelector('tbody');
    	var tabela = document.getElementById('tableAv');
    	function addRow() {
    		if(localStorage.getItem("darkTheme") === "on") {
    			toggleDarkMode();
    		}
    		tbodyEl.innerHTML += `
    			<tr>
	    			<td>
	    				<input class='form-control' type='text' name='nome[]'>
	    			</td>
	    			<td>
	    				<input class='form-control' type='date' name='data[]'>
	    			</td>
	    			<td>
	    				<input class='form-control' type='number' name='peso[]' min="1" max="8">
	    			</td>
	    			<td class='text-md-start text-center'>
	    				<i class='fas fa-minus-circle text-danger ms-md-4 deleteBtn'></i>
	    			</td>
	    		</tr>
    		`;
    		if(localStorage.getItem("darkTheme") === "on") {
    			toggleDarkMode();
    		}
    	}

    	function removeRow(e) {
    		if(!e.target.classList.contains("deleteBtn")) {
    			return;
    		}
    		const btn = e.target;
    		btn.closest("tr").remove();
    	}

    	tabela.addEventListener("click", removeRow);
   	</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>