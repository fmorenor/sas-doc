<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	include_once "../functions.php";	
	
	// Si hay algún valor en los campos de buqueda se realiza, si no solo se ignora...
	
	$orderBy = "ORDER BY d.fecha_recepcion ASC"; // Por defecto esta es la ordenación
	if($_REQUEST['searchInput'] != ''){
		//$fecha_hoy = date("Y-m-d") . " 23:59:59";
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
								
								$orderBy = "ORDER BY d.fecha_recepcion ASC";
								
								break;
			case 'recepcion':	$dateFromTo = split(",",$_REQUEST['searchInput']);
								$searchQuery = "AND (";
								$searchQuery .= "d.fecha_recepcion BETWEEN '".$dateFromTo[0]." 00:00:00' AND '".$dateFromTo[1]."' ";
								$searchQuery .= ")";
								
								$orderBy = "ORDER BY d.fecha_recepcion ASC";
								
								break;
			case 'emision':		$dateFromTo = split(",",$_REQUEST['searchInput']);
								$searchQuery = "AND (";
								$searchQuery .= "d.fecha_emision BETWEEN '".$dateFromTo[0]." 00:00:00' AND '".$dateFromTo[1]."' ";
								$searchQuery .= ")";
								
								$orderBy = "ORDER BY d.fecha_emision ASC";
								
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
								d.id_destinatario,
								dest.nombre as nombre_destinatario,
								d.id_asignado_a,
								asig_a.nombre as nombre_asignado_a,
								d.id_asignado_por,
								asig_por.nombre as nombre_asignado_por,
								count(da.id) as conteo_adjuntos,
								d.vigencia,
								d.id_estatus,
								e.estatus,
								CONCAT(SUBSTRING_INDEX(da.path,'.',1),'.JPG') as thumb
								
								FROM documentos d

								LEFT JOIN documento_adjuntos da
								  ON d.id = da.id_documento
				
								LEFT JOIN catalogo_usuarios dest
								  ON d.id_destinatario = dest.id
				
								LEFT JOIN catalogo_usuarios asig_a
								  ON d.id_asignado_a = asig_a.id
				
								LEFT JOIN catalogo_usuarios asig_por
								  ON d.id_asignado_por = asig_por.id
								  
								LEFT JOIN catalogo_estatus e
								  ON d.id_estatus = e.id
				
								WHERE (d.id_remitente = ".$_REQUEST['id_usuario']."
								OR d.id_destinatario = ".$_REQUEST['id_usuario']."
								OR d.id_asignado_a = ".$_REQUEST['id_usuario']."
								OR d.id_asignado_por = ".$_REQUEST['id_usuario'].")
				
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
			case 4: 
			case 5: $label_estatus = "info"; break;
		}		 
		
		$jsonData[] = array(
					'id_documento' => $row['id'],
					'numero_documento' => $row['numero_documento'],
					'asunto' => $row['asunto'],
					'fecha_emision' => $row['fecha_emision'],
					'fecha_recepcion' => $row['fecha_recepcion'],
					'id_remitente' => $row['id_remitente'],
					'nombre_remitente' => $row['nombre_remitente'],
					'id_destinatario' => $row['id_destinatario'],
					'nombre_destinatario' => $row['nombre_destinatario'],
					'id_asignado_a' => $row['id_asignado_a'],
					'nombre_asignado_a' => $row['nombre_asignado_a'],
					'id_asignado_por' => $row['id_asignado_por'],
					'nombre_asignado_por' => $row['nombre_asignado_por'],
					'conteo_adjuntos' => $row['conteo_adjuntos'],
					'vigencia' => $row['vigencia'],
					'id_estatus' => $row['id_estatus'],
					'estatus' => $row['estatus'],
					'label_estatus' => $label_estatus,
					'thumb' => '../documents/'.$row['thumb']
					);
		
	}	
	$jsonData = array_reverse($jsonData);
	echo json_encode($jsonData);	
?>
