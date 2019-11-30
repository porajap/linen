<?php
    session_start();
    require '../../connect/connect.php';
    date_default_timezone_set("Asia/Bangkok");
    $count  = 0;
    $countx  = 0;
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
    shelfcount.IsStatus,
	users.EngName,
    users.EngLName,
    users.EngPerfix
FROM shelfcount
LEFT JOIN users ON shelfcount.Modify_Code = users.ID
INNER JOIN department ON shelfcount.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode 
WHERE shelfcount.DocDate='$current' 
AND NOT shelfcount.IsStatus = 9 
AND site.HptCode = 'BHQ'
AND shelfcount.DeliveryTime = 0
AND shelfcount.ScTime = 0 
ORDER BY shelfcount.IsStatus ASC , shelfcount.DocNo DESC";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $reurn['Sc'][$count]['EngName']         = $Result['EngName']        ==null?'':$Result['EngName'];
        $reurn['Sc'][$count]['EngLName']        = $Result['EngLName']       ==null?'':$Result['EngLName'];
        $reurn['Sc'][$count]['EngPerfix']       = $Result['EngPerfix']      ==null?'':$Result['EngPerfix'];
        $reurn['Sc'][$count]['DocNo']           = $Result['DocNo'];
        $reurn['Sc'][$count]['DepName']         = $Result['DepName'];
        $reurn['Sc'][$count]['IsStatus']        = $Result['IsStatus'];
        $reurn['Sc'][$count]['ScStartTime']     = $Result['ScStartTime']    ==null?'':$Result['ScStartTime'];
        $reurn['Sc'][$count]['ScEndTime']       = $Result['ScEndTime']      ==null?'':$Result['ScEndTime'];
        $reurn['Sc'][$count]['PkStartTime']     = $Result['PkStartTime']    ==null?'':$Result['PkStartTime'];
        $reurn['Sc'][$count]['PkEndTime']       = $Result['PkEndTime']      ==null?'':$Result['PkEndTime'];
        $reurn['Sc'][$count]['DvStartTime']     = $Result['DvStartTime']    ==null?'':$Result['DvStartTime'];
        $reurn['Sc'][$count]['DvEndTime']       = $Result['DvEndTime']      ==null?'':$Result['DvEndTime'];

        $count++;
    }
	$Sql2 = "SELECT 
	users.EngName AS EngName2,
    users.EngLName AS EngLName2,
    users.EngPerfix AS EngPerfix2
FROM shelfcount
LEFT JOIN users ON shelfcount.UserID = users.ID
INNER JOIN department ON shelfcount.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode 
WHERE shelfcount.DocDate='$current' 
AND NOT shelfcount.IsStatus = 9 
AND site.HptCode = 'BHQ'
AND shelfcount.DeliveryTime = 0
AND shelfcount.ScTime = 0 
ORDER BY shelfcount.DocNo DESC";
    $meQuery = mysqli_query($conn, $Sql2);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $reurn['Sc'][$countx]['EngName2']         = $Result['EngName2']        ==null?'':$Result['EngName2'];
        $reurn['Sc'][$countx]['EngLName2']        = $Result['EngLName2']       ==null?'':$Result['EngLName2'];
        $reurn['Sc'][$countx]['EngPerfix2']       = $Result['EngPerfix2']      ==null?'':$Result['EngPerfix2'];

        $countx  ++;

    }





	mysqli_close($conn);
    $reurn['count'] = $count;
    echo json_encode($reurn);
    die;
?>