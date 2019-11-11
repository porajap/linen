<?php
    session_start();
    require '../connect/connect.php';
    date_default_timezone_set("Asia/Bangkok");
    $count  = 0;
    $current = date('Y-m-d');
    $HptCode = $_SESSION['HptCode'];
	$Sql = "SELECT 
        site.HptName,
        department.DepName,
        shelfcount.DocNo,
        DATE_FORMAT(shelfcount.DocDate, '%d-%m-%Y') AS DocDate,
        shelfcount.IsStatus,
    site.HptCode
    FROM shelfcount
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode WHERE shelfcount.DocDate='$current' AND shelfcount.IsStatus = 0 AND site.HptCode = '$HptCode'
    ORDER BY shelfcount.DocNo DESC";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $reurn['Sc'][$count]['DocNo'] = $Result['DocNo'];
        $reurn['Sc'][$count]['DepName'] = $Result['DepName'];
        $reurn['Sc'][$count]['DocDate'] = $Result['DocDate'];
        $count++;
    }

	mysqli_close($conn);
    $reurn['count'] = $count;
    echo json_encode($reurn);
    die;
?>