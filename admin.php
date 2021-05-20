<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=0.5, maximum-scale=3, shrink-to-fit=no">
		<title>Portal Escolar</title>
		<link rel="icon" href="logoUnicampAzul.png">
		<!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"-->
		<link rel="stylesheet" href="custom.css">
    <style type="text/css">
      th, td {
        padding-left: 1rem;
      }
    </style>
		<script src="https://kit.fontawesome.com/ebb5206ba7.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<nav class="navbar navbar-dark bg-primary" id="navbar">
  			<div class="container-fluid">
    			<a class="navbar-brand" href="#">
    				<img src="logoUnicamp.png" width="32" class="d-inline-block align-text-top">
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
		<nav>
  			<div class="nav nav-tabs" id="nav-tab" role="tablist">
   				<button class="nav-link active text-dark" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-inicio" type="button" role="tab" aria-controls="nav-inicio" aria-selected="true" onclick="toggleActiveTab();">
   					<i class="fas fa-home"></i> Início
   				</button>
    			<button class="nav-link text-dark" id="nav-curso-tab" data-bs-toggle="tab" data-bs-target="#nav-cursos" type="button" role="tab" aria-controls="nav-cursos" aria-selected="false" onclick="toggleActiveTab();">
    				<i class="fas fa-graduation-cap"></i> Cursos
    			</button>
    			<button class="nav-link text-dark" id="nav-disciplina-tab" data-bs-toggle="tab" data-bs-target="#nav-disciplinas" type="button" role="tab" aria-controls="nav-disciplinas" aria-selected="false" onclick="toggleActiveTab();">
    				<i class="fas fa-book"></i> Disciplinas
    			</button>
    			<button class="nav-link text-dark" id="nav-turmas-tab" data-bs-toggle="tab" data-bs-target="#nav-turmas" type="button" role="tab" aria-controls="nav-turmas" aria-selected="false" onclick="toggleActiveTab();">
    				<i class="fas fa-users"></i> Turmas
    			</button>
    			<button class="nav-link text-dark" id="nav-professor-tab" data-bs-toggle="tab" data-bs-target="#nav-professores" type="button" role="tab" aria-controls="nav-professores" aria-selected="false" onclick="toggleActiveTab();">
    				<i class="fas fa-chalkboard-teacher"></i> Professores
    			</button>
    			<button class="nav-link text-dark" id="nav-aluno-tab" data-bs-toggle="tab" data-bs-target="#nav-alunos" type="button" role="tab" aria-controls="nav-alunos" aria-selected="false" onclick="toggleActiveTab();">
    				<i class="fas fa-user"></i> Alunos
    			</button>
  			</div>
		</nav>
		<div class="tab-content" id="nav-tabContent">
 			<div class="tab-pane fade show active" id="nav-inicio" role="tabpanel" aria-labelledby="nav-home-tab">
 				Conteúdo Início
 			</div>
  			<div class="tab-pane fade" id="nav-cursos" role="tabpanel" aria-labelledby="nav-curso-tab">
  				Conteúdo Cursos
  			</div>
  			<div class="tab-pane fade" id="nav-disciplinas" role="tabpanel" aria-labelledby="nav-disciplina-tab">
  				Conteúdo Disciplinas
  			</div>
  			<div class="tab-pane fade" id="nav-turmas" role="tabpanel" aria-labelledby="nav-turmas-tab">
  				Conteúdo Turmas
  			</div>
  			<div class="tab-pane fade" id="nav-professores" role="tabpanel" aria-labelledby="nav-professor-tab">
  				Conteúdo Professores
  			</div>
  			<div class="tab-pane fade" id="nav-alunos" role="tabpanel" aria-labelledby="nav-aluno-tab">
  				<div class="container mt-3">
  					<form method="post">
              <div class="row">
                <div class="col-md-3">
                  <label for="raAluno" class="form-label">RA:</label>
                  <input class="form-control" type="text" id="raAluno" name="raAluno" maxlength="6">
                </div>
                <div class="col-md-6">
                  <label for="nomeAluno" class="form-label">Nome:</label>
                  <input class="form-control" type="text" id="nomeAluno" name="nomeAluno">
                </div>
                <div class="col-md-3">
                  <label for="ordemConsulta" class="form-label">Ordem:</label>
                  <select class="form-select" id="ordemConsulta" name="ordemConsulta" aria-label="Default select example">
                    <option selected value="AlfAZ">Alfabética A-Z</option>
                    <option value="AlfZA">Alfabética Z-A</option>
                    <option value="RAcresc">RA crescente</option>
                    <option value="RAdecresc">RA decrescente</option>
                  </select>
                </div>
              </div>
              <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Consultar Alunos</b></button>
              </div>
              <?php
                if($_SERVER["REQUEST_METHOD"] === "POST") {
                  include("conexaoBD.php");
                  $comando = "select * from AlunosTCC";
                  if(isset($_POST["raAluno"]) && (trim($_POST["raAluno"]) != "")) {
                    $ra = $_POST["raAluno"];
                    $comando .= " where raAluno= :ra";
                  }
                  else if(isset($_POST["nomeAluno"]) && (trim($_POST["nomeAluno"]) != "")) {
                    $nome = $_POST["nomeAluno"];
                    $nome = ucfirst(strtolower($nome));
                    $nome = "%" . $nome . "%";
                    $comando .= " where nomeAluno like :nome";
                  }
                  $ordem = $_POST["ordemConsulta"];
                  switch($ordem) {
                    case 'AlfAZ':
                      $stmt = $pdo->prepare($comando . " order by nomeAluno asc");
                      if(isset($ra)) $stmt->bindParam(':ra', $ra);
                      if(isset($nome)) $stmt->bindParam(':nome', $nome);
                      imprimeTabela($stmt);
                      break;
                    case 'AlfZA' :
                      $stmt = $pdo->prepare($comando . " order by nomeAluno desc");
                      if(isset($ra)) $stmt->bindParam(':ra', $ra);
                      if(isset($nome)) $stmt->bindParam(':nome', $nome);
                      imprimeTabela($stmt);
                      break;
                    case 'RAcresc':
                      $stmt = $pdo->prepare($comando . " order by raAluno asc");
                      if(isset($ra)) $stmt->bindParam(':ra', $ra);
                      if(isset($nome)) $stmt->bindParam(':nome', $nome);
                      imprimeTabela($stmt);
                      break;
                    case 'RAdecresc':
                      $stmt = $pdo->prepare($comando . " order by raAluno desc");
                      if(isset($ra)) $stmt->bindParam(':ra', $ra);
                      if(isset($nome)) $stmt->bindParam(':nome', $nome);
                      imprimeTabela($stmt);
                      break;
                  }
                }

                function imprimeTabela($var) {
                  $stmt = $var;
                  try {
                    echo "<div class='table-responsive mt-4 mb-4'>
                            <table id='tableConsulta' class='table table-sm table-striped table-hover'>
                              <thead>
                                <th>RA</th>
                                <th>Nome</th>
                                <th>RG</th>
                              </thead>
                              <tbody>";
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                      echo "<tr>";
                      echo "<td>" . $row['raAluno'] . "</td>";
                      echo "<td>" . $row['nomeAluno'] . "</td>";
                      echo "<td>" . $row['rgAluno'] . "</td>";
                      echo "</tr>";
                    }
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
              <hr>
            </form>
  					<a href="CadastroAluno.php" class="btn btn-primary btn-lg rounded-pill text-white" role="button">
  						<i class="fas fa-user-plus"></i> Cadastrar Alunos
  					</a>
  				</div>
  			</div>
		</div>
		<script src="js/admin.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
	</body>
</html>