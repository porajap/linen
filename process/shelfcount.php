<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $lang = $DATA["lang"];
  $HptCode = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  if($lang == 'en'){
    $Sql = "SELECT site.HptCode,site.HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
    if($PmID ==2 || $PmID ==3){
      $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
      }else{
        $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0 ";
      }  
    }else{
        $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
        if($PmID ==2 || $PmID ==3){
        $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptNameTH AS HptName1 FROM site  WHERE site.IsStatus = 0 AND site.HptCode = '$HptCode'";
        }else{
        $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptNameTH AS HptName1 FROM site  WHERE site.IsStatus = 0 ";
      }  
    }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    // $count++;
    $boolean = true;
  }
  $meQuery1 = mysqli_query($conn, $Sql1);
  while ($Result1 = mysqli_fetch_assoc($meQuery1)) {
    $return[$count]['HptCode1'] = $Result1['HptCode1'];
    $return[$count]['HptName1'] = $Result1['HptName1'];
    $return[0]['PmID'] = $PmID;
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;
  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment2($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  if($PmID ==3){
  $Hotp = $DATA["Hotp"]==null?$_SESSION['HptCode']:$DATA["Hotp"];
  }else{
    $Hotp = $DATA["Hotp"];
  }
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
      AND department.IsActive = 1
		  AND department.IsStatus = 0
      ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($meQuery = mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "getDepartment2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment2";
    $return['msg'] = "notfound";
    
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn, $DATA)
{
  $count = 0;
  $count2 = 0;
  $boolean = false;
  $boolean2 = false;
  $Hotp = $DATA["Hotp"]==null?$_SESSION['HptCode']:$DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'
  AND department.IsActive = 1
  AND department.IsStatus = 0
  ORDER BY department.DepName ASC";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }
  $return['row'] = $count;



  if ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function gettime($conn, $DATA)
{

  $count = 0;
  $count2 = 0;
  $boolean = false;
  $boolean2 = false;
  $Hotp = $DATA["Hotp"]==null?$_SESSION['HptCode']:$DATA["Hotp"];
  $Sql = "SELECT time_express.Time_ID,time_sc.TimeName
  FROM time_express
  INNER JOIN time_sc ON time_express.Time_ID = time_sc.ID
  WHERE time_express.HptCode = '$Hotp' ORDER BY time_sc.TimeName ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count2]['ID'] = $Result['Time_ID'];
    $return[$count2]['time_value'] = $Result['TimeName'];
    $count2++;
    $boolean2 = true;
  } 
  $return['row'] = $count2;

  if ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['status'] = "success";
    $return['form'] = "gettime";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "gettime";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }




}
function gettimesc($conn, $DATA)
{
  $count = 0;
  $count2 = 0;
  $boolean = false;
  $boolean2 = false;
  $Hotp = $DATA["Hotp"]==null?$_SESSION['HptCode']:$DATA["Hotp"];

  $Sql = "SELECT sc_express.Time_ID,sc_time_2.TimeName
  FROM sc_express
  INNER JOIN sc_time_2 ON sc_express.Time_ID = sc_time_2.ID
  WHERE sc_express.HptCode = '$Hotp' ORDER BY sc_time_2.TimeName ";
  // $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count2]['ID'] = $Result['Time_ID'];
    $return[$count2]['time_value'] = $Result['TimeName'];
    $count2++;
    $boolean2 = true;
  } 
  $return['row'] = $count2;

  if ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['status'] = "success";
    $return['form'] = "gettimesc";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "gettimesc";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }




}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);
function CreateDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"];
  $deptCode = $DATA["deptCode"];
  $setcount = $DATA["setcount"];
  $userid   = $DATA["userid"];
  $cycle   = $DATA["cycle"];
  $settime   = $DATA["settime"];
  $DocDate    = $DATA["DocDate"];
  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);
  $Sql = "SELECT CONCAT('SC',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM shelfcount
  INNER JOIN department on shelfcount.DepCode = department.DepCode
  WHERE DocNo Like CONCAT('SC',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  AND department.HptCode = '$hotpCode'
  ORDER BY DocNo DESC LIMIT 1";


  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($lang =='en'){
      $date2 = explode("-", $DocDate);
      $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
    }else if ($lang == 'th'){
      $date2 = explode("-", $DocDate);
      $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
    }

    $DocNo = $Result['DocNo'];
    $return[0]['DocNo']   = $Result['DocNo'];
    $return[0]['DocDate'] = $newdate;
    $return[0]['RecNow']  = $Result['RecNow'];
    $return[0]['settime']  = $settime;
    $count = 1;

  }


  if ($count == 1) {

    $Sql = "INSERT INTO shelfcount
    ( DocNo,DocDate,DepCode,RefDocNo,
      TaxNo,TaxDate,DiscountPercent,DiscountBath,
      Total,IsCancel,Detail,
      shelfcount.Modify_Code,shelfcount.Modify_Date,shelfcount.IsRef , LabNumber , CycleTime ,ScStartTime , DeliveryTime , ScTime)
      VALUES
      ( '$DocNo','$DocDate','$deptCode','',
      0,DATE(NOW()),0,0,
      0,0,'',
      $userid,NOW(),0 , CONCAT(SUBSTR('$DocNo',3,3),YEAR(DATE(NOW())),LPAD(MONTH(DATE(NOW())),2,0),SUBSTR('$DocNo',11,6)) , $cycle ,NOW() ,  '$settime' , '$setcount' )";
      mysqli_query($conn, $Sql);

      $Sql = "INSERT INTO daily_request
      (DocNo,DocDate,DepCode,RefDocNo,Detail,Modify_Code,Modify_Date)
      VALUES
      ('$DocNo',DATE(NOW()),'$deptCode','','Shelf Count',$userid,DATE(NOW()))";
      mysqli_query($conn, $Sql);


      $Sql = "SELECT users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix
      FROM users
      WHERE users.ID = $userid";

      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[0]['Record']   = $Result['FName'];
        if($lang == "en"){
          $return[0]['Record']  = $Result['EngPerfix'].$Result['EngName'].'  '.$Result['EngLName'];
        }else if($lang == "th"){
          $return[0]['Record']  = $Result['ThPerfix'].' '.$Result['ThName'].'  '.$Result['ThLName'];
        }
      }
  

      $Sql = "SELECT
        par_item_stock.RowID,
        site.HptName,
        department.DepName,
        item_category.CategoryName,
        item.ItemCode,
        item.ItemName,
        item.UnitCode,
        item_unit.UnitName,
        par_item_stock.ParQty,
        par_item_stock.TotalQty,
        item.Weight
          FROM site
        INNER JOIN department ON site.HptCode = department.HptCode
        INNER JOIN par_item_stock ON department.DepCode = par_item_stock.DepCode
        INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
        INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE  par_item_stock.DepCode = '$deptCode'
        GROUP BY item.ItemCode
        ORDER BY item.ItemName ASC LImit 100";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ItemCode = $Result['ItemCode'];
        $UnitCode = $Result['UnitCode'];
        $ParQty = $Result['ParQty'];

        $Sql = "INSERT INTO shelfcount_detail
        (DocNo,ItemCode,UnitCode,ParQty) VALUES
        ('$DocNo','$ItemCode', $UnitCode,$ParQty)";
        mysqli_query($conn, $Sql);
      }
      $boolean = true;
  } else {
    $boolean = false;
  }

    if ($boolean) {
      $return['status'] = "success";
      $return['form'] = "CreateDocument";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['form'] = "CreateDocument";
      $return['msg'] = 'cantcreate';
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
}

function ShowMenu($conn, $DATA)
{

  $boolean = false;
  $count = 0;
  $DocnoXXX = $DATA["DocnoXXX"];
  $Sql = "SELECT   site.HptName,department.DepName,shelfcount.DocNo,shelfcount.DepCode,shelfcount.DocDate,shelfcount.Total,users.FName,
  TIME(shelfcount.Modify_Date) AS xTime,shelfcount.IsStatus , shelfcount.CycleTime ,shelfcount.DeliveryTime ,shelfcount.ScTime
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON shelfcount.Modify_Code = users.ID
  WHERE shelfcount.DocNo = '$DocnoXXX'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return['DepCode']   = $Result['DepCode'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['Record']   = $Result['FName'] ;
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $return[$count]['CycleTime'] = $Result['CycleTime'];
    $return[$count]['DeliveryTime'] = $Result['DeliveryTime'];
    $return[$count]['ScTime'] = $Result['ScTime'];
    $boolean = true;
    $count++;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowMenu";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['HptName']   = "";
    $return[$count]['DepName']   = "";
    $return[$count]['DocNo']   = "";
    $return[$count]['DocDate']   = "";
    $return[$count]['Record']   = "";
    $return[$count]['RecNow']   = "";
    $return[$count]['Total']   = "0.00";
    $return['status'] = "failed";
    $return['form'] = "SelectDocument";
    $return['msg'] = "notchosen";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $PmID = $_SESSION['PmID'];
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $Hotp = $DATA["Hotp"];
  $DocNo = $DATA["docno"];
  $xDocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $selecta = $DATA["selecta"];
  $datepicker = $DATA["datepicker1"];
  //$Datepicker = $DATA["Datepicker"];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('$deptCode : $DocNo')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT site.HptName,
  department.DepName,
  shelfcount.DocNo,
  DATE (shelfcount.DocDate) AS DocDate,
  shelfcount.Total,
  users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
  TIME(shelfcount.Modify_Date)
  AS xTime,
  shelfcount.IsStatus,
  site.HptCode
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON shelfcount.Modify_Code = users.ID ";
  // if($DocNo!=null){
  //   $Sql .= " WHERE shelfcount.DocNo = '$DocNo' AND shelfcount.DocNo LIKE '%$xDocNo%'";
  // }else{
  if($PmID ==1 || $PmID==6){
    if ($Hotp != null && $deptCode == null && $datepicker == null) {
      $Sql .= " WHERE  shelfcount.DocNo LIKE '%$xDocNo%' ";
      if($xDocNo!=null){
        $Sql .= " OR shelfcount.DocNo LIKE '%$xDocNo%' ";
      }
    }else if($Hotp == null && $deptCode != null && $datepicker == null){
        $Sql .= " WHERE shelfcount.DocNo LIKE '%$xDocNo%' ";
    }else if ($Hotp == null && $deptCode == null && $datepicker != null){
      $Sql .= " WHERE DATE(shelfcount.DocDate) = '$datepicker' AND shelfcount.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode != null && $datepicker == null){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND shelfcount.DepCode = $deptCode AND shelfcount.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode == null && $datepicker != null){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND DATE(shelfcount.DocDate) = '$datepicker' AND shelfcount.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp == null && $deptCode != null && $datepicker != null){
      $Sql .= " WHERE shelfcount.DepCode = $deptCode AND DATE(shelfcount.DocDate) = '$datepicker' AND shelfcount.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode != null && $datepicker != null){
      $Sql .= " WHERE shelfcount.DepCode = $deptCode AND DATE(shelfcount.DocDate) = '$datepicker' AND site.HptCode = '$Hotp' AND shelfcount.DocNo LIKE '%$xDocNo%'";
    }
  }else{
  if ($Hotp != null && $deptCode == null && $datepicker == null) {
    $Sql .= " WHERE site.HptCode = '$Hotp' AND shelfcount.DocNo LIKE '%$xDocNo%' ";
    if($xDocNo!=null){
      $Sql .= " OR shelfcount.DocNo LIKE '%$xDocNo%' ";
    }
  }else if($Hotp == null && $deptCode != null && $datepicker == null){
      $Sql .= " WHERE shelfcount.DocNo LIKE '%$xDocNo%' ";
  }else if ($Hotp == null && $deptCode == null && $datepicker != null){
    $Sql .= " WHERE DATE(shelfcount.DocDate) = '$datepicker' AND shelfcount.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp != null && $deptCode != null && $datepicker == null){
    $Sql .= " WHERE site.HptCode = '$Hotp' AND shelfcount.DepCode = $deptCode AND shelfcount.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp != null && $deptCode == null && $datepicker != null){
    $Sql .= " WHERE site.HptCode = '$Hotp' AND DATE(shelfcount.DocDate) = '$datepicker' AND shelfcount.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp == null && $deptCode != null && $datepicker != null){
    $Sql .= " WHERE shelfcount.DepCode = $deptCode AND DATE(shelfcount.DocDate) = '$datepicker' AND shelfcount.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp != null && $deptCode != null && $datepicker != null){
    $Sql .= " WHERE shelfcount.DepCode = $deptCode AND DATE(shelfcount.DocDate) = '$datepicker' AND site.HptCode = '$Hotp' AND shelfcount.DocNo LIKE '%$xDocNo%'";
  }
  }
  // }
  $Sql.= "ORDER BY shelfcount.DocNo DESC LIMIT 500 ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($lang =='en'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
      $return[$count]['Record']  = $Result['EngPerfix'].$Result['EngName'].'  '.$Result['EngLName'];
    }else if ($lang == 'th'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
      $return[$count]['Record']  = $Result['ThPerfix'].' '.$Result['ThName'].'  '.$Result['ThLName'];
    }

    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $newdate;
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }
  $return['Count'] = $count;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    // $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SelectDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $DocNo = $DATA["DocNo"];
  $Datepicker = $DATA["Datepicker"];
  $Sql = "SELECT   site.HptCode,
    department.DepName,
    shelfcount.DocNo,
    shelfcount.DepCode,
    DATE(shelfcount.DocDate) AS DocDate,
    shelfcount.Total,
    users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
    TIME(shelfcount.Modify_Date) AS xTime,
    shelfcount.IsStatus ,
    shelfcount.CycleTime ,
    shelfcount.DeliveryTime ,
    shelfcount.jaipar ,
    shelfcount.PkStartTime,
    shelfcount.ScTime
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON shelfcount.Modify_Code = users.ID
  WHERE shelfcount.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);



  while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($lang =='en'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
      $return[$count]['Record']  = $Result['EngPerfix'].$Result['EngName'].'  '.$Result['EngLName'];
    }else if ($lang == 'th'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
      $return[$count]['Record']  = $Result['ThPerfix'].' '.$Result['ThName'].'  '.$Result['ThLName'];
    }

    $Hotp   = $Result['HptCode'];
    $return[$count]['HptName']   = $Result['HptCode'];
    $return[$count]['jaipar']   = $Result['jaipar'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DepCode']   = $Result['DepCode'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['ScTime']   = $Result['ScTime'];
    $return[$count]['DocDate']   = $newdate;
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $return[$count]['CycleTime'] = $Result['CycleTime'];
    $return[$count]['DeliveryTime'] = $Result['DeliveryTime'];
    $return[$count]['PkStartTime'] = $Result['PkStartTime']==null?0:$Result['PkStartTime'];
    $boolean = true;
    $count++;
  }
  $count2=0;
  $Sql = "SELECT time_express.Time_ID,time_sc.TimeName
  FROM time_express
  INNER JOIN time_sc ON time_express.Time_ID = time_sc.ID
  WHERE time_express.HptCode = '$Hotp' ORDER BY time_sc.TimeName ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count2]['ID'] = $Result['Time_ID'];
    $return[$count2]['time_value'] = $Result['TimeName'];
    $count2++;
  } 
  $return['row'] = $count2;

  $count3=0;
  $Sql = "SELECT sc_express.Time_ID,sc_time_2.TimeName
  FROM sc_express
  INNER JOIN sc_time_2 ON sc_express.Time_ID = sc_time_2.ID
  WHERE sc_express.HptCode = '$Hotp' ORDER BY sc_time_2.TimeName ";
  // $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count3]['ID2'] = $Result['Time_ID'];
    $return[$count3]['time_value2'] = $Result['TimeName'];
    $count3++;
  } 
  $return['row2'] = $count3;




  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "SelectDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['HptName']   = "";
    $return[$count]['DepName']   = "";
    $return[$count]['DocNo']   = "";
    $return[$count]['DocDate']   = "";
    $return[$count]['Record']   = "";
    $return[$count]['RecNow']   = "";
    $return[$count]['Total']   = "0.00";
    $return['status'] = "failed";
    $return['form'] = "SelectDocument";
    $return['msg'] = "notchosen";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }


}
function setpacking($conn, $DATA){
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  $Sql = "UPDATE shelfcount SET PkStartTime = NOW() WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  // $Sql="SELECT PkStartTime FROM shelfcount WHERE DocNo ='$DocNo'  ";
  // $meQuery = mysqli_query($conn, $Sql);
  // while ($Result = mysqli_fetch_assoc($meQuery)) {
  //   $return[0]['PkStartTime'] = $Result['PkStartTime'];
  // }

}
function ShowItem($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = str_replace(' ', '%', $DATA["xitem"]);
  $deptCode = $DATA["deptCode"];
  // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
  // mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
  par_item_stock.RowID,
  site.HptName,
  department.DepName,
  item_category.CategoryName,
  item.ItemCode,
  item.ItemName,
  item.UnitCode,
  item_unit.UnitName,
  par_item_stock.ParQty,
  par_item_stock.TotalQty
    FROM site
  INNER JOIN department ON site.HptCode = department.HptCode
  INNER JOIN par_item_stock ON department.DepCode = par_item_stock.DepCode
  INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
  INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  WHERE  par_item_stock.DepCode = $deptCode AND  item.ItemName LIKE '%$searchitem%'
  GROUP BY item.ItemCode
  ORDER BY item.ItemName ASC LImit 100";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    // $return[$count]['UsageCode'] = $Result['UsageCode'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['ParQty'] = $Result['ParQty'];
    $return[$count]['Qty'] = $Result['TotalQty']==null?0:$Result['TotalQty'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;

    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      $return['sql'] = $countM;
      if($MResult['cnt']!=0){
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
      }else{
        $xSql = "SELECT 
          item.UnitCode,
          item_unit.UnitName
        FROM item
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }

    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    $return[$count]['RowID'] = "";
    $return[$count]['UsageCode'] = "";
    $return[$count]['itemname'] = "";
    $return[$count]['UnitName'] = "";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowUsageCode($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = $DATA["xitem"]; //str_replace(' ', '%', $DATA["xitem"]);

  // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
  // mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
  item_stock.RowID,
  site.HptName,
  department.DepName,
  item_category.CategoryName,
  item_stock.UsageCode,
  item.ItemCode,
  item.ItemName,
  item.UnitCode,
  item_unit.UnitName,
  item_stock.ParQty,
  item_stock.CcQty,
  item_stock.TotalQty
  FROM site
  INNER JOIN department ON site.HptCode = department.HptCode
  INNER JOIN item_stock ON department.DepCode = item_stock.DepCode
  INNER JOIN item ON item_stock.ItemCode = item.ItemCode
  INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  WHERE item.ItemCode = '$searchitem'
  AND item_stock.IsStatus = 1
  ORDER BY item.ItemName ASC LImit 100";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['UsageCode'] = $Result['UsageCode'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;
    $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
    FROM item_multiple_unit
    INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
    WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $xQuery = mysqli_query($conn, $xSql);
    while ($xResult = mysqli_fetch_assoc($xQuery)) {
      $m1 = "MpCode_" . $ItemCode . "_" . $count;
      $m2 = "UnitCode_" . $ItemCode . "_" . $count;
      $m3 = "UnitName_" . $ItemCode . "_" . $count;
      $m4 = "Multiply_" . $ItemCode . "_" . $count;
      $m5 = "Cnt_" . $ItemCode;

      $return[$m1][$count2] = $xResult['MpCode'];
      $return[$m2][$count2] = $xResult['UnitCode'];
      $return[$m3][$count2] = $xResult['UnitName'];
      $return[$m4][$count2] = $xResult['Multiply'];
      $count2++;
    }
    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowUsageCode";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowUsageCode";
    $return[$count]['RowID'] = "";
    $return[$count]['UsageCode'] = "";
    $return[$count]['itemname'] = "";
    $return[$count]['UnitName'] = "";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getImport($conn, $DATA)
{
  $count = 0;
  $count2 = 0;
  $boolean = false;
  $Sel = $DATA["Sel"];
  $DocNo = $DATA["DocNo"];
  $DeptCode = $DATA["deptCode"];
  $xItemStockId = $DATA["xrow"];
  $ItemStockId = explode(",", $xItemStockId);
  $xqty = $DATA["xqty"];
  $nqty = explode(",", $xqty);
  $xweight = $DATA["xweight"];
  $nweight = explode(",", $xweight);
  $xunit = $DATA["xunit"];
  $nunit = explode(",", $xunit);

  $max = sizeof($ItemStockId, 0);

  for ($i = 0; $i < $max; $i++) {
    $iItemStockId = $ItemStockId[$i];
    $iqty = $nqty[$i];
    $iweight = $nweight[$i];
    $iunit1 = 0;
    $iunit2 = $nunit[$i];

    $Sql = "SELECT 
      par_item_stock.ItemCode,
      item.UnitCode,
      par_item_stock.ParQty,
      par_item_stock.TotalQty
      -- item_stock_detail.Qty
    FROM par_item_stock
    INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
    -- LEFT JOIN item_stock_detail ON item_stock_detail.ItemCode = item.ItemCode
    WHERE par_item_stock.RowID = $iItemStockId";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode   = $Result['ItemCode'];
      $UsageCode  = $Result['UsageCode'];
      $iunit1     = $Result['UnitCode'];
      $ParQty     = $Result['ParQty'];
      $Qty     = $Result['TotalQty']==null?0:$Result['TotalQty'];
      $TotalQty   = $Result['TotalQty'];
    }

    $Sql = "SELECT COUNT(*) as Cnt
    FROM shelfcount_detail
    INNER JOIN item  ON shelfcount_detail.ItemCode = item.ItemCode
    INNER JOIN dirty ON dirty.DocNo = shelfcount_detail.DocNo
    WHERE dirty.DocNo = '$DocNo'
    AND item.ItemCode = '$ItemCode'";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $chkUpdate = $Result['Cnt'];
    }
    $iqty2 = $iqty;
    if ($iunit1 != $iunit2) {
      $Sql = "SELECT item_multiple_unit.Multiply
      FROM item_multiple_unit
      WHERE item_multiple_unit.UnitCode = $iunit1
      AND item_multiple_unit.MpCode = $iunit2";

      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Multiply = $Result['Multiply'];
        $iqty2 = $iqty / $Multiply;
      }
    }

    if ($chkUpdate == 0) {
      if ($Sel == 1) {
        $Sql = "INSERT INTO shelfcount_detail
        (DocNo,ItemCode,UnitCode,ParQty,CcQty,TotalQty,IsCancel) VALUES
        ('$DocNo','$ItemCode',$iunit2,$ParQty,$iqty2,($ParQty-$iqty2),0)";

        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO shelfcount_detail_sub
        (DocNo,ItemCode,UsageCode)
        VALUES
        ('$DocNo','$ItemCode','$UsageCode')";
        mysqli_query($conn, $Sql);
        $Sql = "UPDATE item_stock SET IsStatus = 2
        WHERE UsageCode = '$UsageCode'";

        mysqli_query($conn, $Sql);
      }
    } else {
      if ($Sel == 1) {
        $Sql = "UPDATE shelfcount_detail
        SET TotalQty = ($Qty+$iqty2),CcQty = $iqty2,ParQty = $ParQty
        WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO shelfcount_detail_sub
        (DocNo,ItemCode,UsageCode)
        VALUES
        ('$DocNo','$ItemCode','$UsageCode')";
        mysqli_query($conn, $Sql);
        $Sql = "UPDATE item_stock SET IsStatus = 3
        WHERE UsageCode = '$UsageCode'";
        mysqli_query($conn, $Sql);
      }
    }
  }
  if ($Sel == 2) {
    $n = 0;
    $Sql = "SELECT COUNT(*) AS Qty FROM shelfcount_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Qty[$n] = $Result['Qty'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xQty = $Qty[$i];
      // $Sqlx = "INSERT INTO log ( log ) VALUES ('$n :: $xQty :: $ParQty')";
      // mysqli_query($conn, $Sqlx);
      if ($chkUpdate == 0) {
        $Sql = "INSERT INTO shelfcount_detail
        (DocNo,ItemCode,UnitCode,ParQty,CcQty,TotalQty,IsCancel)
        VALUES
        ('$DocNo','$ItemCode',$iunit2,$ParQty,$xQty,($ParQty-$xQty),0)";
      } else {
        $Sql = "UPDATE shelfcount_detail
        SET TotalQty = ($ParQty+$xQty),CcQty = $xQty,ParQty = $ParQty
        WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      }
      mysqli_query($conn, $Sql);
    }
  }
    $sql_update =  "SELECT
    shelfcount_detail.ItemCode,
    shelfcount_detail.ParQty,
    shelfcount_detail.CcQty,
    shelfcount_detail.TotalQty
    FROM shelfcount_detail
    WHERE shelfcount_detail.DocNo = '$DocNo'";
    $result = mysqli_query( $conn, $sql_update);
    while ($row = mysqli_fetch_array($result)) {
      $xItemCode[$n] 	= $row["ItemCode"];
      $xParQty[$n] 	= $row["ParQty"];
      $xCcQty[$n] 	= $row["CcQty"];
      $xTotalQty[$n] 	= $row["TotalQty"];
      $n++;
    }

    // for($i=0;$i<$n;$i++){
    //   $ItemCode = $xItemCode[$i];
    //   $ParQty = $xParQty[$i];
    //   $CcQty = $xCcQty[$i];
    //   $TotalQty = $xTotalQty[$i];
    //   mysqli_query($conn, "UPDATE item_stock SET ParQty=$ParQty,CcQty=$CcQty,TotalQty=$TotalQty WHERE ItemCode = '$ItemCode'");
    // }

    $n = 0;
    $Sql = "SELECT UsageCode FROM shelfcount_detail_sub WHERE DocNo = '$DocNo'";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $zUsageCode[$n] = $Result['UsageCode'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xUsageCode = $zUsageCode[$i];
      $Sql = "UPDATE item_stock SET DepCode = $DeptCode WHERE UsageCode = '$xUsageCode'";
      
      $meQuery = mysqli_query($conn, $Sql);
    }

    $Sql = "SELECT SUM( shelfcount_detail.TotalQty ) AS Qty
    FROM shelfcount_detail
    WHERE shelfcount_detail.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ttQty = $Result['Qty'];
    }
    $Sql = "UPDATE shelfcount SET Total = $ttQty WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
}

function UpdateDetailQty($conn, $DATA)
{
  $max  = $DATA["max"];
  $RowID  = $DATA["Rowid"];
  $CcQty  =  $DATA["CcQty"];
  $UnitCode =  $DATA["unitcode"];
  $Sql = "UPDATE shelfcount_detail
          INNER JOIN par_item_stock ON par_item_stock.ItemCode = shelfcount_detail.ItemCode
          SET  shelfcount_detail.CcQty = $CcQty, shelfcount_detail.TotalQty = ($max - shelfcount_detail.CcQty)
          WHERE shelfcount_detail.Id = $RowID ";
  // $return['sql'] =$Sql;
  // echo json_encode($return);
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function UpdateDetailQty_key($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $CcQty  =  $DATA["CcQty"];
  $TotalQty  =  $DATA["TotalQty"];
  $UnitCode =  $DATA["unitcode"];
  $Sql = "UPDATE shelfcount_detail
  SET CcQty = $CcQty,TotalQty = $TotalQty
  WHERE shelfcount_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function UpdateDetailWeight($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Weight  =  $DATA["Weight"];
  $Price  =  $DATA["Price"];
  $isStatus = $DATA["isStatus"];
  $DocNo = $DATA["DocNo"];

  //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
  //	mysqli_query($conn,$Sqlx);

  $Sql = "UPDATE shelfcount_detail
  SET Weight = $Weight
  WHERE shelfcount_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  //	$wTotal = 0;
  //	$Sql = "SELECT SUM(Weight) AS wTotal
  //	FROM shelfcount_detail
  //	WHERE DocNo = '$DocNo'";
  //	$meQuery = mysqli_query($conn,$Sql);
  //	while ($Result = mysqli_fetch_assoc($meQuery)) {
  //		$wTotal  	= $Result['wTotal'];
  //	}
  //    $Sql = "UPDATE shelfcount SET Total = $wTotal WHERE DocNo = '$DocNo'";
  //  	mysqli_query($conn,$Sql);

  ShowDetail($conn, $DATA);
}

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $UnitCode =  $DATA["unitcode"];
  $qty =  $DATA["qty"];
  $Sql = "UPDATE shelfcount_detail
  SET UnitCode2 = $UnitCode,Qty2 = $qty
  WHERE shelfcount_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function DeleteItem($conn, $DATA)
{
  $RowID = $DATA["rowid"];
  $DocNo = $DATA["DocNo"];
  $n = 0;
  $Sql = "SELECT shelfcount_detail_sub.UsageCode,shelfcount_detail.ItemCode
  FROM shelfcount_detail
  INNER JOIN shelfcount_detail_sub ON shelfcount_detail.DocNo = shelfcount_detail.DocNo
  WHERE  shelfcount_detail.Id = $RowID";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $ItemCode = $Result['ItemCode'];
    $UsageCode[$n] = $Result['UsageCode'];
    $n++;
  }

  for ($i = 0; $i < $n; $i++) {
    $xUsageCode = $UsageCode[$i];
    $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE UsageCode = '$xUsageCode'";
    mysqli_query($conn, $Sql);
  }

  $Sql = "DELETE FROM shelfcount_detail_sub
  WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
  mysqli_query($conn, $Sql);

  $Sql = "DELETE FROM shelfcount_detail
  WHERE shelfcount_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function SaveBill($conn, $DATA)
{
  $DocNo = $DATA["DocNo"];
  $DepCode = $DATA["deptCode"];
  $cycle = $DATA["cycle"];
  $settime = $DATA["settime"];
  $setcount = $DATA["setcount"];


  $ItemCodeArray = $DATA['ItemCode'];
  $ItemCode = explode(",", $ItemCodeArray);
  $QtyArray = explode(",", $DATA['Qty']);
  $limit = sizeof($ItemCode, 0);
  for ($i = 0; $i < $limit; $i++) {
    $Sql = "SELECT 
      shelfcount.DocNo, 
      shelfcount.DepCode, 
      shelfcount_detail.ItemCode,
      shelfcount_detail.TotalQty,

      (SELECT item_stock.TotalQty
      FROM item_stock 
      WHERE item_stock.ItemCode = '$ItemCode[$i]' 
      AND item_stock.DepCode = $DepCode GROUP BY item_stock.ItemCode, item_stock.DepCode) AS TotalQty2,

      (SELECT item_stock.ParQty
      FROM item_stock 
      WHERE item_stock.ItemCode = '$ItemCode[$i]' 
      AND item_stock.DepCode = $DepCode GROUP BY item_stock.ItemCode, item_stock.DepCode) AS ParQty,

      (SELECT item.ItemName
			FROM item
			WHERE item.ItemCode = '$ItemCode[$i]'  
			GROUP BY item.ItemCode) AS ItemName

    FROM shelfcount 
    INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo
    WHERE shelfcount.DocNo = '$DocNo' 
    AND shelfcount_detail.ItemCode = '$ItemCode[$i]' 
    AND shelfcount.DepCode = $DepCode";

    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $MoreThan = $Result['TotalQty'] +  $Result['TotalQty2'];
    //   if($MoreThan>$Result['ParQty']){
    //     $OverPar = $MoreThan - $Result['ParQty'];
    //     $update = "UPDATE shelfcount_detail SET shelfcount_detail.OverPar = $OverPar WHERE shelfcount_detail.DocNo = '$DocNo' 
    //     AND shelfcount_detail.ItemCode = '$ItemCode[$i]'";
    //     mysqli_query($conn, $update);
    //   }else{
    //     $update = "UPDATE shelfcount_detail SET shelfcount_detail.OverPar = 0 WHERE shelfcount_detail.DocNo = '$DocNo' 
    //     AND shelfcount_detail.ItemCode = '$ItemCode[$i]'";
    //     mysqli_query($conn, $update);
    //   }
    // }
    $updateStock = "UPDATE par_item_stock SET TotalQty = $QtyArray[$i] WHERE DepCode = $DepCode AND ItemCode = '$ItemCode[$i]'";
    mysqli_query($conn, $updateStock);
  }
  $Sql = "SELECT SUM(shelfcount_detail.TotalQty) AS Summ
  FROM shelfcount_detail WHERE shelfcount_detail.DocNo = '$DocNo'";
  $meQ = mysqli_query($conn, $Sql);
  while ($Res = mysqli_fetch_assoc($meQ)) {
    $Sum = $Res['Summ'];
  }
  $isStatus = $DATA["isStatus"];
  $Sql = "UPDATE shelfcount SET IsStatus = $isStatus , ScEndTime =NOW() ,Total = $Sum  , DeliveryTime=$settime , ScTime=$setcount WHERE shelfcount.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  // echo json_encode($Sql);
  // exit();
  
  $isStatus = $DATA["isStatus"];
  $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  $DATA['xdocno'] = "";
  $n = 0;
  $Sql = "SELECT shelfcount_detail_sub.UsageCode,shelfcount_detail.ItemCode
  FROM shelfcount_detail
  INNER JOIN shelfcount_detail_sub ON shelfcount_detail.DocNo = shelfcount_detail_sub.DocNo
  WHERE  shelfcount_detail.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $ItemCode = $Result['ItemCode'];
    $UsageCode[$n] = $Result['UsageCode'];
    $n++;
  }

  for ($i = 0; $i < $n; $i++) {
    $xUsageCode = $UsageCode[$i];
    $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE UsageCode = '$xUsageCode'";
    mysqli_query($conn, $Sql);
  }
 
    // =======================================================================================
    // Set Stock Qty
    // =======================================================================================
    $zDepCode = "";
    $zHotp    = "";
    $zDept    = "";

    $sql_update = "SELECT shelfcount.DepCode,department.HptCode
                  FROM shelfcount
                  INNER JOIN department ON shelfcount.DepCode = department.DepCode
                  WHERE shelfcount.DocNo = '$DocNo'";
    $result = mysqli_query( $conn, $sql_update);
    while ($row = mysqli_fetch_array($result)) {
        $zDepCode = $row["DepCode"];
        $zHotp    = $row["HptCode"];
    }
    $sql_update =  "SELECT DepCode FROM department WHERE IsDefault = 1 AND HptCode = '$zHotp'";
    $result = mysqli_query( $conn, $sql_update);
    while ($row = mysqli_fetch_array($result)) {
        $zDept = $row["DepCode"];
    }

    $sql_update =  "SELECT
    shelfcount_detail.ItemCode,
    shelfcount_detail.ParQty,
    shelfcount_detail.CcQty,
    shelfcount_detail.TotalQty
    FROM shelfcount_detail
    WHERE shelfcount_detail.DocNo = '$DocNo'";
    $result = mysqli_query( $conn, $sql_update);
    while ($row = mysqli_fetch_array($result)) {
        $xItemCode[$n] 	= $row["ItemCode"];
        $xParQty[$n] 	= $row["ParQty"];
        $xCcQty[$n] 	= $row["CcQty"];
        $xTotalQty[$n] 	= $row["TotalQty"];
        $n++;
    }
    for($i=0;$i<$n;$i++){
        $ItemCode = $xItemCode[$i];
        $ParQty = $xParQty[$i];
        $CcQty = $xCcQty[$i];
        $TotalQty = $xTotalQty[$i];
    }

  $Sql = "DELETE FROM shelfcount_detail_sub  WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  $Sql = "UPDATE shelfcount SET IsRequest = 1 , IsStatus = 1 WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  // ShowDetailNew($conn, $DATA);
  SelectDocument($conn, $DATA);
}
function SendData($conn, $DATA)
{
  $DocNo = $DATA["xdocno"];
  $n = 0;
  $Sql = "SELECT shelfcount_detail_sub.UsageCode,shelfcount_detail.ItemCode
  FROM shelfcount_detail
  INNER JOIN shelfcount_detail_sub ON shelfcount_detail.DocNo = shelfcount_detail_sub.DocNo
  WHERE  shelfcount_detail.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $ItemCode = $Result['ItemCode'];
    $UsageCode[$n] = $Result['UsageCode'];
    $n++;
  }

  for ($i = 0; $i < $n; $i++) {
    $xUsageCode = $UsageCode[$i];
    $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE UsageCode = '$xUsageCode'";
    mysqli_query($conn, $Sql);
  }

  ShowDocument($conn, $DATA);
}

function UpdateRefDocNo($conn, $DATA)
{
  $DocNo = $DATA["xdocno"];
  $RefDocNo = $DATA["RefDocNo"];
  $Sql = "UPDATE shelfcount SET RefDocNo = $RefDocNo WHERE shelfcount.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  ShowDocument($conn, $DATA);
}

function ShowDetail($conn, $DATA)
{
  $countqty = 0;
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];

   //==========================================================
   $Sql = "SELECT department.HptCode FROM shelfcount 
   INNER JOIN department ON shelfcount.DepCode = department.DepCode 
   WHERE shelfcount.DocNo = '$DocNo'";
   $meQuery = mysqli_query($conn, $Sql);
   while ($Result = mysqli_fetch_assoc($meQuery)) {
       $HptCode = $Result['HptCode'];
   }
   $Sql = "SELECT department.DepCode 
           FROM department
           WHERE department.HptCode = '$HptCode'
           AND department.IsDefault = 1
           AND department.IsStatus =0";
   $meQuery = mysqli_query($conn, $Sql);
   while ($Result = mysqli_fetch_assoc($meQuery)) {
       $DepCode = $Result['DepCode'];
   }
 //==========================================================

  $Sql = "SELECT
  shelfcount_detail.Id,
  shelfcount_detail.ItemCode,
  item.ItemName,
  item_unit.UnitName,
  item_unit.UnitCode,
  (
      SELECT item_stock_detail.Qty 
      FROM item_stock_detail 
      WHERE item_stock_detail.ItemCode = shelfcount_detail.ItemCode 
      AND item_stock_detail.DepCode =  $DepCode 
  ) AS ParQty,
  shelfcount_detail.ParQty,
  shelfcount_detail.CcQty,
  shelfcount_detail.TotalQty
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  WHERE shelfcount_detail.DocNo = '$DocNo'
  ORDER BY shelfcount_detail.Id DESC";
  $return['sq'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $return[$count]['RowID']      = $Result['Id'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $return[$count]['ParQty']     = $Result['ParQty'];
    $return[$count]['CcQty']       = $Result['CcQty'];
    $return[$count]['TotalQty']   = $Result['TotalQty']==null?0:$Result['TotalQty'];
    $return[$count]['Qty']   = $Result['Qty']==null?0:$Result['Qty'];
    $UnitCode                     = $Result['UnitCode'];
    $ItemCode                     = $Result['ItemCode'];
    $count2 = 0;



    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode 
                AND item_multiple_unit.ItemCode = '$ItemCode'";
    $return['sqlxxx'] = $countM;
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      if($MResult['cnt'] !=0){
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2]   = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
      }else{
        $xSql = "SELECT 
          item.UnitCode,
          item_unit.UnitName
        FROM item
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }
    $return[$m5][$count] = $count2;
    //================================================================
    $Total += $Result['Weight'];
    // $Sql = "UPDATE shelfcount SET Total = $Total WHERE DocNo = '$DocNo'";
    //   mysqli_query($conn,$Sql);
    $return[0]['Total']    = round($Total, 2);
    //================================================================
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;
  //==========================================================

  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowDetailSub($conn, $DATA)
{
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  // var_dump($DocNo); die;
  //==========================================================
  $Sql = "SELECT
  shelfcount_detail_sub.UsageCode,
  item.ItemName,
  item_unit.UnitName
  FROM
  shelfcount_detail
  INNER JOIN shelfcount_detail_sub ON shelfcount_detail.DocNo = shelfcount_detail_sub.DocNo
  INNER JOIN item ON shelfcount_detail_sub.ItemCode = item.ItemCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode    
  WHERE shelfcount_detail.DocNo = '$DocNo'
  ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$count :: ".$Result['Id']." / ".$Result['Weight']."')";
    //	mysqli_query($conn,$Sqlx);
    $return[$count]['UsageCode']  = $Result['UsageCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;
  //==========================================================
  // var_dump($Sql); die;
  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDetailSub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function CancelBill($conn, $DATA)
{
  $DocNo = $DATA["DocNo"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "UPDATE shelfcount SET IsStatus = 9 ,IsRequest = 1, Total = 0 WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  ShowDocument($conn, $DATA);
}

function ShowDocument_sub($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  //$Datepicker = $DATA["Datepicker"];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('$deptCode : $DocNo')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT site.HptName,department.DepName,shelfcount.DocNo,shelfcount.DocDate,shelfcount.Total,
  users.FName,TIME(shelfcount.Modify_Date) AS xTime,shelfcount.IsStatus
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON shelfcount.Modify_Code = users.ID
  WHERE shelfcount.DepCode = $deptCode
  AND shelfcount.DocNo LIKE '%$DocNo%'
  ORDER BY shelfcount.DocNo DESC LIMIT 500";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['Record']   = $Result['FName'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDocument_sub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "ShowDocument_sub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function chk_par($conn, $DATA)
{
  $count = 0;

  $HptCode = $DATA['HptCode'];
  $DepCode = $DATA['DepCode'];
  $chk_alert = $DATA['chk_alert'];
  $DocNo = $DATA['DocNo'];
  $ItemCodeArray = $DATA['ItemCode'];

  $ItemCode = explode(",", $ItemCodeArray);
  $limit = sizeof($ItemCode, 0);
  for ($i = 0; $i < $limit; $i++) {
    $Sql = "SELECT 
      shelfcount.DocNo, 
      shelfcount.DepCode, 
      shelfcount_detail.ItemCode,
      shelfcount_detail.TotalQty,
      shelfcount_detail.CcQty,

      (SELECT par_item_stock.TotalQty
      FROM par_item_stock 
      WHERE par_item_stock.ItemCode = '$ItemCode[$i]' 
      AND par_item_stock.DepCode = $DepCode GROUP BY par_item_stock.ItemCode, par_item_stock.DepCode) AS TotalQty2,

      (SELECT par_item_stock.ParQty
      FROM par_item_stock 
      WHERE par_item_stock.ItemCode = '$ItemCode[$i]' 
      AND par_item_stock.DepCode = $DepCode GROUP BY par_item_stock.ItemCode, par_item_stock.DepCode) AS ParQty,

      (SELECT item.ItemName
			FROM item
			WHERE item.ItemCode = '$ItemCode[$i]'  
			GROUP BY item.ItemCode) AS ItemName

    FROM shelfcount 
    INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo
    WHERE shelfcount.DocNo = '$DocNo' 
    AND shelfcount_detail.ItemCode = '$ItemCode[$i]' 
    AND shelfcount.DepCode = $DepCode";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $MoreThan = $Result['TotalQty'] +  $Result['TotalQty2'];
      if($MoreThan>$Result['ParQty']){
        $return[$count]['ItemCode'] = $Result['ItemCode'];
        $return[$count]['TotalQty'] = $Result['TotalQty'];
        $return[$count]['CcQty'] = $Result['CcQty'];
        $return[$count]['TotalQty2'] = $Result['TotalQty2'];
        $return[$count]['OverPar'] = $MoreThan - $Result['ParQty'];
        $return[$count]['ParQty'] = $Result['ParQty'];
        $return[$count]['ItemName'] = $Result['ItemName'];
        $count++;
        $chk = 3;
      }
    }
  }
  if($chk_alert == 1){
    $return['Row'] = 'No';
  }else{
    $return['Row'] = $count;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "chk_par";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['Row'] = 'No';
    $return['status'] = "success";
    $return['form'] = "chk_par";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  
}

function userKeyValue($conn, $DATA){
  $Qty = $DATA['Qty'];
  $Row = $DATA['Row'];
  $chk = $DATA['chk'];
  $Order = $DATA['Order'];
  if($chk == 'short'){
    $Sql = "UPDATE shelfcount_detail SET Short = $Qty ,TotalQty = $Order , Over = 0 WHERE Id = $Row";
  }else if($chk == 'over'){
    $Sql = "UPDATE shelfcount_detail SET Over = $Qty ,TotalQty = $Order , Short = 0 WHERE Id = $Row";
  }else{
    $Sql = "UPDATE shelfcount_detail SET TotalQty = $Order WHERE Id = $Row";
  }
  mysqli_query($conn, $Sql);
}

function SaveDraw($conn, $DATA){
  $DocNo = $DATA["DocNo"];
  $count = 0;
  $cnk = 0;
  $Sql1 = "SELECT department.HptCode, shelfcount.DepCode 
  FROM shelfcount  INNER JOIN department ON shelfcount.DepCode = department.DepCode  WHERE shelfcount.DocNo = '$DocNo'";
  $meQuery1 = mysqli_query($conn, $Sql1);
  while ($Result1 = mysqli_fetch_assoc($meQuery1)) {
    $HptCode = $Result1['HptCode'];
    $SCDepCode = $Result1['DepCode'];
  }
  $Sql2 = "SELECT department.DepCode  FROM department WHERE department.HptCode = '$HptCode' AND department.IsDefault = 1 AND department.IsStatus = 0";
  $meQuery2 = mysqli_query($conn, $Sql2);
  while ($Result2 = mysqli_fetch_assoc($meQuery2)) {
    $DepCode = $Result2['DepCode'];
  }
  $Sql3 = "SELECT
      shelfcount_detail.Id,
      item.ItemName,
      shelfcount_detail.ItemCode,
      shelfcount_detail.ParQty,
      shelfcount_detail.CcQty,
      shelfcount_detail.TotalQty
      FROM item
      INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
      INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
      INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
      WHERE shelfcount_detail.DocNo = '$DocNo'
      ORDER BY shelfcount_detail.Id DESC";

  $meQuery3 = mysqli_query($conn, $Sql3);
  while ($Result3 = mysqli_fetch_assoc($meQuery3)) {
    $ItemCode = $Result3['ItemCode'];
    $Oder = $Result3['TotalQty'];

    $Sql4 = "SELECT par_item_stock.TotalQty  
    FROM par_item_stock 
    INNER JOIN department ON department.DepCode = par_item_stock.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    WHERE par_item_stock.ItemCode = '$ItemCode'
    AND site.HptCode = '$HptCode' AND department.IsDefault = 1 LIMIT 1";
    $meQuery4 = mysqli_query($conn, $Sql4);
    $rowcount=mysqli_num_rows($meQuery4);
    if($rowcount > 0){
        while ($Result4 = mysqli_fetch_assoc($meQuery4)) {
        $QtyCenter = $Result4['TotalQty']==null?0:$Result4['TotalQty'];
        if($QtyCenter >= $Oder){
          // $updateQty = "UPDATE item_stock SET TotalQty = TotalQty + $Oder WHERE ItemCode = '$ItemCode' AND DepCode = $SCDepCode";
          // mysqli_query($conn, $updateQty);
          //===========================================================================================
          $Sql = "UPDATE shelfcount SET PkEndTime = NOW() WHERE DocNo = '$DocNo'";
          mysqli_query($conn, $Sql);

          //===========================================================================================

          $updateQtyCenter = "UPDATE par_item_stock SET TotalQty = TotalQty - $Oder WHERE ItemCode = '$ItemCode' AND DepCode = $DepCode";
          mysqli_query($conn, $updateQtyCenter);
          // $delete = "DELETE item_stock WHERE ItemCode = '$ItemCode' AND DepCode = $DepCode LIMIT $Oder";
          // mysqli_query($conn, $delete);
          $UpdateStatus = "UPDATE shelfcount SET IsStatus = 3 ,  jaipar = 1 WHERE DocNo = '$DocNo'";
          mysqli_query($conn, $UpdateStatus);
        }else if($QtyCenter < $Oder || $QtyCenter == 0){
          $Sql = "UPDATE shelfcount SET PkEndTime = NOW() WHERE DocNo = '$DocNo'";
          mysqli_query($conn, $Sql);
          $return[$count]['ItemCode']  = $Result3['ItemCode'];
          $return[$count]['ItemName']  = $Result3['ItemName'];
          $return[$count]['ParQty']    = $Result3['ParQty'];
          $return[$count]['CcQty']     = $Result3['CcQty'];
          $return[$count]['TotalQty']  = $Result3['TotalQty']==null?0:$Result3['TotalQty'];
          $return[$count]['QtyCenter']   = $Result4['TotalQty']==null?0:$Result4['TotalQty'];
          $chk = 1;
          $count++;
        }
      }
    }else{
      $Sql = "UPDATE shelfcount SET PkEndTime = NOW() WHERE DocNo = '$DocNo'";
          mysqli_query($conn, $Sql);
          $return[$count]['ItemCode']  = $Result3['ItemCode'];
          $return[$count]['ItemName']  = $Result3['ItemName'];
          $return[$count]['ParQty']    = $Result3['ParQty'];
          $return[$count]['CcQty']     = $Result3['CcQty'];
          $return[$count]['TotalQty']  = $Result3['TotalQty']==null?0:$Result3['TotalQty'];
          $return[$count]['QtyCenter']   = $Result4['TotalQty']==null?0:$Result4['TotalQty'];
          $chk = 1;
          $count++;
    }
    
  }
  $Sql5 = "SELECT jaipar FROM shelfcount WHERE DocNo = '$DocNo'";
  $meQuery5 = mysqli_query($conn, $Sql5);
  while ($Result5 = mysqli_fetch_assoc($meQuery5)) {
    $jaipar = $Result5['jaipar'];
  }

  $return['jaipar'] = $jaipar;
  $return['CountRow'] = $count;
  if ($chk == 0) {
    $return['status'] = "success";
    $return['form'] = "SaveDraw";
    $return['chk'] = "OK";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else if($chk == 1){
    $return['status'] = "success";
    $return['form'] = "SaveDraw";
    $return['chk'] = "Lassthan";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  
}

function SaveQty_SC($conn, $DATA){
  $DocNo = $DATA['DocNo'];
  $HptCode = $DATA['HptCode'];
  $DepCodePage = $DATA['DepCode'];
  $ItemCodeArray = explode(",", $DATA['ItemCode']);
  $QtyArray = explode(",", $DATA['Qty']);
  $Sql = "SELECT department.DepCode  FROM department WHERE department.HptCode = '$HptCode' AND department.IsDefault = 1 AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCode = $Result['DepCode'];
  }
  $limit = sizeof($ItemCodeArray, 0);
  for($i=0; $i<$limit; $i++){
    $updateQtyCenter = "UPDATE par_item_stock SET TotalQty = TotalQty - $QtyArray[$i] WHERE ItemCode = '$ItemCodeArray[$i]' AND DepCode = $DepCode";
    mysqli_query($conn, $updateQtyCenter);

    $updateSC_detail = "UPDATE shelfcount_detail SET TotalQty = $QtyArray[$i] WHERE ItemCode = '$ItemCodeArray[$i]' AND DocNo = '$DocNo'";
    mysqli_query($conn, $updateSC_detail);
  }

  $updateSC = "UPDATE shelfcount SET IsStatus = 3 , jaipar = 1 WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $updateSC);
  
  $Sql5 = "SELECT jaipar FROM shelfcount WHERE DocNo = '$DocNo'";
  $meQuery5 = mysqli_query($conn, $Sql5);
  while ($Result5 = mysqli_fetch_assoc($meQuery5)) {
    $jaipar = $Result5['jaipar'];
  }
  $return['jaipar'] = $jaipar;
  //===========================================================================================
  $return['status'] = "success";
  $return['form'] = "SaveQty_SC";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function PrintstickerModal($conn, $DATA){
  $DocNo = $DATA['DocNo'];
  $count = 0;
  $Sql = "SELECT sc_d.ItemCode, item.ItemName, sc_d.ParQty, sc_d.CcQty, sc_d.TotalQty
    FROM shelfcount_detail sc_d
    INNER JOIN item ON item.ItemCode = sc_d.ItemCode 
    WHERE sc_d.DocNo = '$DocNo' ORDER BY sc_d.ItemCode";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['ParQty'] = $Result['ParQty'];
    $return[$count]['CcQty'] = $Result['CcQty'];
    $return[$count]['TotalQty'] = $Result['TotalQty'];
    $count++;
  }
  
  $return['RowCount'] = $count;

  $return['status'] = "success";
  $return['form'] = "PrintstickerModal";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function find_item($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DepCode = $DATA["DepCode"];
  $itemCode = $DATA["itemCode"];
  $DocNo = $DATA["DocNo"];
  $qty = $DATA["qty"];
  $Sqlx = "SELECT
            par_item_stock.ParQty
          FROM par_item_stock
          WHERE  par_item_stock.DepCode = $DepCode  AND par_item_stock.ItemCode ='$itemCode' LIMIT 1";
          $meQueryx = mysqli_query($conn, $Sqlx);
          while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
            $ParQty = $Resultx['ParQty'];
          
          
    $Sql = "SELECT COUNT(*) as Cnt
            FROM shelfcount_detail
            INNER JOIN item  ON shelfcount_detail.ItemCode = item.ItemCode
            INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
            WHERE shelfcount.DocNo = '$DocNo'
            AND item.ItemCode = '$itemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $chkUpdate = $Result['Cnt'];
    }
    $total = $ParQty-$qty;
    if ($chkUpdate == 0) {
      $Sql = "INSERT INTO shelfcount_detail
              (DocNo, ItemCode, UnitCode,ParQty, CcQty,TotalQty,IsCancel, OverPar)
              VALUES
              ('$DocNo','$itemCode', 1, $ParQty,$qty,$total,0,0 )";
      mysqli_query($conn, $Sql);
      #----------------------------------------------------------------------------------------------------------
    } else {
      $Sqlxx = "UPDATE shelfcount_detail SET CcQty = (CcQty + $qty)  WHERE DocNo = '$DocNo' AND ItemCode = '$itemCode'";
      mysqli_query($conn, $Sqlxx);
        
      $Sqlx = "UPDATE shelfcount_detail SET TotalQty = ($ParQty-CcQty)  WHERE DocNo = '$DocNo' AND ItemCode = '$itemCode'";
      mysqli_query($conn, $Sqlx);
      
      #----------------------------------------------------------------------------------------------------------
    }
  }
  ShowDetail($conn, $DATA);
}

function ShowItemAll($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $deptCode = $DATA["deptCode"];

  $Sql = "SELECT
  par_item_stock.RowID,
  site.HptName,
  department.DepName,
  item_category.CategoryName,
  item.ItemCode,
  item.ItemName,
  item.UnitCode,
  item_unit.UnitName,
  par_item_stock.ParQty,
  par_item_stock.TotalQty,
  item.Weight
    FROM site
  INNER JOIN department ON site.HptCode = department.HptCode
  INNER JOIN par_item_stock ON department.DepCode = par_item_stock.DepCode
  INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
  INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  WHERE  par_item_stock.DepCode = $deptCode
  GROUP BY item.ItemCode
  ORDER BY item.ItemName ASC LImit 100";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['ParQty'] = $Result['ParQty'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Qty'] = $Result['TotalQty']==null?0:$Result['TotalQty'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;

    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      $return['sql'] = $countM;
      if($MResult['cnt']!=0){
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
      }else{
        $xSql = "SELECT 
          item.UnitCode,
          item_unit.UnitName
        FROM item
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }

    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;
  $return['status'] = "success";
  $return['form'] = "ShowItemAll";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function ShowDetailNew($conn, $DATA)
{

  $countqty = 0;
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];

  
   //==========================================================
   $Sql = "SELECT department.HptCode FROM shelfcount 
   INNER JOIN department ON shelfcount.DepCode = department.DepCode 
   WHERE shelfcount.DocNo = '$DocNo'";
   $meQuery = mysqli_query($conn, $Sql);
   while ($Result = mysqli_fetch_assoc($meQuery)) {
       $HptCode = $Result['HptCode'];
   }
   $Sql = "SELECT department.DepCode 
           FROM department
           WHERE department.HptCode = '$HptCode'
           AND department.IsDefault = 1
           AND department.IsStatus =0";
   $meQuery = mysqli_query($conn, $Sql);
   while ($Result = mysqli_fetch_assoc($meQuery)) {
       $DepCode = $Result['DepCode'];
   }
 //==========================================================

  $Sql = "SELECT
  shelfcount_detail.Id,
  shelfcount_detail.ItemCode,
  item.ItemName,
  item_unit.UnitName,
  item_unit.UnitCode,
  (
      SELECT item_stock_detail.Qty 
      FROM item_stock_detail 
      WHERE item_stock_detail.ItemCode = shelfcount_detail.ItemCode 
      AND item_stock_detail.DepCode =  '$DepCode' 
  ) AS ParQty,
  shelfcount_detail.ParQty,
  shelfcount_detail.CcQty,
  shelfcount_detail.TotalQty,
  shelfcount_detail.Over,
  shelfcount_detail.Short,
  item.Weight
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  WHERE shelfcount_detail.DocNo = '$DocNo'
  ORDER BY shelfcount_detail.Id DESC";
  $return['sq'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
 
    $return[$count]['RowID']      = $Result['Id'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $return[$count]['ParQty']     = $Result['ParQty'];
    $return[$count]['CcQty']       = $Result['CcQty'];
    $return[$count]['Over']       = $Result['Over']==0?"":$Result['Over'];
    $return[$count]['Short']       = $Result['Short']==0?"":$Result['Short'];
    $return[$count]['Weight']       = $Result['Weight']*$Result['CcQty'];
    $return[$count]['TotalQty']   = $Result['TotalQty']==0?"":$Result['TotalQty'];
    $return[$count]['Qty']   = $Result['Qty']==null?0:$Result['Qty'];
    $UnitCode                     = $Result['UnitCode'];
    $ItemCode                     = $Result['ItemCode'];
    $count2 = 0;



    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode 
                AND item_multiple_unit.ItemCode = '$ItemCode'";
    $return['sqlxxx'] = $countM;
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      if($MResult['cnt'] !=0){
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2]   = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
      }else{
        $xSql = "SELECT 
          item.UnitCode,
          item_unit.UnitName
        FROM item
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }
    $return[$m5][$count] = $count2;
    //================================================================
    $Total += $Result['Weight'];
    // $Sql = "UPDATE shelfcount SET Total = $Total WHERE DocNo = '$DocNo'";
    //   mysqli_query($conn,$Sql);
    $return[0]['Total']    = round($Total, 2);
    //================================================================
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;
  //==========================================================
  $return['DepCode']  = $Result['DepCode'];

  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDetailNew";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDetailNew";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function UpdateNewQty($conn, $DATA){
  $RowID  = $DATA["RowID"];
  $NewQty  =  $DATA["NewQty"];
  $Issue  =  $DATA["Issue"];
  $chk  =  $DATA["chk"];
  $Result  =  $DATA["Result"];
  if($Issue!=""||$Issue!=0){
    if($chk == "Over"){
      $Sql = "UPDATE shelfcount_detail  SET CcQty = $NewQty, TotalQty = $Issue, Over = $Result, Short = 0 WHERE shelfcount_detail.Id = $RowID";
    }else if($chk == "Short"){
      $Sql = "UPDATE shelfcount_detail  SET CcQty = $NewQty, TotalQty = $Issue, Short = $Result, Over = 0 WHERE shelfcount_detail.Id = $RowID";
    }
  }else{
    $Sql = "UPDATE shelfcount_detail  SET CcQty = $NewQty, TotalQty = $Issue, Over = 0, Short = 0 WHERE shelfcount_detail.Id = $RowID";
  }
  
  // echo json_encode($Sql);
  mysqli_query($conn, $Sql);
  ShowDetailNew($conn, $DATA);
}

function ChkItemInDep($conn, $DATA){
  $DepCode = $DATA['DepCode'];
  $HtpCode = $DATA['HtpCode'];
  $Sql = "SELECT COUNT(*) AS cnt FROM par_item_stock WHERE DepCode = $DepCode";
  $MQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($MQuery)) {
    $return['Count'] = $Result['cnt'];
  }
  $return['status'] = "success";
  $return['form'] = "ChkItemInDep";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
  //==========================================================
  //
  //==========================================================
  if (isset($_POST['DATA'])) {
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace('\"', '"', $data), true);

    if ($DATA['STATUS'] == 'OnLoadPage') {
      OnLoadPage($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'getDepartment') {
      getDepartment($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowItem') {
      ShowItem($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowUsageCode') {
      ShowUsageCode($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowDocument') {
      ShowDocument($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowDocument_sub') {
      ShowDocument_sub($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'SelectDocument') {
      SelectDocument($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'CreateDocument') {
      CreateDocument($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'CancelDocNo') {
      CancelDocNo($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'getImport') {
      getImport($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowDetail') {
      ShowDetail($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateDetailQty') {
      UpdateDetailQty($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateDetailQty_key') {
      UpdateDetailQty_key($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'updataDetail') {
      updataDetail($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateDetailWeight') {
      UpdateDetailWeight($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'DeleteItem') {
      DeleteItem($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'SaveBill') {
      SaveBill($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'CancelBill') {
      CancelBill($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateRefDocNo') {
      UpdateRefDocNo($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowDetailSub') {
      ShowDetailSub($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowMenu') {
      ShowMenu($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'chk_par') {
      chk_par($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'userKeyValue') {
      userKeyValue($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'SaveDraw') {
      SaveDraw($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'SaveQty_SC') {
      SaveQty_SC($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'PrintstickerModal') {
      PrintstickerModal($conn, $DATA);
    } elseif ($DATA['STATUS'] == 'find_item') {
      find_item($conn, $DATA);
    } elseif ($DATA['STATUS'] == 'getDepartment2') {
      getDepartment2($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'setpacking') {
      setpacking($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'gettime') {
      gettime($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowItemAll') {
      ShowItemAll($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ShowDetailNew') {
      ShowDetailNew($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateNewQty') {
      UpdateNewQty($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'ChkItemInDep') {
      ChkItemInDep($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'gettimesc') {
      gettimesc($conn, $DATA);
    }
    
    
  } else {
    $return['status'] = "error";
    $return['msg'] = 'noinput';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  