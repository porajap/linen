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
      $this->SetFont('THSarabun', 'b', 14);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Tracking Status for linen operation Report "), 0, 0, 'C');

      $this->Ln(10);
      $this->Cell(10, 0, "");
      $this->Cell(100, 10, iconv("UTF-8", "TIS-620", "หน่วยงาน"), 0, 0, 'L');
      $this->Cell(56, 10, iconv("UTF-8", "TIS-620", "ประจำเดือน"), 0, 0, 'R');
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
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "Date"), 1, 0, 'C');
$pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", "Cycle Time"), 1, 0, 'C');
$pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", "Shelf Count Time"), 1, 0, 'C');
$pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", "Packing Time"), 1, 0, 'C');
$pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", "Delivery Time"), 1, 1, 'C');




for ($i = 1; $i < 10; $i++) {
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "$i/7/2561"), 1, 0, 'C');
  $pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
  $pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
  $pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", ""), 1, 0, 'C');
  $pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", ""), 1, 1, 'C');
}





/*$Sql = "SELECT
        clean.DocNo,
        clean.DocDate,
        factory.FacName
        FROM
        clean
        INNER JOIN factory ON clean.FacCode = factory.FacCode
        WHERE clean.FacCode = 1";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
        $factory = $Result['FacName'];
        $DocDate = date('d/m/Y',strtotime($Result['DocDate']));
}

$pdf->SetFont('THSarabun','b',11);
$pdf->Cell(1);
$pdf->Cell(165,10,iconv("UTF-8","TIS-620","โรงซัก : ".$factory),0,0,'L');
$pdf->Cell(ุ60,10,iconv("UTF-8","TIS-620","วันที่ : " .$DocDate),0,0,'L');
$pdf->Ln(10);
/*
$query = "SELECT
          item.ItemName,
          clean_detail.Qty,
          ROUND((item.FacPrice * clean_detail.Qty)) AS TotalP
          FROM
          clean
          INNER JOIN factory ON clean.FacCode = factory.FacCode
          INNER JOIN clean_detail ON clean.DocNo = clean_detail.DocNo
          INNER JOIN item ON clean_detail.ItemCode = item.ItemCode
          WHERE clean.FacCode = 1";
// var_dump($query); die;
// Number of column
$numfield = 3;
// Field data (Must match with Query)
$field = "ItemName,Qty,TotalP";
// Table header
$header = array('ITEM','จำนวน','จำนวนเงิน');
// width of column table
$width = array(80,55,55);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();
// Get $totalsum
$totalsum1 = 0;
if(is_array($result)){
  foreach($result as $result=>$inner_array){
    $totalsum1 += $inner_array['TotalP'];
  }
}*/
// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
