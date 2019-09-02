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
$where = '';
//print_r($data);
if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header = "วันที่ " . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  } elseif ($format = 3) {
    $where = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
    $date_header = "ประจำปี : $date1";
  }
} elseif ($chk == 'between') {
  $where =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header = "วันที่ " . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year) . " ถึง " .
    "วันที่ " . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);
} elseif ($chk == 'month') {
  $where =   "WHERE month (dirty.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  $date_header = "ประจำเดือน : " . $datetime->getTHmonthFromnum($date1);
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE MONTH(dirty.DocDate) BETWEEN '$date1' AND '$date2'";
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
      $this->Ln(10);
      // Title

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
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 1;
    $rows = 0;

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $total = $inner_array[$field[2]] * $inner_array[$field[3]];
        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count . $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]], 2)), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total, 2)), 1, 0, 'C');
        $this->Ln();
        $count++;
        $totalsum += $total;
      }
    }
    $footer = array('', '', '', 'รวม', number_format($totalsum, 2));
    $pdf->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($footer); $i++) {
      $pdf->Cell($width[$i], 10, iconv("UTF-8", "TIS-620", $footer[$i] . " "), 1, 0, 'R');
    }


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

$Sql = "SELECT
        shelfcount.DocNo,
        department.DepName
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
}
$pdf->SetFont('THSarabun', 'b', 16);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "Summary Report from " . $date_header), 0, 0, 'C');
// Line break
$pdf->Ln(7);

$pdf->SetFont('THSarabun', 'b', 16);
$pdf->Cell(15);
$pdf->Cell(150, 10, iconv("UTF-8", "TIS-620", "แผนก : " . $DeptName), 0, 0, 'C');
$pdf->SetMargins(15, 0, 0);
$pdf->Ln(12);

$query = "SELECT
          item.ItemName,
          shelfcount_detail.ParQty,
          shelfcount_detail.ccqty,
          item.cusPrice
          FROM
          shelfcount_detail
          INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
          INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "ItemName,ParQty,ccqty,cusPrice,";
// Table header
$header = array('Item Name', 'PAR Qty', 'Qty', 'PRICE', 'Total');
// width of column table
$width = array(65, 30, 30, 30, 30);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum



$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Summary_' . $ddate . '.pdf');
