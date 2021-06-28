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