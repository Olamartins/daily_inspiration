<?php 
// Setting up the database:
$localhost  = "sm9j2j5q6c8bpgyq.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$user       = "xsxkyrw3rgisfats";
$password   = "dz2z1y8qiy5ozmd6";

// Database:
$db     = "kzzu1azz2nn5wxqq";

$conn = mysqli_connect($localhost, $user, $password) or die("Connection failed!, Please try again later");
mysqli_select_db($conn, $db) or die("Could not connect to database!, Please try again later");

?>
