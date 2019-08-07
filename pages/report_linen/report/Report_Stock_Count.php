<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// Date
// $eDate = "2018-06-06";
$eDate = $_GET['eDate'];
$eDate = explode("/", $eDate);
$eDate = $eDate[2] . '-' . $eDate[1] . '-' . $eDate[0];
$dept = $_GET['dept'];

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
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "รายงานสต๊อกคงคลัง"), 0, 0, 'C');
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
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

// Using Coding
$pdf->AddPage("P", "A4");
// Sub Head
$Sql = "SELECT
        item_stock.ExpireDate,
        department.DepName
        FROM
        item_stock
        INNER JOIN department ON item_stock.HptCode=department.HptCode
        WHERE DATE(item_stock.ExpireDate) = DATE('$date1')
        AND item_stock.HptCode = $HptCode ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = date('d/m/Y', strtotime($Result['ExpireDate']));
  $DocDate = explode("/", $DocDate);
  $DocDate = $DocDate[2] . '-' . $DocDate[1] . '-' . $DocDate[0];
  $printdate = $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
  $DocDate = $datetime->getTHmonthFromnum($DocDate[1]) . " พ.ศ. " . $datetime->getTHyear($DocDate[2]);


  $depname = $Result['DepName'];
}

$pdf->SetFont('THSarabun', 'b', 15);
$pdf->Cell(100, 10, iconv("UTF-8", "TIS-620", "หน่วยงาน " . $depname), 0, 0, 'L');
$pdf->Cell(90, 10, iconv("UTF-8", "TIS-620", "ประจำเดือน " . $printdate), 0, 0, 'R');
$pdf->Ln(10);
//head field
$pdf->SetFont('THSarabun', 'b', 15);
$pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "ลำดับ"), 1, 0, 'C');
$pdf->Cell(100, 10, iconv("UTF-8", "TIS-620", "รายการ"), 1, 0, 'C');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "หน่วย"), 1, 0, 'C');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "จำนวน"), 1, 1, 'C');

// BODY TABLE
$i = 1;
$header = 0;

$Sql = "SELECT
        item.itemName,
        item_stock.ParQty,
        item_stock.TotalQty
        FROM
        item_stock
        INNER JOIN department ON item_stock.HptCode=department.HptCode
        INNER JOIN item on item.ItemCode=item_stock.ItemCode
        WHERE DATE(item_stock.ExpireDate) = DATE('$date1')
        AND item_stock.HptCode = '$HptCode' ";

$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($i > 23) {
    $header++;
    if ($header % 24 == 1) {
      $pdf->SetFont('THSarabun', 'b', 14);
      $pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "ลำดับ"), 1, 0, 'C');
      $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620", "รายการ"), 1, 0, 'C');
      $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "หน่วย"), 1, 0, 'C');
      $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "จำนวน"), 1, 1, 'C');
    }
  }
  $pdf->SetFont('THSarabun', '', 15);
  $DocDate = date('d/m/Y', strtotime($Result['ExpireDate']));
  $depcode = $Result['Depcode'];
  $pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'C');
  $pdf->Cell(100, 10, iconv("UTF-8", "TIS-620", $Result['itemName']), 1, 0, 'L');
  $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $Result['ParQty']), 1, 0, 'R');
  $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $Result['TotalQty']), 1, 1, 'R');
  $i++;
}

// Footer Table
$pdf->ln(15);
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(20, 10, "", 0, 0, 'C');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Sign.......................................................ผู้ตรจสอบ"), 0, 0, 'C');
$pdf->Cell(80, 10, "", 0, 0, 'C');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date.......................................................time"), 0, 0, 'C');

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
