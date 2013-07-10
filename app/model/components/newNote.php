<?php
    header("Content-type: application/json");
	include_once "../conexion.php";
    $jsonData = array();        
     
    // Eliminar notas
    $sql = mysqli_query($link, "INSERT INTO documento_notas
								SET id_documento = '".$_POST['id_documento']."',
								id_usuario = '".$_POST['id_usuario']."',
								nota = '".$_POST['nota']."',
								fecha = '".date('Y-m-d H:i:s')."';   ");
            
    $jsonData['error_notas'] = mysqli_error($link);	    
    $jsonData['msg'] = "Nota agregada al documento -".$_POST['id_documento']."-";	
	echo json_encode($jsonData);
?>
    
