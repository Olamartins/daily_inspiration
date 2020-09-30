<?php

session_start();

if(isset($_SESSION['userName']) || (isset($_SESSION['userId'])) && !empty($_SESSION['userName'])) {
    $registered_user    = $_SESSION['userName'];
    $registered_userId  = $_SESSION['userId'];
} else {
    header("location:index.php");
}

$mysqli = new mysqli('localhost', 'root', '', 'diary');

if (mysqli_connect_errno()) {
  echo json_encode(array('mysqli' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
  exit;
}



$page = isset($_GET['p'])? $_GET['p'] : '';
if ($page == 'view') {
    
    $result = $mysqli->query("SELECT * FROM diary_table WHERE deleted !='1' AND userId ='$registered_userId'");
    while($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['diaryId']; ?></td>
                <td><?php echo $row['diaryNote']; ?></td>
                <td><?php echo $row['dateCreated']; ?></td>
            </tr>
        <?php
    }
} else {
    

    // Basic example of PHP script to handle with jQuery-Tabledit plug-in.
    // Note that is just an example. Should take precautions such as filtering the input data.

    header('Content-Type: application/json');

    $input = filter_input_array(INPUT_POST);



    if ($input['action'] == 'edit') {
        $mysqli->query("UPDATE diary_table SET diaryNote='". $input['diaryNote']."' WHERE diaryId='" . $input['diaryId'] . "'");
    } else if ($input['action'] == 'delete') {
        $mysqli->query("UPDATE diary_table SET deleted=1 WHERE diaryId='" . $input['diaryId'] . "'");
    } else if ($input['action'] == 'restore') {
        $mysqli->query("UPDATE diary_table SET deleted=0 WHERE diaryId='" . $input['diaryId'] . "'");
    }

    mysqli_close($mysqli);

    echo json_encode($input);
}
?>