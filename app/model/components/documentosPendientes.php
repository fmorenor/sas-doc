<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	include_once "../functions.php";


	//Calculos para obtener fechas de los documentos	
	$hoy = time();
	$fecha_recepcion_inicial = "2008-01-01 00:00:00";
	$fecha_recepcion_hoy = date("Y-m-d") . " 23:59:59";
	
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
						//$jsonData['id_usuario_array'][] = $row['id'];
					}
					$id_usuario_involucrado = implode(",",$id_usuario_array);
					break;
	}
	
	// Obtener el listado de documentos que incluyan al usuario en turnado_a
	$sql_documento_turnado_a = mysqli_query($link, "SELECT id_documento FROM documento_turnado_a WHERE id_turnado_a IN (".$id_usuario_involucrado.") ");
	while($row_documento_turnado = mysqli_fetch_array($sql_documento_turnado_a)){
		$documento_turnado_a .= $row_documento_turnado['id_documento'].",";
	}
		$documento_turnado_a = substr($documento_turnado_a,0,-1);
	// Consulta							
	$sql_principal= mysqli_query($link, "SELECT count(id) as count_pendientes
										FROM documentos
										WHERE fecha_recepcion BETWEEN '".$fecha_recepcion_inicial."'
											AND '".$fecha_recepcion_hoy."'
											AND id_estatus IN (1,2)
											AND vigencia > 0
											AND (id_remitente IN (".$id_usuario_involucrado.")
											OR id_destinatario IN (".$id_usuario_involucrado.")
											OR id_asignado_a IN (".$id_usuario_involucrado.")
											OR id_asignado_por IN (".$id_usuario_involucrado.")
											OR id IN (".$documento_turnado_a."))
											
										ORDER BY fecha_recepcion ASC; ");

	while($row = mysqli_fetch_array($sql_principal)){	
		$jsonData['count_pendientes'][] = $row['count_pendientes'];
	}
	echo json_encode($jsonData);	
	
?>
