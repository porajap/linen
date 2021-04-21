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
  } else if ($_POST['FUNC_NAME'] == 'showSize') {
    showSize($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkName') {
    checkName($conn);
  }
}

function checkName($conn)
{
  $txtItemName = $_POST["txtItemName"];
  $txtItemNameEn = $_POST["txtItemNameEn"];
  $txtItemId = $_POST["txtItemId"];

  if ($txtItemId == "") {
    $Sql = "SELECT
    COUNT( itemcatalog.id ) AS count_name
  FROM
  itemcatalog 
  WHERE
    (itemCategoryName = '$txtItemName' OR itemCategoryNameEn = '$txtItemNameEn') AND itemcatalog.IsStatus = 0 ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $count_name = $Result['count_name'];
    }
  }else{
    $count_name = 0;
  }


  if ($count_name > 0) {
    echo "repeat";
  } else {
    echo "no repeat";
  }

  mysqli_close($conn);
  die;
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

function showSize($conn)
{
  $Sql = "SELECT
            item_size.SizeCode,
            item_size.SizeName 
          FROM
            item_size";
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
  $Sql  = "";
  foreach ($SiteArray as $key => $value) {
    $Sql .= "INSERT INTO multisite SET itemCategoryId = '$txtItemId' ,  site = '$value'; ";
    // mysqli_query($conn, $Sql);
  }
  // echo $Sql;
  if (mysqli_multi_query($conn, $Sql)) {
    echo "1";
  }
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
  $txtItemId = $_POST["txtItemId"];
  $sizeArray = $_POST["sizeArray"];
  $return = array();
  foreach ($sizeArray as $key => $value) {
    $Sql = "DELETE FROM multicolor 
                   WHERE multicolor.color_detail = '$txtColorId' 
                   AND itemCategoryId = '$txtItemId'
                   AND multicolor.itemsize = '$value'";
    mysqli_query($conn, $Sql);
  }

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
  $sizeArray = $_POST["sizeArray"];
  $return = array();
  foreach ($sizeArray as $key => $value) {
    $Sql = "SELECT
              COUNT( multicolor.id ) AS count_id 
            FROM
              multicolor 
            WHERE
              multicolor.itemsize = '$value'
            AND multicolor.itemCategoryId = '$txtItemId' 
            AND multicolor.color_detail  = '$txtColorId' ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($row = mysqli_fetch_assoc($meQuery)) {
      $count_id = $row['count_id'];

      if ($count_id == 0) {
        $Sql_ = "INSERT INTO multicolor SET  itemCategoryId = '$txtItemId',
                                        itemsize = '$value',
                                        color_master = '$colorMaster',
                                        color_detail = '$colorDetail'    ";
      } else {
        $Sql_ = "UPDATE multicolor 
                SET itemsize = '$value',
                color_master = '$colorMaster',
                color_detail = '$colorDetail' 
                WHERE
                  multicolor.color_detail = '$txtColorId' 
                  AND itemCategoryId = '$txtItemId'
                  AND multicolor.itemsize = '$value'";
      }
      mysqli_query($conn, $Sql_);
    }
  }




  echo "1";
  mysqli_close($conn);
  die;

  // if ($txtColorId == "") {
  //   $Sql = "INSERT INTO multicolor SET  itemCategoryId = '$txtItemId',
  //                                       itemsize = '$radioSize',
  //                                       color_master = '$colorMaster',
  //                                       color_detail = '$colorDetail'    ";
  // } else {
  //   $Sql = "UPDATE multicolor SET itemsize = '$radioSize',
  //                                 color_master = '$colorMaster',
  //                                 color_detail = '$colorDetail'  
  //           WHERE multicolor.id = '$txtColorId'  ";
  // }


}

function openMasterColor($conn)
{
  $count    = 0;
  $size     = $_POST["size"];
  $txtItemId = $_POST["txtItemId"];
  $sizeArray = $_POST["sizeArray"];
  $wheresize = "";
  foreach ($sizeArray as $key => $value) {
    $wheresize .= "'" . $value . "',";
  }
  $wheresize = rtrim($wheresize, ',');


  $Sql = "SELECT
            multicolor.itemCategoryId,
            multicolor.itemsize,
            multicolor.color_master,
            multicolor.color_detail,
            multicolor.id 
          FROM
            multicolor
          WHERE multicolor.itemCategoryId = '$txtItemId' AND  multicolor.itemsize IN( $wheresize ) GROUP BY multicolor.color_detail";
  // echo $Sql;
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
  $txtDiscriptionEn = $_POST['txtDiscriptionEn'];
  $txtItemName = $_POST['txtItemName'];
  $txtItemNameEn = $_POST['txtItemNameEn'];
  $selectcategory = $_POST['selectcategory'];
  $selectmaincategory = $_POST['selectmaincategory'];
  $txtItemId = $_POST['txtItemId'];
  $Userid = $_SESSION['Userid'];

  $data_imageOne = $_POST['data_imageOne'];
  $data_imageTwo = $_POST['data_imageTwo'];
  $data_imageThree = $_POST['data_imageThree'];
  // $imageOne = explode('.', $_FILES['imageOne']);
  // $imageTwo = explode('.', $_FILES['imageTwo']);
  // $imageThree = explode('.', $_FILES['imageThree']);



  if ($txtItemId == "") {
    $Sql = "INSERT INTO itemcatalog SET mainCategory = '$selectmaincategory' , create_user = '$Userid' , create_date = NOW() , typeLinen = '$selectcategory' , itemCategoryNameEn = trim('$txtItemNameEn') , discription = '$txtDiscription' , discription_EN = trim('$txtDiscriptionEn') , itemCategoryName = '$txtItemName' ";
  } else {
    $Sql = "UPDATE itemcatalog SET mainCategory = '$selectmaincategory' , typeLinen = '$selectcategory' , itemCategoryNameEn = trim('$txtItemNameEn') , discription = '$txtDiscription' , discription_EN = trim('$txtDiscriptionEn') , itemCategoryName = '$txtItemName' WHERE itemcatalog.id = '$txtItemId' ";
  }
  mysqli_query($conn, $Sql);

  $Sql = "SELECT 
            itemcatalog.id,
            ROUND((37384 - 1231 * RAND()), 0) AS random_new,
            imageOne,
            imageTwo,
            imageThree
          FROM 
            itemcatalog
          WHERE itemcatalog.itemCategoryName = '$txtItemName' ";
  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $txtItemId = $Result['id'];
  $random_new = $Result['random_new'];
  $imageOne = $Result['imageOne'] == null ? "" : $Result['imageOne'];
  $imageTwo = $Result['imageTwo'] == null ? "" : $Result['imageTwo'];
  $imageThree = $Result['imageThree'] == null ? "" : $Result['imageThree'];


  $iamge1 = $txtItemId . "-1" . ".png";
  $iamge2 = $txtItemId . "-2" . ".png";
  $iamge3 = $txtItemId . "-3" . ".png";

  $iamge1_now = $txtItemId . "-1_" . $random_new . ".png";
  $iamge2_now = $txtItemId . "-2_" . $random_new . ".png";
  $iamge3_now = $txtItemId . "-3_" . $random_new . ".png";

  // $iamge1_old = $txtItemId . "-1_" . $ramdom_old. ".png";
  // $iamge2_old = $txtItemId . "-2_" . $ramdom_old. ".png";
  // $iamge3_old = $txtItemId . "-3_" . $ramdom_old. ".png";

  include("gen_thumbnail.php");
  if ($_FILES['imageOne'] != "") {
    if ($imageOne != "") {
      unlink('../profile/catalog/' . $imageOne);
    }
    copy($_FILES['imageOne']['tmp_name'], '../profile/catalog/' . $iamge1_now);

    $Sql = "UPDATE itemcatalog SET itemcatalog.imageOne='$iamge1_now'  WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/catalog/" . $iamge1_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/catalog/" . $iamge1_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
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

      if ($imageOne != "") {
        unlink('../profile/catalog/' . $imageOne);
      }

      $Sql = "UPDATE itemcatalog SET itemcatalog.imageOne=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['imageTwo'] != "") {
    if ($imageTwo != "") {
      unlink('../profile/catalog/' . $imageTwo);
    }
    copy($_FILES['imageTwo']['tmp_name'], '../profile/catalog/' . $iamge2_now);

    $Sql = "UPDATE itemcatalog SET itemcatalog.imageTwo='$iamge2_now' WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/catalog/" . $iamge2_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/catalog/" . $iamge2_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
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
      if ($imageTwo != "") {
        unlink('../profile/catalog/' . $imageTwo);
      }
      $Sql = "UPDATE itemcatalog SET itemcatalog.imageTwo=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['imageThree'] != "") {
    if ($imageThree != "") {
      unlink('../profile/catalog/' . $imageThree);
    }
    copy($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3_now);

    $Sql = "UPDATE itemcatalog SET itemcatalog.imageThree='$iamge3_now' WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/catalog/" . $iamge3_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/catalog/" . $iamge3_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
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
      if ($imageThree != "") {
        unlink('../profile/catalog/' . $imageThree);
      }
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
            itemcatalog.discription_EN AS discriptionEn,
            itemcatalog.typeLinen,
            itemcatalog.mainCategory,
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
  $selectcategory = $_POST["selectcategory"];
  $selectmaincategory = $_POST["selectmaincategory"];
  $txtSearch = $_POST["txtSearch"];
  $lang = $_SESSION["lang"];
  if ($lang == 'en') {
    $typelinen = 'typelinen.name_En AS nameType';
  } else {
    $typelinen = 'typelinen.name_Th AS nameType';
  }
  if ($selectcategory == '0') {
    $wherecategory = "";
  } else {
    $wherecategory = "AND itemcatalog.typeLinen = '$selectcategory' ";
  }
  if ($selectmaincategory == '') {
    $wheremaincategory = "";
  } else {
    $wheremaincategory = "AND itemcatalog.mainCategory = '$selectmaincategory' ";
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
          WHERE   itemcatalog.IsStatus = 0 AND ( itemcatalog.itemCategoryName LIKE '%$txtSearch%' OR  itemcatalog.itemCategoryNameEn LIKE '%$txtSearch%' ) $wherecategory  $wheremaincategory";

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
  $Userid = $_SESSION['Userid'];
  $txtItemId = $_POST["txtItemId"];
  $return = array();

  $Sql = "UPDATE itemcatalog SET IsStatus = '1' , cancel_user =  $Userid WHERE itemcatalog.id = $txtItemId ";
  mysqli_query($conn, $Sql);
  // $Sql = "DELETE FROM multicolor WHERE multicolor.itemCategoryId = $txtItemId ";
  // mysqli_query($conn, $Sql);
  // $Sql = "DELETE FROM multisite WHERE multisite.itemCategoryId = $txtItemId ";
  // mysqli_query($conn, $Sql);
  // $Sql = "DELETE FROM multisupplier WHERE multisupplier.itemCategoryId = $txtItemId ";
  // mysqli_query($conn, $Sql);

  echo json_encode($return);
  mysqli_close($conn);
  die;
}
