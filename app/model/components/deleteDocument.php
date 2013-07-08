<?php
    header("Content-type: application/json");
	include_once "../conexion.php";
    $jsonData = array();
        
    // Eliminar el registro y los archivos adjuntos
    $sql_adjuntos = mysqli_query($link, "SELECT *,
                                CONCAT(SUBSTRING_INDEX(path,'.',1),'_thumb.jpg') as thumb
                                FROM documento_adjuntos
                                WHERE id_documento = ".$_REQUEST['id_documento']);     
    while($row = mysqli_fetch_array($sql_adjuntos)){
		$system_path = str_replace("app\model\components", "", dirname(__FILE__)).'documents/';
        $path = $system_path.$row['path'];
        $thumb = $system_path.$row['thumb'];
        
		if(file_exists($path)){
            unlink($path);
        }
        if(file_exists($thumb)){
            unlink($thumb);
        }
        
		$jsonData['adjuntos'][] = array('path' => $path,
							'thumb' => $thumb);
    }
    $sql = mysqli_query($link, "DELETE FROM documento_adjuntos WHERE id_documento = '".$_REQUEST['id_documento']."'; ");
    
    // Eliminar notas
    $sql = mysqli_query($link, "DELETE FROM documento_notas WHERE id_documento = '".$_REQUEST['id_documento']."'; ");
    
    // Eliminar los turnados_a de este documento
    $sql = mysqli_query($link, "DELETE FROM documento_turnado_a WHERE id_documento = '".$_REQUEST['id_documento']."'; ");
    
    // Eliminar el documento de la tabla documentos
	$sql = mysqli_query($link, "DELETE FROM documentos WHERE id = '".$_REQUEST['id_documento']."'; ");
	    
    $jsonData['msg'] = "Documento -".$_REQUEST['id_documento']."- eliminado";	
	echo json_encode($jsonData);
?>
    
