
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



function ObtenerNombreUsuario($identificador)
{
  global $database_conexionmiura, $conexionmiura;
  mysql_select_db($database_conexionmiura, $conexionmiura);
  $query_consultaFuncion = sprintf("SELECT strName FROM tbluser WHERE idUser = %s", $identificador);
  $consultaFuncion = mysql_query($query_consultaFuncion, $conexionmiura) or die(mysql_error());
  $row_consultaFuncion = mysql_fetch_assoc($consultaFuncion);
  $totalRows_consultaFuncion = mysql_num_rows($consultaFuncion);
  
  
   echo $row_consultaFuncion['strName'];
  mysql_free_result($consultaFuncion);
}


function ObtenerDistribuidor($identificador)
{
  global $database_conexionmiura, $conexionmiura;
  mysql_select_db($database_conexionmiura, $conexionmiura);
  $query_consultaFuncion = sprintf("SELECT tbldistribuidor.strDistribuidor FROM tbluser, tbldistribuidor WHERE tbluser.idUser = %s and tbluser.intdistribuidor = tbldistribuidor.idDistribuidor", $identificador);
  $consultaFuncion = mysql_query($query_consultaFuncion, $conexionmiura) or die(mysql_error());
  $row_consultaFuncion = mysql_fetch_assoc($consultaFuncion);
  $totalRows_consultaFuncion = mysql_num_rows($consultaFuncion);
  
  
   echo $row_consultaFuncion['strDistribuidor'];
  mysql_free_result($consultaFuncion);
}

function ObtenerCierre($identificador)
{
  global $database_conexionmiura, $conexionmiura;
  mysql_select_db($database_conexionmiura, $conexionmiura);
  $query_consultaFuncion = sprintf("SELECT tblCierre.dateCierre FROM tbluser, tbldistribuidor, tblCierre WHERE tbluser.idUser = %s and tbluser.intdistribuidor = tblCierre.idDistribuidor", $identificador);
  $consultaFuncion = mysql_query($query_consultaFuncion, $conexionmiura) or die(mysql_error());
  $row_consultaFuncion = mysql_fetch_assoc($consultaFuncion);
  $totalRows_consultaFuncion = mysql_num_rows($consultaFuncion);
  
  
   echo $row_consultaFuncion['dateCierre'];
  mysql_free_result($consultaFuncion);
}

function ObtenertrimestreActual($identificador)
{
  global $database_conexionmiura, $conexionmiura;
  mysql_select_db($database_conexionmiura, $conexionmiura);
  $query_consultaFuncion = sprintf("SELECT tblCierre.dateCierre FROM tbluser, tbldistribuidor, tblCierre WHERE tbluser.idUser = %s and tbluser.intdistribuidor = tblCierre.idDistribuidor", $identificador);
  $consultaFuncion = mysql_query($query_consultaFuncion, $conexionmiura) or die(mysql_error());
  $row_consultaFuncion = mysql_fetch_assoc($consultaFuncion);
  $totalRows_consultaFuncion = mysql_num_rows($consultaFuncion);
  
  
   echo $row_consultaFuncion['dateCierre'];
  mysql_free_result($consultaFuncion);
}


?>




