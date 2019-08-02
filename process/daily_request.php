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
  $Sql = "SELECT
  daily_request.DocNo,
  daily_request.RefDocNo,
  daily_request.Detail,
  daily_request.DocDate,
  TIME(daily_request.Modify_Date) AS xTime,
  department.DepName,
  site.HptName
  FROM
  daily_request
  INNER JOIN department ON daily_request.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  WHERE daily_request.DocDate = DATE(NOW())
  ORDER BY daily_request.Modify_Date DESC";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['RefDocNo'] = $Result['RefDocNo'];
    $return[$count]['Detail'] = $Result['Detail'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
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
//==========================================================
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }

}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
