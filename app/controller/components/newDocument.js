// BOF Datepicker
	$(document).ready(function() {
		
		// El container tiene una altura fija para convertirse en scroll
		$('#newDocumentScroll').height($(window).height() - 225);
		
		// Cerrar ventana		
		$('#closeNewDocumentButton').bind("click", function(){
            closeNewDocument();
        });
		
		// ComboBox Tipo de documento
		$('#tipo_documento_container').load("model/catalogos/catalogo_tipo_documento.php", function(){
		    $("#tipo_documento_combobox").select2({width: '100%'});
		});
		 
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
		
		$( "#fecha_recepcion" ).datepicker( "option", "dateFormat", "yy-mm-dd");
		$( "#fecha_emision" ).datepicker( "option", "dateFormat", "yy-mm-dd");
		$( "#fecha_recepcion2" ).datepicker( "option", "dateFormat", "yy-mm-dd");
		
		// EOF Campos de fecha 
	
		// Campo vigencia es de tipo spinner
		$('#vigencia').spinner();
		
		// BOF ComboBox remitente
			$('#remitente_combobox').select2({
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
						
					} 
			});
		// BOF ComboBox remitente
		
		// BOF ComboBox destinatario
			$('#destinatario_combobox').select2({
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
				}				
			});
		// BOF ComboBox destinatario
		
		// BOF ComboBox turnado
			$('#turnado_a_combobox').select2({
				placeholder: 'Elegir usuarios turnados / cc',
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
				}				
			});
			
			$("#turnado_a_combobox").select2("container").find("ul.select2-choices").sortable({
				containment: 'parent',
				start: function() { $("#turnado_a_combobox").select2("onSortStart"); },
				update: function() { $("#turnado_a_combobox").select2("onSortEnd"); }
			});
		// BOF ComboBox turnado
		
		
		// BOF ComboBox asignado
			$('#asignado_a_combobox').select2({
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
				}				
			});
		// BOF ComboBox asignado
		
		
		// BOF UPLOADER
		$("#uploader").pluploadQueue({
			// General settings
			runtimes : 'html5,flash',
			url : 'model/upload.php',
			max_file_size : '10mb',
			chunk_size : '1mb',
			unique_names : true,
	
			// Resize images on clientside if we can
			resize : {width : 320, height : 240, quality : 90},
	
			// Specify what files to browse for
			filters : [
				//{title : "Image files", extensions : "jpg,gif,png"},
				{title : "Archivos PDF", extensions : "pdf"}
			],
			
			 // Flash settings
	        flash_swf_url : 'controller/plugins/plupload/plupload.flash.swf'

		});
		
		// Client side form validation
		$('form').submit(function(e) {
			
			var uploader = $('#uploader').pluploadQueue();
	
			// Files in queue upload them first
			if (uploader.files.length > 0) {
				// When all files are uploaded submit form
				uploader.bind('StateChanged', function() {
					if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
						//$('form')[0].submit();
						console.log("Enviar formulario");
					}
				});
					
				uploader.start();
			} else {
				alert('You must queue at least one file.');
			}
	
			return false;
		});
		// EOF UPLOADER

	});
	