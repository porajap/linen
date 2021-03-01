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
  }else  if ($_POST['FUNC_NAME'] == 'saveData_detail') {
    saveData_detail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showColorDetail_size') {
    showColorDetail_size($conn);
  }
  
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
    $name = "typelinen.name_En AS name";
  }else{
    $name = "typelinen.name_Th  AS name";
  }


    $Sql = "SELECT
              typelinen.id, 
              $name
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



    $Sql = "SELECT
              itemcatalog.id,
              itemcatalog.itemCategoryNameEn,
              itemcatalog.itemCategoryName,
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


function saveData_detail($conn)
{
  $txtDiscription = $_POST['txtDiscription'];
  $txtItemName = $_POST['txtItemName'];
  $typelinen_detail = $_POST['typelinen_detail'];
  $txtItemId = $_POST['txtItemId'];
  $activecatalog = $_POST['activecatalog'];
  $txtItemNameEn = $_POST['txtItemNameEn'];

  $data_imageOne = $_POST['data_imageOne'];
  $data_imageTwo = $_POST['data_imageTwo'];
  $data_imageThree = $_POST['data_imageThree'];

    $Sql = "UPDATE itemcatalog SET typeLinen = '$typelinen_detail' , discription = '$txtDiscription' , itemCategoryName = '$txtItemName', itemCategoryNameEn = '$txtItemNameEn'  WHERE itemcatalog.id = '$txtItemId' ";

  mysqli_query($conn, $Sql);

  // $Sql = "SELECT 
  //           itemcatalog.id 
  //         FROM 
  //           itemcatalog
  //         WHERE itemcatalog.itemCategoryName = '$txtItemName' ";
  // $meQuery = mysqli_query($conn, $Sql);
  // $Result = mysqli_fetch_assoc($meQuery);
  // $txtItemId = $Result['id'];


  $iamge1 = $txtItemId . "-1". ".png";
  $iamge2 = $txtItemId . "-2". ".png";
  $iamge3 = $txtItemId . "-3". ".png";

  include("gen_thumbnail.php");

  if ($_FILES['imageOne'] != "") {
    // unlink($_FILES['imageOne']['tmp_name'], '../profile/catalog/' . $iamge1);
    copy($_FILES['imageOne']['tmp_name'], '../profile/catalog/' . $iamge1);

    $Sql = "UPDATE itemcatalog SET itemcatalog.imageOne='$iamge1'  WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/catalog/" . $iamge1,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/catalog/" . $iamge1,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 500,         //  กำหนดความกว้างรูปใหม่
      "height" => 500,       //  กำหนดความสูงรูปใหม่
      "background" => "#fff",    // กำหนดสีพื้นหลังรูปใหม่ (#FF0000) ถ้าไม่กำหนดและ เป็น gif หรือ png จะแสดงเป็นโปร่งใส
      "output" => "",        //  กำหนดนามสกุลไฟล์ใหม่ jpg | gif หรือ png ถ้าไม่กำหนด จะใช้ค่าเริ่มต้นจากต้นฉบับ
      "show" => 0,           //  แสดงเป็นรูปภาพ หรือสร้างเป็นไฟล์ 0=สร้างเป็นไฟล์ | 1=แสดงเป็นรูปภาพ
      "crop" => 1                //  กำหนด crop หรือ ไม่ 0=crop | 1=crop
    );
    createthumb(
      $cfg_thumb->source,
      $cfg_thumb->destination,
      $cfg_thumb->width,
      $cfg_thumb->height,
      $cfg_thumb->background,
      $cfg_thumb->output,
      $cfg_thumb->show,
      $cfg_thumb->crop
    );
  } else {
    if ($data_imageOne == "default") {
      $Sql = "UPDATE itemcatalog SET itemcatalog.imageOne=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['imageTwo'] != "") {
    // unlink($_FILES['imageTwo']['tmp_name'], '../profile/catalog/' . $iamge2);
    copy($_FILES['imageTwo']['tmp_name'], '../profile/catalog/' . $iamge2);

    $Sql = "UPDATE itemcatalog SET itemcatalog.imageTwo='$iamge2' WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/catalog/" . $iamge2,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/catalog/" . $iamge2,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 500,         //  กำหนดความกว้างรูปใหม่
      "height" => 500,       //  กำหนดความสูงรูปใหม่
      "background" => "#fff",    // กำหนดสีพื้นหลังรูปใหม่ (#FF0000) ถ้าไม่กำหนดและ เป็น gif หรือ png จะแสดงเป็นโปร่งใส
      "output" => "",        //  กำหนดนามสกุลไฟล์ใหม่ jpg | gif หรือ png ถ้าไม่กำหนด จะใช้ค่าเริ่มต้นจากต้นฉบับ
      "show" => 0,           //  แสดงเป็นรูปภาพ หรือสร้างเป็นไฟล์ 0=สร้างเป็นไฟล์ | 1=แสดงเป็นรูปภาพ
      "crop" => 1                //  กำหนด crop หรือ ไม่ 0=crop | 1=crop
    );
    createthumb(
      $cfg_thumb->source,
      $cfg_thumb->destination,
      $cfg_thumb->width,
      $cfg_thumb->height,
      $cfg_thumb->background,
      $cfg_thumb->output,
      $cfg_thumb->show,
      $cfg_thumb->crop
    );
  } else {
    if ($data_imageTwo == "default") {
      $Sql = "UPDATE itemcatalog SET itemcatalog.imageTwo=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['imageThree'] != "") {
    // unlink($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3);
    copy($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3);

    $Sql = "UPDATE itemcatalog SET itemcatalog.imageThree='$iamge3' WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/catalog/" . $iamge3,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/catalog/" . $iamge3,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 500,         //  กำหนดความกว้างรูปใหม่
      "height" => 500,       //  กำหนดความสูงรูปใหม่
      "background" => "#fff",    // กำหนดสีพื้นหลังรูปใหม่ (#FF0000) ถ้าไม่กำหนดและ เป็น gif หรือ png จะแสดงเป็นโปร่งใส
      "output" => "",        //  กำหนดนามสกุลไฟล์ใหม่ jpg | gif หรือ png ถ้าไม่กำหนด จะใช้ค่าเริ่มต้นจากต้นฉบับ
      "show" => 0,           //  แสดงเป็นรูปภาพ หรือสร้างเป็นไฟล์ 0=สร้างเป็นไฟล์ | 1=แสดงเป็นรูปภาพ
      "crop" => 1                //  กำหนด crop หรือ ไม่ 0=crop | 1=crop
    );
    createthumb(
      $cfg_thumb->source,
      $cfg_thumb->destination,
      $cfg_thumb->width,
      $cfg_thumb->height,
      $cfg_thumb->background,
      $cfg_thumb->output,
      $cfg_thumb->show,
      $cfg_thumb->crop
    );
  } else {
    if ($data_imageThree == "default") {
      $Sql = "UPDATE itemcatalog SET itemcatalog.imageThree=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  $return[] = $_FILES['imageOne'];
  echo json_encode($return);
  mysqli_close($conn);
}


function showColorDetail_size($conn)
{
  $sizeName = $_POST['sizeName'];
  $catalog_id = $_POST['catalog_id'];

  $Sql = "SELECT
            multicolor.color_detail 
          FROM
            multicolor 
          WHERE
            multicolor.itemCategoryId = '$catalog_id' 
            AND multicolor.itemsize = '$sizeName' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  unset($conn);
  die;
}
