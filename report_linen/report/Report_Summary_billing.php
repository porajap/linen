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
$year = $data['year'];
$DepCode = $data['DepCode'];
$format = $data['Format'];
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
  $where =   "WHERE date(shelfcount.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
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


function m2t($number)
{
  $number = number_format($number, 2, '.', '');
  $numberx = $number;
  $txtnum1 = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
  $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
  $number = str_replace(",", "", $number);
  $number = str_replace(" ", "", $number);
  $number = str_replace("บาท", "", $number);
  $number = explode(".", $number);
  if (sizeof($number) > 2) {
    return 'ทศนิยมหลายตัวนะจ๊ะ';
    exit;
  }
  $strlen = strlen($number[0]);
  $convert = '';
  for ($i = 0; $i < $strlen; $i++) {
    $n = substr($number[0], $i, 1);
    if ($n != 0) {
      if ($i == ($strlen - 1) and $n == 1) {
        $convert .= 'เอ็ด';
      } elseif ($i == ($strlen - 2) and $n == 2) {
        $convert .= 'ยี่';
      } elseif ($i == ($strlen - 2) and $n == 1) {
        $convert .= '';
      } else {
        $convert .= $txtnum1[$n];
      }
      $convert .= $txtnum2[$strlen - $i - 1];
    }
  }

  $convert .= 'บาท';
  if (
    $number[1] == '0' or $number[1] == '00' or
    $number[1] == ''
  ) {
    $convert .= 'ถ้วน';
  } else {
    $strlen = strlen($number[1]);
    for ($i = 0; $i < $strlen; $i++) {
      $n = substr($number[1], $i, 1);
      if ($n != 0) {
        if ($i == ($strlen - 1) and $n == 1) {
          $convert
            .= 'เอ็ด';
        } elseif (
          $i == ($strlen - 2) and
          $n == 2
        ) {
          $convert .= 'ยี่';
        } elseif (
          $i == ($strlen - 2) and
          $n == 1
        ) {
          $convert .= '';
        } else {
          $convert .= $txtnum1[$n];
        }
        $convert .= $txtnum2[$strlen - $i - 1];
      }
    }
    $convert .= 'สตางค์';
  }
  //แก้ต่ำกว่า 1 บาท ให้แสดงคำว่าศูนย์ แก้ ศูนย์บาท
  if ($numberx < 1) {
    $convert = "ศูนย์" .  $convert;
  }

  //แก้เอ็ดสตางค์
  $len = strlen($numberx);
  $lendot1 = $len - 2;
  $lendot2 = $len - 1;
  if (($numberx[$lendot1] == 0) && ($numberx[$lendot2] == 1)) {
    $convert = substr($convert, 0, -10);
    $convert = $convert . "หนึ่งสตางค์";
  }

  //แก้เอ็ดบาท สำหรับค่า 1-1.99
  if ($numberx >= 1) {
    if ($numberx < 2) {
      $convert = substr($convert, 4);
      $convert = "หนึ่ง" .  $convert;
    }
  }
  return $convert;
}
class PDF extends FPDF
{
  function Header()
  {
    $datetime = new DatetimeTH();
    $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
  }
  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
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
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 1;
    $rows = 1;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 22) {
          $count++;
          if ($count % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $pdf->SetFont('THSarabun', '', 12);
        $total = $inner_array[$field[3]] * $inner_array[$field[4]];
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'L');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $total), 1, 0, 'C');
        $this->Ln();
        $total_all += $total;
        $count++;
      }
    }
    $total_all = $total_all * 100 / 100;
    if ($language == 'th') {
      $text = m2t($total_all);
    } else {
      $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
      $text = $f->format($total_all);
      $text = $text . " bath";
    }
    $this->Cell($w[0] + $w[1], 10, iconv("UTF-8", "TIS-620",  $text), 1, 0, 'C');
    $this->Cell($w[2] + $w[3] + $w[4], 10, iconv("UTF-8", "TIS-620", $array2['total_price'][$language]), 1, 0, 'C');
    $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $total_all), 1, 0, 'C');
    $this->Ln();
    // Footer Table

    $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", $array2['nhealth'][$language]), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", $array2['nhealth'][$language]), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 0, iconv("UTF-8", "TIS-620", $array2['examiner'][$language]), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 0, iconv("UTF-8", "TIS-620", $array2['examiner'][$language]), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "   .............................................................................................."), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 5, iconv("UTF-8", "TIS-620", "(                                                                       )"), 0, 0, 'C');
    $pdf->Cell(170, 5, iconv("UTF-8", "TIS-620", "(                                                                       )"), 0, 1, 'C');
    $pdf->Cell(78, 5, iconv("UTF-8", "TIS-620", $array2['foremanlinen'][$language]), 0, 0, 'C');
    $pdf->Cell(120, 5, iconv("UTF-8", "TIS-620", $array2['foremanlinen'][$language]), 0, 1, 'C');
    $pdf->ln(10);
    if ($count % 25 >= 22) {
      $pdf->AddPage("P", "A4");
    }
    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
  }


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
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();
$pdf->AddPage("P", "A4");
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$Sql = "SELECT
site. $HptName
FROM
site
WHERE site.HptCode='$HptCode'
 ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Hpt = $Result[$HptName];
}
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$pdf->SetFont('THSarabun', '', 10);
$image="../images/Nhealth_linen 4.0.png";
$pdf-> Image($image,10,10,43,15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620",  $array2['r20'][$language] . " " . $date_header), 0, 0, 'C');
$pdf->ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['site'][$language] ." : ".$Hpt), 0, 0, 'C');
$pdf->ln(12);


$query = "SELECT
item.ItemName,
item_unit.UnitName,
SUM(shelfcount_detail.TotalQty) AS TotalQty,
category_price.Price ,
SUM(shelfcount_detail.TotalQty*category_price.Price) AS Total
FROM
shelfcount
RIGHT JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
INNER JOIN item_unit ON item_unit.UnitCode = item.UnitCode
INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
INNER JOIN department ON department.DepCode = shelfcount.DepCode
INNER JOIN site ON site.HptCode = department.HptCode
$where
AND site.HptCode = '$HptCode'
AND category_price.HptCode = '$HptCode'
GROUP BY item.ItemName ASC
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = ",ItemName,UnitName,TotalQty,Price,Total";
// Table header
$header = array($array2['no'][$language], $array2['itemname'][$language], $array2['unit'][$language], $array2['amount'][$language], $array2['priceperunit'][$language], $array2['total_price'][$language]);
// width of column table
$width = array(28, 50, 28, 28, 28, 28);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();






$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
