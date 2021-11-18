<?php
  include("../conexaoBD.php");
  try {
    echo "<div class='table-responsive mt-4'>
            <table id='tableConsulta' class='table table-sm table-striped table-hover'>
              <thead>
                <th></th>
                <th colspan='5'>1º Semestre</th>
                <th colspan='5'>2º Semestre</th>
                <th colspan='5'>Resultado Final</th>
              </thead>
              <tbody>
                <tr>
                  <th>Disciplina</th>
                  <th>MS</th>
                  <th>MT</th>
                  <th>AD</th>
                  <th>F</th>
                  <th>%F</th>
                  <th>MS</th>
                  <th>MT</th>
                  <th>AD</th>
                  <th>F</th>
                  <th>%F</th>
                  <th>MA</th>
                  <th>Rec.</th>
                  <th>%F</th>
                  <th>MF</th>
                  <th>Situação</th>
                </tr>";
    $stmt = $pdo->prepare("select d.codDisciplina, d.nomeDisciplina from DisciplinasTCC d inner join LecionaTCC l on d.codDisciplina = l.codDisciplina inner join TurmasTCC t on l.codTurma = t.codTurma where t.codTurma = :codTurma");
    $stmt->bindParam(":codTurma", $_GET["turma"]);
    $stmt->execute();
    while($row = $stmt->fetch()) {
      echo "<tr>";
      echo "<td>" . $row["nomeDisciplina"] . "</td>";

      //Primeiro Semestre
      $stmt2 = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 1");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisciplina", $row["codDisciplina"]);
      $stmt2->execute();
      $ms1 = null;
      $pesos1 = null;
      $mt1 = null;
      $pesot1 = null;
      while($row2 = $stmt2->fetch()) {
        if($row2["nota"] != null) {
          $ms1 += $row2["nota"] * $row2["peso"];
          $pesos1 += $row2["peso"];
        }
        $stmt3 = $pdo->prepare("select a.codAtividade, avg(atda.nota) from AlunoTurmaDisciplinaAtividadeTCC atda inner join AtividadesTCC a on atda.codAtividade = a.codAtividade where a.codAtividade = :codAtividade and a.etapa = 1 group by a.codAtividade;");
        $stmt3->bindParam(":codAtividade", $row2["codAtividade"]);
        $stmt3->execute();
        if($stmt3->rowCount() > 0) {
          $row3 = $stmt3->fetch();
          if($row3["avg(atda.nota)"] != null) {
            $mt1 += $row3["avg(atda.nota)"] * $row2["peso"];
            $pesot1 += $row2["peso"];
          }
        }
      }
      if($pesos1 != 0) $ms1 = $ms1/$pesos1;
      $stmt2 = $pdo->prepare("select distinct adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 1 and a.rec = 1");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisciplina", $row["codDisciplina"]);
      $stmt2->execute();
      if($stmt2->rowCount() > 0) {
        $row2 = $stmt2->fetch();
        if($row2["nota"] != null) {
          if($row2["nota"] > $ms1)
            $ms1 = ($ms1 + $row2["nota"])/2;
        }
      }
      if($pesot1 != 0) $mt1 = $mt1/$pesot1;
      if($ms1 != null)
        echo "<td>" . number_format(bcdiv($ms1, 1, 1), 1, ",") . "</td>";
      else
        echo "<td></td>";
      if($mt1 != null)
        echo "<td>" . number_format(bcdiv($mt1, 1, 1), 1, ",") . "</td>";
      else
        echo "<td></td>";

      $stmt2 = $pdo->prepare("select periodo from TurmasTCC where codTurma = :codTurma");
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->execute();
      $row2 = $stmt2->fetch();
      $periodo = $row2['periodo'];

      $meiodoano = $periodo . "-07-15";
      $stmt2 = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma and codDisc = :codDisc and data < :data");
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisc", $row["codDisciplina"]);
      $stmt2->bindParam(":data", $meiodoano);
      $stmt2->execute();
      $numAulas = $stmt2->rowCount();
      echo "<td>".$numAulas."</td>";

      $stmt2 = $pdo->prepare("select * from PresencasTCC where raAluno = :raAluno and codTurma = :codTurma and codDisciplina = :codDisc and data < :data and presenca = 0");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisc", $row["codDisciplina"]);
      $stmt2->bindParam(":data", $meiodoano);
      $stmt2->execute();
      $numFaltas = $stmt2->rowCount();
      $porcentagem = $numAulas != 0 ? (100 * $numFaltas)/$numAulas : 0;
      echo "<td>".$numFaltas."</td>";
      echo "<td>".number_format(bcdiv($porcentagem, 1, 1), 0)."%</td>";

      //Segundo Semestre
      $stmt2 = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 2");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisciplina", $row["codDisciplina"]);
      $stmt2->execute();
      $ms2 = null;
      $pesos2 = null;
      $mt2 = null;
      $pesot2 = null;
      while($row2 = $stmt2->fetch()) {
        if($row2["nota"] != null) {
          $ms2 += $row2["nota"] * $row2["peso"];
          $pesos2 += $row2["peso"];
        }
        $stmt3 = $pdo->prepare("select a.codAtividade, avg(atda.nota) from AlunoTurmaDisciplinaAtividadeTCC atda inner join AtividadesTCC a on atda.codAtividade = a.codAtividade where a.codAtividade = :codAtividade and a.etapa = 2 group by a.codAtividade;");
        $stmt3->bindParam(":codAtividade", $row2["codAtividade"]);
        $stmt3->execute();
        if($stmt3->rowCount() > 0) {
          $row3 = $stmt3->fetch();
          if($row3["avg(atda.nota)"] != null) {
            $mt2 += $row3["avg(atda.nota)"] * $row2["peso"];
            $pesot2 += $row2["peso"];
          }
        }
      }
      if($pesos2 != 0) $ms2 = $ms2/$pesos2;
      if($pesot2 != 0) $mt2 = $mt2/$pesot2;
      if($ms2 != null)
        echo "<td>" . number_format(bcdiv($ms2, 1, 1), 1, ",") . "</td>";
      else
        echo "<td></td>";
      if($mt2 != null)
        echo "<td>" . number_format(bcdiv($mt2, 1, 1), 1, ",") . "</td>";
      else
        echo "<td></td>";

      $stmt2 = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma and codDisc = :codDisc and data > :data");
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisc", $row["codDisciplina"]);
      $stmt2->bindParam(":data", $meiodoano);
      $stmt2->execute();
      $numAulas = $stmt2->rowCount();
      echo "<td>".$numAulas."</td>";

      $stmt2 = $pdo->prepare("select * from PresencasTCC where raAluno = :raAluno and codTurma = :codTurma and codDisciplina = :codDisc and data > :data and presenca = 0");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisc", $row["codDisciplina"]);
      $stmt2->bindParam(":data", $meiodoano);
      $stmt2->execute();
      $numFaltas = $stmt2->rowCount();
      $porcentagem = $numAulas != 0 ? (100 * $numFaltas)/$numAulas : 0;
      echo "<td>".$numFaltas."</td>";
      echo "<td>".number_format(bcdiv($porcentagem, 1, 1), 0)."%</td>";

      //Resultado Final
      if($ms1 != null && $ms2 != null) {
        echo "<td>" . number_format(bcdiv(($ms1+$ms2)/2, 1, 1), 1, ",") . "</td>";
      }
      else
        echo "<td></td>";
      $rec = null;
      $stmt2 = $pdo->prepare("select distinct a.codAtividade, a.descricao, a.data, a.peso, adta.nota from AlunoTurmaDisciplinaAtividadeTCC adta inner join AlunosTCC al on adta.raAluno = al.raAluno inner join TurmasTCC t on adta.codTurma = t.codTurma inner join DisciplinasTCC d on adta.codDisciplina = d.codDisciplina inner join AtividadesTCC a on adta.codAtividade = a.codAtividade where adta.raAluno = :raAluno and adta.codTurma = :codTurma and adta.codDisciplina = :codDisciplina and a.etapa = 2 and a.rec = 1");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisciplina", $row["codDisciplina"]);
      $stmt2->execute();
      if($stmt2->rowCount() > 0) {
        $row2 = $stmt2->fetch();
        if($row2["nota"] != null) {
          $rec = $row2["nota"];
          echo "<td>" . number_format(bcdiv($row2["nota"], 1, 1), 1, ",") . "</td>";
        }
        else
          echo "<td></td>";
      }
      else
        echo "<td></td>";

      $stmt2 = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma and codDisc = :codDisc");
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisc", $row["codDisciplina"]);
      $stmt2->execute();
      $numAulas = $stmt2->rowCount();
      $stmt2 = $pdo->prepare("select * from PresencasTCC where raAluno = :raAluno and codTurma = :codTurma and codDisciplina = :codDisc and presenca = 0");
      $stmt2->bindParam(":raAluno", $_SESSION["login"]);
      $stmt2->bindParam(":codTurma", $_GET["turma"]);
      $stmt2->bindParam(":codDisc", $row["codDisciplina"]);
      $stmt2->execute();
      $numFaltas = $stmt2->rowCount();
      $porcentagem = $numAulas != 0 ? (100 * $numFaltas)/$numAulas : 0;
      echo "<td>".number_format(bcdiv($porcentagem, 1, 1), 0)."%</td>";

      if($ms1 != null && $ms2 != null) {
        if($rec != null) {
          if($rec > ($ms1+$ms2)/2)
            echo "<td>" . number_format(bcdiv(((($ms1+$ms2)/2) + $rec)/2, 1, 1), 1, ",") . "</td>";
          else
            echo "<td>" . number_format(bcdiv(($ms1+$ms2)/2, 1, 1), 1, ",") . "</td>";
        }
        else
          echo "<td>" . number_format(bcdiv(($ms1+$ms2)/2, 1, 1), 1, ",") . "</td>";
      }
      else
        echo "<td></td>";
      if($rec != null) {
        if(((($ms1+$ms2)/2) + $rec)/2 < 6)
          echo "<td>Reprovado</td>";
        else if($porcentagem <= 25)
          echo "<td>Aprovado</td>";
        else
          echo "<td>Reprovado</td>";
      }
      else if($ms1 != null && $ms2 != null) {
        if((($ms1+$ms2)/2) < 6)
          echo "<td>Indefinido</td>";
        else if($porcentagem <= 25)
          echo "<td>Aprovado</td>";
        else
          echo "<td>Reprovado</td>";
      }
      else
        echo "<td>Indefinido</td>";
      echo "</tr>";
    }
    echo "</tbody></table></div>";

    $stmt2 = $pdo->prepare("select * from AulasTCC where codTurma = :codTurma");
    $stmt2->bindParam(":codTurma", $_GET["turma"]);
    $stmt2->execute();
    $numAulas = $stmt2->rowCount();
    $stmt2 = $pdo->prepare("select * from PresencasTCC where raAluno = :raAluno and codTurma = :codTurma and presenca = 1");
    $stmt2->bindParam(":raAluno", $_SESSION["login"]);
    $stmt2->bindParam(":codTurma", $_GET["turma"]);
    $stmt2->execute();
    $numFaltas = $stmt2->rowCount();
    $porcentagem = $numAulas != 0 ? (100 * $numFaltas)/$numAulas : 0;
    echo "<div class='text-end mb-3'>
            <b>Frequência Geral: ".number_format(bcdiv($porcentagem, 1, 1), 0)."%</b>
          </div>";
  }
  catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
  finally {
    $pdo = null;
  }
?>