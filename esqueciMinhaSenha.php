<?php
  if(isset($_POST["email"]))
    $email = $_POST["email"];
  if(isset($_POST["g-recaptcha-response"]))
    $captcha = $_POST["g-recaptcha-response"];
  if(!$captcha)
    header("Location: index.php");

  $secretKey = "6LczkOUcAAAAAKqjFqTBylkJFhonx3Cq3Ncye2jN";
  $ip = $_SERVER["REMOTE_ADDR"];

  $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
  $response = file_get_contents($url);
  $responseKeys = json_decode($response, true);

  if($responseKeys["success"]) {
    include("conexaoBD.php");
    $stmt = $pdo->prepare("select * from AlunosTCC where emailAluno = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $rows = $stmt->rowCount();
    if($rows > 0) {
      $row = $stmt->fetch();
      $novaSenha = "" . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9);
      include("admin/phpMailer.php");
      esqueciMinhaSenha($row["emailAluno"], $row["nomeAluno"], $novaSenha);
      $novaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("update UsuariosTCC set senha = :senha where usuario = :usuario");
      $stmt->bindParam(":senha", $novaSenha);
      $stmt->bindParam(":usuario", $row["raAluno"]);
      $stmt->execute();
      echo "<script>alert('Sua senha redefinida foi enviada ao seu e-mail.')</script>";
      echo "<script>window.location.replace('http://localhost/php/tcc6/index.php')</script>";
    }
    else {
      echo "<script>alert('E-mail não cadastrado.')</script>";
      echo "<script>window.location.replace('http://localhost/php/tcc6/index.php')</script>";
    }
  }
?>
