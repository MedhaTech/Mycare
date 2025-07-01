<?php
include 'dbconnection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'], $_POST['id'])) {
    $appointmentId = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: appointments.php?deleted=1");
        exit();
    } else {
        echo "<script>alert('❌ Failed to delete appointment'); window.location.href='appointments.php';</script>";
    }

    $stmt->close();
} else {
    // Invalid access
    header("Location: appointments.php");
    exit();
}

$conn->close();
?>
