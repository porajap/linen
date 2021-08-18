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
  } else  if ($_POST['FUNC_NAME'] == 'get_color_master') {
    get_color_master($conn);
  } else  if ($_POST['FUNC_NAME'] == 'chk_color_master') {
    chk_color_master($conn);
  } else  if ($_POST['FUNC_NAME'] == 'save_add_color') {
    save_add_color($conn);
  } else  if ($_POST['FUNC_NAME'] == 'getDetail_color') {
    getDetail_color($conn);
  } else  if ($_POST['FUNC_NAME'] == 'save_edit_color') {
    save_edit_color($conn);
  } else  if ($_POST['FUNC_NAME'] == 'chk_color') {
    chk_color($conn);
  }else  if ($_POST['FUNC_NAME'] == 'getSupplier') {
    getSupplier($conn);
  }
}

function getSupplier($conn)
{
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;
  if ($lang == 'en') {
    $Sql = "SELECT
              supplier.name_En as name_supplier,
              supplier.id 
            FROM
              supplier";
  } else {
    $Sql = "SELECT
              supplier.name_Th as name_supplier,
              supplier.id 
            FROM
              supplier";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
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
function chk_color($conn)
{
  $color_code = $_POST["color_code"];
  $Sql = "SELECT
            COUNT(color_detail.color_code_detail)  AS num
          FROM  color_detail
          INNER JOIN color_master  ON  color_detail.ID_color_master = color_master.ID
          WHERE color_detail.color_code_detail='$color_code' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
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
  $select_supplier = $_POST["select_supplier"];
  if ($color_master == 0) {
    $where = "";
  } else {
    $where = "AND color_detail.ID_color_master ='$color_master'";
  }
  if ($select_supplier == 0) {
    $wheresupplier = "";
  } else {
    $wheresupplier = "AND color_detail.codeSupplier ='$select_supplier'";
  }
  $Sql = "SELECT
            color_detail.ID, 
            color_detail.ID_color_master, 
            color_detail.color_code_detail, 
            color_master.color_master_name, 
            supplier.name_Th, 
            color_detail.remark
          FROM  color_detail
          INNER JOIN color_master  ON  color_detail.ID_color_master = color_master.ID
          INNER JOIN supplier  ON  supplier.id = color_detail.codeSupplier
          WHERE color_detail.ID != '' $where $wheresupplier
           ";

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
  $id = $_POST["text_id_color_detail"];
  $return = array();

  $Sql = "DELETE FROM color_detail WHERE color_detail.ID = $id ";

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
  $return = [];
  $color_master = $_POST["color_master"];
  $color_code = $_POST["color_code"];
  $select_supplier = $_POST["select_supplier"];
  $input_remark = $_POST["input_remark"];


  $Sql = "INSERT INTO color_detail SET color_detail.ID_color_master = '$color_master' , 
          color_detail.color_code_detail= '$color_code' , codeSupplier = '$select_supplier' , remark = '$input_remark'
          ";

  mysqli_query($conn, $Sql);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function save_edit_color($conn)
{
  $return = [];
  $color_master = $_POST["color_master"];
  $color_code = $_POST["color_code"];
  $text_id_color_detail = $_POST["text_id_color_detail"];
  $select_supplier = $_POST["select_supplier"];
  $input_remark = $_POST["input_remark"];

  $Sql = "UPDATE color_detail SET color_detail.ID_color_master = '$color_master' , 
          color_detail.color_code_detail= '$color_code' , 
          color_detail.codeSupplier= '$select_supplier' , 
          color_detail.remark= '$input_remark'
          WHERE color_detail.ID = '$text_id_color_detail'
          ";

  mysqli_query($conn, $Sql);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function getDetail_color($conn)
{
  $id_color_detail = $_POST["id_color_detail"];


  $Sql = "SELECT
            color_detail.ID,
            color_detail.ID_color_master,  
            color_detail.color_code_detail, 
            color_master.color_master_name, 
	          color_master.color_master_code, 
	          color_detail.codeSupplier, 
	          color_detail.remark
          FROM
            color_detail
            INNER JOIN
            color_master
            ON 
              color_detail.ID_color_master = color_master.ID
          WHERE
            color_detail.ID = '$id_color_detail'
          ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}
