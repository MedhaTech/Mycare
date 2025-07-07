<?php
include 'dbconnection.php';
include 'init.php';

$term = isset($_GET['term']) ? $conn->real_escape_string($_GET['term']) : '';
$sql = "SELECT id, name FROM doctors WHERE status = 'Active' AND name LIKE '%$term%' LIMIT 10";
$result = $conn->query($sql);

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = [
        'id' => $row['id'],
        'text' => $row['name']
    ];
}

echo json_encode(['results' => $doctors]);
?>
