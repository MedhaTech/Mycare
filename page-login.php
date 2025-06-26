<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $conn = new mysqli("192.185.129.71", "medha_mycare", "peO*aDq0=Hb&", "medha_mycare");

    
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['email'] = $email; 
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid login');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/demo/favicon.png">
    <title>Login - MyCare</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <link href="assets/vendors/material-icons/material-icons.css" rel="stylesheet">
    <link href="assets/vendors/mono-social-icons/monosocialiconsfont.css" rel="stylesheet">
    <link href="assets/vendors/feather-icons/feather.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Fix for checkbox visibility -->
    <style>
        input[type="checkbox"] {
            appearance: auto;
            -webkit-appearance: auto;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>

<body class="body-bg-full profile-page" style="background-image: url(assets/img/site-bg.jpg)">
    <div id="wrapper" class="row wrapper">
        <div class="container-min-full-height d-flex justify-content-center align-items-center">
            <div class="login-center">

                <!-- Logo and Title -->
                <div class="navbar-header text-center mt-2 mb-4">
                    <a href="index.php">
                        <img alt="MyCare Logo" src="assets/img/logo.png" style="max-height: 50px;">
                    </a>
                    <h2 class="mt-3 text-white">MyCare</h2>
                </div>

                <!-- Login Form -->
                <form action="login.php" method="POST">
                    <h4 class="text-center mb-4">Sign in to your account</h4>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="name@clinic.com" class="form-control form-control-line" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="Password" id="password" class="form-control form-control-line" required>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center">
                        <div style="display: flex; align-items: center;">
                            <input type="checkbox" id="remember">
                            <label for="remember" style="margin: 0;">Remember me</label>
                        </div>
                        <a href="#" class="text-right"><i class="material-icons mr-2 fs-18"></i>Forgot Password?</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-lg btn-primary text-uppercase fs-12 fw-600">Sign In</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="assets/js/material-design.js"></script>
</body>

</html>
