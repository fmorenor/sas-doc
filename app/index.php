<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<!DOCTYPE html>

<html>
<head>
    <title>SAS-DOC .: Sistema de administraci&oacute;n y seguimiento de documentos</title>
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link rel="icon" type="image/ico" href="favicon.ico">
		
	<!-- bootstrap framework css -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
			
	<!-- google web fonts -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Abel">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300">
	
	<!-- main stylesheet -->			
		<link rel="stylesheet" href="css/beoro.css">                
		<link rel="stylesheet" href="css/main.css">  
		<link rel="stylesheet" href="css/ui/jquery-ui.css">						
		<link rel="stylesheet" href="css/ui/jquery.ui.selectmenu.css"  />
		<link rel="stylesheet" href="css/plugins/jquery.throbber.css" />
	
	<!-- Slick Grid-->
		<link rel="stylesheet" href="controller/plugins/slickgrid/slick.grid.css" type="text/css"/>
		<link rel="stylesheet" href="controller/plugins/slickgrid/controls/slick.pager.css" type="text/css"/>
		<link rel="stylesheet" href="controller/plugins/slickgrid/controls/slick.columnpicker.css" type="text/css"/>
    
	 <!--[if gte IE 9]>
        <style type="text/css">
          .gradient {
             filter: none;
          }
        </style>
    <![endif]-->
	
	<!--[if lte IE 8]><link rel="stylesheet" href="app/css/ie/ie8.css"><![endif]-->
    <!--[if IE 9]><link rel="stylesheet" href="app/css/ie/ie9.css"><![endif]-->
            
    <!--[if lt IE 9]>
        <script src="app/controller/ie/html5shiv.min.js"></script>
        <script src="app/controller/ie/respond.min.js"></script>
    <![endif]-->
    
	<!-- main scripts -->
        <script src="controller/jquery-1.9.1.min.js"></script>
		<script src="controller/ui/minified/jquery-ui.min.js"></script>
		<script src="controller/ui/jquery.ui.selectmenu.js"></script>
		<script src="controller/plugins/jquery.throbber.js" ></script>
		
	<!-- highcharts -->	
		<script src="controller/plugins/highcharts/highcharts.js" ></script>
		<script src="controller/plugins/highcharts/highcharts-more.js" ></script>
		<script src="controller/plugins/highcharts/exporting.js" ></script>
		<script src="controller/plugins/jquery.json-2.3.min.js" ></script>
		<script src="controller/css3-mediaqueries.js"></script>
	
	<!-- bootstrap Framework plugins -->
        <script src="controller/bootstrap.min.js"></script>   
	
	 <!-- diTimeout: para executar solo una vez al redimensionar la ventana -->
		<script src="controller/plugins/jquery.ba-dotimeout.min.js" ></script>
	
	<!-- Slickgrid -->
		<script src="controller/plugins/slickgrid/lib/firebugx.js"></script>
		<script src="controller/plugins/slickgrid/lib/jquery.event.drag-2.2.js"></script>  
		<script src="controller/plugins/slickgrid/slick.core.js"></script>
		<script src="controller/plugins/slickgrid/slick.formatters.js"></script>
		<script src="controller/plugins/slickgrid/plugins/slick.autotooltips.js"></script>
		<script src="controller/plugins/slickgrid/plugins/slick.rowselectionmodel.js"></script>	
		<script src="controller/plugins/slickgrid/slick.grid.js"></script>
		<script src="controller/plugins/slickgrid/slick.groupitemmetadataprovider.js"></script>
		<script src="controller/plugins/slickgrid/slick.dataview.js"></script>
		<script src="controller/plugins/slickgrid/controls/slick.columnpicker.js"></script>
		<script src="controller/plugins/slickgrid/slickgrid.functions.js" ></script>
	
	<?php
        @session_start();
        if(isset($_SESSION['appSessionSASDOC'])){
            ?>
			<script type="text/javascript" src="controller/app.js"></script>
            <?php
        } else {
            echo "<script>window.location = '../'; </script>";
        }
    ?>
	
	<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-36518081-1']);
		_gaq.push(['_trackPageview']);
	  
		(function() {
		  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	  
	</script>
</head>
<body>	
	<header>
        <div class="container" id="encabezado"></div>
    </header>
	<!-- Contenido -->
    <div class="container" id="content1"></div>
</body>
</html>