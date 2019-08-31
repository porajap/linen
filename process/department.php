<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function ShowItem($conn, $DATA)
{
  $count = 0;
  $xHptCode = $DATA['HptCode'];
  if($xHptCode==""){
    $xHptCode = 'BHQ';
  }
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT site.HptCode,
          CASE site.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus,
          department.DepCode,department.DepName,department.IsDefault,
		  CASE department.IsDefault WHEN 0 THEN '0' WHEN 1 THEN '1' END AS DefaultName
          FROM site
          INNER JOIN department ON site.HptCode = department.HptCode
          WHERE department.IsStatus = 0
          AND site.HptCode = '$xHptCode'
          AND ( department.DepCode LIKE '%$Keyword%' OR
          department.DepName LIKE '%$Keyword%')
          ORDER BY department.DepName ASC
          ";
          $return['sql'] = $Sql;
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
	$return[$count]['IsDefault'] = $Result['IsDefault'];
    $return[$count]['DefaultName'] = $Result['DefaultName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getdetail($conn, $DATA)
{
  $count = 0;
  $DepCode = $DATA['DepCode'];
  //---------------HERE------------------//
  $Sql = "SELECT
          department.DepCode,
          department.HptCode,
          department.DepName,
          department.IsStatus,
		  department.IsDefault
          FROM department
          WHERE department.IsStatus = 0
          AND department.DepCode = $DepCode LIMIT 1";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['DepCode'] 		= $Result['DepCode'];
    $return['HptCode'] 		= $Result['HptCode'];
    $return['DepName'] 		= $Result['DepName'];
    $return['IsStatus'] 	= $Result['IsStatus'];
	$return['IsDefault'] 	= $Result['IsDefault'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM
          site
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

function AddItem($conn, $DATA)
{
  $count = 0;
  $HptCode = $DATA['HptCode'];
  $DepName = $DATA['DepName'];
  $xCenter = $DATA['xCenter'];

  $Sql =  "SELECT COUNT(*) as Cnt FROM department
  WHERE department.HptCode =  '$HptCode' and department.IsStatus = 0   AND department.IsDefault = 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $count = $Result['Cnt'];
  }

  if($count > 0){
     $return['status'] = "failed";
    $return['msg'] = "editcenterfailedmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

  $Sql = "INSERT INTO department(
          HptCode,
          DepName,
          IsStatus,
		  IsDefault
		  )
          VALUES
          (
            '$HptCode',
            '$DepName',
            0,
            $xCenter
          )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    $return['msg'] = "addsuccess";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function EditItem($conn, $DATA)
{
  // var_dump($DATA); die;
  $count = 0;
  $HptCode = $DATA['HptCode'];
  $DepName = $DATA['DepName'];
  $xCenter = $DATA['xCenter'];

  $Sql =  "SELECT COUNT(*) as Cnt FROM department
  WHERE department.HptCode =  '$HptCode' and department.IsStatus = 0   AND department.IsDefault = 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $count = $Result[ 'Cnt'];
  }

  if($count > 0){
    $return['status'] = "failed";
    $return['msg'] = "editcenterfailedmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

  if($DATA["DepCode"]!=""){
    $Sql = "UPDATE department SET
            HptCode =  '$HptCode',
            DepName = ' $DepName',
            IsDefault =  $xCenter 
            WHERE DepCode = ".$DATA['DepCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "editfailed :  $xCenter";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["DepCode"]!=""){
    $Sql = "UPDATE department SET
            IsStatus = 1
            WHERE DepCode = ".$DATA['DepCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
