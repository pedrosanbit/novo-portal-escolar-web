<html>
<?php 
if($_SERVER["REQUEST_METHOD"] === "POST") {
	include("conexaoBD.php");

	try{
		$stmt=$pdo->prepare("DELETE from TurmasTCC WHERE codTurma= :codTurma");
		$stmt->bindParam(":codTurma",$_POST["codTurma"]);
		$stmt->execute();
		$msg = 1;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: adminTurmas.php?msg=" . $msg);
	}
}
?>
</html>