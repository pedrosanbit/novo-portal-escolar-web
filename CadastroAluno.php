<?php
	if ($_SERVER["REQUEST_METHOD"] === 'POST') {
		include("conexaoBD.php");

        try {            
            $ra = $_POST["ra"];
            $nome = $_POST["nome"];
            $rg = $_POST["rg"];
            $email= $_POST["email"];

            if ((trim($ra) == "") || (trim($nome) == "")  || (trim($rg) == "")) {
                echo "<span id='warning'>RA, RG e nome são obrigatórios!</span>";
            } else {
                $stmt = $pdo->prepare("select * from AlunosTCC where raAluno = :ra");
                $stmt->bindParam(':ra', $ra);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows <= 0) {
                    $stmt = $pdo->prepare("insert into AlunosTCC (raAluno, nomeAluno, rgAluno, emailAluno) values(:ra, :nome, :rg, :email)");
                    $stmt->bindParam(':ra', $ra);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':rg', $rg);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    echo "<span id='sucess'>Aluno Cadastrado!</span>";
                } else {
                    echo "<span id='error'>Ra já existente!</span>";
                }
            }

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        $pdo = null;

    }

?>

<html>
<head>
	<title>Cadastro de Alunos</title>
</head>
<body>
	<h2>Cadastro de Alunos</h2>
	<div>
    	<form method="post">

        	RA:<br>
        	<input type="text" size="10" name="ra"><br><br>

        	Nome:<br>
        	<input type="text" size="30" name="nome"><br><br>

        	RG (somente números):<br>
        	<input type="text" size="30" name="rg"><br><br>

        	Email:<br>
        	<input type="text" size="30" name="email"><br><br>
			<br>

        	<input type="submit" value="Cadastrar">

        	<hr>

    	</form>
	</div>
</body>
</html>