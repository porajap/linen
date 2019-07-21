<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$chk = false;
	if(
		isset($_GET['DocNo']) &&
		isset($_GET['Keyword'])){

		// =======================================================================================
		// Get
		// =======================================================================================
		$DocNo = $_GET['DocNo'];
		$Keyword = $_GET['Keyword'];
		// =======================================================================================
		// Get Item stock detail
		// =======================================================================================
		$Sql = "SELECT
						item_stock.RowID,
						item.ItemName,
						item_stock.ItemCode,
						item.MaxQty AS QtyMax
						-- SUM(item_stock.QtyMax) AS QtyMax
						FROM
						item_stock
						INNER JOIN item ON item_stock.ItemCode = item.ItemCode
						WHERE item_stock.DepCode = (SELECT shelfcount.DepCode FROM shelfcount WHERE shelfcount.DocNo = '$DocNo' LIMIT 1)
						AND item.ItemName LIKE '%$Keyword%' AND item_stock.IsStatus = 0 AND QtyMax > 0
						GROUP BY item_stock.ItemCode
						ORDER BY item.ItemName ASC";
		  $meQuery = mysqli_query($conn,$Sql);
		  while ($Result = mysqli_fetch_assoc($meQuery)){
			$RowID = $Result["RowID"];
			$ItemName = $Result["ItemName"];
			$ItemCode = $Result["ItemCode"];
			$QtyMax = $Result["QtyMax"];
			array_push( $resArray,array('RowID'=>$RowID,'ItemName'=>$ItemName,
			'ItemCode'=>$ItemCode,'QtyMax'=>$QtyMax));
		}
	}else{
		array_push( $resArray,array('Finish'=> 'false'));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
