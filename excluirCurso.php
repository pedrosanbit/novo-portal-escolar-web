<html>
<?php 
if($_SERVER["REQUEST_METHOD"] === "POST") {
	include("conexaoBD.php");

	try{
		$stmt=$pdo->prepare("DELETE from CursosTCC WHERE codCurso= :codCurso");
		$stmt->bindParam(":codCurso",$_POST["codCurso"]);
		$stmt->execute();
		$msg = 1;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	finally{
		$pdo=null;
		header("location: adminCursos.php?msg=" . $msg);
	}
}
?>
</html>