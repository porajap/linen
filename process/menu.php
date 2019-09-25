<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
require '../PHPMailer/PHPMailerAutoload.php';
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn,$DATA){
  $hptcode = $DATA['hptcode'];
  $lang = $_SESSION['lang'];
  $count = 0;
  $boolean = false;
  $Sql = "SELECT
  shelfcount.DocNo,
  shelfcount.DocDate,
  department.DepName,
  site.HptName,
  shelfcount.IsStatus,
  shelfcount.RefDocNo,
  shelfcount.Detail
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  WHERE  site.HptCode = '$hptcode' AND  shelfcount.IsStatus = 0 OR shelfcount.IsStatus = 1 OR shelfcount.IsStatus = 3
  ORDER BY shelfcount.DocNo DESC";
  $return['sql'] =$Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($lang =='en'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
    }else if ($lang == 'th'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
    }
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['RefDocNo'] = $Result['RefDocNo'];
    $return[$count]['Detail'] = $Result['Detail'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $newdate;
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $count++;
    $boolean = true;
  }
  $return['Row'] = $count;
  $boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getnotification($conn,$DATA){
  //  var_dump($DATA);
  $count = 0;
  $boolean = false;
  $Sql = "SELECT COUNT(*) AS Cnt
  FROM shelfcount
  WHERE IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['shelfcount_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM factory_out
  WHERE IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['fac_out_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM daily_request WHERE DATE(daily_request.DocDate) = DATE(NOW())";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['daily_request_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getnotification";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getnotification";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function alert_SetPrice($conn,$DATA)
{
  $PmID = $DATA['PmID'];
  $HptCode = $DATA['HptCode'];
  $Userid = $DATA['Userid'];
  $boolean = false;
  $count = 0;
  if($PmID == 1){
    $Sql = "SELECT
    con.StartDate,
    con.EndDate,
    cat_P.DocNo , 
    site.HptCode , 
    site.HptName ,    
    cat_P.xDate ,  
    DATEDIFF(cat_P.xDate, CURDATE()) AS dateDiff  
  FROM category_price_time cat_P
  INNER JOIN users ON users.ID = $Userid  
  INNER JOIN site ON site.HptCode = cat_P.HptCode  
  INNER JOIN contract_parties_hospital con ON con.HptCode = site.HptCode
  WHERE cat_P.STATUS = 0 AND con.IsStatus = 0
  GROUP BY
    cat_P.xDate,
    cat_P.DocNo
  ORDER BY dateDiff ASC";
  }else{
      $Sql = "SELECT
        con.StartDate,
        con.EndDate,
        cat_P.DocNo ,
        site.HptCode , 
        site.HptName ,    
        cat_P.xDate ,  
        DATEDIFF(cat_P.xDate, CURDATE()) AS dateDiff  
      FROM category_price_time cat_P
      INNER JOIN users ON users.ID = $Userid  
      INNER JOIN site ON site.HptCode = '$HptCode'   
      INNER JOIN contract_parties_hospital con ON con.HptCode = site.HptCode
      WHERE cat_P.HptCode = '$HptCode' AND cat_P.STATUS = 0 AND con.IsStatus = 0
      GROUP BY
        cat_P.xDate,
        cat_P.DocNo
      ORDER BY dateDiff ASC";
  }
  $meQuery = mysqli_query($conn,$Sql);

  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
      $return[$count]['set_price']['HptCode'] = $Result['HptCode'];
      $return[$count]['set_price']['HptName'] = $Result['HptName'];
      $return[$count]['set_price']['StartDate'] = $Result['StartDate'];
      $return[$count]['set_price']['EndDate'] = $Result['EndDate'];
      $return[$count]['set_price']['DocNo'] = $Result['DocNo'];
      $return[$count]['set_price']['xDate'] = $Result['xDate'];
      $return[$count]['set_price']['dateDiff'] = $Result['dateDiff'];
      $DateDiff = $Result['dateDiff'];

      #send email to user---------------------------------------------------
      $HptCode = $Result['HptCode'];
      $DocNo = $Result['DocNo'];

      if($DateDiff == 30){
        $count_active = "SELECT COUNT(*) AS cnt FROM alert_mail_price WHERE DocNo = '$DocNo' AND day_30 = 1";
      }else if($DateDiff == 7){
        $count_active = "SELECT COUNT(*) AS cnt FROM alert_mail_price WHERE DocNo = '$DocNo' AND day_7 = 1";
      }
      $countQuery = mysqli_query($conn,$count_active);
      while ($CResult = mysqli_fetch_assoc($countQuery)) {
        $return[$count]['set_price']['cntAcive'] = $CResult['cnt'];
        if($CResult['cntAcive'] == 0){
          $SelectMail = "SELECT users.email FROM users WHERE users.HptCode = '$HptCode' AND users.Active_mail = 1";
          $SQuery = mysqli_query($conn,$SelectMail);
          while ($SResult = mysqli_fetch_assoc($SQuery)) {
            $return[$count]['set_price']['email'] = $SResult['email'];
          }
        }
      }
      #end send mail------------------------------------------------------------
      $count++;
      $boolean = true; 
    }
  }
  $return['countSetprice'] = $count;
  #-------------------------------------------------------------------------------------
  $count2 = 0;
  if($PmID == 1 || $PmID == 2 || $PmID == 3 || $PmID ==6){
    $Sql = "SELECT factory.FacName,cf.StartDate, cf.EndDate, 
        DATEDIFF(cf.EndDate, cf.StartDate) AS dateDiff   
        FROM contract_parties_factory cf 
        INNER JOIN factory ON factory.FacCode = cf.FacCode
        ORDER BY dateDiff ASC";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
        $return[$count2]['contract_fac']['FacName'] = $Result['FacName'];
        $return[$count2]['contract_fac']['StartDate'] = $Result['StartDate'];
        $return[$count2]['contract_fac']['EndDate'] = $Result['EndDate'];
        $return[$count2]['contract_fac']['dateDiff'] = $Result['dateDiff'];
        $DateDiff = $Result['dateDiff'];
      }
      $count2++;
    }
  }
  $return['countFac'] = $count2;
  #-------------------------------------------------------------------------------------
  $count3 = 0;
  if($PmID == 1 || $PmID == 2 || $PmID == 3 || $PmID ==6){
    $Sql = "SELECT site.HptName,ch.StartDate, ch.EndDate, 
        DATEDIFF(ch.EndDate, ch.StartDate) AS dateDiff   
        FROM contract_parties_hospital ch 
        INNER JOIN site ON site.HptCode = ch.HptCode
        ORDER BY dateDiff ASC";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
        $return[$count3]['contract_hos']['HptName'] = $Result['HptName'];
        $return[$count3]['contract_hos']['StartDate'] = $Result['StartDate'];
        $return[$count3]['contract_hos']['EndDate'] = $Result['EndDate'];
        $return[$count3]['contract_hos']['dateDiff'] = $Result['dateDiff'];
        $DateDiff = $Result['dateDiff'];
      }
      $count3++;
    }
  }
  $return['countHos'] = $count3;
  #-------------------------------------------------------------------------------------


  $return['status'] = "success";
  $return['form'] = "alert_SetPrice";
  echo json_encode($return);
  mysqli_close($conn);
  die;


}


//==========================================================
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }elseif ($DATA['STATUS']=='getnotification') {
    getnotification($conn,$DATA);
  }elseif ($DATA['STATUS']=='alert_SetPrice') {
    alert_SetPrice($conn,$DATA);
  }elseif ($DATA['STATUS']=='email') {
    email($conn,$DATA);
  }
}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
