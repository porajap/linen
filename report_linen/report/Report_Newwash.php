<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
// ?รับค่าจาก process
$data =explode( ',',$_GET['data']);
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
    $where =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    $where_new = "WHERE  DATE (newlinentable.DocDate) LIKE '%$date1%'";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
    $where_new = "WHERE  year (newlinentable.DocDate) LIKE '%$date1%'";

    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where = "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
  $where_new = "WHERE newlinentable.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year2 = $year2 + 543;
    $year = $year + 543;
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . " " . $array['to'][$language] . " " .
      $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
  } else {
    $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year . " " . $array['to'][$language] . " " .
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " .  $year2;
  }
} elseif ($chk == 'month') {
  $where =   "WHERE month (clean.Docdate) = " . $date1;
  $where_new = "WHERE month (newlinentable.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(clean.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_new =  "WHERE date(newlinentable.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
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


class PDF extends FPDF
{
  function Header()
  { }
  // Page footer
  function Footer()

  {

    if ($this->isFinished) {
      $this->SetY(-30);
      $xml = simplexml_load_file('../xml/general_lang.xml');
      $xml2 = simplexml_load_file('../xml/report_lang.xml');
      $json = json_encode($xml);
      $array = json_decode($json, TRUE);
      $json2 = json_encode($xml2);
      $array2 = json_decode($json2, TRUE);
      $language = $_SESSION['lang'];

    }
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 12);
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
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$Sql = "SELECT
			factory.$FacName,
			site.$HptName,
			department.DepName
FROM clean
INNER JOIN factory ON factory.FacCode = clean.FacCode
INNER JOIN department ON clean.DepCode=department.DepCode
INNER JOIN clean_detail ON clean.DocNo=clean_detail.DocNo
INNER JOIN site ON department.HptCode=site.HptCode
$where
AND clean.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
 ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $side = $Result[$HptName];
  $facname = $Result[$FacName];
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
// Title
// Line break
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r22'][$language]), 0, 1, 'C');
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(80);
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['hosname'][$language] . " : " . $side), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620",  $array['factory'][$language] . " : " . $facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);
$next_page = 1;
$r = 1;
$status = 0;
$numfield = 6;
// Field data (Must match with Query)
$field = ", ,ItemName,Totalqty,Weight,TotalPrice,DocDate";
// Table header
$p1 = $array['no'][$language];
$p2 = $array['item'][$language];
$p3 = $array['qty'][$language];
$p4 = $array['weight'][$language];
$p5 = $array['Sum'][$language];
$header = array($array['no'][$language], $array['item'][$language], $array['qty'][$language], $array['weight'][$language]);
// width of column table
$w = array(25, 90, 30, 45);
// Get Data and store in Result
// $result = $data->getdata($conn, $query, $numfield, $field);
// // Set Table
// $pdf->SetFont('THSarabun', 'b', 10);
// $pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$count = 1;
// Get $totalsum
for ($i = 0; $i < count($header); $i++)
  $pdf->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
$pdf->Ln();
$query = "SELECT
newlinentable_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
department.DepName,
sum(newlinentable_detail.Qty) AS Qty,
sum(
  newlinentable_detail.Weight
) AS Weight
FROM
item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN newlinentable_detail ON newlinentable_detail.ItemCode = item.ItemCode
INNER JOIN newlinentable ON newlinentable.DocNo = newlinentable_detail.DocNo
INNER JOIN department ON newlinentable_detail.DepCode = department.DepCode
$where_new
AND department.HptCode = '$HptCode'
AND newlinentable.FacCode = '$FacCode'
AND newlinentable.IsStatus <> 9
GROUP BY
newlinentable_detail.ItemCode";
// -- INNER JOIN item_multiple_unit ON item_multiple_unit.MpCode = clean_detail.UnitCode
// -- AND item_multiple_unit.ItemCode = clean_detail.ItemCode --
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($count > 20) {
    $next_page++;
    if ($status == 0) {
      $pdf->SetFont('THSarabun', 'b', 14);
      for ($i = 0; $i < count($header); $i++) {
        $pdf->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
      }
      $pdf->Ln();
      $y = 20;
      $status = 1;
    }
    if ($next_page % 25 == 1) {
      $pdf->AddPage("P", "A4");
      $pdf->SetFont('THSarabun', 'b', 14);
      for ($i = 0; $i < count($header); $i++)
        $pdf->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
      $pdf->Ln();
      $y = 20;
    }
  }
  $inner_array[$field[1]] = $Result['ItemName'];
  $inner_array[$field[2]] = $Result['Qty'];
  $inner_array[$field[3]] = $Result['Weight'];
  $inner_array[$field[4]] = $inner_array[$field[2]] * $inner_array[$field[3]];

  $pdf->SetFont('THSarabun', '', 14);
  $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'L');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[2]])), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]], 2)), 1, 0, 'C');
  $pdf->Ln();
  $count++;
  $totalsum += $inner_array[$field[3]];
}
// $meQueryy = mysqli_query($conn, $queryy);
// while ($Result1 = mysqli_fetch_assoc($meQueryy)) {
//   $inner_arrayy[$field[2]] = $Result1['ItemName'];
//   $inner_arrayy[$field[3]] = $Result1['UnitName'];
//   $inner_arrayy[$field[4]] = $Result1['Totalqtyy'];
//   $inner_arrayy[$field[5]] = $Result1['Price'];
//   $inner_arrayy[$field[6]] = $Result1['TotalPrice'];
//   $total = $inner_array[$field[4]] * $inner_array[$field[5]];

//   $pdf->SetFont('THSarabun', '', 14);
//   $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
//   $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_arrayy[$field[2]]), 1, 0, 'L');
//   $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_arrayy[$field[3]]), 1, 0, 'C');
//   $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_arrayy[$field[4]]), 1, 0, 'C');
//   $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_arrayy[$field[5]]), 1, 0, 'R');
//   $pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $inner_arrayy[$field[6]]), 1, 0, 'R');
//   $pdf->Ln();
//   $count++;
//   $totalsum1 += $inner_arrayy[$field[6]];
// }

//footer
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(145, 7, iconv("UTF-8", "TIS-620", $array['Sum'][$language]), 1, 0, 'C');
$pdf->Cell(45, 7, iconv("UTF-8", "TIS-620", number_format($totalsum, 2)), 1, 1, 'C');



$pdf->isFinished = true;



$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Cleaned_Linen_Weight_' . $ddate . '.pdf');
