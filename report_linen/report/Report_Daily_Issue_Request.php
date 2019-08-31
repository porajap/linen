<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data=$_SESSION['data_send'];
$HptCode=$data['HptCode'];
$FacCode=$data['FacCode'];
$date1=$data['date1'];
$date2=$data['date2'];
$chk=$data['chk'];
$year=$data['year'];
$depcode=$data['DepCode'];
$format=$data['Format'];
$cycle2=$data['cycle'];
$where='';

//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (shelfcount.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (shelfcount.Docdate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE shelfcount.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (shelfcount.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(shelfcount.Docdate) BETWEEN $date1 AND $date2";
  $datetime = new DatetimeTH();
  $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1)." ถึง ".$datetime->getTHmonthFromnum($date2) ;
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
        department.DepName,
        shelfcount.CycleTime,
				TIME(shelfcount.DvStartTime) as  DvStartTime
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        $where
        AND department.DepCode = $depcode
        AND shelfcount.CycleTime = $cycle2
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $Delivery = $Result['DvStartTime'];
  $CycleTime = $Result['CycleTime'];
}
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(5);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "หน่วยงาน : " . $DeptName), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Cycle : " . $CycleTime), 0, 0, 'L');
$pdf->Cell(115, 10, iconv("UTF-8", "TIS-620", $date_header ), 0, 0, 'R');

$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(110, 10, iconv("UTF-8", "TIS-620", "Delivery : ".$Delivery), 0, 0, 'L');
$pdf->Cell(75, 10, iconv("UTF-8", "TIS-620", "Time : " . $DocTime), 0, 0, 'R');
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
          IFNULL(shelfcount_detail.ParQty,0) AS ParQty,
          IFNULL(shelfcount_detail.CcQty,0) AS CcQty,
          IFNULL(shelfcount_detail.TotalQty,0) AS TotalQty,
          IFNULL(shelfcount_detail.OverPar,0) AS OverPar,
          IFNULL(shelfcount_detail.Short,0) AS Short,
          IFNULL(item.Weight,0) AS Weight
          FROM
          shelfcount
          INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
          INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
          INNER JOIN department ON shelfcount.DepCode = department.DepCode
          $where
          AND department.DepCode = $depcode ";
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
$issue=$Result['ParQty']-$Result['CcQty'];
  $pdf->Cell(10, 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'C');
  $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['ParQty']), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['CcQty']), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $issue), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['Short']), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['TotalQty']), 1, 0, 'C');
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $Result['Weight']), 1, 1, 'C');
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
