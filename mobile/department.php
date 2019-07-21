<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$chk = false;
	if(
		isset($_GET['Hospital'])){

		// =======================================================================================
		// Get
		// =======================================================================================
		$Hpt = $_GET['Hospital'];
		// =======================================================================================
		// Get Department Code and Department Name
		// =======================================================================================
		$Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = $Hpt
		  AND department.IsStatus = 0";
		  $meQuery = mysqli_query($conn,$Sql);
		  while ($Result = mysqli_fetch_assoc($meQuery)){
			$DepCode = $Result["DepCode"];
			$DepName = $Result["DepName"];
			array_push( $resArray,array('depcode'=>$DepCode,'depname'=>$DepName ));
			$i++;
		}
	}else{
		array_push( $resArray,array('depcode'=> $chk, 'depname'=> $FName));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
