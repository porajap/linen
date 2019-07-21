<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
	if(
		isset($_GET['Boolean'])){

		// =======================================================================================
		// Get Hospital Detail
		// =======================================================================================
		$Sql = "SELECT hospital.HptCode,hospital.HptName
		FROM hospital
		WHERE hospital.IsStatus = 0";
		  $meQuery = mysqli_query($conn,$Sql);
		  while ($Result = mysqli_fetch_assoc($meQuery)){
			$HptCode = $Result["HptCode"];
			$HptName = $Result["HptName"];
			array_push( $resArray,array('hptcode'=>$HptCode,'hptname'=>$HptName ));
			$i++;
		}
	}else{
		array_push( $resArray,array('hptcode'=> $chk, 'hptname'=> $FName));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
