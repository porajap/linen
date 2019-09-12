<?php
session_start();
require '../connect/connect.php';
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
    $return[$count]['Hptpercent']  = $Result['percent_value'];
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
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
