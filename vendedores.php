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

    <?php include("headerglobal.php"); ?>
<body>
	 <?php include("headervendedor.php"); ?>
<div class="jumbotron">
      <div class="container">
        <h1>Bienvenido</h1>
        <p>Al programa MIURA de DOW AGROSCIENCES</p>
        <!--<p><a class="btn btn-primary btn-lg">Learn more &raquo;</a></p>-->
      </div>
    </div>
<div class="container">
    
    <div class="panel panel-default">
        <div class="panel-heading"> <h3>Almacenes - <?php echo $_SESSION['MM_Username'] ?> </h3></div>
        <div class="panel-body">
            <table id="example" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    <th data-class="expand">Zona</th>
                    <th>Nombre</th>
                    <th data-hide="phone">Metas cumplidas</th>
                    <th data-hide="phone">Meta</th>
                    <th data-hide="phone">Total</th>
                    <th data-hide="phone,tablet">Detalle periodo actual</th>
                    <th data-hide="phone,tablet">Periodos pasados</th>
                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->
                <tfoot>
                <tr>
                   
                    <th>Zona</th>
                    <th>Nombre</th>
                    <th>Metas cumplidas</th>
                    <th>Meta</th>
                    <th>Total</th>
                    <th>Detalle periodo actual</th>
                    <th>Periodos pasados</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


     <div class="container"> 
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
        <script type="text/javascript" src="js/ajax-vendedores.js"></script>
        
    </body>
</html>