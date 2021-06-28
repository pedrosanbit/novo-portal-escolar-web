<?php
    session_start();
    if(!isset($_SESSION['login']))
        header('location:../index.php');
    else if($_SESSION['tipo'] != 'admin')
        header('location:../index.php');
    if(!isset($_GET['codTurma']))
        header('location:../adminTurmas.php');

    try {
        include("../conexaoBD.php");
        $stmt=$pdo->prepare("select * from TurmasTCC where codTurma = :codTurma");
        $stmt->bindParam(":codTurma",$_GET['codTurma']);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row>0) {
            $row=$stmt->fetch();
            $codTurma = $row['codTurma'];
            $nomeTurma = $row['nomeTurma'];
        }
        else {
            header('location:adminTurmas.php');
        }
    }
    catch(PDOException $e) {
                echo 'Error: ' . $e->getMessage();
    }
    $pdo = null;

	if ($_SERVER["REQUEST_METHOD"] === 'POST') {
		include("../conexaoBD.php");
        $confirmacao;
        $alunos = null;
        if (isset($_POST['alunos'])) {
            $alunos = $_POST['alunos'];
            if($alunos !== null) {
                try {
                    for($i = 0; $i < count($alunos); $i++) {
                        $stmt = $pdo->prepare("select * from AlunoTurmaTCC where raAluno = :raAluno and codTurma = :codTurma");
                        $stmt->bindParam(":raAluno",$alunos[$i]);  
                        $stmt->bindParam(":codTurma",$codTurma);
                        $stmt->execute();
                        $rows = $stmt->rowCount();
                        if($rows > 0) {
                            unset($alunos[$i]);
                            $confirmacao = 0;
                        }
                    }
                    if(count($alunos) > 0) {
                        foreach($alunos as $ra) {
                            $stmt = $pdo->prepare("insert into AlunoTurmaTCC (raAluno,codTurma) values (:raAluno, :codTurma)");
                            $stmt->bindParam(":raAluno",$ra);
                            $stmt->bindParam(":codTurma",$codTurma);
                            $stmt->execute();
                        }
                        $confirmacao = 1;
                    }
                    else
                        $confirmacao = 3;
                }
                catch(PDOException $e) {
                    if($e->errorInfo[1]==1062)
                        $confirmacao=3;
                    else
                        echo 'Error: ' . $e->getMessage();
                }
                $pdo = null;
            }
            else
                $confirmacao = 2;
        }

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
                                <a class="nav-link" aria-current="page" href="admin.php"><i class="fas fa-home"></i> Início</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="adminTurmas.php"><b><i class="fas fa-users"></i> Turmas</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminProfessores.php"><i class="fas fa-chalkboard-teacher"></i> Professores</a>
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
                    <a class="nav-link text-dark" aria-current="page" href="admin.php"><i class="fas fa-home"></i> Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminCursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-primary" id="nav-active" href="adminTurmas.php"><b><i class="fas fa-users"></i> Turmas</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminProfessores.php"><i class="fas fa-chalkboard-teacher"></i> Professores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminAlunos.php"><i class="fas fa-user"></i> Alunos</a>
                </li>
            </ul>
        </div>
                <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="adminTurmas.php">Turmas</a></li>
                        <?php echo "<li class='breadcrumb-item'><a href='editTurma.php?codTurma=".$_GET['codTurma']."'>".$nomeTurma."</a></li> "; ?>
                        <li class="breadcrumb-item active" aria-current="page">Adicionar Alunos</li>
                    </ol>
                </nav>
                <div class="container mt-3">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="raAluno" class="form-label">RA:</label>
                                <input class="form-control" type="text" id="raAluno" name="raAluno" maxlength="6">
                            </div>
                            <div class="col-md-6">
                                <label for="nomeAluno" class="form-label">Nome:</label>
                                <input class="form-control" type="text" id="nomeAluno" name="nomeAluno">
                            </div>
                            <div class="col-md-2">
                                <label for="turmaAluno" class="form-label">Turma:</label>
                                <select class='form-select' id='turmaAluno' name='turmaAluno' aria-label='Default select example'>
                                  <?php include("selectTurmas.php"); ?>
                            </div>
                            <div class="col-md-2">
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
                    </form>
                    <form method="post">
                        <?php include("consultaAddAluno.php"); ?>
                        <?php
                            if(isset($confirmacao)) {
                                switch ($confirmacao) {
                                  case 0:
                                    echo "<span class='text-danger'>RA já cadastrado.</span>";
                                    break;
                                  case 1:
                                    echo "<span class='text-success'>Aluno(s) adicionados!</span>";
                                    break;
                                  case 2:
                                    echo "<span class='text-warning'>Selecione um aluno.</span>";
                                    break;
                                  case 3:
                                    echo "<span class='text-danger'>Aluno(s) já adicionados!</span>";
                                    break;
                                }
                            }
                        ?>
                        <hr>
                    </form>
                </div>
        <script src="../javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>