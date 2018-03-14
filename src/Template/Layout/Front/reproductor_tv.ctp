<!DOCTYPE HTML>
<!-- BEGIN html -->
<html lang = "en" style="background: #d0d0d0;">
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
            '/assets/css/font-awesome.min.css',
            'front/rep/stormtrooper.css',
            ])
        ?>

        <!-- Scripts -->
        <?= $this->Html->script([
                'jscript/rep/jquery.min.js',
                'jscript/rep/jquery-ui.min.js',
                //'http://videostreaminghd.hdstreamhost.com/players/jwp7/jwplayer.js'
                'jscript/rep/bCwJiXlk.js'
            ]); ?>

        <?php
        if($this->request->query['type'] == 'rtmp'){
            $this->Html->script([
                'jscript/rep/bCwJiXlk.js'
                //'http://videostreaminghd.hdstreamhost.com/players/jwplayerV6/jwplayer/jwplayer.js'
            ]);
        }
        elseif($this->request->query['type'] == 'rtmp_coope'){
            $this->Html->script([
                'jscript/rep/bCwJiXlk.js'
            ]);
        }
        ?>

        <!--[if lte IE 8]>
        <link type="text/css" rel="stylesheet" href="css/ie-ancient.css" />
        <![endif]-->

        <!-- Demo Only -->
        <!--<link type="text/css" rel="stylesheet" href="css/demo-settings.css" />-->

	<!-- END head -->
        <style>
            section {
                width: 150px;
                height: auto;
                margin: 30px auto 0;
                position: relative;
            }
            #slider{
                border-width: 1px;
                border-style: solid;
                border-color: #333 #333 #777 #333;
                border-radius: 25px;
                width: 165px;
                position: absolute;
                height: 10px;
                background-color: #8e8d8d;
                background: url('/img/images/bg-track.png') repeat top left;
              box-shadow: inset 0 1px 5px 0px rgba(0, 0, 0, .5), 
                             0 1px 0 0px rgba(250, 250, 250, .5);
              left: 20px;
            }
            .ui-slider-handle {
                position: absolute;
                z-index: 2;
                width: 25px;
                height: 25px;
                cursor: pointer;
                background: url('/img/images/handle.png') no-repeat 50% 50%;
                font-weight: bold;
                color: #1C94C4;
                outline: none;
                top: -7px;
                margin-left: -12px;
            }
            .ui-slider-range {
                background: linear-gradient(top, #ffffff 0%,#eaeaea 100%);
                position: absolute;
                border: 0;
                top: 0;
                height: 100%;
                border-radius: 25px;
            }
        </style>
    </head>

    <!-- BEGIN body -->
    <body>
        <?php
            if($this->request->query['type'] == 'iframe'):
        ?>
        <iframe src="<?= $this->request->query['url'] ?>" width="<?= $this->request->query['w'] ?>" height="<?= $this->request->query['h'] ?>" frameborder="0" allowfullscreen></iframe>
        <?php
            elseif($this->request->query['type'] == 'rtmp' || $this->request->query['type'] == 'rtmp_coope'):
        ?>
        <div id="mediaplayer"></div>
        <?php
        endif;
        ?>

	<!-- Scripts -->
        <?= $this->fetch('scriptsBlock'); ?>
        <?= $this->fetch('script'); ?>
        <script>
            $(function() {
                <?php
                if($this->request->query['type'] == 'rtmp'):
                ?>

                var playerInstance = jwplayer("mediaplayer");
                playerInstance.setup({
                    file: "<?= $this->request->query['url'] ?>",
                    height: <?= $this->request->query['h'] ?>,
                    width: <?= $this->request->query['w'] ?>
                });
                <?php
                elseif($this->request->query['type'] == 'rtmp_coope'):
                ?>
                    var playerInstance = jwplayer("mediaplayer");
                    playerInstance.setup({
                        // file: "http://192.99.46.56:1935/envivo/bbtnet/manifest.mpd",
                        playlist: [{
                           // image: "../assets/myPoster.jpg",
                            sources: [{ 
                                file: "http://hdvideo.masterhost.com.ar:1935/coope-live/coope-live/playlist.m3u8"
                            }]
                        }],
                        logo: {
                            file: "//",
                            link: "//"
                        },

                        width: "100%",
                        aspectratio: "16:9",
                        primary: "flash",

                        abouttext: "Streaming HD |",
                        aboutlink: "http://www.masterhost.com.ar",
                        autostart: "true",
                        androidhls: "true",

                        skin: {
                            name: "stormtrooper"
                        }

                    });
                    //Cuando le das play, reemplaza "Live Broadcast" por "EnVIVO"
                    jwplayer().on('play', function(){
                        setTimeout(function(){
                            var textAlt = document.querySelectorAll('#' + jwplayer().id + ' .jw-controlbar-center-group .jw-text-alt')[0];
                            if(textAlt) textAlt.innerHTML = 'EN VIVO';
                        },300);
                    });
                <?php
                endif;
                ?>
            });
        </script>
    <!-- END body -->
    </body>
<!-- END html -->
</html>