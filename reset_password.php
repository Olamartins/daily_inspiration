<?php
$passFeedback = "";

if(isset($_POST['reset_password_btn'])) {
    $registeredUserEmail = $_POST['registered_email'];
    $newPassword = $_POST['new_password'];
    $confirmpass = $_POST['confirm_new_password'];

    
    $newPassword = md5($newPassword);
    $confirmpass = md5($confirmpass);

    if($newPassword !== $confirmpass) {
        $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <p>Password does not match!</p>
                        </div>";
    } else{
        $sql_pass = "SELECT userPassword FROM users_table WHERE userEmail ='$registeredUserEmail'";
        $sql_pass_run = mysqli_query($conn, $sql_pass);
        if($sql_pass_run) {
            $rowPass = mysqli_num_rows($sql_pass_run);
      
            if($rowPass == 1) {
                $userRegisteredEmail = mysqli_fetch_assoc($sql_pass_run);
                $existingEmail = $userRegisteredEmail['userEmail'];

                if($existingEmail != $registeredUserEmail) {
                    $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                        <p>Incorrect Email address! Please create an account.</p>
                                    </div>";
                } else {
                    $sql_new_pass = "UPDATE users_table SET userPassword = '$newPassword' WHERE userEmail = '$registeredUserEmail'";
                    if(mysqli_query($conn, $sql_new_pass)) {
                        $passFeedback = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                            <p>Your Password has been reset.</p>
                                        </div>";
                    }
                }
            } 
        } else {
            $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <p>Your Email address does not exist!</p>
                            </div>";
        }
        
    }
}


?>