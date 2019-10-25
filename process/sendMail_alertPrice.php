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
$DocNo = $_POST['DocNo'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];
$xDate = $_POST['xDate'];
$email = $_POST['email'];
$dateDiff = $_POST['dateDiff'];

if($dateDiff == 30){
    $update_alert = "UPDATE alert_mail_price SET day_30 = 1 WHERE DocNo = '$DocNo'";
}else{
    $update_alert = "UPDATE alert_mail_price SET day_7 = 1 WHERE DocNo = '$DocNo'";
}
mysqli_query($conn,$update_alert);
    // build message body
$body = '
<html>
<body>
<br>
___________________________________________________________________<br>
<h3>'.$HptNameTH.'</h3>
<b>วันที่ทำสัญญา:</b> '.$StartDate.'<br>
<b>วันที่สิ้นสุดสัญญา:</b> '.$EndDate.'<br>
<b>เลขที่เอกสาร:</b> '.$DocNo.'<br>
<b>เปลี่ยนราคาวันที่:</b> '.$xDate.' เหลือเวลาอีก '.$dateDiff.' วัน<br>
___________________________________________________________________<br>
<h3>'.$HptName.'</h3>
<b>DATE OF CNTRCT:</b> '.$StartDate.'<br>
<b>CNTRCT TERM DATE:</b> '.$EndDate.'<br>
<b>Doc No:</b> '.$DocNo.'<br>
<b>DATE OF CHG PC.:</b> '.$xDate.' TIME LT '.$dateDiff.' วัน<br>
___________________________________________________________________<br>
</body>
</html>
';

$strTo = $email;
$strSubject = "Notification change price";
$strHeader = "From: poseinttelligence@gmail.com";
$strMessage = $body;
$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //
?>
</body>
</html>