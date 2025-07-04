<?php

/*$servername = "localhost";
$username = "root"; 
$password = "";     
$database = "medical";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
*/

$servername = "192.185.129.71";
$username = "medha_mycare"; 
$password = "peO*aDq0=Hb&";     
$database = "medha_mycare";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);    
}

?>
