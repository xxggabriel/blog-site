<?php 
$name = $_FILES["image"]["name"];
$filename = $_FILES["image"]["tmp_name"];
$type = $_FILES["image"]["type"];

        $image = new Imagick();
        $image->readImage($filename);
        $image->setImageCompressionQuality(85);
        switch ($type) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $typeImage = "jpeg";
                break;
            case 'image/png':
                $typeImage = "png";
                break;
            case 'image/gif':
                $typeImage = "gif";
                break;
            default:
                throw new \Exception("Formato da imagem, não tolerado.", 1);
                break;
            }

