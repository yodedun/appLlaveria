<?php require_once('../Connections/conexionmiura.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tblbonos (intCategoria, intPeriodo, intBonouno, intBonodos) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['intCategoria'], "int"),
                       GetSQLValueString($_POST['intPeriodo'], "int"),
                       GetSQLValueString($_POST['intBonouno'], "double"),
                       GetSQLValueString($_POST['intBonodos'], "double"));

  mysql_select_db($database_conexionmiura, $conexionmiura);
  $Result1 = mysql_query($insertSQL, $conexionmiura) or die(mysql_error());

  $insertGoTo = "index.html";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conexionmiura, $conexionmiura);
$query_consultaCategoria = "SELECT * FROM tblcategorias";
$consultaCategoria = mysql_query($query_consultaCategoria, $conexionmiura) or die(mysql_error());
$row_consultaCategoria = mysql_fetch_assoc($consultaCategoria);
$totalRows_consultaCategoria = mysql_num_rows($consultaCategoria);

mysql_select_db($database_conexionmiura, $conexionmiura);
$query_consultaPeriodo = "SELECT * FROM tblperiodo";
$consultaPeriodo = mysql_query($query_consultaPeriodo, $conexionmiura) or die(mysql_error());
$row_consultaPeriodo = mysql_fetch_assoc($consultaPeriodo);
$totalRows_consultaPeriodo = mysql_num_rows($consultaPeriodo);
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

        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/main.css">

        <script src="../js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
          <table align="center">
            <tr valign="baseline">
              <td nowrap align="right">IntCategoria:</td>
              <td><select name="intCategoria">
                <?php 
do {  
?>
                <option value="<?php echo $row_consultaCategoria['idCategoria']?>" ><?php echo $row_consultaCategoria['strdescripcion']?></option>
                <?php
} while ($row_consultaCategoria = mysql_fetch_assoc($consultaCategoria));
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">Periodo:</td>
              <td><select name="intPeriodo">
                <?php 
do {  
?>
                <option value="<?php echo $row_consultaPeriodo['idPeriodo']?>" ><?php echo $row_consultaPeriodo['strDescripcion']?></option>
                <?php
} while ($row_consultaPeriodo = mysql_fetch_assoc($consultaPeriodo));
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">Bonouno:</td>
              <td><input type="text" name="intBonouno" value="" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Bonodos:</td>
              <td><input type="text" name="intBonodos" value="" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">&nbsp;</td>
              <td><input type="submit" value="Insertar registro"></td>
            </tr>
          </table>
          <input type="hidden" name="MM_insert" value="form1">
        </form>
        <p>&nbsp;</p>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="../js/vendor/bootstrap.min.js"></script>

        <script src="../js/plugins.js"></script>
        <script src="../js/main.js"></script>
    </body>
</html>
<?php
mysql_free_result($consultaCategoria);

mysql_free_result($consultaPeriodo);
?>
