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
  }else  if ($_POST['FUNC_NAME'] == 'edit_Detail') {
    edit_Detail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_SizeDetail') {
    show_SizeDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_colorDetail') {
    show_colorDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_supplierDetail') {
    show_supplierDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_siteDetail') {
    show_siteDetail($conn);
  } else  if ($_POST['FUNC_NAME'] == 'openMasterColor') {
    openMasterColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showMasterColor') {
    showMasterColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'switchMasterColor') {
    switchMasterColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveColor') {
    saveColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'deleteColor') {
    deleteColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showSupplierAdd') {
    showSupplierAdd($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkSupplier') {
    checkSupplier($conn);
  } else  if ($_POST['FUNC_NAME'] == 'openModalSupplier') {
    openModalSupplier($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showSiteAdd') {
    showSiteAdd($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkSite') {
    checkSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'openModalSite') {
    openModalSite($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showimg') {
    showimg($conn);
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

function edit_Detail($conn)
{
  $id = $_POST["id"];
  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "itemcatalog.itemCategoryNameEn AS name";
  }else{
    $name = "itemcatalog.itemCategoryName  AS name";
  }

    $Sql = "SELECT
              itemcatalog.id,
              $name,
              itemcatalog.IsActive,
              itemcatalog.typeLinen,
              itemcatalog.discription	
            FROM
              itemcatalog
              INNER JOIN typelinen ON itemcatalog.typeLinen = typelinen.id 
            WHERE
              itemcatalog.id = '$id'
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_SizeDetail($conn)
{
  $id = $_POST["id"];


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


    while ($Result = mysqli_fetch_assoc($meQuery_size)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function show_colorDetail($conn)
{
  $id = $_POST["id"];


  $Sql_color = "SELECT
                  multicolor.color_detail 
                FROM
                  multicolor
                  INNER JOIN item_size ON multicolor.itemsize = item_size.SizeName 
                WHERE
                  multicolor.itemCategoryId = '$id' 
                GROUP BY
                  multicolor.color_detail 
                ORDER BY
                  item_size.SizeCode ASC
                  ";

    $meQuery_color = mysqli_query($conn, $Sql_color);
    while ($Result_color = mysqli_fetch_assoc($meQuery_color)) {
      $return[] = $Result_color;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function show_supplierDetail($conn){

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

function show_siteDetail($conn){

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

function openMasterColor($conn)
{
  $count    = 0;
  $size     = $_POST["size"];
  $txtItemId = $_POST["txtItemId"];

  $Sql = "SELECT
            multicolor.itemCategoryId,
            multicolor.itemsize,
            multicolor.color_master,
            multicolor.color_detail,
            multicolor.id 
          FROM
            multicolor
          WHERE multicolor.itemCategoryId = '$txtItemId' AND  multicolor.itemsize = '$size' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showMasterColor($conn)
{
  $count = 0;
  $Sql = "SELECT
            color_master.color_master_name,
            color_master.color_master_code,
            color_master.id 
          FROM
            color_master";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function switchMasterColor($conn)
{

  $colorMaster = $_POST["colorMaster"];
  $Sql = "SELECT
          color_master.color_master_code,
        color_detail.color_code_detail	
        FROM
          color_master
          LEFT JOIN color_detail ON color_master.ID = color_detail.ID_color_master
        WHERE color_master.ID = $colorMaster ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function deleteColor($conn)
{
  $count    = 0;
  $txtColorId = $_POST["txtColorId"];
  $return = array();

  $Sql = "DELETE FROM multicolor WHERE id = '$txtColorId' ";

  mysqli_query($conn, $Sql);
  echo "1";
  mysqli_close($conn);
  die;
}

function saveColor($conn)
{
  $count    = 0;
  $txtItemId     = $_POST["txtItemId"];
  $radioSize = $_POST["radioSize"];
  $colorDetail = $_POST["colorDetail"];
  $colorMaster = $_POST["colorMaster"];
  $txtColorId = $_POST["txtColorId"];
  $return = array();
  if ($txtColorId == "") {
    $Sql = "INSERT INTO multicolor SET  itemCategoryId = '$txtItemId',
                                        itemsize = '$radioSize',
                                        color_master = '$colorMaster',
                                        color_detail = '$colorDetail'    ";
  } else {
    $Sql = "UPDATE multicolor SET itemsize = '$radioSize',
                                  color_master = '$colorMaster',
                                  color_detail = '$colorDetail'  
            WHERE multicolor.id = '$txtColorId'  ";
  }

  mysqli_query($conn, $Sql);
  echo "1";
  mysqli_close($conn);
  die;
}

function openModalSupplier($conn)
{
  $txtItemId = $_POST["txtItemId"];

  $count = 0;
  $Sql = "SELECT
            multisupplier.id, 
            multisupplier.codeSupplier
          FROM
            multisupplier
          WHERE multisupplier.itemCategoryId = '$txtItemId' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function checkSupplier($conn)
{
  $txtItemId = $_POST["txtItemId"];
  $SupplierArray = $_POST["SupplierArray"];

  $Sql = "DELETE FROM multisupplier WHERE itemCategoryId = '$txtItemId' ";
  mysqli_query($conn, $Sql);

  foreach ($SupplierArray as $key => $value) {
    $Sql = "INSERT INTO multisupplier SET itemCategoryId = '$txtItemId' ,  codeSupplier = '$value' ";
    mysqli_query($conn, $Sql);
  }
  echo "1";
  mysqli_close($conn);
  die;
}

function showSupplierAdd($conn)
{

  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "supplier.name_En AS name";
  }else{
    $name = "supplier.name_Th  AS name";
  }

  $count = 0;
  $Sql = "SELECT
            $name, 
            supplier.id
          FROM
            supplier ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showSiteAdd($conn)
{

  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "site.HptName AS name";
  }else{
    $name = "site.HptNameTH  AS name";
  }
  $count = 0;
  $Sql = "SELECT
            site.HptCode,
            $name 
          FROM
            site";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function checkSite($conn)
{
  $txtItemId = $_POST["txtItemId"];
  $SiteArray = $_POST["SiteArray"];

  $Sql = "DELETE FROM multisite WHERE itemCategoryId = '$txtItemId' ";
  mysqli_query($conn, $Sql);

  foreach ($SiteArray as $key => $value) {
    $Sql = "INSERT INTO multisite SET itemCategoryId = '$txtItemId' ,  site = '$value' ";
    mysqli_query($conn, $Sql);
  }
  echo "1";
  mysqli_close($conn);
  die;
}

function openModalSite($conn)
{
  $txtItemId = $_POST["txtItemId"];

  $count = 0;
  $Sql = "SELECT
            multisite.site, 
            multisite.id
          FROM
            multisite
          WHERE multisite.itemCategoryId = '$txtItemId' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showimg($conn)
{
  $id = $_POST["id"];

  $count = 0;
  $Sql = "SELECT
            itemcatalog.imageOne, 
            itemcatalog.imageTwo, 
            itemcatalog.imageThree
          FROM
            itemcatalog
            WHERE itemcatalog.id='$id' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

