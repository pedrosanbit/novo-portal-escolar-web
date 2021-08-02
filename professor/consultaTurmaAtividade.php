<?php
  if($_SERVER["REQUEST_METHOD"] === "POST") {
  	try{
    	include("../conexaoBD.php");

   		if(isset($_POST["codTurma"]) && $_POST["codTurma"]!= ""){
   			if(isset($_POST["codDisciplina"]) && $_POST["codDisciplina"]!= ""){
   				if(isset($_POST["codAtividade"]) && $_POST["codAtividade"]!= ""){
            echo "<script>alert('Turma: ".$_POST["codTurma"]." Disciplina: ".$_POST["codDisciplina"]." Atividade: ".$_POST["codAtividade"]."')</script>";
            $verificacao=$pdo->prepare("select * from AlunoTurmaDisciplinaAtividadeTCC atda where atda.codTurma= :codTurma and atda.codDisciplina= :codDisciplina and atda.codAtividade= :codAtividade;");
            $verificacao->bindParam(":codTurma",$_POST["codTurma"]);
            $verificacao->bindParam(":codDisciplina",$_POST["codDisciplina"]);
            $verificacao->bindParam(":codAtividade",$_POST["codAtividade"]);
            $verificacao->execute();
            $row=$verificacao->fetch();

            if($row<=0){
              $stmt=$pdo->prepare("select a.raAluno from AlunoTurmaTCC a inner join DisciplinaTurmaAtividadeTCC dta where a.codTurma= :codTurma and dta.codDisciplina= :codDisciplina and dta.codAtividade= :codAtividade;");
              $stmt->bindParam(":codTurma",$_POST["codTurma"]);
              $stmt->bindParam(":codDisciplina",$_POST["codDisciplina"]);
   					  $stmt->bindParam(":codAtividade",$_POST["codAtividade"]);
   					  $stmt->execute();
   					  while($row = $stmt->fetch()) {
   						   $inserir = $pdo->prepare("insert into AlunoTurmaDisciplinaAtividadeTCC (raAluno, codTurma, codDisciplina, codAtividade) values(:ra, :turma, :disc, :ativ)");
   						   $inserir->bindParam(":ra", $row["raAluno"]);
   						   $inserir->bindParam(":turma", $_POST["codTurma"]);
   						   $inserir->bindParam(":disc", $_POST["codDisciplina"]);
   						   $inserir->bindParam(":ativ", $_POST["codAtividade"]);
   						   $inserir->execute();
   					    }
            }


            $stmt=$pdo->prepare("select atda.raAluno, a.nomeAluno, atda.nota from AlunoTurmaDisciplinaAtividadeTCC atda inner join AlunosTCC a on atda.raAluno=a.raAluno where atda.codTurma= :codTurma and atda.codDisciplina= :codDisciplina and atda.codAtividade= :codAtividade;");
            $stmt->bindParam(":codTurma",$_POST["codTurma"]);
            $stmt->bindParam(":codDisciplina",$_POST["codDisciplina"]);
            $stmt->bindParam(":codAtividade",$_POST["codAtividade"]);
            $stmt->execute();


            echo "<form method='post'>
            <div class='table-responsive mt-4 mb-4'>
            <table id='tableConsulta' class='table table-sm table-striped table-hover'>
            <thead>
              <th>RA</th>
              <th>Nome</th>
              <th>Nota</th>
            </thead>
            <tbody>";

            while($row=$stmt->fetch()){
              echo "<tr>";
              echo "<td>" . $row['raAluno'] . "</td>";
              echo "<td>" . $row['nomeAluno'] . "</td>";
              if(!isset($row['nota']) || $row['nota']=='null'){
                echo "<td><input class='form-control' type='number' min='0' name='".$row["raAluno"]." step='0.01' max='10'></td>";
              }
              else{
                echo "<td><input class='form-control' type='number' min='0' name='".$row["raAluno"]." value='".$row["nota"]."' step='0.01' max='10'></td>";
              }
              echo "</tr>";
            }
            echo "</tbody></table></div>
            </form>";
   				}
   			}
   		}
      
   	}
   	catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
    finally{
        $pdo=null;
    }
}

