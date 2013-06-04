<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    $sql = mysqli_query($link, "SELECT * FROM documento_notas dn
								LEFT JOIN catalogo_usuarios cu
									ON dn.id_usuario = cu.id
								WHERE id_documento = ".$_REQUEST['id_documento'].";");
    
    $jsonData = array();
    while($row = mysqli_fetch_array($sql)){        
		$jsonData[] = array('id' => $row['id'],
							'nota' => $row['nota'],
							'fecha' => $row['fecha'],
							'user' => $row['user'],
							'nombre' => $row['nombre']);        
    }
	echo json_encode($jsonData);
?>