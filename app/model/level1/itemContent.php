<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	
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
								
								break;
			case 'recepcion':	$dateFromTo = split(",",$_REQUEST['searchInput']);
								$searchQuery = "AND (";
								$searchQuery .= "d.fecha_recepcion BETWEEN '".$dateFromTo[0]." 00:00:00' AND '".$dateFromTo[1]."' ";
								$searchQuery .= ")";
								
								break;
			case 'emision':		$dateFromTo = split(",",$_REQUEST['searchInput']);
								$searchQuery = "AND (";
								$searchQuery .= "d.fecha_emision BETWEEN '".$dateFromTo[0]." 00:00:00' AND '".$dateFromTo[1]."' ";
								$searchQuery .= ")";
								
								break;
		}
	}
	else {
		$searchQuery = "";
	}

	$sql = mysqli_query($link, "SELECT d.id_estatus,
								ce.estatus,
								COUNT(d.id) as conteo_documentos
								
								FROM documentos d
								
								LEFT JOIN catalogo_estatus ce
								  ON d.id_estatus = ce.id
								
								LEFT JOIN catalogo_usuarios dest
								  ON d.id_destinatario = dest.id
				
								LEFT JOIN catalogo_usuarios asig_a
								  ON d.id_asignado_a = asig_a.id  
								
								WHERE (d.id_remitente = ".$_REQUEST['id_usuario']."
								OR d.id_destinatario = ".$_REQUEST['id_usuario']."
								OR d.id_asignado_a = ".$_REQUEST['id_usuario']."
								OR d.id_asignado_por = ".$_REQUEST['id_usuario'].")
								
								".$searchQuery." 
								
								GROUP BY d.id_estatus;");
	
	$jsonData = array();
	// inicializar
	for($i= 1; $i < 5; $i++){
		$jsonData['semaforo'][$i] = 0;
	}
	
	while($row = mysqli_fetch_array($sql)){
		// Semáforo
		$jsonData['semaforo'][$row['id_estatus']] = (int)$row['conteo_documentos'];
		
		// Chart
		
		switch($row['id_estatus']){
			case 1: $color = '#b23b51'; break;
			case 2: $color = '#f5be6f'; break;
			case 3: $color = '#83c65d'; break;
			case 4: $color = '#587bb0'; break;
			case 5: $color = '#3d6a91'; break;
		}
			
		$jsonData['chart']['data'][] = array(
										$row['estatus'],
										(int)$row['conteo_documentos']
									   );
		$jsonData['colors'][] = $color;
	}
	
	$jsonData['chart']['type'] = "pie";
	$jsonData['chart']['name'] = "Documentos disponibles";

	echo json_encode($jsonData);	
?>
