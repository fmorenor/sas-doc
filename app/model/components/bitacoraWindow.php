<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	include_once "../functions.php";
	
	// Privilegios
	// Si es usuario regular solo se muestran los documentos donde está involucrado.
	// Si es administrador todos los de su grupo.
	switch($_REQUEST['id_privilegios']){
		case '0': $id_usuario_involucrado = $_REQUEST['id_usuario']; break;
		case '1': $sql_privilegios = mysqli_query($link, "SELECT cup.id FROM catalogo_usuarios cu
															LEFT JOIN catalogo_usuarios cup
															ON cu.id_grupo = cup.id_grupo
															where cu.id = '".$_REQUEST['id_usuario']."'
															AND cup.activo = 1;");
					$id_usuario_array = array();
					while($row = mysqli_fetch_array($sql_privilegios)){
						$id_usuario_array[] = $row['id'];
					}
					$id_usuario_involucrado = implode(",",$id_usuario_array);
					break;
	}		
	

	$sql = mysqli_query($link, "SELECT * FROM bitacora
								WHERE id_usuario IN(".$id_usuario_involucrado.")
								ORDER BY fecha_evento DESC;");	
		
	while($row = mysqli_fetch_array($sql)){		
		$jsonData['rows'][] = array(
							'id' => $row['id'],
							'id_usuario' => $row['id_usuario'],
							'usuario' => $row['usuario'],
							'id_documento' => $row['id_documento'],
							'numero_documento' => $row['numero_documento'],
							'evento' => $row['evento'],
							'objeto' => $row['objeto'],
							'fecha_evento' => $row['fecha_evento']
							);
	}
	
	echo json_encode($jsonData);
?>
