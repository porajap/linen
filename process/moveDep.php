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
  }  else  if ($_POST['FUNC_NAME'] == 'showDocument') {
    showDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveComment') {
    saveComment($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveDocument') {
    saveDocument($conn);
  }
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


  $Sql = "SELECT department.DepCode,department.DepName
      FROM department WHERE department.IsStatus = 0 AND department.DepCode = '$DepCode' AND department.HptCode = '$HptCode1' ";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function createDocument($conn)
{
  $hotpCode1 = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $PmID = $_SESSION['PmID'];
  $DepCode = $_SESSION['DepCode'];
  $userid   = $_SESSION["Userid"];
  $Site = $_POST['Site'];
  $txtName = $_POST['txtName'];
  $txtDepTo = $_POST['txtDepTo'];
  $txtPhoneNumber = $_POST["txtPhoneNumber"];
  $txtRemark = $_POST["txtRemark"];

  $return = array();

  if ($PmID == 1) {
    $hotpCode = $Site;
  } else {
    $hotpCode = $hotpCode1;
  }


  $Sql = "SELECT
          CONCAT(
            'MD',
            lpad( '$hotpCode', 3, 0 ),
            SUBSTRING( YEAR ( DATE( NOW())), 3, 4 ),
            LPAD( MONTH ( DATE( NOW())), 2, 0 ),
            '-',
          LPAD( ( COALESCE ( MAX( CONVERT ( SUBSTRING( DocNo, 12, 5 ), UNSIGNED INTEGER )), 0 )+ 1 ), 5, 0 )) AS DocNo,
          DATE(
          NOW()) AS DocDate,
          CURRENT_TIME () AS RecNow 
        FROM
        move_department
          LEFT JOIN department ON move_department.DepCodeForm = department.DepCode
          LEFT JOIN site ON department.HptCode = site.HptCode 
        WHERE
          DocNo LIKE CONCAT(
            'MD',
            lpad( '$hotpCode', 3, 0 ),
            SUBSTRING( YEAR ( DATE( NOW())), 3, 4 ),
            LPAD( MONTH ( DATE( NOW())), 2, 0 ),
            '%' 
          ) 
        ORDER BY
          DocNo DESC 
          LIMIT 1 ";
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



    $Sql = "INSERT INTO move_department (
      move_department.DocNo,
      move_department.DocDate,
      move_department.DepCodeForm,
      move_department.DepCodeTo,
      move_department.Modify_Code,
      move_department.Modify_Date,
      move_department.revealDate,
      move_department.SiteCode,
      move_department.revealName,
      move_department.phoneNumber,
      move_department.remark
    )
    VALUES
      (
        '$DocNo',
        DATE(NOW()),
        '$DepCode',
        '$txtDepTo',
        $userid,
        NOW(),
        NOW(),
        '$hotpCode',
        '$txtName',
        '$txtPhoneNumber',
        '$txtRemark'
      )";


    mysqli_query($conn, $Sql);


    array_push($return, array(
      'txtDocNo' => $DocNo,
      'txtDocDate' => $newdate,
      'txtCreate' => $userid,
      'txtTime' => $RecNow

    ));
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showDocument($conn)
{
  $sDate = $_POST['sDate'];
  $eDate = $_POST['eDate'];
  $txtSearchDoc = $_POST['txtSearchDoc'];
  $PmID = $_SESSION['PmID'];
  $DepCode = $_SESSION['DepCode'];
  $lang = $_SESSION['lang'];
  $Site = $_SESSION['HptCode'];
  $return = array();
  $whereDate= "";

  if($PmID == 1  || $PmID == 5 || $PmID == 6 || $PmID == 2){
    $whereDep = "";
  }else{
    $whereDep = "AND move_department.DepCodeForm = '$DepCode' AND department.DepCode = '$DepCode'  ";
  }

  if($sDate != "-543--" && $sDate != "--" ){
    $whereDate = "AND move_department.DocDate BETWEEN '$sDate' AND '$eDate' ";
  }else{
    $whereDate = "AND move_department.DocDate BETWEEN DATE(NOW()) AND DATE(NOW())  ";
  }
  $Sql = "SELECT
            move_department.DocNo, 
            move_department.DocDate, 
            move_department.revealName, 
            department.DepName,
            move_department.DepCodeTo, 
            TIME(move_department.revealDate) AS revealDate, 
            users.ThName AS approveName ,
            TIME( move_department.approveDate) AS approveDate, 
            move_department.IsStatus, 
            move_department.commentDelete,
            move_department.phoneNumber,
            move_department.remark
          FROM
          move_department
            INNER JOIN  department  ON  move_department.DepCodeForm = department.DepCode 
            LEFT JOIN users ON move_department.approveName = users.ID
            WHERE move_department.SiteCode = '$Site' AND move_department.DocNo LIKE '%$txtSearchDoc%'  AND department.HptCode = '$Site'   $whereDate $whereDep ORDER BY move_department.DocNo DESC";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function saveComment($conn){
  $DocNo = $_POST['DocNo'];
  $comment = $_POST['comment'];

  $Sql = "UPDATE move_department SET IsStatus = 9 , commentDelete = '$comment' , Modify_Date = NOW()  WHERE DocNo = '$DocNo' ";

  if(mysqli_query($conn, $Sql)){
    echo '1';
  }
}

function saveDocument($conn){
  $DocNo = $_POST['DocNo'];

  $Sql = "UPDATE move_department SET IsStatus = 2 , Modify_Date = NOW()  WHERE DocNo = '$DocNo' ";

  if(mysqli_query($conn, $Sql)){
    echo '1';
  }
}

