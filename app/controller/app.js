var userData;

 $(document).ready(function() {
     $.throbber.show({overlay: true});
     userData = JSON.parse(localStorage['userData']);
	 userData.level = 1;
      
     $('#encabezado').load('view/encabezado.php', function(){
          var nombre_completo = (userData.nombre_completo == null) ? userData.usuario : userData.nombre_completo;
           $('.welcome-message').html("Bienvenid@<br />"+nombre_completo+"<br /><a href='../login/view/logout.php'>Salir</a>");
     });	
	  
	  $('#content1').load("view/level1.php", function(){          
          $.throbber.hide();
      });
 });