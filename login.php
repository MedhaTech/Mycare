<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnection.php';

    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $login_error = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MyCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('assets/img/site-bg.jpg');
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-box {
            max-width: 400px;
            margin: 6% auto;
            background: rgba(255, 255, 255, 0.96);
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .login-box img {
            display: block;
            margin: 0 auto 10px;
            max-height: 80px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 10px 40px 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            font-size: 1.1em;
            cursor: pointer;
            color: #666;
        }

        .btn {
            font-weight: 500;
            padding: 10px 0;
        }

        .alert {
            font-size: 14px;
            padding: 8px 12px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <form method="POST" action="login.php">
        <img src="assets/img/mycare.png" alt="MyCare Logo" class="img-fluid">
        <h4 class="text-center mb-4">Sign in to MyCare</h4>

        <?php if (!empty($login_error)): ?>
            <div class="alert alert-danger"><?= $login_error ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="name@clinic.com" class="form-control" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter your password" class="form-control" id="password" required>
            <i class="fa fa-eye-slash toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>

<!-- Toggle Script -->
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function () {
        const isHidden = password.type === 'password';
        password.type = isHidden ? 'text' : 'password';
        toggle.classList.toggle('fa-eye');
        toggle.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
