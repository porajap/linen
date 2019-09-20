<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM site
					WHERE IsStatus = 0";
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
function ShowItem($conn, $DATA){
  $HptCode = $DATA['HptCode'];
  $Keyword = $DATA['Keyword'];
  $count = 0;
  if($HptCode==''){
    $Sql = "SELECT HptCode FROM site WHERE IsStatus = 0 LIMIT 1";
    $meQuery = mysqli_query($conn, $Sql);
    $Result = mysqli_fetch_assoc($meQuery);
    $HptCode = $Result['HptCode'];
  }
  $Select = "SELECT te.ID, te.HptCode, te.time_value , site.HptName
    FROM time_express te
    INNER JOIN site ON site.HptCode = te.HptCode WHERE te.HptCode = '$HptCode'";
    if($Keyword != ''){
      $Select .= "  AND (te.time_value LIKE  '%$Keyword%')";
    }
    $meQuery = mysqli_query($conn, $Select);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['ID']  = $Result['ID'];
      $return[$count]['HptName']  = $Result['HptName'];
      $return[0]['HptCode']  = $Result['HptCode'];
      $return[$count]['time_value']  = $Result['time_value'];
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
  $Sql = "INSERT INTO time_express (HptCode, time_value)VALUES('$HptCode', '$Time')";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function getDetail($conn, $DATA){
  $ID = $DATA['ID'];
  $Sql = "SELECT te.ID, te.HptCode, te.time_value , site.HptName
  FROM time_express te
  INNER JOIN site ON site.HptCode = te.HptCode WHERE te.ID = '$ID'";
  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $return['ID']  = $Result['ID'];
  $return['HptCode']  = $Result['HptCode'];
  $return['time_value']  = $Result['time_value'];
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
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
