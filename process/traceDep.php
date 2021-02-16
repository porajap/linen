<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'showDocument') {
    showDocument($conn);
  }else if ($_POST['FUNC_NAME'] == 'showDetailDoc') {
    showDetailDoc($conn);
  }else  if ($_POST['FUNC_NAME'] == 'GetDep') {
    GetDep($conn);
  }
}

function showDocument($conn)
{
  $lang = $_SESSION['lang'];
  $Dep = $_SESSION['DepCode'];
  $Site = $_SESSION['HptCode'];
  $sDate = $_POST['sDate'];
  $selectDep = $_POST["selectDep"];
  $PmID = $_POST["PmID"];

  if($PmID == 8){
    $whereDep = "AND shelfcount.DepCode = '$Dep' AND department.DepCode = '$Dep' ";
  }else{
    if($selectDep == "0"){
      $whereDep = "";
    }else{
      $whereDep = "AND shelfcount.DepCode = '$selectDep' AND department.DepCode = '$selectDep' ";
    }
  }

  $whereDate= "";
  if($sDate != "-543--" && $sDate != "--"){
    $whereDate = "AND shelfcount.DocDate = '$sDate' ";
  }else{
    $whereDate = "AND shelfcount.DocDate = DATE(NOW()) ";
  }
  
  $return = array();

  $Sql = "SELECT
        shelfcount.DocNo,
        shelfcount.DocDate,
        shelfcount.IsStatus  ,
        shelfcount.statusDepartment  ,
        shelfcount.remark   
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        INNER JOIN users ON shelfcount.Modify_Code = users.ID 
      WHERE
        ( shelfcount.statusDepartment = 0 OR shelfcount.statusDepartment = 1 ) 
        AND shelfcount.SiteCode = '$Site'  
        $whereDate  
        AND department.HptCode = '$Site'
        $whereDep
        AND DeliveryTime = 0 
        AND ScTime = 0
        ORDER BY shelfcount.Modify_Date DESC ";

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

function GetDep($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;


  $Sql = "SELECT department.DepCode,department.DepName
      FROM department WHERE department.IsStatus = 0  AND department.HptCode = '$HptCode1' ORDER BY department.DepName ASC ";


  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showDetailDoc($conn)
{ 
  $lang = $_SESSION['lang'];
  $DocNo = $_POST["DocNo"];
  $return = array();

  $Sql = "SELECT
  	        DATE(shelfcount.Modify_Date) AS Modify_Date,
            TIME(shelfcount.Modify_Date) AS Modify_Time,
            DATE(shelfcount.create_Time) AS create_Date,
            TIME(shelfcount.create_Time) AS create_Time,
            DATE(shelfcount.accept_Time) AS accept_Date,
            TIME(shelfcount.accept_Time) AS accept_Time,
            DATE(shelfcount.PkStartTime) AS PkStart_Date,
            TIME(shelfcount.PkStartTime) AS PkStart_Time,
            DATE(shelfcount.PkEndTime) AS PkEnd_Date,
            TIME(shelfcount.PkEndTime) AS PkEnd_Time,
            DATE(shelfcount.DvStartTime) AS DvStart_Date,
            TIME(shelfcount.DvStartTime) AS DvStart_Time,
            shelfcount.IsStatus,
            shelfcount.statusDepartment 
          FROM
            shelfcount 
          WHERE
            DocNo = '$DocNo'";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['Modify_Date']);
      $Result['Modify_Date'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];

      $date2 = explode("-", $Result['create_Date']);
      $Result['create_Date'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];

      $date2 = explode("-", $Result['accept_Date']);
      $Result['accept_Date'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];

      $date2 = explode("-", $Result['PkStart_Date']);
      $Result['PkStart_Date'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];

      $date2 = explode("-", $Result['PkEnd_Date']);
      $Result['PkEnd_Date'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];

      $date2 = explode("-", $Result['DvStart_Date']);
      $Result['DvStart_Date'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];



    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['Modify_Date']);
      $Result['Modify_Date'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);

      $date2 = explode("-", $Result['create_Date']);
      $Result['create_Date'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);

      $date2 = explode("-", $Result['accept_Date']);
      $Result['accept_Date'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);

      $date2 = explode("-", $Result['PkStart_Date']);
      $Result['PkStart_Date'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);

      $date2 = explode("-", $Result['PkEnd_Date']);
      $Result['PkEnd_Date'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);

      $date2 = explode("-", $Result['DvStart_Date']);
      $Result['DvStart_Date'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['Modify_Date'] . ' เวลา : ' . $Result['Modify_Time'] ;
    $Result['create_Date'] = $Result['create_Date'] . ' เวลา : ' . $Result['create_Time'] ;
    $Result['accept_Date'] = $Result['accept_Date'] . ' เวลา : ' . $Result['accept_Time'] ;
    $Result['PkStart_Date'] = $Result['PkStart_Date'] . ' เวลา : ' . $Result['PkStart_Time'] ;
    $Result['PkEnd_Date'] = $Result['PkEnd_Date'] . ' เวลา : ' . $Result['PkEnd_Time'] ;
    $Result['DvStart_Date'] = $Result['DvStart_Date'] . ' เวลา : ' . $Result['DvStart_Time'] ;

    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}