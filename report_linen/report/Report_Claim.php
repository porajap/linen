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
//print_r($data);
if ($chk == 'one') {
  if ($format == 1) {
    $where_clean =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    $where_rewash =   "WHERE DATE (rewash.Docdate) = DATE('$date1')";
    $where_repair =   "WHERE DATE (repair.Docdate) = DATE('$date1')";
    $where_damage =   "WHERE DATE (damage.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header = "วันที่ " . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  } elseif ($format = 3) {
    $where_clean = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
    $where_rewash = "WHERE  year (rewash.DocDate) LIKE '%$date1%'";
    $where_repair = "WHERE  year (repair.DocDate) LIKE '%$date1%'";
    $where_damage = "WHERE  year (damage.DocDate) LIKE '%$date1%'";
    $date_header = "ประจำปี : $date1";
  }
} elseif ($chk == 'between') {
  $where_clean =   "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
  $where_rewash =   "WHERE rewash.Docdate BETWEEN '$date1' AND '$date2'";
  $where_repair =   "WHERE repair.Docdate BETWEEN '$date1' AND '$date2'";
  $where_damage =   "WHERE damage.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header = "วันที่ " . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year) . " ถึง " .
    "วันที่ " . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);
} elseif ($chk == 'month') {
  $where_clean =   "WHERE month (clean.Docdate) = " . $date1;
  $where_rewash =   "WHERE month (rewash.Docdate) = " . $date1;
  $where_repair =   "WHERE month (repair.Docdate) = " . $date1;
  $where_damage =   "WHERE month (damage.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  $date_header = "ประจำเดือน : " . $datetime->getTHmonthFromnum($date1);
} elseif ($chk == 'monthbetween') {
  $where_clean =   "WHERE month(clean.Docdate) BETWEEN $date1 AND $date2";
  $where_rewash =   "WHERE month(rewash.Docdate) BETWEEN $date1 AND $date2";
  $where_repair =   "WHERE month(repair.Docdate) BETWEEN $date1 AND $date2";
  $where_damage =   "WHERE month(damage.Docdate) BETWEEN $date1 AND $date2";
  $datetime = new DatetimeTH();
  $date_header = "ประจำเดือน : " . $datetime->getTHmonthFromnum($date1) . " ถึง " . $datetime->getTHmonthFromnum($date2);
}

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  {
    $datetime = new DatetimeTH();
    $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
    $edate = $eDate[0] . " " . $datetime->getTHmonthFromnum($eDate[1]) . " พ.ศ. " . $datetime->getTHyear($eDate[2]);

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 10);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "วันที่พิมพ์รายงาน " . $printdate), 0, 0, 'R');
      $this->Ln(5);
      // Title
      $this->SetFont('THSarabun', 'b', 20);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "รายงานผ้าชำรุดเสียหาย "), 0, 0, 'C');
      // Line break
      $this->Ln(10);
    } else {
      // Line break
      $this->Ln(7);
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

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
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
        if ($inner_array[$field[1]] != 0) {
          $reject = 100 - (($inner_array[$field[1]] - $inner_array[$field[2]]) / $inner_array[$field[1]] * 100);
          $repair = 100 - (($inner_array[$field[1]] - $inner_array[$field[3]]) / $inner_array[$field[1]] * 100);
          $damaged = 100 - (($inner_array[$field[1]] - $inner_array[$field[4]]) / $inner_array[$field[1]] * 100);
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
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($reject, 2) . "%"), 1, 0, 'C');
        $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($repair, 2) . "%"), 1, 0, 'C');
        $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", number_format($damaged, 2) . "%"), 1, 1, 'C');
        $totalsum1 += $inner_array[$field[1]];
        $totalsum2 += $inner_array[$field[2]];
        $totalsum3 += $inner_array[$field[3]];
        $totalsum4 += $inner_array[$field[4]];
        $totalsum5 += $reject;
        $totalsum6 += $repair;
        $totalsum7 += $damaged;
        $rows++;
      }
    }
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", "รวม"), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $totalsum1), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $totalsum2), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $totalsum3), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $totalsum4), 1, 0, 'C');
    $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($totalsum5, 2) . "%"), 1, 0, 'C');
    $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($totalsum6, 2) . "%"), 1, 0, 'C');
    $this->Cell($w[7], 10, iconv("UTF-8", "TIS-620", number_format($totalsum7, 2) . "%"), 1, 1, 'C');
    // Closing line


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

$Sql = "SELECT department.DepName,
      claim.DocDate,
      site.HptName,
      factory.facname
      FROM claim
      INNER JOIN clean ON clean.docno = claim.refdocno
      INNER JOIN dirty ON dirty.docno = clean.refdocno
      INNER JOIN factory ON factory.faccode = dirty.FacCode
      INNER JOIN department ON claim.DepCode = department.DepCode
      INNER JOIN site ON site.HptCode = department.HptCode
      $where
      AND claim.HptCode = '$HptCode'
      AND claim.DepCode = $DepCode
      AND factory.FacCode = $FacCode
      GROUP BY claim.DocDate ORDER BY claim.DocDate ASC
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DepName = $Result['DepName'];
  $HptName = $Result['HptName'];
}
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(1);
$pdf->Cell(150, 10, iconv("UTF-8", "TIS-620", "Linen Department site : " . $HptName . " " . $DepName), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(10);

$query = "SELECT 
IFNULL(CLEAN, 0) AS CLEAN,
 IFNULL(REWASH, 0) AS REWASH,
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
$where_clean 
AND clean.DepCode = $DepCode
AND dirty.FacCode=$FacCode
)a,
(SELECT  sum(rewash_detail.Qty) AS REWASH
FROM  clean
INNER JOIN rewash ON clean.RefDocNo=rewash.DocNo
INNER JOIN rewash_detail ON rewash.DocNo=rewash_detail.DocNo
$where_rewash 
AND clean.DepCode = $DepCode
)b,
(SELECT COALESCE(claim.DocNo,'-') AS DocNo 
,COALESCE(SUM(repair_detail.Qty),'-') AS REPAIR
FROM claim
INNER JOIN repair ON claim.DocNo = repair.RefDocNo
INNER JOIN repair_detail ON repair.DocNo=repair_detail.DocNo
INNER JOIN clean ON claim.RefDocNo=clean.DocNo
INNER JOIN dirty ON clean.RefDocNo=dirty.DocNo
$where_repair
 AND repair.DepCode = $DepCode
AND dirty.FacCode=$FacCode)c,
(SELECT  COALESCE(claim.DocNo,'-')  as DocNo
,COALESCE(SUM(damage_detail.Qty),'-') AS DAMAGE
FROM claim
INNER JOIN damage ON claim.DocNo = damage.RefDocNo
INNER JOIN damage_detail ON damage.DocNo=damage_detail.DocNo
INNER JOIN clean ON claim.RefDocNo=clean.DocNo
INNER JOIN dirty ON clean.RefDocNo=dirty.DocNo
$where_damage 
AND damage.DepCode = $DepCode
AND dirty.FacCode=$FacCode)d




            
          ";
// var_dump($query); die;
// Number of column
$numfield = 8;
// Field data (Must match with Query)
$field = "FacName,CLEAN,REWASH,REPAIR,DAMAGE, , , ";
// Table header
$header = array('โรงซัก', 'ผ้าที่รับ(จำนวน)', 'Reject(จำนวน)', 'Repair(จำนวน)', 'Damaged(จำนวน)', 'Reject(%)', 'Repair(%)', 'Damaged(%)');
// width of column table
$width = array(25, 25, 25, 25, 25, 20, 20, 25);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Claim_' . $ddate . '.pdf');
/**FROM (SELECT  sum(clean_detail.Qty) AS CLEAN,
      claim.DocNo,factory.FacName
  FROM  claim,clean,clean_detail,dirty,factory
  WHERE  claim.RefDocNo=clean.DocNo
  AND  clean.DocNo=clean_detail.DocNo
  AND dirty.DocNo=clean.RefDocNo
  AND factory.FacCode=dirty.FacCode
  AND claim.HptCode = '$HptCode'
  AND claim.DepCode = '$DepCode'
  AND factory.FacCode = $FacCode
  GROUP BY claim.DocNo) a, */


//   SELECT a.DocNo,
//   IFNULL(CLEAN, 0) AS CLEAN,
//     IFNULL(REWASH, 0) AS REWASH,
//     IFNULL(REPAIR, 0) AS REPAIR,
//     IFNULL(DAMAGE, 0) AS DAMAGE,
//     FacName
//     FROM (SELECT  sum(clean_detail.Qty) AS CLEAN,
//       claim.DocNo,factory.FacName
//   FROM  claim,clean,clean_detail,dirty,factory
//   WHERE  
//    clean.DocNo=clean_detail.DocNo
//   AND dirty.DocNo=clean.RefDocNo
//   AND factory.FacCode=dirty.FacCode
//   AND claim.HptCode = '$HptCode'
//   AND clean.DepCode = '$DepCode'
//   AND factory.FacCode = 1
//   GROUP BY claim.DocNo) a,
//   (SELECT  sum(rewash_detail.Qty1) AS REWASH
//   FROM  clean
// LEFT  JOIN rewash ON clean.RefDocNo=rewash.DocNo
// LEFT  JOIN rewash_detail ON rewash.DocNo=rewash_detail.DocNo
// WHERE
//    clean.HptCode = '$HptCode'
//   GROUP BY clean.DocNo) b,
//   (
//   SELECT  claim.DocNo,SUM(repair_detail.Qty) AS REPAIR
//   FROM claim
//   LEFT JOIN repair ON claim.DocNo = repair.RefDocNo
//   LEFT JOIN repair_detail ON repair.DocNo=repair_detail.DocNo
//   WHERE  claim.HptCode = '$HptCode'
//   AND claim.DepCode = '$DepCode'
//   GROUP BY claim.DocNo
//   ) c,
//   (SELECT  claim.DocNo,SUM(damage_detail.Qty) AS DAMAGE
//   FROM claim
//   LEFT JOIN damage ON claim.DocNo = damage.RefDocNo
//   LEFT JOIN damage_detail ON damage.DocNo=damage_detail.DocNo
//   WHERE  
//    claim.HptCode = '$HptCode'
//   AND claim.DepCode = '$DepCode'
//   GROUP BY claim.DocNo) d
