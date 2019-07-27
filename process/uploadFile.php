<?php 
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

    $Username = $_POST['username'];
    $UserID = $_POST['UsID'];

    $newname = $Username.$UserID;
    $lastname = explode('.',$_FILES['file']['name']);
    $filename = $newname.'.'.$lastname[1];
    copy($_FILES['file']['tmp_name'], '../profile/img/' . $filename);


    $Sql = "UPDATE users SET pic = '$filename' WHERE users.ID = $UserID";
    mysqli_query($conn,$Sql);


echo json_encode('success');