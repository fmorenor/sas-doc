$(document).ready(function() {
	
	// Dialogo de error
	$('#dialog:ui-dialog').dialog( "destroy" );
        
    $('#dialog-modal').dialog({
        autoOpen: false,
        height: 170,
        modal: true,
		buttons: {
				'Cerrar': function() {
					$( this ).dialog( "close" );
				}
			}
    });
	
	$("#loginForm").submit(function(event) {
		/* stop form from submitting normally */
		event.preventDefault(); 
			
		/* get some values from elements on the page: */
		var $form = $( this ),
			usernameInput = $form.find( 'input[name="username"]' ).val(),
			//passwordInput =  $.md5($form.find( 'input[name="password"]' ).val()),			
			passwordInput =  $form.find( 'input[name="password"]' ).val(),
			url = $form.attr( 'action' );
					
		$.post(url,{ username: usernameInput, password: passwordInput }, function(data) {			
			localStorage['userData'] = JSON.stringify(data);				
			window.location = 'app/';
		}).fail(function() {
			loginErrorDialog();
		});       
	});		
});

function loginErrorDialog(){
   $( "#dialog-modal" ).dialog("open");
}
	