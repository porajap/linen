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
$where = '';
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
    $where = "WHERE  year (dirty.Docdate) LIKE '%$date1%'";
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
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . $year2;
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
  $where =   "WHERE month(dirty.Docdate) BETWEEN $date1 AND $date2";
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
    $this->SetFont('THSarabun', 'b', 12);

    $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
    $this->Cell($w[5], 20, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
    $this->Ln();

    $this->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    for ($i = 0; $i < 4; $i++) {
      $this->Cell(17.5, -10, iconv("UTF-8", "TIS-620", $array2['start'][$language]), 1, 0, 'C');
      $this->Cell(17.5, -10, iconv("UTF-8", "TIS-620", $array2['finish'][$language]), 1, 0, 'C');
    }
    $this->Ln(0);
    // set Data Details
    $rows = 1;
    $loop = 0;
    $totalsum1 = 0;
    $totalsum2 = 0;

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if($inner_array[$field[1]] == null){
          $inner_array[$field[1]] = $inner_array[$field[10]];
        }if($inner_array[$field[1]] == null && $inner_array[$field[10]] == NULL ){
          $inner_array[$field[1]] = $inner_array[$field[11]];
        }
        if ($language == 'th') {
          $hour_show = " ชั่วโมง";
          $min_show = " นาที";
        } else {
          if ($total_hours <= 1) {
            $hour_show = " hour ";
            $min_show = " min ";
          } else {
            $hour_show = " hours ";
            $min_show = " mins ";
          }
        }
        list($hours, $min, $secord) = explode(":", $inner_array[$field[1]]);
        list($hours2, $min2, $secord2) = explode(":", $inner_array[$field[8]]);
        $total_hours = $hours -  $hours2;
        $total_min = $min - $min2;
        $this->SetFont('THSarabun', '', 10);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->SetFont('THSarabun', '', 12);
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[1]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[2]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[3]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[4]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[5]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[6]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[7]], 0, 5)), 1, 0, 'C');
        $this->Cell(17.5, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[8]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", abs($total_hours) . $hour_show ." ". abs($total_min) . $min_show), 1, 0, 'C');
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[2]];
        $totalsum2 += $inner_array[$field[4]];
      }
    }
    // Footer Table

    $pdf->Cell(array_sum($w), 0, '', 'T');
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
    $footer_nextpage = $loop % 24;
    if ($footer_nextpage >= 23) {
      $pdf->AddPage("P", "A4");
    }
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

// Using Coding
$pdf->AddPage("P", "A4");
$Sql = "SELECT
factory.Facname
FROM
process
INNER JOIN dirty ON process.DocNo = dirty.DocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
$where
AND dirty.FacCode = $FacCode
       ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Facname = $Result['Facname'];
}

$datetime = new DatetimeTH();
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
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r15'][$language]), 0, 0, 'C');
$pdf->Ln(10);

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(1);
$pdf->Cell(165, 10, iconv("UTF-8", "TIS-620", $array2['factory'][$language] ." : ". $Facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12 );

$query = "SELECT
TIME (process.WashStartTime) AS WashStartTime ,
TIME (process.WashEndTime) AS WashEndTime,
TIME (process.PackStartTime)AS PackStartTime,
TIME (process.PackEndTime)AS PackEndTime,
TIME (process.SendStartTime)AS SendStartTime,
TIME (process.SendEndTime)AS SendEndTime,
dirty.FacCode,
process.DocNo AS  DocNo1 ,
TIME (dirty.ReceiveDate)AS ReceiveDate1,
TIME (rewash.ReceiveDate )AS ReceiveDate2,
TIME (newlinentable.ReceiveDate)AS ReceiveDate3
FROM
process
LEFT JOIN dirty ON process.DocNo = dirty.DocNo
LEFT JOIN rewash ON process.DocNo = rewash.DocNo
LEFT JOIN newlinentable ON process.DocNo = newlinentable.DocNo
$where
OR dirty.FacCode = $FacCode
OR rewash.FacCode = $FacCode
OR  newlinentable.FacCode = $FacCode
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = "DocNo1,ReceiveDate1,WashStartTime,WashStartTime,WashEndTime,PackStartTime,PackEndTime,SendStartTime,SendEndTime,Total,ReceiveDate2,ReceiveDate3";
// Table header
$header = array($array2['docdate'][$language], $array2['receive_time'][$language], $array2['washing_time'][$language], $array2['packing_time'][$language], $array2['distribute_time'][$language], $array2['total'][$language]);
// width of column table
$width = array(25, 35, 35, 35, 35, 25);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();

// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
