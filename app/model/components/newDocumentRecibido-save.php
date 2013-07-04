<?php session_start(); ?>
<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    include_once("../mcrypt.php");
    include_once('../../../login/model/Ldap.php');
    $month_folder = date('Y_m');
    
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
        $fecha_asignado = "fecha_asignado = '".date('Y-m-d H:i:s')."',";
    } else {
        $fecha_asignado = "";
    }
    
    if($_POST['turnado_a']){
        $fecha_turnado = "fecha_turnado = '".date('Y-m-d H:i:s')."',";
    } else {
        $fecha_turnado = "";
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
    
    // Fechas
    if($_POST['fecha_emision']){
        $fecha_emision = "fecha_emision = '".$_POST['fecha_emision']."',";
    }
    if($_POST['fecha_recepcion']){
        $fecha_recepcion = "fecha_recepcion = '".$_POST['fecha_recepcion']." ".$_POST['hora_recepcion']."',";
    }
    if($_POST['fecha_recepcion2']){
        $fecha_recepcion2 = "fecha_recepcion2 = '".$_POST['fecha_recepcion2']." ".$_POST['hora_recepcion2']."',";
    }
    
    $sql = mysqli_query($link, "INSERT INTO documentos
                                SET numero_documento = '".$_POST['numero_documento']."',
                                asunto = '".$_POST['asunto']."',
                                expediente = '".$_POST['expediente']."',
                                anexos = '".$_POST['anexos']."',
                                ".$fecha_emision."
                                ".$fecha_recepcion."
                                ".$fecha_recepcion2."
                                id_tipo_documento = '".$_POST['tipo_documento']."',
                                vigencia = '".$_POST['vigencia']."',
                                id_usuario_insertar = '".$_POST['id_usuario']."',
                                id_estatus = '".$_POST['estatus']."',
                                
                                id_destinatario = '".$id_destinatario."',
                                ".$id_asignado_a."
                                ".$fecha_asignado."
                                ".$fecha_turnado."
                                id_remitente = '".$id_remitente."',
                                remitente = '".$remitente."',
                                id_asignado_por = '".$_POST['id_usuario']."',
                                fecha_actualizacion = '".date('Y-m-d H:i:s')."';   ");
    
    $jsonData['error_documentos'] = mysqli_error($link);
    
    // Obtener el id del documento insertado
    $id_documento = mysqli_insert_id($link);
    
    // Si se guardó correctemente el documento se procede a hacer todo lo demás...
    if($id_documento > 0){
    
        // Notas
        if($_POST['nota']){
            $sql = mysqli_query($link, "INSERT INTO documento_notas
                                                SET id_documento = '".$id_documento."',
                                                id_usuario = '".$_POST['id_usuario']."',
                                                nota = '".$_POST['nota']."',
                                                fecha = '".date('Y-m-d H:i:s')."';   ");
            
            $jsonData['error_notas'] = mysqli_error($link);
        }
        
        // Archivos adjuntos
        for($i = 0; $i < $_POST['uploader_count']; $i++){
            $status = $_POST['uploader_'.$i.'_status'];
            if($status == 'done'){
                $path = $_POST['uploader_'.$i.'_tmpname'];
                $sql = mysqli_query($link, "INSERT INTO documento_adjuntos
                                            SET id_documento = '".$id_documento."',
                                            path = '".$month_folder."/".$path."';   ");
                $jsonData['error_adjuntos'] = mysqli_error($link);
                
                // Mover de carpeta el archivo y crear la miniatura
                saveFile($path, $month_folder, $i);
            }
            //uploader_0_name	= nombre_original del archivo
            
            // Devolver el arreglo con los archivos subidos para hacer su thumbnail
            // Excepto el primero porque de ese ya se hizo...
            if($i > 0){
                $jsonData['files'][$i] = $month_folder."/".$path;
            }
        }
        
        //turnado_a	3,4,hcantor
        $turnado_a = explode(",", $_POST['turnado_a']);
        foreach ($turnado_a as &$valor) {
            if(is_numeric($valor)){
                $id_turnado_a = $valor;            
            } else {
                $id_turnado_a = insertNewUser($valor);
            }
            if($id_turnado_a > 0){
                $sql = mysqli_query($link, "INSERT INTO documento_turnado_a
                                                SET id_documento = '".$id_documento."',
                                                id_turnado_a = '".$id_turnado_a."';   ");
                $jsonData['error_turnado_a'] = mysqli_error($link);
            }
        }
    }
        
    function insertNewUser($user){
        global $login, $password, $AUT, $link;
        if( $AUT->authenticate( $login ,utf8_encode( $password ) ) ) {            
            // Datos del usuario
            $r = $AUT->user_info($user);
            $nombre_completo = utf8_decode($r[0]['displayname'][0]);
                    
            // Si no existe el usuario, como es válido, se agrega a la base de datos
            // El grupo del usuario no se agrega, este se agregará la primera vez que el usuario se loguee
            if($nombre_completo != ''){
                // Buscar si el usuario se acaba de agregar en una consulta anterior (este mismo archivo)
                $id_new_user = null;
                $select = mysqli_query($link, "SELECT id FROM catalogo_usuarios WHERE user = '".$user."'; ");
                while($row = mysqli_fetch_array($select)){
                    $id_new_user = $row['id'];
                }
                // Si no existe agregarlo
                if($id_new_user == null){
                    $insert = mysqli_query($link, "INSERT INTO catalogo_usuarios ( `user`, `nombre`)
                                           VALUES ('".$user."', '".$nombre_completo."');");
                    $jsonData['error_insertNewUser'] = mysqli_error($link);
                    $id_usuario = mysqli_insert_id($link);
                } else {
                    $id_usuario = $id_new_user;
                }
            }
            return $id_usuario;
        } else {
            return false;
        }
    }
    
    function saveFile($path, $month_folder, $instancia){
        // Crear carpeta si no existe
        if (!is_dir('../../../documents/'.$month_folder)) {
            mkdir('../../../documents/'.$month_folder);
        }
        // Mover el archivo de la carpeta temporal a la definitiva
        rename('../../../documents/temp/'.$path, '../../../documents/'.$month_folder."/".$path);
        $file_name = substr('../../../documents/'.$month_folder."/".$path, 0, -4);
        $file_ext = substr('../../../documents/'.$month_folder."/".$path, -3);
        
        // Sólo se hace la miniatura para el primer archivo, para reducir el tiempo de espera.
        // Los demás se harán de manera asincrónica...
        if($instancia == 0){
            // ImageMagick-6.8.1-Q16 en el server
            $ext = ($file_ext == 'pdf') ? $file_ext.'[0]' : $file_ext;
            exec('"C:\Program Files\ImageMagick-6.7.6-Q16\convert.exe" -thumbnail 100x120! '.$file_name.'.'.$ext.' '.$file_name.'_thumb.jpg');
        } 
    }
    
    $jsonData['msg'] = true;    
    echo json_encode($jsonData);

?>