<?php
    session_start();
    if(!isset($_SESSION['login']))
        header('location:index.php');
    else if($_SESSION['tipo'] != 'admin')
        header('location:index.php');
    if(!isset($_GET['codTurma']))
        header('location:adminTurmas.php');

    if($_SERVER["REQUEST_METHOD"] !== "POST") {
        if(isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            if($msg == 1) {
                echo "<script type='text/javascript'>
                    alert('Disciplina removida.');
                </script>";
            }
            else if($msg == 2) {
                echo "<script type='text/javascript'>
                    alert('Aluno removido.');
                </script>";
            }
            $msg = 0;
        }
    }
    include("conexaoBD.php");
    $stmt = $pdo->prepare("select * from TurmasTCC where codTurma = :codTurma");
    $stmt->bindParam(':codTurma', $_GET["codTurma"]);
    $stmt->execute();
    $row=$stmt->rowCount();

    if($row>0){
        $row= $stmt->fetch();
        $nome=$row['nomeTurma'];
        $codTurma=$row['codTurma'];
        $curso=$row['curso'];
        $periodo=$row['periodo'];
    }
    else {
        header('location:adminTurmas.php');
    }
    $pdo=null;

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $codTurmaAlterado=0;
        $confirmacao=0;
        $msg;
        include("conexaoBD.php");
        $codTurmaNovo=$_POST['codTurma'];
        $nomeNovo=$_POST['nome'];
        $cursoNovo=$_POST['curso'];
        $periodoNovo=$_POST['periodo'];

        $stmt = $pdo->prepare("select * from TurmasTCC where codTurma = :codTurma");
        $stmt->bindParam(':codTurma', $_GET["codTurma"]);
        $stmt->execute();
        $row=$stmt->rowCount();

        if($row==1){
            $row= $stmt->fetch();
            if($nomeNovo!=$row['nomeTurma']){
                $stmt= $pdo->prepare("update TurmasTCC set nomeTurma = :nomeNovo where codTurma= :codTurma");
                $stmt->bindParam(':codTurma', $_GET["codTurma"]);
                $stmt->bindParam(':nomeNovo', $nomeNovo);
                $stmt->execute();
            }
            if($periodoNovo!=$row['periodo']){
                $stmt= $pdo->prepare("update TurmasTCC set periodo = :periodoNovo where codTurma= :codTurma");
                $stmt->bindParam(':codTurma', $_GET["codTurma"]);
                $stmt->bindParam(':periodoNovo', $periodoNovo);
                $stmt->execute();
            }
            if($cursoNovo!=$row['curso']){
                $stmt= $pdo->prepare("update TurmasTCC set curso = :cursoNovo where codTurma= :codTurma");
                $stmt->bindParam(':codTurma', $_GET["codTurma"]);
                $stmt->bindParam(':cursoNovo', $cursoNovo);
                $stmt->execute();
            }
            if($codTurmaNovo!=$row['codTurma']){
                    $stmt = $pdo->prepare("select * from TurmasTCC where codTurma = :codTurma");
                    $stmt->bindParam(':codTurma', $codTurmaNovo);
                    $stmt->execute();
                    $rows = $stmt->rowCount();
                    if ($rows <= 0) {
                        $stmt= $pdo->prepare("update TurmasTCC set codTurma = :codTurmaNovo where codTurma= :codTurma");
                        $stmt->bindParam(':codTurma',$_GET['codTurma']);
                        $stmt->bindParam(':codTurmaNovo',$codTurmaNovo);
                        $stmt->execute();
                        $codTurmaAlterado=1;
                    }
                    else{
                        $msg= "Código de Turma já cadastrado.";
                        $confirmacao+=1;
                    }
            }
            if($confirmacao==0){
                if($codTurmaAlterado==0)
                    header("location:editTurma.php?codTurma=".$_GET['codTurma']);
                else
                    header("location:editTurma.php?codTurma=".$codTurmaNovo);
            }
            /*else if($confirmacao==1){
                echo $msg;
            }
            else if($confirmacao==2){
                echo "RA e RG já cadastrados.";
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
        <nav>
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
        <nav class="ms-5 mt-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="adminTurmas.php">Turmas</a></li>
                <?php echo "<li class='breadcrumb-item active' aria-current='page'>". $nome. "</li> "; ?> 
            </ol>
        </nav>
        <div class="container mt-3">
            <form method="post">
                <h4 class="mb-3">Dados Gerais</h4>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="nome" class="form-label">Nome da Turma:</label>
                        <?php echo "<input value='" .$nome. "' class='form-control' type='text' id='nome' name='nome'>"; ?>
                        <br>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="codTurma" class="form-label">Código da Turma:</label>
                        <?php echo "<input value='" .$codTurma. "' class='form-control' type='text' id='codTurma' name='codTurma'>"; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 col-sm-12">
                        <label for="curso" class="form-label">Curso:</label>
                        <select class="form-select" id="curso" name="curso" aria-label='Default select example'>
                            <?php
                                include("conexaoBD.php");
                                try{
                                    $stmt= $pdo->prepare("select * from CursosTCC where codCurso = :curso");
                                    $stmt->bindParam(':curso',$curso);
                                    $stmt->execute();
                                    if($row= $stmt->fetch())
                                        echo "<option value='". $row["codCurso"] ."'>".$row["nomeCurso"]."</option>";

                                    $stmt= $pdo->prepare("select * from CursosTCC where codCurso != :curso");
                                    $stmt->bindParam(':curso',$curso);
                                    $stmt->execute();
                                    while($row= $stmt->fetch())
                                        echo "<option value='". $row["codCurso"] ."'>".$row["nomeCurso"]."</option>";
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
                        <label for="periodo" class="form-label">Período:</label>
                        <?php echo "<input value='" .$periodo. "' class='form-control' type='text' id='periodo' name='periodo' maxlength='4'>"; ?> 
                    </div>
                </div>
                <?php
                    if(isset($confirmacao)) {
                        if($confirmacao==1)
                            echo "<span class='text-danger'>" . $msg . "</span>";
                    }
                ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill text-white"><b><i class='fas fa-edit'></i> Alterar Dados</b></button>
                </div>
            </form>
            <hr>
            <h4>Disciplinas e Professores</h4>
            <?php
                include("conexaoBD.php");
                try {
                    $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina, p.rfProfessor, p.nomeProfessor from ProfessoresTCC p inner join LecionaTCC l on p.rfProfessor = l.rfProfessor inner join DisciplinasTCC d on l.codDisciplina = d.codDisciplina inner join TurmasTCC t on t.codTurma = l.codTurma where t.codTurma = :codTurma");
                    $stmt->bindParam(':codTurma',$_GET['codTurma']);
                    $stmt->execute();

                    echo "<div class='table-responsive mt-4 mb-2'>
                            <table id='tableDisciplinas' class='table table-sm table-striped table-hover'>
                                <thead>
                                    <th>Disciplina</th>
                                    <th>Professor</th>
                                    <th>Ações</th>
                                </thead>
                            <tbody>";

                    while($row = $stmt->fetch()) {
                        echo "<tr>";
                        echo "<td>" . $row['nomeDisciplina'] . "</td>";
                        echo "<td>" . $row['nomeProfessor'] . "</td>";
                        echo "<td class='text-md-start text-center'>" . "<a href='editDisciplinaTurma.php?codTurma=" . $_GET['codTurma'] . "&codDisciplina=" . $row['codDisciplina'] . "'><i class='fas fa-edit' me-2 ms-md-0 ms-2'></i></a>" . "<i class='fas fa-minus-circle text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirDisciplinaTurma". $row['codDisciplina'] ."'></i>" . "</td>";
                        echo "</tr>";
                        echo "<div class='modal fade' id='modalExcluirDisciplinaTurma". $row['codDisciplina'] ."' tabindex='-1' aria-labelledby='modalExcluirDisciplinaTurmaLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content' id='modalContentExcluirDisciplinaTurma'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='modalExcluirDisciplinaTurmaLabel'>Remover Disciplina</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            Tem certeza que deseja remover ". $row['nomeDisciplina'] ." da turma?
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                            <form method='post' action='excluirDisciplinaTurma.php'>
                                                <button name='codTurma' value='". $_GET['codTurma'] . "|" . $row['codDisciplina'] . "|". $row['rfProfessor'] ."' type='submit' class='btn btn-outline-danger'>Remover disciplina</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                    echo "</tbody></table></div>";
                }
                catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                finally {
                    $pdo = null;
                }
            ?>
            <div class="text-center">
                <?php echo "<a href='addDisciplinaTurma.php?codTurma=".$_GET['codTurma']."' class='btn btn-primary rounded-pill text-white' role='button'>";?>
                    <b><i class="fas fa-plus-square"></i> Adicionar Disciplinas</b>
                </a>
            </div>
            <hr>
            <h4>Alunos</h4>
            <?php
                include("conexaoBD.php");
                try {
                    $stmt = $pdo->prepare("select a.raAluno, a.nomeAluno from AlunosTCC a inner join AlunoTurmaTCC alt on a.raAluno = alt.raAluno inner join TurmasTCC t on t.codTurma = alt.codTurma where t.codTurma = :codTurma");
                    $stmt->bindParam(':codTurma',$_GET['codTurma']);
                    $stmt->execute();

                    echo "<div class='table-responsive mt-4 mb-2'>
                            <table id='tableAlunos' class='table table-sm table-striped table-hover'>
                                <thead>
                                    <th>RA</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </thead>
                            <tbody>";

                    while($row = $stmt->fetch()) {
                        echo "<tr>";
                        echo "<td>" . $row['raAluno'] . "</td>";
                        echo "<td>" . $row['nomeAluno'] . "</td>";
                        echo "<td class='text-md-start text-center'>" . "<i class='fas fa-minus-circle text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirAlunoTurma". $row['raAluno'] ."'></i>" . "</td>";
                        echo "</tr>";
                        echo "<div class='modal fade' id='modalExcluirAlunoTurma". $row['raAluno'] ."' tabindex='-1' aria-labelledby='modalExcluirAlunoTurmaLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content' id='modalContentExcluirAlunoTurma'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='modalExcluirAlunoTurmaLabel'>Remover Aluno</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            Tem certeza que deseja remover ". $row['nomeAluno'] ." da turma?
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                            <form method='post' action='excluirAlunoTurma.php'>
                                                <button name='codTurma' value='". $_GET['codTurma'] . "|" . $row['raAluno'] ."' type='submit' class='btn btn-outline-danger'>Remover aluno</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                    echo "</tbody></table></div>";
                }
                catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                finally {
                    $pdo = null;
                }
            ?>
            <div class="text-center mb-4">
                <?php echo "<a href='addAlunoTurma.php?codTurma=".$_GET['codTurma']."' class='btn btn-primary rounded-pill text-white' role='button'>";?>
                    <b><i class="fas fa-user-plus"></i> Adicionar Alunos</b>
                </a>
            </div>
        </div>
        <script type="text/javascript" src="javascript/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    </body>
</html>