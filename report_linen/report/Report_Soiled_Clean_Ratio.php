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
    $where =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header = "วันที่ " . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  } elseif ($format = 3) {
    $where = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
    $date_header = "ประจำปี : $date1";
  }
} elseif ($chk == 'between') {
  $where =   "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header = "วันที่ " . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year) . " ถึง " .
    "วันที่ " . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);
} elseif ($chk == 'month') {
  $where =   "WHERE month (clean.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  $date_header = "ประจำเดือน : " . $datetime->getTHmonthFromnum($date1);
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(clean.Docdate) BETWEEN $date1 AND $date2";
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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Soiled Clean Ratio (SCR) "), 0, 0, 'C');
      // Line break
      $this->Ln(7);
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

  // function setTable($pdf, $header, $data, $width, $numfield, $field)
  // {
  //   $field = explode(",", $field);
  //   // Column widths
  //   $w = $width;
  //   // Header
  //   $this->SetFont('THSarabun', 'b', 10);
  //   for ($i = 0; $i < count($header); $i++)
  //     $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
  //   $this->Ln();

  //   // set Data Details
  //   $count = 0;
  //   $rows = 1;
  //   if (is_array($data)) {
  //     foreach ($data as $data => $inner_array) {
  //       if ($rows > 23) {
  //         $count++;
  //         if ($count % 25 == 1) {
  //           $this->SetFont('THSarabun', 'b', 10);
  //           for ($i = 0; $i < count($header); $i++)
  //             $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
  //           $this->Ln();
  //         }
  //       }
  //       if ($inner_array[$field[3]] == null) {
  //         $inner_array[$field[3]] = 0;
  //         $inner_array[$field[3]]  = $inner_array[$field[2]] - $inner_array[$field[3]];
  //       } elseif ($inner_array[$field[3]] == null) {
  //         $inner_array[$field[3]]  = $inner_array[$field[2]] - $inner_array[$field[3]];
  //       }
  //       $this->SetFont('THSarabun', '', 12);
  //       $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
  //       $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
  //       $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
  //       $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
  //       $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", abs($inner_array[$field[4]]) . "%"), 1, 0, 'C');
        
  //       $this->Ln();
  //       $rows++;
  //     }
  //   }
  //   // Closing line
  //   $pdf->Cell(array_sum($w), 0, '', 'T');
  // }
}
//39 42
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
        DATE(clean.DocDate) AS DocDate
        FROM
        clean
				INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        INNER JOIN department ON department.depcode = clean.depcode
        $where
        AND factory.FacCode= $FacCode
        AND clean.depcode= $DepCode";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {

  $factory = $Result['FacName'];
}
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(1);
$pdf->Cell(145, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(10);
$pdf->SetFont('THSarabun', 'b', 10);
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", "หมายเลขเอกสารสกปรก"), 1, 0, 'C');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", "หมายเลขเอกสารสะอาด"), 1, 0, 'C');
  $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "ส่งผ้าเปื้อน Weight (Kg)"), 1, 0, 'C');
  $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "รับผ้าสะอาด Weight (Kg)"), 1, 0, 'C');
  $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "ส่วนต่าง (%)"), 1, 0, 'C');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", "หมายเลขเอกสารซักใหม่"), 1, 0, 'C');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", "จำนวนผ้าซักใหม่  (Kg)"), 1, 1, 'C');

$query = "SELECT department.DepName,
COALESCE(clean.DocNo,'-') AS clean_DocNo,
COALESCE(dirty.DocNo,'-') AS dirty_DocNo,
IFNULL(dirty.Total,0) AS Total1,
IFNULL(clean.Total,0) AS Total2,
item.weight,
CASE
WHEN clean.Total > dirty.Total THEN COALESCE(ROUND( (-1*((clean.Total - dirty.Total ) / clean.Total) * 100), 2),'-'  )
WHEN dirty.Total > clean.Total  THEN COALESCE(ROUND( (-1*((dirty.Total - clean.Total ) / dirty.Total) * 100), 2),'-'  )
WHEN dirty.Total = clean.Total THEN dirty.Total - clean.Total
END AS Precent 
FROM clean 
INNER JOIN dirty ON clean.RefDocNo=dirty.DocNo
INNER JOIN clean_detail ON clean.DocNo=clean_detail.DocNo
INNER JOIN item ON item.ItemCode=clean_detail.ItemCode
INNER JOIN department ON department.DepCode=dirty.DepCode
INNER JOIN factory ON factory.FacCode= dirty.FacCode
$where
AND factory.FacCode=  $FacCode
AND clean.depcode= $DepCode
group by clean.DocNo,dirty.DocNo ";
          $Sql1 = "SELECT
          COALESCE(rewash.DocNo,'-') AS rewash_DocNo,
          COALESCE(sum(item.weight *rewash_detail.qty1 ),'-') AS TOTAL 
          FROM rewash
          INNER JOIN rewash_detail ON rewash.DocNo = rewash_detail.DocNo
          INNER JOIN clean ON clean.RefDocNo = rewash.DocNo
          INNER JOIN item ON rewash_detail.ItemCode = Item.ItemCode
        $where
        AND clean.depcode= $DepCode 
        group by rewash.DocNo";
        $count=0;
  $meQuery = mysqli_query($conn, $Sql1);
 
  for ($i = 0; $i < count($header); $i++){
  $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
$this->Ln();
  }
  
  $meQuery1 = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery1)) {

  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", $Result['dirty_DocNo']), 1, 0, 'C');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", $Result['clean_DocNo']), 1, 0, 'C');
  $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $Result['Total1']), 1, 0, 'C');
  $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $Result['Total2']), 1, 0, 'C');
  $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", ABS($Result['Precent'])."%"), 1, 0, 'C');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
  $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');

  }
  $pdf->SetY(42);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
  
    $rewash_DocNo = $Result['rewash_DocNo'];
    $TOTAL = $Result['TOTAL'];
    
    $pdf->Cell(140, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", $rewash_DocNo), 1, 0, 'C');
    $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", $TOTAL), 1, 1, 'C');
    $count++;
  }
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "dirty_DocNo,clean_DocNo,Total1,Total2,Precent,rewash_DocNo,TOTAL";
// Table header"
$header = array('หมายเลขเอกสารสกปรก', 'หมายเลขเอกสารสะอาด', 'ส่งผ้าเปื้อน Weight (Kg)', 'รับผ้าสะอาด Weight (Kg)', 'ส่วนต่าง (%)', 'หมายเลขเอกสารซักใหม่', 'จำนวนผ้าซักใหม่  (Kg)');
// width of column table
$width = array(25, 25, 30, 30, 30, 25, 25);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 12);
// $pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Soiled_Clean_Ratio_' . $ddate . '.pdf');
