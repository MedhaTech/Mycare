
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: page-login.php");
    exit();
}
$conn = new mysqli("192.185.129.71", "medha_mycare", "peO*aDq0=Hb&", "medha_mycare");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$doctor_id = $_GET['id'];
$sql = "SELECT * FROM doctors WHERE doctor_id='$doctor_id'";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Doctor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Doctor Profile: #<?php echo $row['doctor_id']; ?></h3>
    <table class="table table-bordered mt-3">
        <?php foreach ($row as $key => $value) { ?>
            <tr><th><?php echo ucfirst($key); ?></th><td><?php echo $value; ?></td></tr>
        <?php } ?>
    </table>
    <a href="doctors-list.php" class="btn btn-secondary">Back to List</a>
</div>
</body>
</html>
<?php } else { echo "Doctor not found."; } $conn->close(); ?>
