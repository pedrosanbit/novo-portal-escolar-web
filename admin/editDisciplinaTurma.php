<?php
    session_start();
    if(!isset($_SESSION['login']))
        header('location:../index.php');
    else if($_SESSION['tipo'] != 'admin')
        header('location:../index.php');
    if(!isset($_GET['codTurma']) || !isset($_GET['codDisciplina']))
        header('location:adminTurmas.php');
    include("../conexaoBD.php");
    $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina, p.rfProfessor, p.nomeProfessor, t.nomeTurma from ProfessoresTCC p inner join LecionaTCC l on p.rfProfessor = l.rfProfessor inner join DisciplinasTCC d on l.codDisciplina = d.codDisciplina inner join TurmasTCC t on t.codTurma = l.codTurma where t.codTurma = :codTurma and d.codDisciplina = :codDisciplina");
    $stmt->bindParam(':codTurma', $_GET["codTurma"]);
    $stmt->bindParam(':codDisciplina', $_GET["codDisciplina"]);
    $stmt->execute();
    $row=$stmt->rowCount();

    if($row>0){
        $row= $stmt->fetch();
        $codDisciplina=$row['codDisciplina'];
        $nomeDisciplina=$row['nomeDisciplina'];
        $rfProfessor=$row['rfProfessor'];
        $professor=$row['nomeProfessor'];
        $nomeTurma=$row['nomeTurma'];
    }
    else {
       header('location:adminTurmas.php'); 
    }
    $pdo=null;

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $codDisciplinaAlterado=0;
        $professorAlterado=0;
        $confirmacao=0;
        $msg;
        include("../conexaoBD.php");
        $codDisciplinaNovo=$_POST['disciplina'];
        $professorNovo=$_POST['professor'];

        $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina, p.rfProfessor, p.nomeProfessor, t.nomeTurma from ProfessoresTCC p inner join LecionaTCC l on p.rfProfessor = l.rfProfessor inner join DisciplinasTCC d on l.codDisciplina = d.codDisciplina inner join TurmasTCC t on t.codTurma = l.codTurma where t.codTurma = :codTurma and d.codDisciplina = :codDisciplina and p.rfProfessor = :professor");
        $stmt->bindParam(':codTurma', $_GET["codTurma"]);
        $stmt->bindParam(':codDisciplina', $_GET["codDisciplina"]);
        $stmt->bindParam(':professor', $rfProfessor);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row==1) {
            $row=$stmt->fetch();
            if($professorNovo!=$row['rfProfessor']) {
                $stmt=$pdo->prepare("update LecionaTCC set rfProfessor = :professor where codTurma = :codTurma and codDisciplina = :codDisciplina and rfProfessor = :rfProfessor");
                $stmt->bindParam(':professor', $professorNovo);
                $stmt->bindParam(':codTurma', $_GET["codTurma"]);
                $stmt->bindParam(':codDisciplina', $_GET["codDisciplina"]);
                $stmt->bindParam(':rfProfessor', $rfProfessor);
                $stmt->execute();
                $professorAlterado=1;
            }
            if($codDisciplinaNovo!=$row['codDisciplina']) {
                $stmt=$pdo->prepare("select * from LecionaTCC where codDisciplina = :codDisciplina and codTurma = :codTurma ");
                $stmt->bindParam(':codDisciplina', $codDisciplinaNovo);
                $stmt->bindParam(':codTurma', $_GET['codTurma']);
                $stmt->execute();
                $rows=$stmt->rowCount();
                if ($rows <= 0) {
                    $stmt = $pdo->prepare("update LecionaTCC set codDisciplina = :codDisciplina where codTurma = :codTurma and rfProfessor = :professor and codDisciplina = :disciplina");
                    $stmt->bindParam(':codDisciplina', $codDisciplinaNovo);
                    $stmt->bindParam(':codTurma', $_GET["codTurma"]);
                    if($professorAlterado) $rfProfessor = $professorNovo;
                    $stmt->bindParam(':professor', $rfProfessor);
                    $stmt->bindParam(':disciplina', $_GET['codDisciplina']);
                    $stmt->execute();
                    $codDisciplinaAlterado=1;
                }
                else {
                    $msg = "A turma já possui a disciplina.";
                    $confirmacao++;
                }
            }
            if($confirmacao==0){
                if($codDisciplinaAlterado==0)
                    header("location:editDisciplinaTurma.php?codTurma=".$_GET['codTurma']."&codDisciplina=".$_GET['codDisciplina']);
                else
                    header("location:editDisciplinaTurma.php?codTurma=".$_GET['codTurma']."&codDisciplina=".$codDisciplinaNovo);
            }
        }
        $pdo=null;
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
                    <a class="nav-link active text-primary" id="nav-active" href="adminTurmas.php"><i class="fas fa-users"></i><b> Turmas</b></a>
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
                <?php echo "<li class='breadcrumb-item active' aria-current='page'>".$nomeDisciplina."</li>";?>
            </ol>
        </nav>
        <div class="container mt-3">
            <form method="post">
                <div class="row mb-2">
                    <div class="col-md-6 col-sm-12">
                        <label for="disciplina" class="form-label">Disciplina:</label>
                        <select class="form-select" id="disciplina" name="disciplina" aria-label='Default select example'>
                            <?php
                                include("../conexaoBD.php");
                                try {
                                    $stmt = $pdo->prepare("select * from DisciplinasTCC where codDisciplina = :codDisciplina");
                                    $stmt->bindParam(':codDisciplina',$codDisciplina);
                                    $stmt->execute();
                                    if($row= $stmt->fetch())
                                        echo "<option value='". $row["codDisciplina"] ."'>".$row["nomeDisciplina"]."</option>";

                                    $stmt= $pdo->prepare("select * from DisciplinasTCC where codDisciplina != :codDisciplina");
                                    $stmt->bindParam(':codDisciplina',$codDisciplina);
                                    $stmt->execute();
                                    while($row= $stmt->fetch())
                                        echo "<option value='". $row["codDisciplina"] ."'>".$row["nomeDisciplina"]."</option>";
                                    echo "</select>";
                                }
                                catch(PDOException $e){
                                    echo 'Error: ' . $e->getMessage();
                                }
                                finally{
                                    $pdo=null;
                                }
                            ?>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="professor" class="form-label">Professor:</label>
                        <select class="form-select" id="professor" name="professor" aria-label='Default select example'>
                            <?php
                                include("../conexaoBD.php");
                                try {
                                    $stmt = $pdo->prepare("select * from ProfessoresTCC where rfProfessor = :professor");
                                    $stmt->bindParam(':professor',$rfProfessor);
                                    $stmt->execute();
                                    if($row= $stmt->fetch())
                                        echo "<option value='". $row["rfProfessor"] ."'>".$row["nomeProfessor"]."</option>";

                                    $stmt= $pdo->prepare("select * from ProfessoresTCC where rfProfessor != :professor");
                                    $stmt->bindParam(':professor',$rfProfessor);
                                    $stmt->execute();
                                    while($row= $stmt->fetch())
                                        echo "<option value='". $row["rfProfessor"] ."'>".$row["nomeProfessor"]."</option>";
                                    echo "</select>";
                                }
                                catch(PDOException $e){
                                    echo 'Error: ' . $e->getMessage();
                                }
                                finally{
                                    $pdo=null;
                                }
                            ?>
                    </div>
                </div>
                <?php
                    if(isset($confirmacao)) {
                        if($confirmacao==1)
                            echo "<span class='text-warning'>" . $msg . "</span>";
                    }
                ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill text-white"><b><i class='fas fa-edit'></i> Alterar Dados</b></button>
                </div>
            </form>
        </div>
        <script type="text/javascript" src="../javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>