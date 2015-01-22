<?php header('Content-Type: application/json;charset=utf-8');
require_once('../Connections/conexionmiura.php');
mysql_set_charset('utf8'); ?>
<?php
mysql_query("set sql_big_selects=1");
$sql = "SELECT 
*,
CONCAT(FORMAT((ventasProductoVendedores.bonopesosNum/ventasProductoVendedores.ventaNum)*100, 2),'%') as porcentajebono
FROM
(
SELECT
tblproductos.strProducto,
tblmetas_llaveria.intProducto as producto,
Sum(tblventas_llaveria.intVenta) AS ventaNum,
CONCAT('$ ',FORMAT(Sum(tblventas_llaveria.intVenta), 0)) AS venta,
CONCAT('$ ',FORMAT(Sum(tblmetas_llaveria.intValor), 0)) as meta2,
tblperiodo_llaveria.strDescripcion,

    IF((Sum(tblventas_llaveria.intVenta) > (Sum(tblmetas_llaveria.intValor)  * 115) / 100 ),0,CONCAT('$ ',FORMAT((Sum(tblmetas_llaveria.intValor)  * 115) / 100 -  Sum(tblventas_llaveria.intVenta), 0),' para el 115% '))  AS falta1,

CONCAT(FORMAT((( Sum(tblventas_llaveria.intVenta) / Sum(tblmetas_llaveria.intValor))*100 ), 0),' %')as porcentaje,


 IF(

tblperiodo_llaveria.idPeriodo=5

,( IF (( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 115 ),(CONCAT('$ ',FORMAT(Sum(tblventas_llaveria.intVenta)*0.04, 0))),
(CONCAT('$ ',FORMAT(Sum(tblventas_llaveria.intVenta)*0.02, 0)))) ),

0 ))


,( IF (( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 100 ),
	
( IF(( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 115 ),(CONCAT('$ ',FORMAT(Sum(tblventas_llaveria.intVenta)*0.03, 0))),
(CONCAT('$ ',FORMAT(Sum(tblventas_llaveria.intVenta)*0.015, 0)))) ),

0 ))


) as bonoPesos,
 IF(

tblperiodo_llaveria.idPeriodo=5

,( IF (( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 115 ),
	(Sum(tblventas_llaveria.intVenta)*0.04),
(Sum(tblventas_llaveria.intVenta)*0.02)) ),

0 ))
,( IF (( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 100 ),
	
( IF(( (( Sum(tblventas_llaveria.intVenta) / tblmetas_llaveria.intValor)*100 ) >= 115 ),(Sum(tblventas_llaveria.intVenta)*0.03),
(Sum(tblventas_llaveria.intVenta)*0.015)) ),

0 ))


) as bonopesosNum

FROM
tblventas_llaveria ,
tblperiodo_llaveria ,
tbluserlista ,
tbluser,
tblcategorias ,
tblproductos,
tblmetas_llaveria
WHERE
tbluserlista.idVendedor= :User AND
tbluserlista.idUser=tblventas_llaveria.intUser AND
tblmetas_llaveria.intUser=tbluserlista.idUser and
tblventas_llaveria.intUser=tbluser.idUser AND
tblventas_llaveria.intProducto=tblproductos.codProducto and
tblproductos.codProducto=tblmetas_llaveria.intProducto and
tblcategorias.idCategoria=8 AND
tblproductos.intCategoria = tblcategorias.idCategoria AND
tblmetas_llaveria.intPeriodo=tblperiodo_llaveria.idPeriodo and
tbluserlista.idUser=tblmetas_llaveria.intUser and
tblmetas_llaveria.intUser=tbluser.idUser AND
(:dates) BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin and
tblperiodo_llaveria.intSemestre=0
GROUP BY
tblproductos.strProducto,
tblmetas_llaveria.intProducto,
tblperiodo_llaveria.strDescripcion
) AS ventasProductoVendedores

";

try {
	$dbh = new PDO("mysql:host=$hostname_conexionmiura;dbname=$database_conexionmiura", $username_conexionmiura, $password_conexionmiura);	
	$dbh -> exec("set names utf8");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare($sql);  
	$stmt->bindParam("User", $_SESSION['MM_idUsuario']);
	$stmt->bindParam("dates", $_GET['date']);
	$stmt->execute();
	$aaData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"aaData":'. json_encode($aaData) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 

}

?>