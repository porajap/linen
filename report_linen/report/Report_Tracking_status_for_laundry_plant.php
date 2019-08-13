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
    $where =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (dirty.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(dirty.Docdate) BETWEEN $date1 AND $date2";
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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Tracking Status Laundry Plant Report "), 0, 0, 'C');
      $this->Ln(10);
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
    $this->SetFont('THSarabun', 'b', 12);

    $this->Cell($w[0], 20, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
    $this->Cell($w[5], 20, iconv("UTF-8", "TIS-620", $header[5]), 1, 0  , 'C');
    $this->Ln();

    $this->Cell(20, 0, iconv("UTF-8", "TIS-620", ""), 0, 0  , 'C');
    for($i=0;$i<4;$i++){
    $this->Cell(18.75,-10, iconv("UTF-8", "TIS-620", 'Start Time'), 1, 0, 'C');
    $this->Cell(18.75,-10, iconv("UTF-8", "TIS-620", 'Finish Time '), 1, 0, 'C');
  }
    $this->Ln(0);


    // set Data Details
    $rows = 1;
    $loop = 0;
    $totalsum1 = 0;
    $totalsum2 = 0;
    echo $inner_array[$field[0]];
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        list($hours,$min,$secord)=explode(":",$inner_array[$field[1]]);
        list($hours2,$min2,$secord2)=explode(":",$inner_array[$field[8]]);
        $total_hours = $hours -  $hours2;
        $total_min = $min-$min2;
        $this->SetFont('THSarabun', '', 10);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[0]],8)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[1]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[2]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[3]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[4]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[5]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[6]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[7]],0,5)), 1, 0, 'C');
        $this->Cell(18.75, 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[8]],0,5)), 1, 0, 'C');
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", abs($total_hours)." ชั่วโมง ". abs($total_min)." นาที"), 1, 0, 'C');
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[2]];
        $totalsum2 += $inner_array[$field[4]];
      }
      }
      // Footer Table


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
$Sql = "SELECT
factory.Facname
FROM
process
INNER JOIN dirty ON Process.RefDocNo = dirty.DocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
$where
AND dirty.FacCode = $FacCode
       ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Facname = $Result['Facname'];
}
$pdf->SetFont('THSarabun','b',11);
$pdf->Cell(1);
$pdf->Cell(165,10,iconv("UTF-8","TIS-620","โรงซัก : ".$Facname),0,0,'L');
$pdf->Cell(ุ60,10,iconv("UTF-8","TIS-620",$date_header),0,0,'R');
$pdf->Ln(10);

$query = "SELECT
TIME (process.WashStartTime) AS WashStartTime ,
TIME (process.WashEndTime) AS WashEndTime,
TIME (process.PackStartTime)AS PackStartTime,
TIME (process.PackEndTime)AS PackEndTime,
TIME (process.SendStartTime)AS SendStartTime,
TIME (process.SendEndTime)AS SendEndTime,
dirty.FacCode,
dirty.DocDate,
TIME (dirty.ReceiveDate)AS ReceiveDate
FROM
process
INNER JOIN dirty ON Process.RefDocNo = dirty.DocNo
$where
AND dirty.FacCode = $FacCode
";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = "DocDate,ReceiveDate,SendEndTime,WashStartTime,WashEndTime,PackStartTime,PackEndTime,SendStartTime,SendEndTime,Total";
// Table header
$header = array('date','Receive Dirty Linen Time','Washing Time','Packing Time','Distribute Time','Total');
// width of column table
$width = array(20,37.5,37.5,37.5,37.5,20);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();

// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Clean_' . $ddate . '.pdf');
