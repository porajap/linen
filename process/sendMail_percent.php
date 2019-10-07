<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
require '../PHPMailer/PHPMailerAutoload.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}

$HptName = $_POST['HptName'];
$Total1 = $_POST['Total1'];
$Total2 = $_POST['Total2'];
$Precent = $_POST['Precent'];
$email = $_POST['email'];
$DocNoC = $_POST['DocNoC'];
$DocNoD = $_POST['DocNoD'];
$HptNameTH = $_POST['HptNameTH'];

// if($dateDiff == 30){
//     $update_alert = "UPDATE contract_parties_hospital SET day_30 = 1 WHERE RowID = '$RowID'";
// }else{
//     $update_alert = "UPDATE contract_parties_hospital SET day_7 = 1 WHERE RowID = '$RowID'";
// }
// mysqli_query($conn,$update_alert);
    // build message body
$body = '
<html>
<body>
<br>
___________________________________________________________________<br>
<h3>'.$HptNameTH.'</h3>
<b>เลขที่เอกสารผ้าสกปรก:</b> '.$DocNoD.'<br> 
<b>นํ้าหนักผ้าสกปรก:</b> '.$Total1.'<br>
<b>เลขที่เอกสารผ้าสะอาด:</b> '.$DocNoC.'<br>
<b>นํ้าหนักผ้าสะอาด:</b> '.$Total2.' <br>
<b>เปอร์เซ็นที่ได้:</b> '.$Precent.'  <br>
___________________________________________________________________<br>
<h3>'.$HptName.'</h3>
<b>DOCNO DIRTY:</b> '.$DocNoD.'<br> 
<b>WEIGHT DIRTY:</b> '.$Total1.'<br>
<b>DOCNO CLEAN:</b> '.$DocNoC.'<br>
<b>WEIGHT CIEAN:</b> '.$Total2.'<br>
<b>PERCENT:</b> '.$Precent.' <br>
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
$mail->Subject = 'แจ้งเตือนเปอร์เซ็นต์เกิน';
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