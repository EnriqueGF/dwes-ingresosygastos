<?php
require_once('db.php');
session_start();
if (!isset($_SESSION["logged"])) {return;}
if (isset($_POST["fecha"]) && isset($_POST["descripcion"]) && isset($_POST["cantidad"]) && $_SESSION["logged"]) {

    $usuario = $_SESSION["username"];
    $fecha = $_POST["fecha"];
    $descripcion = $_POST["descripcion"];
    $cantidad = $_POST["cantidad"];

    $dsn = "mysql:$DB_HOST=$DB_HOST;dbname=$DB_DATABASE";
    $dbh = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);

    $stmt = $dbh->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $usuario);
    $stmt->execute();
    $result = $stmt->fetch();

    $userid = $result["id"];

    $stmt = $dbh->prepare("INSERT INTO ingresos (users_id, fecha, descripcion, cantidad) VALUES (:userid, :fecha, :descripcion, :cantidad);");
    $stmt->bindParam(':userid', $userid);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':cantidad', $cantidad);
    if ($stmt->execute()) {
        echo("Ingreso efectuado.");
    } else {
        echo("fail");
    }
}