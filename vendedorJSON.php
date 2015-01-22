<?php header('Content-Type: application/json');
require_once('Connections/conexionmiura.php'); ?>
<?php
mysql_query("set sql_big_selects=1");
$sql = "SELECT 
*
FROM
(SELECT 
	id AS id2,
	nombre AS name2,
	Zona,
	sum(metascum) AS metascumplidas,
	periodo AS pe,
	CONCAT('$',FORMAT(Sum(ventasCa), 0)) AS sum,
	CONCAT('$',FORMAT(Sum(metasCa), 0)) AS met,
	GROUP_CONCAT(strMetascum, cat) AS categorias
FROM
	(
		SELECT
			tbluser.strUsername AS nombre,
			tbluser.idUser AS id,
			tblcategorias.strdescripcion AS cat,
			tblperiodo.strDescripcion AS periodo,
			Sum(tblventas.intVenta) AS ventasCa,
			tblmetas.intValor AS metasCa,

		IF (
			(
				(
					Sum(tblventas.intVenta) > tblmetas.intValor
				)
			),
			(1),
			0
		) AS metascum,

	IF (
		(
			(
				Sum(tblventas.intVenta) > tblmetas.intValor
			)
		),
		('Cumplio '),
		'Falta '
	) AS strMetascum,
	tbluser.strZona AS Zona
FROM
	tbluser
LEFT JOIN tblventas ON tbluser.idUser = tblventas.intUser
INNER JOIN tblproductos ON tblventas.intProducto = tblproductos.codProducto
RIGHT JOIN tblcategorias ON tblcategorias.idCategoria = tblproductos.intCategoria,
 tblperiodo,
 tblmetas,
 tbluserlista
WHERE
	tblcategorias.idCategoria <= 3
AND NOW() BETWEEN tblperiodo.dateComienzo
AND tblperiodo.dateFin
AND tblventas.dateFecha BETWEEN tblperiodo.dateComienzo
AND tblperiodo.dateFin
AND tblmetas.intPeriodo = tblperiodo.idPeriodo
AND tblmetas.intCategoria = tblcategorias.idCategoria
AND tblmetas.intUser = tbluser.idUser
AND tbluserlista.idUser = tbluser.idUser
AND tbluserlista.idVendedor = :User
GROUP BY
	tbluser.strUsername,
	tbluser.idUser,
	tblcategorias.strdescripcion,
	tblperiodo.strDescripcion,
	tblmetas.intValor
	) AS sumas
GROUP BY
	name2,
	Zona,
	id2,
	pe) AS uno
RIGHT JOIN (SELECT 
tbluser.idUser,
tbluser.strUsername,
tbluser.strZona


FROM
tbluser ,
tbluserlista
where
tbluserlista.idUser = tbluser.idUser
and tbluserlista.idVendedor = :User) AS dos ON uno.id2= dos.idUser

";

try {
	$dbh = new PDO("mysql:host=$hostname_conexionmiura;dbname=$database_conexionmiura", $username_conexionmiura, $password_conexionmiura);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare($sql);  
	$stmt->bindParam("User", $_SESSION['MM_idUsuario']);
	$stmt->execute();
	$aaData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"aaData":'. json_encode($aaData) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 

}

?>