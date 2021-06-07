<html>
<?php 
if($_SERVER["REQUEST_METHOD"] === "POST") {
	include("conexaoBD.php");

	try{
		$stmt=$pdo->prepare("DELETE from ProfessoresTCC WHERE rfProfessor= :rf");
		$stmt->bindParam(":rf",$_POST["rf"]);
		$stmt->execute();
		$msg = 1;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: adminProfessores.php?msg=" . $msg);
	}
}
?>
</html>