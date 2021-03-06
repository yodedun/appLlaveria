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
			CONCAT(
				'$ ',
				FORMAT(
					Sum(
						tblventas_llaveria.intVenta
					),
					0
				)
			) AS venta,
			CONCAT(
				'$ ',
				FORMAT(metatrimestre.meta, 0)
			) AS meta2,
			tblperiodo_llaveria.strDescripcion,

		IF (
			(
				Sum(
					tblventas_llaveria.intVenta
				) > metatrimestre.meta
			),
			(

				IF (
					(
						Sum(
							tblventas_llaveria.intVenta
						) > (metatrimestre.meta * 115) / 100
					),
					0,
					CONCAT(
						'$ ',
						FORMAT(
							(metatrimestre.meta * 115) / 100 - Sum(
								tblventas_llaveria.intVenta
							),
							0
						),
						' para el 115% '
					)
				)
			),
			CONCAT(
				'$ ',
				FORMAT(
					metatrimestre.meta - Sum(
						tblventas_llaveria.intVenta
					),
					0
				),
				' para el 100% '
			)
		) AS falta1,
		CONCAT(
			FORMAT(
				(
					(
						Sum(
							tblventas_llaveria.intVenta
						) / metatrimestre.meta
					) * 100
				),
				0
			),
			' %'
		) AS porcentaje,

	IF (
		tblperiodo_llaveria.idPeriodo = 5,
		(

			IF (
				(
					(
						(
							Sum(
								tblventas_llaveria.intVenta
							) / metatrimestre.meta
						) * 100
					) >= 100
				),
				(

					IF (
						(
							(
								(
									Sum(
										tblventas_llaveria.intVenta
									) / metatrimestre.meta
								) * 100
							) >= 115
						),
						4,
						2
					)
				),
				0
			)
		),
		(

			IF (
				(
					(
						(
							Sum(
								tblventas_llaveria.intVenta
							) / metatrimestre.meta
						) * 100
					) >= 100
				),
				(

					IF (
						(
							(
								(
									Sum(
										tblventas_llaveria.intVenta
									) / metatrimestre.meta
								) * 100
							) >= 115
						),
						3,
						1.5
					)
				),
				0
			)
		)
	) AS bono
	FROM
		tblventas_llaveria,
		tblperiodo_llaveria,
		tbluserlista,
		tbluser,
		tblcategorias,
		tblproductos,
		metatrimestre
	WHERE
		tbluserlista.idUser = :User
	AND tbluserlista.idUser = tblventas_llaveria.intUser
	AND metatrimestre.intUser = tbluserlista.idUser
	AND tblventas_llaveria.intUser = tbluser.idUser
	AND tblventas_llaveria.intProducto = tblproductos.codProducto
	AND tblproductos.intYear = tblperiodo_llaveria.intYear 
	AND tblcategorias.idCategoria = 8
	AND tblproductos.intCategoria = tblcategorias.idCategoria
	AND metatrimestre.idPeriodo = tblperiodo_llaveria.idPeriodo
	AND NOW() BETWEEN tblperiodo_llaveria.dateComienzo
	AND tblperiodo_llaveria.dateFin
	AND tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo
	AND tblperiodo_llaveria.dateFin
	AND tblperiodo_llaveria.intSemestre = 0
	GROUP BY
		tbluser.strUsername,
		tblventas_llaveria.intUser,
		tblperiodo_llaveria.strDescripcion
	) AS uno
RIGHT JOIN (
	SELECT
		tbluser.strName,
		tbluser.strZona,
		tblmetas_llaveria.intUser AS intUser2,
		CONCAT(
			'$ ',
			FORMAT(
				Sum(tblmetas_llaveria.intValor),
				0
			)
		) AS meta2,
		tblperiodo_llaveria.strDescripcion AS strDescripcion2
	FROM
		tblmetas_llaveria,
		tbluserlista,
		tbluser,
		tblperiodo_llaveria
	WHERE
		tbluserlista.idUser = :User
	AND tbluserlista.idUser = tblmetas_llaveria.intUser
	AND tblmetas_llaveria.intUser = tbluser.idUser
	AND tblmetas_llaveria.intPeriodo = tblperiodo_llaveria.idPeriodo
	AND NOW() BETWEEN tblperiodo_llaveria.dateComienzo
	AND tblperiodo_llaveria.dateFin
	AND tblperiodo_llaveria.intSemestre = 0
	GROUP BY
		tbluser.strName,
		tbluser.strZona,
		tblmetas_llaveria.intUser,
		tblmetas_llaveria.intPeriodo,
		tblperiodo_llaveria.strDescripcion
) AS dos ON uno.intUser = dos.intUser2
RIGHT JOIN (
	SELECT
		todo.intUser,
		CONCAT(
			'$ ',
			FORMAT(SUM(todo.bonificacion), 0)
		) AS suma
	FROM
		(
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
						tblperiodo_llaveria
					WHERE
						tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo
					AND tblperiodo_llaveria.dateFin
					AND NOW() BETWEEN tblperiodo_llaveria.dateComienzo
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
					vistabono.bono
				FROM
					tblmetas_llaveria,
					tblperiodo_llaveria,
					tblproductos,
					tbluser,
					tbluserlista,
					vistabono
				WHERE
					tbluserlista.idUser = :User
				AND tbluserlista.idUser = tbluser.idUser
				AND tbluser.idUser = tblmetas_llaveria.intUser
				AND tblmetas_llaveria.intProducto = tblproductos.codProducto
				AND tblproductos.intCategoria = 8 
				AND tblproductos.intYear = tblperiodo_llaveria.intYear 
				AND tblmetas_llaveria.intPeriodo = tblperiodo_llaveria.idPeriodo
				AND NOW() BETWEEN tblperiodo_llaveria.dateComienzo
				AND tblperiodo_llaveria.dateFin
				AND tblperiodo_llaveria.intSemestre = 0
				AND vistabono.userId = tbluser.idUser
			) AS dos ON uno.intUser = dos.idUser
			AND uno.codProducto = dos.intProducto
		) AS todo
WHERE
		todo.intUser=:User
	GROUP BY
		todo.intUser
) AS tres ON uno.intUser = tres.intUser

";

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