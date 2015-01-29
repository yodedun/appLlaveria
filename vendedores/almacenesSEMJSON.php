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
tblcategorias.strdescripcion as categoria,
tblcategorias.idCategoria

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
metaSemestres.categoria= tblcategorias.idCategoria AND
NOW() BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin and
tblperiodo_llaveria.intSemestre=1
GROUP BY
tbluser.strUsername,
tblventas_llaveria.intUser,
tblperiodo_llaveria.strDescripcion,
tblcategorias.strdescripcion,
tblcategorias.idCategoria
) as uno
RIGHT JOIN
(SELECT
tbluser.strName,
tbluser.strZona,
tblmetas_llaveria.intUser as intUser2,
tblmetas_llaveria.intCategoria as idcategoria,
CONCAT('$ ',FORMAT(tblmetas_llaveria.intValor, 0)) AS meta2,
tblperiodo_llaveria.strDescripcion as strDescripcion2,
tblcategorias.strdescripcion as categoria
FROM
tblmetas_llaveria,
tbluserlista ,
tbluser,
tblperiodo_llaveria,
tblcategorias
WHERE
tbluserlista.idUser = :User AND
tbluserlista.idUser=tblmetas_llaveria.intUser AND
tblmetas_llaveria.intUser=tbluser.idUser AND
tblmetas_llaveria.intPeriodo=tblperiodo_llaveria.idPeriodo and
tblmetas_llaveria.intCategoria = tblcategorias.idCategoria and
NOW()  BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND 
tblperiodo_llaveria.intSemestre=1
GROUP BY
tbluser.strName,
tbluser.strZona,
tblmetas_llaveria.intUser,
tblmetas_llaveria.intPeriodo,
tblperiodo_llaveria.strDescripcion,
tblcategorias.strdescripcion,
tblmetas_llaveria.intCategoria)as dos

ON  uno.intUser= dos.intUser2 and uno.idCategoria = dos.idcategoria";

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