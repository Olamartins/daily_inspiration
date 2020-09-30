<?php
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}
//add_comment.php

include("includes/connection.php");

$error = '';
$comment_name = '';
$comment_content = '';


if(empty($_POST["comment_name"]))
{
 $error .= '<p class="text-danger alert-danger text-left">Name is required</p>';
}
else
{
 $comment_name = $_POST["comment_name"];
}

if(empty($_POST["comment_content"]))
{
 $error .= '<p class="text-danger alert-danger text-left">Comment is required</p>';
}
else
{
 $comment_content = $_POST["comment_content"];
}

if($error == '')
{

    $parent_comment_id = $_POST["comment_id"];
    $comment_content = $_POST["comment_content"];
    $comment_name = $_POST["comment_name"];
    $storyId = $_POST["storyId"];

 $query = " INSERT INTO tbl_comment 
        (parent_comment_id, comment, comment_sender_name, storyId) 
        VALUES ('".$parent_comment_id."', '".$comment_content."', '".$comment_name ."', '".$storyId."')
        ";
 $statement = mysqli_query($conn, $query);
 
 $error = '<label class="text-success alert-success">Comment Added</label>';
}

$data = array(
 'error'  => $error
);

echo json_encode($data);

?>