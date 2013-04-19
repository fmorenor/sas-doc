$(document).ready(function(){
    
    //$.post("model/level1/level1.php", function(){
        // Cargar la vista de la lista de meses
        $('#itemListL1').load("view/level1/itemList.php", function(){          
            //$.throbber.hide();
        });
        
        // Cargar la vista del contenido del level1
        $('#itemContentL1').load("view/level1/itemContent.php", function(){          
            //$.throbber.hide();
              
            //Cargar los valores con los datos provenientes del POST
            $('#resume .error').text("237");
            $('#resume .success').text("1597");
            $('#resume .info').text("123");
              
            //Cargar el gráfico con los datos provenientes del POST
            $('#itemContentL1 #chart').load("view/level1/chart.php", function(){
                
            });
              
        });
    //});
    
});