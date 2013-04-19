<?php
header("Content-type: application/json");
include "conexion.php";


//Calculos para obtener fechas de los documentos	
	$hoy = time();
	$fecha_recepcion_inicial = "2008-01-01 00:00:00";
	$fecha_recepcion_hoy = date("Y-m-d") . " 23:59:59";
	
	// Obtener el listado de documentos que incluyan al usuario en turnado_a
	$sql_documento_turnado_a = mysql_query("SELECT id_documento FROM documento_turnado_a WHERE id_turnado_a = '".$_REQUEST['id_usuario']."' ");
	while($row_documento_turnado = mysql_fetch_array($sql_documento_turnado_a)){
		$documento_turnado_a .= $row_documento_turnado['id_documento'].",";
	}
		$documento_turnado_a = substr($documento_turnado_a,0,-1);
	// Consulta							
	$sql_principal= mysql_query("SELECT DISTINCT(id_documento) as id_unico,
									d.* FROM documentos d
									WHERE fecha_recepcion BETWEEN '".$fecha_recepcion_inicial."' AND '".$fecha_recepcion_hoy."'
									AND id_destinatario = '".$_REQUEST['id_usuario']."'
									AND id_estatus IN (1,2)
									OR (id_documento IN (".$documento_turnado_a.") AND id_estatus IN (1,2))
									ORDER BY fecha_recepcion ASC; ");

while($row = @mysql_fetch_array($sql_principal)){	
	$id_documento = $row['id_documento'];
	$numero_documento = $row['numero_documento'];
	$dias = $row['vigencia'];
	
	if($row['fecha_recepcion'] != '0000-00-00 00:00:00'){
		$fecha_recepcion = $row['fecha_recepcion'];
	}
	
	//Vigencia	
	$fecha_recepcion_date = substr($fecha_recepcion,0,10);
	$fecha = split("-",$fecha_recepcion_date);
	$fecha_entero = mktime(0,0,0,(int)$fecha[1],(int)$fecha[2],(int)$fecha[0]);
	$fecha_vencimiento = $fecha_entero + ($dias * 24 * 60 * 60);
	$dias_restantes = (int)( ($fecha_vencimiento - $hoy) / (24*60*60) ) + 1;
// Expirado
	if($dias_restantes < 0){
		// Agregar 1 cero si es menor a 10, para optimizar el ordenamiento
		if($dias_restantes > -10)  
			$dias_string = "0".-($dias_restantes);
		else
			$dias_string = -($dias_restantes);
		// Determinar si son 1 o más días		
		if($dias_restantes == -1)		
			$dias_restantes = "Expiró hace ".$dias_string." día";
		else
			$dias_restantes = "Expiró hace ".$dias_string." días";		
	} 
// Hoy vence
	else if($dias_restantes == 0){
				$dias_restantes = "Expira hoy";
	}
// No ha vencido
	else {
		// Agregar 1 cero si es menor a 10, para optimizar el ordenamiento
		if($dias_restantes < 10)  
			$dias_string = "0".$dias_restantes;
		else
			$dias_string = $dias_restantes;
		// Determinar si son 1 o más días
		if($dias_restantes == 1)
			$dias_restantes = $dias_string." día restante";
		else 
			$dias_restantes = $dias_string." días restantes";
	}
	
	switch($_REQUEST['id_usuario']){
			case $row['id_asignado_a']: $correspondencia = "ASIGNADO a usted para ser atendido"; break;
			case $row['id_destinatario']: $correspondencia = "Usted es el DESTINATARIO"; break;			
			default: $correspondencia = "TURNADO a usted para ser atendido"; break;
		}
	
	//Mostrar si cumple los criterios
	if($hoy > $fecha_vencimiento && $dias > 0){	
		echo "	<item>\n";
		echo "		<id_documento>".$id_documento."</id_documento>\n";
		echo "		<numero_documento>".$numero_documento."</numero_documento>\n";
		echo "		<fecha_recepcion>".$fecha_recepcion."</fecha_recepcion>\n";
		echo "		<dias_establecidos>".$dias."</dias_establecidos>\n";
		echo "		<dias_restantes>".$dias_restantes."</dias_restantes>\n";
		echo "		<correspondencia>".$correspondencia."</correspondencia>\n";
		echo "	</item>\n";
		$existe = 1;
	}		
}
if($existe == 1){
	echo "<resultado>1</resultado>";
} else {
	echo "<resultado>0</resultado>";
}	


echo json_encode($jsonData);
	
?>
