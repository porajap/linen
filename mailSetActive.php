<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Bangkok');
require 'PHPMailer/PHPMailerAutoload.php';

if(isset($_POST['DATA']))
{
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace ('\"','"', $data), true);
    $Email = $DATA['Email'];
    $Username = $DATA['Username'];
    $Password = $DATA['Password'];
}else{

}

// build message body
$body = '
<html>
<body>
<br>
___________________________________________________________________<br>
Titel: Internet disconnect and Reset login.<br>
UserName: '.$Username.'<br>
Password: '.$Password.'<br>
___________________________________________________________________<br>
<br>
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

$mail->addAddress($Email, $Username);
$mail->Subject = $Subject;
$mail->msgHTML($body);
$mail->AltBody = 'This is a plain-text message body';
//$mail->addAttachment('images/phpmailer_mini.png');
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