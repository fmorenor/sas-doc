<?php
	header("Content-type: application/json");
	require_once("../conexion.php");
	require_once('../functions.php');
	require_once("phpMailer/class.phpmailer.php");
	
	// Enviar correos a destinatario y turnados donde cumplan las condiciones
	$mail = new phpmailer();
	$mail->PluginDir = "phpMailer/";
	$mail->Mailer = "smtp";
	$mail->Host = "10.254.253.1:2525";
	$mail->SMTPAuth = true;
	$mail->SMPTDebug = true;
	$mail->Username = "snif_user"; 
	$mail->Password = "1602Sn1F";
	$mail->From = "snif@conafor.gob.mx";
	$mail->FromName = utf8_decode("SAS-DOC .: Sistema de Administración y Seguimiento de Documentos");
	$mail->Timeout=30;
	
	$jsonData = array();
	// Obtener los datos del documento
	$sql = mysqli_query($link, "SELECT
								d.*,
								dest.user as user_destinatario,
								asig_a.user as user_asignado_a,
								ctd.nombre as tipo_documento

								FROM documentos d

								LEFT JOIN catalogo_usuarios dest
								  ON d.id_destinatario = dest.id
				
								LEFT JOIN catalogo_usuarios asig_a
								  ON d.id_asignado_a = asig_a.id
                                
                                LEFT JOIN catalogo_tipo_documento ctd
								  ON d.id_tipo_documento = ctd.id  

								WHERE d.id = '".$_REQUEST['id_documento']."'; ");
	
	
    $hoy = time(); // Posix
	while($row = mysqli_fetch_array($sql)){
		
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
		
		//Body
		$body = "<span style='font-family:Calibri'>\n";
		$body .= "<span style='font-size:20px'>";
		$body .= ($row['id_estatus'] <= 3) ? "Se ha recibido un nuevo documento en el <b>SAS-DOC.</b><br /><br />" : "Se ha registrado el seguimiento a un documento en el <b>SAS-DOC.</b><br /><br />";
		$body .= "<ul><li>\n";
		$body .= "<a href='http://148.223.105.188/gif/sas-doc/app?id_documento_email=".$row['id']."&email_link=true' target='_self'>";
		$body .= $row['id']." - <b>".$row['numero_documento']."</b>";
		$body .= "</a></span><br /><br />";			
		$body .= "<b>Tipo:</b> ".$row['tipo_documento']."<br />";
		$body .= "<b>Asunto: </b>".$row['asunto'].".<br />";	
		if($row['nota'])
			$body .= "<b>Nota:</b> ".$row['nota']."<br />";
		if($row['anexos'])
			$body .= "<b>Anexos:</b> ".$row['anexos']."<br />";
		if($row['fecha_emision'])
			$body .= "<b>Fecha de emisión:</b> ".$row['fecha_emision'].".<br />";
		if($row['fecha_recepcion'])
			$body .= "<b>Fecha de recepción:</b> ".$row['fecha_recepcion'].".<br />";
		if($row['vigencia'] > 0){
			$body .= "Fueron establecidos <b>".$row['vigencia']." días naturales </b>para atender este documento ";
			
			if($dias_restantes == 1){
				$body .= "y queda 1 día a partir de este momento.<br />";
			} else if($dias_restantes > 0){
				$body .= "y quedan ".$dias_restantes." días a partir de este momento.<br />";			
			} else if($dias_restantes == -1){
				$body .= "y la vigencia expiró hace 1 día.<br />";
			} else if($dias_restantes < 0){
				$body .= "y la vigencia expiró hace ".($dias_restantes * -1)." días.<br />";
			} else {
				$body .= "y hoy finaliza la vigencia establecida para este documento.<br />";
			}
		}
		$body .= "</li></ul>\n";
		$body .= "</span><br />";	
		
		$mail->Subject = utf8_decode("SAS-DOC .: Se recibió un nuevo documento '".$row['numero_documento']."'");
		
		///// Variables para de usuarios a notificar /////
		$jsonData['notificados'] = ($row['notificados'] != '') ? explode(",", $row['notificados']) : null;
		
		// Destinatario
		$arrayData['CC'][$row['id_destinatario']] = array('id' => $row['id_destinatario'],
														  'user' => $row['user_destinatario'],
														  'type' => 'destinatario');
		
		// Asignado a
		$arrayData['CC'][$row['id_asignado_a']] = array('id' => $row['id_asignado_a'],
														'user' => $row['user_asignado_a'],
														'type' => 'asignado_a');
	}
	
	// Turnados
	$sql = mysqli_query($link, "SELECT dt.*,
								cu.user,
								cu.nombre
								FROM documento_turnado_a dt
								LEFT JOIN catalogo_usuarios cu
									ON dt.id_turnado_a = cu.id
								WHERE dt.id_documento = ".$_REQUEST['id_documento']."
									AND cu.activo = 1;");
   
    while($row = mysqli_fetch_array($sql)){        
		$arrayData['CC'][$row['id_turnado_a']] = array('id' => $row['id_turnado_a'],
													   'user' => $row['user'],
													   'type' => 'turnado_a');        
    }
	
	
	// Se realiza una copia del arreglo con los usuarios a notificar,
	// porque posteriormente se quitarán los ya notificados.
	$arrayData['CCTemp'] = $arrayData['CC'];
	
	// Se eliminar del arreglo los usuairos ya notificados para no enviarle correo 2 veces.
	if($jsonData['notificados'] != null){
		foreach ($jsonData['notificados'] as &$index) {
			unset($arrayData['CC'][$index]);
		}		
	}
	unset($arrayData['CC'][0]);
	
	// Se crean arreglos temporales para hacer cadenas correctas con direcciones de correo a quienes se enviarán
	// También id's de usuarios notificados para guardar en la base de datos.
	$arrayData['usuarios']['id'] = $jsonData['notificados'];
	foreach($arrayData['CC'] as &$index){
		$arrayData['usuarios']['user'][] = $index['user']."@conafor.gob.mx";
		$arrayData['usuarios']['id'][] = $index['id'];
	}
	
	$jsonData['addAddress'] = ($arrayData['usuarios']['user']) ? implode(",",$arrayData['usuarios']['user']) : null;
	$jsonData['nuevosNotificados'] = ($arrayData['usuarios']['id']) ? implode(",",$arrayData['usuarios']['id']) : null;
	
	if($jsonData['addAddress'] != null){
		$sql_notificados = mysqli_query($link, "UPDATE documentos
												SET notificados = '".$jsonData['nuevosNotificados']."'
												WHERE id = '".$_REQUEST['id_documento']."'; ");		
					
		$mail->AddAddress($jsonData['addAddress']);
		$mail->AddBCC("agonzalezr@conafor.gob.mx");	
		$mail->Body = utf8_decode($body);		
		$exito = $mail->Send();
		
		$intentos=1; 
		while ((!$exito) && ($intentos < 5)) {
		  sleep(5);
			  $exito = $mail->Send();
			  $intentos=$intentos+1;		
		 }		
		if(!$exito) {
			// $mail->ErrorInfo
			$jsonData['success'] = $mail->ErrorInfo;
		}  else  {
			$jsonData['success'] = true;
		}
	}
	
	echo json_encode($jsonData);
	
?>
    
