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
$HptNameTH = $_POST['HptNameTH'];
$HptName = $_POST['HptName'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];
$email = $_POST['email'];
$dateDiff = $_POST['dateDiff'];
$RowID = $_POST['RowID'];

if($dateDiff == 30){
    $update_alert = "UPDATE contract_parties_hospital SET day_30 = 1 WHERE RowID = '$RowID'";
}else{
    $update_alert = "UPDATE contract_parties_hospital SET day_7 = 1 WHERE RowID = '$RowID'";
}
mysqli_query($conn,$update_alert);
    // build message body
$body = '

<br>
___________________________________________________________________<br>
<h3>'.$HptNameTH.'</h3>
<b>วันที่ทำสัญญา:</b> '.$StartDate.'<br>
<b>วันที่สิ้นสุดสัญญา:</b> '.$EndDate.'<br>
<b>หมดสัญญาวันที่:</b> '.$EndDate.' เหลือเวลาอีก '.$dateDiff.' วัน<br>
___________________________________________________________________<br>
<h3>'.$HptName.'</h3>
<b>DATE OF CNTRCT:</b> '.$StartDate.'<br>
<b>CONTRCT TERM DATE:</b> '.$EndDate.'<br>
<b>Expire:</b> '.$EndDate.' Time left '.$dateDiff.' Day<br>
___________________________________________________________________<br>

';

$strTo = $email;
$strSubject = "Notification of hospital contract expiration";
$strHeader = "From: poseinttelligence@gmail.com";
$strMessage = $body;
$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //

?>
</body>