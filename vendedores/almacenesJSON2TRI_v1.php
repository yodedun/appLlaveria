<?php header('Content-Type: application/json;charset=utf-8');
require_once('../Connections/conexionmiura.php');
mysql_set_charset('utf8'); ?>
<?php
mysql_query("set sql_big_selects=1");
$sql = "SELECT 
*
FROM
(
SELECT
tbluser.strUsername,
tblventas_llaveria.intUser,
CONCAT('$ ',FORMAT(Sum(tblventas_llaveria.intVenta), 0)) AS venta,
CONCAT('$ ',FORMAT(metaPeriodo.meta, 0)) as meta2,
tblperiodo_llaveria.strDescripcion,

    IF (
      (
         Sum(tblventas_llaveria.intVenta) > metaPeriodo.meta
      ),
      (
        IF((Sum(tblventas_llaveria.intVenta) > (metaPeriodo.meta  * 115) / 100 ),0,CONCAT('$ ',FORMAT((metaPeriodo.meta  * 115) / 100 -  Sum(tblventas_llaveria.intVenta), 0),' para el 115% ')) 
      ),
      CONCAT('$ ',FORMAT(metaPeriodo.meta  -  Sum(tblventas_llaveria.intVenta), 0),' para el 100% ')
    ) AS falta1,

CONCAT(FORMAT((( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ), 0),' %')as porcentaje,

 IF(

tblperiodo_llaveria.idPeriodo=5

,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 115 ),4,
2) ),

0 ))


,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 115 ),3,
1.5) ),

0 ))


) as bono,
 IF(

tblperiodo_llaveria.idPeriodo=5

,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 115 ),(Sum(tblventas_llaveria.intVenta)*0.04),
(Sum(tblventas_llaveria.intVenta)*0.02)) ),

0 ))


,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaPeriodo.meta)*100 ) >= 115 ),(Sum(tblventas_llaveria.intVenta)*0.03),
(Sum(tblventas_llaveria.intVenta)*0.015)) ),

0 ))


) as bonoPesos

FROM
tblventas_llaveria ,
tblperiodo_llaveria ,
tbluserlista ,
tbluser,
tblcategorias ,
tblproductos,
metaPeriodo
WHERE
tbluserlista.idUser = :User AND
tbluserlista.idUser=tblventas_llaveria.intUser AND
metaPeriodo.intUser=tbluserlista.idUser and
tblventas_llaveria.intUser=tbluser.idUser AND
tblventas_llaveria.intProducto=tblproductos.codProducto and
tblproductos.intYear = tblperiodo_llaveria.intYear  and
tblcategorias.idCategoria=8 AND
tblproductos.intCategoria = tblcategorias.idCategoria AND
metaPeriodo.idPeriodo=tblperiodo_llaveria.idPeriodo and
(:dates) BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin and
tblperiodo_llaveria.intSemestre=0
GROUP BY
tbluser.strUsername,
tblventas_llaveria.intUser,
tblperiodo_llaveria.strDescripcion
) AS uno
RIGHT JOIN (
SELECT
tbluser.strName,
tbluser.strZona,
tblmetas_llaveria.intUser as intUser2,
CONCAT('$ ',FORMAT(Sum(tblmetas_llaveria.intValor), 0)) AS meta2,
tblperiodo_llaveria.strDescripcion as strDescripcion2
FROM
tblmetas_llaveria,
tbluserlista ,
tbluser,
tblperiodo_llaveria
WHERE
tbluserlista.idUser = :User AND
tbluserlista.idUser=tblmetas_llaveria.intUser AND
tblmetas_llaveria.intUser=tbluser.idUser AND
tblmetas_llaveria.intPeriodo=tblperiodo_llaveria.idPeriodo and
(:dates) BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblperiodo_llaveria.intSemestre=0
GROUP BY
tbluser.strName,
tbluser.strZona,
tblmetas_llaveria.intUser,
tblmetas_llaveria.intPeriodo,
tblperiodo_llaveria.strDescripcion

) AS dos 
ON uno.intUser= dos.intUser2
RIGHT JOIN (
SELECT todo.intUser,  CONCAT('$ ',FORMAT(SUM(todo.bonificacion), 0)) as suma
from(

SELECT
	intUser,
	codProducto,
	venta,
	strProducto,
	meta,
 periodo,
 bono,

IF (
(dos.meta > 0),
(
	IF (
	(uno.venta > dos.meta),
		((bono * uno.venta) / 100)
	,
	0
) ),0
) AS bonificacion
FROM
	(
		SELECT
			tblventas_llaveria.intUser,
			tblventas_llaveria.intProducto AS codProducto,
			Sum(
				tblventas_llaveria.intVenta
			) AS venta
		FROM
			tblventas_llaveria,
			tblperiodo_llaveria,
			tbluserlista
		WHERE
		tbluserlista.idUser = :User AND
		tbluserlista.idUser=tblventas_llaveria.intUser AND
			tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo
		AND tblperiodo_llaveria.dateFin
		AND (:dates) BETWEEN tblperiodo_llaveria.dateComienzo
		AND tblperiodo_llaveria.dateFin
		AND tblperiodo_llaveria.intSemestre = 0
		GROUP BY
			tblventas_llaveria.intUser,
			tblventas_llaveria.strCode,
			tblventas_llaveria.intProducto
	) AS uno
LEFT JOIN (
	SELECT
		tbluser.idUser,
		tblmetas_llaveria.intProducto,
		tblproductos.strProducto,
		tblmetas_llaveria.intValor AS meta,
		tblperiodo_llaveria.strDescripcion AS periodo,
		vistaBonos.bono
	FROM
		tblmetas_llaveria,
		tblperiodo_llaveria,
		tblproductos,
		tbluser,
		tbluserlista,
		vistaBonos
	WHERE
		tbluserlista.idUser = :User AND
		tbluserlista.idUser=tbluser.idUser AND
		tbluser.idUser = tblmetas_llaveria.intUser
	AND tblmetas_llaveria.intProducto = tblproductos.codProducto
	AND tblproductos.intYear = tblperiodo_llaveria.intYear 
	AND tblproductos.intCategoria=8 
	AND tblmetas_llaveria.intPeriodo = tblperiodo_llaveria.idPeriodo
	AND (:dates) BETWEEN tblperiodo_llaveria.dateComienzo
	AND tblperiodo_llaveria.dateFin
	AND tblperiodo_llaveria.intSemestre = 0
	AND vistaBonos.periodo = tblperiodo_llaveria.idPeriodo
	AND vistaBonos.userId = tbluser.idUser
) AS dos ON uno.intUser = dos.idUser
AND uno.codProducto = dos.intProducto
) as todo
GROUP BY
			todo.intUser
) as tres
ON uno.intUser= tres.intUser


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