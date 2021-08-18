<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
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
$DepCode[] = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$DepCode = array();
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
  {
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


    // set Data Details
    $count = 0;
    $rows = 1;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $y = 70;
    $old_code = '';
    $old_dateshow = '';
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $code =  $inner_array[$field[14]];
        $name =  $inner_array[$field[15]];
        $dateshow =  $inner_array[$field[16]];
        list($y, $m, $d) = explode('-', $dateshow);
        if ($language ==  'th') {
          $y = $y + 543;
        }
        $dateshow = $d . '-' . $m . '-' . $y;
        if ($dateshow <> $old_dateshow) {
          $this->SetFont('THSarabun', '', 20);
          $this->ln(10);
          $this->Cell(0, 10, iconv("UTF-8", "TIS-620", $dateshow), T, 1, 'C');
          $this->ln(5);
          $old_dateshow = $dateshow;
        }
        if ($inner_array[$field[0]] <> null) {
          if ($code <> $old_code) {
            $this->SetFont('THSarabun', 'b', 14);
            $this->Cell(10, 10, iconv("UTF-8", "TIS-620", $array2['department'][$language] . " : " . $name), 0, 1, 'L');
            $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
            $this->Cell($w[7], 20, iconv("UTF-8", "TIS-620", $header[7]), 1, 0, 'C');
            $this->Cell($w[1], 20, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
            $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
            $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
            $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
            $this->Cell($w[5], 20, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
            $this->Cell($w[6], 20, iconv("UTF-8", "TIS-620", $header[6]), 1, 1, 'C');
            $this->Cell($w[0] + $w[1] + $w[7], 0, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
            $this->SetFont('THSarabun', 'b', 10);
            if ($language == 'th') {
              $size = 12;
            } else {
              $size = 12;
            }
            for ($i = 0; $i < 3; $i++) {
              $this->SetFont('THSarabun', 'b', $size);
              $this->Cell($w[2] / 3, -10, iconv("UTF-8", "TIS-620", $array2['start'][$language]), 1, 0, 'C');
              $this->Cell($w[2] / 3, -10, iconv("UTF-8", "TIS-620", $array2['finish'][$language]), 1, 0, 'C');
              $this->Cell($w[2] / 3, -10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
            }
            $this->Cell(0, 0, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');
            $old_code = $code;
          }


          $txt = getStrLenTH($inner_array[$field[12]]); // 10
          $round = $txt / 35;
          list($main, $point) = explode(".", $round);
          if ($point > 0) {
            $point = 1;
            $main += $point;
          }
          if ($inner_array[$field[1]] == 'Extra') {
            $sc1 = substr($inner_array[$field[2]], 0, 5);
            $sc2 = substr($inner_array[$field[2]], 0, 5);
          } else {
            $sc1 = substr($inner_array[$field[2]], 0, 5);
            $sc2 = substr($inner_array[$field[3]], 0, 5);
          }
          $pdf->SetFont('THSarabun', '', 12);
          // list($hoursSS, $minSS, $secordSS) = explode(":",  $sc1);
          list($hoursSF, $minSF, $secordSF) = explode(":", $inner_array[$field[4]]);
          list($hoursPS, $minPS, $secordPS) = explode(":", $inner_array[$field[5]]);
          list($hoursPF, $minPF, $secordPF) = explode(":", $inner_array[$field[6]]);
          list($hoursDS, $minDS, $secordDS) = explode(":", $inner_array[$field[8]]);
          list($hoursDF, $minDF, $secordDF) = explode(":", $inner_array[$field[9]]);
          // $h1 = $hoursSS - $hoursSF;
          // $m1 = $minSS - $minSF;
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
              $h2 = "0" . $h2;
            }
            if ($m3 == $i) {
              $m3 = "0" . $m3;
            }
            if ($h3 == $i) {
              $h3 = "0" . $h3;
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
            $pack1 = $inner_array[$field[5]];
            $pack2 = $inner_array[$field[6]];
            $total2 = $inner_array[$field[7]];
            $totalpk = $inner_array[$field[7]];
          } else {
            $pack1 = '-';
            $pack2 = '-';
            $total2 = '-';
            $totalpk = '00:00:00';
          }



          $totalTime  =  sum_the_time($inner_array[$field[4]],$totalpk,$inner_array[$field[10]]);  // this will give you a result: 19:12:25          ;

          $pdf->SetFont('THSarabun', '', 12);
          $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
          $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[13]]), 1, 0, 'C');
          $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
          $this->Cell($w[2] / 3, 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
          $this->Cell($w[3] / 3, 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
          $this->Cell($w[4] / 3, 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
          $this->Cell($w[2] / 3, 10, iconv("UTF-8", "TIS-620", $pack1), 1, 0, 'C');
          $this->Cell($w[3] / 3, 10, iconv("UTF-8", "TIS-620", $pack2), 1, 0, 'C');
          $this->Cell($w[4] / 3, 10, iconv("UTF-8", "TIS-620", $total2), 1, 0, 'C');
          $this->Cell($w[2] / 3, 10, iconv("UTF-8", "TIS-620", $inner_array[$field[8]]), 1, 0, 'C');
          $this->Cell($w[3] / 3, 10, iconv("UTF-8", "TIS-620", $inner_array[$field[9]]), 1, 0, 'C');
          $this->Cell($w[4] / 3, 10, iconv("UTF-8", "TIS-620", $inner_array[$field[10]]), 1, 0, 'C');
          $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $totalTime), 1, 0, 'C');
          $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[12]]), 1, 1,   'C');
          $y += 10;
        }
      }
      $this->ln();
    }
  }


  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 13);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", '') . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }
}
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
$pdf->AddPage("L", "A4");
$pdf->SetAutoPageBreak(true, 20);
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

$pdf->Cell(0, 10, iconv("UTF-8", "TIS-620", $array2['r18'][$language]), 0, 0, 'C');

$pdf->Ln(12);

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(ุ0, 7, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(10);
if ($language == 'th') {

  $Perfix = THPerfix;
  $Name = THName;
  $LName = THLName;
} else {

  $Perfix = EngPerfix;
  $Name = EngName;
  $LName = EngLName;
}

$header = array($array2['docno'][$language], $array2['Cycle'][$language], $array2['shelfcount'][$language], $array2['packing_time'][$language], $array2['delivery_time'][$language], $array2['total'][$language], $array2['user'][$language], $array2['receivecycle'][$language]);
// for ($i = 0; $i <= $Count_Dep; $i++) {
$query = "SELECT
  department.DepCode,
  department.DepName,
  shelfcount.docno,
  time_sc.TimeName AS CycleTime,
  COALESCE(TIME(shelfcount.ScStartTime),'-') AS ScStartTime ,
  COALESCE(TIME(shelfcount.ScEndTime),'-') AS ScEndTime ,  
  COALESCE(TIME(shelfcount.PkEndTime),'-') AS PkEndTime ,
  COALESCE(TIME(shelfcount.PkStartTime),'-') AS PkStartTime ,
  COALESCE(TIME(shelfcount.DvStartTime),'-') AS DvStartTime ,
  COALESCE(TIME(shelfcount.DvEndTime),'-') AS DvEndTime ,
  TIMEDIFF(shelfcount.ScEndTime,shelfcount.ScStartTime)AS SC ,
  TIMEDIFF(shelfcount.PkEndTime,shelfcount.PkStartTime)AS PK ,
  TIMEDIFF(shelfcount.DvEndTime,shelfcount.DvStartTime)AS DV,
  CONCAT($Perfix,' ' , $Name,' ' ,$LName)  as USER,
  sc_time_2.TimeName ,
  shelfcount.DocDate
  FROM
	shelfcount
INNER JOIN department ON department.DepCode = shelfcount.DepCode
INNER JOIN users ON users.ID = shelfcount.Modify_Code
LEFT JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
LEFT JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
$where 
AND department.HptCode = '$HptCode'
AND shelfcount.isStatus <> 9
GROUP BY shelfcount.DocNo
ORDER BY shelfcount.DocDate,shelfcount.DepCode ASC ";
$field = "docno,CycleTime,ScStartTime,ScEndTime,SC,PkStartTime,PkEndTime,PK,DvStartTime,DvEndTime,DV,,USER,TimeName,DepCode,DepName,DocDate";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
// Table header
$width = array(40, 15, 40, 40, 40, 40, 50, 15);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
// }
// }
// Footer Table
function sum_the_time($time1,$time2,$time3)
{
  $times = array($time1,$time2,$time3);
  $seconds = 0;
  foreach ($times as $time) {
    list($hour, $minute, $second) = explode(':', $time);
    $seconds += $hour * 3600;
    $seconds += $minute * 60;
    $seconds += $second;
  }
  $hours = floor($seconds / 3600);
  $seconds -= $hours * 3600;
  $minutes  = floor($seconds / 60);
  $seconds -= $minutes * 60;
  // return "{$hours}:{$minutes}:{$seconds}";
  return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
}
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Tracking_status_for_linen_operation_by_ward_' . $ddate . '.pdf');
