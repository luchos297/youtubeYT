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
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap core CSS -->
    <?= $this->Html->css([
        '/assets/plugins/bootstrap/css/bootstrap.min.css',
        '/assets/css/font-awesome.min.css',
        '/assets/css/animate.css'
    ]);?>
    
    <?= $this->Less->less(['/less/main.less']); ?>
    
    <?= $this->Html->css([
        '/assets/plugins/messenger/css/messenger.css',
        '/assets/plugins/messenger/css/messenger-theme-flat.css'
    ]);?>  
       
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <!-- Feature detection -->
    <?= $this->Html->script(['/assets/js/modernizr-2.6.2.min.js']); ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="animated fadeIn">
    <section id="login-container">
        <div id="content">
            <?= $this->Flash->render() ?>

            <div class="row">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </section>
    <!--Global JS-->
    <?= $this->Html->script([
        '/assets/js/jquery-1.10.2.min.js',
        '/assets/plugins/bootstrap/js/bootstrap.min.js',        
        
        'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js',
        'http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.0/backbone-min.js',
        
        '/assets/plugins/messenger/js/messenger.min.js',
        '/assets/plugins/messenger/js/messenger-theme-future.js',
        
        '/assets/plugins/messenger/js/demo/location-sel.js',        
        '/assets/plugins/messenger/js/demo/theme-sel.js',
        '/assets/plugins/messenger/js/demo/demo.js',
        '/assets/plugins/messenger/js/demo/demo-messages.js'        
    ]);?>
</body>

</html>
