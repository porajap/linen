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
$format = $data['Format'];
$DepCode = $data['DepCode'];
$where = '';
$language = $_SESSION['lang'];
$DocNo = $_GET['Docno'];
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
    $where =   "WHERE DATE (rewash.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (rewash.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE rewash.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (rewash.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(rewash.Docdate) BETWEEN $date1 AND $date2";
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1)  . " " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2);
  } else {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2);
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

  function setTable($pdf, $header, $header1, $header2, $data, $width, $numfield, $field)
  {
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 12);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();
    for ($i = 0; $i < count($header1); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header1[$i]), 1, 0, 'C');
    $this->Ln();
    for ($i = 0; $i < count($header2); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header2[$i]), 1, 0, 'C');
    $this->Ln();
    $this->SetFont('THSarabun', 'b', 10);

    // if (is_array($data)) {
    //   foreach ($data as $data => $inner_array) {
    //     if ($inner_array[$field[0]] == 1) {
    // $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[8], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[9], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    // $this->Cell($w[10], 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');
    //     }
    //   }
    // }
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

// $Sql = "SELECT
//         factory.FacName,
//         DATE(rewash.DocDate) AS DocDate,
//         TIME(rewash.DocDate) AS DocTime
//         FROM
//         rewash
//         INNER JOIN factory ON rewash.FacCode = factory.FacCode
//         INNER JOIN department ON department.depcode = rewash.depcode
//         $where
//         AND rewash.FacCode = $FacCode
//         AND department.HptCode = '$HptCode'";
// $meQuery = mysqli_query($conn, $Sql);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $factory = $Result['FacName'];
// }
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
// Move to the right
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(5);
// Title
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r21'][$language]), 0, 0, 'C');
// Line break
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['docno'][$language]."  ".$DocNo), 0, 1, 'C');
$pdf->Ln(2);
$query = "SELECT
SUM(tdas_detail.Qty) AS qty,
tdas_detail.DepCode,
tdas_detail.DocNo,
tdas_detail.Type as t,
tdas_detail.TotalStock,
tdas_detail.TotalPar,
tdas_detail.Percent
FROM
tdas_detail
where tdas_detail.depcode IN(224,234,238,239)
AND tdas_detail.DocNo = '$DocNo'
GROUP BY DepCode ,Type
ORDER BY DepCode,Type";
// $meQuery = mysqli_query($conn, $Sql);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $factory = $Result['FacName'];

// // }

// // var_dump($query); die;
// // Number of column
$numfield = 11;
// Field data (Must match with Query)
$field = "t,qty";
// Table header
$header = array('ลำดับ', '', 'DEPARTMENT', 'Change', 'N-health', 'BHQ1', 'BHQ2', ' BHQ3', 'Total', 'Total', 'Total');
$header1 = array('', '', 'Cost Center', '', '', '', '', ' ', 'Ex.STOCK', 'Par', 'Par');
$header2 = array('', '', 'Name', '', '', '', '', ' ', '', '1', '3.5');
// width of column Total
$width = array(8, 7, 45, 20, 15, 15, 15, 15, 15, 15, 15);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->setTable($pdf, $header, $header1, $header2, $result, $width, $numfield, $field);

// $header2 = array('จำนวนเตียงรวม(Total Patient Room)', 'ห้องพักญาติ(Relative Room In VIP)', 'จำนวนผู้ป่วย(AVG Patient Census)', 'จำนวนผู้ป่วยกลับบ้าน(Dis charge plan)');
// for($i=0;$i<4;$i++){


// -----------------------------------------------1--------------------------------------------------
$pdf->Cell($width[0], 10, iconv("UTF-8", "TIS-620", 1), 1, 0, 'C');
$pdf->Cell($width[1], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[2], 10, iconv("UTF-8", "TIS-620", "จำนวนเตียงรวม(Total Patient Room)"), 1, 0, 'C');
$pdf->Cell($width[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $t = $Result['t'];
  $qty = $Result['qty'];
  if ($t == 1) {
    $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", number_format($qty,2)), 1, 0, 'C');
  }
}
$pdf->Cell($width[8], 10, iconv("UTF-8", "TIS-620", "1.00"), 1, 0, 'C');
$pdf->Cell($width[9], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[10], 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');

// -----------------------------------------------2--------------------------------------------------
$pdf->Cell($width[0], 10, iconv("UTF-8", "TIS-620", 2), 1, 0, 'C');
$pdf->Cell($width[1], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[2], 10, iconv("UTF-8", "TIS-620", "ห้องพักญาติ(Relative Room In VIP)"), 1, 0, 'C');
$pdf->Cell($width[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $t = $Result['t'];
  $qty = $Result['qty'];
  if ($t == 2) {
    $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620",  number_format($qty,2)), 1, 0, 'C');
  }
}
$pdf->Cell($width[8], 10, iconv("UTF-8", "TIS-620", "2.00"), 1, 0, 'C');
$pdf->Cell($width[9], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[10], 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');

// -----------------------------------------------3--------------------------------------------------
$pdf->Cell($width[0], 10, iconv("UTF-8", "TIS-620", 3), 1, 0, 'C');
$pdf->Cell($width[1], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[2], 10, iconv("UTF-8", "TIS-620", "จำนวนผู้ป่วย(AVG Patient Census)"), 1, 0, 'C');
$pdf->Cell($width[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $t = $Result['t'];
  $qty = $Result['qty'];
  if ($t == 3) {
    $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620",  number_format($qty,2)), 1, 0, 'C');
  }
}
$pdf->Cell($width[8], 10, iconv("UTF-8", "TIS-620", "3.00"), 1, 0, 'C');
$pdf->Cell($width[9], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[10], 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');

// -----------------------------------------------4--------------------------------------------------
$pdf->Cell($width[0], 10, iconv("UTF-8", "TIS-620", 4), 1, 0, 'C');
$pdf->Cell($width[1], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[2], 10, iconv("UTF-8", "TIS-620", "จำนวนผู้ป่วยกลับบ้าน(Dis charge plan)"), 1, 0, 'C');
$pdf->Cell($width[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $t = $Result['t'];
  $qty = $Result['qty'];
  if ($t == 4) {
    $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", number_format($qty,2)), 1, 0, 'C');
  }
}
$pdf->Cell($width[8], 10, iconv("UTF-8", "TIS-620", "4.00"), 1, 0, 'C');
$pdf->Cell($width[9], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[10], 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');
// }


$count=0;
$q = "SELECT
tdas_percent.Percent_value
FROM
tdas_percent
WHERE DepCode in (224,234,238,239)
";
$pdf->Cell($width[0], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[1], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[2], 10, iconv("UTF-8", "TIS-620", "Total Safty stock"), 1, 0, 'C');
$pdf->Cell($width[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$meQuery = mysqli_query($conn, $q);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Percent_value = $Result['Percent_value'];
    $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", $Percent_value."%"), 1, 0, 'C');
    $count++;
}
$c=4-$count;
for($i;$i<$c;$i++){
$pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", "0%"), 1, 0, 'C');
}
$pdf->Cell($width[8], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[9], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
$pdf->Cell($width[10], 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');
// }

$i = 1;
$query1 = "SELECT
tdas_detail_item.ItemCode,
tdas_detail_item.Change_value,
item.ItemName,
item_main_category.MainCategoryName
FROM
tdas_detail_item
INNER JOIN item on item.ItemCode = tdas_detail_item.ItemCode
INNER JOIN item_category ON item_category.categorycode = item.categorycode
INNER JOIN item_main_category ON item_category.maincategorycode = item_main_category.maincategorycode
WHERE DepCode in (224,234,238,239)
AND tdas_detail_item.DocNo = '$DocNo'
GROUP BY ItemCode
";
$meQueryy = mysqli_query($conn, $query1);
while ($Result = mysqli_fetch_assoc($meQueryy)) {
  $MainCategoryName = $Result['MainCategoryName'];
  $Change_value = $Result['Change_value'];
  $ItemName = $Result['ItemName'];
  $pdf->Cell($width[0], 10, iconv("UTF-8", "TIS-620", $i), 1, 0, 'C');
  $pdf->Cell($width[1], 10, iconv("UTF-8", "TIS-620", $MainCategoryName), 1, 0, 'C');
  $pdf->Cell($width[2], 10, iconv("UTF-8", "TIS-620", $ItemName), 1, 0, 'C');
  $pdf->Cell($width[3], 10, iconv("UTF-8", "TIS-620", $Change_value), 1, 1, 'C');
  $i++;
}
$query2 = "SELECT
 sum(tdas_detail_item.Result) as  r
 FROM
 tdas_detail_item
 WHERE DepCode in (224,234,238,239)
 AND DocNo = '$DocNo'
 GROUP BY ItemCode,DepCode
 ORDER BY ItemCode ASC
";
$x = 90;
$y = 114;
$count = 1;
$meQueryyy = mysqli_query($conn, $query2);
while ($Result = mysqli_fetch_assoc($meQueryyy)) {
  $r = $Result['r'];
  $pdf->SetXY($x, $y);
  $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", number_format($r,2)), 1, 0, 'C');
  $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
  $x = $x + 15;
  if ($count == 4) {
    $y =$y + 10;
    $x = 90;
  }
  $count++;
}



$query3 = " SELECT DISTINCT
tdas_detail_item.SumResult,
tdas_detail_item.CalSum
FROM
tdas_detail_item
WHERE DocNo = '$DocNo'
ORDER BY ItemCode

";
$x = 165;
$y = 114;
$count = 1;
$pdf->SetXY($x, $y);
$meQuery = mysqli_query($conn, $query3);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $SumResult = $Result['SumResult'];
  $CalSum = $Result['CalSum'];
  $y+=10;
  $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", number_format($SumResult,2)), 1, 0, 'C');
  $pdf->Cell($width[4], 10, iconv("UTF-8", "TIS-620", number_format($CalSum,2)), 1, 1, 'C');
  $pdf->SetXY($x, $y);
}








$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Rewash_' . $ddate . '.pdf');
