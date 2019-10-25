<html>
<head>
<title></title>
</head>
<body>
<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
require '../PHPMailer/PHPMailerAutoload.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
$FacName = $_POST['FacName'];
$FacNameTH = $_POST['FacNameTH'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];
$email = $_POST['email'];
$dateDiff = $_POST['dateDiff'];
$RowID = $_POST['RowID'];
if($dateDiff == 30){
    $update_alert = "UPDATE contract_parties_factory SET day_30 = 1 WHERE RowID = '$RowID'";
}else{
    $update_alert = "UPDATE contract_parties_factory SET day_7 = 1 WHERE RowID = '$RowID'";
}
mysqli_query($conn,$update_alert);
    // build message body
$body = '
   
___________________________________________________________________   
'.$FacNameTH.'
วันที่ทำสัญญา: '.$StartDate.'   
วันที่สิ้นสุดสัญญา: '.$EndDate.'   
หมดสัญญาวันที่: '.$EndDate.' เหลือเวลาอีก '.$dateDiff.' วัน   
___________________________________________________________________   
'.$FacName.'
DATE OF CNTRCT: '.$StartDate.'   
CONTRCT TERM DATE: '.$EndDate.'   
Expire: '.$EndDate.' Time left '.$dateDiff.' Day   
___________________________________________________________________   
';
$strTo = $email;
$strSubject = "Notification of laundry contract expiration";
$strHeader = "From: poseinttelligence@gmail.com";
$strMessage = $body;
$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //
?>
</body>
</html>