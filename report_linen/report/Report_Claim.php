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
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
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
    $where_clean =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    $where_rewash =   "WHERE DATE (rewash.Docdate) = DATE('$date1')";
    $where_repair =   "WHERE DATE (repair.Docdate) = DATE('$date1')";
    $where_damage =   "WHERE DATE (damage.Docdate) = DATE('$date1')";
    $where_newlinentable =   "WHERE DATE (newlinentable.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  
  } elseif ($format = 3) {
    $where_clean = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
    $where_rewash = "WHERE  year (rewash.DocDate) LIKE '%$date1%'";
    $where_repair = "WHERE  year (repair.DocDate) LIKE '%$date1%'";
    $where_damage = "WHERE  year (damage.DocDate) LIKE '%$date1%'";
    $where_newlinentable = "WHERE  year (newlinentable.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }

} elseif ($chk == 'between') {
  $where_clean =   "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
  $where_rewash =   "WHERE rewash.Docdate BETWEEN '$date1' AND '$date2'";
  $where_repair =   "WHERE repair.Docdate BETWEEN '$date1' AND '$date2'";
  $where_damage =   "WHERE damage.Docdate BETWEEN '$date1' AND '$date2'";
  $where_newlinentable = "WHERE newlinentable.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where_clean =   "WHERE month (clean.Docdate) = " . $date1;
  $where_rewash =   "WHERE month (rewash.Docdate) = " . $date1;
  $where_repair =   "WHERE month (repair.Docdate) = " . $date1;
  $where_damage =   "WHERE month (damage.Docdate) = " . $date1;
  $where_newlinentable = "WHERE month (newlinentable.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }

} elseif ($chk == 'monthbetween') {
  $where_clean =   "WHERE date(clean.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_rewash =   "WHERE date(rewash.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_repair =   "WHERE date(repair.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_damage =   "WHERE date(damage.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_newlinentable = "WHERE date(newlinentable.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    // Header
    $this->SetFont('THSarabun', 'b', 12);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $loop = 1;
    $rows = 1;
    $new_header = 0;
    $reject = 0;
    $repair = 0;
    $damaged = 0;
    $totalsum1 = '';
    $totalsum2 = '';
    $totalsum3 = '';

    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $clean = $inner_array[$field[1]] + $inner_array[$field[5]];
        if ($clean != 0) {
          $reject = 100 - (($clean - $inner_array[$field[2]]) / $clean * 100);
          $repair = 100 - (($clean - $inner_array[$field[3]]) / $clean * 100);
          $damaged = 100 - (($clean - $inner_array[$field[4]]) / $clean * 100);
        }
        if ($rows > 23) {
          $new_header++;
          if ($new_header % 24 == 1) {
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }

        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $clean), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($reject, 2) . "%"), 1, 0, 'C');
        $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($repair, 2) . "%"), 1, 0, 'C');
        $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", number_format($damaged, 2) . "%"), 1, 1, 'C');
        $totalsum1 += $clean;
        $totalsum2 += $inner_array[$field[2]];
        $totalsum3 += $inner_array[$field[3]];
        $totalsum4 += $inner_array[$field[4]];
        $totalsum5 += $reject;
        $totalsum6 += $repair;
        $totalsum7 += $damaged;
        $rows++;
      }
    }
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $totalsum1), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $totalsum2), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $totalsum3), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $totalsum4), 1, 0, 'C');
    $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($totalsum5, 2) . "%"), 1, 0, 'C');
    $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($totalsum6, 2) . "%"), 1, 0, 'C');
    $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", number_format($totalsum7, 2) . "%"), 1, 1, 'C');
    // Closing line

    $pdf->Cell(array_sum($w), 0, '', 'T');
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
  }
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();
$pdf->AddPage("P", "A4");

if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
if ($DepCode == " ") {
  $depname =  "ทั้งหมด";
  $Sql = "SELECT 
          site.$HptName
          FROM site
          WHERE site.HptCode ='$HptCode';
          ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $HptName = $Result[$HptName];
  }
} else {
  $DepCode = "AND  $DepCode";
  $Sql = "SELECT department.DepName,
      site.$HptName
      FROM department
      INNER JOIN site ON site.HptCode = department.HptCode
      WHERE
      site.HptCode = '$HptCode'
      $DepCode
        ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepName = $Result['DepName'];
    $HptName = $Result[$HptName];
  }
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
// Title
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r2'][$language]), 0, 0, 'C');
// Line break
$pdf->Ln(12);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(70, 5, iconv("UTF-8", "TIS-620",  $array2['site'][$language] . " : " . $HptName . "  " . $array2['department'][$language] . " : "  . $depname . $DepName), 0, 0, 'L');
$pdf->Cell(ุ60, 5, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(10);

$query = "SELECT 
IFNULL(CLEAN, 0) AS CLEAN,
IFNULL(REWASH, 0) AS REWASH,
IFNULL(NEWLINEN, 0) AS NEWLINEN,
IFNULL(REPAIR, 0) AS REPAIR,
IFNULL(DAMAGE, 0) AS DAMAGE,
FacName
FROM (
SELECT
sum(clean_detail.Qty) AS CLEAN,
factory.FacName
FROM
clean
INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
INNER JOIN department ON clean.DepCode = department.DepCode
$where_clean
$DepCode
AND dirty.FacCode=$FacCode
)a,
(SELECT  sum(rewash_detail.Qty) AS REWASH
FROM  clean
INNER JOIN rewash ON clean.RefDocNo=rewash.DocNo
INNER JOIN rewash_detail ON rewash.DocNo=rewash_detail.DocNo
INNER JOIN department ON rewash.DepCode = department.DepCode
$where_rewash
$DepCode
)b,
(SELECT COALESCE(claim.DocNo,'-') AS DocNo 
,COALESCE(SUM(repair_detail.Qty),'0') AS REPAIR
FROM claim
INNER JOIN repair ON claim.DocNo = repair.RefDocNo
INNER JOIN repair_detail ON repair.DocNo=repair_detail.DocNo
INNER JOIN clean ON claim.RefDocNo=clean.DocNo
INNER JOIN dirty ON clean.RefDocNo=dirty.DocNo
INNER JOIN department ON claim.DepCode = department.DepCode
$where_repair
$DepCode
AND dirty.FacCode=$FacCode
)c,
(SELECT  COALESCE(claim.DocNo,'-')  as DocNo
,COALESCE(SUM(damage_detail.Qty),'0') AS DAMAGE
FROM claim
INNER JOIN damage ON claim.DocNo = damage.RefDocNo
INNER JOIN damage_detail ON damage.DocNo=damage_detail.DocNo
INNER JOIN department ON damage.DepCode = department.DepCode
$where_damage
$DepCode
)d,
(SELECT
sum(clean_detail.Qty) AS NEWLINEN
FROM
clean
INNER JOIN clean_detail ON clean_detail.Docno=clean.Docno
INNER JOIN newlinentable ON newlinentable.DocNo = clean.RefDocNo
INNER JOIN newlinentable_detail ON newlinentable_detail.DocNo = newlinentable.DocNo
INNER JOIN department ON newlinentable.DepCode = department.DepCode
$where_newlinentable
$DepCode
)e";

// Number of column
$numfield = 8;
// Field data (Must match with Query)
$field = "FacName,CLEAN,REWASH,REPAIR,DAMAGE,NEWLINEN, , ";
$p1 = $array2['factory'][$language];
$p2 = $array2['receive'][$language];
$p3 = $array2['reject'][$language];
$p4 = $array2['repair'][$language];
$p5 = $array2['damaged'][$language];
$p6 = $array2['receivepercent'][$language];
$p7 = $array2['rejectpercent'][$language];
$p8 = $array2['repairpercent'][$language];

// Table header
$header = array($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8);
// width of column table
$width = array(25, 25, 25, 25, 25, 20, 20, 25);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Claim_' . $ddate . '.pdf');
