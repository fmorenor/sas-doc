<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    
    $sql = mysqli_query($link, "SELECT *,
                                CONCAT(SUBSTRING_INDEX(path,'.',1),'.JPG') as thumb
                                FROM documento_adjuntos
                                WHERE id_documento = ".$_REQUEST['id_documento']);
    
    $jsonData = array();
    while($row = mysqli_fetch_array($sql)){
        //if(file_exists("../documents/".$row['path'])){
            $thumb = (file_exists("../documents/".$row['thumb'])) ? "../documents/".$row['thumb'] : "assets/img/thumb_null.png";
            $jsonData[] = array('path' => '../documents/'.$row['path'],
                                'thumb' => $thumb);
        //}
    }
	
	echo json_encode($jsonData);
?>