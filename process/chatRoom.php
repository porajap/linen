<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'GetSite') {
    GetSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetDep') {
    GetDep($conn);
  } else  if ($_POST['FUNC_NAME'] == 'createDocument') {
    createDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveMessage') {
    saveMessage($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showMessage') {
    showMessage($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDocument') {
    showDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'selectDocument') {
    selectDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveDocument') {
    saveDocument($conn);
  }
}

function saveDocument($conn){
  $queryUpdate = $_POST['queryUpdate'];
  $Sql = $queryUpdate;

  if(mysqli_multi_query($conn, $Sql)){
    echo "1";
  }


  exit();
  mysqli_close($conn);
  die;
}

function GetSite($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;
  if ($lang == 'en') {
    if ($PmID != 1) {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    } else {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  } else {
    if ($PmID != 1) {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    } else {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetDep($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;

  if ($PmID == 2 || $PmID == 5 || $PmID == 6) {
    $wheredep = "";
  } else {
    $wheredep = "AND department.DepCode = '$DepCode'";
  }


  $Sql = "SELECT department.DepCode,department.DepName
      FROM department WHERE department.IsStatus = 0 $wheredep  AND department.HptCode = '$HptCode1' ORDER BY department.DepName ASC ";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function CreateDocument($conn)
{
  $lang = $_SESSION['lang'];
  $PmID = $_SESSION['PmID'];
  $userid   = $_SESSION["Userid"];
  $Site = $_POST['Site'];
  $Dep = $_POST['Dep'];
  $txtTopic = $_POST['txtTopic'];

  $return = array();




  $Sql = "SELECT CONCAT('CR',lpad('$Site', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow 
  FROM chat_room
  LEFT JOIN department on chat_room.DepCode = department.DepCode
  LEFT JOIN site on department.HptCode = site.HptCode
  WHERE DocNo Like CONCAT('CR',lpad('$Site', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  ORDER BY DocNo DESC LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {

    $DocNo = $row["DocNo"];
    $RecNow = $row["RecNow"];
    if ($lang == 'en') {
      $date2 = explode("-", $row['DocDate']);
      $newdate = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $row['DocDate']);
      $newdate = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }



    $Sql = "INSERT INTO chat_room (
      DocNo,
      DocDate,
      DepCode,
      chat_room.Modify_Code,
      chat_room.Modify_Date,
      SiteCode,
      Topic,
      IsStatus,
      CheckPm
    )
    VALUES
      (
        '$DocNo',
        DATE(NOW()),
        '$Dep',
        $userid,
        NOW(),
        '$Site',
        '$txtTopic',
        0,
        $PmID
      )";


    mysqli_query($conn, $Sql);


    $SqlUser = "SELECT users.ThName FROM users WHERE ID = '$userid' ";
    $meQuery_users = mysqli_query($conn, $SqlUser);
    $row_users = mysqli_fetch_assoc($meQuery_users);
    $ThName = $row_users['ThName'];




    array_push($return, array(
      'txtDocNo' => $DocNo,
      'txtDocDate' => $newdate,
      'txtCreate' => $ThName

    ));
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function saveMessage($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $post = $_POST["post"];
  $docNo = $_POST["docNo"];
  $count = 0;
  $return = array();

  if ($PmID == 2 || $PmID == 5 || $PmID == 6) {
    $DepCode = $_SESSION['DepCode'];
  } else {
    $DepCode = $_POST["Dep"];
  }


  $Sql = "INSERT INTO chat_room_detail SET DocNo = '$docNo' , message = '$post' , chatDate = NOW() , depCode = '$DepCode'   ";
  mysqli_query($conn, $Sql);


  $Sql2 = "UPDATE chat_room SET isStatus = '0' , CheckPm = $PmID WHERE  DocNo = '$docNo'";
  mysqli_query($conn, $Sql2);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showMessage($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $docNo = $_POST["docNo"];
  $return = array();



  $Sql = "SELECT
            chat_room_detail.message,
            chat_room_detail.depCode,
            department.DepName 
          FROM
            chat_room_detail
            INNER JOIN department ON chat_room_detail.depCode = department.DepCode 
          WHERE
            DocNo = '$docNo' AND department.HptCode = '$HptCode1'  ORDER BY chatDate ASC";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }


  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function showDocument($conn)
{
  $Site = $_POST['Site'];
  $Dep = $_POST['Dep'];
  $sDate = $_POST['sDate'];
  $status = $_POST['status'];
  $lang = $_SESSION['lang'];
  $return = array();
  $whereDate= "";

  if($sDate != "-543--" && $sDate != "--" ){
    $whereDate = "AND chat_room.DocDate = '$sDate' ";
  }else{
    $whereDate = "AND chat_room.DocDate = DATE(NOW()) ";
  }

  if($status == 1){
    $whereStatus = "AND chat_room.isStatus = 0";
  }else if($status == 2){
    $whereStatus = "AND ( chat_room.isStatus = 1 OR chat_room.isStatus = 2 )";
  }else if($status == 3){
    $whereStatus = "AND chat_room.isStatus = 9";
  }

  if($Dep != "0"){
    $wheredep = "AND chat_room.DepCode = '$Dep' AND department.DepCode = '$Dep'";
  }else{
    $wheredep = "";
  }



  $Sql = "SELECT
        chat_room.DocNo,
        chat_room.DocDate,
        department.DepName,
        chat_room.isStatus,
        TIME( chat_room.Modify_Date ) AS xTime,
        users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
        users.FName
        FROM
        chat_room
        INNER JOIN department ON chat_room.DepCode = department.DepCode
        INNER JOIN users ON chat_room.Modify_Code = users.ID 
      WHERE
        chat_room.SiteCode = '$Site' AND department.HptCode = '$Site' $wheredep  $whereDate  $whereStatus ORDER BY chat_room.Modify_Date DESC";
        
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
      $Result['ThName'] = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
      $Result['ThName']   = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
    }
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function selectDocument($conn)
{
  $DocNo = $_POST['DocNo'];
  $lang = $_SESSION['lang'];
  $HptCode = $_SESSION['HptCode'];
  $return = array();

  $Sql = "SELECT
          chat_room.DocNo,
          chat_room.DocDate,
          department.DepName,
          department.DepCode,
          users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
          users.FName,          
          TIME( chat_room.Modify_Date ) AS xTime,
          chat_room.IsStatus,
          chat_room.Topic,
          site.HptName
        FROM
          chat_room
          INNER JOIN department ON chat_room.DepCode = department.DepCode
          INNER JOIN users ON chat_room.Modify_Code = users.ID
          INNER JOIN site ON chat_room.SiteCode = site.HptCode 
        WHERE
        chat_room.DocNo = '$DocNo' AND department.HptCode = '$HptCode' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
      $Result['FName'] = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
      $Result['FName']   = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
    }
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
