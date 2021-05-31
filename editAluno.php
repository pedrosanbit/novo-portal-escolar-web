<?php
    include("conexaoBD.php");
    $stmt = $pdo->prepare("select * from AlunosTCC where raAluno = :ra");
    $stmt->bindParam(':ra', $_GET["ra"]);
    $stmt->execute();
    $row=$stmt->rowCount();

    if($row>0){
        $row= $stmt->fetch();
        $nome=$row['nomeAluno'];
        $rg=$row['rgAluno'];
        $ra=$row['raAluno'];
        $email=$row['emailAluno'];
    }
    $pdo=null;

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $raAlterado=0;
        $confirmacao=0;
        $msg;
        include("conexaoBD.php");
        $rgNovo=$_POST['rg'];
        $nomeNovo=$_POST['nome'];
        $raNovo=$_POST['ra'];
        $emailNovo=$_POST['email'];

        $stmt = $pdo->prepare("select * from AlunosTCC where raAluno = :ra");
        $stmt->bindParam(':ra', $_GET["ra"]);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row==1){
            $row= $stmt->fetch();
            if($nomeNovo!=$row['nomeAluno']){
                $stmt= $pdo->prepare("update AlunosTCC set nomeAluno = :nomeNovo where raAluno= :ra");
                $stmt->bindParam(':ra', $_GET["ra"]);
                $stmt->bindParam(':nomeNovo', $nomeNovo);
                $stmt->execute();
            }
            if($emailNovo!=$row['emailAluno']){
                $stmt= $pdo->prepare("update AlunosTCC set emailAluno = :emailNovo where raAluno= :ra");
                $stmt->bindParam(':ra', $_GET["ra"]);
                $stmt->bindParam(':emailNovo', $emailNovo);
                $stmt->execute();
            }
            if($rgNovo!=$row['rgAluno']){
                    $stmt = $pdo->prepare("select * from AlunosTCC where rgAluno = :rg");
                    $stmt->bindParam(':rg', $rgNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update AlunosTCC set rgAluno = :rgNovo where raAluno= :ra");
                        $stmt->bindParam(':ra',$_GET['ra']);
                        $stmt->bindParam(':rgNovo',$rgNovo);
                        $stmt->execute();
                    }
                    else{
                        $msg= "RG já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($raNovo!=$row['raAluno']){
                    $stmt = $pdo->prepare("select * from AlunosTCC where raAluno = :ra");
                    $stmt->bindParam(':ra', $raNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update AlunosTCC set raAluno = :raNovo where raAluno= :ra");
                        $stmt->bindParam(':ra',$_GET['ra']);
                        $stmt->bindParam(':raNovo',$raNovo);
                        $stmt->execute();
                        $raAlterado=1;
                    }
                    else{
                        $msg= "RA já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($confirmacao==0){
                if($raAlterado==0)
                    header("location:editAluno.php?ra=".$_GET['ra']);
                else
                    header("location:editAluno.php?ra=".$raNovo);
            }
            else if($confirmacao==1){
                echo $msg;
            }
            else if($confirmacao==2){
                echo "RA e RG já cadastrados.";
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
                <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin.php">Alunos</a></li>
                        <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nome. "</li> "; ?> <!--USAR GET-->
                    </ol>
                </nav>
                <div class="container mt-3">
                    <form method="post">
                        <label for="nome" class="form-label">Nome:</label>
                        <?php echo "<input value='" .$nome. "' class='form-control' type='text' id='nome' name='nome'>"; ?> <!--USAR GET-->
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="ra" class="form-label">RA:</label>
                                <?php echo "<input value='" .$ra. "' class='form-control' type='text' id='ra' name='ra' maxlength='6'>"; ?><!--USAR GET-->
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="rg" class="form-label">RG (somente números):</label>
                                <?php echo "<input value='" .$rg. "' class='form-control' type='text' id='rg' name='rg' maxlength='9'>"; ?>  <!--USAR GET-->
                            </div>
                        </div>
                        <br>
                        <label for="email" class="form-label">Email:</label>
                        <?php echo "<input value='" .$email. "' class='form-control mb-3' type='text' id='email' name='email'>"; ?> <!--USAR GET-->
                        
                        <!--PHP-->

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill text-white"><b>Alterar Dados</b></button>
                        </div>
                        <hr>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="javascript/adminCadastroAluno.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>