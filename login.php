<?php
session_start(); // Starting Session
include("includes/connection.php");

$login_message =''; // Variable To Store Error Message

if (isset($_POST['login'])) {
        
        $loginUserName     =   $_POST['login_username'];
        $loginPassword     =   $_POST['login_password'];
    
    if (!empty($loginUserName) && !empty($loginPassword)) {
        
		$loginPassword = MD5($loginPassword);
        $query = "SELECT userId, userName, userEmail, userPassword FROM users_table WHERE userName = '$loginUserName' AND userPassword = '$loginPassword'";

        if($query_run = mysqli_query($conn, $query)) {
            $rows = mysqli_num_rows($query_run);
            if($rows == 0) {
                $login_message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <h4>Login Error!</h4>
                                    <p>Username or Password does not exist</p>
                                </div>";
					
            } else if($rows == 1) {
                // fectching username and password from databse:
                $rows = mysqli_fetch_assoc($query_run);
                $registered_userId     =   $rows['userId'];
                $registered_user       =   $rows['userName'];
                $registered_pass       =   $rows['userPassword'];
                // checking if username/password supplied is strictly the same in the 'Users' table:
                if(($loginUserName == $registered_user) && ($loginPassword == $registered_pass)) {
                    $userId = mysql_result($query_run, 0, 'userId');
                    $_SESSION['userId'] = $registered_userId;
                    $_SESSION['userName'] = $registered_user;
                    header("location:dashboard.php"); 
                } else {
                    // redirect to the index.php(login page):
                    header("location:index.php");
                }
            }
        } /*else {
            
        //}*/
    
    } else {
        //output: if fields are empty:
        $login_message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <h4>Login Error!</h4>
                            <p>Username or Password field is required</p>
                        </div>";
		
    }
} // END ($_POST['submit']) IF
    
?>