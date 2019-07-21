<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'connect/connect.php';
$resArray = array();
$chk = false;
	if(
		isset($_GET['HptCode']) &&
    isset($_GET['DeptCode']) &&
    isset($_GET['Userid'])){

		// =======================================================================================
		// Get
		// =======================================================================================
    $HptCode = $_GET['HptCode'];
    $DeptCode = $_GET['DeptCode'];
		$Userid = $_GET['Userid'];
    $boolean = false;
    $count = false;
		// =======================================================================================
		// Get Prefix and Suffix for DocNo
		// =======================================================================================
    $Sql = "SELECT CONCAT('DT',lpad($HptCode, 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
    LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
    CURRENT_TIME() AS RecNow
    FROM dirty
    WHERE DocNo Like CONCAT('DT',lpad($HptCode, 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
    ORDER BY DocNo DESC LIMIT 1";

    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)){
      $DocNo = $Result['DocNo'];
      $count = true;
    }

    if($count==true){
		// =======================================================================================
		// If DocNo can be Genarate then Insert
		// =======================================================================================
        $Sql = "INSERT INTO dirty(
                DocNo,
                DocDate,
                DepCode,
                IsCancel,
                Modify_Code,
                Modify_Date
                )VALUES(
                '$DocNo',
                NOW(),
                $DeptCode,
                0,
                $Userid,
                NOW()
                )";
        if(mysqli_query($conn,$Sql)){
          $boolean = true;
        }else{
          $boolean = false;
        }
        array_push( $resArray,array('Finish'=> $boolean, 'DocNo'=> $DocNo));
    }else{
        $boolean = false;
    }
	}else{
		array_push( $resArray,array('Finish'=> $boolean, 'DocNo'=> $DocNo));
	}
	echo json_encode(array("result"=>$resArray));
	mysqli_close($conn);
?>
