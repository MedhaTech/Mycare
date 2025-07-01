<?php
include 'dbconnection.php';
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['status'])) {
        $id = intval($_POST['id']);
        $status = $_POST['status'];

        // Optional: Validate allowed statuses
        $allowed_statuses = ['Scheduled', 'Tentative', 'Waitlist', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'];
        if (!in_array($status, $allowed_statuses)) {
            die("Invalid status value.");
        }

        // Update query
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            header("Location: appointments.php?updated=1");
            exit();
        } else {
            echo "❌ Error updating status: " . $conn->error;
        }
    } else {
        echo "Missing appointment ID or status.";
    }
} else {
    echo "❌ Invalid request method. Use POST.";
}
?>
