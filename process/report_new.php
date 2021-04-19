<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'GetSite') {
    GetSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetDep') {
    GetDep($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetFac') {
    GetFac($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetGroup') {
    GetGroup($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetItem') {
    GetItem($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetRound') {
    GetRound($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetRoundLinen') {
    GetRoundLinen($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetCategory') {
    GetCategory($conn);
  } else  if ($_POST['FUNC_NAME'] == 'searchReport') {
    searchReport($conn);
  }
}

function searchReport($conn)
{
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $selectReport = $_POST["selectReport"];
  $selectFac = $_POST["selectFac"];
  $selectDep = $_POST["selectDep"];
  $selectSite = $_POST["selectSite"];
  $selectGroup = $_POST["selectGroup"];
  $selectItem = $_POST["selectItem"];
  $selectRound = $_POST["selectRound"];
  $selectCategory = $_POST["selectCategory"];
  $selectRoundLinen = $_POST["selectRoundLinen"];
  $selectUsageType = $_POST["selectUsageType"];
  $FormatDate = $_POST["FormatDate"];
  $FormatDay = $_POST["FormatDay"];
  $oneday = $_POST["oneday"];
  $someday = $_POST["someday"];
  $onemonth = $_POST["onemonth"];
  $year = $_POST["year"];

  $oneday = explode('-', $oneday);
  if ($lang ==  'th') {
    $oneday = ($oneday[2] - 543) . "-" . $oneday[1] . "-" . $oneday[0];
  } else {
    $oneday = ($oneday[2] + 543) . "-" . $oneday[1] . "-" . $oneday[0];
  }

  $someday = explode('-', $someday);
  if ($lang ==  'th') {
    $someday = ($someday[2] - 543) . "-" . $someday[1] . "-" . $someday[0];
    $year = ($year - 543);
  } else {
    $someday = ($someday[2] + 543) . "-" . $someday[1] . "-" . $someday[0];
    $year = ($year + 543);
  }

  if ($selectReport == '1') {
    if ($FormatDate == 'day') {
      if ($FormatDay == '1') {
        $whereDate = "WHERE DATE( dirty.DocDate ) = DATE( '$oneday' )";
      } else {
        $whereDate = "WHERE DATE( dirty.DocDate ) BETWEEN DATE( '$oneday' ) AND DATE( '$someday' )";
      }
    } else if ($FormatDate == 'month') {
      $onemonth = newMonth($onemonth);
      $whereDate = "WHERE MONTH( dirty.DocDate ) = '$onemonth' ";
    } else if ($FormatDate == 'year') {
      $whereDate = "WHERE YEAR( dirty.DocDate ) = '$year' ";
    }

    $whereRound = "";
    if ($selectRound != 'ALL') {
      $whereRound = "AND dirty.Time_ID  = '$selectRound'";
    }

    $query = "SELECT
                site.HptName,
                factory.FacName 
              FROM
                dirty
                INNER JOIN factory ON factory.FacCode = dirty.FacCode
                INNER JOIN site ON site.hptcode = department.hptcode
              WHERE
                $whereDate 
                AND dirty.FacCode = '$selectFac'
                AND site.HptCode = '$selectSite' 
                $whereRound 
                AND dirty.isStatus <> 9 LIMIT 1 ";
    $meQuery = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($meQuery)) {
      // $return[] = $row;
    }

    // echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function newMonth($month)
{
  $mount = explode('-', $month);
  $chk = chk_mount($mount[0]);
  $month = $chk . '-' . $mount[1];
  return $month;
}

function chk_mount($month)
{
  $language = $_SESSION['lang'];
  $youMonth = trim($month);
  if ($language == 'en') {
    $MonthArray = [
      '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October',
      '11' => 'November', '12' => 'December'
    ];
  } else {
    $MonthArray = [
      '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม',
      '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
    ];
  }
  $numMonth = array_search($youMonth, $MonthArray);
  return $numMonth;
}

function GetSite($conn)
{
  $Site = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;



  if ($lang == 'en') {
    $siteName = "site.HptName AS HptName";
  } else {
    $siteName = "site.HptNameTH AS HptName";
  }

  if ($PmID == 1 && $PmID == 6) {
    $wheresite = "AND HptCode = '$Site'";
  } else {
    $wheresite = "";
  }


  $Sql = "SELECT
            site.HptCode,
            $siteName 
          FROM
            site 
          WHERE
            site.IsStatus = 0 
            $wheresite ";

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
  $Site = $_POST["Site"];
  $count = 0;


  $Sql = "SELECT
            department.DepCode,
            department.DepName 
          FROM
            department 
          WHERE
            department.IsStatus = 0 
            AND department.HptCode = '$Site'
          ORDER BY
            department.DepName ASC";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetFac($conn)
{
  $Site = $_POST["Site"];
  $lang = $_POST["lang"];
  $count = 0;
  if ($lang == 'en') {
    $facName = "factory.FacName AS FacName";
  } else {
    $facName = "factory.FacNameTH AS FacName";
  }

  $Sql = "SELECT
            factory.FacCode,
            $facName 
          FROM
            factory 
          WHERE
            factory.IsCancel = 0
            AND factory.HptCode = '$Site' ";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetGroup($conn)
{
  $Site = $_POST["Site"];
  $lang = $_POST["lang"];
  $count = 0;

  $Sql = "SELECT
            grouphpt.GroupCode,
            grouphpt.GroupName 
          FROM
            grouphpt 
          WHERE
            grouphpt.IsStatus = 0
            AND grouphpt.HptCode = '$Site' ";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetItem($conn)
{
  $Site = $_POST["Site"];
  $lang = $_POST["lang"];
  $count = 0;

  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item.IsActive 
          FROM
            item 
          WHERE
            item.IsActive = 1
            AND item.HptCode = '$Site' ";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetRound($conn)
{
  $Site = $_POST["Site"];
  $lang = $_POST["lang"];
  $count = 0;

  $Sql = "SELECT
            time_dirty.TimeName,
            time_dirty.id 
          FROM
            time_dirty
            INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id 
          WHERE
            round_time_dirty.HptCode = '$Site' 
          GROUP BY
            time_dirty.TimeName";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetRoundLinen($conn)
{
  $Site = $_POST["Site"];
  $lang = $_POST["lang"];
  $count = 0;

  $Sql = "SELECT
            time_express.Time_ID,
            time_sc.TimeName 
          FROM
            time_express
            INNER JOIN time_sc ON time_express.Time_ID = time_sc.ID 
          WHERE 
            time_express.HptCode = '$Site' 
          ORDER BY time_sc.TimeName";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetCategory($conn)
{
  $Site = $_POST["Site"];
  $lang = $_POST["lang"];
  $count = 0;

  $Sql = "SELECT
            item_category.CategoryCode,
            item_category.CategoryName 
          FROM
            item_category 
          WHERE
            item_category.IsStatus = 0";



  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}
