<?php
include 'dbconnection.php';
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $reason = trim($_POST['cancellation_reason'] ?? '');

    if ($id && $reason) {
        $stmt = $conn->prepare("UPDATE procedures SET status = 'Cancelled', cancellation_reason = ? WHERE id = ?");
        $stmt->bind_param("si", $reason, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Procedure cancelled successfully.'); window.location.href='procedure.php';</script>";
        } else {
            echo "<script>alert('Error cancelling procedure.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Missing ID or reason.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
