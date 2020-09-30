<?php 
// Setting up the database:
$localhost  = "localhost";
$user       = "root";
$password   = "";

// Database:
$db     = "diary";

$conn = mysqli_connect($localhost, $user, $password) or die("Connection failed!, Please try again later");
mysqli_select_db($conn, $db) or die("Could not connect to database!, Please try again later");

?>