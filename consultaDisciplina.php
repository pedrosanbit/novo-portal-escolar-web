<?php
  if($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conexaoBD.php");
    $comando = "select * from DisciplinasTCC";

    if(isset($_POST["codDisciplina"]) && (trim($_POST["codDisciplina"]) != "")) {
      $cod = $_POST["codDisciplina"];
      $comando .= " where codDisciplina= :cod";
    }
    else if(isset($_POST["nomeDisciplina"]) && (trim($_POST["nomeDisciplina"]) != "")) {
      $nome = $_POST["nomeDisciplina"];
      $nome = ucwords(strtolower($nome));
      $nome = "%" . $nome . "%";
      $comando .= " where nomeDisciplina like :nome";
    }
    else if(isset($_POST["turmaDisciplina"]) && $_POST["turmaDisciplina"]!="null" && isset($_POST["professorDisciplina"]) && $_POST["professorDisciplina"]!="null"){
      $codturma=$_POST["turmaDisciplina"];
      $rfProfessor=$_POST["professorDisciplina"];
      $comando= "select d.codDisciplina, cargaHoraria, nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina inner join ProfessoresTCC p on p.rfProfessor = l.rfProfessor inner join TurmasTCC t on t.codTurma = l.codTurma where p.rfProfessor = :rfProfessor and t.codTurma = :codTurma";
    }
    else if(isset($_POST["turmaDisciplina"]) && $_POST["turmaDisciplina"]!="null"){
      $codturma=$_POST["turmaDisciplina"];
      $comando= "select d.codDisciplina, cargaHoraria, nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina inner join TurmasTCC t on t.codTurma = l.codTurma where t.codTurma = :codTurma";
    }
    else if(isset($_POST["professorDisciplina"]) && $_POST["professorDisciplina"]!="null"){
      $rfProfessor=$_POST["professorDisciplina"];
      $comando= "select d.codDisciplina, cargaHoraria, nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina inner join ProfessoresTCC p on p.rfProfessor = l.rfProfessor where p.rfProfessor = :rfProfessor";
    }

    $stmt=$pdo->prepare($comando . " order by codDisciplina");
    if(isset($cod)) $stmt->bindParam(':cod', $cod);
    if(isset($nome)) $stmt->bindParam(':nome', $nome);
    if(isset($codturma)) $stmt->bindParam(':codTurma',$codturma);
    if(isset($rfProfessor)) $stmt->bindParam(':rfProfessor',$rfProfessor);
    try{
      echo "<div class='table-responsive mt-4 mb-4'>
              <table id='tableConsulta' class='table table-sm table-striped table-hover'>
              <thead>
                <th>Código da Disciplina</th>
                <th>Nome</th>
                <th>Carga Horária</th>
                <th>Ações</th>
              </thead>
              <tbody>";
      $stmt->execute();
      while($row = $stmt->fetch()) {
          echo "<tr>";
          echo "<td>" . $row['codDisciplina'] . "</td>";
          echo "<td>" . $row['nomeDisciplina'] . "</td>";
          echo "<td>" . $row['cargaHoraria'] . "</td>";
          echo "<td class='text-md-start text-center'>" . "<a href='editDisciplina.php?codDisciplina=" . $row['codDisciplina'] . "'><i class='fas fa-user-edit me-2 ms-md-0 ms-2'></i></a>" . "<i class='fas fa-user-minus text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirDisciplina". $row['codDisciplina'] ."'></i>" . "</td>";
          echo "</tr>";
          echo "<div class='modal fade' id='modalExcluirDisciplina". $row['codDisciplina'] ."' tabindex='-1' aria-labelledby='modalExcluirDisciplinaLabel' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content' id='modalContentExcluirDisciplina'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='modalExcluirDisciplinaLabel'>Remover Disciplina</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        Tem certeza que deseja remover ". $row['nomeDisciplina'] ."?
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                        <form method='post' action='excluirDisciplina.php'>
                        <button id='codDisciplina' name='codDisciplina' value='". $row['codDisciplina'] ."' type='submit' class='btn btn-outline-danger'>Remover disciplina</button>
                        </form>
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