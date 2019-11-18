<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
session_start();
header('Content-Type: text/html; charset=utf-8');
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
date_default_timezone_set("Asia/Bangkok");
// $data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2, 'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];

$data = explode(',', $_GET['data']);
// echo "<pre>";
// print_r($data);
// echo "</pre>"; 
$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
$DepCode = $data[7];
$chk = $data[8];
$year = $data['year'];



$where = '';
$language = $_SESSION['lang'];
//print_r($data);
if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
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
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " . $year2;
  }
} elseif ($chk == 'month') {
  $where =   "WHERE month (dirty.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE DATE(dirty.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    $check = 0;
    $y = 57;
    $date = '';
    $next_page = 1;
    $fisrt_page = 0;
    $r = 1;
    $status = 0;
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
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    $this->Ln(1);
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();
    // set Data Details

    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($inner_array[$field[4]] <> null) {
          $inner_array[$field[0]] = $inner_array[$field[4]];
        }
        if ($r > 21) {
          $next_page++;
          if ($status == 0) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++) {
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            }
            $this->Ln();
            $y = 20;
            $status = 1;
          }
          if ($next_page % 25 == 1) {
            $pdf->AddPage("P", "A4");
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
            $y = 20;
          }
        }
        /*หน้าใหม่*/
        // if ($next_page % 25 >=16) {
        //   $pdf->AddPage("P", "A4");
        // }


        $this->SetFont('THSarabun', '', 13);
        $txt = getStrLenTH($inner_array[$field[2]]); // 10
        $round = $txt / 37;
        list($main, $point) = explode(".", $round);
        if ($point > 0) {
          $point = 1;
          $main += $point;
        }
        //เช็คข้อมูลซ้ำออกมาตัวเดียว
        for ($i = 0; $i < count($inner_array[$field[0]]); $i++) {
          if ($inner_array[$field[0]] == $date) {
            $count++;
            $this->Cell($w[0], 10, "", 1, 0, 'C');
            $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[1]])), 1, 0, 'C');
          } else {
            $count = 1;
            $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'L');
            $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($inner_array[$field[1]])), 1, 0, 'C');
            $date = $inner_array[$field[0]];
            $check++;
            $name[] = $inner_array[$field[0]];
          }
          $sum_check[] = $check;
        }
        $sumcount[] = $count;

        $this->SetX($w[0] + $w[1] + 10);
        $this->MultiCell($w[2], 10 / $main, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 'C');
        $this->SetXY($w[0] + $w[1] + $w[2] + 10, $y);
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]], 2)), 1, 0, 'C');
        $this->Ln();
        $totalsum += $inner_array[$field[3]];
        $totalqty += $inner_array[$field[1]];
        $y += 10;
        $this->isFinished = false;
        $r++;
      }
    }
    // $count_value = array_count_values($sum_check);
    //   $total = array_sum($count_value);
    $this->SetFont('THSarabun', 'b', 14);
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'L');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($totalqty)), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($totalsum, 2)), 1, 1, 'C');
  }
}
// Convert a string to an array with multibyte string
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
// Using Coding
$pdf->AddPage("P", "A4");

if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$Sql = "SELECT
        factory.$FacName,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
        INNER JOIN department ON dirty_detail.DepCode = department.DepCode
        INNER JOIN site ON site.hptcode =department.hptcode
        $where
        AND  factory.FacCode = '$FacCode'
        AND  site.HptCode = '$HptCode'
        AND dirty.isStatus <> 9
        GROUP BY factory.$FacName
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $facname = $Result[$FacName];
}
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}

$pdf->SetFont('THSarabun', '', 10);
$image = "../images/Nhealth_linen 4.0.png";
$pdf->Image($image, 10, 10, 43, 15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['r1'][$language]), 0, 0, 'C');
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Ln(10);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(120, 5, iconv("UTF-8", "TIS-620",  $array2['factory'][$language] . " : " . $facname), 0, 0, 'L');
$pdf->Cell(ุ60, 5, iconv("UTF-8", "TIS-620", $date_header), 0, 1, 'R');
$pdf->Ln(3);

$query = "SELECT
item.ItemName,
dirty_detail.Weight,
department.DepName,
SUM(dirty_detail.Qty) AS Qty,
dirty_detail.RequestName
FROM
dirty
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
LEFT  JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND dirty.isStatus <> 9
AND dirty.isStatus <> 0
GROUP BY item.ItemName,department.DepName,date(dirty.DocDate),dirty_detail.RequestName
ORDER BY item.ItemName , department.DepName ASC";
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = "ItemName,Qty,DepName,Weight,RequestName";
// Table header
$header = array($array2['itemname'][$language], $array2['amount'][$language], $array2['department1'][$language], $array2['weight_kg'][$language]);
// width of column table
$width = array(70, 25, 60, 35);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$queryy = "SELECT
item.ItemName,
SUM(dirty_detail.Qty) AS Qty,
dirty_detail.RequestName
FROM
dirty
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
LEFT  JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND dirty.isStatus <> 9
GROUP BY item.ItemName,dirty_detail.RequestName
ORDER BY item.ItemName , department.DepName ASC
          ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result['ItemName'] = $Result['RequestName'];
  }
  $ItemName = $Result['ItemName'];
  $Qty = $Result['Qty'];
  $pdf->SetFont('THSarabun', '', 14);
  $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", $ItemName), 1, 0, 'L');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620",  number_format($Qty)), 1, 0, 'C');
  $pdf->Cell(60, 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'C');
  $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", ''), 1, 1, 'R');
}

$pdf->isFinished = true;
// $pdf->Ln(7);
$ddate = date('d_m_Y');
$pdf->Output('Report_Dirty_Linen_Weight' . $ddate . '.pdf', 'I');
