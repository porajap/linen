<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  $UnitCode = $DATA['UnitCode'];
  if($UnitCode==""){
    $UnitCode = 1;
  }
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT
          item_multiple_unit.RowID,
          item_multiple_unit.UnitCode,
          item_multiple_unit.MpCode,
          item_multiple_unit.Multiply,
          i1.UnitName AS UnitName,
          i2.UnitName AS MpName
          FROM
          item_multiple_unit
          INNER JOIN item_unit AS i1 ON item_multiple_unit.UnitCode = i1.UnitCode
          INNER JOIN item_unit AS i2 ON item_multiple_unit.MpCode = i2.UnitCode
          WHERE item_multiple_unit.UnitCode = $UnitCode
          AND (item_multiple_unit.UnitCode LIKE '%$Keyword%' OR
          item_multiple_unit.MpCode LIKE '%$Keyword%')";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['Row_Id'] = $Result['RowID'];
    $return[$count]['UnitCode'] = $Result['UnitName'];
    $return[$count]['MpCode'] = $Result['MpName'];
    $return[$count]['Multiply'] = $Result['Multiply'];
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
    $return['msg'] = "ไม่พบข้อมูล";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getdetail($conn, $DATA)
{
  $count = 0;
  $Row_Id = $DATA['Row_Id'];
  //---------------HERE------------------//
  $Sql = "SELECT
          item_multiple_unit.RowID,
          item_multiple_unit.MpCode,
          item_multiple_unit.UnitCode,
          item_multiple_unit.Multiply
          FROM
          item_multiple_unit
          WHERE item_multiple_unit.RowID = $Row_Id LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['Row_Id'] = $Result['RowID'];
    $return['MpCode'] = $Result['MpCode'];
    $return['UnitCode'] = $Result['UnitCode'];
    $return['Multiply'] = $Result['Multiply'];
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
    $return['msg'] = "ไม่พบข้อมูล";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          item_unit.IsStatus
          FROM
          item_unit
          WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
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
  $Sql = "INSERT INTO item_multiple_unit(
          MpCode,
          UnitCode,
          Multiply
        )
        VALUES
        (
          ".$DATA['MpCode'].",
          '".$DATA['UnitCode']."',
          ".$DATA['Multiply']."
        )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    $return['msg'] = "เพิ่มข้อมูลสำเร็จ";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "ไม่สามารถเพิ่มข้อมูลได้";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function EditItem($conn, $DATA)
{
  $count = 0;
  if($DATA["Row_Id"]!=""){
    $Sql = "UPDATE Item_multiple_unit SET
            MpCode = '".$DATA['MpCode']."',
            UnitCode = ".$DATA['UnitCode'].",
            Multiply = ".$DATA['Multiply']."
            WHERE Row_Id = ".$DATA['Row_Id']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "แก้ไขข้อมูลสำเร็จ";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "ไม่สามารถแก้ไขข้อมูลได้";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "ไม่สามารถแก้ไขข้อมูลได้";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["Row_Id"]!=""){
    $Sql = "DELETE FROM item_multiple_unit
            WHERE RowID = ".$DATA['Row_Id']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "ยกเลิกข้อมูลสำเร็จ";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "ไม่สามารถยกเลิกข้อมูลได้";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "ไม่สามารถยกเลิกข้อมูลได้";
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
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
