<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    $sql = mysqli_query($link, "SELECT dt.*,
								cu.user,
								cu.nombre
								FROM documento_turnado_a dt
								LEFT JOIN catalogo_usuarios cu
									ON dt.id_turnado_a = cu.id
								WHERE dt.id_documento = ".$_REQUEST['id_documento']."
									AND cu.activo = 1;");
    
    $jsonData = array();
    while($row = mysqli_fetch_array($sql)){        
		$jsonData[] = array('id_turnado_a' => $row['id_turnado_a'],
							'user' => $row['user'],
							'nombre' => $row['nombre']);        
    }
	echo json_encode($jsonData);
?>