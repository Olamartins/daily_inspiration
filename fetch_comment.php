<?php
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

//fetch_comment.php
$headers = apache_request_headers();
$address =  $headers["Referer"];
$address = substr($address, 42, 1);
// echo $address;


$connect = new PDO('mysql:host=localhost;dbname=diary', 'root', '');

$query = "
SELECT * FROM tbl_comment 
WHERE parent_comment_id = '0' AND storyId = '$address'
ORDER BY comment_id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row)
{
 $output .= '
    <div class="card mb-3 card-comments">
        <div class="card-header">
            By <b>'.$row["comment_sender_name"].'</b> on <small class="text-white">'.$row["date"].'</small>
        </div>
        <div class="card-body">
            <p class="card-text">'.$row["comment"].'</p>
        </div>
        <div class="card-footer text-muted" align="right">
            <button type="button" class="btn btn-info btn-sm reply" id="'.$row["comment_id"].'">Reply</button>
        </div>
    </div>
 ';
 $output .= get_reply_comment($connect, $row["comment_id"]);
}

echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
 $query = "
 SELECT * FROM tbl_comment WHERE parent_comment_id = '".$parent_id."'
 ";
 $output = '';
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 $count = $statement->rowCount();
 if($parent_id == 0)
 {
  $marginleft = 0;
 }
 else
 {
  $marginleft = $marginleft + 48;
 }
 if($count > 0)
 {
  foreach($result as $row)
  {
   $output .= 
   '
    <div class="card mb-3 card-comments" style="margin-left:'.$marginleft.'px">
        <div class="card-header">
         By <b>'.$row["comment_sender_name"].'</b> on <small class="text-white">'.$row["date"].'</small>
        </div>
        <div class="card-body">
            <p class="card-text">'.$row["comment"].'</p>
        </div>
        <div class="card-footer text-muted" align="right">
            <button type="button" class="btn btn-info btn-sm reply" id="'.$row["comment_id"].'">Reply</button>
        </div>
    </div>
 ';
   $output .= get_reply_comment($connect, $row["comment_id"], $marginleft);
  }
 }
 return $output;
}

?>
