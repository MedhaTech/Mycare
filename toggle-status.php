<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    include 'dbconnection.php';
    include 'init.php';

    $doctorId = $_POST['id'];

    // Fetch current status
    $stmt = $conn->prepare("SELECT status FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    // Toggle it
    $newStatus = ($currentStatus === 'Active') ? 'Inactive' : 'Active';

    $stmt = $conn->prepare("UPDATE doctors SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $doctorId);
    $stmt->execute();
    if ($stmt->execute()) {
        // Set success message for Toastr
        if ($newStatus === 'Active') {
            $_SESSION['success'] = "Doctor activated successfully!";
        } else {
            $_SESSION['success'] = "Doctor inactivated successfully!";
        }
    } else {
        // Error message
        $_SESSION['error'] = "Failed to update doctor status.";
    }
    $stmt->close();

    $conn->close();
}

header("Location: doctors-list.php");
exit();
