<?php 
if (!isset($_SESSION)) {
  session_start();
}
?>
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conexionmiura = "localhost";
$database_conexionmiura = "appLlaveria";
$username_conexionmiura = "admin";
$password_conexionmiura = "admin";
$conexionmiura = mysql_pconnect($hostname_conexionmiura, $username_conexionmiura, $password_conexionmiura) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES 'utf8'"); 
date_default_timezone_set("America/Bogota");
$hoy = date("Y-m-d H:i:s");
?>
<?php 
if (is_file("includes/funtions.php"))
{
	include("includes/funtions.php");
}
else
{
	include("../includes/funtions.php");
}
?>