<aside id="sidebar">      

    <!-- BEGIN BANNER 5 -->
    <div class="home-block">
        <div class="banner" id="banner">            
            <?php $banner = array_pop($banners_300x250);
            $width = $banner->banner_tipo->ancho;
            $height = $banner->banner_tipo->alto;?> 

            <?php if(strpos($banner->filename, "swf") !== false): ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>                    
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner->href != ""): ?>                    
                    <a href="<?= $banner->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>                    
                    <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-300x250') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN BANNER 4 -->
    <div class="home-block">
        <div class="banner" id="banner">
            <?php $banner = array_pop($banners_300x250);
            $width = $banner->banner_tipo->ancho;
            $height = $banner->banner_tipo->alto;?> 

            <?php if(strpos($banner->filename, "swf") !== false): ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>                    
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner->href != ""): ?>                    
                    <a href="<?= $banner->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>                    
                    <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-300x250') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN BANNER 3 -->
    <div class="home-block">
        <div class="banner" id="banner">
            <?php $banner = array_pop($banners_300x250);
            $width = $banner->banner_tipo->ancho;
            $height = $banner->banner_tipo->alto;?> 

            <?php if(strpos($banner->filename, "swf") !== false): ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>                    
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner->href != ""): ?>                    
                    <a href="<?= $banner->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>                    
                    <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-300x250') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN BANNER 2 -->
    <div class="home-block">
        <div class="banner" id="banner">            
            <?php $banner = array_pop($banners_300x250);
            $width = $banner->banner_tipo->ancho;
            $height = $banner->banner_tipo->alto;?> 

            <?php if(strpos($banner->filename, "swf") !== false): ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>                    
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner->href != ""): ?>                    
                    <a href="<?= $banner->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>                    
                    <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-300x250') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN BANNER 1 -->
    <div class="home-block">
        <div class="banner" id="banner">            
            <?php $banner = array_pop($banners_300x250);
            $width = $banner->banner_tipo->ancho;
            $height = $banner->banner_tipo->alto;?> 

            <?php if(strpos($banner->filename, "swf") !== false): ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>                    
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner->href != ""): ?>                    
                    <a href="<?= $banner->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>                    
                    <?= $this->element('Front/banners-dinamicos/banner-300x250', ['banner' => $banner]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<!-- END #sidebar -->
</aside>