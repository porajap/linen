<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function GetHospital($conn, $DATA)
{
    $count = 0;
    $Sql = "SELECT    *
			    FROM      site
          WHERE     IsStatus = 0";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['HospitalName'] = $Result['HptName'];
        $return[$count]['HospitalCode'] = $Result['HptCode'];
        $count++;
    }

    if ($count > 0) {
        $return['status'] = "success";
        $return['form'] = "get_hospital";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    } else {
        $return['status'] = "notfound";
        $return['msg'] = "notfound";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function get_dep($conn, $DATA)
{
    $count = 0;
    $hpt = $DATA['hpt'];
    $Sql = "SELECT *
            FROM department
            WHERE HptCode ='$hpt'
            AND IsStatus = 1";
    $return['Sql'] = $Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DepName'] = $Result['DepName'];
        $return[$count]['DepCode'] = $Result['DpeCode'];
        $count++;
    }

    if ($count > 0) {
        $return['status'] = "success";
        $return['form'] = "get_dep";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    } else {
        $return['status'] = "notfound";
        $return['msg'] = "notfound";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function get_dirty_doc($conn, $DATA)
{
    $count = 0;
    $From = $DATA['From'];
    $DocNo = $DATA['DocNo'];
    $hpt = $DATA['hpt'];
    $dep = $DATA['dep'];
    $date = $DATA['date'];
    if ($hpt == "All" || $hpt == null) {
        $Sql = "SELECT * 
                FROM  (
                            SELECT process.DocNo,
                            dirty.IsProcess,
                            IFNULL(DATE_FORMAT(dirty.ReceiveDate, '%T'),'-') AS Receivetime,
                            IFNULL(DATE_FORMAT(WashStartTime, '%d/%M/%Y'),'-') AS Wash,
                            IFNULL(DATE_FORMAT(WashStartTime, '%T'),'-') AS WashStartTime,
                            IFNULL(DATE_FORMAT(WashEndTime, '%T'),'-') AS WashEndTime,
                            IFNULL(TIMEDIFF(WashEndTime,WashStartTime),'-') AS WashDiff,
                            IFNULL(DATE_FORMAT(PackStartTime, '%d/%M/%Y'),'-') AS Pack,
                            IFNULL(DATE_FORMAT(PackStartTime, '%T'),'-') AS PackStartTime,
                            IFNULL(DATE_FORMAT(PackEndTime, '%T'),'-') AS PackEndTime,
                            IFNULL(TIMEDIFF(PackEndTime,PackStartTime),'-') AS PackDiff,
                            IFNULL(DATE_FORMAT(SendStartTime, '%d/%M/%Y'),'-') AS Send,
                            IFNULL(DATE_FORMAT(SendStartTime, '%T'),'-') AS SendStartTime,
                            IFNULL(DATE_FORMAT(SendEndTime, '%T'),'-') AS SendEndTime,
                            IFNULL(TIMEDIFF(SendEndTime,SendStartTime),'-') AS sendDiff
                            FROM process,dirty
                            WHERE dirty.DocNo LIKE '%$DocNo%'
                            AND dirty.DocNo = process.DocNo
                            AND dirty.ReceiveDate LIKE '%$date%'
                        UNION ALL
                            SELECT process.DocNo,
                            rewash.IsProcess,
                            IFNULL(DATE_FORMAT(rewash.ReceiveDate, '%T'),'-') AS Receivetime,
                            IFNULL(DATE_FORMAT(WashStartTime, '%d/%M/%Y'),'-') AS Wash,
                            IFNULL(DATE_FORMAT(WashStartTime, '%T'),'-') AS WashStartTime,
                            IFNULL(DATE_FORMAT(WashEndTime, '%T'),'-') AS WashEndTime,
                            IFNULL(TIMEDIFF(WashEndTime,WashStartTime),'-') AS WashDiff,
                            IFNULL(DATE_FORMAT(PackStartTime, '%d/%M/%Y'),'-') AS Pack,
                            IFNULL(DATE_FORMAT(PackStartTime, '%T'),'-') AS PackStartTime,
                            IFNULL(DATE_FORMAT(PackEndTime, '%T'),'-') AS PackEndTime,
                            IFNULL(TIMEDIFF(PackEndTime,PackStartTime),'-') AS PackDiff,
                            IFNULL(DATE_FORMAT(SendStartTime, '%d/%M/%Y'),'-') AS Send,
                            IFNULL(DATE_FORMAT(SendStartTime, '%T'),'-') AS SendStartTime,
                            IFNULL(DATE_FORMAT(SendEndTime, '%T'),'-') AS SendEndTime,
                            IFNULL(TIMEDIFF(SendEndTime,SendStartTime),'-') AS sendDiff
                            FROM process,rewash
                            WHERE rewash.DocNo LIKE '%$DocNo%'
                            AND rewash.DocNo = process.DocNo
                            AND rewash.ReceiveDate LIKE '%$date%'
                        ) A
                        ORDER BY ReceiveTime";
    } else if ($dep == "All") {
        $Sql = "SELECT * 
                FROM  (
                            SELECT process.DocNo,
                            dirty.IsProcess,
                            IFNULL(DATE_FORMAT(dirty.ReceiveDate, '%T'),'-') AS Receivetime,
                            IFNULL(DATE_FORMAT(WashStartTime, '%d/%M/%Y'),'-') AS Wash,
                            IFNULL(DATE_FORMAT(WashStartTime, '%T'),'-') AS WashStartTime,
                            IFNULL(DATE_FORMAT(WashEndTime, '%T'),'-') AS WashEndTime,
                            IFNULL(TIMEDIFF(WashEndTime,WashStartTime),'-') AS WashDiff,
                            IFNULL(DATE_FORMAT(PackStartTime, '%d/%M/%Y'),'-') AS Pack,
                            IFNULL(DATE_FORMAT(PackStartTime, '%T'),'-') AS PackStartTime,
                            IFNULL(DATE_FORMAT(PackEndTime, '%T'),'-') AS PackEndTime,
                            IFNULL(TIMEDIFF(PackEndTime,PackStartTime),'-') AS PackDiff,
                            IFNULL(DATE_FORMAT(SendStartTime, '%d/%M/%Y'),'-') AS Send,
                            IFNULL(DATE_FORMAT(SendStartTime, '%T'),'-') AS SendStartTime,
                            IFNULL(DATE_FORMAT(SendEndTime, '%T'),'-') AS SendEndTime,
                            IFNULL(TIMEDIFF(SendEndTime,SendStartTime),'-') AS sendDiff
                            FROM process,dirty,department
                            WHERE dirty.DocNo LIKE '%$DocNo%'
                            AND dirty.DocNo = process.DocNo
                            AND dirty.DepCode = department.DepCode
                            AND department.HptCode = '$hpt'
                            AND dirty.ReceiveDate LIKE '%$date%'
                        UNION ALL
                            SELECT process.DocNo,
                            rewash.IsProcess,
                            IFNULL(DATE_FORMAT(rewash.ReceiveDate, '%T'),'-') AS Receivetime,
                            IFNULL(DATE_FORMAT(WashStartTime, '%d/%M/%Y'),'-') AS Wash,
                            IFNULL(DATE_FORMAT(WashStartTime, '%T'),'-') AS WashStartTime,
                            IFNULL(DATE_FORMAT(WashEndTime, '%T'),'-') AS WashEndTime,
                            IFNULL(TIMEDIFF(WashEndTime,WashStartTime),'-') AS WashDiff,
                            IFNULL(DATE_FORMAT(PackStartTime, '%d/%M/%Y'),'-') AS Pack,
                            IFNULL(DATE_FORMAT(PackStartTime, '%T'),'-') AS PackStartTime,
                            IFNULL(DATE_FORMAT(PackEndTime, '%T'),'-') AS PackEndTime,
                            IFNULL(TIMEDIFF(PackEndTime,PackStartTime),'-') AS PackDiff,
                            IFNULL(DATE_FORMAT(SendStartTime, '%d/%M/%Y'),'-') AS Send,
                            IFNULL(DATE_FORMAT(SendStartTime, '%T'),'-') AS SendStartTime,
                            IFNULL(DATE_FORMAT(SendEndTime, '%T'),'-') AS SendEndTime,
                            IFNULL(TIMEDIFF(SendEndTime,SendStartTime),'-') AS sendDiff
                            FROM process,rewash,department
                            WHERE rewash.DocNo LIKE '%$DocNo%'
                            AND rewash.DocNo = process.DocNo
                            AND rewash.DepCode = department.DepCode
                            AND department.HptCode = '$hpt'
                            AND rewash.ReceiveDate LIKE '%$date%'
                    ) A
                ORDER BY ReceiveTime";
    } else {
        $Sql = "SELECT * 
                FROM  (
                            SELECT process.DocNo,
                            dirty.IsProcess,
                            IFNULL(DATE_FORMAT(dirty.ReceiveDate, '%T'),'-') AS Receivetime,
                            IFNULL(DATE_FORMAT(WashStartTime, '%d/%M/%Y'),'-') AS Wash,
                            IFNULL(DATE_FORMAT(WashStartTime, '%T'),'-') AS WashStartTime,
                            IFNULL(DATE_FORMAT(WashEndTime, '%T'),'-') AS WashEndTime,
                            IFNULL(TIMEDIFF(WashEndTime,WashStartTime),'-') AS WashDiff,
                            IFNULL(DATE_FORMAT(PackStartTime, '%d/%M/%Y'),'-') AS Pack,
                            IFNULL(DATE_FORMAT(PackStartTime, '%T'),'-') AS PackStartTime,
                            IFNULL(DATE_FORMAT(PackEndTime, '%T'),'-') AS PackEndTime,
                            IFNULL(TIMEDIFF(PackEndTime,PackStartTime),'-') AS PackDiff,
                            IFNULL(DATE_FORMAT(SendStartTime, '%d/%M/%Y'),'-') AS Send,
                            IFNULL(DATE_FORMAT(SendStartTime, '%T'),'-') AS SendStartTime,
                            IFNULL(DATE_FORMAT(SendEndTime, '%T'),'-') AS SendEndTime,
                            IFNULL(TIMEDIFF(SendEndTime,SendStartTime),'-') AS sendDiff
                            FROM process,dirty
                            WHERE dirty.DocNo LIKE '%$DocNo%'
                            AND dirty.DocNo = process.DocNo
                            AND dirty.DepCode = $dep
                            AND dirty.ReceiveDate LIKE '%$date%'
                        UNION ALL
                            SELECT process.DocNo,
                            rewash.IsProcess,
                            IFNULL(DATE_FORMAT(rewash.ReceiveDate, '%T'),'-') AS Receivetime,
                            IFNULL(DATE_FORMAT(WashStartTime, '%d/%M/%Y'),'-') AS Wash,
                            IFNULL(DATE_FORMAT(WashStartTime, '%T'),'-') AS WashStartTime,
                            IFNULL(DATE_FORMAT(WashEndTime, '%T'),'-') AS WashEndTime,
                            IFNULL(TIMEDIFF(WashEndTime,WashStartTime),'-') AS WashDiff,
                            IFNULL(DATE_FORMAT(PackStartTime, '%d/%M/%Y'),'-') AS Pack,
                            IFNULL(DATE_FORMAT(PackStartTime, '%T'),'-') AS PackStartTime,
                            IFNULL(DATE_FORMAT(PackEndTime, '%T'),'-') AS PackEndTime,
                            IFNULL(TIMEDIFF(PackEndTime,PackStartTime),'-') AS PackDiff,
                            IFNULL(DATE_FORMAT(SendStartTime, '%d/%M/%Y'),'-') AS Send,
                            IFNULL(DATE_FORMAT(SendStartTime, '%T'),'-') AS SendStartTime,
                            IFNULL(DATE_FORMAT(SendEndTime, '%T'),'-') AS SendEndTime,
                            IFNULL(TIMEDIFF(SendEndTime,SendStartTime),'-') AS sendDiff
                            FROM process,rewash
                            WHERE rewash.DocNo LIKE '%$DocNo%'
                            AND rewash.DocNo = process.DocNo
                            AND rewash.DepCode = $dep
                            AND rewash.ReceiveDate LIKE '%$date%'
                    ) A
                ORDER BY ReceiveTime";
    }

    $return['Sql'] = $Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DocNo'] = $Result['DocNo'];
        $return[$count]['IsProcess'] = $Result['IsProcess'];
        $return[$count]['Receivetime'] = $Result['Receivetime'];
        $return[$count]['Wash'] = $Result['Wash'];
        $return[$count]['WashDiff'] = $Result['WashDiff'];
        $return[$count]['WashStartTime'] = $Result['WashStartTime'];
        $return[$count]['WashEndTime'] = $Result['WashEndTime'];
        
        $return[$count]['Pack'] = $Result['Pack'];
        $return[$count]['PackDiff'] = $Result['PackDiff'];
        $return[$count]['PackStartTime'] = $Result['PackStartTime'];
        $return[$count]['PackEndTime'] = $Result['PackEndTime'];
        
        $return[$count]['Send'] = $Result['Send'];
        $return[$count]['SendDiff'] = $Result['sendDiff'];
        $return[$count]['SendStartTime'] = $Result['SendStartTime'];
        $return[$count]['SendEndTime'] = $Result['SendEndTime'];
        $count++;
    }

    if ($count > 0) {
        $return['status'] = "success";
        $return['form'] = $From;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    } else {
        $return['status'] = "failed";
        $return['msg'] = "notfound";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

if (isset($_POST['DATA'])) {
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace('\"', '"', $data), true);

    if ($DATA['STATUS'] == 'get_dep') {
        get_dep($conn, $DATA);
    } else if ($DATA['STATUS'] == 'get_hospital') {
        GetHospital($conn, $DATA);
    } else if ($DATA['STATUS'] == 'get_dirty_doc') {
        get_dirty_doc($conn, $DATA);
    }
} else {
    $return['status'] = "error";
    $return['msg'] = 'noinput';
    echo json_encode($return);
    mysqli_close($conn);
    die;
}
