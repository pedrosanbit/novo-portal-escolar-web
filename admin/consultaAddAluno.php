<?php
  if($_SERVER["REQUEST_METHOD"] === "POST") {
    include("../conexaoBD.php");
    $comando = "select * from AlunosTCC";
                  
    if(isset($_POST["raAluno"]) && (trim($_POST["raAluno"]) != "")) {
      $ra = $_POST["raAluno"];
      $comando .= " where raAluno= :ra";
    }
    else if(isset($_POST["nomeAluno"]) && (trim($_POST["nomeAluno"]) != "")) {
      $nome = $_POST["nomeAluno"];
      $nome = ucwords(strtolower($nome));
      $nome = "%" . $nome . "%";
      $comando .= " where nomeAluno like :nome";
    }
    else if(isset($_POST["turmaAluno"]) && $_POST["turmaAluno"]!="null"){
      $codturma=$_POST["turmaAluno"];
      $comando= "select a.raAluno, rgAluno, nomeAluno from AlunosTCC a inner join AlunoTurmaTCC b on a.raAluno = b.raAluno inner join TurmasTCC t on t.codTurma = b.codTurma where t.codTurma = :codTurma";
    }

    if(isset($_POST["ordemConsulta"])){
      $ordem = $_POST["ordemConsulta"];
      switch($ordem) {
        case 'AlfAZ':
          $stmt = $pdo->prepare($comando . " order by nomeAluno asc");
          if(isset($ra)) $stmt->bindParam(':ra', $ra);
          if(isset($nome)) $stmt->bindParam(':nome', $nome);
          if(isset($codturma)) $stmt->bindParam(':codTurma',$codturma);
          imprimeTabela($stmt);
          break;
        case 'AlfZA' :
          $stmt = $pdo->prepare($comando . " order by nomeAluno desc");
          if(isset($ra)) $stmt->bindParam(':ra', $ra);
          if(isset($nome)) $stmt->bindParam(':nome', $nome);
          if(isset($codturma)) $stmt->bindParam(':codTurma',$codturma);
          imprimeTabela($stmt);
          break;
        case 'RAcresc':
          $stmt = $pdo->prepare($comando . " order by raAluno asc");
          if(isset($ra)) $stmt->bindParam(':ra', $ra);
          if(isset($nome)) $stmt->bindParam(':nome', $nome);
          if(isset($codturma)) $stmt->bindParam(':codTurma',$codturma);
          imprimeTabela($stmt);
          break;
        case 'RAdecresc':
          $stmt = $pdo->prepare($comando . " order by raAluno desc");
          if(isset($ra)) $stmt->bindParam(':ra', $ra);
          if(isset($nome)) $stmt->bindParam(':nome', $nome);
          if(isset($codturma)) $stmt->bindParam(':codTurma',$codturma);
          imprimeTabela($stmt);
          break;
      }
    }
  }

  function imprimeTabela($var) {
    $stmt = $var;
    try {
      echo "<div class='table-responsive mt-4 mb-4'>
            <table id='tableConsulta' class='table table-sm table-striped table-hover'>
            <thead>
              <th>RA</th>
              <th>Nome</th>
              <th>RG</th>
              <th class='text-center'>Selecionar</th>
            </thead>
            <tbody>";
      $stmt->execute();
      while($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $row['raAluno'] . "</td>";
        echo "<td>" . $row['nomeAluno'] . "</td>";
        echo "<td>" . $row['rgAluno'] . "</td>";
        echo "<td>" . "<div class='form-check d-flex justify-content-center'>
                        <input class='form-check-input' type='checkbox' name='alunos[]' value='".$row['raAluno']."' id='flexCheckDefault'>
                      </div>" . "</td>";
        echo "</tr>";
      }
      echo "</tbody></table></div>";
      echo "<div class='text-center mt-4'>
          <button type='submit' class='btn btn-primary rounded-pill text-white w-25'><b>Adicionar</b></button>
        </div>";
    }
    catch(PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
    finally {
      $pdo = null;
    }
  }
?>