<?php
// require('fpdf.php');
// require('../connect/connect.php');
// require('Class.php');
// header('Content-Type: text/html; charset=utf-8');
// date_default_timezone_set("Asia/Bangkok");
// // Date
// // $eDate = "2018-06-06";
// $eDate = $_GET['eDate'];
// $eDate = explode("/", $eDate);
// $eDate = $eDate[2] . '-' . $eDate[1] . '-' . $eDate[0];

// $dept = $_GET['dept'];
// $DocNo = $_GET['DocNo'];
// $language = $_GET['lang'];
// if ($language == "en") {
//   $language = "en";
// } else {
//   $language = "th";
// }

// header('Content-type: text/html; charset=utf-8');
// $xml = simplexml_load_file('../xml/report_lang.xml');
// $json = json_encode($xml);
// $array = json_decode($json, TRUE);

// class PDF extends FPDF
// {
//   function Header()
//   {
//     $xml = simplexml_load_file('../xml/report_lang.xml');
//     $json = json_encode($xml);
//     $array = json_decode($json, TRUE);
//     $datetime = new DatetimeTH();
//     $language = $_GET['lang'];
//     $datetime = new DatetimeTH();
//     $eDate = $_GET['eDate'];
//     $eDate = explode("/", $eDate);
//     // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
//     $printdate = date('d-m-Y');
//     $edate = $eDate[0] . " " . $datetime->getTHmonthFromnum($eDate[1]) . " พ.ศ. " . $datetime->getTHyear($eDate[2]);

//     if ($this->page == 1) {
//       // Move to the right
//       $this->SetFont('THSarabun', '', 12);
//       $this->Cell(190, 10, iconv("UTF-8", "TIS-620", $array['printdate'][$language] . ' ' . $printdate), 0, 0, 'R');
//       $this->Ln(10);
//       $image="../report_linen/images/Nhealth_linen 4.0.png";
//       $this-> Image($image,10,10,43,15);
//       $this->Ln(10);
//       // Title
//       $this->SetFont('THSarabun', 'b', 21);
//       $this->Cell(80);
//       $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r4'][$language]), 0, 0, 'C');
//       // Line break
//       $this->Ln(10);
//     } else {
//       $this->Ln(15);
//     }
//   }

//   // Page footer
//   function Footer()
//   {
//     $xml = simplexml_load_file('../xml/report_lang.xml');
//     $json = json_encode($xml);
//     $array = json_decode($json, TRUE);
//     $datetime = new DatetimeTH();
//     $language = $_GET['lang'];
//     // Position at 1.5 cm from bottom
//     $this->SetY(-15);
//     // Arial italic 8
//     $this->SetFont('THSarabun', 'i', 13);
//     // Page number
//     $this->Cell(0, 10, iconv("UTF-8", "TIS-620", $array['page'][$language]) . $this->PageNo() . '/{nb}', 0, 0, 'R');
//   }

//   function setTable($pdf, $header, $data, $width, $numfield, $field,$private)
//   {
//     $field = explode(",", $field);
//     // Column widths
//     $w = $width;
//     // Header
//     $this->SetFont('THSarabun', 'b', 14);
//     for ($i = 0; $i < count($header); $i++)
//       $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
//     $this->Ln();

//     // set Data Details
//     $count = 0;
//     $this->SetFont('THSarabun', '', 14);
//     if (is_array($data)) {
//       foreach ($data as $data => $inner_array) {
//         $issue = $inner_array[$field[2]] - $inner_array[$field[3]];
//         $price = $issue * $inner_array[$field[6]];
//         $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
//         $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
//         $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", "  " . $inner_array[$field[2]]), 1, 0, 'C');
//         $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]] . " "), 1, 0, 'C');
//         $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $issue . " "), 1, 0, 'C');
//         $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[5]] . " "), 1, 0, 'C');
//         $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[6]] . " "), 1, 0, 'C');
//         if ($private == 1) {
//         $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[7]],2) . " "), 1, 0, 'C');
//         }
//         $this->Ln();
//         $count++;
//       }
//     }


//     // Closing line
//     $pdf->Cell(array_sum($w), 0, '', 'T');
//   }
// }

// // *** Prepare Data Resource *** //
// // Instanciation of inherited class
// $pdf = new PDF();
// $font = new Font($pdf);
// $data = new Data();
// $datetime = new DatetimeTH();

// $DocNo = $_GET['DocNo'];

// // Using Coding
// $pdf->AddPage();

// if ($language == 'th') {
//   $HptName = HptNameTH;
//   $FacName = FacNameTH;
// } else {
//   $HptName = HptName;
//   $FacName = FacName;
// }
// $Sql = "SELECT   site.$HptName,
// site.HptCode,
//         department.DepName,
//         shelfcount.DocNo,
//         shelfcount.CycleTime,
//         DATE_FORMAT(shelfcount.DocDate,'%d-%m-%Y')AS DocDate,
//         shelfcount.Total,
//         users.FName,
//         TIME(shelfcount.Modify_Date) AS xTime,
//         shelfcount.IsStatus,
//         time_sc.TimeName AS CycleTime
//         FROM shelfcount
//         INNER JOIN department ON shelfcount.DepCode = department.DepCode
//         INNER JOIN site ON department.HptCode = site.HptCode
//         INNER JOIN users ON shelfcount.Modify_Code = users.ID
//         INNER JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
//         WHERE shelfcount.DocNo = '$DocNo'";
// $meQuery = mysqli_query($conn, $Sql);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $HptName = $Result[$HptName];
//   $HptCode = $Result['HptCode'];
//   $DepName = $Result['DepName'];
//   $DocNo = $Result['DocNo'];
//   $DocDate = $Result['DocDate'];
//   $Total = $Result['Total'];
//   $FirstName = $Result['FName'];
//   $xTime = $Result['xTime'];
//   $CycleTime = $Result['CycleTime'];
// }
// list($day,$month,$year)=explode('-',$DocDate);
// if($language == 'th'){
//   $year= $year+543;
// }
// $DocDate = $day."-".$month."-".$year;
// $pdf->SetFont('THSarabun', 'b', 16);
// $pdf->Cell(15);
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]), 0, 0, 'L');
// $pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $HptName), 0, 0, 'L');
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['department'][$language]), 0, 0, 'L');
// $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DepName), 0, 0, 'L');
// $pdf->Ln();
// $pdf->Cell(15);
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docno'][$language]), 0, 0, 'L');
// $pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $DocNo), 0, 0, 'L');
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docdate'][$language]), 0, 0, 'L');
// $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DocDate), 0, 0, 'L');
// $pdf->Ln();
// $pdf->Cell(15);
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['user'][$language]), 0, 0, 'L');
// $pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $FirstName . " " . $LastName), 0, 0, 'L');
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['time'][$language]), 0, 0, 'L');
// $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $xTime), 0, 0, 'L');
// $pdf->Ln();
// $pdf->Cell(15);
// $pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['Cycle'][$language]), 0, 0, 'L');
// $pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $CycleTime), 0, 0, 'L');
// $pdf->Ln(15);
// $query = "SELECT
// item.ItemName,
// item.weight,
// IFNULL(shelfcount_detail.ParQty,0) AS ParQty,
// IFNULL(shelfcount_detail.CcQty,0) AS CcQty,
// IFNULL(shelfcount_detail.TotalQty,0) AS TotalQty,
// IFNULL(shelfcount_detail.Short,0) AS Short,
// IFNULL(item.Weight,0) AS Weight,
// (category_price.price * item.Weight) as total
// FROM
// shelfcount
// INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
// INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
// INNER JOIN category_price ON item.categorycode = category_price.categorycode
// INNER JOIN department ON shelfcount.DepCode = department.DepCode
// WHERE shelfcount.DocNo = '$DocNo'
// AND category_price.HptCode = '$HptCode'
// ORDER BY item.ItemCode
//           ";
// // var_dump($query); die;
// // Number of column
// $queryy = "SELECT
// site.private,
// site.government
// FROM
// site
// WHERE site.HptCode = '$HptCode' ";
// $meQuery = mysqli_query($conn, $queryy);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $private = $Result['private'];
//   $government = $Result['government'];
// }
 
// if ($private == 1) {
// $numfield = 7;
// // Field data (Must match with Query)
// $field = ",ItemName,ParQty,CcQty,,TotalQty,Weight,total";
// // Table header
// $header = array($array['no'][$language], $array['itemname'][$language], $array['parqty'][$language], $array['shelfcount1'][$language], $array['max'][$language], $array['issue'][$language], $array['weight'][$language], $array['price1'][$language]);
// // width of column table
// $width = array(10, 40, 140 / 6, 140 / 6, 140 / 6, 140 / 6, 140 / 6, 140 / 6);
// // Get Data and store in Result
// $result = $data->getdata($conn, $query, $numfield, $field);
// // Set Table
// $pdf->SetFont('THSarabun', 'b', 12);
// $pdf->setTable($pdf, $header, $result, $width, $numfield, $field,$private);
// $pdf->Ln();
// }
// if ($government == 1) {
//   $numfield = 7;
//   // Field data (Must match with Query)
//   $field = ",ItemName,ParQty,CcQty,,TotalQty,Weight,total";
//   // Table header
//   $header = array($array['no'][$language], $array['itemname'][$language], $array['parqty'][$language], $array['shelfcount1'][$language], $array['max'][$language], $array['issue'][$language], $array['weight'][$language]);
//   // width of column table
//   $width = array(10, 40, 140 / 5, 140 / 5, 140 / 5, 140 / 5, 140 / 5);
//   // Get Data and store in Result
//   $result = $data->getdata($conn, $query, $numfield, $field);
//   // Set Table
//   $pdf->SetFont('THSarabun', 'b', 12);
//   $pdf->setTable($pdf, $header, $result, $width, $numfield, $field,$private);
//   $pdf->Ln();
//   }
  
//   $pdf->Ln(10);
//   $pdf->SetFont('THSarabun', 'b', 11);
//   $pdf->Cell(5);
//   $pdf->Cell(110, 10, iconv("UTF-8", "TIS-620", $array['sign'][$language]."...................................................".$array['packing'][$language]), 0, 0, 'L');
//   $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", $array['date'][$language]."........................................".$array['time'][$language]."............................."), 0, 0, 'L');
//   $pdf->Ln(7);
//   $pdf->Cell(5);
//   $pdf->Cell(110, 10, iconv("UTF-8", "TIS-620", $array['sign'][$language]."...................................................".$array['passenger'][$language]), 0, 0, 'L');
//   $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", $array['date'][$language]."........................................".$array['time'][$language]."............................."), 0, 0, 'L');
//   $pdf->Ln(7);
//   $pdf->Cell(5);
//   $pdf->Cell(110, 10, iconv("UTF-8", "TIS-620", $array['sign'][$language]."...................................................".$array['receiver'][$language]), 0, 0, 'L');
//   $pdf->Cell(35, 10, iconv("UTF-8", "TIS-620", $array['date'][$language]."........................................".$array['time'][$language]."............................."), 0, 0, 'L');
//   $pdf->Ln(7);
//   $pdf->Ln(7);
//   $pdf->Cell(5);
//   $image1 = "../report_linen/images/chb.jpg";
//   $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 5);
//   $pdf->Cell(7);
//   $pdf->Cell(30, 5, iconv("UTF-8", "TIS-620", "Check"), 0, 0, 'L');
//   $image2 = "../report_linen/images/chb.jpg";
//   $pdf->Image($image2, $pdf->GetX(), $pdf->GetY(), 5);
//   $pdf->Cell(7);
//   $pdf->Cell(40, 5, iconv("UTF-8", "TIS-620", "Not Check"), 0, 0, 'L');
//   $pdf->Ln(7);
// // // Get $totalsum
// // $totalsum = 0;
// // if(is_array($result)){
// //   foreach($result as $result=>$inner_array){
// //     $totalsum += $inner_array['Qty'];
// //   }
// // }
// // // Footer Table
// // $footer = array('','','รวม',$totalsum,'');
// // $pdf->SetFont('THSarabun','b',14);
// // for($i=0;$i<count($footer);$i++)
// //   $pdf->Cell($width[$i],7,iconv("UTF-8","TIS-620",$footer[$i]),1,0,'C');
// // $pdf->Ln(8);


// // $pdf->SetFont('THSarabun','b',10);
// // $pdf->setFillColor(230,230,230);
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'พร7A'),'LTR',1,'L');
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'A TEC Trimavie 13-09'),'LR',1,'L');
// // $pdf->Cell(25,5,iconv("UTF-8","TIS-620",'เตรียม: ศุภานัน '),'L',0,'L');
// // $pdf->Cell(25,5,iconv("UTF-8","TIS-620",' ตรวจ: เล็กข้างกาด'),'R',1,'R');
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'ห่อ : รจนา  ฆ่าเชื้อ : รจนา'),'LR',1,'L');
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'เครื่อง :SA & FH /รอบ: 1 No.001659'),'LR',1,'L');
// // $pdf->SetFont('THSarabun','b',12);
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'EXP : 11/02/63'),'LR',1,'R');
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'ผลิต 16/08/62'),'LR',1,'R');
// // $pdf->Cell(50,5,iconv("UTF-8","TIS-620",),'LBR',1,'L');

// $ddate = date('d_m_Y');
// $pdf->Output('I', 'Report_Stock_' . $ddate . '.pdf');
// }

require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$docno = $_GET['Docno'];
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$depcode = $data['DepCode'];
$format = $data['Format'];
$cycle = $data['cycle'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
$where = '';
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
// if($chk == 'one'){
//   if ($format == 1) {
//     $where =   "WHERE DATE (shelfcount.Docdate) = DATE('$date1')";
//     list($year,$mouth,$day) = explode("-", $date1);
//     $datetime = new DatetimeTH();
//     if ($language == 'th') {
//       $year = $year + 543;
//       $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
//     } else {
//       $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
//     }
//   }
//   elseif ($format = 3) {
//       $where = "WHERE  year (shelfcount.Docdate) LIKE '%$date1%'";
//       if ($language == "th") {
//         $date1 = $date1 + 543;
//         $date_header = $array['year'][$language] . " " . $date1;
//       } else {
//         $date_header = $array['year'][$language] . $date1;
//       }
//     }
// }
// elseif($chk == 'between'){
//   $where =   "WHERE shelfcount.Docdate BETWEEN '$date1' AND '$date2'";
//   list($year,$mouth,$day) = explode("-", $date1);
//   list($year2,$mouth2,$day2) = explode("-", $date2);
//   $datetime = new DatetimeTH();
//   if ($language == 'th') {
//     $year2=$year2+543;
//     $year=$year+543;
//     $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . $array['to'][$language] .
//       $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
//   } else {
//     $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth)." " . $year ." " . $array['to'][$language] ." " .
//          $day2 . " " . $datetime->getmonthFromnum($mouth2) . $year2;
//   }

// }
// elseif($chk == 'month'){
//     $where =   "WHERE month (shelfcount.Docdate) = ".$date1;
//     $datetime = new DatetimeTH();
//     if ($language == 'th') {
//       $date_header = $array['month'][$language]  ." " . $datetime->getTHmonthFromnum($date1);
//       }else{
//         $date_header = $array['month'][$language] ." " . $datetime->getmonthFromnum($date1);
//       }

// }
// elseif ($chk == 'monthbetween') {
//   $where =   "WHERE date(shelfcount.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
//   $datetime = new DatetimeTH();
//   list($year, $mouth, $day) = explode("-", $betweendate1);
//   list($year2, $mouth2, $day2) = explode("-", $betweendate2);
//   $datetime = new DatetimeTH();
//   if ($language == 'th') {
//     $year = $year + 543;
//     $year2 = $year2 + 543;
//     $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2) . " $year2 ";
//   } else {
//     $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2) . " $year2 ";
//   }
// }


header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$docno = $_GET['DocNo'];
class PDF extends FPDF
{
  function Header()
  { }
  // Page footer
  function Footer()
  {
    if ($this->isFinished) {
      $this->SetFont('THSarabun', '', 10);
      $this->SetY(-45);
      $xml = simplexml_load_file('../xml/general_lang.xml');
      $xml2 = simplexml_load_file('../xml/report_lang.xml');
      $json = json_encode($xml);
      $array = json_decode($json, TRUE);
      $json2 = json_encode($xml2);
      $array2 = json_decode($json2, TRUE);
      $language = $_SESSION['lang'];
      $this->SetFont('THSarabun', 'b', 11);
      $this->Cell(5);
      $this->Cell(110, 10, iconv("UTF-8", "TIS-620", $array2['sign'][$language] . "..................................................." . $array2['packing'][$language]), 0, 0, 'L');
      $this->Cell(35, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "........................................" . $array2['time'][$language] . "............................."), 0, 0, 'L');
      $this->Ln(7);
      $this->Cell(5);
      $this->Cell(110, 10, iconv("UTF-8", "TIS-620", $array2['sign'][$language] . "..................................................." . $array2['passenger'][$language]), 0, 0, 'L');
      $this->Cell(35, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "........................................" . $array2['time'][$language] . "............................."), 0, 0, 'L');
      $this->Ln(7);
      $this->Cell(5);
      $this->Cell(110, 10, iconv("UTF-8", "TIS-620", $array2['sign'][$language] . "..................................................." . $array2['receiver'][$language]), 0, 0, 'L');
      $this->Cell(35, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "........................................" . $array2['time'][$language] . "............................."), 0, 0, 'L');
      $this->Ln(10);
      $this->Cell(5);
      $image1 = "../report_linen/images//chb.jpg";
      $this->Image($image1, $this->GetX(), $this->GetY(), 5);
      $this->Cell(7);
      $this->Cell(30, 5, iconv("UTF-8", "TIS-620", "Check"), 0, 0, 'L');
      $image2 = "../report_linen/images//chb.jpg";
      $this->Image($image2, $this->GetX(), $this->GetY(), 5);
      $this->Cell(7);
      $this->Cell(40, 5, iconv("UTF-8", "TIS-620", "Not Check"), 0, 0, 'L');
      $this->Ln(7);
    }
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

$queryy = "SELECT
site.private,
site.government
FROM
site
WHERE site.HptCode = 'BHQ' ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $private = $Result['private'];
  $government = $Result['government'];
}
if($private==1){
$w = array(10, 40, 20, 20, 20,20,20,15,15,15);
}elseif ($government==1){
$w = array(10, 40, 20, 20, 20,20,20,22.5,22.5);
}
$Sql = "SELECT
        shelfcount.DocNo,
        DATE(shelfcount.DocDate) AS DocDate,
        TIME(shelfcount.DocDate) AS DocTime,
        department.DepName,
        time_sc.TimeName AS CycleTime,
        site.HptName,
        sc_time_2.TimeName AS TIME , 
        time_sc.timename AS ENDTIME
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        INNER JOIN site ON site.HptCode = department.HptCode
        INNER JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
        INNER JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
        WHERE shelfcount.DocNo='$docno' 
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $TIME = $Result['TIME'];
  $ENDTIME = $Result['ENDTIME'];
  $HptName = $Result['HptName'];
}
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
list($year, $month, $day) = explode('-', $DocDate);
if ($language == 'th') {
  $year = $year + 543;
}
$DocDate = $day . "-" . $month . "-" . $year;
$image="../report_linen/images/Nhealth_linen 4.0.png";
$pdf-> Image($image,10,10,43,15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r4'][$language]), 0, 1, 'C');
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['docno'][$language] . " : " . $docno), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['hospital'][$language] . " : " . $HptName), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['ward'][$language] . " : " . $DeptName), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['date'][$language] . " : " . $DocDate), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['shelfcounttime'][$language] . " : " . $TIME), 0, 1, 'L');
$pdf->Cell(30, 7, iconv("UTF-8", "TIS-620", $array['deliverytime'][$language] . " : " . $ENDTIME), 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('THSarabun', '', 14);

$pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $array2['no'][$language]), 1, 0, 'C');
$pdf->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $array2['itemname'][$language]), 1, 0, 'C');
$pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $array2['parqty'][$language]), 1, 0, 'C');
$pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $array2['shelfcount1'][$language]), 1, 0, 'C');
$pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $array2['max'][$language]), 1, 0, 'C');
$pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $array2['issue'][$language]), 1, 0, 'C');
$pdf->Cell($w[6], 10, iconv("UTF-8", "TIS-620", $array2['short'][$language]), 1, 0, 'C');
$pdf->Cell($w[7], 10, iconv("UTF-8", "TIS-620", $array2['over'][$language]), 1, 0, 'C');
$pdf->Cell($w[8], 10, iconv("UTF-8", "TIS-620", $array2['weight'][$language]), 1, 0, 'C');

if($private == 1){
$pdf->Cell($w[9], 10, iconv("UTF-8", "TIS-620", $array2['price'][$language]), 1, 0, 'C');
}
$pdf->ln();
$query = "SELECT
item.ItemName,
item.weight,
IFNULL(shelfcount_detail.ParQty, 0) AS ParQty,
IFNULL(shelfcount_detail.CcQty, 0) AS CcQty,
IFNULL(
  shelfcount_detail.TotalQty,
  0
) AS TotalQty,
IFNULL(shelfcount_detail.Over, 0) AS OverPar,
IFNULL(shelfcount_detail.Short, 0) AS Short,
IFNULL(item.Weight, 0) AS Weight,
category_price.Price
FROM
shelfcount
INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
INNER JOIN department ON shelfcount.DepCode = department.DepCode
          WHERE shelfcount.DocNo='$docno'
          AND shelfcount_detail.TotalQty <> 0
           ";
$meQuery = mysqli_query($conn, $query);
$i = 1;
$y= 95;
$pdf->SetFont('THSarabun', '', 14);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $txt = getStrLenTH($Result['ItemName']); // 10
        $round = $txt /20;
        list($main, $point) = explode(".", $round);
        if ($point > 0) {
          $point = 1;
          $main += $point;
        }
  $issue = $Result['ParQty'] - $Result['CcQty'];
  $totalweight = $Result['TotalQty'] * $Result['Weight'];
  $price = $totalweight * $Result['Price'];
  $pdf->Cell($w[0], 10, iconv("UTF-8", "TIS-620", "$i"), 1, 0, 'C');
  $pdf->SetX($w[0]+ 10);
  $pdf->MultiCell($w[1], 10/$main, iconv("UTF-8", "TIS-620", $Result['ItemName']), 1, 'L');
  $pdf->SetXY($w[0] + $w[1]  +10, $y);
  $pdf->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($Result['ParQty'])), 1, 0, 'C');
  $pdf->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($Result['CcQty'])), 1, 0, 'C');
  $pdf->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($issue)), 1, 0, 'C');
  $pdf->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($Result['TotalQty'])), 1, 0, 'C');
  $pdf->Cell($w[6], 10, iconv("UTF-8", "TIS-620", number_format($Result['Short'])), 1, 0, 'C');
  $pdf->Cell($w[7], 10, iconv("UTF-8", "TIS-620", number_format($Result['OverPar'])), 1, 0, 'C');
  $pdf->Cell($w[8], 10, iconv("UTF-8", "TIS-620", number_format($totalweight,2)), 1, 0, 'C');
  if($private == 1){
  $pdf->Cell($w[9], 10, iconv("UTF-8", "TIS-620", number_format($price,2)), 1, 0, 'C');
  }
  $pdf->ln();
  $i++;
  $totalw += $totalweight;
  $price_total += $price;
  $y+=10;
}
$pdf->Cell(150, 7, iconv("UTF-8", "TIS-620", $array2['total_weight'][$language]), 1, 0, 'C');
$pdf->Cell($w[8]+$w[9], 7, iconv("UTF-8", "TIS-620", number_format($totalw,2)), 1, 0, 'C');
$pdf->Cell($w[8], 7, iconv("UTF-8", "TIS-620", $array2['kg'][$language]), 1, 0, 'C');
$pdf->ln();
if($private == 1){
$pdf->Cell(150, 7, iconv("UTF-8", "TIS-620", $array2['total_price'][$language]), 1, 0, 'C');
$pdf->Cell($w[8]+$w[9], 7, iconv("UTF-8", "TIS-620", number_format($price_total,2)), 1, 0, 'C');
$pdf->Cell($w[8], 7, iconv("UTF-8", "TIS-620", $array2['bath'][$language]), 1, 0, 'C');
}
function getMBStrSplit($string, $split_length = 1)
{
  mb_internal_encoding('UTF-8');
  mb_regex_encoding('UTF-8');

  $split_length = ($split_length <= 0) ? 1 : $split_length;
  $mb_strlen = mb_strlen($string, 'utf-8');
  $array = array();
  $i = 0;

  while ($i < $mb_strlen) {
    $array[] = mb_substr($string, $i, $split_length);
    $i = $i + $split_length;
  }

  return $array;
}
// Get string length for Character Thai
function getStrLenTH($string)
{
  $array = getMBStrSplit($string);
  $count = 0;

  foreach ($array as $value) {
    $ascii = ord(iconv("UTF-8", "TIS-620", $value));

    if (!($ascii == 209 || ($ascii >= 212 && $ascii <= 218) || ($ascii >= 231 && $ascii <= 238))) {
      $count += 1;
    }
  }
  return $count;
}
// CHECK FOOTER NEXT PAGE

$pdf->isFinished = true;

// Footer Table
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Shelfcount' . $ddate . '.pdf');
