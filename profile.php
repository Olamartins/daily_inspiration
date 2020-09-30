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


 // Getting user profile information

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
                    <div class="row mt-3 align-items-center justify-content-center profile">
                        <div class="col-md-12 align-self-center">
                            <div class="profile-img">
                                <img src="<?php if(!empty($profile_img)) { echo 'data:image/png;base64,'.base64_encode($profile_img); } else { echo "img/avatar.jpg";} ?>" alt="Profile-Image" class="rounded" style="width:150px; height:150px;">
                            </div>
                            <div class="username my-4">
                                <h4><?php echo $profile_name; ?></h4>
                                <h5><?php echo $profile_email; ?></h5>
                            </div>

                            <div class="bio  mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-info">Biography</h3>
                                        <p class="card-text"><?php if(!empty($profile_bio)) { echo $profile_bio; } else { echo "Please update your biography";} ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="fav-scripture mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-info">Favourite Scripture</h3>
                                        <p class="card-text"><?php if(!empty($profile_scripture)) { echo $profile_scripture; } else { echo "Please update your favourite scripture";} ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="fav-quote mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-info">Favourite Quote</h3>
                                        <p class="card-text"><?php if(!empty($profile_quote)) { echo $profile_quote; } else { echo "Please update your favourite quote";} ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="./js/custom.js"></script>

    <script>
    // remove the mesages
		  $(".alert-success").delay(500).show(10, function() {
			$(this).delay(3000).hide(10, function() {
				$(this).remove();
			});
		}); // /.alert
    </script>
</body>

</html>