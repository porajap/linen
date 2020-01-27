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
  $boolean = false;
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
function getfactory($conn,$DATA){
  $Hotp = $DATA["Hotp"];
  $PmID = $_SESSION['PmID'];
  $lang = $_SESSION['lang'];
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

  // select หาแผนกหลัก
  $Sql2 = "SELECT department.DepName 
                FROM department 
                WHERE department.HptCode = '$Hotp' 
                AND department.IsDefault = 1 
                AND department.IsActive   = 1 
                AND department.IsStatus   =0";
  $meQuery2 = mysqli_query($conn, $Sql2);
  $Result2 = mysqli_fetch_assoc($meQuery2);
  $return['DepName'] = $Result2['DepName'];

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
    $return['msg'] = 'selectnothospital';
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
  $deptCode = $DATA["deptCode"];
  $factory   = $DATA["factory"];
  $userid   = $DATA["userid"];
  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT CONCAT('DMN',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM damagenh
  INNER JOIN department on damagenh.DepCode = department.DepCode
  WHERE DocNo Like CONCAT('DMN',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
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
    $Sql = "INSERT INTO damagenh (
              DocNo,
              DocDate,
              DepCode,
              Total,
              damagenh.Modify_Code,
              damagenh.Modify_Date,
              FacCode
            )
            VALUES
              (
                '$DocNo',
                DATE(NOW()),
                '$deptCode',
                0,
                $userid,
                NOW(),
                '$factory' )";
      mysqli_query($conn, $Sql);

      //var_dump($Sql);
      $Sql = "INSERT INTO daily_request
      (DocNo,DocDate,DepCode,Detail,Modify_Code,Modify_Date)
      VALUES
      ('$DocNo',DATE(NOW()),'$deptCode','damagenh',$userid,DATE(NOW()))";

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
    $lang                             = $_SESSION['lang'];
    $boolean                       = false;
    $count                            = 0;
    $Hotp                            = $DATA["Hotp"];
    $deptCode                     = $DATA["deptCode"];
    $DocNo                         = $DATA["docno"];
    $xDocNo                       = str_replace(' ', '%', $DATA["xdocno"]);
    $datepicker                   = $DATA["datepicker1"]==''?date('Y-m-d'):$DATA["datepicker1"];
    $selecta                         = $DATA["selecta"];
    $process                        = $DATA["process"];

    if( $process == 'chkpro1'){
      $onprocess1 = 0;
    }else if($process == 'chkpro2'){
      $onprocess1 = 1;
    }else if($process == 'chkpro3'){
      $onprocess1 = 9;
    }
    // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
    // mysqconn,$Sql);
    $Sql = "SELECT
    site.HptName,
    department.DepName,
    damagenh.DocNo,
    DATE(damagenh.DocDate) AS DocDate,
    damagenh.Total,
    users.EngName,
    users.EngLName,
    users.ThName,
    users.ThLName,
    users.EngPerfix,
    users.ThPerfix,
    TIME(damagenh.Modify_Date) AS xTime,
    damagenh.IsStatus
  FROM
    damagenh
  INNER JOIN department ON damagenh.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN users ON damagenh.Modify_Code = users.ID ";
  // if($DocNo!=null){
  //   $Sql .= " WHERE damagenh.DocNo = '$DocNo' AND damagenh.DocNo LIKE '%$xDocNo%'";
  // }else{
    if ($Hotp != null && $deptCode != null && $datepicker == null && $process == 'chkpro') {
      $Sql .= " WHERE site.HptCode = '$Hotp'  ";
      if($xDocNo!=null){
        $Sql .= " OR damagenh.DocNo LIKE '%$xDocNo%' ";
      }
    }else if($Hotp == null && $deptCode != null && $datepicker == null && $process == 'chkpro'){
        $Sql .= " WHERE damagenh.DocNo LIKE '%$xDocNo%' ";
    }else if ($Hotp == null && $deptCode == null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE DATE(damagenh.DocDate) = '$datepicker' AND damagenh.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode != null && $datepicker == null && $process == 'chkpro'){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND damagenh.DepCode = '$deptCode' AND damagenh.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode == null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE site.HptCode = '$Hotp' AND DATE(damagenh.DocDate) = '$datepicker' AND damagenh.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp == null && $deptCode != null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE damagenh.DepCode = '$deptCode' AND DATE(damagenh.DocDate) = '$datepicker' AND damagenh.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp != null && $deptCode != null && $datepicker != null && $process == 'chkpro'){
      $Sql .= " WHERE damagenh.DepCode = '$deptCode' AND DATE(damagenh.DocDate) = '$datepicker' AND site.HptCode = '$Hotp' AND damagenh.DocNo LIKE '%$xDocNo%'";
    }else if($Hotp == 'chkhpt' && $deptCode != 'chkdep' && $datepicker == null && $process != 'chkpro'){
      $Sql .= " WHERE damagenh.DepCode = '$deptCode'  AND damagenh.IsStatus = $onprocess1  ";
  }else if ($Hotp == 'chkhpt' && $deptCode == 'chkdep' && $datepicker != null && $process != 'chkpro'){
    $Sql .= " WHERE DATE(damagenh.DocDate) = '$datepicker' AND damagenh.DocNo LIKE '%$xDocNo%'   AND  damagenh.IsStatus = $onprocess1  ";
  }else if ($Hotp != 'chkhpt' && $deptCode == 'chkdep' && $datepicker != null && $process != 'chkpro'){
    $Sql .= " WHERE site.HptCode LIKE '%$Hotp%' AND DATE(damagenh.DocDate) = '$datepicker' AND damagenh.DocNo LIKE '%$xDocNo%'  AND  damagenh.IsStatus = $onprocess1  ";
  }else if ($Hotp != 'chkhpt' && $deptCode != 'chkdep' && $datepicker != null && $process != 'chkpro'){
    $Sql .= " WHERE site.HptCode LIKE '%$Hotp%' AND damagenh.DepCode = '$deptCode' AND  DATE(damagenh.DocDate) = '$datepicker' AND damagenh.DocNo LIKE '%$xDocNo%'   AND  damagenh.IsStatus = $onprocess1 ";
  }
  // }
    $Sql .= "ORDER BY damagenh.DocNo DESC LIMIT 500";
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
    // select  เพื่อไปแสดงหน้า web
    $Sql = "SELECT   site.HptCode,
                                department.DepName,
                                damagenh.DocNo,
                                DATE(damagenh.DocDate) AS DocDate ,
                                damagenh.Total,
                                users.EngName ,
                                users.EngLName ,
                                users.ThName , 
                                users.ThLName , 
                                users.EngPerfix , 
                                users.ThPerfix ,
                                TIME(damagenh.Modify_Date) AS xTime,
                                damagenh.IsStatus,
                                damagenh.RefDocNo,
                                damagenh.FacCode
              FROM damagenh
              INNER JOIN department ON damagenh.DepCode = department.DepCode
              INNER JOIN site ON department.HptCode = site.HptCode
              INNER JOIN users ON damagenh.Modify_Code = users.ID
              LEFT JOIN factory ON damagenh.FacCode = factory.FacCode
    WHERE damagenh.DocNo = '$DocNo'";
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
      $return[$count]['IsStatus'] = $Result['IsStatus'];
      $return[$count]['RefDocNo'] = $Result['RefDocNo'];
      $return[$count]['FacCode2'] = $Result['FacCode'];

      $boolean = true;
      $count++;
    }
  //===============================================
  // select หาแผนกหลัก
  $Sql2 = "SELECT department.DepName 
  FROM department 
  WHERE department.HptCode = '$Hotp' 
  AND department.IsDefault = 1 
  AND department.IsActive   = 1 
  AND department.IsStatus   =0";
  $meQuery2 = mysqli_query($conn, $Sql2);
  $Result2 = mysqli_fetch_assoc($meQuery2);
  $return['DepName'] = $Result2['DepName'];
// ===============================================

// select โรงซัก
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
    $return['Rowx'] = $countx;
// ===============================================
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
    while ($Result = mysqli_fetch_assoc($meQuery)) 
    {
      $return[$count]['ItemCode'] = $Result['ItemCode'];
      $return[$count]['ItemName'] = $Result['ItemName'];
      $return[$count]['UnitCode'] = $Result['UnitCode'];
      $return[$count]['UnitName'] = $Result['UnitName'];
      $ItemCode = $Result['ItemCode'];
      $UnitCode = $Result['UnitCode'];
      $count2 = 0;
      $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode' ";
      $MQuery = mysqli_query($conn, $countM);
      while ($MResult = mysqli_fetch_assoc($MQuery)) 
      {
        $return['sql'] = $countM;
        if($MResult['cnt']!=0)
        {
          $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
          FROM item_multiple_unit
          INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
          WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
          $xQuery = mysqli_query($conn, $xSql);
          while ($xResult = mysqli_fetch_assoc($xQuery)) 
          {
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
        }
        else
        {
          $xSql = "SELECT 
            item.UnitCode,
            item_unit.UnitName
          FROM item
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          WHERE item.ItemCode = '$ItemCode'";
          $xQuery = mysqli_query($conn, $xSql);
          while ($xResult = mysqli_fetch_assoc($xQuery)) 
          {
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

    if ($boolean)
    {
      $return['status'] = "success";
      $return['form'] = "ShowItem";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } 
    else 
    {
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
    $RefDocNo = $DATA["RefDocNo"];
    $DocNo = $DATA["DocNo"];
    $xItemStockId = $DATA["xrow"];
    $ItemStockId = explode(",", $xItemStockId);
    $xqty = $DATA["xqty"];
    $nqty = explode(",", $xqty);
    $xweight = $DATA["xweight"]==null?0:$DATA["xweight"] ;
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
      FROM damagenh_detail
      INNER JOIN item  ON damagenh_detail.ItemCode = item.ItemCode
      INNER JOIN damagenh ON damagenh.DocNo = damagenh_detail.DocNo
      WHERE damagenh.DocNo = '$DocNo'
      AND item.ItemCode = '$ItemCode'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $chkUpdate = $Result['Cnt'];
      }

      if ($chkUpdate == 0) {
        if ($Sel == 1) 
        {
          $Sql = " INSERT INTO damagenh_detail(DocNo, ItemCode, UnitCode, Qty, Weight, IsCancel , RefDocNo)
          VALUES('$DocNo', '$ItemCode', $iunit2, $iqty, $iweight , 0 , '$RefDocNo') ";
          mysqli_query($conn, $Sql);
        } 
      } 
      else 
      {
        if ($Sel == 1) 
        {
          $Sql = " UPDATE damagenh_detail
          SET  Qty = (Qty + $iqty)
          WHERE DocNo = '$DocNo' and ItemCode = '$ItemCode' ";
          mysqli_query($conn, $Sql);
        }

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
    $Sql = "UPDATE damagenh_detail
    SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
    WHERE damagenh_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function UpdateQty($conn, $DATA)
  {
     $RowID  = $DATA["Rowid"];
     $Qty  =  $DATA["Qty"];
     $Sql = "UPDATE damagenh_detail SET Qty = $Qty WHERE damagenh_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    // ShowDetail($conn, $DATA);
  }

  function UpdateDetailWeight($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $Weight  =  $DATA["Weight"];
    $Price  =  $DATA["Price"];
    $isStatus = $DATA["isStatus"];

    //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
    //	mysqli_query($conn,$Sqlx);

    $Sql = "UPDATE damagenh_detail
    SET Weight = $Weight
    WHERE damagenh_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function updataDetail($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $UnitCode =  $DATA["unitcode"];
    $qty =  $DATA["qty"];
    $Sql = "UPDATE damagenh_detail
    SET UnitCode = $UnitCode
    WHERE damagenh_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function DeleteItem($conn, $DATA)
  {
    $RowID  = $DATA["rowid"];
    $DocNo = $DATA["DocNo"];
    $n = 0;
    // $Sql = "SELECT damagenh_detail_sub.UsageCode,damagenh_detail.ItemCode
    // FROM damagenh_detail
    // INNER JOIN damagenh_detail_sub ON damagenh_detail.DocNo = damagenh_detail_sub.DocNo
    // WHERE  damagenh_detail.Id = $RowID";
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

    // $Sql = "DELETE FROM damagenh_detail_sub
    // WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    // mysqli_query($conn, $Sql);

    $Sql = "DELETE FROM damagenh_detail
    WHERE damagenh_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function SaveBill($conn, $DATA)
  {
    $Qty55 = $DATA["Qty"];
    $Qtyz = explode(",", $Qty55);
    $ItemCode = $DATA["ItemCode"];
    $ItemCodex = explode(",", $ItemCode);
    $DocNo = $DATA["xdocno"];
    $isStatus = $DATA["isStatus"];

    $max = sizeof($ItemCodex, 0);
    for ($i = 0; $i < $max; $i++) {
      $iItemStockId = $ItemCodex[$i];
      $Qtyzz = $Qtyz[$i];

      $update55 = "UPDATE damagenh_detail SET Qty = $Qtyzz WHERE DocNo = '$DocNo' AND ItemCode = '$iItemStockId'";
      mysqli_query($conn, $update55);

    }

    $Sql = "UPDATE damagenh SET IsStatus = $isStatus WHERE damagenh.DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);

    // $Sql = "UPDATE dirty SET IsRef = 1 WHERE dirty.DocNo = '$DocNo2'";
    // mysqli_query($conn, $Sql);

    $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);

  }

  function UpdateRefDocNo($conn, $DATA)
  {
    $hptcode = $DATA["hptcode"];
    $DocNo = $DATA["xdocno"];
    $RefDocNo = $DATA["RefDocNo"];
    // $checkitem = $DATA["checkitem"];
    // $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo / $RefDocNo')";
    // mysqli_query($conn,$Sqlx);
    $Sql = "UPDATE damagenh SET RefDocNo = '$RefDocNo' WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
    $Sql = "UPDATE daily_request SET RefDocNo = '$RefDocNo' WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
    $Sql = "UPDATE clean SET IsRef = 1 WHERE DocNo = '$RefDocNo'";
    mysqli_query($conn, $Sql);
    // $n = 0;
    // $Sql = "SELECT
    // claim_detail.ItemCode,
    // claim_detail.UnitCode1,
    // claim_detail.IsCancel
    // FROM claim_detail
    // WHERE claim_detail.DocNo = '$RefDocNo'";
    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $zItemCode[$n] = $Result['ItemCode'];
    //   $zUnitCode[$n] = $Result['UnitCode1'];
    //   $zIsCancel[$n] = $Result['IsCancel'];
    //   $n++;
    // }
    // for ($i = 0; $i < $n; $i++) {
    //   $ItemCode = $zItemCode[$i];
    //   $UnitCode = $zUnitCode[$i];
    //   $IsCancel = $zIsCancel[$i];
    //   $Sql = "INSERT INTO damagenh_detail
    //   (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel,RefDocNo)
    //   VALUES
    //   ('$DocNo','$ItemCode',$UnitCode,0,0,$IsCancel,'$RefDocNo')";
    //   mysqli_query($conn, $Sql);
    // }




    SelectDocument($conn, $DATA);
  }





  function ShowDetail($conn, $DATA)
  {
    $count = 0;
    $Total = 0;
    $boolean = false;
    $DocNo = $DATA["DocNo"];


    //==========================================================
    $Sql = "SELECT
    damagenh_detail.Id,
    damagenh_detail.DocNo,
    damagenh_detail.ItemCode,
    damagenh_detail.RefDocNo,
    item.ItemName,
    item.UnitCode AS UnitCode1,
    item_unit.UnitName,
    damagenh_detail.UnitCode AS UnitCode2,
    damagenh_detail.Weight,
    damagenh_detail.Qty
    FROM
    item
    INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
    INNER JOIN damagenh_detail ON damagenh_detail.ItemCode = item.ItemCode
    INNER JOIN item_unit ON damagenh_detail.UnitCode = item_unit.UnitCode
    INNER JOIN damagenh ON damagenh_detail.DocNo = damagenh.DocNo
    WHERE damagenh_detail.DocNo = '$DocNo'
    ORDER BY damagenh_detail.Id DESC";
    $return['sql']=$Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {

      $RefDocNo = $Result['RefDocNo'];
      $ItemCode = $Result['ItemCode'];

      $Sqlx =  "SELECT SUM(Qty) AS Qty FROM damagenh_detail WHERE RefDocNo = '$RefDocNo' AND ItemCode = '$ItemCode'";
      $meQueryx = mysqli_query($conn, $Sqlx);
      while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
        $Qtyx = $Resultx['Qty'];
      }
      $return[$count]['RowID']      = $Result['Id'];
      $return[$count]['ItemCode']   = $Result['ItemCode'];
      $return[$count]['ItemName']   = $Result['ItemName'];
      $return[$count]['UnitCode']   = $Result['UnitCode2'];
      $return[$count]['UnitName']   = $Result['UnitName'];
      $return[$count]['Weight']     = $Result['Weight'];
      $return[$count]['Qty']        = $Result['Qty']  ==0?'':$Result['Qty'];
      $return[$count]['Detail']     = $Result['Detail']==null?'':$Result['Detail'];
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

    $Sql = "UPDATE damagenh SET Total = $Total WHERE DocNo = '$DocNo'";
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
    // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
    // mysqli_query($conn,$Sql);
    $Sql = "UPDATE damagenh SET IsStatus = 9  WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    ShowDocument($conn, $DATA);
  }

  function get_claim_doc($conn, $DATA)
  {
    $hptcode = $DATA["hptcode"];
    $searchitem1 = $DATA["searchitem1"];
    $boolean = false;
    $count = 0;
    $Sql = "SELECT clean.DocNo
    FROM clean
    INNER JOIN department ON clean.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    WHERE clean.IsCancel = 0 
    AND clean.IsStatus = 1
    AND clean.IsRef = 0
    AND site.HptCode = '$hptcode'
    AND  clean.DocNo LIKE '%$searchitem1%'
    ORDER BY clean.Modify_Date DESC
    LIMIT 100";
    // var_dump($Sql); die;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['RefDocNo'] = $Result['DocNo'];
      $boolean = true;
      $count++;
    }
    $return['Row'] = $count;
    // $return['form'] = "get_dirty_doc";
    // echo json_encode($return);
    // mysqli_close($conn);
    // die;
    if ($boolean) {
      $return['status'] = "success";
      $return['form'] = "get_claim_doc";
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
  function showExcel($conn, $DATA){
    $HptCode = $_SESSION['HptCode'];
    // $ID = $DATA['ID'];
    $Keyword = $DATA['Keyword'];
    $KeyDate = $DATA['KeyDate'];
 
    $count = 0;
    $Sql = "SELECT df.ID, df.FileName, df.Date, users.EngName  
    FROM damage_file df
    INNER JOIN users ON users.ID = df.UserID
    WHERE df.HptCode = '$HptCode' 
    AND (df.Date LIKE '%$KeyDate%') AND (df.FileName LIKE '%$Keyword%') AND df.Status = 0 ORDER BY df.Date ASC";
    $return['sql'] = $Sql;
    $meQuery = mysqli_query($conn, $Sql);
     while ($Result = mysqli_fetch_assoc($meQuery)) {
       $return[$count]['ID'] = $Result['ID'];
       $return[$count]['FileName'] = $Result['FileName'];
       $return[$count]['Date'] = $Result['Date'];
       $return[$count]['FName'] = $Result['EngName'];
       $boolean = true;
       $count++;
     }
      $return['Row'] = $count;
      $return['status'] = "success";
      $return['form'] = "showExcel";
      echo json_encode($return);
      mysqli_close($conn);
      die;

  }
      
  function deleteExcel($conn, $DATA){
    $ID = $DATA['ID'];
    $Sql = "UPDATE damagenh_file SET Status = 1 WHERE ID = $ID";
    mysqli_query($conn, $Sql);
    showExcel($conn, $DATA);
  }
  function UpdateDetail($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $Detail  =  $DATA["Detail"];
    $isStatus = $DATA["isStatus"];
    //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
    //	mysqli_query($conn,$Sqlx);
    $Sql = "UPDATE damagenh_detail
    SET Detail = '$Detail'
    WHERE damagenh_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    // ShowDetail($conn, $DATA);
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
    } elseif ($DATA['STATUS'] == 'get_claim_doc') {
      get_claim_doc($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateQty') {
      UpdateQty($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'showExcel') {
      showExcel($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'deleteExcel') {
      deleteExcel($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'getfactory') {
      getfactory($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'UpdateDetail') {
      UpdateDetail($conn, $DATA);
    }
    
  } else {
    $return['status'] = "error";
    $return['msg'] = 'noinput';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
