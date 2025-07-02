<?php
include 'dbconnection.php';
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT id FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    echo $stmt->num_rows > 0 ? 'exists' : 'not_found';
}
?>
