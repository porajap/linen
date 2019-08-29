<?php
require('fpdf.php');
require('../connect/connect.php');
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
      $this->SetFont('THSarabun', 'b', 14);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "สรุปค่าบริการ รับ-ส่งผ้า (Bill-Claim)"), 0, 0, 'C');
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
        $total=$inner_array[$field[3]]*$inner_array[$field[4]]*$inner_array[$field[6]];
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]].$inner_array[$field[5]]), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620",$inner_array[$field[6]]), 1, 0, 'C');
        $this->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($total,2)), 1, 0, 'C');
        $this->Ln();
        $total_all+=$total;
        $count++;
      }
    }
    $tax=$total_all*7/100;
    $total_with_tax = $tax + $total_all;
    $pdf->Cell(70, 30, iconv("UTF-8", "TIS-620", "จำนวนเงิน"), 1, 0, 'C');
    $pdf->Cell(96, 10, iconv("UTF-8", "TIS-620", "ยอดเงินก่อนภาษีมูลค่าเพิ่ม"), 1, 0, 'C');
    $pdf->Cell(24, 10, iconv("UTF-8", "TIS-620", number_format($total_all, 2)), 1, 1, 'C');
    $pdf->Cell(70, 30, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(96, 10, iconv("UTF-8", "TIS-620", "ภาษีมูลค่าเพิ่ม 7%"), 1, 0, 'C');
    $pdf->Cell(24, 10, iconv("UTF-8", "TIS-620", number_format($tax, 2)), 1, 1, 'C');
    $pdf->Cell(70, 30, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(96, 10, iconv("UTF-8", "TIS-620", "รวมเงินสุทธิ (รวมภาษีมูลค่าเพิ่ม)"), 1, 0, 'C');
    $pdf->Cell(24, 10, iconv("UTF-8", "TIS-620", number_format($total_with_tax, 2)), 1, 1, 'C');
    $pdf->ln(10);

    // Footer Table

    $pdf->Cell(25, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(24, 10, iconv("UTF-8", "TIS-620", "สวนของเจ้าหน้าที่ บริษัท เนชั่นแนล เฮลท์แคร์ ซิสเท็มส์ จำกัด"), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(24, 10, iconv("UTF-8", "TIS-620", "สวนของเจ้าหน้าที่ บริษัท เนชั่นแนล เฮลท์แคร์ ซิสเท็มส์ จำกัด"), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(24, 0, iconv("UTF-8", "TIS-620", "ผู้ตรวจสอบ"), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(24, 0, iconv("UTF-8", "TIS-620", "ผู้ตรวจสอบ"), 0, 1, 'C');
    $pdf->Cell(25, 0, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(24, 15, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 0, 'C');
    $pdf->Cell(70, 10, iconv("UTF-8", "TIS-620", ""), 0, 0, 'C');
    $pdf->Cell(24, 15, iconv("UTF-8", "TIS-620", ".............................................................................................."), 0, 1, 'C');
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
$DocNo = $_GET['DocNo'];

$Sql = "SELECT
claim.DocNo,
DATE_FORMAT(claim.DocDate,'%d-%m-%Y') AS DocDate,
site.HptName,
department.DepName,
users.FName,
claim.Total,
claim.IsStatus,
TIME(claim.Modify_Date) AS xTime
FROM
claim
INNER JOIN claim_detail ON claim.DocNo = claim_detail.DocNo
INNER JOIN site ON claim.HptCode = site.HptCode
INNER JOIN department ON claim.DepCode = department.DepCode
INNER JOIN item ON claim_detail.ItemCode = item.ItemCode
INNER JOIN users ON claim.Modify_Code = users.ID
INNER JOIN employee ON users.ID = employee.EmpCode
WHERE claim.DocNo = '$DocNo'
";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
$HptName = $Result['HptName'];
$DepName = $Result['DepName'];
$DocDate = $Result['DocDate'];
$Total = $Result['Total'];
$FirstName = $Result['FName'];
$xTime = $Result['xTime'];
}
$pdf->SetFont('THSarabun','b',11);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['hospital'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$HptName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['department'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$DepName),0,0,'L');
$pdf->Ln(5);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docno'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$DocNo),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docdate'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$DocDate),0,0,'L');
$pdf->Ln(5);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['user'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$FirstName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['time'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$xTime),0,0,'L');
$pdf->SetMargins(15,0,0);
$pdf->Ln(12);


$query = "SELECT
claim_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
item_unit.UnitName2,
claim_detail.Qty1,
claim_detail.Weight,
claim_detail.Total,
claim_detail.Price
FROM claim_detail
INNER JOIN item_unit ON claim_detail.UnitCode2 = item_unit.UnitCode
INNER JOIN item ON item.ItemCode = claim_detail.ItemCode
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
WHERE claim_detail.DocNo = '$DocNo'
GROUP BY item.ItemCode
ORDER BY item.ItemName ASC
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = ",ItemName,UnitName,Qty1,Weight,UnitName2,Price";
// Table header
$header = array('ลำดับ','รายการ','หน่วย','จำนวน','ขนาดต่อหน่วย','ราคาต่อหน่วย','จำนวนเงิน');
// width of column table
$width = array(24,46,24,24,24,24,24);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
