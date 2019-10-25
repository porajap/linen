<?php
require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// Date
// $eDate = "2018-06-06";
$eDate = $_GET['eDate'];
$eDate = explode("/", $eDate);
$eDate = $eDate[2] . '-' . $eDate[1] . '-' . $eDate[0];
$DocNo = $_GET['DocNo'];
$hos = $_GET['hos'];
$date = $_GET['date'];
$recorder = $_GET['recorder'];
$timerec = $_GET['timerec'];
$fac = $_GET['fac'];


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
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $datetime = new DatetimeTH();
    $language = $_GET['lang'];
    $eDate = $_GET['eDate'];
    $eDate = explode("/", $eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d-m-Y');
    list($day, $mouth, $year) = explode("-", $printdate);
    if ($language == 'th') {
      $year = $year + 543;
      $date_header =  $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 12);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", $array['printdate'][$language] . ' ' . $date_header), 0, 0, 'R');
      $this->Ln(10);
      // Title
      $image="../report_linen/images/Nhealth_linen 4.0.png";
      $this-> Image($image,10,10,43,15);
      $this->Ln(10);
      $this->SetFont('THSarabun', 'b', 21);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620",  $array['rfid_dirty'][$language]), 0, 0, 'C');
      // Line break
      $this->Ln(10);
    } else {
      $this->Ln(15);
    }
  }

  // Page footer
  function Footer()
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $datetime = new DatetimeTH();
    $language = $_GET['lang'];
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 13);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", $array['page'][$language]) . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $language = $_GET['lang'];
    $total = 0;
    $wtotal = 0;
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 16);
    $this->Cell(10, 7, iconv("UTF-8", "TIS-620",""), 0, 0, 'C');
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $this->Cell(10, 6, iconv("UTF-8", "TIS-620",""), 0, 0, 'C');
        $this->Cell($w[0], 6, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
        $this->Cell($w[1], 6, iconv("UTF-8", "TIS-620", "  " . $inner_array[$field[1]]), 1, 0, 'L');
        $this->Cell($w[2], 6, iconv("UTF-8", "TIS-620", $inner_array[$field[2]] ), 1, 0, 'C');
        $this->Ln();
        $count++;
      }
    }
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
$pdf->AddPage();



if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
  $Perfix = THPerfix;
  $Name = THName;
  $LName = THLName;
} else {
  $HptName = HptName;
  $FacName = FacName;
  $Perfix = EngPerfix;
  $Name = EngName;
  $LName = EngLName;
}
$Sql = "SELECT site.$HptName,
        factory.$FacName,
        DATE_FORMAT(dirty.DocDate,'%d-%m-%Y')AS DocDate,
        TIME(dirty.Modify_Date) AS xTime,
        CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName
        FROM dirty
        INNER JOIN site ON dirty.HptCode = site.HptCode
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        INNER JOIN users ON dirty.Modify_Code = users.ID
        WHERE dirty.DocNo = '$DocNo'";
// echo $Sql;
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $FacName = $Result[$FacName];
  $xTime = $Result['xTime'];
  $FName = $Result['FName'];
  $DocDate = $Result['DocDate'];
}
list($d,$m,$y)=explode('-',$DocDate);
if($language == 'th'){
  $y = $y+543;
}else{
  $y =$y;
}
$DocDate = $d."-".$m."-".$y;
$hos . $date . $recorder . $timerec . $fac;
$pdf->SetFont('THSarabun', 'b', 16);
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $HptName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['factory'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $FacName), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $DocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docdate'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", ": " . $DocDate), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['user'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", ": " . $FName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['time'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", ": " . $xTime), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);

$pdf->SetMargins(15, 0, 0);
$pdf->Ln();
$pdf->Ln(5);
$query = "SELECT
item.ItemName,
count(dirty_detail_sub.ItemCode) as Qty
FROM item
INNER JOIN dirty_detail_sub ON dirty_detail_sub.ItemCode = item.ItemCode
WHERE dirty_detail_sub.DocNo = '$DocNo'
GROUP BY item.ItemCode
ORDER BY
item.ItemCode ASC
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = ",ItemName,Qty";
// Table header
$header = array($array['no'][$language],  $array['itemname'][$language],$array['qty'][$language]." RFID");
// width of column table
$width = array(30, 85, 45);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
$pdf->Ln(8);
$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", $array['comlinen'][$language] . "..................................................."), 0, 0, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['comlaundry'][$language] . "........................................"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", $array['date'][$language] . "......................................................................"), 0, 0, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['date'][$language] . ".........................................................."), 0, 0, 'L');
$pdf->Ln(7);

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Dirty2_' . $ddate . '.pdf');
