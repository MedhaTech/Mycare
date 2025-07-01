<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'dbconnection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Doctor deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete doctor: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

$conn->close();
header("Location: doctors-list.php");
exit();
