<?php
    header("Content-type: application/json");
	include_once "../conexion.php";
    $jsonData = array();        
     
    // Eliminar notas
    $sql = mysqli_query($link, "INSERT INTO bitacora
								SET id_usuario = '".$_POST['id_usuario']."',
								usuario = '".$_POST['usuario']."',
								id_documento = '".$_POST['id_documento']."',
								numero_documento = '".$_POST['numero_documento']."',								
								evento = '".$_POST['evento']."',
								objeto = '".$_POST['objeto']."';   ");
            
    $jsonData['error_bitacora'] = mysqli_error($link);	    
    $jsonData['msg'] = "Evento de la bitácora -".$_POST['id_documento']."-";	
	echo json_encode($jsonData);
?>
    
