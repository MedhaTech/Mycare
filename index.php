<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include("header.php");
?>
<?php include 'footer.php'; ?>

</body>

</html>