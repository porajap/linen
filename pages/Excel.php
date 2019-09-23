<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$filename = 'tdas'.date('Y-m-d').'.xls';
header("Content-Type: application/vnd.ms-excel"); // ประเภทของไฟล์
header('Content-Disposition: attachment; filename="'.$filename.'"'); //กำหนดชื่อไฟล์
header("Content-Type: application/force-download"); // กำหนดให้ถ้าเปิดหน้านี้ให้ดาวน์โหลดไฟล์
header("Content-Type: application/octet-stream"); 
header("Content-Type: application/download"); // กำหนดให้ถ้าเปิดหน้านี้ให้ดาวน์โหลดไฟล์
header("Content-Transfer-Encoding: binary"); 
header("Content-Length: ".filesize("'.$filename.'"));   

// @readfile($filename); 
  $HptCode = $_SESSION['HptCode'];
  $DepCode = $_SESSION['DepCode'];
  $ItemCode = explode(',' , $_SESSION['Excel']['ItemCodeArray']);
  $QtyArray1 = explode(',' , $_SESSION['Excel']['QtyArray1']);
  $QtyArray2 = explode(',' , $_SESSION['Excel']['QtyArray2']);
  $QtyArray3 = explode(',' , $_SESSION['Excel']['QtyArray3']);
  $QtyArray4 = explode(',' , $_SESSION['Excel']['QtyArray4']);
  $PercentArray = explode(',' , $_SESSION['Excel']['PercentArray']);
  $changeArray = explode(',' , $_SESSION['Excel']['changeArray']);
  $CalArray = explode(',' , $_SESSION['Excel']['CalArray']);
  $AllSum = explode(',' , $_SESSION['Excel']['AllSum']);
  $TotalArray = explode(',' , $_SESSION['Excel']['TotalArray']);
  $Total_par2 = $_SESSION['Excel']['Total_par2'];

  $Qty[0] = explode(',', $_SESSION['Excel']['QtyArray1']);
  $Qty[1] = explode(',', $_SESSION['Excel']['QtyArray2']);
  $Qty[2] = explode(',', $_SESSION['Excel']['QtyArray3']);
  $Qty[3] = explode(',', $_SESSION['Excel']['QtyArray4']);

  // unset($_SESSION['Excel']);
  #---------------------------------------------------------------------------
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

  foreach($ItemCode AS $key => $value){
    $Sql = "SELECT item_main_category.MainCategoryName, item.ItemName, item.ItemCode  
      FROM item 
      INNER JOIN item_category ON item_category.CategoryCode = item.CategoryCode
      INNER JOIN item_main_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
      WHERE item.ItemCode = '$value'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Item[$key]['MainCategoryName'] = $Result['MainCategoryName'];
        $Item[$key]['ItemName'] = $Result['ItemName'];
      }
  }
 
  // echo '<pre>';
  // print_r($Qty);
  // echo '</pre>';

  $DepLoop = $count;
  $ItemLoop = sizeof($ItemCode, 0);
  $TypeLoop = 4;
  // for($d = 0; $d<$DepLoop; $d++){
  //   for($t = 0; $t<$TypeLoop; $t++){
  //     foreach($Qty[$t] AS $key => $value){
  //       if($d==$t){
  //         $SumCol[$d] += $Qty[$key][$d];
  //       }
  //     }
  //   }
  // }

  for($t = 0; $t<$TypeLoop; $t++){
    for($d = 0; $d<$DepLoop; $d++){
      foreach($Qty[$t] AS $key => $value){
        if($key==$t){
          $SumCol[$d] += $Qty[$key][$d];
        }
      }
    }
  }
  // echo 'Dep: '.$DepLoop;
  // echo '<br>Key: '.$key;
  // echo '<pre>';
  // print_r($SumCol);
  // echo '</pre>';
?>

<!-- <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"> -->
 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
  <tr>
  <td></td>
  <td></td>
  <td></td>
    <td align="center" valign="middle"><strong>แผนก</strong></td>
    <td align="center" valign="middle"><strong>Change<br>(ความถึ่ในการเปลี่ยน)</strong></td>
    <?php foreach($DepName as $key => $value){?>
  	  <td align="center" valign="middle"><strong><?php echo $value?></strong></td>
    <?php }?>
    <td align="center" valign="middle"><strong>Total</strong> </td>
    <td align="center" valign="middle"><strong>Total</strong> </td>
    <td align="center" valign="middle"><strong>Total</strong> </td>
  </tr>

  <tr>
  <td></td>
  <td></td>
  <td></td>
    <td align="center" valign="middle"><strong>COST CENTER</strong></td>
    <?php foreach($DepName as $key => $value){?>
  	<td></td>
    <?php }?>
    <td></td>
    <td align="center" valign="middle"><strong>Ex.STOCK</strong></td>
    <td align="center" valign="middle"><strong>Par</strong></td>
    <td align="center" valign="middle"><strong>Par</strong></td>
  </tr>


  <tr>
  <td></td>
  <td></td>
  <td></td>
    <td align="center" valign="middle"><strong>NAME</strong></td>
    <?php foreach($DepName as $key => $value){?>
  	<td></td>
    <?php }?>
    <td></td>
    <td></td>
    <td align="center">1</td>
    <td align="center"><?php echo $Total_par2 ?></td>
  </tr>

  
  <tr>
    <td></td>
    <td></td>
    <td align="center">1.</td>
    <td>จำนวนเตียงรวม (Total Patient Room)</td>
    <td></td>
    <?php foreach($QtyArray1 as $key => $Qty1){?>
    <td align="center">
        <?php 
          echo $Qty1==null?0:$Qty1;
          $sumQty1 += $Qty1;
        ?>
      </td>
    <?php }?>
    <td align="center"><?php echo $sumQty1?></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="center">2.</td>
    <td>ห้องพักญาติ (Relative Room In VIP)</td>
    <td></td>
    <?php foreach($QtyArray2 as $key => $Qty2){?>
  	  <td align="center">
        <?php 
          echo $Qty2==null?0:$Qty2;
          $sumQty2 += $Qty2;
        ?>
      </td>
    <?php }?>
    <td align="center"><?php echo $sumQty2?></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="center">3.</td>
    <td>จำนวนผู้ป่วย (AVG Patient Census)</td>
    <td></td>
    <?php foreach($QtyArray3 as $key => $Qty3){?>
  	  <td align="center">
        <?php 
          echo $Qty3==null?0:$Qty3;
          $sumQty3 += $Qty3;
        ?>
      </td>
    <?php }?>
    <td align="center"><?php echo $sumQty3?></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
      <td></td>
      <td></td>
      <td align="center">4.</td>
      <td>จำนวนผู้ป่วยกลับบ้าน (Dis charge plan)</td>
      <td></td>
    <?php foreach($QtyArray4 as $key => $Qty4){?>
  	  <td align="center"> 
        <?php 
          echo $Qty4==null?0:$Qty4;
          $sumQty4 += $Qty4;
        ?>
      </td>
    <?php }?>
    <td align="center"><?php echo $sumQty4?></td>
    <td></td>
    <td></td>
  </tr>

  <tr>
     <td></td>
     <td align="center" valign="middle"><strong>หมวดหมู่</strong></td>
     <td></td>
     <td align="center"><strong>รายการ</strong></td>
     <td></td>
     <?php foreach($PercentArray as $key => $PercentArray1){?>
  	  <td align="center">
        <?php 
          echo $PercentArray1==null?'0%':$PercentArray1.'%' ;
        ?>
      </td>
    <?php }?>
      <td></td>
      <td></td>
      <td></td>
  </tr>

<?php foreach($Item as $key => $value){?>
    <tr>
      <td align="center"><?php echo ($key+1).'.'; ?></td>
      <td><?php echo $Item[$key]['MainCategoryName']; ?></td>
      <td align="center"><?php echo $AllSum[$key]==''?0:$AllSum[$key]; ?></td>
      <td><?php echo $Item[$key]['ItemName']; ?> </td>
      <td align="center"><?php  echo $changeArray[$key]==null?0:$changeArray[$key] ;  ?></td>
      <?php 
          for($d = 0; $d<$DepLoop; $d++){
            if($AllSum[$key]==0||$AllSum[$key]==''){
              $result = number_format(((($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$key]) + $SumCol[$d])-$Qty[1][$d],2);
            }else{
              $result = number_format((($SumCol[$d]*$PercentArray[$d]/100)*$changeArray[$key]) + $SumCol[$d],2);
            } ?>
            <td align="right"><?php echo $result; ?></td>
          <?php  }
      ?>
        <td> </td>
        <td align="right"><?php echo number_format($TotalArray[$key],2);?></td>
        <td align="right"><?php echo number_format($CalArray[$key],2);?></td>
    </tr>
<?php }?>

</table>
</body>
</html>
