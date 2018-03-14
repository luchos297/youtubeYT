<div class="widget">
    <h3>#Redes</h3>
    <div class="socialize-widget">
        <p>Contactanos a trav&eacute;s de nuestras redes y compart&iacute; con tus amigos </p>
        <div class="ot-social-block">
            <a href="http://www.facebook.com/885480398208688" class="soc-link soc-facebook" target="_blank">
                <strong class="count"><span></span><small>me gusta</small></strong>
                <span>facebook</span>
            </a>
            <a href="https://twitter.com/vista_medios" class="soc-link soc-twitter" target="_blank">
                <strong class="count"><span></span><small>seguidores</small></strong>
                <span>twitter</span>
            </a>
            <a href="https://plus.google.com/u/0/107350038594612955632" class="soc-link soc-google" target="_blank">
                <strong class="count"><span></span><small>seguidores</small></strong>
                <span>google+</span>
            </a>
        </div>
    </div>
</div>

<!-- BEGIN .home-block -->
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

<?= $this->Html->scriptStart(['block' => true]) ?>    
    jQuery(document).ready(function() { 
        var banner = <?php echo $banner; ?>;
        if(banner.mobile == true && banner.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {                    
            $('#banner').html('');
            $('#banner').html('<?php if($banner->href != ""): ?><a href="<?= $banner->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');                    
        }
    });

    jQuery.get( "<?= $this->Url->build(['controller' =>'redes','action' =>'social_facebook']); ?>", function( data ) {
        $( ".soc-facebook .count span" ).html( JSON.parse(data).likes );
    });

    jQuery.get( "<?= $this->Url->build(['controller' =>'redes','action' =>'social_twitter']); ?>", function( data ) {
        $( ".soc-twitter .count span" ).html( data );
    });

    jQuery.get( "<?= $this->Url->build(['controller' =>'redes','action' =>'social_g_plus']); ?>", function( data ) {
        $( ".soc-google .count span" ).html( data );
    });
<?= $this->Html->scriptEnd() ?>