<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$ItemSplit = array();
$MaxSplit = array();
$ParSplit = array();
$TotalSplit = array();
$WeightSplit = array();
$count = 0;
$boolean = false;
	if(
		isset($_GET['DocNo']) &&
		isset($_GET['Weight']) &&
		isset($_GET['ItemCode'])){
		// =======================================================================================
		// Get
		// =======================================================================================
		$DocNo = $_GET['DocNo'];
		$Weight = $_GET['Weight'];
		$ItemCode = $_GET['ItemCode'];
		// =======================================================================================
		// Split with (,)
		// =======================================================================================
		$ItemSplit = explode(",",$ItemCode);
		$WeightSplit = explode(",",$Weight);
		// =======================================================================================
		// Loop for insert detail
		// =======================================================================================
		$Sum = 0;
		for ($i=0; $i < sizeof($ItemSplit); $i++) {
			$Sum += $WeightSplit[$i];
			// var_dump($Sum); die;

		// =======================================================================================
		// Insert Shelfcount Detail
		// =======================================================================================
		$Finddup = "SELECT
								COUNT(dirty_detail.ItemCode) AS Countn
								FROM
								dirty
								INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
								WHERE dirty.DocNo = '$DocNo' AND dirty_detail.ItemCode = '".$ItemSplit[$i]."'";
		$meQuery = mysqli_query($conn,$Finddup);
		while ($Resultfind = mysqli_fetch_assoc($meQuery)) {
			$countfind = $Resultfind['Countn'];
		}
		if($countfind>0){
			$Sqlupdate = "UPDATE dirty_detail SET Weight = ".$WeightSplit[$i]." WHERE DocNo = '$DocNo' AND ItemCode = '".$ItemSplit[$i]."'";
			if(mysqli_query($conn,$Sqlupdate)){
				array_push( $resArray,array('Finish'=> 'true'));
			}else{
				array_push( $resArray,array('Finish'=> 'false'));
			}
		}else{
			$Sqlinsert = "INSERT INTO dirty_detail(
																DocNo,
																ItemCode,
																MaxQty,
																ParQty,
																TotalQty,
																UnitCode,
																Weight,
																IsCancel
																) VALUES (
																'$DocNo',
																'".$ItemSplit[$i]."',
																0,
																0,
																0,
																1,
																".$WeightSplit[$i].",
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
						$Sqlupdate = "UPDATE dirty SET IsStatus = 1,Total = $Sum WHERE DocNo = '$DocNo'";
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
