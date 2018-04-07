<!DOCTYPE HTML>
<!-- BEGIN html -->
<html lang = "en">
    <!-- BEGIN head -->
    <head>
        <!-- Meta Tags -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="es-AR">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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
        <!-- Favicon -->
        <link rel="shortcut icon" href="/img/images/favicon.png" type="image/x-icon" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="Vista Medios">
        <meta property="og:locale" content="es_LA">

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
            'jscript/remodal.min.js',            
            'jscript/autoFill.bootstrap.min.js',
            'jscript/buttons.bootstrap.min.js',
            'jscript/dataTables.colReorder.min.js',
            'jscript/dataTables.fixedColumns.min.js',
            'jscript/dataTables.fixedHeader.min.js',
            'jscript/dataTables.keyTable.min.js',            
            'jscript/dataTables.keyTable.min.js',
            'jscript/dataTables.responsive.min.js',
            'jscript/dataTables.rowGroup.min.js',
            'jscript/dataTables.rowReorder.min.js',
            'jscript/dataTables.select.min.js'
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
            'front/site.css',
            'front/jquery.dataTables.min.css',
            'front/autoFill.bootstrap.min.css',
            'front/buttons.bootstrap.min.css',
            'front/colReorder.bootstrap.min.css',
            'front/datatables.min.css',
            'front/fixedColumns.bootstrap.min.css',
            'front/fixedHeader.bootstrap.min.css',
            'front/keyTable.bootstrap.min.css',
            'front/responsive.bootstrap.min.css',
            'front/rowGroup.bootstrap.min.css',
            'front/rowReorder.bootstrap.min.css',
            'front/select.bootstrap.min.css'
        ]);
        ?>

        <!-- END head -->
    </head>

    <!-- BEGIN body -->
    <body>
        <a href="#dat-menu" class="ot-menu-toggle"><i class="fa fa-bars"></i>Men&#250;</a>
        <!-- BEGIN .boxed -->
        <div class="boxed active">
            <section class="content">
                <!-- BEGIN .wrapper -->
                <div class="wrapper">
                    <div class="main-content">                        
                        <table id="table" class="display">
                            <thead>
                                <tr style="">
                                    <th>Title</th>
                                    <th>Artist</th>
                                    <th>Album</th>
                                    <th>Year</th>
                                    <th>Genre</th>
                                    <th>Duration</th>
                                    <th>Youtube</th>
                                    <th>Filesize</th>
                                    <th>Sample Rate</th>
                                    <th>Bitrate</th>
                                    <th>Format</th>
                                    <th>Quality</th>
                                    <th>URL</th>
                                    <th>Downloaded</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($resultadoDTO_recargado['listado'] as $song): ?>
                                    <tr>
                                        <td style="vertical-align: middle;"><?= $song['title']; ?></td>
                                        <td style="vertical-align: middle;"><?= $song['artist']; ?></td>
                                        <td style="vertical-align: middle;"><?= $song['album']; ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $song['year']; ?></td>
                                        <td style="vertical-align: middle;"><?= $song['genre']; ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $song['duration']; ?></td>
                                        <td style="vertical-align: middle;"><a href="<?= $song['url_yt']; ?>" target="_blank"><?= $song['url_yt'] ?></a></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $song['filesize'] . ' MB'; ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $song['sample_rate']; ?></td>
                                        <td style="vertical-align: middle;"><?= $song['bitrate'] . ' Kbps'; ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= strtoupper($song['dataformat']); ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $song['quality']; ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><a href="<?= $song['url_yt_download']; ?>" target="_blank">Download</a></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= (count($song['downloaded']) > 0) ? 'No' : 'Yes'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- END .wrapper -->
                </div>
                <!-- BEGIN .content -->
            </section>
        </div>

        <?= $this->fetch('scriptsBlock'); ?>
        <?= $this->fetch('script'); ?>
        <script>
            jQuery(document).ready(function() {
                
                $('#table').DataTable();
            });
        </script>

        <!-- END body -->
    </body>
    <!-- END html -->
</html>