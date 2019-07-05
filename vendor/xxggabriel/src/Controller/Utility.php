<?php 

namespace App\Controller;

class Utility 
{

    public static function decreaseImageQuality($filename,$quality = 85)
    {
        if (!is_file($filename)) {
            throw new \Exception("Diretorio não existe: ".$filename, 500);
            
        }

        $image = new \Imagick();
        $image->readImage($filename);
        $image->setImageCompressionQuality($quality);
        $resutlImage = $image->getImageBlob();
        
        if(!$resutlImage){
            throw new \Exception("Erro ao salvar a imagem.", 500);
            
        }
        return 'data:image/jpg;base64,'.base64_encode($resutl);
    }   


}