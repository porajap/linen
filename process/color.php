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
  }
}


function saveData($conn)
{
  $txtNumber = $_POST["txtNumber"];
  $selectSiteLow = $_POST["selectSiteLow"];
  $txtNameEn = $_POST["txtNameEn"];
  $txtNameTh = $_POST["txtNameTh"];
  $txtAddress = $_POST["txtAddress"];
  $txtPhoneNumber = $_POST["txtPhoneNumber"];
  $userid   = $_SESSION["Userid"];
  $return = array();

  if ($txtNumber == "") {
    $Sql = "INSERT INTO supplier SET supplier.site = '$selectSiteLow' , 
                                     supplier.name_En = '$txtNameEn', 
                                     supplier.name_Th = '$txtNameTh', 
                                     supplier.address = '$txtAddress', 
                                     supplier.phoneNumber = '$txtPhoneNumber', 
                                     supplier.Create_Date = NOW(), 
                                     supplier.Modify_Date = NOW(), 
                                     supplier.Modify_Code = $userid  ";
  } else {
    $Sql = "UPDATE supplier SET      supplier.site = '$selectSiteLow' , 
                                     supplier.name_En = '$txtNameEn', 
                                     supplier.name_Th = '$txtNameTh', 
                                     supplier.address = '$txtAddress', 
                                     supplier.phoneNumber = '$txtPhoneNumber', 
                                     supplier.Create_Date = NOW(), 
                                     supplier.Modify_Date = NOW(), 
                                     supplier.Modify_Code = $userid  WHERE supplier.id = $txtNumber ";
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
            supplier.id,
            supplier.site,
            supplier.name_En,
            supplier.name_Th,
            supplier.address,
            supplier.phoneNumber 
          FROM
            supplier
          WHERE supplier.id = '$id' ";

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
  $txtSearch = $_POST["txtSearch"];

  $Sql = "SELECT
            color.id,
            color.colorName,
            color.colorCode 
          FROM
            color
          WHERE  color.colorName LIKE '%$txtSearch%' ";

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

  $Sql = "DELETE FROM color WHERE color.id = $id ";

  mysqli_query($conn, $Sql);
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
