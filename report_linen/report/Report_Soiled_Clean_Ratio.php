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
    $where_clean =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    $where_dirty =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    $where_rewash =   "WHERE DATE (rewash.Docdate) = DATE('$date1')";
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
    $where_dirty = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
    $where_rewash = "WHERE  year (rewash.DocDate) LIKE '%$date1%'";
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
  $where_dirty =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
  $where_rewash =   "WHERE rewash.Docdate BETWEEN '$date1' AND '$date2'";
  $where_newlinentable =   "WHERE newlinentable.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where_dirty =   "WHERE month (dirty.Docdate) = " . $date1;
  $where_rewash =   "WHERE month (rewash.Docdate) = " . $date1;
  $where_newlinentable =   "WHERE month (newlinentable.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where_clean =   "WHERE MONTH(clean.DocDate) BETWEEN '$date1' AND '$date2'";
  $where_dirty =   "WHERE MONTH(dirty.DocDate) BETWEEN '$date1' AND '$date2'";
  $where_rewash =   "WHERE MONTH(rewash.DocDate) BETWEEN '$date1' AND '$date2'";
  $where_newlinentable =   "WHERE MONTH(newlinentable.DocDate) BETWEEN '$date1' AND '$date2'";
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
    $this->Ln(1);
    // Header
    $this->SetFont('THSarabun', 'b', 14);

    $this->Cell($w[1], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
    $this->Cell(87.5, 10, iconv("UTF-8", "TIS-620", $array2['soild'][$language]), 1, 0, 'C');
    $this->Cell(87.5, 10, iconv("UTF-8", "TIS-620",  $array2['clean1'][$language]), 1, 1, 'C');
    $this->SetFont('THSarabun', 'b', 11.5);
    $this->Cell(19.875, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['dirty'][$language]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['repair_dirty'][$language]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['newlinen'][$language]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['Totaldirty'][$language]), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['clean'][$language]), 1, 0, 'C');
  //   if($language== 'th'){
  //   $size = 9;
  // }else {
  //   $size = 12;
  // }
    $this->SetFont('THSarabun', 'b', $size);
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['receive_dirty'][$language]), 1, 0, 'C');
    if($language== 'th'){
      $size = 12;
    }else {
      $size = 10;
    }
    $this->SetFont('THSarabun', 'b', $size);
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['receive_newlinen'][$language]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['Totalclean'][$language]), 1, 1, 'C');
    // set Data Details

    $total1 = 0;
    $total2 = 0;
    $r = 0;

    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $total1 = $inner_array[$field[1]] + $inner_array[$field[2]] + $inner_array[$field[3]];
        $total2 = $inner_array[$field[4]] + $inner_array[$field[5]] + $inner_array[$field[6]];
        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $total1), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[5]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[6]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $total2), 1, 0, 'C');
        $totalsum1+=$inner_array[$field[1]];
        $totalsum2+=$inner_array[$field[2]];
        $totalsum3+=$inner_array[$field[3]];
        $totalsum4+= $total1;
        $totalsum5+=$inner_array[$field[4]];
        $totalsum6+=$inner_array[$field[5]];
        $totalsum7+=$inner_array[$field[6]];
        $totalsum8+=$total2;
        $this->Ln();
      }
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  $totalsum1), 1, 0, 'C');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  $totalsum2), 1, 0, 'C');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  $totalsum3), 1, 0, 'C');
      $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  $totalsum4), 1, 0, 'C');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  $totalsum5), 1, 0, 'C');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620",  $totalsum6), 1, 0, 'C');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620",  $totalsum7), 1, 0, 'C');
      $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620",  $totalsum8), 1, 0, 'C');
    }
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
        factory.facname,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        INNER JOIN department ON department.depcode =dirty.depcode
        INNER JOIN site ON site.hptcode =department.hptcode
       
        ";

$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $facname = $Result['facname'];
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
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r8'][$language]), 0, 0, 'C');
// Line break
$pdf->Ln(3);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", $array2['factory'][$language] . " : " . $facname), 0, 0, 'L');
$pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);

$query = "SELECT 
IFNULL(DIRTY, 0) AS DIRTY,
IFNULL(REWASH, 0) AS REWASH,
IFNULL(NEWLINEN, 0) AS NEWLINEN,
IFNULL(CLEAN, 0) AS CLEAN,
IFNULL(CLEAN_REWASH, 0) AS CLEAN_REWASH,
IFNULL(CLEAN_NEWLINEN, 0) AS CLEAN_NEWLINEN,
a.DocDate,
b.DocDate,
c.DocDate,
d.DocDate,
e.DocDate,
f.DocDate
FROM (
SELECT
COALESCE(sum(dirty.Total),0) AS DIRTY ,
COALESCE(dirty.DocDate,0) AS DocDate
FROM
dirty
$where_dirty AND dirty.faccode= $FacCode
GROUP BY DATE(dirty.DocDate)
)a,
(SELECT  COALESCE(sum(rewash.Total),0) AS REWASH,
COALESCE(rewash.DocDate,0) AS DocDate
FROM  rewash
LEFT JOIN clean ON rewash.DocNo=clean.RefDocNo
$where_rewash 
AND rewash.FacCode = $FacCode
GROUP BY DATE(rewash.DocDate)LIMIT 1
)b,
(SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
COALESCE(newlinentable.DocDate,0) AS DocDate
FROM newlinentable
$where_newlinentable AND newlinentable.FacCode = $FacCode
GROUP BY DATE(newlinentable.DocDate)LIMIT 1
)c,
(SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
COALESCE(clean.DocDate,0) AS DocDate
FROM clean
$where_clean AND clean.RefDocNo <> (
  SELECT
    clean.RefDocNo
  FROM
    clean
  INNER JOIN rewash ON rewash.Docno = clean.RefDocNo
)
GROUP BY DATE(clean.DocDate)LIMIT 1
)d,
(SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_REWASH,
COALESCE(rewash.DocDate,0) AS DocDate
FROM clean
LEFT JOIN rewash ON rewash.DocNo=clean.RefDocNo
$where_clean
AND rewash.FacCode = $FacCode
GROUP BY DATE(rewash.DocDate)LIMIT 1
)e,
(SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
COALESCE(newlinentable.DocDate,0) AS DocDate
FROM clean
LEFT JOIN newlinentable ON newlinentable.DocNo=clean.RefDocNo
$where_clean 
AND newlinentable.FacCode = $FacCode
GROUP BY DATE(newlinentable.DocDate)LIMIT 1)
f

";
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = "DocDate,DIRTY,REWASH,NEWLINEN,CLEAN,CLEAN_REWASH,CLEAN_NEWLINEN";
// Table header
$header = array($array2['docdate'][$language], 'ditry', 'rewash', 'new', 'total', 'clean', 'rewash', 'new', 'total');
// width of column table
$width = array(15, 19.875, 24.875, 21.875, 20.875, 19.875, 24.875, 21.875,20.875);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Dirty_Linen_Weight' . $ddate . '.pdf');
// SELECT 
// IFNULL(DIRTY, 0) AS DIRTY,
// IFNULL(REWASH, 0) AS REWASH,
// IFNULL(NEWLINEN, 0) AS NEWLINEN,
// IFNULL(CLEAN, 0) AS CLEAN,
// IFNULL(CLEAN_REWASH, 0) AS CLEAN_REWASH,
// IFNULL(CLEAN_NEWLINEN, 0) AS CLEAN_NEWLINEN,
// a.DocDate,
// b.DocDate,
// c.DocDate,
// d.DocDate,
// e.DocDate,
// f.DocDate
// FROM (
// SELECT
// COALESCE(sum(dirty_detail.Qty),0) AS DIRTY ,
// COALESCE(dirty.DocDate,0) AS DocDate
// FROM
// dirty_detail
// LEFT JOIN dirty ON dirty_detail.DocNo = dirty.DocNo
// GROUP BY DATE(dirty.DocDate)
// UNION
// SELECT
// COALESCE(sum(dirty_detail.Qty),0) AS DIRTY ,
// COALESCE(dirty.DocDate,0) AS DocDate
// FROM
// dirty_detail
// RIGHT JOIN dirty ON dirty_detail.DocNo = dirty.DocNo
// GROUP BY DATE(dirty.DocDate)
// LIMIT 1 
// )a,
// (SELECT  COALESCE(sum(rewash_detail.Qty),0) AS REWASH,
// COALESCE(rewash.DocDate,0) AS DocDate
// FROM  rewash_detail
// LEFT JOIN rewash ON rewash_detail.DocNo=rewash.DocNo
// LEFT JOIN clean ON rewash.DocNo=clean.RefDocNo
// LEFT JOIN dirty ON clean.DocNo=dirty.RefDocNo
// GROUP BY DATE(rewash.DocDate)
// UNION
// SELECT  COALESCE(sum(rewash_detail.Qty),0) AS REWASH,
// COALESCE(rewash.DocDate,0) AS DocDate
// FROM  rewash_detail
// RIGHT JOIN rewash ON rewash_detail.DocNo=rewash.DocNo
// RIGHT JOIN clean ON rewash.DocNo=clean.RefDocNo
// RIGHT JOIN dirty ON clean.DocNo=dirty.RefDocNo
// GROUP BY DATE(rewash.DocDate)LIMIT 1 
// )b,
// (SELECT COALESCE(SUM(newlinentable_detail.Qty),'0') AS NEWLINEN ,
// COALESCE(newlinentable.DocDate,0) AS DocDate
// FROM newlinentable_detail
// LEFT JOIN newlinentable ON newlinentable_detail.DocNo = newlinentable.DocNo
// GROUP BY DATE(newlinentable.DocDate)
// UNION
// SELECT COALESCE(SUM(newlinentable_detail.Qty),'0') AS NEWLINEN ,
// COALESCE(newlinentable.DocDate,0) AS DocDate
// FROM newlinentable_detail
// RIGHT JOIN newlinentable ON newlinentable_detail.DocNo = newlinentable.DocNo
// GROUP BY DATE(newlinentable.DocDate)LIMIT 1 
// )c,
// (SELECT  COALESCE(SUM(clean_detail.Qty),'0') AS CLEAN , 
// COALESCE(clean.DocDate,0) AS DocDate
// FROM clean_detail
// LEFT JOIN clean ON clean_detail.DocNo=clean.DocNo
// GROUP BY DATE(clean.DocDate)
// UNION
// SELECT  COALESCE(SUM(clean_detail.Qty),'0') AS CLEAN , 
// COALESCE(clean.DocDate,0) AS DocDate
// FROM clean_detail
// RIGHT JOIN clean ON clean_detail.DocNo=clean.DocNo
// GROUP BY DATE(clean.DocDate)LIMIT 1 
// )d,
// (SELECT  COALESCE(SUM(rewash_detail.Qty),'0') AS CLEAN_REWASH,
// COALESCE(rewash.DocDate,0) AS DocDate
// FROM rewash_detail
// LEFT JOIN rewash ON rewash_detail.DocNo=rewash.DocNo
// LEFT JOIN clean ON rewash.RefDocNo=clean.DocNo
// GROUP BY DATE(rewash.DocDate)
// UNION
// SELECT  COALESCE(SUM(rewash_detail.Qty),'0') AS CLEAN_REWASH,
// COALESCE(rewash.DocDate,0) AS DocDate
// FROM rewash_detail
// RIGHT JOIN rewash ON rewash_detail.DocNo=rewash.DocNo
// RIGHT JOIN clean ON rewash.RefDocNo=clean.DocNo
// WHERE clean.RefDocNo <> (
//   SELECT
//     clean.RefDocNo
//   FROM
//     clean
//   INNER JOIN rewash ON rewash.Docno = clean.RefDocNo
// )
// GROUP BY DATE(rewash.DocDate)LIMIT 1 
// )e,
// (SELECT  COALESCE(SUM(newlinentable_detail.Qty),'0') AS CLEAN_NEWLINEN,
// COALESCE(newlinentable.DocDate,0) AS DocDate
// FROM newlinentable_detail
// LEFT JOIN newlinentable ON newlinentable_detail.DocNo=newlinentable.DocNo
// LEFT JOIN clean ON clean.RefDocNo=newlinentable.DocNo
// GROUP BY DATE(newlinentable.DocDate)
// UNION
// SELECT  COALESCE(SUM(newlinentable_detail.Qty),'0') AS CLEAN_NEWLINEN,
// COALESCE(newlinentable.DocDate,0) AS DocDate
// FROM newlinentable_detail
// RIGHT JOIN newlinentable ON newlinentable_detail.DocNo=newlinentable.DocNo
// RIGHT JOIN clean ON clean.RefDocNo=newlinentable.DocNo
// GROUP BY DATE(newlinentable.DocDate)LIMIT 1 
// )f 
