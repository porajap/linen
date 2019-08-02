<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
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
    $date = $DATA['date'];
    $date1 = '';
    $date2 = '';

    if($typeReport == 1){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r1($conn, $HptCode, $FacCode, $date1, $date2, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r1($conn, $HptCode, $FacCode, $date1, $date2, 'between');
        }
      }else if($Format == 2){
        $date1 = newMount($date);
        $return = r1($conn, $HptCode, $FacCode, $date1, $date2, 'one');
      }
    }else if($typeReport == 2){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r2($conn, $HptCode, $FacCode, $date1, $date2, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r2($conn, $HptCode, $FacCode, $date1, $date2, 'between');
        }
      }else if($Format == 2){
        $date1 = newMount($date);
        $return = r2($conn, $HptCode, $date1, $date2, 'one');
      }
    }else if($typeReport == 3){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r3($conn, $HptCode, $FacCode, $date1, $date2, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r3($conn, $HptCode, $FacCode, $date1, $date2, 'between');
        }
      }else if($Format == 2){
        $date1 = newMount($date);
        $return = r3($conn, $HptCode, $FacCode, $date1, $date2, 'one');
      }
    }else if($typeReport == 6){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r6($conn, $HptCode, $FacCode, $date1, $date2, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r6($conn, $HptCode, $FacCode, $date1, $date2, 'between');
        }
      }else if($Format == 2){
        $date1 = newMount($date);
        $return = r6($conn, $HptCode, $FacCode, $date1, $date2, 'one');
      }
    }else if($typeReport == 8){
      if($Format == 1 || $Format == 3){
        if($FormatDay == 1 || $Format == 3){
          $date1 = $date;
          $return = r8($conn, $HptCode, $FacCode, $date1, $date2, 'one');
        }else{
          $date1 = newDate1($date);
          $date2 = newDate2($date);
          $return = r8($conn, $HptCode, $FacCode, $date1, $date2, 'between');
        }
      }else if($Format == 2){
        $date1 = newMount($date);
        $return = r8($conn, $HptCode, $FacCode, $date1, $date2, 'one');
      }
    }

    echo json_encode($return);
  }
  // 

  #----------------------------chk number mount
  function chk_mount($date){
    $chk = ['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October',
    '11'=>'November','12'=>'December'];
    $numMont = array_search($date, $chk);
    return $numMont;
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
  function newMount($date){
    $mount = explode('/' , $date);
    $chk = chk_mount($mount[0]);
    $date1 = $mount[1].'-'.$chk;
    return $date1;
  }
  #----------------------------Format new date

  function r1($conn, $HptCode, $FacCode, $date1, $date2, $chk){
    $boolean = false;
    $count = 0;
    if($chk == 'one'){
      $Sql = "SELECT dirty.DocNo, dirty.DocDate, factory.FacName, dirty.RefDocNo
        FROM dirty
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
        INNER JOIN department ON dirty.DepCode = department.DepCode
        WHERE dirty.DocDate LIKE '%$date1%' AND dirty.FacCode = $FacCode GROUP BY dirty.DocNo ORDER BY dirty.DocNo ASC";
    }else{
      $Sql = "SELECT dirty.DocNo, dirty.DocDate, factory.FacName, dirty.RefDocNo
        FROM dirty
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
        INNER JOIN department ON dirty.DepCode = department.DepCode
        WHERE dirty.DocDate BETWEEN '$date1' AND '$date2' 
        AND dirty.FacCode = $FacCode GROUP BY dirty.DocNo ORDER BY dirty.DocNo ASC";
    }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DocNo'] = $Result['DocNo'];
        $return[$count]['DocDate'] = $Result['DocDate'];
        $return[$count]['FacName'] = $Result['FacName'];
        $return[$count]['RefDocNo'] = $Result['RefDocNo']==null?'-':$Result['RefDocNo'];
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

  function r2($conn, $HptCode, $FacCode, $date1, $date2, $chk){
    $boolean = false;
    $count = 0;
    if($chk == 'one'){
      $Sql = "SELECT  claim.DocNo, claim.DocDate, claim.RefDocNo, department.DepName
            FROM claim
            INNER JOIN site ON claim.HptCode = site.HptCode
            INNER JOIN department ON department.DepCode = claim.DepCode
            WHERE claim.DocDate LIKE '%$date1%' AND claim.HptCode = '$HptCode' AND claim.FacCode = $FacCode
            GROUP BY claim.DocNo ORDER BY claim.DocNo ASC";
    }else{
      $Sql = "SELECT claim.DocNo, claim.DocDate, claim.RefDocNo, department.DepName
            FROM claim
            INNER JOIN site ON claim.HptCode = site.HptCode
            INNER JOIN department ON department.DepCode = claim.DepCode
            WHERE claim.DocDate BETWEEN '$date1' AND '$date2' 
            AND claim.HptCode = '$HptCode' AND claim.FacCode = $FacCode
            GROUP BY claim.DocNo ORDER BY claim.DocNo ASC";
    }
    $return['sql'] = $Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $return[$count]['RefDocNo'] = $Result['RefDocNo']==null?'-':$Result['RefDocNo'];
      $return[$count]['DepName'] = $Result['DepName'];
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

  function r3($conn, $HptCode, $FacCode, $date1, $date2, $chk){
    $count = 0;
    $boolean = false;
    if($chk == 'one'){
      $Sql = "SELECT factory.facname,
                clean.DocDate,
                site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode =clean.FacCode
              INNER JOIN site ON site.HptCode = clean.HptCode
              WHERE clean.DocDate LIKE '%$date1%' AND clean.HptCode = '$HptCode' AND clean.FacCode = $FacCode";
    }else{
      $Sql = "SELECT factory.facname,
                clean.DocDate,
                site.HptName
              FROM clean
              INNER JOIN factory ON factory.FacCode =clean.FacCode
              INNER JOIN site ON site.HptCode = clean.HptCode
              WHERE clean.DocDate BETWEEN '$date1' AND '$date2'  
              AND clean.HptCode = '$HptCode' AND clean.FacCode = $FacCode";
    }
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['facname'] = $Result['facname'];
      $return[$count]['DocDate'] = $Result['DocDate'];
      $return[$count]['HptName'] = $Result['HptName'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r3';
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r3';
      return $return;
    }
  }

  function r6($conn, $HptCode, $FacCode, $date1, $date2, $chk){
    $count = 0;
    $boolean = false;
    if($chk == 'one'){
      $Sql = "SELECT 
                factory.FacName,
                DATE(rewash.DocDate) AS DocDate,
                TIME(rewash.DocDate) AS DocTime
              FROM rewash
              INNER JOIN factory ON rewash.FacCode = factory.FacCode
              WHERE rewash.DocDate LIKE '%$date1%' AND rewash.FacCode = $FacCode";
    }else{
      $Sql = "SELECT 
                factory.FacName,
                DATE(rewash.DocDate) AS DocDate,
                TIME(rewash.DocDate) AS DocTime
              FROM rewash
              INNER JOIN factory ON rewash.FacCode = factory.FacCode
              WHERE rewash.DocDate BETWEEN '$date1' AND '$date2'   
              AND rewash.FacCode = $FacCode";
    }
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
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r6';
      return $return;
    }
  }

  function r8($conn, $HptCode, $FacCode, $date1, $date2, $chk){
    $count = 0;
    $boolean = false;
    if($chk == 'one'){
      $Sql = "SELECT 
                  site.HptName,
                  department.DepName,
                  dirty.DocNo AS DocNo1,
                  dirty.DocDate AS DocDate1,
                  dirty.Total AS Total1,
                  clean.DocNo AS DocNo2,
                  clean.DocDate AS DocDate2,
                  clean.Total AS Total2,
              ROUND( (-1*((clean.Total - dirty.Total ) / dirty.Total) * 100), 2)  AS Precent
              FROM clean
              INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
              INNER JOIN department ON clean.DepCode = department.DepCode
              INNER JOIN site ON department.HptCode = site.HptCode
              WHERE clean.DocDate LIKE '%$date1%' AND clean.HptCode = '$HptCode' AND clean.FacCode = $FacCode";
    }else{
      $Sql = "SELECT 
                site.HptName,
                department.DepName,
                dirty.DocNo AS DocNo1,
                dirty.DocDate AS DocDate1,
                dirty.Total AS Total1,
                clean.DocNo AS DocNo2,
                clean.DocDate AS DocDate2,
                clean.Total AS Total2,
            ROUND( (-1*((clean.Total - dirty.Total ) / dirty.Total) * 100), 2)  AS Precent
            FROM clean
            INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
            INNER JOIN department ON clean.DepCode = department.DepCode
            INNER JOIN site ON department.HptCode = site.HptCode
            WHERE clean.DocDate BETWEEN '$date1' AND '$date2'
            AND clean.HptCode = '$HptCode' AND clean.FacCode = $FacCode";
    }
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['DepName'] = $Result['DepName'];
      $return[$count]['DocNo1'] = $Result['DocNo1'];
      $return[$count]['DocDate1'] = $Result['DocDate1'];
      $return[$count]['Total1'] = $Result['Total1'];
      $return[$count]['DocNo2'] = $Result['DocNo2'];
      $return[$count]['DocDate2'] = $Result['DocDate2'];
      $return[$count]['Total2'] = $Result['Total2'];
      $return[$count]['Precent'] = $Result['Precent'];
      $count++;
      $boolean = true;
    }

    if($boolean == true){
      $return['status'] = 'success';
      $return['form'] = 'r8';
      return $return;
    }else{
      $return['status'] = 'notfound';
      $return['form'] = 'r8';
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
