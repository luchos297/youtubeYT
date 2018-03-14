<!DOCTYPE HTML>
<!-- BEGIN html -->
<html lang = "en">
    <!-- BEGIN head -->
    <head>
        <title><?= $noticia->titulo ?></title>
        <!-- Meta Tags -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="es-AR">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="<?= $noticia->descripcion ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="viewport" content="initial-scale=1, minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no">
        <meta name="city" content="Mendoza">
        <meta name="country" content="Argentina">
        <meta name="geo.region" content="AR-M">
        <meta name="geo.placename" content="Mendoza">
        <meta name="distribution" content="global">
        <meta name="author" content="Vista Medios">
        <meta name="robots" content="index, follow">
        <meta name="Googlebot" content="all">
        <meta name="rating" content="general">
        <meta name="keywords" content="<?= $noticia->titulo ?>">
        <meta name="twitter:card" value="summary">
        <meta name="twitter:site" value="@<?= Cake\Core\Configure::read('usuario_twitter') ?>">
        <meta name="twitter:creator" value="@<?= Cake\Core\Configure::read('usuario_twitter') ?>">
        <meta name="twitter:url" value="<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id]); ?>">
        <meta name="twitter:title" value="<?= $noticia->titulo ?>">
        <meta name="twitter:description" value="<?= $noticia->titulo ?>">
        <?php if ($noticia->has('imagenes')): ?>
            <meta name="twitter:image" value="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename ?>" />
        <?php endif; ?>

        <!-- Favicon -->
        <link rel="shortcut icon" href="/img/images/favicon.png" type="image/x-icon" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="Vista Medios">
        <meta property="og:locale" content="es_LA">
        <meta property="og:url" content="<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id]); ?>" />
        <?php if ($noticia->has('imagenes')): ?>
            <meta property="og:image" content="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename ?>" />
        <?php endif; ?>
        <meta property="og:title" content="<?= $noticia->titulo ?>" />
        <meta property="og:description" content="<?= $noticia->descripcion ?>" />
        <meta itemprop="url" content="<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id]); ?>">
        <meta itemprop="name" content="<?= $noticia->titulo ?>">
        <meta itemprop="title" content="<?= $noticia->titulo ?>">
        <meta itemprop="description" content="<?= $noticia->descripcion ?>">

        <!-- Scripts -->
        <?=
        $this->Html->script([
            'jscript/jquery-latest.min.js',
            'jscript/snap.svg-min.js',
            'jscript/theme-scripts.js',
            'jscript/lightbox.js',
            'jscript/owl.carousel.min.js',
            'jscript/SmoothScroll.min.js',
            'jscript/iscroll.min.js',
            'jscript/modernizr.custom.50878.js',
            'jscript/dat-menu.js',
            'jscript/jqclock_201.min.js',
            'jscript/pgwslideshow.min.js',
            'jscript/bootstrap.min.js',
            'jscript/jquery.simpleWeather.min.js',
            'jscript/remodal.min.js'
        ]);
        ?>

        <!-- Stylesheets -->
        <?=
        $this->Html->css([
            'front/reset.css',
            '/assets/css/font-awesome.min.css',
            'front/animate.css',
            'front/main-stylesheet.css',
            'front/lightbox.css',
            'front/shortcodes.css',
            'front/custom-fonts.css',
            'front/custom-colors.css',
            'front/responsive.css',
            'front/owl.carousel.css',
            'front/owl.theme.css',
            'front/dat-menu.css',
            'front/pgwslideshow.min.css',
            'front/remodal-default-theme.min.css',
            'front/remodal.min.css',
            'front/site.css'
        ]);
        ?>

        <?= $this->Less->less(['/less/Front/simpleweather.less']); ?>

        <!--[if lte IE 8]>
        <link type="text/css" rel="stylesheet" href="css/ie-ancient.css" />
        <![endif]-->

        <!-- Demo Only -->
        <!--<link type="text/css" rel="stylesheet" href="css/demo-settings.css" />-->

        <!-- END head -->
    </head>

    <!-- BEGIN body -->
    <body>
        <a href="#dat-menu" class="ot-menu-toggle"><i class="fa fa-bars"></i>Men&#250;</a>
        <!-- BEGIN .boxed -->
        <div class="boxed active">
            <!-- BEGIN .header -->
            <?= $this->element('Front/header'); ?>
            <!-- BEGIN .content -->
            <section class="content">
                <!-- BEGIN .wrapper -->
                <div class="wrapper">
                    <div class="main-content">
                        <!-- <div class="main-content has-double-sidebar"> -->
                        <!-- BEGIN .left-content -->
                        <div class="left-content">
                            <div class="main-title">
                                <h2><?= $noticia->categoria->nombre ?></h2>
                                <span>
                                    <a href="#<?= $noticia->portal->codigo ?>">
                                        <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                                    </a>
                                </span>
                            </div>
                            <div class="home-block">
                            </div>
                            <div class="home-block">
                                <div class="banner" id="banner">
                                    <?php
                                    $banner = array_pop($banners_728x90);
                                    $width = $banner->banner_tipo->ancho;
                                    $height = $banner->banner_tipo->alto;
                                    ?> 

                                    <?php if (strpos($banner->filename, "swf") !== false): ?>
                                        <?php if ($banner->href != ""): ?>
                                            <a href="<?= $banner->href ?>" target="_blank">
                                                <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?= $banner->filename ?>" value="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                                                    <embed src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                                                </object>
                                            </a>
                                            <!-- END .home-block -->
                                        <?php else: ?>
                                            <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?= $banner->filename ?>" value="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                                                <embed src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                                            </object>
                                            <!-- END .home-block -->
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($banner->href != ""): ?>
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

                            <?php if (count($noticia->imagenes) > 0): ?>
                                <!-- BEGIN .carrousel -->
                                <?php if (count($noticia->imagenes) < 2): ?>
                                    <div class="wp-caption aligncenter" style="width: 88%; margin-bottom: 35px!important; padding-bottom: 15px!important;">
                                        <div class="article-content">
                                            <figure>
                                                <img src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename ?>" style="width:100%; height:100%; display: block; margin: 0 auto;" id="photo" alt="">
                                            </figure>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="slides">
                                        <ul class="pgwSlideshow">
                                            <?php
                                            $imagenes = $noticia->imagenes;
                                            foreach ($imagenes as $key => $imagen):
                                                if ($imagen->descripcion != ""):
                                                    ?>
                                                    <li>
                                                        <img width="100%" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename ?>" data-description="<?= $imagen->descripcion ?>" style="font-family: Montserrat, sans-serif; size: 9px;">
                                                    </li>
                                                <?php else : ?>
                                                    <li>
                                                        <img width="100%" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename ?>">
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <!-- END .carrousel -->
                            <?php endif; ?>

                            <div class="article-content">
                                <div class="article-header">
                                    <span>
                                        <!--<span>by <a href="#">orange-themes</a></span>-->
                                        <!--<span>September 11, 2014 20:00</span>-->
                                        <!--<span><a href="#">3 comments</a></span>-->
                                    </span>

                                    <h1><?= $noticia->titulo ?></h1>

                                    <h5>
                                        <?php
                                            if (strlen($noticia->descripcion) > 0 && !(strpos($noticia->descripcion, "...", strlen($noticia->descripcion) - strlen("...")) !== false)) {
                                                echo $noticia->descripcion;
                                            }
                                        ?>
                                    </h5>

                                    <div class="row">
                                        <div class="col-xs-6" id="publicada" style="position:absolute;">
                                            <i class="fa fa-glyphicons time"></i><strong><?php echo $noticia->publicado; ?></strong>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="social-icon">
                                                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id]); ?>&amp;t=<?= $noticia->titulo ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');
                                                        return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                                                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id]); ?>&amp;text=<?= $noticia->titulo ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');
                                                        return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                                                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');
                                                        return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>                            
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!is_null($noticia->url_video) && $noticia->url_video != ""):
                                    echo $noticia->url_video;
                                endif; ?>

                                <?php
                                //si es solo texto plano
                                if (substr_count($noticia->texto, 'img') == 0 && substr_count($noticia->texto, 'iframe') == 0):
                                    //si es de MDZ que trae el caracter #
                                    if (substr_count($noticia->texto, '#') > 0):
                                        $texto_final = preg_replace("/#/", "", $noticia->texto);
                                        echo $texto_final;
                                    //sino
                                    else:
                                        echo $noticia->texto;
                                    endif;
                                //sino
                                else:
                                    //si es de MDZ que trae el caracter #
                                    if (substr_count($noticia->texto, '#') > 0):
                                        //partimos el texto por linea en base al caracter delimitador
                                        $texto_splited = explode('#', $noticia->texto);
                                        //removemos el caracter delimitador
                                        $texto_splited = preg_replace("/#/", "", $texto_splited);
                                        foreach ($texto_splited as $row):
                                            //nos fijamos si viene una foto o un video
                                            $image = substr_count($row, 'img');
                                            $iframe = substr_count($row, 'iframe');
                                            if ($image > 0 || $iframe > 0 || strpos($row, "<i>") !== false):
                                                echo $row;
                                                ?>&nbsp;<?php
                                            else:
                                                echo $row;
                                            endif;
                                        endforeach;
                                    //sino
                                    else:
                                        echo $noticia->texto;
                                    endif;
                                endif;
                                ?>
                            </div>
                            <br />
                            <span style="font-style: italic;"><strong style="color: #AAA;">Fuente:</strong>
                                <a href="<?= $noticia->portal->url ?>">
                                    <?= $noticia->portal->url ?>
                                </a>
                            </span>
                            <br /><br /><br />
                            <!-- -->
                            <div class="gallery-shortcode relacionadas-portal">
                                <div class="gallery-shortcode-content">
                                    <strong><a href="#" onclick="return false;">Ver más de <b><?= $noticia->portal->nombre ?></b></a></strong>
                                </div>
                                <div class="gallery-shortcode-photos">
                                    <?php foreach ($ultimas_por_portal as $noticia_portal): ?>
                                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia_portal->id, Cake\Utility\Inflector::slug(strtolower($noticia_portal->titulo))]); ?>" style="position: relative;" class="image-hover">
                                            <figure>
                                                <?php if ($noticia->has('imagenes') && !empty($noticia_portal->imagenes)): ?>
                                                    <img style="width: 300px; height: 300px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . reset($noticia_portal->imagenes)->file_url . '/' . reset($noticia_portal->imagenes)->filename ?>" />
                                                <?php else: ?>
                                                    <?php foreach ($portales as $portal): ?>
                                                        <?php if ($portal->codigo == $noticia->portal->codigo && $portal->has('imagen')): ?>
                                                            <img style="width: 300px; height: 300px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>" />
                                                            <?php break; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M 20,100 60,100 50,100 80,100 z" fill="#429d4a" /></svg>
                                                <figcaption>
                                                    <span class="hover-text"><i class=""></i></span>
                                                </figcaption>

                                            </figure>
                                            <span class="contenido">
                                                <p><?= $noticia_portal->titulo ?></p>
                                            </span>
                                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 3px; top: 7px; opacity: 1;"><?php echo $noticia_portal->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="share-article-body">
                                <div class="main-title">
                                    <h2>Seguinos</h2>
                                    <span>#redes</span>
                                </div>
                                <div class="right">
                                    <a href="http://www.facebook.com/885480398208688" target="_blank" class="share-body ot-facebook"><i class="fa fa-facebook"></i><span></span></a>
                                    <a href="https://twitter.com/vista_medios" target="_blank" class="share-body ot-twitter"><i class="fa fa-twitter"></i><span></span></a>
                                    <a href="https://plus.google.com/u/0/107350038594612955632" target="_blank" class="share-body ot-google"><i class="fa fa-google-plus"></i><span></span></a>
                                </div>
                            </div>

                            <div class="article-body-banner banner">
                                <?= $this->element('Front/adsense/primer-728x90') ?>
                            </div>

                            <!-- END .left-content -->
                        </div>
                    </div>
                    <!-- END .wrapper -->
                </div>
                <!-- BEGIN .content -->
            </section>

            <!-- BEGIN .footer -->
            <?= $this->element('Front/footer') ?>
            <?= $this->element('Front/Resenias/resenias-portales') ?>
        </div>

        <?= $this->fetch('scriptsBlock'); ?>
        <?= $this->fetch('script'); ?>
        <script>
            jQuery(document).ready(function() {

                var banner = <?php echo $banner; ?>;
                if (banner.mobile == true && banner.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    $('#banner').html('');
                    $('#banner').html('<?php if ($banner->href != ""): ?><a href="<?= $banner->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');
                }

                $('.pgwSlideshow').pgwSlideshow({
                autoSlide: true,
                        displayList: true,
                        transitionEffect: 'fading'
                });

                $('.imagenes img').click(function() {
                    var imagen = $(this).attr("src");
                    $('#photo').attr("src", imagen);
                });

                jQuery.simpleWeather({
                    location: '',
                    woeid: '332471',
                    unit: 'c',
                    success: function(weather) {
                    html = '<h2>' + weather.temp + '&deg;' + weather.units.temp + ' <i class="icon-' + weather.code + '"></i></h2>';
                            html += '<ul><li>' + weather.city + ', ' + weather.region + '</li>';
                            //html += '<li class="currently">'+weather.currently+'</li>';
                            //html += '<li>'+weather.wind.direction+' '+weather.wind.speed+' '+weather.units.speed+'</li></ul>';

                            $("#weather").html(html);
                    },
                    error: function(error) {
                        $("#weather").html('<p>' + error + '</p>');
                    }
                });

                jQuery.ajax({
                    url:"/gethora.php",
                        cache: false,
                        success:function(html){
                        jQuery("div#fecha").clock({"timestamp":html}); }
                });

                jQuery(".gallery-shortcode-photos").owlCarousel({
                    items : 4,
                    autoPlay : true,
                    stopOnHover : true,
                    navigation : false,
                    lazyLoad : true,
                    singleItem : false,
                    pagination : true
                });

                jQuery.get( "<?= $this->Url->build(['controller' => 'redes', 'action' => 'social_facebook']); ?>", function( data ) {
                    $( ".ot-facebook span" ).html( JSON.parse(data).likes );
                });

                jQuery.get( "<?= $this->Url->build(['controller' => 'redes', 'action' => 'social_twitter']); ?>", function( data ) {
                    $( ".ot-twitter span" ).html( data );
                });

                jQuery.get( "<?= $this->Url->build(['controller' => 'redes', 'action' => 'social_g_plus']); ?>", function( data ) {
                    $( ".ot-google span" ).html( data );
                });
            });
        </script>

        <?= $this->element('Front/GA') ?>
        <!-- Demo Only -->
        <!--<script type="text/javascript" src="jscript/demo-settings.js"></script>-->
        <!-- END body -->
    </body>
    <!-- END html -->
</html>