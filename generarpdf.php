<?php
require_once('db.php');
require_once __DIR__ . '/vendor/autoload.php';
session_start();

if (!isset($_SESSION["logged"])) {return;}

$dsn = "mysql:$DB_HOST=$DB_HOST;dbname=$DB_DATABASE";
$dbh = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
$usuario = $_SESSION["username"];

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg .tg-cly1{text-align:left;vertical-align:middle}
.tg .tg-0lax{text-align:left;vertical-align:top}
</style>
<table class="tg">
  <tr>
    <th class="tg-0lax" colspan="6">BALANCE</th>
  </tr>
  <tr>
    <td class="tg-0lax" colspan="3">Ingresos</td>
    <td class="tg-cly1" colspan="3">Gastos</td>
  </tr>
  <tr>
    <td class="tg-0lax">Fecha</td>
    <td class="tg-0lax">Descripción</td>
    <td class="tg-cly1">Cantidad</td>
    <td class="tg-cly1">Fecha</td>
    <td class="tg-0lax">Descripción</td>
    <td class="tg-0lax">Cantidad</td>
  </tr>
');

$dsn = "mysql:$DB_HOST=$DB_HOST;dbname=$DB_DATABASE";
$dbh = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);


$stmt = $dbh->prepare("SELECT id FROM users WHERE username = :username");
$stmt->bindParam(':username', $usuario);
$stmt->execute();
$result = $stmt->fetch();
$userid = $result["id"];


$stmt = $dbh->prepare("SELECT * FROM ingresos WHERE users_id = :username");
$stmt->bindParam(':username', $userid);
$stmt->execute();
$ingresos = $stmt->fetchAll(PDO::FETCH_OBJ);
$ingresosRows = $stmt->rowCount();

$stmt = $dbh->prepare("SELECT * FROM gastos WHERE users_id = :username");
$stmt->bindParam(':username', $userid);
$stmt->execute();

$gastos = $stmt->fetchAll(PDO::FETCH_OBJ);
$gastosRows = $stmt->rowCount();

// Aviso por código asquerosamente spaguetti, no he tenido otra porque me ha complicado mucho la vida
// que los ingresos y gastos estén en la misma tabla, ya que por html un ingreso y un gasto van en el mismo <tr> y no por separado.
// Al menos funciona.

if ($ingresosRows >= $gastosRows) {
    foreach ($ingresos as $index => $ingreso) {

        $encontrado = false;

        $mpdf->WriteHTML("<tr>
        <td>$ingreso->fecha</td>
        <td>$ingreso->descripcion</td>
        <td>$ingreso->cantidad</td>");

        foreach ($gastos as $indexgasto => $gasto) {
            $encontrado = true;
            if ($indexgasto == $index) {
                $mpdf->WriteHTML("
                <td>$gasto->fecha</td>
                <td>$gasto->descripcion</td>
                <td>$gasto->cantidad</td>
                </tr>");
            }
        }
    }

} else {

    foreach ($gastos as $index => $gasto) {

        $encontrado = false;
        $mpdf->WriteHTML("<tr>");

        foreach ($ingresos as $indexingreso => $ingreso) {
            if ($indexingreso == $index) {
                $encontrado = true;
                $mpdf->WriteHTML("
                <td>$ingreso->fecha</td>
                <td>$ingreso->descripcion</td>
                <td>$ingreso->cantidad</td>");
            }
        }
        if (!$encontrado) {
            $mpdf->WriteHTML("
            <td></td>
            <td></td>
            <td></td>");
        }
        $mpdf->WriteHTML("
        <td>$gasto->fecha</td>
        <td>$gasto->descripcion</td>
        <td>$gasto->cantidad</td>");

        $mpdf->WriteHTML("</tr>");

    }

}


$mpdf->WriteHTML('</table>');

$mpdf->Output();