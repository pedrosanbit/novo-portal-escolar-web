<?php
	try {        
        $pdo = new PDO('mysql:host=143.106.241.3:3306;dbname=cl19143;charset=utf8', 'cl19143', 'cl*21012004');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $output = 'Conexão estabelecida. <br>';
    } catch (PDOException $e) {
        $output = 'Impossível conectar BD : ' . $e . '<br>';
    }
?>