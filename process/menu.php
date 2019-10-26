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
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  if($PmID == 1 || $PmID == 6){
    $Sql = "SELECT
    con.StartDate,
    con.EndDate,
    cat_P.DocNo , 
    site.HptCode , 
    site.HptName ,    
    site.HptNameTH ,       
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
        site.HptNameTH ,       
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

      if($lang == 'th'){
        if($lang == 'th'){
          $hptlang = $Result['HptNameTH'];
          $date = explode("-", $Result['StartDate']);
          $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);
      
          $date2 = explode("-", $Result['EndDate']);
          $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0] +543);
        }
      }else if($lang == 'en'){
        $hptlang = $Result['HptName'];
        $date = explode("-", $Result['StartDate']);
        $newdate = $date[2].'-'.$date[1].'-'.$date[0];
    
        $date2 = explode("-", $Result['EndDate']);
        $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];
      }

      $return[$count]['set_price']['HptCode'] = $Result['HptCode'];
      $return[$count]['set_price']['hptlang'] = $hptlang;
      $return[$count]['set_price']['HptName'] = $Result['HptName'];
      $return[$count]['set_price']['HptNameTH'] = $Result['HptNameTH'];
      $return[$count]['set_price']['StartDate'] = $newdate;
      $return[$count]['set_price']['EndDate'] = $newdate2;
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
          $SelectMail = "SELECT users.email, 	site.HptName
          FROM users
          INNER JOIN site ON site.HptCode = users.HptCode
          WHERE users.HptCode = '$HptCode'
          AND users.PmID IN (1,6)
          AND email IS NOT NULL AND NOT email = '' AND NOT email = '-'";
          $return['mail'] = $SelectMail;
          $SQuery = mysqli_query($conn,$SelectMail);
          while ($SResult = mysqli_fetch_assoc($SQuery)) {
            $return[$count]['set_price']['email'] = trim($SResult['email']);
          }
        }
      }
      #end send mail------------------------------------------------------------
      $count++;
    }
  }
  $return['countSetprice'] = $count;
  #-------------------------------------------------------------------------------------
  $count2 = 0;
  if($PmID == 1 || $PmID == 2 || $PmID == 3 || $PmID ==6){
    $Sql = "SELECT cf.RowID, factory.FacCode, factory.FacName, cf.StartDate, cf.EndDate,
        DATEDIFF(cf.EndDate, DATE(NOW()))  AS dateDiff , factory.FacNameTH  
        FROM contract_parties_factory cf 
        INNER JOIN factory ON factory.FacCode = cf.FacCode
        WHERE cf.IsStatus = 0
        ORDER BY dateDiff ASC";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
        if($lang == 'th'){
          if($lang == 'th'){
            $hptlang = $Result['FacNameTH'];
            $date = explode("-", $Result['StartDate']);
            $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);
        
            $date2 = explode("-", $Result['EndDate']);
            $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0] +543);
          }
        }else if($lang == 'en'){
          $hptlang = $Result['FacName'];
          $date = explode("-", $Result['StartDate']);
          $newdate = $date[2].'-'.$date[1].'-'.$date[0];
      
          $date2 = explode("-", $Result['EndDate']);
          $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];
        }
        $return[$count2]['contract_fac']['FacName'] = $Result['FacName'];
        $return[$count2]['contract_fac']['FacNameTH'] = $Result['FacNameTH'];
        $return[$count2]['contract_fac']['StartDate'] = $newdate;
        $return[$count2]['contract_fac']['hptlang'] = $hptlang;
        $return[$count2]['contract_fac']['EndDate'] = $newdate2;
        $return[$count2]['contract_fac']['dateDiff'] = $Result['dateDiff'];
        $return[$count2]['contract_fac']['RowID'] = $Result['RowID'];
        $DateDiff = $Result['dateDiff'];
        $RowID = $Result['RowID'];
        $FacCode = $Result['FacCode'];
      
        if($DateDiff == 30){
          $count_active = "SELECT COUNT(*) AS cnt FROM contract_parties_factory WHERE RowID = '$RowID' AND day_30 = 1";
        }else if($DateDiff == 7){
          $count_active = "SELECT COUNT(*) AS cnt FROM contract_parties_factory WHERE RowID = '$RowID' AND day_7 = 1";
        }
        $countQuery = mysqli_query($conn,$count_active);
        while ($CResult = mysqli_fetch_assoc($countQuery)) {
          $return[$count2]['contract_fac']['cntAcive'] = $CResult['cnt'];
          if($CResult['cntAcive'] == 0){
            $i = 0;
            $SelectMail = "SELECT users.email, 	site.HptName
            FROM users
            INNER JOIN site ON site.HptCode = users.HptCode
            WHERE users.HptCode = '$HptCode'
            AND users.PmID IN (1,6)
            AND email IS NOT NULL AND NOT email = '' AND NOT email = '-'";
            $SQuery = mysqli_query($conn,$SelectMail);
            while ($SResult = mysqli_fetch_assoc($SQuery)) {
              $return[$i]['contract_fac']['email'] = $SResult['email'];
              $return[0]['contract_fac']['HptName'] = $SResult['HptName'];
              $i++;
            }
          }
        }
        $return[$count2]['countMailFac'] = $i;
        $count2++;
      }
      
    }
  }
  $return['countFac'] = $count2;
  #-------------------------------------------------------------------------------------
  $count3 = 0;
  if($PmID == 1 || $PmID == 2 || $PmID == 3 || $PmID ==6){
    $Sql = "SELECT site.HptName, ch.RowID, ch.StartDate, ch.EndDate, 
        DATEDIFF(ch.EndDate, DATE(NOW())) AS dateDiff ,  site.HptNameTH 
        FROM contract_parties_hospital ch 
        INNER JOIN site ON site.HptCode = ch.HptCode
        WHERE ch.IsStatus = 0
        ORDER BY dateDiff ASC";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
        if($lang == 'th'){
          if($lang == 'th'){
            $hptlang = $Result['HptNameTH'];
            $date = explode("-", $Result['StartDate']);
            $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);
        
            $date2 = explode("-", $Result['EndDate']);
            $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0] +543);
          }
        }else if($lang == 'en'){
          $hptlang = $Result['HptName'];
          $date = explode("-", $Result['StartDate']);
          $newdate = $date[2].'-'.$date[1].'-'.$date[0];
      
          $date2 = explode("-", $Result['EndDate']);
          $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];
        }
        $return[$count3]['contract_hos']['HptName'] = $Result['HptName'];
        $return[$count3]['contract_hos']['HptNameTH'] = $Result['HptNameTH'];
        $return[$count3]['contract_hos']['StartDate'] = $newdate;
        $return[$count3]['contract_hos']['hptlang'] = $hptlang;
        $return[$count3]['contract_hos']['EndDate'] = $newdate2;
        $return[$count3]['contract_hos']['dateDiff'] = $Result['dateDiff'];
        $return[$count3]['contract_hos']['RowID'] = $Result['RowID'];
        $DateDiff = $Result['dateDiff'];
        $RowID = $Result['RowID'];
        if($DateDiff == 30){
          $count_active = "SELECT COUNT(*) AS cnt FROM contract_parties_hospital WHERE RowID = '$RowID' AND day_30 = 1";
        }else if($DateDiff == 7){
          $count_active = "SELECT COUNT(*) AS cnt FROM contract_parties_hospital WHERE RowID = '$RowID' AND day_7 = 1";
        }
        $countQuery = mysqli_query($conn,$count_active);
        while ($CResult = mysqli_fetch_assoc($countQuery)) {
          $return[$count3]['contract_hos']['cntAcive'] = $CResult['cnt'];
          if($CResult['cntAcive'] == 0){
            $i = 0;
            $SelectMail = "SELECT users.email, 	site.HptName
            FROM users
            INNER JOIN site ON site.HptCode = users.HptCode
            WHERE users.HptCode = '$HptCode'
            AND users.PmID IN (1,6)
            AND email IS NOT NULL AND NOT email = '' AND NOT email = '-'";
            $SQuery = mysqli_query($conn,$SelectMail);
            while ($SResult = mysqli_fetch_assoc($SQuery)) {
              $return[$i]['contract_hos']['email'] = $SResult['email'];
              $return[0]['contract_hos']['HptName'] = $SResult['HptName'];
              $i++;
            }
          }
        }
        $return[$count3]['countMailHos'] = $i;
        $count3++;
      }
      
    }
  }
  $return['countHos'] = $count3;
  #-------------------------------------------------------------------------------------
  $count4 = 0;
  if($PmID == 1 ){
    $Sql = "SELECT
    site.HptName,
    department.DepName,
    dirty.DocNo AS DocNo1,
    DATE(dirty.DocDate) AS DocDate1,
    dirty.Total AS Total1,
    clean.DocNo AS DocNo2,
    DATE(clean.DocDate) AS DocDate2,
    clean.Total AS Total2,
    ROUND( (((dirty.Total - clean.Total )/dirty.Total)*100), 2)  AS Precent,
		clean.sendmail
    FROM clean
    INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
    INNER JOIN department ON clean.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    ORDER BY clean.DocNo DESC LIMIT 100";
    $meQueryPercent = mysqli_query($conn,$Sql);
    while ($ResultPercent = mysqli_fetch_assoc($meQueryPercent)) {
      $percent 	= $ResultPercent['Precent'];
      $DocNoClean 	= $ResultPercent['DocNo2'];
      $sendmail 	= $ResultPercent['sendmail'];
      if($percent > 8 && $sendmail ==0){
        $return[$count4]['percent']['DocNoD'] 	= $ResultPercent['DocNo1'];
        $return[$count4]['percent']['DocNoC'] 	= $ResultPercent['DocNo2'];
        $return[$count4]['percent']['Total1'] 	= $ResultPercent['Total1'];
        $return[$count4]['percent']['Total2'] 	= $ResultPercent['Total2'];
        $return[$count4]['percent']['Precent'] 	= $ResultPercent['Precent'];
        $SqlUp="UPDATE clean SET sendmail = 1 WHERE DocNo = '$DocNoClean'";
        $meQuery = mysqli_query($conn,$SqlUp);
        $i = 0;
        if($meQuery = mysqli_query($conn,$SqlUp)){
        $SelectMail1 = "SELECT users.email, 	site.HptName , site.HptNameTH
            FROM users
            INNER JOIN site ON site.HptCode = users.HptCode
            WHERE users.HptCode = '$HptCode'
            AND users.PmID = 1
            AND email IS NOT NULL AND NOT email = ''";
            $SQuery1 = mysqli_query($conn,$SelectMail1);
            while ($SResult1 = mysqli_fetch_assoc($SQuery1)) {
              $return[$i]['percent']['email'] = $SResult1['email'];
              $return[0]['percent']['HptName'] = $SResult1['HptName'];
              $return[0]['percent']['HptNameTH'] = $SResult1['HptNameTH'];
              $i++;
            }
            $return[$count4]['countMailpercent'] = $i;
            $count4++;
      }
    }
  }
}
  $return['countpercent'] = $count4;
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
