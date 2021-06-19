<html>
<?php
session_start();
if(!isset($_SESSION['login']))
    header('location:index.php');
else if($_SESSION['tipo'] != 'admin')
    header('location:index.php');
    
if($_SERVER["REQUEST_METHOD"] === "POST") {
	include("conexaoBD.php");

	try{
		$stmt=$pdo->prepare("DELETE from DisciplinasTCC WHERE codDisciplina= :cod");
		$stmt->bindParam(":cod",$_POST["codDisciplina"]);
		$stmt->execute();
		$msg = 1;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: adminDisciplinas.php?msg=" . $msg);
	}
}
?>
</html>