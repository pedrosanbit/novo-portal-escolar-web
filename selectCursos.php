<?php
  include("conexaoBD.php");
  try{
    $stmt= $pdo->prepare("select * from CursosTCC");
    $stmt->execute();
                        
    echo "<option value='null'></option>";
    while($row= $stmt->fetch()){
      echo "<option value='". $row["codCurso"] ."'>".$row["nomeCurso"]."</option>";
    }
    echo "</select>";       
  }
  catch(PDOException $e){
    echo 'Error: ' . $e->getMessage();
  }
  finally{
    $pdo=null;
  }  
?>