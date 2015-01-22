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

$varUser_consultadetalles = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_consultadetalles = $_SESSION['MM_idUsuario'];
}
$varCategoria_consultadetalles = "0";
if (isset($_GET["cat"])) {
  $varCategoria_consultadetalles = $_GET["cat"];
}
$varPeriodo_consultadetalles = "0";
if (isset($_GET["periodo"])) {
  $varPeriodo_consultadetalles = $_GET["periodo"];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_consultadetalles = sprintf("SELECT ve.strDocumento, pr.strProducto, ve.intVenta, ca.strdescripcion as categoria, ve.dateFecha, pe.strDescripcion as periodo FROM tblventas as ve, tblproductos as pr, tblcategorias as ca , tblperiodo as pe, tbluser as us WHERE us.idUser = %s AND ca.idCategoria=%s AND pe.idPeriodo = %s AND ve.intUser = us.idUser  AND ve.dateFecha BETWEEN pe.dateComienzo AND pe.datefin AND ve.intProducto = pr.codProducto AND pr.intCategoria = ca.idCategoria ", GetSQLValueString($varUser_consultadetalles, "int"),GetSQLValueString($varCategoria_consultadetalles, "int"),GetSQLValueString($varPeriodo_consultadetalles, "int"));
$consultadetalles = mysql_query($query_consultadetalles, $conexionmiura) or die(mysql_error());
$row_consultadetalles = mysql_fetch_assoc($consultadetalles);
$totalRows_consultadetalles = mysql_num_rows($consultadetalles);
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
        <h1> Hola <?php echo $_SESSION['MM_Username'] ?></h1>
        <p>Programa Miura - Gana mucho más con Miura de Dow AgroSciences Colombia</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      
        <div class="panel panel-default">
                <div class="panel-heading"><h3><?php echo $row_consultadetalles['categoria']; ?> en <?php echo $row_consultadetalles['periodo']; ?> <div class="btn btn-danger pull-right"><a href="javascript:history.back()">Volver</a></div></h3> </div>
                	<div class="panel-body">
                    
                    	  <table border="0" class="table">
                              <tr>
                                <th>Documento</th>
                                <th>Producto</th>
                                <th>Valor</th>
                                <th>Fecha</th>
                             
                              </tr>
                              <?php do { ?>
                          <tr>
                            <td><?php echo $row_consultadetalles['strDocumento']; ?></td>
                            <td><?php echo $row_consultadetalles['strProducto']; ?></td>
                            <td><?php echo number_format ($row_consultadetalles['intVenta'],0,",","."); ?></td>
                            <td><?php echo $row_consultadetalles['dateFecha']; ?></td>
                           
                          </tr>
                          <?php } while ($row_consultadetalles = mysql_fetch_assoc($consultadetalles)); ?>
                              </table>
                    
                    </div>
              	</div>
           	</div>
   

      <hr>

      <footer>
        <p>&copy; Dow AgroSciences Colombia - 2013</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php
mysql_free_result($consultadetalles);

mysql_free_result($consultaAlmacen);

mysql_free_result($consultaUser);
?>
