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

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.DepName
          FROM
          department
					WHERE IsStatus = 0 AND HptCode='BHQ'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']  = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}



if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
