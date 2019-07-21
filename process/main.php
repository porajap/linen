<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn,$DATA){
  $HptCode = $_SESSION['HptCode'];
  $count = 0;
  $boolean = false;
  $Sql = "SELECT COUNT(*) AS Cnt
  FROM contract_parties_factory
  WHERE IsStatus = 0
  AND DATEDIFF(DATE(contract_parties_factory.EndDate),DATE(NOW())) < 31";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['CPF_Cnt'] = $Result['Cnt'];
	$boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM contract_parties_hospital
  WHERE IsStatus = 0
  AND DATEDIFF(DATE(contract_parties_hospital.EndDate),DATE(NOW())) < 31";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['HOS_Cnt'] = $Result['Cnt'];
	$boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM shelfcount
  INNER JOIN department ON department.DepCode = shelfcount.DepCode
  INNER JOIN site ON site.HptCode = department.HptCode
  WHERE site.HptCode = '$HptCode' AND IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['shelfcount_Cnt'] = $Result['Cnt'];
	$boolean = true;
  }
  $Sql = "SELECT COUNT(*) AS Cnt
  FROM factory_out
  INNER JOIN department ON department.DepCode = factory_out.DepCode
  INNER JOIN site ON site.HptCode = department.HptCode
  WHERE site.HptCode = '$HptCode' AND IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['fac_out_Cnt'] = $Result['Cnt'];
	$boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM clean
  INNER JOIN department ON department.DepCode = clean.DepCode
  INNER JOIN site ON site.HptCode = department.HptCode
  WHERE site.HptCode = '$HptCode' AND clean.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['clean_Cnt'] = $Result['Cnt'];
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

// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function SETLANG($conn, $DATA){
  $lang = $DATA['lang'];
  $_SESSION['lang'] = $lang;
}

function Active($conn,$DATA){
  $Userid = $DATA['Userid'];
  $Sql = "UPDATE users SET users.IsActive = 0 WHERE users.ID = '$Userid'";
  mysqli_query($conn,$Sql);
//  $Sql = "UPDATE users SET users.IsActive = 0 WHERE users.ID = '$Userid'";
//  mysqli_query($conn,$Sql);

  $boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "Active";
    $return['Sql'] = $Sql;
    echo json_encode($return);
    mysqli_close($conn);
    session_destroy();
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
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
      }else if($DATA['STATUS']=='SETLANG'){
        SETLANG($conn,$DATA);
      }else if($DATA['STATUS']=='Active'){
        Active($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
