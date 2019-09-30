<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
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
session_start();
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$format = $data['Format'];
$DepCode = $data['DepCode'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
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
    $count = 1;
    $rows = 0;
    $check = 0;
    $r = 0;
    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($r > 22) {
          $rows++;
          if ($rows % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        //เช็คข้อมูลซ้ำออกมาตัวเดียว
        for ($i = 0; $i < count($inner_array[$field[0]]); $i++) {
          if ($inner_array[$field[0]] == $date) {
            $count++;
            $this->Cell($w[0], 10, "", 1, 0, 'C');
            $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
            $loop++;
          } else {
            $count = 1;
            $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'L');
            $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
            $date = $inner_array[$field[0]];
            $loop = 0;
            $check++;
            $name[] = $inner_array[$field[0]];
          }
          $sum_loop[] = $loop;
          $sum_check[] = $check;
        }

        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]], 2)), 1, 0, 'C');
        $this->Ln();
        $totalsum += $inner_array[$field[3]];
        $totalqty += $inner_array[$field[1]];
        $r++;
      }
      //นับจำนวนของเเต่ละวันที่
      if ($rows % 25 >= 16) {
        $pdf->AddPage("P", "A4");
      }
      $count_value = array_count_values($sum_check);
      $total = array_sum($count_value);
    }
    $this->SetFont('THSarabun', 'b', 14);
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'L');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $totalqty), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($totalsum, 2)), 1, 1, 'C');
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
        INNER JOIN department ON department.depcode =dirty.depcode
        INNER JOIN site ON site.hptcode =department.hptcode
        $where
        AND  factory.FacCode = '$FacCode'
        AND  site.HptCode = '$HptCode'
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
// $image="../images/Nhealth_linen 4.0.png";
// $pdf-> Image($image,10,10,43,15);
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
SUM(dirty_detail.Qty) AS Qty
FROM
dirty
INNER JOIN department ON dirty.DepCode = department.DepCode
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN factory ON dirty.FacCode = factory.FacCode
INNER JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
GROUP BY item.ItemName,department.DepName,dirty.DocDate
ORDER BY item.ItemName , department.DepName ASC
          ";
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = "ItemName,Qty,DepName,Weight";
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
SUM(dirty_detail.Qty) AS Qty
FROM
dirty
INNER JOIN department ON dirty.DepCode = department.DepCode
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN factory ON dirty.FacCode = factory.FacCode
INNER JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
GROUP BY item.ItemName
ORDER BY item.ItemName , department.DepName ASC
          ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['ItemName'];
  $facname = $Result['Qty'];
  $pdf->SetFont('THSarabun', '', 14);
  $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", $DocDate), 1, 0, 'L');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620",  $facname), 1, 0, 'C');
  $pdf->Cell(60, 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'C');
  $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", ''), 1, 1, 'R');
}
$pdf->Ln(8);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", $array2['comlinen'][$language] . "..................................................."), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['comlaundry'][$language] . "........................................"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "......................................................................"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . ".........................................................."), 0, 0, 'L');
$pdf->Ln(7);
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Dirty_Linen_Weight' . $ddate . '.pdf');
