var userDataL1 = new Array();

$(document).ready(function(){
    
    // Cargar la vista de la lista de meses    
    $('#itemListL1').load("view/components/itemList.php?method=POST&id_usuario="+userData.id_usuario+"&estatus="+userData.estatus);
    
    // Cargar la vista del contenido del components
    $('#itemContentL1').load("view/components/itemContent.php?method=POST&id_usuario="+userData.id_usuario);
    
});