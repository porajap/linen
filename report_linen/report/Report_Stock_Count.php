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
$format=$data['Format'];
$depcode=$data['DepCode'];
$where='';

//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (item_stock.ExpireDate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (item_stock.ExpireDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE item_stock.ExpireDate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (item_stock.ExpireDate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE MONTH(item_stock.ExpireDate) BETWEEN '$date1' AND '$date2'";
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
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "รายงานสต๊อกคงคลัง"), 0, 0, 'C');
      $this->Ln(10);
    } else {
      // Line break
      $this->Ln(7);
    }
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

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 23) {
          $loop++;
          if ($loop % 24 == 1) {
                $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'R');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'R');
        $this->Ln();
      }
      }
      // Footer Table
      $this->SetFont('THSarabun', 'B', 14);
      $footer = array('Total','', number_format($totalsum1, 2), '', number_format($totalsum2, 2),'');
      for ($i = 0; $i < count($footer); $i++)
      $pdf->Cell($width[$i], 7, iconv("UTF-8", "TIS-620", $footer[$i] . " "), 1, 0, 'R');
      $pdf->Ln(10);

    $footer_nextpage = $loop % 24;
    if ($footer_nextpage >= 23) {
      $pdf->AddPage("P", "A4");
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
        department.DepName
        FROM
        item_stock
        INNER JOIN department ON item_stock.Depcode=department.Depcode
        $where
        AND item_stock.Depcode = $depcode
       ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $depname = $Result['DepName'];
}

$pdf->SetFont('THSarabun', 'b', 15);
$pdf->Cell(100, 10, iconv("UTF-8", "TIS-620", "หน่วยงาน " . $depname), 0, 0, 'L');
$pdf->Cell(90, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
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
        INNER JOIN department ON item_stock.Depcode=department.Depcode
        INNER JOIN item on item.ItemCode=item_stock.ItemCode
        $where
        AND item_stock.Depcode = $depcode  ";

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
$pdf->Output('I', 'Report_Stock_Count_' . $ddate . '.pdf');
