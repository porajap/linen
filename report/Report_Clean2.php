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
    $language = $_GET['lang'];
    $datetime = new DatetimeTH();

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
    $edate = $eDate[0] . " " . $datetime->getTHmonthFromnum($eDate[1]) . " พ.ศ. " . $datetime->getTHyear($eDate[2]);

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
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['rfid_clean'][$language]), 0, 0, 'C');
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
    $language = $_GET['lang'];
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 13);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", $array['page'][$language]) . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  // function setTable($pdf,$header,$data,$width,$numfield,$field)
  // {$xml = simplexml_load_file('../xml/report_lang.xml');
  //   $image="../report_linen/images/chk1.png";
  //   // $this-> Image($image,5,5,5,5);
  //   $json = json_encode($xml);
  //   $array = json_decode($json,TRUE);
  //   $language = $_GET['lang'];
  //   $total = 0;
  //   $wtotal = 0;
  //   $field = explode(",",$field);
  //   // Column widths
  //   $w = $width;
  //   // Header
  //   $this->SetFont('THSarabun','b',16);
  //   for($i=0;$i<count($header);$i++)
  //       $this->Cell($w[$i],7,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
  //   $this->Ln();

  //   // set Data Details
  //   $count = 0;
  //   $this->SetFont('THSarabun','',14);
  //   if(is_array($data)){
  //   foreach($data as $data=>$inner_array){
  //     $this->Cell($w[0],6,iconv("UTF-8","TIS-620",$count+1),1,0,'C');
  //     $this->Cell($w[1],6,iconv("UTF-8","TIS-620","  ".$inner_array[$field[1]]),1,0,'L');
  //     $this->Cell($w[2],6,iconv("UTF-8","TIS-620",$inner_array[$field[2]]." "),1,0,'C');
  //     $this->Cell($w[3],6,iconv("UTF-8","TIS-620",$inner_array[$field[2]]." "),1,0,'C');
  //     $this->Cell( $w[4],6, $this->Image($image, $pdf->GetX()+18, $pdf->GetY()+1,3), 1, 0, 'C' );
  //     $this->Ln();
  //     $total=$inner_array[$field[3]] + $total;
  //     $count++;
  //     break;
  //   }

  // }

  // }

}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

$DocNo = $_GET['DocNo'];

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
$Sql = "SELECT   site.$HptName,
        department.DepName,
        clean.DocNo,
        DATE_FORMAT(clean.DocDate,'%d-%m-%Y')AS DocDate,
        clean.Total,
        CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
        TIME(clean.Modify_Date) AS xTime,
        clean.RefDocNo
        FROM clean
        INNER JOIN department ON clean.DepCode = department.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        INNER JOIN users ON clean.Modify_Code = users.ID
        WHERE clean.DocNo = '$DocNo'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
  $RefDocNo = $Result['RefDocNo'];
}

list($d,$m,$y)=explode('-',$DocDate);
if($language == 'th'){
  $y = $y+543;
}else{
  $y =$y;
}
$DocDate = $d."-".$m."-".$y;
$pdf->SetFont('THSarabun', 'b', 16);
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $HptName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['department'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DepName), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $DocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docdate'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DocDate), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['refdocno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $RefDocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['time'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $xTime), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['user'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $FirstName), 0, 0, 'L');
$pdf->Ln();
$pdf->Ln(5);

$i = 0;
$image = "../report_linen/images/chk1.png";
$w = array(15, 60, 35, 40, 40);
$header = array($array['no'][$language], $array['itemname'][$language], "RFID " . $array['dirty'][$language], "RFID " . $array['clean'][$language], "RFID ที่รหัสตรงกัน ");
for ($i = 0; $i < count($header); $i++) {
  $pdf->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
}
$pdf->Ln();

$query = "SELECT
dirty_detail_sub.ItemCode
FROM item
INNER JOIN dirty_detail_sub ON dirty_detail_sub.ItemCode = item.ItemCode
WHERE dirty_detail_sub.DocNo = (SELECT clean.RefDocNo FROM clean WHERE clean.DocNo  = '$DocNo')
GROUP BY item.ItemCode
ORDER BY
item.ItemCode ASC
";
$meQueryy = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQueryy)) {
  $ItemCode[] = $Result['ItemCode'];
}
for ($i = 0; $i < sizeof($ItemCode); $i++) {
  $dirty = "SELECT
                      count(dirty_detail_sub.ItemCode) as count_dirty,
                      item.itemname
                      FROM dirty_detail_sub
                      INNER JOIN ITEM ON ITEM.itemCode = dirty_detail_sub.itemcode
                      WHERE dirty_detail_sub.itemCode ='$ItemCode[$i]'
                      AND dirty_detail_sub.DocNo = (SELECT clean.RefDocNo FROM clean WHERE clean.DocNo  = '$DocNo')
                      GROUP BY dirty_detail_sub.ItemCode
                      ORDER BY
                      dirty_detail_sub.ItemCode ASC";
  $pdf->Cell($w[0], 6, iconv("UTF-8", "TIS-620", $i + 1), 1, 0, 'C');

  $meQueryy = mysqli_query($conn, $dirty);
  while ($Result = mysqli_fetch_assoc($meQueryy)) {
    $count_dirty = $Result['count_dirty'];
    $itemname = $Result['itemname'];
    $pdf->Cell($w[1], 6, iconv("UTF-8", "TIS-620", $itemname), 1, 0, 'L');
    $pdf->Cell($w[2], 6, iconv("UTF-8", "TIS-620", $count_dirty), 1, 0, 'L');
  }
  $clean = "SELECT
                      count(clean_detail_sub.ItemCode) as count_clean
                      FROM clean_detail_sub
                      WHERE clean_detail_sub.ItemCode ='$ItemCode[$i]'
                      AND clean_detail_sub.DocNo = '$DocNo'
                      GROUP BY clean_detail_sub.ItemCode
                      ORDER BY
                      clean_detail_sub.ItemCode ASC";
  $meQueryy = mysqli_query($conn, $clean);
  while ($Result = mysqli_fetch_assoc($meQueryy)) {
    $count_clean = $Result['count_clean'];
    $pdf->Cell($w[3], 6, iconv("UTF-8", "TIS-620", $count_clean), 1, 0, 'L');
  }
  if ($count_clean == $count_dirty) {
    $pdf->Cell($w[4], 6, $pdf->Image($image, $pdf->GetX() + 18, $pdf->GetY() + 1, 3), 1, 0, 'C');
  } else {
    $pdf->Cell($w[4], 6, iconv("UTF-8", "TIS-620", ""), 1, 1, 'L');
  }
  $pdf->ln();
}



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
$pdf->Output('I', 'Report_Clean2_' . $ddate . '.pdf');
