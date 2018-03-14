<?php
/**
 * Image Helper class file.
 *
 * Generate an image with a specific size.
 *
 *
 * @package       Cake.View.Helper
 * @since         CakePHP(tm) v 1.1
 */
//App::uses('AppHelper', 'View/Helper');
namespace App\View\Helper;

use Cake\View\Helper;
/**
 * Image Helper class for generate an image with a specific size.
 *
 * ImageHelper encloses 2 method needed while resizing images.
 *
 * @package       View.Helper
 */
class ImageHelper extends Helper{
    public $helpers = ['Html','Form'];
    
    /**
     * Generate an image with a specific size
     * @param  string   $image   Path of the image (from the webroot directory)
     * @param  int      $width
     * @param  int      $height
     * @param  array    $options Options (same that HtmlHelper::image)
     * @param  int      $quality
     * @return string   <img> tag
     */
    public function resize($image, $width, $height, $path, $options = [], $quality = 100){
        $options['width'] = $width;
        $options['height'] = $height;
        return $this->Html->image($path . $this->resizedUrl($image, $width, $height, $quality), $options);
    }
    /**
     * Create an image with a specific size
     * @param  string   $file   Path of the image (from the webroot directory)
     * @param  int      $width
     * @param  int      $height
     * @param  array    $options Options (same that HtmlHelper::image)
     * @param  int      $quality
     * @return string   image path
     */
    public function resizedUrl($file, $width, $height, $quality = 100){
        # We define the image dir include Theme support
        //$imageDir = (!isset($this->theme)) ? IMAGES : APP.'View'.DS.'Themed'.DS.$this->theme.DS.'webroot'.DS.'img'.DS;
        
        $imageDir = WWW_ROOT .'files' .DS. 'imagenes' .DS. 'filename'.DS;
        # We find the right file
        $pathinfo   = pathinfo(trim($file, '/'));
        $file       = $imageDir . trim($file, '/');
        $output     = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_' . $width . 'x' . $height . '.' . $pathinfo['extension'];
        if (!file_exists($imageDir . $output)) {
            # Setting defaults and meta
            $info                         = getimagesize($file);
            list($width_old, $height_old) = $info;
            # Create image ressource
            switch ( $info[2] ) {
                case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
                case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
                case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
                default: return false;
            }
            # We find the right ratio to resize the image before cropping
            $heightRatio = $height_old / $height;
            $widthRatio  = $width_old /  $width;
            $optimalRatio = $widthRatio;
            if ($heightRatio < $widthRatio) {
                $optimalRatio = $heightRatio;
            }
            $height_crop = ($height_old / $optimalRatio);
            $width_crop  = ($width_old  / $optimalRatio);
            # The two image ressources needed (image resized with the good aspect ratio, and the one with the exact good dimensions)
            $image_crop = imagecreatetruecolor( $width_crop, $height_crop );
            $image_resized = imagecreatetruecolor($width, $height);
            # This is the resizing/resampling/transparency-preserving magic
            if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
                $transparency = imagecolortransparent($image);
                if ($transparency >= 0) {
                    $transparent_color  = imagecolorsforindex($image, $trnprt_indx);
                    $transparency       = imagecolorallocate($image_crop, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                    imagefill($image_crop, 0, 0, $transparency);
                    imagecolortransparent($image_crop, $transparency);
                    imagefill($image_resized, 0, 0, $transparency);
                    imagecolortransparent($image_resized, $transparency);
                }elseif ($info[2] == IMAGETYPE_PNG) {
                    imagealphablending($image_crop, false);
                    imagealphablending($image_resized, false);
                    $color = imagecolorallocatealpha($image_crop, 0, 0, 0, 127);
                    imagefill($image_crop, 0, 0, $color);
                    imagesavealpha($image_crop, true);
                    imagefill($image_resized, 0, 0, $color);
                    imagesavealpha($image_resized, true);
                }
            }
            imagecopyresampled($image_crop, $image, 0, 0, 0, 0, $width_crop, $height_crop, $width_old, $height_old);
            imagecopyresampled($image_resized, $image_crop, 0, 0, ($width_crop - $width) / 2, ($height_crop - $height) / 2, $width, $height, $width, $height);
            # Writing image according to type to the output destination and image quality
            switch ( $info[2] ) {
              case IMAGETYPE_GIF:   imagegif($image_resized, $imageDir . $output, $quality);    break;
              case IMAGETYPE_JPEG:  imagejpeg($image_resized, $imageDir . $output, $quality);   break;
              case IMAGETYPE_PNG:   imagepng($image_resized, $imageDir . $output, 9);    break;
              default: return false;
            }
        }
        return $output;
    }
    
    /**
     * Create an temporal image with a specific size
     * @param  string   $file   Path of the image (from the webroot directory)
     * @param  int      $width
     * @param  int      $height
     * @param  array    $options Options (same that HtmlHelper::image)
     * @param  int      $quality
     * @return string   image path
     */
    public function resizedImage($file, $width, $height, $align = 'left', $quality = 100){
        // thumb width
        /*$square = 150;
        $large = 200;
        $small = 100;*/

        $imgSrc = WWW_ROOT .'files' .DS. 'imagenes' .DS. 'filename'.DS. $file;
        
        ////////////////////////////////////////////////////////////////////////////////// square
        if(true){

            // thumb size
            $thumb_width = $width;
            $thumb_height = $height;

            // align
            //$align = $align_image;

            // image source
            //$imgSrc = $file;
            $imgExt = substr($imgSrc,-3);

            // image extension
            if($imgExt == "jpg"){ $myImage = imagecreatefromjpeg($imgSrc); }
            if($imgExt == "gif"){ $myImage = imagecreatefromgif($imgSrc); }
            if($imgExt == "png"){ $myImage = imagecreatefrompng($imgSrc); }

            // getting the image dimensions  
            list($width_orig, $height_orig) = getimagesize($imgSrc);   

            // ratio
            $ratio_orig = $width_orig/$height_orig;

            // landscape or portrait?
            if ($thumb_width/$thumb_height > $ratio_orig) {
               $new_height = $thumb_width/$ratio_orig;
               $new_width = $thumb_width;
            } else {
               $new_width = $thumb_height*$ratio_orig;
               $new_height = $thumb_height;
            }

            // middle
            $x_mid = $new_width/2;
            $y_mid = $new_height/2;

            // create new image
            $process = imagecreatetruecolor(round($new_width), round($new_height)); 
            imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
            $thumb = imagecreatetruecolor($thumb_width, $thumb_height); 

            // alignment
            if($align == ""){
            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumb_width/2)), ($y_mid-($thumb_height/2)), $thumb_width, $thumb_height, $thumb_width, $thumb_height);
            }
            if($align == "top"){
            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumb_width/2)), 0, $thumb_width, $thumb_height, $thumb_width, $thumb_height);
            }
            if($align == "bottom"){
            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumb_width/2)), ($new_height-$thumb_height), $thumb_width, $thumb_height, $thumb_width, $thumb_height);
            }
            if($align == "left"){
            imagecopyresampled($thumb, $process, 0, 0, 0, ($y_mid-($thumb_height/2)), $thumb_width, $thumb_height, $thumb_width, $thumb_height);
            }
            if($align == "right"){
            imagecopyresampled($thumb, $process, 0, 0, ($new_width-$thumb_width), ($y_mid-($thumb_height/2)), $thumb_width, $thumb_height, $thumb_width, $thumb_height);
            }

            imagedestroy($process);
            imagedestroy($myImage); 

            if($imgExt == "jpg"){ imagejpeg($thumb, null, 100); }
            if($imgExt == "gif"){ imagegif($thumb); }
            if($imgExt == "png"){ imagepng($thumb, null, 9); }

            //header("Content-type: image/jpeg");  
            $thumb;
            }

        ////////////////////////////////////////////////////////////////////////////////// normal
        /*if( isset($_GET[img]) && ( $_GET[type] == "large" || $_GET[type] == "small" )  ){

        if( $_GET[type] == "large" ){ $thumb_width = $large; }
        if( $_GET[type] == "small" ){ $thumb_width = $small; }

        // image source
        $imgSrc = $_GET[img];
        $imgExt = substr($imgSrc,-3);

        // image extension
        if($imgExt == "jpg"){ $myImage = imagecreatefromjpeg($imgSrc); }
        if($imgExt == "gif"){ $myImage = imagecreatefromgif($imgSrc); }
        if($imgExt == "png"){ $myImage = imagecreatefrompng($imgSrc); }

        //getting the image dimensions  
        list($width_orig, $height_orig) = getimagesize($imgSrc);   

        // ratio
        $ratio_orig = $width_orig/$height_orig;
        $thumb_height = $thumb_width/$ratio_orig;

        // new dimensions
        $new_width = $thumb_width;
        $new_height = $thumb_height;

        // middle
        $x_mid = $new_width/2;
        $y_mid = $new_height/2;

        // create new image
        $process = imagecreatetruecolor(round($new_width), round($new_height));

        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height); 
        imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumb_width/2)), ($y_mid-($thumb_height/2)), $thumb_width, $thumb_height, $thumb_width, $thumb_height);

        if($imgExt == "jpg"){ imagejpeg($thumb, null, 100); }
        if($imgExt == "gif"){ imagegif($thumb); }
        if($imgExt == "png"){ imagepng($thumb, null, 9); }

        }*/
    }
}

