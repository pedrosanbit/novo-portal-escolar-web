<?php
    $confirmacao=0;
    $tipo;
    $usuario = array("admin","prof","estudante");
    $senha   =  array("admin123","prof123","est123");

    $usuarioInformado = $_GET["usuario"];
    $senhaInformada   = $_GET["senha"];

    $msg="";
    $login="";

    if ( empty($usuarioInformado) || empty($senhaInformada) ) {
        $msg = "Informe seu login e senha!";
    }

    else if ( strlen($usuarioInformado) < 4 ) {
        $msg = "O login deve possuir no minimo 4 caracteres!";
    }

    else if ( strlen($senhaInformada) < 4 ) {
        $msg = "A senha deve possuir no minimo 4 caracteres!";
    }
    else{

        foreach($usuario as $pos => $valor){
            if($usuarioInformado==$valor){
                    if($senhaInformada==$senha[$pos]){
                        $login = "logado";
                        $confirmacao++;
                    }
                    else{
                        $msg = "Senha inválida!";
                        $confirmacao++;
                    }
                    $tipo=$valor;
            }
        }
        if($confirmacao==0){
            $msg = "Login inválido!";
        }
    }

    header('Location: index.php?msg='.$msg.'&login='.$login.'&tipo='.$tipo);

