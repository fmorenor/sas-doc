$(document).ready(function() {
        
    $.post("model/components/itemDetail.php", {id_documento: userData.selectedDocumentId}, function(data){
        documentData = data;
        // BOF LLenar de datos la ficha            
            // Titulo
            $('#numero_documento').text(data.numero_documento);
            $('.label_estatus_detail_class').addClass('label-'+data.label_estatus);            
            $('.label_estatus_detail_class').text(data.estatus);
            // $('.label_estatus_detail_class').on('click', function(){switchList(data.id_estatus)});
            if (documentData.id_estatus <= 3) {
                $('.label_estatus_detail_class').prepend('<i class="icon-refresh icon-white"></i> ');
                $('.label_estatus_detail_class').css('cursor', 'pointer');
                //$('.label_estatus_detail_class').on('click', function(){changeStatus(documentData.id_documento)});
                // PopOver para cambiar el estatus   
                $('.label_estatus_detail_class').popover({
                    placement: 'left',
                    html: true,
                    title:"Cambiar el estatus del documento",					
                    content: function() {                           
                        var message = '   <button type="button" class="btn btn-small btn-danger" onclick="confirmationDialog(\'CambiarEstatus\',1)">Recibido</button>'
                        message += '   <button type="button" class="btn btn-small btn-warning" onclick="confirmationDialog(\'CambiarEstatus\',2)">Turnado</button>'
                        message += '   <button type="button" class="btn btn-small btn-success" onclick="confirmationDialog(\'CambiarEstatus\',3)">Atendido</button>';                        
                        return message;
                    }
                });
                
                $(':not(#anything)').on('click', function (e) {
                    $('.popover-link-status').each(function () {
                        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                            $(this).popover('hide');
                            return;
                        }
                    });
                });
                
                $('.label_estatus_detail_class').attr('title', 'Cambiar el estatus del documento');
            }            
            
            if (data.id_estatus == '1' || data.id_estatus == '2') {            
                if(data.dias_restantes){
                    $('.label_estatus_detail_class').after('<span class="label label-important label_vigencia_detail">Vigencia: '+data.vigencia+' días / Días restantes: '+data.dias_restantes+'</span>');
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
                if (data.expediente) {
                     $('#detail-table').append("<tr>"
                    +"    <th>Tipo de documento</th><td>"+data.tipo_documento+"</td>"
                    +"    <th>Expediente</th><td>"+data.expediente+"</td>"
                    +"</tr>");
                } else {
                     $('#detail-table').append("<tr>"
                    +"    <th>Tipo de documento</th><td colspan='3'>"+data.tipo_documento+"</td>"
                    +"</tr>");
                }                   
            } else {
                if (data.expediente) {
                     $('#detail-table').append("<tr>"
                    +"    <th>Expediente</th><td colspan='3'>"+data.expediente+"</td>"
                    +"</tr>");
                } 
            }
            
            if(data.anexos){
               $('#detail-table').append("<tr>"
                +"  <th>Anexos</th><td colspan='3'>"+data.anexos+"</td>"
                +"</tr>");
            }
            
            if(data.fecha_emision != '0000-00-00 00:00:00'){  
                switch(data.id_estatus){
                    case '4': // Generado
                        $('#detail-table').append("<tr>"
                        +"  <th>Fecha emisión</th><td colspan='3'>"+data.fecha_emision+"</td>"
                        +"</tr>");
                        break;
                    case '5': // Seguimiento
                        $('#detail-table').append("<tr>"                            
                        +"  <th>Fecha docto. origen</th><td>"+data.fecha_recepcion+"</td>"
                        +"  <th>Fecha de seguimiento</th><td>"+data.fecha_emision+"</td>"
                        +"</tr>");
                        break;                       
                    default: // Recibido, Turnado, Atendido
                        $('#detail-table').append("<tr>"
                        +"  <th>Fecha emisión</th><td>"+data.fecha_emision+"</td>"
                        +"  <th>Fecha recepción</th><td>"+data.fecha_recepcion+"</td>"
                        +"</tr>");
                        break;
                }    
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
        $('.detail-scroll').height(userData.itemDetailHeight - $('.detail-title').height() - 10); // 10: margenes
        
        
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
                
                var adjuntosTop = ($('.detail-adjuntos').offset()) ? $('.detail-adjuntos').offset().top : 0;
                var itemDetailHeight = ($('#itemDetail').height()+30);
                
                if ((adjuntosTop + 40) < itemDetailHeight) { // Si hay espacio se muestran
                    $('a.prev').css('display', 'block');
                    $('a.next').css('display', 'block');
                    $('a.prev').css('top', adjuntosTop);
                    $('a.next').css('top', adjuntosTop);
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
        
        // Cargar la tabla de notas
        loadNotes();        
        
        // Cargar el listado de "documentos padre"
        $.post("model/components/itemDetail-documentos-padre.php", {id_documento: userData.selectedDocumentId}, function(data){                
            if(data.length > 0){
                var table_padre = "<h5>Documentos a los que da seguimiento este documento</h5>";
                table_padre += "<div class='detail-table-container'>"
                    + "<table class='table table-bordered table-striped' id='table_padre'>"
                    + "<tr>"
                    + " <th>Documento</th>"
                    + " <th>Asunto</th>"
                    + " <th>Estatus</th>"                       
                    + "</tr>";
                $.each(data, function(i, obj) {
                    table_padre += "<tr>";
                    table_padre += "    <td><button class='btn' onclick='selectDocument("+obj.id+")'><i class='icon-arrow-left'></i> "+obj.numero_documento+"</button></td>";
                    table_padre += "    <td>"+obj.asunto+"</td>";
                    table_padre += "    <td><div class='label label-"+obj.label_estatus+"'>"+obj.estatus+"</div></td>";                        
                    table_padre += "</tr>";
                });
                table_padre +="</table></div>";                    
                $('.detail-documento-padre').html(table_padre);                    
            }   
        }); // EOF  $.post("model/components/itemDetail-documentos-padre.php...
        
        // Cargar el listado de "documentos hijos"
        $.post("model/components/itemDetail-documentos-hijos.php", {id_documento: userData.selectedDocumentId}, function(data){                
            if(data.length > 0){
                var table_hijos = "<h5>Documentos derivados de este documento</h5>";
                table_hijos += "<div class='detail-table-container'>"
                    + "<table class='table table-bordered table-striped' id='table_hijos'>"
                    + "<tr>"
                    + " <th>Documento</th>"
                    + " <th>Asunto</th>"
                    + " <th>Estatus</th>"                       
                    + "</tr>";
                $.each(data, function(i, obj) {
                    table_hijos += "<tr>";
                    table_hijos += "    <td><button class='btn' onclick='selectDocument("+obj.id+")'><i class='icon-arrow-right'></i> "+obj.numero_documento+"</button></td>";
                    table_hijos += "    <td>"+obj.asunto+"</td>";
                    table_hijos += "    <td><div class='label label-"+obj.label_estatus+"'>"+obj.estatus+"</div></td>";                        
                    table_hijos += "</tr>";
                });
                table_hijos +="</table></div>";                    
                $('.detail-documento-hijo').html(table_hijos);                    
            }   
        }); // EOF  $.post("model/components/itemDetail-documentos-hijos.php...
        
    }); // EOF $.post("model/components/itemDetail.php... 
    
    
    $('#closeDocumentButton').bind("click", function(){
        closeSelectedDocument();
    });
});
    
function switchList(id_estatus){
    switch (id_estatus) {
        case 1:
        case 2:   userData.estatus = "1,2";
                    $('#myNav li:eq(0) a').tab('show');
                    break;
        case 3:   userData.estatus = "3";
                    $('#myNav li:eq(1) a').tab('show');
                    break;
        case 4:
        case 5:   userData.estatus = "4,5";
                    $('#myNav li:eq(2) a').tab('show');
                    break;
    }
    
    $("#itemListL1").unhighlight();
    $('#itemListL1').load("view/components/itemList.php?method=POST");            
}

// BOF Notas
function loadNotes() {            
    $.post("model/components/itemDetail-notas.php", {id_documento: userData.selectedDocumentId}, function(data){                
        if(data.length > 0){           
            var table_notas = "<div class='row-fluid'><div class='span1'><h5>Notas</h5></div>";
            table_notas += "<div class='span3' style='padding-top:9px'><a href='javascript:void(0)' id='agregar_notas' class='btn btn-mini popover-link'><i class='icon-plus-sign'></i> Agregar más notas...</a></div></div>";
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
            $('.detail-notas').html(table_notas);   
        }  else {
            var table_notas = "<a href='javascript:void(0)' id='agregar_notas' class='btn btn-small popover-link'><i class='icon-plus-sign'></i> Agregar notas a este documento</a>";
            $('.detail-notas').html(table_notas);
        }
        // PopOver para agregar notas   
        $('#agregar_notas').popover({
            placement: 'right',
            html: true,
            title:"Agregar una nueva nota a este documento",					
            content: function() {
               var message = '<textarea rows="2" id="nueva_nota" placeholder="Nueva nota" class="fullsize-textarea" />';
               message += '<br /><a href="javascript:void(0)" class="btn btn-small" onClick="saveNewNote()">Guardar</a>';
               message += ' <a href="javascript:void(0)" class="btn btn-small" onClick="$(\'#agregar_notas\').popover(\'hide\');">Cancelar</a>'
               return message;
            }
        });
        
        $(':not(#anything)').on('click', function (e) {
            $('.popover-link').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons and other elements within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                    return;
                }
            });
        });
    }); 
}
// EOF Notas

function saveNewNote(){    
    var n = $('textarea#nueva_nota').val();
    $.post("model/components/newNote.php", {id_documento: userData.selectedDocumentId, nota: n, id_usuario: userData.id_usuario}, function(data){
        $('#agregar_notas').popover('hide');
        loadNotes();
    });
}

function changeStatus(id_estatus) {
    $.post("model/components/changeStatus.php", {'id_documento': documentData.id_documento, 'id_estatus': id_estatus}, function(data){
        // 1. Ocultar el PopOver
        $('.label_estatus_detail_class').popover('hide');
        
        // 2. Cambiar los datos del Label y del itemList
        var lastStatusId = documentData.id_estatus;
        var lastStatus = documentData.estatus;
        documentData.id_estatus = data.id_estatus;
        documentData.estatus = data.estatus;
        $('.label_estatus_detail_class').removeClass('label-important label-warning label-success').addClass('label-'+data.label_estatus);            
        $('.label_estatus_detail_class').text(data.estatus);
        $('.label_estatus_detail_class').prepend('<i class="icon-refresh icon-white"></i> ');
        $('.label_estatus_detail_class').css('cursor', 'pointer');
        
        // 3. Cargar el itemList del nuevo estatus        
        switchList(id_estatus);
        
        // Bitácora
        var objeto = "Estatus anterior: "+lastStatusId+" - "+lastStatus+" -> Nuevo estatus: "+id_estatus+" - "+documentData.estatus;
		setBitacora(userData.id_usuario, userData.usuario, documentData.id_documento, documentData.numero_documento, 'cambiar_estatus', objeto);
    });
}