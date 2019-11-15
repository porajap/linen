<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$docno = $_GET['Docno'];
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$depcode = $data['DepCode'];
$format = $data['Format'];
$cycle = $data['cycle'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
$where = '';
$language = $_SESSION['lang'];
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

    if ($this->isFinished) {
      $this->SetFont('THSarabun', '', 10);
      $this->SetY(-45);
      $xml = simplexml_load_file('../xml/general_lang.xml');
      $xml2 = simplexml_load_file('../xml/report_lang.xml');
      $json = json_encode($xml);
      $array = json_decode($json, TRUE);
      $json2 = json_encode($xml2);
      $array2 = json_decode($json2, TRUE);
      $language = $_SESSION['lang'];
      $this->SetFont('THSarabun', 'b', 11);
      $this->Cell(5);
      $this->Cell(110, 10, iconv("UTF-8", "TIS-620", $array2['sign'][$language] . "..................................................." . $array2['packing'][$language]), 0, 0, 'L');
      $this->Cell(35, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "........................................" . $array2['time'][$language] . "............................."), 0, 0, 'L');
      $this->Ln(7);
      $this->Cell(5);
      $this->Cell(110, 10, iconv("UTF-8", "TIS-620", $array2['sign'][$language] . "..................................................." . $array2['passenger'][$language]), 0, 0, 'L');
      $this->Cell(35, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "........................................" . $array2['time'][$language] . "............................."), 0, 0, 'L');
      $this->Ln(7);
      $this->Cell(5);
      $this->Cell(110, 10, iconv("UTF-8", "TIS-620", $array2['sign'][$language] . "..................................................." . $array2['receiver'][$language]), 0, 0, 'L');
      $this->Cell(35, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "........................................" . $array2['time'][$language] . "............................."), 0, 0, 'L');
      $this->Ln(10);
      $this->Cell(5);
      $image1 = "../images/chb.jpg";
      $this->Image($image1, $this->GetX(), $this->GetY(), 5);
      $this->Cell(7);
      $this->Cell(30, 5, iconv("UTF-8", "TIS-620", "Check"), 0, 0, 'L');
      $image2 = "../images/chb.jpg";
      $this->Image($image2, $this->GetX(), $this->GetY(), 5);
      $this->Cell(7);
      $this->Cell(40, 5, iconv("UTF-8", "TIS-620", "Not Check"), 0, 0, 'L');
      $this->Ln(7);
    }
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 9);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", '') . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();
// Using Coding
$pdf->AddPage("P", "A4");


$Sql = "SELECT
shelfcount.DocNo,
DATE(shelfcount.DocDate) AS DocDate,
TIME(shelfcount.DocDate) AS DocTime,
department.DepName,
time_sc.TimeName AS CycleTime,
site.HptName,
sc_time_2.TimeName AS TIME , 
time_sc.timename AS ENDTIME ,
site.HptCode
FROM
shelfcount
LEFT JOIN department ON shelfcount.DepCode = department.DepCode
LEFT JOIN site ON site.HptCode = department.HptCode
LEFT JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
LEFT JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
WHERE shelfcount.DocNo='$docno'
        AND shelfcount.isStatus<> 9
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $TIME = $Result['TIME'];
  $ENDTIME = $Result['ENDTIME'];
  $HptName = $Result['HptName'];
 $HptCode = $Result['HptCode'];
}
//---------------------------------------------------------------------
$queryy = "SELECT
site.private,
site.government
FROM
site
WHERE site.HptCode = '$HptCode' ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $private = $Result['private'];
  $government = $Result['government'];
}
if ($private == 1) {
  $w = array(10, 40, 20, 20, 20, 20, 20, 15, 15, 15);
} elseif ($government == 1) {
  $w = array(10, 40, 20, 20, 20, 20, 20, 22.5, 22.5);
}

if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
list($year, $month, $day) = explode('-', $DocDate);
if ($language == 'th') {
  $year = $year + 543;
}
$DocDate = $day . "-" . $month . "-" . $year;
$image = "../images/Nhealth_linen 4.0.png";
$pdf->Image($image, 10, 10, 43, 15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r4'][$language]), 0, 1, 'C');
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['docno'][$language] . " : " . $docno), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['hospital'][$language] . " : " . $HptName), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['ward'][$language] . " : " . $DeptName), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['date'][$language] . " : " . $DocDate), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['shelfcounttime'][$language] . " : " . $TIME), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['deliverytime'][$language] . " : " . $ENDTIME), 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('THSarabun', '', 14);

$pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['no'][$language]), 1, 0, 'C');
$pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['itemname'][$language]), 1, 0, 'C');
$pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['parqty'][$language]), 1, 0, 'C');
$pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['shelfcount1'][$language]), 1, 0, 'C');
$pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['max'][$language]), 1, 0, 'C');
$pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $array2['issue'][$language]), 1, 0, 'C');
$pdf->Cell($w[6], 10, iconv("UTF-8", "TIS-620", $array2['short'][$language]), 1, 0, 'C');
$pdf->Cell($w[7], 10, iconv("UTF-8", "TIS-620", $array2['over'][$language]), 1, 0, 'C');
$pdf->Cell($w[8], 10, iconv("UTF-8", "TIS-620", $array2['weight'][$language]), 1, 0, 'C');

if ($private == 1) {
  $pdf->Cell($w[9], 10, iconv("UTF-8", "TIS-620", $array2['price'][$language]), 1, 0, 'C');
}
$pdf->ln();
$query = "SELECT
item.ItemName,
item.weight,
IFNULL(shelfcount_detail.ParQty, 0) AS ParQty,
IFNULL(shelfcount_detail.CcQty, 0) AS CcQty,
IFNULL(
  shelfcount_detail.TotalQty,
  0
) AS TotalQty,
IFNULL(shelfcount_detail.Over, 0) AS OverPar,
IFNULL(shelfcount_detail.Short, 0) AS Short,
IFNULL(item.Weight, 0) AS Weight,
category_price.Price
FROM
shelfcount
INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
INNER JOIN department ON shelfcount.DepCode = department.DepCode
          WHERE shelfcount.DocNo='$docno'
          AND shelfcount_detail.TotalQty <> 0
            AND shelfcount.isStatus<> 9";
$meQuery = mysqli_query($conn, $query);
$i = 1;
$y = 95;
$r = 1;
$main = 1;
$status = 0;
$next_page = 1;
$rows = 26 ;
$pdf->SetFont('THSarabun', '', 14);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($r > 16) {
    $next_page++;
    if ($status == 0) {
      $pdf->SetFont('THSarabun', 'b', 14);
      $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['no'][$language]), 1, 0, 'C');
      $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['itemname'][$language]), 1, 0, 'C');
      $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['parqty'][$language]), 1, 0, 'C');
      $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['shelfcount1'][$language]), 1, 0, 'C');
      $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['max'][$language]), 1, 0, 'C');
      $pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $array2['issue'][$language]), 1, 0, 'C');
      $pdf->Cell($w[6], 10, iconv("UTF-8", "TIS-620", $array2['short'][$language]), 1, 0, 'C');
      $pdf->Cell($w[7], 10, iconv("UTF-8", "TIS-620", $array2['over'][$language]), 1, 0, 'C');
      $pdf->Cell($w[8], 10, iconv("UTF-8", "TIS-620", $array2['weight'][$language]), 1, 0, 'C');
      if ($private == 1) {
        $pdf->Cell($w[9], 10, iconv("UTF-8", "TIS-620", $array2['price'][$language]), 1, 0, 'C');
      }
      $pdf->Ln();
      $y = 20;
      $status = 1;
    }
    if ($next_page % $rows== 1) {
      $pdf->AddPage("P", "A4");
      $pdf->SetFont('THSarabun', 'b', 14);
      $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['no'][$language]), 1, 0, 'C');
      $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['itemname'][$language]), 1, 0, 'C');
      $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['parqty'][$language]), 1, 0, 'C');
      $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['shelfcount1'][$language]), 1, 0, 'C');
      $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['max'][$language]), 1, 0, 'C');
      $pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $array2['issue'][$language]), 1, 0, 'C');
      $pdf->Cell($w[6], 10, iconv("UTF-8", "TIS-620", $array2['short'][$language]), 1, 0, 'C');
      $pdf->Cell($w[7], 10, iconv("UTF-8", "TIS-620", $array2['over'][$language]), 1, 0, 'C');
      $pdf->Cell($w[8], 10, iconv("UTF-8", "TIS-620", $array2['weight'][$language]), 1, 0, 'C');
      if ($private == 1) {
        $pdf->Cell($w[9], 10, iconv("UTF-8", "TIS-620", $array2['price'][$language]), 1, 0, 'C');
      }
      $pdf->Ln();
      $y = 20;
      $rows = 25;
    }
  }
  $txt = getStrLenTH($Result['ItemName']); // 10
  $round = $txt / 20;
  list($main, $point) = explode(".", $round);
  if ($point > 0) {
    $point = 1;
    $main += $point;
  }
  $issue = $Result['ParQty'] - $Result['CcQty'];
  $totalweight = $Result['TotalQty'] * $Result['Weight'];
  $price = $totalweight * $Result['Price'];
  $pdf->SetFont('THSarabun', '', 14);
  $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'C');
  $pdf->SetX($w[0] + 10);
  $pdf->MultiCell($w[1], 10 / $main, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 'L');
  $pdf->SetXY($w[0] + $w[1]  + 10, $y);
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($Result['ParQty'])), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($Result['CcQty'])), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($issue)), 1, 0, 'C');
  $pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($Result['TotalQty'])), 1, 0, 'C');
  $pdf->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($Result['Short'])), 1, 0, 'C');
  $pdf->Cell($w[7], 10, iconv("UTF-8", "TIS-620", number_format($Result['OverPar'])), 1, 0, 'C');
  $pdf->Cell($w[8], 10, iconv("UTF-8", "TIS-620", number_format($totalweight)), 1, 0, 'C');
  if ($private == 1) {
    $pdf->Cell($w[9], 10, iconv("UTF-8", "TIS-620", $price), 1, 0, 'C');
  }
  $pdf->ln();
  $i++;
  $totalw += $totalweight;
  $price_total += $price;
  $y += 10;
  $r++;
}
$pdf->Cell(150, 7, iconv("UTF-8", "TIS-620", $array2['total_weight'][$language]), 1, 0, 'C');
$pdf->Cell($w[8] + $w[9], 7, iconv("UTF-8", "TIS-620", $totalw), 1, 0, 'C');
$pdf->Cell($w[8], 7, iconv("UTF-8", "TIS-620", $array2['kg'][$language]), 1, 0, 'C');
$pdf->ln();
if ($private == 1) {
  $pdf->Cell(150, 7, iconv("UTF-8", "TIS-620", $array2['total_price'][$language]), 1, 0, 'C');
  $pdf->Cell($w[8] + $w[9], 7, iconv("UTF-8", "TIS-620", $price_total), 1, 0, 'C');
  $pdf->Cell($w[8], 7, iconv("UTF-8", "TIS-620", $array2['bath'][$language]), 1, 0, 'C');
}
        /*หน้าใหม่*/
        // if ($next_page % 25 >=21) {
        //   $pdf->AddPage("P", "A4");
        // }

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
// CHECK FOOTER NEXT PAGE

$pdf->isFinished = true;

// Footer Table
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Daily_Issue_Request_' . $ddate . '.pdf');
