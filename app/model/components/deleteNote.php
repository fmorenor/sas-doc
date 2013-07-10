<?php
    header("Content-type: application/json");
	include_once "../conexion.php";
    $jsonData = array();        
     
    // Eliminar notas
    $sql = mysqli_query($link, "DELETE FROM documento_notas WHERE id = '".$_REQUEST['id_nota']."'; ");
	    
    $jsonData['msg'] = "Nota -".$_REQUEST['id_nota']."- eliminada";	
	echo json_encode($jsonData);
?>
    
