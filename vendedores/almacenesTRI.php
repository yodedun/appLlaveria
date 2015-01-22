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

    <?php include("headerglobal.php"); ?>
<body>
	 <?php include("headervendedor.php"); ?>

          <div id="sticktable" class="container-fluid tableStick hiden">


        
        <div class="col-md-12">

<div class="container interior">
<div class="panel panel-default">
<table id="example2" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered " >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    
                    <th data-class="expand">Almacen</th>                    
                    <th data-hide="phone">Meta</th>
                    <th data-hide="phone,tablet">Total Compra</th>
                    <th data-hide="phone">Cumplimiento</th>
                    <th data-hide="phone,tablet">Le falta para cumplir</th>
                    <th data-hide="phone">Bono</th>
                    <th data-hide="phone">Porcentaje pago</th>
                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->

</table>
</div>
</div>
</div>
</div>


       <div class="container-fluid">


        
        <div class="col-md-12">

<div class="container interior">



    
    <div class="panel panel-default">
        <div class="panel-heading">
         <div class="row">
           <div class="col-md-12 inlineBlock">
              <div class="col-md-9">
                  <h3> <span class="glyphicon glyphicon-list-alt"></span> Ventas de Almacenes - <?php echo $_GET['tri'] ?></h3>
              </div>

              <div class="col-md-3">
                  <div class="btn btn-success pull-right"><a href="javascript:history.back()">Volver</a></div>
                  <div class="btn btn-success pull-right"><a href="javascript:window.print();">Imprimir</a></div>
              </div>
           </div>
          </div>
        </div>
        <div class="panel-body">

                


              <table id="example3" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    
                    <th data-class="expand">Almacen</th>                    
                    <th data-hide="phone">Meta</th>
                    <th data-hide="phone,tablet">Total Compra</th>
                    <th data-hide="phone">Cumplimiento</th>
                    <th data-hide="phone,tablet">Le falta para cumplir</th>
                    <th data-hide="phone">Bono</th>
                    <th data-hide="phone">Porcentaje pago</th>
                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->

            </table>
            <hr>
            <table id="example" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    <th data-class="expand">Producto</th>
                    <th data-hide="phone,tablet">Meta</th>                    
                    <th data-hide="phone">Compras</th>
                    <th data-hide="phone,tablet">%</th>
                    <th data-hide="phone">Le falta para cumplir</th>
                    <th>Bono</th>
                    <th data-hide="phone,tablet">Detalle</th>
                  
                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->
                <tfoot>
                <tr>
                   
                    <th >Producto</th>
                    <th>Meta</th>                    
                    <th >Compras</th>
                    <th >%</th>
                    <th>Le falta para cumplir</th>
                    <th>Bono</th>
                    <th>Detalle</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

<div class="panel panel-default">

       <div class="panel-heading">
         <div class="row">
           <div class="col-md-12 inlineBlock">
              <div class="col-md-9">
                  <h3> <span class="glyphicon glyphicon-list-alt"></span> Bono Semestre </h3>
              </div>


           </div>
          </div>
        </div>
  <div class="panel-body">
    
              <table id="example4" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    <th data-class="expand"></th>
                    <th data-hide="phone,tablet">Meta</th>                    
                    <th data-hide="phone">Compras</th>
                    <th data-hide="phone,tablet">%</th>
                    <th data-hide="phone">Le falta para cumplir</th>
                    <th>Bono</th>
                    <th>Porcentaje pago</th>
                    <th data-hide="phone,tablet">Detalle</th>
                  
                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->
                <tfoot>
                <tr>
                   
                    <th ></th>
                    <th>Meta</th>                    
                    <th >Compras</th>
                    <th >%</th>
                    <th>Le falta para cumplir</th>
                    <th>Bono</th>
                    <th>Porcentaje pago</th>
                    <th>Detalle</th>
                </tr>
                </tfoot>
            </table>
  </div>  
</div>    
           <footer>


        <hr>
        <p>&copy; Dow AgroSciences Colombia - 2014</p>
      </footer>
</div>

          


        </div>

      </div>  
<div class="barras"></div>


           <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="../js/vendor/bootstrap.min.js"></script>

        <script src="../js/plugins.js"></script>
        <script src="../js/main.js"></script>
        <script src="../js/jquery.sticky.js"></script>       
        
        <script type="text/javascript" src="../lib/lodash/lodash.min.js"></script>
        <script type="text/javascript" src="../lib/jquery.dataTables/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../lib/jquery.dataTables.bootstrap3/js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="../files/js/datatables.responsive.js"></script>
        <script type="text/javascript" src="../js/ajax-almacenesLLTRI.js"></script>
        <script type="text/javascript" src="../js/ajax-almacenesLL2TRI.js"></script>
        <script type="text/javascript" src="../js/ajax-almacenesLL2-2TRI.js"></script>
        <script type="text/javascript" src="../js/ajax-almacenesLLSEMTRI.js"></script>
        <script>
          $(document).ready(function(){
            $("#sticktable").sticky({topSpacing:114});

          });
        </script>
        
    </body>
</html>