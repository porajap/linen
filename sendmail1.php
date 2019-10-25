<html>
<head>
<title>PHP Sending Email</title>
</head>
<body>
<?php
	$strTo = "modepc001@gmail.com";
	$strSubject = "Test Send Email";
	$strHeader = "From: poseinttelligence@gmail.com";
	$strMessage = "My Body & My Description";
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