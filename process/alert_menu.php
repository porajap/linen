<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'alert_RevealDep') {
    alert_RevealDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'alert_CallDirtyDep') {
    alert_CallDirtyDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'alert_RequestPar') {
    alert_RequestPar($conn);
  } else if ($_POST['FUNC_NAME'] == 'alert_MoveDep') {
    alert_MoveDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'alert_OtherDep') {
    alert_OtherDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'blinkShelfcount') {
    blinkShelfcount($conn);
  } else if ($_POST['FUNC_NAME'] == 'blinkcallDirtyDep') {
    blinkcallDirtyDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'blinkmoveDepartment') {
    blinkmoveDepartment($conn);
  } else if ($_POST['FUNC_NAME'] == 'blinkotherDepartment') {
    blinkotherDepartment($conn);
  } else if ($_POST['FUNC_NAME'] == 'blinkparDep') {
    blinkparDep($conn);
  } else if ($_POST['FUNC_NAME'] == 'GetSite') {
    GetSite($conn);
  } else if ($_POST['FUNC_NAME'] == 'alert_ChatRoom') {
    alert_ChatRoom($conn);
  } else if ($_POST['FUNC_NAME'] == 'blinkChatRoom') {
    blinkChatRoom($conn);
  } else if ($_POST['FUNC_NAME'] == 'showDetailParDocument') {
    showDetailParDocument($conn);
  }
}
function GetSite($conn)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_POST["lang"];
  $count = 0;
  if ($lang == 'en') {

    $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
  } else {

    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $return[] = $row;
  }

  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function alert_RevealDep($conn)
{
  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $site = $_POST["site"];

  if ($site == "" || $site == '0') {
    $hotpCode = $_SESSION['HptCode'];
  } else {
    $hotpCode = $site;
  }

  $Sql = "SELECT
            shelfcount.DocNo,
	          department.DepName,
	          DATE(shelfcount.Modify_Date) AS DateSc,
            TIME(shelfcount.Modify_Date) AS TimeSc,
            shelfcount.Modify_Date
          FROM
            shelfcount
            INNER JOIN department ON shelfcount.DepCode = department.DepCode 
          WHERE statusDepartment = 0 AND shelfcount.IsStatus = 2 AND shelfcount.SiteCode = '$hotpCode' AND department.HptCode = '$hotpCode' ORDER BY shelfcount.Modify_Date ASC";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['DateSc'] . ' เวลา : ' . $Result['TimeSc'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function alert_CallDirtyDep($conn)
{
  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $site = $_POST["site"];
  if ($site == "" || $site == '0') {
    $hotpCode = $_SESSION['HptCode'];
  } else {
    $hotpCode = $site;
  }
  $Sql = "SELECT
            call_dirty.DocNo,
	          department.DepName,
	          DATE(call_dirty.Modify_Date) AS DateSc,
            TIME(call_dirty.Modify_Date) AS TimeSc,
            call_dirty.Modify_Date
          FROM
          call_dirty
            INNER JOIN department ON call_dirty.DepCode = department.DepCode 
          WHERE call_dirty.IsStatus = 0 AND SiteCode = '$hotpCode' AND department.HptCode = '$hotpCode' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['DateSc'] . ' เวลา : ' . $Result['TimeSc'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function alert_RequestPar($conn)
{
  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $site = $_POST["site"];
  if ($site == "" || $site == '0') {
    $hotpCode = $_SESSION['HptCode'];
  } else {
    $hotpCode = $site;
  }
  $Sql = "SELECT
            request_par.DocNo,
	          department.DepName,
            DATE(request_par.Modify_Date) AS DateSc,
            TIME(request_par.Modify_Date) AS TimeSc,
            request_par.Modify_Date
          FROM
          request_par
            INNER JOIN department ON request_par.DepCode = department.DepCode 
          WHERE request_par.IsStatus = 1 AND SiteCode = '$hotpCode' AND department.HptCode = '$hotpCode' ORDER BY request_par.Modify_Date ASC";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['DateSc'] . ' เวลา : ' . $Result['TimeSc'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function alert_MoveDep($conn)
{
  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $site = $_POST["site"];
  if ($site == "" || $site == '0') {
    $hotpCode = $_SESSION['HptCode'];
  } else {
    $hotpCode = $site;
  }
  $Sql = "SELECT
            move_department.DocNo,
	          department.DepName,
	          DATE(move_department.Modify_Date) AS DateSc,
            TIME(move_department.Modify_Date) AS TimeSc,
            move_department.Modify_Date,
	          move_department.DepCodeTo
          FROM
          move_department
            INNER JOIN department ON move_department.DepCodeForm = department.DepCode 
          WHERE move_department.IsStatus = 0 AND SiteCode = '$hotpCode' AND department.HptCode = '$hotpCode' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['DateSc'] . ' เวลา : ' . $Result['TimeSc'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function alert_OtherDep($conn)
{
  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $DepCode = $_SESSION['DepCode'];
  $site = $_POST["site"];
  if ($site == "" || $site == '0') {
    $hotpCode = $_SESSION['HptCode'];
  } else {
    $hotpCode = $site;
  }
  $Sql = "SELECT
            other_department.DocNo,
	          department.DepName,
            DATE(other_department.Modify_Date) AS DateSc,
            TIME(other_department.Modify_Date) AS TimeSc,
            other_department.Modify_Date,
            other_department.Message
          FROM
          other_department
            INNER JOIN department ON other_department.DepCode = department.DepCode 
          WHERE other_department.IsStatus = 0 AND other_department.SiteCode = '$hotpCode' AND department.HptCode = '$hotpCode' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['DateSc'] . ' เวลา : ' . $Result['TimeSc'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function alert_ChatRoom($conn)
{
  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $DepCode = $_SESSION['DepCode'];
  $PmID = $_SESSION['PmID'];
  $site = $_POST["site"];
  if ($site == "" || $site == '0') {
    $hotpCode = $_SESSION['HptCode'];
  } else {
    $hotpCode = $site;
  }
  $wheredep = "";
  if ($PmID == 8) {
    $wheredep = "AND chat_room.DepCode = '$DepCode' ";
  }

  $Sql = "SELECT
            chat_room.DocNo,
            department.DepName,
            DATE(chat_room.Modify_Date) AS DateSc,
            TIME(chat_room.Modify_Date) AS TimeSc,
            chat_room.Modify_Date,
            chat_room.CheckPm
          FROM
          chat_room
            INNER JOIN department ON chat_room.DepCode = department.DepCode 
          WHERE chat_room.IsStatus = 0   $wheredep  AND   chat_room.SiteCode = '$hotpCode' AND    department.HptCode = '$hotpCode' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DateSc']);
      $Result['DateSc'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
    }

    $Result['Modify_Date'] = $Result['DateSc'] . ' เวลา : ' . $Result['TimeSc'];
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function blinkShelfcount($conn)
{
  $DocNo = $_POST["DocNo"];
  $userid   = $_SESSION["Userid"];

  $Sql = "UPDATE shelfcount SET statusDepartment = 1 , IsStatus = 0 , accept_Time = NOW() , checkAlert = 1 , accept_User = $userid  WHERE DocNo = '$DocNo' ";
  if (mysqli_query($conn, $Sql)) {
    echo "1";
  }

  exit();
  mysqli_close($conn);
  die;
}

function blinkcallDirtyDep($conn)
{
  $DocNo = $_POST["DocNo"];
  $userid   = $_SESSION["Userid"];

  $Sql = "UPDATE call_dirty SET IsStatus = 2 , approveName = '$userid' ,  approveDate = NOW()   WHERE DocNo = '$DocNo' ";
  if (mysqli_query($conn, $Sql)) {
    echo "1";
  }

  exit();
  mysqli_close($conn);
  die;
}

function blinkmoveDepartment($conn)
{
  $DocNo = $_POST["DocNo"];
  $userid   = $_SESSION["Userid"];

  $Sql = "UPDATE move_department SET IsStatus = 2 , approveName = '$userid' ,  approveDate = NOW()   WHERE DocNo = '$DocNo' ";
  if (mysqli_query($conn, $Sql)) {
    echo "1";
  }

  exit();
  mysqli_close($conn);
  die;
}

function blinkotherDepartment($conn)
{
  $DocNo = $_POST["DocNo"];
  $userid   = $_SESSION["Userid"];

  $Sql = "UPDATE other_department SET IsStatus = 2 , approveName = '$userid' ,  approveDate = NOW()    WHERE DocNo = '$DocNo' ";
  if (mysqli_query($conn, $Sql)) {
    echo "1";
  }

  exit();
  mysqli_close($conn);
  die;
}

function blinkparDep($conn)
{
  $DocNo = $_POST["DocNo"];
  $userid   = $_SESSION["Userid"];

  $Sql = "UPDATE request_par SET IsStatus = 2  ,  approveDate = NOW()    WHERE DocNo = '$DocNo' ";
  if (mysqli_query($conn, $Sql)) {
    echo "1";
  }

  exit();
  mysqli_close($conn);
  die;
}

function blinkChatRoom($conn)
{
  $DocNo = $_POST["DocNo"];
  $userid   = $_SESSION["Userid"];

  $Sql = "UPDATE chat_room SET IsStatus = 1   WHERE DocNo = '$DocNo' ";
  if (mysqli_query($conn, $Sql)) {
    echo "1";
  }

  exit();
  mysqli_close($conn);
  die;
}

function showDetailParDocument($conn)
{
  $DocNo = $_POST['DocNo'];
  $return = array();

  $Sql = "SELECT
            item.ItemName,
            item.ItemCode,
            request_par_detail.ParQty,
            request_par_detail.Qty 
          FROM
          request_par_detail
            INNER JOIN item ON request_par_detail.ItemCode = item.ItemCode
          WHERE request_par_detail.DocNo = '$DocNo'";

// AND request_par_detail.Qty != 0 
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
