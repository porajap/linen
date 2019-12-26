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
  $count_cycle = 0;
  $count_main = 0;
  $countG = 0;
  $count_item_sc  =  0;
  $count_time_dirty = 0;
  $HptCode = $_SESSION['HptCode'];
  $FacCode = $_SESSION['FacCode'];
  $DepCode = $_SESSION['DepCode'];
  $GroupCode =  $_SESSION['GroupCode'];
  $lang = $_SESSION['lang'];
  if ($lang == 'th') {
    $HptName = HptNameTH;
    $FacName = FacNameTH;
  } else {
    $HptName = HptName;
    $FacName = FacName;
  }
  $Sqlx = "SELECT factory.FacCode,factory.$FacName FROM factory WHERE factory.IsCancel = 0 AND factory.HptCode =  '$HptCode' ";
  $meQueryx = mysqli_query($conn, $Sqlx);
  while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
    $return[$countx]['FacCode'] = trim($Resultx['FacCode']);
    $return[$countx]['FacName'] = trim($Resultx[$FacName]);
    $countx++;
  }
  $return['Rowx'] = $countx;

  $Sql = "SELECT site.HptCode,site.$HptName FROM site WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = trim($Result['HptCode']);
    $return[$count]['HptName'] = trim($Result[$HptName]);
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;

  $SqlG = "SELECT grouphpt.GroupName,grouphpt.GroupCode FROM grouphpt WHERE grouphpt.HptCode = '$HptCode' ";
  $meQuery = mysqli_query($conn, $SqlG);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$countG]['GroupCode'] = trim($Result['GroupCode']);
    $return[$countG]['GroupName'] = trim($Result['GroupName']);
    $countG++;
    $boolean = true;
  }
  $return['RowG'] = $countG;
  $return['SqlG'] = $SqlG;

  $Sql = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' and department.isDefault= 1  order by department.DepName asc";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$countDep]['DepCode'] = trim($Result['DepCode']);
    $return[$countDep]['DepName'] = trim($Result['DepName']);
    $countDep++;
    $boolean = true;
  }

  $Sql = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' and department.isDefault= 0 and department.isActive= 1  order by department.DepName asc";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$countDep]['DepCode'] = trim($Result['DepCode']);
    $return[$countDep]['DepName'] = trim($Result['DepName']);
    $countDep++;
    $boolean = true;
  }
  $return['RowDep'] = $countDep;

  $Sql = "SELECT
  item.itemname,
  item.itemcode
  FROM
  shelfcount_detail
  INNER JOIN item ON item.itemcode = shelfcount_detail.itemcode
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  WHERE
    shelfcount.isStatus <> 9
    AND shelfcount_detail.TotalQty <> 0
    GROUP BY item.itemcode ORDER BY item.ItemName ASC ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count_item_sc]['itemname'] = trim($Result['itemname']);
    $return[$count_item_sc]['itemcode'] = trim($Result['itemcode']);
    $count_item_sc++;
    $boolean = true;
  }
  $return['count_item_sc'] = $count_item_sc;

  $Sql = "SELECT shelfcount.CycleTime FROM shelfcount  Group by shelfcount.CycleTime  ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count_cycle]['CycleTime'] = trim($Result['CycleTime']);
    $count_cycle++;
    $boolean = true;
  }
  $return['Rowcycle'] = $count_cycle;
  $boolean = true;


  $Sql = "SELECT time_dirty.TimeName,time_dirty.id FROM time_dirty 
  INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id  
  WHERE round_time_dirty.HptCode = '$HptCode'
  Group by time_dirty.TimeName  ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count_time_dirty]['TimeName'] = trim($Result['TimeName']);
    $return[$count_time_dirty]['id'] = trim($Result['id']);
    $count_time_dirty++;
    $boolean = true;
  }
  $return['count_time_dirty'] = $count_time_dirty;
  $boolean = true;


  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    $return['alldep29'] = "r30";
    $return['alldep30'] = "r29";
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
  $lang = $_SESSION['lang'];
  if ($lang == 'th') {
    $HptName = HptNameTH;
    $FacName = FacNameTH;
  } else {
    $HptName = HptName;
    $FacName = FacName;
  }
  $HptCode = $DATA['HptCode'];
  $GroupCode = $DATA['GroupCode'];
  if ($GroupCode == 0) {
    $Sql1 = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode'  AND department.isDefault= 1  ORDER BY department.DepName ASC ";
    $Sql2 = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' AND department.isDefault= 0 AND department.isActive= 1  ORDER BY department.DepName ASC ";
  } else {
    $Sql1 = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' AND department.GroupCode = '$GroupCode'  AND department.isDefault= 1  ORDER BY department.DepName ASC ";
    $Sql2 = "SELECT department.DepCode,department.DepName FROM department WHERE department.HptCode = '$HptCode' AND department.GroupCode = '$GroupCode' AND department.isDefault= 0 AND department.isActive= 1  ORDER BY department.DepName ASC ";
  }
  $Sql3 = "SELECT factory.FacCode,factory.$FacName FROM factory WHERE factory.IsCancel = 0 AND factory.HptCode =  '$HptCode' ";

  $count = 0;
  $countfac = 0;
  $count_time_dirty = 0;
  $boolean = false;
  $meQuery = mysqli_query($conn, $Sql1);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = trim($Result['DepCode']);
    $return[$count]['DepName'] = trim($Result['DepName']);
    $count++;
    $boolean = true;
  }
  $meQuery = mysqli_query($conn, $Sql2);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = trim($Result['DepCode']);
    $return[$count]['DepName'] = trim($Result['DepName']);
    $count++;
    $boolean = true;
  }
  $meQuery = mysqli_query($conn, $Sql3);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$countfac]['FacCode'] = trim($Result['FacCode']);
    $return[$countfac]['FacName'] = trim($Result[$FacName]);
    $countfac++;
    $boolean = true;
  }
  $Sql = "SELECT time_dirty.TimeName,time_dirty.id FROM time_dirty 
  INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id  
  WHERE round_time_dirty.HptCode = '$HptCode'
  Group by time_dirty.TimeName  ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count_time_dirty]['TimeName'] = trim($Result['TimeName']);
    $return[$count_time_dirty]['id'] = trim($Result['id']);
    $count_time_dirty++;
    $boolean = true;
  }
  $return['count_time_dirty'] = $count_time_dirty;
  $return['Row'] = $count;
  $return['Rowfac'] = $countfac;
  $boolean = true;
  if ($boolean) {
    $return['555'] = $Sql1;
    $return['555'] = $Sql2;
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
  $ppu = $DATA['ppu'];
  $GroupCode = $DATA['GroupCode'];
  $Item = $DATA['Item'];
  $time_dirty = $DATA['time_dirty'];
  $Userid = $_SESSION['Userid'];
  $date1 = '';
  $date2 = '';
  if ($typeReport == 1) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $time_dirty, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $time_dirty, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $time_dirty, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $time_dirty, 'monthbetween');
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
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $cycle, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $cycle, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $cycle, 'monthbetween');
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
    $return = r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
  } else if ($typeReport == 13) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $ppu, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $ppu, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $ppu, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $ppu, 'monthbetween');
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
  } else if ($typeReport == 18) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r18($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r18($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r18($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r18($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, 'monthbetween');
      }
    }
  } else if ($typeReport == 19) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r19($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r19($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r19($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r19($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'monthbetween');
      }
    }
  } else if ($typeReport == 20) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r20($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r20($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r20($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r20($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'monthbetween');
      }
    }
  } else if ($typeReport == 21) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r21($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r21($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r21($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r21($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, 'monthbetween');
      }
    }
  } else if ($typeReport == 22) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r22($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r22($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r22($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r22($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 23) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r23($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r23($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r23($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r23($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 24) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r24($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r24($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r24($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r24($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 25) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r25($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r25($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r25($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r25($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 26) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r26($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r26($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r26($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r26($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 27) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r27($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r27($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r27($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r27($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 28) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r28($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode,  $GroupCode,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r28($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode,  'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r28($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode,  'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r28($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode,  'monthbetween');
      }
    }
  } else if ($typeReport == 29) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r29($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r29($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r29($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r29($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, 'monthbetween');
      }
    }
  } else if ($typeReport == 30) {
    if ($Format == 1 || $Format == 3) {
      if ($FormatDay == 1 || $Format == 3) {
        $date1 = $date;
        $return = r30($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item,  'one');
      } else {
        $date1 = newDate1($date);
        $date2 = newDate2($date);
        $return = r30($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, 'between');
      }
    } else if ($Format == 2) {
      if ($FormatMonth == 1) {
        $date1 = newMonth($date);
        $return = r30($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, 'month');
      } else {
        $date1 = newMonth1($date);
        $date2 = newMonth2($date);
        $return = r30($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, 'monthbetween');
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
  if ($language == 'en') {
    $MonthArray = [
      '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October',
      '11' => 'November', '12' => 'December'
    ];
  } else {
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
  $date1 = ereg_replace('[[:space:]]+', '', trim($d1[0] . '-' . $d1[1] . '-' . $d1[2]));
  return $date1;
}
function newDate2($date)
{
  $sub = explode('-', $date);
  $d2 = explode('/', $sub[1]);
  $date2 = ereg_replace('[[:space:]]+', '', trim($d2[0] . '-' . $d2[1] . '-' . $d2[2]));
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
  $year1 = trim($month1[0]);
  $date1 = trim($month1[1]);
  $month2 = explode('-', $date2);
  $year2 = trim($month2[0]);
  $date2 = trim($month2[1]);
  $date['year1'] = $year1;
  $date['year2'] = $year2;
  $date['date1'] = $date1;
  $date['date2'] = $date2;

  return $date;
}
function typeReport($typeReport)
{
  $type = trim($typeReport);
  $lang = $_SESSION['lang'];
  if ($lang == 'en') {
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
        'Report Damaged ' => 17,
        'Report Tracking status for linen operation by Ward' => 18,
        'Report Tracking status for linen operation by User' => 19,
        'Report Summary billing' => 20,
        'Report Tdas' => 21,
        'Report New Linen' => 22,
        'Report repair' => 23,
        'Report Claim Factory' => 24,
        'Report_QC_Process_checklist_Dirty' => 25,
        'Report_QC_Process_checklist_clean1' => 26,
        'Report_QC_Process_checklist_clean2' => 27,
        'Report_billing' => 28,
        'Report SUMMARY' => 29,
        'Report Usage Detail' => 30
      ];
  } else {
    $typeArray =
      [
        'รายงานบันทึกการส่งผ้าเปื้อน' => 1,
        'รายงานบันทึกผ้าส่งเคลม' => 2,
        'รายงานบันทึกการรับผ้าสะอาด' => 3,
        'รายงานคำร้องขอการส่งผ้า' => 4,
        'Report Operations Time Spend' => 5,
        'รายงานบันทึกผ้าแก้ไข' => 6,
        'รายงานบันทึกผ้าขาด และผ้าเกินจากยอด' => 7,
        'รายงานส่งผ้าเปื้อน-รับผ้าสะอาด' => 8,
        'รายงานสต๊อกคงคลัง' => 9,
        'Report Billing Claim' => 10,
        'Report Billing Customer' => 11,
        'Report Billing Factory' => 12,
        'รายงานรวมบันทึกการแปรรูปผ้าสกปรก' => 13,
        'รายงานสรุปราคาการส่งผ้า' => 14,
        'รายงานการติดตามสถานะรายการผ้าจากโรงซัก' => 15,
        'Report Tracking status for linen operation' => 16,
        'รายงานสรุปยอดผ้าถูกซ่อมและผ้าสูญหาย' => 17,
        'รายงานการติดตามการจัดจ่ายผ้าโดยอิงจากแผนก' => 18,
        'รายงานการติดตามการจัดจ่ายผ้าโดยอิงจากผู้ใช้' => 19,
        'รายงานสรุปค่าบริการรับ-ส่งผ้าประจำเดือน' => 20,
        'รายงาน T-das' => 21,
        'รายงานแบบบันทึกการส่งซักผ้าใหม่ให้โรงซัก' => 22,
        'รายงานผ้าชำรุด NHealth' => 23,
        'รายงานเคลมโรงซัก' => 24,
        'Report_QC_Process_checklist_Dirty' => 25,
        'Report_QC_Process_checklist_clean1' => 26,
        'Report_QC_Process_checklist_clean2' => 27,
        'Report_billing' => 28,
        'Report SUMMARY' => 29,
        'Report Usage Detail' => 30
      ];
  }
  $myReport = array_search($type, $typeArray);
  return $myReport;
}
#----------------------------Format new date

function r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $time_dirty, $chk)
{
  $boolean = false;
  $count = 0;
  if ($time_dirty == 0) {
    $time_dirty = '';
    $time_dirty_value = 0;
  } else {
    $time_dirty_value = $time_dirty;
    $time_dirty = " AND time_dirty.ID  = '$time_dirty'";
  }
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  
              DATE(dirty.DocDate) AS DocDate1,
              site.hptname,
              factory.facname
              FROM dirty
              INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON dirty_detail.DepCode = department.DepCode
              INNER JOIN site ON site.hptcode = department.hptcode
              INNER JOIN time_dirty ON time_dirty.id = dirty.Time_ID
              WHERE DATE(dirty.DocDate) = DATE('$date1')
              AND dirty.FacCode = $FacCode
              AND site.HptCode = '$HptCode'
              $time_dirty
              AND dirty.isStatus <> 9 
              GROUP BY dirty.DocDate
              ORDER BY dirty.DocDate ASC
              limit 1";
    } else {
      $Sql = "SELECT  
              DATE(dirty.DocDate) AS DocDate1,
              site.hptname,
              factory.facname
              FROM dirty
              INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON dirty_detail.DepCode = department.DepCode
              INNER JOIN site ON site.hptcode = department.hptcode
              INNER JOIN time_dirty ON time_dirty.id = dirty.Time_ID
              WHERE dirty.DocDate BETWEEN '$date1' AND '$date2'
              AND dirty.FacCode = $FacCode
              AND site.HptCode = '$HptCode'
              $time_dirty
              AND dirty.isStatus <> 9 
              GROUP BY MONTH(dirty.DocDate)
              ORDER BY dirty.DocDate ASC limit 1";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      INNER JOIN department ON dirty_detail.DepCode = department.DepCode
      INNER JOIN site ON site.hptcode = department.hptcode
      INNER JOIN time_dirty ON time_dirty.id = dirty.Time_ID
                WHERE MONTH(dirty.DocDate)= '$date1'
                AND dirty.FacCode = $FacCode
                AND site.HptCode = '$HptCode'
                AND dirty.isStatus <> 9 
                $time_dirty
                 GROUP BY MONTH(dirty.DocDate)
                 ORDER BY dirty.DocDate ASC limit 1";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT  
      MONTH(dirty.DocDate) AS DocDate1,
      site.hptname,
      factory.facname
      FROM dirty
      INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
      INNER JOIN factory ON factory.FacCode = dirty.FacCode
      INNER JOIN department ON dirty_detail.DepCode = department.DepCode
      INNER JOIN site ON site.hptcode = department.hptcode
      INNER JOIN time_dirty ON time_dirty.id = dirty.Time_ID
      WHERE DATE(dirty.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
              AND dirty.FacCode = $FacCode
              AND site.HptCode = '$HptCode'
              AND dirty.isStatus <> 9 
              $time_dirty
              GROUP BY YEAR(dirty.DocDate)
              ORDER BY  dirty.DocNo ASC LIMIT 1 ";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT  
    site.hptname,
    factory.facname
    FROM dirty
    INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
    INNER JOIN factory ON factory.FacCode = dirty.FacCode
    INNER JOIN department ON dirty_detail.DepCode = department.DepCode
    INNER JOIN site ON site.hptcode = department.hptcode
    INNER JOIN time_dirty ON time_dirty.id = dirty.Time_ID
    WHERE YEAR(dirty.DocDate)= '$date1'
              AND dirty.FacCode = $FacCode
              AND site.HptCode = '$HptCode'
              AND dirty.isStatus <> 9 
              $time_dirty
               GROUP BY year(dirty.DocDate)
               ORDER BY dirty.DocDate ASC limit 1";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2, 'time_dirty' => $time_dirty_value];
  // $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Dirty_Linen_Weight.php';
  $return['urlxls'] = '../report_linen/excel/Report_Dirty_Linen_Weight_xls.php';
  $return['555'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocDate3'] = $Result['DocDate3'];
    $return[$count]['DocDate2'] = $Result['DocDate2'];
    $return[$count]['FacName'] = $Result['facname'];
    $return[$count]['HptName'] = $Result['hptname'];
    $boolean = true;
    $count++;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['countRow'] = $count;
    $return['form'] = 'Fac';
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Fac';
    return $return;
  }
}
function r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  if ($DepCode == "all") {
    $DepCode = " ";
  } else {
    $DepCode = "AND department.DepCode = $DepCode";
  }
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT department.DepName, 
      claim.DocDate,
      factory.facname,
      site.Hptname,
      claim.Docno
      FROM
      claim ,
      clean ,
      dirty ,
      factory ,
      department ,
      site
      WHERE DATE(claim.DocDate) = '$date1'
      AND site.HptCode = '$HptCode'
      
      AND factory.FacCode = $FacCode
      GROUP BY claim.DocDate
      ORDER BY claim.DocDate ASC";
    } else {
      $Sql = "SELECT department.DepName, 
      claim.DocDate,
      factory.facname,
      site.Hptname,
      claim.Docno
      FROM
      claim ,
      clean ,
      dirty ,
      factory ,
      department ,
      site
              WHERE claim.Docdate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              
              AND factory.FacCode = $FacCode
              GROUP BY MONTH (claim.DocDate) ORDER BY claim.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT department.DepName, 
      claim.DocDate,
      factory.facname,
      site.Hptname,
      claim.Docno
      FROM
      claim ,
      clean ,
      dirty ,
      factory ,
      department ,
      site
      WHERE MONTH(claim.DocDate) = '$date1'
      AND site.HptCode = '$HptCode'
      
      AND factory.FacCode = $FacCode
      GROUP BY MONTH (claim.DocDate)
      ORDER BY claim.DocDate ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT department.DepName, 
      claim.DocDate,
      factory.facname,
      site.Hptname,
      claim.Docno
      FROM
      claim ,
      clean ,
      dirty ,
      factory ,
      department ,
      site
    WHERE DATE(claim.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND site.HptCode = '$HptCode'
      
      AND factory.FacCode = $FacCode
      GROUP BY YEAR (claim.DocDate)
      ORDER BY claim.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT department.DepName, 
    claim.DocDate,
    factory.facname,
    site.Hptname,
    claim.Docno
    FROM
    claim ,
    clean ,
    dirty ,
    factory ,
    department ,
    site
              WHERE  YEAR(claim.DocDate) =  '$date1'
              AND site.HptCode = '$HptCode'
              
               AND factory.FacCode = $FacCode
              GROUP BY YEAR (claim.DocDate) ORDER BY claim.DocDate ASC";
  }
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  // $_SESSION['data_send'] = $data_send;
  $return['Sql'] = $Sql;
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
  $return['data_send'] = $data_send;
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
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN clean_detail ON clean.DocNo=clean_detail.DocNo
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE DATE(clean.DocDate) = DATE('$date1')
              AND site.HptCode = '$HptCode'
              AND clean.FacCode = $FacCode
              AND clean.isStatus <> 9 
              GROUP BY Date(clean.DocDate)
              ORDER BY clean.DocDate ASC limit 1";
    } else {
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE clean.DocDate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              AND clean.FacCode = $FacCode
              AND clean.isStatus <> 9 
              GROUP BY MONTH (clean.Docdate)
              ORDER BY clean.DocDate ASC limit 1";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE MONTH(clean.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              AND clean.FacCode = $FacCode
              AND clean.isStatus <> 9 
              GROUP BY MONTH (clean.Docdate)
              ORDER BY clean.DocDate ASC limit 1";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE DATE(clean.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
           AND site.HptCode = '$HptCode'
           AND clean.FacCode = $FacCode
           AND clean.isStatus <> 9 
           GROUP BY YEAR (clean.Docdate)
           ORDER BY clean.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              WHERE YEAR(clean.DocDate) = '$date1'
             AND site.HptCode = '$HptCode'
             AND clean.FacCode = $FacCode
             AND clean.isStatus <> 9 
             GROUP BY YEAR (clean.Docdate)
             ORDER BY clean.DocDate ASC limit 1";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Cleaned_Linen_Weight.php';
  $return['urlxls'] = '../report_linen/excel/Report_Cleaned_Linen_Weight_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Fac';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Fac';
    return $return;
  }
}
function r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $cycle, $chk)
{
  if ($DepCode == "ALL") {
    $DepCode  = "0";
    $DepCode1 = "";
  } else {
    $DepCode = "$DepCode";
    $DepCode1 = "AND shelfcount.DepCode = '$DepCode'";
  }
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
      WHERE DATE(shelfcount.DocDate) = '$date1' 
      $DepCode1
       AND shelfcount.isStatus <> 9 
      ";
    } else {
      $Sql = "SELECT shelfcount.DocNo,
      DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
      $DepCode1
      AND shelfcount.isStatus <> 9 
    ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate,
      TIME(shelfcount.DocDate) AS DocTime,
      department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE MONTH(shelfcount.DocDate) = '$date1'
      $DepCode1
      AND shelfcount.isStatus <> 9 
     ";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      $DepCode1
      AND shelfcount.isStatus <> 9 
      ";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
              FROM shelfcount
              INNER JOIN department ON shelfcount.DepCode = department.DepCode
              WHERE YEAR(shelfcount.DocDate) = '$date1'
              $DepCode1
              AND shelfcount.isStatus <> 9 
             ";
  }
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'cycle' => $cycle, 'chk' => $chk];
  // $_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Daily_Issue_Request.php';
  $return['urlxls'] = '../report_linen/excel/Report_Daily_issue_Request_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    list($y, $m, $d) = explode("-", $Result['DocDate']);
    if ($_SESSION['lang'] == 'th') {
      $y = $y + 543;
      $Result['DocDate'] = $d . '-' . $m . '-' . $y;
    } else {
      $Result['DocDate'] = $d . '-' . $m . '-' . $y;
    }
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'DepDoc';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'DepDoc';
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
      WHERE DATE(shelfcount.DocDate) = '$date1'
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND department.DepCode = $DepCode
      GROUP BY YEAR(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
              FROM shelfcount
              INNER JOIN department ON shelfcount.DepCode = department.DepCode
              WHERE shelfcount.DocDate LIKE '%$date1%'
              GROUP BY YEAR(shelfcount.Docdate) ORDER BY shelfcount.DocNo ASC";
  }
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  // $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Daily_Issue_Request.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
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
              factory.FacName, DATE(repair_wash.DocDate) AS DocDate,
              site.hptname,
              department.DepName
              FROM repair_wash
              INNER JOIN repair_wash_detail ON repair_wash_detail.DocNo = repair_wash.DocNo
              INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
              INNER JOIN department ON repair_wash.DepCode =  department.DepCode
              INNER JOIN site ON department.hptcode = site.hptcode
              WHERE DATE(repair_wash.DocDate) = '$date1'
              AND repair_wash.FacCode = $FacCode
              AND site.hptcode = '$HptCode'
              GROUP BY repair_wash.DocDate
              ORDER BY repair_wash.DocDate ASC LIMIT 1";
    } else {
      $Sql = "SELECT
              factory.FacName, month(repair_wash.DocDate) AS DocDate,
              site.hptname,
              department.DepName
              FROM repair_wash
              INNER JOIN repair_wash_detail ON repair_wash_detail.DocNo = repair_wash.DocNo
              INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
              INNER JOIN department ON repair_wash.DepCode =  department.DepCode
              INNER JOIN site ON department.hptcode = site.hptcode
              WHERE repair_wash.DocDate BETWEEN '$date1' AND '$date2'
              AND repair_wash.FacCode = $FacCode
              AND site.hptcode = '$HptCode'
              GROUP BY MONTH(repair_wash.DocDate)
              ORDER BY repair_wash.DocDate ASC  LIMIT 1";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT  factory.FacName, month(repair_wash.DocDate) AS DocDate,
      site.hptname,
      department.DepName
      FROM repair_wash
      INNER JOIN repair_wash_detail ON repair_wash_detail.DocNo = repair_wash.DocNo
      INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
      INNER JOIN department ON repair_wash.DepCode =  department.DepCode
      INNER JOIN site ON department.hptcode = site.hptcode
      WHERE MONTH(repair_wash.DocDate) = '$date1' 
      AND repair_wash.FacCode = $FacCode
      AND site.hptcode = '$HptCode'
      GROUP BY MONTH (repair_wash.DocDate)
      ORDER BY repair_wash.DocDate ASC  LIMIT 1";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT  factory.FacName, year(repair_wash.DocDate) AS DocDate,
      site.hptname,
      department.DepName
      FROM repair_wash
      INNER JOIN repair_wash_detail ON repair_wash_detail.DocNo = repair_wash.DocNo
      INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
      INNER JOIN department ON repair_wash.DepCode =  department.DepCode
      INNER JOIN site ON department.hptcode = site.hptcode
      WHERE DATE(repair_wash.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND repair_wash.FacCode = $FacCode
      AND site.hptcode = '$HptCode'
      GROUP BY YEAR (repair_wash.DocDate)
      ORDER BY repair_wash.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT  factory.FacName, year(repair_wash.DocDate) AS DocDate,
    site.hptname,
    department.DepName
            FROM repair_wash
            INNER JOIN repair_wash_detail ON repair_wash_detail.DocNo = repair_wash.DocNo
            INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
            INNER JOIN department ON repair_wash.DepCode =  department.DepCode
            INNER JOIN site ON department.hptcode = site.hptcode
            WHERE YEAR(repair_wash.DocDate) = '$date1'
            AND repair_wash.FacCode = $FacCode
            AND site.hptcode = '$HptCode'
            GROUP BY YEAR (repair_wash.DocDate)
            ORDER BY repair_wash.DocDate ASC  LIMIT 1";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];
  // $_SESSION['data_send'] = $data_send;
  $return['Sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Rewash.php';
  $return['urlxls'] = '../report_linen/excel/Report_Rewash_xls.php';

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['HptName'] = $Result['hptname'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Fac';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Fac';
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
      department.DepName,
      department.DepCode
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
      WHERE DATE(shelfcount.DocDate) = '$date1'  
      AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
      AND shelfcount.isStatus <> 9
      AND shelfcount.isStatus <> 0
      AND department.HptCode = '$HptCode'
      GROUP BY department.DepCode ";
    } else {
      $Sql = "SELECT
      department.DepName,
      department.DepCode
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode AND shelfcount.isStatus <> 9
      AND shelfcount.isStatus <> 0
      WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
      AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
      AND shelfcount.isStatus <> 9
      AND shelfcount.isStatus <> 0
      AND department.HptCode = '$HptCode'
      GROUP BY department.DepCode";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT
      department.DepName,
      department.DepCode
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
      WHERE MONTH(shelfcount.DocDate) = '$date1'  
      AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
      AND shelfcount.isStatus <> 9
      AND shelfcount.isStatus <> 0
      AND department.HptCode = '$HptCode'
            GROUP BY department.DepCode";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      department.DepName,
      department.DepCode
      FROM
      shelfcount
      INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
      INNER JOIN department ON department.depcode = shelfcount.DepCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'       
      AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
      AND shelfcount.isStatus <> 9
      AND shelfcount.isStatus <> 0
      AND department.HptCode = '$HptCode'
            GROUP BY department.DepCode";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      department.DepName,
      department.DepCode
    FROM
    shelfcount
    INNER JOIN shelfcount_detail ON shelfcount.DocNo =  shelfcount_detail.DocNo
    INNER JOIN department ON department.depcode = shelfcount.DepCode
    WHERE YEAR(shelfcount.DocDate) = '$date1'       
    AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
    AND shelfcount.isStatus <> 9
    AND shelfcount.isStatus <> 0
    AND department.HptCode = '$HptCode'
            GROUP BY department.DepCode";
  }
  $return['ql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2, 'item' => $Item];

  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Shot_and_Over_item_tc.php';
  $return['urlxls'] = '../report_linen/excel/Report_Shot_And_Over_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['department'][$count]['DepName'] = $Result['DepName'];
    $return['department'][$count]['DepCode'] = $Result['DepCode'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Dep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['statusDep'] = 'alldepartment';
    $return['r'] = 'r7';
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
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
            WHERE DATE(clean.DocDate) = '$date1'  
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      WHERE MONTH(clean.DocDate) = '$date1'  AND dirty.FacCode = $FacCode
      AND department.HptCode = '$HptCode'
      GROUP BY MONTH(clean.DocDate)";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT factory.FacName, 
      DATE(clean.DocDate) AS DocDate,
       site.HptName,
      department.DepName
    FROM clean
    INNER JOIN department ON department.DepCode = clean.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    INNER JOIN dirty ON clean.refdocno = dirty.docno
    INNER JOIN factory ON dirty.FacCode = factory.FacCode
    WHERE DATE(clean.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND department.HptCode = '$HptCode'
      AND dirty.FacCode = $FacCode
      GROUP BY YEAR(clean.DocDate) LIMIT 1";
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
            WHERE YEAR(clean.DocDate) = '$date1'
            AND department.HptCode = '$HptCode'
            AND dirty.FacCode = $FacCode
            GROUP BY YEAR(clean.DocDate)";
  }
  $return['ql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'year1' => $year1, 'year2' => $year2, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Soiled_Clean_Ratio.php';
  $return['urlxls'] = '../report_linen/excel/Report_Soiled_Clean_Ratio_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Fac';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Fac';
    return $return;
  }
}
function r9($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  $Sql = "SELECT  
  department.DepName,
  site.HptName  FROM  par_item_stock 
  INNER JOIN department ON par_item_stock.DepCode = department.DepCode 
  INNER JOIN site ON department.HptCode = site.HptCode 
  INNER JOIN item ON item.itemCode = par_item_stock.itemCode 
                WHERE par_item_stock.DepCode= '$DepCode'
                AND par_item_stock.HptCode= '$HptCode'
                GROUP BY DATE(par_item_stock.DepCode)";

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,  'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2,  'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Stock_Count.php';
  $return['urlxls'] = '../report_linen/excel/Report_Stock_Count_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['department'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['ExpireDateX'];
    $return[$count]['DocTime'] = $Result['DocTime'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Dep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = '9';
    $return['statusDep'] = 'somedepartment';
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Dep';
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
              WHERE DATE(claim.DocDate) = '$date1'
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT
              site.HptName , DATE(claim.DocDate) AS DocDate
              FROM claim_detail
              INNER JOIN claim on claim.docno = claim_detail.DocNo
              INNER JOIN site on site.HptCode = claim.HptCode
              WHERE MONTH(claim.DocDate) = '$date1'
              AND claim.HptCode = '$HptCode'
              AND claim.DepCode = '$DepCode'
              GROUP BY MONTH (claim.DocDate)
              ORDER BY claim.DocDate ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      site.HptName , DATE(claim.DocDate) AS DocDate
      FROM claim_detail
      INNER JOIN claim on claim.docno = claim_detail.DocNo
      INNER JOIN site on site.HptCode = claim.HptCode
      WHERE DATE(claim.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = '$DepCode'
      GROUP BY year (claim.DocDate)
      ORDER BY claim.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      site.HptName , DATE(claim.DocDate) AS DocDate
      FROM claim_detail
      INNER JOIN claim on claim.docno = claim_detail.DocNo
      INNER JOIN site on site.HptCode = claim.HptCode
      WHERE YEAR(claim.DocDate) = '$date1'
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = '$DepCode'
      GROUP BY year (claim.DocDate)
       ORDER BY claim.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Billing_Claim.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      site.HptName ,
      DATE(billcustomer.DocDate) AS DocDate
      FROM
      billcustomer_detail
      INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
      INNER JOIN site on site.HptCode = billcustomer.HptCode
      WHERE DATE(billcustomer.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND billcustomer.HptCode = '$HptCode'
      AND billcustomer.DepCode = '$DepCode'
      GROUP BY year (billcustomer.DocDate)
      ORDER BY billcustomer.DocDate ASC LIMIT 1";
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

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Billing_Customer.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      site.HptName ,
      DATE(billwash.DocDate) AS DocDate
      FROM
      billwash_detail
      INNER JOIN billwash on billwash.docno = billwash_detail.DocNo
      INNER JOIN site on site.HptCode = billwash.HptCode
      WHERE DATE(billwash_detail.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND billwash.HptCode = '$HptCode'
      AND billwash.DepCode = '$DepCode'
      GROUP BY year (billwash.DocDate)
      ORDER BY billwash.DocDate ASC LIMIT 1";
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

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  // $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Billing_Factory.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    // $return[$count]['DocTime'] = $Result['DocTime'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
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
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE DATE(shelfcount.DocDate) = '$date1'
      AND site.HptCode ='$HptCode'
      AND shelfcount.DepCode = $DepCode
      GROUP BY date(shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    } else {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'  AND site.HptCode ='$HptCode'
      AND shelfcount.DepCode = $DepCode
      GROUP BY MONTH (shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE MONTH(shelfcount.DocDate) = '%$date1%'  AND site.HptCode ='$HptCode'
      AND shelfcount.DepCode = $DepCode
      GROUP BY MONTH (shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT 	department.DepName,DocDate,site.HptName
      FROM shelfcount
      INNER JOIN department ON shelfcount.DepCode = department.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND site.HptCode ='$HptCode'
      AND shelfcount.DepCode = $DepCode
      GROUP BY YEAR (shelfcount.DocDate)
      ORDER BY shelfcount.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT 	department.DepName,DocDate,site.HptName
    FROM shelfcount
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    WHERE YEAR(shelfcount.DocDate) = '$date1'  AND site.HptCode ='$HptCode'
      AND shelfcount.DepCode = $DepCode
    GROUP BY YEAR (shelfcount.DocDate) AND shelfcount.DepCode
    ORDER BY shelfcount.DocDate  ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2, 'item' => $Item];
  //$_SESSION['data_send'] = $data_send;
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
  $return['data_send'] = $data_send;
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
function r13($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $ppu, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT 	factory.FacName,
      site.HptName ,
      item_main_category.MainCategoryName ,
      DATE(clean.DocDate) AS DocDate
      FROM clean
      INNER JOIN department ON clean.DepCode = department.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
      INNER JOIN item ON clean_detail.ItemCode=item.ItemCode
      INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
      INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
              WHERE DATE(clean.DocDate) = '$date1'
              AND factory.FacCode = $FacCode
              AND site.HptCode ='$HptCode'
              AND item_main_category.MainCategoryCode = '$ppu'
              GROUP BY date(clean.DocDate)
              ORDER BY clean.DocDate ASC";
    } else {
      $Sql = "SELECT 	factory.FacName,
      site.HptName ,
      item_main_category.MainCategoryName ,
      DATE(clean.DocDate) AS DocDate
      FROM clean
      INNER JOIN department ON clean.DepCode = department.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
      INNER JOIN item ON clean_detail.ItemCode=item.ItemCode
      INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
      INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
              WHERE clean.DocDate BETWEEN '$date1' AND '$date2' AND factory.FacCode = $FacCode AND site.HptCode ='$HptCode'
              AND item_main_category.MainCategoryCode = '$ppu'
              GROUP BY MONTH (clean.DocDate)
              ORDER BY clean.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT 	factory.FacName,
      site.HptName ,
      DATE(clean.DocDate) AS DocDate,
      item_main_category.MainCategoryName 
      FROM clean
      INNER JOIN department ON clean.DepCode = department.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
      INNER JOIN item ON clean_detail.ItemCode=item.ItemCode
      INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
      INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
              WHERE MONTH(clean.DocDate) = '$date1' AND factory.FacCode = $FacCode AND site.HptCode ='$HptCode'
              AND item_main_category.MainCategoryCode = '$ppu'
              GROUP BY MONTH (clean.DocDate)
              ORDER BY clean.DocDate ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT 	factory.FacName,
      site.HptName ,
      DATE(clean.DocDate) AS DocDate,
      item_main_category.MainCategoryName
      FROM clean
      INNER JOIN department ON clean.DepCode = department.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
      INNER JOIN item ON clean_detail.ItemCode=item.ItemCode
      INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
      INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
      WHERE DATE(clean.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
              AND item_main_category.MainCategoryCode = '$ppu'
              AND factory.FacCode = $FacCode
              AND site.HptCode ='$HptCode'
              GROUP BY YEAR (clean.DocDate)
              ORDER BY clean.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT 	factory.FacName,
    site.HptName ,
    DATE(clean.DocDate) AS DocDate,
    item_main_category.MainCategoryName
    FROM clean
    INNER JOIN department ON clean.DepCode = department.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
    INNER JOIN factory ON dirty.FacCode = factory.FacCode
    INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
    INNER JOIN item ON clean_detail.ItemCode=item.ItemCode
    INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
    INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
              WHERE YEAR(clean.DocDate) = '$date1' AND factory.FacCode = $FacCode AND site.HptCode ='$HptCode'
              AND item_main_category.MainCategoryCode = '$ppu'
              GROUP BY YEAR (clean.DocDate)
              ORDER BY clean.DocDate ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'ppu' => $ppu];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Summary_Dirty.php';
  $return['Sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
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
  $doc = array(dirty, repair_wash, newlinentable);
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
        process.DocNo,
        process.FacCode,
        factory.FacName,
        dirty.DocDate AS DIRTY_DOC,
        newlinentable.DocDate AS NEWLINENTABLE_DOC,
        repair_wash.DocDate AS REPAIR_WASH_DOC
      FROM
        process
      INNER JOIN factory ON process.FacCode = factory.FacCode
      LEFT JOIN dirty ON dirty.DocNo = process.DocNo
      LEFT JOIN newlinentable ON newlinentable.DocNo = process.DocNo
      LEFT JOIN repair_wash ON repair_wash.DocNo = process.DocNo
      WHERE
        '$date1' IN (
          date(dirty.DocDate),
          date(newlinentable.DocDate),
          date(repair_wash.DocDate)
        ) 
        AND process.FacCode = $FacCode 
        AND process.isStatus <> 9
        AND 
  (
        dirty.HptCode = '$HptCode'
        OR newlinentable.HptCode = '$HptCode'
        OR repair_wash.HptCode = '$HptCode') limit 1";
      // }
    } else {
      $Sql = "SELECT
            process.DocNo,
            process.FacCode,
            factory.FacName,
            dirty.DocDate AS DIRTY_DOC,
            newlinentable.DocDate AS NEWLINENTABLE_DOC,
            repair_wash.DocDate AS REPAIR_WASH_DOC
          FROM
            process
          INNER JOIN factory ON process.FacCode = factory.FacCode
          LEFT JOIN dirty ON dirty.DocNo = process.DocNo
          LEFT JOIN newlinentable ON newlinentable.DocNo = process.DocNo
          LEFT JOIN repair_wash ON repair_wash.DocNo = process.DocNo
          WHERE
          dirty.DocDate BETWEEN '$date1' AND '$date2'
            OR
      newlinentable.DocDate BETWEEN '$date1' AND '$date2'
            OR
      repair_wash.DocDate BETWEEN '$date1' AND '$date2'
      AND process.FacCode = $FacCode
      AND process.isStatus <> 9
      AND dirty.HptCode ='$HptCode'
      AND 
      (
      dirty.HptCode = '$HptCode'
      OR newlinentable.HptCode = '$HptCode'
      OR repair_wash.HptCode = '$HptCode')limit 1";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
            process.DocNo,
            process.FacCode,
            factory.FacName,
            dirty.DocDate AS DIRTY_DOC,
            newlinentable.DocDate AS NEWLINENTABLE_DOC,
            repair_wash.DocDate AS REPAIR_WASH_DOC
          FROM
            process
          INNER JOIN factory ON process.FacCode = factory.FacCode
          LEFT JOIN dirty ON dirty.DocNo = process.DocNo
          LEFT JOIN newlinentable ON newlinentable.DocNo = process.DocNo
          LEFT JOIN repair_wash ON repair_wash.DocNo = process.DocNo
          WHERE
            '$date1' IN (
              MONTH(dirty.DocDate),
              MONTH(newlinentable.DocDate),
              MONTH(repair_wash.DocDate)
            )
            AND process.FacCode = $FacCode 
            AND process.isStatus <> 9
            AND 
      (
      dirty.HptCode = '$HptCode'
      OR newlinentable.HptCode = '$HptCode'
      OR repair_wash.HptCode = '$HptCode')limit 1";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
            process.DocNo,
            process.FacCode,
            factory.FacName,
            dirty.DocDate AS DIRTY_DOC,
            newlinentable.DocDate AS NEWLINENTABLE_DOC,
            repair_wash.DocDate AS REPAIR_WASH_DOC
          FROM
            process
          INNER JOIN factory ON process.FacCode = factory.FacCode
          LEFT JOIN dirty ON dirty.DocNo = process.DocNo
          LEFT JOIN newlinentable ON newlinentable.DocNo = process.DocNo
          LEFT JOIN repair_wash ON repair_wash.DocNo = process.DocNo
          WHERE
          dirty.DocDate BETWEEN '$betweendate1' AND '$betweendate2'
            OR
      newlinentable.DocDate BETWEEN '$betweendate1' AND '$betweendate2'
            OR
      repair_wash.DocDate BETWEEN '$betweendate1' AND '$betweendate2'
      AND process.FacCode = $FacCode
      AND process.isStatus <> 9
      AND 
      (
      dirty.HptCode = '$HptCode'
      OR newlinentable.HptCode = '$HptCode'
      OR repair_wash.HptCode = '$HptCode')limit 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
          process.DocNo,
          process.FacCode,
          factory.FacName,
          dirty.DocDate AS DIRTY_DOC, 
          newlinentable.DocDate AS NEWLINENTABLE_DOC,
          repair_wash.DocDate AS REPAIR_WASH_DOC
        FROM
          process
        INNER JOIN factory ON process.FacCode = factory.FacCode
        LEFT JOIN dirty ON dirty.DocNo = process.DocNo
        LEFT JOIN newlinentable ON newlinentable.DocNo = process.DocNo
        LEFT JOIN repair_wash ON repair_wash.DocNo = process.DocNo
        WHERE
          '$date1' IN (
            YEAR(dirty.DocDate),
            YEAR(newlinentable.DocDate),
            YEAR(repair_wash.DocDate)
          )
          AND process.FacCode = $FacCode
          AND process.isStatus <> 9
          AND 
      (
      dirty.HptCode = '$HptCode'
      OR newlinentable.HptCode = '$HptCode'
      OR repair_wash.HptCode = '$HptCode')limit 1 ";
  }


  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Tracking_status_for_laundry_plant.php';
  $return['urlxls'] = '../report_linen/excel/Report_Tracking_status_for_laundry_plant_xls.php';
  $return['555'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DIRTY_DOC'] = $Result['DIRTY_DOC'];
    $return[$count]['NEWLINENTABLE_DOC'] = $Result['NEWLINENTABLE_DOC'];
    $return[$count]['REPAIR_WASH_DOC'] = $Result['REPAIR_WASH_DOC'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Fac';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = 'r15';
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Fac';
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
      WHERE DATE(shelfcount.DocDate) = '$date1'
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      WHERE MONTH(shelfcount.DocDate) = '$date1' 
      GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      GROUP BY shelfcount.DocDate
            ORDER BY shelfcount.DocNo ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      WHERE YEAR(shelfcount.DocDate) = '$date1' 
      GROUP BY YEAR(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
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
  $return['data_send'] = $data_send;
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
      WHERE DATE(damage.DocDate) = '$date1'
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
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      WHERE MONTH(damage.DocDate) = '$date1' 
      GROUP BY month(damage.DocDate)
      ORDER BY damage.DocNo ASC
  ";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
        site.HptName,
        department.DepName
        FROM damage
        INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
        INNER JOIN department ON damage.DepCode=department.DepCode
        INNER JOIN site ON department.HptCode=site.HptCode
        WHERE DATE(damage.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
        GROUP BY YEAR(damage.DocDate)
        ORDER BY damage.DocNo ASC LIMIT 1
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
        WHERE YEAR(damage.DocDate) = '$date1' 
        GROUP BY YEAR(damage.DocDate)
        ORDER BY damage.DocNo ASC
  ";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  // $_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Damaged_And_Loss.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
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
function r18($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo,
      site.HptName
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode=department.HptCode
      WHERE DATE(shelfcount.DocDate) = '$date1'
			GROUP BY DATE(shelfcount.DocDate)
			ORDER BY shelfcount.DocNo ASC";
    } else {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo,
      site.HptName
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode=department.HptCode
            WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
            GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC
            ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo,
      site.HptName
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode=department.HptCode
      WHERE MONTH(shelfcount.DocDate) = '$date1' 
      GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo,
      site.HptName
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode=department.HptCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      GROUP BY shelfcount.DocDate
            ORDER BY shelfcount.DocNo ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
    department.DepName,
    shelfcount.DocDate,
    shelfcount.DocNo,
    site.HptName
    FROM
    shelfcount
    INNER JOIN department on department.DepCode = shelfcount.DepCode
    INNER JOIN site ON site.HptCode=department.HptCode
      WHERE YEAR(shelfcount.DocDate) = '$date1' 
      GROUP BY YEAR(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];

  // $_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Tracking_status_for_linen_operation_by_ward.php';
  $return['urlxls'] = '../report_linen/excel/Report_Tracking_status_for_linen_operation_by_ward_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'NoFacDep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['r'] = 'r18';
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
    return $return;
  }
}
function r19($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN users ON users.id = shelfcount.modify_Code
      WHERE DATE(shelfcount.DocDate) = '$date1'
      -- AND shelfcount.Modify_code = $Userid
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
      INNER JOIN users ON users.id = shelfcount.modify_Code
            WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
            -- AND shelfcount.Modify_code = $Userid
            GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC
            ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      INNER JOIN users ON users.id = shelfcount.modify_Code
      WHERE MONTH(shelfcount.DocDate) = '$date1' 
      -- AND shelfcount.Modify_code = $Userid
      GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN users ON users.id = shelfcount.modify_Code
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      -- AND shelfcount.Modify_code = $Userid
      GROUP BY shelfcount.DocDate
            ORDER BY shelfcount.DocNo ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN users ON users.id = shelfcount.modify_Code
      WHERE YEAR(shelfcount.DocDate) = '$date1' 
      -- AND shelfcount.Modify_code = $Userid
      GROUP BY YEAR(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Tracking_status_for_linen_operation_by_user.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r19';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r19';
    return $return;
  }
}
function r20($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      WHERE DATE(shelfcount.DocDate) = '$date1'
      AND site.HptCode  = '$HptCode'
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
      INNER JOIN site ON site.HptCode = department.HptCode
            WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
            AND site.HptCode  = '$HptCode'
            GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC
            ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
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
      INNER JOIN site ON site.HptCode = department.HptCode
      WHERE MONTH(shelfcount.DocDate) = '$date1' 
      AND site.HptCode  = '$HptCode'
      GROUP BY MONTH(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND site.HptCode  = '$HptCode'
      GROUP BY shelfcount.DocDate
            ORDER BY shelfcount.DocNo ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
      department.DepName,
      shelfcount.DocDate,
      shelfcount.DocNo
      FROM
      shelfcount
      INNER JOIN department on department.DepCode = shelfcount.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      WHERE YEAR(shelfcount.DocDate) = '$date1' 
      AND site.HptCode  = '$HptCode'
      GROUP BY YEAR(shelfcount.DocDate)
            ORDER BY shelfcount.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year' => $year];
  //$_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Summary_billing.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r20';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r20';
    $return['Format'] = $Format;
    return $return;
  }
}
function r21($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $Userid, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT
      tdas_document.DocNo,
      tdas_document.DocDate,
      tdas_document.HptCode
      FROM
      tdas_document
      WHERE tdas_document.DocDate LIKE '%$date1%' 
      AND tdas_document.HptCode = '$HptCode'
			GROUP BY tdas_document.DocNo
			ORDER BY tdas_document.DocNo ASC";
    } else {
      $Sql = "SELECT
      tdas_document.DocNo,
      tdas_document.DocDate,
      tdas_document.HptCode
      FROM
      tdas_document
            WHERE tdas_document.DocDate BETWEEN '$date1' AND '$date2'
            AND tdas_document.HptCode = '$HptCode'
			GROUP BY tdas_document.DocNo
			ORDER BY tdas_document.DocNo ASC
            ";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];
    if ($chk == 'month') {
      $Sql = "SELECT
      tdas_document.DocNo,
      tdas_document.DocDate,
      tdas_document.HptCode
      FROM
      tdas_document
      WHERE tdas_document.DocDate LIKE '%$date1%' 
      AND tdas_document.HptCode = '$HptCode'
			GROUP BY tdas_document.DocNo
			ORDER BY tdas_document.DocNo ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT
      tdas_document.DocNo,
      tdas_document.DocDate,
      tdas_document.HptCode
      FROM
      tdas_document
      WHERE DATE(tdas_document.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
      AND tdas_document.HptCode = '$HptCode'
			GROUP BY tdas_document.DocNo
			ORDER BY tdas_document.DocNo ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT
    tdas_document.DocNo,
    tdas_document.DocDate,
    tdas_document.HptCode
    FROM
    tdas_document
      WHERE tdas_document.DocDate LIKE '%$date1%' 
      AND tdas_document.HptCode = '$HptCode'
			GROUP BY tdas_document.DocNo
			ORDER BY tdas_document.DocNo ASC";
  }

  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year' => $year];
  //$_SESSION['data_send'] = $data_send;
  $return['sql'] = $Sql;
  $return['url'] = '../report_linen/report/Report_Tdas.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'r21';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'r21';
    $return['Format'] = $Format;
    return $return;
  }
}
function r22($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT factory.FacName, newlinentable.DocDate, site.HptName
              FROM
              newlinentable  
              INNER JOIN factory ON factory.FacCode = newlinentable.FacCode  
              INNER JOIN site ON site.HptCode = newlinentable.HptCode  
              WHERE DATE(newlinentable.DocDate) = DATE('$date1')
              AND site.HptCode = '$HptCode'
              AND newlinentable.FacCode = $FacCode
              AND newlinentable.isStatus <> 9 
              GROUP BY Date(newlinentable.DocDate)
              ORDER BY newlinentable.DocDate ASC";
    } else {
      $Sql = "SELECT factory.FacName, newlinentable.DocDate, site.HptName
              FROM
              newlinentable  
              INNER JOIN factory ON factory.FacCode = newlinentable.FacCode  
              INNER JOIN site ON site.HptCode = newlinentable.HptCode  
              WHERE newlinentable.DocDate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              AND newlinentable.FacCode = $FacCode
              AND newlinentable.isStatus <> 9 
              GROUP BY MONTH (newlinentable.Docdate)
              ORDER BY newlinentable.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT factory.FacName, newlinentable.DocDate, site.HptName
              FROM
              newlinentable  
              INNER JOIN factory ON factory.FacCode = newlinentable.FacCode  
              INNER JOIN site ON site.HptCode = newlinentable.HptCode  
              WHERE MONTH(newlinentable.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              AND newlinentable.FacCode = $FacCode
              AND newlinentable.isStatus <> 9 
              GROUP BY MONTH (newlinentable.Docdate)
              ORDER BY newlinentable.DocDate ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = "SELECT factory.FacName, newlinentable.DocDate, site.HptName
              FROM
              newlinentable  
              INNER JOIN factory ON factory.FacCode = newlinentable.FacCode  
              INNER JOIN site ON site.HptCode = newlinentable.HptCode  
              WHERE DATE(newlinentable.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
           AND site.HptCode = '$HptCode'
           AND newlinentable.FacCode = $FacCode
           AND newlinentable.isStatus <> 9 
           GROUP BY YEAR (newlinentable.Docdate)
           ORDER BY newlinentable.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "SELECT factory.FacName, newlinentable.DocDate, site.HptName
            FROM
              newlinentable  
              INNER JOIN factory ON factory.FacCode = newlinentable.FacCode  
              INNER JOIN site ON site.HptCode = newlinentable.HptCode  
              WHERE YEAR(newlinentable.DocDate) = '$date1'
             AND site.HptCode = '$HptCode'
             AND newlinentable.FacCode = $FacCode
             AND newlinentable.isStatus <> 9 
             GROUP BY YEAR (newlinentable.Docdate)
             ORDER BY newlinentable.DocDate ASC";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Newwash.php';
  $return['urlxls'] = '../report_linen/excel/Report_Newwash_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Fac';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = 'r22';
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Fac';
    return $return;
  }
}
function r23($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  damagenh.DocDate, site.HptName
              FROM
              damagenh  
              INNER JOIN department ON department.DepCode = damagenh.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              WHERE DATE(damagenh.DocDate) = DATE('$date1')
              AND site.HptCode = '$HptCode'
              AND damagenh.IsStatus <> 9 AND damagenh.IsStatus <> 0
              GROUP BY Date(damagenh.DocDate)
              ORDER BY damagenh.DocDate ASC";
    } else {
      $Sql = "SELECT  damagenh.DocDate, site.HptName
              FROM
              damagenh  
              INNER JOIN department ON department.DepCode = damagenh.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              WHERE damagenh.DocDate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              AND damagenh.IsStatus <> 9 AND damagenh.IsStatus <> 0
              GROUP BY MONTH (damagenh.Docdate)
              ORDER BY damagenh.DocDate ASC";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT  damagenh.DocDate, site.HptName
              FROM
              damagenh  
              INNER JOIN department ON department.DepCode = damagenh.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              WHERE MONTH(damagenh.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
             AND damagenh.IsStatus <> 9 AND damagenh.IsStatus <> 0       
              GROUP BY MONTH (damagenh.Docdate)
              ORDER BY damagenh.DocDate ASC";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = " SELECT  damagenh.DocDate, site.HptName
              FROM
              damagenh  
              INNER JOIN department ON department.DepCode = damagenh.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              WHERE DATE(damagenh.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
           AND site.HptCode = '$HptCode'
            AND damagenh.IsStatus <> 9 AND damagenh.IsStatus <> 0 
           GROUP BY YEAR (damagenh.Docdate)
           ORDER BY damagenh.DocDate ASC ";
    }
  } else if ($Format == 3) {
    $Sql = "  SELECT  damagenh.DocDate, site.HptName
               FROM
              damagenh  
              INNER JOIN department ON department.DepCode = damagenh.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              WHERE YEAR(damagenh.DocDate) = '$date1'
             AND site.HptCode = '$HptCode'
             AND damagenh.IsStatus <> 9 AND damagenh.IsStatus <> 0 
             GROUP BY YEAR (damagenh.Docdate)
             ORDER BY damagenh.DocDate ASC";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];

  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_damagenh.php';
  $return['urlxls'] = '../report_linen/excel/Report_damagenh_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['FacCode'] = $Result['FacCode'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'NoFacDep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = 'r23';
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
    return $return;
  }
}
function r24($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  damage.DocDate, site.HptName
              FROM
              damage  
              INNER JOIN department ON department.DepCode = damage.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN factory ON factory.faccode = damage.faccode           
              WHERE DATE(damage.DocDate) = DATE('$date1')
              AND site.HptCode = '$HptCode'
              AND factory.faccode = '$FacCode'
              AND damage.isStatus <> 9 
              GROUP BY Date(damage.DocDate)
              ORDER BY damage.DocDate ASC LIMIT 1";
    } else {
      $Sql = "SELECT  damage.DocDate, site.HptName
              FROM
              damage  
              INNER JOIN department ON department.DepCode = damage.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN factory ON factory.faccode = damage.faccode   
              WHERE damage.DocDate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              AND factory.faccode = '$FacCode'
              AND damage.isStatus <> 9 
              GROUP BY MONTH (damage.Docdate)
              ORDER BY damage.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT  damage.DocDate, site.HptName
              FROM
              damage  
              INNER JOIN department ON department.DepCode = damage.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN factory ON factory.faccode = damage.faccode   
              WHERE MONTH(damage.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              AND factory.faccode = '$FacCode'
              AND damage.isStatus <> 9 
              GROUP BY MONTH (damage.Docdate)
              ORDER BY damage.DocDate ASC LIMIT 1";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = " SELECT  damage.DocDate, site.HptName
              FROM
              damage  
              INNER JOIN department ON department.DepCode = damage.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN factory ON factory.faccode = damage.faccode 
              WHERE DATE(damage.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
           AND site.HptCode = '$HptCode'
           AND damage.isStatus <> 9
           AND factory.faccode = '$FacCode' 
           GROUP BY YEAR (damage.Docdate)
           ORDER BY damage.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "  SELECT  damage.DocDate, site.HptName
               FROM
              damage  
              INNER JOIN department ON department.DepCode = damage.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN factory ON factory.faccode = damage.faccode 
              WHERE YEAR(damage.DocDate) = '$date1'
             AND site.HptCode = '$HptCode'
             AND factory.faccode = '$FacCode'
             AND damage.isStatus <> 9 
             GROUP BY YEAR (damage.Docdate)
             ORDER BY damage.DocDate ASC LIMIT 1";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];

  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/report/Report_Claim_Factory.php';
  $return['urlxls'] = '../report_linen/excel/Report_Claim_Factory_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'NoFacDep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = 'r24';
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
    return $return;
  }
}
function r25($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT site.HptName
              from kpi_dirty1
              INNER JOIN site ON site.HptCode = kpi_dirty1.HptCode
              WHERE MONTH(kpi_dirty1.DocDate) = '$date1'
              AND site.HptCode = '$HptCode' AND kpi_dirty1.isStatus = 1
              GROUP BY MONTH (kpi_dirty1.Docdate)
              ORDER BY kpi_dirty1.DocDate ASC";
    }
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/excel/Report_QC_Process_checklist_dirty.php';
  $return['urlxls'] = '../report_linen/excel/Report_Cleaned_Linen_Weight_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'NoFacDep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
    return $return;
  }
}
function r26($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT site.HptName
              from kpi_clean1
              INNER JOIN site ON site.HptCode = kpi_clean1.HptCode
              WHERE MONTH(kpi_clean1.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              GROUP BY MONTH (kpi_clean1.Docdate)
              ORDER BY kpi_clean1.DocDate ASC";
    }
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/excel/Report_QC_Process_checklist_clean1.php';
  $return['urlxls'] = '../report_linen/excel/Report_Cleaned_Linen_Weight_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'NoFacDep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
    return $return;
  }
}
function r27($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT site.HptName
              from kpi_clean1
              INNER JOIN site ON site.HptCode = kpi_clean1.HptCode
              WHERE MONTH(kpi_clean1.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              GROUP BY MONTH (kpi_clean1.Docdate)
              ORDER BY kpi_clean1.DocDate ASC";
    }
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2];
  //$_SESSION['data_send'] = $data_send;
  $return['url'] = '../report_linen/excel/Report_QC_Process_checklist_clean2.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'NoFacDep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'NoFacDep';
    return $return;
  }
}
function r28($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $chk)
{
  $count = 0;
  $boolean = false;
  if ($GroupCode == "0") {
    $GroupCode  = "0";
    $GroupCode1 = "";
  } else {
    $GroupCode = "$GroupCode";
    $GroupCode1 = "AND grouphpt.GroupCode = $GroupCode";
  }
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName , department.DepName
        FROM
        shelfcount  
        INNER JOIN department ON department.DepCode = shelfcount.DepCode  
        INNER JOIN site ON site.HptCode = department.HptCode  
        INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
                WHERE DATE(shelfcount.DocDate) = DATE('$date1')
                AND site.HptCode = '$HptCode'
                AND shelfcount.isStatus <> 9 
                $GroupCode1
                GROUP BY Date(shelfcount.DocDate)
                ORDER BY shelfcount.DocDate ASC limit 1 ";
    } else {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName , department.DepName
        FROM
        shelfcount  
        INNER JOIN department ON department.DepCode = shelfcount.DepCode  
        INNER JOIN site ON site.HptCode = department.HptCode  
        INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
                WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
                AND site.HptCode = '$HptCode'
                AND shelfcount.isStatus <> 9 
                $GroupCode1
                GROUP BY MONTH (shelfcount.Docdate)
                ORDER BY shelfcount.DocDate ASC limit 1";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName , department.DepName
        FROM
        shelfcount  
        INNER JOIN department ON department.DepCode = shelfcount.DepCode  
        INNER JOIN site ON site.HptCode = department.HptCode  
        INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
                WHERE MONTH(shelfcount.DocDate) = '$date1'
                AND site.HptCode = '$HptCode'
                $GroupCode1
                AND shelfcount.isStatus <> 9 
                GROUP BY MONTH (shelfcount.Docdate)
                ORDER BY shelfcount.DocDate ASC limit 1 ";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = " SELECT  shelfcount.DocDate, site.HptName , department.DepName
        FROM
        shelfcount  
        INNER JOIN department ON department.DepCode = shelfcount.DepCode  
        INNER JOIN site ON site.HptCode = department.HptCode  
        INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
                WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
             AND site.HptCode = '$HptCode'
             $GroupCode1
             AND shelfcount.isStatus <> 9 
             GROUP BY YEAR (shelfcount.Docdate)
             ORDER BY shelfcount.DocDate ASC LIMIT 1";
    }
  } else if ($Format == 3) {
    $Sql = "  SELECT  shelfcount.DocDate, site.HptName , department.DepName
      FROM
      shelfcount  
      INNER JOIN department ON department.DepCode = shelfcount.DepCode  
      INNER JOIN site ON site.HptCode = department.HptCode  
      INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
                WHERE YEAR(shelfcount.DocDate) = '$date1'
               AND site.HptCode = '$HptCode'
               $GroupCode1
               AND shelfcount.isStatus <> 9 
               GROUP BY YEAR (shelfcount.Docdate)
               ORDER BY shelfcount.DocDate ASC limit 1";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2, 'GroupCode' => $GroupCode];
  //$_SESSION['data_send'] = $data_send;
  $return['urlxls'] = '../report_linen/excel/Report_Billing_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Group';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Group';
    return $return;
  }
}
function r29($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, $chk)
{
  $count = 0;
  $boolean = false;
  $limit = '';
  if ($GroupCode == "0") {
    $GroupCode  = "0";
    $GroupCode1 = "";
  } else {
    $GroupCode = "$GroupCode";
    $GroupCode1 = "AND department.GroupCode = '$GroupCode' AND  grouphpt.HptCode = '$HptCode' ";
  }
  if ($DepCode == "ALL") {
    $OldDepCode = $DepCode;
    $DepCode  = "0";
    if ($Item <> '0') {
      $DepCode1 = "AND shelfcount_detail.itemCode = '$Item'";
      $limit = 'limit 1';
    }
  } else {
    $DepCode = "$DepCode";
    $DepCode1 = "AND shelfcount.DepCode = '$DepCode' ";
  }
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName , department.DepName ,department.DepCode
              FROM
              shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE DATE(shelfcount.DocDate) = DATE('$date1')
              AND site.HptCode = '$HptCode'
              AND shelfcount.isStatus <> 9 
              $DepCode1
              $GroupCode1
              GROUP BY department.DepCode
              ORDER BY department.DepName ASC $limit";
    } else {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
              FROM
              shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo 
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode   
              WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              AND shelfcount.isStatus <> 9 
              $DepCode1
              $GroupCode1
              GROUP BY department.DepCode
              ORDER BY department.DepName ASC $limit";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
              FROM
              shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE MONTH(shelfcount.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              $DepCode1
              $GroupCode1
              AND shelfcount.isStatus <> 9 
              GROUP BY department.DepCode
              ORDER BY department.DepName ASC $limit";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = " SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
              FROM
              damage  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
           AND site.HptCode = '$HptCode'
           $DepCode1
           $GroupCode1
           AND shelfcount.isStatus <> 9 
           GROUP BY YEAR (shelfcount.Docdate)
           ORDER BY department.DepName ASC $limit";
    }
  } else if ($Format == 3) {
    $Sql = "  SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
               FROM
               shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE YEAR(shelfcount.DocDate) = '$date1'
             AND site.HptCode = '$HptCode'
             $DepCode1
             $GroupCode1
             AND shelfcount.isStatus <> 9 
             GROUP BY YEAR (shelfcount.Docdate)
             ORDER BY shelfcount.DocDate ASC $limit";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2, 'item' => $Item];
  //$_SESSION['data_send'] = $data_send;
  $return['urlxls'] = '../report_linen/excel/Report_Summary_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['department'][$count]['HptName'] = $Result['HptName'];
    $return['department'][$count]['DocDate'] = $Result['DocDate'];
    $return['department'][$count]['DepName'] = $Result['DepName'];
    $return['department'][$count]['DepCode'] = $Result['DepCode'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Dep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = 'r29';
    $return['item'] = $Item;

    if ($OldDepCode == 'ALL' && $Item <> '0') {
      $return['statusDep'] = 'somedepartment';
    } elseif ($OldDepCode == 'ALL' && $Item == '0') {
      $return['statusDep'] = 'alldepartment';
    } elseif ($OldDepCode <> 'ALL') {
      $return['statusDep'] = 'somedepartment';
    }
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Dep';
    return $return;
  }
}
function r30($conn, $HptCode, $FacCode, $date1, $date2, $Format, $DepCode, $GroupCode, $Item, $chk)
{
  $count = 0;
  $boolean = false;
  $limit = '';
  if ($GroupCode == "0") {
    $GroupCode  = "0";
    $GroupCode1 = "";
  } else {
    $GroupCode = "$GroupCode";
    $GroupCode1 = "AND department.GroupCode = '$GroupCode' AND  grouphpt.HptCode = '$HptCode' ";
  }
  if ($DepCode == "ALL") {
    $OldDepCode = $DepCode;
    $DepCode  = "0";
    if ($Item <> '0') {
      $DepCode1 = "AND shelfcount_detail.itemCode = '$Item'";
      $limit = 'limit 1';
    }
  } else {
    $DepCode = "$DepCode";
    $DepCode1 = "AND shelfcount.DepCode = '$DepCode' ";
  }
  if ($Format == 1) {
    if ($chk == 'one') {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName , department.DepName ,department.DepCode
              FROM
              shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE DATE(shelfcount.DocDate) = DATE('$date1')
              AND site.HptCode = '$HptCode'
              AND shelfcount.isStatus <> 9 
              $DepCode1
              $GroupCode1
              GROUP BY department.DepCode
              ORDER BY department.DepName ASC $limit";
    } else {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
              FROM
              shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo 
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode   
              WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
              AND site.HptCode = '$HptCode'
              AND shelfcount.isStatus <> 9 
              $DepCode1
              $GroupCode1
              GROUP BY department.DepCode
              ORDER BY department.DepName ASC $limit";
    }
  } else if ($Format == 2) {
    $date = subMonth($date1, $date2);
    $year1 = $date['year1'];
    $year2 = $date['year2'];
    $date1 = $date['date1'];
    $date2 = $date['date2'];

    if ($chk == 'month') {
      $Sql = "SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
              FROM
              shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE MONTH(shelfcount.DocDate) = '$date1'
              AND site.HptCode = '$HptCode'
              $DepCode1
              $GroupCode1
              AND shelfcount.isStatus <> 9 
              GROUP BY department.DepCode
              ORDER BY department.DepName ASC $limit";
    } else {
      $lastday = cal_days_in_month(CAL_GREGORIAN, $date2, $year2);
      $betweendate1 = $year1 . '-' . $date1 . '-1';
      $betweendate2 = $year2 . '-' . $date2 . '-' . $lastday;
      $Sql = " SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
              FROM
              damage  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'
           AND site.HptCode = '$HptCode'
           $DepCode1
           $GroupCode1
           AND shelfcount.isStatus <> 9 
           GROUP BY YEAR (shelfcount.Docdate) ,department.DepCode
           ORDER BY department.DepName ASC $limit";
    }
  } else if ($Format == 3) {
    $Sql = "  SELECT  shelfcount.DocDate, site.HptName, department.DepName ,department.DepCode
               FROM
               shelfcount  
              INNER JOIN department ON department.DepCode = shelfcount.DepCode  
              INNER JOIN site ON site.HptCode = department.HptCode  
              INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo    
              INNER JOIN grouphpt ON  grouphpt.HptCode = site.HptCode
              WHERE YEAR(shelfcount.DocDate) = '$date1'
             AND site.HptCode = '$HptCode'
             $DepCode1
             $GroupCode1
             AND shelfcount.isStatus <> 9 
             GROUP BY department.DepCode
             ORDER BY shelfcount.DocDate ASC $limit";
  }
  $return['sql'] = $Sql;
  $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk, 'year1' => $year1, 'year2' => $year2, 'item' => $Item];
  //$_SESSION['data_send'] = $data_send;
  $return['urlxls'] = '../report_linen/excel/Report_Usage_Detail_xls.php';
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['department'][$count]['HptName'] = $Result['HptName'];
    $return['department'][$count]['DocDate'] = $Result['DocDate'];
    $return['department'][$count]['DepName'] = $Result['DepName'];
    $return['department'][$count]['DepCode'] = $Result['DepCode'];
    $count++;
    $boolean = true;
  }
  $return['data_send'] = $data_send;
  if ($boolean == true) {
    $return['status'] = 'success';
    $return['form'] = 'Dep';
    $return['countRow'] = $count;
    $return['date1'] = $date1;
    $return['date2'] = $date2;
    $return['Format'] = $Format;
    $return['chk'] = $chk;
    $return['r'] = 'r30';
    $return['item'] = $Item;

    if ($OldDepCode == 'ALL' && $Item <> '0') {
      $return['statusDep'] = 'somedepartment';
    } elseif ($OldDepCode == 'ALL' && $Item == '0') {
      $return['statusDep'] = 'alldepartment';
    } elseif ($OldDepCode <> 'ALL') {
      $return['statusDep'] = 'somedepartment';
    }
    return $return;
  } else {
    $return['status'] = 'notfound';
    $return['form'] = 'Dep';
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
