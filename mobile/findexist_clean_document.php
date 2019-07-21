<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
	if(
		isset($_GET['DeptCode'])){
		// =======================================================================================
		// Get Hospital Detail
		// =======================================================================================
		$DeptCode = $_GET['DeptCode'];
		// =======================================================================================
		// Get Hospital Detail
		// =======================================================================================
		$countn = 0;
		$Sql = "SELECT
						clean.DocNo
						FROM clean
						WHERE clean.IsStatus = 0 AND clean.DepCode = $DeptCode
						ORDER BY clean.DocNo DESC
						LIMIT 1";
		  $meQuery = mysqli_query($conn,$Sql);
		  while ($Result = mysqli_fetch_assoc($meQuery)){
				$DocNo = $Result['DocNo'];
				$countn++;
				$i++;
		}

		if($countn!=0){
			$boolean = false;
			$Sqlfind = "SELECT
						item_stock.RowID,
						item.ItemName,
						item_stock.ItemCode,
						SUM(item_stock.QtyMax) AS QtyMax,
						COALESCE((SELECT
						scd.ParQty
						FROM
						clean
						INNER JOIN clean_detail AS scd ON clean.DocNo = scd.DocNo
						WHERE clean.DocNo = '$DocNo' AND scd.ItemCode = item_stock.ItemCode LIMIT 1),'0') AS ParQty
						FROM
						item_stock
						INNER JOIN item ON item_stock.ItemCode = item.ItemCode
						WHERE item_stock.DepCode = (SELECT clean.DepCode FROM clean WHERE clean.DocNo = '$DocNo' LIMIT 1)
						AND item.ItemName LIKE '%%' AND item_stock.IsStatus = 0 AND QtyMax > 0
						GROUP BY item_stock.ItemCode
						ORDER BY item.ItemName ASC";
						// var_dump($Sqlfind); die;
			$meQuery = mysqli_query($conn,$Sqlfind);
		  while ($Result = mysqli_fetch_assoc($meQuery)){
				$RowID = $Result["RowID"];
				$ItemName = $Result["ItemName"];
				$ItemCode = $Result["ItemCode"];
				$QtyMax = $Result["QtyMax"];
				$ParQty = $Result["ParQty"];
				array_push( $resArray,array('DocNo'=>$DocNo,'RowID'=>$RowID,'ItemName'=>$ItemName,
				'ItemCode'=>$ItemCode,'QtyMax'=>$QtyMax,'ParQty'=>$ParQty));
				$boolean = true;
			}
			if(!$boolean){
				array_push( $resArray,array('Finish'=> 'false'));
			}
		}else{
			array_push( $resArray,array('Finish'=> 'false'));
		}
	}else{
		array_push( $resArray,array('Finish'=> 'false'));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
