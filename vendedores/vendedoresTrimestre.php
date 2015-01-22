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


       <div class="container-fluid">

        
        <div class="  col-md-12 interior">

<div class="container">
    
    <div class="panel panel-default">
        <div class="panel-heading">
 <div class="row">
           <div class="col-md-12 inlineBlock">
            <div class="col-md-7">
              <h3> <span class="glyphicon glyphicon-list-alt"></span> Ventas de Almacenes - <?php echo $_GET['tri'] ?> </h3>
            </div>
            <div class="col-md-5 hidden-xs">
              <div class="btn btn-success pull-right">
                  <a href="javascript:window.print();">Imprimir</a>
              </div>

              <div class="btn-group pull-right">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> Trimestres
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu"> 
                      <li>
                        <a href="vendedoresTrimestre.php?date=2014-01-01&tri=Trimestre 1 ">Trimestre 1</a></li>
                      <li>
                        <a href="vendedoresTrimestre.php?date=2014-04-01&tri=Trimestre 2">Trimestre 2</a>
                      </li>
                      <li>
                        <a href="vendedoresTrimestre.php?date=2014-07-01&tri=Trimestre 3">Trimestre 3</a>
                      </li>
                      <li>
                        <a href="vendedoresTrimestre.php?date=2014-10-01&tri=Trimestre 4">Trimestre 4</a>
                      </li>
                    </ul>
              </div>

              <div class="btn btn-success pull-right">
                  <a href="vendedores.php">Trimestre Actual</a>
              </div>

            </div>
          </div>
</div>

            </div>
        <div class="panel-body">
            <table id="example" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    <th data-hide="phone,tablet">Zona</th>
                    <th data-class="expand">Almacen</th>                    
                    <th data-hide="phone">Meta</th>
                    <th data-hide="phone,tablet">Total Compra</th>
                    <th >Cumplimiento</th>
                    <th data-hide="phone,tablet">Le falta para cumplir</th>
                    <th data-hide="phone">Bono</th>

                    <th data-hide="phone,tablet">Detalle periodo actual</th>
                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->
                <tfoot>
                <tr>
                   
                    <th>Zona</th>
                    <th>Almacen</th>
                    <th>Meta</th>
                    <th>Total Compra</th>
                    <th>Cumplimiento</th>
                    <th>Le falta para cumplir</th>
                    <th>Bono</th>
                 
                    <th>Detalle periodo actual</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
           <div class="col-md-12 inlineBlock">
            <div class="col-md-7">
              <h3> <span class="glyphicon glyphicon-list-alt"></span> Ventas Productos</h3>
            </div>
          </div>
      </div>

            </div>
        <div class="panel-body">
            <table id="exampleProductos" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <!--thead section is required-->
                <thead>
                <tr>
                    
                    <th data-hide="phone,tablet">Producto</th>
                    <th data-class="expand">Total compra</th>                    
                    <th data-hide="phone">Total meta</th>
                    <th data-hide="phone,tablet">Falto</th>
                    <th >Cumplimiento</th>
                    <th data-hide="phone,tablet">Bono</th>
                    <th data-hide="phone">Porcentaje bono</th>

                </tr>
                </thead>
                <!--tbody section is required-->
                <tbody></tbody>
                <!--tfoot section is optional-->
                <tfoot>
                <tr>
                   
                    <th>Producto</th>
                    <th>Total compra</th>                    
                    <th>Total meta</th>
                    <th>Falto</th>
                    <th >Cumplimiento</th>
                    <th>Bono</th>
                    <th>Porcentaje bono</th>
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
        <script type="text/javascript" src="../lib/lodash/lodash.min.js"></script>
        <script type="text/javascript" src="../lib/jquery.dataTables/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../lib/jquery.dataTables.bootstrap3/js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="../files/js/datatables.responsive.js"></script>
        <script type="text/javascript" src="../js/ajax-vendedoresTRI.js"></script>
        <script type="text/javascript" src="../js/ajax-vendedoresTRIProductos.js"></script>
        
    </body>
</html>