<?php require_once('Connections/conexionmiura.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$varUser_categoria2 = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_categoria2 = $_SESSION['MM_idUsuario'];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_categoria2 = sprintf("SELECT 
intUser ,codProducto, venta, strProducto, meta, CONCAT(FORMAT(((uno.venta / dos.meta)*100 ), 0),' %') as porcentaje,
IF (
      (
         uno.venta > dos.meta
      ),
      (
        IF((uno.venta > (dos.meta  * 115) / 100 ),0,CONCAT('$ ',FORMAT((dos.meta  * 115) / 100 - uno.venta, 0),' para el 115% ')) 
      ),
      CONCAT('$ ',FORMAT(dos.meta  -  uno.venta, 0),' para el 100% ')
    ) AS falta1,
periodo, vistabono.bono,
IF (
(uno.venta > dos.meta ),
CONCAT('$ ',FORMAT(((bono*uno.venta)/100), 0)),
0
)as bonificacion


FROM
vistabono,
(
SELECT
tblventas_llaveria.intUser,
tblventas_llaveria.intProducto as codProducto,
Sum(tblventas_llaveria.intVenta) as venta

FROM
tblventas_llaveria ,
tblperiodo_llaveria
WHERE
tblventas_llaveria.intUser=%s AND
tblventas_llaveria.dateFecha BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND
NOW() BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND
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
tblperiodo_llaveria.strDescripcion as periodo
FROM
tblmetas_llaveria ,
tblperiodo_llaveria ,
tblproductos ,
tbluser
WHERE
tbluser.idUser=%s AND
tbluser.idUser=tblmetas_llaveria.intUser AND
tblmetas_llaveria.intProducto=tblproductos.codProducto and
tblmetas_llaveria.intPeriodo=tblperiodo_llaveria.idPeriodo and
NOW() BETWEEN tblperiodo_llaveria.dateComienzo AND tblperiodo_llaveria.dateFin AND
tblperiodo_llaveria.intSemestre=0

) AS dos ON uno.intUser= dos.idUser and uno.codProducto = dos.intProducto
WHERE
vistabono.userId=intUser", GetSQLValueString($varUser_categoria2, "int"),GetSQLValueString($varUser_categoria2, "int"));
$categoria2 = mysql_query($query_categoria2, $conexionmiura) or die(mysql_error());
$row_categoria2 = mysql_fetch_assoc($categoria2);
$totalRows_categoria2 = mysql_num_rows($categoria2);

$varUser_categoria3 = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_categoria3 = $_SESSION['MM_idUsuario'];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_categoria3 = sprintf("SELECT
  * , IFNULL(falta1,metaPorcentajeSV) as faltaMeta,  IFNULL(porcentaje,PorcentajeUno) as porcentaje2
FROM
  (
    SELECT
      us.idUser,
      pe.idPeriodo,
      ca.idCategoria,
      ca.strdescripcion AS segmento,
      pe.strDescripcion,
      Sum(ve.intVenta) AS ventas,
      (
        me.intValor * bn.intPorcentajeUno
      ) / 100 AS meta,
      (
        sum(ve.intVenta) / me.intValor
      ) * 100 AS cumplimiento,

    IF (
      (
         sum(ve.intVenta) > (me.intValor * bn.intPorcentajeUno) / 100 
      ),
      (
        IF((sum(ve.intVenta) > (me.intValor * bn.intPorcentajeDos) / 100 ),0,(me.intValor * bn.intPorcentajeDos) / 100 - sum(ve.intVenta)) 
      ),
      (me.intValor * bn.intPorcentajeUno) / 100 - sum(ve.intVenta)
    ) AS falta1,

IF (
      (
         sum(ve.intVenta) > (me.intValor * bn.intPorcentajeUno) / 100 
      ),
      (
        bn.intPorcentajeDos
      ),
      bn.intPorcentajeUno
    ) AS porcentaje,

IF (
  (
    (
      me.intValor * bn.intPorcentajeUno
    ) / 100 > sum(ve.intVenta)
  ),
  0,

IF (
  (
    (
      me.intValor * bn.intPorcentajeDos
    ) / 100 > sum(ve.intVenta)
  ),
  (
    sum(ve.intVenta) * bn.intBonouno
  ),
  (
    sum(ve.intVenta) * bn.intBonodos
  )
)
) AS bono
FROM
  tblbonos AS bn,
  tblcategorias AS ca,
  tblperiodo AS pe,
  tbluser AS us,
  tblproductos AS pr,
  tblmetas AS me,
  tblventas AS ve
WHERE
  ca.idCategoria = pr.intCategoria
AND me.intUser = us.idUser
AND me.intCategoria = ca.idCategoria
AND me.intPeriodo = pe.idPeriodo
AND ve.intProducto = pr.codProducto
AND bn.intPeriodo = pe.idPeriodo
AND bn.intCategoria = ca.idCategoria
AND ve.dateFecha BETWEEN pe.dateComienzo
AND pe.datefin
AND us.idUser = %s
AND ca.idCategoria = 3
AND NOW() BETWEEN pe.dateComienzo
AND pe.dateFin
AND ve.intUser = us.idUser
GROUP BY
  ca.strdescripcion,
  pe.strDescripcion,
  me.intValor,
  bn.intBonouno
  ) AS uno
RIGHT JOIN (
  SELECT
    tbluser.idUser AS idUserSV,
    tbluser.strUsername AS usernameSV,
    tbluser.strZona AS zonaSV,
    tblmetas.intValor AS metasSV,
    (
      tblmetas.intValor * tblbonos.intPorcentajeUno
    ) / 100 AS metaPorcentajeSV,
    (
      tblmetas.intValor * tblbonos.intPorcentajeDos
    ) / 100 AS meta2PorcentajeSV,
    tblperiodo.idPeriodo AS periodoSV,
    tblcategorias.idCategoria AS categoriaSV,
    tblcategorias.strdescripcion AS namecategoriaSV,
 (
  tblmetas.intValor * tblbonos.intPorcentajeUno
) / 100 * tblbonos.intBonouno AS ganaria1,
 (
  tblmetas.intValor * tblbonos.intPorcentajeDos
) / 100 * tblbonos.intBonodos AS ganaria2,
tblbonos.intPorcentajeUno as PorcentajeUno
    
  FROM
    tbluser
  INNER JOIN tblmetas ON tblmetas.intUser = tbluser.idUser
  INNER JOIN tblperiodo ON tblperiodo.idPeriodo = tblmetas.intPeriodo
  INNER JOIN tblcategorias ON tblcategorias.idCategoria = tblmetas.intCategoria
  INNER JOIN tblbonos ON tblbonos.intCategoria = tblcategorias.idCategoria
  AND tblbonos.intPeriodo = tblperiodo.idPeriodo
  WHERE
    tblcategorias.idCategoria = 3
  AND tbluser.idUser = %s
  AND NOW() BETWEEN tblperiodo.dateComienzo
  AND tblperiodo.dateFin
) AS dos ON uno.idUser = dos.idUserSV", GetSQLValueString($varUser_categoria3, "int"),GetSQLValueString($varUser_categoria3, "int"));
$categoria3 = mysql_query($query_categoria3, $conexionmiura) or die(mysql_error());
$row_categoria3 = mysql_fetch_assoc($categoria3);
$totalRows_categoria3 = mysql_num_rows($categoria3);

$varUser_consultaAlmacen = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_consultaAlmacen = $_SESSION['MM_idUsuario'];
}
mysql_query("set sql_big_selects=1");
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_consultaAlmacen = sprintf("SELECT
  * , IFNULL(falta1,metaPorcentajeSV) as faltaMeta,  IFNULL(porcentaje,PorcentajeUno) as porcentaje2
FROM
  (
    SELECT
      us.idUser,
      pe.idPeriodo,
      ca.idCategoria,
      ca.strdescripcion AS segmento,
      pe.strDescripcion,
      Sum(ve.intVenta) AS ventas,
      (
        me.intValor * bn.intPorcentajeUno
      ) / 100 AS meta,
      (
        sum(ve.intVenta) / me.intValor
      ) * 100 AS cumplimiento,

    IF (
      (
         sum(ve.intVenta) > (me.intValor * bn.intPorcentajeUno) / 100 
      ),
      (
        IF((sum(ve.intVenta) > (me.intValor * bn.intPorcentajeDos) / 100 ),0,(me.intValor * bn.intPorcentajeDos) / 100 - sum(ve.intVenta)) 
      ),
      (me.intValor * bn.intPorcentajeUno) / 100 - sum(ve.intVenta)
    ) AS falta1,

IF (
      (
         sum(ve.intVenta) > (me.intValor * bn.intPorcentajeUno) / 100 
      ),
      (
        bn.intPorcentajeDos
      ),
      bn.intPorcentajeUno
    ) AS porcentaje,

IF (
  (
    (
      me.intValor * bn.intPorcentajeUno
    ) / 100 > sum(ve.intVenta)
  ),
  0,

IF (
  (
    (
      me.intValor * bn.intPorcentajeDos
    ) / 100 > sum(ve.intVenta)
  ),
  (
    sum(ve.intVenta) * bn.intBonouno
  ),
  (
    sum(ve.intVenta) * bn.intBonodos
  )
)
) AS bono
FROM
  tblbonos AS bn,
  tblcategorias AS ca,
  tblperiodo AS pe,
  tbluser AS us,
  tblproductos AS pr,
  tblmetas AS me,
  tblventas AS ve
WHERE
  ca.idCategoria = pr.intCategoria
AND me.intUser = us.idUser
AND me.intCategoria = ca.idCategoria
AND me.intPeriodo = pe.idPeriodo
AND ve.intProducto = pr.codProducto
AND bn.intPeriodo = pe.idPeriodo
AND bn.intCategoria = ca.idCategoria
AND ve.dateFecha BETWEEN pe.dateComienzo
AND pe.datefin
AND us.idUser = %s
AND ca.idCategoria = 1
AND NOW() BETWEEN pe.dateComienzo
AND pe.dateFin
AND ve.intUser = us.idUser
GROUP BY
  ca.strdescripcion,
  pe.strDescripcion,
  me.intValor,
  bn.intBonouno
  ) AS uno
RIGHT JOIN (
  SELECT
    tbluser.idUser AS idUserSV,
    tbluser.strUsername AS usernameSV,
    tbluser.strZona AS zonaSV,
    tblmetas.intValor AS metasSV,
    (
      tblmetas.intValor * tblbonos.intPorcentajeUno
    ) / 100 AS metaPorcentajeSV,
    (
      tblmetas.intValor * tblbonos.intPorcentajeDos
    ) / 100 AS meta2PorcentajeSV,
    tblperiodo.idPeriodo AS periodoSV,
    tblcategorias.idCategoria AS categoriaSV,
    tblcategorias.strdescripcion AS namecategoriaSV,
 (
  tblmetas.intValor * tblbonos.intPorcentajeUno
) / 100 * tblbonos.intBonouno AS ganaria1,
 (
  tblmetas.intValor * tblbonos.intPorcentajeDos
) / 100 * tblbonos.intBonodos AS ganaria2,
tblbonos.intPorcentajeUno as PorcentajeUno
    
  FROM
    tbluser
  INNER JOIN tblmetas ON tblmetas.intUser = tbluser.idUser
  INNER JOIN tblperiodo ON tblperiodo.idPeriodo = tblmetas.intPeriodo
  INNER JOIN tblcategorias ON tblcategorias.idCategoria = tblmetas.intCategoria
  INNER JOIN tblbonos ON tblbonos.intCategoria = tblcategorias.idCategoria
  AND tblbonos.intPeriodo = tblperiodo.idPeriodo
  WHERE
    tblcategorias.idCategoria = 1
  AND tbluser.idUser = %s
  AND NOW() BETWEEN tblperiodo.dateComienzo
  AND tblperiodo.dateFin
) AS dos ON uno.idUser = dos.idUserSV
  ", GetSQLValueString($varUser_consultaAlmacen, "int"), GetSQLValueString($varUser_consultaAlmacen, "int"));
$consultaAlmacen = mysql_query($query_consultaAlmacen, $conexionmiura) or die(mysql_error());
$row_consultaAlmacen = mysql_fetch_assoc($consultaAlmacen);
$totalRows_consultaAlmacen = mysql_num_rows($consultaAlmacen);$varUser_consultaAlmacen = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_consultaAlmacen = $_SESSION['MM_idUsuario'];
}

?>
   <?php include("headerglobal.php"); ?>
<body>
    <?php include("header.php"); ?>

    
    <div class="jumbotron">
      <div class="container">
        <h1> Hola <?php echo $_SESSION['MM_Username'] ?></h1>
        <p>Programa Miura - Gana mucho más con Miura de Dow AgroSciences Colombia</p>
      </div>
    </div>

    <div class="container">
     
      
        <div class="panel panel-default">
                    <div class="panel-heading"><h3> Trimestre Actual </h3></div>
                <!--panel -->
                     <div class="panel-body">
                        <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">

                          <thead>
                            <tr>
                                
                                <th data-class="expand">Segmento</th>
                                <th data-hide="phone">Meta</th>
                                <th >Compras a la fecha</th>
                                <th data-hide="phone">%</th>
                                <th data-hide="phone">Falta para bono</th>
                                <th data-hide="phone,tablet">Al 100% el bono sería de</th>
                              
                                
                                <th data-hide="phone,tablet">Detalles</th>
                            </tr>
                          </thead>
                         
                          <tbody>

                            

                            <tr>
                              <?php $periodo=$row_categoria2['idPeriodo'];
                              $categoria=$row_categoria2['idCategoria']; ?>

                              <td><?php echo $row_categoria2['strProducto']; ?></td>
                              <td><?php echo number_format ($row_categoria2['meta'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria2['venta'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria2['porcentaje'],0,",",","); ?></td>
                              <td> Falta para el <?php echo $row_categoria2['falta1']; ?></td>
                              <td><?php echo number_format ($row_categoria2['bonificacion'],0,",",","); ?></td>
                             
                              <td><a class="btn btn-success" href="detalles_almacen.php?periodo=<?php echo $row_categoria2['idPeriodo'] ?>&cat=<?php echo $categoria ?>">Detalles</a></td>
                            </tr>

                          


                          </tbody>
                        </table>
                      </div>

                    <!-- finpanel-->

                    <!--panel -->
                    <div class="panel-body">
                        <table  border="0" class="table table-bordered table-striped">

                          <thead>
                            <tr>
                                
                                <th data-class="expand">Segmento</th>
                                <th data-hide="phone">Meta</th>
                                <th>Compras a la fecha</th>
                                <th data-hide="phone">%</th>
                                <th data-hide="phone">Falta para bono</th>
                                <th data-hide="phone,tablet">Al 100% el bono sería de</th>
                                <th data-hide="phone,tablet">Al 115% el bono sería de</th>
                                <th data-hide="phone,tablet">Bono Actual</th>
                                <th data-hide="phone,tablet">Detalles</th>
                            </tr>
                          </thead>
                         
                          <tbody>
                            <tr>
                              <?php $periodo=$row_categoria3['idPeriodo'];
                              $categoria=$row_categoria3['idCategoria']; ?>

                              <td><?php echo $row_categoria3['namecategoriaSV']; ?></td>
                              <td><?php echo number_format ($row_categoria3['metasSV'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria3['ventas'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria3['cumplimiento'],0,",",","); ?></td>
                              <td> Falta para el <?php echo $row_categoria3['porcentaje2']; ?>%:  $<?php echo number_format ($row_categoria3['faltaMeta'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria3['ganaria1'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria3['ganaria2'],0,",",","); ?></td>
                              <td><?php echo number_format ($row_categoria3['bono'],0,",",","); ?></td>
                              <td><a class="btn btn-success" href="detalles_almacen.php?periodo=<?php echo $row_categoria3['idPeriodo'] ?>&cat=<?php echo $categoria ?>">Detalles</a></td>
                            </tr>

                          </tbody>
                        </table>
                    
                    </div>

                    <!-- finpanel-->
        </div>
      </div>
     
   

    <div class="container"> 
      <hr>

      <footer>
        <p>&copy; Dow AgroSciences Colombia - 2014</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript" src="lib/lodash/lodash.min.js"></script>
        <script type="text/javascript" src="lib/jquery.dataTables/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="lib/jquery.dataTables.bootstrap3/js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="files/js/datatables.responsive.js"></script>
        <script type="text/javascript" src="js/ajax-almacen.js"></script>
    </body>
</html>
<?php
mysql_free_result($categoria2);

mysql_free_result($categoria3);

mysql_free_result($consultaAlmacen);

?>
