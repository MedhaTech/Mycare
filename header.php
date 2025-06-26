<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: page-login.php");
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
    <title>Doctors List</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet">
    <link href="assets/vendors/material-icons/material-icons.css" rel="stylesheet">
    <link href="assets/vendors/mono-social-icons/monosocialiconsfont.css" rel="stylesheet">
    <link href="assets/vendors/feather-icons/feather.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="assets/js/modernizr.min.js"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="assets/js/pace.min.js"></script>
</head>

<body class="sidebar-horizontal">
<div id="wrapper" class="wrapper">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container px-0 align-items-stretch">
        <div class="navbar-header">
            <a href="index.php" class="navbar-brand">
                <img class="logo-expand" alt="" src="assets/img/logo.png">
                <img class="logo-collapse" alt="" src="assets/img/logo.png">
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li class="sidebar-toggle dropdown"><a href="javascript:void(0)" class="ripple"><i class="material-icons list-icon md-24">menu</i></a></li>
        </ul>
        <form class="navbar-search d-none d-sm-block" role="search"><i class="material-icons list-icon">search</i>
            <input type="search" class="search-query" placeholder="Search anything...">
            <a href="javascript:void(0);" class="remove-focus"><i class="material-icons md-24">close</i></a>
        </form>
        <div class="spacer"></div>
        <ul class="nav navbar-nav d-none d-lg-flex ml-2 ml-0-rtl">
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons list-icon">notifications_none</i><span class="button-pulse bg-danger"></span></a></li>
            <li><a href="javascript:void(0);" class="right-sidebar-toggle"><i class="material-icons list-icon">border_all</i></a></li>
        </ul>
        <ul class="nav navbar-nav">
            <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle dropdown-toggle-user ripple" data-toggle="dropdown"><span class="avatar thumb-xs2"><img src="assets/demo/users/user1.jpg" class="rounded-circle" alt=""><i class="material-icons list-icon">expand_more</i></span></a></li>
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
                    <li class="menu-item-has-children">
                        <a href="#"><i class="list-icon material-icons">local_hospital</i><span class="hide-menu">Doctors</span></a>
                        <ul class="list-unstyled sub-menu">
                            <li><a href="doctors-list.php">Doctors List</a></li>
                            <li><a href="add-doctor.php">Add Doctor</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#"><i class="list-icon material-icons">people</i><span class="hide-menu">Patients</span></a>
                        <ul class="list-unstyled sub-menu">
                            <li><a href="patient-list.php">Patients List</a></li>
                            <li><a href="add-patient.php">Add Patient</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#"><i class="list-icon material-icons">event</i><span class="hide-menu">Appointments</span></a>
                        <ul class="list-unstyled sub-menu">
                            <li><a href="appointments.php">All Appointments</a></li>
                            <li><a href="add-appointment.php">Add Appointment</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
