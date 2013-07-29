var userData;
var documentData;
var sortedData;

 $(document).ready(function() {
   
	$.throbber.show({overlay: true});
	userData = JSON.parse(localStorage['userData']);
	userData.level = 1;
	userData.searchType = 'general';
    userData.estatus = '1,2';
	 
    // BOF Encabezado 
	$('#encabezado').load('view/encabezado.php', function(){
         var nombre_completo = (userData.nombre_completo == null) ? userData.usuario : userData.nombre_completo;
         
         var pendientesBadge = '<a id="popover-pendientes" href="javascript:void(0)" rel="desc">'
         + '<span class="badge badge-important badge-pendientes"></span>'
         + '</a>';
         
         $.post("model/components/documentosPendientes.php", {'id_privilegios': userData.id_privilegios, 'id_usuario': userData.id_usuario}, function(data){
            userData.documentos_pendientes = data.count_pendientes;
            $('.badge-pendientes').text(userData.documentos_pendientes);
            $('.badge-pendientes').attr('title', 'No. de documentos pendientes de atender');
            $('.badge-pendientes').css('visibility', 'visible');
            $('#popover-pendientes').popover('show');
         });
         
         // Botón de Herramientas
         var toolsButton = '<div class="btn-group">'
         + '<a class="btn btn-mini btn-inverse dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" title="Herramientas">'
         + '<i class="icon-wrench icon-white"></i> '
         + '<span class="caret"></span>'
         + '</a>'
         + '<ul class="dropdown-menu pull-right">'
         + '<li><a href="javascript:void(0)" onclick="confirmationDialog(\'VerBitacora\')">Ver la bit&aacute;cora del sistema</a></li>'
         + '</ul>'
         + '</div> ';
         toolsButton = (userData.id_privilegios == '1') ? toolsButton : '';
         
         // Mensaje de bienvenida
         $('.welcome-message').html("Bienvenid@<br /><div class='nombre'>"+nombre_completo+"</div>"+pendientesBadge+toolsButton+"<a href='../login/view/logout.php?rnd="+Math.random()+"' class='btn btn-mini btn-inverse'><i class='icon-user icon-white'></i> Salir</a>");
         
         $('#popover-pendientes').popover({
            placement: 'bottom',
            html: true,
            //trigger: 'manual',
            title:"&iexcl;Documentos pendientes de atender!",					
            content: function() {                           
               var message = 'Hay '+userData.documentos_pendientes+' documentos que tienen una vigencia'
               + ' establecida para su atenci&oacute;n y siguen pendientes.'
               + '<br /><br />Por favor atenderlos a la brevedad.'
               + '<br /><br /><a href="javascript:void(0)" class="btn btn-mini" onClick="sortPendientes()"><i class="icon-arrow-down"></i> Ordenar por d&iacute;as restantes</a>'
               + '<a href="javascript:void(0)" class="btn btn-mini pull-right" onClick="$(\'#popover-pendientes\').popover(\'hide\');">Cerrar</a>';
                return message;
            }
         });
         
         $(':not(#anything)').on('click', function (e) {
            $('#popover-pendientes').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons and other elements within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                    return;
                }
            });
        });
         
         $('#btn-new-document').click(function(){
             createNewDocumentWindow('Recibido');
         });
	});
    // EOF Encabezado
	
	// BOF Nav Container
	$('#nav-container').load('view/nav-container.php', function(){
		$('#myNav li a').bind("click",function(event){
			event.preventDefault();
			userData.estatus = $(this).attr("rel");
			
			$("#itemListL1").unhighlight();
			$('#itemListL1').load("view/components/itemList.php?method=GET");
			
		});
		
		// Botón de limpieza...
		$('#searchClear').css('display', 'none');		
		$('#searchClear').bind("click",function(event){
			$('#searchClear').css('display', 'none');
			$('#searchInput').val(null);
			search();
		});
		
		$('#searchClear2').bind("click",function(event){
			$('#searchClear2').css('display', 'none');
			$('#searchDateFrom').val(null);
			$('#searchDateTo').val(null);
			searchDateRange();
		});
		
		// BOF Datepicker		
		$( "#searchDateFrom" ).datepicker({
			defaultDate: 0,
			minDate: new Date(2008, 1 - 1, 1),
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
			onClose: function( selectedDate ) {
			$( "#searchDateTo" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		
		$( "#searchDateTo" ).datepicker({
			defaultDate: 0,
			minDate: new Date(2008, 1 - 1, 1),
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
			onClose: function( selectedDate ) {
			$( "#searchDateFrom" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
		
		$( "#searchDateFrom" ).datepicker( "option", "dateFormat", "yy-mm-dd");
		$( "#searchDateTo" ).datepicker( "option", "dateFormat", "yy-mm-dd");
		$('#searchClear2').css('display', 'none');
		// EOF Datepicker
        
        // Botones de edición del documento selecionado
        $('#btn-edit-document').click(function(){
            switch (documentData.id_estatus) {
               case '1':
               case '2': 
               case '3': t = "Recibido"; break;
               case '4': t = "Generado"; break;
               case '5': t = "Seguimiento"; break;
            }
			createNewDocumentWindow('Editar'+t);
		});
        
         $('#btn-follow-document').click(function(){
			createNewDocumentWindow('Seguimiento');
		});
         
         $('#btn-delete-document').click(function(){
            confirmationDialog('Eliminar');
		});
         
         // Funciones de hover en menu de ordenamiento 
         $('#sorting li a').hover(
            function(){
               if($(this).attr('rel') == 'asc'){
                  $(this).find('i').removeClass('icon-chevron-down').addClass('icon-arrow-up');
                  $(this).attr('title', 'Ordenar de manera ascendente');
               } else {
                  $(this).find('i').removeClass('icon-chevron-up').addClass('icon-arrow-down');
                  $(this).attr('title', 'Ordenar de manera descendente');                  
               }
            },
            function(){
               $(this).find('i').removeClass('icon-arrow-up');
               $(this).find('i').removeClass('icon-arrow-down');
               $(this).attr('title', '');
               
               if($(this).parent().hasClass('selected')){
                  if($(this).attr('rel') == 'desc'){
                     $(this).find('i').addClass('icon-chevron-up');
                  } else {
                     $(this).find('i').addClass('icon-chevron-down');
                  }
               }
            }
         );
		
	});
	// EOF Nav Container
	 
	// Cargar los contenidos del components 
	$('#content1').load("view/main-container.php", function(){          
	   $.throbber.hide();	   
	});
 });
 
function createNewDocumentWindow(type) {
   // Si existe el div itemDetail se procede a ocultarlo y a destruirlo. Despues se crea la ventana "newDocument"	 
   if ($('#itemDetail').length > 0) {
       
       $('#itemDetailTools').fadeOut(function(){
           setSearchGroup(userData.searchType);	
       });	
       
       $('#itemDetail').slideUp(function(){
               $('#itemDetail').remove();
               newDocument(type);               
       });
       
   } else { //Si no existe se crea y luego se muestra	
       newDocument(type);
   }
}

// Funciones de búsqueda 
function search() {
	if ($('#searchInput').val()) {
		$('#searchClear').css('display', 'inline');
	}	
	// Limpiar la función de resaltar palabras buscadas en el documento
	$("#itemListL1").unhighlight();
	userData.searchInput = $('#searchInput').val();
	$('#itemListL1').load("view/components/itemList.php?method=POST");
	$('#itemContentL1').load("view/components/itemContent.php?method=POST");
}

function searchDateRange() {
	if ($('#searchDateFrom').val() && $('#searchDateTo').val()) {
		$('#searchClear2').css('display', 'inline');
		userData.searchInput = $('#searchDateFrom').val()+","+$('#searchDateTo').val();
	} else {
		$('#searchDateFrom').val(null);
		$('#searchDateTo').val(null);
		userData.searchInput = null;
	}
	// Limpiar la función de resaltar palabras buscadas en el documento
	$("#itemListL1").unhighlight();
	$('#itemListL1').load("view/components/itemList.php?method=POST");
	$('#itemContentL1').load("view/components/itemContent.php?method=POST");
}

function setSearchGroup(type) {
	userData.searchType = type;
	switch (type) {
		case 'general': /*$('#searchGroup1').css('display', 'block');
						$('#searchGroup2').css('display', 'none');*/
						
						$('#searchGroup2').fadeOut(function(){
							$('#searchGroup1').fadeIn();
						});
						break;
		case 'recepcion': 	/*$('#searchGroup1').css('display', 'none');
							$('#searchGroup2').css('display', 'block');*/
							$('#searchGroup1').fadeOut(function(){
								$('#searchGroup2 .label-search-style').html("B&uacute;squeda por fecha de recepci&oacute;n");
								$('#searchGroup2').fadeIn();
							});							
							break;
		case 'emision': /*$('#searchGroup1').css('display', 'none');
						$('#searchGroup2').css('display', 'block');*/						
						$('#searchGroup1').fadeOut(function(){
							$('#searchGroup2 .label-search-style').html("B&uacute;squeda por fecha de emisi&oacute;n");
							$('#searchGroup2').fadeIn();
						});						
						break;
	}
}

function selectDocument(id) {
	// Mostrar herramientas del item	
	$('#searchGroup1').fadeOut(function(){
		$('#searchGroup2').fadeOut(function(){
			$('#itemDetailTools').fadeIn();
		});
	});
	
	// Si existe el div itemDetail se procede a ocultarlo y a destruirlo. Despues se crea y luego se muestra	 
	if ($('#itemDetail').length > 0) {
		
		$('#itemDetail').slideUp(function(){
			$('#itemDetail').remove();
			$.doTimeout( 'click', 250, function(){
				loadDocument(id);	
			});
		});		
		
	} else { //Si no existe se crea y luego se muestra	
		loadDocument(id);		
	}
	
}

function closeSelectedDocument() {
	// Ocultar herramientas del item
	$('#itemDetailTools').fadeOut(function(){
		setSearchGroup(userData.searchType);	
	});	
	
	$('#itemDetail').slideUp(function(){
			$('#itemDetail').remove();
	});
}

function loadDocument(id) {
	$('#content1').append('<div id="itemDetail"></div>');
		
	$('#itemDetail').css('left', $('#itemListL1').width());
	$('#itemDetail').width($(document).width() - $('#itemListL1').width() - 45); // 45px por el padding en el CSS
	$('#itemDetail').height($(window).height() - 155); // 145px por el top y el padding en el CSS
	
	$('#itemDetail').slideDown({
		duration: 750,
		specialEasing: {
			width: 'linear',
			height: 'easeOutBounce'
		},
		complete: function(){ // PRAGMA, puede ser "complete" o "start", depende del efecto deseado...
            userData.selectedDocumentId = id;
            userData.itemDetailHeight = $('#itemDetail').height();
            $('#itemDetail').load("view/components/itemDetail.php");
		}
	});
}

function newDocument(type) {
	// Crear ventana newDocument si no existe
	if ($('#newDocument').length == 0) {
         if ($('#bitacoraWindow').length == 0) { // Mostrar el modal si no existe bitacoraWindow
            toggleModal();
         }
         $('#content1').append('<div id="newDocument"></div>');
         
         var newDocumentMargin = 20;
         $('#newDocument').width($(window).width() - (newDocumentMargin * 4)); // 80px para dejar un espacio a ambos lados
         $('#newDocument').height($(window).height() - 140); // 160px por el top y el padding en el CSS
         $('#newDocument').css('left', newDocumentMargin);
         
         $('#newDocument').slideDown({
             duration: 850,
             specialEasing: {
                width: 'linear',
                height: 'easeOutBounce'
             },
             complete: function(){ // PRAGMA, puede ser "complete" o "start", depende del efecto deseado...
                $('#newDocument').load("view/components/newDocument"+type+".php");
             }
         });	
	}
}

function deleteDocument() {
   $('#itemDetailTools').fadeOut(function(){
      setSearchGroup(userData.searchType);	
   });	
   
   $('#itemDetail').slideUp(function(){
       $('#itemDetail').remove();
       // Borrar documento y refrescar el listado y los contadores
       $.post("model/components/deleteDocument.php", {'id_documento': userData.selectedDocumentId}, function(){
          $('#content1').load("view/main-container.php");
          // Bitácora
          setBitacora(userData.id_usuario, userData.usuario, documentData.id_documento, documentData.numero_documento, 'eliminar_documento');
       });
   });       
}

function closeNewDocument(id_documento) {	
   if (documentData) {
      documentData = null;
   }
   
   $('#newDocument').slideUp(function(){
         if ($('#bitacoraWindow').length == 0) { // Mostrar el modal si no existe bitacoraWindow
            toggleModal();
         }
         $('#newDocument').remove();			
   });
   
   // Abrir el documento anterior
   // Parametros opcionales
   if(typeof(id_documento)==='undefined') id_documento = null;
   else selectDocument(id_documento);   
}

function toggleModal() {
	if ($('.modal-back').css('display') == 'none') {
		$('.modal-back').fadeIn();
	} else {
		$('.modal-back').fadeOut();
	}
}

// Función para confirmar antr de ejeutar una acción.
// Se le envia como parámetro el tipo de evento deseado y ya dentro con un SWITCH se decide la acción.
function confirmationDialog(eventType, id){
   // Parametros opcionales
   if(typeof(id)==='undefined') id = null;
   
   switch (eventType) {
      case 'Eliminar':  title = "&iquest;Est&aacute;s segur@ de esto?";
                        text = "Este documento se eliminar&aacute; definitivamente, as&iacute; como todos los datos y archivos asociados a el.";
                        if($('#table_hijos').length > 0) text += "<br /><br />Adem&aacute;s, los documentos derivados de este documento quedar&aacute;n sin referencia de segumiento.";
                        text += "<br /><br />Si estas segur@ por favor conf&iacute;rmalo.";
                        break;
      case 'EliminarNota':  title = "&iquest;Est&aacute;s segur@ de esto?";
                        text = "Esta nota se eliminar&aacute; definitivamente.";                        
                        text += "<br /><br />Si estas segur@ por favor conf&iacute;rmalo.";
                        break;
      case 'EliminarAdjunto':  title = "&iquest;Est&aacute;s segur@ de esto?";
                        text = "Este archivo adjunto se eliminar&aacute; definitivamente, a&uacute;n cuando no guardes los cambios del documento.";                        
                        text += "<br /><br />Si estas segur@ por favor conf&iacute;rmalo.";
                        break;
      case 'VerBitacora':  title = "Bit&aacute;cora del SAS-DOC";
                        text = "En la bit&aacute;cora del sistema podr&aacute;s ver las actividades de los usuarios que pertenecen a tu &aacute;rea de trabajo.";                        
                        text += "<br /><br />Por favor conf&iacute;rma tus datos.";
                        break;
      case 'CambiarEstatus':   title = "&iquest;Est&aacute;s segur@ de esto?";
                        text = "Se cambiar&aacute; el estatus del documento y esta acci&oacute;n quedar&aacute; registrada en la bit&aacute;cora.";                        
                        text += "<br /><br />Si estas segur@ por favor conf&iacute;rmalo.";
                        break;
   }
   
   var dcm = '<div id="dialog-confirmation-modal" title="'+title+'" style="display: none">'     
      +'<p>'+text+'</p>'
      +'<input type="password" name="confirmationPassword" id="confirmationPassword" placeholder="Escribe tu Contrase&ntilde;a" value="" />'
      +'<div id="confirmationMessage"></div>'
      +'</div>';      
   $('body').append(dcm);
   
  
   $('#dialog-confirmation-modal').dialog({
      autoOpen: false,
      //height: 170,
      modal: true,
      close: function(event, ui) {
         $('#dialog-confirmation-modal').remove();
      },
      buttons:
         {
            'Confirmar': function() {                
               confirmationCheck(id);                   
            },
            'Cancelar': function() {
               $( this ).dialog( "close" );
               $('#dialog-confirmation-modal').remove();
            }
         }
   });
   
   $( "#dialog-confirmation-modal" ).dialog("open");
   
   // Asociarle el evento de confirmationCheck al input Password, cuando se presione la tecla ENTER
   $('#confirmationPassword').keypress(function (e) {
      if (e.which == 13) {
        confirmationCheck(id);
      }
   });
   
   function confirmationCheck(elem) {
      $.post('../login/model/verify-user.php',{
         username: userData.usuario,
         password: $('#confirmationPassword').val()
      },
      function(data) {
         $('#dialog-confirmation-modal').dialog( "close" );
         $('#dialog-confirmation-modal').remove();
         if(data.usuario == userData.usuario){               
            switch (eventType) {
               case 'Eliminar':  deleteDocument();
                                 break;
               case 'EliminarNota':  deleteNote(elem);
                                 break;
               case 'EliminarAdjunto':  deleteAdjunto(elem);
                                 break;
               case 'VerBitacora':  getBitacora(userData.id_usuario);
                                 break;
               case 'CambiarEstatus':  changeStatus(elem);
                                 break;
            }
         }
      }).fail(function() {
          $('#confirmationMessage').html('<div class="alert alert-error">Datos incorrectos, int&eacute;ntalo nuevamente.</div>');
      });  
   }
}

function setBitacora(id_usuario, usuario, id_documento, numero_documento, evento, objeto) {
   //Parámetros opcionales
   if(typeof(objeto)==='undefined') objeto = '';
   $.post('model/components/setBitacora.php',
         {'id_usuario': id_usuario,
         'usuario': usuario,
         'id_documento': id_documento,
         'numero_documento':numero_documento,
         'evento':evento,
         'objeto': objeto}
   );
}

function getBitacora(id_usuario) {
   // Crear ventana newDocument si no existe
   if ($('#bitacoraWindow').length == 0) {
      
      if ($('#newDocument').length == 0) { // Mostrar el modal si no existe newDocument
         toggleModal();
      }
      
       $('#content1').append('<div id="bitacoraWindow"></div>');
       
       var bitacoraWindowMargin = 20;
       $('#bitacoraWindow').width($(window).width() - (bitacoraWindowMargin * 4)); // 80px para dejar un espacio a ambos lados
       $('#bitacoraWindow').height($(window).height() - 140); // 160px por el top y el padding en el CSS
       $('#bitacoraWindow').css('left', bitacoraWindowMargin);
       
       $('#bitacoraWindow').slideDown({
           duration: 850,
           specialEasing: {
              width: 'linear',
              height: 'easeOutBounce'
           },
           complete: function(){ // PRAGMA, puede ser "complete" o "start", depende del efecto deseado...
              $('#bitacoraWindow').load("view/components/bitacoraWindow.php");
           }
       });	
   }
}

function sortList(elem, direction) {
   if(typeof(direction)==='undefined') direction = $(elem).attr('rel');
   userData.sortProp = $(elem).attr('id');      
   
   $(elem).parent().parent().find('li').removeClass('selected');
   $(elem).parent().parent().find('i').removeClass('icon-arrow-down');
   $(elem).parent().parent().find('i').removeClass('icon-arrow-up');
   $(elem).parent().parent().find('i').removeClass('icon-chevron-down');
   $(elem).parent().parent().find('i').removeClass('icon-chevron-up');
   $(elem).parent().parent().find('i').removeClass('icon-white');
   
   if(direction == 'asc'){
      userData.sortDirect = 1;
      // Despues de establecer el ordenamiento se cambian las flechas para mostrar el estatus actual
      $(elem).attr('rel','desc');      
      $(elem).find('i').addClass('icon-chevron-up icon-white');
      $(elem).parent().addClass('selected');
   } else {
      userData.sortDirect = -1;
      // Despues de establecer el ordenamiento se cambian las flechas para mostrar el estatus actual
      $(elem).attr('rel','asc');
      $(elem).find('i').addClass('icon-chevron-down icon-white');            
      $(elem).parent().addClass('selected');
   }
   
   // Ordenamiento 
   sortedData.sort(compare);
   
   grid = new Slick.Grid("#myGridL1", sortedData, columns, options);
}

function sortPendientes() {
   //if (userData.estatus != '1,2') {
      userData.estatus = '1,2';
      $('#itemListL1').load("view/components/itemList.php?method=GET");
   //}   
   $('#myNav li:eq(0) a').tab('show');
   sortList($('#sort_dias_restantes'), 'desc');
   $('#popover-pendientes').popover('hide');
}

function compare(a,b) {
   if (a[userData.sortProp] < b[userData.sortProp])
      return (-1 * userData.sortDirect);
   if (a[userData.sortProp] > b[userData.sortProp])
     return (1 * userData.sortDirect);
   return 0;
}   

 
