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

$MM_restrictGoTo = "superadministrador";
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

$varUser_userVendedor = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_userVendedor = $_SESSION['MM_idUsuario'];
}
mysql_query("set sql_big_selects=1");
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_userVendedor = sprintf("SELECT
	nombre as name2,
	id as id2,
	Zona,
	sum(metascum) as metascumplidas,
	periodo as pe,
	Sum(ventasCa) as sum,
	Sum(metasCa) as met,
	GROUP_CONCAT( strMetascum, cat) as categorias
FROM
	(
		SELECT
tbluser.strUsername AS nombre,
tbluser.idUser AS id,
tblcategorias.strdescripcion AS cat,
tblperiodo.strDescripcion AS periodo,
Sum(tblventas.intVenta) AS ventasCa,
tblmetas.intValor AS metasCa,
IF(((Sum(tblventas.intVenta) > tblmetas.intValor )),(1),0) as metascum,
IF(((Sum(tblventas.intVenta) > tblmetas.intValor )),('Cumplio '),'Falta ') as strMetascum,
tbluser.strZona as Zona
FROM
			tbluser
		LEFT JOIN tblventas ON tbluser.idUser = tblventas.intUser
		INNER JOIN tblproductos ON tblventas.intProducto = tblproductos.idProductos
		RIGHT JOIN tblcategorias ON tblcategorias.idCategoria = tblproductos.intCategoria,
		tblperiodo,
		tblmetas,
		tbluserlista
WHERE
		tblcategorias.idCategoria <= 3
	AND NOW() BETWEEN tblperiodo.dateComienzo and tblperiodo.dateFin 
	AND tblventas.dateFecha BETWEEN tblperiodo.dateComienzo
	AND tblperiodo.dateFin
	AND tblmetas.intPeriodo = tblperiodo.idPeriodo
	AND tblmetas.intCategoria = tblcategorias.idCategoria
	AND tblmetas.intUser = tbluser.idUser
	AND tbluserlista.idUser = tbluser.idUser
	AND tbluserlista.idVendedor = %s
GROUP BY
		tbluser.strUsername,
		tbluser.idUser,
		tblcategorias.strdescripcion,
		tblperiodo.strDescripcion,
		tblmetas.intValor
	) as sumas
GROUP BY
	name2,
	Zona,
	id2,
	pe
ORDER BY
Zona ASC", GetSQLValueString($varUser_userVendedor, "int"));
$userVendedor = mysql_query($query_userVendedor, $conexionmiura) or die(mysql_error());
$row_userVendedor = mysql_fetch_assoc($userVendedor);
$totalRows_userVendedor = mysql_num_rows($userVendedor);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
    <?php include("header.php"); ?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg">Learn more &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
       <div class="panel panel-default">
                <div class="panel-heading"><h3>Vendedor <?php echo $row_userVendedor['pe']; ?></h3></div>
                	<div class="panel-body">
                    
                    <table border="0" class="table">
  <tr>
 	<th>Zona</th>
    <th>Almacen</th>
    <th>Metas Cumplidas</th>
    <th>Total Ventas</th>
    <th>Total Meta</th>
    
  </tr>
  <?php do { ?>
  <tr>
  	<td><?php echo $row_userVendedor['Zona']; ?></td>
    <td><?php echo $row_userVendedor['name2']; ?></td>
    <td title="<?php echo $row_userVendedor['categorias']; ?>"><?php echo $row_userVendedor['metascumplidas']; ?></td>
    <td><?php echo $row_userVendedor['sum']; ?></td>
    <td><?php echo $row_userVendedor['met']; ?></td>
    
  </tr>
  <?php } while ($row_userVendedor = mysql_fetch_assoc($userVendedor)); ?>
                    </table>

                    
                    	
                        
		 </div>
      </div>
    </div>

      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php
mysql_free_result($userVendedor);
?>
