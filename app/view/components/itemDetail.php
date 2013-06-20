<script>
    $(document).ready(function() {
        
        $.post("model/components/itemDetail.php", {id_documento: userData.selectedDocumentId}, function(data){
            
            // BOF LLenar de datos la ficha            
                // Titulo
                $('#numero_documento').text(data.numero_documento);
                $('.label_estatus_detail_class').addClass('label-'+data.label_estatus);
                $('.label_estatus_detail_class').text(data.estatus);
                if (data.id_estatus == '1' || data.id_estatus == '2') {            
                    if(data.dias_restantes){
                        $('.label_estatus_detail_class').after('<span class="label label-important label_vigencia_detail">Vigencia: '+data.vigencia+' días - Días restantes: '+data.dias_restantes+'</span>');
                    } 
                }   
                
                // Asunto
                $('#detail-asunto').append('<h5>Asunto</h5>'
                                           +'<table class="table table-bordered table-striped">'
                                           +'   <tr>'
                                           +'       <td>'+data.asunto+'</td>'
                                           +'   </tr>'
                                           +'</table>');
                
                // Tabla
                $('#detail-table').before("<h5>Detalles del documento</h5>");
                if(data.tipo_documento){
                    $('#detail-table').append("<tr>"
                    +"    <th>Tipo de documento</th><td>"+data.tipo_documento+"</td>"
                    +"    <th>Expediente</th><td>"+data.expediente+"</td>"
                    +"</tr>");
                }
                
                if(data.anexos){
                   $('#detail-table').append("<tr>"
                    +"  <th>Anexos</th><td colspan='3'>"+data.anexos+"</td>"
                    +"</tr>");
                }
                
                if(data.fecha_emision){
                    $('#detail-table').append("<tr>"
                    +"  <th>Fecha emisión</th><td>"+data.fecha_emision+"</td>"
                    +"  <th>Fecha recepción</th><td>"+data.fecha_recepcion+"</td>"
                    +"</tr>");
                }
                
                 if(data.fecha_recepcion2 != '0000-00-00 00:00:00'){
                    $('#detail-table').append("<tr>"
                    +"  <th colspan='2'>Fecha de recepción en oficialía de partes</th><td colspan='2'>"+data.fecha_recepcion2+"</td>"
                    +"</tr>");
                }
                
                if(data.nombre_remitente){
                    $('#detail-table').append("<tr>"
                    +"  <th>Remitente</th><td colspan='3'>"+data.nombre_remitente+"</td>"
                    +"</tr>");
                }
                
                if (data.id_estatus == '1' || data.id_estatus == '2' || data.id_estatus == '3') { 
                    if(data.nombre_destinatario){
                        $('#detail-table').append("<tr>"
                        +"  <th>Destinatario</th><td colspan='3'>"+data.nombre_destinatario+"</td>"
                        +"</tr>");
                    }
                } else {
                     if(data.destinatario_documento_enviado){
                        $('#detail-table').append("<tr>"
                        +"  <th>Destinatario</th><td colspan='3'>"+data.destinatario_documento_enviado+"</td>"
                        +"</tr>");
                    }
                }
                
                if(data.nombre_asignado_a){
                    $('#detail-table').append("<tr>"
                    +"  <th>Asignado a</th><td colspan='3'>"+data.nombre_asignado_a+"</td>"
                    +"</tr>");
                }
                
                //if(data.nombre_asignado_por){
                //    $('#detail-table').append("<tr>"
                //    +"  <th>Asignado por</th><td colspan='3'>"+data.nombre_asignado_por+"</td>"
                //    +"</tr>");
                //}            
            // EOF LLenar de datos la ficha
            
            // Altura dinámica del contenedor para mostrar la barra de scroll
            $('.detail-scroll').height(<?php echo $_REQUEST['itemDetailHeight']; ?> - $('.detail-title').height() - 10); // 10: margenes
            
            
            // Carga de thumbs de adjuntos hasta que se haya cargado itemDeatil.php            
            $.post("model/components/itemDetail-adjuntos.php", {id_documento: userData.selectedDocumentId}, function(data){                
                $('.detail-scroll').unbind("scroll");
                
                // Si existen registros de documentos anjuntos
                if(data.length > 0){
                    // Agregar titulo del módulo "adjuntos"
                    $('.detail-adjuntos').before('<h5>Archivos adjuntos</h5>');
                    
                    // Agregar clase al contenedor, si no existe no se agrega para no meter un espacio vacio
                    $('.detail-adjuntos').css('padding', '0 20px 15px 0');
                                        
                    // Agregar las flechas del carrousel (ocultas)
                    $('.clearfix').after('<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>'
                                     +'<a class="next" id="foo1_next" href="#"><span>next</span></a>'
                                     +'<br />');
                    
                    // Agregar las vistas previas respectivas a cada archivo adjunto
                    $.each(data, function(i, obj) {
                        $('#detail-adjuntos-list').append('<div>'
                            +'    <div class="image-holder"><img src="'+obj.thumb+'" title="'+obj.path+'" /></div>'
                            +'    <div class="btn btn-mini" onclick="window.open(\'../documents/'+obj.path+'\')"><i class="icon-download"></i> Descargar</div>'
                            +'</div>');
                    });                    
                
                    // Iniciar el carrusel de thumbs de adjuntos
                    $("#detail-adjuntos-list").carouFredSel({
                        circular	: false,
                        infinite	: false,
                        auto 		: false,
                        width       : "100%",
                        height      : 175,
                        //mousewheel  : true,
                        swipe		: {
                            onTouch		: true,
                            onMouse		: true
                        },
                        prev : "#foo1_prev",
                        next : "#foo1_next"
                    });
                    
                    // Detectar la posición de ".detail-adjuntos", de eso depende la posición de las flechas
                    // ya que deben tener posición absoluta y se debe calcular al comienzo.
                    var adjuntosTop = $('.detail-adjuntos').offset().top;
                    var itemDetailHeight = ($('#itemDetail').height()+30);
                    
                    if (adjuntosTop < itemDetailHeight) { // Si hay espacio se muestran
                        $('a.prev').css('display', 'block');
                        $('a.next').css('display', 'block');
                        $('a.prev').css('top', adjuntosTop - 40);
                        $('a.next').css('top', adjuntosTop - 40);
                    } else {  // Si no hay espacio se ocultan           
                        $('a.prev').css('display', 'none');
                        $('a.next').css('display', 'none');
                    }
                    
                    // Mover las flechas del carrousel de acuerdo al scroll de .detail-scroll
                    // (debido a que el scroll rompe la posicion absoluta)                                 
                    var scrollTop = $('a.prev').css('top').replace("px", "");                    
                    $('.detail-scroll').scroll(function(){
                        $.doTimeout( 'scroll', 5, function(){
                            
                            var newAdjuntosTop = $('.detail-adjuntos').offset().top;
                            if (newAdjuntosTop < itemDetailHeight) {
                                $('a.prev').css('display', 'block');
                                $('a.next').css('display', 'block');
                                $('a.prev').css('top', newAdjuntosTop - 40);
                                $('a.next').css('top', newAdjuntosTop - 40);                                
                            } else {
                                $('a.prev').css('display', 'none');
                                $('a.next').css('display', 'none');
                            }
                        });
                    });                    
                    
                } // EOF Si hay documentos adjuntos
                
            }); // EOF  $.post("model/components/itemDetail-adjuntos.php...
            
            
            // Cargar los registros de "turnado a"
            $.post("model/components/itemDetail-turnado-a.php", {id_documento: userData.selectedDocumentId}, function(data){                
                if(data.length > 0){
                    var turnado_a = "<tr>";
                    turnado_a += "<th rowspan='"+data.length+"'>Turnado a</th>";
                    $.each(data, function(i, obj) {
                        if (i > 0) {
                            turnado_a += "</tr><tr>"
                        }
                        turnado_a += "<td colspan='3'>"+obj.nombre+"</td>";
                    });
                    turnado_a +="</tr>";                    
                    $('#detail-table').append(turnado_a);                    
                }   
            }); // EOF  $.post("model/components/itemDetail-turnado-a.php...
            
            // Cargar las "notas"
            $.post("model/components/itemDetail-notas.php", {id_documento: userData.selectedDocumentId}, function(data){                
                if(data.length > 0){
                    var table_notas = "<h5>Notas</h5>";
                    table_notas += "<div class='detail-table-container'>"
                        + "<table class='table table-bordered table-striped'>"
                        + "<tr>"
                        + " <th width='45%'>Nota</th>"
                        + " <th width='35%'>Usuario</th>"
                        + " <th width='15%'>Fecha</th>"                       
                        + "</tr>";
                    $.each(data, function(i, obj) {
                        table_notas += "<tr>";
                        table_notas += "    <td>"+obj.nota+"</td>";
                        table_notas += "    <td>"+obj.nombre+"</td>";
                        table_notas += "    <td>"+obj.fecha+"</td>";                        
                        table_notas += "</tr>";
                    });
                    table_notas +="</table></div>";                    
                    $('.detail-more-data').append(table_notas);                    
                }   
            }); // EOF  $.post("model/components/itemDetail-notas.php...
            
            // Cargar el listado de "documentos padre"
            $.post("model/components/itemDetail-documentos-padre.php", {id_documento: userData.selectedDocumentId}, function(data){                
                if(data.length > 0){
                    var table_padre = "<h5>Documentos a los que da seguimiento este documento</h5>";
                    table_padre += "<div class='detail-table-container'>"
                        + "<table class='table table-bordered table-striped'>"
                        + "<tr>"
                        + " <th>Documento</th>"
                        + " <th>Asunto</th>"
                        + " <th>Estatus</th>"                       
                        + "</tr>";
                    $.each(data, function(i, obj) {
                        table_padre += "<tr>";
                        table_padre += "    <td><button class='btn' onclick='selectDocument("+obj.id+")'>"+obj.numero_documento+"</button></td>";
                        table_padre += "    <td>"+obj.asunto+"</td>";
                        table_padre += "    <td><div class='label label-"+obj.label_estatus+"'>"+obj.estatus+"</div></td>";                        
                        table_padre += "</tr>";
                    });
                    table_padre +="</table></div>";                    
                    $('.detail-more-data').append(table_padre);                    
                }   
            }); // EOF  $.post("model/components/itemDetail-documentos-padre.php...
            
            // Cargar el listado de "documentos hijos"
            $.post("model/components/itemDetail-documentos-hijos.php", {id_documento: userData.selectedDocumentId}, function(data){                
                if(data.length > 0){
                    var table_hijos = "<h5>Documentos derivados de este documento</h5>";
                    table_hijos += "<div class='detail-table-container'>"
                        + "<table class='table table-bordered table-striped'>"
                        + "<tr>"
                        + " <th>Documento</th>"
                        + " <th>Asunto</th>"
                        + " <th>Estatus</th>"                       
                        + "</tr>";
                    $.each(data, function(i, obj) {
                        table_hijos += "<tr>";
                        table_hijos += "    <td><button class='btn' onclick='selectDocument("+obj.id+")'>"+obj.numero_documento+"</button></td>";
                        table_hijos += "    <td>"+obj.asunto+"</td>";
                        table_hijos += "    <td><div class='label label-"+obj.label_estatus+"'>"+obj.estatus+"</div></td>";                        
                        table_hijos += "</tr>";
                    });
                    table_hijos +="</table></div>";                    
                    $('.detail-more-data').append(table_hijos);                    
                }   
            }); // EOF  $.post("model/components/itemDetail-documentos-hijos.php...
            
        }); // EOF $.post("model/components/itemDetail.php... 
        
        
        $('#closeDocumentButton').bind("click", function(){
            closeSelectedDocument();
        });
        
    });
</script>
<div id="closeDocumentButton" class="closer"></div>
<div id="detail-content">
    <div class="detail-title">
        <h4>
            <span id="numero_documento"></span>          
            <span class="label label_estatus_detail_class pull-right"></span>
        </h4>
    </div>
    
    <div class="detail-scroll">
        <div id="detail-asunto" class="detail-table-container"></div>        
        
        <div class="detail-table-container">            
            <table id="detail-table" class="table table-bordered table-striped"></table>
        </div>
        
        <div class="detail-adjuntos">
            <div id="detail-adjuntos-list">
                <!--Carga de thumbs de adjuntos-->
            </div>
        </div>
        <div class="clearfix"></div>
        
        <div class="detail-more-data"></div>
    </div>
</div>