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
      $this->SetFont('THSarabun', 'b', 14);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "แบบบันทึกส่งผ้าเปื้อน"), 0, 0, 'C');
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
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($count > 22) {
              $rows++;
              if ($rows % 25 == 1) {
                $this->SetFont('THSarabun', 'b', 12);
                for ($i = 0; $i < count($header); $i++)
                  $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
                $this->Ln();
              }
            }
            for ($i=0; $i <count($inner_array[$field[0]]) ; $i++) {
               if ($inner_array[$field[0]]==$date) {
                     $this->Cell($w[0], 10, "", 1, 0, 'C');
                     $loop++;
               }
             else {
               $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'L');
               $date=$inner_array[$field[0]];
               $loop=0;
               $check++;
               $name[]=$inner_array[$field[0]];
             }
             $sum_loop[]=$loop;
             $sum_check[]=$check;
             }
            $this->SetFont('THSarabun', '', 12);
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'R');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'L');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'R');
        $this->Ln();
        $count++;
        $Total+=$inner_array[$field[3]];
      }
    }
    $count_value = array_count_values($sum_check);
    $total=array_sum($count_value);
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620",'Total'), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",'' ), 1, 0, 'R');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620",'' ), 1, 0, 'L');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620",$Total ), 1, 1, 'L');

    for ($i=0; $i <$check ; $i++) {
      $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620",$name[$i]), 1, 0, 'L');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",$count_value[$i+1] ), 1, 0, 'C');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620",'' ), 1, 0, 'L');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620",'' ), 1, 1, 'L');
    }
    $pdf->Ln(8);

    $pdf->SetFont('THSarabun', 'b', 11);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่ห้องผ้า..................................................."), 0, 0, 'L');
    $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่โรงซัก........................................"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "วันที่......................................................................"), 0, 0, 'L');
    $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่.........................................................."), 0, 0, 'L');
    $pdf->Ln(7);


    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');

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
        factory.FacName,
        DATE(dirty.DocDate) AS DocDate,
        TIME(dirty.DocDate) AS DocTime
        FROM
        dirty
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        WHERE dirty.FacCode = 1";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $factory = $Result['FacName'];
  $DocDate = date('d/m/Y', strtotime($Result['DocDate']));
  $DocTime = $Result['DocTime'];
}
// $DocDate = date('d/m/Y',strtotime($Result['DocDate']));
// $docdate = date('d')." ".$datetime->getTHmonth(date('F'))." พ.ศ. ".$datetime->getTHyear(date('Y'));
// $docdate = $DocDate[0]." ".$datetime->getTHmonthFromnum($DocDate[1])." พ.ศ. ".$datetime->getTHyear($DocDate[2]);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(1);
$pdf->Cell(140, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", "วันที่ : " . $DocDate . " วันที่ : " . $DocTime), 0, 0, 'L');
$pdf->Ln(10);

$query = "SELECT
          item.ItemName,
          dirty_detail.Qty,
          department.DepName,
          ROUND((item.Weight * dirty_detail.Qty),2) AS TWeight
          FROM
          dirty
          INNER JOIN factory ON dirty.FacCode = factory.FacCode
          INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
          INNER JOIN item ON dirty_detail.ItemCode = item.ItemCode
          INNER JOIN department ON dirty.DepCode = department.DepCode
          WHERE dirty.FacCode = 1
          ORDER BY item.ItemName DESC ";
// var_dump($query); die;
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = "ItemName,Qty,DepName,TWeight";
// Table header
$header = array('BAG', 'NO', 'LOCATION', 'WEIGHT (Kg)');
// width of column table
$width = array(50, 45, 45, 45);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$pdf->Ln(8);

$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่ห้องผ้า..................................................."), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่โรงซัก........................................"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "วันที่......................................................................"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่.........................................................."), 0, 0, 'L');
$pdf->Ln(7);



$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Dirty_' . $ddate . '.pdf');
