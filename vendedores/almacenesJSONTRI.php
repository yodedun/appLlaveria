<?php header('Content-Type: application/json;charset=utf-8');
require_once('../Connections/conexionmiura.php');
mysql_set_charset('utf8'); ?>
<?php
mysql_query("set sql_big_selects=1");
$sql = "SELECT 
intUser ,codProducto, CONCAT('$ ',FORMAT(venta, 0)) as venta, strProducto, CONCAT('$ ',FORMAT((meta), 0)) as meta, CONCAT(FORMAT(((uno.venta / dos.meta)*100 ), 0),' %') as porcentaje,
IF((uno.venta > (dos.meta  * 115) / 100 )
        	,0,
        	CONCAT('Falta $ ',FORMAT((dos.meta  * 115) / 100 - uno.venta, 0),' para el 115% '))  AS falta1,
periodo, bono,
IF (
(dos.meta > 0),
(
	IF (
(uno.venta > dos.meta ),
CONCAT('$ ',FORMAT(((bono*uno.venta)/100), 0)),
0
) ),0
) as bonificacion


FROM
(
SELECT
tblventas_llaveria.intUser,
tblventas_llaveria.intProducto as codProducto,
Sum(tblventas_llaveria.intVenta) as venta

FROM
tblventas_llaveria ,
tblperiodo_llaveria
WHERE
tblventas_llaveria.intUser=:User AND
tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND
(:dates) BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND
tblperiodo_llaveria.intSemestre=0
GROUP BY
tblventas_llaveria.intUser,
tblventas_llaveria.strCode,
tblventas_llaveria.intProducto

) AS uno
RIGHT JOIN (
SELECT
tbluser.idUser,
tblmetas_llaveria.intProducto,
tblproductos.strProducto,
tblmetas_llaveria.intValor as meta,
tblperiodo_llaveria.strDescripcion as periodo,
vistaBonos.bono 
FROM
tblmetas_llaveria ,
tblperiodo_llaveria ,
tblproductos ,
tbluser,
vistaBonos
WHERE
tbluser.idUser=:User AND
tbluser.idUser=tblmetas_llaveria.intUser AND
tblmetas_llaveria.intProducto=tblproductos.codProducto and
tblproductos.intCategoria=8 AND
tblmetas_llaveria.intPeriodo=tblperiodo_llaveria.idPeriodo and
(:dates) BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND
tblperiodo_llaveria.intSemestre=0 AND
vistaBonos.periodo = tblperiodo_llaveria.idPeriodo AND
vistaBonos.userId=tbluser.idUser

) AS dos ON uno.intUser= dos.idUser and uno.codProducto = dos.intProducto



";

try {
	$dbh = new PDO("mysql:host=$hostname_conexionmiura;dbname=$database_conexionmiura", $username_conexionmiura, $password_conexionmiura);	
	$dbh -> exec("set names utf8");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare($sql);  
	$stmt->bindParam("User", $_GET['almacen']);
	$stmt->bindParam("dates", $_GET['date']);
	$stmt->execute();
	$aaData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"aaData":'. json_encode($aaData) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 

}

?>