<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'showParItemStock') {
    showParItemStock($conn);
  }else if ($_POST['FUNC_NAME'] == 'GetSite') {
    GetSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'GetDep') {
    GetDep($conn);
  } else  if ($_POST['FUNC_NAME'] == 'createDocument') {
    createDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'insertDocDetail') {
    insertDocDetail($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDocument') {
    showDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'selectDocument') {
    selectDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDetailDocument') {
    showDetailDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveDocument') {
    saveDocument($conn);
  } else  if ($_POST['FUNC_NAME'] == 'cancelDocment') {
    cancelDocment($conn);
  }else  if ($_POST['FUNC_NAME'] == 'lockPar') {
    lockPar($conn);
  }
}

function lockPar($conn)
{
  $HptCode = $_SESSION['HptCode'];

      $Sql = "SELECT
                site.par 
              FROM
                site 
              WHERE
                HptCode = '$HptCode'";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
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

function showParItemStock($conn)
{
  $DepCode = $_SESSION['DepCode'];
  $return = array();

  $Sql = "SELECT
            item.ItemName,
            par_item_stock.ParQty 
          FROM
            par_item_stock
            INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
          WHERE par_item_stock.DepCode = '$DepCode' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $return[] = $Result;

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
  $return = array();

  if ($PmID == 1) {
    $hotpCode = $Site;
  } else {
    $hotpCode = $hotpCode1;
  }


  $Sql = "SELECT
          CONCAT(
            'RQP',
            lpad( '$hotpCode', 3, 0 ),
            SUBSTRING( YEAR ( DATE( NOW())), 3, 4 ),
            LPAD( MONTH ( DATE( NOW())), 2, 0 ),
            '-',
          LPAD( ( COALESCE ( MAX( CONVERT ( SUBSTRING( DocNo, 12, 5 ), UNSIGNED INTEGER )), 0 )+ 1 ), 5, 0 )) AS DocNo,
          DATE(
          NOW()) AS DocDate,
          CURRENT_TIME () AS RecNow 
        FROM
          request_par
          LEFT JOIN department ON request_par.DepCode = department.DepCode
          LEFT JOIN site ON department.HptCode = site.HptCode 
        WHERE
          DocNo LIKE CONCAT(
            'RQP',
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



    $Sql = "INSERT INTO request_par (
      DocNo,
      DocDate,
      DepCode,
      request_par.Modify_Code,
      request_par.Modify_Date,
      SiteCode,
      revealName,
      phoneNumber
    )
    VALUES
      (
        '$DocNo',
        DATE(NOW()),
        '$DepCode',
        $userid,
        NOW(),
        '$hotpCode',
        '$txtName',
        '$txtPhoneNumber'
      )";


    mysqli_query($conn, $Sql);

    $SqlUser = "SELECT users.ThName FROM users WHERE ID = '$userid' ";
    $meQuery_users = mysqli_query($conn, $SqlUser);
    $row_users = mysqli_fetch_assoc($meQuery_users);
    $ThName = $row_users['ThName'];

    array_push($return, array(
      'txtDocNo' => $DocNo,
      'txtDocDate' => $newdate,
      'txtCreate' => $ThName,
      'txtTime' => $RecNow

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

  $Sqlx = "INSERT INTO request_par_detail   (DocNo,ItemCode,UnitCode,ParQty) VALUES  ";
  foreach ($_ItemCode as $key => $value) {
    $Sqlx .= " ('$DocNo','$value', $_UnitCode[$key],$_ParQty[$key]) ,";
  }
  $Sqlx = rtrim($Sqlx, ',');
  mysqli_query($conn, $Sqlx);


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
  $PmID = $_SESSION['PmID'];
  $lang = $_SESSION['lang'];
  $return = array();
  $whereDate= "";

  if($sDate != "-543--" && $sDate != "--" ){
    $whereDate = "AND request_par.DocDate = '$sDate' ";
  }else{
    $whereDate = "AND request_par.DocDate = DATE(NOW()) ";
  }

  if($status == 1){
    $whereStatus = "AND request_par.isStatus = 0";
  }else if($status == 2){
    $whereStatus = "AND ( request_par.isStatus = 1 OR request_par.isStatus = 2  OR request_par.isStatus = 3 ) ";
  }else if($status == 3){
    $whereStatus = "AND request_par.isStatus = 9";
  }

  if($PmID == 1 ||  $PmID == 2 || $PmID == 3 || $PmID == 5 || $PmID == 6 || $PmID == 7) {
    $whereDep= "";
  }else{
    $whereDep= "AND request_par.DepCode = '$Dep' AND department.DepCode = '$Dep'";
  }

  $Sql = "SELECT
        request_par.DocNo,
        request_par.DocDate,
        department.DepName,
        request_par.isStatus,
        TIME( request_par.Modify_Date ) AS xTime,
        TIME( request_par.approveDate ) AS approveDate,
        request_par.approveName,
        users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
        users.FName ,
        request_par.commentDelete ,
        request_par.phoneNumber
      FROM
      request_par
        INNER JOIN department ON request_par.DepCode = department.DepCode
        INNER JOIN users ON request_par.Modify_Code = users.ID 
      WHERE
         request_par.SiteCode = '$Site' AND department.HptCode = '$Site'   $whereDep   $whereDate $whereStatus ORDER BY request_par.Modify_Date DESC";

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
          request_par.DocNo,
          request_par.DocDate,
          request_par.approveName,
          department.DepName,
          department.DepCode,
          users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
          users.FName,
          TIME( request_par.Modify_Date ) AS xTime,
          request_par.IsStatus,
          request_par.revealName,
          site.HptName,
          request_par.phoneNumber
        FROM
        request_par
          INNER JOIN department ON request_par.DepCode = department.DepCode
          INNER JOIN users ON request_par.Modify_Code = users.ID
          INNER JOIN site ON request_par.SiteCode = site.HptCode 
        WHERE
        request_par.DocNo = '$DocNo' ";
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

  $SqlStatus = "SELECT request_par.IsStatus FROM request_par WHERE DocNo = '$DocNo' ";
  $meQuery_Status = mysqli_query($conn, $SqlStatus);
  $row_Status = mysqli_fetch_assoc($meQuery_Status);
  $IsStatus = $row_Status['IsStatus'];

  if($IsStatus == 0){
    $whereQty = "";
  }else{
    // $whereQty = "AND request_par_detail.Qty != 0";
    $whereQty = "";
  }

  $Sql = "SELECT
            item.ItemName,
            item.ItemCode,
            request_par_detail.ParQty,
            request_par_detail.Qty 
          FROM
          request_par_detail
            INNER JOIN item ON request_par_detail.ItemCode = item.ItemCode
          WHERE request_par_detail.DocNo = '$DocNo' $whereQty ";
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
  $Sql = $queryUpdate;

  if(mysqli_multi_query($conn, $Sql)){
    echo "1";
  }


  exit();
  mysqli_close($conn);
  die;
}

function cancelDocment($conn){
  $DocNo = $_POST['DocNo'];
  $comment = $_POST['comment'];

  $Sql = "UPDATE request_par SET IsStatus = 9 , commentDelete = '$comment'  WHERE DocNo = '$DocNo' ";

  if(mysqli_query($conn, $Sql)){
    echo '1';
  }
}