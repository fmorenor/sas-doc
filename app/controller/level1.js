var userDataL1 = new Array();

$(document).ready(function(){
    
    userData.id_usuario = 2; // PRAGMA BORRAR!!! Sólo como prueba usi el id_usuario de Bibinana
    
    // Cargar la vista de la lista de meses
    userData.estatus = '1,2';
    $('#itemListL1').load("view/level1/itemList.php", {id_usuario: userData.id_usuario, estatus: userData.estatus});
    
    // Cargar la vista del contenido del level1
    $('#itemContentL1').load("view/level1/itemContent.php", {id_usuario: userData.id_usuario});   
    
});