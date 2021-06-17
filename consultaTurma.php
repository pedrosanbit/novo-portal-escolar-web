<?php
  if($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conexaoBD.php");
    $comando = "select * from TurmasTCC";
                  
    if(isset($_POST["codTurma"]) && (trim($_POST["codTurma"]) != "")) {
      $codTurma = $_POST["codTurma"];
      $comando .= " where codTurma= :codTurma";
    }
    else if(isset($_POST["nomeTurma"]) && (trim($_POST["nomeTurma"]) != "")) {
      $nome = $_POST["nomeTurma"];
      $nome = ucwords(strtolower($nome));
      $nome = "%" . $nome . "%";
      $comando .= " where nomeTurma like :nome";
    }
    else if(isset($_POST["curso"]) && $_POST["curso"]!="null") {
      $curso=$_POST["curso"];
      $comando= "select * from TurmasTCC where curso = :curso";
    }
    else if(isset($_POST["periodo"]) && $_POST["periodo"]!="null"){
      $periodo=$_POST["periodo"];
      $comando= "select * from TurmasTCC where periodo = :periodo";
    }
    $stmt = $pdo->prepare($comando . " order by nomeTurma");
    if(isset($codTurma)) $stmt->bindParam(':codTurma', $codTurma);
    if(isset($nome)) $stmt->bindParam(':nome', $nome);
    if(isset($curso)) $stmt->bindParam(':curso',$curso);
    if(isset($periodo)) $stmt->bindParam(':periodo',$periodo);
    try{
      echo "<div class='table-responsive mt-4 mb-4'>
              <table id='tableConsulta' class='table table-sm table-striped table-hover'>
              <thead>
                <th>Código</th>
                <th>Nome</th>
                <th>Curso</th>
                <th>Período</th>
                <th>Ações</th>
              </thead>
              <tbody>";
      $stmt->execute();
      while($row = $stmt->fetch()) {
          echo "<tr>";
          echo "<td>" . $row['codTurma'] . "</td>";
          echo "<td>" . $row['nomeTurma'] . "</td>";
          echo "<td>" . $row['curso'] . "</td>";
          echo "<td>" . $row['periodo'] . "</td>";
          echo "<td class='text-md-start text-center'>" . "<a href='editTurma.php?codTurma=" . $row['codTurma'] . "'><i class='fas fa-edit' me-2 ms-md-0 ms-2'></i></a>" . "<i class='fas fa-minus-circle text-danger ms-md-2' data-bs-toggle='modal' data-bs-target='#modalExcluirTurma". $row['codTurma'] ."'></i>" . "</td>";
          echo "</tr>";
          echo "<div class='modal fade' id='modalExcluirTurma". $row['codTurma'] ."' tabindex='-1' aria-labelledby='modalExcluirTurmaLabel' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content' id='modalContentExcluirTurma'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='modalExcluirTurmaLabel'>Remover Turma</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        Tem certeza que deseja remover ". $row['nomeTurma'] ."?
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                        <form method='post' action='excluirTurma.php'>
                          <button name='codTurma' value='". $row['codTurma'] ."' type='submit' class='btn btn-outline-danger'>Remover turma</button>
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