<?php include 'header.php'; ?>
<?php
include 'dbconnection.php';
include 'init.php';

session_start();
$_SESSION['id'] = 1; // Simulate user login for testing

$currentError = $newError = $confirmError = "";
$currentPassword = $newPassword = $confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    $valid = true;

    // Check empty fields
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
        // Fetch current password from DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        $stmt->close();

        // Validate current password
        if ($currentPassword !== $dbPassword) {
            $currentError = "Current password is incorrect.";
            $valid = false;
        }

        // Check if new password is same as current
        if ($newPassword === $currentPassword) {
            $newError = "New password must be different from current.";
            $valid = false;
        }

        // Check if confirm password matches
        if ($newPassword !== $confirmPassword) {
            $confirmError = "Passwords do not match.";
            $valid = false;
        }

        // Update password if all is valid
        if ($valid) {
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $newPassword, $userId);
            if ($update->execute()) {
                header("Location: header.php");
                exit;
            } else {
                $currentError = "Something went wrong while updating.";
            }
            $update->close();
        }
    }
}
?>
<div class="container-fluid">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Change Password</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right d-none d-sm-inline-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Change Password</li>
                        </ol>
                    </div>
                    <!-- /.page-title-right -->
                </div>
                <!-- /.page-title -->
            </div>
<div class="col-md-12 widget-holder">
    <div class="widget-bg">
        <div class="widget-body clearfix">
            <h5 class="box-title mr-b-0">Reset Password</h5>
            <p class="text-muted">A strong password helps prevent unauthorized access to your account</p>
            <div class="row">
                <div class="col-md-6">
                    <form class="mr-t-30" method="POST" action="">
                        <div class="form-group row">
                            <label class="text-sm-center col-sm-6 col-form-label">Current Password</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="material-icons">lock</i></div>
                                    </div>
                                    <input type="password" class="form-control" name="current_password" placeholder="Current Password">
                                </div>
                                <small class="text-danger"><?php echo $currentError; ?></small>
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
                                <small class="text-danger"><?php echo $newError; ?></small>
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
                                <small class="text-danger"><?php echo $confirmError; ?></small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="form-group row">
                                <div class="col-sm-9 ml-auto btn-list">
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                    <a href="header.php" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <img src="https://amjmed.org/wp-content/uploads/2016/02/heart-stethoscope-stock.jpg"
                         alt="Reset Password Illustration"
                         style="max-height: 60%; display: block; margin: 0 auto;" />
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
