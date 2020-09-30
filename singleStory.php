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

if(isset($_GET['id'])){
    $storyId = $_GET['id'];

    ///////////// FETCHING ALL STORIES
    // $sql_all_story = "SELECT * FROM story_table INNER JOIN users_table ON story_table.storyId = users_table.userId WHERE storyCategory ='public' AND deleted != '1' AND storyId = '$storyId' ORDER BY dateCreated DESC";
    $sql_all_story = "SELECT story_table.storyId, story_table.storyTitle, story_table.storyNote, users_table.userName, users_table.userImage,
    COUNT(story_like.id) as thumbsup
    FROM story_table
    JOIN users_table
    ON story_table.userId = users_table.userId
    LEFT JOIN story_like
    ON story_like.storyId = story_table.storyId
    WHERE story_table.storyCategory ='public' AND story_table.deleted != '1' AND story_table.storyId = '$storyId'
    GROUP BY story_table.storyId
    ORDER BY story_table.dateCreated DESC";

    $sql_all_story_run = mysqli_query($conn, $sql_all_story);
    
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
                    <div class="row align-items-center justify-content-center profile">
                        <div class="col-md-12 align-self-center">
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="stories.php" class="back_nav float-left"><i class="fas fa-arrow-circle-left"> </i> back</a>
                                    <h3 class="trends mb-4">Read Amazing Stories</h3>
                                </div>
                            </div>
                            <div class="row">
                                    <?php foreach($sql_all_story_run as $rowData ) { 
                                        $content = $rowData['storyNote']; 
                                        ?>
                                        <div class="col-12 mb-2">
                                            <div class="card" style="text-align:justify;">
                                                <div class="card-body story_line">
                                                    <p class="hidden"><?php echo $rowData['storyId']; ?></p>
                                                    <img src="<?php if(!empty($rowData['userImage'])) { echo 'data:image/png;base64,'.base64_encode($rowData['userImage']); } else { echo "img/avatar.jpg";} ?>" alt="" width="70px" height="70px" class="profile-pix mb-2">
                                                    <h5 class="card-text font-weight-bolder"><?php echo $rowData['storyTitle']; ?> </h5>
                                                    <small>Posted by: <?php echo "<strong>".$rowData['userName']."</strong>"; ?></small>
                                                    <p class="card-text read-more"><?php echo nl2br($content); ?> </p>
                                                </div>
                                                <div class="card-footer text-muted">
                                                    <i class="fas fa-thumbs-up"></i> <?php echo $rowData['thumbsup'] ." ". "People like this.";  ?>
                                                </div>
                                            </div>
                                        </div>
                                         <!-- working with comments -->
                                            <div class="container comment-form">
                                                <h6 class="text-left font-weight-bold">Add Comments</h6>
                                                <form method="POST" id="comment_form">
                                                    <div class="form-group">
                                                        <input type="hidden" name="storyId" id="storyId" class="form-control" placeholder="Enter Name" value = "<?php echo $rowData['storyId']; ?>" />
                                                        <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="Enter Name" />
                                                    </div>
                                                    <div class="form-group comment-textarea">
                                                        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Comment" rows="5"></textarea>
                                                    </div>
                                                    <div class="form-group" align="left">
                                                        <input type="hidden" name="comment_id" id="comment_id" value="0" />
                                                        <input type="submit" name="submit" id="submit" class="btn btn-info btn-sm" value="Submit" />
                                                    </div>
                                                </form>
                                                <span id="comment_message"></span>
                                                <br />
                                                <div id="display_comment"></div>
                                            </div>
                                        <!-- working with comments -->
                                    <?php } ?>
                            </div>
                           
                        </div>
                    </div>
                    <!-- row-profile -->
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
    
    <!-- comment-processing -->
    <script>
        $(document).ready(function(){
        
        $('#comment_form').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
        url:"add_comment.php",
        method:"POST",
        data:form_data,
        dataType:"JSON",
        success:function(data)
        {
            if(data.error != '')
            {
            $('#comment_form')[0].reset();
            $('#comment_message').html(data.error);
            $('#storyId').val();
            $('#comment_id').val('0');
            console.log(data);
            load_comment();
            }
        }
        })
        });

        load_comment();

        function load_comment()
        {
        $.ajax({
        url:"fetch_comment.php",
        method:"POST",
        success:function(data)
        {
            $('#display_comment').html(data);
        }
        })
        }

        $(document).on('click', '.reply', function(){
        var comment_id = $(this).attr("id");
        $('#comment_id').val(comment_id);
        $('#comment_name').focus();
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
        $(".alert-danger").delay(500).show(10, function() {
        $(this).delay(3000).hide(10, function() {
            $(this).remove();
        });
    }); // /.alert
    </script>
</body>

</html>