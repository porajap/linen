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
  // $selectSiteLow = $_POST["selectSiteLow"];
  $txtNameEn = $_POST["txtNameEn"];
  $txtNameTh = $_POST["txtNameTh"];
  $userid   = $_SESSION["Userid"];
  $return = array();

  if ($txtNumber == "") {
    $Sql = "INSERT INTO typelinen SET typelinen.name_En = '$txtNameEn', 
                                     typelinen.name_Th = '$txtNameTh', 
                                     typelinen.Create_Date = NOW(), 
                                     typelinen.Modify_Date = NOW(), 
                                     typelinen.Modify_Code = $userid  ";
  } else {
    $Sql = "UPDATE typelinen SET      typelinen.name_En = '$txtNameEn', 
                                     typelinen.name_Th = '$txtNameTh', 
                                     typelinen.Create_Date = NOW(), 
                                     typelinen.Modify_Date = NOW(), 
                                     typelinen.Modify_Code = $userid  WHERE typelinen.id = $txtNumber ";
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
            typelinen.id,
            typelinen.name_En,
            typelinen.name_Th
          FROM
          typelinen
          WHERE typelinen.id = '$id' ";

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
            typelinen.id,
            typelinen.name_En,
            typelinen.name_Th
          FROM
          typelinen
          WHERE  ( typelinen.name_En LIKE '%$txtSearch%' OR typelinen.name_Th LIKE '%$txtSearch%')";
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

  $Sql = "DELETE FROM typelinen WHERE typelinen.id = $id ";

  mysqli_query($conn, $Sql);
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
