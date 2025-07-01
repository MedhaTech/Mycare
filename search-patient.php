<?php
include 'dbconnection.php';
include 'init.php';
$q = $_GET['q'] ?? '';
$response = [];

if ($q !== '') {
    $stmt = $conn->prepare("SELECT id, name, phone, gender, dob FROM patients WHERE name LIKE ? OR phone LIKE ?");
    $like = "%$q%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($response);
