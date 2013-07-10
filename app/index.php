<?php session_start(); ?>
<!DOCTYPE html>

<html>
<head>
    <title>SAS-DOC .: Sistema de administraci&oacute;n y seguimiento de documentos</title>
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link rel="icon" type="image/ico" href="favicon.ico">
		
	<!-- bootstrap framework css -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/bootstrap-responsive.min.css">
			
	<!-- google web fonts -->
		<!--<link href='http://fonts.googleapis.com/css?family=Raleway:400,700' rel='stylesheet' type='text/css'>-->
	
	<!-- main stylesheet -->			           
		<link rel="stylesheet" href="assets/css/main.css">  
		<link rel="stylesheet" href="assets/css/ui/jquery-ui.css">
		<link rel="stylesheet" href="assets/css/plugins/jquery.throbber.css" />
		<link rel="stylesheet" href="assets/css/plugins/bootstrap-timepicker.min.css" />
		<link rel="stylesheet" href="assets/css/itemList.css">
	
	<!-- Slick Grid-->
		<link rel="stylesheet" href="controller/plugins/slickgrid/slick.grid.css" type="text/css"/>
		<link rel="stylesheet" href="controller/plugins/slickgrid/controls/slick.pager.css" type="text/css"/>
		<link rel="stylesheet" href="controller/plugins/slickgrid/controls/slick.columnpicker.css" type="text/css"/>
		
	<!-- Carrousel -->
		<link rel="stylesheet" href="assets/css/detail-carrousel.css" >
			
	<!-- Select 2 -->
		<link href="controller/plugins/select2/select2.css" rel="stylesheet"/>
	
	<!-- PLUPLOAD -->
		<link rel="stylesheet" href="controller/plugins/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" media="screen" />
    
	 <!--[if gte IE 9]>
        <style type="text/css">
          .gradient {
             filter: none;
          }
        </style>
    <![endif]-->
	
	<!--[if lte IE 8]><link rel="stylesheet" href="app/assets/css/ie/ie8.css"><![endif]-->
    <!--[if IE 9]><link rel="stylesheet" href="app/assets/css/ie/ie9.css"><![endif]-->
            
    <!--[if lt IE 9]>
        <script src="app/controller/ie/html5shiv.min.js" async="true"></script>
        <script src="app/controller/ie/respond.min.js" async="true"></script>
    <![endif]-->
    
	<!-- jQuery -->
        <script src="controller/jquery-1.9.1.min.js"></script>
		
	<!-- bootstrap Framework plugins -->
        <script src="controller/bootstrap.min.js"></script>
		
	<!-- main scripts -->	
		<script src="controller/ui/minified/jquery-ui.min.js"></script>
		<script src="controller/plugins/jquery.throbber.js"></script>
		<script src="controller/ui/minified/i18n/jquery.ui.datepicker-es.min.js" async="true"></script>
		<!--<script src="controller/plugins/jquery.timepicker.js"></script>-->
		<script src="controller/plugins/bootstrap-timepicker.min.js" async="true"></script>
		
		
	<!-- highcharts -->	
		<script src="controller/plugins/highcharts/highcharts.js"  async="true"></script>
		<script src="controller/plugins/highcharts/highcharts-more.js"  async="true"></script>
		<script src="controller/plugins/highcharts/exporting.js"  async="true"></script>
		<script src="controller/plugins/jquery.json-2.3.min.js"  async="true"></script>
		<!--<script src="controller/css3-mediaqueries.js" async="true"></script>-->
	
	<!-- diTimeout: para executar solo una vez al redimensionar la ventana -->
		<script src="controller/plugins/jquery.ba-dotimeout.min.js"  async="true"></script>
		
	<!-- Plugin para agregar ellispis a textos multilineas muy largos -->
		<script src="controller/plugins/jquery.dotdotdot-1.5.7-packed.js"  async="true"></script>
	
	<!-- Slickgrid -->
		<script src="controller/plugins/slickgrid/lib/firebugx.js" async="true"></script>
		<script src="controller/plugins/slickgrid/lib/jquery.event.drag-2.2.js" async="true"></script>  
		<script src="controller/plugins/slickgrid/slick.core.js" async="true"></script>
		<script src="controller/plugins/slickgrid/slick.formatters.js" async="true"></script>		
		<script src="controller/plugins/slickgrid/slick.grid.js" async="true"></script>
		<!--<script src="controller/plugins/slickgrid/slick.groupitemmetadataprovider.js"></script>-->
		<!--<script src="controller/plugins/slickgrid/slick.dataview.js"></script>-->
		<!--<script src="controller/plugins/slickgrid/controls/slick.columnpicker.js"></script>-->
		<!--<script src="controller/plugins/slickgrid/slickgrid.functions.js" ></script>-->
		<!--<script src="controller/plugins/slickgrid/plugins/slick.autotooltips.js"></script>-->
		<!--<script src="controller/plugins/slickgrid/plugins/slick.rowselectionmodel.js"></script>	-->
		
		
		<!-- Plugin carrusel -->
		<script src="controller/plugins/jquery.carouFredSel-6.2.1-packed.js" async="true"></script>	
		<script src="controller/plugins/helper-plugins/jquery.mousewheel.min.js" async="true"></script>
		<script src="controller/plugins/helper-plugins/jquery.touchSwipe.min.js" async="true"></script>
		
		<!-- Resaltar texto de busqueda en el listado -->
		<script src="controller/plugins/jquery.highlight.js" async="true"></script>
		
		<!-- Select2 -->
		<script src="controller/plugins/select2/select2.js" async="true"></script>
		<script src="controller/plugins/select2/select2_locale_es.js" async="true"></script>
		
		<!-- PLUPLOAD -->
		<script src="controller/plugins/plupload/plupload.js"></script>
		<script src="controller/plugins/plupload/plupload.html5.js" async="true"></script>
		<script src="controller/plugins/plupload/plupload.flash.js" async="true"></script>
		<script src="controller/plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js" async="true"></script>
		<script src="controller/plugins/plupload/i18n/es.js" async="true"></script>

	
	<?php
        if(isset($_SESSION['appSessionSASDOC'])){
            ?>
			<script type="text/javascript" src="controller/app.js"></script>
            <?php
        } else {
            echo "<script>window.location = '../'; </script>";
        }
    ?>
	
	<script type="text/javascript">

		//var _gaq = _gaq || [];
		//_gaq.push(['_setAccount', 'UA-36518081-1']);
		//_gaq.push(['_trackPageview']);
		// 
		//(function() {
		//  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		//  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		//  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		//})();
	  
	</script>
</head>
<body>	
	<header>
        <div class="container" id="encabezado"></div>		
    </header>
	<!-- Nav Container -->
	<div id="nav-container"></div>
	<!-- Contenido -->
    <div id="content1"></div>
	<div class="modal-back">&nbsp;</div>
</body>
</html>