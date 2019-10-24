<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
    $count = 0;
    $UsID = $_POST['UsID'];
    $UserName = $_POST['UserName'];
    $Password = md5($_POST['Password']);
    $host = $_POST['host'];
    $department = $_POST['department'];
    $Permission = $_POST['Permission'];
    $facID = $_POST['facID'];
    $email = $_POST['email'];
    $xemail = $_POST['xemail'];
    $Userid = $_SESSION['Userid'];
    $boolean = false ;
    $UserID = $_POST['UsID'];

    $EngPerfix = $_POST['EngPerfix'];
    $ThPerfix = $_POST['ThPerfix'];
    $EngName = $_POST['EngName'];
    $EngLName = $_POST['EngLName'];
    $ThName = $_POST['ThName'];
    $ThLName = $_POST['ThLName'];
    $remask = $_POST['remask'];

    $newname = $UserName;
    $lastname = explode('.',$_FILES['file']['name']);
    $filename = $newname.'.'.$lastname[1];

    if($xemail == 1){
        $setActive = "SELECT users.ID FROM users WHERE HptCode = '$host' AND Active_mail = 1";
        $setQuery = mysqli_query($conn, $setActive);
        while ($MResult = mysqli_fetch_assoc($setQuery)) {
            $ID = $MResult['ID'];
            $Update = "UPDATE users SET Active_mail = 0 WHERE users.ID = $ID";
            mysqli_query($conn, $Update);
        }
    }

    $Sqlz = " SELECT users.UserName FROM users WHERE users.UserName = '$UserName'";
    $meQueryz = mysqli_query($conn, $Sqlz);
    $Result =   mysqli_fetch_assoc($meQueryz);
    $User = $Result['UserName'];
    

    if($UsID != ""){
        if($_FILES['file']!=""){
            copy($_FILES['file']['tmp_name'], '../profile/img/' . $filename);
            $Sql = "UPDATE users SET 
                users.HptCode='$host',
                users.DepCode=$department,
                users.UserName='$UserName',
                users.EngPerfix='$EngPerfix',
                users.ThPerfix='$ThPerfix',
                users.EngName='$EngName',
                users.EngLName='$EngLName',
                users.ThName='$ThName',
                users.ThLName='$ThLName',
                users.PmID=$Permission,
                users.FacCode=$facID,
                users.email='$email',
                users.pic='$filename',
                users.remask='$remask',
                users.Active_mail='$xemail',
                users.Modify_Date=NOW(),
                Modify_Code =  $Userid   
                WHERE users.ID = $UsID";
        }else{
            $Sql = "UPDATE users SET 
                users.HptCode='$host',
                users.DepCode=$department,
                users.UserName='$UserName',
                users.EngPerfix='$EngPerfix',
                users.ThPerfix='$ThPerfix',
                users.EngName='$EngName',
                users.EngLName='$EngLName',
                users.ThName='$ThName',
                users.ThLName='$ThLName',
                users.PmID=$Permission,
                users.FacCode=$facID,
                users.remask='$remask',
                users.email='$email',
                users.Active_mail='$xemail',
                users.Modify_Date=NOW() ,
                Modify_Code =  $Userid   
                WHERE users.ID = $UsID";
        }
        if(mysqli_query($conn, $Sql)){
            $result= 3;
        }else{
            $result = 4;
        }
    }else{
        if($User == ""){
        if($_FILES['file']!=""){
            copy($_FILES['file']['tmp_name'], '../profile/img/' . $filename);
            $Sql = "INSERT INTO users(
                users.HptCode,
                users.DepCode,
                users.UserName,
                users.Password,
                users.EngPerfix,
                users.ThPerfix,
                users.EngName,
                users.EngLName,
                users.ThName,
                users.ThLName,
                users.IsCancel,
                users.PmID,
                users.lang,
                users.FacCode,
                users.Count,
                users.Modify_Date,
                users.TimeOut,
                users.email,
                users.remask,
                users.pic,
                users.Active_mail,
                users.DocDate,
                users.Modify_Code 
                )
                VALUES
                (
                    '$host',
                    $department,
                    '$UserName',
                    '$Password',
                    '$EngPerfix',
                    '$ThPerfix',
                    '$EngName',
                    '$EngLName',
                    '$ThName',
                    '$ThLName',
                    0,
                    $Permission,
                    'en',
                    $facID,
                    0,
                    NOW(),
                    30,
                    '$email',
                    $remask',
                    '$filename',
                    $xemail ,
                    NOW(),
                    $Userid
                )";
              $boolean = true ;
        }else{
            $Sql = "INSERT INTO users(
                users.HptCode,
                users.DepCode,
                users.UserName,
                users.Password,
                users.EngPerfix,
                users.ThPerfix,
                users.EngName,
                users.EngLName,
                users.ThName,
                users.ThLName,
                users.IsCancel,
                users.PmID,
                users.lang,
                users.FacCode,
                users.Count,
                users.Modify_Date,
                users.TimeOut,
                users.email,
                users.remask,
                users.Active_mail,
                users.DocDate,
                users.Modify_Code 
        
                )
                VALUES
                (
                    '$host',
                    $department,
                    '$UserName',
                    '$Password',
                    '$EngPerfix',
                    '$ThPerfix',
                    '$EngName',
                    '$EngLName',
                    '$ThName',
                    '$ThLName',
                    0,
                    $Permission,
                    'en',
                    $facID,
                    0,
                    NOW(),
                    30,
                    '$email',
                    '$remask',
                    $xemail,
                    NOW(),
                    $Userid
                )";
                $boolean = true ;
        }
        mysqli_query($conn, $Sql);
    }
        if($boolean){
            $result = 1;
        }else{
            $result = 2;
        }
    }

    echo json_encode($result);
    mysqli_close($conn);
