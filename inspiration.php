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

$fetchNote = "SELECT * FROM diary_table WHERE userId = '$registered_userId'";
$fetchNote_run = mysqli_query($conn, $fetchNote);


    $successmsg = "";
    if($_POST['action'] == "update") {
        $diaryNote = $_POST['diaryNote'];

        $query = "UPDATE diary_table SET diaryNote = '$diaryNote' WHERE diaryId = '".$_POST['diaryId']."'";
        if(mysqli_query($conn, $query)) {
            $successmsg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <h4>Cool!! Successful</h4>
                            <p>Your Note has been kept safe</p>
                        </div>";
                        header("location:inspiration.php");
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

        <a class="navbar-brand" href="#"><?php echo "$registered_user"; ?></a>
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
                    <li class="nav-item"><a href="#" class="nav-link px-2"><i class="material-icons icon">
                        dashboard
                    </i><span class="text">Dashboard</span></a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2"><i class="material-icons icon">
                        person
                    </i><span class="text">User Profile</span></a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2"><i class="material-icons icon">
                        settings
                    </i><span class="text">Settings</span></a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2"><i class="material-icons icon">
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
                            <div><?=$successmsg; ?></div>
                                <div class="row mb-5">
                                    <?php if(mysqli_num_rows($fetchNote_run) > 0) {  ?>
                                       <?php while($rowInspire = mysqli_fetch_assoc($fetchNote_run)) { ?>
                                        
                                            <div class="col-12 mb-4">
                                             
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-success"><?php echo $rowInspire['dateCreated']; ?></h6>
                                                        <p class="card-text"><?php echo $rowInspire['diaryNote']; ?> </p>
                                                        <form method="post" id="fde">
                                                        <input type="hidden" name="Id" value="<?php echo $rowInspire['diaryId']; ?>" >
                                                        <div class="dropdown float-right">
                                                            <a class="btn btn-secondary btn-sm dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                            </a>
                                                            <input type="submit" name="uu" value="Click to Update" class="float-left btn btn-sm btn-info">
                                                            <div class="dropdown-menu option" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item text-info update" href="#" name="update" id="<?php echo $rowInspire['diaryId']; ?>" ><i class="fa fa-edit text-info"></i> Edit</a>
                                                                <a class="dropdown-item text-danger" href="#"><i class="fa fa-trash text-danger"></i> Delete</a>
                                                            </div>
                                                            
                                                            </form>
                                                        </div>
                                                    </div>
                                                   
                                                  
                                                </div>
                                            </div>
                                            <?php 
                                                    
                                                    if(isset($_POST['uu'])){ 
                                                        $id = $_POST['Id'];

                                                        $sqltest = "SELECT * FROM diary_table WHERE diaryId = '$id'";
                                                        $sqlrun = mysqli_query($conn, $sqltest);
                                                       
                                                             while($craze = mysqli_fetch_assoc($sqlrun)) { ?>
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content  bg-dark">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title text-info font-weight-bolder" id="newstory">Update Note</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            
                                                                            <div class="modal-body">
                                                                                <form id="update-form" method="post" action="inspiration.php">
                                                                                    <div class="form-group newstory">
                                                                                        <textarea class="form-control diaryNotes" name="diaryNote" id="diaryNote" required><?php echo $craze['diaryNote']; ?></textarea>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="hidden" class="form-control" name="action" id="action" value="update">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="hidden" class="form-control" name="diaryId" id="diaryId">
                                                                                    </div>
                                                                                    <button type="submit" name="update" id="update" class="btn btn-info">Update</button>
                                                                                </form>
                                                                            </div>
                                                                        
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <!-- Modal -->
                                                        <?php } ?>
                                                     
                                             <?php  } ?>
                                       <?php } ?> 
                                        
                                  <?php }?>
                                </div>
                        
                                   
                            </div><!-- container -->

                            
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
    <!-- LOAD JQUERY-INLINEEDITABLE -->
    <script src="./js/jquery.inlineedit.js"></script>
    <script src="./js/custom.js"></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.update', function(){
            $('#diaryId').val($(this).attr("id"));
            $("#action").val("update");
            $('.modal-title').val("Update Note");
            $("#diaryNote").text();
            $("#insert").val("Update");
            $('#updateModal').modal("show");
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