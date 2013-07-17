<?php
	header("Content-type: application/json");
	include_once "../conexion.php";
	include_once "../functions.php";

	$sql = mysqli_query($link, "SELECT
								LEFT(d.fecha_recepcion,7) as fecha_recepcion_mes,
								COUNT(d.id) as conteo_documentos,
								d.id_estatus,
								CONCAT(SUBSTRING_INDEX(da.path,'.',1),'.JPG') as thumb
								
								FROM documentos d
								LEFT JOIN documento_adjuntos da
								  ON d.id = da.id_documento
								
								WHERE (d.id_remitente = ".$_REQUEST['id_usuario']."
								OR d.id_destinatario = ".$_REQUEST['id_usuario']."
								OR d.id_asignado_a = ".$_REQUEST['id_usuario']."
								OR d.id_asignado_por = ".$_REQUEST['id_usuario'].")
								
								GROUP BY LEFT(d.fecha_recepcion,7),
								d.id_estatus;");
	
	$jsonData = array();	
	$mes_anterior = "";
	$i = -1;
	$pendientes = 0;
	$atendidos = 0;
	$enviados = 0;
	while($row = mysqli_fetch_array($sql)){
		
		// Si es un mes que no se ha repetido se inicializan las variables
		if($row['fecha_recepcion_mes'] != $mes_anterior){
			$pendientes = 0;
			$atendidos = 0;
			$enviados = 0;			
		}
		
		// Se suman los valores para pendientes (recibidos-turnados) y enviados (generados-seguimientos)
		switch($row['id_estatus']){
			case 1:
			case 2:	$pendientes = $pendientes + (int)$row['conteo_documentos']; break;
			case 3:	$atendidos = $atendidos + (int)$row['conteo_documentos']; break;
			case 4:
			case 5: $enviados = $enviados + (int)$row['conteo_documentos']; break;
		}
		
		// Después de sumar los datos, si es un mes que no se ha repetido se incrementa $i
		if($row['fecha_recepcion_mes'] != $mes_anterior){			
			$i++;
		}
		
		// Se guardan los datos recopilados en las diversas vueltas de $i
		$fecha_recepcion_mes_label = getMes(substr($row['fecha_recepcion_mes'],-2))." de ".substr($row['fecha_recepcion_mes'],0,4);
		$jsonData[$i] = array(
					'fecha_recepcion_mes'=>$row['fecha_recepcion_mes'],
					'fecha_recepcion_mes_label'=>$fecha_recepcion_mes_label,
					'thumb'=>"../documents/".$row['thumb'],
					'pendientes'=>$pendientes,
					'atendidos'=>$atendidos,
					'enviados'=>$enviados
					);
		
		// Se Inicilaiza $mes_anterior
		$mes_anterior = $row['fecha_recepcion_mes'];
		
	}
	
	$jsonData = array_reverse($jsonData);
	
	echo json_encode($jsonData);	
?>
