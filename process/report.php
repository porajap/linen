<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
$lang = $_SESSION['lang'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $countx = 0;

  $Sqlx = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0";
  $meQueryx = mysqli_query($conn, $Sqlx);
  while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
    $return[$countx]['FacCode'] = $Resultx['FacCode'];
    $return[$countx]['FacName'] = $Resultx['FacName'];
    $countx  ++;
  }
  $return['Rowx'] = $countx;


  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;

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

  function find_report($conn, $DATA){
    $FacCode = $DATA['factory'];
    $HptCode = $DATA['HptCode'];
    $typeReport = $DATA['typeReport'];
    $Format = $DATA['Format'];
    $FormatDay = $DATA['FormatDay'];
    $FormatMonth = $DATA['FormatMonth'];
    $date = $DATA['date'];
    $date1 = '';
    $date2 = '';
    if($typeReport == 1){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
        
      }
    }else if($typeReport == 2){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
        
      }
    }else if($typeReport == 3){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
      }
    }else if($typeReport == 4){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
      }
    }else if($typeReport == 6){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r6($conn, $HptCode, $FacCode, $date1, $date2, $Format,'monthbetween');
        }
        
      }
    }else if($typeReport == 8){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
      }
    }else if($typeReport == 15){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
      }
    }else if($typeReport == 16){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'between');
        }
      }else if($Format == 2){
        if($FormatMonth == 1){
          $date1 = newMonth($date);
          $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'month');
        }else{
          $date1 = newMonth1($date);
          $date2 = newMonth2($date);
          $return = r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, 'monthbetween');
        }
      }
    }
    echo json_encode($return);
  }
  // 

  #----------------------------chk number mount
  function chk_mount($date){
    $youDate = trim($date);
    $MonthArray = ['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October',
    '11'=>'November','12'=>'December'];
    $numMonth = array_search($youDate, $MonthArray);
    return $numMonth;
  }
  #----------------------------Format new date
  function newDate1($date){
    $sub = explode('-', $date);
    $d1 = explode('/', $sub[0]);
    $date1 = trim($d1[0].'-'.$d1[1].'-'.$d1[2]);
    return $date1;
  }
  function newDate2($date){
    $sub = explode('-', $date);
    $d2 = explode('/', $sub[1]);
    $date2 = trim($d2[0].'-'.$d2[1].'-'.$d2[2]);
    return $date2;
  }
  function newMonth($date){
    $mount = explode('/' , $date);
    $chk = chk_mount($mount[0]);
    $date1 = $mount[1].'-'.$chk;
    return $date1;
  }
  function newMonth1($date){
    $month = explode('-' , $date);
    $month1 = explode('/' , $month[0]);
    $numMonth = chk_mount($month1[0]);
    $date1 = $month1[1].'-'.$numMonth;
    return $date1;
  }
  function newMonth2($date){
    $month = explode('-' , $date);
    $month2 = explode('/' , $month[1]);
    $numMonth = chk_mount($month2[0]);
    $date2 = $month2[1].'-'.$numMonth;
    return $date2;
  }
  function subMonth($date1, $date2){
    $month1 = explode('-',$date1);
    $year = trim($month1[0]);
    $date1 = trim($month1[1]);
    $month2 = explode('-',$date2);
    $date2 = trim($month2[1]);
    $date['year'] = $year;
    $date['date1'] = $date1;
    $date['date2'] = $date2;
    return $date;
  }
  #----------------------------Format new date

  function r1($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $boolean = false;
    $count = 0;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT  factory.FacName, clean.DocDate, site.HptName
            FROM clean
            INNER JOIN factory ON factory.FacCode =clean.FacCode
            INNER JOIN site ON site.HptCode = clean.HptCode
          WHERE clean.DocDate LIKE '%$date1%' 
          AND clean.FacCode = $FacCode  ORDER BY clean.DocDate ASC";
      }else{
        $Sql = "SELECT  factory.FacName, clean.DocDate, site.HptName
          FROM clean
          INNER JOIN factory ON factory.FacCode =clean.FacCode
          INNER JOIN site ON site.HptCode = clean.HptCode
          WHERE clean.DocDate BETWEEN '$date1' AND '$date2' 
          AND clean.FacCode = $FacCode ORDER BY clean.DocNo ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
          $Sql = "SELECT  factory.FacName, clean.DocDate, site.HptName
          FROM clean
          INNER JOIN factory ON factory.FacCode =clean.FacCode
          INNER JOIN site ON site.HptCode = clean.HptCode
        WHERE clean.DocDate LIKE '%$date1%' 
        AND clean.FacCode = $FacCode  ORDER BY clean.DocDate ASC";
      }else{
        $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName  
        FROM clean  
        INNER JOIN factory ON factory.FacCode = clean.FacCode  
        INNER JOIN site ON site.HptCode = clean.HptCode  
        WHERE YEAR(clean.DocDate) = $year AND MONTH(clean.DocDate) BETWEEN $date1 AND $date2
        AND clean.FacCode = 1
        ORDER BY  clean.DocNo ASC ";
      }
    }
      $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format ,'chk' => $chk, 'lang' => $lang];
      $_SESSION['data_send'] = $data_send;
      $return['url'] = '../pages/test_report.php';
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['HptName'] = $Result['HptName'];
        $return[$count]['DocDate'] = $Result['DocDate'];
        $return[$count]['FacName'] = $Result['FacName'];
        $boolean = true;
        $count ++ ;
      }
    
      if($boolean == true){
        $return['status'] = 'success';
        $return['countRow'] = $count;
        $return['form'] = 'r1';
        return $return;
      }else{
        $return['status'] = 'notfound';
        $return['form'] = 'r1';
        return $return;
      }
      
  }

  function r2($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $boolean = false;
    $count = 0;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT site.HptName, claim.DocDate
              FROM claim
              INNER JOIN site ON claim.HptCode = site.HptCode
              WHERE claim.DocDate LIKE '%$date1%' AND claim.HptCode = '$HptCode' AND claim.FacCode = $FacCode
              ORDER BY claim.DocDate ASC";
      }else{
        $Sql = "SELECT  site.HptName, claim.DocDate
              FROM claim
              INNER JOIN site ON claim.HptCode = site.HptCode
              WHERE claim.DocDate BETWEEN '$date1' AND '$date2' 
              AND claim.HptCode = '$HptCode' AND claim.FacCode = $FacCode
              ORDER BY claim.DocDate ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT site.HptName, claim.DocDate
        FROM claim
        INNER JOIN site ON claim.HptCode = site.HptCode
        WHERE claim.DocDate LIKE '%$date1%' AND claim.HptCode = '$HptCode' AND claim.FacCode = $FacCode
        ORDER BY claim.DocDate ASC";
      }else{
        $Sql = "SELECT  site.HptName, claim.DocDate
        FROM claim
        INNER JOIN site ON claim.HptCode = site.HptCode
        WHERE YEAR(claim.DocDate) = $year AND MONTH(claim.DocDate) BETWEEN $date1 AND $date2
        AND claim.HptCode = '$HptCode' AND claim.FacCode = $FacCode
        ORDER BY claim.DocDate ASC";
      }
    }
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = '../report_linen/report/Report_Dirty_Linen_Weight.php';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $boolean = true;
      $count ++;
    }
    
    if($boolean == true){
      $return['status'] = 'success';
      $return['countRow'] = $count;
      $return['form'] = 'r2';
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r2';
      return $return;
    }
  }

  function r3($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $count = 0;
    $boolean = false;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
                FROM clean
                INNER JOIN factory ON factory.FacCode = clean.FacCode
                INNER JOIN site ON site.HptCode = clean.HptCode
                WHERE clean.DocDate LIKE '%$date1%' AND clean.HptCode = '$HptCode' 
                AND clean.FacCode = $FacCode GROUP BY clean.DocNo ORDER BY clean.DocNo ASC";
      }else{
        $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
                FROM clean
                INNER JOIN factory ON factory.FacCode = clean.FacCode
                INNER JOIN site ON site.HptCode = clean.HptCode
                WHERE clean.DocDate BETWEEN '$date1' AND '$date2'  
                AND clean.HptCode = '$HptCode' AND clean.FacCode = $FacCode 
                GROUP BY clean.DocNo ORDER BY clean.DocNo ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
                FROM clean
                INNER JOIN factory ON factory.FacCode = clean.FacCode
                INNER JOIN site ON site.HptCode = clean.HptCode
                WHERE clean.DocDate LIKE '%$date1%' AND clean.HptCode = '$HptCode' 
                AND clean.FacCode = $FacCode GROUP BY clean.DocNo ORDER BY clean.DocNo ASC";
      }else{
        $Sql = "SELECT factory.FacName, clean.DocDate, site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              INNER JOIN site ON site.HptCode = clean.HptCode
              WHERE YEAR(clean.DocDate) = $year AND MONTH(clean.DocDate) BETWEEN $date1 AND $date2
              AND clean.FacCode = $FacCode GROUP BY clean.DocNo ORDER BY clean.DocNo ASC";
      }
    }
    $return['sql'] = $Sql;
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = '../report_linen/report/Report_Dirty_Linen_Weight.php';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['FacName'] = $Result['FacName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r3';
      $return['countRow'] = $count;
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r3';
      return $return;
    }
  }

  function r4($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $count = 0;
    $boolean = false;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
                FROM shelfcount
                INNER JOIN department ON shelfcount.DepCode = department.DepCode
                WHERE shelfcount.DocDate LIKE '%$date1%'
                GROUP BY shelfcount.DocNo ORDER BY shelfcount.DocNo ASC";
      }else{
        $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
                FROM shelfcount
                INNER JOIN department ON shelfcount.DepCode = department.DepCode
                WHERE shelfcount.DocDate BETWEEN '$date1' AND '$date2'
                GROUP BY shelfcount.DocNo ORDER BY shelfcount.DocNo ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
                FROM shelfcount
                INNER JOIN department ON shelfcount.DepCode = department.DepCode
                WHERE shelfcount.DocDate LIKE '%$date1%'
                GROUP BY shelfcount.DocNo ORDER BY shelfcount.DocNo ASC";
      }else{
        $Sql = "SELECT shelfcount.DocNo, DATE(shelfcount.DocDate) AS DocDate, TIME(shelfcount.DocDate) AS DocTime, department.DepName
        FROM shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        WHERE YEAR(shelfcount.DocDate) = $year AND MONTH(shelfcount.DocDate) BETWEEN $date1 AND $date2
        GROUP BY shelfcount.DocNo ORDER BY shelfcount.DocNo ASC";
      }
    }
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = 'test_report.php?DocNo=';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['DepName'] = $Result['DepName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r4';
      $return['countRow'] = $count;
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r4';
      return $return;
    }
  }

  function r6($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $count = 0;
    $boolean = false;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT  factory.FacName, DATE(rewash.DocDate) AS DocDate, TIME(rewash.DocDate) AS DocTime 
                FROM rewash
                INNER JOIN factory ON rewash.FacCode = factory.FacCode
                WHERE rewash.DocDate LIKE '%$date1%' AND rewash.FacCode = $FacCode ORDER BY rewash.DocDate ASC";
      }else{
        $Sql = "SELECT  factory.FacName, DATE(rewash.DocDate) AS DocDate, TIME(rewash.DocDate) AS DocTime 
                FROM rewash
                INNER JOIN factory ON rewash.FacCode = factory.FacCode
                WHERE rewash.DocDate BETWEEN '$date1' AND '$date2'   
                AND rewash.FacCode = $FacCode ORDER BY rewash.DocDate ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT  factory.FacName, DATE(rewash.DocDate) AS DocDate, TIME(rewash.DocDate) AS DocTime 
        FROM rewash
        INNER JOIN factory ON rewash.FacCode = factory.FacCode
        WHERE rewash.DocDate LIKE '%$date1%' AND rewash.FacCode = $FacCode ORDER BY rewash.DocDate ASC";
      }else{
        $Sql = "SELECT  factory.FacName, DATE(rewash.DocDate) AS DocDate, TIME(rewash.DocDate) AS DocTime 
        FROM rewash
        INNER JOIN factory ON rewash.FacCode = factory.FacCode
        WHERE YEAR(rewash.DocDate) = $year AND MONTH(rewash.DocDate) BETWEEN $date1 AND $date2
        AND rewash.FacCode = $FacCode ORDER BY rewash.DocDate ASC";
      }
    }
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = '../report_linen/report/Report_Dirty_Linen_Weight.php';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['FacName'] = $Result['FacName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $return[$count]['DocTime'] = $Result['DocTime'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r6';
      $return['countRow'] = $count;
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r6';
      return $return;
    }
  }

  function r8($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $count = 0;
    $boolean = false;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT factory.FacName, DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN factory ON clean.FacCode = factory.FacCode
              WHERE clean.DocDate LIKE '%$date1%'  AND clean.FacCode = $FacCode";
      }else{
        $Sql = "SELECT factory.FacName, DATE(clean.DocDate) AS DocDate
              FROM clean
              INNER JOIN factory ON clean.FacCode = factory.FacCode
              WHERE clean.DocDate BETWEEN '$date1' AND '$date2'
              AND clean.FacCode = $FacCode";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT factory.FacName, DATE(clean.DocDate) AS DocDate
        FROM clean
        INNER JOIN factory ON clean.FacCode = factory.FacCode
        WHERE clean.DocDate LIKE '%$date1%'  AND clean.FacCode = $FacCode";
      }else{
        $Sql = "SELECT factory.FacName, DATE(clean.DocDate) AS DocDate
        FROM clean
        INNER JOIN factory ON clean.FacCode = factory.FacCode
        WHERE YEAR(clean.DocDate) = $year AND MONTH(clean.DocDate) BETWEEN $date1 AND $date2
        AND clean.FacCode = $FacCode";
      }
    }
    $return['ql'] = $Sql;
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = '../report_linen/report/Report_Dirty_Linen_Weight.php';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['FacName'] = $Result['FacName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r8';
      $return['countRow'] = $count;
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r8';
      return $return;
    }
  }

  function r15($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $count = 0;
    $boolean = false;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
            FROM dirty
            INNER JOIN process ON dirty.DocNo = process.DocNo
            INNER JOIN factory ON dirty.FacCode = factory.FacCode
            WHERE dirty.DocDate LIKE '%$date1%' ORDER BY dirty.DocNo ASC";
      }else{
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
              FROM dirty
              INNER JOIN process ON dirty.DocNo = process.DocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE dirty.DocDate BETWEEN '$date1' AND '$date2'
              ORDER BY dirty.DocNo ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
        FROM dirty
        INNER JOIN process ON dirty.DocNo = process.DocNo
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        WHERE dirty.DocDate LIKE '%$date1%' ORDER BY dirty.DocNo ASC";
      }else{
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
        FROM dirty
        INNER JOIN process ON dirty.DocNo = process.DocNo
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        WHERE YEAR(dirty.DocDate) = $year AND MONTH(dirty.DocDate) BETWEEN $date1 AND $date2
        ORDER BY dirty.DocNo ASC";
      }
    }
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = 'test_report.php?DocNo=';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['FacName'] = $Result['FacName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r15';
      $return['countRow'] = $count;
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r15';
      return $return;
    }
  }

  function r16($conn, $HptCode, $FacCode, $date1, $date2, $Format, $chk){
    $count = 0;
    $boolean = false;
    if($Format == 1 || $Format == 3){
      if($chk == 'one'){
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
            FROM dirty
            INNER JOIN process ON dirty.DocNo = process.DocNo
            INNER JOIN factory ON dirty.FacCode = factory.FacCode
            WHERE dirty.DocDate LIKE '%$date1%' ORDER BY dirty.DocNo ASC";
      }else{
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
              FROM dirty
              INNER JOIN process ON dirty.DocNo = process.DocNo
              INNER JOIN factory ON dirty.FacCode = factory.FacCode
              WHERE dirty.DocDate BETWEEN '$date1' AND '$date2'
              ORDER BY dirty.DocNo ASC";
      }
    }else if($Format == 2){
      $date = subMonth($date1, $date2);
      $year = $date['year'];
      $date1 = $date['date1'];
      $date2 = $date['date2'];
      if($chk == 'month'){
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
        FROM dirty
        INNER JOIN process ON dirty.DocNo = process.DocNo
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        WHERE dirty.DocDate LIKE '%$date1%' ORDER BY dirty.DocNo ASC";
      }else{
        $Sql = "SELECT process.DocNo, factory.FacName, dirty.DocDate
        FROM dirty
        INNER JOIN process ON dirty.DocNo = process.DocNo
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        WHERE YEAR(dirty.DocDate) = $year AND MONTH(dirty.DocDate) BETWEEN $date1 AND $date2
        ORDER BY dirty.DocNo ASC";
      }
    }
    $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'Format' => $Format, 'chk' => $chk, 'lang' => $lang];
    $_SESSION['data_send'] = $data_send;
    $return['url'] = 'test_report.php?DocNo=';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['FacName'] = $Result['FacName'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r16';
      $return['countRow'] = $count;
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r16';
      return $return;
    }
  }

  //==========================================================
  //
  //==========================================================
  if (isset($_POST['DATA'])) {
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace('\"', '"', $data), true);

    if ($DATA['STATUS'] == 'OnLoadPage') {
      OnLoadPage($conn, $DATA);
    }elseif($DATA['STATUS'] == 'find_report') {
      find_report($conn, $DATA);
    }  
  } else {
    $return['status'] = "error";
    $return['msg'] = 'noinput';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
