<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'saveData') {
    saveData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showData') {
    showData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDetail') {
    showDetail($conn);
  } else  if ($_POST['FUNC_NAME'] == 'deleteData') {
    deleteData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkTypelinen') {
    checkTypelinen($conn);
  }
}


function saveData($conn)
{
  $txtNumber = $_POST["txtNumber"];
  $txtName = $_POST["txtName"];
  $userid   = $_SESSION["Userid"];
  $return = array();

  if ($txtNumber == "") {
    $Sql = "INSERT INTO fabric SET    fabric.name_Fabric = '$txtName', 
                                      fabric.Create_Date = NOW(), 
                                      fabric.Modify_Date = NOW(), 
                                      fabric.Modify_Code = $userid  ";
  } else {
    $Sql = "UPDATE fabric SET        fabric.name_Fabric = '$txtName', 
                                     fabric.Create_Date = NOW(), 
                                     fabric.Modify_Date = NOW(), 
                                     fabric.Modify_Code = $userid  WHERE fabric.id = $txtNumber ";
  }


  mysqli_query($conn, $Sql);
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showDetail($conn)
{
  $id = $_POST["id"];

  $Sql = "SELECT
            fabric.id,
            fabric.name_Fabric
          FROM
          fabric
          WHERE fabric.id = '$id' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showData($conn)
{
  // $selectSite = $_POST["selectSite"];
  $txtSearch = $_POST["txtSearch"];

  $Sql = "SELECT
            fabric.id,
            fabric.name_Fabric
          FROM
          fabric
          WHERE  fabric.name_Fabric LIKE '%$txtSearch%' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function deleteData($conn)
{
  $id = $_POST["txtNumber"];
  $return = array();

  $Sql = "DELETE FROM fabric WHERE fabric.id = $id ";

  mysqli_query($conn, $Sql);
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function checkTypelinen($conn)
{
  $txtName   = $_POST["txtName"];
  $txtNumber = $_POST["txtNumber"];

  if ($txtNumber == "") {
    $Sql = "SELECT
    COUNT( fabric.id ) AS count_name
  FROM
    fabric 
  WHERE
    name_Fabric = '$txtName' ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $count_name = $Result['count_name'];
    }
  } else {
    $count_name = 0;
  }


  if ($count_name > 0) {
    echo "repeat";
  } else {
    echo "no repeat";
  }

  mysqli_close($conn);
  die;
}
