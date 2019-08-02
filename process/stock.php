<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn,$DATA){
  $PmID = $_SESSION['PmID'];
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];

  if($PmID != 1 && $PmID != 2 && $PmID != 3){
    $Sql = "SELECT department.DepCode,department.DepName,department.IsDefault
    FROM department
    WHERE department.HptCode = '$Hotp' 
    AND  department.IsDefault = 1
    AND department.IsStatus = 0
    ORDER BY department.DepCode DESC";
    $return['sql'] = $Sql;
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['DepCode'] = $Result['DepCode'];
      $return[$count]['DepName'] = $Result['DepName'];
      $return[$count]['IsDefault'] = $Result['IsDefault'];
      $count++;
      $boolean = true;
    }
  }else{
    $Sql = "SELECT department.DepCode,department.DepName,department.IsDefault
    FROM department
    WHERE department.HptCode = '$Hotp' 
    AND department.IsStatus = 0
    ORDER BY department.DepCode DESC";
    $return['sql'] = $Sql;
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['DepCode'] = $Result['DepCode'];
      $return[$count]['DepName'] = $Result['DepName'];
      $return[$count]['IsDefault'] = $Result['IsDefault'];
      $count++;
      $boolean = true;
    }
  }
  $return['Row'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function ShowDocument($conn,$DATA){
  $boolean = false;
  $count = 0;
  $dept = $DATA["dept"];
  $hos = $DATA["hos"];
  $search = $DATA["search"];
  $selecta = $DATA["selecta"];

  $Sql = "SELECT
    item_stock.ItemCode,
    item.ItemName,
    department.DepCode,
    department.DepName,
    site.HptName,
    item_stock.ParQty,
    item_stock.TotalQty,
    item_category.CategoryName
  FROM item_stock
  INNER JOIN item ON item_stock.ItemCode = item.ItemCode
  INNER JOIN department ON item_stock.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode ";
  if ($selecta==0) {
    $Sql.="WHERE site.HptCode = '$hos' AND item_stock.DepCode =  $dept AND item.ItemName LIKE '%$search%' ";
  }elseif($selecta==2){
    $Sql.="WHERE site.HptCode = '$hos'";
  }
  $Sql.="GROUP BY item_stock.ItemCode , item_stock.DepCode ORDER BY department.DepCode,item_stock.ItemCode";

  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] 	= $Result['ItemCode'];
    $return[$count]['ItemName'] 	= $Result['ItemName'];
    $return[$count]['CategoryName'] 	= $Result['CategoryName'];
    $return[$count]['DepCode'] 	= $Result['DepCode'];
    $return[$count]['DepName'] 	= $Result['DepName'];
    $return[$count]['Qty'] 	= $Result['TotalQty'];
    $return[$count]['ParQty'] 	= $Result['ParQty'];

    $boolean = true;
    $count++;
  }
  $return['Row'] = $count;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "ShowDocument";
    $return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

//==========================================================
//
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }elseif ($DATA['STATUS']=='getDepartment') {
    getDepartment($conn, $DATA);
  }elseif($DATA['STATUS']=='ShowDocument'){
    ShowDocument($conn,$DATA);
  }

}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
