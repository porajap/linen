<html>
<head>
<title>ThaiCreate.Com PHP PDF</title>
</head>
<body>

<?php
require('fpdf.php');

class PDF extends FPDF
{
//Load data
function LoadData($file)
{
	//Read file lines
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
}

//Simple table
function BasicTable($header,$data)
{
	//Header
	$w=array(30,30,55,25,20,20);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data
	foreach ($data as $eachResult) 
	{
		$this->Cell(30,6,$eachResult["CustomerID"],1);
		$this->Cell(30,6,$eachResult["Name"],1);
		$this->Cell(55,6,$eachResult["Email"],1);
		$this->Cell(25,6,$eachResult["CountryCode"],1,0,'C');
		$this->Cell(20,6,$eachResult["Budget"],1);
		$this->Cell(20,6,$eachResult["Budget"],1);
		$this->Ln();
	}
}

//Better table
function ImprovedTable($header,$data)
{
	//Column widths
	$w=array(20,30,55,25,25,25);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data

	foreach ($data as $eachResult) 
	{
		$this->Cell(20,6,$eachResult["CustomerID"],1);
		$this->Cell(30,6,$eachResult["Name"],1);
		$this->Cell(55,6,$eachResult["Email"],1);
		$this->Cell(25,6,$eachResult["CountryCode"],1,0,'C');
		$this->Cell(25,6,number_format($eachResult["Budget"],2),1,0,'R');
		$this->Cell(25,6,number_format($eachResult["Budget"],2),1,0,'R');
		$this->Ln();
	}
	//Closure line
	$this->Cell(array_sum($w),0,'','T');
}

//Colored table
function FancyTable($header,$data)
{
	//Colors, line width and bold font
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	//Header
	$w=array(20,30,55,25,25,25);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	//Color and font restoration
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	//Data
	$fill=false;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
		$this->Cell($w[3],6,$row[3],'LR',0,'C',$fill);
		$this->Cell($w[4],6,number_format($row[4]),'LR',0,'R',$fill);
		$this->Cell($w[5],6,number_format($row[5]),'LR',0,'R',$fill);
		$this->Ln();
		$fill=!$fill;
	}
	$this->Cell(array_sum($w),0,'','T');
}
}

$pdf=new PDF();
//Column titles
$header=array('CustomerID','Name','Email','Country Code','Budget','Used');
//Data loading

//*** Load MySQL Data ***//
$objConnect = mysql_connect("localhost","root","root") or die("Error Connect to Database");
$objDB = mysql_select_db("mydatabase");
$strSQL = "SELECT * FROM customer";
$objQuery = mysql_query($strSQL);
$resultData = array();
for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
	$result = mysql_fetch_array($objQuery);
	array_push($resultData,$result);
}
//************************//



$pdf->SetFont('Arial','',10);

//*** Table 1 ***//
$pdf->AddPage();
$pdf->Image('logo.png',80,8,33);
$pdf->Ln(35);
$pdf->BasicTable($header,$resultData);

//*** Table 2 ***//
$pdf->AddPage();
$pdf->Image('logo.png',80,8,33);
$pdf->Ln(35);
$pdf->ImprovedTable($header,$resultData);

//*** Table 3 ***//
$pdf->AddPage();
$pdf->Image('logo.png',80,8,33);
$pdf->Ln(35);
$pdf->FancyTable($header,$resultData);

$pdf->Output("MyPDF/MyPDF.pdf","F");


//*************** Send Email ***************//

$strTo = "member@thaicreate.com";
$strSubject = "PDF Report";
$strMessage = "Download MyPDF.pdf for PDF Report";

//*** Uniqid Session ***//
$strSid = md5(uniqid(time()));

$strHeader = "";
$strHeader .= "From: Mr.Weerachai Nukitram<webmaster@thaicreate.com>\nReply-To: webmaster@thaicreate.com\n";
$strHeader .= "Cc: Mr.Surachai Sirisart<surachai@thaicreate.com>";
$strHeader .= "Bcc: webmaster@thaicreate.com";

$strHeader .= "MIME-Version: 1.0\n";
$strHeader .= "Content-Type: multipart/mixed; boundary=\"".$strSid."\"\n\n";
$strHeader .= "This is a multi-part message in MIME format.\n";

$strHeader .= "--".$strSid."\n";
$strHeader .= "Content-type: text/html; charset=windows-874\n"; // or UTF-8 //
$strHeader .= "Content-Transfer-Encoding: 7bit\n\n";
$strHeader .= $strMessage."\n\n";

$strContent1 = chunk_split(base64_encode(file_get_contents("MyPDF/MyPDF.pdf")));
$strHeader .= "--".$strSid."\n";
$strHeader .= "Content-Type: application/octet-stream; name=\"MyPDF.pdf\"\n";
$strHeader .= "Content-Transfer-Encoding: base64\n";
$strHeader .= "Content-Disposition: attachment; filename=\"MyPDF.pdf\"\n\n";
$strHeader .= $strContent1."\n\n";


$flgSend = @mail($strTo,$strSubject,null,$strHeader); // @ = No Show Error //
if($flgSend)
{
echo "PDF Generated & Email Sending.";
}
else
{
echo "Email Can Not Send.";
}

?>
</body>
</html>
