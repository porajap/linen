<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

header("Content-Type: application/vnd.ms-excel"); // ประเภทของไฟล์
header('Content-Disposition: attachment; filename="myexcel.xls"'); //กำหนดชื่อไฟล์
header("Content-Type: application/force-download"); // กำหนดให้ถ้าเปิดหน้านี้ให้ดาวน์โหลดไฟล์
header("Content-Type: application/octet-stream"); 
header("Content-Type: application/download"); // กำหนดให้ถ้าเปิดหน้านี้ให้ดาวน์โหลดไฟล์
header("Content-Transfer-Encoding: binary"); 
header("Content-Length: ".filesize("myexcel.xls"));   

@readfile($filename); 
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $ItemCode = explode(',' , $_SESSION['Excel']['ItemCodeArray']);
  $QtyArray1 = explode(',' , $_SESSION['Excel']['QtyArray1']);
  $QtyArray2 = explode(',' , $_SESSION['Excel']['QtyArray2']);
  $QtyArray3 = explode(',' , $_SESSION['Excel']['QtyArray3']);
  $QtyArray4 = explode(',' , $_SESSION['Excel']['QtyArray4']);


  $count = 0;
  $Sql = "SELECT
    department.DepCode,
    department.DepName,
    tdas_percent.Percent_value
  FROM department
  LEFT JOIN tdas_percent ON tdas_percent.DepCode = department.DepCode
  WHERE department.IsStatus = 0 AND department.HptCode ='$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCodeX[$count]  = $Result['DepCode'];
    $DepName[$count]  = $Result['DepName'];
    $count++;
  }
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel">
 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table>
  <tr>
    <td>แผนก</td>
    <?php foreach($DepName as $key => $value){?>
  	  <td><?php echo $value?></td>
    <?php }?>
    <td>Total Ex.STOCK</td>
    <td>Total PAR</td>
    <td>Total PAR</td>
  </tr>
  <tr>
    <td>1.</td>
    <td>จำนวนเตียงรวม (Total Patient Room)</td>
    <?php foreach($QtyArray1 as $key => $Qty1){?>
  	  <td>
        <?php 
          echo $Qty1;
          $sumQty1 += $Qty1;
        ?>
      </td>
    <?php }?>
    <td><?php echo $$sumQty1?></td>
  </tr>
  <tr>
    <td>2.</td>
    <td>ห้องพักญาติ (Relative Room In VIP)</td>
    <?php foreach($QtyArray2 as $key => $Qty2){?>
  	  <td>
        <?php 
          echo $Qty2;
          $sumQty2 += $Qty2;
        ?>
      </td>
    <?php }?>
    <td><?php echo $$sumQty2?></td>
  </tr>
  <tr>
    <td>3.</td>
    <td>จำนวนผู้ป่วย (AVG Patient Census)</td>
    <?php foreach($QtyArray3 as $key => $Qty3){?>
  	  <td>
        <?php 
          echo $Qty3;
          $sumQty3 += $Qty3;
        ?>
      </td>
    <?php }?>
    <td><?php echo $$sumQty3?></td>
  </tr>
  <tr>
     <td>4.</td>
    <td>จำนวนผู้ป่วยกลับบ้าน (Dis charge plan)</td>
    <?php foreach($QtyArray4 as $key => $Qty4){?>
  	  <td>
        <?php 
          echo $Qty4;
          $sumQty4 += $Qty4;
        ?>
      </td>
    <?php }?>
    <td><?php echo $$sumQty4?></td>
  </tr>
</table>
</body>
</html>
