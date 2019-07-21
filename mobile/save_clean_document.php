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
		isset($_GET['RefDocNo']) &&
		isset($_GET['ItemCode'])){
			// var_dump($_GET); die;

		// =======================================================================================
		// Get
		// =======================================================================================
		$DocNo = $_GET['DocNo'];
		$Weight = $_GET['Weight'];
		$RefDocNo = $_GET['RefDocNo'];
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

		// =======================================================================================
		// Insert Shelfcount Detail
		// =======================================================================================
		$Finddup = "SELECT
								COUNT(clean_detail.ItemCode) AS Countn
								FROM
								clean
								INNER JOIN clean_detail ON clean.DocNo = clean_detail.DocNo
								WHERE clean.DocNo = '$DocNo' AND clean_detail.ItemCode = '".$ItemSplit[$i]."'";
		$meQuery = mysqli_query($conn,$Finddup);
		while ($Resultfind = mysqli_fetch_assoc($meQuery)) {
			$countfind = $Resultfind['Countn'];
		}
		if($countfind>0){
			$Sqlupdate = "UPDATE clean_detail SET Weight = ".$WeightSplit[$i]." WHERE DocNo = '$DocNo' AND ItemCode = '".$ItemSplit[$i]."'";
			if(mysqli_query($conn,$Sqlupdate)){
				$boolean = true;
			}else{
				$boolean = false;
			}
		}else{
			$Sqlinsert = "INSERT INTO clean_detail(
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
					if(mysqli_query($conn,$Sqlinsert)){
						$boolean = true;
					}else{
						$boolean = false;
						$count++;
					}

			}

		}
		if($boolean){
			$Sqlupdate = "UPDATE clean JOIN dirty SET clean.IsStatus = 1,clean.Total = $Sum,clean.RefDocNo = '$RefDocNo',dirty.IsRef = 1
										WHERE clean.DocNo = '$DocNo'
										AND dirty.DocNo = '$RefDocNo'";
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
	}else{
		array_push( $resArray,array('Finish'=> 'false'));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
