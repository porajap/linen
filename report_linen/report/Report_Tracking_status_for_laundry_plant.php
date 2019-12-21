<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data_send = ['HptCode' => $HptCode, 'FacCode' => $FacCode, 'date1' => $date1, 'date2' => $date2,   'betweendate1' => $betweendate1, 'betweendate2' => $betweendate2, 'Format' => $Format, 'DepCode' => $DepCode, 'chk' => $chk];

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
$date_query1 = $data[2];
$date_query2 = $data[3];
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
    $where =   "WHERE DATE (process.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (process.Docdate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE process.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (process.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(process.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $datetime = new DatetimeTH();
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
  {
  }

  function setTable($pdf, $header, $data, $width, $numfield, $field, $i)
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
    if ($i == 0 || $rows == 11) {
      $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[6]), 1, 0, 'C');
      $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
      $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
      $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
      $this->Ln();

      $this->Cell(40, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
      $this->Cell(40, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
      for ($i = 0; $i < 4; $i++) {
        $this->Cell(20, -10, iconv("UTF-8", "TIS-620", $array2['start'][$language]), 1, 0, 'C');
        $this->Cell(20, -10, iconv("UTF-8", "TIS-620", $array2['finish'][$language]), 1, 0, 'C');
      }
      $this->Ln(0);
    }

    // set Data Details
    $rows = 1;
    $loop = 0;
    $totalsum1 = 0;
    $totalsum2 = 0;

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
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
        list($hours1, $min1, $secord1) = explode(":", $inner_array[$field[12]]);
        list($hours2, $min2, $secord2) = explode(":", $inner_array[$field[13]]);
        list($hours3, $min3, $secord3) = explode(":", $inner_array[$field[14]]);
        list($hours4, $min4, $secord4) = explode(":", $inner_array[$field[11]]);
        $hours1 = str_replace("-", '', $hours1);
        $hours2 = str_replace("-", '', $hours2);
        $hours3 = str_replace("-", '', $hours3);
        $hours4 = str_replace("-", '', $hours4);
        $hous = $hours1 + $hours2 + $hours3 + $hours4;
        $mins = $min1 + $min2 + $min3 + $min4;
        if ($mins >= 60) {
          $Ansmin = $mins / 60;
          $Ansmin = (int) ($Ansmin);
          $hous = $hous + $Ansmin;
          $mins = $mins % 60;
        }
        $timeshow = $hous . $hour_show . " " . $mins . $min_show;
        $this->SetFont('THSarabun', '', 12);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->SetFont('THSarabun', '', 12);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[10]]), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[1]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[2]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[3]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[4]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[5]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[6]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[7]], 0, 5)), 1, 0, 'C');
        $this->Cell(20, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[8]], 0, 5)), 1, 0, 'C');
        // $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[11]]), 1, 0, 'C');
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $timeshow), 1, 0, 'C');
        $this->Ln();
        $rows++;
        if ($rows == 11) {
          $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
          $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
          $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
          $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
          $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
          $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
          $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
          $this->Ln();

          $this->Cell(40, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
          $this->Cell(40, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
          for ($i = 0; $i < 4; $i++) {
            $this->Cell(20, -10, iconv("UTF-8", "TIS-620", $array2['start'][$language]), 1, 0, 'C');
            $this->Cell(20, -10, iconv("UTF-8", "TIS-620", $array2['finish'][$language]), 1, 0, 'C');
          }
          $this->Ln(0);
        }
      }
    }
    $field = "DocNo1,ReceiveDate1,WashStartTime,WashStartTime,WashEndTime,PackStartTime,PackEndTime,SendStartTime,SendEndTime";
    // Footer Table


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
$pdf->AddPage("L", "A4");
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$Sql = "SELECT
factory.$FacName
FROM
process
INNER JOIN factory ON factory.FacCode = process.FacCode
where process.FacCode = $FacCode
       ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Facname = $Result[$FacName];
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
$pdf->Cell(0, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
// Title
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(0, 10, iconv("UTF-8", "TIS-620", $array2['r15'][$language]), 0, 0, 'C');
$pdf->Ln(10);

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(1);
$pdf->Cell(165, 10, iconv("UTF-8", "TIS-620", $array2['factory'][$language] . " : " . $Facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);
$HptCode = substr($HptCode, 0, 3);
$doc = array(dirty, repair_wash, newlinentable);
$j = 0;
for ($i = 0; $i < 3; $i++) {
  if ($chk == 'one') {
    if ($format == 1) {
      $where =   "WHERE DATE (" . $doc[$i] . ".Docdate) = DATE('$date_query1')";
    } elseif ($format = 3) {
      $where = "WHERE  year (" . $doc[$i] . ".Docdate) LIKE '%$date_query1%'";
    }
  } elseif ($chk == 'between') {
    $where =   "WHERE " . $doc[$i] . ".Docdate BETWEEN '$date_query1' AND '$date_query2'";
  } elseif ($chk == 'month') {
    $where =   "WHERE month (" . $doc[$i] . ".Docdate) = " . $date_query1;
  } elseif ($chk == 'monthbetween') {
    $where =   "WHERE date(" . $doc[$i] . ".Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  }
  $query = "SELECT
TIME (process.WashStartTime) AS WashStartTime ,
TIME (process.WashEndTime) AS WashEndTime,
TIME (process.PackStartTime)AS PackStartTime,
TIME (process.PackEndTime)AS PackEndTime,
TIME (process.SendStartTime)AS SendStartTime,
TIME (process.SendEndTime)AS SendEndTime,
$doc[$i].FacCode,
process.DocNo AS  DocNo1 ,
TIME ($doc[$i].ReceiveDate)AS ReceiveDate1,
DATE_FORMAT($doc[$i].DocDate,'%d/%m/%Y') AS Date1,
TIME_FORMAT(TIMEDIFF($doc[$i].ReceiveDate, process.SendEndTime), '%H:%i') AS TIME ,
TIME_FORMAT(TIMEDIFF(process.WashStartTime ,process.WashEndTime), '%H:%i') AS wash ,
TIME_FORMAT(TIMEDIFF(process.PackStartTime ,process.PackEndTime), '%H:%i') AS pack ,
TIME_FORMAT(TIMEDIFF(process.SendStartTime ,process.SendEndTime), '%H:%i') AS send 
FROM
process
LEFT JOIN $doc[$i] ON process.DocNo = $doc[$i].DocNo
$where AND $FacCode in ($doc[$i].FacCode)
AND process.isStatus <> 9
";
  // Number of column
  $numfield = 6;
  // Field data (Must match with Query)
  $field = "DocNo1,ReceiveDate1,WashStartTime,WashStartTime,WashEndTime,PackStartTime,PackEndTime,SendStartTime,SendEndTime,Total,Date1,TIME,wash,pack,send";
  // Table header
  $header = array($array2['docdate'][$language], $array2['receive_time'][$language], $array2['washing_time'][$language], $array2['packing_time'][$language], $array2['distribute_time'][$language], $array2['total'][$language], $array2['docno'][$language]);
  // width of column table
  $width = array(40, 40, 40, 40, 40, 25);
  // Get Data and store in Result
  $result = $data->getdata($conn, $query, $numfield, $field);
  // Set Table
  $pdf->SetFont('THSarabun', 'b', 10);
  $pdf->setTable($pdf, $header, $result, $width, $numfield, $field, $i);
  $j++;
}

// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Tracking_status_for_laundry_plant_' . $ddate . '.pdf');
