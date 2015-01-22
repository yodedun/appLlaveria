<?php require_once('Connections/conexionmiura.php'); ?>
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
$query_consultaUser = "SELECT * FROM tbluser";
$consultaUser = mysql_query($query_consultaUser, $conexionmiura) or die(mysql_error());
$row_consultaUser = mysql_fetch_assoc($consultaUser);
$totalRows_consultaUser = mysql_num_rows($consultaUser);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password= md5($_POST['pass']);
  $MM_fldUserAuthorization = "intPerfil";
  $MM_redirectLoginSuccess = "redireccion.php";
  $MM_redirectLoginFailed = "loginfallo.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conexionmiura, $conexionmiura);
  	
  $LoginRS__query=sprintf("SELECT idUser, strUsername, strPass, intPerfil FROM tbluser WHERE strUsername=%s AND strPass=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conexionmiura) or die(mysql_error());
  $row_LoginRS = mysql_fetch_assoc($LoginRS);
  $loginFoundUser = mysql_num_rows($LoginRS);
  
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'intPerfil');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_idUsuario'] = $row_LoginRS["idUser"];        

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>

    <div class="navbar navbar-default navbar-fixed-top blanco">
 
        
        <div class="navbar-header">
          
          <a class="navbar-brand" href="index.php"><img src="http://programamiura.com/llaveria/img/logodow.png" width="200px" style="margin: 19px 14px"></a>
          
        </div>
        <div class="slogan pull-right">Soluciones para un Mundo en Crecimiento</div>


          
          
        <!--/.navbar-collapse -->

    </div>

    <div class="navbar navbar-default navbar-fixed-top top2">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">
            LLAVERIA
          </a>
        </div>
        
          
          
          
           <?php  
if ((isset($_SESSION['MM_Username'])) && ($_SESSION['MM_Username'] != ""))
   {
	    
	  
	   ?>
     <div class="navbar-collapse collapse">
       	   <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="almacen.php">Mis Almacenes</a></li>
                     
          
            
          </ul>
          <form class="navbar-form navbar-right">
         
          <div class="form-group">
              <a href="redireccion.php"><span class="namelogin">   <span class="glyphicon glyphicon-user"> </span> Hola 
			  <?php echo $_SESSION['MM_Username'] ?> </span></a>  
            </div>
            
            <button type="button" class="btn btn-default"  onclick="location.href = 'salir_allusers.php'">
            Salir
            </button>
          </form>
      </div>
   <?php
   }
   else
   {
  ?>  
  <div class="navbar-collapse collapse">
     <ul class="nav navbar-nav navbar-right">
            
            <li><a href="index.php"> <span class="glyphicon glyphicon-envelope"></span> Soporte</a></li>
    
            <li class="mobil"><?php include("includes/module_login.php"); ?></li>
            
          </ul>
  		<!--<form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="login" class="navbar-form navbar-right" id="login">
            <div class="form-group">
              <input name="username" type="text" placeholder="username" class="form-control">
            </div>
            <div class="form-group">
              <input name="pass" type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-default">Ingresar</button>
          </form>-->
  
    </div>
  <?php } ?>
          
          
        <!--/.navbar-collapse -->
     
    </div>

<?php
mysql_free_result($consultaUser);
?>


