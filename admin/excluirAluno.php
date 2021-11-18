<html>
<?php 
session_start();
if(!isset($_SESSION['login']))
    header('location:../index.php');
else if($_SESSION['tipo'] != 'admin')
    header('location:../index.php');

if($_SERVER["REQUEST_METHOD"] === "POST") {
	include("../conexaoBD.php");

	try{
		$stmt=$pdo->prepare("DELETE from AlunosTCC WHERE raAluno= :ra");
		$stmt->bindParam(":ra",$_POST["ra"]);
		$stmt->execute();

		$stmt=$pdo->prepare("DELETE from UsuariosTCC where usuario = :ra and tipo = 'aluno'");
		$stmt->bindParam(":ra",$_POST["ra"]);
		$stmt->execute();
		
		$msg = 1;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: adminAlunos.php?msg=" . $msg);
	}
}
?>
</html>