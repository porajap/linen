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
    $sDate = $_GET['sDate'];
    // $sDate = explode("/",$sDate);
    // $sDate = $sDate[2].'-'.$sDate[1].'-'.$sDate[0];

    $eDate = $_GET['eDate'];
    // $eDate = explode("/",$eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];

    $Dept = $_GET['DepCode'];

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

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 12);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 1;
    $rows = 0;

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($count > 23) {
          $rows++;
          if ($rows % 26 == 1) {
            $this->SetFont('THSarabun', 'b', 12);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $this->SetFont('THSarabun', '', 12);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count . $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Ln();
        $count++;
      }
    }

    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
  }
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

$sDate = $_GET['sDate'];
$sDate = explode("/", $sDate);
$sDate = $sDate[2] . '-' . $sDate[1] . '-' . $sDate[0];

$eDate = $_GET['eDate'];
$eDate = explode("/", $eDate);
$eDate = $eDate[2] . '-' . $eDate[1] . '-' . $eDate[0];

$Dept = $_GET['DepCode'];

// Using Coding
$pdf->AddPage("P", "A4");

$Sql = "SELECT
        shelfcount.DocNo,
        department.DepName
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        WHERE DATE(shelfcount.DocDate) BETWEEN DATE('15/07/2019') AND DATE('15/07/2019')
        AND department.DepCode = 1";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
}
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "Summary Report from " . "15/07/2019" . " - " . "15/07/2019"), 0, 0, 'C');
// Line break
$pdf->Ln(7);

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(15);
$pdf->Cell(150, 10, iconv("UTF-8", "TIS-620", "แผนก : Endoscope"), 0, 0, 'C');
$pdf->SetMargins(15, 0, 0);
$pdf->Ln(12);

$query = "SELECT
          item.ItemName,
          shelfcount_detail.ParQty,
          item.Weight,
          item.FacPrice,
          ROUND((item.Weight*item.FacPrice),2) AS TotalP,
          shelfcount.DocNo,
          users.FName
          FROM
          shelfcount_detail
          INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
          INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
          INNER JOIN users ON shelfcount.Modify_Code = users.ID
          WHERE shelfcount.DepCode = 1";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "FName,ItemName,ParQty,Weight,TotalP";
// Table header
$header = array('CUS NAME', 'ITEM NAME', 'PAR QTY', 'ITEM WEIGHT', 'PRICE');
// width of column table
$width = array(40, 55, 35, 20, 20);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum
$totalsum = 0;
if (is_array($result)) {
  foreach ($result as $result => $inner_array) {
    $totalsum += $inner_array['TotalP'];
  }
}
// Footer Table
$footer = array('', '', '', 'รวม', number_format($totalsum, 2));
$pdf->SetFont('THSarabun', 'b', 12);
for ($i = 0; $i < count($footer); $i++)
  $pdf->Cell($width[$i], 10, iconv("UTF-8", "TIS-620", $footer[$i] . " "), 1, 0, 'R');
$pdf->Ln(8);

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Summary_' . $ddate . '.pdf');
