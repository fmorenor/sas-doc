<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    $sql = mysqli_query($link, "SELECT dp.id,
									dp.numero_documento,
									dp.asunto,
									dp.id_estatus,
									ce.estatus
								FROM documentos d
								INNER JOIN documentos dp
									ON dp.id_documento_padre = d.id
								LEFT JOIN catalogo_estatus ce
									ON dp.id_estatus = ce.id
								WHERE dp.id_documento_padre = ".$_REQUEST['id_documento'].";");
    
    $jsonData = array();
    while($row = mysqli_fetch_array($sql)){
		
		switch($row['id_estatus']){
			case 1: $label_estatus = "important"; break;
			case 2: $label_estatus = "warning"; break;
			case 3: $label_estatus = "success"; break;
			case 4: 
			case 5: $label_estatus = "info"; break;
		}
		
		$jsonData[] = array('id' => $row['id'],
							'numero_documento' => $row['numero_documento'],
							'asunto' => $row['asunto'],
							'estatus' => $row['estatus'],
							'label_estatus' => $label_estatus);        
    }
	echo json_encode($jsonData);
?>