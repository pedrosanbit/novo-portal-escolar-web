<?php
    session_start();
    if(!isset($_SESSION['login']))
        header('location:../index.php');
    else if($_SESSION['tipo'] != 'admin')
        header('location:../index.php');
        
	if ($_SERVER["REQUEST_METHOD"] === 'POST') {
		include("../conexaoBD.php");

        try {
            $confirmacao;            
            $rf = $_POST["rf"];
            $nome = $_POST["nome"];
            $rg = $_POST["rg"];
            $email = $_POST["email"];

            if ((trim($rf) == "") || (trim($nome) == "")  || (trim($rg) == "")) {
                $confirmacao = 2;
            } else {
                $stmt = $pdo->prepare("select * from ProfessoresTCC where rfProfessor = :rf");
                $stmt->bindParam(':rf', $rf);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows <= 0) {
                    $stmt = $pdo->prepare("insert into ProfessoresTCC (rfProfessor, nomeProfessor, rgProfessor, email) values(:rf, :nome, :rg, :email)");
                    $stmt->bindParam(':rf', $rf);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':rg', $rg);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    $senha = "" . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9);
		    include("phpMailer.php");
                    enviarEmail($email, $nome, $rf, $senha);
			
                    $senha = password_hash($senha, PASSWORD_DEFAULT);
                    $tipo = "prof";
                    $stmt = $pdo->prepare("insert into UsuariosTCC (usuario, senha, tipo) values(:rf, :senha, :tipo)");
                    $stmt->bindParam(':rf', $rf);
                    $stmt->bindParam(':senha', $senha);
                    $stmt->bindParam(':tipo', $tipo);
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
        <link rel="icon" href="../logoUnicampAzul.png">
        <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"-->
        <link rel="stylesheet" href="../custom.css">
        <style type="text/css">
            #navsanduiche {
                visibility: hidden;
                position: absolute;
                top: -9999px;
            }

            @media (max-width: 680px) {
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
                                <a class="nav-link" aria-current="page" href="admin.php"><i class="fas fa-home"></i> In??cio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="adminProfessores.php"><b><i class="fas fa-chalkboard-teacher"></i> Professores</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
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
                    <a class="nav-link text-dark" aria-current="page" href="admin.php"><i class="fas fa-home"></i> In??cio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-primary" id="nav-active" href="adminProfessores.php"><b><i class="fas fa-chalkboard-teacher"></i> Professores</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
                </li>
            </ul>
        </div>
        <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="adminProfessores.php">Professores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cadastro</li>
            </ol>
        </nav>
        <div class="container mt-3">
            <form method="post">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="nome" class="form-label">Nome:</label>
                        <input class="form-control" type="text" id="nome" name="nome">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="ra" class="form-label">RF:</label>
                        <input class="form-control" type="text" id="rf" name="rf" maxlength="6">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="email" class="form-label">E-mail:</label>
                        <input class="form-control" type="text" id="email" name="email">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="rg" class="form-label">RG (somente n??meros):</label>
                        <input class="form-control" type="text" id="rg" name="rg" maxlength="9">
                    </div>
                </div>
                <br>
                <?php
                    if(isset($confirmacao)) {
                        switch ($confirmacao) {
                            case 0:
                                echo "<span class='text-danger'>RF j?? cadastrado.</span>";
                                break;
                            case 1:
                                echo "<span class='text-success'>Professor Cadastrado!</span>";
                                break;
                            case 2:
                                echo "<span class='text-warning'>RF, RG e nome s??o obrigat??rios.</span>";
                                break;
                            case 3:
                                echo "<span class='text-danger'>RG j?? cadastrado!</span>";
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
        <script src="../javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>
