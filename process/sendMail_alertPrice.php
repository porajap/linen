
<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
?>


<html>
<head>
<!-- <title>Pose Inttelligence</title> -->
</head>
<body>
<?php
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
  #-------------------------------------------------------------------------------------

  $strTo = $email;
  $strSubject = "=?UTF-8?B?".base64_encode("Notification change price")."?=";
	// $strHeader .= "MIME-Version: 1.0'\r\n";
	$strHeader .= "Content-type: text/html; charset=utf-8\r"; 
	$strHeader .= "From: poseinttelligence@gmail.com>";
	$strMessage = "
	<br>
  ___________________________________________________________________<br>
  <h3>$HptName</h3>
  <b>วันที่ทำสัญญา:</b>$StartDate<br>
  <b>วันที่สิ้นสุดสัญญา:</b>$EndDate<br>
  <b>เลขที่เอกสาร:</b>$DocNo<br>
  <b>เปลี่ยนราคาวันที่:</b> $xDate เหลือเวลาอีก $dateDiff วัน<br>
  ___________________________________________________________________<br>
  <h3>$HptName</h3>
  <b>DATE OF CNTRCT:</b> $StartDate<br>
  <b>CNTRCT TERM DATE:</b> $EndDate<br>
  <b>Doc No:</b> $DocNo<br>
  <b>DATE OF CHG PC.:</b> $xDate TIME LT $dateDiff วัน<br>
  ___________________________________________________________________<br>";

	$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //
	if($flgSend)
	{
    echo "Email Sending.";
	}
	else
	{
		echo "Email Can Not Send.";
	}
?>
</body>
</html>