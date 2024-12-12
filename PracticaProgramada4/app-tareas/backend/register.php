<?php
require 'db.php';

function userRegistry($username, $password)
{
    try {
        global $pdo;
        //encriptamos el password;
        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password) values (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute( ['username' => $username, 'password' => $passwordHashed]);
        logDebug("Usuario Registrado");
        return true;

    } catch (Exception $e) {
        logError("Ocurrio un error: " . $e->getMessage());
        return false;
    }
}

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST'){

    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if(userRegistry($username, $password )){
            http_response_code(200);
            echo json_encode(["message" => "Registro exitoso "]);
        }else{
            http_response_code(response_code: 500);
            echo json_encode(["error" => "Eror registrando el usuario"]);
        }

    }else{
        http_response_code(response_code: 400);
        echo json_encode(["error" => "Email y password son requeridos"]);
    }

}else{
    http_response_code(405);
    echo json_encode(["error"=> "Metodo not permitido"]);
}