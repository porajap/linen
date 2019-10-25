
<?php

if(isset($_POST['DATA']))
{
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace ('\"','"', $data), true);
    $email = $DATA['email'];
    $user = $DATA['UserName'];
    $pass = $DATA['Password'];
    $FName = $DATA['FName'];
    $Subject = $DATA['Subject'];
    $HptName = $DATA['HptName'];
    $DepName = $DATA['DepName'];
}else{

}
// build message body
$body = '

Hospital: '.$HptName.'
Department: '.$DepName.'
___________________________________________________________________
Name: '.$FName.'

UserName: '.$user.'

Password: '.$pass.'
___________________________________________________________________
Thanks...

';
	$strTo = $email;
	$strSubject = "Reset password...";
	$strHeader = "From: poseinttelligence@gmail.com";
	$strMessage = $body;
	$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //
?>
