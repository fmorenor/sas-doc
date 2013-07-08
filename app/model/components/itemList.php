<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	include_once "../functions.php";
	
	$hoy = time(); // Posix
	//$fecha_hoy = date("Y-m-d") . " 23:59:59";
	
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
	
	// Si hay algún valor en los campos de buqueda se realiza, si no solo se ignora...
	// El ordenamiento se hace inverso DESC -> ASC porque se meten al arreglo del ultimo al primero
	switch($_REQUEST['estatus']){
		case '1,2': $orderBy = "ORDER BY d.fecha_recepcion ASC, d.fecha_actualizacion ASC"; break;
		case '3':	$orderBy = "ORDER BY d.fecha_actualizacion ASC, d.fecha_recepcion ASC"; break;
		case '4,5': $orderBy = "ORDER BY d.fecha_emision ASC, d.fecha_actualizacion ASC"; break;
		default: $orderBy = "ORDER BY d.fecha_recepcion ASC, d.fecha_actualizacion ASC"; break;
	}
	
	if($_REQUEST['searchInput'] != ''){		
		switch($_REQUEST['searchType']){
			case 'general': 	$searchQuery = "AND (";
								$searchQuery .= "d.numero_documento LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= "OR d.asunto LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= "OR d.fecha_emision LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= "OR d.fecha_recepcion LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= "OR d.remitente LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= "OR dest.nombre LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= "OR asig_a.nombre LIKE '%".$_REQUEST['searchInput']."%' ";
								//$searchQuery .= "OR asig_por.nombre LIKE '%".$_REQUEST['searchInput']."%' ";
								$searchQuery .= ")";
								
								//$orderBy = "ORDER BY d.fecha_recepcion ASC, d.fecha_actualizacion ASC"; // Por defecto esta es la ordenación
								
								break;
			case 'recepcion':	$dateFromTo = split(",",$_REQUEST['searchInput']);
								$searchQuery = "AND (";
								$searchQuery .= "d.fecha_recepcion BETWEEN '".$dateFromTo[0]." 00:00:00' AND '".$dateFromTo[1]."' ";
								$searchQuery .= ")";
								
								$orderBy = "ORDER BY d.fecha_recepcion ASC, d.fecha_actualizacion ASC";
								
								break;
			case 'emision':		$dateFromTo = split(",",$_REQUEST['searchInput']);
								$searchQuery = "AND (";
								$searchQuery .= "d.fecha_emision BETWEEN '".$dateFromTo[0]." 00:00:00' AND '".$dateFromTo[1]."' ";
								$searchQuery .= ")";
								
								$orderBy = "ORDER BY d.fecha_emision ASC, d.fecha_actualizacion ASC";
								
								break;
		}
	}
	else {
		$searchQuery = "";
	}	

	$sql = mysqli_query($link, "SELECT
								d.id,
								d.numero_documento,
								d.asunto,
								d.fecha_emision,
								d.fecha_recepcion,
								d.id_remitente,
								d.remitente as nombre_remitente,
								rem.nombre as nombre_remitente_cat,
								d.id_destinatario,
								dest.nombre as nombre_destinatario,
								d.destinatario_documento_enviado,
								d.id_asignado_a,
								asig_a.nombre as nombre_asignado_a,
								d.id_asignado_por,
								asig_por.nombre as nombre_asignado_por,
								count(da.id) as conteo_adjuntos,
								d.vigencia,
								d.id_estatus,
								e.estatus,
								CONCAT(SUBSTRING_INDEX(da.path,'.',1),'_thumb.jpg') as thumb
								
								FROM documentos d

								LEFT JOIN documento_adjuntos da
								  ON d.id = da.id_documento
				
								LEFT JOIN catalogo_usuarios dest
								  ON d.id_destinatario = dest.id
				
								LEFT JOIN catalogo_usuarios asig_a
								  ON d.id_asignado_a = asig_a.id
				
								LEFT JOIN catalogo_usuarios asig_por
								  ON d.id_asignado_por = asig_por.id
								  
								LEFT JOIN catalogo_usuarios rem
								  ON d.id_remitente = rem.id  
								  
								LEFT JOIN catalogo_estatus e
								  ON d.id_estatus = e.id
				
								WHERE (d.id_remitente IN (".$id_usuario_involucrado.")
								OR d.id_destinatario IN (".$id_usuario_involucrado.")
								OR d.id_asignado_a IN (".$id_usuario_involucrado.")
								OR d.id_asignado_por IN (".$id_usuario_involucrado."))
				
								AND d.id_estatus IN (".$_REQUEST['estatus'].")
								
								".$searchQuery." 
				
							   GROUP BY d.id
							   
							   ".$orderBy."; ");
	
	$jsonData = array();		
	while($row = mysqli_fetch_array($sql)){
		
		switch($row['id_estatus']){
			case 1: $label_estatus = "important"; break;
			case 2: $label_estatus = "warning"; break;
			case 3: $label_estatus = "success"; break;
			case 4: $label_estatus = "inverse"; break;
			case 5: $label_estatus = "info"; break;
		}
		
		// BOF Vigencia	- Días restantes
		$dias_restantes = null;
		if($row['vigencia'] > 0 && ($row['id_estatus'] == 1 || $row['id_estatus'] == 2)){
			$fecha_recepcion_date = substr($row['fecha_recepcion'],0,10);
			$fecha = split("-",$fecha_recepcion_date);
			$fecha_entero = mktime(0,0,0,(int)$fecha[1],(int)$fecha[2],(int)$fecha[0]);
			$fecha_vencimiento = $fecha_entero + ($row['vigencia'] * 24 * 60 * 60);
			$dias_restantes = (int)( ($fecha_vencimiento - $hoy) / (24*60*60) ) + 1;
		}
		// EOF Vigencia	- Días restantes
		
		$system_path = str_replace("app\model\components", "", dirname(__FILE__)).'documents/';		
		$thumb = (file_exists($system_path.$row['thumb']) && !is_dir($system_path.$row['thumb'])) ? "../documents/".$row['thumb'] : "assets/img/thumb_null.png";
		$jsonData[] = array(
					'id_documento' => $row['id'],
					'numero_documento' => $row['numero_documento'],
					'asunto' => $row['asunto'],
					'fecha_emision' => $row['fecha_emision'],
					'fecha_recepcion' => $row['fecha_recepcion'],
					'id_remitente' => $row['id_remitente'],
					'nombre_remitente' => ($row['id_remitente'] > 0) ? $row['nombre_remitente_cat'] : $row['nombre_remitente'],
					'id_destinatario' => $row['id_destinatario'],
					'nombre_destinatario' => $row['nombre_destinatario'],
					'destinatario_documento_enviado' => $row['destinatario_documento_enviado'],
					'id_asignado_a' => $row['id_asignado_a'],
					'nombre_asignado_a' => $row['nombre_asignado_a'],
					'id_asignado_por' => $row['id_asignado_por'],
					'nombre_asignado_por' => $row['nombre_asignado_por'],
					'conteo_adjuntos' => $row['conteo_adjuntos'],
					'vigencia' => $row['vigencia'],
					'dias_restantes' => $dias_restantes,
					'id_estatus' => $row['id_estatus'],
					'estatus' => ($row['estatus'] == 'Seguimiento') ? 'Seguim.' : $row['estatus'],
					'label_estatus' => $label_estatus,
					'thumb' => $thumb,
					'order' => $orderBy
					);
		
	}	
	$jsonData = array_reverse($jsonData);	
	echo json_encode($jsonData);	
?>
