<?php
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

    $count = 0;
    $UsID = $_POST['UsID'];
    $UserName = $_POST['UserName'];
    $Password = $_POST['Password'];
    $host = $_POST['host'];
    $FName = $_POST['FName'];
    $Permission = $_POST['Permission'];
    $facID = $_POST['facID'];
    $email = $_POST['email'];
    $xemail = $_POST['xemail'];

    $Username = $_POST['username'];
    $UserID = $_POST['UsID'];

    $newname = $Username.$UserID;
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


    // $countMail = "SELECT COUNT(*) as cnt FROM users WHERE HptCode = '$host' AND Active_mail = $xemail";
    // $MQuery = mysqli_query($conn, $countMail);
    // while ($MResult = mysqli_fetch_assoc($MQuery)) {

    //     if ($MResult['cnt'] == 0){
    //         $xxemail = 1;
    //     }else{
    //         $xxemail = 0;
    //     }
    // }
    if($UsID != ""){
        if($_FILES['file']!=""){
            $newname = $Username.$UsID;
            $lastname = explode('.',$_FILES['file']['name']);
            $filename = $newname.'.'.$lastname[1];
            copy($_FILES['file']['tmp_name'], '../profile/img/' . $filename);
            $Sql = "UPDATE users SET 
                users.HptCode='$host',
                users.UserName='$UserName',
                users.Password ='$Password',
                users.FName='$FName',
                users.PmID=$Permission,
                users.FacCode=$facID,
                users.email='$email',
                users.pic='$filename',
                users.Active_mail='$xemail',
                users.Modify_Date=NOW() 
                WHERE users.ID = $UsID";
        }else{
            $Sql = "UPDATE users SET 
                users.HptCode='$host',
                users.UserName='$UserName',
                users.Password='$Password',
                users.FName='$FName',
                users.PmID=$Permission,
                users.FacCode=$facID,
                users.email='$email',
                users.Active_mail='$xemail',
                users.Modify_Date=NOW() 
                WHERE users.ID = $UsID";
        }
        if(mysqli_query($conn, $Sql)){
            $result= "editsuccess";
        }else{
            $result = "editfailed";
        }
    }else{
        if($_FILES['file']!=""){
            $newname = $Username.$UsID;
            $lastname = explode('.',$_FILES['file']['name']);
            $filename = $newname.'.'.$lastname[1];
            copy($_FILES['file']['tmp_name'], '../profile/img/' . $filename);
            $Sql = "INSERT INTO users(
                users.HptCode,
                users.UserName,
                users.Password,
                users.FName,
                users.IsCancel,
                users.PmID,
                users.lang,
                users.FacCode,
                users.Count,
                users.Modify_Date,
                users.TimeOut,
                users.email,
                users.pic,
                users.Active_mail
        
                )
                VALUES
                (
                    '$host',
                    '$UserName',
                    '$Password',
                    '$FName',
                    0,
                    $Permission,
                    'en',
                    $facID,
                    0,
                    NOW(),
                    30,
                    '$email',
                    '$filename',
                    $xemail
                )";
        }else{
            $Sql = "INSERT INTO users(
                users.HptCode,
                users.UserName,
                users.Password,
                users.FName,
                users.IsCancel,
                users.PmID,
                users.lang,
                users.FacCode,
                users.Count,
                users.Modify_Date,
                users.TimeOut,
                users.email,
                users.Active_mail
        
                )
                VALUES
                (
                    '$host',
                    '$UserName',
                    '$Password',
                    '$FName',
                    0,
                    $Permission,
                    'en',
                    $facID,
                    0,
                    NOW(),
                    30,
                    '$email',
                    $xemail
                )";
        }
        if(mysqli_query($conn, $Sql)){
            $result = "addsuccess";
        }else{
            $result = "addfailedmsg";
        }
    }

    echo json_encode($result);
    mysqli_close($conn);
