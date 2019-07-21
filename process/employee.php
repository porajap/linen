<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  $xHptCode = $DATA['HptCode'];
  if($xHptCode==""){
    $xHptCode = 1;
  }
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT employee.EmpCode,IFNULL(employee.FirstName,' ') AS FirstName,IFNULL(employee.LastName,' ') AS LastName,department.DepCode,department.DepName,site.HptCode,site.HptName
          FROM employee
          INNER JOIN department ON employee.DepCode = department.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
          WHERE employee.IsStatus = 1
          AND site.HptCode = $xHptCode
          AND ( employee.EmpCode LIKE '%$Keyword%' OR
          employee.FirstName LIKE '%$Keyword%')";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
	  $return[$count]['EmpCode'] = $Result['EmpCode'];
    $return[$count]['FirstName'] = $Result['FirstName'];
    $return[$count]['LastName'] = $Result['LastName'];
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
  $EmpCode = $DATA['EmpCode'];
  //---------------HERE------------------//
  $Sql = "SELECT employee.EmpCode,IFNULL(employee.FirstName,' ') AS FirstName,IFNULL(employee.LastName,' ') AS LastName,department.DepCode,department.DepName,site.HptCode,site.HptName
          FROM employee
          INNER JOIN department ON employee.DepCode = department.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
          WHERE employee.EmpCode = $EmpCode AND employee.IsStatus = 1";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['DepCode'] 		= $Result['DepCode'];
    $return['HptCode'] 		= $Result['HptCode'];
    $return['EmpCode'] 		= $Result['EmpCode'];
    $return['FirstName'] 	= $Result['FirstName'];
	  $return['LastName'] 	= $Result['LastName'];
    $HptCode              = $Result['HptCode'];
    $count++;
  }

  $cnt = 0;
  $Sql = "SELECT site.HptCode,site.HptName
          FROM site
          WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['Hpt'.$cnt]['HptCode']  = $Result['HptCode'];
    $return['Hpt'.$cnt]['HptName']  = $Result['HptName'];
    $cnt++;
  }
  $return['HptCnt'] = $cnt;
  $cnt=0;
  $Sql = "SELECT department.DepCode,department.DepName
          FROM department
          WHERE department.IsStatus = 0
          AND department.HptCode = $HptCode";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['Dep'.$cnt]['DepCode']  = $Result['DepCode'];
    $return['Dep'.$cnt]['DepName']  = $Result['DepName'];
    $cnt++;
  }
  $return['DepCnt'] = $cnt;
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
  $HptCode = $DATA['HptCode'];
  $Sql = "SELECT department.DepCode,department.DepName
          FROM department
          WHERE department.IsStatus = 0
          AND department.HptCode = $HptCode";

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

function getHotpital($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT site.HptCode,site.HptName
          FROM site
          WHERE site.IsStatus = 0";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $count = 0;
  $HptSel   = $DATA['HptSel'];
  $DepSel   = $DATA['DepSel'];
  $EmpCode  = $DATA['EmpCode'];
  $EmpFName = $DATA['EmpFName'];
  $EmpLName = $DATA['EmpLName'];

  $Sql =  "SELECT COUNT(*) AS Cnt FROM employee WHERE employee.EmpCode = $EmpCode";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $count = $Result['Cnt'];
  }

  if($count == 0){
    $Sql = "INSERT INTO employee ( FirstName,LastName,DepCode ) VALUES ( '$EmpFName','$EmpLName',$DepSel )";
  }else{
    $Sql = "UPDATE employee SET FirstName='$EmpFName',LastName='$EmpLName',DepCode=$DepSel WHERE EmpCode = $EmpCode";
  }

  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    if($count==0){
        $return['msg'] = "addsuccess";
    }else{
        $return['msg'] = "editsuccess";
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function EditItem($conn, $DATA)
{
  // var_dump($DATA); die;
  $count = 0;
  $HptCode = $DATA['HptCode'];
  $DepName = $DATA['DepName'];
  $xCenter = $DATA['xCenter'];

  // $Sql =  "SELECT COUNT(*) as Cnt FROM department
  // WHERE department.HptCode =  $HptCode and department.IsDefault = 1";
  // $meQuery = mysqli_query($conn, $Sql);
  // while ($Result = mysqli_fetch_assoc($meQuery)) {
  //   $count = $Result[ 'Cnt'];
  // }

  // if($count > 0){
  //   $return['status'] = "failed";
  //   $return['msg'] = "editfailed";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }

  if($DATA["DepCode"]!=""){
    $Sql = "UPDATE department SET
            HptCode =  $HptCode,
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
  if($DATA["EmpCode"]!=""){
    $Sql = "UPDATE employee SET IsStatus = 0 WHERE EmpCode = ".$DATA['EmpCode'];
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
      }else if ($DATA['STATUS'] == 'getHotpital') {
        getHotpital($conn, $DATA);
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
