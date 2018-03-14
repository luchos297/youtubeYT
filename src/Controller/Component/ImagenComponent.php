<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class ImagenComponent extends Component {
    
    function imageResize($fileName, $newwidth, $newheight) {
        $resp = false;
        $newFilename = "";
        try{
            $newFilename = substr_replace($fileName, "-$newheight.", strrpos($fileName, "."), 1);
            if (is_file($newFilename)) {
                unlink($newFilename);
            }

            if (is_file($fileName) && !is_file($newFilename)) {
                list($widthOrig, $heightOrig) = getimagesize($fileName);

                $newheight = floor(($newwidth*$heightOrig)/$widthOrig)-1;

                $ratioAspect = ($widthOrig * 1.0) / $heightOrig;
                $ratioAspectNew = ($newwidth * 1.0) / $newheight;

                $centerX = 0;
                $centerY = 0;
                $width = $widthOrig;
                $height = $heightOrig;

                if ($ratioAspect > 1) {
                    if ($ratioAspectNew > 1) {
                        $height = $widthOrig / $ratioAspectNew;
                        $centerY = ($heightOrig - $height) / 2;
                    } else {
                        $width = $heightOrig * $ratioAspectNew;
                        $centerX = ($widthOrig - $width) / 2;
                    }
                } else {
                    if ($ratioAspectNew >= 1) {
                        $height = $widthOrig / $ratioAspectNew;
                        $centerY = ($heightOrig - $height) / 2;
                    } else {
                        $width = $heightOrig * $ratioAspectNew;
                        $centerX = ($widthOrig - $width) / 2;
                    }
                }
                $thumb = imagecreatetruecolor($width, $height);
                $thumbFinal = imagecreatetruecolor($newwidth, $newheight);

                $info = getimagesize($fileName);
                $mime = $info['mime'];
                $type = explode("/", $mime);
                $imagetype = $type[1];

                if ($imagetype == "jpeg") {
                    $source = imagecreatefromjpeg($fileName);
                }
                if ($imagetype == "png") {
                    $source = imagecreatefrompng($fileName);
                }
                if ($imagetype == "gif") {
                    $source = imagecreatefromgif($fileName);
                }

                // Recorta la imagen
                imagecopyresized($thumb, $source, 0, 0, $centerX, $centerY, $width, $height, $width, $height);
                // Ajusta el tamaño
                //imagecopyresampled($thumbFinal, $thumb, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                if ($imagetype == "jpeg") {
                    //imagejpeg($thumbFinal, $newFilename, 90);
                    $resp = imagejpeg($thumb, $newFilename, 90);
                }
                if ($imagetype == "png") {
                    //imagepng($thumbFinal, $newFilename);
                    $resp = imagepng($thumb, $newFilename);
                }
                if ($imagetype == "gif") {
                    //imagegif($thumbFinal, $newFilename);
                    $resp = imagegif($thumb, $newFilename);
                }

                imagedestroy($thumb);
                imagedestroy($thumbFinal);
            }
            //aseguro de que le archivo fue creado
            if(file_exists($newFilename)){
                $resp = true;
            }
            else{
                $resp = false;
            }
        }
        catch (Exception $e){
            $resp = false;
        }
        
        return $resp;
    }

    function imageResizeOverwrite($fileName, $newwidth, $newheight) {

	$newFilename = substr_replace($fileName, "-$newheight.", strrpos($fileName, "."), 1);
    if (is_file($newFilename)) {
        unlink($newFilename);
    }

	if (is_file($fileName) && !is_file($newFilename)) {
	    list($widthOrig, $heightOrig) = getimagesize($fileName);

        
	    $ratioAspect = ($widthOrig * 1.0) / $heightOrig;
	    $ratioAspectNew = ($newwidth * 1.0) / $newheight;

	    $centerX = 0;
	    $centerY = 0;
	    $width = $widthOrig;
	    $height = $heightOrig;

	    if ($ratioAspect >= 1) {
		    if ($ratioAspectNew >= 1) {
			    $width = $heightOrig * $ratioAspectNew;
			    $centerX = ($widthOrig - $width) / 2;
		    } else {
			    $height = $widthOrig / $ratioAspectNew;
			    $centerY = ($heightOrig - $height) / 2;
		    }
	    } else {
		    if ($ratioAspectNew >= 1) {
			    $width = $heightOrig * $ratioAspectNew;
			    $centerX = ($widthOrig - $width) / 2;
		    } else {
			    $height = $widthOrig / $ratioAspectNew;
			    $centerY = ($heightOrig - $height) / 2;
		    }
	    }
	    $thumb = imagecreatetruecolor($width, $height);
	    $thumbFinal = imagecreatetruecolor($newwidth, $newheight);
        
        $info = getimagesize($fileName);
        $mime = $info['mime'];
        $type = explode("/", $mime);
        $imagetype = $type[1];

        if ($imagetype == "jpeg") {
	        $source = imagecreatefromjpeg($fileName);
        }
        if ($imagetype == "png") {
	        $source = imagecreatefrompng($fileName);
        }
        if ($imagetype == "gif") {
	        $source = imagecreatefromgif($fileName);
        }

	    // Recorta la imagen
	    imagecopyresized($thumb, $source, 0, 0, $centerX, $centerY, $width, $height, $width, $height);
	    // Ajusta el tamaño
	    imagecopyresized($thumbFinal, $thumb, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        if ($imagetype == "jpeg") {
	        imagejpeg($thumbFinal, $newFilename, 90);
        }
        if ($imagetype == "png") {
	        imagepng($thumbFinal, $newFilename);
        }
        if ($imagetype == "gif") {
	        imagegif($thumbFinal, $newFilename);
        }

	    imagedestroy($thumb);
	    imagedestroy($thumbFinal);

        rename($newFilename, $fileName);
	}
    }

    function imageResizeComplete($fileName, $x1, $y1, $x2, $y2) {


	if (is_file($fileName)) {

	    list($width, $height) = getimagesize($fileName);

	    $thumb = imagecreatetruecolor($x2-$x1, $y2-$y1);

        $info = getimagesize($fileName);
        $mime = $info['mime'];
        $type = explode("/", $mime);
        $imagetype = $type[1];

        if ($imagetype == "jpeg") {
	        $source = imagecreatefromjpeg($fileName);
        }
        if ($imagetype == "png") {
	        $source = imagecreatefrompng($fileName);
        }
        if ($imagetype == "gif") {
	        $source = imagecreatefromgif($fileName);
        }


	    imagecopyresized($thumb, $source, 0, 0, $x1, $y1, $x2-$x1, $y2-$y1, $x2-$x1, $y2-$y1);

        if ($imagetype == "jpeg") {
	        imagejpeg($thumb, $fileName.'tmp', 90);
        }
        if ($imagetype == "png") {
	        imagejpeg($thumb, $fileName.'tmp');
        }
        if ($imagetype == "gif") {
	        imagejpeg($thumb, $fileName.'tmp');
        }


	    imagedestroy($thumb);
        rename($fileName.'tmp', $fileName);
	}
    }


    function deleteFile($fileName) {
        if (!$fileName || !is_file($fileName)) {
          return true;
        }
        if(unlink($fileName)) {
          return true;
        }

        return false;
    }

    function getLastUploadPath(){
        $charid = md5(rand() . uniqid() . time());
        $hyphen = chr(45);// "-"
        $uuid = //chr(123)// "{"
            substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
            //.chr(125);// "}"
        return $uuid;
    }
}
?>
