<!DOCTYPE HTML>
<!-- BEGIN html -->
<html lang = "en">
    <!-- BEGIN head -->
    <head>
        <title>VistaMedios</title>

        <!-- Meta Tags -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <!-- Favicon -->
        <link rel="shortcut icon" href="/img/images/favicon.png" type="image/x-icon" />

        <!-- Stylesheets -->
        <?= $this->Html->css([
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
            'front/remodal-default-theme.min.css',
            'front/remodal.min.css',
            'front/site.css'
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
            'jscript/remodal.min.js',
            ]); ?>

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
                        <?= $this->fetch('content') ?>
                <!-- BEGIN .content -->
                </section>

                <!-- BEGIN .footer -->
                <?= $this->element('Front/footer'); ?>
                <?= $this->element('Front/Resenias/resenias-radios') ?>
        <!-- END .boxed -->
        </div>

	<!-- Scripts -->
        <?= $this->fetch('scriptsBlock'); ?>
        <?= $this->fetch('script'); ?>
        <script>
            jQuery(document).ready(function() {
                jQuery.simpleWeather({
                    location: '',
                    woeid: '332471',
                    unit: 'c',
                    success: function(weather) {
                      html = '<h2>'+weather.temp+'&deg;'+weather.units.temp+' <i class="icon-'+weather.code+'"></i></h2>';
                      html += '<ul><li>'+weather.city+', '+weather.region+'</li>';
                      //html += '<li class="currently">'+weather.currently+'</li>';
                      //html += '<li>'+weather.wind.direction+' '+weather.wind.speed+' '+weather.units.speed+'</li></ul>';

                      $("#weather").html(html);
                    },
                    error: function(error) {
                      $("#weather").html('<p>'+error+'</p>');
                    }
                });

                jQuery.ajax({
                url:"/gethora.php",
                cache: false,
                success:function(html){
                    jQuery("div#fecha").clock({"timestamp":html});}
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