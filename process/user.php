<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  $xHptCode = $DATA['HptCode'];
  if($xHptCode==""){
    $xHptCode = 1;
  }

  $Keyword = $DATA['Keyword'];
  $Sql="SELECT users.ID,users.FName,users.`Password`,users.UserName,users.email,
        -- CONCAT(IFNULL(employee.FirstName,''),' ',IFNULL(employee.LastName,'')) AS xName
        permission.Permission, HptName
        FROM users
        INNER JOIN permission ON users.PmID = permission.PmID
        INNER JOIN site ON site.HptCode = users.HptCode
        WHERE users.IsCancel = 0 AND ( users.FName LIKE '%$Keyword%') AND site.HptCode = '$xHptCode'";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ID'] = $Result['ID'];
    $return[$count]['FName'] = $Result['FName'];
    $return[$count]['Password'] = $Result['Password'];
    $return[$count]['UserName'] = $Result['UserName'];
    $return[$count]['email'] = $Result['email'];
	  $return[$count]['Permission'] = $Result['Permission'];
	  $return[$count]['HptName'] = $Result['HptName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM
          site
					WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}



function getdetail($conn, $DATA)
{
  $ID = $DATA['ID'];

  //    $Sqlx = "INSERT INTO log ( log ) VALUES ('ID : $ID')";
  //    mysqli_query($conn,$Sqlx);

  $Sql = "SELECT users.ID,users.UserName,users.`Password`,users.FName,site.HptName,site.HptCode,permission.Permission,
      permission.PmID, factory.FacCode , users.email , users.Active_mail, users.pic
        FROM users
        INNER JOIN permission ON users.PmID = permission.PmID
        INNER JOIN site ON users.HptCode = site.HptCode
        LEFT JOIN factory ON factory.FacCode = users.FacCode  
        WHERE users.ID = $ID AND users.IsCancel = 0";
        $return['sql'] = $Sql;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return['ID'] = $Result['ID'];
      $return['UserName'] = $Result['UserName'];
      $return['Password'] = $Result['Password'];
      $return['FName'] = $Result['FName'];
      $return['Permission'] = $Result['Permission'];
      $return['PmID'] = $Result['PmID'];
      $return['HptName'] = $Result['HptName'];
      $return['HptCode'] = $Result['HptCode'];
      $return['FacCode'] = $Result['FacCode'];
      $return['email'] = $Result['email'];
      $return['xemail'] = $Result['Active_mail'];
      $return['pic'] = $Result['pic']==null?'default_img.png':$Result['pic'];

  }

    $count = 0;
    $Sql = "SELECT permission.PmID,permission.Permission FROM permission";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return['Pm'.$count]['xPmID']  = $Result['PmID'];
        $return['Pm'.$count]['xPermission']  = $Result['Permission'];
        $count++;
    }
    $return['PmCnt'] = $count;

    $count = 0;
    $Sql = "SELECT site.HptCode,site.HptName FROM site";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return['hos'.$count]['xHptCode']  = $Result['HptCode'];
        $return['hos'.$count]['xHptName']  = $Result['HptName'];
        $count++;
    }
    $return['EmpCnt'] = $count;

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getEmployee($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT employee.EmpCode,CONCAT(IFNULL(employee.FirstName,''),' ',IFNULL(employee.LastName,'')) AS xName 
            FROM employee WHERE employee.IsStatus = 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['EmpCode']  = $Result['EmpCode'];
    $return[$count]['xName']  = $Result['xName'];
    $count++;
  }
  $return['status'] = "success";
  $return['form'] = "getEmployee";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function getHotpital($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT site.HptCode,site.HptName FROM site 	WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}


function getHotpital_user($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT site.HptCode,site.HptName 
  FROM site  
  -- INNER JOIN site ON users.HptCode = site.HptCode 
  WHERE IsStatus = 0 ";
  
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital_user";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}


function getPermission($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT permission.PmID,permission.Permission FROM permission";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['PmID']  = $Result['PmID'];
    $return[$count]['Permission']  = $Result['Permission'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getPermission";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function CancelItem($conn, $DATA)
{
    $UsID = $DATA['UsID'];
    $Sql = "UPDATE users SET IsCancel = 1 WHERE ID = $UsID;";
    mysqli_query($conn, $Sql);
    $return['status'] = "success";
    $return['form'] = "CancelItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;

}

function getFactory($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
            factory.FacCode,
            factory.FacName
          FROM factory
          WHERE factory.IsCancel = 0";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacCode'] = $Result['FacCode'];
    $return[$count]['FacName'] = $Result['FacName'];
    $count++;
  }

    $return['status'] = "success";
    $return['form'] = "getFactory";
    echo json_encode($return);
    mysqli_close($conn);
    die;

}


function AddItem($conn, $DATA)
{
    $count = 0;
    $UsID = $DATA['UsID'];
    $UserName = $DATA['UserName'];
    $Password = $DATA['Password'];
    $host = $DATA['host'];
    $FName = $DATA['FName'];
    $Permission = $DATA['Permission'];
    $facID = $DATA['facID'];
    $email = $DATA['email'];
    $xemail = $DATA['xemail'];

    $countMail = "SELECT COUNT(*) as cnt FROM users WHERE HptCode = '$host' AND Active_mail = $xemail";
    $MQuery = mysqli_query($conn, $countMail);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {

    if ($MResult['cnt'] == 0){
      $xxemail = 1;
    }else{
      $xxemail = 0;

    }
  }
    if($UsID != ""){
        $Sql = "UPDATE users SET 
        users.HptCode='$host',
        users.UserName='$UserName',
        users.`Password`='$Password',
        users.FName='$FName',
        users.PmID=$Permission,
        users.FacCode=$facID,
        users.email='$email',
        users.Active_mail='$xxemail',
        users.Modify_Date=NOW() 
        WHERE users.ID = $UsID";

    $return['sql']=$Sql;
        if(mysqli_query($conn, $Sql)){
            $return['status'] = "success";
            $return['form'] = "AddItem";
            $return['msg'] = "Edit Success";
        }else{
            $return['status'] = "failed";
            $return['msg'] = "Edit Failed";
        }
    }else{
        $Sql = "INSERT INTO users(
        users.HptCode,
        users.UserName,
        users.`Password`,
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
            $xxemail
          )";


  $return['sql']=$Sql;
        if(mysqli_query($conn, $Sql)){
            $return['status'] = "success";
            $return['form'] = "AddItem";
            $return['msg'] = "Insert Success";
        }else{
            $return['status'] = "failed";
            $return['msg'] = "Insert Failed";
        }
    }
    echo json_encode($return);
    mysqli_close($conn);
}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getEmployee') {
        getEmployee($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getHotpital') {
        getHotpital($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getPermission') {
        getPermission($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
          AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getHotpital_user') {
        getHotpital_user($conn,$DATA);
      }
      else if ($DATA['STATUS'] == 'getFactory') {
        getFactory($conn,$DATA);
      }  else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn,$DATA);
      }



      
}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
