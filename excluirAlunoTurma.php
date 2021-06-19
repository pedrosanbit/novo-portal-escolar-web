<html>
<?php
session_start();
if(!isset($_SESSION['login']))
    header('location:index.php');
else if($_SESSION['tipo'] != 'admin')
    header('location:index.php');
    
if($_SERVER["REQUEST_METHOD"] === "POST") {
	$post = explode("|",$_POST['codTurma']);
	$codTurma = $post[0];
	$raAluno = $post[1];
	include("conexaoBD.php");
	try{
		$stmt=$pdo->prepare("DELETE from AlunoTurmaTCC WHERE codTurma= :codTurma AND raAluno = :raAluno");
		$stmt->bindParam(":codTurma",$codTurma);
		$stmt->bindParam(":raAluno",$raAluno);
		$stmt->execute();
		$msg = 2;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: editTurma.php?codTurma=".$codTurma."&msg=" . $msg);
	}
}
?>
</html>