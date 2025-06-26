<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
if (isset($_GET['id'])) {
    $conn = new mysqli("localhost", "root", "", "medical");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $doctorId = $_GET['id'];

    
    $stmt = $conn->prepare("SELECT status FROM doctors WHERE doctor_id = ?");
    $stmt->bind_param("s", $doctorId);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    
    $newStatus = ($currentStatus === "Active") ? "Inactive" : "Active";

    $stmt = $conn->prepare("UPDATE doctors SET status = ? WHERE doctor_id = ?");
    $stmt->bind_param("ss", $newStatus, $doctorId);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: doctors-list.php");
    exit();
}
?>
