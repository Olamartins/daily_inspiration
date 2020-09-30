<?php
session_start();

if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])) {
    
    session_destroy();
    header("location:index.php");

} else {
    header("location:index.php");
}

?>