<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'insertColor') {
    insertColor($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showColor') {
    showColor($conn);
  }else  if ($_POST['FUNC_NAME'] == 'deleteColor') {
    deleteColor($conn);
  }else  if ($_POST['FUNC_NAME'] == 'GetDep') {
    GetDep($conn);
  }else  if ($_POST['FUNC_NAME'] == 'insertSupplier') {
    insertSupplier($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showSupplier') {
    showSupplier($conn);
  }else  if ($_POST['FUNC_NAME'] == 'deleteSupplier') {
    deleteSupplier($conn);
  }else  if ($_POST['FUNC_NAME'] == 'updateSupplier') {
    updateSupplier($conn);
  }else  if ($_POST['FUNC_NAME'] == 'updateColor') {
    updateColor($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showImagesCatalog') {
    showImagesCatalog($conn);
  }else  if ($_POST['FUNC_NAME'] == 'insertDiscription') {
    insertDiscription($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showDiscription') {
    showDiscription($conn);
  }
}

function showImagesCatalog($conn)
{

  $itemCode = $_POST["ItemCode"];
  $count = 0;


  $Sql = "SELECT
            item.imageOne, 
            item.imageTwo, 
            item.imageThree
          FROM
            item 
          WHERE item.ItemCode = '$itemCode' ";

  
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function GetDep($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $site = $_POST["site"];
  $count = 0;

  if($lang =='en'){
    $text = "supplier.name_En AS name";
  }else{
    $text = "supplier.name_Th AS name";
  }


  $Sql = "SELECT
            supplier.id,
            $text
          FROM
            supplier 
          WHERE
            supplier.site = '$site'";


  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function insertSupplier($conn)
{
  $supplier = $_POST["supplier"];
  $ItemCode = $_POST["ItemCode"];
  $return = array();

  $Sqlinsert = "INSERT INTO multisupplier (itemCode , codeSupplier) VALUE('$ItemCode' , '$supplier')";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function insertDiscription($conn)
{
  $txtDiscription = $_POST["txtDiscription"];
  $ItemCode = $_POST["ItemCode"];
  $return = array();

  $Sqlinsert = "UPDATE item SET discription = '$txtDiscription' WHERE ItemCode = '$ItemCode'  ";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function insertColor($conn)
{
  $codeColor = $_POST["codeColor"];
  $ItemCode = $_POST["ItemCode"];
  $return = array();

  $Sqlinsert = "INSERT INTO multicolor (itemCode , codeColor) VALUE('$ItemCode' , '$codeColor')";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function deleteColor($conn)
{
  $id = $_POST["id"];
  $ItemCode = $_POST["ItemCode"];
  $return = array();

  $Sqlinsert = "DELETE FROM multicolor WHERE id = '$id' ";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function deleteSupplier($conn)
{
  $id = $_POST["id"];
  $ItemCode = $_POST["ItemCode"];
  $return = array();

  $Sqlinsert = "DELETE FROM multisupplier WHERE id = '$id' ";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function updateSupplier($conn)
{
  $id = $_POST["id"];
  $ItemCode = $_POST["ItemCode"];
  $supplier = $_POST["supplier"];
  $return = array();

  $Sqlinsert = "UPDATE multisupplier SET codeSupplier = '$supplier'  WHERE id = '$id' ";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function updateColor($conn)
{
  $id = $_POST["id"];
  $ItemCode = $_POST["ItemCode"];
  $codeColor = $_POST["codeColor"];
  $return = array();

  $Sqlinsert = "UPDATE multicolor SET codeColor = '$codeColor'  WHERE id = '$id' ";
  mysqli_query($conn, $Sqlinsert);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showColor($conn)
{
  $ItemCode = $_POST["ItemCode"];
  $count = 0;

      $Sql = "SELECT multicolor.itemCode,multicolor.codeColor,multicolor.id 
      FROM multicolor WHERE itemCode = '$ItemCode'";
    
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showDiscription($conn)
{
  $ItemCode = $_POST["ItemCode"];
  $count = 0;

      $Sql = "SELECT item.discription
      FROM item WHERE ItemCode = '$ItemCode'";
    
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showSupplier($conn)
{
  $ItemCode = $_POST["ItemCode"];
  $count = 0;

      $Sql = "SELECT multisupplier.itemCode,multisupplier.codeSupplier,multisupplier.id 
      FROM multisupplier WHERE itemCode = '$ItemCode'";
    
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}