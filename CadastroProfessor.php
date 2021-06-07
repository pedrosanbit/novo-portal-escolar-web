<?php
	if ($_SERVER["REQUEST_METHOD"] === 'POST') {
		include("conexaoBD.php");

        try {
            $confirmacao;            
            $rf = $_POST["rf"];
            $nome = $_POST["nome"];
            $rg = $_POST["rg"];

            if ((trim($rf) == "") || (trim($nome) == "")  || (trim($rg) == "")) {
                $confirmacao = 2;
            } else {
                $stmt = $pdo->prepare("select * from ProfessoresTCC where rfProfessor = :rf");
                $stmt->bindParam(':rf', $rf);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows <= 0) {
                    $stmt = $pdo->prepare("insert into ProfessoresTCC (rfProfessor, nomeProfessor, rgProfessor) values(:rf, :nome, :rg)");
                    $stmt->bindParam(':rf', $rf);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':rg', $rg);
                    $stmt->execute();
                   
                    $confirmacao = 1;
                } else {
                    $confirmacao = 0;
                }
            }

        } catch(PDOException $e) {
        	if($e->errorInfo[1]==1062){
        		$confirmacao=3;
        	}
        	else
            	echo 'Error: ' . $e->getMessage();
        }

        $pdo = null;

    }

?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=0.5, maximum-scale=3, shrink-to-fit=no">
        <title>Portal Escolar</title>
        <link rel="icon" href="logoUnicampAzul.png">
        <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"-->
        <link rel="stylesheet" href="custom.css">
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
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link text-dark" aria-current="page" href="#"><i class="fas fa-home"></i> Início</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#"><i class="fas fa-graduation-cap"></i> Cursos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#"><i class="fas fa-book"></i> Disciplinas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#"><i class="fas fa-users"></i> Turmas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-primary" id="nav-active" href="#"><b><i class="fas fa-chalkboard-teacher"></i> Professores</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#"><i class="fas fa-user"></i> Alunos</a>
            </li>
        </ul>
        <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="adminProfessores.php">Professores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cadastro</li>
            </ol>
        </nav>
        <div class="container mt-3">
            <form method="post">
                <label for="nome" class="form-label">Nome:</label>
                <input class="form-control" type="text" id="nome" name="nome">
                <br>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="ra" class="form-label">RF:</label>
                        <input class="form-control" type="text" id="rf" name="rf" maxlength="6">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="rg" class="form-label">RG (somente números):</label>
                        <input class="form-control" type="text" id="rg" name="rg" maxlength="9">
                    </div>
                </div>
                <br>
                <?php
                    if(isset($confirmacao)) {
                        switch ($confirmacao) {
                            case 0:
                                echo "<span class='text-danger'>RF já cadastrado.</span>";
                                break;
                            case 1:
                                echo "<span class='text-success'>Professor Cadastrado!</span>";
                                break;
                            case 2:
                                echo "<span class='text-warning'>RF, RG e nome são obrigatórios.</span>";
                                break;
                            case 3:
                                echo "<span class='text-danger'>RG já cadastrado!</span>";
                                break;
                        }
                    }
                ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill text-white w-25"><b>Cadastrar</b></button>
                </div>
                <hr>
            </form>
        </div>
        <script src="javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>