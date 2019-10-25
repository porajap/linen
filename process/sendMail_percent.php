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
___________________________________________________________________
'.$HptNameTH.'
เลขที่เอกสารผ้าสกปรก: '.$DocNoD.' 
นํ้าหนักผ้าสกปรก: '.$Total1.' กิโลกรัม
เลขที่เอกสารผ้าสะอาด: '.$DocNoC.'
นํ้าหนักผ้าสะอาด: '.$Total2.' กิโลกรัม
เปอร์เซ็นที่ได้: '.$Precent.'  %
___________________________________________________________________
'.$HptName.'
DOCNO DIRTY: '.$DocNoD.' 
WEIGHT DIRTY: '.$Total1.' kilogram
DOCNO CLEAN: '.$DocNoC.'
WEIGHT CLEAN: '.$Total2.' kilogram
PERCENT: '.$Precent.'  %
___________________________________________________________________
';
$strTo = $email;
$strSubject = "Notification Over Percent";
$strHeader = "From: poseinttelligence@gmail.com";
$strMessage = $body;
$flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //
?>
</body>