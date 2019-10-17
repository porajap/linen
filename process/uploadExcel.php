<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
    $UserID = $_SESSION['Userid'];
    $HptCode = $_SESSION['HptCode'];

    // $newname = date('Y-m-d-H:i:s');
    // $lastname = explode('.',$_FILES['file']['name']);
    $filename = $_FILES['file']['name'];
    copy($_FILES['file']['tmp_name'], 'excelFiles/' . $_FILES['file']['name']);
    $Sql = "INSERT damage_file (FileName, Date, Status, UserID, HptCode)VALUES('$filename', NOW(), 0, $UserID, '$HptCode')";
    mysqli_query($conn,$Sql);
echo json_encode('success');