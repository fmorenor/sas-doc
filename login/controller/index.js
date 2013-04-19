$(document).ready(function() {
	
	// Check for Retina display screen size
	if (window.devicePixelRatio >= 2 && screen.width == 320) {
		$('meta[name=viewport]').attr('content','width=device-width, user-scalable=no,initial-scale=.5, maximum-scale=1');
	}
	
	$('#encabezado').load('login/view/encabezado.php');
	$('#login').load('login/view/login.php');		
	
});