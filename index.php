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
       
        <!-- google web fonts -->              
            <link href='http://fonts.googleapis.com/css?family=Raleway:400,700' rel='stylesheet' type='text/css'>

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
            <script src="app/controller/ui/minified/jquery-ui.min.js"></script>
        <!-- bootstrap Framework plugins -->
            <!--<script src="app/controller/bootstrap.min.js"></script>             -->
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
    
</body>
</html>
