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
            'DD',
            lpad( '$hotpCode', 3, 0 ),
            SUBSTRING( YEAR ( DATE( NOW())), 3, 4 ),
            LPAD( MONTH ( DATE( NOW())), 2, 0 ),
            '-',
          LPAD( ( COALESCE ( MAX( CONVERT ( SUBSTRING( DocNo, 12, 5 ), UNSIGNED INTEGER )), 0 )+ 1 ), 5, 0 )) AS DocNo,
          DATE(
          NOW()) AS DocDate,
          CURRENT_TIME () AS RecNow 
        FROM
          call_dirty
          LEFT JOIN department ON call_dirty.DepCode = department.DepCode
          LEFT JOIN site ON department.HptCode = site.HptCode 
        WHERE
          DocNo LIKE CONCAT(
            'DD',
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



    $Sql = "INSERT INTO call_dirty (
      call_dirty.DocNo,
      call_dirty.DocDate,
      call_dirty.DepCode,
      call_dirty.Modify_Code,
      call_dirty.Modify_Date,
      call_dirty.revealDate,
      call_dirty.SiteCode,
      call_dirty.revealName,
      call_dirty.phoneNumber,
      call_dirty.remark
    )
    VALUES
      (
        '$DocNo',
        DATE(NOW()),
        '$DepCode',
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
    $whereDep = "AND call_dirty.DepCode = '$DepCode' AND department.DepCode = '$DepCode' ";
  }

  if($sDate != "-543--" && $sDate != "--" ){
    $whereDate = "AND call_dirty.DocDate BETWEEN '$sDate' AND '$eDate' ";
  }else{
    $whereDate = "AND call_dirty.DocDate BETWEEN DATE(NOW()) AND DATE(NOW())  ";
  }
  $Sql = "SELECT
            call_dirty.DocNo, 
            call_dirty.DocDate, 
            call_dirty.revealName, 
            department.DepName, 
            TIME(call_dirty.revealDate) AS revealDate, 
            call_dirty.IsStatus, 
            users.ThName AS approveName ,
            TIME( call_dirty.approveDate) AS approveDate, 
            call_dirty.commentDelete,
            call_dirty.phoneNumber,
            call_dirty.remark
          FROM
            call_dirty
            INNER JOIN  department  ON  call_dirty.DepCode = department.DepCode 
            LEFT JOIN users ON call_dirty.approveName = users.ID
            WHERE call_dirty.SiteCode = '$Site' AND call_dirty.DocNo LIKE '%$txtSearchDoc%' AND department.HptCode = '$Site'  $whereDate  $whereDep  ORDER BY call_dirty.DocNo DESC ";
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

  $Sql = "UPDATE call_dirty SET IsStatus = 9 , commentDelete = '$comment' , Modify_Date = NOW()  WHERE DocNo = '$DocNo' ";

  if(mysqli_query($conn, $Sql)){
    echo '1';
  }
}

function saveDocument($conn){
  $DocNo = $_POST['DocNo'];

  $Sql = "UPDATE call_dirty SET IsStatus = 2 , Modify_Date = NOW()  WHERE DocNo = '$DocNo' ";

  if(mysqli_query($conn, $Sql)){
    echo '1';
  }
}