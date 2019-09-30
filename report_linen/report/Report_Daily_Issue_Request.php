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
$cycle=$data['cycle'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
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
// print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (shelfcount.Docdate) = DATE('$date1')";
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
      $where = "WHERE  year (shelfcount.Docdate) LIKE '%$date1%'";
      if ($language == "th") {
        $date1 = $date1 + 543;
        $date_header = $array['year'][$language] . " " . $date1;
      } else {
        $date_header = $array['year'][$language] . $date1;
      }
    }
}
elseif($chk == 'between'){
  $where =   "WHERE shelfcount.Docdate BETWEEN '$date1' AND '$date2'";
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
    $where =   "WHERE month (shelfcount.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $date_header = $array['month'][$language]  ." " . $datetime->getTHmonthFromnum($date1);
      }else{
        $date_header = $array['month'][$language] ." " . $datetime->getmonthFromnum($date1);
      }

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(shelfcount.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $datetime = new DatetimeTH();
  list($year, $mouth, $day) = explode("-", $betweendate1);
  list($year2, $mouth2, $day2) = explode("-", $betweendate2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year = $year + 543;
    $year2 = $year2 + 543;
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2) . " $year2 ";
  } else {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2) . " $year2 ";
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
        shelfcount.DocNo,
        DATE(shelfcount.DocDate) AS DocDate,
        TIME(shelfcount.DocDate) AS DocTime,
        department.DepName,
        shelfcount.CycleTime,
        site.HptName,
				TIME(shelfcount.DvStartTime) as  DvStartTime
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        INNER JOIN site ON site.HptCode = department.HptCode
        $where
        AND department.DepCode = $depcode
        AND shelfcount.CycleTime = '$cycle'
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $Delivery = $Result['DvStartTime'];
  $CycleTime = $Result['CycleTime'];
  $HptName = $Result['HptName'];
}
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}

  // $image="../images/Nhealth_linen 4.0.png";
  // $pdf-> Image($image,10,10,43,15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r4'][$language]), 0, 1, 'C');
$pdf->SetFont('THSarabun', 'b', 14  );
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]." : ".$HptName), 0, 1, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['ward'][$language]." : ". $DeptName), 0, 1, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['date'][$language]." : ". $date_header), 0, 1, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['shelfcounttime'][$language]), 0, 1, 'L');
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['deliverytime'][$language]), 0, 1, 'L');
$pdf->Ln(5);



$pdf->SetFont('THSarabun', '', 14);

$pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", $array2['no'][$language]), 1, 0, 'C');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['itemname'][$language]), 1, 0, 'C');
$pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $array2['parqty'][$language]), 1, 0, 'C');
$pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $array2['shelfcount1'][$language]), 1, 0, 'C');
$pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $array2['max'][$language]), 1, 0, 'C');
$pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $array2['issue'][$language]), 1, 0, 'C');
$pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $array2['short'][$language]), 1, 0, 'C');
$pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $array2['over'][$language]), 1, 1, 'C');

$query = "SELECT
          item.ItemName,
          item.weight,
          IFNULL(shelfcount_detail.ParQty,0) AS ParQty,
          IFNULL(shelfcount_detail.CcQty,0) AS CcQty,
          IFNULL(shelfcount_detail.TotalQty,0) AS TotalQty,
          IFNULL(shelfcount_detail.Over,0) AS OverPar,
          IFNULL(shelfcount_detail.Short,0) AS Short,
          IFNULL(item.Weight,0) AS Weight
          FROM
          shelfcount
          INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
          INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
          INNER JOIN department ON shelfcount.DepCode = department.DepCode
          $where
          AND department.DepCode = $depcode
          AND shelfcount.CycleTime = '$cycle'";
$meQuery = mysqli_query($conn, $query);
$i = 1;
$header = 0;
$pdf->SetFont('THSarabun', '', 14);
while ($Result = mysqli_fetch_assoc($meQuery)) {

$issue=$Result['ParQty']-$Result['CcQty'];
  $pdf->Cell(20, 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'C');
  $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 0, 'L');
  $pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $Result['ParQty']), 1, 0, 'C');
  $pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $Result['CcQty']), 1, 0, 'C');
  $pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $issue), 1, 0, 'C');
  $pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $issue), 1, 0, 'C');
  $pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $Result['Short']), 1, 0, 'C');
  $pdf->Cell(21.5, 10, iconv("UTF-8", "TIS-620", $Result['OverPar']), 1, 1, 'C');
  $i++;
}

//CHECK FOOTER NEXT PAGE


// $pdf->Ln();

// $pdf->SetFont('THSarabun', 'b', 10);
// $pdf->SetFont('THSarabun', 'b', 11);
// $pdf->Cell(5);
// $pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "Sign...................................................Packing"), 0, 0, 'L');
// $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date........................................Time............................."), 0, 0, 'L');
// $pdf->Ln(7);
// $pdf->Cell(5);
// $pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "Sign...................................................Passenger"), 0, 0, 'L');
// $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date........................................Time............................."), 0, 0, 'L');
// $pdf->Ln(7);
// $pdf->Cell(5);
// $pdf->Cell(120, 10, iconv("UTF-8", "TIS-620", "Sign...................................................Receiver"), 0, 0, 'L');
// $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "Date........................................Time............................."), 0, 0, 'L');
// $pdf->Ln(7);
// $pdf->Ln(7);
// $pdf->Cell(5);
// $image1 = "chb.jpg";
// $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 5);
// $pdf->Cell(7);
// $pdf->Cell(30, 5, iconv("UTF-8", "TIS-620", "Check"), 0, 0, 'L');
// $image2 = "chb.jpg";
// $pdf->Image($image2, $pdf->GetX(), $pdf->GetY(), 5);
// $pdf->Cell(7);
// $pdf->Cell(40, 5, iconv("UTF-8", "TIS-620", "Not Check"), 0, 0, 'L');
// $pdf->Ln(7);

// Footer Table
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Daily_Issue_Request_' . $ddate . '.pdf');
