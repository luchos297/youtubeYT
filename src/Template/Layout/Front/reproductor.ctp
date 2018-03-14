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
        ]);?>

        <!-- Scripts -->
        <?= $this->Html->script([
            'jscript/rep/jquery.min.js',
            'jscript/rep/jquery-ui.min.js',
            //'jscript/rep/soundmanager2-jsmin.js',
            'jscript/rep/soundmanager2-nodebug-jsmin.js'
        ]); ?>

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
            .tooltip {
                position: absolute;
                display: block;
                top: -25px;
                width: 35px;
                height: 17px;
                color: #fff;
                text-align: center;
                font: 9pt Tahoma, Arial, sans-serif ;
                border-radius: 3px;
                border: 1px solid #333;
                -webkit-box-shadow:  1px 1px 2px 0px rgba(0, 0, 0, .3);
                box-shadow:  1px 1px 2px 0px rgba(0, 0, 0, .3);
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                background: -moz-linear-gradient(top,  rgba(69,72,77,0.5) 0%, rgba(0,0,0,0.5) 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(69,72,77,0.5)), color-stop(100%,rgba(0,0,0,0.5))); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top,  rgba(69,72,77,0.5) 0%,rgba(0,0,0,0.5) 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top,  rgba(69,72,77,0.5) 0%,rgba(0,0,0,0.5) 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top,  rgba(69,72,77,0.5) 0%,rgba(0,0,0,0.5) 100%); /* IE10+ */
                background: linear-gradient(top,  rgba(69,72,77,0.5) 0%,rgba(0,0,0,0.5) 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8045484d', endColorstr='#80000000',GradientType=0 ); /* IE6-9 */
            }
            .volume {
                display: inline-block;
                width: 29px;
                height: 25px;
                right: -71px;
                background: url('/img/images/volume.png') no-repeat 0 -51px;
                position: absolute;
                margin-top: -5px;
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
            if($this->request->query['type'] == 'disney'):
        ?>
        <div style="border: 1px solid #c0c0c0;margin: 15px 6px;padding: 20px;overflow: hidden;background: #fff;position: absolute;width: 320px;height: 164px;border-radius: 5px;" >
            <iframe id="playerIFrame" frameborder="0" scrolling="no" align="middle" src= "http://www.disneylatino.com/radio/player/arg/widget.html" height="147" width="314" ></iframe>
        </div>
        <?php
        else:
        ?>
        <div style="border: 1px solid #c0c0c0;margin: 15px 6px;padding: 20px;overflow: hidden;background: #fff;position: absolute;width: 330px;height: 55px;border-radius: 5px;" >
            <img  style="position: absolute;left: 0;top: 0;" src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . '/img/' . $this->request->query['imagen']?>&h=95&w=150" />           
            <div id="" style="position: absolute;right: 70px;top: 39px;">
                <section> 
                    <!--<span class="tooltip"></span>-->
                    <div id="slider"></div>
                    <span class="volume"></span>
                </section>
            </div>
            <div id="" style="position: absolute;right: 37px;top: 25px;">
                <img src="/img/images/equalizer.jpg" alt="Off Line" width="105" height="30" id="sm-equalizer"/>
            </div>
            <div id="rtmp-player" style="position: absolute;left: 166px;top: 21px;">
                <img src="http://cdn.webrad.io/images/stop.png" alt="Parar" width="40" height="40" id="sm-button"/>
                <!--<i class="fa fa-play" alt="Parar" width="50" height="50" id="sm-button"></i>-->
            </div>
        </div>
        <?php
        endif;
        ?>

        <!-- Scripts -->
        <script>
            $(function() {
                window.onloadRetry = 0;
                window.soundManager = new SoundManager();

                // Configure soundManager
                soundManager.setup({
                    //debugMode: true,
                    flashLoadTimeout: 0,
                    flashVersion: 9,
                    preferFlash: <?= ($this->request->query['serverurl'] != '') ? 1 : 0 ?>,
                    url: "/swf/",
                    useHighPerformance: true,
                    waitForWindowLoad: false,
                    onready: function() {
                        soundManager.createSound({
                            id: "webradio",
                            volume: 50,
                            <?php 
                            if($this->request->query['serverurl'] != ''):
                            ?>
                                serverURL: "<?= $this->request->query['serverurl'] ?>",
                            <?php endif; ?>
                            url: [{
                                    type: "<?= $this->request->query['type'] ?>",
                                    url: "<?= $this->request->query['url'] ?>"
                                }],
                            autoLoad: true,
                            autoPlay: true,
                            multiShot: false,
                            onconnect: function( bConnect ) {
                                setButtonStop();
                            },
                            onfailure: function() {
                                setButtonError();
                            },
                            onload: function( bSuccess ) {
                                if ( bSuccess == true ) {
                                    setButtonStop();
                                } else {
                                    if ( window.onloadRetry != 2 ) {
                                        window.onloadRetry++;
                                        soundManager.reboot();
                                    } else {
                                        setButtonError();
                                    }
                                }
                            },
                            onplay: function() {
                                <?php 
                                if($this->request->query['serverurl'] != ''):
                                ?>
                                setButtonStop();
                                <?php
                                else:
                                ?>
                                setButtonPreloader();
                                <?php endif; ?>
                            }
                        });
                    },
                    ontimeout: function() {
                        // setButtonError();
                    }
                });

                // Define the buttons
                function setButtonError() {
                    $( "#sm-button" ).attr( "src", "http://cdn.webrad.io/images/error.png" ).attr( "alt", "Error" ).removeAttr( 'style' );
                    $( "#sm-equalizer" ).attr( "src", "/img/images/equalizer.jpg" ).attr( "alt", "Off Line" ).removeAttr( 'style' );
                    //ga( "send", "event", { eventCategory: "Player", eventAction: "Error" } );
                    // logStreamError( "55", "desktop" );
                }
                function setButtonFlash() {
                    $( "#sm-button" ).attr( "src", "http://cdn.webrad.io/images/button-get-flash-player.png" ).attr( "alt", "Flash" ).attr( "style", "width:160px !important; height:41px !important;" );
                }
                function setButtonPlay() {
                    $( "#sm-button" ).attr( "src", "http://cdn.webrad.io/images/play_new.png" ).attr( "alt", "Sonar" ).removeAttr( 'style' );
                    $( "#sm-equalizer" ).attr( "src", "/img/images/equalizer.jpg" ).attr( "alt", "Sonar" ).removeAttr( 'style' );
                }
                function setButtonPreloader() {//http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif
                    $( "#sm-button" ).attr( "src", "/img/images/loading.gif" ).attr( "alt", "Cargando..." ).removeAttr( 'style' );
                }
                function setButtonStop() {
                    $( "#sm-button" ).attr( "src", "http://cdn.webrad.io/images/stop.png" ).attr( "alt", "Parar" ).removeAttr( 'style' );
                    $( "#sm-equalizer" ).attr( "src", "/img/images/equalizer.gif" ).attr( "alt", "Online" ).removeAttr( 'style' );
                }

                // Set the controls
                $( "#sm-button" ).bind( "click", function() {
                    if ( $( this ).attr( "alt" ) == "Flash" ) {
                        window.open( 'https://get.adobe.com/flashplayer/' );
                    } else if ( $( this ).attr( "alt" ) == "Sonar" ) {
                        setButtonStop();
                        ( "desktop" != "desktop" ) ? soundManager.play( "webradio" ) : soundManager.unmute( "webradio" );
                    } else if ( $( this ).attr( "alt" ) == "Inicio" ) {
                        setButtonPreloader();
                        ( "desktop" != "desktop" ) ? soundManager.play( "webradio" ) : '';
                    } else if ( $( this ).attr( "alt" ) == "Parar" ) {
                        setButtonPlay();
                        ( "desktop" != "desktop" ) ? soundManager.unload( "webradio" ) : soundManager.mute( "webradio" );
                    }
                });

                // Kick-start the SoundManager init process?
                if ( "<?= ($this->request->query['serverurl'] != '') ? 1:'' ?>" == "1" && typeof( detectFlash ) === "function" && !detectFlash() ) {
                    setButtonFlash();
                } else {
                    soundManager.beginDelayedInit();
                }

                var slider = $('#slider'), tooltip = $('.tooltip');

                tooltip.hide();

                slider.slider({
                    range: "min",
                    min: 1,
                    value: 50,

                    start: function(event,ui) {
                      tooltip.fadeIn('fast');
                    },

                    slide: function(event, ui) {

                        var value = slider.slider('value'),
                            volume = $('.volume');

                        tooltip.css('left', value).text(ui.value);

                        if(value <= 5) {
                            volume.css('background-position', '0 0');
                        }
                        else if (value <= 25) {
                            volume.css('background-position', '0 -25px');
                        }
                        else if (value <= 75) {
                            volume.css('background-position', '0 -50px');
                        }s
                        else {
                            volume.css('background-position', '0 -75px');
                        };
                        soundManager.setVolume(value)
                    },

                    stop: function(event,ui) {
                        tooltip.fadeOut('fast');
                    },
                });
            });
        </script>
    <!-- END body -->
    </body>
<!-- END html -->
</html>