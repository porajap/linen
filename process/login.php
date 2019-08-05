<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function checklogin($conn,$DATA)
{
  if (isset($DATA)) {
    $user = $DATA['USERNAME'];
    $password = $DATA['PASSWORD'];
    $boolean = false;
    $Sql = "SELECT
            users.ID,
            users.UserName,
            users.`Password`,
            users.lang,
            users.FName,
            permission.PmID,
            permission.Permission,
            site.HptCode,
            site.HptName,
            users.Count,
            users.TimeOut,
            users.IsActive,
            users.chk_logoff,
            users.pic
            FROM permission
            INNER JOIN users ON users.PmID = permission.PmID
            INNER JOIN site ON users.HptCode = site.HptCode
        WHERE users.UserName = '$user' AND users.`Password` = '$password' AND users.IsCancel = 0";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ID                   = $Result['ID'];
      $_SESSION['Userid']   = $Result['ID'];
      $_SESSION['Username'] = $Result['UserName'];
      $_SESSION['FName']    = $Result['FName'];
      $_SESSION['PmID']     = $Result['PmID'];
      $_SESSION['HptCode']  = $Result['HptCode'];
      $_SESSION['HptName']  = $Result['HptName'];
      $_SESSION['TimeOut']  = $Result['TimeOut'];
      $_SESSION['chk_logoff']  = $Result['chk_logoff'];
      $_SESSION['pic']  = $Result['pic']==null?'default_img.png':$Result['pic'];
      $_SESSION['lang']     = $Result['lang']==null?'th':$Result['lang'];
      $IsActive  = $Result['IsActive'];

      $Count = $Result['Count'];
      // $FirstName = $Result['FirstName'];
      $boolean = true;

      mysqli_query($conn,$Sql);

    }

    if($boolean){
      $return['Count'] = $Count;
      if($Count != 0){

        if($IsActive == 0){
          $return['status'] = "success";
          $return['form'] = "chk_login";
          $return['msg'] = "Login Success";
          $Sql = "UPDATE users SET users.IsActive = 1 WHERE users.ID = $ID";
          mysqli_query($conn,$Sql);
        }else if($IsActive == 1){
          $return['status'] = "failed";
          $return['msg'] = "Username Password is Active";
          echo json_encode($return);
          mysqli_close($conn);
          die;
        }
      }else{
        // $return['status'] = "success";
        $return['status'] = "change_pass";
        $return['username'] = $user;
        $return['password'] =$password;
      }
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "Username or Password is Wrong!";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }
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
      $return['form'] = "change_password";
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

function rand_string( $length ) {
  $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#$&*";
  $size = strlen( $chars );
  $str = '';
  for( $i = 0; $i < $length; $i++ ) {
    $str .= $chars[ rand( 0, $size - 1 ) ];
  }
  return $str;
}

function sendmail($conn,$DATA)
{
  if (isset($DATA)) {
    $email = $DATA['email'];
    $user = $DATA['user'];
    $newpassword = rand_string(5);

    $Sql = "UPDATE users SET users.`Password` = '$newpassword',Count = 0,users.IsActive = 0 WHERE users.email = '$email' AND users.Username = '$user'";
    $Chk = mysqli_query($conn,$Sql);
    if($Chk){
        $Sql = "SELECT users.UserName,users.`Password`,users.FName
              FROM users
              WHERE users.email = '$email'";
        $meQuery = mysqli_query($conn,$Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $return['UserName'] = $Result['UserName'];
          $return['Password'] = $Result['Password'];
          $return['FName']    = $Result['FName'];
        }
        $return['status'] = "success";
        $return['form']   = "sendmail";
        $return['msg']    = "Chang password to email success.";
        $return['email']  = $email;

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
  // checklogin($conn,$DATA);
  if ($DATA['STATUS'] == 'checklogin') {
    checklogin($conn, $DATA);
  }else if ($DATA['STATUS'] == 'cPassword') {
    cPassword($conn, $DATA);
  }else if ($DATA['STATUS'] == 'sendmail') {
    sendmail($conn, $DATA);
  }
}else{
	$return['status'] = "error";
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า [ $FirstName ]';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
