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
$DepCode=$data['DepCode'];
$where='';
//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (rewash.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (rewash.DocDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE rewash.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (rewash.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(rewash.Docdate) BETWEEN $date1 AND $date2";
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

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 10);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "วันที่พิมพ์รายงาน " . $printdate), 0, 0, 'R');
      $this->Ln(5);
      // Title
      $this->SetFont('THSarabun', 'b', 20);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "แบบบันทึก Re-Wash Record"), 0, 0, 'C');
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
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $rows = 1;
    $loop = 0;
    $totalsum1 = 0;
    $totalsum2 = 0;
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
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[2]];
      }
      }
      // Footer Table
      $this->SetFont('THSarabun', 'B', 14);
      $this->Cell(140, 10, iconv("UTF-8", "TIS-620", "Total"), 1, 0, 'C');
      $this->Cell(50, 10, iconv("UTF-8", "TIS-620", $totalsum1), 1, 0, 'C');
      $pdf->Ln(10);

    $footer_nextpage = $loop % 24;
    if ($footer_nextpage >= 23) {
      $pdf->AddPage("P", "A4");
    }
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
        DATE(rewash.DocDate) AS DocDate,
        TIME(rewash.DocDate) AS DocTime
        FROM
        rewash
        INNER JOIN factory ON rewash.FacCode = factory.FacCode
        INNER JOIN department ON department.depcode = rewash.depcode
        $where
        AND rewash.FacCode = $FacCode
        AND department.HptCode = '$HptCode'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $factory = $Result['FacName'];
}

$pdf->SetFont('THSarabun', 'b', 12);
$pdf->Cell(1);
$pdf->Cell(140, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(10);

$query = "SELECT
          item.ItemName,
          rewash.DocNo,
          rewash_detail.Qty
          FROM
          rewash_detail
          INNER JOIN item ON rewash_detail.ItemCode = item.ItemCode
          INNER JOIN rewash ON rewash_detail.DocNo = rewash.DocNo
          INNER JOIN department ON department.depcode = rewash.depcode
          $where
          AND rewash.FacCode = $FacCode
          AND department.HptCode = '$HptCode'";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "DocNo,ItemName,Qty";
// Table header
$header = array('DETAILS','ชื่อ', 'จำนวน(ชิ้น)',);
// width of column table
$width = array(60,80,50);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);


$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่ห้องผ้า..................................................."), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่โรงซัก........................................"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "วันที่......................................................................"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่.........................................................."), 0, 0, 'L');
$pdf->Ln(12);
$pdf->Cell(190, 0, '', 'T');
$pdf->Ln(10);


$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Rewash_' . $ddate . '.pdf');
