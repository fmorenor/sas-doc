$(document).ready(function() {
	
	setAutologin();
	
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
	
	$("#autologin").on('click', function(){
		getAutologin();
	});
});

function loginErrorDialog(){
	$( "#dialog-modal" ).dialog("open");
   
	// Eliminar los datos guardados
	$("#autologin").attr("checked", false);
	$.removeCookie("ucnf");
	$.removeCookie("pcnf");
	$.removeCookie("ulencnf");
	$.removeCookie("plencnf");
	$.removeCookie("autocnf");
}

function getAutologin(){
	var c = $("#autologin"); //INPUT CHECKBOX
	
	//IF CHECKBOX IS SET, COOKIE WILL BE SET
	if(c.is(":checked")){
		var u = runEncrypt($("#username").val()); //VALUE OF USERNAME
		var ulen = $("#username").val().length;
		var p = runEncrypt($("#password").val()); //VALUE OF PASSWORD
		var plen = $("#password").val().length;
		$.cookie("ucnf", u, { expires: 365 }); //SETS IN DAYS (1 YEARS)
		$.cookie("pcnf", p, { expires: 365 }); //SETS IN DAYS (1 YEARS)
		$.cookie("ulencnf", ulen, { expires: 365 });
		$.cookie("plencnf", plen, { expires: 365 });
		$.cookie("autocnf", true, { expires: 365 }); 
	} else {
		$.removeCookie("ucnf");
		$.removeCookie("pcnf");
		$.removeCookie("ulencnf");
		$.removeCookie("plencnf");
		$.removeCookie("autocnf");
	}
}
//NEXT PAGE LOAD, THE USERNAME & PASSWORD WILL BE SHOWN IN THEIR FIELDS
function setAutologin(){
	if ($.cookie("autocnf") != null) {
		if ($.cookie("ucnf").length >= 32 && $.cookie("autocnf") == 'true') {
			
			$("#autologin").attr("checked", $.cookie("autocnf"));
			
			var e = runDecrypt($.cookie("ucnf"), $.cookie("ulencnf")); //"USERNAME" COOKIE
			var p = runDecrypt($.cookie("pcnf"), $.cookie("plencnf")); //"PASSWORD" COOKIE
			
			$("#username").val(e); //FILLS WITH "USERNAME" COOKIE
			$("#password").val(p); //FILLS WITH "PASSWORD" COOKIE		
		}
	}
}
	