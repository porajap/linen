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
  } else  if ($_POST['FUNC_NAME'] == 'showMasterColor') {
    showMasterColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'switchMasterColor') {
    switchMasterColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'openMasterColor') {
    openMasterColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'saveColor') {
    saveColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'deleteColor') {
    deleteColor($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showSupplier') {
    showSupplier($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkSupplier') {
    checkSupplier($conn);
  } else  if ($_POST['FUNC_NAME'] == 'openModalSupplier') {
    openModalSupplier($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showSite') {
    showSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkSite') {
    checkSite($conn);
  } else  if ($_POST['FUNC_NAME'] == 'openModalSite') {
    openModalSite($conn);
  } else if ($_POST['FUNC_NAME'] == 'getTypeLinen') {
    getTypeLinen($conn);
  }
}

function getTypeLinen($conn)
{
 $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;
  if ($lang == 'en') {
      $Sql = "SELECT
                typelinen.id, 
                typelinen.name_En  as nametype
              FROM
                typelinen";
    
  } else {
      $Sql = "SELECT
                typelinen.id, 
                typelinen.name_Th as nametype
              FROM
                typelinen";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showSite($conn)
{
  $count = 0;
  $Sql = "SELECT
            site.HptCode,
            site.HptName 
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

function showSupplier($conn)
{
  $count = 0;
  $Sql = "SELECT
            supplier.name_Th, 
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

function saveData($conn)
{
  $txtDiscription = $_POST['txtDiscription'];
  $txtItemName = $_POST['txtItemName'];
  $selectcategory = $_POST['selectcategory'];
  $txtItemId = $_POST['txtItemId'];
  $txtItemNameEn = $_POST['txtItemNameEn'];

  $data_imageOne = $_POST['data_imageOne'];
  $data_imageTwo = $_POST['data_imageTwo'];
  $data_imageThree = $_POST['data_imageThree'];

  
  // $imageOne = explode('.', $_FILES['imageOne']);
  // $imageTwo = explode('.', $_FILES['imageTwo']);
  // $imageThree = explode('.', $_FILES['imageThree']);
  // ss


  if ($txtItemId == "") {
    $Sql = "INSERT INTO itemcatalog SET typeLinen = '$selectcategory' , discription = '$txtDiscription' , itemCategoryName = '$txtItemName' , itemCategoryNameEn = '$txtItemNameEn'  ";
  } else {
    $Sql = "UPDATE itemcatalog SET typeLinen = '$selectcategory' , discription = '$txtDiscription' , itemCategoryName = '$txtItemName' , itemCategoryNameEn = '$txtItemNameEn' WHERE itemcatalog.id = '$txtItemId' ";
  }
  mysqli_query($conn, $Sql);

  $Sql = "SELECT 
            itemcatalog.id 
          FROM 
            itemcatalog
          WHERE itemcatalog.itemCategoryName = '$txtItemName' ";
  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $txtItemId = $Result['id'];


  $iamge1 = $txtItemId . "-1". "png";
  $iamge2 = $txtItemId . "-2". "png";
  $iamge3 = $txtItemId . "-3". "png";

  include("gen_thumbnail.php");

  if ($_FILES['imageOne'] != "") {
    unlink($_FILES['imageOne']['tmp_name'], '../profile/catalog/' . $iamge1);
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
    unlink($_FILES['imageTwo']['tmp_name'], '../profile/catalog/' . $iamge2);
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
    unlink($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3);
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

  $return[] = $txtItemId;
  echo json_encode($return);
  mysqli_close($conn);
}


function showDetail($conn)
{
  $id = $_POST["id"];

  $Sql = "SELECT
            itemcatalog.id,
            itemcatalog.itemCategoryName,
            itemcatalog.itemCategoryNameEn,
            itemcatalog.discription,
            itemcatalog.typeLinen,
            itemcatalog.imageOne,
            itemcatalog.imageTwo,
            itemcatalog.imageThree  
          FROM
            itemcatalog
          WHERE itemcatalog.id = '$id' ";
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
  $lang = $_SESSION["lang"];
  if($lang == 'en'){
    $typelinen = 'typelinen.name_En AS nameType';
  }else{
    $typelinen = 'typelinen.name_Th AS nameType';
  }
  $Sql = "SELECT
            itemcatalog.id,
            itemcatalog.itemCategoryName,
            itemcatalog.itemCategoryNameEn, 
            $typelinen,
            itemcatalog.discription 
          FROM
          itemcatalog
          INNER JOIN typelinen ON itemcatalog.typeLinen = typelinen.id 
          WHERE  itemcatalog.itemCategoryName LIKE '%$txtSearch%' LIMIT 50";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    // $Result['discription'] ==null?'':$Result['discription'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function deleteData($conn)
{
  $txtItemId = $_POST["txtItemId"];
  $return = array();

  $Sql = "DELETE FROM itemcatalog WHERE itemcatalog.id = $txtItemId ";
  mysqli_query($conn, $Sql);
  $Sql = "DELETE FROM multicolor WHERE multicolor.itemCategoryId = $txtItemId ";
  mysqli_query($conn, $Sql);
  $Sql = "DELETE FROM multisite WHERE multisite.itemCategoryId = $txtItemId ";
  mysqli_query($conn, $Sql);
  $Sql = "DELETE FROM multisupplier WHERE multisupplier.itemCategoryId = $txtItemId ";
  mysqli_query($conn, $Sql);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}
