var userDataL1 = new Array();

$(document).ready(function(){
    
    // PRAGMA BORRAR!!! Sólo como prueba el id_usuario de Bibiana
    userData.id_usuario = 2; 
    userData.id_privilegios = 1;
    
    // Cargar la vista de la lista de meses
    userData.estatus = '1,2';
    $('#itemListL1').load("view/components/itemList.php?method=GET&id_usuario="+userData.id_usuario+"&estatus="+userData.estatus);
    
    // Cargar la vista del contenido del components
    $('#itemContentL1').load("view/components/itemContent.php?method=GET&id_usuario="+userData.id_usuario);
    
});