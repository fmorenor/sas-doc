<?php session_start(); ?>
<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    include_once("../mcrypt.php");
    include_once('../../../login/model/Ldap.php');
    
    // BOF LDAP
    $login = $_SESSION['usuario'];
    $password = decrypt($_SESSION['cryptkey'],$key,$iv,$bit_check);        

    $AUT = new ldap();	
    $ldap_config = array();
    $ldap_config['base_dn'] = 'dc=conafor,dc=gob,dc=mx';
    $ldap_config['domain_controllers'] =array('10.0.0.7');
    $ldap_config['ad_username'] = $login;
    $ldap_config['ad_password'] = $password;
    $ldap_config['account_preffix'] = 'CONAFOR';
    
    $AUT->initialize( $ldap_config );
    // EOF LDAP    
    
    // destinatario
    if($_POST['destinatario']){
        if(is_numeric($_POST['destinatario'])){
            $id_destinatario = $_POST['destinatario'];
        } else {
            $id_destinatario = insertNewUser($_POST['destinatario']);
        }
    } 
    
    // asignado_a
    if($_POST['asignado_a']){
        if(is_numeric($_POST['asignado_a'])){
            $id_asignado_a = "id_asignado_a = '".$_POST['asignado_a']."', ";
        } else {
            $id_asignado_a = "id_asignado_a = '".insertNewUser($_POST['asignado_a'])."', ";
        }
    }
    
    // remitente
    if($_POST['remitente']){
        if(is_numeric($_POST['remitente'])){
            $id_remitente = $_POST['remitente'];
            $remitente = $_POST['remitente_nombre'];
        } else {
            $id_remitente = 0;
            $remitente = $_POST['remitente'];
        }
    }
    
    if($_POST['fecha_recepcion']){
        $fecha_recepcion = "fecha_recepcion = '".$_POST['fecha_recepcion']." ".$_POST['hora_recepcion']."',";
    }
    
    // Fechas
    if($_POST['fecha_recepcion2']){
        $fecha_recepcion2 = "fecha_recepcion2 = '".$_POST['fecha_recepcion2']." ".$_POST['hora_recepcion2']."',";
    }
    
    $sql = mysqli_query($link, "INSERT INTO documentos
                                SET numero_documento = '".$_POST['numero_documento']."',
                                asunto = '".$_POST['asunto']."',
                                expediente = '".$_POST['expediente']."',
                                anexos = '".$_POST['anexos']."',
                                fecha_emision = '".$_POST['fecha_emision']."',
                                ".$fecha_recepcion."
                                ".$fecha_recepcion2."
                                id_tipo_documento = '".$_POST['tipo_documento']."',
                                vigencia = '".$_POST['vigencia']."',
                                id_usuario_insertar = '".$_POST['id_usuario']."',
                                id_estatus = '".$_POST['estatus']."',
                                
                                id_destinatario = '".$id_destinatario."',
                                ".$id_asignado_a."
                                id_remitente = '".$id_remitente."',
                                remitente = '".$remitente."',
                                id_asignado_por = '".$_POST['id_usuario']."';    ");
    
    $jsonData['error_documentos'] = mysqli_error($link);
    
    // Obtener el id del documento insertado
    $id_documento = mysqli_insert_id($link);
    
    // Notas
    $sql = mysqli_query($link, "INSERT INTO documento_notas
                                        SET id_documento = '".$id_documento."',
                                        id_usuario = '".$_POST['id_usuario']."',
                                        nota = '".$_POST['nota']."',
                                        fecha = '".date('Y-m-d H:i:s')."';   ");
    
    $jsonData['error_notas'] = mysqli_error($link);    
    
    // Archivos adjuntos
    for($i = 0; $i < $_POST['uploader_count']; $i++){
        $status = $_POST['uploader_'.$i.'_status'];
        if($status == 'done'){
            $path = $_POST['uploader_'.$i.'_tmpname'];
            $sql = mysqli_query($link, "INSERT INTO documento_adjuntos
                                        SET id_documento = '".$id_documento."',
                                        path = '".$path."';   ");
            $jsonData['error_adjuntos'] = mysqli_error($link);
        }
        //uploader_0_name	= nombre_original del archivo
    }
    
    //turnado_a	3,4,hcantor
    $turnado_a = explode(",", $_POST['turnado_a']);
    foreach ($turnado_a as &$valor) {
        if(is_numeric($valor)){
            $id_turnado_a = $valor;            
        } else {
            $id_turnado_a = insertNewUser($valor);
        }
        $sql = mysqli_query($link, "INSERT INTO documento_turnado_a
                                        SET id_documento = '".$id_documento."',
                                        id_turnado_a = '".$id_turnado_a."';   ");
            $jsonData['error_turnado_a'] = mysqli_error($link);
    }
    
    function insertNewUser($user){
        global $login, $password, $AUT, $link;
        if( $AUT->authenticate( $login ,utf8_encode( $password ) ) ) {            
            // Datos del usuario
			$r = $AUT->user_info($user);
			$nombre_completo = utf8_decode($r[0]['displayname'][0]);
		            
            // Si no existe el usuario, como es válido se agrega a la base de datos
            // El grupo del usuario no se agrega, este se agregará la primera vez que el usuario se loguee
            $insert = mysqli_query($link, "INSERT INTO catalogo_usuarios ( `user`, `nombre`)
                                   VALUES ('".$user."', '".$nombre_completo."');");
            $jsonData['error_insertNewUser'] = mysqli_error($link);
            $id_usuario = mysqli_insert_id($link);
            return $id_usuario;
        } else {
            return false;
        }
    }
    
    $jsonData['msg'] = true;    
    echo json_encode($jsonData);

?>