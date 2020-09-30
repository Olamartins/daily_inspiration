<?php 
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

include("includes/connection.php");


$getUser = "SELECT userName FROM users_table WHERE userId = '$registered_userId'";
$getUser_run = mysqli_query($conn, $getUser);
$userRow = mysqli_num_rows($getUser_run);
if($userRow == 1) {
    $currentUser = mysqli_fetch_assoc($getUser_run);
    $currentUserName = $currentUser['userName'];
}


 ///////////////////// Getting user profile information

 $fetchUser        = "SELECT * FROM users_table WHERE userId = '$registered_userId'";
 $fetchUser_run    = mysqli_query($conn, $fetchUser);
 // total no of rows
 $rows  = mysqli_num_rows($fetchUser_run );
 if($rows == 1) {
     while($profile = mysqli_fetch_assoc($fetchUser_run )){
        $profile_img = $profile['userImage'];
        $profile_name = $profile['userName'];
        $profile_email = $profile['userEmail'];
        $profile_bio = $profile['userBio'];
        $profile_scripture = $profile['userFavScripture'];
        $profile_quote = $profile['userQuote'];
    }
 }

/////////////////////// UPDATE PROFILE INFO /////////////////////
$success_msg = "";

if(isset($_POST['updatebtn'])) {
    $userName   =   trim($_POST['userName']);
    $userEmail  =   trim($_POST['userEmail']);
    $userBio    =   mysqli_escape_string($conn, trim($_POST['userBio']));
    $userFs     =   mysqli_escape_string($conn, trim($_POST['userFS']));
    $userQu     =   mysqli_escape_string($conn, trim($_POST['userQuote']));

    $userBio    =   str_replace("'", "\'", $userBio);
    $userFs     =   str_replace("'", "\'", $userFs);
    $userQu    =   str_replace("'", "\'", $userQu);

    $sql_fetchUsers = "SELECT userName, userEmail FROM users_table WHERE userId ='$registered_userId'";
    $sql_fetchUsers_run = mysqli_query($conn, $sql_fetchUsers);

    $userRows = mysqli_num_rows($sql_fetchUsers_run);

    if($userRows == 1) {
        $rowUser = mysqli_fetch_assoc($sql_fetchUsers_run);

        if(($userName == $rowUser['userName']) || (($userEmail == $rowUser['userEmail']))){

            $sql_update =  "UPDATE users_table SET userName = '$userName', userEmail = '$userEmail', userBio = '$userBio', userFavScripture = '$userFs', userQuote = '$userQu' WHERE userId = '$registered_userId'";
        
            $sql_update_run = mysqli_query($conn, $sql_update);
    
            if($sql_update_run) {
                $success_msg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <h5>Cool!! Successful</h5>
                                    <p>Your Profile Information has been updated</p>
                                </div>";
                            header("location:settings.php");
            } else {
                $success_msg = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                    <h5>Awww!! Something went wrong</h5>
                                    <p>Username or Email address already exist!</p>
                                </div>";
            }
            
        }
         
    }
}

///////////////// PASSWORD RESET /////////////////////////

$passFeedback = "";

if(isset($_POST['resetpassword'])) {
    $oldPassword = $_POST['oldpass'];
    $newPassword = $_POST['newpass'];
    $confirmpass = $_POST['confirm_pass'];

    $oldPassword = md5($oldPassword);
    $newPassword = md5($newPassword);
    $confirmpass = md5($confirmpass);

    if($newPassword !== $confirmpass) {
        $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <p>Password does not match!</p>
                        </div>";
    } else{
        $sql_pass = "SELECT userPassword FROM users_table WHERE userId ='$registered_userId'";
        $sql_pass_run = mysqli_query($conn, $sql_pass);
        $rowPass = mysqli_num_rows($sql_pass_run);
        if($rowPass == 1) {
            $userPassword = mysqli_fetch_assoc($sql_pass_run);
            $existingPass = $userPassword['userPassword'];

            if($existingPass != $oldPassword) {
                $passFeedback = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                    <p>Incorrect Password!</p>
                                </div>";
            } else {
                $sql_new_pass = "UPDATE users_table SET userPassword = '$newPassword' WHERE userId = '$registered_userId'";
                if(mysqli_query($conn, $sql_new_pass)) {
                    $passFeedback = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        <p>Your Password has been reset.</p>
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./iconfont/material-icons.css">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="./croppie/croppie.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container"></div>
        <button class="navbar-toggler sideMenuToggler" type="button">
                <i class="fas fa-bars"></i>
            </button>

        <a class="navbar-brand" href="#"><?php echo "$currentUserName"; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-ellipsis-v"></i>
            </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Account
                </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="wrapper d-flex">
        <!-- sideBarMenu section -->
        <div class="sideMenu bg-dark">
            <div class="sidebar">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="dashboard.php" class="nav-link px-2"><i class="material-icons icon">
                        dashboard
                    </i><span class="text">Dashboard</span></a></li>
                    <li class="nav-item"><a href="profile.php" class="nav-link px-2"><i class="material-icons icon">
                        person
                    </i><span class="text">User Profile</span></a></li>
                    <li class="nav-item"><a href="settings.php" class="nav-link px-2"><i class="material-icons icon">
                        settings
                    </i><span class="text">Settings</span></a></li>
                    <li class="nav-item"><a href="stories.php" class="nav-link px-2"><i class="material-icons icon">
                        book
                    </i><span class="text">Read Stories</span></a></li>
                    <li class="nav-item"><a href="#" class="nav-link sideMenuToggler px-2"><i class="material-icons icon">
                        view_list
                    </i><span class="text">Resize</span></a></li>
                </ul>
            </div>
        </div>
        <!-- mainContent section -->
        <div class="content">
            <main>
                <div class="container-fluid">
                    <div class="row mt-3 profile-edit">
                        <div class="col-md-4 password-reset mb-4">
                            <div><?=$passFeedback; ?></div>
                            <h4>Reset Password</h4>
                            <form method="post">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="oldpass"  placeholder="Old Password" required autocomplete="off">
                                </div>
                               
                                <div class="form-group">
                                    <input type="password" class="form-control" name="newpass" placeholder="New Password" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="confirm_pass" placeholder="Confirm New Password" required autocomplete="off">
                                </div>
                                <button type="submit" name="resetpassword" class="btn btn-info btn-sm">Reset Password</button>
                            </form>
                        </div>
                        <div class="col-md-8 profile-reset mb-5">
                            <div><?=$success_msg; ?></div>
                            <h4>Edit Profile Information</h4>
                            <div class="image-pic mb-3">
                                
                                    <img src="<?php if(!empty($profile_img)) { echo 'data:image/png;base64,'.base64_encode($profile_img); } else { echo "img/avatar.jpg";} ?>" alt="Profile-Image" class="rounded" style="width:100px; height:100px;">
                                
                                    <h6>Select Your Profile Picture</h6>
                                    <input type="file" name="insert_image" id="insert_image" accept="image/*" />
                            </div>
                            <div class="profile-detail">
                                <form action="settings.php" method="post">
                                    <div class="form-group">
                                        <label for="username">Username:</label>
                                        <input type="text" class="form-control" name="userName" value="<?php echo $profile_name; ?>" id="username" placeholder="Username" required autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        <label for="email_address">Email address:</label>
                                        <input type="text" id="email_address" class="form-control" name=userEmail value="<?php echo $profile_email; ?>" placeholder="Email address" required autocomplete="off">
                                    </div>

                                    <div class="form-group profile_bio">
                                        <label for="biography">Biography:</label>
                                        <textarea class="form-control" name="userBio" placeholder="Enter your Biography" id="biography" required><?php if(!empty($profile_bio)) { echo $profile_bio; } else { echo "Please update your biography...";} ?></textarea>
                                    </div>

                                    <div class="form-group profile_bio">
                                     <label for="scripture">Favourite Scripture:</label>
                                        <textarea class="form-control" name="userFS" placeholder="Enter your favourite scripture" id="scripture" required><?php if(!empty($profile_scripture)) { echo $profile_scripture; } else { echo "Please update your favourite scripture...";} ?></textarea>
                                    </div>

                                    <div class="form-group profile_bio">
                                        <label for="quote">Favourite Quote:</label>
                                        <textarea class="form-control" name="userQuote" placeholder="Enter your favourite quote" id="quote" required><?php if(!empty($profile_quote)) { echo $profile_quote; } else { echo "Please update your favourite quote...";} ?></textarea>
                                    </div>

                                    <button class="btn btn-sm btn-info" type="submit" name="updatebtn">Update Profile</button>
                                </form>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="insertImageModal" tabindex="-1" role="dialog" aria-labelledby="newstory" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title text-white font-weight-bolder" id="newstory">Crop Image</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <div class="row">
                                            <div class="col-md-12 text-center upload">
                                                <div id="image_demo" style="width:350px; margin:auto;">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-info btn-sm crop_image">Crop and Upload Image</button>
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Modal -->
                    </div>
                </div>
                <!-- container-fluid -->
            </main>
        </div>
        <!--content -->

    </div>
    <script src="./js/jquery-3.4.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jquery.slimscroll.min.js"></script>
    <script src="./croppie/croppie.js"></script>
    <script src="./croppie/exif.js"></script>
    <script src="./js/custom.js"></script>

    <script>
        $(document).ready(function() {
            $image_crop = $("#image_demo").croppie({
                enableExif: true,
                viewport: {
                    width: 150,
                    height: 150,
                    type: 'square'
                },
                boundary: { 
                    width: 300,
                    height: 300
                }
            });

            $("#insert_image").on('change', function() {
            var reader = new FileReader();
            reader.onload = function(event) {
                    $image_crop.croppie('bind', {
                        url:event.target.result
                 }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $("#insertImageModal").modal('show');
        }); 
        
        $('.crop_image').click(function(event) {
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                $.ajax({
                    url: 'insert.php',
                    type: 'POST',
                    data: {"image":response},
                    success: function(data){
                        $("#insertImageModal").modal('hide');
                        alert(data);
                    }
                });
            });
        });
    });
    </script>

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
    </script>
</body>

</html>