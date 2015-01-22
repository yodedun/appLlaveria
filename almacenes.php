<?php require_once('Connections/conexionmiura.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
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
if (isset($_GET['almacen'])) {
  $varUser_categoria2 = $_GET['almacen'];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_categoria2 = sprintf("SELECT 	pe.idPeriodo, 	ca.idCategoria, 	ca.strdescripcion AS segmento, 	pe.strDescripcion, 	sum(ve.intVenta) AS ventas, 	me.intValor, 	( 		sum(ve.intVenta) / me.intValor 	) * 100 AS cumplimiento, IF ( 	( 		me.intValor > sum(ve.intVenta) 	), 	( 		me.intValor - sum(ve.intVenta) 	), 	0 ) AS falta1, IF ( 	( 		((115 / 100) * me.intValor) > sum(ve.intVenta) 	), 	( 		((115 / 100) * me.intValor) - sum(ve.intVenta) 	), 	0 ) AS falta2, IF ( 	( 		me.intValor > sum(ve.intVenta) 	), 	0, IF ( 	( 		((115 / 100) * me.intValor) > sum(ve.intVenta) 	), 	( 		sum(ve.intVenta) * bn.intBonouno 	), 	( 		sum(ve.intVenta) * bn.intBonodos 	) ) ) AS bono,  me.intValor * bn.intBonouno AS ganaria1,  ((115 / 100) * me.intValor) * bn.intBonodos AS ganaria2 FROM 	tblbonos AS bn, 	tblcategorias AS ca, 	tblperiodo AS pe, 	tbluser AS us, 	tblproductos AS pr, 	tblmetas AS me, 	tblventas AS ve WHERE 	ca.idCategoria = pr.intCategoria AND me.intUser = us.idUser AND me.intCategoria = ca.idCategoria AND me.intPeriodo = pe.idPeriodo AND ve.intProducto = pr.codProducto AND bn.intPeriodo = pe.idPeriodo AND bn.intCategoria = ca.idCategoria AND ve.dateFecha BETWEEN pe.dateComienzo AND pe.datefin AND us.idUser = %s AND ca.idCategoria = 2 AND NOW() BETWEEN pe.dateComienzo AND pe.dateFin AND ve.intUser = us.idUser GROUP BY 	ca.strdescripcion, 	pe.strDescripcion, 	me.intValor, 	bn.intBonouno ORDER BY 	ca.idCategoria ASC ", GetSQLValueString($varUser_categoria2, "int"));
$categoria2 = mysql_query($query_categoria2, $conexionmiura) or die(mysql_error());
$row_categoria2 = mysql_fetch_assoc($categoria2);
$totalRows_categoria2 = mysql_num_rows($categoria2);

$varUser_categoria3 = "0";
if (isset($_GET['almacen'])) {
  $varUser_categoria3 = $_GET['almacen'];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_categoria3 = sprintf("SELECT 	pe.idPeriodo, 	ca.idCategoria, 	ca.strdescripcion AS segmento, 	pe.strDescripcion, 	sum(ve.intVenta) AS ventas, 	me.intValor, 	( 		sum(ve.intVenta) / me.intValor 	) * 100 AS cumplimiento, IF ( 	( 		me.intValor > sum(ve.intVenta) 	), 	( 		me.intValor - sum(ve.intVenta) 	), 	0 ) AS falta1, IF ( 	( 		((115 / 100) * me.intValor) > sum(ve.intVenta) 	), 	( 		((115 / 100) * me.intValor) - sum(ve.intVenta) 	), 	0 ) AS falta2, IF ( 	( 		me.intValor > sum(ve.intVenta) 	), 	0, IF ( 	( 		((115 / 100) * me.intValor) > sum(ve.intVenta) 	), 	( 		sum(ve.intVenta) * bn.intBonouno 	), 	( 		sum(ve.intVenta) * bn.intBonodos 	) ) ) AS bono,  me.intValor * bn.intBonouno AS ganaria1,  ((115 / 100) * me.intValor) * bn.intBonodos AS ganaria2 FROM 	tblbonos AS bn, 	tblcategorias AS ca, 	tblperiodo AS pe, 	tbluser AS us, 	tblproductos AS pr, 	tblmetas AS me, 	tblventas AS ve WHERE 	ca.idCategoria = pr.intCategoria AND me.intUser = us.idUser AND me.intCategoria = ca.idCategoria AND me.intPeriodo = pe.idPeriodo AND ve.intProducto = pr.codProducto AND bn.intPeriodo = pe.idPeriodo AND bn.intCategoria = ca.idCategoria AND ve.dateFecha BETWEEN pe.dateComienzo AND pe.datefin AND us.idUser = %s AND ca.idCategoria = 3 AND NOW() BETWEEN pe.dateComienzo AND pe.dateFin AND ve.intUser = us.idUser GROUP BY 	ca.strdescripcion, 	pe.strDescripcion, 	me.intValor, 	bn.intBonouno ORDER BY 	ca.idCategoria ASC ", GetSQLValueString($varUser_categoria3, "int"));
$categoria3 = mysql_query($query_categoria3, $conexionmiura) or die(mysql_error());
$row_categoria3 = mysql_fetch_assoc($categoria3);
$totalRows_categoria3 = mysql_num_rows($categoria3);

$varUser_consultaAlmacen = "0";
if (isset($_GET['almacen'])) {
  $varUser_consultaAlmacen = $_GET['almacen'];
}
mysql_query("set sql_big_selects=1");
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_consultaAlmacen = sprintf("SELECT pe.idPeriodo,
ca.idCategoria, ca.strdescripcion as segmento, pe.strDescripcion, sum(ve.intVenta) as ventas, me.intValor,
(sum(ve.intVenta)/ me.intValor)*100 AS cumplimiento, if((me.intValor > sum(ve.intVenta)),(me.intValor - sum(ve.intVenta)),0) as falta1,
 if((((115/100)* me.intValor) > sum(ve.intVenta)),(((115/100)*me.intValor)  - sum(ve.intVenta)),0) AS falta2,
if((me.intValor > sum(ve.intVenta)),0,if((((115/100)* me.intValor) > sum(ve.intVenta)),
         (sum(ve.intVenta) * bn.intBonouno ),
         (sum(ve.intVenta) * bn.intBonodos ))) AS bono,
me.intValor * bn.intBonouno AS ganaria1, 
((115/100)* me.intValor)* bn.intBonodos AS ganaria2 FROM tblbonos AS bn, tblcategorias AS ca, tblperiodo AS pe, tbluser AS us, tblproductos AS pr, tblmetas AS me, tblventas AS ve WHERE ca.idCategoria = pr.intCategoria AND me.intUser = us.idUser AND me.intCategoria = ca.idCategoria AND me.intPeriodo = pe.idPeriodo AND ve.intProducto = pr.codProducto AND bn.intPeriodo = pe.idPeriodo AND bn.intCategoria = ca.idCategoria AND ve.dateFecha BETWEEN pe.dateComienzo AND pe.datefin AND us.idUser = %s AND ca.idCategoria = 1 AND NOW() BETWEEN pe.dateComienzo and pe.dateFin AND ve.intUser = us.idUser GROUP BY ca.strdescripcion, pe.strDescripcion, me.intValor, bn.intBonouno ORDER BY ca.idCategoria ASC ", GetSQLValueString($varUser_consultaAlmacen, "int"));
$consultaAlmacen = mysql_query($query_consultaAlmacen, $conexionmiura) or die(mysql_error());
$row_consultaAlmacen = mysql_fetch_assoc($consultaAlmacen);
$totalRows_consultaAlmacen = mysql_num_rows($consultaAlmacen);$varUser_consultaAlmacen = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_consultaAlmacen = $_SESSION['MM_idUsuario'];
}

?>
   <?php include("headerglobal.php"); ?>
    <body>
    <?php include("headervendedor.php"); ?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1> Hola <?php echo $_SESSION['MM_Username'] ?></h1>
        <p>Programa Miura - Gana mucho más con Miura de Dow AgroSciences Colombia</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      
        <div class="panel panel-default">
                <div class="panel-heading"><h3> <?php echo ObtenerNombreUsuario( $_GET['almacen'])?> </h3></div>
                	<div class="panel-body">
                    	  <table id="example" border="0" class="table">

                          <thead>
                            <tr>
                                
                                <th data-class="expand">Segmento</th>
                                <th>Meta</th>
                                <th data-hide="phone">Compras a la fecha</th>
                                <th data-hide="phone">%</th>
                                <th data-hide="phone">Falta para el 100%</th>
                                <th data-hide="phone,tablet">Al 100% el bono sería de</th>
                                <th data-hide="phone,tablet">Falta para el 115%</th>
                                <th data-hide="phone,tablet">Al 115% el bono sería de</th>
                                <th data-hide="phone,tablet">Bono</th>
                                <th data-hide="phone,tablet">Detalles</th>
                            </tr>
                          </thead>
                         
                          <tbody>
                            <tr>
                          	<?php $periodo=$row_consultaAlmacen['idPeriodo'];
								$categoria=$row_consultaAlmacen['idCategoria']; ?>
                           
                            <td><?php echo $row_consultaAlmacen['segmento']; ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['intValor'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['ventas'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['cumplimiento'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['falta1'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['ganaria1'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['falta2'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['ganaria2'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_consultaAlmacen['bono'],0,",",","); ?></td>
                            <td><a class="btn btn-danger" href="detalles_almacenes.php?periodo=<?php echo $row_consultaAlmacen['idPeriodo'] ?>&almacen=<?php echo $_GET['almacen'] ?>&cat=<?php echo $categoria ?>">Detalles</a></td>
                          </tr>
                          
                           <tr>
                          	<?php $periodo=$row_categoria2['idPeriodo'];
								$categoria=$row_categoria2['idCategoria']; ?>
                           
                            <td><?php echo $row_categoria2['segmento']; ?></td>
                            <td><?php echo number_format ($row_categoria2['intValor'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['ventas'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['cumplimiento'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['falta1'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['ganaria1'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['falta2'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['ganaria2'],0,",",","); ?></td>
                            <td><?php echo number_format ($row_categoria2['bono'],0,",",","); ?></td>
                            <td><a class="btn btn-danger" href="detalles_almacenes.php?periodo=<?php echo $row_categoria2['idPeriodo'] ?>&almacen=<?php echo $_GET['almacen'] ?>&cat=<?php echo $categoria ?>">Detalles</a></td>
                          </tr>
                           <?php if ($totalRows_categoria3 > 0) { // Show if recordset not empty ?>
  <tr>
    <?php $periodo=$row_categoria3['idPeriodo'];
								$categoria=$row_categoria3['idCategoria']; ?>
    
  <td><?php echo $row_categoria3['segmento']; ?></td>
      <td><?php echo number_format ($row_categoria3['intValor'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['ventas'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['cumplimiento'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['falta1'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['ganaria1'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['falta2'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['ganaria2'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['bono'],0,",",","); ?></td>
      <td><a class="btn btn-danger" href="detalles_almacenes.php?periodo=<?php echo $row_categoria3['idPeriodo'] ?>&almacen=<?php echo $_GET['almacen'] ?>&cat=<?php echo $categoria ?>">Detalles</a></td>
     
  </tr>
  <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_categoria3 == 0) { // Show if recordset empty ?>
    <tr>
    <?php $periodo=$row_categoria3['idPeriodo'];
								$categoria=$row_categoria3['idCategoria']; ?>
    
  <td>Nuevas tecnologías (Pastar, Mazo, Magnitude, Tronador
)
</td>
      <td><?php echo number_format ($row_categoria3['intValor'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['ventas'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['cumplimiento'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['falta1'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['ganaria1'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['falta2'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['ganaria2'],0,",",","); ?></td>
      <td><?php echo number_format ($row_categoria3['bono'],0,",",","); ?></td>
      <td><a class="btn btn-danger" href="detalles_almacenes.php?periodo=<?php echo $row_categoria3['idPeriodo'] ?>&cat=<?php echo $categoria ?>">Detalles</a></td>
     
  </tr>
     <?php } // Show if recordset empty ?>
                            </tbody>
                          </table>
                    
                    </div>
              	</div>
           	</div>
   

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
        <script type="text/javascript" src="js/ajax-almacenes.js"></script>
    </body>
</html>
<?php
mysql_free_result($categoria2);

mysql_free_result($categoria3);

mysql_free_result($consultaAlmacen);

mysql_free_result($consultaUser);
?>
