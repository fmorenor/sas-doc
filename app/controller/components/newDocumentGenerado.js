// BOF Datepicker
	$(document).ready(function() {			
			
        // ComboBox Tipo de documento
        $('#tipo_documento_container').load("model/catalogos/catalogo_tipo_documento.php",  function(){
            $("#tipo_documento").select2({width: '100%'});
        });
         
        // BOF Campos de fecha 			
        $( "#fecha_emision_generado" ).datepicker({
            defaultDate: 0,
            minDate: new Date(2008, 1 - 1, 1),
            maxDate: 0,
            changeMonth: true,
            changeYear: true,
            //onClose: function( selectedDate ) {
            //    $( "#fecha_recepcion" ).datepicker( "option", "minDate", selectedDate );
            //    $( "#fecha_recepcion2" ).datepicker( "option", "minDate", selectedDate );
            //}
        });			
        $( "#fecha_emision_generado" ).datepicker( "option", "dateFormat", "yy-mm-dd");
        
        $('#hora_emision').timepicker({
            minuteStep: 1,
            showMeridian: false,
            showSeconds: true
        });
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
				}
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
				} 				
			});
		// BOF ComboBox destinatario
		
		// El container tiene una altura fija para convertirse en scroll
		$('#newDocumentScroll').height($(window).height() - 225);
		
		// Cerrar ventana		
		$('#closeNewDocumentButton').bind("click", function(){			
            closeNewDocument();
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
			formData['estatus'] = 4; // El estatus "generado" es 4
			formData['id_usuario'] = userData.id_usuario;			
			formData['remitente_nombre'] = $('#s2id_remitente a.select2-choice span').text();
			formData['destinatario_nombre'] = $('#s2id_destinatario a.select2-choice span').text();
            
            // Se crean parametros genericos para los campos de fecha, los cuales se llaman direfente en cada módulo
            formData['fecha_emision'] = $('#fecha_emision_generado').val();
				
			// Enviar los datos del formulario por POST
			var posting = $.post( url, formData);			
			posting.done(function( data ) {				
				// Cerrar la ventana
				closeNewDocument();
				// Refrescar la pantalla				
				$("#itemListL1").unhighlight();
				userData.estatus = '4,5'; // Se cambia el estatus para que se muetren los documentos enviados
				$('#myNav li:eq(2) a').tab('show'); 
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
                // Bitácora
                setBitacora(userData.id_usuario, userData.usuario, data.id_documento, data.numero_documento, 'insertar_generado');
			});
		}		
		
		$('#newDocumentScroll').scrollTop(1);

	});
	