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

mysql_select_db($database_conexionmiura, $conexionmiura);
$query_Recordset1 = "SELECT * FROM tbldistribuidor";
$Recordset1 = mysql_query($query_Recordset1, $conexionmiura) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>

<?php include('header.php'); ?>
<div class="row">
      <p>Agregar</p>
		<table border="0" class="table">
  <tr>
    <td>Distribuidores</td>
    <td>Acciones</td>
  </tr>
  <?php do { ?>
<tr>
    <td><?php echo $row_Recordset1['strDistribuidor']; ?></td>
    <td>editar - eliminar</td>
  </tr>
<?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
        </table>


      </div>
<?php include('footer.php'); ?>
  
<?php
mysql_free_result($Recordset1);
?>
