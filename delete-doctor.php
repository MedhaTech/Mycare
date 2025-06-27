<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'dbconnection.php';
include 'init.php';
$conn->close();
header("Location: doctors-list.php");
exit();
?>