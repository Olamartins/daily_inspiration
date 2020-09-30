<?php 

    include('includes/connection.php');
    include("login.php");
    

    // procesing form registration submission
    $message = "";

    if(isset($_POST['registerbtn'])){
        $username       =   $_POST['register_userName'];
        $userEmail      =   $_POST['register_userEmail'];
        $userpassword   =   $_POST['register_userpassword'];
      
        $username       =   mysqli_real_escape_string($conn, trim($username));
        $userEmail      =   mysqli_real_escape_string($conn, trim($userEmail));
        $userpassword   =   mysqli_real_escape_string($conn, trim($userpassword));
        $userpassword   =   md5($userpassword);

        if(!empty($username) || !empty($userEmail) || !empty($userpassword)) {
            //checking for existing username and email:
            $fetchdata  =   "SELECT * FROM users_table WHERE userName = '$username' AND userEmail = '$userEmail'";
            $fetchdata_run = mysqli_query($conn, $fetchdata);
           if($fetchdata_run){
                $rows   =   mysqli_num_rows($fetchdata_run);
                if($rows == 0) {
                    $submit_query = "INSERT INTO users_table (userName, userEmail, userPassword) VALUES('".$username."', '".$userEmail."','". $userpassword."')";
                    $submit_query_run = mysqli_query($conn, $submit_query);
                    if($submit_query_run == true) {
                        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        <h4>Successfully registered!</h4>
                                        <p>Great!!! Your account has been created.</p>
                                    </div>";
                    } else{
                        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <h4>Unsuccessful registration!</h4>
                                    <p>Username or Email address already Exist</p>
                                </div>";
                        }
                } else {
                    $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <h4>Registration Failed!</h4>
                                    <p>Cannot connect to the database!</p>
                                </div>";
                    }
            }
        }
    }



$passFeedback = "";

if(isset($_POST['reset_password_btn'])) {
    $registeredUserEmail = trim($_POST['registered_email']);
    $newPassword         = $_POST['new_password'];
    $confirmpass         = $_POST['confirm_new_password'];

    $newPassword = md5($newPassword);
    $confirmpass = md5($confirmpass);
    echo $registeredUserEmail;
    if($newPassword !== $confirmpass) {
        $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <h4>Error! Something Wrong</h4>
                            <p>Password does not match!</p>
                        </div>";
    } else{
        $sql_pass_reset = "SELECT userEmail FROM users_table WHERE userEmail ='$registeredUserEmail'";
        $sql_pass_reset_run = mysqli_query($conn, $sql_pass_reset);
        if($sql_pass_reset_run) {
            $rowEmail = mysqli_num_rows($sql_pass_reset_run);
            if($rowEmail == 0){
                $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                    <p>Your Email address does not exist!</p>
                                </div>";
            }
            if($rowEmail == 1) {
                $userRow = mysqli_fetch_assoc($sql_pass_reset_run);
                $existingEmail = $userRow['userEmail'];

                if($existingEmail == $registeredUserEmail) {
                    $sql_new_pass = "UPDATE users_table SET userPassword = '$newPassword' WHERE userEmail = '$registeredUserEmail'";
                    if(mysqli_query($conn, $sql_new_pass)) {
                        $passFeedback = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                            <p>Your Password has been reset.</p>
                                        </div>";
                    }
                } else {
                    $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                        <p>Incorrect Email address! Please create an account.</p>
                                    </div>";
                }
            }
        } 
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diary and Story</title>
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="brand">
                <h2>Daily <span>Inspiration</span></h2>
            </div>
            <div class="donate">
                <a href="#" class="donatebtn">support</a>
            </div>
        </header>
        <section class="main">
            <div class="main-text">
                <h2>Have you been inspired by something or got any inspiration from reading Bible or Books? </h2>
                <p>Daily<span> Inspiration</span> gives you a platform to save your inspirations and share your stories.</p>
            </div>
            <div class="main-login">
                
                <form action="#" method="post">
                    <div class="loginfeedback"><?=$login_message ?></div>
                    <input type="text" name="login_username" placeholder="Username" autocomplete="off" required="required">
                    <input type="password" name="login_password" placeholder="Password" required="required" />
                    <small><a href="#modalpassword"> Forgot your password?</a> </small>
                    <button type="submit" name="login"><i class="fas fa-sign-in-alt"></i> login</button>
                </form>
                <p>Not yet registered? <a href="#modal">Sign Up</a></p>
            </div>
            <div class="modal" id="modal">
                <div class="modal-content">
                    <a href="#" class="modal-close">&times;</a>
                    <div class="modal-title">
                        <h2>Create Account</h2>
                        <div class="feedback"><?=$message; ?></div>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post">
                            <input type="text" name="register_userName" placeholder="Enter your name" required autocomplete="off" />
                            <input type="email" name="register_userEmail" placeholder="Enter your email" required autocomplete="off" />
                            <input type="password" name="register_userpassword" placeholder="Enter your password" required />
                            <input type="submit" name="registerbtn" value="Sign up">
                        </form>
                    </div>
                </div>
            </div>
            <!-- forgot password modal -->
            <div class="modal" id="modalpassword">
                <div class="modal-content">
                    <a href="#" class="modal-close">&times;</a>
                    <div class="modal-title">
                        <h2>Reset Password</h2>
                        <div class="feedback"><?=$passFeedback; ?></div>
                    </div>
                    <div class="modal-body-password">
                        <form action="" method="post">
                            <input type="email" name="registered_email" placeholder="Enter your Email" required autocomplete="off" />
                            <input type="password" name="new_password" placeholder="Enter new password" required autocomplete="off" />
                            <input type="password" name="confirm_new_password" placeholder="Confirm new password" required />
                            <input type="submit" name="reset_password_btn" value="Reset Password">
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <div class="footer">
        <a href="#">Made with <i class="fas fa-heart"></i>  by: Olamartins</a>
    </div>
    <script src="./js/jquery-3.4.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jquery.slimscroll.min.js"></script>
    <script src="./js/custom.js"></script>

    <script>
		// remove the mesages
		  $(".alert-success").delay(500).show(10, function() {
			$(this).delay(3000).hide(10, function() {
				$(this).remove();
			});
		}); // /.alert
		// remove the mesages
		  $(".alert-warning").delay(500).show(10, function() {
			$(this).delay(3000).hide(10, function() {
				$(this).remove();
			});
		}); // /.alert
		// remove the mesages
		  $(".alert-danger").delay(500).show(10, function() {
			$(this).delay(3000).hide(10, function() {
				$(this).remove();
			});
		}); // /.alert
    </script>
</body>

</html>