<?php 
session_start();

$dt=new DateTime('now', new DateTimezone('Africa/Accra'));
$ladate = $dt->format('Y-m-d');
$lamonth = $dt->format('m');
$latodayz = $ladate;
$ladatez = $dt->format('Y-m-d H:i:s');
$latimez = $dt->format('H:i:s');
$ladate_full=$dt->format("D M j Y");


include '../apis/config/Database.php';
include '../apis/models/Exam_Results.php';

//instantiate database and connect
$database=new Database();
$app_con=$database->connect('app_db');
//instantiate access object and assign the connection to it
$my_results=new Exam_Results();

if(isset($_SESSION['student_id'] ) ) 
{


    $all_results="";
    $my_results->ID=$_SESSION['student_id'];
    $get_my_results=$my_results->get_all_my_results($app_con);
    
    
} 
else    
{
  $_SESSION['backend_message']='Access Required To Visit Page';
  $_SESSION['message_type']='warning';
  header("location: ../");
  return;
}

?>


<!DOCTYPE html>
<html>

<head>
    <!-- Meta and Title -->
    <meta charset="utf-8">
    <title>SQL INJECTION TUTORIAL</title>
    <meta name="keywords" content="HTML5, Bootstrap 3, Admin Template, UI Theme"/>
    <meta name="description" content="AdminK - A Responsive HTML5 Admin UI Framework">
    <meta name="author" content="ThemeREX">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon_io/favicon.ico">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon_io/favicon.ico">
    <link rel="icon" type="image/ico" href="../assets/img/favicon_io/favicon.ico">

    <!-- Angular material -->
    <link rel="stylesheet" type="text/css" href="assets/skin/css/angular-material.min.css">
    
    <!-- Icomoon -->
    <link rel="stylesheet" type="text/css" href="assets/fonts/icomoon/icomoon.css">    
    
    <!-- AnimatedSVGIcons -->
    <link rel="stylesheet" type="text/css" href="assets/fonts/animatedsvgicons/css/codropsicons.css">


    <!-- Plugins -->
    <link rel="stylesheet" type="text/css" href="assets/js/utility/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.min.css">

    <!-- CSS - theme -->
    <link rel="stylesheet" type="text/css" href="assets/skin/default_skin/less/theme.css">
    
    <!-- IE8 HTML5 support -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="sb-top sb-top-sm">

<!-- Body Wrap -->
<div id="main">

        <!-- Header  -->
    <header class="navbar navbar-fixed-top bg-info phn">
        <div class="navbar-logo-wrapper">
            <a class="navbar-logo-img" href="index.html">
                <img src="../assets/img/logo_sm.png" alt="" style="width: 50%;height:50%;">
            </a>
        </div>
        <span id="sidebar_top_toggle" class="ad ad-lines navbar-nav navbar-left showing-sm"></span>
        <ul class="nav navbar-nav navbar-left">
            
            <li class="hidden-xs">
                <div class="navbar-btn btn-group">
                    <button class="btn-hover-effects navbar-fullscreen toggle-active btn si-icons si-icons-default">
                        <span class="si-icon si-icon-maximize-rotate default" data-icon-name="maximizeRotate"></span>
                    </button>
                </div>
            </li>
        </ul>
        
        <ul class="nav navbar-nav navbar-right bg-info darker mn pv10">
            <li class="dropdown dropdown-fuse navbar-user">
                <a href="#" class="dropdown-toggle mln" data-toggle="dropdown">
                    <img src="assets/img/avatars/profile_avatar.jpg" alt="avatar">
                    <span class="hidden-xs">
                        <span class="name"><?php echo $_SESSION['student_name']; ?></span>
                    </span>
                    <span class="fa fa-caret-down hidden-xs"></span>
                </a>
                <ul class="dropdown-menu list-group keep-dropdown w230" role="menu">
                    
                    <li class="dropdown-footer text-center">
                        <a href="#" class="btn btn-warning">
                            logout 
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </header>
    <!-- /Header -->

        <!-- Sidebar  -->
    <aside id="sidebar_left" class="">

        <!-- Sidebar Left Wrapper  -->
        <div class="sidebar-left-content nano-content">

            <!-- Sidebar Menu  -->
            
            <!-- /Sidebar Menu  -->

        </div>
        <!-- /Sidebar Left Wrapper  -->

    </aside>
    <!-- /Sidebar -->
    <!-- Main Wrapper -->
    <section id="content_wrapper">

        <section class="content_container">

                    <!-- Topbar Menu Wrapper -->
        <div id="topbar-dropmenu-wrapper">
            <div class="topbar-menu row">
                
            </div>
        </div>
        <!-- /Topbar Menu Wrapper -->
            
                    <!-- Topbar -->
        <header id="topbar" class="breadcrumb_style_2">
            <div class="topbar-left">
                <ol class="breadcrumb">
                    <li class="breadcrumb-icon breadcrumb-active">
                        <a href="index.php">
                            <span class="fa fa-circle-o"></span>
                        </a>
                    </li>
                    <li class="breadcrumb-icon breadcrumb-link">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-current-item">Exam Results</li>
                </ol>
            </div>
            <div class="topbar-right">
                <div class="ib topbar-dropdown">
                    <label for="topbar-multiple" class="control-label">Date Generated: <?php echo date("F j, Y, g:i a"); ?></label>
                    
                </div>
                <div class="ml15 ib va-m" id="sidebar_right_toggle">
                    <div class="navbar-btn btn-group btn-group-number mv0">
                        <button class="btn btn-sm prn pln">
                            <i class="fa fa-bar-chart fs22 text-default"></i>
                        </button>

                    </div>
                </div>
            </div>
        </header>
        <!-- /Topbar -->



            <!-- Content -->
            <section id="content" class="animated fadeIn">

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <span class="panel-icon"></span>
                                <h2><center><?php echo $_SESSION['student_name']; ?></center></h2>
                                <h3><center><?php echo $_SESSION['student_id']; ?></center></h3>
                                <p><center><?php echo $_SESSION['student_programme']; ?></center></p>
                            </div>
                            <div class="panel-body">
                                                <div class="panel" id="spy9">
                                                    <?php 

                                                        if ($get_my_results AND (($get_my_results->num_rows)>0) ) 
                                                        {
                                                            $totalrecords=$get_my_results->num_rows;
                                                            $count=0;
                                                            $current_year="";
                                                            while ($row = $get_my_results->fetch_assoc()) 
                                                            {
                                                                if ((!($row['course_year']==$current_year)) AND $count>0) 
                                                                {
                                                                    $count=0;
                                                                    $current_year=$row['course_year'];

                                                                    ?>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                }

                                                                switch (true) 
                                                                {

                                                                    case ( $count==0 ):
                                                                            $current_year=$row['course_year'];
                                                                        ?>
                                                                            <div class="panel-heading">

                                                                                <div class="text-center hidden-xs">
                                                                                    <code class="mr20"><?php echo $row['course_year']; ?></code>
                                                                                </div>
                                                                            </div>
                                                                            <div class="panel-body pn">
                                                                                <div class="bs-component">
                                                                                    <table class="table table-hover mbn">
                                                                                        <thead>
                                                                                        <tr class="active">
                                                                                            <th>#</th>
                                                                                            <th>Course</th>
                                                                                            <th>Cummulative Assessment</th>
                                                                                            <th>Exam Score</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <tr class="primary">
                                                                                                <td><?php echo $count+1; ?></td>
                                                                                                <td><?php echo $row['course_code'].":".$row['course_name']; ?></td>
                                                                                                <td><?php echo $row['cummulative_Assessment']; ?></td>
                                                                                                <td><?php echo $row['exams_score']; ?></td>
                                                                                            </tr>
                                                                        <?php

                                                                    break;
                                                                    
                                                                    default:
                                                                        
                                                                        ?>
                                                                            <tr class="primary">
                                                                                <td><?php echo $count+1; ?></td>
                                                                                <td><?php echo $row['course_code'].":".$row['course_name']; ?></td>
                                                                                <td><?php echo $row['cummulative_Assessment']; ?></td>
                                                                                <td><?php echo $row['exams_score']; ?></td>
                                                                            </tr>
                                                                        <?php

                                                                    break;
                                                                }
                                                                
                                                                $count++;
                                                            }
                                                        }


                                                    ?>
                                                    
                                                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>     
                               
                                                            
                                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>


            </section>
            <!-- /Content -->

        </section>

    </section>

</div>
<!-- /Body Wrap  -->



<!-- Scripts -->

<!-- jQuery -->
<script src="assets/js/jquery/jquery-1.12.3.min.js"></script>
<script src="assets/js/jquery/jquery_ui/jquery-ui.min.js"></script>

<!-- AnimatedSVGIcons -->
<script src="assets/fonts/animatedsvgicons/js/snap.svg-min.js"></script>
<script src="assets/fonts/animatedsvgicons/js/svgicons-config.js"></script>
<script src="assets/fonts/animatedsvgicons/js/svgicons.js"></script>
<script src="assets/fonts/animatedsvgicons/js/svgicons-init.js"></script>

<!-- HighCharts Plugin -->
<script src="assets/js/plugins/highcharts/highcharts.js"></script>

<!-- Scroll -->
<script src="assets/js/utility/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- Theme Scripts -->
<script src="assets/js/utility/utility.js"></script>
<script src="assets/js/demo/demo.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/demo/widgets_sidebar.js"></script>
<script src="assets/js/pages/dashboard_init.js"></script>


<!-- <script src="../assets/libraries/jquery-3.4.0.min.js"></script> -->
<script src="../assets/libraries/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="../assets/libraries/jquery.toaster.js"></script>
<script src="../assets/libraries/kingslibrary.js?v=<?php echo $ladate; ?>"></script>


<!-- /Scripts -->

</body>

</html>