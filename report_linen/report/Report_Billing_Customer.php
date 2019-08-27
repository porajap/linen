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
$DepCode=$data['DepCode'];
$format=$data['Format'];
$where='';

//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (billcustomer.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (billcustomer.DocDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE billcustomer.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (billcustomer.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(billcustomer.Docdate) BETWEEN $date1 AND $date2";
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
      $this->SetFont('THSarabun', 'b', 14);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "สรุปค่าบริการ รับ-ส่งผ้า (Customer)"), 0, 0, 'C');
            $this->Ln(5);


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
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 1;
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
        $pdf->SetFont('THSarabun', '', 12);
        $total=$inner_array[$field[3]]*$inner_array[$field[4]];
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $total), 1, 0, 'C');
        $this->Ln();
        $total_all+=$total;
        $count++;
      }
    }
    $tax=$total_all*7/100;
    $total_with_tax = $tax + $total_all;
    $pdf->Cell(78, 30, iconv("UTF-8", "TIS-620", "จำนวนเงิน"), 1, 0, 'C');
    $pdf->Cell(84, 10, iconv("UTF-8", "TIS-620", "ยอดเงินก่อนภาษีมูลค่าเพิ่ม"), 1, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", number_format($total_all, 2)), 1, 1, 'C');
    $pdf->Cell(78, 30, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(84, 10, iconv("UTF-8", "TIS-620", "ภาษีมูลค่าเพิ่ม 7%"), 1, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", number_format($tax, 2)), 1, 1, 'C');
    $pdf->Cell(78, 30, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(84, 10, iconv("UTF-8", "TIS-620", "รวมเงินสุทธิ (รวมภาษีมูลค่าเพิ่ม)"), 1, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", number_format($total_with_tax, 2)), 1, 1, 'C');
    $pdf->ln(10);

    // Footer Table

    $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "สวนของเจ้าหน้าที่ บริษัท เนชั่นแนล เฮลท์แคร์ ซิสเท็มส์ จำกัด"), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 10, iconv("UTF-8", "TIS-620", "สวนของเจ้าหน้าที่ บริษัท เนชั่นแนล เฮลท์แคร์ ซิสเท็มส์ จำกัด"), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 0, iconv("UTF-8", "TIS-620", "ผู้ตรวจสอบ"), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 0, iconv("UTF-8", "TIS-620", "ผู้ตรวจสอบ"), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 15, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(28, 15, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 1, 'C');
    if ($count % 25 >= 22) {
      $pdf->AddPage("P", "A4");
    }
    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
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
$pdf->AddPage("P", "A4");

$Sql = "SELECT
site.HptName
FROM
billcustomer_detail
INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
INNER JOIN site on site.HptCode = billcustomer.HptCode
$where
AND billcustomer.HptCode = '$HptCode'
AND billcustomer.DepCode = '$DepCode' ";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
        $HptName = $Result['HptName'];
}
      $pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(1);
$pdf->Cell(190,10,iconv("UTF-8","TIS-620","โรงพยาบาล ".$HptName." ".$date_header),0,0,'C');
$pdf->ln(10);


$query = "SELECT
item.ItemName,
billcustomer_detail.Qty1,
item_unit.Unitname,
billcustomer_detail.Price
FROM
billcustomer_detail
INNER JOIN billcustomer on billcustomer.docno = billcustomer_detail.DocNo
INNER JOIN item on item.ItemCode = billcustomer_detail.ItemCode
INNER JOIN item_unit on item_unit.UnitCode = billcustomer_detail.UnitCode1
INNER JOIN site on site.HptCode = billcustomer.HptCode
$where
AND billcustomer.HptCode = '$HptCode'
AND billcustomer.DepCode = '$DepCode'
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = ",ItemName,Unitname,Qty1,Price,";
// Table header
$header = array('ลำดับ','รายการ','หน่วย','จำนวน','ราคาต่อหน่วย ','จำนวนเงิน');
// width of column table
$width = array(28,50,28,28,28,28);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();






$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
