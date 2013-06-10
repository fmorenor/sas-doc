<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	
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
								
								WHERE (d.id_remitente IN (".$id_usuario_involucrado.")
								OR d.id_destinatario IN (".$id_usuario_involucrado.")
								OR d.id_asignado_a IN (".$id_usuario_involucrado.")
								OR d.id_asignado_por IN (".$id_usuario_involucrado."))
								
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
	
	$jsonData['id_usuario_involucrado'] = $id_usuario_involucrado;

	echo json_encode($jsonData);	
?>
