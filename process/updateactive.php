<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid=="")
{
  header("location:../index.html");
}


  $UserID = $_SESSION['Userid'];
  $Sql = "UPDATE users SET IsActive = 0, chk_logoff = 0 WHERE ID = $UserID";
  mysqli_query($conn,$Sql);
  mysqli_close($conn);

?>
