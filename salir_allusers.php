<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_idUsuario'] = NULL;  
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_idUsuario']);
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
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
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Bosques de Zapan 4</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">El Conjunto</a></li>
            <li><a href="#">Galeria</a></li>
            <li><a href="#">Clasificados</a></li>
            <li><a href="#">Contacto</a></li>
            
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Ingresar</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- llamado de accion-->
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="mylog">
            </div>
          </div>
          <div class="col-lg-8">
              <h2><strong>Bosques de Zapan 4</strong>
                <p>NIT 99999999-9</p>
                <small><p>Tr 4b # 3 - 75<br>Tel: 6890298</p></small>
              </h2>

          </div>
        </div> 
        
      </div>
    </div>

    <div class="container">
      <!-- columnas -->
      <div class="panel panel-default">
        
        <div class="panel-body">

    	Haz cerrado sesion Correctamente

        </div>
      </div>

      

     
      <hr>

      <footer>
        <div class="container">
          <div class="row">
              <div class="col-lg-8">
                <h3>Mapa del sitio</h3>
                <ul class="listFotter">
                  <li>Inicio</li>
                  <li>El conjunto</li>
                  <li>Galeria</li>
                  <li>Clasificados</li>
                  <li>Sugerencias, Quejas y Reclamos</li>
                </ul>

              </div>
              <div class="col-lg-4">
               
                  <button type="button" class="btn btn-danger btn-lg"  onClick="window.location.href='user_admin.php'">
                    <span class="glyphicon glyphicon-off"> </span> Administración</button>
                
                <div class="btn-group" style="padding-left:13px;">
                    <button type="button" class="btn btn-danger btn-lg">
                      <span class="glyphicon glyphicon-off"></span></button>
                   <button type="button" class="btn btn-danger btn-lg">
                      <span class="glyphicon glyphicon-off"></span></button>
                </div>

              </div>

          </div>

        </div>
        <hr>

        <div class="copy">
          <p>&copy; Administrado por: Carlos Escudero // www.kol3.com // Cel: 318 6523655</p>
        </div>

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
