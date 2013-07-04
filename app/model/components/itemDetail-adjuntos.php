<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    
    $sql = mysqli_query($link, "SELECT *,
                                CONCAT(SUBSTRING_INDEX(path,'.',1),'_thumb.jpg') as thumb
                                FROM documento_adjuntos
                                WHERE id_documento = ".$_REQUEST['id_documento']);
    
    $jsonData = array();
    while($row = mysqli_fetch_array($sql)){
		$system_path = str_replace("app\model\components", "", dirname(__FILE__)).'documents/';		
		$thumb = (file_exists($system_path.$row['thumb'])) ? "../documents/".$row['thumb'] : "assets/img/thumb_null.png";
		$jsonData[] = array('path' => '../documents/'.$row['path'],
							'thumb' => $thumb);
    }
	
	echo json_encode($jsonData);
?>