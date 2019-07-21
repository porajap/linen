<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$FName = "false";
$chk = false;
	if(
		isset($_GET['username']) &&
		isset($_GET["password"])){

		// =======================================================================================
		// Get
		// =======================================================================================
		$uName = $_GET['username'];
		$pWord = $_GET['password'];
		// =======================================================================================
		// Get Username and Password
		// =======================================================================================
		$Sql = "SELECT CONCAT(IFNULL(employee.FirstName,''),' ',IFNULL(employee.LastName,'')) AS xName,
		users.ID
		FROM users
		INNER JOIN employee ON users.EmpCode = employee.EmpCode
		WHERE users.UserName = '$uName' AND users.`Password` = '$pWord'";
		$meQuery = mysqli_query($conn,$Sql);
		while ($Result = mysqli_fetch_array($meQuery)){
			$FName	=  $Result["xName"];
		  $ID	=  $Result["ID"];
		  $chk = true;
			array_push(
				$resArray,array(
					'Finish'=>$chk,
					'FName'=>$Result["xName"],
					'ID'=>$Result["ID"]
				)
			);
		}
		// =======================================================================================
		// Check Username and Password
		// =======================================================================================
		// if($chk == true){
		// 	array_push( $resArray,array('Finish'=> $chk, 'FName'=> $FName,'ID'=>$ID));
		// }
	}else{
		array_push( $resArray,array('Finish'=> $chk, 'FName'=> $FName,'ID'=>$ID));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
