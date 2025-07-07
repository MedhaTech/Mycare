<?php
include 'dbconnection.php';

$q = $_GET['q'] ?? '';
$q = $conn->real_escape_string($q);

$sql = "SELECT a.appointment_id, p.name, p.phone 
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        WHERE a.appointment_id LIKE '%$q%' 
           OR p.name LIKE '%$q%' 
           OR p.phone LIKE '%$q%'
        LIMIT 10";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
