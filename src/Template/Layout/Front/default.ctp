<!DOCTYPE HTML>
<!-- BEGIN html -->
<html lang = "en">
    <!-- BEGIN head -->
    <head>
        <title>VistaMedios</title>

        <!-- Meta Tags -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Language" content="es-AR">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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
        <meta http-equiv="refresh" content="600">
        <meta property="og:type" content="article">
        <meta property="og:site_name" content="Vista Medios">
        <meta property="og:locale" content="es_LA">
        <!-- Favicon -->
        <link rel="shortcut icon" href="/img/images/favicon.png" type="image/x-icon" />

        <!-- Stylesheets -->
        <?= $this->Html->css([
            'front/reset.css',
            '../assets/css/font-awesome.min.css',
            'front/animate.min.css',
            'front/main-stylesheet.min.css',
            'front/lightbox.css',
            'front/shortcodes.css',
            'front/custom-fonts.css',
            'front/custom-colors.css',
            'front/responsive.css',
            'front/owl.carousel.css',
            'front/owl.theme.css',
            'front/dat-menu.css',
            'front/remodal-default-theme.min.css',
            'front/remodal.min.css',
            'front/site.css',
        ]);?>

        <!-- Scripts -->
        <?= $this->Html->script([
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
            'jscript/jquery.simpleWeather.min.js',
            'jscript/jquery.lazyload.min.js',
            'jscript/remodal.min.js',
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

                    <!-- BEGIN .ultimas -->
                    <?= $this->element('Front/noticias-recientes-horizontal'); ?>

                    <!-- BEGIN .temas -->
                    <?= $this->element('Front/temas-mas-importantes'); ?>

                    <!-- BEGIN .ultima -->
                    <?= $this->element('Front/ultima-noticia'); ?>

                </div>

                <div class="wrapper">

                    <div class="main-content has-sidebar">
                        <!-- <div class="main-content has-double-sidebar"> -->
                        <!-- <div class="main-content"> -->

                        <!-- BEGIN .carousel -->
                        <?= $this->element('Front/carousel'); ?>

                        <!-- BEGIN .left-content -->
                        <?= $this->element('Front/contenido-centro-izquierda'); ?>

                        <!-- BEGIN .sidebar -->
                        <?= $this->element('Front/contenido-derecha-superior'); ?>
                        <?= $this->element('Front/contenido-derecha-central'); ?>
                        <?= $this->element('Front/contenido-derecha-inferior'); ?>
                        <?= $this->element('Front/contenido-derecha-fondo'); ?>

                    </div>

                    <!-- END .wrapper -->
                </div>

                <!-- BEGIN .footer -->
                <?= $this->element('Front/footer') ?>
                <?= $this->element('Front/Resenias/resenias-portales') ?>

        </div>

        <!-- Scripts -->
        <?= $this->fetch('scriptsBlock'); ?>
        <?= $this->fetch('script'); ?>
        <script>
            jQuery(document).ready(function () {
                jQuery(".ot-slider").owlCarousel({
                    items: 1,
                    autoPlay: 4000,
                    stopOnHover: true,
                    navigation: true,
                    lazyLoad: false,
                    singleItem: true,
                    pagination: false
                });

                jQuery.simpleWeather({
                    location: '',
                    woeid: '332471',
                    unit: 'c',
                    success: function (weather) {
                        html = '<h2>' + weather.temp + '&deg;' + weather.units.temp + ' <i class="icon-' + weather.code + '"></i></h2>';
                        html += '<ul><li>' + weather.city + ', ' + weather.region + '</li>';

                        $("#weather").html(html);
                    },
                    error: function (error) {
                        $("#weather").html('<p>' + error + '</p>');
                    }
                });

                jQuery.ajax({
                    url: "/gethora.php",
                    cache: false,
                    success: function (html) {
                        jQuery("div#fecha").clock({"timestamp": html});
                    }
                });
            });
            jQuery("img.lazy").lazyload({
                event: "sporty"
            });
            jQuery(window).bind("load", function () {
                var timeout = setTimeout(function () {
                    $("img.lazy").trigger("sporty");
                }, 5000);
            });
        </script>

        <?= $this->element('Front/GA') ?>
        <!-- Demo Only -->
        <!--<script type="text/javascript" src="jscript/demo-settings.js"></script>-->

        <!-- END body -->
    </body>
    <!-- END html -->
</html>