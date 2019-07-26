<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
require '../PHPMailer/PHPMailerAutoload.php';
$xDate = date('Y-m-d');
// 
function OnLoadPage($conn,$DATA){
  $hptcode = $DATA['hptcode'];

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
  WHERE  site.HptCode = '$hptcode' AND shelfcount.IsStatus = 0
  ORDER BY shelfcount.DocNo DESC";
  $return['sql'] =$Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['RefDocNo'] = $Result['RefDocNo'];
    $return[$count]['Detail'] = $Result['Detail'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
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
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);

  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['dateDiff'] == 30 || $Result['dateDiff'] == 7){
      $return[$count]['HptCode'] = $Result['HptCode'];
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['StartDate'] = $Result['StartDate'];
      $return[$count]['EndDate'] = $Result['EndDate'];
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['xDate'] = $Result['xDate'];
      $return[$count]['DateDiff'] = $Result['dateDiff'];
      $DateDiff = $Result['dateDiff'];

      #send email to user---------------------------------------------------
      $HptCode = $Result['HptCode'];
      $DocNo = $Result['DocNo'];
      if($DateDiff == 30){
        $count_active = "SELECT COUNT(*) AS cnt FROM alert_mail_price WHERE DocNo = '$DocNo' AND HptCode = '$HptCode' AND day_30 = 1";
      }else if($DateDiff == 7){
        $count_active = "SELECT COUNT(*) AS cnt FROM alert_mail_price WHERE DocNo = '$DocNo' AND HptCode = '$HptCode' AND day_7 = 1";
      }
      $countQuery = mysqli_query($conn,$count_active);
      while ($CResult = mysqli_fetch_assoc($countQuery)) {
        if($CResult['cnt'] == 0){
          $SelectMail = "SELECT users.email FROM users WHERE users.HptCode = '$HptCode' AND users.Active_mail = 1";
          $SQuery = mysqli_query($conn,$SelectMail);
          while ($SResult = mysqli_fetch_assoc($SQuery)) {
            $email = $SResult['email'];

            alert_sendMail($email);

            if($DateDiff == 30){
              $update_alert = "UPDATE alert_mail_price SET day_30 = 1 WHERE DocNo = '$DocNo' AND HptCode = '$HptCode'";
            }else{
              $update_alert = "UPDATE alert_mail_price SET day_7 = 1 WHERE DocNo = '$DocNo' AND HptCode = '$HptCode'";
            }
            mysqli_query($conn,$update_alert);
          }
        }
      }
      #end send mail------------------------------------------------------------
      $count++;
      $boolean = true; 
    }

  }

  $return['countRow'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "alert_SetPrice";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "alert_SetPrice";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function alert_sendMail($email){
  // build message body
$body = '
<html>
<body>
<br>
___________________________________________________________________<br>
Name: Test<br>
UserName: Test<br>
___________________________________________________________________<br>
<br>
Thanks...<br>
</body>
</html>
';

$mail = new PHPMailer;
$mail->CharSet = "UTF-8";
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.live.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "poseintelligence@hotmail.com";
$mail->Password = "P6o6s2e8";
$mail->setFrom('poseintelligence@hotmail.com', 'Pose Intelligence');
$mail->addAddress($email);
$mail->Subject = 'แจ้งเตือนเปลี่ยนราคา';
$mail->msgHTML($body);
$mail->AltBody = 'This is a plain-text message body';

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
  }
}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
