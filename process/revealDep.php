<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'GetSite') {
    GetSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetDep') {
    GetDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'createDocument') {
    createDocument($conn);
  } else if ($_POST['FUNC_NAME'] == 'insertDocDetail') {
    insertDocDetail($conn);
  } else if ($_POST['FUNC_NAME'] == 'showDocument') {
    showDocument($conn);
  } else if ($_POST['FUNC_NAME'] == 'selectDocument') {
    selectDocument($conn);
  } else if ($_POST['FUNC_NAME'] == 'showDetailDocument') {
    showDetailDocument($conn);
  } else if ($_POST['FUNC_NAME'] == 'saveDocument') {
    saveDocument($conn);
  } else if ($_POST['FUNC_NAME'] == 'cancelDocment') {
    cancelDocment($conn);
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
      FROM department WHERE department.IsStatus = 0 AND department.DepCode = '$DepCode' AND department.HptCode = '$HptCode1' ORDER BY department.DepName ASC ";



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
  $hotpCode1 = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $PmID = $_SESSION['PmID'];
  $DepCode = $_SESSION['DepCode'];
  $userid   = $_SESSION["Userid"];
  $Site = $_POST['Site'];
  $txtName = $_POST['txtName'];
  $txtPhoneNumber = $_POST["txtPhoneNumber"];

  $return = array();

  if ($PmID == 1) {
    $hotpCode = $Site;
  } else {
    $hotpCode = $hotpCode1;
  }


  $Sql = "SELECT CONCAT('SC',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow 
  FROM shelfcount
  LEFT JOIN department on shelfcount.DepCode = department.DepCode
  LEFT JOIN site on department.HptCode = site.HptCode
  WHERE DocNo Like CONCAT('SC',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
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
    $sitepath = "SELECT Site_Path FROM site WHERE HptCode = '$hotpCode' ";
    $meQuery = mysqli_query($conn, $sitepath);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $sitepath = $Result['Site_Path'];
    }


    $Sql = "INSERT INTO shelfcount (
      DocNo,
      DocDate,
      DepCode,
      Total,
      shelfcount.Modify_Code,
      shelfcount.Modify_Date,
      LabNumber,
      CycleTime,
      ScStartTime,
      DeliveryTime,
      ScTime,
      ScEndTime,
      IsMobile,
      SiteCode,
      SitePath,
      revealName,
      statusDepartment,
      phoneNumber,
      create_Time,
      create_User
    )
    VALUES
      (
        '$DocNo',
        DATE(NOW()),
        '$DepCode',
        0,
        $userid,
        NOW(),
        CONCAT(SUBSTR('$DocNo', 3, 3),YEAR (DATE(NOW())),LPAD(MONTH(DATE(NOW())), 2, 0),SUBSTR('$DocNo', 11, 6)),
        '0',
        NOW(),
        '0',
        '0',
        NOW(),
        1,
        '$hotpCode',
        '$sitepath',
        '$txtName',
        0,
        '$txtPhoneNumber',
        NOW(),
        $userid
      )";


    mysqli_query($conn, $Sql);


    $SqlUser = "SELECT users.ThName FROM users WHERE ID = '$userid' ";
    $meQuery_users = mysqli_query($conn, $SqlUser);
    $row_users = mysqli_fetch_assoc($meQuery_users);
    $ThName = $row_users['ThName'];

      $Sql_alert = "SELECT
                      site.alertTime 
                    FROM
                      site 
                    WHERE
                      HptCode = '$hotpCode'";
      $meQuery = mysqli_query($conn, $Sql_alert);

      $Result = mysqli_fetch_assoc($meQuery);
      $alertTime = $Result['alertTime'];


    array_push($return, array(
      'txtDocNo' => $DocNo,
      'txtDocDate' => $newdate,
      'txtCreate' => $ThName,
      'txtTime' => $RecNow,
      'alertTime' => $alertTime

    ));
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function insertDocDetail($conn)
{

  $hotpCode1 = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $PmID = $_SESSION['PmID'];
  $DepCode = $_SESSION['DepCode'];
  $userid   = $_SESSION["Userid"];
  $Site = $_POST['Site'];
  $DocNo = $_POST['DocNo'];
  $return = array();

  $Sql = "SELECT
            department.DepCode,
            item.CategoryCode,
            item.ItemCode,
            item.ItemName,
            item.UnitCode,
            par_item_stock.ParQty,
            item.Weight 
          FROM
            site
            INNER JOIN department ON site.HptCode = department.HptCode
            INNER JOIN par_item_stock ON department.DepCode = par_item_stock.DepCode
            INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
          WHERE  par_item_stock.DepCode = '$DepCode' 
            AND  par_item_stock.HptCode = '$hotpCode1' 
            AND  item.IsActive = 1
          GROUP BY 
            item.ItemCode
          ORDER BY 
            item.ItemName ASC 
            LImit 100 ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $_ItemCode[]          = $Result['ItemCode'];
    $_UnitCode[]          = $Result['UnitCode'];
    $_ParQty[]            = $Result['ParQty'];
    $_DepCode[]           = $Result['DepCode'];
    $_ItemName[]          = $Result['ItemName'];
    $_Weight[]            = $Result['Weight'];
    $_CategoryCode[]      = $Result['CategoryCode'];

    $return[] = $Result;
  }

  // ==============================

  $Sqlx = "INSERT INTO shelfcount_detail   (DocNo,ItemCode,UnitCode,ParQty) VALUES  ";
  foreach ($_ItemCode as $key => $value) {
    $Sqlx .= " ('$DocNo','$value', $_UnitCode[$key],$_ParQty[$key]) ,";
  }
  $Sqlx = rtrim($Sqlx, ',');
  mysqli_query($conn, $Sqlx);

  $Sqlx2 = "INSERT INTO report_sc  (DocNo,ItemCode,UnitCode,ParQty , DocDate , DepCode , ItemName , WeightPerQty , CategoryCode) VALUES ";
  foreach ($_ItemCode as $key => $value) {
    $Sqlx2 .= " ('$DocNo','$value', $_UnitCode[$key],$_ParQty[$key] , DATE(NOW()) , '$DepCode[$key]'  , '$_ItemName[$key]' , $_Weight[$key] ,  $_CategoryCode[$key] ),";
  }
  $Sqlx2 = rtrim($Sqlx2, ',');
  mysqli_query($conn, $Sqlx2);

  // ================================
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


  if($sDate != "-543--" && $sDate != "--"){
    $whereDate = "AND shelfcount.DocDate = '$sDate' ";
  }else{
    $whereDate = "AND shelfcount.DocDate = DATE(NOW()) ";
  }

  if($status == 1){
    $whereStatus = "AND shelfcount.isStatus = 0";
  }else if($status == 2){
    $whereStatus = "AND ( shelfcount.isStatus = 1 OR shelfcount.isStatus = 2 )";
  }else if($status == 3){
    $whereStatus = "AND shelfcount.isStatus = 9";
  }


  $Sql = "SELECT
        shelfcount.DocNo,
        shelfcount.DocDate,
        department.DepName,
        shelfcount.statusDepartment,
        shelfcount.isStatus,
        TIME( shelfcount.Modify_Date ) AS xTime,
        users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
        users.FName     ,
        shelfcount.phoneNumber,
        shelfcount.remark
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        INNER JOIN users ON shelfcount.Modify_Code = users.ID 
      WHERE
        ( shelfcount.statusDepartment = 0 OR shelfcount.statusDepartment = 1 ) 
        AND shelfcount.SiteCode = '$Site' AND department.HptCode = '$Site' AND shelfcount.DepCode = '$Dep' AND department.DepCode = '$Dep'  $whereDate  $whereStatus ORDER BY shelfcount.Modify_Date DESC";

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
  $return = array();

  $Sql = "SELECT
          shelfcount.DocNo,
          shelfcount.DocDate,
          department.DepName,
          users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
          users.FName,          
          TIME( shelfcount.Modify_Date ) AS xTime,
          shelfcount.IsStatus,
          shelfcount.statusDepartment,
          shelfcount.revealName,
          site.HptName,
          shelfcount.phoneNumber 
        FROM
          shelfcount
          INNER JOIN department ON shelfcount.DepCode = department.DepCode
          INNER JOIN users ON shelfcount.Modify_Code = users.ID
          INNER JOIN site ON shelfcount.SiteCode = site.HptCode 
        WHERE
          shelfcount.DocNo = '$DocNo' ";
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

function showDetailDocument($conn)
{
  $DocNo = $_POST['DocNo'];
  $return = array();

  $Sql = "SELECT
            item.ItemName,
            item.ItemCode,
            shelfcount_detail.ParQty,
            shelfcount_detail.CcQty ,
            shelfcount_detail.TotalQty 
          FROM
            shelfcount_detail
            INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
          WHERE shelfcount_detail.DocNo = '$DocNo' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function saveDocument($conn){
  $queryUpdate = $_POST['queryUpdate'];
  $DocNo = $_POST['DocNo'];
  $hotpCode = $_SESSION['HptCode'];
  $Sql = $queryUpdate;

  if(mysqli_multi_query($conn, $Sql)){

    echo "1";
  }
  

  // $Sql_alert = "SELECT
  //                 site.alertTime 
  //               FROM
  //                 site 
  //               WHERE
  //                 HptCode = '$hotpCode'";
  // $meQuery = mysqli_query($conn, $Sql_alert);

  // $Result = mysqli_fetch_assoc($meQuery);
  // $alertTime = $Result['alertTime'];


  // $SqlUpdate = "UPDATE shelfcount SET  DATE_ADD( shelfcount.alertTime, INTERVAL $alertTime MINUTE ) WHERE DocNo = '$DocNo' ";

  // if(mysqli_query($conn, $Sql)){
    
  // }


  exit();
  mysqli_close($conn);
  die;
}

function cancelDocment($conn){
  $DocNo = $_POST['DocNo'];
  $comment = $_POST['comment'];
  $userid   = $_SESSION["Userid"];

  $Sql="SELECT
        shelfcount.statusDepartment
      FROM
        shelfcount
      WHERE
        shelfcount.DocNo = '$DocNo' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    $statusDepartment = $Result['statusDepartment'];
  }

  if($statusDepartment != 1){
    $Sql = "UPDATE shelfcount SET isStatus = 9 , commentDelete = '$comment' , cancel_User = '$userid' WHERE DocNo = '$DocNo' ";
    mysqli_query($conn, $Sql);
  }


  echo json_encode($return);
  mysqli_close($conn);
  die;
}