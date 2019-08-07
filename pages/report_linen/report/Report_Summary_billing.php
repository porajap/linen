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
$Sql = "SELECT
        COUNT(billwash.date)as count,
        billwash.docdate,
        site.HptName
        FROM
        summary_billing
        INNER JOIN site ON billwash.Hptcode=site.Hptcode
        WHERE YEAR(billwash.docdate)='2019'  AND MONTH(billwash.docdate) = '7'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = date('d/m/Y', strtotime($Result['date']));
  $DocDate = explode("/", $DocDate);
  $DocDate = $DocDate[2] . '-' . $DocDate[1] . '-' . $DocDate[0];
  $printdate = $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
  $DocDate = $datetime->getTHmonthFromnum($DocDate[1]) . " พ.ศ. " . $datetime->getTHyear($DocDate[2]);
  $Name = $Result['HptName'];
  $count = $Result['count'];
}

$footer_nextpage = (($count - 23) % 25);
// Using Coding
$pdf->AddPage("P", "A4");
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "สรุปค่าบริการ รับ-ส่งผ้า ประจำเดือน " . $printdate), 0, 0, 'C');
$pdf->Ln(7);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $Name), 0, 0, 'C');
$pdf->Ln(15);

$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "ลำดับ"), 1, 0, 'C');
$pdf->Cell(58, 10, iconv("UTF-8", "TIS-620", "รายการ"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "หน่วย"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "จำนวน"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "ราคาต่อหน่วย"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "จำนวนเงิน"), 1, 1, 'C');

$Sql = "SELECT
        summary_billing.item_bill,
        summary_billing.unit,
        summary_billing.number,
        summary_billing.price_per_unit,
        (summary_billing.price_per_unit* summary_billing.number)as total
        FROM
        summary_billing
        WHERE YEAR(summary_billing.date)='2019'  AND MONTH(summary_billing.date) = '7'";
$meQuery = mysqli_query($conn, $Sql);
$i = 1;
$rows = 1;
$header = 0;
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($rows > 22) {
    $header++;
    if ($header % 24 == 1) {
      $pdf->SetFont('THSarabun', 'b', 11);
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "ลำดับ"), 1, 0, 'C');
      $pdf->Cell(58, 10, iconv("UTF-8", "TIS-620", "รายการ"), 1, 0, 'C');
      $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "หน่วย"), 1, 0, 'C');
      $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "จำนวน"), 1, 0, 'C');
      $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "ราคาต่อหน่วย"), 1, 0, 'C');
      $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "จำนวนเงิน"), 1, 0, 'C');
      $pdf->ln();
    }
  }
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'C');
  $pdf->Cell(58, 10, iconv("UTF-8", "TIS-620", $Result['item_bill']), 1, 0, 'C');
  $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", $Result['unit']), 1, 0, 'C');
  $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", $Result['number']), 1, 0, 'C');
  $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", $Result['price_per_unit']), 1, 0, 'C');
  $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", $Result['total']), 1, 1, 'C');
  $total = $total + $Result['total'];
  $i++;
  $rows++;
}/*
if($footer_nextpage>=16){
$pdf->AddPage("P","A4");
}*/

$total_all = $tax + $total;
$pdf->Cell(78, 30, iconv("UTF-8", "TIS-620", "จำนวนเงิน"), 1, 0, 'C');
$pdf->Cell(84, 10, iconv("UTF-8", "TIS-620", "ยอดเงินก่อนภาษีมูลค่าเพิ่ม"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", number_format($total, 2)), 1, 1, 'C');
$pdf->Cell(78, 30, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(84, 10, iconv("UTF-8", "TIS-620", "ภาษีมูลค่าเพิ่ม 7%"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", number_format($tax, 2)), 1, 1, 'C');
$pdf->Cell(78, 30, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(84, 10, iconv("UTF-8", "TIS-620", "รวมเงินสุทธิ (รวมภาษีมูลค่าเพิ่ม)"), 1, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", number_format($total_all, 2)), 1, 1, 'C');
$pdf->ln(10);

// Footer Table

$pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "สวนของเจ้าหน้าที่ บริษัท เนชั่นแนล เฮลท์แคร์ ซิสเท็มส์ จำกัด"), 0, 0, 'C');
$pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "สวนของเจ้าหน้าที่ บริษัท เนชั่นแนล เฮลท์แคร์ ซิสเท็มส์ จำกัด"), 0, 1, 'C');
$pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(28, 0, iconv("UTF-8", "TIS-620", "ผู้ตรวจสอบ"), 0, 0, 'C');
$pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(28, 0, iconv("UTF-8", "TIS-620", "ผู้ตรวจสอบ"), 0, 1, 'C');
$pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(28, 15, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 0, 'C');
$pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
$pdf->Cell(28, 15, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 1, 'C');




$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
