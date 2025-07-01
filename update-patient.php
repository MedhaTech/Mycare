<?php
session_start();

include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);

   
    $name          = $conn->real_escape_string($_POST['name'] ?? '');
    $phone         = $conn->real_escape_string($_POST['phone'] ?? '');
    $dob           = $conn->real_escape_string($_POST['dob'] ?? '');
    $gender        = $conn->real_escape_string($_POST['gender'] ?? '');
    $abha_number   = $conn->real_escape_string($_POST['abha_number'] ?? '');
    $aadhar_number = $conn->real_escape_string($_POST['aadhar_number'] ?? '');
    $address1      = $conn->real_escape_string($_POST['address1'] ?? '');
    $address2      = $conn->real_escape_string($_POST['address2'] ?? '');
    $city          = $conn->real_escape_string($_POST['city'] ?? '');
    $state         = $conn->real_escape_string($_POST['state'] ?? '');
    $pincode       = $conn->real_escape_string($_POST['pincode'] ?? '');
    $blood_group   = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $bp            = $conn->real_escape_string($_POST['bp'] ?? '');

    
    $height_cm    = ($_POST['height_cm'] === '') ? 'NULL' : floatval($_POST['height_cm']);
    $weight_kg    = ($_POST['weight_kg'] === '') ? 'NULL' : floatval($_POST['weight_kg']);
    $sugar_level  = ($_POST['sugar_level'] === '') ? 'NULL' : floatval($_POST['sugar_level']);

    $sql = "UPDATE patients SET
                name = '$name',
                phone = '$phone',
                dob = '$dob',
                gender = '$gender',
                abha_number = '$abha_number',
                aadhar_number = '$aadhar_number',
                address1 = '$address1',
                address2 = '$address2',
                city = '$city',
                state = '$state',
                pincode = '$pincode',
                blood_group = '$blood_group',
                height_cm = $height_cm,
                weight_kg = $weight_kg,
                sugar_level = $sugar_level,
                bp = '$bp'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: patient-list.php?updated=1");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>