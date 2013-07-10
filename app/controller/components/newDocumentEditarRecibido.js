	$(document).ready(function() {
		
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
			$('#vigencia').val(documentData.vigencia);
			loadPreviousNotes();			
						
			// BOF Campos de fecha 
			$( "#fecha_recepcion" ).datepicker({
				defaultDate: 0,
				minDate: new Date(2008, 1 - 1, 1),
				maxDate: 0,
				changeMonth: true,
				changeYear: true,
				onClose: function( selectedDate ) {
					$( "#fecha_emision" ).datepicker( "option", "maxDate", selectedDate );
				}
			});
			
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
			
			$( "#fecha_recepcion2" ).datepicker({
				defaultDate: 0,
				minDate: new Date(2008, 1 - 1, 1),
				maxDate: 0,
				changeMonth: true,
				changeYear: true,
				onClose: function( selectedDate ) {
					//$( "#fecha_emision" ).datepicker( "option", "maxDate", selectedDate );
				}
			});		
			
			$( "#fecha_emision" ).datepicker( "option", "dateFormat", "yy-mm-dd");
			$( "#fecha_recepcion" ).datepicker( "option", "dateFormat", "yy-mm-dd");
			$( "#fecha_recepcion2" ).datepicker( "option", "dateFormat", "yy-mm-dd");
			
			// Horas
			$('#hora_recepcion').timepicker({
				minuteStep: 1,
				showMeridian: false,
				showSeconds: true
			});			
			
			$('#hora_recepcion2').timepicker({
				minuteStep: 1,
				showMeridian: false,
				showSeconds: true
			});
			
			// Set Dates
			if (documentData.fecha_emision != '0000-00-00 00:00:00') {
				$( "#fecha_emision" ).datepicker('setDate', documentData.fecha_emision.substring(0,10));
			}
			if (documentData.fecha_recepcion != '0000-00-00 00:00:00') {
				$( "#fecha_recepcion" ).datepicker('setDate', documentData.fecha_recepcion.substring(0,10));
				$('#hora_recepcion').timepicker('setTime',  documentData.fecha_recepcion.substring(12,20));
			}
			if (documentData.fecha_recepcion2 != '0000-00-00 00:00:00') {
				$( "#fecha_recepcion2" ).datepicker('setDate', documentData.fecha_recepcion2.substring(0,10));
				$('#hora_recepcion2').timepicker('setTime',  documentData.fecha_recepcion2.substring(12,20));
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
					createSearchChoice: function(term, data) {
						if ($(data).filter(function() {
							return this.text.localeCompare(term)===0;
							}).length===0) {
								return {id:term, text:term};
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
			
			// BOF ComboBox asignado
			$('#asignado_a').select2({
				placeholder: 'Usuario responsable del seguimiento',
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
					var d = {id: documentData.id_asignado_a, text: documentData.nombre_asignado_a};
					if(d.text){
						element.val(documentData.id_asignado_a);
						callback(d);
					}			
				},
			});
			// BOF ComboBox asignado
			
			// Campo vigencia es de tipo spinner
			$('#vigencia').spinner();
			
		
		// El container tiene una altura fija para convertirse en scroll
		$('#newDocumentScroll').height($(window).height() - 225);
		
		// Cerrar ventana		
		$('#closeNewDocumentButton').bind("click", function(){			
            closeNewDocument(documentData.id_documento);
        });
		
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
				
				if(typeof(show)==='undefined') show = false;
				if (show == true) { 					
					$('#notas_anteriores').popover('show');
				}
			} else {
				$('#notas_anteriores').attr("disabled", "disabled");
			}
		});
	}            
	
	function deleteNote(id_nota) {
		 $.post("model/components/deleteNote.php", {id_nota: id_nota}, function(data){
			$('#notas_anteriores').popover('hide');
			$('#notas_anteriores').popover('destroy');
			loadPreviousNotes(true);
		 });
	}
	// EOF  Notas
	