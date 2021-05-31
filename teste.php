
<select class="form-select" id="turmaAluno" name="turmaAluno" aria-label="Default select example">
<?php
                      include("conexaoBD.php");
                      try{
                        $stmt= $pdo->prepare("select * from TurmasTCC");
                        $stmmt->execute();

                        while($row= $stmt->fetch()){
                          echo "<option value='". $row["nomeTurma"] ."'>".$row["nomeTurma"]."</option>";
                        }        
                      }
                      catch(PDOException $e){
                        echo 'Error: ' . $e->getMessage();
                      }
                      finally{
                        $pdo=null;
                      }  
?>
</select>