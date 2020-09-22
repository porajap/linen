<?php
    session_start();
    require '../../connect/connect.php';
    date_default_timezone_set("Asia/Bangkok");
    $count  = 0;
    $countx  = 0;
    $countxx  = 0;
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
            U2.EngName AS EngName2,
            U2.EngLName AS EngLName2,
            U2.EngPerfix AS EngPerfix2
            FROM shelfcount
            LEFT JOIN users AS U2 ON shelfcount.UserID = U2.ID
            INNER JOIN department ON shelfcount.DepCode = department.DepCode
            INNER JOIN site ON department.HptCode = site.HptCode 
            WHERE shelfcount.DocDate='$current' 
            AND NOT shelfcount.IsStatus = 9 
            AND site.HptCode = 'BHQ'
            AND shelfcount.DeliveryTime = 0
            AND shelfcount.ScTime = 0 
            ORDER BY shelfcount.IsStatus ASC , shelfcount.DocNo DESC";
    $meQuery = mysqli_query($conn, $Sql2);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $reurn['Sc'][$countx]['EngName2']         = $Result['EngName2']        ==null?'':$Result['EngName2'];
        $reurn['Sc'][$countx]['EngLName2']        = $Result['EngLName2']       ==null?'':$Result['EngLName2'];
        $reurn['Sc'][$countx]['EngPerfix2']       = $Result['EngPerfix2']      ==null?'':$Result['EngPerfix2'];

        $countx  ++;

    }

    $Sql3 = "SELECT 
    U1.EngName AS EngName3,
    U1.EngLName AS EngLName3,
    U1.EngPerfix AS EngPerfix3
    FROM shelfcount
    LEFT JOIN users AS U1 ON shelfcount.UserID_End = U1.ID
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode 
    WHERE shelfcount.DocDate='$current' 
    AND NOT shelfcount.IsStatus = 9 
    AND site.HptCode = 'BHQ'
    AND shelfcount.DeliveryTime = 0
    AND shelfcount.ScTime = 0 
    ORDER BY shelfcount.IsStatus ASC , shelfcount.DocNo DESC";
$meQuery = mysqli_query($conn, $Sql3);
while ($Result = mysqli_fetch_assoc($meQuery)) {

$reurn['Sc'][$countxx]['EngName3']         = $Result['EngName3']        ==null?'':$Result['EngName3'];
$reurn['Sc'][$countxx]['EngLName3']        = $Result['EngLName3']       ==null?'':$Result['EngLName3'];
$reurn['Sc'][$countxx]['EngPerfix3']       = $Result['EngPerfix3']      ==null?'':$Result['EngPerfix3'];
$countxx  ++;

}



	mysqli_close($conn);
    $reurn['count'] = $count;
    echo json_encode($reurn);
    die;
?>