<?php 
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

include("includes/connection.php");

 if(isset($_POST['image'])) {
     $data = $_POST['image'];

     $image_array_1 = explode(";", $data);

     $image_array_2 = explode(",", $image_array_1[1]);

     $data = base64_decode($image_array_2[1]);

     $imageName = time() . '.png';

     file_put_contents($imageName, $data);

     $image_file = addslashes(file_get_contents($imageName));

     $query = "UPDATE users_table SET userImage = '$image_file' WHERE userId = '$registered_userId'";

     $query_run = mysqli_query($conn, $query);

     if($query_run) {
         echo "Image Uploaded Successful";
         unlink($imageName);
     }
    
 }

?>