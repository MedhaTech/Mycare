<?php
$query = "SELECT p.*, pt.name AS patient_name, d.name AS doctor_name 
          FROM `procedures` p
          JOIN patients pt ON p.patient_id = pt.id
          JOIN doctors d ON p.doctor_id = d.id
          WHERE p.status = 'Cancelled'
          ORDER BY p.procedure_date DESC";

$result = $conn->query($query);
include 'tab-procedure-template.php';
?>
