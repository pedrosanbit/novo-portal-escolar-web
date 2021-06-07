<?php
  if($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conexaoBD.php");
    $comando = "select * from ProfessoresTCC";
                  
    if(isset($_POST["rfProfessor"]) && (trim($_POST["rfProfessor"]) != "")) {
      $rf = $_POST["rfProfessor"];
      $comando .= " where rfProfessor= :rf";
    }
    else if(isset($_POST["nomeProfessor"]) && (trim($_POST["nomeProfessor"]) != "")) {
      $nome = $_POST["nomeProfessor"];
      $nome = ucwords(strtolower($nome));
      $nome = "%" . $nome . "%";
      $comando .= " where nomeProfessor like :nome";
    }
    else if(isset($_POST["turmaProfessor"]) && $_POST["turmaProfessor"]!="null"){
      $codturma=$_POST["turmaProfessor"];
      $comando= "select p.rfProfessor, rgProfessor, nomeProfessor from ProfessoresTCC p inner join LecionaTCC l on p.rfProfessor = l.rfProfessor inner join TurmasTCC t on t.codTurma = l.codTurma where t.codTurma = :codTurma";
    }
    else if(isset($_POST["disciplinaProfessor"]) && $_POST["disciplinaProfessor"]!="null"){
      $codDisciplina=$_POST["disciplinaProfessor"];
      $comando= "select p.rfProfessor, rgProfessor, nomeProfessor from ProfessoresTCC p inner join LecionaTCC l on p.rfProfessor = l.rfProfessor inner join DisciplinasTCC d on l.codDisciplina = d.codDisciplina where d.codDisciplina = :codDisciplina";
    }
    $stmt = $pdo->prepare($comando . " order by nomeProfessor");
    if(isset($rf)) $stmt->bindParam(':rf', $rf);
    if(isset($nome)) $stmt->bindParam(':nome', $nome);
    if(isset($codturma)) $stmt->bindParam(':codTurma',$codturma);
    if(isset($codDisciplina)) $stmt->bindParam(':codDisciplina',$codDisciplina);
    try{
      echo "<div class='table-responsive mt-4 mb-4'>
              <table id='tableConsulta' class='table table-sm table-striped table-hover'>
              <thead>
                <th>RF</th>
                <th>Nome</th>
                <th>RG</th>
                <th>Ações</th>
              </thead>
              <tbody>";
      $stmt->execute();
      while($row = $stmt->fetch()) {
          echo "<tr>";
          echo "<td>" . $row['rfProfessor'] . "</td>";
          echo "<td>" . $row['nomeProfessor'] . "</td>";
          echo "<td>" . $row['rgProfessor'] . "</td>";
          echo "<td class='text-md-start text-center'>" . "<a href='editProfessor.php?rf=" . $row['rfProfessor'] . "'><i class='fas fa-user-edit me-2 ms-md-0 ms-2'></i></a>" . "<i class='fas fa-user-minus text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirProfessor". $row['rfProfessor'] ."'></i>" . "</td>";
          echo "</tr>";
          echo "<div class='modal fade' id='modalExcluirProfessor". $row['rfProfessor'] ."' tabindex='-1' aria-labelledby='modalExcluirProfessorLabel' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content' id='modalContentExcluirProfessor'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='modalExcluirProfessorLabel'>Remover Pofessor</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        Tem certeza que deseja remover ". $row['nomeProfessor'] ."?
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                        <form method='post' action='excluirProfessor.php'><button name='rf' value='". $row['rfProfessor'] ."' type='submit' class='btn btn-outline-danger'>Remover professor</button></form>
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