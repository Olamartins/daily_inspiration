<?php 
session_start();
error_reporting(0);

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


$fetchStory = "SELECT * FROM story_table WHERE userId = '$registered_userId' AND deleted != '1' ORDER BY dateCreated DESC";
$fetchStory_run = mysqli_query($conn, $fetchStory);


$success_msg = "";
if($_POST['action'] == "update") {
    $storyTitle = $_POST['storyTitle'];
    $category  = $_POST['storyCategory'];
    $storyNote = $_POST['storyNote'];
    $storyNote = str_replace("'", "\'", $storyNote);

    $query = "UPDATE story_table SET storyTitle = '$storyTitle', storyCategory = '$category', storyNote = '$storyNote' WHERE storyId = '".$_POST['storyId']."'";
    if(mysqli_query($conn, $query)) {
        $success_msg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <h4>Cool!! Successful</h4>
                        <p>Your Note has been kept safe</p>
                    </div>";
                    header("location:personalstory.php");
    }
}


if(isset($_POST['delete'])) {
    
    $id = $_POST['deleteId'];

    $querydel = "UPDATE story_table SET deleted = '1' WHERE storyId = '$id'";
    if(mysqli_query($conn, $querydel)) {
        $success_msg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <h4>Cool!! Successful</h4>
                        <p>Your Story has been deleted!</p>
                    </div>";
                    header("location:personalstory.php");
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
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="container-fluid mt-5">
                            <div><?=$success_msg; ?></div>
                            <div class="row mt-3">
                                <div class="col-12">
                                 <h3 class="trends">Your Stories</h3>
                                </div>
                            </div>
                            <div class="row mb-5">
                                    <?php foreach($fetchStory_run as $rowStory ) { ?>
                                        <div class="col-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="hidden"><?php echo $rowStory['storyId']; ?></p>
                                                    <p class="card-title text-success"><?php echo $rowStory['dateCreated']; ?></p>
                                                    <p class="card-text font-weight-bolder"><?php echo $rowStory['storyTitle']; ?> </p>
                                                    <p class="card-text"><?php echo $rowStory['storyNote']; ?> </p>
                                                    <p><button class="btn btn-sm btn-info update float-left" name="update" id="<?php echo $rowStory['storyId']; ?>"><i class="fa fa-edit text-white"></i> Edit</button></p>
                                                    <p><button class="btn btn-sm btn-danger delete"><i class="fa fa-trash text-white"></i> Delete</button></p>
                                                </div>
                                            </div>
                                        </div>
                                <?php }?>
                            </div>
                        </div><!-- container-fluid-mt-5 -->
                         <!-- Modal -->
                         <div class="modal fade" id="updateStoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title text-white font-weight-bolder" id="newstory">Update Story</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <form id="update-form" method="post" action="personalstory.php">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="storyTitle" id="storyTitle" required >
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Private or Public?</label>
                                                <select class="form-control"  name="storyCategory" id="category">
                                                    <option value="private">Private</option>
                                                    <option value="public">Public</option>
                                                </select>
                                            </div>
                                            <div class="form-group newstory">
                                                <textarea class="form-control diaryNotes" name="storyNote" id="storyNote" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="action" id="action" value="update">
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="storyId" id="storyId">
                                            </div>
                                            <button type="submit" name="update" id="update" class="btn btn-info btn-sm">Update</button>
                                        </form>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    <!-- Modal -->

                     <!-- Delete Modal -->
                     <div class="modal fade" id="deleteStoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content bg-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-white font-weight-bolder" id="newstory">Delete Story</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    
                                    <div class="modal-body bg-light">
                                        <form id="delete-form" method="post" action="personalstory.php">
                                           
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="deleteId" id="deleteId">
                                            </div>
                                            <h6 class="text-center text-dark"><i class="fa fa-exclamation-triangle text-warning"></i> Do you want to delete this story?</h6>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">No</button>
                                            <button type="submit" name="delete" id="delete" class="btn btn-danger btn-sm">Yes! Delete</button>
                                            </div>
                                            
                                        </form>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    <!-- Modal -->

                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </main>
    </div>
    <!--content -->

</div><!-- wrapper-container -->

<script src="./js/jquery-3.4.1.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
<script src="./js/jquery.slimscroll.min.js"></script>
<script src="./js/custom.js"></script>

<script>
    $(document).ready(function() {
        $('.delete').on('click', function(){

            $tr = $(this).closest('.card-body');
            var data = $tr.children("p").map(function(){
                return $(this).text();
            }).get();

            console.log(data);
            $("#deleteId").val(data[0]);

            $('#deleteStoryModal').modal("show");
        });
    });
        
</script>


<script>
    $(document).ready(function() {
        $('.update').on('click', function(){

            $('#storyId').val($(this).attr("id"));
            $("#action").val("update");

            $tr = $(this).closest('.card-body');
            var data = $tr.children("p").map(function(){
                return $(this).text();
            }).get();

            console.log(data);
            $("#storyTitle").val(data[2]);
            $("#storyNote").val(data[3]);
            $('#updateStoryModal').modal("show");
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
</script>
</body>

</html>