<?php
    session_start();
    if(!isset($_SESSION['login']))
        header('location:../index.php');
    else if($_SESSION['tipo'] != 'admin')
        header('location:../index.php');
    if(!isset($_GET['codCurso']))
        header('location:adminCursos.php');
    include("../conexaoBD.php");
    $stmt = $pdo->prepare("select * from CursosTCC where codCurso = :codCurso");
    $stmt->bindParam(':codCurso', $_GET["codCurso"]);
    $stmt->execute();
    $row=$stmt->rowCount();

    if($row>0){
        $row= $stmt->fetch();
        $nomeCurso=$row['nomeCurso'];
        $codCurso=$row['codCurso'];
    }
    else {
        header('location:adminCursos.php');
    }

    $pdo=null;

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $codCursoAlterado=0;
        $confirmacao=0;
        $msg;
        include("../conexaoBD.php");
        $nomeCursoNovo=$_POST['nomeCurso'];
        $codCursoNovo=$_POST['codCurso'];

        $stmt = $pdo->prepare("select * from CursosTCC where codCurso = :codCurso");
        $stmt->bindParam(':codCurso', $_GET["codCurso"]);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row==1){
            $row= $stmt->fetch();
            if($nomeCursoNovo!=$row['nomeCurso']){
                $stmt= $pdo->prepare("update CursosTCC set nomeCurso = :nomeCursoNovo where codCurso= :codCurso");
                $stmt->bindParam(':codCurso', $_GET["codCurso"]);
                $stmt->bindParam(':nomeCursoNovo', $nomeCursoNovo);
                $stmt->execute();
            }
            
            if($codCursoNovo!=$row['codCurso']){
                    $stmt = $pdo->prepare("select * from CursosTCC where codCurso = :codCurso");
                    $stmt->bindParam(':codCurso', $codCursoNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update CursosTCC set codCurso = :codCursoNovo where codCurso= :codCurso");
                        $stmt->bindParam(':codCurso',$_GET['codCurso']);
                        $stmt->bindParam(':codCursoNovo',$codCursoNovo);
                        $stmt->execute();
                        $codCursoAlterado=1;
                    }
                    else{
                        $msg= "Código de curso já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($confirmacao==0){
                if($codCursoAlterado==0)
                    header("location:editCurso.php?codCurso=".$_GET['codCurso']);
                else
                    header("location:editCurso.php?codCurso=".$codCursoNovo);
            }
            /*else if($confirmacao==1){
                echo $msg;
            }*/

            $pdo=null;
            
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
                                <a class="nav-link active" href="adminCursos.php"><b><i class="fas fa-graduation-cap"></i> Cursos</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
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
                    <a class="nav-link active text-primary" id="nav-active" href="adminCursos.php"><b><i class="fas fa-graduation-cap"></i> Cursos</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminDisciplinas.php"><i class="fas fa-book"></i> Disciplinas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="adminTurmas.php"><i class="fas fa-users"></i> Turmas</a>
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
                <li class="breadcrumb-item"><a href="adminCursos.php">Cursos</a></li>
                <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nomeCurso. "</li> "; ?> 
            </ol>
        </nav>
        <div class="container mt-3">
            <form method="post">
                <label for="nomeCurso" class="form-label">Nome do Curso:</label>
                <?php echo "<input value='" .$nomeCurso. "' class='form-control' type='text' id='nomeCurso' name='nomeCurso'>"; ?>
                <br>
                <label for="codCurso" class="form-label">Código do Curso:</label>
                <?php echo "<input value='" .$codCurso. "' class='form-control mb-3' type='number' id='codCurso' name='codCurso' min='1' max='99' maxlenght='2'>"; ?>
                <?php
                    if(isset($confirmacao)) {
                        if($confirmacao==1)
                            echo "<span class='text-danger'>" . $msg . "</span>";
                    }
                ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill text-white"><b><i class='fas fa-edit'></i> Alterar Dados</b></button>
                </div>
                <hr>
            </form>
        </div>
        <script type="text/javascript" src="../javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>