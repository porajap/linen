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
<html>
<body>
<br>
___________________________________________________________________<br>
<h3>'.$FacName.'</h3>
<b>วันที่ทำสัญญา:</b> '.$StartDate.'<br>
<b>วันที่สิ้นสุดสัญญา:</b> '.$EndDate.'<br>
<b>หมดสัญญาวันที่:</b> '.$EndDate.' เหลือเวลาอีก '.$dateDiff.' วัน<br>
___________________________________________________________________<br>
<h3>'.$FacName.'</h3>
<b>DATE OF CNTRCT:</b> '.$StartDate.'<br>
<b>CNTRCT TERM DATE:</b> '.$EndDate.'<br>
___________________________________________________________________<br>
</body>
</html>
';

$mail = new PHPMailer;
$mail->CharSet = "UTF-8";
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "poseinttelligence@gmail.com";
$mail->Password = "pose6628";
$mail->setFrom('poseinttelligence@gmail.com', 'Pose Intelligence');
$mail->addAddress($email);
$mail->Subject = 'แจ้งเตือนหมดสัญญาโรงซัก';
$mail->msgHTML($body);
$mail->AltBody = 'This is a plain-text message body';
// $mail->send();
if (!$mail->send()) {
$return['msg'] = "Mailer Error: " . $mail->ErrorInfo;
echo json_encode($return);
die;
} else {
$return['msg'] = "Message sent!";
echo json_encode($return);
die;
}



?>