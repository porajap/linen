<?php
session_start();
require '../connect/connect.php';

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
    $DocNo = $DATA['DocNo'];
    $hpt = $DATA['hpt'];
    $dep = $DATA['dep'];
    $date = $DATA['date'];
    if($hpt=="All"){
        $Sql = "SELECT *
        FROM process,dirty
        WHERE dirty.DocNo LIKE '%$DocNo%'
        AND dirty.DocNo = process.DocNo
        AND dirty.ReceiveDate LIKE '%$date%'";
    }else if($dep=="All"){
        $Sql = "SELECT *
        FROM process,dirty,department
        WHERE dirty.DocNo LIKE '%$DocNo%'
        AND dirty.DocNo = process.DocNo
        AND dirty.DepCode = department.DepCode
        AND department.HptCode = '$hpt'
        AND dirty.ReceiveDate LIKE '%$date%'";
    }else{
        $Sql = "SELECT *
        FROM process,dirty
        WHERE dirty.DocNo LIKE '%$DocNo%'
        AND dirty.DocNo = process.DocNo
        AND dirty.DepCode = $dep
        AND dirty.ReceiveDate LIKE '%$date%'";
    }
    
    $return['Sql'] = $Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DocNo'] = $Result['DocNo'];
        $return[$count]['WashStartTime'] = $Result['WashStartTime'];
        $return[$count]['WashEndTime'] = $Result['WashEndTime'];
        $return[$count]['PackStartTime'] = $Result['PackStartTime'];
        $return[$count]['PackEndTime'] = $Result['PackEndTime'];
        $return[$count]['SendStartTime'] = $Result['SendStartTime'];
        $return[$count]['SendEndTime'] = $Result['SendEndTime'];
        $return[$count]['Signature'] = $Result['Signature'];
        $count++;
    }

    if ($count > 0) {
        $return['status'] = "success";
        $return['form'] = "get_dirty_doc";
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
