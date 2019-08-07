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

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 10);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $rows = 1;
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows >23) {
          $count++;
          if ($count % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 12);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $this->SetFont('THSarabun', '', 12);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'L');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'L');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'R');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'R');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'R');
        $this->Ln();
        $rows++;
        $total_dirty+=$inner_array[$field[2]];
        $total_clean+=$inner_array[$field[3]];
      }
    }
          $total_percent  = (-1*(($total_clean -$total_dirty ) / $total_dirty) * 100);
    $this->SetFont('THSarabun', '', 12);
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'L');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'L');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($total_dirty,2)), 1, 0, 'R');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($total_clean,2)), 1, 0, 'R');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total_percent,2)), 1, 0, 'R');
    $this->Ln();
    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
  }
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
        INNER JOIN factory ON clean.FacCode = factory.FacCode
        WHERE DATE(clean.DocDate)= DATE('$date1')
        AND clean.FacCode = $FacCode ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $factory = $Result['FacName'];
}

$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(1);
$pdf->Cell(145, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", "ประจำเดือน : " . $DocDate), 0, 0, 'L');
$pdf->Ln(10);


$query = "SELECT
          department.DepName,
          dirty.DocNo AS DocNo1,
          dirty.Total AS Total1,
          clean.Total AS Total2,
          ROUND( (-1*((clean.Total - dirty.Total ) / dirty.Total) * 100), 2)  AS Precent
          FROM clean
          INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
          INNER JOIN department ON clean.DepCode = department.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
          WHERE DATE(clean.DocDate)= DATE('$date1')
          AND clean.FacCode = $FacCode";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "HptName,DocNo1,Total1,Total2,Precent";
// Table header"
$header = array('Details',"เอกสาร",'ส่งผ้าเปื้น Weight(Kg)', 'รับผ้าสะอาด Weight(Kg)', 'ส่วนต่าง (%)');
// width of column table
$width = array(30,30, 50, 50, 30);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Soiled_Clean_Ratio_' . $ddate . '.pdf');
