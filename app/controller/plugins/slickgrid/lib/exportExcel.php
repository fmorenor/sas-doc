<?php
/*
 * Make sure you link this file up to the table-to-csv.js file with the proper file path.
 * This is what gets called on to create the Excel file after the JS gets the data.
 */
    //header("Content-type: application/vnd.ms-excel; name='excel'");
    //header("Content-Disposition: filename=export.xls");
    //header("Pragma: no-cache");
    //header("Expires: 0");
    //
    //print $_REQUEST['exportdata'];
    
    
/**
 * Gracias al Content-disposition, conseguimos que el navegador nos muestre el diálogo de Guardar...
 * Sería sencillo incorporar en el POST a este script un parámetro adicional para indicar el nombre del fichero
 * deseado para nuestro .XLS.
 * 
 * Francisco Rodríguez Cala
 * http://www.meetworks.com
 * http://www.presenciaeninternet.es
 *
 * Export data, delivered in the POST, to excel.
 * 
 * @author S.Radovanovic
 * @version $Id$
 */
header('ETag: etagforie7download'); //IE7 requires this header
header('Content-type: application/octet_stream');
header('Content-disposition: attachment; filename="Bitácora del SAS-DOC '.date('Y-m-d H:i:s').'.xls"');
 
//Add html tags, so that excel can interpret it
echo "<html>
<head>
<style>
tr td{
    height:100%;
    vertical-align:middle;
}
.header td{
    background-color: #CCCCCC;
    font-size:16px;
    font-weight:bold;
    height:24px;
}
.odd td{
    background-color: #EFEFEF;
}

.nowrap{
    width: 100px;
    white-space: nowrap;      
    font-weight:bold;
}

</style>
</head>
<body>
<table border='1' bordercolor='#DDDDDD'>
".stripslashes(str_replace("undefined","",$_POST['exportdata']))."
</table>
</body>
</html>
";
?>
