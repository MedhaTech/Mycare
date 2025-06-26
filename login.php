<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();



$conn = new mysqli("192.185.129.71", "medha_mycare", "peO*aDq0=Hb&", "medha_mycare");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

   
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password'); window.location.href='login.php';</script>";
        exit();
    }
}

$conn->close();
?>

