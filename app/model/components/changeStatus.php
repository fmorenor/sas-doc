<?php
    header("Content-type: application/json");
	include_once "../conexion.php";
    $jsonData = array();        
     
    // Eliminar notas
    $sql = mysqli_query($link, "UPDATE documentos
								SET id_estatus = '".$_POST['id_estatus']."'
								WHERE id = '".$_POST['id_documento']."';   ");
            
    $jsonData['error_estatus'] = mysqli_error($link);
	
	$sql = mysqli_query($link, "SELECT * FROM catalogo_estatus
								WHERE id = '".$_POST['id_estatus']."';   ");
	
	while($row = mysqli_fetch_array($sql)){
		
		switch($row['id']){
			case 1: $label_estatus = "important"; break;
			case 2: $label_estatus = "warning"; break;
			case 3: $label_estatus = "success"; break;
			case 4: $label_estatus = "inverse"; break;
			case 5: $label_estatus = "info"; break;
		}
		
		$jsonData['id_estatus'] = $row['id'];
		$jsonData['estatus'] = $row['estatus'];
		$jsonData['label_estatus'] = $label_estatus;
		
	}
	
    $jsonData['msg'] = "Cambió el estatus del documento -".$_POST['id_documento']."-";	
	echo json_encode($jsonData);
?>
    
