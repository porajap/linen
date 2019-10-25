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

<br>
___________________________________________________________________<br>
<h3>'.$HptNameTH.'</h3>
<b>เลขที่เอกสารผ้าสกปรก:</b> '.$DocNoD.'<br> 
<b>นํ้าหนักผ้าสกปรก:</b> '.$Total1.' <b>กิโลกรัม</b><br>
<b>เลขที่เอกสารผ้าสะอาด:</b> '.$DocNoC.'<br>
<b>นํ้าหนักผ้าสะอาด:</b> '.$Total2.' <b>กิโลกรัม</b><br>
<b>เปอร์เซ็นที่ได้:</b> '.$Precent.'  <b>%</b><br>
___________________________________________________________________<br>
<h3>'.$HptName.'</h3>
<b>DOCNO DIRTY:</b> '.$DocNoD.'<br> 
<b>WEIGHT DIRTY:</b> '.$Total1.' <b>kilogram</b><br>
<b>DOCNO CLEAN:</b> '.$DocNoC.'<br>
<b>WEIGHT CLEAN:</b> '.$Total2.' <b>kilogram</b><br>
<b>PERCENT:</b> '.$Precent.'  <b>%</b><br>
___________________________________________________________________<br>

';




$strTo = $email;
$strSubject = "Notification Over Percent";
$strHeader = "From: poseinttelligence@gmail.com";
$strHeader .= "Content-type: text/html; charset=utf-8\r\n"; 
$strMessage = $body;
$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //

?>
</body>