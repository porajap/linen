<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
$UserID = $_POST['UserID'];

$Sql = "SELECT users.UserName FROM users WHERE users.ID = $UserID LIMIT 1";
$Query = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($Query)) {
    if($_FILES['file'] != null){
        $newname = $Result['UserName'];
        $lastname = explode('.',$_FILES['file']['name']);
        $filename = $newname.'.'.$lastname[1];
        copy($_FILES['file']['tmp_name'], '../profile/img/' . $filename);

        $update = "UPDATE users SET users.pic = '$filename' WHERE users.ID = $UserID";
        if(mysqli_query($conn, $update)){
            $result = "editsuccess";
            $_SESSION['pic']  = $filename==null?'default_img.png':$filename;
        }else{
            $result = "editfailed";
        }
    }else{
        $update = "UPDATE users SET users.pic = 'default_img.png' WHERE users.ID = $UserID";
        if(mysqli_query($conn, $update)){
            $result = "editsuccess";
            $_SESSION['pic']  = 'default_img.png';
        }else{
            $result = "editfailed";
        }
    }
    
}

echo json_encode($result);
mysqli_close($conn);
