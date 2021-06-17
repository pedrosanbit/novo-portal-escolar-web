<?php
    include("conexaoBD.php");
    $stmt = $pdo->prepare("select * from CursosTCC where codCurso = :codCurso");
    $stmt->bindParam(':codCurso', $_GET["codCurso"]);
    echo $_GET["codCurso"];
    $stmt->execute();
    $row=$stmt->rowCount();

    if($row>0){
        $row= $stmt->fetch();
        $nomeCurso=$row['nomeCurso'];
        $codCurso=$row['codCurso'];
    }

    $pdo=null;

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $codCursoAlterado=0;
        $confirmacao=0;
        $msg;
        include("conexaoBD.php");
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
            else if($confirmacao==1){
                echo $msg;
            }

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
        <nav>
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
                <?php echo "<input value='" .$codCurso. "' class='form-control mb-3' type='text' id='codCurso' name='codCurso'>"; ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Alterar Dados</b></button>
                </div>
                <hr>
            </form>
        </div>
            <!--div class="nav nav-tabs" id="nav-tab" role="tablist">
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
                <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="adminAlunos.php">Alunos</a></li>
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
                                <label for="ra" class="form-label">RA:</label>
                                <?php echo "<input value='" .$ra. "' class='form-control' type='text' id='ra' name='ra' maxlength='6'>"; ?>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="rg" class="form-label">RG (somente números):</label>
                                <?php echo "<input value='" .$rg. "' class='form-control' type='text' id='rg' name='rg' maxlength='9'>"; ?> 
                            </div>
                        </div>
                        <br>
                        <label for="email" class="form-label">Email:</label>
                        <?php echo "<input value='" .$email. "' class='form-control mb-3' type='text' id='email' name='email'>"; ?>
                    

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Alterar Dados</b></button>
                        </div>
                        <hr>
                    </form>
                </div>
            </div>
        </div-->
        <script type="text/javascript" src="javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>