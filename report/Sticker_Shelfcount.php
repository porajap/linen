<?php

date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
require '../connect/connect.php';

$DocNo = $_GET['DocNo'];
$lang = $_GET['lang'];
$ItemCode = $_GET['ItemCode'];
$TotalQty = $_GET['TotalQty'];
$sendQty = $_GET['sendQty'];
$UserID = $_SESSION['PmID'];
$count = 0;

// $Sql = "SELECT
//   shelfcount_detail.ItemCode,
//   item.ItemName,
//   item_unit.UnitName,
//   shelfcount_detail.ParQty,
//   shelfcount_detail.CcQty,
//   shelfcount_detail.TotalQty,
//   users.FName,
// 	(
// 		SELECT users.FName FROM users WHERE ID = $UserID
// 	) AS UserC
//   FROM item
//   INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
//   INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
//   INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
//   INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
// 	INNER JOIN users ON users.ID = shelfcount.Modify_Code
//   WHERE shelfcount_detail.DocNo = '$DocNo' AND shelfcount_detail.ItemCode = '$ItemCode'
//   GROUP BY item.ItemCode
//   ORDER BY item.ItemName ASC ";
// $meQuery = mysqli_query($conn, $Sql);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $DepCode = $Result['DepCode'];
// }

// echo '<br>DocNo : '.$DocNo ;
// echo '<br>ItemCode : '.$ItemCode ;
// echo '<br>TotalQty : '.$TotalQty ;
// echo '<br>sendQty : '.$sendQty ;

// echo "<br>";

$loop1 = floor($TotalQty/$sendQty);
for($i=1;$i<=$loop1;$i++){
  echo '<br>'.$i.'. ItemCode : '. $ItemCode;
  echo '<br>TotalQty : '. $sendQty;
  echo '<br><hr>';
}
echo "<br>";
$loop2 = $loop1*$sendQty;
if($loop2<$TotalQty){
  $lass = $TotalQty - $loop2;
  echo '<br>'.$i.'. ItemCode : '. $ItemCode;
  echo '<br>TotalQty : '. $lass;
  echo '<br><hr>';
}



