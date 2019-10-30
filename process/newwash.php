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
  $Hotp = $DATA["Hotp"];
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
  $Sql = "SELECT site.HptCode,site.HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$Hotp'";
  if($PmID ==2 || $PmID ==3){
  $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
  }else{
    $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0 ";
  }
}else{
  $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$Hotp'";
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

/**
 * @param $conn
 * @param $DATA
 */
function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'
  AND department.IsActive = 1
  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;
  if ($boolean) {
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
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function CreateDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"];
  // $deptCode = $DATA["deptCode"];
  $userid   = $DATA["userid"];
  $FacCode   = $DATA["FacCode"];


  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);


  $Sql = "SELECT CONCAT( 'NL', lpad('$hotpCode', 3, 0), SUBSTRING(YEAR(DATE(NOW())), 3, 4), LPAD(MONTH(DATE(NOW())), 2, 0), '-' ,  LPAD(
    ( COALESCE ( 	MAX( CONVERT ( SUBSTRING(DocNo, 12, 5), UNSIGNED INTEGER ) ), 0 ) + 1 ), 5, 0 ) ) AS DocNo, DATE(NOW()) AS DocDate ,  CURRENT_TIME () AS RecNow  
    FROM newlinentable   INNER JOIN site ON site.HptCode = newlinentable.HptCode WHERE DocNo LIKE CONCAT( 'NL', lpad('$hotpCode', 3, 0), 
    SUBSTRING(YEAR(DATE(NOW())), 3, 4), LPAD(MONTH(DATE(NOW())), 2, 0), '%' ) AND site.HptCode = '$hotpCode'  ORDER BY DocNo DESC LIMIT 1";
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
  $Sql = "INSERT INTO log ( log ) VALUES ('" . $Result['DocDate'] . " : " . $Result['DocNo'] . " :: $hotpCode :: $deptCode')";
  mysqli_query($conn, $Sql);
}

  if ($count == 1) {
    $Sql = "INSERT INTO newlinentable
      ( DocNo,DocDate,HptCode,RefDocNo,
		TaxNo,TaxDate,DiscountPercent,DiscountBath,
		Total,IsCancel,Detail,
		newlinentable.Modify_Code,newlinentable.Modify_Date,newlinentable.FacCode )
      VALUES
      ( '$DocNo',NOW(),'$hotpCode','',
		0,NOW(),0,0,
		0,0,'',
		$userid,NOW(),$FacCode )";
    mysqli_query($conn,$Sql);

  //     $Sql = "INSERT INTO daily_request
  // (DocNo,DocDate,DepCode,RefDocNo,Detail,Modify_Code,Modify_Date)
  // VALUES
  // ('$DocNo',DATE(NOW()),$deptCode,'','newlinentable',$userid,DATE(NOW()))";
  //     mysqli_query($conn, $Sql);

  //     $Sql = "SELECT users.FName
  //     FROM users
  //     WHERE users.ID = $userid";

  //     $meQuery = mysqli_query($conn, $Sql);
  //     while ($Result = mysqli_fetch_assoc($meQuery)) {
  //       $DocNo = $Result['DocNo'];
  //       $return[0]['Record']   = $Result['FName'];
  //     }

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
function showDep($conn, $DATA){
  $count = 0;
  $HptCode = $_SESSION['HptCode'];
  $Sql = "SELECT dep.DepCode, dep.DepName FROM department dep 
  WHERE dep.HptCode = '$HptCode' AND dep.IsStatus = 0 AND dep.IsActive = 1
  ORDER BY dep.DepName ASC ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = trim($Result['DepCode']);
    $return[$count]['DepName'] = trim($Result['DepName']);
    $count++;
  }
  $return['CountDep'] = $count;
  $return['ItemCode'] = $DATA['ItemCode'];
  $return['ItemName'] = $DATA['ItemName'];
  $return['status'] = "success";
  $return['form'] = "showDep";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function confirmDep($conn, $DATA){
  $DocNo = $DATA['DocNo'];
  $ItemCode = $DATA['ItemCode'];
  $DepCode = explode(',', $DATA['DepCode']);
  $limit = sizeof($DepCode, 0);
  for($i=0; $i<$limit; $i++){
    $count = "SELECT COUNT(*) as cnt FROM newlinentable_detail WHERE DocNo = '$DocNo' AND DepCode = $DepCode[$i] AND ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $count);
    $Result = mysqli_fetch_assoc($meQuery);
    if($Result['cnt']==0){
      $Insert = "INSERT newlinentable_detail (DocNo, ItemCode, UnitCode, DepCode, Qty)VALUES('$DocNo', '$ItemCode', 1, $DepCode[$i], 1)";
      mysqli_query($conn, $Insert);
    }
  }
  ShowDetailDoc($conn, $DATA);
}
function ShowDetailDoc($conn, $DATA)
{
  $count1 = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  //==========================================================

    $SqlItem = "SELECT newlinentable_detail.Id, newlinentable_detail.ItemCode, item.ItemName, item.UnitCode AS UnitCode1,
      item_unit.UnitName, newlinentable_detail.UnitCode AS UnitCode2, newlinentable_detail.Weight, newlinentable_detail.Qty, item.UnitCode,
      department.DepCode, department.DepName
      FROM item
      INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
      INNER JOIN newlinentable_detail ON newlinentable_detail.ItemCode = item.ItemCode
      INNER JOIN department ON department.DepCode = newlinentable_detail.DepCode
      INNER JOIN item_unit ON newlinentable_detail.UnitCode = item_unit.UnitCode
      WHERE newlinentable_detail.DocNo = '$DocNo'
      ORDER BY newlinentable_detail.DepCode, newlinentable_detail.ItemCode ASC";
      $meQuery = mysqli_query($conn, $SqlItem);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $count2 = 0;
      $return[$count1]['RowID']     = $Result['Id'];
      $return[$count1]['ItemCode']  = $Result['ItemCode'];
      $return[$count1]['ItemName']  = $Result['ItemName'];
      $return[$count1]['UnitCode']  = $Result['UnitCode2'];
      $return[$count1]['UnitName']  = $Result['UnitName'];
      $return[$count1]['DepCode']   = $Result['DepCode'];
      $return[$count1]['DepName']   = $Result['DepName'];
      $return[$count1]['Weight']    = $Result['Weight']==0?'':$Result['Weight'];
      $return[$count1]['Qty']       = $Result['Qty']==0?'':$Result['Qty'];
      $UnitCode                     = $Result['UnitCode1'];
      $ItemCode                     = $Result['ItemCode'];

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
          $m1 = "MpCode_" . $ItemCode . "_" . $count1;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count1;
          $m3 = "UnitName_" . $ItemCode . "_" . $count1;
          $m4 = "Multiply_" . $ItemCode . "_" . $count1;
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
          $m1 = "MpCode_" . $ItemCode . "_" . $count1;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count1;
          $m3 = "UnitName_" . $ItemCode . "_" . $count1;
          $m4 = "Multiply_" . $ItemCode . "_" . $count1;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }
      $return[$m5][$count1] = $count2;
      $count++;
      $boolean = true;
      $return['Row'] = $count1;
      //================================================================
      $Total += $Result['Weight'];
      $count1++;
    }
    
  $return[0]['Total']    = round($Total, 2);
  $return['CountDep'] = $count1;
  $return['status'] = "success";
  $return['form'] = "ShowDetailDoc";
  echo json_encode($return);
  mysqli_close($conn);
  die;
 

 
}
function ShowDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $Hotp = $DATA["Hotp"]==null? $_SESSION['HptCode']:$DATA["Hotp"];
  $DocNo = $DATA["DocNo"];
  $xDocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $datepicker = $DATA["datepicker1"];
  $selecta = $DATA["selecta"];

  // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "SELECT site.HptName,
  newlinentable.DocNo,
  DATE(newlinentable.DocDate) AS DocDate,
  newlinentable.Total,
  users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix , TIME(newlinentable.Modify_Date) AS xTime,newlinentable.IsStatus
  FROM newlinentable
  INNER JOIN site ON newlinentable.HptCode = site.HptCode
  INNER JOIN users ON newlinentable.Modify_Code = users.ID ";

  // if($DocNo!=null){
  //   $Sql .= " WHERE newlinentable.DocNo = '$DocNo' AND newlinentable.DocNo LIKE '%$xDocNo%'";
  // }else{
    if ($Hotp != null  && $datepicker == null) {
      $Sql .= " WHERE site.HptCode = '$Hotp' AND newlinentable.DocNo LIKE '%$xDocNo%' ";
    }else if ($Hotp == null  && $datepicker != null){
      $Sql .= " WHERE newlinentable.DocDate = '$datepicker' AND newlinentable.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null  && $datepicker != null){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND newlinentable.DocDate = '$datepicker' AND newlinentable.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null  && $datepicker != null){
      $Sql .= " WHERE  newlinentable.DocDate = '$datepicker' AND site.HptCode = '$Hotp' AND newlinentable.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp == null  && $datepicker == null){
      $Sql .= "WHERE newlinentable.DocNo LIKE '%$xDocNo%'";
    }
  // }
  // if($selecta == null){
  //   $Sql .= " WHERE newlinentable.DocNo = '$DocNo'";
  // }else if ($selecta == 1) {
  //   $Sql .= " WHERE newlinentable.HptCode = $Hotp AND newlinentable.DepCode = $deptCode OR newlinentable.DocNo LIKE '%$xDocNo%'";
  // }else if($selecta == 2){
  //   $Sql .= " WHERE site.HptCode = '$Hotp'";
  // }
  $Sql .= " ORDER BY newlinentable.DocNo DESC LIMIT 500";
  $return['sql']= $Sql;
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
  $DocNo = $DATA["xdocno"];
  $Datepicker = $DATA["Datepicker"];
    $Sql = "SELECT   site.HptCode,newlinentable.DocNo,DATE(newlinentable.DocDate) 
    AS DocDate,newlinentable.Total,users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,newlinentable.FacCode,TIME(newlinentable.Modify_Date) AS xTime,newlinentable.IsStatus
  FROM newlinentable
  INNER JOIN site ON newlinentable.HptCode = site.HptCode
  INNER JOIN users ON newlinentable.Modify_Code = users.ID
  WHERE newlinentable.DocNo = '$DocNo'";
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

    $return[$count]['HptName']   = $Result['HptCode'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $newdate;
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $return[$count]['FacCode'] = $Result['FacCode'];

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

  $Sql = "SELECT item.ItemCode , item.ItemName , item_unit.UnitCode , item_unit.UnitName 

  FROM item , item_unit WHERE item.UnitCode = item_unit.UnitCode AND item.ItemName LIKE '%$searchitem%'";
    $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
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
    $return['status'] = "failed";
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
        AND item_stock.IsStatus = 7
        LImit 100";
  // (item_stock.IsStatus = 1 OR
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
  $Hotp = $DATA["Hotp"];
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
    $iqty = $nqty[$i];
    $iweight = $nweight[$i]==null?0:$nweight[$i];
    $iunit1 = 0;
    $iunit2 = $nunit[$i];

    $Sql = "SELECT item.ItemCode,item.UnitCode
		  FROM item
      WHERE ItemCode = '$iItemStockId'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode  = $Result['ItemCode'];
      $iunit1    = $Result['UnitCode'];
    }

    $Sql = "SELECT COUNT(*) as Cnt
		  FROM newlinentable_detail
		  INNER JOIN item  ON newlinentable_detail.ItemCode = item.ItemCode
		  INNER JOIN newlinentable ON newlinentable.DocNo = newlinentable_detail.DocNo
		  WHERE newlinentable.DocNo = '$DocNo'
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
        $Sql = "INSERT INTO newlinentable_detail
            (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
            VALUES
            ('$DocNo','$ItemCode',$iunit2,$iqty,$iweight,0)";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO newlinentable_detail_sub
            (DocNo,ItemCode,UsageCode)
            VALUES
            ('$DocNo','$ItemCode','$UsageCode')";
        mysqli_query($conn, $Sql);
        $Sql = "UPDATE item_stock SET IsStatus = 3
            WHERE UsageCode = '$UsageCode'";
        mysqli_query($conn, $Sql);
      }
    } else {
      if ($Sel == 1) {
        $Sql = "UPDATE newlinentable_detail
          SET Weight = (weight+$iweight),Qty = (Qty+$iqty2)
          WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO newlinentable_detail_sub
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
    $Sql = "SELECT COUNT(*) AS Qty FROM newlinentable_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Qty[$n] = $Result['Qty'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xQty = $Qty[$i];
      // $Sqlx = "INSERT INTO log ( log ) VALUES ('$n :: $xQty :: $chkUpdate :: $iweight')";
      // mysqli_query($conn,$Sqlx);
      if ($chkUpdate == 0) {
        $Sql = "INSERT INTO newlinentable_detail
              (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
              VALUES
              ('$DocNo','$ItemCode',$iunit2,$xQty,0,0)";
      } else {
        $Sql = "UPDATE newlinentable_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      }
      mysqli_query($conn, $Sql);
    }
  }

    $n = 0;
    $Sql = "SELECT UsageCode FROM newlinentable_detail_sub WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $zUsageCode[$n] = $Result['UsageCode'];
      $n++;
    }
	$DepCode = 1;
	$Sql = "SELECT DepCode FROM department
	WHERE department.HptCode = '$Hotp' AND department.IsDefault = 1
	ORDER BY DepCode ASC LIMIT 1";
	$meQuery = mysqli_query($conn, $Sql);
	while ($Result = mysqli_fetch_assoc($meQuery)) {
	  $DepCode = $Result['DepCode'];
	}
    for ($i = 0; $i < $n; $i++) {
      $xUsageCode = $zUsageCode[$i];
	  $Sql = "UPDATE item_stock SET DepCode = $DepCode WHERE UsageCode = '$xUsageCode'";
      $meQuery = mysqli_query($conn, $Sql);
    }
    ShowDetail($conn, $DATA);
    
}

function UpdateDetailQty($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Qty  =  $DATA["Qty"];
  $OleQty =  $DATA["OleQty"];
  $UnitCode =  $DATA["unitcode"];
  $Sql = "UPDATE newlinentable_detail
	SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
	WHERE newlinentable_detail.Id = $RowID";
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

  $Sql = "UPDATE newlinentable_detail
	SET Weight = $Weight
	WHERE newlinentable_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  	$wTotal = 0;
  	$Sql = "SELECT SUM(Weight) AS wTotal FROM newlinentable_detail WHERE DocNo = '$DocNo'";
  	$meQuery = mysqli_query($conn,$Sql);
  	while ($Result = mysqli_fetch_assoc($meQuery)) {
      $wTotal  	= $Result['wTotal'];
      $return[0]['wTotal'] = $Result['wTotal'];
  	}
     $Sql = "UPDATE newlinentable SET Total = $wTotal WHERE DocNo = '$DocNo'";
   	mysqli_query($conn,$Sql);

     if (mysqli_query($conn,$Sql)) {
      $return['status'] = "success";
      $return['form'] = "UpdateDetailWeight";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['form'] = "UpdateDetailWeight";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }}

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $UnitCode =  $DATA["unitcode"];
  $qty =  $DATA["qty"];
  $Sql = "UPDATE newlinentable_detail
	SET UnitCode = $UnitCode
	WHERE newlinentable_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function DeleteItem($conn, $DATA)
{
  $RowID  = $DATA["rowid"];
  $DocNo = $DATA["DocNo"];
  $n = 0;
  $Sql = "SELECT newlinentable_detail_sub.UsageCode,newlinentable_detail.ItemCode
  FROM newlinentable_detail
  INNER JOIN newlinentable_detail_sub ON newlinentable_detail.DocNo = newlinentable_detail_sub.DocNo
  WHERE  newlinentable_detail.Id = $RowID";
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

  $Sql = "DELETE FROM newlinentable_detail_sub
	WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
  mysqli_query($conn, $Sql);

  $Sql = "DELETE FROM newlinentable_detail
	WHERE newlinentable_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetailDoc($conn, $DATA);
}

function SaveBill($conn, $DATA)
{
  $DocNo = $DATA["DocNo"];
  $isStatus = $DATA["isStatus"];

  $Sql = "UPDATE newlinentable SET IsStatus = $isStatus WHERE newlinentable.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  ShowDocument($conn,$DATA);
}

function UpdateRefDocNo($conn, $DATA)
{
  $DocNo = $DATA["xdocno"];
  $RefDocNo = $DATA["RefDocNo"];
  $Sql = "UPDATE newlinentable SET RefDocNo = '$RefDocNo' WHERE newlinentable.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  ShowDocument($conn, $DATA);
}

function ShowDetail($conn, $DATA)
{
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  //==========================================================
  $Sql = "SELECT
  newlinentable_detail.Id,
  newlinentable_detail.ItemCode,
  item.ItemName,
  item.UnitCode AS UnitCode1,
  item_unit.UnitName,
  newlinentable_detail.UnitCode AS UnitCode2,
  newlinentable_detail.Weight,
  newlinentable_detail.Qty,
  item.UnitCode
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN newlinentable_detail ON newlinentable_detail.ItemCode = item.ItemCode
  INNER JOIN item_unit ON newlinentable_detail.UnitCode = item_unit.UnitCode
  WHERE newlinentable_detail.DocNo = '$DocNo'
  ORDER BY newlinentable_detail.Id DESC";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID']      = $Result['Id'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitCode']   = $Result['UnitCode2'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $return[$count]['Weight']     = $Result['Weight'];
    $return[$count]['Qty']         = $Result['Qty'];
    $UnitCode                     = $Result['UnitCode1'];
    $ItemCode                     = $Result['ItemCode'];
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

  $Sql = "UPDATE newlinentable SET Total = $Total WHERE DocNo = '$DocNo'";
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

function CancelBill($conn, $DATA){
  $DocNo = $DATA["DocNo"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "UPDATE newlinentable SET IsStatus = 9  WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  ShowDocument($conn, $DATA);
}

function updateQty($conn, $DATA){
  $newQty = $DATA['newQty'];
  $RowID = $DATA['RowID'];
  $Sql = "UPDATE newlinentable_detail SET Qty = $newQty WHERE Id = $RowID";
  mysqli_query($conn, $Sql);
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
  } elseif ($DATA['STATUS'] == 'updateQty') {
    updateQty($conn, $DATA);
  }elseif ($DATA['STATUS'] == 'showDep') {
    showDep($conn, $DATA);
  }elseif ($DATA['STATUS'] == 'confirmDep') {
    confirmDep($conn, $DATA);
  }elseif ($DATA['STATUS'] == 'ShowDetailDoc') {
    ShowDetailDoc($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
