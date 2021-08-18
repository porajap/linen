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
  }else  if ($_POST['FUNC_NAME'] == 'show_FacbricDetail') {
    show_FacbricDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_Thread_countDetail') {
    show_Thread_countDetail($conn);
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
  }else  if ($_POST['FUNC_NAME'] == 'show_banner') {
    show_banner($conn);
  }else  if ($_POST['FUNC_NAME'] == 'save_banner') {
    save_banner($conn);
  }else  if ($_POST['FUNC_NAME'] == 'showData_htp') {
    showData_htp($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_htp') {
    show_htp($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_htpDetail') {
    show_htpDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_storeDetail') {
    show_storeDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'saveData_storeDetail') {
    saveData_storeDetail($conn);
  }else  if ($_POST['FUNC_NAME'] == 'save_Timestoce') {
    save_Timestoce($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_Timestoce') {
    show_Timestoce($conn);
  }else  if ($_POST['FUNC_NAME'] == 'show_edit_time') {
    show_edit_time($conn);
  }else  if ($_POST['FUNC_NAME'] == 'save_Timestoce_edit') {
    save_Timestoce_edit($conn);
  }else  if ($_POST['FUNC_NAME'] == 'delete_time') {
    delete_time($conn);
  }else  if ($_POST['FUNC_NAME'] == 'delete_storeDetail') {
    delete_storeDetail($conn);
  } else if ($_POST['FUNC_NAME'] == 'showSize') {
    showSize($conn);
  }else if ($_POST['FUNC_NAME'] == 'save_about') {
    save_about($conn);
  }else if ($_POST['FUNC_NAME'] == 'show_about') {
    show_about($conn);
  }
  
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

function showData($conn)
{
  $input_typeline = $_POST["input_typeline"];
  $selectMainCategoryTop = $_POST["selectMainCategoryTop"];
  $txtSearch = $_POST["txtSearch"];

  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $name = "typelinen.name_En AS typeLinen";
  }else{
    $name = "typelinen.name_Th  AS typeLinen";
  }
  if($input_typeline==00){
    $where="";
  }else{
    $where="itemcatalog.typeLinen = '$input_typeline' AND";
  }
  if ($selectMainCategoryTop == '') {
    $wheremaincategory = "";
  } else {
    $wheremaincategory = "AND itemcatalog.mainCategory = '$selectMainCategoryTop' ";
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
          $where
             itemcatalog.itemCategoryName LIKE '%$txtSearch%' AND itemcatalog.IsStatus = 0 $wheremaincategory
         
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
  $chk_lang = $_POST["chk_lang"];
  
  if($chk_lang == 0){
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
              itemcatalog.discription,
              itemcatalog.discription_EN		
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

function show_FacbricDetail($conn){
  $id = $_POST["id"];
  $lang = $_SESSION['lang'];
  $num_lang = $_POST['num_lang'];

  if($num_lang == 0){
    $name = "fabric.name_Fabric AS name";
  }else{
    $name = "fabric.name_Fabric  AS name";
  }

    $Sql = "SELECT
              multifabric.codeFabric,
              $name 
            FROM
            multifabric
              INNER JOIN fabric ON multifabric.codeFabric = fabric.id
              WHERE multifabric.itemCategoryId='$id'
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function show_Thread_countDetail($conn){
  $id = $_POST["id"];
  $lang = $_SESSION['lang'];
  $num_lang = $_POST['num_lang'];

  if($num_lang == 0){
    $name = "thread_count.name_Thread AS name";
  }else{
    $name = "thread_count.name_Thread  AS name";
  }

    $Sql = "SELECT
              multithread_count.codeThread_count,
              $name 
            FROM
            multithread_count
              INNER JOIN thread_count ON multithread_count.codeThread_count = thread_count.id
              WHERE multithread_count.itemCategoryId='$id'  ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_supplierDetail($conn){

    $id = $_POST["id"];
    $lang = $_SESSION['lang'];
    $num_lang = $_POST['num_lang'];

    if($num_lang == 0){
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
  $num_lang = $_POST['num_lang'];
  if($num_lang == 0){
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
  // $return[] = $Sql;
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
  $chk_lang = $_POST["chk_lang"];
  

  if($chk_lang == 0){
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
  $chk_lang = $_POST["chk_lang"];
 
  if($chk_lang == 0){
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
            itemcatalog.imageThree,
            itemcatalog.imageDefault
          FROM
            itemcatalog
            WHERE itemcatalog.id='$id' 
          ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_banner($conn)
{
  $id = $_POST["id"];

  $count = 0;
  $Sql = "SELECT
            banner.bannerOne, 
            banner.bannerTwo, 
            banner.bannerThree
          FROM banner
          ORDER BY banner.row ASC LIMIT 1";

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
  $txtDiscription_EN = $_POST['txtDiscription_EN'];
  $txtItemName = $_POST['txtItemName'];
  $typelinen_detail = $_POST['typelinen_detail'];
  $txtItemId = $_POST['txtItemId'];
  $activecatalog = $_POST['activecatalog'];
  $txtItemNameEn = $_POST['txtItemNameEn'];

  $data_imageOne = $_POST['data_imageOne'];
  $data_imageTwo = $_POST['data_imageTwo'];
  $data_imageThree = $_POST['data_imageThree'];
  $radio_img = $_POST['radio_img'];
  
    $Sql = "UPDATE itemcatalog SET typeLinen = '$typelinen_detail' , discription = '$txtDiscription' , discription_EN = '$txtDiscription_EN' , itemCategoryName = '$txtItemName', itemCategoryNameEn = '$txtItemNameEn', IsActive = '$activecatalog'  WHERE itemcatalog.id = '$txtItemId' ";

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
  $imageOne = $Result['imageOne']==null?"":$Result['imageOne'];
  $imageTwo = $Result['imageTwo']==null?"":$Result['imageTwo'];
  $imageThree = $Result['imageThree']==null?"":$Result['imageThree'];


  $iamge1 = $txtItemId . "-1" .".png";
  $iamge2 = $txtItemId . "-2" .".png";
  $iamge3 = $txtItemId . "-3" .".png";

  $iamge1_now = $txtItemId . "-1_" . $random_new. ".png";
  $iamge2_now = $txtItemId . "-2_" . $random_new. ".png";
  $iamge3_now = $txtItemId . "-3_" . $random_new. ".png";



  $iamge1 = $txtItemId . "-1". ".png";
  $iamge2 = $txtItemId . "-2". ".png";
  $iamge3 = $txtItemId . "-3". ".png";

  include("gen_thumbnail.php");

  if ($_FILES['imageOne'] != "") {
    if($imageOne != ""){
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

      if($imageOne != ""){
        unlink('../profile/catalog/' . $imageOne);
      }

      $Sql = "UPDATE itemcatalog SET itemcatalog.imageOne=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['imageTwo'] != "") {
   if($imageTwo != ""){
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

      if($imageTwo != ""){
        unlink('../profile/catalog/' . $imageTwo);
      }
      
      $Sql = "UPDATE itemcatalog SET itemcatalog.imageTwo=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['imageThree'] != "") {
    // unlink($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3);
    if($imageThree != ""){
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
      if($imageThree != ""){
        unlink('../profile/catalog/' . $imageThree);
      }

      $Sql = "UPDATE itemcatalog SET itemcatalog.imageThree=null  WHERE itemcatalog.id = '$txtItemId';";
      mysqli_query($conn, $Sql);
    }
  }


  $Sql2 = "SELECT 
            itemcatalog.id,
            imageOne,
            imageTwo,
            imageThree
          FROM 
            itemcatalog
          WHERE itemcatalog.id = '$txtItemId' ";

  $meQuery2 = mysqli_query($conn, $Sql2);
  $Result2 = mysqli_fetch_assoc($meQuery2);
  $imageOne = $Result2['imageOne']==null?"":$Result2['imageOne'];
  $imageTwo = $Result2['imageTwo']==null?"":$Result2['imageTwo'];
  $imageThree = $Result2['imageThree']==null?"":$Result2['imageThree'];

  if ($radio_img == 1) {
    $imageDefault =  $imageOne;
  } else if($radio_img == 2) {
    $imageDefault =  $imageTwo;
  } else if($radio_img == 3){
    $imageDefault =  $imageThree;
  }

    $Sql_img_def = "UPDATE itemcatalog SET itemcatalog.imageDefault='$imageDefault'  WHERE itemcatalog.id = '$txtItemId';";
    mysqli_query($conn, $Sql_img_def);

  $return[] = $txtItemId;
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


function save_banner($conn)
{

  $data_bannerOne = $_POST['data_bannerOne'];
  $data_bannerTwo = $_POST['data_bannerTwo'];
  $data_bannerThree = $_POST['data_bannerThree'];

  $Sql = "SELECT 
            banner.row,
            ROUND((37384 - 1231 * RAND()), 0) AS random_new,
            banner.bannerOne,
            banner.bannerTwo,
            banner.bannerThree
          FROM banner
          ORDER BY banner.row ASC LIMIT 1
          ";

  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $bannerId = $Result['row'];
  $random_new = $Result['random_new'];
  $bannerOne = $Result['bannerOne']==null?"":$Result['bannerOne'];
  $bannerTwo = $Result['bannerTwo']==null?"":$Result['bannerTwo'];
  $bannerThree = $Result['bannerThree']==null?"":$Result['bannerThree'];


  $banner1 = $bannerId . "-1" .".jpg";
  $banner2 = $bannerId . "-2" .".jpg";
  $banner3 = $bannerId . "-3" .".jpg";

  $banner1_now = $bannerId . "-1_" . $random_new. ".jpg";
  $banner2_now = $bannerId . "-2_" . $random_new. ".jpg";
  $banner3_now = $bannerId . "-3_" . $random_new. ".jpg";



  $banner1 = $bannerId . "-1". ".jpg";
  $banner2 = $bannerId . "-2". ".jpg";
  $banner3 = $bannerId . "-3". ".jpg";

  include("gen_thumbnail.php");

  if ($_FILES['bannerOne'] != "") {
    if($bannerOne != ""){
      unlink('../profile/banner/' . $bannerOne);
    }
    copy($_FILES['bannerOne']['tmp_name'], '../profile/banner/' . $banner1_now);

    $Sql = "UPDATE banner SET banner.bannerOne='$banner1_now'  WHERE banner.row = '$bannerId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/banner/" . $banner1_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/banner/" . $banner1_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 1900,         //  กำหนดความกว้างรูปใหม่
      "height" => 600,       //  กำหนดความสูงรูปใหม่
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
    if ($data_bannerOne == "default") {

      if($bannerOne != ""){
        unlink('../profile/banner/' . $bannerOne);
      }

      $Sql = "UPDATE banner SET banner.bannerOne=null  WHERE banner.row = '$bannerId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['bannerTwo'] != "") {
   if($bannerTwo != ""){
      unlink('../profile/banner/' . $bannerTwo);
    }
    copy($_FILES['bannerTwo']['tmp_name'], '../profile/banner/' . $banner2_now);

    $Sql = "UPDATE banner SET banner.bannerTwo='$banner2_now' WHERE banner.row = '$bannerId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/banner/" . $banner2_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/banner/" . $banner2_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 1900,         //  กำหนดความกว้างรูปใหม่
      "height" => 600,       //  กำหนดความสูงรูปใหม่
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
    if ($data_bannerTwo == "default") {

      if($bannerTwo != ""){
        unlink('../profile/banner/' . $bannerTwo);
      }
      
      $Sql = "UPDATE banner SET banner.bannerTwo=null  WHERE banner.row = '$bannerId';";
      mysqli_query($conn, $Sql);
    }
  }

  if ($_FILES['bannerThree'] != "") {
    if($bannerThree != ""){
      unlink('../profile/banner/' . $bannerThree);
    }
    copy($_FILES['bannerThree']['tmp_name'], '../profile/banner/' . $banner3_now);

    $Sql = "UPDATE banner SET banner.bannerThree='$banner3_now' WHERE banner.row = '$bannerId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/banner/" . $banner3_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/banner/" . $banner3_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 1900,         //  กำหนดความกว้างรูปใหม่
      "height" => 600,       //  กำหนดความสูงรูปใหม่
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
    if ($data_bannerThree == "default") {
      if($bannerThree != ""){
        unlink('../profile/banner/' . $bannerThree);
      }

      $Sql = "UPDATE banner SET banner.bannerThree=null  WHERE banner.row = '$bannerId';";
      mysqli_query($conn, $Sql);
    }
  }

  $return[] = $bannerId;
  echo json_encode($return);
  mysqli_close($conn);
}

function showData_htp($conn)
{
  $txtSearch_htp = $_POST["txtSearch_htp"];
  $lang = $_SESSION['lang'];
  
 

  if($lang == "en"){
    $name = "site.HptName AS HptName";
  }else{
    $name = "site.HptNameTH  AS HptName";
  }

  $Sql = "SELECT
              store_location.id,
              store_location.HptCode,
              store_location.phone,
              store_location.IsActive,
              store_location.address,
              $name
            FROM
              store_location
              INNER JOIN site ON store_location.HptCode = site.HptCode
              WHERE (site.HptName LIKE '%$txtSearch_htp%' OR site.HptNameTH LIKE '%$txtSearch_htp%')
              ORDER BY store_location.id DESC
          ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_htp($conn){

  $lang = $_SESSION['lang'];

  if($lang == "en"){
    $name = "site.HptName AS name";
    // $name2 = "site.HptName";
  }else{
    $name = "site.HptNameTH  AS name";
    // $name2 = "site.HptNameTH";
  }
  $count=0;
  $Sql_htpstore = "SELECT
                    store_location.HptCode
                   FROM
                    store_location
                    GROUP BY store_location.HptCode
                  ";
  $meQuery_htpstore = mysqli_query($conn, $Sql_htpstore);
  $HptCode="''";
  while ($Result_htpstore = mysqli_fetch_assoc($meQuery_htpstore)) {
    
    if($count==0){
      $t="";
    }else{
      $t=",";
    }

    $HptCode .= $t."'".$Result_htpstore['HptCode']."'";
    $count++;
  }

    $Sql = "SELECT
                site.HptCode, 
                $name
              FROM
                site
              WHERE site.HptCode NOT IN ($HptCode) ";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }
 

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_htpDetail($conn){

  $htpcode = $_POST["htpcode"];
  $lang = $_SESSION['lang'];

  if($lang == "en"){
    $name = "site.HptName AS name";
  }else{
    $name = "site.HptNameTH  AS name";
  }

      $Sql = "SELECT
                site.HptCode, 
                $name
              FROM
                site
              WHERE site.HptCode ='$htpcode'
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }


  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_storeDetail($conn){

  $id = $_POST["id"];

    $Sql = "SELECT
              store_location.phone,
              store_location.HptCode,
              store_location.IsActive,
              store_location.image_htp,
              store_location.address,
              store_location.address_En
            FROM
              store_location
              INNER JOIN site ON store_location.HptCode = site.HptCode
              WHERE store_location.id ='$id'
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }
   

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function saveData_storeDetail($conn)
{
  $id_store = $_POST['id_store'];
  $htp_select = $_POST['htp_select'];
  $txtaddress = $_POST['txtaddress'];
  $txtaddress_EN = $_POST['txtaddress_EN'];
  $txtphone = $_POST['txtphone'];
  $active_htp = $_POST['active_htp'];
  $Userid= $_SESSION['Userid']; 
  $data_imag_htp = $_POST['data_imag_htp'];

  if ($id_store == "") {
    $Sql = "INSERT INTO store_location SET HptCode = '$htp_select' , phone = '$txtphone' , create_date = DATE(NOW()) , create_user = '$Userid' , IsActive = '$active_htp'
    , address = '$txtaddress', address_En = '$txtaddress_EN' ";
  } else {
    $Sql = "UPDATE store_location SET HptCode = '$htp_select' , phone = '$txtphone' , IsActive = '$active_htp' , create_user = '$Userid' , address = '$txtaddress', address_En = '$txtaddress_EN' WHERE store_location.id = '$id_store' ";
  }
  

  mysqli_query($conn, $Sql);

  $Sql = "SELECT 
            store_location.id,
            ROUND((37384 - 1231 * RAND()), 0) AS random_new,
            store_location.image_htp
          FROM 
          store_location
          WHERE store_location.HptCode = '$htp_select' ";

  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $txtstoreId = $Result['id'];
  $random_new = $Result['random_new'];
  $imageOne = $Result['image_htp']==null?"":$Result['image_htp'];



  $iamge1 = $txtstoreId . "-1" .".png";

  $iamge1_now = $txtstoreId . "-1_" . $random_new. ".png";

  $iamge1 = $txtstoreId . "-1". ".png";

  include("gen_thumbnail.php");

  if ($_FILES['imag_htp'] != "") {
    if($imageOne != ""){
      unlink('../profile/img_store/' . $imageOne);
    }
    copy($_FILES['imag_htp']['tmp_name'], '../profile/img_store/' . $iamge1_now);

    $Sql = "UPDATE store_location SET store_location.image_htp='$iamge1_now'  WHERE store_location.id = '$txtstoreId';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
      "source" => "../profile/img_store/" . $iamge1_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
      "destination" => "../profile/img_store/" . $iamge1_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
      "width" => 350,         //  กำหนดความกว้างรูปใหม่
      "height" => 450,       //  กำหนดความสูงรูปใหม่
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
    if ($data_imag_htp == "default") {

      if($imageOne != ""){
        unlink('../profile/img_store/' . $imageOne);
      }

      $Sql = "UPDATE store_location SET store_location.image_htp=null  WHERE store_location.id = '$txtstoreId';";
      mysqli_query($conn, $Sql);
    }
  }

  

  $return[] = $txtstoreId;
  echo json_encode($return);
  mysqli_close($conn);
}

function save_Timestoce($conn)
{
  $id_store = $_POST["id_store"];
  $txtTimestoce = $_POST["txtTimestoce"];
  $txtTimestoce_EN = $_POST["txtTimestoce_EN"];

    $Sql = "INSERT INTO office_hours SET id_storc_location = '$id_store' ,  office_hours = '$txtTimestoce',  office_hours_EN = '$txtTimestoce_EN' ";
    mysqli_query($conn, $Sql);

    $return[] = $id_store;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_Timestoce($conn)
{
  $id_store = $_POST["id_store"];

  $lang = $_SESSION['lang'];

  if($lang == "en"){
    $name = "office_hours.office_hours_EN AS name";
  }else{
    $name = "office_hours.office_hours  AS name";
  }

  $Sql = "SELECT
            office_hours.id,
            $name 
          FROM
            office_hours 
          WHERE
            office_hours.id_storc_location = '$id_store'";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function show_edit_time($conn)
{
  $id_Timestoce = $_POST["id_Timestoce"];

  $Sql = "SELECT
            office_hours.id,
            office_hours.office_hours,
            office_hours.office_hours_EN  
          FROM
            office_hours 
          WHERE
            office_hours.id = '$id_Timestoce'";

  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function save_Timestoce_edit($conn)
{
  $id_Timestoce = $_POST["id_Timestoce"];
  $txtTimestoce_edit = $_POST["txtTimestoce_edit"];
  $txtTimestoce_edit_EN = $_POST["txtTimestoce_edit_EN"];
  
    $Sql = "UPDATE office_hours SET   office_hours = '$txtTimestoce_edit',office_hours_EN = '$txtTimestoce_edit_EN' WHERE office_hours.id = '$id_Timestoce' ";
    mysqli_query($conn, $Sql);

    $return[] = $id_Timestoce;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function delete_time($conn)
{
  $id_Timestoce = $_POST["id_Timestoce"];

    $Sql = "DELETE FROM  office_hours  WHERE office_hours.id = '$id_Timestoce' ";
    mysqli_query($conn, $Sql);

    $return[] = $id_Timestoce;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function delete_storeDetail($conn)
{
  $id_store = $_POST["id_store"];

    $Sql = "DELETE FROM  store_location  WHERE store_location.id = '$id_store' ";
    mysqli_query($conn, $Sql);

    $return[] = $id_store;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function save_about($conn)
{
  $html = $_POST["html"];
  $html_EN = $_POST["html_EN"];
  $data_imageabout = $_POST["data_imageabout"];

  $Sql_ab = "SELECT
              about.id,
              ROUND((37384 - 1231 * RAND()), 0) AS random_new,
              about.image_about
            FROM
              about
              ORDER BY about.id DESC LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql_ab);
  $row = mysqli_fetch_assoc($meQuery);
  $id=$row['id'];
  $random_new=$row['random_new'];
  $image_about = $row['image_about']==null?"":$row['image_about'];
    
    $Sql = "UPDATE about SET   about = '$html',about_EN = '$html_EN' WHERE about.id = '$id' ";
    mysqli_query($conn, $Sql);


    $iamge1 = $id . "-1" .".png";

    $iamge1_now = $id . "-1_" . $random_new. ".png";

    $iamge1 = $id . "-1". ".png";

    include("gen_thumbnail.php");

    if ($_FILES['imageabout'] != "") {
    if($image_about != ""){
    unlink('../profile/about/' . $image_about);
    }
    copy($_FILES['imageabout']['tmp_name'], '../profile/about/' . $iamge1_now);

    $Sql = "UPDATE about SET about.image_about='$iamge1_now'  WHERE about.id = '$id';";
    mysqli_query($conn, $Sql);

    $cfg_thumb =  (object) array(
    "source" => "../profile/about/" . $iamge1_now,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
    "destination" => "../profile/about/" . $iamge1_now,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
    "width" => 1900,         //  กำหนดความกว้างรูปใหม่
    "height" => 600,       //  กำหนดความสูงรูปใหม่
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
      if ($data_imageabout == "default") {

      if($image_about != ""){
      unlink('../profile/about/' . $image_about);
      }

      $Sql = "UPDATE about SET about.image_about=null  WHERE about.id = '$id';";
      mysqli_query($conn, $Sql);
      }
    }

  $return[] = $id;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function show_about($conn){

    $Sql = "SELECT
              about.id,
              about.image_about,
              about.about,
              about.about_EN
            FROM
              about
              ORDER BY about.id DESC LIMIT 1
            ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
    }


  echo json_encode($return);
  mysqli_close($conn);
  die;
}
