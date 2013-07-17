<?php include_once("../../model/functions.php"); ?>

<!--Librerias de slickgrid-->
<script src="controller/plugins/slickgrid/slick.groupitemmetadataprovider.js"></script>
<script src="controller/plugins/slickgrid/slick.dataview.js"></script>
<script src="controller/plugins/slickgrid/plugins/slick.autotooltips.js"></script>
<!--Librerias del sistema-->
<script src="controller/components/bitacoraWindow.js"></script>
<script src="controller/plugins/slickgrid/lib/grid2Excel.js"></script> 
    
<div id="closeBitacoraButton" class="closer"></div>
    <fieldset>
        <legend>Bitácora del SAS-DOC</legend>        
        <div id="bitacoraGrid"></div>
        <br /><a href="javascript:void(0)" onclick="exportExcel()" class="btn pull-right"><i class="icon-share"></i> Exportar la bitácora a Excel</a>
    </fieldset>
</div>
        