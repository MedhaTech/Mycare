<?php
include 'init.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'login.php' && !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/mycareicon.png">
    <title>MyCare</title>

    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="assets/vendors/material-icons/material-icons.css" rel="stylesheet" type="text/css">
    <link href="assets/vendors/mono-social-icons/monosocialiconsfont.css" rel="stylesheet" type="text/css">
    <link href="assets/vendors/feather-icons/feather.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <!-- JS -->
    <script src="assets/js/modernizr.min.js"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="assets/js/pace.min.js"></script>

    <style>
        .dropdown-menu {
            min-width: 220px;
            max-width: 240px;
            padding: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .dropdown-item {
            font-size: 14px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .dropdown-item i.material-icons {
            font-size: 20px;
            color: #444;
            width: 24px;
            /* fixes spacing, makes sure icon doesn't collapse */
            text-align: center;
            margin-right: 8px;
        }

        .dropdown-item span {
            flex-grow: 1;
            color: #333;
        }

        .modal-content {
            border-radius: 8px;
        }

        .modal-title {
            font-size: 1.4rem;
        }

        .modal-footer .btn {
            min-width: 100px;
        }
    </style>


</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">

        <!-- NAVBAR -->
        <nav class="navbar">
            <div class="container px-0 align-items-stretch">

                <!-- Logo -->
                <div class="navbar-header">
                    <a href="index.php" class="navbar-brand d-flex align-items-center">
                        <img src="assets/img/mycare-light.png" alt="MyCare Logo" style="max-height: 100px;">
                    </a>
                </div>

                <!-- Sidebar Toggle -->
                <ul class="nav navbar-nav">
                    <li class="sidebar-toggle dropdown">
                        <a href="javascript:void(0)" class="ripple">
                            <i class="material-icons list-icon md-24">menu</i>
                        </a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <form class="navbar-search d-none d-sm-block" role="search">
                    <i class="material-icons list-icon">search</i>
                    <input type="search" class="search-query" placeholder="Search anything...">
                    <a href="javascript:void(0);" class="remove-focus"><i class="material-icons md-24">close</i></a>
                </form>

                <div class="spacer"></div>

                <ul class="nav navbar-nav d-none d-lg-flex align-items-center ml-2 ml-0-rtl">
                    <!-- Notification Icon -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        </a>
                        <div class="dropdown-menu dropdown-left dropdown-card animated flipInY">
                            <!-- (notification dropdown content...) -->
                        </div>
                    </li>

                    <!-- Appointment Icon (Centered) -->
                    <li class="mx-3">
                        <a href="add-appointment.php" class="nav-link">
                            <i class="material-icons list-icon">event</i>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                            role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="assets/img/avatar.jpg" class="rounded-circle" width="36" height="36" alt="User Avatar">
                            <i class="material-icons ml-1">expand_more</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated flipInY" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="manage-account.php">
                                <i class="material-icons">manage_accounts</i><span>Manage Account</span>
                            </a>
                            <a class="dropdown-item" href="change-password.php">
                                <i class="material-icons">lock</i><span>Change Password</span>
                            </a>
                            <a class="dropdown-item" href="logout.php">
                                <i class="material-icons">logout</i><span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>



            </div>
        </nav>

        <!-- SIDEBAR -->
        <div class="content-wrapper">
            <aside class="site-sidebar clearfix">
                <div class="container">
                    <nav class="sidebar-nav">
                        <ul class="nav in side-menu">
                            <li><a href="index.php"><i class="list-icon material-icons">home</i><span class="hide-menu">Dashboard</span></a></li>
                            <li><a href="doctors-list.php"><i class="list-icon material-icons">local_hospital</i><span class="hide-menu">Doctors</span></a></li>
                            <li><a href="patient-list.php"><i class="list-icon material-icons">people</i><span class="hide-menu">Patients</span></a></li>
                            <li><a href="appointments.php"><i class="list-icon material-icons">event</i><span class="hide-menu">Appointments</span></a></li>
                            <li><a href="expenses-list.php"><i class="list-icon material-icons">account_balance_wallet</i><span class="hide-menu">Expenses</span></a></li>

                        </ul>
                    </nav>
                </div>
            </aside>
        </div>

    </div>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> <!-- ✅ 4. DataTables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/custom.js"></script>

</body>

</html>