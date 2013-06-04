<?php
    header("Content-type: application/json");
	include_once "../conexion.php";

	$sql = mysqli_query($link, "SELECT
								d.*,
								dest.nombre as nombre_destinatario,
								asig_a.nombre as nombre_asignado_a,
								asig_por.nombre as nombre_asignado_por,
								e.estatus,
                                ctd.nombre as tipo_documento

								FROM documentos d

								LEFT JOIN catalogo_usuarios dest
								  ON d.id_destinatario = dest.id
				
								LEFT JOIN catalogo_usuarios asig_a
								  ON d.id_asignado_a = asig_a.id

								LEFT JOIN catalogo_usuarios asig_por
								  ON d.id_asignado_por = asig_por.id

								LEFT JOIN catalogo_estatus e
								  ON d.id_estatus = e.id
                                
                                LEFT JOIN catalogo_tipo_documento ctd
								  ON d.id_tipo_documento = ctd.id  
				
								WHERE d.id = '".$_REQUEST['id_documento']."'; ");
	
	$jsonData = array();
    $hoy = time(); // Posix
	while($row = mysqli_fetch_array($sql)){
		
		switch($row['id_estatus']){
			case 1: $label_estatus = "important"; break;
			case 2: $label_estatus = "warning"; break;
			case 3: $label_estatus = "success"; break;
			case 4: 
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
		
		$jsonData = array(
					'id_documento' => $row['id'],
					'numero_documento' => $row['numero_documento'],
					'asunto' => $row['asunto'],
                    'expediente' => $row['expediente'],
                    'anexos' => $row['anexos'],
					'fecha_emision' => $row['fecha_emision'],
					'fecha_recepcion' => $row['fecha_recepcion'],
                    'fecha_recepcion2' => $row['fecha_recepcion2'],
					'id_remitente' => $row['id_remitente'],
					'nombre_remitente' => $row['remitente'],
					'id_destinatario' => $row['id_destinatario'],
					'nombre_destinatario' => $row['nombre_destinatario'],
                    'destinatario_documento_enviado' => $row['destinatario_documento_enviado'],
					'id_asignado_a' => $row['id_asignado_a'],
					'nombre_asignado_a' => $row['nombre_asignado_a'],
					'id_asignado_por' => $row['id_asignado_por'],
					'nombre_asignado_por' => $row['nombre_asignado_por'],					
					'vigencia' => $row['vigencia'],
					'dias_restantes' => $dias_restantes,
					'id_estatus' => $row['id_estatus'],
					'estatus' => $row['estatus'],
					'label_estatus' => $label_estatus,                    
                    'id_tipo_documento' => $row['id_tipo_documento'],
                    'tipo_documento' => $row['tipo_documento'],
                    'id_documento_padre' => $row['id_documento_padre'],
                    'notificados' => $row['notificados'],
                    'fecha_actualizacion' => $row['fecha_actualizacion'],
					);
		
	}	
	echo json_encode($jsonData);
?>
    
