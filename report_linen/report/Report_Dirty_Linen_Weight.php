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
  $where =   "WHERE MONTH(dirty.DocDate) BETWEEN '$date1' AND '$date2'";
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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "แบบบันทึกส่งผ้าเปื้อน"), 0, 0, 'C');
      // Line break
      $this->Ln(5);
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
    $this->Ln(1);
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();
    // set Data Details
    $count = 1;
    $rows = 0;
    $check=0;
    $r=0;

    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($r > 22) {
                $rows++;
                if ($rows % 25 == 1) {
                  $this->SetFont('THSarabun', 'b', 14);
                  for ($i = 0; $i < count($header); $i++)
                    $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
                  $this->Ln();
                }
              }
         //เช็คข้อมูลซ้ำออกมาตัวเดียว
      for ($i=0; $i <count($inner_array[$field[0]]) ; $i++) {
         if ($inner_array[$field[0]]==$date) {
               $count++;
               $this->Cell($w[0], 10, "", 1, 0, 'C');
               $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
               $loop++;
         }
       else {
         $count=1;
         $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
         $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $count), 1, 0, 'C');
         $date=$inner_array[$field[0]];
         $loop=0;
         $check++;
         $name[]=$inner_array[$field[0]];
       }
       $sum_loop[]=$loop;
       $sum_check[]=$check;
       }

        $this->SetFont('THSarabun', '', 12);
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'R');
        $this->Ln();
        $totalsum += $inner_array[$field[3]];
        $r++;
      }
      //นับจำนวนของเเต่ละวันที่
      if ($rows % 25 >=16) {
        $pdf->AddPage("P", "A4");
      }
$count_value = array_count_values($sum_check);
$total=array_sum($count_value);
    }
    $this->SetFont('THSarabun', 'b', 12);
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620",'Total'), 1, 0, 'L');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",$total ), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $totalsum), 1, 1, 'R');
    for ($i=0; $i <$check ; $i++) {
      $this->SetFont('THSarabun', '', 12);
      $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620",$name[$i]), 1, 0, 'L');
      $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",$count_value[$i+1] ), 1, 0, 'C');
      $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", ''), 1, 0, 'C');
      $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", ''), 1, 1, 'R');
    }



    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
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
        factory.facname,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        INNER JOIN department ON department.depcode =dirty.depcode
        INNER JOIN site ON site.hptcode =department.hptcode
        $where
        AND  factory.FacCode = '$FacCode'
        AND  site.HptCode = '$HptCode'
        " ;

$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $facname = $Result['facname'];
}

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(1);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "โรงซัก : " . $facname), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln();

$query = "SELECT
item.ItemName,
dirty_detail.Weight,
department.DepName
FROM
department
INNER JOIN dirty ON dirty.DepCode = department.DepCode
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN factory ON dirty.FacCode = factory.FacCode
INNER JOIN item ON item.itemcode = dirty_detail.itemcode
          $where
          AND factory.FacCode = '$FacCode'
          AND department.HptCode = '$HptCode'
          ORDER BY item.ItemName , department.DepName
          ";
// Number of column
$numfield = 4;
// Field data (Must match with Query)
$field = "ItemName, ,DepName,Weight";
// Table header
$header = array("ชื่อ", 'ลำดับ', 'แผนก', 'น้ำหนัก(กิโลกรัม)');
// width of column table
$width = array(50,60,40,40);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Dirty_Linen_Weight' . $ddate . '.pdf');
