<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");

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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "สรุปรายการซักผ้า PPU"), 0, 0, 'C');
      // Line break
      $this->Ln(7);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "โรงพยาบาลกรุงเทพ ศูนย์วิจัย"), 0, 0, 'C');
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
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $rows = 1;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 22) {
          $count++;
          if ($count % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $sum=($inner_array[$field[1]]+$inner_array[$field[2]])*$inner_array[$field[3]];
        $tax=(($inner_array[$field[1]]+$inner_array[$field[2]])*$inner_array[$field[3]])*0.07;
        $total=$sum+$tax;
        $pdf->SetFont('THSarabun', '', 12);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $total), 1, 0, 'C');
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[1]];
        $totalsum2 += $inner_array[$field[2]];
        $totalsum3 += $total;
      }
    }
    $footer = array('Total', number_format($totalsum1, 2),number_format($totalsum2, 2),'', number_format($totalsum3, 2));
    $pdf->SetFont('THSarabun', 'b', 12);
    for ($i = 0; $i < count($footer); $i++)
      $pdf->Cell($width[$i], 10, iconv("UTF-8", "TIS-620", $footer[$i] . " "), 1, 0, 'R');
    $pdf->Ln(10);


    if ($count % 25 >= 22) {
      $pdf->AddPage("P", "A4");
    }
    $pdf->SetFont('THSarabun', 'b', 12);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "จำนวนกิโลกรัม = Clean Linen (After inspection) + Repair Convert by item to weight and sum"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "ราคาต่อกิโลกรัม = Price per kg. Depend on outsource Laundry"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "รวมเป็นเงิน = Total amount exclude vat 7%"), 0, 0, 'L');
    $pdf->Ln(10);
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
        DATE(clean.DocDate) AS DocDate
        FROM
        clean
        INNER JOIN factory ON clean.FacCode = factory.FacCode
        WHERE DATE(clean.DocDate) = DATE ('$date1')
        AND clean.FacCode = $FacCode";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $factory = $Result['FacName'];
  $DocDate = date('d/m/Y', strtotime($Result['DocDate']));
}

$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(1);
$pdf->Cell(160, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", "วันที่ : " . $DocDate), 0, 0, 'L');
$pdf->Ln(10);

  $query = "SELECT
            item.ItemName,
            SUM(clean_detail.Weight) as clean_weight,
            SUM(repair_detail.Weight) as repair_Weight,
            item.FacPrice
            FROM
            clean
            INNER JOIN clean_detail ON clean.docno = clean_detail.docno
            INNER JOIN item ON item.itemcode = clean_detail.itemcode
            INNER JOIN claim ON clean.refdocno = claim.docno
            INNER JOIN repair ON claim.refdocno = repair.docno
            INNER JOIN repair_detail ON repair.docno = repair_detail.docno
            WHERE DATE(clean.DocDate) = DATE ('$date1')
            AND clean.FacCode = $FacCode
            GROUP BY item.ItemName" ;
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "ItemName,clean_weight,repair_Weight,FacPrice,TOTAL";
// Table header
$header = array('DETAILS', 'WEIGHT - Clean (Kg)','WEIGHT - Repair (Kg)', 'ราคาต่อกิโลกรัม', 'รวมเป็นเงิน');
// width of column table
$width = array(50,40,40,40,20);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum




$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Summary_Dirty_' . $ddate . '.pdf');
