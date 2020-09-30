<?php 
session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

// include("includes/connection.php");
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
    <link rel="stylesheet" href="datatables/jquery.dataTables.min.css" />
     <!-- FooTables -->
    <link rel="stylesheet" href="footable/css/footable.core.css" type="text/css" />
    <link rel="stylesheet" href="footable/css/footable.standalone.css" type="text/css" />
</head>

<body onload = "viewData()" >

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container"></div>
        <button class="navbar-toggler sideMenuToggler" type="button">
                <i class="fas fa-bars"></i>
            </button>

        <a class="navbar-brand" href="#"><?php echo "$registered_user"; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
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
                    </i><span class="text">Read</span></a></li>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="container-fluid" style="margin-top:35px;">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="page-heading p-3 text-center font-weight-bolder"> <i class="glyphicon glyphicon-list"></i> MY DAILY SCRIPTURES</div>
                                    </div> <!-- /panel-heading -->
                                    <div class="panel-body">
                                        <!-- <div class="table-responsive"> -->
                                            <table id="StudentTable" class="footable table table-border" style="text-align:left;">
                                                <thead>
                                                <tr class="bg-dark">
                                                    <th style="width:20px;">NoteID</th>
                                                    <th>Daily Scriptures</th>
                                                    <th>Date Created</th>
                                                </tr>
                                                </thead>
                                            <tbody>
                                                
                                            </tbody>
                                            <tfoot class="hide-if-no-paging">
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="pagination pagination-centered" data-page-navigation=".pagination" data-page-size="20"></div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            </table>
                                        <!--</div> table responsive -->
                                    </div> <!-- /panel-body -->
                                </div> <!-- /panel -->	
                            </div><!-- container -->
                            <!-- DataTable script -->
                            <script type="text/javascript">
                                $(function(){
                                $('.footable').footable(); 
                                });
                            </script>

                            <script type="text/javascript">
                                function viewData() {

                                    $.ajax ({
                                        url: 'viewDiary.php?p=view',
                                        method: 'GET'
                                    }).done(function(data) {
                                        $('tbody').html(data)
                                        tableData()
                                    })
                                }
                                function tableData() {
                                    // $("#StudentTable").DataTable();
                                    $('#StudentTable').Tabledit({
                                        url: 'viewDiary.php',
                                        eventType: 'dblclick',
                                        editButton: true,
                                        deleteButton: true,    
                                        hideIdentifier: false,
                                        buttons: {
                                            edit: {
                                                class: 'btn btn-sm btn-warning',
                                                html: '<span class="glyphicon glyphicon-pencil"></span> Edit',
                                                action: 'edit'
                                            },
                                            delete: {
                                                class: 'btn btn-sm btn-danger',
                                                html: '<span class="glyphicon glyphicon-trash"></span> Trash',
                                                action: 'delete'
                                            },
                                            save: {
                                                class: 'btn btn-sm btn-success',
                                                html: 'Save',
                                                action: 'save'
                                            },
                                            restore: {
                                                class: 'btn btn-sm btn-warning',
                                                html: 'Restore',
                                                action: 'restore'
                                            },
                                            confirm: {
                                                class: 'btn btn-sm btn-default',
                                                html: 'Confirm'
                                            }
                                        },
                                        columns: {
                                            identifier: [0, 'diaryId'],
                                            editable: [[1, 'diaryNote']]
                                        },
                                        onSuccess: function(data, textStatus, jqXHR) {
                                            viewData()
                                        },
                                        onFail: function(jqXHR, textStatus, errorThrown) {
                                            console.log('onFail(jqXHR, textStatus, errorThrown)');
                                            console.log(jqXHR);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        },
                                        onAjax: function(action, serialize) {
                                            console.log('onAjax(action, serialize)');
                                            console.log(action);
                                            console.log(serialize);
                                        }
                                    });
                                }
                                
                            </script>
                            <!-- end script -->
                        </div>
                    </div>
                </div>
                <!-- container-fluid -->
            </main>
            <footer class="bg-dark p-2 mt-5 align-baseline fixed-bottom">
                <p class="text-right">&copy;Copyrights reserved 2020. Olamartins.</p>
            </footer>
        </div>
        <!--content -->

    </div><!-- wrapper-container -->

    <script src="./js/jquery-3.4.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
      <!-- FooTables -->
    <script type="text/javascript" src="footable/js/footable.js"></script>
    <script type="text/javascript" src="footable/js/footable.filter.js"></script>
    <script type="text/javascript" src="footable/js/footable.paginate.js"></script>
    <script src="./js/jquery.slimscroll.min.js"></script>
    <script src="./js/jquery.tabledit.js"></script>
	<script src="datatables/jquery.dataTables.min.js"></script>
   
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