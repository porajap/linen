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
  $lang = $DATA["lang"];
  $HptCode = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $count = 0; 
  $countx = 0;
  $boolean = false;


  if($lang == 'en'){
    $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0 ";
  }else{
    $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName FROM factory WHERE factory.IsCancel = 0 ";
  }  
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $return[$countx]['FacCode'] = $Result['FacCode'];
    $return[$countx]['FacName'] = $Result['FacName'];
    $countx  ++;
  }
  $return['Rowx'] = $countx;

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
function saveDep($conn, $DATA){
  $DocNo = $DATA["DocNo"];
  $DepCode = $DATA["DepCode"];

  $Sql ="UPDATE return_doc SET DepCodeTo = '$DepCode' WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  $return['DepCode'] = $DepCode;
  if (mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "saveDep";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "saveDep";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'
  AND department.IsDefault = 1  AND department.IsActive = 1 
  AND department.IsStatus = 0 ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getDepartment2($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'AND department.IsActive = 1 
  AND department.IsStatus = 0 ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function CreateDocument($conn, $DATA)
  {
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"];
  $deptCode = $DATA["deptCode"];
  $userid   = $DATA["userid"];

  $Sql = "SELECT department.DepCode
  FROM department
  WHERE department.HptCode = '$hotpCode'
  AND department.IsDefault = 1
  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCodeDefault = $Result['DepCode'];
  }

  $Sql = "SELECT CONCAT('RT',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM return_doc
  INNER JOIN department on return_doc.DepCodeFrom = department.DepCode
  WHERE DocNo Like CONCAT('RT',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  AND department.HptCode = '$hotpCode'
  ORDER BY DocNo DESC LIMIT 1";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($lang =='en'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
    }else if ($lang == 'th'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
    }

    $DocNo = $Result['DocNo'];
    $return[0]['DocNo']   = $Result['DocNo'];
    $return[0]['DocDate'] = $newdate;
    $return[0]['RecNow']  = $Result['RecNow'];
    $count = 1;
    mysqli_query($conn,$Sql);
  }

  if ($count == 1) {
    $Sql = "INSERT INTO return_doc
    ( DocNo, DocDate, HptCode, DepCodeFrom, DepCodeTo, RefDocNo, IsCancel, Modify_Code, Modify_Date )VALUES
    ('$DocNo', NOW(), '$hotpCode', '$deptCode', '$DepCodeDefault','$RefDocNo', 0, $userid, NOW() )";
      mysqli_query($conn, $Sql);


      $Sql = "SELECT users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix
      FROM users
      WHERE users.ID = $userid";

      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DocNo = $Result['DocNo'];
        $return[0]['Record']   = $Result['FName'];
        if($lang == "en"){
          $return[0]['Record']  = $Result['EngPerfix'].$Result['EngName'].'  '.$Result['EngLName'];
        }else if($lang == "th"){
          $return[0]['Record']  = $Result['ThPerfix'].' '.$Result['ThName'].'  '.$Result['ThLName'];
        }
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

function ShowDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $Hotp = $DATA["Hotp"];
  $deptCode = $DATA["deptCode"];
  $DocNo = $DATA["docno"];
  $xDocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $datepicker = $DATA["datepicker1"]==''?date('Y-m-d'):$DATA["datepicker1"];
  $selecta = $DATA["selecta"];
  $Sql = "SELECT site.HptName,department.DepName,return_doc.DocNo,DATE(return_doc.DocDate) 
  AS DocDate,return_doc.RefDocNo,return_doc.Total,users.EngName , 
  users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,TIME(return_doc.Modify_Date) AS xTime,return_doc.IsStatus
  FROM return_doc
  INNER JOIN department ON return_doc.DepCodeFrom = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON return_doc.Modify_Code = users.ID ";

  if ($Hotp != null && $deptCode == null && $datepicker == null) {
    $Sql .= " WHERE site.HptCode = '$Hotp' AND return_doc.DocNo LIKE '%$xDocNo%' ";
  }else if($Hotp == null && $deptCode != null && $datepicker == null){
    $Sql .= "  WHERE return_doc.DocNo LIKE '%$xDocNo%'";
  }else if ($Hotp == null && $deptCode == null && $datepicker != null){
    $Sql .= " WHERE DATE(return_doc.DocDate) = '$datepicker' AND return_doc.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp != null && $deptCode != null && $datepicker == null){
    $Sql .= " WHERE site.HptCode = '$Hotp' AND return_doc.DepCodeFrom = $deptCode AND return_doc.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp != null && $deptCode == null && $datepicker != null){
    $Sql .= " WHERE site.HptCode = '$Hotp' AND DATE(return_doc.DocDate) = '$datepicker' AND return_doc.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp == null && $deptCode != null && $datepicker != null){
    $Sql .= " WHERE return_doc.DepCodeFrom = $deptCode AND DATE(return_doc.DocDate) = '$datepicker' AND return_doc.DocNo LIKE '%$xDocNo%'";
  }else if($Hotp != null && $deptCode != null && $datepicker != null){
    $Sql .= " WHERE return_doc.DepCodeFrom = $deptCode AND DATE(return_doc.DocDate) = '$datepicker' AND site.HptCode = '$Hotp' AND return_doc.DocNo LIKE '%$xDocNo%'";
  }

  $Sql .= "ORDER BY return_doc.DocNo DESC LIMIT 500";
  $return['qqq'] = $Sql;
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
    $return[$count]['RefDocNo']   = $Result['RefDocNo'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total']==null?0:$Result['Total'];
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
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    $return['msg'] = "notfound";
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
  $Sql = "SELECT site.HptName,department.DepName,return_doc.DocNo,DATE(return_doc.DocDate) 
  AS DocDate, return_doc.Total,users.EngName, 
  users.EngLName,  return_doc.DepCodeFrom, return_doc.DepCodeTo,
  users.ThName, users.ThLName , users.EngPerfix , users.ThPerfix ,
  TIME(return_doc.Modify_Date) AS xTime,return_doc.IsStatus,return_doc.RefDocNo
  FROM return_doc
  INNER JOIN department ON return_doc.DepCodeFrom = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON return_doc.Modify_Code = users.ID
  WHERE return_doc.DocNo = '$DocNo'";
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
    $return[$count]['RefDocNo'] = $Result['RefDocNo'];
    $return[$count]['DepCodeFrom'] = $Result['DepCodeFrom'];
    $return[$count]['DepCodeTo'] = $Result['DepCodeTo'];

    $boolean = true;
    $count++;
  }

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

function ShowItem($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = str_replace(' ', '%', $DATA["xitem"]);
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
  par_item_stock.TotalQty
    FROM site
  INNER JOIN department ON site.HptCode = department.HptCode
  INNER JOIN par_item_stock ON department.DepCode = par_item_stock.DepCode
  INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
  LEFT  JOIN item_stock_detail i_detail ON i_detail.ItemCode = item.ItemCode
  INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  WHERE  par_item_stock.DepCode = '$deptCode' AND  item.ItemName LIKE '%$searchitem%' AND item.IsActive = 1
  GROUP BY item.ItemCode
  ORDER BY item.ItemName ASC LImit 100";
  $return['sdqel'] = $Sql;
    $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['TotalQty'] = $Result['TotalQty'];
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
    $return['msg'] = "notfound";
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
  AND item_stock.IsStatus = 6
  ORDER BY item.ItemName ASC LImit 100";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID']      = $Result['RowID'];
    $return[$count]['UsageCode']  = $Result['UsageCode'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitCode']   = $Result['UnitCode'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $ItemCode                     = $Result['ItemCode'];
    $UnitCode                     = $Result['UnitCode'];
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
  $DeptCode = $DATA["deptCode"];
  $DocNo = $DATA["DocNo"];
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
    $iqty = $nqty[$i]==""?0:$nqty[$i];
    $iweight = $nweight[$i]==null?0:$nweight[$i];
    $iunit1 = 0;
    $iunit2 = $nunit[$i];


    $Sql = "SELECT item.ItemCode,item.UnitCode
    FROM item
    WHERE ItemCode = '$iItemStockId'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode = $Result['ItemCode'];
      $iunit1 = $Result['UnitCode'];
    }

    $Sql = "SELECT COUNT(*) as Cnt
    FROM return_detail
    INNER JOIN item  ON return_detail.ItemCode = item.ItemCode
    INNER JOIN return_doc ON return_doc.DocNo = return_detail.DocNo
    WHERE return_doc.DocNo = '$DocNo'
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
   
        $Sql = " INSERT INTO return_detail(DocNo, ItemCode, UnitCode, Qty, Weight, IsCancel)
        VALUES('$DocNo', '$ItemCode', $iunit2, $iqty2, $iweight, 0) ";
        mysqli_query($conn, $Sql);

    } else {

        $Sql = " UPDATE return_detail
        SET Weight = (Weight+$iweight), Qty = (Qty + $iqty2)
        WHERE DocNo = '$DocNo' and ItemCode = '$ItemCode' ";
        mysqli_query($conn, $Sql);

    }
    // $return[$i]['asdasd'] = $Sql;
  }

  if ($Sel == 2) {
    for ($i = 0; $i < $n; $i++) {
      $xQty = $Qty[$i];
      if ($chkUpdate == 0) {
        $Sql = "INSERT INTO return_detail
        (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
        VALUES
        ('$DocNo','$ItemCode',$iunit2,$xQty,0,0)";
      } else {
        $Sql = "UPDATE return_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      }
      mysqli_query($conn, $Sql);
    // $return[$i]['assassdasd'] = $Sql;

    }
  }
  ShowDetail($conn, $DATA);
  // echo json_encode($return);
}

function UpdateDetailQty($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Qty  =  $DATA["Qty"];
  $OleQty =  $DATA["OleQty"];
  $UnitCode =  $DATA["unitcode"];
  $Sql = "UPDATE clean_detail
  SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
  WHERE clean_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function UpdateDetailWeight($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Weight  =  $DATA["Weight"];
  $Price  =  $DATA["Price"];
  $isStatus = $DATA["isStatus"];

  //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
  //	mysqli_query($conn,$Sqlx);

  $Sql = "UPDATE clean_detail
  SET Weight = $Weight
  WHERE clean_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $UnitCode =  $DATA["unitcode"];
  $qty =  $DATA["qty"];
  $Sql = "UPDATE clean_detail
  SET UnitCode = $UnitCode
  WHERE clean_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function DeleteItem($conn, $DATA)
{
  $RowID  = $DATA["rowid"];
  $DocNo = $DATA["DocNo"];
  
  $Sql = "DELETE FROM return_detail
  WHERE return_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function SaveBill($conn, $DATA)
{
  $PmID = $_SESSION['PmID'];
  $HptCode = $_SESSION['HptCode'];
  $DocNo = $DATA["xdocno"];
  $isStatus = $DATA["isStatus"];
  $count = 0 ;
  $count4 = 0;
  $Sql = "UPDATE return_doc SET IsStatus = $isStatus  WHERE return_doc.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  // ================================================================================

  $Sql = "SELECT DepCodeFrom, DepCodeTo, ItemCode, Qty
    FROM return_doc
    INNER JOIN return_detail ON return_detail.DocNo = return_doc.DocNo
    WHERE return_doc.DocNo = '$DocNo'";
    $Query = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($Query)) {
      $DepCodeFrom = $Result['DepCodeFrom'];
      $DepCodeTo = $Result['DepCodeTo'];
      $ItemCode = $Result['ItemCode'];
      $Qty = $Result['Qty'];

      $DelUpdate = "UPDATE par_item_stock SET TotalQty = (TotalQty-$Qty) WHERE ItemCode = '$ItemCode' AND DepCode = '$DepCodeFrom'";
      mysqli_query($conn, $DelUpdate);

      $PlusUpdate = "UPDATE par_item_stock SET TotalQty = (TotalQty+$Qty) WHERE ItemCode = '$ItemCode' AND DepCode = '$DepCodeTo'";
      mysqli_query($conn, $PlusUpdate);
    }
  


  if($percent > 8){
    $return['status'] = "success";
    $return['form'] = "SaveBill";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  ShowDocument($conn, $DATA);
}

function UpdateRefDocNo($conn, $DATA)
{
  $hptcode = $DATA["hptcode"];
  $DocNo = $DATA["DocNo"];
  $RefDocNo = $DATA["RefDocNo"];

  $Sql = "SELECT department.DepCode
  FROM department
  WHERE department.HptCode = '$hptcode'
  AND department.IsDefault = 1
  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCode = $Result['DepCode'];
  }


  $Sql = "UPDATE return_doc SET RefDocNo = '$RefDocNo', DepCodeTo = '$DepCode' WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  SelectDocument($conn, $DATA);

}

function chk_percent($conn, $DATA){
  $Total = 0;
  $count=0;
  $boolean = false;
  $RefDocNo = $DATA['RefDocNo'];
  $DocNo = $DATA['DocNo'];
  $Sql = "SELECT clean.Total
  FROM clean WHERE clean.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $cTotal	= $Result['Total']==null?0:$Result['Total'];
  }
  $Sqlx = "SELECT dirty.DocNo FROM dirty WHERE dirty.DocNo = '$RefDocNo' ";
  $meQuery = mysqli_query($conn, $Sqlx);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNoDirty = $Result['DocNo'];
  }
  if($DocNoDirty != ''){
    $Sql = "SELECT dirty.Total
    FROM dirty WHERE dirty.DocNo = '$RefDocNo'";
    $return['sql'] = $Sql;
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $dTotal	= $Result['Total']==null?0:$Result['Total'];
      $boolean = true;
      $count++;
    }
  }
  if($dTotal !=0){
    $Total =  ROUND( ((($dTotal - $cTotal )/$dTotal)*100) , 2)  ;
    }else{
      $Total = 0;
    }
    $return['xxx'] = $Total;
    $return[0]['Percent'] 	= $Total;
    $return[0]['DocNo'] 	= $DocNo;
    $return['Row'] = $count;

  if($Total > 8){
    $over = $Total - 8 ;
    $return[0]['over'] 	= abs($over);
    $return['status'] = "success";
    $return['form'] = "chk_percent";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['Row'] = 'No';
    $return['status'] = "success";
    $return['form'] = "chk_percent";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }



}


function ShowDetail($conn, $DATA)
{
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  $DepCode = $DATA["deptCode"];
  //==========================================================
  $Sql = "SELECT
  return_detail.Id,
  return_detail.DocNo,
  return_detail.ItemCode,
  item.ItemName,
  item.UnitCode AS UnitCode1,
  item_unit.UnitName,
  return_detail.UnitCode AS UnitCode2,
  return_detail.Weight,
  return_doc.RefDocNo,
  return_doc.DepCodeFrom,
  return_doc.DepCodeTo,
  return_detail.Qty,
  par_item_stock.TotalQty
  FROM
  item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN return_detail ON return_detail.ItemCode = item.ItemCode
  INNER JOIN item_unit ON return_detail.UnitCode = item_unit.UnitCode
  INNER JOIN return_doc ON return_detail.DocNo = return_doc.DocNo
  INNER JOIN par_item_stock ON par_item_stock.ItemCode = item.ItemCode
  WHERE return_detail.DocNo = '$DocNo' AND par_item_stock.DepCode = '$DepCode' GROUP BY return_detail.ItemCode
  ORDER BY return_detail.Id DESC";
  $return['sqlss']=$Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $return[$count]['RowID']    = $Result['Id'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitCode']   = $Result['UnitCode2'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $return[$count]['Weight']     = $Result['Weight'];
    $return[$count]['RefDocNo']     = $Result['RefDocNo'];
    $return[$count]['DepCodeFrom']     = $Result['DepCodeFrom'];
    $return[$count]['DepCodeTo']     = $Result['DepCodeTo'];
    $return[$count]['TotalQty']     = $Result['TotalQty'];
    $return[$count]['Qty']     = $Result['Qty'];
    $UnitCode           = $Result['UnitCode1'];
    $ItemCode               = $Result['ItemCode'];
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

    //================================================================
    $Total += $Result['Weight'];
    //================================================================
    $count++;
    $boolean = true;
  }

  if ($count == 0) $Total = 0;

  $Sql = "UPDATE return_doc SET Total = $Total WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  $return[0]['Total']    = round($Total, 2);

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

function CancelBill($conn, $DATA)
{
  $DocNo = $DATA["DocNo"];
  $RefDocNo = $DATA["RefDocNo"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "UPDATE return_doc SET IsStatus = 9  WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);

  // $Sqlx = "SELECT dirty.DocNo FROM dirty WHERE dirty.DocNo = '$RefDocNo' ";
  // $meQuery = mysqli_query($conn, $Sqlx);
  // while ($Result = mysqli_fetch_assoc($meQuery)) {
  //   $DocNoDirty = $Result['DocNo'];
  // }
  // if($DocNoDirty != "" ){
  // $Sql = "UPDATE dirty SET IsRef = 0 , IsStatus = 3 WHERE dirty.DocNo = '$RefDocNo'";
  // mysqli_query($conn, $Sql);
  // }else{
  // $Sql = "UPDATE repair_wash SET IsRef = 0 , IsStatus = 3 WHERE repair_wash.DocNo = '$RefDocNo'";
  // mysqli_query($conn, $Sql);
  // }
  // $Sqlx = "SELECT newlinentable.DocNo FROM newlinentable WHERE newlinentable.DocNo = '$RefDocNo' ";
  // $meQuery = mysqli_query($conn, $Sqlx);
  // while ($Result = mysqli_fetch_assoc($meQuery)) {
  //   $DocNonewlinentable = $Result['DocNo'];
  // }
  // if($DocNonewlinentable != "" ){
  //   $Sql = "UPDATE newlinentable SET IsRef = 0 , IsStatus = 3 WHERE newlinentable.DocNo = '$RefDocNo'";
  //   mysqli_query($conn, $Sql);
  //   }
    ShowDocument($conn, $DATA);
}

function updateQty($conn, $DATA){
  $newQty = $DATA['newQty'];
  $RowID = $DATA['RowID'];
  $Sql = "UPDATE return_detail SET Qty = $newQty WHERE Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function get_dirty_doc($conn, $DATA)
{
  $hptcode = $DATA["hptcode"];
  $searchitem1 = $DATA["searchitem1"];
  $DepCode = $DATA["DepCode"];
  $boolean = false;
  $count = 0;
  $count2 = 0;
  $Sql = "SELECT shelfcount.DocNo,
  RefDocNo,
  shelfcount.DocDate,
  department.DepName
    FROM
      shelfcount
    INNER JOIN department ON department.DepCode = shelfcount.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    WHERE
      shelfcount.IsCancel = 0
    AND shelfcount.IsStatus = 4
    AND shelfcount.DepCode = '$DepCode'
    AND site.HptCode = '$hptcode'
    AND shelfcount.DocNo LIKE '%$searchitem1%'";
    $return['fgfg'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    
    $return[$count]['RefDocNo'] = $Result['DocNo'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['FacName'] = $Result['DepName'];

      
    $boolean = true;
    $count++;
    $count2++;
  }
  $return['Sql'] = $Sql;
  $return['Row'] = $count;
  $return['count2'] = $count2;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "get_dirty_doc";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "get_dirty_doc";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
//==========================================================
//
//==========================================================
if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'OnLoadPage') {
    OnLoadPage($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getDepartment') {
    getDepartment($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowUsageCode') {
    ShowUsageCode($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDocument') {
    ShowDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SelectDocument') {
    SelectDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CreateDocument') {
    CreateDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CancelDocNo') {
    CancelDocNo($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getImport') {
    getImport($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDetail') {
    ShowDetail($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateDetailQty') {
    UpdateDetailQty($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'updataDetail') {
    updataDetail($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateDetailWeight') {
    UpdateDetailWeight($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'DeleteItem') {
    DeleteItem($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SaveBill') {
    SaveBill($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CancelBill') {
    CancelBill($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateRefDocNo') {
    UpdateRefDocNo($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'get_dirty_doc') {
    get_dirty_doc($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'chk_percent') {
    chk_percent($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'updateQty') {
    updateQty($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'saveDep') {
    saveDep($conn, $DATA);
  }elseif ($DATA['STATUS'] == 'getDepartment2') {
    getDepartment2($conn, $DATA);
  }



  
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
