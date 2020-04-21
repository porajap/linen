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
  if($PmID ==2 || $PmID ==3 || $PmID ==5  || $PmID ==7){
    $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
    }else{
      $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0 ";
    }  
  }else{
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
      if($PmID ==2 || $PmID ==3 || $PmID ==5  || $PmID ==7){
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

function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'
  AND department.IsDefault = 1
  AND department.IsStatus = 0";
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
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function CreateDocument($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"]==null?' ':$DATA["hotpCode"];
  $deptCode = $DATA["deptCode"];
  $factory =  $DATA["factory"];
  $userid   = $DATA["userid"];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT CONCAT('RPW',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM repair_wash
  INNER JOIN department on repair_wash.DepCode = department.DepCode
  WHERE DocNo Like CONCAT('RPW',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  AND department.HptCode = '$hotpCode'
  ORDER BY DocNo DESC LIMIT 1";

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
    $Sql = "INSERT INTO log ( log ) VALUES ('".$Result['DocDate']." : ".$Result['DocNo']." :: $hotpCode :: $deptCode')";
      mysqli_query($conn,$Sql);
  }

  if ($count == 1) {
    $Sql = "INSERT INTO repair_wash (
              DocNo,
              HptCode,
              DocDate,
              DepCode,
              RefDocNo,
              Total,
              repair_wash.Modify_Code,
              repair_wash.Modify_Date
            )
            VALUES
              (
                '$DocNo',
                '$hotpCode',
                DATE(NOW()),
                '$deptCode',
                '',
                0,
                $userid,
                NOW()
              ) ";
      mysqli_query($conn, $Sql);

      //var_dump($Sql);
      $Sql = "INSERT INTO daily_request
      (DocNo,DocDate,DepCode,Detail,Modify_Code,Modify_Date)
      VALUES
      ('$DocNo',NOW(),'$deptCode','repair_wash',$userid,DATE(NOW()))";

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
    $lang                                = $_SESSION['lang'];
    $boolean                         = false;
    $count                            = 0;
    $Hotp                             = $DATA["Hotp"];
    $deptCode                     = $DATA["deptCode"];
    $DocNo                          = $DATA["docno"];
    $xDocNo                         = str_replace(' ', '%', $DATA["xdocno"]);
    $datepicker                     = $DATA["datepicker1"]==''?date('Y-m-d'):$DATA["datepicker1"];
    $selecta                          = $DATA["selecta"];
    $process                        = $DATA["process"];

    if( $process == 'chkpro1'){
      $onprocess1 = 0;
      $onprocess3 = 0;
      $onprocess4 = 0;
    }else if($process == 'chkpro2'){
      $onprocess1 = 1;
      $onprocess3 = 3;
      $onprocess4 = 4;
    }else if($process == 'chkpro3'){
      $onprocess1 = 9;
      $onprocess3 = 9;
      $onprocess4 = 9;
    }
    $Sql = "SELECT
    site.HptName,
    department.DepName,
    repair_wash.DocNo,
    DATE(repair_wash.DocDate) 
    AS DocDate,repair_wash.RefDocNo,
    repair_wash.Total, 
    users.EngName , 
    users.EngLName , 
    users.ThName , 
    users.ThLName , 
    users.EngPerfix , 
    users.ThPerfix ,
    TIME(repair_wash.Modify_Date) AS xTime,
    repair_wash.IsStatus,
    factory.FacName
    FROM repair_wash
    LEFT JOIN factory ON repair_wash.FacCode = factory.FacCode
    INNER JOIN department ON repair_wash.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    INNER JOIN users ON repair_wash.Modify_Code = users.ID ";
  // if($DocNo!=null){
  //   $Sql .= " WHERE repair_wash.DocNo = '$DocNo' ";
  // }else{
    if ($Hotp != null && $deptCode == null && $datepicker == null) {
      $Sql .= " WHERE site.HptCode = '$Hotp' AND repair_wash.DocNo LIKE '%$xDocNo%' ";
      if($xDocNo!=null){
        $Sql .= " OR repair_wash.DocNo LIKE '%$xDocNo%' ";
      }
    }else if($Hotp == null && $deptCode != null && $datepicker == null && $process == 'chkpro'){
        $Sql .= " WHERE repair_wash.DocNo LIKE '%$xDocNo%' ";
    }else if ($Hotp == null && $deptCode == null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE DATE(repair_wash.DocDate) = '$datepicker' AND repair_wash.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode != null && $datepicker == null && $process == 'chkpro'){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND repair_wash.DepCode = '$deptCode' AND repair_wash.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode == null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND DATE(repair_wash.DocDate) = '$datepicker' AND repair_wash.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp == null && $deptCode != null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE repair_wash.DepCode = '$deptCode' AND DATE(repair_wash.DocDate) = '$datepicker' AND repair_wash.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode != null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE repair_wash.DepCode = '$deptCode' AND DATE(repair_wash.DocDate) = '$datepicker' AND site.HptCode = '$Hotp' AND repair_wash.DocNo LIKE '%$xDocNo%'";
    }else if ($Hotp !='chkhpt' && $deptCode == 'chkdep' && $datepicker == null && $process != 'chkpro') {
      $Sql .= " WHERE  site.HptCode LIKE '%$Hotp%' AND  repair_wash.DocNo LIKE '%$xDocNo%'  AND ( repair_wash.IsStatus = $onprocess1 OR repair_wash.IsStatus = $onprocess3  OR repair_wash.IsStatus = $onprocess4 ) ";
    }else if($Hotp == 'chkhpt' && $deptCode != 'chkdep' && $datepicker == null && $process != 'chkpro'){
      $Sql .= " WHERE repair_wash.DepCode = '$deptCode'  AND ( repair_wash.IsStatus = $onprocess1 OR repair_wash.IsStatus = $onprocess3  OR repair_wash.IsStatus = $onprocess4 ) ";
  }else if ($Hotp == 'chkhpt' && $deptCode == 'chkdep' && $datepicker != null && $process != 'chkpro'){
    $Sql .= " WHERE DATE(repair_wash.DocDate) = '$datepicker' AND repair_wash.DocNo LIKE '%$xDocNo%'   AND ( repair_wash.IsStatus = $onprocess1 OR repair_wash.IsStatus = $onprocess3  OR repair_wash.IsStatus = $onprocess4 ) ";
  }else if ($Hotp != 'chkhpt' && $deptCode == 'chkdep' && $datepicker != null && $process != 'chkpro'){
    $Sql .= " WHERE site.HptCode LIKE '%$Hotp%' AND DATE(repair_wash.DocDate) = '$datepicker' AND ( repair_wash.IsStatus = $onprocess1 OR repair_wash.IsStatus = $onprocess3  OR repair_wash.IsStatus = $onprocess4 ) ";
  }else if ($Hotp != 'chkhpt' && $deptCode != 'chkdep' && $datepicker != null && $process != 'chkpro'){
    $Sql .= " WHERE site.HptCode LIKE '%$Hotp%' AND repair_wash.DepCode = '$deptCode' AND  DATE(repair_wash.DocDate) = '$datepicker' AND repair_wash.DocNo LIKE '%$xDocNo%'  AND ( repair_wash.IsStatus = $onprocess1 OR repair_wash.IsStatus = $onprocess3  OR repair_wash.IsStatus = $onprocess4 )";
  }
  // }
    $Sql .= "ORDER BY repair_wash.DocNo DESC LIMIT 500";
    $return['Sql'] =  $Sql;
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
      $return[$count]['FacName']      = $Result['FacName']==NULL?'':$Result['FacName'];
      $return[$count]['HptName']      = $Result['HptName'];
      $return[$count]['DepName']      = $Result['DepName'];
      $return[$count]['DocNo']        = $Result['DocNo'];
      $return[$count]['DocDate']      = $newdate;
      $return[$count]['RefDocNo']     = $Result['RefDocNo'];
      $return[$count]['RecNow']       = $Result['xTime'];
      $return[$count]['Total']        = $Result['Total'];
      $return[$count]['IsStatus']     = $Result['IsStatus'];
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
    $DocNo = $DATA["xdocno"];
    $Datepicker = $DATA["Datepicker"];
    $Sql = "SELECT site.HptCode,department.DepName,repair_wash.DocNo,DATE(repair_wash.DocDate) 
    AS DocDate ,repair_wash.Total,users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
    TIME(repair_wash.Modify_Date) AS xTime,repair_wash.IsStatus,repair_wash.RefDocNo,repair_wash.FacCode
    FROM repair_wash
    INNER JOIN department ON repair_wash.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    INNER JOIN users ON repair_wash.Modify_Code = users.ID
    WHERE repair_wash.DocNo = '$DocNo'";
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
      $return[$count]['DepName']   = $Result['DepName'];
      $return[$count]['DocNo']   = $Result['DocNo'];
      $return[$count]['DocDate']   = $newdate;
      $return[$count]['RecNow']   = $Result['xTime'];
      $return[$count]['Total']   = $Result['Total'];
      $return[$count]['FacCode2']   = $Result['FacCode'];
      $return[$count]['IsStatus'] = $Result['IsStatus'];
      $return[$count]['RefDocNo'] = $Result['RefDocNo'];

      $boolean = true;
      $count++;
    }

    $countx = 0;
    if($lang == 'en'){
      $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$Hotp'";
      }else{
      $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$Hotp'";
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
    
      $return[$countx]['FacCode'] = $Result['FacCode'];
      $return[$countx]['FacName'] = $Result['FacName'];
      $countx  ++;
    }
    $boolean = true;
    $return['Rowx'] = $countx;

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
  function getfactory($conn, $DATA){
    $lang     = $DATA["lang"];
    $hotpital = $DATA["hotpital"]==null?$_SESSION['HptCode']:$DATA["hotpital"];
    $boolean  = false;
    $countx = 0;
    if($lang == 'en'){
      $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$hotpital'";
      }else{
      $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$hotpital'";
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
    
      $return[$countx]['FacCode'] = $Result['FacCode'];
      $return[$countx]['FacName'] = $Result['FacName'];
      $countx  ++;
    }
    $boolean = true;
    $return['Rowx'] = $countx;
  
    if ($boolean) {
      $return['status'] = "success";
      $return['form'] = "getfactory";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['form'] = "getfactory";
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

    // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
    // mysqli_query($conn,$Sqlx);

    $Sql = "SELECT
                  item.ItemCode,
                  item.ItemName,
                  item.UnitCode,
                  item_unit.UnitName
                FROM
                  site
                INNER JOIN department ON site.HptCode = department.HptCode
                INNER JOIN par_item_stock ON department.DepCode = par_item_stock.DepCode
                INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
                WHERE
                  par_item_stock.DepCode = '$deptCode'
                AND item.ItemName LIKE '%$searchitem%'
                AND NOT item.IsClean = 1
                AND NOT item.IsDirtyBag = 1
                AND item.IsActive = 1
                GROUP BY
                  item.ItemCode
                ORDER BY
                  item.ItemName ASC
                LIMIT 100 ";
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
      $iqty = $nqty[$i];
      $iweight = $nweight[$i]==null?0:$nweight[$i];
      $iunit1 = 0;
      $iunit2 = $nunit[$i];

      // $Sqlx = "INSERT INTO log ( log ) VALUES ('RowID :: $iItemStockId')";
      // mysqli_query($conn, $Sqlx);

      $Sql = "SELECT item.ItemCode,item.UnitCode
		  FROM item
      WHERE ItemCode = '$iItemStockId'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ItemCode = $Result['ItemCode'];
        $iunit1 = $Result['UnitCode'];
      }

      $Sql = "SELECT COUNT(*) as Cnt
      FROM repair_wash_detail
      INNER JOIN item  ON repair_wash_detail.ItemCode = item.ItemCode
      INNER JOIN repair_wash ON repair_wash.DocNo = repair_wash_detail.DocNo
      WHERE repair_wash.DocNo = '$DocNo'
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
          $Sql = " INSERT INTO repair_wash_detail(DocNo, ItemCode, UnitCode, Qty, Weight, IsCancel)
          VALUES('$DocNo', '$ItemCode', $iunit2, $iqty2, $iweight, 0) ";
          mysqli_query($conn, $Sql);
        }
      } else {
        if ($Sel == 1) {
          $Sql = " UPDATE repair_wash_detail
          SET Weight = (Weight+$iweight), Qty = (Qty + $iqty2)
          WHERE DocNo = '$DocNo' and ItemCode = '$ItemCode' ";
          mysqli_query($conn, $Sql);
        }
      }
    }

    if ($Sel == 2) {
      $n = 0;
      $Sql = "SELECT COUNT(*) AS Qty FROM repair_wash_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
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
          $Sql = "INSERT INTO repair_wash_detail
          (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
          VALUES
          ('$DocNo','$ItemCode',$iunit2,$xQty,0,0)";
        } else {
          $Sql = "UPDATE repair_wash_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
        }
        mysqli_query($conn, $Sql);
      }
    }

    ShowDetail($conn, $DATA);
  }

  function UpdateDetailQty($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $Qty  =  $DATA["Qty"];
    $OleQty =  $DATA["OleQty"];
    $UnitCode =  $DATA["unitcode"];
    $Sql = "UPDATE repair_wash_detail
    SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
    WHERE repair_wash_detail.Id = $RowID";
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

    $Sql = "UPDATE repair_wash_detail
    SET Weight = $Weight
    WHERE repair_wash_detail.Id = $RowID";
    mysqli_query($conn, $Sql);

    $Sql = "SELECT SUM(Weight) AS Weight2 FROM repair_wash_detail WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Weight2 = $Result['Weight2'];
    }

  $Sql="UPDATE repair_wash SET Total = $Weight2 WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
    // ShowDetail($conn, $DATA);
  }

  function updataDetail($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $UnitCode =  $DATA["unitcode"];
    $qty =  $DATA["qty"];
    $Sql = "UPDATE repair_wash_detail
    SET UnitCode = $UnitCode
    WHERE repair_wash_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function DeleteItem($conn, $DATA)
  {
    $RowID  = $DATA["rowid"];
    $DocNo = $DATA["DocNo"];
    $n = 0;
    // $Sql = "SELECT repair_wash_detail_sub.UsageCode,repair_wash_detail.ItemCode
    // FROM repair_wash_detail
    // INNER JOIN repair_wash_detail_sub ON repair_wash_detail.DocNo = repair_wash_detail_sub.DocNo
    // WHERE  repair_wash_detail.Id = $RowID";
    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $ItemCode = $Result['ItemCode'];
    //   $UsageCode[$n] = $Result['UsageCode'];
    //   $n++;
    // }

    // for ($i = 0; $i < $n; $i++) {
    //   $xUsageCode = $UsageCode[$i];
    //   $Sql = "UPDATE item_stock SET IsStatus = 6 WHERE UsageCode = '$xUsageCode'";
    //   mysqli_query($conn, $Sql);
    // }

    // $Sql = "DELETE FROM repair_wash_detail_sub
    // WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    // mysqli_query($conn, $Sql);

    $Sql = "DELETE FROM repair_wash_detail
    WHERE repair_wash_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function SaveBill($conn, $DATA)
  {
    $DocNo = $DATA["xdocno"];
    $DocNo2 = $DATA["xdocno2"];
    $isStatus = $DATA["isStatus"];
    $factory = $DATA["factory"];

    $Sql = "UPDATE repair_wash SET IsStatus = $isStatus  , FacCode = $factory  WHERE repair_wash.DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);

    // ================================================================================
 
    $Sql = "UPDATE rewash SET IsRef = 1 WHERE rewash.DocNo = '$DocNo2'";
    mysqli_query($conn, $Sql);
    
    $Sqlx = "SELECT newlinentable.DocNo FROM newlinentable WHERE newlinentable.DocNo = '$DocNo2' ";
    $meQuery = mysqli_query($conn, $Sqlx);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $DocNonewlinentable = $Result['DocNo'];
    }
    if($DocNonewlinentable != "" ){
      $Sql = "UPDATE newlinentable SET IsRef = 1 WHERE newlinentable.DocNo = '$DocNo2'";
      mysqli_query($conn, $Sql);
      }
    
    // ================================================================================

    $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);

    $Sql = "UPDATE factory_out SET IsRequest = 1 WHERE DocNo = '$DocNo2'";
    mysqli_query($conn, $Sql);

  }

  function UpdateRefDocNo($conn, $DATA)
  {
    $count = 0;
    $hptcode = $DATA["hptcode"];
    $DocNo = $DATA["xdocno"];
    $RefDocNo = $DATA["RefDocNo"];

    // $checkitem = $DATA["checkitem"];
    // $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo / $RefDocNo')";
    // mysqli_query($conn,$Sqlx);


    // update เอกสารอ้างอิง เข้า repair_wash
    $Sql = "UPDATE repair_wash SET RefDocNo = '$RefDocNo' WHERE DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);

    $Sql = "UPDATE daily_request SET RefDocNo = '$RefDocNo' WHERE DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);

    $Sql = "UPDATE clean SET IsRef = 1 WHERE clean.DocNo = '$RefDocNo'";
      mysqli_query($conn, $Sql);

    // select เอา faccode กับ docno ไปแสดงหน้า page
    $Sql2 = "SELECT clean.FacCode , clean.DocNo  FROM clean WHERE clean.DocNo = '$RefDocNo'";
        $meQuery = mysqli_query($conn, $Sql2);
          while ($Result2 = mysqli_fetch_assoc($meQuery)) {
            $return['FacCode']  = $Result2['FacCode'];
            $return['DocNo']    = $Result2['DocNo'];
            $FacCode1           = $Result2['FacCode'];
            $count              == 1;
          }
    
    // update refdocno , faccode เข้าเอกสาร repair_wash
    $Sql = "UPDATE repair_wash SET RefDocNo = '$RefDocNo' , FacCode = $FacCode1 WHERE DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);
    
    // $n = 0;
    // $Sql = "SELECT
    // rewash_detail.ItemCode,
    // rewash_detail.UnitCode,
    // rewash_detail.Qty,
    // rewash_detail.Weight,
    // rewash_detail.IsCancel
    // FROM rewash_detail
    // WHERE rewash_detail.DocNo = '$RefDocNo'";
    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $zItemCode[$n] = $Result['ItemCode'];
    //   $zUnitCode[$n] = $Result['UnitCode'];
    //   $zQty[$n]      = $Result['Qty'];
    //   $zWeight[$n]   = $Result['Weight'];
    //   $zIsCancel[$n] = $Result['IsCancel'];
    //   $n++;
    // }
    // for ($i = 0; $i < $n; $i++) {
    //   $ItemCode = $zItemCode[$i];
    //   $UnitCode = $zUnitCode[$i];
    //   $Qty      = $zQty[$i];
    //   $Weight   = $zWeight[$i];
    //   $IsCancel = $zIsCancel[$i];
    //   $Sql = "INSERT INTO repair_wash_detail
    //   (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
    //   VALUES
    //   ('$DocNo','$ItemCode',$UnitCode,$Qty,$Weight,$IsCancel)";
    //   mysqli_query($conn, $Sql);
    // }

    if($count == 1){
      $return['status'] = "success";
      $return['form'] = "UpdateRefDocNo";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "success";
      $return['form'] = "UpdateRefDocNo";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    // SelectDocument($conn, $DATA);

    // $n = 0;
    // $Sql = "SELECT
    // dirty_detail.ItemCode,
    // dirty_detail.UnitCode,
    // dirty_detail.Qty,
    // dirty_detail.Weight,
    // dirty_detail.IsCancel
    // FROM dirty_detail
    // WHERE dirty_detail.DocNo = '$RefDocNo'";
    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $zItemCode[$n] = $Result['ItemCode'];
    //   $zUnitCode[$n] = $Result['UnitCode'];
    //   $zQty[$n]      = $Result['Qty'];
    //   $zWeight[$n]   = $Result['Weight'];
    //   $zIsCancel[$n] = $Result['IsCancel'];
    //   $n++;
    // }
    // for ($i = 0; $i < $n; $i++) {
    //   $ItemCode = $zItemCode[$i];
    //   $UnitCode = $zUnitCode[$i];
    //   $Qty      = $zQty[$i];
    //   $Weight   = $zWeight[$i];
    //   $IsCancel = $zIsCancel[$i];
    //   $Sql = "INSERT INTO repair_wash_detail
    //   (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
    //   VALUES
    //   ('$DocNo','$ItemCode',$UnitCode,$Qty,$Weight,$IsCancel)";
    //   mysqli_query($conn, $Sql);
    // }
    // $n = 0;
    // $Sql = "SELECT factory_out_detail_sub.UsageCode,factory_out_detail.ItemCode
    // FROM factory_out_detail
    // INNER JOIN factory_out_detail_sub ON factory_out_detail.DocNo = factory_out_detail_sub.DocNo
    // WHERE  factory_out_detail_sub.DocNo = '$RefDocNo'";
    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $ItemCode = $Result['ItemCode'];
    //   $UsageCode[$n] = $Result['UsageCode'];
    //   $n++;
    // }
    // for ($i = 0; $i < $n; $i++) {
    //   $xUsageCode = $UsageCode[$i];
    //   $Sql = " INSERT INTO repair_wash_detail_sub(DocNo, ItemCode, UsageCode)
    //   VALUES('$DocNo', '$ItemCode', '$xUsageCode') ";
    //   mysqli_query($conn, $Sql);

    //   $Sql = "UPDATE item_stock SET IsStatus = 0 WHERE UsageCode = '$xUsageCode'";
    //   mysqli_query($conn, $Sql);
    // }

  }

  function chk_percent($conn, $DATA){
    $Total = 0;
    $count=0;
    $boolean = false;
    $RefDocNo = $DATA['RefDocNo'];
    $DocNo = $DATA['DocNo'];

    $Sql = "SELECT repair_wash.Total
      FROM repair_wash WHERE repair_wash.DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn,$Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $cTotal	= $Result['Total']==null?0:$Result['Total'];
      }

    $Sql = "SELECT dirty.Total
    FROM dirty WHERE dirty.DocNo = '$RefDocNo'";
      $return['sql'] = $Sql;
      $meQuery = mysqli_query($conn,$Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $dTotal	= $Result['Total']==null?0:$Result['Total'];
        $boolean = true;
        $count++;
      }
    if($dTotal !=0){
      $Total =  ROUND( ((($cTotal - $dTotal )/$dTotal)*100)*-1 , 2)  ;
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
    //==========================================================
    $Sql = "        SELECT
                                      repair_wash_detail.Id,
                                      repair_wash_detail.DocNo,
                                      repair_wash_detail.ItemCode,
                                      item.ItemName,
                                      item.UnitCode AS UnitCode1,
                                      item_unit.UnitName,
                                      repair_wash_detail.UnitCode AS UnitCode2,
                                      repair_wash_detail.Weight,
                                      repair_wash_detail.Qty

                      FROM    item
                      INNER JOIN item_category        ON item.CategoryCode = item_category.CategoryCode
                      INNER JOIN repair_wash_detail ON repair_wash_detail.ItemCode = item.ItemCode
                      INNER JOIN item_unit                ON repair_wash_detail.UnitCode = item_unit.UnitCode
                      INNER JOIN repair_wash            ON repair_wash_detail.DocNo = repair_wash.DocNo
                      WHERE repair_wash_detail.DocNo = '$DocNo'
                      ORDER BY repair_wash_detail.Id DESC";
    $return['sql']=$Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {

      //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$count :: ".$Result['Id']." / ".$Result['Weight']."')";
      //	mysqli_query($conn,$Sqlx);

      $return[$count]['RowID']      = $Result['Id'];
      $return[$count]['ItemCode']   = $Result['ItemCode'];
      $return[$count]['ItemName']   = $Result['ItemName'];
      $return[$count]['UnitCode']   = $Result['UnitCode2'];
      $return[$count]['UnitName']   = $Result['UnitName'];
      $return[$count]['Weight']     = $Result['Weight'] ==0?'':$Result['Weight'];
      $return[$count]['Qty']        = $Result['Qty']    ==0?'':$Result['Qty'];
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

    $Sql = "      UPDATE      repair_wash

                       SET            Total = $Total 

                      WHERE       DocNo = '$DocNo'";

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
    $Sql = "    UPDATE      repair_wash 

                    SET             IsStatus = 9  

                    WHERE       DocNo = '$DocNo'";

    $meQuery = mysqli_query($conn, $Sql);

    $Sql = "    UPDATE      rewash 

                    SET             IsRef = 0 

                    WHERE       rewash.DocNo = '$RefDocNo'";

    mysqli_query($conn, $Sql);
    ShowDocument($conn, $DATA);
  }

  function updateQty($conn, $DATA)
  {
    $newQty = $DATA['newQty'];
    $RowID = $DATA['RowID'];

    $Sql = "  UPDATE      repair_wash_detail 

                  SET             Qty = $newQty

                  WHERE       Id = $RowID";

    mysqli_query($conn, $Sql);
  }

  function get_dirty_doc($conn, $DATA)
  {
    $lang = $_SESSION['lang'];
    $hptcode = $DATA["hptcode"];
    $searchitem1 = $DATA["searchitem1"];
    $datepicker = $DATA["datepicker"]==''?date('Y-m-d'):$DATA["datepicker"];
    $boolean = false;
    $count = 0;
    $Sql =  "SELECT    
                                  clean.DocNo  ,
                                  clean.DocDate  , 
                                  factory.FacName ,
                                 TIME(clean.Modify_Date) AS Modify_Date
                  FROM      clean

                  INNER JOIN factory 
                  ON factory.FacCode = clean.FacCode

                  INNER JOIN department 
                  ON department.DepCode = clean.DepCode

                  INNER JOIN site 
                  ON site.HptCode = department.HptCode

                  WHERE (clean.IsStatus = 1 OR clean.IsStatus = 2 OR clean.IsStatus = 3 ) 
                  AND site.HptCode= '$hptcode'  
                  AND  clean.DocNo LIKE '%$searchitem1%'
                  AND (clean.DocDate LIKE '%$datepicker%')

                  ORDER BY  clean.Modify_Date ASC ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) 
    {
      if($lang =='en')
      {
        $date2 = explode("-", $Result['DocDate']);
        $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
      }
      else if ($lang == 'th')
      {
        $date2 = explode("-", $Result['DocDate']);
        $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
      }
      $return[$count]['DocDate'] =  $newdate;
      $return[$count]['RefDocNo'] = $Result['DocNo'];
      $return[$count]['FacName'] = $Result['FacName'];
      $return[$count]['Modify_Date'] = $Result['Modify_Date'];
      $boolean = true;
      $count++;
    }
    
    $return['Row'] = $count;
    // echo json_encode($return);
    // mysqli_close($conn);
    // die;
    if ($boolean) {
      $return['status'] = "success";
      $return['form']   = "get_dirty_doc";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "success";
      $return['form']   = "get_dirty_doc";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }
  function savefactory($conn, $DATA){
    $DocNo = $DATA["DocNo"];
    $factory2 = $DATA["factory2"];
  
    $Sql ="UPDATE repair_wash 
              SET FacCode = $factory2 
              WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    $return['FacCode'] = $factory2;
  
    if (mysqli_query($conn, $Sql)) {
      $return['status2'] = $Sql;
      $return['status'] = "success";
      $return['form'] = "savefactory";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['form'] = "savefactory";
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
  } elseif ($DATA['STATUS'] == 'getfactory') {
    getfactory($conn, $DATA);
  }  elseif ($DATA['STATUS'] == 'savefactory') {
    savefactory($conn, $DATA);
  }



    
  } else {
    $return['status'] = "error";
    $return['msg'] = 'noinput';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
