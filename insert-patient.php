<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php

$conn = new mysqli("localhost", "root", "", "medical");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name = $conn->real_escape_string($_POST['name']);
$phone = $conn->real_escape_string($_POST['phone']);
$dob = $_POST['dob'];
$gender = $conn->real_escape_string($_POST['gender']);
$abha = $conn->real_escape_string($_POST['abha']);
$aadhar = $conn->real_escape_string($_POST['aadhar']);

$address1 = $conn->real_escape_string($_POST['address1']);
$address2 = $conn->real_escape_string($_POST['address2']);
$city = $conn->real_escape_string($_POST['city']);
$state = $conn->real_escape_string($_POST['state']);
$pincode = $conn->real_escape_string($_POST['pincode']);

$blood = $conn->real_escape_string($_POST['blood_group']);
$height = $conn->real_escape_string($_POST['height']);
$weight = $conn->real_escape_string($_POST['weight']);
$sugar = $conn->real_escape_string($_POST['sugar']);
$bp = $conn->real_escape_string($_POST['bp']);


$doctor_id = $_POST['doctor_id']; 


$sql = "INSERT INTO patients (
    name, phone, dob, gender, abha_number, aadhar_number,
    address1, address2, city, state, pincode,
    blood_group, height_cm, weight_kg, sugar_level, bp,
    doctor_id, status
) VALUES (
    '$name', '$phone', '$dob', '$gender', '$abha', '$aadhar',
    '$address1', '$address2', '$city', '$state', '$pincode',
    '$blood', '$height', '$weight', '$sugar', '$bp',
    " . ($doctor_id ? "'$doctor_id'" : "NULL") . ", 'Active'
)";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Patient added successfully!'); window.location.href = 'patient-list.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
