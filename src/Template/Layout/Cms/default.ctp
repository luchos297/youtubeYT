<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VistaMedios</title>
    <?= $this->Html->meta(
        'description',
        ''
    );
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon -->
    <?= $this->Html->meta(
        '/img/images/favicon.png',
        ['type' => 'icon']
    );
    ?>
    
    <?= $this->Html->css([
        /*Bootstrap core CSS*/
        '/assets/plugins/bootstrap/css/bootstrap.min.css',
        /*Font Icons*/
        '/assets/css/font-awesome.min.css',
        '/assets/css/simple-line-icons.css',
        /*CSS Animate*/
        '/assets/css/animate.css',
        /*Switchery*/
        '/assets/plugins/switchery/switchery.min.css',
        /*DataTables*/
        '/assets/plugins/dataTables/css/dataTables.css'
        ]);?>
    
    <!-- Custom styles for this theme -->
    <?= $this->Less->less(['/less/main.less']); ?>
    
    <?= $this->Html->css([
        /*Bootstrap core CSS*/
        '/assets/plugins/icheck/css/_all.css',
        
        '/assets/plugins/messenger/css/messenger.css',
        '/assets/plugins/messenger/css/messenger-theme-flat.css',
        '/css/back/site.css',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.min.css'
        //'/css/back/switch.css'
        ]);?>
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    
    <!-- Feature detection -->
    <?= $this->Html->script(['/assets/js/modernizr-2.6.2.min.js']); ?>
    <?= $this->Html->script([
        '/assets/js/jquery-1.10.2.min.js',
        '/assets/plugins/bootstrap/js/bootstrap.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/locales/bootstrap-datepicker.es.min.js'
        ]);
    ?>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="off-canvas">
    <div id="container">
        <header id="header">
            <!--logo start-->
            <div class="brand">
                <!--<a href="#" class="logo"><span>Vista</span>Medios</a>-->
            </div>
            <!--<?= $this->Html->image(Cake\Core\Configure::read('path_imagen_rss').'../../img/logo.png') ?>-->
            <!--logo end-->
            <div class="toggle-navigation toggle-left">
                <button type="button" class="btn btn-default" id="toggle-left" data-toggle="tooltip" data-placement="right" title="Toggle Navigation">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <?= $this->element('Cms/nav-top'); ?>
        </header>
       <!--sidebar left start-->
        <?= $this->element('Cms/nav-left'); ?>
        <!--sidebar left end-->
        <!--main content start-->
        <section class="main-content-wrapper">
            <section id="main-content">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </section>
        </section>
        <!--main content end-->
    </div>    
    <!--Global JS-->    
    <?= $this->Html->script([  
        'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js',
        'http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.0/backbone-min.js',
        
        '/assets/plugins/messenger/js/messenger.min.js',
        '/assets/plugins/messenger/js/messenger-theme-future.js',
        
        '/assets/plugins/navgoco/jquery.navgoco.min.js',
        
        '/assets/plugins/waypoints/waypoints.min.js',
        //'/assets/plugins/switchery/switchery.min.js',
        '/assets/js/application.js',
        
        /*Page Level JS*/
        '/assets/plugins/dataTables/js/jquery.dataTables.js',
        '/assets/plugins/dataTables/js/dataTables.bootstrap.js',
        
        '/assets/plugins/messenger/js/demo/location-sel.js',        
        '/assets/plugins/messenger/js/demo/theme-sel.js',
        '/assets/plugins/messenger/js/demo/demo.js',
        '/assets/plugins/messenger/js/demo/demo-messages.js',
        
        //'/assets/plugins/ckeditor/ckeditor.js',
        '/assets/plugins/icheck/js/icheck.min.js',
        
        '/assets/plugins/sortNestable/js/jquery.nestable.js'
        ]); 
    ?>
    <?= $this->fetch('scriptsBlock'); ?>
    <?= $this->fetch('script'); ?>
    <script>
        $(document).ready(function() {
            $('#data-table-cms').dataTable({
                "bFilter": false,
                "bLengthChange" : false,
                "ordering": false,
                "paging":   false,
                "info":     false
            });
            
            //$('.html-editor').wysihtml5();             
            $('input.icheck').iCheck({
                checkboxClass: 'icheckbox_flat-grey',
                radioClass: 'iradio_flat-grey'
            });
            
            $('.volver').click(function(){
                window.history.back();
            });         
        });
    </script>
    <div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>
