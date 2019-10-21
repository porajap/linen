<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}

function getSection($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $count = 0;
  if($lang=='en'){
  $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM site
          WHERE IsStatus = 0";
  }else{
    $Sql = "SELECT
    site.HptCode,
    site.HptNameTH AS HptName
    FROM site
    WHERE IsStatus = 0";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
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
  $Sql = "SELECT ID, TimeName FROM time_sc WHERE ID  BETWEEN 1 AND 48  ORDER BY ID ASC";
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
  $Sql = "SELECT ts.ID, ts.TimeName FROM time_sc ts
  LEFT JOIN time_express te ON te.Time_ID = ts.ID
  WHERE ts.ID NOT IN(SELECT time_express.Time_ID  FROM time_express WHERE time_express.HptCode = '$HptCode') AND Ts.ID  BETWEEN 1 AND 48
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
  $Select = "SELECT te.ID, te.HptCode, time_sc.TimeName , site.HptName
    FROM time_express te
    INNER JOIN site ON site.HptCode = te.HptCode 
    INNER JOIN time_sc ON time_sc.ID = te.Time_ID  
    WHERE te.HptCode = '$HptCode' ORDER BY time_sc.ID";
    if($Keyword != ''){
      $Select .= "  AND (time_sc.TimeName LIKE  '%$Keyword%')";
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
  $HptCode = $DATA['HptCode'];
  $Time = $DATA['Time'];
  $Sql = "INSERT INTO time_express (Time_ID, HptCode)VALUES($Time, '$HptCode')";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function getDetail($conn, $DATA){
  $ID = $DATA['ID'];
  $Sql = "SELECT te.ID, te.HptCode , site.HptName
  FROM time_express te
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
  $ID = $DATA['TimeID'];
  $HptCode = $DATA['HptCode'];
  $Time = $DATA['Time'];
  $Sql = "UPDATE time_express SET time_value = '$Time' WHERE ID = $ID";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function CancelItem($conn, $DATA){
  $HptCode = $DATA['HptCode'];
  $TimeID = $DATA['TimeID'];
  $Sql = "DELETE FROM time_express WHERE ID = $TimeID";
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
