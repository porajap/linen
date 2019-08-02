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
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = $Hotp
		  AND department.IsStatus = 0
      ORDER BY department.DepCode DESC";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
	$boolean = true;
  }

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

function getRow($conn,$DATA){
  $boolean = false;
  $count = 0;
  $RowID = $DATA["RowID"];

  $Sql = "SELECT
item.ItemName,
item_stock.ExpireDate,
site.HptCode,
site.HptName,
department.DepCode,
department.DepName,
item.ItemCode
FROM
item_stock
INNER JOIN item ON item.ItemCode = item_stock.ItemCode
INNER JOIN department ON item_stock.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
WHERE item_stock.IsStatus = 0
AND RowID = $RowID";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
$return[$count]['RowID'] 		= $Result['RowID'];
$return[$count]['HptCode'] 		= $Result['HptCode'];
$return[$count]['HptName'] 		= $Result['HptName'];
$return[$count]['DepCode'] 		= $Result['DepCode'];
$return[$count]['DepName'] 		= $Result['DepName'];
$return[$count]['ItemCode'] 		= $Result['ItemCode'];
$return[$count]['ItemName'] 		= $Result['ItemName'];
$return[$count]['ExpireDate'] 		= $Result['ExpireDate'];
$Hosp 							= $Result['HptCode'];
  $boolean = true;
  $count++;
}
$count = 0;
$Sql = "SELECT department.DepCode,department.DepName
FROM department
WHERE department.HptCode = $Hosp
AND department.IsStatus = 0";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Dep_ = 'Dep_'.$count;
  $return[$Dep_]['DepCode'] = $Result['DepCode'];
  $return[$Dep_]['DepName'] = $Result['DepName'];
  $count++;
}
$return['Dep_Cnt'] = $count;
if($boolean){
  $return['status'] = "success";
  $return['form'] = "getRow";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}else{
  $return[$count]['DocNo'] = "";
  $return[$count]['DocDate'] = "";
  $return[$count]['Qty'] = "";
  $return[$count]['Elc'] = "";
  $return['status'] = "failed";
  $return['form'] = "getRow";
$return['msg'] = "nodetail";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
}

function ShowDocument($conn,$DATA){
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);

  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];

  $sl1 = strlen($sDate);
  $sl2 = strlen($eDate);

//	 $Sql = "INSERT INTO log ( log ) VALUES ('$sl1  :  $sl2')";
//     mysqli_query($conn,$Sql);

  $Sql = "SELECT
item.ItemName,
item_stock.ExpireDate,
site.HptCode,
site.HptName,
department.DepCode,
department.DepName,
item.ItemCode
FROM
item_stock
INNER JOIN item ON item.ItemCode = item_stock.ItemCode
INNER JOIN department ON item_stock.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
WHERE item_stock.DepCode = $deptCode
AND item_stock.IsStatus = 0
AND (DATE(item_stock.ExpireDate) BETWEEN DATE(NOW()) AND
        DATE_SUB(DATE(NOW()),INTERVAL -('30') DAY))
ORDER BY item_stock.ExpireDate ASC;
";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
	$return[$count]['RowID'] 		= $Result['RowID'];
	$return[$count]['ItemCode'] 		= $Result['ItemCode'];
	$return[$count]['ItemName'] 		= $Result['ItemName'];
	$return[$count]['ExpireDate'] 		= $Result['ExpireDate'];

    $boolean = true;
    $count++;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "ShowDocument";
	$return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
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
      }elseif($DATA['STATUS']=='getRow'){
        getRow($conn,$DATA);
      }elseif($DATA['STATUS']=='SaveRow'){
        SaveRow($conn,$DATA);
      }elseif($DATA['STATUS']=='CancelRow'){
        CancelRow($conn,$DATA);
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
 ?>
