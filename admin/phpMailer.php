<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmail($email, $nome, $usuario, $senha) {
    //Load Composer's autoloader
    require '../vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'cotilportalescolar@gmail.com';                      //SMTP username
        $mail->Password   = 'suasenha123';                             //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          //Enable implicit TLS encryption
        $mail->Port       = 465;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('cotilportalescolar@gmail.com', 'Portal Escolar');
        $mail->addAddress($email, $nome);     //Add a recipient //Tem que mudar depois 
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Acesso ao Portal Escolar';
        $mail->Body    = 'Você agora possui acesso ao conteúdo do Portal Escolar<br><br>Usuário: <b>'.$usuario.'</b><br>Senha: <b>'.$senha.'</b>';
        $mail->AltBody = 'Você agora possui acesso ao conteúdo do Portal Escolar\n\nUsuário: '.$usuario.'\nSenha: '.$senha;

        $mail->send();
        //echo 'Foi';
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}

function esqueciMinhaSenha($email, $nome, $senha) {
    //Load Composer's autoloader
    require 'vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'cotilportalescolar@gmail.com';                      //SMTP username
        $mail->Password   = 'suasenha123';                             //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          //Enable implicit TLS encryption
        $mail->Port       = 465;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('cotilportalescolar@gmail.com', 'Portal Escolar');
        $mail->addAddress($email, $nome);     //Add a recipient //Tem que mudar depois 
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Redefinicao de Senha de Acesso ao Portal Escolar';
        $mail->Body    = 'Você solicitou a redefinição de sua senha de acesso ao Portal Escolar. Caso não tenha sido você, reporte ao administrador.<br><br>Senha: <b>'.$senha.'</b>';
        $mail->AltBody = 'Você solicitou a redefinição de sua senha de acesso ao Portal Escolar. Caso não tenha sido você, reporte ao administrador.\n\nSenha: '.$senha;

        $mail->send();
        //echo 'Foi';
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}
