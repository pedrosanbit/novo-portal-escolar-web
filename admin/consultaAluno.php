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
              <th>Ações</th>
            </thead>
            <tbody>";
      $stmt->execute();
      while($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $row['raAluno'] . "</td>";
        echo "<td>" . $row['nomeAluno'] . "</td>";
        echo "<td>" . $row['rgAluno'] . "</td>";
        echo "<td class='text-md-start text-center'>" . "<a href='editAluno.php?ra=" . $row['raAluno'] . "'><i class='fas fa-user-edit me-2 ms-md-0 ms-2'></i></a>" . "<i class='fas fa-user-minus text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirAluno". $row['raAluno'] ."'></i>" . "</td>";
        echo "</tr>";
        echo "<div class='modal fade' id='modalExcluirAluno". $row['raAluno'] ."' tabindex='-1' aria-labelledby='modalExcluirAlunoLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content' id='modalContentExcluirAluno'>
                    <div class='modal-header'>
                      <h5 class='modal-title' id='modalExcluirAlunoLabel'>Remover Aluno</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                      Tem certeza que deseja remover ". $row['nomeAluno'] ."?
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                      <form method='post' action='excluirAluno.php'><button name='ra' value='". $row['raAluno'] ."' type='submit' class='btn btn-outline-danger'>Remover aluno</button></form>
                    </div>
                  </div>
                </div>
              </div>";
      }
      echo "</tbody></table></div>";
    }
    catch(PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
    finally {
      $pdo = null;
    }
  }
?>