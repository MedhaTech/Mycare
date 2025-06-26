<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
$conn = new mysqli("192.185.129.71", "medha_mycare", "peO*aDq0=Hb&", "medha_mycare");
if (isset($_POST['deleteDoctor'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM doctors WHERE id = $id");
}
$conn->close();
header("Location: doctors-list.php");
exit();
?>
