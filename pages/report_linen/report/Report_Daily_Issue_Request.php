<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// Date
// $eDate = "2018-06-06";
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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Daily Issue Request"), 0, 0, 'C');
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
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

// Using Coding
$pdf->AddPage("P", "A4");
// SUB Header
$Sql = "SELECT
        shelfcount.DocNo,
        DATE(shelfcount.DocDate) AS DocDate,
        TIME(shelfcount.DocDate) AS DocTime,
        department.DepName
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        WHERE shelfcount.DocNo = 'SCBHQ/1907-00001'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $rows = $Result['rows'];
}
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(5);
$pdf->Cell(60, 10, iconv("UTF-8", "TIS-620", "หน่วยงาน : " . $DeptName), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่ : " . $DocDate), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Time : " . $DocTime), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Cycle : " . $DocNo), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(100, 10, iconv("UTF-8", "TIS-620", "Delivery : "), 0, 0, 'L');
$pdf->Ln(10);


//Header
$pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "No"), 1, 0, 'C');
$pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", "ItemName"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Par Qty"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Shelf Count"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Max"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Issue"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Short"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Order"), 1, 0, 'C');
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Weight"), 1, 1, 'C');

//Table

$query = "SELECT
          item.ItemName,
          shelfcount_detail.ParQty,
          shelfcount_detail.CcQty,
          shelfcount_detail.TotalQty,
          item.Weight
          FROM
          shelfcount
          INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
          INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
          WHERE shelfcount.DocNo = 'SCBHQ/1907-00001'";
$meQuery = mysqli_query($conn, $query);
$i = 1;
$header = 0;
$pdf->SetFont('THSarabun', 'b', 12);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($i > 22) {
    $header++;
    if ($header % 24 == 1) {
      $pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "No"), 1, 0, 'C');
      $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", "ItemName"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Par Qty"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Shelf Count"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Max"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Issue"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Short"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Order"), 1, 0, 'C');
      $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Weight"), 1, 0, 'C');
      $pdf->ln();
    }
  }
  $pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'L');
  $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['ParQty']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['CcQty']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['ParQty']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['TotalQty']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 0, 'L');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['Weight']), 1, 1, 'L');
  $i++;
}

//CHECK FOOTER NEXT PAGE

$Sql = "SELECT
COUNT(shelfcount_detail.ItemCode)as count
FROM
shelfcount_detail";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $count = $Result['count'];
}
$page = (($count - 22) % 24);
if ($page >= 20) {
  $pdf->AddPage("P", "A4");
}

$pdf->Ln();

$pdf->SetFont('THSarabun', 'b', 10);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "Sign...................................................Packing"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date........................................Time............................."), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "Sign...................................................Passenger"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date........................................Time............................."), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "Sign...................................................Receiver"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date........................................Time............................."), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Ln(7);
$pdf->Cell(5);
$image1 = "chb.jpg";
$pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 5);
$pdf->Cell(7);
$pdf->Cell(30, 5, iconv("UTF-8", "TIS-620", "Check"), 0, 0, 'L');
$image2 = "chb.jpg";
$pdf->Image($image2, $pdf->GetX(), $pdf->GetY(), 5);
$pdf->Cell(7);
$pdf->Cell(40, 5, iconv("UTF-8", "TIS-620", "Not Check"), 0, 0, 'L');
$pdf->Ln(7);

// Footer Table
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Daily_Issue_Request_' . $ddate . '.pdf');
