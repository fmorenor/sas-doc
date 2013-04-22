<?php
	header("Content-type: application/json");
	include("../../app/model/conexion.php");
	
	///// BOF Ldap	
	include('Ldap.php');
	
	$login = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	
	$AUT = new ldap();	
	$ldap_config = array();
	$ldap_config['base_dn'] = 'dc=conafor,dc=gob,dc=mx';
	$ldap_config['domain_controllers'] =array('10.0.0.7');
	$ldap_config['ad_username'] = $login;
	$ldap_config['ad_password'] = $password;
	$ldap_config['account_preffix'] = 'CONAFOR';
	
	$AUT->initialize( $ldap_config );
	if( $AUT->authenticate( $login ,utf8_encode( $password ) ) )
	{
		// Datos del usuario
			$r = $AUT->user_info($login);
			$nombre_completo = utf8_decode($r[0]['displayname'][0]);
		
		// Grupo del Usuario (Verificar si se encuentra en uno de los ya dados de alta)
			$g = $AUT->user_groups($login);
			$g= array_reverse($g);
			
			$keys = false;	
			if($keys === false){
				$keys = (search_array($g, 'Gerencia ') === false) ? false : search_array($g, 'Gerencia ');				
			}
			if($keys === false){
				$keys = (search_array($g, 'Coordinaci') === false) ? false : search_array($g, 'Coordinaci');				
			}
			if($keys === false){
				$keys = (search_array($g, 'Internacionales') === false) ? false : search_array($g, 'Internacionales');
			}
			if($keys === false){
				$keys = (search_array($g, 'Dirección General') === false) ? false : search_array($g, 'Dirección General');
			}
			if($keys === false){
				$keys = (search_array($g, 'Jurídicos') === false) ? false : search_array($g, 'Jurídicos');
			}
			$grupo = $g[$keys];
			
		// Inicia la las variables de sesión	
			@session_start();
			$_SESSION['nombre_completo'] = $nombre_completo;
			$_SESSION['grupo'] = $grupo;
			$_SESSION['appSessionSASDOC'] = "true";
			
		// Consultar en la base de datos si el usuario ya existe
			$sql = mysqli_query($link, "SELECT *
								FROM catalogo_usuarios 
								WHERE user = '".$login."'
								ORDER BY activo DESC;");
			
			while($row = mysqli_fetch_array($sql)){
				$userDataTemp['id_usuario'][] = $row['id'];
				$userDataTemp['id_privilegios'][] = $row['id_privilegios'];
				$userDataTemp['id_grupo'][] = $row['id_grupo'];
				$userDataTemp['recibir_correo'][] = $row['recibir_correo'];
				$userDataTemp['alerta_pendientes'][] = $row['alerta_pendientes'];
				$userDataTemp['activo'][] = $row['activo'];
			}
		
		// Consultar en la base de datos si el grupo ya existe
			$id_grupo = 0;
			$sql = mysqli_query($link, "SELECT * FROM catalogo_grupos
								WHERE nombre_grupo = '".utf8_decode($grupo)."'; ");
			while($row = mysqli_fetch_array($sql)){				
				$id_grupo = $row['id'];
			}		
			if($id_grupo == 0){
				$insert_grupo = mysqli_query($link, "INSERT INTO catalogo_grupos
													SET nombre_grupo = '".utf8_decode($grupo)."'; ");
				$id_grupo = mysqli_insert_id($link);
			}	
		
		// Si existe el usuario y está activo se guardan los datos 
			if(count($userDataTemp['id_usuario']) > 0 && $userDataTemp['activo'][0] = "1"){
				// Agrupar ids de usuario anteriores pertenecientes al mismo usuario				
					$idUTemp = array();
					for($i = 1; $i < count($userDataTemp['id_usuario']); $i++){
						$idUTemp[] = $userDataTemp['id_usuario'][$i];
					}			
					$id_usuario_anterior = (implode(",",$idUTemp) != "") ? implode(",",$idUTemp) : null;				
					
					$_SESSION['id_usuario'] = $userDataTemp['id_usuario'][0];
					$_SESSION['id_privilegios'] = $userDataTemp['id_privilegios'][0];
					$_SESSION['id_grupo'] = $userDataTemp['id_grupo'][0];
					$_SESSION['recibir_correo'] = $userDataTemp['recibir_correo'][0];
					$_SESSION['alerta_pendientes'] = $userDataTemp['alerta_pendientes'][0];
					$_SESSION['id_usuario_anterior'] = $id_usuario_anterior;				
			} else {
				// Si no existe el usuario, como es válido se agrega a la base de datos				
					$insert = mysqli_query($link, "INSERT INTO catalogo_usuarios ( `user`, `id_grupo`)
										   VALUES ('".$login."', '".$id_grupo."');");
					
					echo mysqli_error($link);
					
					$_SESSION['id_usuario'] = mysqli_insert_id($link);
					$_SESSION['id_privilegios'] = 0;
					$_SESSION['id_grupo'] = $id_grupo;
					$_SESSION['recibir_correo'] = 1;
					$_SESSION['alerta_pendientes'] = 1;
					$_SESSION['id_usuario_anterior'] = null;
			}
		
	} else {
		return false;
	}
	
	function search_array ( array $array, $term ){
		foreach ( $array as $key => $value )
			if ( stripos( $value, $term ) !== false )
				return $key;
	
		return false;
	}
	
	///// EOF Ldap	
	if($nombre_completo != null){
		$jsonData = @array('usuario'=>$_REQUEST['username'],
		'nombre_completo'=>$nombre_completo,
		'grupo'=>$grupo,
		
		'id_usuario' => $_SESSION['id_usuario'],
		'id_privilegios' => $_SESSION['id_privilegios'],
		'id_grupo' => $_SESSION['id_grupo'],
		'recibir_correo' => $_SESSION['recibir_correo'],
		'alerta_pendientes' => $_SESSION['alerta_pendientes'],
		'id_usuario_anterior' => $_SESSION['id_usuario_anterior']);
	}
	
	echo json_encode($jsonData);	
?>