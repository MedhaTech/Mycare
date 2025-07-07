<?php
session_start();
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    $id = $_POST['id'];
    $reason = trim($_POST['cancel_reason']);

    if (empty($reason)) {
        $_SESSION['error'] = "Cancellation reason is required.";
        header("Location: appointments.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE appointments SET status = 'CANCELLED', cancel_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment cancelled successfully.";
    } else {
        $_SESSION['error'] = "Failed to cancel appointment: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: appointments.php");
exit();
