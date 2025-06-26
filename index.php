<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "<script>
        sessionStorage.clear(); // Optional: Clean up any leftover browser session
        window.location.href = 'page-login.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/pace.css">
  
    <title>Starter Template for Unifato</title>
   
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="assets/vendors/material-icons/material-icons.css" rel="stylesheet" type="text/css">
    <link href="assets/vendors/mono-social-icons/monosocialiconsfont.css" rel="stylesheet" type="text/css">
    <link href="assets/vendors/feather-icons/feather.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    
    <script src="assets/js/modernizr.min.js"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="assets/js/pace.min.js"></script>
</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <nav class="navbar">
            <div class="container px-0 align-items-stretch">
                <!-- Logo Area -->
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">
                        <img class="logo-expand" alt="" src="assets/img/logo.png">
                        <img class="logo-collapse" alt="" src="assets/img/logo.png">
                    </a>
                </div>
                <!-- /.navbar-header -->
                <!-- Left Menu & Sidebar Toggle -->
                <ul class="nav navbar-nav">
                    <li class="sidebar-toggle dropdown"><a href="javascript:void(0)" class="ripple"><i class="material-icons list-icon md-24">menu</i></a>
                    </li>
                </ul>
                <!-- /.navbar-left -->
                <!-- Search Form -->
                <form class="navbar-search d-none d-sm-block" role="search"><i class="material-icons list-icon">search</i> 
                    <input type="search" class="search-query" placeholder="Search anything..."> <a href="javascript:void(0);" class="remove-focus"><i class="material-icons md-24">close</i></a>
                </form>
                <!-- /.navbar-search -->
                <div class="spacer"></div>
                <!-- Right Menu -->
                <ul class="nav navbar-nav d-none d-lg-flex ml-2 ml-0-rtl">
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons list-icon">notifications_none</i> <span class="button-pulse bg-danger"></span></a>
                        <div class="dropdown-menu dropdown-left dropdown-card animated flipInY">
                            <div class="card">
                                <header class="card-header d-flex justify-content-between mb-0"><a href="javascript:void(0);"><i class="material-icons color-primary" aria-hidden="true">notifications_none</i></a>  <span class="heading-font-family flex-1 text-center fw-400">Notifications</span> 
                                    <a href="javascript:void(0);"><i class="material-icons color-content">settings</i>
                                    </a>
                                </header>
                                <ul class="card-body list-unstyled dropdown-list-group">
                                    <li><a href="#" class="media"><span class="d-flex"><i class="material-icons list-icon">check</i> </span><span class="media-body"><span class="heading-font-family media-heading">Invitation accepted</span> <span class="media-content">Your have been Invited ...</span></span></a>
                                    </li>
                                    <li><a href="#" class="media"><span class="d-flex thumb-xs user--online"><img src="assets/demo/users/user3.jpg" class="rounded-circle" alt=""> </span><span class="media-body"><span class="heading-font-family media-heading">Steve Smith</span> <span class="media-content">I slowly updated projects</span></span></a>
                                    </li>
                                    <li><a href="#" class="media"><span class="d-flex"><i class="material-icons list-icon">event_available</i> </span><span class="media-body"><span class="-heading-font-family media-heading">To Do</span> <span class="media-content">Meeting with Nathan on Friday 8 AM ...</span></span></a>
                                    </li>
                                </ul>
                                <!-- /.dropdown-list-group -->
                                <footer class="card-footer text-center"><a href="javascript:void(0);" class="headings-font-family text-uppercase fs-13">See all activity</a>
                                </footer>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.dropdown-menu -->
                    </li>
                    <!-- /.dropdown -->
                    <li><a href="javascript:void(0);" class="right-sidebar-toggle"><i class="material-icons list-icon">border_all</i></a>
                    </li>
                </ul>
                <!-- /.navbar-right -->
                <!-- User Image with Dropdown -->
                <ul class="nav navbar-nav">
                    <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle dropdown-toggle-user ripple" data-toggle="dropdown"><span class="avatar thumb-xs2"><img src="assets/demo/users/user1.jpg" class="rounded-circle" alt=""> <i class="material-icons list-icon">expand_more</i></span></a>
                        <div
                        class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
                            <div class="card">
                                <header class="card-header d-flex mb-0"><a href="javascript:void(0);" class="col-md-4 text-center"><i class="material-icons md-24 align-middle">person_add</i> </a><a href="javascript:void(0);" class="col-md-4 text-center"><i class="material-icons md-24 align-middle">settings</i> </a>
                                    <a
                                    href="javascript:void(0);" class="col-md-4 text-center"><i class="material-icons md-24 align-middle">power_settings_new</i>
                                        </a>
                                </header>
                                <ul class="list-unstyled card-body">
                                    <li><a href="#"><span><span class="align-middle">Manage Accounts</span></span></a>
                                    </li>
                                    <li><a href="#"><span><span class="align-middle">Change Password</span></span></a>
                                    </li>
                                    <li><a href="#"><span><span class="align-middle">Check Inbox</span></span></a>
                                    </li>
                                    <li><a href="logout.php"><span><span class="align-middle">Sign Out</span></span></a></li>

                                    </li>
                                </ul>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
            </div>
            <!-- /.dropdown-card-profile -->
            </li>
            <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-nav -->
    </div>
    <!-- /.container -->
    </nav>
    <!-- /.navbar -->
    <div class="content-wrapper">
        <!-- SIDEBAR -->
        <aside class="site-sidebar clearfix">
            <div class="container">
                <nav class="sidebar-nav">
                   <ul class="nav in side-menu">
                             <li><a href="index.php"><i class="list-icon material-icons">home</i> <span class="hide-menu">Dashboard</span></a></li>

                             <li class="menu-item-has-children">
                              <a href="javascript:void(0);"><i class="list-icon material-icons">local_hospital</i> <span class="hide-menu">Doctors</span></a>
                                <ul class="list-unstyled sub-menu">
                              <li><a href="doctors-list.php">Doctors List</a></li>
                                  <li><a href="add-doctor.php">Add Doctor</a></li>
                                     </ul>
                                     </li>

                                     <li class="menu-item-has-children">
                                      <a href="javascript:void(0);"><i class="list-icon material-icons">people</i> <span class="hide-menu">Patients</span></a>
                                     <ul class="list-unstyled sub-menu">
                                       <li><a href="patient-list.php">Patients List</a></li>
                                            <li><a href="add-patient.php">Add Patient</a></li>
                                            </ul>
                                               </li>

                                                   <li class="menu-item-has-children">
                                                   <a href="javascript:void(0);"><i class="list-icon material-icons">event</i> <span class="hide-menu">Appointments</span></a>
                                                   <ul class="list-unstyled sub-menu">
                                                        <li><a href="appointments.php">All Appointments</a></li>
                                                            <li><a href="add-appointment.php">Add Appointment</a></li>
                                                                </ul>
                     </li>
                  
    <!--/ #wrapper -->
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>