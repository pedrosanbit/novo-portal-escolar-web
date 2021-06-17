<html>
<?php 
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