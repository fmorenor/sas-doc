<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    
    $id = $_REQUEST['id_documento'];
    
    $sql = mysqli_query($link, "SELECT *,
                                CONCAT(SUBSTRING_INDEX(path,'.',1),'.JPG') as thumb
                                FROM documento_adjuntos
                                WHERE id_documento > ".$id." and id_documento < ".($id+20));
    
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