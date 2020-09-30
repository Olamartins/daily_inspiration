<?php
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

include("includes/connection.php");

if($_POST['action'] == "update") {
    $diaryNote = $_POST['diaryNote'];

    $query = "UPDATE diary_table SET diaryNote = '$diaryNote' WHERE diaryId = '".$_POST['diaryId']."'";
    if(mysqli_query($conn, $query)) {
        echo "Success";
    }
}

?>