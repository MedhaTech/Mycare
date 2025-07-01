<?php
include 'dbconnection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM patients WHERE id = $id");

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Patient not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
