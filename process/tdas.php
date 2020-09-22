<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function GetSite($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $DATA["lang"];
  $count = 0;
  if($lang == 'en')
  {
    if($PmID != 1)
    {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }
    else
    {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }
  else
  {
    if($PmID != 1)
    {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }
    else
    {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }    
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "GetSite";
  $return[0]['PmID']  = $PmID;
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function getSection($conn, $DATA)
{
  $PmID = $_SESSION['PmID'];

  if($PmID ==1){
    $HptCode = $DATA['HptCode'];
  }else{
    $HptCode = $_SESSION['HptCode'];
  }
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.DepName,
          tdas_percent.Percent_value
        FROM department
        LEFT JOIN tdas_percent ON tdas_percent.DepCode = department.DepCode
        WHERE department.IsStatus = 0 AND department.HptCode ='$HptCode' ORDER BY department.DepCode";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']     = $Result['DepCode'];
    $return[$count]['DepName']    = $Result['DepName'];
    $return[$count]['Hptpercent']  = $Result['Percent_value'];
    $DepCode[$count]                   = $Result['DepCode'];
    $count++;
  }
  $return['CountRow'] = $count;
  $limit = $count;
  $count = 0;
  $Sql = "SELECT item_category.CategoryName, item.ItemName, item.ItemCode  FROM item 
    INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
    LEFT JOIN tdas_change ON tdas_change.ItemCode = item.ItemCode
    WHERE item.Tdas = 1 AND item.HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['mainType']      = $Result['CategoryName'];
    $return[$count]['ItemName']     = $Result['ItemName'];
    $return[$count]['ItemCode']      = $Result['ItemCode'];
    $ItemCode[$count]                    = $Result['ItemCode'];
    $count++;
  }
  $return['RowCount'] = $count;
  $loopChg = $count;
  for($i=0;$i<$count;$i++){
    $Sql = "SELECT change_value 
    FROM tdas_change 
    WHERE HptCode = '$HptCode' 
    AND ItemCode = '$ItemCode[$i]'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$i]['change_value']  = $Result['change_value'];
    }
  }
  
  for($i=0;$i<$limit;$i++){
    $Type1 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 1 AND DepCode = '$DepCode[$i]'";
    $TypeQuery1 = mysqli_query($conn, $Type1);
    while ($Result = mysqli_fetch_assoc($TypeQuery1)) {
      $return[$i]['ID1']  = $Result['ID'];
      $return[$i]['Qty1']  = $Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type2 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 2 AND DepCode = '$DepCode[$i]'";
    $TypeQuery2 = mysqli_query($conn, $Type2);
    while ($Result = mysqli_fetch_assoc($TypeQuery2)) {
      $return[$i]['ID2']  = $Result['ID'];
      $return[$i]['Qty2']  = $Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type3 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 3 AND DepCode = '$DepCode[$i]'";
    $TypeQuery3 = mysqli_query($conn, $Type3);
    while ($Result = mysqli_fetch_assoc($TypeQuery3)) {
      $return[$i]['ID3']  = $Result['ID'];
      $return[$i]['Qty3']  = $Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type4 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 4 AND DepCode = '$DepCode[$i]'";
    $TypeQuery4 = mysqli_query($conn, $Type4);
    while ($Result = mysqli_fetch_assoc($TypeQuery4)) {
      $return[$i]['ID2']  = $Result['ID'];
      $return[$i]['Qty4']  = $Result['Qty'];
    }
  }

  $Sql = "SELECT total_par1, total_par2 FROM tdas_total WHERE HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['total_par1']  = $Result['total_par1'];
    $return['total_par2']  = $Result['total_par2'];
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}
function SaveQty($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $DATA['DepCode'];
  $Type = $DATA['Type'];
  $Qty = $DATA['Qty'];

  $SqlFind = "SELECT COUNT(*) AS cnt FROM tdas_qty WHERE HptCode = '$HptCode' AND DepCode = '$DepCode' AND Type = $Type";
  $meQuery = mysqli_query($conn, $SqlFind);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['cnt'] > 0){
      $Sql = "UPDATE tdas_qty SET Qty = $Qty WHERE HptCode = '$HptCode' AND DepCode = '$DepCode' AND Type = $Type";
    }else{
      $Sql = "INSERT INTO tdas_qty (HptCode, DepCode, Type, Qty)VALUES('$HptCode', '$DepCode', $Type, $Qty)";
    }
    $return['sql'] = $Sql;
    mysqli_query($conn, $Sql);
  }
}
function SaveChange($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $ItemCode = $DATA['ItemCode'];
  $Qty = $DATA['Qty'];

  $SqlFind = "SELECT COUNT(*) AS cnt FROM tdas_change WHERE HptCode = '$HptCode' AND ItemCode = '$ItemCode'";
  $meQuery = mysqli_query($conn, $SqlFind);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['cnt'] > 0){
      $Sql = "UPDATE tdas_change SET change_value = $Qty WHERE HptCode = '$HptCode' AND ItemCode = '$ItemCode'";
    }else{
      $Sql = "INSERT INTO tdas_change (HptCode, ItemCode, change_value)VALUES('$HptCode', '$ItemCode', $Qty)";
    }
    mysqli_query($conn, $Sql);
  }
}
function SavePar($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $ItemCode = $DATA['ItemCode'];
  $Qty = $DATA['Qty'];

  $SqlFind = "SELECT COUNT(*) AS cnt FROM tdas_total WHERE HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $SqlFind);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['cnt'] > 0){
      $Sql = "UPDATE tdas_total SET total_par2 = $Qty WHERE HptCode = '$HptCode'";
    }else{
      $Sql = "INSERT INTO tdas_total (HptCode, total_par2)VALUES('$HptCode', $Qty)";
    }
    mysqli_query($conn, $Sql);
  }
}
function SavePercent($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $DATA['DepCode'];
  $Percent = $DATA['Percent'];

  $SqlFind = "SELECT COUNT(*) AS cnt FROM tdas_percent WHERE HptCode = '$HptCode' AND DepCode = '$DepCode'";
  $meQuery = mysqli_query($conn, $SqlFind);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['cnt'] > 0){
      $Sql = "UPDATE tdas_percent SET Percent_value = $Percent WHERE HptCode = '$HptCode' AND DepCode = '$DepCode'";
    }else{
      $Sql = "INSERT INTO tdas_percent (HptCode, DepCode, Percent_value)VALUES('$HptCode', '$DepCode', $Percent)";
    }
    mysqli_query($conn, $Sql);
  }
}
function CreateDocument($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $UserID = $_SESSION['Userid'];
  #-------------------------------------
  $QtyArray1 = explode(',', $DATA['QtyArray1']);
  $QtyArray2 = explode(',', $DATA['QtyArray2']);
  $QtyArray3 = explode(',', $DATA['QtyArray3']);
  $QtyArray4 = explode(',', $DATA['QtyArray4']);

  $Qty[0] = explode(',', $DATA['QtyArray1']);
  $Qty[1] = explode(',', $DATA['QtyArray2']);
  $Qty[2] = explode(',', $DATA['QtyArray3']);
  $Qty[3] = explode(',', $DATA['QtyArray4']);
  #-------------------------------------
  $ItemCodeArray = explode(',', $DATA['ItemCodeArray']);
  $AllSum = explode(',', $DATA['AllSum']);
  $changeArray = explode(',', $DATA['changeArray']);
  $PercentArray = explode(',', $DATA['PercentArray']);
  $Total_par2 = $DATA['Total_par2'];
  #-------------------------------------
  $SumType[0] = array_sum($QtyArray1);
  $SumType[1] = array_sum($QtyArray2);
  $SumType[2] = array_sum($QtyArray3);
  $SumType[3] = array_sum($QtyArray4);
  #-------------------------------------
  $TotalArray = explode(',', $DATA['TotalArray']);
  $CalArray = explode(',', $DATA['CalArray']);
  $resultStock = $DATA['resultStock'];
  // echo '<pre>';
  // print_r($TotalArray);
  // echo '</pre>';
  // echo '<pre>';
  // print_r($Qty);
  // echo '</pre>';
  // exit();
  #-------------------------------------
  $Sql = "SELECT CONCAT('TD',lpad('$HptCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM tdas_document
  INNER JOIN site on site.HptCode = tdas_document.HptCode
  WHERE DocNo Like CONCAT('TD',lpad('$HptCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  AND site.HptCode = '$HptCode'
  ORDER BY DocNo DESC LIMIT 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $Result['DocNo'];
    $RecNow = $Result['RecNow'];
    $DocDate = $Result['DocDate'];
  }
  $TDDocument = "INSERT INTO tdas_document (DocNo, DocDate, HptCode, Modify_Code, Modify_Date, IsStatus, IsCancel)
              VALUES('$DocNo', '$DocDate', '$HptCode', $UserID, '$RecNow', 0, 0)";
              mysqli_query($conn, $TDDocument);
  #---------------------------------------
  $count = 0;
  $Sql = "SELECT department.DepCode FROM department
  WHERE department.IsStatus = 0 AND department.HptCode ='$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCodeX[$count]  = $Result['DepCode'];
    $return[$count]['Dep'] =  $Result['DepCode'];
    $count++;
  }
  #----------------------------------------
  $TypeLoop = 4;
  $DepLoop = $count;
  $ItemLoop = sizeof($ItemCodeArray, 0);
  #-------------------------------------
  // for($d = 0; $d<$DepLoop; $d++){
  //   for($t = 0; $t<$TypeLoop; $t++){
  //     foreach($Qty[$d] AS $key => $value){
  //       if($d==$t){
  //         $SumCol[$d] += $Qty[$key][$t];
  //       }
  //     }
  //   }
  // }
  // for($i=0;$i<$ItemLoop;$i++){
  //   for($d = 0; $d<$DepLoop; $d++){
  //     $SumRow[$i] += (($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$i]) + $SumCol[$d];
  //   }
  // }

  for($t = 0; $t<$TypeLoop; $t++){
    for($d = 0; $d<$DepLoop; $d++){
      foreach($Qty[$t] AS $key => $value){
        if($key==$t){
          $SumCol[$d] += $Qty[$key][$d];
        }
      }
    }
  }
  #-------------------------------------
  for($t = 0; $t<$TypeLoop; $t++){
    for($d = 0; $d<$DepLoop; $d++){
      $Sql1 = "INSERT INTO tdas_detail (DocNo, DepCode, Type, Qty, TotalStock, TotalPar, Percent) 
            VALUES ('$DocNo', '$DepCodeX[$d]', $t+1, '".$Qty[$t][$d]."', $SumType[$t], $Total_par2, $PercentArray[$d])";
            mysqli_query($conn, $Sql1);
    }
  }
  #-------------------------------------
  for($i=0;$i<$ItemLoop;$i++){
    for($d = 0; $d<$DepLoop; $d++){
      $result = $resultStock[$i][$d]==''?0:$resultStock[$i][$d];
      $change = $changeArray[$i]==null?0:$changeArray[$i];
      $Sql2 = "INSERT INTO tdas_detail_item (DocNo, DepCode, Change_value, ItemCode , Result, SumResult, CalSum, AllSum)VALUES
      ('$DocNo', '$DepCodeX[$d]', $change, '$ItemCodeArray[$i]', $result, $TotalArray[$i], $CalArray[$i], $AllSum[$i])";
      // if($AllSum[$i]==0){
      //   $Sql2 = "INSERT INTO tdas_detail_item (DocNo, DepCode, Change_value, ItemCode , Result, SumResult, CalSum, AllSum)VALUES
      //   ('$DocNo', $DepCodeX[$d], $changeArray[$i], '$ItemCodeArray[$i]', ((($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$i]) + $SumCol[$d])-'".$Qty[1][$d]."', $TotalArray[$i], $CalArray[$i], $AllSum[$i])";
      // }else{
      //   $Sql2 = "INSERT INTO tdas_detail_item (DocNo, DepCode, Change_value, ItemCode , Result, SumResult, CalSum, AllSum)VALUES
      //   ('$DocNo', $DepCodeX[$d], $changeArray[$i], '$ItemCodeArray[$i]', (($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$i]) + $SumCol[$d], $TotalArray[$i], $CalArray[$i], $AllSum[$i])";
      // }
      mysqli_query($conn, $Sql2);
    }
  }
  // echo '<pre>'; 
  // print_r($change);
  // echo '</pre>';
  
  $return['status'] = "success";
  $return['form'] = "CreateDocument";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function updateStock($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $UserID = $_SESSION['Userid'];
  #-------------------------------------
  $QtyArray1 = explode(',', $DATA['QtyArray1']);
  $QtyArray2 = explode(',', $DATA['QtyArray2']);
  $QtyArray3 = explode(',', $DATA['QtyArray3']);
  $QtyArray4 = explode(',', $DATA['QtyArray4']);

  $Qty[0] = explode(',', $DATA['QtyArray1']);
  $Qty[1] = explode(',', $DATA['QtyArray2']);
  $Qty[2] = explode(',', $DATA['QtyArray3']);
  $Qty[3] = explode(',', $DATA['QtyArray4']);
  #-------------------------------------
  $ItemCodeArray = explode(',', $DATA['ItemCodeArray']);
  $AllSum = explode(',', $DATA['AllSum']);
  $changeArray = explode(',', $DATA['changeArray']);
  $PercentArray = explode(',', $DATA['PercentArray']);
  $Total_par2 = $DATA['Total_par2'];
  #-------------------------------------
  $SumType[0] = array_sum($QtyArray1);
  $SumType[1] = array_sum($QtyArray2);
  $SumType[2] = array_sum($QtyArray3);
  $SumType[3] = array_sum($QtyArray4);
  #-------------------------------------
  $TotalArray = explode(',', $DATA['TotalArray']);
  $CalArray = explode(',', $DATA['CalArray']);
  $resultStock = $DATA['resultStock'];
  // echo '<pre>';
  // print_r($resultStock);
  // echo '</pre>';
  // exit();
  #-------------------------------------
  $count = 0;
  $Sql = "SELECT department.DepCode FROM department
  WHERE department.IsStatus = 0 AND department.HptCode ='$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCodeX[$count]  = $Result['DepCode'];
    $return[$count]['Dep'] =  $Result['DepCode'];
    $count++;
  }
  #-------------------------------------
  $TypeLoop = 4;
  $DepLoop = $count;
  $ItemLoop = sizeof($ItemCodeArray, 0);
  #-------------------------------------
  // for($d = 0; $d<$DepLoop; $d++){
  //   for($t = 0; $t<$TypeLoop; $t++){
  //     foreach($Qty[$d] AS $key => $value){
  //       if($d==$t){
  //         $SumCol[$d] += $Qty[$key][$t];
  //       }
  //     }
  //   }
  // }
  for($t = 0; $t<$TypeLoop; $t++){
    for($d = 0; $d<$DepLoop; $d++){
      foreach($Qty[$t] AS $key => $value){
        if($key==$t){
          $SumCol[$d] += $Qty[$key][$d];
        }
      }
    }
  }

  for($i=0;$i<$ItemLoop;$i++){
    for($d = 0; $d<$DepLoop; $d++){
      // if($AllSum[$i]==0){
      //   $result = round(((($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$i]) + $SumCol[$d])-$Qty[1][$d]);
      // }else{
      //   $result = round((($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$i]) + $SumCol[$d]);
      // }
      $Sql = "SELECT COUNT(*) AS cnt, ParQty FROM par_item_stock WHERE ItemCode = '$ItemCodeArray[$i]' AND DepCode = '$DepCodeX[$d]' LIMIT 1";
      $meQuery = mysqli_query($conn, $Sql);
      $Result = mysqli_fetch_assoc($meQuery);
      $cnt = $Result['cnt'];
      $ParQty = $Result['ParQty'];
      if($cnt==0){
        if($resultStock[$i][$d]!=0||$resultStock[$i][$d]!=''){
          // for($m = 0; $m<$resultStock[$i][$d]; $m++){
          //   $Insert = "INSERT INTO item_stock (ItemCode, ExpireDate, DepCode, ParQty, TotalQty, UsageCode, IsStatus)
          //               VALUES('$ItemCodeArray[$i]', NOW(), $DepCodeX[$d], '".$resultStock[$i][$d]."', 0, 0, 9)";
          //   mysqli_query($conn, $Insert);
          // }
          $Insert = "INSERT INTO par_item_stock (ItemCode, ExpireDate, DepCode, ParQty, TotalQty, UsageCode, IsStatus)
                        VALUES('$ItemCodeArray[$i]', NOW(), '$DepCodeX[$d]', '".$resultStock[$i][$d]."', 0, 0, 9)";
            mysqli_query($conn, $Insert);
        }
        
      }else{
        if($ParQty < $result){
          $Update = "UPDATE par_item_stock SET ParQty = $result WHERE ItemCode = '$ItemCodeArray[$i]' AND DepCode = '$DepCodeX[$d]'";
          mysqli_query($conn, $Update);
        }
      }
    }
  }

}
function ShowDocument($conn, $DATA){
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $Keyword = $DATA['Keyword'];
  $count = 0;
  $Sql = "SELECT td.DocNo, td.DocDate, users.EngName
  FROM tdas_document td INNER JOIN users ON users.ID = td.Modify_Code
  WHERE td.HptCode = '$HptCode' AND td.IsCancel = 0 AND (td.DocNo LIKE '%$Keyword%') ORDER BY td.DocDate, td.DocNo";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo']  = $Result['DocNo'];
    $return[$count]['DocDate']  = $Result['DocDate'];
    $return[$count]['EngName']  = $Result['EngName'];
    $count ++;
  }
    $return['CountRow'] = $count;
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
}
function CancelDocNo($conn, $DATA){
  $DocNo = $DATA['DocNo'];
  $Sql = "UPDATE tdas_document SET IsCancel = 1 WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  mysqli_close($conn);
  die;
}
function SelectDocument($conn, $DATA){
  $DocNo = $DATA['DocNo'];
  $count = 0;
  $SqlHpt = "SELECT HptCode FROM tdas_document WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $SqlHpt);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $HptCode = $Result['HptCode'];
  }
  #-----------------------------------------------------------------
  $SqlDepCode = "SELECT tdas_detail.DepCode, department.DepName, tdas_detail.Percent
  FROM tdas_detail 
  INNER JOIN department ON department.DepCode = tdas_detail.DepCode
  WHERE tdas_detail.DocNo = '$DocNo' 
  GROUP BY tdas_detail.DepCode ORDER BY tdas_detail.DepCode ASC";
  $meQuery = mysqli_query($conn, $SqlDepCode);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']  = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $return[$count]['Hptpercent']  = $Result['Percent'];
    $DepCode[$count] = $Result['DepCode'];
    $count ++;
  }
  $return['CountRow'] = $count;
  $limit = $count;

  $Sql = "SELECT total_par1, total_par2 FROM tdas_total WHERE HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['total_par1'] = $Result['total_par1'];
    $return['total_par2'] = $Result['total_par2'];
  }
  #-----------------------------------------------------------------
  for($i=0;$i<$limit;$i++){
    $Type1 = "SELECT Qty FROM tdas_detail WHERE DocNo = '$DocNo' AND Type = 1 AND DepCode = '$DepCode[$i]'";
    $TypeQuery1 = mysqli_query($conn, $Type1);
    while ($Result = mysqli_fetch_assoc($TypeQuery1)) {
      $return[$i]['Qty1']  = $Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type2 = "SELECT Qty FROM tdas_detail WHERE DocNo = '$DocNo' AND Type = 2 AND DepCode = '$DepCode[$i]'";
    $TypeQuery2 = mysqli_query($conn, $Type2);
    while ($Result = mysqli_fetch_assoc($TypeQuery2)) {
      $return[$i]['Qty2']  = $Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type3 = "SELECT Qty FROM tdas_detail WHERE DocNo = '$DocNo' AND Type = 3 AND DepCode = '$DepCode[$i]'";
    $TypeQuery3 = mysqli_query($conn, $Type3);
    while ($Result = mysqli_fetch_assoc($TypeQuery3)) {
      $return[$i]['Qty3']  = $Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type4 = "SELECT Qty FROM tdas_detail WHERE DocNo = '$DocNo' AND Type = 4 AND DepCode = '$DepCode[$i]'";
    $TypeQuery4 = mysqli_query($conn, $Type4);
    while ($Result = mysqli_fetch_assoc($TypeQuery4)) {
      $return[$i]['Qty4']  = $Result['Qty'];
    }
  }
  #-----------------------------------------------------------------
  $count = 0;
  $Sql = "SELECT
      item_category.CategoryName,
      item.ItemName,
      item.ItemCode,
      tdas_detail_item.Change_value
    FROM
      tdas_detail_item
    INNER JOIN item ON item.ItemCode = tdas_detail_item.ItemCode
    INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
    LEFT JOIN tdas_change ON tdas_change.ItemCode = item.ItemCode
    WHERE tdas_detail_item.DocNo = '$DocNo'
    AND item.Tdas = 1
    AND item.HptCode = '$HptCode'
    GROUP BY tdas_detail_item.ItemCode ORDER BY tdas_detail_item.ItemCode DESC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['mainType']  = $Result['CategoryName'];
    $return[$count]['ItemName']  = $Result['ItemName'];
    $return[$count]['ItemCode']  = $Result['ItemCode'];
    $return[$count]['change_value']  = $Result['Change_value'];
    $ItemCode[$count]  = $Result['ItemCode'];
    $count++;
  }
  $return['RowCount'] = $count;
  #-----------------------------------------------------------------
  $return['DocNo'] = $DATA['DocNo'];
  $return['status'] = "success";
  $return['form'] = "SelectDocument";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function UpdateQty($conn, $DATA){
  $DepCode = $DATA['DepCode'];
  $DocNo = $DATA['DocNo'];
  $Type = $DATA['Type'];
  $Qty = $DATA['Qty'];

  $Update = "UPDATE tdas_detail SET Qty = $Qty WHERE DocNo = '$DocNo' AND DepCode = '$DepCode' AND Type = $Type";
  mysqli_query($conn, $Update);
}
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if($DATA['STATUS'] == 'SaveQty'){
        SaveQty($conn, $DATA);
      }else if($DATA['STATUS'] == 'SaveChange'){
        SaveChange($conn, $DATA);
      }else if($DATA['STATUS'] == 'SavePar'){
        SavePar($conn, $DATA);
      }else if($DATA['STATUS'] == 'CreateDocument'){
        CreateDocument($conn, $DATA);
      }else if($DATA['STATUS'] == 'SavePercent'){
        SavePercent($conn, $DATA);
      }else if($DATA['STATUS'] == 'updateStock'){
        updateStock($conn, $DATA);
      }else if($DATA['STATUS'] == 'ShowDocument'){
        ShowDocument($conn, $DATA);
      }else if($DATA['STATUS'] == 'CancelDocNo'){
        CancelDocNo($conn, $DATA);
      }else if($DATA['STATUS'] == 'SelectDocument'){
        SelectDocument($conn, $DATA);
      }else if($DATA['STATUS'] == 'UpdateQty'){
        UpdateQty($conn, $DATA);
      }else if($DATA['STATUS'] == 'GetSite'){
        GetSite($conn, $DATA);
      }
      

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
