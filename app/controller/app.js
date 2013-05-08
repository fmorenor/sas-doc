var userData;

 $(document).ready(function() {
	$.throbber.show({overlay: true});
	userData = JSON.parse(localStorage['userData']);
	userData.level = 1;
	userData.searchType = 'general';
	 
	$('#encabezado').load('view/encabezado.php', function(){
		var nombre_completo = (userData.nombre_completo == null) ? userData.usuario : userData.nombre_completo;
		$('.welcome-message').html("Bienvenid@<br />"+nombre_completo+"<br /><a href='../login/view/logout.php'>Salir</a>");
	});
	
	// BOF Nav Container
	$('#nav-container').load('view/nav-container.php', function(){
		$('#myNav li a').bind("click",function(event){
			event.preventDefault();
			userData.estatus = $(this).attr("rel");
			
			//switch (userData.searchType) {
			//	case 'general': search(); break;
			//	case 'recepcion':
			//	case 'emision': searchDateRange(); break;			
			//}
			$("#itemListL1").unhighlight();
			$('#itemListL1').load("view/level1/itemList.php");
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
	 
	// Cargar los contenidos del level1 
	$('#content1').load("view/level1.php", function(){          
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
	$('#itemListL1').load("view/level1/itemList.php");
	$('#itemContentL1').load("view/level1/itemContent.php");
}

function searchDateRange() {
	if ($('#searchDateFrom').val() && $('#searchDateTo').val()) {
		$('#searchClear2').css('display', 'inline');
		//$('#searchGroup2').removeClass('control-group');
		//$('#searchGroup2').removeClass('error');
		userData.searchInput = $('#searchDateFrom').val()+","+$('#searchDateTo').val();
	} else {
		$('#searchDateFrom').val(null);
		$('#searchDateTo').val(null);
		//$('#searchGroup2').addClass('control-group');
		//$('#searchGroup2').addClass('error');
		userData.searchInput = null;
	}
	// Limpiar la función de resaltar palabras buscadas en el documento
	$("#itemListL1").unhighlight();
	$('#itemListL1').load("view/level1/itemList.php");
	$('#itemContentL1').load("view/level1/itemContent.php");
}

function setSearchGroup(type) {
	userData.searchType = type;
	switch (type) {
		case 'general': $('#searchGroup1').css('display', 'block');
						$('#searchGroup2').css('display', 'none');
						break;
		case 'recepcion': 	$('#searchGroup1').css('display', 'none');
							$('#searchGroup2').css('display', 'block');
							$('#searchGroup2 .label-search-style').html("B&uacute;squeda por fecha de recepci&oacute;n");
							break;
		case 'emision': $('#searchGroup1').css('display', 'none');
						$('#searchGroup2').css('display', 'block');
						$('#searchGroup2 .label-search-style').html("B&uacute;squeda por fecha de emisi&oacute;n");
						break;
	}
}
 
