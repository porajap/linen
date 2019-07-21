<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$ItemSplit = array();
$MaxSplit = array();
$ParSplit = array();
$TotalSplit = array();
$count = 0;
$boolean = false;
	if(
		isset($_GET['DocNo']) &&
		isset($_GET['QtyMax']) &&
		isset($_GET['ParQty']) &&
		isset($_GET['TotalQty']) &&
		isset($_GET['ItemCode'])){

		// =======================================================================================
		// Get
		// =======================================================================================
		$DocNo = $_GET['DocNo'];
		$QtyMax = $_GET['QtyMax'];
		$ParQty = $_GET['ParQty'];
		$TotalQty = $_GET['TotalQty'];
		$ItemCode = $_GET['ItemCode'];
		// =======================================================================================
		// Split with (,)
		// =======================================================================================
		$ItemSplit = explode(",",$ItemCode);
		$MaxSplit = explode(",",$QtyMax);
		$ParSplit = explode(",",$ParQty);
		$TotalSplit = explode(",",$TotalQty);
		// =======================================================================================
		// Loop for insert detail
		// =======================================================================================
		$Sum = 0;
		for ($i=0; $i < sizeof($ItemSplit); $i++) {
		$Sum += $ParSplit[$i];
		// =======================================================================================
		// Insert Shelfcount Detail
		// =======================================================================================
		$Finddup = "SELECT
								COUNT(shelfcount_detail.ItemCode) AS Countn
								FROM
								shelfcount
								INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
								WHERE shelfcount.DocNo = '$DocNo' AND shelfcount_detail.ItemCode = '".$ItemSplit[$i]."'";
		$meQuery = mysqli_query($conn,$Finddup);
		while ($Resultfind = mysqli_fetch_assoc($meQuery)) {
			$countfind = $Resultfind['Countn'];
		}
			if($countfind>0){
				$Sqlupdate = "UPDATE shelfcount_detail SET MaxQty = ".$MaxSplit[$i].",ParQty = ".$ParSplit[$i].",TotalQty = ".$TotalSplit[$i]." WHERE DocNo = '$DocNo' AND ItemCode = '".$ItemSplit[$i]."'";
				if(mysqli_query($conn,$Sqlupdate)){
					array_push( $resArray,array('Finish'=> 'true'));
				}else{
					array_push( $resArray,array('Finish'=> 'false'));
				}
			}else{
				$Sqlinsert = "INSERT INTO shelfcount_detail(
																DocNo,
																ItemCode,
																MaxQty,
																ParQty,
																TotalQty,
																IsCancel
																) VALUES (
																'$DocNo',
																'".$ItemSplit[$i]."',
																".$MaxSplit[$i].",
																".$ParSplit[$i].",
																".$TotalSplit[$i].",
																0
																)";
																// var_dump($Sqlinsert); die;
					if(mysqli_query($conn,$Sqlinsert)){
						$boolean = true;
					}else{
						$boolean = false;
						$count++;
					}
					if($boolean){
						$Sqlupdate = "UPDATE shelfcount SET IsStatus = 1,Total = $Sum WHERE DocNo = '$DocNo'";
						if(!mysqli_query($conn,$Sqlupdate)){
							$count++;
						}
					}else{
						$count++;
					}

				if($count==0){
					array_push( $resArray,array('Finish'=> 'true'));
				}else{
					array_push( $resArray,array('Finish'=> 'false'));
				}
			}
		}

	}else{
		array_push( $resArray,array('Finish'=> 'false'));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
