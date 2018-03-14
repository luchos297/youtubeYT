<?php
//$banner = array_pop($banners_728x90);
if(!is_null($banner)){
    echo $this->Html->image(Cake\Core\Configure::read('path_imagen_banner') . $banner->file_url.'/'.$banner->filename); 
}
?>