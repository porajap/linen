<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}


function getSection($conn, $DATA)
{
  $HptCode = $_SESSION['HptCode'];
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.DepName,
          tdas_percentvalue.percent_value
        FROM department
        LEFT JOIN tdas_percent ON tdas_percent.DepCode = department.DepCode
        LEFT JOIN tdas_percentvalue ON tdas_percentvalue.ID = tdas_percent.Percent_ID
        WHERE department.IsStatus = 0 AND department.HptCode ='$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']  = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $return[$count]['Hptpercent']  = $Result['percent_value']==null?10:$Result['percent_value'];
    $DepCode[$count]  = $Result['DepCode'];
    $count++;
  }
  $return['CountRow'] = $count;
  $limit = $count;
  $count = 0;
  $Sql = "SELECT item_main_category.MainCategoryName, item.ItemName, item.ItemCode  FROM item 
    INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
    INNER JOIN item_main_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
    LEFT JOIN tdas_change ON tdas_change.ItemCode = item.ItemCode
    WHERE Tdas = 1 ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['mainType']  = $Result['MainCategoryName'];
    $return[$count]['ItemName']  = $Result['ItemName'];
    $return[$count]['ItemCode']  = $Result['ItemCode'];
    $ItemCode[$count]  = $Result['ItemCode'];
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
    $Type1 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 1 AND DepCode = $DepCode[$i]";
    $TypeQuery1 = mysqli_query($conn, $Type1);
    while ($Result = mysqli_fetch_assoc($TypeQuery1)) {
      $return[$i]['ID1']  = $Result['ID'];
      $return[$i]['Qty1']  = $Result['Qty']==null?0:$Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type2 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 2 AND DepCode = $DepCode[$i]";
    $TypeQuery2 = mysqli_query($conn, $Type2);
    while ($Result = mysqli_fetch_assoc($TypeQuery2)) {
      $return[$i]['ID2']  = $Result['ID'];
      $return[$i]['Qty2']  = $Result['Qty']==null?0:$Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type3 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 3 AND DepCode = $DepCode[$i]";
    $TypeQuery3 = mysqli_query($conn, $Type3);
    while ($Result = mysqli_fetch_assoc($TypeQuery3)) {
      $return[$i]['ID3']  = $Result['ID'];
      $return[$i]['Qty3']  = $Result['Qty']==null?0:$Result['Qty'];
    }
  }
  for($i=0;$i<$limit;$i++){
    $Type4 = "SELECT ID, Qty FROM tdas_qty WHERE HptCode = '$HptCode' AND Type = 4 AND DepCode = $DepCode[$i]";
    $TypeQuery4 = mysqli_query($conn, $Type4);
    while ($Result = mysqli_fetch_assoc($TypeQuery4)) {
      $return[$i]['ID2']  = $Result['ID'];
      $return[$i]['Qty4']  = $Result['Qty']==null?0:$Result['Qty'];
    }
  }

  $count = 0;
  $Sql = "SELECT percent_value FROM tdas_percentValue";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['percent_value']  = $Result['percent_value'];
    $count++;
  }
  $return['CountPercent'] = $count;

  $Sql = "SELECT total_par1, total_par2 FROM tdas_total WHERE HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['total_par1']  = $Result['total_par1']==null?0:$Result['total_par1'];
    $return['total_par2']  = $Result['total_par2']==null?0:$Result['total_par2'];
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

  $SqlFind = "SELECT COUNT(*) AS cnt FROM tdas_qty WHERE HptCode = '$HptCode' AND DepCode = $DepCode AND Type = $Type";
  $meQuery = mysqli_query($conn, $SqlFind);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['cnt'] > 0){
      $Sql = "UPDATE tdas_qty SET Qty = $Qty WHERE HptCode = '$HptCode' AND DepCode = $DepCode AND Type = $Type";
    }else{
      $Sql = "INSERT INTO tdas_qty (HptCode, DepCode, Type, Qty)VALUES('$HptCode', $DepCode, $Type, $Qty)";
    }
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
  $Qty[3] = explode(',', $DATA['QtyArray3']);
  $Qty[4] = explode(',', $DATA['QtyArray4']);
  #-------------------------------------
  $ItemCodeArray = explode(',', $DATA['ItemCodeArray']);
  $changeArray = explode(',', $DATA['changeArray']);
  $PercentArray = explode(',', $DATA['PercentArray']);
  $Total_par2 = $DATA['Total_par2'];
  #-------------------------------------
  $SumType[0] = array_sum($QtyArray1);
  $SumType[1] = array_sum($QtyArray2);
  $SumType[2] = array_sum($QtyArray3);
  $SumType[3] = array_sum($QtyArray4);
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
              // mysqli_query($conn, $TDDocument);
  #---------------------------------------
  $count = 0;
  $Sql = "SELECT department.DepCode FROM department
  WHERE department.IsStatus = 0 AND department.HptCode ='$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCode[$count]  = $Result['DepCode'];
    $count++;
  }
  #----------------------------------------
  $loop1 = 4;
  $loop2 = $count;
  $loop3 = sizeof($ItemCodeArray, 0);
  #-------------------------------------
  for($i = 0; $i<$loop1; $i++){
    for($j = 0; $j<$loop2; $j++){
      $Sql1 = "INSERT INTO tdas_detail (DocNo, DepCode, Type, Qty, TotalStock, TotalPar, Percent) 
            VALUES ('$DocNo', $DepCode, $i+1, $Qty[$i][$j], $SumType[$i], $Total_par2, $PercentArray[$i])";
      mysqli_query($conn, $Sql1);
      $return[$j] = $Sql1;
    }
  }
  echo '<pre>';
  print_r($Qty[0]);
  echo '</pre>';
  // echo json_encode($return);
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
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
