<?php require_once('../Connections/conexionmiura.php'); ?>
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

$MM_restrictGoTo = "../index.php";
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

$varUser_listaC = "0";
if (isset($_SESSION['MM_idUsuario'])) {
  $varUser_listaC = $_SESSION['MM_idUsuario'];
}
mysql_select_db($database_conexionmiura, $conexionmiura);
$query_listaC = sprintf("SELECT
tbluser.strName,
tbluser.strZona,
tbluser.idUser,
tbluser.strCode
FROM
tbluser
RIGHT JOIN tbluserlista ON tbluser.idUser = tbluserlista.idUser
WHERE
tbluserlista.idVendedor = %s", GetSQLValueString($varUser_listaC, "int"));
$listaC = mysql_query($query_listaC, $conexionmiura) or die(mysql_error());
$row_listaC = mysql_fetch_assoc($listaC);
$totalRows_listaC = mysql_num_rows($listaC);


?>
   <?php include("headerglobal.php"); ?>
<body>
    <?php include("headervendedor.php"); ?>

    
    <div class="jumbotron">
      <div class="container">
          <p>Programa Miura - Gana mucho más con Miura de Dow AgroSciences Colombia</p>
          <h2>Lista Almacenes</h2>
      </div>
    </div>

    <div class="container">
     
      
        <div class="panel panel-default">
                    <div class="panel-heading"><h3>Almacenes <div class="btn btn-success pull-right"><a href="vendedores.php">Volver</a></div>   <div class="btn btn-success pull-right"><a href="javascript:window.print();">Imprimir</a></div></h3></div>
                <!--panel -->
                     <div class="panel-body">
                        <table id="example" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">

                          <thead>

                            <tr>
                                
                                <th data-class="expand">Almacen</th>
                                <th>Zona</th>
                                <th>Codigo</th>
                               
                            </tr>
                          </thead>
                         
                          <tbody>
                                <?php do { ?>
                            <tr>
                              
                              <td><?php echo $row_listaC['strName']; ?></td>
                              <td><?php echo $row_listaC['strZona']; ?></td>
                              <td><?php echo $row_listaC['strCode']; ?></td>
                           
                            </tr>
                          
                              <?php } while ($row_listaC = mysql_fetch_assoc($listaC)); ?>

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
        <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="../js/vendor/bootstrap.min.js"></script>

        <script src="../js/plugins.js"></script>
        <script src="../js/main.js"></script>
        <script type="text/javascript" src="../lib/lodash/lodash.min.js"></script>
        <script type="text/javascript" src="../lib/jquery.dataTables/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../lib/jquery.dataTables.bootstrap3/js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="../files/js/datatables.responsive.js"></script>
        <script type="text/javascript" src="../js/ajax-almacenes.js"></script>
    </body>
</html>
<?php
mysql_free_result($listaC);

?>
