<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
// 
function checklogin($conn,$DATA)
{
  if (isset($DATA)) {
    $user = $DATA['USERNAME'];
    $password = md5($DATA['PASSWORD']);
    $boolean = false;
    $Sql = "SELECT
            users.ID,
            users.UserName,
            users.`Password`,
            users.lang,
            users.EngPerfix, users.EngName, users.EngLName,
            users.ThPerfix, users.ThName, users.ThLName,
            permission.PmID,
            permission.Permission,
            site.HptCode,
            site.HptName,
            users.Count,
            users.TimeOut,
            users.IsActive,
            users.chk_logoff,
            users.pic,
            users.DepCode,
            department.GroupCode
            FROM permission
            INNER JOIN users ON users.PmID = permission.PmID
            INNER JOIN department ON  users.DepCode = department.DepCode
            INNER JOIN site ON users.HptCode = site.HptCode
        WHERE users.UserName = '$user' AND users.`Password` = '$password' AND users.IsCancel = 0";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ID                   = $Result['ID'];
      $_SESSION['Userid']   = $Result['ID'];
      $_SESSION['Username'] = $Result['UserName'];
      if($Result['lang']=="th"){
        $_SESSION['FName']    = $Result['ThPerfix'].''.$Result['ThName'].'  '.$Result['ThLName'];
      }else{
        $_SESSION['FName']    = $Result['EngPerfix'].$Result['EngName'].'  '.$Result['EngLName'];
      }
      $_SESSION['PmID']     = $Result['PmID'];
      $_SESSION['GroupCode']     = $Result['GroupCode'];
      $_SESSION['HptCode']  = $Result['HptCode'];
      $_SESSION['Permission']  = $Result['Permission'];
      $_SESSION['HptName']  = $Result['HptName'];
      $_SESSION['TimeOut']  = $Result['TimeOut'];
      $_SESSION['DepCode']  = $Result['DepCode'];
      $_SESSION['pic']  = $Result['pic']==null?'default_img.png':$Result['pic'];
      $_SESSION['lang']     = $Result['lang']==null?'th':$Result['lang'];
      $lang1     = $Result['lang']==null?'th':$Result['lang'];
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
          if($lang1 == 'en'){
          $return['msg'] = "Login Success";
          }else{
            $return['msg'] = "เข้าสู่ระบบสำเร็จ";  
          }
          $Sql = "UPDATE users SET users.IsActive = 1 , users.chk_logoff = 0 WHERE users.ID = $ID";
          mysqli_query($conn,$Sql);

     
          $_SESSION['chk_logoff']  = 0;
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
    $Count = 0;
    $oldpassword = md5($DATA['oldpassword']);
    $newpassword = md5($DATA['newpassword']);
    $confirmpassword = md5($DATA['confirmpassword']);
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
    $newpassword2 = md5($newpassword);
    $countMail=0;
    $Sql = "UPDATE users SET users.`Password` = '$newpassword2', Count = 0, users.IsActive = 0 WHERE  users.Username = '$user'";
    $Chk = mysqli_query($conn,$Sql);
    if($Chk){
        $Sql = "SELECT users.UserName, users.Password, users.EngName, site.HptName, department.DepName
              FROM users
              INNER JOIN site ON site.HptCode = users.HptCode
              INNER JOIN department ON department.DepCode = users.DepCode
              WHERE users.UserName = '$user' ";
        $meQuery = mysqli_query($conn,$Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $return['UserName'] = $Result['UserName'];
          $return['Password'] = $newpassword;
          $return['FName']    = $Result['EngName'];
          $return['HptName']    = $Result['HptName'];
          $return['DepName']    = $Result['DepName'];
        }

          $SelectMail = "SELECT users.email, 	site.HptName
          FROM users
          INNER JOIN site ON site.HptCode = users.HptCode
          WHERE users.PmID IN (1,6)
          AND email IS NOT NULL AND NOT email = '' AND NOT email = '-'";
          $return['mail'] = $SelectMail;
          $SQuery = mysqli_query($conn,$SelectMail);
          while ($SResult = mysqli_fetch_assoc($SQuery)) {
            $return[$countMail]['email'] = trim($SResult['email']);
            $countMail++;
          }
        
        $return['status'] = "success";
        $return['form']   = "sendmail";
        $return['msg']    = "Chang password to email success.";
        // $return['email']  = $email;
        $return['countMail']  = $countMail;
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

function reset_pass($conn,$DATA)
{
  // if (isset($DATA)) {

    $user = $DATA['user'];
    $email = "";
    $Sql = "SELECT users.HptCode FROM users WHERE users.Username = '$user'";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $HptCode = $Result['HptCode'];
    }

    $Sql = "SELECT users.email FROM users
              WHERE users.Active_mail = 1 AND users.HptCode = '$HptCode' LIMIT 1";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $email = $Result['email'];
    }
      $return['status'] = "success";
      $return['form']   = "rPass";
      $return['msg']    = "Get email success.";
      $return['user'] = $user;
      $return['email'] = $email;
      echo json_encode($return);
      mysqli_close($conn);

    // if($email != ""){
    //   $return['status'] = "success";
    //   $return['form']   = "rPass";
    //   $return['msg']    = "Get email success.";
    //   $return['user'] = $user;
    //   $return['email'] = $email;
    //   echo json_encode($return);
    //   mysqli_close($conn);
    //   die;
    // }else{
    //   $return['status'] = "success";
    //   $return['form']   = "rPass";
    //   $return['email'] = "";
    //   $return['msg'] = "Not found email. !";
    //   echo json_encode($return);
    //   mysqli_close($conn);
    //   die;
    // }

  // }else{
  //   $return['status'] = "failed";
  //   $return['msg'] = "Not found chang password !";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }
}

function SetActive($conn,$DATA){
  $boolean = false;
  $Username = $DATA['Username'];
  $Password = md5($DATA['Password']);
  $count = "SELECT COUNT(*) AS cnt, HptCode FROM users WHERE UserName = '$Username' AND Password = '$Password' LIMIT 1";
  $meQuery = mysqli_query($conn, $count);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['cnt'] > 0){
      $HptCode = $Result['HptCode'];
      $update = "UPDATE users SET IsActive = 0 WHERE UserName = '$Username' AND Password = '$Password' LIMIT 1";
      mysqli_query($conn, $update);

      $mailSelect = "SELECT email FROM users WHERE HptCode = '$HptCode'  LIMIT 1";
      $mailQuery = mysqli_query($conn, $mailSelect);
      while ($MResult = mysqli_fetch_assoc($mailQuery)) {
        $Email = $MResult['email'];
      }
      $boolean = true;
    }
  }
  // $return['count'] = $count;
  // $return['update'] = $update;
  // $return['mailSelect'] = $mailSelect;
  if($boolean == true){
    $return['count']  = 1;
    $return['status'] = "success";
    $return['form']   = "SetActive";
    $return['Username'] = $Username;
    $return['Password'] = $Password;
    $return['Email'] = $Email;
    $return['msg']   = "Set login success!";
    $return['text']   = "Can go back to log in again";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['count']  = 0;
    $return['status'] = "success";
    $return['form']   = "SetActive";
    $return['msg']   = "Username or Password is Wrong!";
    echo json_encode($return);
    mysqli_close($conn);
    die;
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
  }else if ($DATA['STATUS'] == 'rPass') {
    reset_pass($conn, $DATA);
  }else if ($DATA['STATUS'] == 'SetActive') {
    SetActive($conn, $DATA);
  }
}else{
	$return['status'] = "error";
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า [ $FirstName ]';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
