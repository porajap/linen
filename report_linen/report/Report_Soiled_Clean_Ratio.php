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
$year1 = $data[2];
$year2 = $data[3];
$date1 = $data[4];
$date2 = $data[5];
$betweendate1 = $data[6];
$betweendate2 = $data[7];
$format = $data[8];
$DepCode = $data[9];
$chk = $data[10];
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
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
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
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
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
//query 
$Sql = "SELECT
        factory.$FacName,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        INNER JOIN dirty_detail ON dirty.Docno =dirty_detail.Docno
        INNER JOIN department ON department.depcode =dirty_detail.depcode
        INNER JOIN site ON site.hptcode =department.hptcode
        WHERE factory.FacCode = '$FacCode'
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $facname = $Result[$FacName];
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
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r8'][$language]), 0, 0, 'C');
$pdf->Ln(3);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", $array2['factory'][$language] . " : " . $facname), 0, 0, 'L');
$pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);
//width
$w = array(15, 19.875, 24.875, 21.875, 20.875, 19.875, 24.875, 21.875, 20.875);

//header
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell($w[1], 20, iconv("UTF-8", "TIS-620", $array2['docdate'][$language]), 1, 0, 'C');
$pdf->Cell(87.5, 10, iconv("UTF-8", "TIS-620", $array2['soild'][$language]), 1, 0, 'C');
$pdf->Cell(87.5, 10, iconv("UTF-8", "TIS-620",  $array2['clean1'][$language]), 1, 1, 'C');
$pdf->SetFont('THSarabun', 'b', 11.5);
$pdf->Cell(19.875, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['dirty'][$language]), 1, 0, 'C');
$pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['repair_dirty'][$language]), 1, 0, 'C');
$pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['newlinen'][$language]), 1, 0, 'C');
$pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['Totaldirty'][$language]), 1, 0, 'C');
$pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['clean'][$language]), 1, 0, 'C');
$pdf->SetFont('THSarabun', 'b', $size);
$pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['receive_dirty'][$language]), 1, 0, 'C');
if ($language == 'th') {
  $size = 12;
} else {
  $size = 10;
}
$pdf->SetFont('THSarabun', 'b', $size);
$pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['receive_newlinen'][$language]), 1, 0, 'C');
$pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['Totalclean'][$language]), 1, 1, 'C');

//data
if ($chk == 'one') {
  if ($format == 1) { // 1วัน
    $date = $date1;
    $count = count($date);
    for ($i = 1; $i <= $count; $i++) {
      $query = "SELECT 
     DIRTY,
    repair_wash,
     NEWLINEN,
     CLEAN,
     CLEAN_repair_wash,
     CLEAN_NEWLINEN,
    a.DocDate,
    b.DocDate,
    c.DocDate,
    d.DocDate,
    e.DocDate,
    f.DocDate
    FROM (
    SELECT
    COALESCE(sum(dirty.Total),'0') AS DIRTY ,
    COALESCE(dirty.DocDate,0) AS DocDate
    FROM
    dirty
    WHERE DATE (dirty.Docdate) =  '$date'  AND dirty.faccode= '$FacCode' AND dirty.HptCode= '$HptCode'
   AND dirty.isstatus <> 9
    )a,
    (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
    COALESCE(repair_wash.DocDate,0) AS DocDate
    FROM  repair_wash
     
    WHERE DATE (repair_wash.Docdate) = '$date'
    AND repair_wash.FacCode = '$FacCode'
    AND repair_wash.HptCode= '$HptCode'
    AND repair_wash.isStatus<>9
    )b,
    (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
    COALESCE(newlinentable.DocDate,0) AS DocDate
    FROM newlinentable
    WHERE DATE (newlinentable.Docdate) = '$date' AND newlinentable.FacCode = '$FacCode' AND newlinentable.HptCode= '$HptCode'
    AND newlinentable.isStatus<>9
    )c,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
    COALESCE(clean.DocDate,0) AS DocDate
    FROM clean
    LEFT JOIN department ON department.DepCode = clean.DepCode
		LEFT JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date' AND (clean.RefDocNo = '' OR clean.RefDocNo LIKE '%DT%')
      AND clean.IsStatus <>9 AND site.HptCode= '$HptCode' AND clean.FacCode = '$FacCode'
    )d,
    (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
      COALESCE(return_wash.DocDate,0) AS DocDate
      FROM return_wash
      LEFT JOIN department ON department.DepCode = return_wash.DepCode
      LEFT JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (return_wash.Docdate) = '$date' AND return_wash.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
    AND return_wash.IsStatus  <> 9
    )e,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
    COALESCE(newlinentable.DocDate,0) AS DocDate
    FROM clean
    LEFT JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
    LEFT JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.RefDocNo
    LEFT JOIN department ON department.DepCode = clean.DepCode
		LEFT JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date'
    AND clean.FacCode = '$FacCode' AND site.HptCode= '$HptCode'AND clean.IsStatus  <> 9 )
    f";
      $meQuery = mysqli_query($conn, $query);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $docdate = $Result['DocDate'];
        $dirty = $Result['DIRTY'];
        $repair_wash = $Result['repair_wash'];
        $newlinen = $Result['NEWLINEN'];
        $clean = $Result['CLEAN'];
        $clean_repair_wash = $Result['CLEAN_repair_wash'];
        $clean_newlinen = $Result['CLEAN_NEWLINEN'];
        $total1 = 0;
        $total2 = 0;
        $r = 0;
        if (
          $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
        ) {
          list($year, $month, $day) = explode('-', $date);
          if ($language == 'th') {
            $year = $year + 543;
            $date = $day . "-" . $month . "-" . $year;
          } else {
            $date = $day . "-" . $month . "-" . $year;
          }
          $pdf->SetFont('THSarabun', '', 14);
          $total1 = $dirty + $repair_wash + $newlinen;
          $total2 = $clean + $clean_repair_wash + $clean_newlinen;
          $pdf->SetFont('THSarabun', '', 14);
          $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $date), 1, 0, 'C');
          $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($dirty, 2)), 1, 0, 'C');
          $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($repair_wash, 2)), 1, 0, 'C');
          $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($newlinen, 2)), 1, 0, 'C');
          $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total1, 2)), 1, 0, 'C');
          $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($clean, 2)), 1, 0, 'C');
          $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($clean_repair_wash, 2)), 1, 0, 'C');
          $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($clean_newlinen, 2)), 1, 0, 'C');
          $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total2, 2)), 1, 0, 'C');
          $totalsum1 += $dirty;
          $totalsum2 += $repair_wash;
          $totalsum3 += $newlinen;
          $totalsum4 += $total1;
          $totalsum5 += $clean;
          $totalsum6 += $clean_repair_wash;
          $totalsum7 += $clean_newlinen;
          $totalsum8 += $total2;
          $pdf->Ln();
        }
      }
    }
    $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum1, 2)), 1, 0, 'C');
    $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum2, 2)), 1, 0, 'C');
    $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum3, 2)), 1, 0, 'C');
    $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum4, 2)), 1, 0, 'C');
    $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum5, 2)), 1, 0, 'C');
    $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum6, 2)), 1, 0, 'C');
    $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum7, 2)), 1, 0, 'C');
    $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum8, 2)), 1, 1, 'C');
    if ($totalsum8 == 0) {
      $totalsum8 = 1;
    }
    $scr = (($totalsum4 / $totalsum8) - 1) * 100;

    $pdf->ln(7);
    $pdf->Cell(95, 10, iconv("UTF-8", "TIS-620",  'SCR (Soiled-Clean Ratio)'), 1, 0, 'C');
    $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620",  number_format(abs($scr), 2) . '%'), 1, 1, 'C');
  } elseif ($format = 3) {
    if ($language == 'th') {
      $date1 = $date1 - 543;
    }
    $year = $date1;
    $monthh = 12;
    for ($i = 1; $i <= $monthh; $i++) {
      $day = 1;
      $count = cal_days_in_month(CAL_GREGORIAN, $i, $year);
      $datequery =  $year . '-' . $i . '-';
      $dateshow = '-' . $i . '-' . $year;
      for ($j = 0; $j < $count; $j++) {
        $date[] = $datequery . $day;
        $DateShow[] = $day . $dateshow;
        $day++;
      }
    }
    $count = sizeof($date);
    for ($i = 0; $i < $count; $i++) {
      $query = "SELECT 
       DIRTY,
       repair_wash,
       NEWLINEN,
       CLEAN,
       CLEAN_repair_wash,
       CLEAN_NEWLINEN,
      a.DocDate,
      b.DocDate,
      c.DocDate,
      d.DocDate,
      e.DocDate,
      f.DocDate
      FROM (
      SELECT
       COALESCE(sum(dirty.Total),'0') AS DIRTY ,
      COALESCE(dirty.DocDate,0) AS DocDate
      FROM
      dirty
      WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.HptCode= '$HptCode'
      AND dirty.isstatus <> 9
      )a,
      (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
      COALESCE(repair_wash.DocDate,0) AS DocDate
      FROM  repair_wash
      WHERE DATE (repair_wash.Docdate) = '$date[$i]'
      AND repair_wash.FacCode = '$FacCode' AND repair_wash.HptCode= '$HptCode'
      AND repair_wash.isStatus<>9
      )b,
      (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
      COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM newlinentable
      WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.HptCode= '$HptCode'
      AND newlinentable.isStatus<>9
      )c,
      (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
      COALESCE(clean.DocDate,0) AS DocDate
      FROM clean
      LEFT JOIN department ON department.DepCode = clean.DepCode
		  LEFT JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (clean.Docdate) = '$date[$i]' AND (clean.RefDocNo = '' OR clean.RefDocNo LIKE '%DT%')
      AND clean.IsStatus <>9 AND site.HptCode= '$HptCode' AND clean.FacCode = '$FacCode'
      )d,
      (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
          COALESCE(return_wash.DocDate,0) AS DocDate
          FROM return_wash
          LEFT JOIN department ON department.DepCode = return_wash.DepCode
          LEFT JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
      AND return_wash.IsStatus  <> 9
      )e,
      (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
      COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM clean
      LEFT JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
      LEFT JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.RefDocNo
      LEFT JOIN department ON department.DepCode = clean.DepCode
		  LEFT JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (clean.Docdate) = '$date[$i]'
      AND clean.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
      AND clean.IsStatus  <> 9 )
      f";
      $meQuery = mysqli_query($conn, $query);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $docdate = $Result['DocDate'];
        $dirty = $Result['DIRTY'];
        $repair_wash = $Result['repair_wash'];
        $newlinen = $Result['NEWLINEN'];
        $clean = $Result['CLEAN'];
        $clean_repair_wash = $Result['CLEAN_repair_wash'];
        $clean_newlinen = $Result['CLEAN_NEWLINEN'];
        $total1 = 0;
        $total2 = 0;
        $r = 0;
        if (
          $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
        ) {
          list($day, $month, $year) = explode('-', $DateShow[$i]);
          if ($language == 'th') {
            $year = $year + 543;
            $datesh = $day . "-" . $month . "-" . $year;
          } else {
            $datesh = $day . "-" . $month . "-" . $year;
          }
          $pdf->SetFont('THSarabun', '', 14);
          $total1 = $dirty + $repair_wash + $newlinen;
          $total2 = $clean + $clean_repair_wash + $clean_newlinen;
          $pdf->SetFont('THSarabun', '', 14);
          $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $datesh), 1, 0, 'C');
          $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($dirty, 2)), 1, 0, 'C');
          $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($repair_wash, 2)), 1, 0, 'C');
          $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($newlinen, 2)), 1, 0, 'C');
          $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total1, 2)), 1, 0, 'C');
          $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($clean, 2)), 1, 0, 'C');
          $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($clean_repair_wash, 2)), 1, 0, 'C');
          $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($clean_newlinen, 2)), 1, 0, 'C');
          $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total2, 2)), 1, 0, 'C');
          $totalsum1 += $dirty;
          $totalsum2 += $repair_wash;
          $totalsum3 += $newlinen;
          $totalsum4 += $total1;
          $totalsum5 += $clean;
          $totalsum6 += $clean_repair_wash;
          $totalsum7 += $clean_newlinen;
          $totalsum8 += $total2;
          $pdf->Ln();
        }
      }
    }
    $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum1, 2)), 1, 0, 'C');
    $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum2, 2)), 1, 0, 'C');
    $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum3, 2)), 1, 0, 'C');
    $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum4, 2)), 1, 0, 'C');
    $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum5, 2)), 1, 0, 'C');
    $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum6, 2)), 1, 0, 'C');
    $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum7, 2)), 1, 0, 'C');
    $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum8, 2)), 1, 1, 'C');
    if ($totalsum8 == 0) {
      $totalsum8 = 1;
    }
    $scr = (($totalsum4 / $totalsum8) - 1) * 100;
    $pdf->ln(7);
    $pdf->Cell(95, 10, iconv("UTF-8", "TIS-620",  'SCR (Soiled-Clean Ratio)'), 1, 0, 'C');
    $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620",  number_format(abs($scr), 2) . '%'), 1, 1, 'C');
  }
} elseif ($chk == 'between') {
  list($year, $month, $day) = explode('-', $date2);
  if ($day <> 31) {
    $day = $day + 1;
  }
  $date2 = $year . "-" . $month . "-" . $day;
  $period = new DatePeriod(
    new DateTime($date1),
    new DateInterval('P1D'),
    new DateTime($date2)
  );
  foreach ($period as $key => $value) {
    $date[] = $value->format('Y-m-d');
    $dateshow[] = $value->format('d-m-Y');
  }
  $count = sizeof($date);
  for ($i = 0; $i < $count; $i++) {
    $query = "SELECT 
    DIRTY,
    repair_wash,
    NEWLINEN,
    CLEAN,
    CLEAN_repair_wash,
    CLEAN_NEWLINEN,
    a.DocDate,
    b.DocDate,
    c.DocDate,
    d.DocDate,
    e.DocDate,
    f.DocDate
    FROM (
    SELECT
    COALESCE(sum(dirty.Total),'0') AS DIRTY ,
    COALESCE(dirty.DocDate,0) AS DocDate
    FROM
    dirty
    WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.HptCode= '$HptCode'
    AND dirty.isstatus <> 9
    )a,
    (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
    COALESCE(repair_wash.DocDate,0) AS DocDate
    FROM  repair_wash
     
    WHERE DATE (repair_wash.Docdate) = '$date[$i]'
    AND repair_wash.FacCode = '$FacCode' AND repair_wash.HptCode= '$HptCode'
    AND repair_wash.isStatus<>9
    )b,
    (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
    COALESCE(newlinentable.DocDate,0) AS DocDate
    FROM newlinentable
    WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.HptCode= '$HptCode'
    AND newlinentable.isStatus<>9
    )c,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
    COALESCE(clean.DocDate,0) AS DocDate
    FROM clean
    LEFT JOIN department ON department.DepCode = clean.DepCode
      LEFT JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date[$i]' AND (clean.RefDocNo = '' OR clean.RefDocNo LIKE '%DT%')
        AND clean.IsStatus <>9 AND site.HptCode= '$HptCode' AND clean.FacCode = '$FacCode'
    )d,
    (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
    COALESCE(return_wash.DocDate,0) AS DocDate
    FROM return_wash
    LEFT JOIN department ON department.DepCode = return_wash.DepCode
    LEFT JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
    AND return_wash.IsStatus  <> 9
    )e,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
    COALESCE(newlinentable.DocDate,0) AS DocDate
    FROM clean
    LEFT JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
    LEFT JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.RefDocNo
    LEFT JOIN department ON department.DepCode = clean.DepCode
      LEFT JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date[$i]'
    AND clean.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
    AND clean.IsStatus  <> 9)
    f";
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $docdate = $Result['DocDate'];
      $dirty = $Result['DIRTY'];
      $repair_wash = $Result['repair_wash'];
      $newlinen = $Result['NEWLINEN'];
      $clean = $Result['CLEAN'];
      $clean_repair_wash = $Result['CLEAN_repair_wash'];
      $clean_newlinen = $Result['CLEAN_NEWLINEN'];
      $total1 = 0;
      $total2 = 0;
      $r = 0;
      if ($dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0) {

        list($day, $month, $year) = explode('-', $dateshow[$i]);
        if ($language == 'th') {
          $year = $year + 543;
          $datesh = $day . "-" . $month . "-" . $year;
        } else {
          $datesh = $day . "-" . $month . "-" . $year;
        }
        $pdf->SetFont('THSarabun', '', 14);
        $total1 = $dirty + $repair_wash + $newlinen;
        $total2 = $clean + $clean_repair_wash + $clean_newlinen;
        $pdf->SetFont('THSarabun', '', 14);
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $datesh), 1, 0, 'C');
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($dirty, 2)), 1, 0, 'C');
        $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($repair_wash, 2)), 1, 0, 'C');
        $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($newlinen, 2)), 1, 0, 'C');
        $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total1, 2)), 1, 0, 'C');
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($clean, 2)), 1, 0, 'C');
        $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($clean_repair_wash, 2)), 1, 0, 'C');
        $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($clean_newlinen, 2)), 1, 0, 'C');
        $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total2, 2)), 1, 0, 'C');
        $totalsum1 += $dirty;
        $totalsum2 += $repair_wash;
        $totalsum3 += $newlinen;
        $totalsum4 += $total1;
        $totalsum5 += $clean;
        $totalsum6 += $clean_repair_wash;
        $totalsum7 += $clean_newlinen;
        $totalsum8 += $total2;
        $pdf->Ln();
      }
    }
  }
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum1, 2)), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum2, 2)), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum3, 2)), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum4, 2)), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum5, 2)), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum6, 2)), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum7, 2)), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($totalsum8, 2)), 1, 1, 'C');
  $scr = (($totalsum4 / $totalsum8) - 1) * 100;
  $pdf->ln(7);
  $pdf->Cell(95, 10, iconv("UTF-8", "TIS-620",  'SCR (Soiled-Clean Ratio)'), 1, 0, 'C');
  $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620",  number_format(abs($scr), 2) . '%'), 1, 1, 'C');
} elseif ($chk == 'month') {
  // 1เดือน
  $day = 1;
  $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
  $datequery =  $year1 . '-' . $date1 . '-';
  $dateshow = '-' . $date1 . '-' . $year1;
  for ($i = 0; $i < $count; $i++) {
    $date[] = $datequery . $day;
    $DateShow[] = $day . $dateshow;
    $day++;
  }
  for ($i = 1; $i <= $count; $i++) {
    $query = "SELECT 
   DIRTY,
   repair_wash,
   NEWLINEN,
   CLEAN,
   CLEAN_repair_wash,
   CLEAN_NEWLINEN,
  a.DocDate,
  b.DocDate,
  c.DocDate,
  d.DocDate,
  e.DocDate,
  f.DocDate
  FROM (
  SELECT
   COALESCE(sum(dirty.Total),'0') AS DIRTY ,
  COALESCE(dirty.DocDate,0) AS DocDate
  FROM
  dirty
  WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.HptCode= '$HptCode'
  AND dirty.isstatus <> 9
  )a,
  (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
  COALESCE(repair_wash.DocDate,0) AS DocDate
  FROM  repair_wash
   
  WHERE DATE (repair_wash.Docdate) = '$date[$i]' AND repair_wash.HptCode= '$HptCode'
  AND repair_wash.FacCode = '$FacCode'
  AND repair_wash.isStatus<>9
  )b,
  (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
  COALESCE(newlinentable.DocDate,0) AS DocDate
  FROM newlinentable
  WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.HptCode= '$HptCode'
  AND newlinentable.isStatus<>9
  )c,
  (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
  COALESCE(clean.DocDate,0) AS DocDate
  FROM clean
  LEFT JOIN department ON department.DepCode = clean.DepCode
		LEFT JOIN site ON department.HptCode = site.HptCode
  WHERE DATE (clean.Docdate) = '$date[$i]' AND (clean.RefDocNo = '' OR  clean.RefDocNo LIKE '%DT%')
      AND clean.IsStatus <>9 AND site.HptCode= '$HptCode' AND clean.FacCode = '$FacCode'
  )d,
  (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
  COALESCE(return_wash.DocDate,0) AS DocDate
  FROM return_wash
  LEFT JOIN department ON department.DepCode = return_wash.DepCode
	LEFT JOIN site ON department.HptCode = site.HptCode
  WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
  AND return_wash.IsStatus  <> 9
  )e,
  (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
  COALESCE(newlinentable.DocDate,0) AS DocDate
  FROM clean
    LEFT JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
    LEFT JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.RefDocNo
  LEFT JOIN department ON department.DepCode = clean.DepCode
	LEFT JOIN site ON department.HptCode = site.HptCode
  WHERE DATE (clean.Docdate) = '$date[$i]'
  AND clean.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
  AND clean.IsStatus  <> 9)
  f";
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $docdate = $Result['DocDate'];
      $dirty = $Result['DIRTY'];
      $repair_wash = $Result['repair_wash'];
      $newlinen = $Result['NEWLINEN'];
      $clean = $Result['CLEAN'];
      $clean_repair_wash = $Result['CLEAN_repair_wash'];
      $clean_newlinen = $Result['CLEAN_NEWLINEN'];
      $total1 = 0;
      $total2 = 0;
      $r = 0;
      if (
        $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
      ) {
        list($day, $month, $year) = explode('-', $DateShow[$i]);
        if ($language == 'th') {
          $year = $year + 543;
          $datesh = $day . "-" . $month . "-" . $year;
        } else {
          $datesh = $day . "-" . $month . "-" . $year;
        }
        $pdf->SetFont('THSarabun', '', 14);
        $total1 = $dirty + $repair_wash + $newlinen;
        $total2 = $clean + $clean_repair_wash + $clean_newlinen;
        $pdf->SetFont('THSarabun', '', 14);
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $datesh), 1, 0, 'C');
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($dirty, 2)), 1, 0, 'C');
        $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($repair_wash, 2)), 1, 0, 'C');
        $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($newlinen, 2)), 1, 0, 'C');
        $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total1, 2)), 1, 0, 'C');
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($clean, 2)), 1, 0, 'C');
        $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($clean_repair_wash, 2)), 1, 0, 'C');
        $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($clean_newlinen, 2)), 1, 0, 'C');
        $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total2, 2)), 1, 0, 'C');
        $totalsum1 += $dirty;
        $totalsum2 += $repair_wash;
        $totalsum3 += $newlinen;
        $totalsum4 += $total1;
        $totalsum5 += $clean;
        $totalsum6 += $clean_repair_wash;
        $totalsum7 += $clean_newlinen;
        $totalsum8 += $total2;
        $pdf->Ln();
      }
    }
  }
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum1, 2)), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum2, 2)), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum3, 2)), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum4, 2)), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum5, 2)), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum6, 2)), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum7, 2)), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($totalsum8, 2)), 1, 1, 'C');
  $scr = (($totalsum4 / $totalsum8) - 1) * 100;
  $pdf->ln(7);
  $pdf->Cell(95, 10, iconv("UTF-8", "TIS-620",  'SCR (Soiled-Clean Ratio)'), 1, 0, 'C');
  $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620",  number_format(abs($scr), 2) . '%'), 1, 1, 'C');
} elseif ($chk == 'monthbetween') {
  list($year, $month, $day) = explode('-', $betweendate2);
  if ($day <> 31) {
    $day = $day + 1;
  }
  $betweendate2 = $year . "-" . $month . "-" . $day;
  $period = new DatePeriod(
    new DateTime($betweendate1),
    new DateInterval('P1D'),
    new DateTime($betweendate2)
  );
  foreach ($period as $key => $value) {
    $date[] = $value->format('Y-m-d');

    $dateshow[] = $value->format('d-m-Y');
  }
  $date[] = $betweendate2;
  $count = sizeof($date);
  for ($i = 0; $i < $count; $i++) {
    $query = "SELECT 
   DIRTY,
  repair_wash,
   NEWLINEN,
   CLEAN,
   CLEAN_repair_wash,
   CLEAN_NEWLINEN,
  a.DocDate,
  b.DocDate,
  c.DocDate,
  d.DocDate,
  e.DocDate,
  f.DocDate
  FROM (
  SELECT
   COALESCE(sum(dirty.Total),'0') AS DIRTY ,
  COALESCE(dirty.DocDate,0) AS DocDate
  FROM
  dirty
  WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.HptCode= '$HptCode'
 AND dirty.isstatus <> 9
  )a,
  (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
  COALESCE(repair_wash.DocDate,0) AS DocDate
  FROM  repair_wash
   
  WHERE DATE (repair_wash.Docdate) = '$date[$i]'
  AND repair_wash.FacCode = '$FacCode' AND repair_wash.HptCode= '$HptCode'
  AND repair_wash.isStatus<>9
  )b,
  (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
  COALESCE(newlinentable.DocDate,0) AS DocDate
  FROM newlinentable
  WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.HptCode= '$HptCode'
  AND newlinentable.isStatus<>9
  )c,
  (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
  COALESCE(clean.DocDate,0) AS DocDate
  FROM clean
  LEFT JOIN department ON department.DepCode = clean.DepCode
		LEFT JOIN site ON department.HptCode = site.HptCode
  WHERE DATE (clean.Docdate) = '$date[$i]' AND (clean.RefDocNo = '' OR clean.RefDocNo LIKE '%DT%')
      AND clean.IsStatus <>9 AND site.HptCode= '$HptCode' AND clean.FacCode = '$FacCode'
  )d,
  (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
  COALESCE(return_wash.DocDate,0) AS DocDate
  FROM return_wash
  LEFT JOIN department ON department.DepCode = return_wash.DepCode
	LEFT JOIN site ON department.HptCode = site.HptCode
  WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
  AND return_wash.IsStatus  <> 9
  )e,
  (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
  COALESCE(newlinentable.DocDate,0) AS DocDate
  FROM clean
    LEFT JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
    LEFT JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.RefDocNo
  LEFT JOIN department ON department.DepCode = clean.DepCode
		LEFT JOIN site ON department.HptCode = site.HptCode
  WHERE DATE (clean.Docdate) = '$date[$i]'
  AND clean.FacCode = '$FacCode' AND site.HptCode= '$HptCode'
  AND clean.IsStatus  <> 9)
  f";
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $docdate = $Result['DocDate'];
      $dirty = $Result['DIRTY'];
      $repair_wash = $Result['repair_wash'];
      $newlinen = $Result['NEWLINEN'];
      $clean = $Result['CLEAN'];
      $clean_repair_wash = $Result['CLEAN_repair_wash'];
      $clean_newlinen = $Result['CLEAN_NEWLINEN'];
      $total1 = 0;
      $total2 = 0;
      $r = 0;
      if (
        $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
      ) {
        list($day, $month, $year) = explode('-', $dateshow[$i]);
        if ($language == 'th') {
          $year = $year + 543;
          $datesh = $day . "-" . $month . "-" . $year;
        } else {
          $datesh = $day . "-" . $month . "-" . $year;
        }
        $pdf->SetFont('THSarabun', '', 14);
        $total1 = $dirty + $repair_wash + $newlinen;
        $total2 = $clean + $clean_repair_wash + $clean_newlinen;
        $pdf->SetFont('THSarabun', '', 14);
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $datesh), 1, 0, 'C');
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($dirty, 2)), 1, 0, 'C');
        $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($repair_wash, 2)), 1, 0, 'C');
        $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($newlinen, 2)), 1, 0, 'C');
        $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total1, 2)), 1, 0, 'C');
        $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", number_format($clean, 2)), 1, 0, 'C');
        $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($clean_repair_wash, 2)), 1, 0, 'C');
        $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($clean_newlinen, 2)), 1, 0, 'C');
        $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total2, 2)), 1, 0, 'C');
        $totalsum1 += $dirty;
        $totalsum2 += $repair_wash;
        $totalsum3 += $newlinen;
        $totalsum4 += $total1;
        $totalsum5 += $clean;
        $totalsum6 += $clean_repair_wash;
        $totalsum7 += $clean_newlinen;
        $totalsum8 += $total2;
        $pdf->Ln();
      }
    }
  }
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum1, 2)), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum2, 2)), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum3, 2)), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum4, 2)), 1, 0, 'C');
  $pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum5, 2)), 1, 0, 'C');
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum6, 2)), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  number_format($totalsum7, 2)), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($totalsum8, 2)), 1, 1, 'C');
  $scr = (($totalsum4 / $totalsum8) - 1) * 100;
  $pdf->ln(7);
  $pdf->Cell(95, 10, iconv("UTF-8", "TIS-620",  'SCR (Soiled-Clean Ratio)'), 1, 0, 'C');
  $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620",  number_format(abs($scr), 2) . '%'), 1, 1, 'C');
}

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Soiled_Clean_Ratio_' . $ddate . '.pdf');
