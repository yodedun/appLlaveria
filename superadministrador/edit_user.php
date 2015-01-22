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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbluser SET strUsername=%s, strPass=%s, strName=%s, strCode=%s, strZona=%s, intPerfil=%s, intDistribuidor=%s WHERE idUser=%s",
                       GetSQLValueString($_POST['strUsername'], "text"),
                       GetSQLValueString(md5($_POST['strPass']), "text"),
                       GetSQLValueString($_POST['strName'], "text"),
                       GetSQLValueString($_POST['strCode'], "text"),
                       GetSQLValueString($_POST['strZona'], "text"),
                       GetSQLValueString($_POST['intPerfil'], "int"),
                       GetSQLValueString($_POST['intDistribuidor'], "int"),
                       GetSQLValueString($_POST['idUser'], "int"));

  mysql_select_db($database_conexionmiura, $conexionmiura);
  $Result1 = mysql_query($updateSQL, $conexionmiura) or die(mysql_error());

  $updateGoTo = "list_user.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$varUser_useredit = "0";
if (isset($_GET["userID"])) {
  $varUser_useredit = $_GET["userID"];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_useredit = sprintf("SELECT * FROM tbluser WHERE tbluser.idUser=%s", GetSQLValueString($varUser_useredit, "int"));
$useredit = mysql_query($query_useredit, $conexionmiura) or die(mysql_error());
$row_useredit = mysql_fetch_assoc($useredit);
$totalRows_useredit = mysql_num_rows($useredit);
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
       <div class="panel panel-default">
                <div class="panel-heading"><h3>Lista User</h3></div>
                	<div class="panel-body">
      <p>Agregar User</p>
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
        <table align="center">
          <tr valign="baseline">
            <td nowrap align="right">Username:</td>
            <td><input type="text" name="strUsername" value="<?php echo htmlentities($row_useredit['strUsername'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Pass:</td>
            <td><input type="text" name="strPass" value="<?php echo htmlentities($row_useredit['strPass'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Name:</td>
            <td><input type="text" name="strName" value="<?php echo htmlentities($row_useredit['strName'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Code:</td>
            <td><input type="text" name="strCode" value="<?php echo htmlentities($row_useredit['strCode'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Zona:</td>
            <td><input type="text" name="strZona" value="<?php echo htmlentities($row_useredit['strZona'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Perfil:</td>
            <td><select name="intPerfil">
              <option value="1" <?php if (!(strcmp(1, htmlentities($row_useredit['intPerfil'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>almacen</option>
              <option value="2" <?php if (!(strcmp(2, htmlentities($row_useredit['intPerfil'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>vendedor</option>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Distribuidor:</td>
            <td><input type="text" name="intDistribuidor" value="<?php echo htmlentities($row_useredit['intDistribuidor'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">&nbsp;</td>
            <td><input type="submit" value="Actualizar registro"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="idUser" value="<?php echo $row_useredit['idUser']; ?>">
      </form>
      <p>&nbsp;</p>
                    </div>
	  </div>
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
mysql_free_result($useredit);

mysql_free_result($user);

mysql_free_result($Recordset1);
?>
