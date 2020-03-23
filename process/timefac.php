<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}

function getSection($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_SESSION['lang'];
  $count = 0;
  if($lang == 'en'){
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }else{
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }     
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $return[0]['PmID']  = $PmID;
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}
function getTime($conn, $DATA){
  $HptCode = $DATA['HptCode'];
  $count = 0;
  $Sql = "SELECT ID, TimeName FROM time_dirty WHERE ID  BETWEEN 1 AND 48  ORDER BY ID ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ID']  = $Result['ID'];
    $return[$count]['TimeName']  = $Result['TimeName'];
    $count++;
  }
  $return['Count'] = $count;
  $return['Sql'] = $Sql;
  $return['HptCode'] = $HptCode;
  $return['status'] = "success";
  $return['form'] = "getTime";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function getTime2($conn, $DATA){
  $HptCode = $DATA['HptCode'];
  $count = 0;
  $Sql = "SELECT ts.ID, ts.TimeName FROM time_dirty ts
  WHERE ts.ID NOT IN(SELECT round_time_dirty.Time_ID  FROM round_time_dirty WHERE round_time_dirty.HptCode = '$HptCode') AND ts.ID  BETWEEN 1 AND 48
  ORDER BY ts.ID ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ID']  = $Result['ID'];
    $return[$count]['TimeName']  = $Result['TimeName'];
    $count++;
  }
  $return['Count'] = $count;
  $return['status'] = "success";
  $return['form'] = "getTime2";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function ShowItem($conn, $DATA){
  $HptCode = $DATA['HptCode'];
  $Keyword = $DATA['Keyword'];
  $count = 0;
  $Select = "SELECT te.ID, te.HptCode, time_dirty.TimeName , site.HptName
    FROM round_time_dirty te
    INNER JOIN site ON site.HptCode = te.HptCode 
    INNER JOIN time_dirty ON time_dirty.ID = te.Time_ID  
    WHERE te.HptCode = '$HptCode' ORDER BY time_dirty.ID";
    if($Keyword != ''){
      $Select .= "  AND (time_dirty.TimeName LIKE  '%$Keyword%')";
    }
    $meQuery = mysqli_query($conn, $Select);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['ID']  = $Result['ID'];
      $return[$count]['HptName']  = $Result['HptName'];
      $return[0]['HptCode']  = $Result['HptCode'];
      $return[$count]['TimeName']  = $Result['TimeName'];
      $count++;
    }
    $return['Count'] = $count;
    $return['status'] = "success";
    $return['form'] = 'ShowItem';
    echo json_encode($return);
    mysqli_close($conn);
    die;
    
}
function AddItem($conn, $DATA){
  $Userid = $_SESSION['Userid'];
  $HptCode = $DATA['HptCode'];
  $Time = $DATA['Time'];
  $Sql = "INSERT INTO round_time_dirty (Time_ID, HptCode ,DocDate ,Modify_Code ,Modify_Date)VALUES($Time, '$HptCode',NOW(),$Userid,NOW() )";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function getDetail($conn, $DATA){
  $ID = $DATA['ID'];
  $Sql = "SELECT te.ID, te.HptCode , site.HptName
  FROM round_time_dirty te
  INNER JOIN site ON site.HptCode = te.HptCode WHERE te.ID = '$ID'";
  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $return['ID']  = $Result['ID'];
  $return['HptCode']  = $Result['HptCode'];
  $return['status'] = "success";
  $return['form'] = 'getDetail';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function EditItem($conn, $DATA){
  $Userid = $_SESSION['Userid'];
  $ID = $DATA['TimeID'];
  $HptCode = $DATA['HptCode'];
  $Time = $DATA['Time'];
  $Sql = "UPDATE round_time_dirty SET time_value = '$Time' , Modify_Date = NOW() , Modify_Code =  $Userid    WHERE ID = $ID";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function CancelItem($conn, $DATA){
  $HptCode = $DATA['HptCode'];
  $TimeID = $DATA['TimeID'];
  $Sql = "DELETE FROM round_time_dirty WHERE ID = $TimeID";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}


if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getDetail') {
        getDetail($conn, $DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getTime') {
        getTime($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getTime2') {
        getTime2($conn, $DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
