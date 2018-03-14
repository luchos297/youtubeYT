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
    <title>SpaceLab</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/images/favicon.png" type="image/x-icon">
    
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
        ]);?>
    
    <!-- Custom styles for this theme -->
    <?= $this->Less->less(['/less/main.less']); ?>
    
    <?= $this->Html->css([
        /*DataTables*/
        '/assets/plugins/dataTables/css/dataTables.css'
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

<body class="off-canvas">
    <div id="container">
        <header id="header">
            <!--logo start-->
            <div class="brand">
                <a href="index.html" class="logo"><span>Space</span>Lab</a>
            </div>
            <!--logo end-->
            <div class="toggle-navigation toggle-left">
                <button type="button" class="btn btn-default" id="toggle-left" data-toggle="tooltip" data-placement="right" title="Toggle Navigation">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="user-nav">
                <ul>
                    <li class="dropdown messages">
                        <span class="badge badge-danager animated bounceIn" id="new-messages">5</span>
                        <button type="button" class="btn btn-default dropdown-toggle options" id="toggle-mail" data-toggle="dropdown">
                            <i class="fa fa-envelope"></i>
                        </button>
                        <ul class="dropdown-menu alert animated fadeInDown">
                            <li>
                                <h1>You have <strong>5</strong> new messages</h1>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="profile-photo">
                                        <img src="assets/img/avatar.gif" alt="" class="img-circle">
                                    </div>
                                    <div class="message-info">
                                        <span class="sender">James Bagian</span>
                                        <span class="time">30 mins</span>
                                        <div class="message-content">Lorem ipsum dolor sit amet, elit rutrum felis sed erat augue fusce...</div>
                                    </div>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <div class="profile-photo">
                                        <img src="assets/img/avatar1.gif" alt="" class="img-circle">
                                    </div>
                                    <div class="message-info">
                                        <span class="sender">Jeffrey Ashby</span>
                                        <span class="time">2 hour</span>
                                        <div class="message-content">hendrerit pellentesque, iure tincidunt, faucibus vitae dolor aliquam...</div>
                                    </div>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <div class="profile-photo">
                                        <img src="assets/img/avatar2.gif" alt="" class="img-circle">
                                    </div>
                                    <div class="message-info">
                                        <span class="sender">John Douey</span>
                                        <span class="time">3 hours</span>
                                        <div class="message-content">Penatibus suspendisse sit pellentesque eu accumsan condimentum nec...</div>
                                    </div>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <div class="profile-photo">
                                        <img src="assets/img/avatar3.gif" alt="" class="img-circle">
                                    </div>
                                    <div class="message-info">
                                        <span class="sender">Ellen Baker</span>
                                        <span class="time">7 hours</span>
                                        <div class="message-content">Sem dapibus in, orci bibendum faucibus tellus, justo arcu...</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="profile-photo">
                                        <img src="assets/img/avatar4.gif" alt="" class="img-circle">
                                    </div>
                                    <div class="message-info">
                                        <span class="sender">Ivan Bella</span>
                                        <span class="time">6 hours</span>
                                        <div class="message-content">Curabitur metus faucibus sapien elit, ante molestie sapien...</div>
                                    </div>
                                </a>
                            </li>
                            <li><a href="#">Check all messages <i class="fa fa-angle-right"></i></a>
                            </li>
                        </ul>

                    </li>
                    <li class="profile-photo">
                        <img src="assets/img/avatar.png" alt="" class="img-circle">
                    </li>
                    <li class="dropdown settings">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      Mike Adams <i class="fa fa-angle-down"></i>
                    </a>
                        <ul class="dropdown-menu animated fadeInDown">
                            <li>
                                <a href="#"><i class="fa fa-user"></i> Profile</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-calendar"></i> Calendar</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge badge-danager" id="user-inbox">5</span></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-power-off"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="toggle-navigation toggle-right">
                            <a href="#right-menu" class="btn btn-default" id="toggle-right">
                                <i class="fa fa-comment"></i>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </header>
       <!--sidebar left start-->
        <nav class="sidebar sidebar-left">
             <h5 class="sidebar-header">Navigation</h5>
            <ul class="nav nav-pills nav-stacked">
                <li>
                    <a href="index.html" title="Dashboard">
                        <i class="icon-speedometer"></i> Dashboard
                    </a>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="UI Elements">
                        <i class="icon-chemistry"></i> UI Elements
                    </a>
                    <ul class="nav-sub">
                        <li><a href="ui-alerts-notifications.html">Alerts &amp; Notifications</a>
                        </li>
                        <li><a href="ui-panels.html">Panels</a>
                        </li>
                        <li><a href="ui-buttons.html">Buttons</a>
                        </li>
                        <li><a href="ui-slider-progress.html">Sliders &amp; Progress</a>
                        </li>
                        <li><a href="ui-modals-popups.html">Modals &amp; Popups</a>
                        </li>
                        <li><a href="ui-icons.html">Icons</a>
                        </li>
                        <li><a href="ui-grid.html">Grid</a>
                        </li>
                        <li><a href="ui-tabs-accordions.html">Tabs &amp; Accordions</a>
                        </li>
                        <li><a href="ui-nestable-list.html">Nestable Lists</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown open active">
                    <a href="#" title="Tables">
                        <i class="fa fa-table"></i> Tables
                    </a>
                    <ul class="nav-sub">
                        <li><a href="tables-basic.html">Basic Tables</a>
                        </li>
                        <li class="active"><a href="tables-data.html">Data Tables</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="Forms">
                        <i class="fa fa-list-alt"></i> Forms
                    </a>
                    <ul class="nav-sub">
                        <li><a href="forms-components.html">Components</a>
                        </li>
                        <li><a href="forms-validation.html">Validation</a>
                        </li>
                        <li><a href="forms-mask.html">Mask</a>
                        </li>
                        <li><a href="forms-wizard.html">Wizard</a>
                        </li>
                        <li><a href="forms-multiple-file.html">Multiple File Upload</a>
                        </li>
                        <li><a href="forms-wysiwyg.html">WYSIWYG Editor</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="Mail">
                        <i class="icon-envelope"></i> Mail
                    </a>
                    <ul class="nav-sub">
                        <li><a href="mail-inbox.html">Inbox</a>
                        </li>
                        <li><a href="mail-compose.html">Compose Mail</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="Charts">
                        <i class="icon-bar-chart"></i> Charts
                    </a>
                    <ul class="nav-sub">
                        <li><a href="charts-chartjs.html">Chartjs</a>
                        </li>
                        <li><a href="charts-morris.html">Morris</a>
                        </li>
                        <li><a href="charts-c3.html">C3 Charts</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="Maps">
                        <i class="icon-pointer"></i> Maps
                    </a>
                    <ul class="nav-sub">
                        <li><a href="map-google.html">Google Map</a>
                        </li>
                        <li><a href="map-vector.html">Vector Map</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="typography.html" title="Typography">
                        <i class="icon-note"></i> Typography
                    </a>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="Pages">
                        <i class="icon-doc"></i> Pages
                    </a>
                    <ul class="nav-sub">
                        <li><a href="pages-blank.html">Blank Page</a>
                        </li>
                        <li><a href="pages-login.html">Login</a>
                        </li>
                        <li><a href="pages-sign-up.html">Sign Up</a>
                        </li>
                        <li><a href="pages-calendar.html">Calendar</a>
                        </li>
                        <li><a href="pages-timeline.html">Timeline</a>
                        </li>
                        <li><a href="pages-404.html">404</a>
                        </li>
                        <li><a href="pages-500.html">500</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a href="#" title="Menu Levels">
                        <i class="fa fa-folder-open-o"></i> Menu Levels
                    </a>
                    <ul class="nav-sub">
                        <li>
                            <a href="javascript:;" title="Level 2.1">
                                 <i class="icon-doc"></i> Level 1.1
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" title="Level 2.2">
                                <i class="icon-doc"></i> Level 1.2
                            </a>
                        </li>
                        <li class="nav-dropdown">
                            <a href="#" title="Level 2.3">
                                 <i class="fa fa-folder-open-o"></i> Level 1.3
                            </a>
                            <ul class="nav-sub">
                                <li>
                                    <a href="javascript:;" title="Level 3.1">
                                        <i class="icon-doc"></i> Level 2.1
                                    </a>
                                </li>
                                <li class="nav-dropdown">
                                    <a href="#" title="Level 3.2">
                                         <i class="fa fa-folder-open-o"></i> Level 2.2
                                    </a>
                                    <ul class="nav-sub">
                                        <li>
                                            <a href="javascript:;" title="Level 4.1">
                                                <i class="icon-doc"></i> Level 3.1
                                            </a>
                                        </li>
                                        <li class="nav-dropdown">
                                            <a href="#" title="Level 4.2">
                                                 <i class="fa fa-folder-open-o"></i> Level 3.2
                                            </a>
                                            <ul class="nav-sub">
                                                <li class="nav-dropdown">
                                                    <a href="#" title="Level 5.1">
                                                         <i class="fa fa-folder-open-o"></i> Level 4.1
                                                    </a>
                                                    <ul class="nav-sub">
                                                        <li>
                                                            <a href="javascript:;" title="Level 6.1">
                                                                 <i class="icon-doc"></i> Level 5.1
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:;" title="Level 6.2">
                                                                 <i class="icon-doc"></i> Level 5.2
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" title="Level 5.2">
                                                        <i class="icon-doc"></i> Level 4.2
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" title="Level 5.3">
                                                         <i class="icon-doc"></i> Level 4.3
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!--sidebar left end-->
        <!--main content start-->
        <section class="main-content-wrapper">
            <section id="main-content">
                <div class="row">
                    <div class="col-md-12">
                        <!--breadcrumbs start -->
                        <ul class="breadcrumb">
                            <li><a href="#">Dashboard</a>
                            </li>
                            <li>UI Elements</li>
                            <li class="active">Date Tables</li>
                        </ul>
                        <!--breadcrumbs end -->
                        <h1 class="h1">Date Tables</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Data Tables</h3>
                                <div class="actions pull-right">
                                    <i class="fa fa-chevron-down"></i>
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td>$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td>$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td>$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td>$162,700</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td>$372,000</td>
                                        </tr>
                                        <tr>
                                            <td>Herrod Chandler</td>
                                            <td>Sales Assistant</td>
                                            <td>San Francisco</td>
                                            <td>59</td>
                                            <td>2012/08/06</td>
                                            <td>$137,500</td>
                                        </tr>
                                        <tr>
                                            <td>Rhona Davidson</td>
                                            <td>Integration Specialist</td>
                                            <td>Tokyo</td>
                                            <td>55</td>
                                            <td>2010/10/14</td>
                                            <td>$327,900</td>
                                        </tr>
                                        <tr>
                                            <td>Colleen Hurst</td>
                                            <td>Javascript Developer</td>
                                            <td>San Francisco</td>
                                            <td>39</td>
                                            <td>2009/09/15</td>
                                            <td>$205,500</td>
                                        </tr>
                                        <tr>
                                            <td>Sonya Frost</td>
                                            <td>Software Engineer</td>
                                            <td>Edinburgh</td>
                                            <td>23</td>
                                            <td>2008/12/13</td>
                                            <td>$103,600</td>
                                        </tr>
                                        <tr>
                                            <td>Jena Gaines</td>
                                            <td>Office Manager</td>
                                            <td>London</td>
                                            <td>30</td>
                                            <td>2008/12/19</td>
                                            <td>$90,560</td>
                                        </tr>
                                        <tr>
                                            <td>Quinn Flynn</td>
                                            <td>Support Lead</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2013/03/03</td>
                                            <td>$342,000</td>
                                        </tr>
                                        <tr>
                                            <td>Charde Marshall</td>
                                            <td>Regional Director</td>
                                            <td>San Francisco</td>
                                            <td>36</td>
                                            <td>2008/10/16</td>
                                            <td>$470,600</td>
                                        </tr>
                                        <tr>
                                            <td>Haley Kennedy</td>
                                            <td>Senior Marketing Designer</td>
                                            <td>London</td>
                                            <td>43</td>
                                            <td>2012/12/18</td>
                                            <td>$313,500</td>
                                        </tr>
                                        <tr>
                                            <td>Tatyana Fitzpatrick</td>
                                            <td>Regional Director</td>
                                            <td>London</td>
                                            <td>19</td>
                                            <td>2010/03/17</td>
                                            <td>$385,750</td>
                                        </tr>
                                        <tr>
                                            <td>Michael Silva</td>
                                            <td>Marketing Designer</td>
                                            <td>London</td>
                                            <td>66</td>
                                            <td>2012/11/27</td>
                                            <td>$198,500</td>
                                        </tr>
                                        <tr>
                                            <td>Paul Byrd</td>
                                            <td>Chief Financial Officer (CFO)</td>
                                            <td>New York</td>
                                            <td>64</td>
                                            <td>2010/06/09</td>
                                            <td>$725,000</td>
                                        </tr>
                                        <tr>
                                            <td>Gloria Little</td>
                                            <td>Systems Administrator</td>
                                            <td>New York</td>
                                            <td>59</td>
                                            <td>2009/04/10</td>
                                            <td>$237,500</td>
                                        </tr>
                                        <tr>
                                            <td>Bradley Greer</td>
                                            <td>Software Engineer</td>
                                            <td>London</td>
                                            <td>41</td>
                                            <td>2012/10/13</td>
                                            <td>$132,000</td>
                                        </tr>
                                        <tr>
                                            <td>Dai Rios</td>
                                            <td>Personnel Lead</td>
                                            <td>Edinburgh</td>
                                            <td>35</td>
                                            <td>2012/09/26</td>
                                            <td>$217,500</td>
                                        </tr>
                                        <tr>
                                            <td>Jenette Caldwell</td>
                                            <td>Development Lead</td>
                                            <td>New York</td>
                                            <td>30</td>
                                            <td>2011/09/03</td>
                                            <td>$345,000</td>
                                        </tr>
                                        <tr>
                                            <td>Yuri Berry</td>
                                            <td>Chief Marketing Officer (CMO)</td>
                                            <td>New York</td>
                                            <td>40</td>
                                            <td>2009/06/25</td>
                                            <td>$675,000</td>
                                        </tr>
                                        <tr>
                                            <td>Caesar Vance</td>
                                            <td>Pre-Sales Support</td>
                                            <td>New York</td>
                                            <td>21</td>
                                            <td>2011/12/12</td>
                                            <td>$106,450</td>
                                        </tr>
                                        <tr>
                                            <td>Doris Wilder</td>
                                            <td>Sales Assistant</td>
                                            <td>Sidney</td>
                                            <td>23</td>
                                            <td>2010/09/20</td>
                                            <td>$85,600</td>
                                        </tr>
                                        <tr>
                                            <td>Angelica Ramos</td>
                                            <td>Chief Executive Officer (CEO)</td>
                                            <td>London</td>
                                            <td>47</td>
                                            <td>2009/10/09</td>
                                            <td>$1,200,000</td>
                                        </tr>
                                        <tr>
                                            <td>Gavin Joyce</td>
                                            <td>Developer</td>
                                            <td>Edinburgh</td>
                                            <td>42</td>
                                            <td>2010/12/22</td>
                                            <td>$92,575</td>
                                        </tr>
                                        <tr>
                                            <td>Jennifer Chang</td>
                                            <td>Regional Director</td>
                                            <td>Singapore</td>
                                            <td>28</td>
                                            <td>2010/11/14</td>
                                            <td>$357,650</td>
                                        </tr>
                                        <tr>
                                            <td>Brenden Wagner</td>
                                            <td>Software Engineer</td>
                                            <td>San Francisco</td>
                                            <td>28</td>
                                            <td>2011/06/07</td>
                                            <td>$206,850</td>
                                        </tr>
                                        <tr>
                                            <td>Fiona Green</td>
                                            <td>Chief Operating Officer (COO)</td>
                                            <td>San Francisco</td>
                                            <td>48</td>
                                            <td>2010/03/11</td>
                                            <td>$850,000</td>
                                        </tr>
                                        <tr>
                                            <td>Shou Itou</td>
                                            <td>Regional Marketing</td>
                                            <td>Tokyo</td>
                                            <td>20</td>
                                            <td>2011/08/14</td>
                                            <td>$163,000</td>
                                        </tr>
                                        <tr>
                                            <td>Michelle House</td>
                                            <td>Integration Specialist</td>
                                            <td>Sidney</td>
                                            <td>37</td>
                                            <td>2011/06/02</td>
                                            <td>$95,400</td>
                                        </tr>
                                        <tr>
                                            <td>Suki Burks</td>
                                            <td>Developer</td>
                                            <td>London</td>
                                            <td>53</td>
                                            <td>2009/10/22</td>
                                            <td>$114,500</td>
                                        </tr>
                                        <tr>
                                            <td>Prescott Bartlett</td>
                                            <td>Technical Author</td>
                                            <td>London</td>
                                            <td>27</td>
                                            <td>2011/05/07</td>
                                            <td>$145,000</td>
                                        </tr>
                                        <tr>
                                            <td>Gavin Cortez</td>
                                            <td>Team Leader</td>
                                            <td>San Francisco</td>
                                            <td>22</td>
                                            <td>2008/10/26</td>
                                            <td>$235,500</td>
                                        </tr>
                                        <tr>
                                            <td>Martena Mccray</td>
                                            <td>Post-Sales support</td>
                                            <td>Edinburgh</td>
                                            <td>46</td>
                                            <td>2011/03/09</td>
                                            <td>$324,050</td>
                                        </tr>
                                        <tr>
                                            <td>Unity Butler</td>
                                            <td>Marketing Designer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009/12/09</td>
                                            <td>$85,675</td>
                                        </tr>
                                        <tr>
                                            <td>Howard Hatfield</td>
                                            <td>Office Manager</td>
                                            <td>San Francisco</td>
                                            <td>51</td>
                                            <td>2008/12/16</td>
                                            <td>$164,500</td>
                                        </tr>
                                        <tr>
                                            <td>Hope Fuentes</td>
                                            <td>Secretary</td>
                                            <td>San Francisco</td>
                                            <td>41</td>
                                            <td>2010/02/12</td>
                                            <td>$109,850</td>
                                        </tr>
                                        <tr>
                                            <td>Vivian Harrell</td>
                                            <td>Financial Controller</td>
                                            <td>San Francisco</td>
                                            <td>62</td>
                                            <td>2009/02/14</td>
                                            <td>$452,500</td>
                                        </tr>
                                        <tr>
                                            <td>Timothy Mooney</td>
                                            <td>Office Manager</td>
                                            <td>London</td>
                                            <td>37</td>
                                            <td>2008/12/11</td>
                                            <td>$136,200</td>
                                        </tr>
                                        <tr>
                                            <td>Jackson Bradshaw</td>
                                            <td>Director</td>
                                            <td>New York</td>
                                            <td>65</td>
                                            <td>2008/09/26</td>
                                            <td>$645,750</td>
                                        </tr>
                                        <tr>
                                            <td>Olivia Liang</td>
                                            <td>Support Engineer</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2011/02/03</td>
                                            <td>$234,500</td>
                                        </tr>
                                        <tr>
                                            <td>Bruno Nash</td>
                                            <td>Software Engineer</td>
                                            <td>London</td>
                                            <td>38</td>
                                            <td>2011/05/03</td>
                                            <td>$163,500</td>
                                        </tr>
                                        <tr>
                                            <td>Sakura Yamamoto</td>
                                            <td>Support Engineer</td>
                                            <td>Tokyo</td>
                                            <td>37</td>
                                            <td>2009/08/19</td>
                                            <td>$139,575</td>
                                        </tr>
                                        <tr>
                                            <td>Thor Walton</td>
                                            <td>Developer</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2013/08/11</td>
                                            <td>$98,540</td>
                                        </tr>
                                        <tr>
                                            <td>Finn Camacho</td>
                                            <td>Support Engineer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009/07/07</td>
                                            <td>$87,500</td>
                                        </tr>
                                        <tr>
                                            <td>Serge Baldwin</td>
                                            <td>Data Coordinator</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2012/04/09</td>
                                            <td>$138,575</td>
                                        </tr>
                                        <tr>
                                            <td>Zenaida Frank</td>
                                            <td>Software Engineer</td>
                                            <td>New York</td>
                                            <td>63</td>
                                            <td>2010/01/04</td>
                                            <td>$125,250</td>
                                        </tr>
                                        <tr>
                                            <td>Zorita Serrano</td>
                                            <td>Software Engineer</td>
                                            <td>San Francisco</td>
                                            <td>56</td>
                                            <td>2012/06/01</td>
                                            <td>$115,000</td>
                                        </tr>
                                        <tr>
                                            <td>Jennifer Acosta</td>
                                            <td>Junior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>43</td>
                                            <td>2013/02/01</td>
                                            <td>$75,650</td>
                                        </tr>
                                        <tr>
                                            <td>Cara Stevens</td>
                                            <td>Sales Assistant</td>
                                            <td>New York</td>
                                            <td>46</td>
                                            <td>2011/12/06</td>
                                            <td>$145,600</td>
                                        </tr>
                                        <tr>
                                            <td>Hermione Butler</td>
                                            <td>Regional Director</td>
                                            <td>London</td>
                                            <td>47</td>
                                            <td>2011/03/21</td>
                                            <td>$356,250</td>
                                        </tr>
                                        <tr>
                                            <td>Lael Greer</td>
                                            <td>Systems Administrator</td>
                                            <td>London</td>
                                            <td>21</td>
                                            <td>2009/02/27</td>
                                            <td>$103,500</td>
                                        </tr>
                                        <tr>
                                            <td>Jonas Alexander</td>
                                            <td>Developer</td>
                                            <td>San Francisco</td>
                                            <td>30</td>
                                            <td>2010/07/14</td>
                                            <td>$86,500</td>
                                        </tr>
                                        <tr>
                                            <td>Shad Decker</td>
                                            <td>Regional Director</td>
                                            <td>Edinburgh</td>
                                            <td>51</td>
                                            <td>2008/11/13</td>
                                            <td>$183,000</td>
                                        </tr>
                                        <tr>
                                            <td>Michael Bruce</td>
                                            <td>Javascript Developer</td>
                                            <td>Singapore</td>
                                            <td>29</td>
                                            <td>2011/06/27</td>
                                            <td>$183,000</td>
                                        </tr>
                                        <tr>
                                            <td>Donna Snider</td>
                                            <td>Customer Support</td>
                                            <td>New York</td>
                                            <td>27</td>
                                            <td>2011/01/25</td>
                                            <td>$112,000</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </section>
    </div>
    <!--main content end-->
    <!--sidebar right start-->
    <div class="sidebarRight">
        <div id="rightside-navigation">
            <div id="right-panel-tabs" role="tabpanel">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="active"><a data-target="#chat" data-toggle="tab" role="tab" data-toggle="tab" title="Chat"><i class="icon-users fa-lg"></i></a>
                    </li>
                    <li><a data-target="#settings" role="tab" data-toggle="tab" title="Settings"><i class="icon-settings fa-lg"></i></a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="chat">
                        <div class="heading">
                            <ul>
                                <li>
                                    <input type="text" class="search" placeholder="Search">
                                    <button type="submit" class="btn btn-sm btn-search"><i class="fa fa-search"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <h3 class="sidebar-title">online</h3>
                        <div class="list-contacts">
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>James Bagian</h4>
                                    <p>Los Angeles, CA</p>
                                </div>
                                <div class="item-status item-status-online"></div>
                            </a>
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar1.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>Jeffrey Ashby</h4>
                                    <p>New York, NY</p>
                                </div>
                                <div class="item-status item-status-online"></div>
                            </a>
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar2.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>John Douey</h4>
                                    <p>Dallas, TX</p>
                                </div>
                                <div class="item-status item-status-online"></div>
                            </a>
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar3.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>Ellen Baker</h4>
                                    <p>London</p>
                                </div>
                                <div class="item-status item-status-away"></div>
                            </a>
                        </div>

                        <h3 class="sidebar-title">offline</h3>
                        <div class="list-contacts">
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar4.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>Ivan Bella</h4>
                                    <p>Tokyo, Japan</p>
                                </div>
                                <div class="item-status"></div>
                            </a>
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar5.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>Gerald Carr</h4>
                                    <p>Seattle, WA</p>
                                </div>
                                <div class="item-status"></div>
                            </a>
                            <a href="javascript:void(0)" class="list-item">
                                <div class="list-item-image">
                                    <img src="assets/img/avatar6.gif" class="img-circle">
                                </div>
                                <div class="list-item-content">
                                    <h4>Viktor Gorbatko</h4>
                                    <p>Palo Alto, CA</p>
                                </div>
                                <div class="item-status"></div>
                            </a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="settings">
                        <ul class="setting-list">
                            <li>
                                <h3 class="sidebar-title">Account Settings</h3>
                            </li>
                            <li>
                                <h5>Share your status</h5>
                                <input type="checkbox" class="js-switch" checked />
                            </li>
                            <li>
                                Vivamus sagittis lacus vel augue laoreet rutrums.
                            </li>
                            <li>
                                <h5>Notifications</h5>
                                <input type="checkbox" class="js-switch" />
                            </li>
                            <li>
                                Vivamus sagittis lacus vel augue laoreet rutrums.
                            </li>
                            <li>
                                <h5>Vacation responder</h5>
                                <input type="checkbox" class="js-switch" checked />
                            </li>
                            <li>
                                Vivamus sagittis lacus vel augue laoreet rutrums.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--sidebar right end-->
    <!--Global JS-->    
    <?= $this->Html->script([
        '/assets/js/jquery-1.10.2.min.js',
        '/assets/plugins/bootstrap/js/bootstrap.min.js',
        '/assets/plugins/navgoco/jquery.navgoco.min.js',
        '/assets/plugins/waypoints/waypoints.min.js',
        '/assets/plugins/switchery/switchery.min.js',
        '/assets/js/application.js',
        /*Page Level JS*/
        '/assets/plugins/dataTables/js/jquery.dataTables.js',
        '/assets/plugins/dataTables/js/dataTables.bootstrap.js'        
        ]); ?>
    <script>
    $(document).ready(function() {
        $('#example').dataTable();
    });
    </script>
</body>

</html>
