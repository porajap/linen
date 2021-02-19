<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'showData') {
      showData($conn);
  }else  if ($_POST['FUNC_NAME'] == 'get_color_master') {
    get_color_master($conn);
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


function showData($conn)
{
  $input_typeline = $_POST["input_typeline"];
  $txtSearch = $_POST["txtSearch"];

  $Sql = "SELECT
            itemcatalog.id, 
            itemcatalog.itemCategoryName, 
            itemcatalog.typeLinen, 
            itemcatalog.IsActive
          FROM
            itemcatalog
          WHERE itemcatalog.typeLinen ='$input_typeline'
          AND  itemcatalog.itemCategoryName LIKE '$txtSearch%'
          ORDER BY itemcatalog.id ASC
          ";
$count_i=0;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
  $id = $Result['id'];

//----------------------------color_detail-----------------------------------------
      $Sql_color = "SELECT
                      multicolor.color_detail
                    FROM
                      multicolor
                      WHERE multicolor.itemCategoryId='$id'
                      GROUP BY multicolor.color_detail
                  ";

      $meQuery_color = mysqli_query($conn, $Sql_color);
      while ($Result_color = mysqli_fetch_assoc($meQuery_color)) {
        $return['color'][$id][] = $Result_color;
      }
//----------------------------size_detail-----------------------------------------
      $Sql_size = "SELECT
              multicolor.color_detail
            FROM
              multicolor
              WHERE multicolor.itemCategoryId='$id'
              GROUP BY multicolor.color_detail
          ";

        $meQuery_size = mysqli_query($conn, $Sql_size);
        while ($Result_size = mysqli_fetch_assoc($meQuery_size)) {
        $return['size'][$id][] = $Result_size;
        }
//---------------------------------------------------------------------

    $return['item'][] = $Result;
    $count_i++;
  }
 
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



