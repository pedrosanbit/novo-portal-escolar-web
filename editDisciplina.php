<?php
	include("conexaoBD.php");
    $stmt = $pdo->prepare("select * from DisciplinasTCC where codDisciplina = :cod");
    $stmt->bindParam(':cod', $_GET["codDisciplina"]);
    $stmt->execute();
    $row=$stmt->rowCount();
    if($row>0){
        $row= $stmt->fetch();
        $nome=$row['nomeDisciplina'];
        $cargaHoraria=$row['cargaHoraria'];
        $cod=$row['codDisciplina'];
    }
    $pdo=null;


    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $codAlterado=0;
        $confirmacao=0;
        $msg;
        include("conexaoBD.php");
        $cargaHorariaNova=$_POST['CH'];
        $nomeNovo=$_POST['nome'];
        $codNovo=$_POST['cod'];
        $stmt = $pdo->prepare("select * from DisciplinasTCC where codDisciplina = :cod");
        $stmt->bindParam(':cod', $_GET["codDisciplina"]);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row==1){
            $row= $stmt->fetch();
            if($cargaHorariaNova!=$row['cargaHoraria'] && $cargaHorariaNova!=null){
                	$stmt= $pdo->prepare("update DisciplinasTCC set cargaHoraria = :CH where codDisciplina =:cod");
                	$stmt->bindParam(':cod', $_GET["codDisciplina"]);
                	$stmt->bindParam(':CH', $cargaHorariaNova);
                	$stmt->execute();
            }
            if($nomeNovo!=$row['nomeDisciplina']){
                    $stmt = $pdo->prepare("select * from DisciplinasTCC where codDisciplina = :cod");
                    $stmt->bindParam(':cod', $codDisciplina);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update DisciplinasTCC set nomeDisciplina = :nomeNovo where codDisciplina= :cod");
                        $stmt->bindParam(':cod',$_GET['codDisciplina']);
                        $stmt->bindParam(':nomeNovo',$nomeNovo);
                        $stmt->execute();
                    }
                    else{
                        $msg= "Nome da disciplina já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($codNovo!=$row['codDisciplina']){
                    $stmt = $pdo->prepare("select * from DisciplinasTCC where codDisciplina= :cod");
                    $stmt->bindParam(':cod', $codNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update DisciplinasTCC set codDisciplina = :codNovo where codDisciplina= :cod");
                        $stmt->bindParam(':cod',$_GET['codDisciplina']);
                        $stmt->bindParam(':codNovo',$codNovo);
                        $stmt->execute();
                        $codAlterado=1;
                    }
                    else{
                        $msg= "Código já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($confirmacao==0){
                	if($codAlterado==0)
                    	header("location:editDisciplina.php?codDisciplina=".$_GET['codDisciplina']);
                	else
                    	header("location:editDisciplina.php?codDisciplina=".$codNovo);
            }
            else if($confirmacao==1){
                	echo $msg;
            }
            else if($confirmacao==2){
                	echo "Código e Nome já cadastrados.";
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
        <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="adminDisciplinas.php">Disciplinas</a></li>
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
                        <label for="rf" class="form-label">Código:</label>
                        <?php echo "<input value='" .$cod. "' class='form-control' type='text' id='cod' name='cod' maxlength='6'>"; ?>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="rg" class="form-label">Carga Horária (qtd de horas aula):</label>
                        <?php echo "<input value='" .$cargaHoraria. "' class='form-control' type='text' id='CH' name='CH' maxlength='9'>"; ?>  
                    </div>
                </div>
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