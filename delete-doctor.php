<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
$conn = new mysqli("localhost", "root", "", "medical");
if (isset($_POST['deleteDoctor'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM doctors WHERE id = $id");
}
$conn->close();
header("Location: doctors-list.php");
exit();
?>
