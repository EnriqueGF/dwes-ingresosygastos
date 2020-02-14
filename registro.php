<?php
require_once('db.php');
session_start();

if (isset($_POST["user"]) && isset($_POST["password"])) {

    $usuario = $_POST["user"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    try {
        $dsn = "mysql:host=$DB_HOST;dbname=$DB_DATABASE";
        $dbh = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);

        $stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            echo("Ya existe el nombre de usuario solicitado para registro.");
            return;
        } else { 
            $stmt = $dbh->prepare("INSERT INTO users (username, password) VALUES (:username, :password);");
            $stmt->bindParam(':username', $usuario);
            $stmt->bindParam(':password', $password);
            if ($stmt->execute()) {
                $_SESSION["logged"] = true;
                $_SESSION["username"] = $usuario;
                $_SESSION["password"] = $password;
                echo("Usuario creado correctamente.");
                return;
            } else {
                echo("El usuario no ha sido creado.");
            }

            return;
        }
    } catch (Exception $e){
        echo $e;
    }

}