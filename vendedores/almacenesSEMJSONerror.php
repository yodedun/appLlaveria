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
CONCAT('$ ',FORMAT(metaSemestres.meta, 0)) as meta2,
tblperiodo_llaveria.strDescripcion,

    IF (
      (
         Sum(tblventas_llaveria.intVenta) > metaSemestres.meta
      ),
      (
        IF((Sum(tblventas_llaveria.intVenta) > (metaSemestres.meta  * 115) / 100 ),0,CONCAT('$ ',FORMAT((metaSemestres.meta  * 115) / 100 -  Sum(tblventas_llaveria.intVenta), 0),' para el 115% ')) 
      ),
      CONCAT('$ ',FORMAT(metaSemestres.meta  -  Sum(tblventas_llaveria.intVenta), 0),' para el 100% ')
    ) AS falta1,

CONCAT(FORMAT((( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ), 0),' %')as porcentaje,

 IF(

tblperiodo_llaveria.idPeriodo=5

,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 115 ),1.5,
1) ),

0 ))


,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 115 ),1.5,
1) ),

0 ))


) as bono,
 IF(

tblperiodo_llaveria.idPeriodo=5

,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 115 ),(Sum(tblventas_llaveria.intVenta)*0.015),
(Sum(tblventas_llaveria.intVenta)*0.01)) ),

0 ))


,( IF (( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 100 ),
( IF(( (( Sum(tblventas_llaveria.intVenta) / metaSemestres.meta)*100 ) >= 115 ),CONCAT('$ ',FORMAT((Sum(tblventas_llaveria.intVenta)*0.015), 0)),
CONCAT('$ ',FORMAT((Sum(tblventas_llaveria.intVenta)*0.01), 0))) ),

0 ))


) as bonoPesos,
tblcategorias.strdescripcion as categoria

FROM
tblventas_llaveria ,
tblperiodo_llaveria ,
tbluserlista ,
tbluser,
tblcategorias ,
tblproductos,
metaSemestres
WHERE
tbluserlista.idUser = :User AND
tbluserlista.idUser=tblventas_llaveria.intUser AND
metaSemestres.intUser=tbluserlista.idUser and
tblventas_llaveria.intUser=tbluser.idUser AND
tblventas_llaveria.intProducto=tblproductos.codProducto and
tblproductos.intYear = tblperiodo_llaveria.intYear and
tblcategorias.idCategoria > 8 AND
tblproductos.intCategoria = tblcategorias.idCategoria AND
metaSemestres.idPeriodo=tblperiodo_llaveria.idPeriodo and
NOW() BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin and
tblperiodo_llaveria.intSemestre=1
GROUP BY
tbluser.strUsername,
tblventas_llaveria.intUser,
tblperiodo_llaveria.strDescripcion,
tblcategorias.strdescripcion
) AS uno
RIGHT JOIN (
SELECT
tbluser.strName,
tbluser.strZona,
tblmetas_llaveria.intUser as intUser2,
CONCAT('$ ',FORMAT(tblmetas_llaveria.intValor, 0)) AS meta2,
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
NOW()  BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblperiodo_llaveria.intSemestre=1
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
		AND NOW()  BETWEEN tblperiodo_llaveria.dateComienzo
		AND tblperiodo_llaveria.dateFin
		AND tblperiodo_llaveria.intSemestre = 1
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
		bonosSemestres.bono
	FROM
		tblmetas_llaveria,
		tblperiodo_llaveria,
		tblproductos,
		tbluser,
		tbluserlista,
		bonosSemestres
	WHERE
		tbluserlista.idUser = :User AND
		tbluserlista.idUser=tbluser.idUser AND
		tbluser.idUser = tblmetas_llaveria.intUser
	AND tblmetas_llaveria.intProducto = tblproductos.codProducto
	AND tblproductos.intYear = tblperiodo_llaveria.intYear 
	AND tblproductos.intCategoria > 9 
	AND tblmetas_llaveria.intPeriodo = tblperiodo_llaveria.idPeriodo
	AND NOW() BETWEEN tblperiodo_llaveria.dateComienzo
	AND tblperiodo_llaveria.dateFin
	AND tblperiodo_llaveria.intSemestre = 1
	AND bonosSemestres.periodo = tblperiodo_llaveria.idPeriodo
	AND bonosSemestres.userId = tbluser.idUser
) AS dos ON uno.intUser = dos.idUser
AND uno.codProducto = dos.intProducto
) as todo
GROUP BY
			todo.intUser
) as tres
ON uno.intUser= tres.intUser";

try {
	$dbh = new PDO("mysql:host=$hostname_conexionmiura;dbname=$database_conexionmiura", $username_conexionmiura, $password_conexionmiura);	
	$dbh -> exec("set names utf8");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare($sql);  
	$stmt->bindParam("User", $_GET['almacen']);
	$stmt->execute();
	$aaData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"aaData":'. json_encode($aaData) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 

}

?>