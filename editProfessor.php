<?php
    include("conexaoBD.php");
    $stmt = $pdo->prepare("select * from ProfessoresTCC where rfProfessor = :rf");
    $stmt->bindParam(':rf', $_GET["rf"]);
    $stmt->execute();
    $row=$stmt->rowCount();

    if($row>0){
        $row= $stmt->fetch();
        $nome=$row['nomeProfessor'];
        $rg=$row['rgProfessor'];
        $rf=$row['rfProfessor'];
    }
    $pdo=null;

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $rfAlterado=0;
        $confirmacao=0;
        $msg;
        include("conexaoBD.php");
        $rgNovo=$_POST['rg'];
        $nomeNovo=$_POST['nome'];
        $rfNovo=$_POST['rf'];

        $stmt = $pdo->prepare("select * from ProfessoresTCC where rfProfessor = :rf");
        $stmt->bindParam(':rf', $_GET["rf"]);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row==1){
            $row= $stmt->fetch();
            if($nomeNovo!=$row['nomeProfessor']){
                $stmt= $pdo->prepare("update ProfessoresTCC set nomeProfessor = :nomeNovo where rfProfessor= :rf");
                $stmt->bindParam(':rf', $_GET["rf"]);
                $stmt->bindParam(':nomeNovo', $nomeNovo);
                $stmt->execute();
            }
            if($rgNovo!=$row['rgProfessor']){
                    $stmt = $pdo->prepare("select * from ProfessoresTCC where rgProfessor = :rg");
                    $stmt->bindParam(':rg', $rgNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update ProfessoresTCC set rgProfessor = :rgNovo where rfProfessor= :rf");
                        $stmt->bindParam(':rf',$_GET['rf']);
                        $stmt->bindParam(':rgNovo',$rgNovo);
                        $stmt->execute();
                    }
                    else{
                        $msg= "RG já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($rfNovo!=$row['rfProfessor']){
                    $stmt = $pdo->prepare("select * from ProfessoresTCC where rfProfessor= :rf");
                    $stmt->bindParam(':rf', $rfNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update ProfessoresTCC set rfProfessor = :rfNovo where rfProfessor= :rf");
                        $stmt->bindParam(':rf',$_GET['rf']);
                        $stmt->bindParam(':rfNovo',$rfNovo);
                        $stmt->execute();
                        $rfAlterado=1;
                    }
                    else{
                        $msg= "RF já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($confirmacao==0){
                if($rfAlterado==0)
                    header("location:editProfessor.php?rf=".$_GET['rf']);
                else
                    header("location:editProfessor.php?rf=".$rfNovo);
            }
            /*else if($confirmacao==1){
                echo $msg;
            }
            else if($confirmacao==2){
                echo "RF e RG já cadastrados.";
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
                <a class="nav-link text-dark" aria-current="page" href="admin.php"><i class="fas fa-home"></i> Início</a>
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
        <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="adminProfessores.php">Professores</a></li>
                <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nome. "</li> "; ?> 
            </ol>
        </nav>
        <div class="container mt-3">
            <form method="post">
                <label for="nome" class="form-label">Nome:</label>
                <?php echo "<input value='" .$nome. "' class='form-control' type='text' id='nome' name='nome'>"; ?> 
                <br>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="rf" class="form-label">RF:</label>
                        <?php echo "<input value='" .$rf. "' class='form-control' type='text' id='rf' name='rf' maxlength='6'>"; ?>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="rg" class="form-label">RG (somente números):</label>
                        <?php echo "<input value='" .$rg. "' class='form-control' type='text' id='rg' name='rg' maxlength='9'>"; ?>  
                    </div>
                </div>
                <?php
                    if(isset($confirmacao)) {
                        if($confirmacao==1)
                            echo "<span class='text-danger'>" . $msg . "</span>";
                        else if($confirmacao==2)
                            echo "<span class='text-danger'>RF e RG já cadastrados.</span>";
                    }
                ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Alterar Dados</b></button>
                </div>
                <hr>
            </form>
        </div>
        <script type="text/javascript" src="javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>