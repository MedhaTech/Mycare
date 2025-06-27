<?php
session_start();
session_unset();     
session_destroy();  
header("Location: page-login.php");  
exit();
 ?>   

<!DOCTYPE html>
<html>
<head>
    <script>
      
        localStorage.removeItem("isLoggedIn"); 
        window.location.href = "page-login.php";
    </script>
</head>
<body>
    <script>
    sessionStorage.clear(); 
    window.location.href = "page-login.php";
</script>
</body>
</html>