<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
    if ($_POST['FUNC_NAME'] == 'saveData') {
    saveData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDataColor') {
    showDataColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDetail') {
    showDetail($conn);
  } else  if ($_POST['FUNC_NAME'] == 'deleteData') {
    deleteData($conn);
  }else  if ($_POST['FUNC_NAME'] == 'get_color_master') {
    get_color_master($conn);
  }else  if ($_POST['FUNC_NAME'] == 'chk_color_master') {
    chk_color_master($conn);
  }else  if ($_POST['FUNC_NAME'] == 'save_add_color') {
    save_add_color($conn);
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

function showDataColor($conn)
{
  $color_master = $_POST["color_master"];

  $Sql = "SELECT
            color_detail.ID, 
            color_detail.ID_color_master, 
            color_detail.color_code_detail, 
            color_master.color_master_name
          FROM  color_detail
          INNER JOIN color_master  ON  color_detail.ID_color_master = color_master.ID
          WHERE color_detail.ID_color_master ='$color_master' ";

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

function get_color_master($conn)
{
    $Sql = "SELECT
              color_master.ID, 
              color_master.color_master_name
            FROM
              color_master
              ORDER BY color_master.ID ASC ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function chk_color_master($conn)
{
  $id_color_master = $_POST["id_color_master"];
    $Sql = "SELECT
              color_master.ID, 
              color_master.color_master_code
            FROM
              color_master
            WHERE
              color_master.ID = $id_color_master
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function save_add_color($conn)
{
  $color_master = $_POST["color_master"];
  $color_code = $_POST["color_code"];


  $Sql = "INSERT INTO color_detail SET color_detail.ID_color_master = '$color_master' , 
          color_detail.color_code_detail= '$color_code'
          ";

     mysqli_query($conn, $Sql);
   
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
