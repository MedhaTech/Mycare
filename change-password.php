<?php
ob_start(); // Start output buffering to avoid header issues

include 'header.php'; // Must be at top
include 'dbconnection.php';
include 'init.php';

$success = "";
$error = "";

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

$_SESSION['id'] = 1; // Simulate logged-in user

$currentError = $newError = $confirmError = "";
$currentPassword = $newPassword = $confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    $valid = true;

    if (empty($currentPassword)) {
        $currentError = "Current password is required.";
        $valid = false;
    }
    if (empty($newPassword)) {
        $newError = "New password is required.";
        $valid = false;
    }
    if (empty($confirmPassword)) {
        $confirmError = "Please confirm the new password.";
        $valid = false;
    }

    if ($valid) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        $stmt->close();

        if ($currentPassword !== $dbPassword) {
            $currentError = "Current password is incorrect.";
            $valid = false;
        }

        if ($newPassword === $currentPassword) {
            $newError = "New password must be different from current.";
            $valid = false;
        }

        if ($newPassword !== $confirmPassword) {
            $confirmError = "Passwords do not match.";
            $valid = false;
        }

        if ($valid) {
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $newPassword, $userId);
            if ($update->execute()) {
               echo "<script>
            setTimeout(function() {
                $.toast({
                    heading: 'Success',
                    text: 'Password Changed Successfully',
                    showHideTransition: 'slide',
                    icon: 'success',
                    position: 'top-right'
                });
            }, 300);
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000);
        </script>";
    } else {
        echo "<script>
            setTimeout(function() {
                $.toast({
                    heading: 'Error',
                    text: 'Failed to change the password.',
                    showHideTransition: 'fade',
                    icon: 'error',
                    position: 'top-right'
                });
            }, 300);
        </script>";
    }

            $update->close();
        }
    }
}
?>

<!-- Title -->

<div class="container-fluid">

  <div class="row mb-3">
    <div class="col-md-10 offset-md-1 d-flex justify-content-between align-items-center">
      <h6 class="mb-0" style="font-weight: 600;">Change Password</h6>
      <nav>
        <ol class="breadcrumb mb-0 bg-transparent p-0">
          <li class="breadcrumb-item">Dashboard</li>
          <li class="breadcrumb-item active">Change Password</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<!-- Form -->
<div class="d-flex justify-content-center align-items-center">
    <div class="container widget-holder">
        <div class="widget-bg">
            <div class="widget-body clearfix">
                <h5 class="box-title mr-b-0">Reset Password</h5>
                <p class="text-muted">A strong password helps prevent unauthorized access to your account</p>
                <div class="row">
                    <!-- Form Column -->
                    <div class="col-md-6">
                        <form class="mr-t-30" method="POST">
                            <div class="form-group row">
                                <label class="text-sm-center col-sm-6 col-form-label">Current Password</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock</i></div>
                                        </div>
                                        <input type="password" class="form-control" name="current_password" placeholder="Current Password">
                                    </div>
                                    <small class="text-danger"><?= $currentError ?></small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="text-sm-center col-sm-6 col-form-label">New Password</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock</i></div>
                                        </div>
                                        <input type="password" class="form-control" name="new_password" placeholder="New Password">
                                    </div>
                                    <small class="text-danger"><?= $newError ?></small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="text-sm-center col-sm-6 col-form-label">Confirm New Password</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock</i></div>
                                        </div>
                                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                                    </div>
                                    <small class="text-danger"><?= $confirmError ?></small>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="form-group row">
                                    <div class="col-sm-9 ml-auto btn-list">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                        <a href="index.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Image Column -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <img src="https://amjmed.org/wp-content/uploads/2016/02/heart-stethoscope-stock.jpg"
                             alt="Reset Password Illustration"
                             class="img-fluid" style="max-height: 300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first (required for Toastr) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Toastr plugin -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>


<?php 
include 'footer.php'; 
ob_end_flush(); // Flush output buffer at end
?>
