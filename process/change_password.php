<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function cPassword($conn,$DATA)
{
  if (isset($DATA)) {
    $Cnt1 = 0;
    $Cnt2 = 0;
    $oldpassword = $DATA['oldpassword'];
    $newpassword = $DATA['newpassword'];
    $confirmpassword = $DATA['confirmpassword'];
    $Username = $DATA['Username'];
    $boolean = false;
    $Sql = "SELECT users.ID
            FROM users
            WHERE users.UserName = '$Username' AND users.`Password` = '$oldpassword' AND users.IsCancel = 0";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ID = $Result['ID'];
      $Cnt1 = 1;
    }

    if($newpassword == $confirmpassword) $Cnt2 = 1;


    if($Cnt1 == $Cnt2){
      $Sql = "UPDATE users SET `Password` = '$newpassword',Count = (Count+1) WHERE users.ID = $ID";
      $Chk = mysqli_query($conn,$Sql);
      if($Chk) $boolean = true;
    }

    if($boolean){
      $return['status'] = "success";
      $return['Count'] = $Count;
      $return['msg'] = "Chang password success.";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "Not found chang password !";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }
}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);
  cPassword($conn,$DATA);
}else{
	$return['status'] = "error";
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า [ $FirstName ]';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
