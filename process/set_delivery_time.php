<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}


function getDetail($conn, $DATA)
{
    $HptCode = $DATA['hptCode'];
    $FacCode = $DATA['FacCode'];
  $count=0;
  $Sql = "  SELECT ID,HptName,FacName,SendTime
            FROM
            delivery_fac_nhealth,factory,site
            WHERE delivery_fac_nhealth.HptCode=site.HptCode
            AND delivery_fac_nhealth.FacCode=factory.FacCode
            AND delivery_fac_nhealth.HptCode LIKE '%$HptCode%'
            AND delivery_fac_nhealth.FacCode LIKE '%$FacCode%'
          ";
  // var_dump($Sql); die;
  $return['ss'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['ID'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['SendTime'] = $Result['SendTime'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getItem($conn, $DATA)
{
    $id = $DATA['id'];
  $count=0;
  $Sql = "  SELECT HptCode,FacName,SendTime
            FROM
            delivery_fac_nhealth,factory
            WHERE delivery_fac_nhealth.FacCode=factory.FacCode
            AND delivery_fac_nhealth.ID LIKE '$id'
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['RowID'] = $id;
    $return['HptCode'] = $Result['HptCode'];
    $return['FacName'] = $Result['FacName'];
    $return['SendTime'] = $Result['SendTime'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function load_site_fac($conn, $DATA) {
    $HptCode = $DATA['hptCode'];
    $cnt_Fac = 0;

    $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE IsCancel = 0 
            AND FacCode NOT IN(SELECT FacCode FROM delivery_fac_nhealth WHERE delivery_fac_nhealth.HptCode = '$HptCode')";
    $meQuery = mysqli_query($conn, $Sql);
    $return['Faode'] = $Sql;
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$cnt_Fac]['FacCode'] = $Result['FacCode'];
        $return[$cnt_Fac]['FacName'] = $Result['FacName'];
        $cnt_Fac++;
    }

    $return['status'] = "success";
    $return['cnt_Fac'] = $cnt_Fac;
    $return['form'] = "load_site_fac";
    echo json_encode($return);
    mysqli_close($conn);
    die;
}

function getFactory($conn, $DATA)
{
  $count=0;
  $Sql = "SELECT
          FacCode,FacName
          FROM
          factory
          WHERE IsCancel = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacCode'] = $Result['FacCode'];
    $return[$count]['FacName'] = $Result['FacName'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getFactory";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getHotpital($conn, $DATA)
{
  $count=0;
  $Sql = "SELECT
          HptCode,HptName
          FROM
          site
          WHERE IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getHotpital";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function addTime($conn, $DATA)
{
  $HptCode = $DATA['hptCode'];
  $FacCode = $DATA['FacCode'];
  $time = $DATA['time'];

  $Sql = "INSERT INTO delivery_fac_nhealth (`HptCode`, `FacCode`, `SendTime`)
          VALUES ('$HptCode', $FacCode, $time)
          ";

  if (mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "addTime";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function editTime($conn, $DATA)
{
  $id = $DATA['id'];
  $time = $DATA['time'];

  $Sql = "UPDATE delivery_fac_nhealth 
          SET SendTime =  $time 
          WHERE ID = $id
          ";
  $return['sss'] = "$Sql";
  if (mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "editTime";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function deleteTime($conn, $DATA)
{
  $id = $DATA['id'];
  $Sql =  " DELETE FROM delivery_fac_nhealth WHERE ID = $id
          ";

  if (mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "deleteTime";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'getHotpital') {
    getHotpital($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getFactory') {
    getFactory($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getDetail') {
    getDetail($conn, $DATA);
  }else if ($DATA['STATUS'] == 'load_site_fac') {
    load_site_fac($conn, $DATA);
  }else if ($DATA['STATUS'] == 'getItem') {
    getItem($conn, $DATA);
  }else if ($DATA['STATUS'] == 'addTime') {
    addTime($conn, $DATA);
  }else if ($DATA['STATUS'] == 'editTime') {
    editTime($conn, $DATA);
  }else if ($DATA['STATUS'] == 'deleteTime') {
    deleteTime($conn, $DATA);
  }
  
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
