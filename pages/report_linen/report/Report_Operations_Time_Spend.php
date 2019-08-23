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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Opertations Time Spend"), 0, 0, 'C');
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
      $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $loop = 0;
    $rows = 1;
    $this->SetFont('THSarabun', '', 10);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 39) {
          $loop++;
          if ($loop % 41 == 1) {
            $this->SetFont('THSarabun', 'b', 12);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $this->Cell($w[0], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]->format("l Y-m-d H:i:s\n")), 1, 0, 'C');
        $this->Cell($w[1], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Ln();
        $rows++;
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


// Using Coding
$pdf->AddPage("P", "A4");

$Sql = "SELECT
        factory.FacName,
        processtime.docDate
        FROM
        processtime
        INNER JOIN factory ON processtime.FacCode = factory.FacCode";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $factory = $Result['FacName'];
}

$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(1);
$pdf->Cell(145, 10, iconv("UTF-8", "TIS-620", "หน่วยงาน : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", "ประจำเดือน : " . $DocDate), 0, 0, 'L');
$pdf->Ln(10);


$query = "SELECT
            DATE(process.WashStartTime) AS DateS,
            TIME(process.WashStartTime) AS TimeStart,
            process.WashStopTime,
            TIME(process.WashEndTime) AS TimeFinish,
            process.PackStartTime,
            process.PackEndTime,
            process.SendStartTime,
            process.SendEndTime
            FROM
            process
            WHERE MONTH(process.WashStartTime) = 7";
// var_dump($query); die;
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = "DateS, ,TimeStart,TimeFinish";
// Table header
$header = array('Date', 'Cycle', 'Start Time', 'Finish Time');
// width of column table
$width = array(30, 65, 45, 45);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Operations_Time_Spend_' . $ddate . '.pdf');
