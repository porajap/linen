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
$depcode=$data['DepCode'];
$format=$data['Format'];
$where='';

//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (clean.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(clean.Docdate) BETWEEN $date1 AND $date2";
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
      $this->SetFont('THSarabun', 'b', 20);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "รายงานรับผ้าสะอาด NON PPU"), 0, 0, 'C');
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


  function setTable($pdf, $header, $data, $width, $numfield, $field){
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
    $rows = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        //เช็คค่า=1 ให้เริ่มหัวข้อ
        if ($count > 22) {
          $rows++;
          if ($rows % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        //table data
        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'L');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[5]],2)), 1, 0, 'R');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[6]],2)) ,1, 0, 'R');
        $this->Ln();
        $count++;
        $totalsum += $inner_array[$field[6]];
      }
    }
    //footer
    $this->SetFont('THSarabun', 'b', 14);
    $this->Cell(160, 7, iconv("UTF-8", "TIS-620", "รวม"), 1, 0, 'R');
    $this->Cell(30, 7, iconv("UTF-8", "TIS-620", number_format($totalsum,2)), 1, 1, 'R');

    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
  }
}
function setmuntiTable($pdf, $header, $data, $width, $numfield, $field){
  $field = explode(",", $field);
  // Column widths
  $w = $width;
  // Header
  $this->SetFont('THSarabun', 'b', 14);
  for ($i = 0; $i < count($header); $i++)
    $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
  $this->Ln();
  // set Data Details

  $this->SetFont('THSarabun', '', 12);
  if (is_array($data)) {
    foreach ($data as $data => $inner_array) {
      $this->SetFont('THSarabun', '', 14);
      $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'L');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]]), 1, 0, 'C');
      $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[5]]), 1, 0, 'R');
      $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[6]]), 1, 0, 'R');
      $this->Ln();
      $count++;
      $totalsum += $inner_array[$field[6]];
    }
  }
  //footer
  $this->SetFont('THSarabun', 'b', 14);
  $this->Cell(160, 7, iconv("UTF-8", "TIS-620", "รวม"), 1, 0, 'R');
  $this->Cell(30, 7, iconv("UTF-8", "TIS-620", $totalsum), 1, 1, 'R');

  // Closing line
  $pdf->Cell(array_sum($w), 0, '', 'T');
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
			site.HptName,
			department.DepName
FROM clean
INNER JOIN dirty ON dirty.DocNo =clean.RefDocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
INNER JOIN department ON clean.DepCode=department.DepCode
INNER JOIN site ON department.HptCode=site.HptCode
$where
AND dirty.FacCode = $FacCode
AND department.HptCode = '$HptCode'
AND department.DepCode = $depcode";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $side = $Result['HptName'];
  $facname = $Result['FacName'];
}

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", "โรงพยาบาล" . $side), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(1);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "โรงซัก  " . $facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620",$date_header), 0, 0, 'R');
$pdf->Ln(10);

$query = "SELECT
            item.itemname,
            item_unit.unitname,
            clean_detail.QTY,
            item.facprice,
            item.facprice,
            clean.DocDate,
            (clean_detail.QTY*item.facprice) as total
            FROM
            clean_detail
            INNER JOIN clean ON clean_detail.DocNo = clean.DocNo
            INNER JOIN dirty ON dirty.DocNo =clean.RefDocNo
            INNER JOIN factory ON factory.FacCode =dirty.FacCode
            INNER JOIN department ON department.DepCode =clean.DepCode
    				INNER JOIN site ON department.HptCode =site.HptCode
            INNER JOIN item ON item.itemcode = clean_detail.itemcode
            INNER JOIN item_unit ON item_unit.unitcode = clean_detail.unitcode
            $where
            AND dirty.FacCode = $FacCode
            AND department.HptCode = '$HptCode'
            AND department.DepCode = $depcode
					  ";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = ", ,itemname,unitname,QTY,facprice,total,DocDate";
// Table header
$header = array('ลำดับ', 'ชื่อรายการ', 'หน่วย', 'จำนวน', "ราคา", "รวม");
// width of column table
$width = array( 20, 65, 25, 20, 30, 30);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);

$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Cleaned_Linen_Weight_' . $ddate . '.pdf');
