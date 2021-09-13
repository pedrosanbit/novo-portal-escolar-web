<?php
  if($_SERVER["REQUEST_METHOD"] === "POST") {
    include("../conexaoBD.php");
    $comando = "select * from CursosTCC";
                  
    if(isset($_POST["codCurso"]) && (trim($_POST["codCurso"]) != "")) {
      $codCurso = $_POST["codCurso"];
      $comando .= " where codCurso= :codCurso";
    }
    else if(isset($_POST["nomeCurso"]) && (trim($_POST["nomeCurso"]) != "")) {
      $nome = $_POST["nomeCurso"];
      $nome = ucwords(strtolower($nome));
      $nome = "%" . $nome . "%";
      $comando .= " where nomeCurso like :nome";
    }
    else if(isset($_POST["cursoProfessor"]) && $_POST["cursoProfessor"]!="null"){
      $codProfessor=$_POST["cursoProfessor"];
      $comando= "select distinct c.codCurso, c.nomeCurso from CursosTCC c inner join TurmasTCC t on c.codCurso = t.curso inner join LecionaTCC l on t.codTurma = l.codTurma inner join ProfessoresTCC p on p.rfProfessor = l.rfProfessor where p.rfProfessor = :codProfessor";
    }
    $stmt = $pdo->prepare($comando . " order by nomeCurso");
    if(isset($codCurso)) $stmt->bindParam(':codCurso', $codCurso);
    if(isset($nome)) $stmt->bindParam(':nome', $nome);
    if(isset($codProfessor)) $stmt->bindParam(':codProfessor',$codProfessor);
    try{
      echo "<div class='table-responsive mt-4 mb-4'>
              <table id='tableConsulta' class='table table-sm table-striped table-hover'>
              <thead>
                <th>Código</th>
                <th>Nome</th>
                <th>Ações</th>
              </thead>
              <tbody>";
      $stmt->execute();
      while($row = $stmt->fetch()) {
          echo "<tr>";
          echo "<td>" . $row['codCurso'] . "</td>";
          echo "<td>" . $row['nomeCurso'] . "</td>";
          echo "<td class='text-md-start text-center'>" . "<a href='editCurso.php?codCurso=" . $row['codCurso'] . "'><i class='fas fa-edit me-2 ms-md-0 ms-2'></i></a>" . "<i class='fas fa-minus-circle text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirCurso". $row['codCurso'] ."'></i>" . "</td>";
          echo "</tr>";
          echo "<div class='modal fade' id='modalExcluirCurso". $row['codCurso'] ."' tabindex='-1' aria-labelledby='modalExcluirCursoLabel' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content' id='modalContentExcluirCurso'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='modalExcluirCursoLabel'>Remover Aluno</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        Tem certeza que deseja remover ". $row['nomeCurso'] ."?
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                        <form method='post' action='excluirCurso.php'><button name='codCurso' value='". $row['codCurso'] ."' type='submit' class='btn btn-outline-danger'>Remover Curso</button></form>
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