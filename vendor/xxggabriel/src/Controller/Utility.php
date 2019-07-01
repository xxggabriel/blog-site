<?php 

namespace App\Controller;

class Utility 
{

    public static function decreaseImageQuality($filename,$quality = 85)
    {
        if (!is_file($filename)) {
            throw new \Exception("Diretorio não existe: ".$filename, 1);
            
        }

        $image = new \Imagick();
        $image->readImage($filename);
        $image->setImageCompressionQuality($quality);
        $resutl = $image->getImageBlob();
        
        if(!$resutl){
            throw new \Exception("Erro ao salvar a imagem.", 1);
            
        }
        return 'data:image/jpg;base64,'.base64_encode($resutl);
    }   


}