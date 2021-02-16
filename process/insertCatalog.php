<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if ($Userid == "") {
    header("location:../index.html");
}

$codeColorArray = $_POST['codeColorArray'];
$supplierArray = $_POST['supplierArray'];
$itemCode = $_POST['ItemCode'];
$data_imageOne = $_POST['data_imageOne'];
$data_imageTwo = $_POST['data_imageTwo'];
$data_imageThree = $_POST['data_imageThree'];

// echo $_FILES['imageOne']['name'];
// echo $_FILES['imageTwo']['name'];
// echo $_FILES['imageThree']['name'];

$imageOne = explode('.', $_FILES['imageOne']);
$imageTwo = explode('.', $_FILES['imageTwo']);
$imageThree = explode('.', $_FILES['imageThree']);

$iamge1 = $itemCode."-1" . '.' . $imageOne[1]."png";
$iamge2 = $itemCode."-2" . '.' . $imageTwo[1]."png";
$iamge3 = $itemCode."-3" . '.' . $imageThree[1]."png";

if ($_FILES['imageOne'] != ""){
  unlink($_FILES['imageOne']['tmp_name'], '../profile/catalog/' . $iamge1);
  copy($_FILES['imageOne']['tmp_name'], '../profile/catalog/' . $iamge1);

  $Sql = "UPDATE item SET item.imageOne='$iamge1'  WHERE item.ItemCode = '$itemCode';";
  mysqli_query($conn, $Sql);
}else{
  if($data_imageOne == "default"){
    $Sql = "UPDATE item SET item.imageOne=null  WHERE item.ItemCode = '$itemCode';";
    mysqli_query($conn, $Sql);
  }
}


if ($_FILES['imageTwo'] != ""){
  unlink($_FILES['imageTwo']['tmp_name'], '../profile/catalog/' . $iamge2);
  copy($_FILES['imageTwo']['tmp_name'], '../profile/catalog/' . $iamge2);

  $Sql = "UPDATE item SET item.imageTwo='$iamge2' WHERE item.ItemCode = '$itemCode';";
  mysqli_query($conn, $Sql);
}else{
  if($data_imageTwo == "default"){
    $Sql = "UPDATE item SET item.imageTwo=null  WHERE item.ItemCode = '$itemCode';";
    mysqli_query($conn, $Sql);
  }

}


if ($_FILES['imageThree'] != ""){
  unlink($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3);
  copy($_FILES['imageThree']['tmp_name'], '../profile/catalog/' . $iamge3);

  $Sql = "UPDATE item SET item.imageThree='$iamge3' WHERE item.ItemCode = '$itemCode';";
  mysqli_query($conn, $Sql);
}else{
  if($data_imageThree == "default"){
    $Sql = "UPDATE item SET item.imageThree=null  WHERE item.ItemCode = '$itemCode';";
    mysqli_query($conn, $Sql);
  }

}




include("gen_thumbnail.php");

$cfg_thumb=  (object) array(
  "source"=>"../profile/catalog/".$iamge1,                // ตำแหน่งและชื่อไฟล์ต้นฉบับ
  "destination"=>"../profile/catalog/".$iamge1,   // ตำแแหน่งและชื่อไฟล์ที่สร้างใหม่ ถ้าเลือกสร้างเป็นไฟล์ใหม่
  "width"=>500,         //  กำหนดความกว้างรูปใหม่
  "height"=>500,       //  กำหนดความสูงรูปใหม่
  "background"=>"#fff",    // กำหนดสีพื้นหลังรูปใหม่ (#FF0000) ถ้าไม่กำหนดและ เป็น gif หรือ png จะแสดงเป็นโปร่งใส
  "output"=>"",        //  กำหนดนามสกุลไฟล์ใหม่ jpg | gif หรือ png ถ้าไม่กำหนด จะใช้ค่าเริ่มต้นจากต้นฉบับ
  "show"=>0,           //  แสดงเป็นรูปภาพ หรือสร้างเป็นไฟล์ 0=สร้างเป็นไฟล์ | 1=แสดงเป็นรูปภาพ
  "crop"=>1                //  กำหนด crop หรือ ไม่ 0=crop | 1=crop
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





if(mysqli_query($conn, $Sql)){
  $result = 1;
}else{
  $result = 0;
}



echo json_encode($result);
mysqli_close($conn);


?>