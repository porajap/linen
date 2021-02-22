<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'showData') {
      showData($conn);
  }else  if ($_POST['FUNC_NAME'] == 'get_typelinen') {
    get_typelinen($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_supplier') {
    show_supplier($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_site') {
    show_site($conn);
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

  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "typelinen.name_En AS typeLinen";
  }else{
    $name = "typelinen.name_Th  AS typeLinen";
  }

  $Sql = "SELECT
            itemcatalog.id, 
            itemcatalog.itemCategoryName, 
            itemcatalog.IsActive, 
            $name
          FROM
            itemcatalog
            INNER JOIN
            typelinen
            ON 
              itemcatalog.typeLinen = typelinen.id
          WHERE
            itemcatalog.typeLinen = '$input_typeline' AND
            itemcatalog.itemCategoryName LIKE '$txtSearch%'
         
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
        $return['color_c'][$id][] = $Result_color;
      }
//----------------------------size_detail-----------------------------------------
      $Sql_size = "SELECT
                      multicolor.itemsize
                    FROM
                      multicolor
                      INNER JOIN item_size ON multicolor.itemsize = item_size.SizeName 
                    WHERE
                      multicolor.itemCategoryId = '$id' 
                    GROUP BY
                      multicolor.itemsize 
                    ORDER BY item_size.SizeCode ASC
                  ";

        $meQuery_size = mysqli_query($conn, $Sql_size);
        $itemsize="";
        $count_size=0;
        while ($Result_size = mysqli_fetch_assoc($meQuery_size)) {
          if($count_size==0){
            $t="";
          }else{
            $t=",";
          }
          $itemsize .= $t.$Result_size['itemsize'];
          $count_size++;
        }
        $return['size'][$id][] = $itemsize;
//---------------------------------------------------------------------

    $return['item'][] = $Result;
    $count_i++;
  }
 
  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function get_typelinen($conn)
{
  
  $lang = $_SESSION['lang'];
  if($lang == 'en'){
    $name = "supplier.name_En AS name";
  }else{
    $name = "supplier.name_Th  AS name";
  }


    $Sql = "SELECT
              typelinen.id, 
              typelinen.name_En
            FROM
              typelinen ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_supplier($conn)
{
  $id = $_POST["id"];
  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "supplier.name_En AS name";
  }else{
    $name = "supplier.name_Th  AS name";
  }

    $Sql = "SELECT
              multisupplier.codeSupplier,
              $name 
            FROM
              multisupplier
              INNER JOIN supplier ON multisupplier.codeSupplier = supplier.id
              WHERE multisupplier.itemCategoryId='$id'
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_site($conn)
{
  $id = $_POST["id"];
  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "site.HptName AS name";
  }else{
    $name = "site.HptNameTH  AS name";
  }

    $Sql = "SELECT
              multisite.id,
              $name
            FROM
              multisite
              INNER JOIN site ON multisite.site = site.HptCode
            WHERE multisite.itemCategoryId='$id'
            ORDER BY multisite.id ASC
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}



