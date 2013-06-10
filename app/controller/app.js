var userData;

 $(document).ready(function() {
    
    // Force cache Javascript files
    $.ajaxSetup({
        cache: true
    });	
    
	$.throbber.show({overlay: true});
	userData = JSON.parse(localStorage['userData']);
	userData.level = 1;
	userData.searchType = 'general';
	 
    // BOF Encabezado 
	$('#encabezado').load('view/encabezado.php', function(){
		var nombre_completo = (userData.nombre_completo == null) ? userData.usuario : userData.nombre_completo;
		$('.welcome-message').html("Bienvenid@<br />"+nombre_completo+"<br /><a href='../login/view/logout.php?rnd="+Math.random()+"'>Salir</a>");
		
		$('.btn-new-document').click(function(){
			// Si existe el div itemDetail se procede a ocultarlo y a destruirlo. Despues se crea la ventana "newDocument"	 
			if ($('#itemDetail').length > 0) {
				
				$('#itemDetailTools').fadeOut(function(){
					setSearchGroup(userData.searchType);	
				});	
				
				$('#itemDetail').slideUp(function(){
						$('#itemDetail').remove();
						newDocument();
				});
				
			} else { //Si no existe se crea y luego se muestra	
				newDocument();		
			}
		});
	});
    // EOF Encabezado
	
	// BOF Nav Container
	$('#nav-container').load('view/nav-container.php', function(){
		$('#myNav li a').bind("click",function(event){
			event.preventDefault();
			userData.estatus = $(this).attr("rel");
			
			$("#itemListL1").unhighlight();
			$('#itemListL1').load("view/components/itemList.php");
			
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
		
	});
	// EOF Nav Container
	 
	// Cargar los contenidos del components 
	$('#content1').load("view/main-container.php", function(){          
	   $.throbber.hide();	   
	});
 });

// Funciones de búsqueda 
function search() {
	if ($('#searchInput').val()) {
		$('#searchClear').css('display', 'inline');
	}	
	// Limpiar la función de resaltar palabras buscadas en el documento
	$("#itemListL1").unhighlight();
	userData.searchInput = $('#searchInput').val();
	$('#itemListL1').load("view/components/itemList.php");
	$('#itemContentL1').load("view/components/itemContent.php");
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
	$('#itemListL1').load("view/components/itemList.php");
	$('#itemContentL1').load("view/components/itemContent.php");
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
	$('#itemDetail').height($(window).height() - 150); // 145px por el top y el padding en el CSS
	
	$('#itemDetail').slideDown({
		duration: 750,
		specialEasing: {
			width: 'linear',
			height: 'easeOutBounce'
		},
		complete: function(){ // PRAGMA, puede ser "complete" o "start", depende del efecto deseado...
            userData.selectedDocumentId = id;
            $('#itemDetail').load("view/components/itemDetail.php?itemDetailHeight="+$('#itemDetail').height());
		}
	});
}

function newDocument() {
	// Crear ventana newDocument si no existe
	if ($('#newDocument').length == 0) {		
		toggleModal();
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
			   $('#newDocument').load("view/components/newDocument.php");
			}
		});	
	}
}

function closeNewDocument() {
	// Ocultar herramientas del item
	//$('#itemDetailTools').fadeOut(function(){
	//	setSearchGroup(userData.searchType);	
	//});
	
	
	$('#newDocument').slideUp(function(){
			toggleModal();
			$('#newDocument').remove();			
	});
}

function toggleModal() {
	if ($('.modal-back').css('display') == 'none') {
		$('.modal-back').fadeIn();
	} else {
		$('.modal-back').fadeOut();
	}
}

 
