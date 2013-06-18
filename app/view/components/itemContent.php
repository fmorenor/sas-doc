<script>    
    $(document).ready(function(){
        
        //$.post("model/components/itemContent.php", {id_usuario: userData.id_usuario, id_privilegios: userData.id_privilegios, estatus: userData.estatus, searchType: userData.searchType, searchInput: userData.searchInput}, function(data){
        $.ajax({
                type: '<?php echo $_GET['method']; ?>',
                url: "model/components/itemContent.php",
                data: {
                        id_usuario: userData.id_usuario,
                        id_privilegios: userData.id_privilegios,
                        estatus: userData.estatus,
                        searchType: userData.searchType,
                        searchInput: userData.searchInput
                        }
        }).done(function(data){              
            //Cargar los valores con los datos provenientes del POST
                var conteo_pendientes = data['semaforo'][1]+data['semaforo'][2];
                conteo_pendientes = (conteo_pendientes > 0) ? conteo_pendientes : 0;
                $('#resume .estatus-pendiente').text(conteo_pendientes); // Se suman los recibidos y los turnados
                $('#resume .estatus-recibido').text(data['semaforo'][1]);
                $('#resume .estatus-turnado').text(data['semaforo'][2]);
                
                $('#resume .estatus-atendido').text(data['semaforo'][3]);
                
                var conteo_enviados = data['semaforo'][4]+data['semaforo'][5];
                conteo_enviados = (conteo_enviados > 0) ? conteo_enviados : 0;
                $('#resume .estatus-enviado').text(conteo_enviados); // Se suman los generados y las respuestas
                $('#resume .estatus-generado').text(data['semaforo'][4]);
                $('#resume .estatus-respuesta').text(data['semaforo'][5]);
              
                
            //Cargar el gráfico con los datos provenientes del POST
                userDataL1['chart'] =  data['chart'];
                userDataL1['colors'] =  data['colors'];
                $('#itemContentL1 #chart').load("view/components/chart.php");
        });
        
        // NavBar
        $('#contador-pendiente').css('cursor', 'pointer');
        $('#contador-atendido').css('cursor', 'pointer');       
        $('#contador-enviado').css('cursor', 'pointer');
        
         $('#contador-pendiente').bind("click",function(event){
            userData.estatus = '1,2';
            $('#itemListL1').load("view/components/itemList.php?method=GET");
            $('#myNav li:eq(0) a').tab('show'); 
        });
        
        $('#contador-atendido').bind("click",function(event){
            userData.estatus = '3';
            $('#itemListL1').load("view/components/itemList.php?method=GET");            
            $('#myNav li:eq(1) a').tab('show');             
        });   
         
        $('#contador-enviado').bind("click",function(event){
            userData.estatus = '4,5';
            $('#itemListL1').load("view/components/itemList.php?method=GET");
            $('#myNav li:eq(2) a').tab('show'); 
        });
    });
</script>
<div id="title">
    <h4>Documentos disponibles</h4>
    <span>Resumen del estatus de los documentos que están disponibles para tí</span>
</div>
<br />
<div class="row-fluid" id="resume">   
    <!--Pendientes-->
    <div class="span2 left-separator" id="contador-pendiente">
        <div>Pendientes</div>
        <div class="resume-value estatus-pendiente">0</div>
    </div>
    <div class="span3 right-separator">
        <div class="row">
            <div class="span9">Recibidos</div>
            <div class="span3 resume-subvalue estatus-recibido">0</div>
        </div>
        <div class="row">
            <div class="span9">Turnados</div>
            <div class="span3 resume-subvalue estatus-turnado">0</div>
        </div>
    </div>
    <!--Atendidos-->
    <div class="span2 right-separator" id="contador-atendido">
        <div>Atendidos</div>
        <div class="resume-value estatus-atendido">0</div>
    </div>
    <!--Enviados-->
    <div class="span2" id="contador-enviado">
        <div>Enviados</div>
        <div class="resume-value estatus-enviado">0</div>
    </div>
    <div class="span3">
        <div class="row">
            <div class="span9">Generados</div>
            <div class="span3 resume-subvalue estatus-generado">0</div>
        </div>
        <div class="row">
            <div class="span9">Respuestas</div>
            <div class="span3 resume-subvalue estatus-respuesta">0</div>
        </div>
    </div>
</div>
<br />
<div id="chart" style="min-width: 400px; min-height: 270px; margin: 0 auto"></div>