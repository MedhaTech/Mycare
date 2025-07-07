<?php
session_start();
include 'dbconnection.php';

if (isset($_POST['cancel'])) {
    $id = $_POST['id'];
    $reason = $_POST['cancellation_reason'];

    $sql = "UPDATE procedures SET status='CANCELLED', cancellation_reason=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $reason, $id);

    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Procedure cancelled successfully.";
    } else {
        $_SESSION['toast_error'] = "Failed to cancel procedure.";
    }

    header("Location: procedure.php"); // 👈 redirect to the parent page
    exit();
}
?>
