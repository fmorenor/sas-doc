<?php
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
	echo '<tr>';
	echo '		<th>ID Usuario</th>';
	echo '		<th>Usuario que realizó la acción</th>';
	echo '		<th>ID Documento afectado</th>';
	echo '		<th>Documento afectado</th>';
	echo '		<th>Evento</th>';
	echo '		<th>Elementos</th>';
	echo '		<th>Fecha</th>';
	echo '	</tr>';	

	$sql = mysqli_query($link, "SELECT * FROM bitacora
								WHERE id_usuario IN(".$id_usuario_involucrado.")
								ORDER BY fecha_evento DESC;");	
		
	while($row = mysqli_fetch_array($sql)){		
		echo '<tr>';
		echo '		<td>'.$row['id_usuario'].'</td>';
		echo '		<td>'.$row['usuario'].'</td>';
		echo '		<td>'.$row['id_documento'].'</td>';
		echo '		<td>'.$row['documento'].'</td>';
		echo '		<td>'.$row['evento'].'</td>';
		echo '		<td>'.$row['objeto'].'</td>';
		echo '		<td>'.$row['fecha_evento'].'</td>';
		echo '	</tr>';	
	}	
?>
