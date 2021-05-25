<html>
<?php 
if($_SERVER["REQUEST_METHOD"] === "POST") {
	include("conexaoBD.php");

	try{
		$stmt=$pdo->prepare("DELETE from AlunosTCC WHERE raAluno= :ra");
		$stmt->bindParam(":ra",$_POST["ra"]);
		$stmt->execute();
		$msg = 1;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: admin.php?msg=" . $msg);
	}
}
?>
</html>