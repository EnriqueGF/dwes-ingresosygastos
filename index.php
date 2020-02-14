<?php

session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Balance de ingresos y gastos</title>
</head>
<body>
    <h1> Balance de ingresos y gastos </h1>

<?php
if (!@$_SESSION["logged"]) {

?>
No estás logeado.
<h2> Login </h2>
<form method="post" action="login.php">
  Usuario:<br>
  <input type="text" name="user" value="">
  <br>
  Contraseña:<br>
  <input type="password" name="password" value="">
  <br><br>
  <input type="submit" value="Submit">
</form> 
<h2> Registro </h2>
<form method="post" action="registro.php">
  Usuario:<br>
  <input type="text" name="user" value="">
  <br>
  Contraseña:<br>
  <input type="password" name="password" value="">
  <br><br>
  <input type="submit" value="Submit">
</form> 

<?php

} else {


?>
Estás logeado como <?=$_SESSION["username"]?>
<p><a href="logout.php">Desconectar</a></p>

<hr>

<h2> Nuevo ingreso </h2>
<form method="post" action="ingreso.php">
  Fecha:<br>
  <input type="date" name="fecha" value="">
  <br>
  Descripción:<br>
  <input type="text" name="descripcion" value="">
  <br>
  Cantidad:<br>
  <input type="text" name="cantidad" value="">
  <br>  
  <br>
  <input type="submit" value="Submit">
</form> 

<hr>
<h2> Nuevo gasto </h2>
<form method="post" action="gasto.php">
  Fecha:<br>
  <input type="date" name="fecha" value="">
  <br>
  Descripción:<br>
  <input type="text" name="descripcion" value="">
  <br>
  Cantidad:<br>
  <input type="text" name="cantidad" value="">
  <br>  
  <br>
  <input type="submit" value="Submit">
</form> 

<hr>

<a href="generarpdf.php">Obtener PDF.</a>
<?php
}

?>


</body>
</html>