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
	$codDisciplina = $post[1];
	$rfProfessor = $post[2];
	include("conexaoBD.php");
	try{
		$stmt=$pdo->prepare("DELETE from LecionaTCC WHERE codTurma= :codTurma AND codDisciplina = :codDisciplina and rfProfessor = :rfProfessor");
		$stmt->bindParam(":codTurma",$codTurma);
		$stmt->bindParam(":codDisciplina",$codDisciplina);
		$stmt->bindParam(":rfProfessor",$rfProfessor);
		$stmt->execute();
		$msg = 1;
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