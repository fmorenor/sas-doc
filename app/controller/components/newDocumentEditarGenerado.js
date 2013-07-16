	$(document).ready(function() {
		btnClass = ($(window).width() < 1024) ? 'btn-mini' : 'btn-small';
		$('#notas_anteriores').addClass(btnClass);
			
		// Obtener los datos del documento padre
			$('#selectedDocument').text(documentData.numero_documento);
			
			$('#numero_documento').val(documentData.numero_documento);	
			
			// ComboBox Tipo de documento
			$('#tipo_documento_container').load("model/catalogos/catalogo_tipo_documento.php", {id_tipo_documento: documentData.id_tipo_documento},  function(){
				$("#tipo_documento").select2({width: '100%'});
			});
			
			$('#asunto').val(documentData.asunto);
			$('#expediente').val(documentData.expediente);
			$('#anexos').val(documentData.anexos);			
			loadPreviousNotes();			
						
			// BOF Campos de fecha 			
			$( "#fecha_emision" ).datepicker({
				defaultDate: 0,
				minDate: new Date(2008, 1 - 1, 1),
				maxDate: 0,
				changeMonth: true,
				changeYear: true,
				onClose: function( selectedDate ) {
					$( "#fecha_recepcion" ).datepicker( "option", "minDate", selectedDate );
					$( "#fecha_recepcion2" ).datepicker( "option", "minDate", selectedDate );
				}
			});
			
			$( "#fecha_emision" ).datepicker( "option", "dateFormat", "yy-mm-dd");
			
			// Horas
			$('#hora_emision').timepicker({
				minuteStep: 1,
				showMeridian: false,
				showSeconds: true
			});		
			
			// Set Dates
			if (documentData.fecha_emision != '0000-00-00 00:00:00') {
				$( "#fecha_emision" ).datepicker('setDate', documentData.fecha_emision.substring(0,10));
				$('#hora_emision').timepicker('setTime',  documentData.fecha_emision.substring(12,20));
			}
			
			// EOF Campos de fecha 
			
			// BOF ComboBox remitente
				$('#remitente').select2({
					placeholder: 'Buscar un remitente',
					minimumInputLength: 5,
					allowClear: true,
					ajax: {
						url: "model/catalogos/catalogo_usuarios.php",
						dataType: 'json',
						quietMillis: 100,
						data: function (term, page) {
							return {
								term: term, //search term
								page_limit: 10, // page size
								page: page // page number
							};
						},
						results: function (data, page) {
							//return { results: data.results};
							var more = (page * 100) < data.total; // whether or not there are more results available
	 
							// notice we return the value of more so Select2 knows if more results can be loaded
							return {results: data.results, more: more};
						}
					},	
					initSelection: function(element, callback) {						
						var d = {id: documentData.id_remitente, text: documentData.nombre_remitente};
						if(d.text){
							element.val(documentData.id_remitente);
							callback(d);
						}
					},
				});
			// BOF ComboBox remitente
			
			// BOF ComboBox destinatario
				$('#destinatario').select2({
					placeholder: 'Buscar un destinatario',
					minimumInputLength: 5,
					allowClear: true,
					ajax: {
						url: "model/catalogos/catalogo_usuarios.php",
						dataType: 'json',
						quietMillis: 100,
						data: function (term, page) {
							return {
								term: term, //search term
								page_limit: 10, // page size
								page: page // page number
							};
						},
						results: function (data, page) {
							//return { results: data.results};
							var more = (page * 100) < data.total; // whether or not there are more results available
	 
							// notice we return the value of more so Select2 knows if more results can be loaded
							return {results: data.results, more: more};
						}
					},			
					createSearchChoice: function(term, data) {
						if ($(data).filter(function() {
							return this.text.localeCompare(term)===0;
							}).length===0) {
								return {id:term, text:term};
							}						
						},
					initSelection: function(element, callback) {						
						var d = {id: documentData.id_destinatario, text: (documentData.nombre_destinatario) ? documentData.nombre_destinatario : documentData.destinatario_documento_enviado};
						if(d.text){
							element.val(documentData.id_destinatario);
							callback(d);
						}
					}
				});
			// BOF ComboBox destinatario
			
			// BOF ComboBox turnado
				$.post("model/components/itemDetail-turnado-a.php", {id_documento: userData.selectedDocumentId}, function(dataTurnado){
					
					$('#turnado_a').select2({
						//placeholder: 'Elegir usuarios turnados / cc',
						minimumInputLength: 5,
						allowClear: true,
						multiple: true,
						width: ($('#newDocumentScroll').width() / 3.5),
						ajax: {
							url: "model/catalogos/catalogo_usuarios.php",
							dataType: 'json',
							quietMillis: 100,
							data: function (term, page) {
								return {
									term: term, //search term
									page_limit: 10, // page size
									page: page // page number
								};
							},
							results: function (data, page) {
								//return { results: data.results};
								var more = (page * 100) < data.total; // whether or not there are more results available
		 
								// notice we return the value of more so Select2 knows if more results can be loaded
								return {results: data.results, more: more};
							}
						},
						initSelection: function(element, callback) {
							// Cambiar los nombres de los keys del objeto por "id" y "text"
							for(var i = 0; i < dataTurnado.length; i++){
								dataTurnado[i].id = dataTurnado[i]['id_turnado_a'];
								delete dataTurnado[i].id_turnado_a;
								
								dataTurnado[i].text = dataTurnado[i]['nombre'];
								delete dataTurnado[i].nombre;
							}
							
							element.val(dataTurnado.id);
							callback(dataTurnado);
						},
					});
					
					$("#turnado_a").select2("container").find("ul.select2-choices").sortable({
						containment: 'parent',
						start: function() { $("#turnado_a").select2("onSortStart"); },
						update: function() { $("#turnado_a").select2("onSortEnd"); }
					});
				});			
				
			// BOF ComboBox turnado
			
		
		// El container tiene una altura fija para convertirse en scroll
		$('#newDocumentScroll').height($(window).height() - 225);
		
		// Cerrar ventana		
		$('#closeNewDocumentButton').bind("click", function(){			
            closeNewDocument(documentData.id_documento);
        });
		
		// Cargar el listado de archivos adjuntos para poder editarlos
		loadAdjuntos();
		
		// BOF UPLOADER
		$("#uploader").pluploadQueue({
			// General settings
			runtimes : 'html5,flash',
			url : 'model/upload.php',
			max_file_size : '2mb',
			chunk_size : '1mb',
			unique_names : true,
	
			// Resize images on clientside if we can
			//resize : {width : 320, height : 240, quality : 90},
	
			// Specify what files to browse for
			filters : [				
				{title : "Archivos PDF", extensions : "pdf"},
				{title : "Archivos de imágen", extensions : "jpg"}
			],
			
			 // Flash settings
	        flash_swf_url : 'controller/plugins/plupload/plupload.flash.swf'

		});
		
		// Client side form validation
		$('#form-nuevo-documento').submit(function(e) {
			
			var uploader = $('#uploader').pluploadQueue();
	
			// Files in queue upload them first
			if (uploader.files.length > 0) {
				// When all files are uploaded submit form
				uploader.bind('StateChanged', function() {
					if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
						//$('form')[0].submit();
						//$.doTimeout( 'click', 250, postData());
						postData();
					}
				});					
				uploader.start();
			} else {
				//alert('You must queue at least one file.');
				postData();
			}
			
			return false;
		});
		// EOF UPLOADER
		
		function postData() {
			$('.modal-back').css('z-index', '3');
			$.throbber.show({overlay: true});
			var url = $('#form-nuevo-documento').attr( 'action' );
						
			var formData = {};			
			$("#form-nuevo-documento :input").each(function(){
				if ($(this)[0].id && $(this)[0].value) {
					var id = $(this)[0].id;
					var value = $(this)[0].value;
					formData[id] = value; 
				} else if ($(this)[0].name && $(this)[0].value){
					var id = $(this)[0].name;
					var value = $(this)[0].value;
					formData[id] = value; 
				}
			});
			
			// Datos fuera del formulario
			formData['id_documento'] = userData.selectedDocumentId;	
			//formData['estatus'] = 5; // El estatus del segumiento es 5
			formData['estatus'] = documentData.id_estatus;
			formData['id_usuario'] = userData.id_usuario;			
			formData['remitente_nombre'] = $('#s2id_remitente a.select2-choice span').text();
			formData['destinatario_nombre'] = $('#s2id_destinatario a.select2-choice span').text();
			// Si el estatus del doccumento padre es 4 o 5 (generado o seguimiento) la fecha a guardar será fecha emisión
			// si tiene otro estatus será fecha_recepcion
			//formData['fecha_padre'] = (documentData.id_estatus == "4" || documentData.id_estatus == "5") ? documentData.fecha_emision : documentData.fecha_recepcion;
			
			// Enviar los datos del formulario por POST
			var posting = $.post( url, formData);			
			posting.done(function( data ) {				
				// Cerrar la ventana
				closeNewDocument();
				// Refrescar la pantalla				
				$("#itemListL1").unhighlight();
				//userData.estatus = '4,5'; // Se cambia el estatus para que se muetren los documentos enviados
				//$('#myNav li:eq(2) a').tab('show'); 
				$('#content1').load("view/main-container.php", function(){          
					$.throbber.hide();
					$('.modal-back').css('z-index', '1');
					// Generar los thumbs para los archivos que se subieron
					if (data.files) {
						$.each(data.files, function(){
							$.post("model/components/generateThumb.php", {file: this});
						});	
					}					
				});
				// console.log(data.msg);
			});
		}		
		
		$('#newDocumentScroll').scrollTop(1);
	});
	
	// BOF Notas
	function loadPreviousNotes(show) {
		$.post("model/components/itemDetail-notas.php", {id_documento: userData.selectedDocumentId}, function(data){                
			if(data.length > 0){
				var table_notas = "<table class='table table-bordered table-striped'>"
					+ "<tr>"
					+ " <th width='42%'>Nota</th>"
					+ " <th width='33%'>Usuario</th>"
					+ " <th width='15%'>Fecha</th>"
					+ " <th width='5%'></th>"     
					+ "</tr>";
				$.each(data, function(i, obj) {
					table_notas += "<tr>";
					table_notas += "    <td>"+obj.nota+"</td>";
					table_notas += "    <td>"+obj.nombre+"</td>";
					table_notas += "    <td>"+obj.fecha+"</td>";
					table_notas += "    <td><a class='btn btn-mini btn-danger' title='Eliminar esta nota' href='javascript:confirmationDialog(\"EliminarNota\", "+obj.id+")'><i class='icon-trash icon-white'></i></a></td>";  
					table_notas += "</tr>";
				});
				table_notas +="</table>";  
				
				$('#notas_anteriores').popover({
					placement: 'left',
					html: true,
					title:"Notas asociadas a este documento",					
					content: function() {
					   var message = table_notas;
						 return message;
					}
				});
				
				//Parámetros opcionales
				if(typeof(show)==='undefined') show = false;
				if (show == true) { 					
					$('#notas_anteriores').popover('show');
				}
			} else {
				$('#notas_anteriores').attr("disabled", "disabled");
			}
		});
	}            
	
	function deleteNote(id) {
		 $.post("model/components/deleteNote.php", {id_nota: id}, function(data){
			$('#notas_anteriores').popover('hide');
			$('#notas_anteriores').popover('destroy');
			loadPreviousNotes(true);
		 });
	}
	// EOF  Notas
	
	// BOF Adjuntos
	function loadAdjuntos(reload) {
        $.post("model/components/itemDetail-adjuntos.php", {id_documento: userData.selectedDocumentId}, function(data){
			
			//Parámetros opcionales
			if(typeof(reload)==='undefined') reload = false;
			
			// Si el parametro "reload" es true se limpia el contenedor para agregar los nuevos datos
			if (reload == true) {
				$('.detail-adjuntos').html('<div id="detail-adjuntos-list"></div>');
				$('#adjuntos-title').remove();
				$('#foo1_prev').remove();
				$('#foo1_next').remove();
				$('.clearfix').remove();
			}
            
            // Si existen registros de documentos anjuntos
            if(data.length > 0){
				
				// Agregar titulo del módulo "adjuntos"	
				$('.detail-adjuntos').before('<h5 id="adjuntos-title">Archivos adjuntos de este documento</h5>');					
                
                // Agregar clase al contenedor, si no existe no se agrega para no meter un espacio vacio
                $('.detail-adjuntos').css('padding', '0 20px 15px 0');
                                    
                // Agregar las flechas del carrousel (ocultas)
				$('.detail-adjuntos').after('<div class="clearfix"></div>');
				$('.clearfix').after('<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>'
                                 +'<a class="next" id="foo1_next" href="#"><span>next</span></a>');
                
                // Agregar las vistas previas respectivas a cada archivo adjunto
                $.each(data, function(i, obj) {
                    $('#detail-adjuntos-list').append('<div>'
                        +'    <div class="image-holder"><img src="'+obj.thumb+'" title="'+obj.path+'" /></div>'
						+'    <div class="btn btn-mini" onclick="window.open(\'../documents/'+obj.path+'\')"><i class="icon-download"></i> Descargar</div>'
                        +'    <div class="btn btn-mini btn-danger" onclick="javascript:confirmationDialog(\'EliminarAdjunto\', '+obj.id+')"><i class="icon-trash icon-white"></i> Eliminar</div>'
                        +'</div>');
                });                    
            
                // Iniciar el carrusel de thumbs de adjuntos
                $("#detail-adjuntos-list").carouFredSel({
                    circular	: false,
                    infinite	: false,
                    auto 		: false,
                    width       : "100%",
                    height      : 205,
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
                var itemDetailHeight = ($('#newDocument').height()+30);
                
                if (adjuntosTop < itemDetailHeight) { // Si hay espacio se muestran
                    $('a.prev').css('display', 'block');
                    $('a.next').css('display', 'block');
                    $('a.prev').css('top', adjuntosTop - 20);
                    $('a.next').css('top', adjuntosTop - 20);
                } else {  // Si no hay espacio se ocultan           
                    $('a.prev').css('display', 'none');
                    $('a.next').css('display', 'none');
                }
                
                // Mover las flechas del carrousel de acuerdo al scroll de .detail-scroll
                // (debido a que el scroll rompe la posicion absoluta)                                 
                var scrollTop = $('a.prev').css('top').replace("px", "");                    
                $('#newDocumentScroll').scroll(function(){
                    $.doTimeout( 'scroll', 5, function(){
                        
                        var newAdjuntosTop = ($('.detail-adjuntos').offset()) ? $('.detail-adjuntos').offset().top : 0;
                        if (((newAdjuntosTop + 60) < itemDetailHeight) && (newAdjuntosTop > 81)) {
                            $('a.prev').css('display', 'block');
                            $('a.next').css('display', 'block');
                            $('a.prev').css('top', newAdjuntosTop - 20);
                            $('a.next').css('top', newAdjuntosTop - 20);                                
                        } else {
                            $('a.prev').css('display', 'none');
                            $('a.next').css('display', 'none');
                        }
                    });
                });                    
                // EOF Si hay documentos adjuntos 
            } else {
				$('.detail-adjuntos').parent().parent().remove();
			}           
        });		
	}
	
	function deleteAdjunto(id) {
		$.post("model/components/deleteAdjunto.php", {id_adjunto: id}, function(data){
		   loadAdjuntos(true);
		});
	}
	// EOF  Adjuntos
	