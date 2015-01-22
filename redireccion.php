<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<?php
// *** Logout the current user.
$pag1 = "almacen/almacenes.php";
$pag2 = "vendedores/vendedores.php";
if ($_SESSION['MM_UserGroup'] == 1) 
	{header("Location: $pag1");
	exit;
	}
elseif ($_SESSION['MM_UserGroup'] == 2) 
	{header("Location: $pag2");
	exit;
	}

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

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 5px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
    

    <!-- llamado de accion-->
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="mylog">
            </div>
          </div>
          <div class="col-lg-8">
              <h2><strong>Programa MIURA</strong>
               
              </h2>

          </div>
        </div> 
        
      </div>
    </div>

    <div class="container">
      <!-- columnas -->
      <div class="panel panel-default">
        
        <div class="panel-body">

    	<?php echo $_SESSION['MM_UserGroup']; ?>

        </div>
      </div>

      

     
      <hr>

      <footer>
        
        <hr>

      

      </footer>
    </div> <!-- /container -->        
      <script src="js/vendor/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
      -->
    </body>
</html>
