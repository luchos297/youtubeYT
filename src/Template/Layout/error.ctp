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
    <link rel="shortcut icon" href="/img/images/favicon.png" type="image/x-icon" />
    
    <?= $this->Html->css([
        /*Bootstrap core CSS*/
        '/assets/plugins/bootstrap/css/bootstrap.min.css',
        /*Font Icons*/
        '/assets/css/font-awesome.min.css',
        /*CSS Animate*/
        '/assets/css/animate.css',
        '/assets/css/main.css'
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
<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?> 
<!--Global JS-->    
<?= $this->Html->script([  
    '/assets/js/jquery-1.10.2.min.js',
    '/assets/plugins/bootstrap/js/bootstrap.min.js',
    '/assets/plugins/waypoints/waypoints.min.js',
    '/assets/js/application.js',
    ]); 
?>
</html>
