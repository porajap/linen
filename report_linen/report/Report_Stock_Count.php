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
$language = $_SESSION['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (item_stock.ExpireDate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  }
  elseif ($format = 3) {
      $where = "WHERE  year (item_stock.ExpireDate) LIKE '%$date1%'";
      if ($language == "th") {
        $date1 = $date1 + 543;
        $date_header = $array['year'][$language] . " " . $date1;
      } else {
        $date_header = $array['year'][$language] . $date1;
      }
    }
}
elseif($chk == 'between'){
  $where =   "WHERE item_stock.ExpireDate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year2=$year2+543;
    $year=$year+543;
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . $array['to'][$language] .
      $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
  } else {
    $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth)." " . $year ." " . $array['to'][$language] ." " .
         $day2 . " " . $datetime->getmonthFromnum($mouth2) . $year2;
  }

}
elseif($chk == 'month'){
    $where =   "WHERE month (item_stock.ExpireDate) = ".$date1;
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $date_header = $array['month'][$language]  ." " . $datetime->getTHmonthFromnum($date1);
      }else{
        $date_header = $array['month'][$language] ." " . $datetime->getmonthFromnum($date1);
      }

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE MONTH(item_stock.ExpireDate) BETWEEN '$date1' AND '$date2'";
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1)  ." " . $array['to'][$language] ." " . $datetime->getTHmonthFromnum($date2);
  }else{
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) ." " . $array['to'][$language] ." " . $datetime->getmonthFromnum($date2);
  }
}
 

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  {
    
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
        WHERE item_stock.DepCode=$depcode
       ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $depname = $Result['DepName'];
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}

      // Move to the right
      $pdf->SetFont('THSarabun', '', 10);
      $image="../images/Nhealth_linen 4.0.png";
      $pdf-> Image($image,10,10,43,15);
      $pdf->SetFont('THSarabun', '', 10);
      $pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
      $pdf->Ln(18);
      // Title
      $pdf->SetFont('THSarabun', 'b', 20);
      $pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['r9'][$language]), 0, 0, 'C');
      $pdf->Ln(10);

//head field
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620",$array2['department'][$language]." : ".$depname), 0, 1, 'L');
$pdf->Ln(2);

$pdf->Cell(15, 10, iconv("UTF-8", "TIS-620", $array2['no'][$language]), 1, 0, 'C');
$pdf->Cell(50, 10, iconv("UTF-8", "TIS-620", $array2['itemname'][$language]), 1, 0, 'C');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['unit'][$language]), 1, 0, 'C');
$pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620",  $array2['par'][$language]), 1, 0, 'C');
$pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", $array2['order'][$language]), 1, 1, 'C');


// BODY TABLE
$i = 1;
$header = 0;

$Sql = "SELECT
        item.itemName,
        item_unit.unitname,
        item_stock.TotalQty AS  TotalQty,
        item_stock.ParQty
        FROM
        item_stock
        INNER JOIN department ON item_stock.Depcode=department.Depcode
        INNER JOIN item on item.ItemCode=item_stock.ItemCode
        INNER JOIN item_unit on item.unitcode=item_unit.unitcode
        WHERE item_stock.DepCode=$depcode
        GROUP BY item.itemName ";

$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {

  $pdf->SetFont('THSarabun', '', 14);
  $DocDate = date('d/m/Y', strtotime($Result['ExpireDate']));
  $pdf->Cell(15, 10, iconv("UTF-8", "TIS-620", $i), 1, 0, 'C');
  $pdf->Cell(50, 10, iconv("UTF-8", "TIS-620", $Result['itemName']), 1, 0, 'L');
  $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $Result['unitname']), 1, 0, 'C');
  $pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", $Result['ParQty']), 1, 0, 'C');
  $pdf->Cell(42.5, 10, iconv("UTF-8", "TIS-620", $Result['TotalQty']), 1, 1, 'C');
  $i++;
}

// Footer Table


$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Stock_Count_' . $ddate . '.pdf');
