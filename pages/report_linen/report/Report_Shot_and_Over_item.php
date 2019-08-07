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
      $this->SetFont('THSarabun', 'b', 14);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Shot and Over Items Report"), 0, 0, 'C');
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

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 10);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $loop = 0;
    $this->SetFont('THSarabun', '', 10);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $this->Cell($w[0], 6, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
        $this->Cell($w[1], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[5], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[5]]), 1, 0, 'C');
        $this->Cell($w[6], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[6]]), 1, 0, 'C');
        $this->Cell($w[7], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[7]]), 1, 0, 'C');
        $this->Cell($w[8], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[8]]), 1, 0, 'C');
        $this->Cell($w[9], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[9]]), 1, 0, 'C');
        $this->Ln();
        $count++;
        $loop++;
      }
      if ($loop == 40) {
        $this->SetFont('THSarabun', 'b', 10);
        for ($i = 0; $i < count($header); $i++)
          $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
        $this->Ln();
        $loop = -2;
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
        claim.DocNo,
        DATE(claim.DocDate) AS DocDate,
        TIME(claim.DocDate) AS DocTime,
        site.HptName
        FROM
        claim
        INNER JOIN department ON claim.DepCode = department.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        WHERE claim.DocNo = 'CMBHQ/1907-00001'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $Hospital = $Result['HptName'];
}

$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(1);
$pdf->Cell(145, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $Hospital), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", "ประจำเดือน : " . $DocDate), 0, 0, 'L');
$pdf->Ln(10);


$query = "SELECT
            site.HptName,
            department.DepName,
            dirty.DocNo AS DocNo1,
            dirty.DocDate AS DocDate1,
            dirty.Total AS Total1,
            clean.DocNo AS DocNo2,
            clean.DocDate AS DocDate2,
            clean.Total AS Total2,
            ROUND( (((clean.Total - dirty.Total ) / dirty.Total) * 100), 2)  AS Precent
            FROM clean
            INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
            INNER JOIN department ON clean.DepCode = department.DepCode
            INNER JOIN site ON department.HptCode = site.HptCode";
// var_dump($query); die;
// Number of column
$numfield = 9;
// Field data (Must match with Query)
$field = "count,Total1,Total2,Precent";
// Table header
$header = array('No', 'Item Name', 'ParOty', 'Shalf Count', 'Max', 'Issue', 'Order', 'Shot', 'Over');
// width of column table
$width = array(15, 35, 20, 20, 20, 20, 20, 20, 20);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Soiled_Clean_Ratio_' . $ddate . '.pdf');
