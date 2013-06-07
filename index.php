<!DOCTYPE html>

<html>
<head>
    <title>SAS-DOC .: Sistema de administraci&oacute;n y seguimiento de documentos</title>
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
        <link rel="icon" type="image/ico" href="favicon.ico">
        
    <!-- common stylesheets-->
        <!-- bootstrap framework css -->
            <link rel="stylesheet" href="app/assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="app/assets/css/bootstrap-responsive.min.css">
    
    <!-- aditional stylesheets -->

        <!-- main stylesheet -->              
            <link rel="stylesheet" href="app/assets/css/main.css">  
            <link rel="stylesheet" href="app/assets/css/ui/jquery-ui.css">

        <!--[if lte IE 8]><link rel="stylesheet" href="app/assets/css/ie/ie8.css"><![endif]-->
        <!--[if IE 9]><link rel="stylesheet" href="app/assets/css/ie/ie9.css"><![endif]-->
            
        <!--[if lt IE 9]>
            <script src="app/controller/ie/html5shiv.min.js"></script>
            <script src="app/controller/ie/respond.min.js"></script>
        <![endif]-->
        
        <!-- Common JS -->
        <!-- jQuery framework -->
            <script src="app/controller/jquery-1.9.1.min.js"></script>
        <!-- jQuery UI -->
            <script src="app/controller/ui/minified/jquery-ui.min.js" async="true"></script>
        <!-- bootstrap Framework plugins -->
            <script src="app/controller/bootstrap.min.js" async="true"></script>
        <!-- common functions -->
            <script src="login/controller/index.js"></script>
</head>

<body>
    <?php
        @session_start();        
        if(isset($_SESSION['appSessionSASDOC'])){
             echo "<script>window.location = 'app/'; </script>";
        }
    ?>
    <header>
        <div class="container" id="encabezado"></div>
    </header>

    <!-- Contenido -->
    <div class="container" id="login"></div>
    
    <!-- Navegador viejo -->
    <div id="browserMessage" style="display: none; padding-top: 40px;">
        <div class="row-fluid">    
            <div class="span6 offset3 alert alert-block">                
                <h4>Espera</h4>
                <br />
                <p>Estas usando un navegador inseguro y que no cumple con los estandares internacionales de la W3C. Por favor utiliza por lo menos Internet Explorer 10 o un navegador m&aacute;s moderno y seguro como:</p>
                <br />
                <div class="btn-toolbar offset2">
                    <div class="btn-group">
                        <button class="btn" onclick='window.open("http://www.mozilla.org/es-MX/firefox/fx/")'><i class="icon-download"></i> Mozilla Firefox</button>
                        <button class="btn" onclick='window.open("https://www.google.com/intl/es/chrome/")'><i class="icon-download"></i> Google Chrome</button>
                        <button class="btn" onclick='window.open("http://www.opera.com/download")'><i class="icon-download"></i> Opera</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
