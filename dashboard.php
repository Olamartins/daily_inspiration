<?php 
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

include("includes/connection.php");

//////////////////////// USER DISPLAYED INFORMATION

$getUser = "SELECT userName, userImage FROM users_table WHERE userId = '$registered_userId'";
$getUser_run = mysqli_query($conn, $getUser);
$userRow = mysqli_num_rows($getUser_run);
if($userRow == 1) {
    $currentUser = mysqli_fetch_assoc($getUser_run);
    $currentUserName = $currentUser['userName'];
    $profile_img    = $currentUser['userImage'];
}

////////////////////////////////////  Writing new diary
$success_msg ="";

 if(isset($_POST['postDiary'])) {
     $diaryNote     =   $_POST['diary'];
     
     $diaryNote     =   trim($diaryNote);
     $diaryNote     =   str_replace("'", "\'", $diaryNote);
     $diarySql      =   "INSERT INTO diary_table (userId, diaryNote) VALUES('".$registered_userId."', '".$diaryNote."')";
     $diarySql_run  =   mysqli_query($conn, $diarySql);

     if($diarySql_run == true) {
         $success_msg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <h4>Cool!! Successful</h4>
                        <p>Your Note has been kept safe</p>
                    </div>";
     }
 }

 /////////////////////////////////// Getting total number of diaryNotes
 $fetchDiary        = "SELECT diaryId, diaryNote FROM diary_table WHERE userId = '$registered_userId' AND deleted != '1' ORDER BY dateCreated DESC";
 $fetchDiary_run    = mysqli_query($conn, $fetchDiary);
 // total no of rows
 $rows  = mysqli_num_rows($fetchDiary_run);
 if($rows >= 1) {
     $notes = mysqli_fetch_assoc($fetchDiary_run);
     $diary = $notes['diaryNote'];
 } else {
     $diary = "You have no recent note/scripture in your diary. Pen down your inspiration for future reference.";
 }

//////////////////////////////  Writing new story:
if(isset($_POST['newStorybtn'])) {
    $storyTitle =   $_POST['storyTitle'];
    $storyCat   =   $_POST['storyCategory'];
    $storyText  =   $_POST['storyText'];

    $storyTitle =   trim($storyTitle);
    $storyText  =   trim($storyText);
    $storyText  =   str_replace("'", "\'", $storyText);

    $story_sql  =   "INSERT INTO story_table (userId, storyTitle, storyCategory, storyNote) VALUES('".$registered_userId."', '".$storyTitle."', '".$storyCat."', '".$storyText."')";
    $story_sql_run  =   mysqli_query($conn, $story_sql);

    if($story_sql_run == true) {
        $success_msg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <h4>Cool!! Successful</h4>
                        <p>Your story has been saved and secured</p>
                    </div>";
    }
}
/////////////////////////////  Stat for Stories
$fetchStory = "SELECT storyId, storyTitle, storyNote FROM story_table WHERE userId = '$registered_userId' AND deleted != '1' ORDER BY dateCreated DESC";
$fetchStory_run = mysqli_query($conn, $fetchStory);
$rowStory   = mysqli_num_rows($fetchStory_run);
if($rowStory >=1) {
    $story = mysqli_fetch_assoc($fetchStory_run);
    $sTitle = $story['storyTitle'];
    $sNote =  $story['storyNote'];

    $sNoteLimit = substr($sNote, 0, 100);
    $sNote = substr($sNoteLimit, 0, strrpos($sNoteLimit, ' '))."...";

    $showStory = "<strong>". $sTitle."</strong>"."<br />".$sNote;
} else {
    $showStory = "You do not have any recent story in your story book. Be inspired! Break the boredom.";
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
                    <div><?=$success_msg; ?></div>
                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-4 my-3">
                            <div class="card">
                                <div class="card-body bg-note">
                                    <h5 class="card-title text-white">Daily Inspirations</h5>
                                    <p class="card-text">You have kept <span class="counter"><?php echo $rows; ?></span> awesome inspirations in your Notebook. </p>
                                    <a href="inspire.php" class="btn btn-info btn-sm btn-custom-n">Open Notebook</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 my-3">
                            <div class="card">
                                <div class="card-body bg-story">
                                    <h5 class="card-title text-white">Your Inspired Stories</h5>
                                    <p class="card-text">Wow! You've written <span class="counter"><?php echo $rowStory; ?></span> amazing stories. Writing is fulfilling.</p>
                                    <a href="personalstory.php" class="btn btn-info btn-sm btn-custom-s">Read your stories</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 my-3">
                            <div class="card">
                                <div class="card-body bg-newtory">
                                    <h5 class="card-title text-white">Be Inspired!</h5>
                                    <p class="card-text">Got an inspiration to write a life-transform story? Writing is fun and fulfilling.</p>
                                    <a href="#" class="btn btn-info btn-sm btn-custom-ns" data-toggle="modal" data-target="#storyModal">Write new story</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                        <div class="modal fade" id="storyModal" tabindex="-1" role="dialog" aria-labelledby="newstory" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title text-white font-weight-bolder" id="newstory">NEW STORY</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <form method="post">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="storyTitle" placeholder="Story title" required autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Private or Public?</label>
                                                <select class="form-control"  name="storyCategory" id="category">
                                                    <option value="private">Private</option>
                                                    <option value="public">Public</option>
                                                </select>
                                            </div>
                                            <div class="form-group newstory">
                                                <textarea class="form-control" name="storyText" placeholder="Your Story Line" required></textarea>
                                            </div>
                                            <button type="submit" name="newStorybtn" class="btn btn-info btn-sm">Post Story</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Modal -->

                    <div class="row d-flex align-items-center mt-3">
                        <div class="col-md-1 col-sm-2">
                            <img src="<?php if(!empty($profile_img)) { echo 'data:image/png;base64,'.base64_encode($profile_img); } else { echo "img/avatar.jpg";} ?>" alt="" width="70px" height="70px" class="profile-pix mr-2 mb-2">
                        </div>
                        <div class="col-md-10 col-sm-10">
                            <form action="" method="post">
                                <div class="row new-diary align-items-center">
                                    <div class="col-md-9 col-sm-8">
                                        <textarea name="diary" id="diary" class="w-100" placeholder="Your today's favourite scripture or inspiration..." required></textarea>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <input type="submit" name="postDiary" value="Add Your Note" class="btn btn-info">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-3 mb-3 pb-3">
                        <div class="col-md-6 mt-3">
                            <div class="card">
                                <div class="card-body r_note">
                                    <h5 class="card-title text-info">Recent Note</h5>
                                    <p class="card-text"><?php echo $diary; ?></p>
                                    <a href="inspire.php" class="btn btn-custom-n text-light">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="card">
                                <div class="card-body r_story">
                                    <h5 class="card-title text-info">Recent Story</h5>
                                    <p class="card-text"><?php echo $showStory; ?></p>
                                    <a href="personalstory.php" class="btn btn-custom-s text-light">Read More</a>
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