<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
$lang = $_SESSION['lang'];
if ($Userid == "") {
  header("location:../index.html");
}

function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $countx = 0;
  $countDep = 0;
  $HptCode = $_SESSION['HptCode'];
  $FacCode = $_SESSION['FacCode'];
  $DepCode = $_SESSION['FacCode'];

  $Sqlx = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0";
  $meQueryx = mysqli_query($conn, $Sqlx);
  while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
    $return[$countx]['FacCode'] = trim($Resultx['FacCode']);
    $return[$countx]['FacName'] = trim($Resultx['FacName']);
    $countx++;
  }
  $return['Rowx'] = $countx;


  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = trim($Result['HptCode']);
    $return[$count]['HptName'] = trim($Result['HptName']);
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;

  $Sql = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' AND  department.IsStatus = 0 ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$countDep]['DepCode'] = trim($Result['DepCode']);
    $return[$countDep]['DepName'] = trim($Result['DepName']);
    $countDep++;
    $boolean = true;
  }
  $return['RowDep'] = $countDep;

  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function departmentWhere($conn, $DATA)
{
  $HptCode = $DATA['HptCode'];
  $count = 0;
  $boolean = false;
  $Sql = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' AND department.IsStatus = 0  AND department.IsDefault = 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = trim($Result['DepCode']);
    $return[$count]['DepName'] = trim($Result['DepName']);
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;
  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "departmentWhere";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "departmentWhere";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function find_report($conn, $DATA)
{
  $FacCode = $DATA['factory'];
  $HptCode = $DATA['HptCode'] == null ? $_SESSION['HptCode'] : $DATA['HptCode'];
  $DepCode = $DATA['DepCode'] == null ? $_SESSION['DepCode'] : $DATA['DepCode'];
  $typeReport = $DATA['typeReport'];
  $Format = $DATA['Format'];
  $FormatDay = $DATA['FormatDay'];
  $FormatMonth = $DATA['FormatMonth'];
  $date = $DATA['date'];
  $cycle = $DATA['cycle'];
  $date1 = '';
  $date2 = '';

  if ($typeReport == 1) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }

  } else if ($typeReport == 2) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 3) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 4) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $cycle, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,$cycle, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,$cycle, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,$cycle, 'monthbetween');
      }
    }
  } else if ($typeReport == 5) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r5($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r5($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r5($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r5($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 6) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 7) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r7($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r7($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r7($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r7($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 8) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 9) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    } else if ($typeReport == 9) {
      if ($Format == 1 || $Format == 3) {
        if ($FormatDay == 1 || $Format == 3) {
          $date1 = $date;
          $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
        } else {
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
        }
      } else if ($Format == 2) {
        if ($FormatMonth == 1) {
          $date1 = newMonth($date);
          $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
        } else {
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
        }
      }
    }
  } else if ($typeReport == 13) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 10) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r10($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r10($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r10($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r10($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 11) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r11($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r11($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r11($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r11($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 12) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r12($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r12($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r12($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r12($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 14) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r14($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r14($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r14($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r14($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 15) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 16) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 17) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r17($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r17($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r17($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r17($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  }
  $return['typeReport'] = typeReport($typeReport);
  echo json_encode($return);
}
#----------------------------chk number mount
function chk_mount($date)
{ 
  $language = $_SESSION['lang'];
  $youDate = trim($date);
  if($language == 'en'){
    $MonthArray = [
      '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October',
      '11' => 'November', '12' => 'December'
    ];
  }else{
    $MonthArray = [
      '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม',
      '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
    ];
  }
  $numMonth = array_search($youDate, $MonthArray);
  return $numMonth;
}
#----------------------------Format new date
function newDate1($date)
{
  $sub = explode('-', $date);
  $d1 = explode('/', $sub[0]);
  $date1 = ereg_replace('[[:space:]]+', '', trim($d1[0].'-'.$d1[1].'-'.$d1[2]));
  return $date1;
}
function newDate2($date)
{
  $sub = explode('-', $date);
  $d2 = explode('/', $sub[1]);
  $date2 = ereg_replace('[[:space:]]+', '', trim($d2[0].'-'.$d2[1].'-'.$d2[2]));
  return $date2;
}
function newMonth($date)
{
  $mount = explode('-', $date);
  $chk = chk_mount($mount[0]);
  $date1 = $mount[1] . '-' . $chk;
  return $date1;
}
function newMonth1($date)
{
  $month = explode('-', $date);
  $month1 = explode('/', $month[0]);
  $numMonth = chk_mount($month1[0]);
  $date1 = $month1[1] . '-' . $numMonth;
  return $date1;
}
function newMonth2($date)
{
  $month = explode('-', $date);
  $month2 = explode('/', $month[1]);
  $numMonth = chk_mount($month2[0]);
  $date2 = $month2[1] . '-' . $numMonth;
  return $date2;
} 
function subMonth($date1, $date2)
{
  $month1 = explode('-', $date1);
  $year = trim($month1[0]);
  $date1 = trim($month1[1]);
  $month2 = explode('-', $date2);
  $date2 = trim($month2[1]);
  $date['year'] = $year;
  $date['date1'] = $date1;
  $date['date2'] = $date2;
  return $date;
}
function typeReport($typeReport)
{
  $type = trim($typeReport);
  $typeArray =
    [
      'Record dirty linen weight' => 1,
      'Report Claim' => 2,
      'Report Cleaned Linen Weight' => 3,
      'Report Daily Issue Request' => 4,
      'Report Operations Time Spend' => 5,
      'Report Rewash' => 6,
      'Report Shot and Over item' => 7,
      'Report Soiled Clean Ratio' => 8,
      'Report stock count' => 9,
      'Report Billing Claim' => 10,
      'Report Billing Customer' => 11,
      'Report Billing Factory' => 12,
      'Report Summary Dirty' => 13,
      'Report Summary' => 14,
      'Report Tracking status for laundry plant' => 15,
      'Report Tracking status for linen operation' => 16,
      'Report Damaged And Loss' => 17
    ];
  $myReport = array_search($type, $typeArray);
  return $myReport;
}
#----------------------------Format new date

function r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $boolean = false;
  $count = 0;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  
              DATE(dirty.DocDate) AS DocDate1,
              site.hptname,
              factory.facname
              FROM dirty
              INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON dirty.DepCode = department.DepCode
              INNER JOIN site ON site.hptcode = department.hptcode
              WHERE dirty.DocDate LIKE '%$date1%'
              AND dirty.FacCode = $FacCode
              GROUP BY dirty.DocDate
              ORDER BY dirty.DocDate ASC";
    } else {
      $Sql = "SELECT  
              DATE(dirty.DocDate) AS DocDate1,
              site.hptname,
              factory.facname
              FROM dirty
              INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON dirty.DepCode = department.DepCode
              INNER JOIN site ON site.hptcode = department.hptcode
              WHERE dirty.DocDate BETWEEN '$date1' AND '$date2'
              AND dirty.FacCode = $FacCode
              GROUP BY MONTH(dirty.DocDate)
              ORDER BY dirty.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT  
      MONTH(dirty.DocDate) AS DocDate1,
      site.hptname,
      factory.facname
      FROM dirty
      INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
      INNER JOIN factory ON factory.FacCode = dirty.FacCode
      INNER JOIN department ON dirty.DepCode = department.DepCode
      INNER JOIN site ON site.hptcode = department.hptcode
                WHERE dirty.DocDate LIKE '%$date1%'
                AND dirty.FacCode = $FacCode
                 GROUP BY MONTH(dirty.DocDate)
                 ORDER BY dirty.DocDate ASC";
    } else {
      $Sql = "SELECT  
      MONTH(dirty.DocDate) AS DocDate1,
      site.hptname,
      factory.facname
      FROM dirty
      INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
      INNER JOIN factory ON factory.FacCode = dirty.FacCode
      INNER JOIN department ON dirty.DepCode = department.DepCode
      INNER JOIN site ON site.hptcode = department.hptcode
              WHERE YEAR(dirty.DocDate) = $year AND MONTH(dirty.DocDate) BETWEEN $date1 AND $date2
              AND dirty.FacCode = $FacCode
              GROUP BY YEAR(dirty.DocDate)
              ORDER BY  dirty.DocNo ASC ";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT  
    YEAR(dirty.DocDate) AS DocDate1,
    site.hptname,
    factory.facname
    FROM dirty
    INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
    INNER JOIN factory ON factory.FacCode = dirty.FacCode
    INNER JOIN department ON dirty.DepCode = department.DepCode
    INNER JOIN site ON site.hptcode = department.hptcode
              WHERE dirty.DocDate LIKE '%$date1%'
              AND dirty.FacCode = $FacCode
               GROUP BY year(dirty.DocDate)
               ORDER BY dirty.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Dirty_Linen_Weight.php';
  $return['555'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocDate3'] = $Result['DocDate3'];
    $return[$count]['DocDate2'] = $Result['DocDate2'];
    $return[$count]['facname'] = $Result['facname'];
    $return[$count]['hptname'] = $Result['hptname'];
    $boolean = true;
    $count++;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['countRow'] = $count;
    $return['form'] = 'r1';
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r1';
    return $return;
  }
}
function r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT department.DepName, 
              claim.DocDate,
              factory.facname,
              site.Hptname
            FROM claim
            INNER JOIN clean ON clean.docno = claim.refdocno
            INNER JOIN dirty ON dirty.docno = clean.refdocno
            INNER JOIN factory ON factory.faccode = dirty.FacCode
            INNER JOIN department ON claim.DepCode = department.DepCode
            INNER JOIN site ON site.HptCode = department.HptCode
            WHERE claim.DocDate LIKE '%$date1%'
            AND claim.HptCode = '$HptCode'
            AND department.DepCode = $DepCode
            AND factory.FacCode = $FacCode
            GROUP BY claim.DocDate
            ORDER BY claim.DocDate ASC";
    } else {
      $Sql = "SELECT department.DepName, 
              claim.DocDate,
              factory.facname,
              site.Hptname
            FROM claim
            INNER JOIN clean ON clean.docno = claim.refdocno
            INNER JOIN dirty ON dirty.docno = clean.refdocno
            INNER JOIN factory ON factory.faccode = dirty.FacCode
            INNER JOIN department ON claim.DepCode = department.DepCode
            INNER JOIN site ON site.HptCode = department.HptCode
              WHERE claim.Docdate BETWEEN '$date1' AND '$date2'
              AND claim.HptCode = '$HptCode'
              AND claim.DepCode = $DepCode
              AND factory.FacCode = $FacCode
              GROUP BY MONTH (claim.DocDate) ORDER BY claim.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT department.DepName, 
      claim.DocDate,
      factory.facname,
      site.Hptname
    FROM claim
    INNER JOIN clean ON clean.docno = claim.refdocno
    INNER JOIN dirty ON dirty.docno = clean.refdocno
    INNER JOIN factory ON factory.faccode = dirty.FacCode
    INNER JOIN department ON claim.DepCode = department.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
      WHERE claim.DocDate LIKE '%$date1%'
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = $DepCode
      AND factory.FacCode = $FacCode
      GROUP BY MONTH (claim.DocDate)
      ORDER BY claim.DocDate ASC";
    } else {
      $Sql = "SELECT department.DepName, 
      claim.DocDate,
      factory.facname,
      site.Hptname
    FROM claim
    INNER JOIN clean ON clean.docno = claim.refdocno
    INNER JOIN dirty ON dirty.docno = clean.refdocno
    INNER JOIN factory ON factory.faccode = dirty.FacCode
    INNER JOIN department ON claim.DepCode = department.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
      WHERE YEAR(claim.DocDate) = $year AND MONTH(claim.DocDate) BETWEEN $date1 AND $date2
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = $DepCode
      AND factory.FacCode = $FacCode
      GROUP BY YEAR (claim.DocDate)
      ORDER BY claim.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT department.DepName, 
    claim.DocDate,
    factory.facname,
    site.Hptname
  FROM claim
  INNER JOIN clean ON clean.docno = claim.refdocno
  INNER JOIN dirty ON dirty.docno = clean.refdocno
  INNER JOIN factory ON factory.faccode = dirty.FacCode
  INNER JOIN department ON claim.DepCode = department.DepCode
  INNER JOIN site ON site.HptCode = department.HptCode
              WHERE  YEAR(claim.DocDate) =  $date1
              AND claim.HptCode = '$HptCode'
              AND claim.DepCode = $DepCode
              AND factory.FacCode = $FacCode
              GROUP BY YEAR (claim.DocDate) ORDER BY claim.DocDate ASC";
  }
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Claim.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['facname'] = $Result['facname'];
    $return[$count]['Hptname'] = $Result['Hptname'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r2';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r2';
    return $return;
  }
}
function r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN dirty ON dirty.DocNo = clean.refdocno
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN clean_detail ON clean.DocNo=clean_detail.DocNo
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE clean.DocDate LIKE '%$date1%'
              AND site.HptCode = '$HptCode'
              AND dirty.FacCode = $FacCode
              GROUP BY Date(clean.DocDate)
              ORDER BY clean.DocDate ASC";
    } else {
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN dirty ON dirty.DocNo = clean.refdocno
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE clean.DocDate BETWEEN '$date1'
              AND site.HptCode = '$HptCode'
              AND dirty.FacCode = $FacCode
              GROUP BY MONTH (clean.Docdate)
              ORDER BY clean.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN dirty ON dirty.DocNo = clean.refdocno
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE clean.DocDate LIKE '%$date1%'
              AND site.HptCode = '$HptCode'
              AND dirty.FacCode = $FacCode
              GROUP BY MONTH (clean.Docdate)
              ORDER BY clean.DocDate ASC";
    } else {
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN dirty ON dirty.DocNo = clean.refdocno
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
            WHERE YEAR(clean.DocDate) = $year AND MONTH(clean.DocDate) BETWEEN $date1 AND $date2
           AND site.HptCode = '$HptCode'
           AND dirty.FacCode = $FacCode
           GROUP BY YEAR (clean.Docdate)
           ORDER BY clean.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN dirty ON dirty.DocNo = clean.refdocno
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE clean.DocDate LIKE '%$date1%'
             AND site.HptCode = '$HptCode'
             AND dirty.FacCode = $FacCode
             GROUP BY YEAR (clean.Docdate)
             ORDER BY clean.DocDate ASC";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Cleaned_Linen_Weight.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r3';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r3';
    return $return;
  }
}
function r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $cycle, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT shelfcount.DocNo,
      DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%' 
      AND department.DepCode = $DepCode
      AND shelfcount.CycleTime = $cycle
      GROUP BY shelfcount.Docdate ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT shelfcount.DocNo,
      DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
      AND department.DepCode = $DepCode
      AND shelfcount.CycleTime = $cycle
      GROUP BY MONTH(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%'
      AND department.DepCode = $DepCode
      AND shelfcount.CycleTime = $cycle
      GROUP BY MONTH(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE YEAR(shelfcount.DocDate) = $year AND MONTH(shelfcount.DocDate) BETWEEN $date1 AND $date2
      AND department.DepCode = $DepCode
      AND shelfcount.CycleTime = $cycle
      GROUP BY YEAR(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
              FROM shelfcount
              INNER JOIN department ON shelfcount.DepCode = department.DepCode
              WHERE shelfcount.DocDate LIKE '%$date1%'
              AND shelfcount.CycleTime = $cycle
              GROUP BY YEAR(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
  }
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode,'cycle' => $cycle, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Daily_Issue_Request.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r4';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r4';
    return $return;
  }
}
function r5($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT shelfcount.DocNo,
      DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%'
      AND department.DepCode = $DepCode
      GROUP BY shelfcount.Docdate ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT shelfcount.DocNo,
      DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
      AND department.DepCode = $DepCode
      GROUP BY MONTH(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%'
      AND department.DepCode = $DepCode
      GROUP BY MONTH(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE YEAR(shelfcount.DocDate) = $year AND MONTH(shelfcount.DocDate) BETWEEN $date1 AND $date2
      AND department.DepCode = $DepCode
      GROUP BY YEAR(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
              FROM shelfcount
              INNER JOIN department ON shelfcount.DepCode = department.DepCode
              WHERE shelfcount.DocDate LIKE '%$date1%'
              GROUP BY YEAR(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
  }
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Daily_Issue_Request.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r4';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r5';
    return $return;
  }
}
function r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
              factory.FacName, DATE(rewash.DocDate) AS DocDate,
              site.hptname,
              department.DepName
              FROM rewash
              INNER JOIN rewash_detail ON rewash_detail.DocNo = rewash.DocNo
              INNER JOIN factory ON rewash.FacCode = factory.FacCode
              INNER JOIN department ON rewash.DepCode =  department.DepCode
              INNER JOIN site ON department.hptcode = site.hptcode
              WHERE rewash.DocDate LIKE '%$date1%'
              AND rewash.FacCode = $FacCode
              AND site.hptcode = '$HptCode'
              GROUP BY rewash.DocDate
              ORDER BY rewash.DocDate ASC";
    } else {
      $Sql = "SELECT
              factory.FacName, month(rewash.DocDate) AS DocDate,
              site.hptname,
              department.DepName
              FROM rewash
              INNER JOIN rewash_detail ON rewash_detail.DocNo = rewash.DocNo
              INNER JOIN factory ON rewash.FacCode = factory.FacCode
              INNER JOIN department ON rewash.DepCode =  department.DepCode
              INNER JOIN site ON department.hptcode = site.hptcode
              WHERE rewash.DocDate BETWEEN '$date1' AND '$date2'
              AND rewash.FacCode = $FacCode
              AND site.hptcode = '$HptCode'
              GROUP BY MONTH(rewash.DocDate)
              ORDER BY rewash.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT  factory.FacName, month(rewash.DocDate) AS DocDate,
      site.hptname,
      department.DepName
      FROM rewash
      INNER JOIN rewash_detail ON rewash_detail.DocNo = rewash.DocNo
      INNER JOIN factory ON rewash.FacCode = factory.FacCode
      INNER JOIN department ON rewash.DepCode =  department.DepCode
      INNER JOIN site ON department.hptcode = site.hptcode
      WHERE rewash.DocDate LIKE '%$date1%' 
      AND rewash.FacCode = $FacCode
      AND site.hptcode = '$HptCode'
      GROUP BY MONTH (rewash.DocDate)
      ORDER BY rewash.DocDate ASC";
    } else {
      $Sql = "SELECT  factory.FacName, year(rewash.DocDate) AS DocDate,
      site.hptname,
      department.DepName
      FROM rewash
      INNER JOIN rewash_detail ON rewash_detail.DocNo = rewash.DocNo
      INNER JOIN factory ON rewash.FacCode = factory.FacCode
      INNER JOIN department ON rewash.DepCode =  department.DepCode
      INNER JOIN site ON department.hptcode = site.hptcode
      WHERE YEAR(rewash.DocDate) = $year AND MONTH(rewash.DocDate) BETWEEN $date1 AND $date2
      AND rewash.FacCode = $FacCode
      AND site.hptcode = '$HptCode'
      GROUP BY YEAR (rewash.DocDate)
      ORDER BY rewash.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT  factory.FacName, year(rewash.DocDate) AS DocDate,
    site.hptname,
    department.DepName
            FROM rewash
            INNER JOIN rewash_detail ON rewash_detail.DocNo = rewash.DocNo
            INNER JOIN factory ON rewash.FacCode = factory.FacCode
            INNER JOIN department ON rewash.DepCode =  department.DepCode
            INNER JOIN site ON department.hptcode = site.hptcode
            WHERE rewash.DocDate LIKE '%$date1%'
            AND rewash.FacCode = $FacCode
            AND site.hptcode = '$HptCode'
            GROUP BY YEAR (rewash.DocDate)
            ORDER BY rewash.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Rewash.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['hptname'] = $Result['hptname'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r6';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r6';
    return $return;
  }
}
function r7($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      department.depname
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
            WHERE shelfcount.DocDate LIKE '%$date1%'  
            AND shelfcount.DepCode = $DepCode
            GROUP BY DATE(shelfcount.DocDate)";
    } else {
      $Sql = "SELECT
      department.depname
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
            WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
            AND shelfcount.DepCode = $DepCode
            GROUP BY MONTH(shelfcount.DocDate)";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
      department.depname
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%'  AND dirty.FacCode = $FacCode
      AND shelfcount.DepCode = $DepCode
            GROUP BY MONTH(shelfcount.DocDate)";
    } else {
      $Sql = "SELECT
      department.depname
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
      WHERE YEAR(shelfcount.DocDate) = $year AND MONTH(shelfcount.DocDate) BETWEEN $date1 AND $date2
      AND shelfcount.DepCode = $DepCode
            GROUP BY YEAR(shelfcount.DocDate)";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
    department.depname
    FROM
    shelfcount
    INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
    INNER JOIN department ON department.depcode = shelfcount.DepCode
            WHERE shelfcount.DocDate LIKE '%$date1%'
            AND shelfcount.DepCode = $DepCode
            GROUP BY YEAR(shelfcount.DocDate)";
  }
  $return['ql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Shot_and_Over_item.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r7';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r7';
    return $return;
  }
}
function r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT factory.FacName, 
              DATE(clean.DocDate) AS DocDate,
               site.HptName,
              department.DepName
            FROM clean
            INNER JOIN department ON department.DepCode = clean.DepCode
            INNER JOIN site ON site.HptCode = department.HptCode
            INNER JOIN dirty ON clean.refdocno = dirty.docno
            INNER JOIN factory ON dirty.FacCode = factory.FacCode
            WHERE clean.DocDate LIKE '%$date1%'  
            AND dirty.FacCode = $FacCode
            AND department.HptCode = '$HptCode'
            GROUP BY DATE(clean.DocDate)";
    } else {
      $Sql = "SELECT factory.FacName, 
      DATE(clean.DocDate) AS DocDate,
       site.HptName,
      department.DepName
    FROM clean
    INNER JOIN department ON department.DepCode = clean.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    INNER JOIN dirty ON clean.refdocno = dirty.docno
    INNER JOIN factory ON dirty.FacCode = factory.FacCode
            WHERE clean.DocDate BETWEEN '$date1' AND '$date2'
            AND dirty.FacCode = $FacCode
            AND department.HptCode = '$HptCode'
            GROUP BY MONTH(clean.DocDate)";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT factory.FacName, 
      DATE(clean.DocDate) AS DocDate,
       site.HptName,
      department.DepName
    FROM clean
    INNER JOIN department ON department.DepCode = clean.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    INNER JOIN dirty ON clean.refdocno = dirty.docno
    INNER JOIN factory ON dirty.FacCode = factory.FacCode
      WHERE clean.DocDate LIKE '%$date1%'  AND dirty.FacCode = $FacCode
      AND department.HptCode = '$HptCode'
      GROUP BY MONTH(clean.DocDate)";
    } else {
      $Sql = "SELECT factory.FacName, 
      DATE(clean.DocDate) AS DocDate,
       site.HptName,
      department.DepName
    FROM clean
    INNER JOIN department ON department.DepCode = clean.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    INNER JOIN dirty ON clean.refdocno = dirty.docno
    INNER JOIN factory ON dirty.FacCode = factory.FacCode
      WHERE YEAR(clean.DocDate) = $year AND MONTH(clean.DocDate) BETWEEN $date1 AND $date2
      AND department.HptCode = '$HptCode'
      AND dirty.FacCode = $FacCode
      GROUP BY YEAR(clean.DocDate)";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT factory.FacName, 
    DATE(clean.DocDate) AS DocDate,
     site.HptName,
    department.DepName
  FROM clean
  INNER JOIN department ON department.DepCode = clean.DepCode
  INNER JOIN site ON site.HptCode = department.HptCode
  INNER JOIN dirty ON clean.refdocno = dirty.docno
  INNER JOIN factory ON dirty.FacCode = factory.FacCode
            WHERE clean.DocDate LIKE '%$date1%'
            AND department.HptCode = '$HptCode'
            AND dirty.FacCode = $FacCode
            GROUP BY YEAR(clean.DocDate)";
  }
  $return['ql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Soiled_Clean_Ratio.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r8';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r8';
    return $return;
  }
}
function r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {

      $Sql = "SELECT
              DATE(item_stock.ExpireDate) AS ExpireDateX,
              department.DepName
              FROM
              item_stock
              INNER JOIN department ON item_stock.DepCode=department.DepCode
              WHERE item_stock.ExpireDate LIKE '%$date1%'
              AND item_stock.DepCode = $DepCode
              GROUP BY DATE(item_stock.ExpireDate)
              ORDER BY item_stock.ExpireDate ASC";
    } else {
      $Sql = "SELECT
              DATE(item_stock.ExpireDate) AS ExpireDateX,
              department.DepName
              FROM item_stock
              INNER JOIN department ON item_stock.DepCode=department.DepCode
              WHERE item_stock.ExpireDate BETWEEN '$date1' AND '$date2'
              AND item_stock.DepCode = $DepCode
              GROUP BY month (item_stock.ExpireDate) ORDER BY item_stock.ExpireDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
              DATE(item_stock.ExpireDate) AS ExpireDateX,
              department.DepName
              FROM
              item_stock
              INNER JOIN department ON item_stock.DepCode=department.DepCode
              WHERE item_stock.ExpireDate LIKE '%$date1%'
              AND item_stock.DepCode = $DepCode
              GROUP BY month (item_stock.ExpireDate)
              ORDER BY item_stock.ExpireDate ASC";
    } else {
      $Sql = "SELECT
              DATE(item_stock.ExpireDate) AS ExpireDateX,
              department.DepName
              FROM
              item_stock
              INNER JOIN department ON item_stock.DepCode=department.DepCode
              WHERE YEAR(item_stock.ExpireDate) = $year AND MONTH(item_stock.ExpireDate) BETWEEN $date1 AND $date2
              AND item_stock.DepCode = $DepCode
              GROUP BY year (item_stock.ExpireDate)
              ORDER BY item_stock.ExpireDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
              DATE(item_stock.ExpireDate) AS ExpireDateX,
              department.DepName
              FROM
              item_stock
              INNER JOIN department ON item_stock.DepCode=department.DepCode
              WHERE item_stock.ExpireDate LIKE '%$date1%'
              AND item_stock.DepCode = $DepCode
              GROUP BY year (item_stock.ExpireDate)
              ORDER BY item_stock.ExpireDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Stock_Count.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['ExpireDateX'];
    $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r9';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r9';
    return $return;
  }
}
function r10($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {

      $Sql = "SELECT
              site.HptName , DATE(claim.DocDate) AS DocDate
              FROM claim_detail
              INNER JOIN claim on claim.docno = claim_detail.DocNo
              INNER JOIN site on site.HptCode = claim.HptCode
              WHERE claim.DocDate LIKE '%$date1%'
              AND claim.HptCode = '$HptCode'
              AND claim.DepCode = '$DepCode'
              GROUP BY claim.DocDate
              ORDER BY claim.DocDate ASC";
    } else {
      $Sql = "SELECT
              site.HptName , DATE(claim.DocDate) AS DocDate
              FROM claim_detail
              INNER JOIN claim on claim.docno = claim_detail.DocNo
              INNER JOIN site on site.HptCode = claim.HptCode
              WHERE claim.DocDate BETWEEN '$date1' AND '$date2'
              AND claim.HptCode = '$HptCode'
              AND claim.DepCode = '$DepCode'
              GROUP BY MONTH(claim.DocDate) ORDER BY claim.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
              site.HptName , DATE(claim.DocDate) AS DocDate
              FROM claim_detail
              INNER JOIN claim on claim.docno = claim_detail.DocNo
              INNER JOIN site on site.HptCode = claim.HptCode
              WHERE claim.DocDate LIKE '%$date1%'
              AND claim.HptCode = '$HptCode'
              AND claim.DepCode = '$DepCode'
              GROUP BY MONTH (claim.DocDate)
              ORDER BY claim.DocDate ASC";
    } else {
      $Sql = "SELECT
      site.HptName , DATE(claim.DocDate) AS DocDate
      FROM claim_detail
      INNER JOIN claim on claim.docno = claim_detail.DocNo
      INNER JOIN site on site.HptCode = claim.HptCode
      WHERE YEAR(claim.DocDate) = $year AND MONTH(claim.DocDate) BETWEEN $date1 AND $date2
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = '$DepCode'
      GROUP BY year (claim.DocDate)
      ORDER BY claim.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      site.HptName , DATE(claim.DocDate) AS DocDate
      FROM claim_detail
      INNER JOIN claim on claim.docno = claim_detail.DocNo
      INNER JOIN site on site.HptCode = claim.HptCode
      WHERE claim.DocDate LIKE '%$date1%'
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = '$DepCode'
      GROUP BY year (claim.DocDate)
       ORDER BY claim.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Billing_Claim.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r10';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r10';
    return $return;
  }
}
function r11($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {

      $Sql = "SELECT
              site.HptName ,
              DATE(billcustomer.DocDate) AS DocDate
              FROM
              billcustomer_detail
              INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
              INNER JOIN site on site.HptCode = billcustomer.HptCode
              WHERE billcustomer.DocDate LIKE '%$date1%'
              AND billcustomer.HptCode = '$HptCode'
              AND billcustomer.DepCode = '$DepCode'
              GROUP BY billcustomer.DocDate
              ORDER BY billcustomer.DocDate ASC";
    } else {
      $Sql = "SELECT
              site.HptName ,
              DATE(billcustomer.DocDate) AS DocDate
              FROM
              billcustomer_detail
              INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
              INNER JOIN site on site.HptCode = billcustomer.HptCode
              WHERE billcustomer.DocDate BETWEEN '$date1' AND '$date2'
              AND billcustomer.HptCode = '$HptCode'
              AND billcustomer.DepCode = '$DepCode'
              GROUP BY billcustomer.DocDate ORDER BY billcustomer.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
              site.HptName ,
              DATE(billcustomer.DocDate) AS DocDate
              FROM
              billcustomer_detail
              INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
              INNER JOIN site on site.HptCode = billcustomer.HptCode
              WHERE billcustomer.DocDate LIKE '%$date1%'
              AND billcustomer.HptCode = '$HptCode'
              AND billcustomer.DepCode = '$DepCode'
              GROUP BY month (billcustomer.DocDate)
              ORDER BY billcustomer.DocDate ASC";
    } else {
      $Sql = "SELECT
      site.HptName ,
      DATE(billcustomer.DocDate) AS DocDate
      FROM
      billcustomer_detail
      INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
      INNER JOIN site on site.HptCode = billcustomer.HptCode
      WHERE YEAR(billcustomer.DocDate) = $year AND MONTH(billcustomer.DocDate) BETWEEN $date1 AND $date2
      AND billcustomer.HptCode = '$HptCode'
      AND billcustomer.DepCode = '$DepCode'
      GROUP BY year (billcustomer.DocDate)
      ORDER BY billcustomer.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      site.HptName ,
      DATE(billcustomer.DocDate) AS DocDate
      FROM
      billcustomer_detail
      INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
      INNER JOIN site on site.HptCode = billcustomer.HptCode
      WHERE billcustomer.DocDate LIKE '%$date1%'
      AND billcustomer.HptCode = '$HptCode'
      AND billcustomer.DepCode = '$DepCode'
      GROUP BY year (billcustomer.DocDate)
       ORDER BY billcustomer.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Billing_Customer.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r11';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r11';
    return $return;
  }
}
function r12($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {

      $Sql = "SELECT
              site.HptName ,
              DATE(billwash.DocDate) AS DocDate
              FROM
              billwash_detail
              INNER JOIN billwash on billwash.docno = billwash_detail.DocNo
              INNER JOIN site on site.HptCode = billwash.HptCode
              WHERE billwash.DocDate LIKE '%$date1%'
              AND billwash.HptCode = '$HptCode'
              AND billwash.DepCode = '$DepCode'
              GROUP BY billwash.DocDate
              ORDER BY billwash.DocDate ASC";
    } else {
      $Sql = "SELECT
              site.HptName ,
              DATE(billwash.DocDate) AS DocDate
              FROM
              billwash_detail
              INNER JOIN billwash on billwash.docno = billwash_detail.DocNo
              INNER JOIN site on site.HptCode = billwash.HptCode
              WHERE billwash.DocDate BETWEEN '$date1' AND '$date2'
              AND billwash.HptCode = '$HptCode'
              AND billwash.DepCode = '$DepCode'
              GROUP BY billwash.DocDate ORDER BY billwash.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
              site.HptName ,
              DATE(billwash.DocDate) AS DocDate
              FROM
              billwash_detail
              INNER JOIN billwash on billwash.docno = billwash_detail.DocNo
              INNER JOIN site on site.HptCode = billwash.HptCode
              WHERE billwash.DocDate LIKE '%$date1%'
              AND billwash.HptCode = '$HptCode'
              AND billwash.DepCode = '$DepCode'
              GROUP BY month (billwash.DocDate)
              ORDER BY billwash.DocDate ASC";
    } else {
      $Sql = "SELECT
      site.HptName ,
      DATE(billwash.DocDate) AS DocDate
      FROM
      billwash_detail
      INNER JOIN billwash on billwash.docno = billwash_detail.DocNo
      INNER JOIN site on site.HptCode = billwash.HptCode
      WHERE YEAR(billwash.DocDate) = $year AND MONTH(billwash.DocDate) BETWEEN $date1 AND $date2
      AND billwash.HptCode = '$HptCode'
      AND billwash.DepCode = '$DepCode'
      GROUP BY year (billwash.DocDate)
      ORDER BY billwash.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      site.HptName ,
      DATE(billwash.DocDate) AS DocDate
      FROM
      billwash_detail
      INNER JOIN billwash on billwash.docno = billwash_detail.DocNo
      INNER JOIN site on site.HptCode = billwash.HptCode
      WHERE billwash.DocDate LIKE '%$date1%'
      AND billwash.HptCode = '$HptCode'
      AND billwash.DepCode = '$DepCode'
      GROUP BY year (billwash.DocDate)
       ORDER BY billwash.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Billing_Factory.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r12';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r12';
    return $return;
  }
}
function r14($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1 || $Format == 3) {
    if ($chk == 'one') {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE shelfcount.DocDate LIKE '%$date1%'
      AND site.HptCode ='$HptCode'
      GROUP BY date(shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    } else {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'  AND site.HptCode ='$HptCode'
      GROUP BY MONTH (shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE shelfcount.DocDate LIKE '%$date1%'  AND site.HptCode ='$HptCode'
      GROUP BY MONTH (shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    } else {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE YEAR(shelfcount.DocDate) = $year AND MONTH(shelfcount.DocDate) BETWEEN $date1 AND $date2
      GROUP BY YEAR (shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT 	department.DepName,DocDate,site.HptName
    FROM shelfcount
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    WHERE shelfcount.DocDate LIKE '%$date1%'  AND site.HptCode ='$HptCode'
    GROUP BY YEAR (shelfcount.DocDate)
    ORDER BY shelfcount.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Summary.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r14';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r14';
    return $return;
  }
}
function r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1 || $Format == 3) {
    if ($chk == 'one') {
      $Sql = "SELECT 	factory.FacName,
                      site.HptName ,
                      DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE clean.DocDate LIKE '%$date1%'
              AND factory.FacCode = $FacCode
              AND site.HptCode ='$HptCode'
              GROUP BY date(clean.DocDate)
              ORDER BY clean.DocDate ASC";
    } else {
      $Sql = "SELECT 	factory.FacName,
              site.HptName ,
              DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE clean.DocDate BETWEEN '$date1' AND '$date2' AND factory.FacCode = $FacCode AND site.HptCode ='$HptCode'
              GROUP BY MONTH (clean.DocDate)
              ORDER BY clean.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT 	factory.FacName,
              site.HptName ,
              DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE clean.DocDate LIKE '%$date1%' AND factory.FacCode = $FacCode AND site.HptCode ='$HptCode'
              GROUP BY MONTH (clean.DocDate)
              ORDER BY clean.DocDate ASC";
    } else {
      $Sql = "SELECT 	factory.FacName,
              site.HptName ,
              DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE YEAR(clean.DocDate) = $year AND MONTH(clean.DocDate) BETWEEN $date1 AND $date2
              GROUP BY YEAR (clean.DocDate)
              ORDER BY clean.DocDate ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT 	factory.FacName,
              site.HptName ,
              DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE clean.DocDate LIKE '%$date1%' AND factory.FacCode = $FacCode AND site.HptCode ='$HptCode'
              GROUP BY YEAR (clean.DocDate)
              ORDER BY clean.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Summary_Dirty.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r13';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r13';
    return $return;
  }
}
function r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
          FROM dirty
          INNER JOIN process ON dirty.DocNo = process.DocNo
          INNER JOIN factory ON dirty.FacCode = factory.FacCode
          WHERE dirty.DocDate LIKE '%$date1%' 
          GROUP BY  dirty.DocDate
          ORDER BY dirty.DocNo ASC";
    } else {
      $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
            FROM dirty
            INNER JOIN process ON dirty.DocNo = process.DocNo
            INNER JOIN factory ON dirty.FacCode = factory.FacCode
            WHERE dirty.DocDate BETWEEN '$date1' AND '$date2'
            GROUP BY MONTH (dirty.DocDate)
            ORDER BY dirty.DocNo ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
      FROM dirty
      INNER JOIN process ON dirty.DocNo = process.DocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      WHERE dirty.DocDate LIKE '%$date1%'
      GROUP BY MONTH (dirty.DocDate)
      ORDER BY dirty.DocNo ASC";
    } else {
      $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
      FROM dirty
      INNER JOIN process ON dirty.DocNo = process.DocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      WHERE YEAR(dirty.DocDate) = $year AND MONTH(dirty.DocDate) BETWEEN $date1 AND $date2
      GROUP BY YEAR (dirty.DocDate)
      ORDER BY dirty.DocNo ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
      FROM dirty
      INNER JOIN process ON dirty.DocNo = process.DocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      WHERE dirty.DocDate LIKE '%$date1%'
      GROUP BY YEAR (dirty.DocDate)
      ORDER BY dirty.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Tracking_status_for_laundry_plant.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }

  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r15';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r15';
    return $return;
  }
}
function r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%'
			GROUP BY DATE(shelfcount.DocDate)
			ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
            WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
            GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC
            ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%' 
      GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      WHERE YEAR(shelfcount.DocDate) = $year AND MONTH(shelfcount.DocDate) BETWEEN $date1 AND $date2
      GROUP BY shelfcount.DocDate
            ORDER BY shelfcount.DocNo ASC";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      WHERE shelfcount.DocDate LIKE '%$date1%' 
      GROUP BY YEAR(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Tracking_status_for_linen_operation.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r16';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r16';
    return $return;
  }
}
function r17($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      site.HptName,
      department.DepName
      FROM damage
      INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
      INNER JOIN department ON damage.DepCode=department.DepCode
      INNER JOIN site ON department.HptCode=site.HptCode
      WHERE damage.DocDate LIKE '%$date1%'
      GROUP BY date(damage.DocDate)
      ORDER BY damage.DocNo ASC
      ";
    } else {
      $Sql = "SELECT
      site.HptName,
      department.DepName
      FROM damage
      INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
      INNER JOIN department ON damage.DepCode=department.DepCode
      INNER JOIN site ON department.HptCode=site.HptCode
      WHERE damage.DocDate BETWEEN '$date1' AND '$date2'
      GROUP BY month(damage.DocDate)
      ORDER BY damage.DocNo ASC
            ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year = $date['year'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
      site.HptName,
      department.DepName
      FROM damage
      INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
      INNER JOIN department ON damage.DepCode=department.DepCode
      INNER JOIN site ON department.HptCode=site.HptCode
      WHERE damage.DocDate LIKE '%$date1%' 
      GROUP BY month(damage.DocDate)
      ORDER BY damage.DocNo ASC
";
    } else {
      $Sql = "SELECT
      site.HptName,
      department.DepName
      FROM damage
      INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
      INNER JOIN department ON damage.DepCode=department.DepCode
      INNER JOIN site ON department.HptCode=site.HptCode
      WHERE YEAR(damage.DocDate) = $year AND MONTH(damage.DocDate) BETWEEN $date1 AND $date2
      GROUP BY YEAR(damage.DocDate)
      ORDER BY damage.DocNo ASC
";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
    site.HptName,
    department.DepName
    FROM damage
    INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
    INNER JOIN department ON damage.DepCode=department.DepCode
    INNER JOIN site ON department.HptCode=site.HptCode
      WHERE damage.DocDate LIKE '%$date1%' 
      GROUP BY YEAR(damage.DocDate)
      ORDER BY damage.DocNo ASC
";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Damaged_And_Loss.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r17';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r17';
    return $return;
  }
}

//=========================================================
//
//=========================================================
if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'OnLoadPage') {
    OnLoadPage($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'find_report') {
    find_report($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'departmentWhere') {
    departmentWhere($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
