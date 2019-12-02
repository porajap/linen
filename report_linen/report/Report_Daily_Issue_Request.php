<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
$year = $data['year'];
$format = $data['Format'];
$DepCode = $data['DepCode'];
$where = '';
$language = $_SESSION['lang'];
$docno = $_GET['Docno'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
//print_r($data);
if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (shelfcount.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (shelfcount.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE shelfcount.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year2 = $year2 + 543;
    $year = $year + 543;
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . $array['to'][$language] .
      $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
  } else {
    $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year . " " . $array['to'][$language] . " " .
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . $year2;
  }
} elseif ($chk == 'month') {
  $where =   "WHERE month (shelfcount.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
  list($year, $mouth, $day) = explode("-", $betweendate1);
  list($year2, $mouth2, $day2) = explode("-", $betweendate2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year = $year + 543;
    $year2 = $year2 + 543;
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2) . " $year2 ";
  } else {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2) . " $year2 ";
  }
}




header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  { }



  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 9);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", '') . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    $this->Cell(22.5, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $loop = 0;
    $y = 75;
    $main = 1;
    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {

        $txt = getStrLenTH($inner_array[$field[1]]); // 10
        $round = $txt / 22;
        list($main, $point) = explode(".", $round);
        if ($point > 0) {
          $point = 1;
          $main += $point;
        }
        $this->Cell(22.5, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
        $pdf->SetX($w[0] + 22.5 + 10);
        $this->MultiCell($w[1], 10 / $main, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 'L');
        $pdf->SetXY($w[0] + $w[1] + 22.5 + 10, $y);
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[2]])), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]])), 1, 0, 'C');
        $this->Ln();
        $count++;
        $y += 10;
      }
    }

    // Closing line

  }
}
function getMBStrSplit($string, $split_length = 1)
{
  mb_internal_encoding('UTF-8');
  mb_regex_encoding('UTF-8');

  $split_length = ($split_length <= 0) ? 1 : $split_length;
  $mb_strlen = mb_strlen($string, 'utf-8');
  $array = array();
  $i = 0;

  while ($i < $mb_strlen) {
    $array[] = mb_substr($string, $i, $split_length);
    $i = $i + $split_length;
  }

  return $array;
}
// Get string length for Character Thai
function getStrLenTH($string)
{
  $array = getMBStrSplit($string);
  $count = 0;

  foreach ($array as $value) {
    $ascii = ord(iconv("UTF-8", "TIS-620", $value));

    if (!($ascii == 209 || ($ascii >= 212 && $ascii <= 218) || ($ascii >= 231 && $ascii <= 238))) {
      $count += 1;
    }
  }
  return $count;
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();
$pdf->AddPage("P", "A4");
$Sql = "SELECT
department.depname,
shelfcount.isStatus
FROM
shelfcount
INNER JOIN department ON department.depcode = shelfcount.DepCode
WHERE shelfcount.DocNo = '$docno'
";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $depname = $Result['depname'];
  $isStatus = $Result['isStatus'];
}
if ($isStatus == 1 || $isStatus == 0 ) {
  $Status = 'On Process';
} elseif ($isStatus == 3 || $isStatus == 4) {
  $Status = 'Complete';
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
// Move to the right
$pdf->SetFont('THSarabun', '', 10);
$image = "../images/Nhealth_linen 4.0.png";
$pdf->Image($image, 10, 10, 43, 15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 20, iconv("UTF-8", "TIS-620", $array2['r4'][$language]), 0, 0, 'C');
// Move to the right
$pdf->Ln(20);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(32);
$pdf->Cell(30, 5, iconv("UTF-8", "TIS-620", $array2['docno'][$language] . ' :    ' . $docno), 0, 1, 'C');
$pdf->Cell(22.5);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", $array2['department'][$language] . " : " . $depname), 0, 0, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", 'Status :  '.$Status), 0, 0, 'R');
$pdf->Ln(12);

$query = "SELECT
IFNULL(SUM(shelfcount_detail.Over),0) AS OverPar,
IFNULL(SUM(shelfcount_detail.Short),0) AS Short ,
item.itemName
FROM
shelfcount_detail
INNER JOIN shelfcount ON shelfcount.DocNo =  shelfcount_detail.DocNo
INNER JOIN item ON item.itemCode = shelfcount_detail.ItemCode
INNER JOIN department ON department.DepCode = shelfcount.DepCode
WHERE shelfcount.DocNo = '$docno'
GROUP BY item.itemName
";
// var_dump($query); die;
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = ",itemName,Short,OverPar";
// Table header
$header = array($array2['no'][$language], $array2['itemname'][$language], $array2['shot'][$language], $array2['over'][$language]);
// width of column table
$width = array(30, 40, 40, 40);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum
//*************** Send Email ***************//
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Shot_And_Over_item_' . $ddate . '.pdf');

// require '../../PHPMailer/PHPMailerAutoload.php';
//     // build message body
// $body = '
// ';

// $mail = new PHPMailer;
// $mail->CharSet = "UTF-8";
// $mail->isSMTP();
// $mail->SMTPDebug = 2;
// $mail->Debugoutput = 'html';
// $mail->Host = 'smtp.gmail.com';
// $mail->Port = 587;
// $mail->SMTPSecure = 'tls';
// $mail->SMTPAuth = true;
// $mail->Username = "poseinttelligence@gmail.com";
// $mail->Password = "pose6628";
// $mail->AddAttachment("Report_Shot_and_Over_item.pdf");
// $mail->setFrom('poseinttelligence@gmail.com', 'Pose Intelligence');
// $mail->addAddress('poseinttelligence@gmail.com');
// $mail->Subject = 'แจ้งเตือนเปลี่ยนราคา';
// $mail->msgHTML($body);
// $mail->AltBody = 'This is a plain-text message body';
// // $mail->send();
// if (!$mail->send()) {
// $return['msg'] = "Mailer Error: " . $mail->ErrorInfo;
// echo json_encode($return);
// die;
// } else {
// $return['msg'] = "Message sent!";
// echo json_encode($return);
// die;
// }
