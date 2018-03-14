<!-- BEGIN .wrapper -->
<div class="wrapper">
    <!-- <div class="main-content has-sidebar"> -->
    <div class="main-content has-sidebar">
        <!-- BEGIN .left-content -->
        <div class="left-content">

            <!-- BEGIN .home-block -->
            <div class="home-block">
                <div class="banner" id="banner">
                    <?php $banner = array_pop($banners_728x90);
                    $width = $banner->banner_tipo->ancho;
                    $height = $banner->banner_tipo->alto; ?>

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
                                <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner]) ?>
                            </a>
                            <!-- END .home-block -->
                        <?php else: ?>
                            <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner]) ?>
                            <!-- END .home-block -->
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="main-title" style="border-left: 4px solid #276197;">
                <h2 class="widget"><?= reset($radios)->tipo ?></h2>
            </div>
            <!-- star test -->
            <div class="widget radios">
                <div class="article-list-block">
                <?php foreach($radios as $radio): ?>
                    <div class="item">
                        <div class="item-header">
                            <a href="#<?= $radio->codigo ?>">
                                <i class="seccion-nota"><?= $radio->nombre ?></i>
                            </a>
                            <a href='<?= Cake\Core\Configure::read('reproductor') ?>'
                                onclick="NewWindow(this.href+'?url='+'<?= $radio->url ?>'+'&type='+'<?= $radio->type ?>'+'&serverurl='+'<?= $radio->serverurl ?>'+'&imagen='+'<?= $radio->filename ?>'+'&h='+'<?= $radio->height ?>'+'&w='+'<?= $radio->weight ?>',
                                           '<?= $radio->nombre ?>',
                                           '<?= $radio->weight ?>',
                                           '<?= $radio->height ?>',
                                           '<?= $radio->type ?>',
                                           '<?= $radio->url ?>',
                                           'yes');return false" class="image-hover">
                                <figure>
                                    <img src="<?= Cake\Core\Configure::read('dominio') . 'img/' . $radio->filename ?>">
                                </figure>
                            </a>
                        </div>
                        <div class="item-content">
                            <div class="content-category"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <!-- end test -->
            <div class="home-block">
            </div>
            <div class="home-block">
                <div class="banner">
                    <?= $this->element('Front/adsense/primer-728x90') ?>
                </div>
                <!-- END .home-block -->
            </div>
        <!-- END .left-content -->
        </div>
        <div id="mediaplayer"></div>
        <!-- BEGIN .small-sidebar -->
        <!-- BEGIN #sidebar -->
        <aside id="sidebar">

            <?= $this->element('Front/temas-mas-destacados'); ?>

            <!-- BEGIN .home-block -->
            <div class="home-block">
                <?= $this->element('Front/adsense/primer-300x250') ?>
            </div>

            <?= $this->element('Front/noticias-mas-leidas'); ?>

            <!-- BEGIN .home-block -->
            <div class="home-block">
                <?= $this->element('Front/adsense/primer-300x250') ?>
            </div>

            <?= $this->element('Front/redes-fixed'); ?> 

        <!-- END #sidebar -->
        </aside>
    </div>
<!-- END .wrapper -->
</div>
<?= $this->Html->scriptStart(['block' => true]) ?>
    jQuery(document).ready(function() {

        var banner = <?php echo $banner; ?>;
        if(banner.mobile == true && banner.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {                    
            $('#banner').html('');
            $('#banner').html('<?php if($banner->href != ""): ?><a href="<?= $banner->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');        
        }

        jQuery('.provinciales').click(function(){
            if(!jQuery(this).hasClass( "active" )){
                jQuery(this).toggleClass( "active");
                jQuery('.nacionales').toggleClass( "active");
                jQuery('.nacionales-block').toggleClass( "active");
                jQuery('.provinciales-block').toggleClass( "active");
            }
        });

        jQuery('.nacionales').click(function(){
            if(!jQuery(this).hasClass( "active" )){
                jQuery(this).toggleClass( "active");
                jQuery('.provinciales').toggleClass( "active");
                jQuery('.provinciales-block').toggleClass( "active");
                jQuery('.nacionales-block').toggleClass( "active");
            }
        });
    });

    var win = null;
    function NewWindow(mypage,myname,w,h,type,url,scroll){
        LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
        TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
        settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable=0';

        if(type=="page" || type=="video"){
            win = window.open(url,myname,settings);
        }
        else{
            win = window.open(mypage,myname,settings);
        }
    }
<?= $this->Html->scriptEnd() ?>