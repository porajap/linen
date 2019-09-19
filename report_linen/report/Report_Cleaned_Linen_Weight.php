<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
header('Content-type: text/html; charset=utf-8');

session_start();
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$depcode = $data['DepCode'];
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
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . $array['to'][$language] .
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
  $where =   "WHERE month(clean.Docdate) BETWEEN $date1 AND $date2";
  $where_new =  "WHERE month(newlinentable.Docdate) BETWEEN $date1 AND $date2";
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1)  . " " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2);
  } else {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2);
  }
}


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
}
function setmuntiTable($pdf, $header, $data, $width, $numfield, $field)
{
  $field = explode(",", $field);
  // Column widths
  $w = $width;
  // Header
  $this->SetFont('THSarabun', 'b', 14);
  for ($i = 0; $i < count($header); $i++)
    $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
  $this->Ln();
  // set Data Details
  $total = 0;
  $this->SetFont('THSarabun', '', 12);
  if (is_array($data)) {
    foreach ($data as $data => $inner_array) {
      $total = $inner_array[$field[3]] * $inner_array[$field[4]];
      $this->SetFont('THSarabun', '', 14);
      $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", " "), 1, 0, 'C');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'L');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
      $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[5]]), 1, 0, 'R');
      $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $total), 1, 0, 'C');
      $this->Ln();
      $count++;
      $totalsum += $inner_array[$field[6]];
    }
  }
  // $field = ", ,ItemName,Totalqty,Weight,TotalPrice,DocDate";
  //footer
  $this->SetFont('THSarabun', 'b', 14);
  $this->Cell(160, 7, iconv("UTF-8", "TIS-620", "รวม"), 1, 0, 'C');
  $this->Cell(30, 7, iconv("UTF-8", "TIS-620", $totalsum), 1, 1, 'R');

  // Closing line

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
			factory.FacName,
			site.HptName,
			department.DepName
FROM clean
INNER JOIN dirty ON dirty.DocNo =clean.RefDocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
INNER JOIN department ON clean.DepCode=department.DepCode
INNER JOIN clean_detail ON clean.DocNo=clean_detail.DocNo
INNER JOIN site ON department.HptCode=site.HptCode
$where
AND dirty.FacCode = $FacCode
AND department.HptCode = '$HptCode'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $side = $Result['HptName'];
  $facname = $Result['FacName'];
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
// Move to the right
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language]  . $printdate), 0, 0, 'R');
$pdf->Ln(5);
// Title
// Line break
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r3'][$language]), 0, 1, 'C');
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(80);
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['hosname'][$language] . " : " . $side), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620",  $array['factory'][$language] . " : " . $facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);

$query = " SELECT
item.ItemName,
item_unit.UnitName,
SUM(clean_detail.Qty) AS Totalqty,
SUM(clean_detail.Weight) AS Weight,
-- ((SUM(clean_detail.Qty) * category_price.Price )*item_multiple_unit.PriceUnit) as TotalPrice ,
(category_price.Price *item_multiple_unit.PriceUnit) AS Price
FROM
clean_detail
LEFT JOIN clean ON clean.DocNo = clean_detail.DocNo
LEFT JOIN dirty ON dirty.DocNo = clean.RefDocNo
LEFT JOIN rewash ON clean.RefDocNo = rewash.DocNo
LEFT JOIN newlinentable ON clean.RefDocNo = newlinentable.DocNo
LEFT JOIN newlinentable_detail ON newlinentable_detail.DocNo = newlinentable.DocNo
LEFT JOIN factory ON factory.FacCode = dirty.FacCode = newlinentable.FacCode
INNER JOIN department ON clean.DepCode = department.DepCode
INNER JOIN item ON item.ItemCode = clean_detail.ItemCode
INNER JOIN category_price ON item.CategoryCode = category_price.CategoryCode
INNER JOIN item_unit ON clean_detail.UnitCode = item_unit.UnitCode
INNER JOIN item_multiple_unit ON item_multiple_unit.MpCode = clean_detail.UnitCode
AND item_multiple_unit.ItemCode = clean_detail.ItemCode
$where
AND clean.RefDocNo <> (
  SELECT
    clean.RefDocNo
  FROM
    clean
  INNER JOIN rewash ON rewash.Docno = clean.RefDocNo
)
AND category_price.HptCode = '$HptCode'
AND department.HptCode = '$HptCode'
AND (
dirty.FacCode = '$FacCode'
OR newlinentable.FacCode = '$FacCode'
)
GROUP BY
clean_detail.ItemCode";

// $queryy = "SELECT item.ItemName,
// item_unit.UnitName,
// SUM(clean_detail.Qty) AS Totalqtyy,
// ((SUM(clean_detail.Qty) * category_price.Price )*item_multiple_unit.PriceUnit) as TotalPrice ,
// (category_price.Price *item_multiple_unit.PriceUnit) AS Price
// FROM newlinentable_detail 
// INNER JOIN newlinentable ON newlinentable.DocNo = newlinentable_detail.DocNo
// INNER JOIN clean ON newlinentable.DocNo = clean.RefDocNo
// INNER JOIN clean_detail ON clean_detail.Docno=clean.Docno
// INNER JOIN department ON clean.DepCode=department.DepCode
// INNER JOIN factory ON factory.FacCode =newlinentable.FacCode
// INNER JOIN item ON item.ItemCode = clean_detail.ItemCode
// INNER JOIN category_price ON item.CategoryCode  = category_price.CategoryCode
// INNER JOIN item_unit ON clean_detail.UnitCode = item_unit.UnitCode
// INNER JOIN item_multiple_unit  ON item_multiple_unit.MpCode = clean_detail.UnitCode AND item_multiple_unit.ItemCode = clean_detail.ItemCode
// $where
// AND category_price.HptCode= '$HptCode'  
// AND newlinentable.FacCode = $FacCode
// AND department.HptCode = '$HptCode'
// GROUP BY newlinentable_detail.ItemCode
//                 ";

// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = ", ,ItemName,Totalqty,Weight,TotalPrice,DocDate";
// Table header
$p1 = $array['no'][$language];
$p2 = $array['item'][$language];
$p3 = $array['qty'][$language];
$p4 = $array['weight'][$language];
$p5 = $array['Sum'][$language];
$header = array($p1, $p2, $p3, $p4, $p5);
// width of column table
$w = array(25, 70, 25, 35, 35);
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


$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $inner_array[$field[1]] = $Result['ItemName'];
  $inner_array[$field[2]] = $Result['Totalqty'];
  $inner_array[$field[3]] = $Result['Weight'];
  $inner_array[$field[4]] = $inner_array[$field[2]] * $inner_array[$field[3]];
  $pdf->SetFont('THSarabun', '', 14);
  $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",number_format($inner_array[$field[4]], 2)), 1, 0, 'C');
  $pdf->Ln();
  $count++;
  $totalsum += $inner_array[$field[4]];
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

$total = $totalsum1 + $totalsum;
//footer
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(155, 7, iconv("UTF-8", "TIS-620", $array['Sum'][$language]), 1, 0, 'C');
$pdf->Cell(35, 7, iconv("UTF-8", "TIS-620", number_format($totalsum, 2)), 1, 1, 'C');






$pdf->Ln(8);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่ห้องผ้า..................................................."), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่โรงซัก........................................"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "วันที่......................................................................"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่.........................................................."), 0, 0, 'L');
$pdf->Ln(7);
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Cleaned_Linen_Weight_' . $ddate . '.pdf');
