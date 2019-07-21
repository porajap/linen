<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn,$DATA){
  $hptcode = $DATA['hptcode'];

  $count = 0;
  $boolean = false;
  $Sql = "SELECT
  shelfcount.DocNo,
  shelfcount.DocDate,
  department.DepName,
  site.HptName,
  shelfcount.IsStatus,
  shelfcount.RefDocNo,
  shelfcount.Detail
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  WHERE  site.HptCode = '$hptcode' AND shelfcount.IsStatus = 0
  ORDER BY shelfcount.DocNo DESC";
  $return['sql'] =$Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['RefDocNo'] = $Result['RefDocNo'];
    $return[$count]['Detail'] = $Result['Detail'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;
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

function getnotification($conn,$DATA){
  //  var_dump($DATA);
  $count = 0;
  $boolean = false;
  $Sql = "SELECT COUNT(*) AS Cnt
  FROM shelfcount
  WHERE IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['shelfcount_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM factory_out
  WHERE IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['fac_out_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM daily_request WHERE DATE(daily_request.DocDate) = DATE(NOW())";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['daily_request_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getnotification";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getnotification";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function alert_SetPrice($conn,$DATA)
{
  $PmID = $DATA['PmID'];
  $HptCode = $DATA['HptCode'];
  $Userid = $DATA['Userid'];
  $boolean = false;
  $count = 0;
  if($PmID == 1){
    $Sql = "SELECT 
      cat_P.DocNo,
      site.HptName,
      CURDATE() AS CurDate,
      c.StartDate,
      c.EndDate,
      DATEDIFF(c.EndDate, CURDATE()) AS dateDiff
    FROM contract_parties_hospital c
    INNER JOIN users ON users.ID = $Userid
    INNER JOIN site ON site.HptCode = c.HptCode
    INNER JOIN category_price_time cat_P ON cat_P.HptCode = c.HptCode
    WHERE cat_P.Status = 0 AND c.IsStatus=0
    GROUP BY  c.StartDate,c.EndDate, cat_P.DocNo ORDER BY dateDiff ASC";
  }else{
      $Sql = "SELECT 
      cat_P.DocNo,
      site.HptName,
      CURDATE() AS CurDate,
      c.StartDate,
      c.EndDate,
      DATEDIFF(c.EndDate, CURDATE()) AS dateDiff
    FROM contract_parties_hospital c
    INNER JOIN users ON users.ID = $Userid
    INNER JOIN site ON site.HptCode = '$HptCode'
    INNER JOIN category_price_time cat_P ON cat_P.HptCode = c.HptCode
    WHERE c.HptCode = '$HptCode' AND cat_P.Status = 0 AND c.IsStatus=0
    GROUP BY c.StartDate,c.EndDate, cat_P.DocNo ORDER BY dateDiff ASC";
  }
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);

  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['StartDate'] = $Result['StartDate'];
      $return[$count]['EndDate'] = $Result['EndDate'];
      $return[$count]['DateDiff'] = $Result['dateDiff'];
      $count++;
      $boolean = true; 
    }
    
  }

  $return['countRow'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "alert_SetPrice";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "alert_SetPrice";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}
//==========================================================
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }elseif ($DATA['STATUS']=='getnotification') {
    getnotification($conn,$DATA);
  }elseif ($DATA['STATUS']=='alert_SetPrice') {
    alert_SetPrice($conn,$DATA);
  }
}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
