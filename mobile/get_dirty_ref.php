<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$chk = false;
	if(
		isset($_GET['DepCode'])){

		// =======================================================================================
		// Get
		// =======================================================================================
		$DepCode = $_GET['DepCode'];
		// =======================================================================================
		// Get Item stock detail
		// =======================================================================================
		$Sql = "SELECT
						dirty.DocNo
						FROM
						dirty
						WHERE dirty.IsCancel = 0 AND dirty.IsStatus = 1 AND dirty.DepCode = $DepCode
						AND dirty.IsRef = 0
						ORDER BY dirty.DocNo DESC LIMIT 15";
		  $meQuery = mysqli_query($conn,$Sql);
		  while ($Result = mysqli_fetch_assoc($meQuery)){
			$DocNo = $Result["DocNo"];
			array_push( $resArray,array('RowID'=>$DocNo));
		}
	}else{
		array_push( $resArray,array('Finish'=> 'false'));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
