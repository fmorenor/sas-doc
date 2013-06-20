<?php
    $file_name = substr('../../../documents/'.$_POST['file'], 0, -4);
    $system_path = str_replace("app\model\components", "", dirname(__FILE__)).'documents/';		
	if(file_exists($system_path.$_POST['file'])){
         // ImageMagick-6.8.1-Q16 en el server
        exec('"C:\Program Files\ImageMagick-6.7.6-Q16\convert.exe" -thumbnail 100x120! '.$file_name.'.pdf[0] '.$file_name.'.jpg');
    } else {
        echo "No existe: ".$system_path.$_POST['file'];
    }
?>