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
$format = $data['Format'];
$Userid = $_SESSION['Userid'];
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



header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  { }
  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
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

    $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
    $this->Cell($w[7], 20, iconv("UTF-8", "TIS-620", $header[7]), 1, 0, 'C');
    $this->Cell($w[1], 20, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
    $this->Cell($w[5], 20, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
    $this->Cell($w[6], 20, iconv("UTF-8", "TIS-620", $header[6]), 1, 1, 'C');
    $this->Cell($w[0] + $w[1] + $w[7], 0, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
    for ($i = 0; $i < 3; $i++) {
      $this->SetFont('THSarabun', 'b', 12);
      $this->Cell($w[2] / 3, -10, iconv("UTF-8", "TIS-620", $array2['start'][$language]), 1, 0, 'C');
      $this->Cell($w[2] / 3, -10, iconv("UTF-8", "TIS-620", $array2['finish'][$language]), 1, 0, 'C');
      $this->Cell($w[2] / 3, -10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    }
    $this->Ln(0);

    // set Data Details
    $count = 0;
    $rows = 1;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $y = 68;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {

        // if ($rows > 22) {
        //   $count++;
        //   if ($count % 25 == 1) {
        //     $this->SetFont('THSarabun', 'b', 14);
        //     for ($i = 0; $i < count($header); $i++)
        //       $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
        //     $this->Ln();
        //   }
        // }
        $txt = getStrLenTH($inner_array[$field[12]]); // 10
        $round = $txt / 20;
        list($main, $point) = explode(".", $round);
        if ($point > 0) {
          $point = 1;
          $main += $point;
        }
        $pdf->SetFont('THSarabun', '', 12);
        list($hoursSS, $minSS, $secordSS) = explode(":", $inner_array[$field[2]]);
        list($hoursSF, $minSF, $secordSF) = explode(":", $inner_array[$field[3]]);
        list($hoursPS, $minPS, $secordPS) = explode(":", $inner_array[$field[5]]);
        list($hoursPF, $minPF, $secordPF) = explode(":", $inner_array[$field[6]]);
        list($hoursDS, $minDS, $secordDS) = explode(":", $inner_array[$field[8]]);
        list($hoursDF, $minDF, $secordDF) = explode(":", $inner_array[$field[9]]);
        $h1 = $hoursSS - $hoursSF;
        $m1 = $minSS - $minSF;
        if ($inner_array[$field[1]] == 'Extra') {
          $h2 = $hoursPS - $hoursPF;
          $m2 = $minPS - $minPF;
        }
        $h3 = $hoursDS - $hoursDF;
        $m3 = $minDS - $minDF;
        $m1 = abs($m1);
        $m2 = abs($m2);
        $m3 = abs($m3);
        $h1 = abs($h1);
        $h2 = abs($h2);
        $h3 = abs($h3);

        // $total_hours_min_SS = ($hoursSS * 60) + $minSS;
        // $total_hours_min_DF = ($hoursDF * 60) + $minDF;
        // $total = $total_hours_min_SS - $total_hours_min_DF;
        // $Total_hours = $total / 60;
        // $Total_min = $total % 60;
        // $Total_hours = abs($Total_hours);
        // $Total_min = abs($Total_min);
        // if ($total_min / 60 >= 1) {
        //   $total_hours += $total_min / 60;
        //   $total_min = $total_min % 60;
        // }
        $total1 = substr($inner_array[$field[4]], 0, 5);
        $total2 = substr($inner_array[$field[7]], 0, 5);
        $total3 = substr($inner_array[$field[10]], 0, 5);
        list($sumh1, $summ1) = explode(":", $total1);
        list($sumh2, $summ2) = explode(":", $total2);
        list($sumh3, $summ3) = explode(":", $total3);


        // echo timeplus($arr);
        $totalhour = $h1 + $h2 + $h3;
        
        $totalmin = $m1 + $m2 + $m3;
        if ($totalmin >= 60) {
          $totalhouradd = ($totalmin / 60);
          $totalhour += $totalhouradd;
          $totalmin = $totalmin % (60 * $totalhouradd);
        }
        for ($i = 0; $i < 10; $i++) {
          if ($m1 == $i) {
            $m1 = "0" . $m1;
          }
          if ($h1 == $i) {
            $h1 =  "0" . $h1;
          }
          if ($m2 == $i) {
            $m2 = "0" . $m2;
          }
          if ($h2 == $i) {
            $h2 =  "0" . $h2;
          }
          if ($m3 == $i) {
            $m3 = "0" . $m3;
          }
          if ($h3 == $i) {
            $h3 =  "0" . $h3;
          }
        }
        if ($language == 'th') {
          $hour_show = " ชั่วโมง";
          $min_show = " นาที";
        } else {
          if ($totalhour <= 1) {
            $hour_show = " hour ";
          } else {
            $hour_show = " hours ";
          }
          if ($totalmin <= 1) {
            $min_show = " min ";
          } else {
            $min_show = " mins ";
          }
        }
        $total1 = $h1 . ":" . $m1;
        $total2 = $h2 . ":" . $m2;
        $total3 = $h3 . ":" . $m3;
        if ($inner_array[$field[1]] == 'Extra') {
          $pack1 = substr($inner_array[$field[5]], 0, 5);
          $pack2 = substr($inner_array[$field[6]], 0, 5);
          $total2 = $h2 . ":" . $m2;
        } else {
          $pack1 = '-';
          $pack2 = '-';
          $total2 = '-';
        }
        $pdf->SetFont('THSarabun', '', 14);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $pdf->SetFont('THSarabun', '', 14);
        $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[13]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[1]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[2] / 3, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[2]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[3] / 3, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[3]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[4] / 3, 10, iconv("UTF-8", "TIS-620", $total1), 1, 0, 'C');
        $this->Cell($w[2] / 3, 10, iconv("UTF-8", "TIS-620", $pack1), 1, 0, 'C');
        $this->Cell($w[3] / 3, 10, iconv("UTF-8", "TIS-620", $pack2), 1, 0, 'C');
        $this->Cell($w[4] / 3, 10, iconv("UTF-8", "TIS-620", $total2), 1, 0, 'C');
        $this->Cell($w[2] / 3, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[8]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[3] / 3, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[9]], 0, 5)), 1, 0, 'C');
        $this->Cell($w[4] / 3, 10, iconv("UTF-8", "TIS-620", $total3), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($totalhour) . $hour_show . " " . $totalmin .  $min_show), 1, 0, 'C');
        $this->SetX(240);
        $this->MultiCell($w[6], 10 / $main, iconv("UTF-8", "TIS-620", $inner_array[$field[12]]), 1, 'C');
        $this->SetXY($w[0] + $w[1] + $w[2] + $w[3] + $w[4] + $w[2] + $w[3] + $w[4] + $w[2] + $w[3] + $w[4] + $w[5] + $w[6] + 10, $y);
        $this->Ln();
        $y += 10;
      }
    }




    // Closing line

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
$pdf->AddPage("L", "A4");
if ($language == 'th') {
  $Perfix = THPerfix;
  $Name = THName;
  $LName = THLName;
} else {
  $Perfix = EngPerfix;
  $Name = EngName;
  $LName = EngLName;
}
$Sql = "SELECT
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName
FROM
users
INNER JOIN shelfcount ON shelfcount.Modify_Code= users.ID
WHERE shelfcount.Modify_Code = $Userid
 ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $FName = $Result['FName'];
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$pdf->SetFont('THSarabun', '', 10);
$image = "../images/Nhealth_linen 4.0.png";
$pdf->Image($image, 10, 10, 43, 15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(0, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
// Title
$pdf->SetFont('THSarabun', 'b', 18);

$pdf->Cell(0, 10, iconv("UTF-8", "TIS-620", $array2['r19'][$language]), 0, 0, 'C');

$pdf->Ln(10);

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(1);
$pdf->Cell(165, 10, iconv("UTF-8", "TIS-620", $array2['officer'][$language] . " :  " . $FName), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(10);

$query = "SELECT
shelfcount.docno,
time_sc.TimeName AS CycleTime,
COALESCE(TIME(shelfcount.ScStartTime),'-') AS ScStartTime ,
COALESCE(TIME(shelfcount.ScEndTime),'-') AS ScEndTime ,  
COALESCE(TIME(shelfcount.PkEndTime),'-') AS PkEndTime ,
COALESCE(TIME(shelfcount.PkStartTime),'-') AS PkStartTime ,
COALESCE(TIME(shelfcount.DvStartTime),'-') AS DvStartTime ,
COALESCE(TIME(shelfcount.DvEndTime),'-') AS DvEndTime ,
TIMEDIFF(shelfcount.ScStartTime,shelfcount.ScEndTime)AS SC ,
TIMEDIFF(shelfcount.PkStartTime,shelfcount.PkEndTime)AS PK ,
TIMEDIFF(shelfcount.DvStartTime,shelfcount.DvEndTime)AS DV,
department.DepName,
sc_time_2.TimeName 
FROM
shelfcount
INNER JOIN department on department.DepCode = shelfcount.DepCode
INNER JOIN users ON users.ID = shelfcount.Modify_Code
LEFT JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
LEFT JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
$where
AND shelfcount.Modify_code = $Userid
AND shelfcount.isStatus <> 9
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = "docno,CycleTime,ScStartTime,ScEndTime,SC,PkStartTime,PkEndTime,PK,DvStartTime,DvEndTime,DV,,DepName,TimeName";
// Table header
$header = array($array2['docno'][$language], $array2['Cycle'][$language], $array2['shelfcount'][$language], $array2['packing_time'][$language], $array2['delivery_time'][$language], $array2['total'][$language], $array2['department1'][$language], $array2['receivecycle'][$language]);
// width of column table
$width = array(40, 15, 40, 40, 40, 40, 50, 15);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();

// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Tracking_status_for_linen_operation_by_user_' . $ddate . '.pdf');
