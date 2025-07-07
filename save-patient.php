<?php
include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $abha = $_POST['abha_number'];
    $aadhar = $_POST['aadhar_number'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $blood_group = $_POST['blood_group'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $sugar = $_POST['sugar'];
    $bp = $_POST['bp'];

    $status = 1; // default status
    $created_at = date('Y-m-d H:i:s'); // current timestamp

    $stmt = $conn->prepare("INSERT INTO patients 
        (name, phone, dob, gender, abha_number, aadhar_number, address1, address2, city, state, pincode, blood_group, height_cm, weight_kg, sugar_level, bp, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssssssssss", 
        $name, $phone, $dob, $gender, $abha, $aadhar, $address1, $address2,
        $city, $state, $pincode, $blood_group, $height, $weight, $sugar, $bp, $status, $created_at);

    if ($stmt->execute()) {
        header("Location: patient-list.php?msg=added");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}

?>
