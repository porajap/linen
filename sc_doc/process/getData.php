<?php
    session_start();
    require '../../connect/connect.php';
    date_default_timezone_set("Asia/Bangkok");
    $count  = 0;
    $current = date('Y-m-d');
    $HptCode = $_SESSION['HptCode'];
	$Sql = "SELECT 
    site.HptName,
    department.DepName,
    shelfcount.DocNo,
    DATE_FORMAT(shelfcount.DocDate, '%d-%m-%Y') AS DocDate,
    TIME(shelfcount.ScStartTime) 	AS ScStartTime,
    TIME(shelfcount.ScEndTime) 		AS ScEndTime, 
    TIME(shelfcount.PkStartTime) 	AS PkStartTime,
    TIME(shelfcount.PkEndTime) 		AS PkEndTime,
    TIME(shelfcount.DvStartTime) 	AS DvStartTime,
    TIME(shelfcount.DvEndTime) 		AS DvEndTime,
    shelfcount.IsStatus
FROM shelfcount
INNER JOIN department ON shelfcount.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode 
WHERE shelfcount.DocDate='$current' 
AND NOT shelfcount.IsStatus = 4 
AND site.HptCode = '$HptCode' 
AND shelfcount.DeliveryTime = 0
AND shelfcount.ScTime = 0 
ORDER BY shelfcount.DocNo DESC";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $reurn['Sc'][$count]['DocNo']       = $Result['DocNo'];
        $reurn['Sc'][$count]['DepName']     = $Result['DepName'];
        $reurn['Sc'][$count]['IsStatus']     = $Result['IsStatus'];
        $reurn['Sc'][$count]['ScStartTime'] = $Result['ScStartTime']    ==null?'Not arrive':$Result['ScStartTime'];
        $reurn['Sc'][$count]['ScEndTime']   = $Result['ScEndTime']      ==null?'Not arrive':$Result['ScEndTime'];
        $reurn['Sc'][$count]['PkStartTime'] = $Result['PkStartTime']    ==null?'Not arrive':$Result['PkStartTime'];
        $reurn['Sc'][$count]['PkEndTime']   = $Result['PkEndTime']      ==null?'Not arrive':$Result['PkEndTime'];
        $reurn['Sc'][$count]['DvStartTime'] = $Result['DvStartTime']    ==null?'Not arrive':$Result['DvStartTime'];
        $reurn['Sc'][$count]['DvEndTime']   = $Result['DvEndTime']      ==null?'Not arrive':$Result['DvEndTime'];

        $count++;
    }

	mysqli_close($conn);
    $reurn['count'] = $count;
    echo json_encode($reurn);
    die;
?>