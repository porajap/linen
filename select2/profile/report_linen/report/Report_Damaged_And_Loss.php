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
$depcode = $data['DepCode'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
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
    $where_damage =   " DATE (damage.Docdate) = DATE('$date1')";
    $where_repair =   " DATE (repair.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where_damage = "  year (damage.DocDate) LIKE '%$date1%'";
    $where_repair = "  year (repair.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where_damage =   " damage.Docdate BETWEEN '$date1' AND '$date2'";
  $where_repair =   " repair.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where_damage =   " month (damage.Docdate) = " . $date1;
  $where_repair =   " month (repair.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where_damage =   " date(damage.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_repair =   " date(repair.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    $total1 = 0;
    $total2 = 0;
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
    $rows = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        //เช็คค่า=1 ให้เริ่มหัวข้อ
        if ($count > 22) {
          $rows++;
          if ($rows % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        //table data
        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'L');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $total1 += $inner_array[$field[2]];
        $total2 += $inner_array[$field[3]];
        $this->Ln();
        $count++;
      }
    }
    $this->Cell($w[0] + $w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  $total1), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $total2), 1, 0, 'C');
    //footer


    // Closing line
    $pdf->Ln(20);
    $pdf->SetFont('THSarabun', 'b', 11);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", $array2['comlinen'][$language]."..................................................."), 0, 0, 'L');
    $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['comlaundry'][$language]."........................................"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language]."......................................................................"), 0, 0, 'L');
    $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language].".........................................................."), 0, 0, 'L');
    $pdf->Ln(7);
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
site.$HptName,
department.DepName
FROM damage
INNER JOIN damage_detail ON damage.DocNo =damage_detail.DocNo
INNER JOIN department ON damage.DepCode=department.DepCode
INNER JOIN site ON department.HptCode=site.HptCode
where site.HptCode = '$HptCode' ";

$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $side = $Result[$HptName];
  $DepName = $Result['DepName'];
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

// Title
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r17'][$language]), 0, 0, 'C');
// Line break
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['site'][$language] . " : " . $side), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "  " . $facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Cell(1);


$pdf->Ln(12);

$query = "SELECT
COALESCE(repair_detail.Qty,'-') AS a,
COALESCE(damage_detail.Qty,'-') AS b,
CASE 
WHEN repair_detail.ItemCode IS NULL THEN (SELECT item.ItemName FROM item WHERE item.ItemCode = damage_detail.ItemCode )
WHEN damage_detail.ItemCode IS NULL THEN (SELECT item.ItemName FROM item WHERE item.ItemCode = repair_detail.ItemCode )
WHEN damage_detail.ItemCode =  repair_detail.ItemCode THEN (SELECT item.ItemName FROM item WHERE item.ItemCode = repair_detail.ItemCode OR  item.ItemCode = damage_detail.ItemCode AND $where_damage AND $where_repair )
END AS ItemName
FROM
item
RIGHT JOIN repair_detail ON item.ItemCode = repair_detail.ItemCode
RIGHT JOIN damage_detail ON item.ItemCode = damage_detail.ItemCode
LEFT JOIN  repair ON repair.DocNo = repair_detail.DocNo  
LEFT JOIN  damage ON damage.DocNo = repair_detail.DocNo
ORDER BY item.ItemName
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = ",ItemName,b,a,TOTAL";
// Table header
$p1 = $array2['no'][$language];
$p2 = $array2['itemname'][$language];
$p3 = $array2['damaged'][$language];
$p4 = $array2['loss'][$language];

$header = array($p1, $p2, $p3, $p4);
// width of column table
$width = array(20, 70, 50, 50);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_damageed_Linen_Weight_' . $ddate . '.pdf');
