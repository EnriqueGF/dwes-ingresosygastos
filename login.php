<?php
require_once('db.php');
session_start();

if (isset($_POST["user"]) && isset($_POST["password"])) {

    $usuario = $_POST["user"];
    $password = $_POST["password"];

    try {
        $dsn = "mysql:host=$DB_HOST;dbname=$DB_DATABASE";
        $dbh = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);

        $stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $result = $stmt->fetch();
            if (password_verify($password, $result["password"])) {
                $_SESSION["logged"] = true;
                $_SESSION["username"] = $result["username"];
                $_SESSION["password"] = $result["password"];
                header("Location: index.php");
            } else {
                echo('Login incorrecto');
                return;
            }
        } else { 
            echo('El usuario no existe.');
            return;
        }
    } catch (Exception $e){
        echo $e;
    }

}