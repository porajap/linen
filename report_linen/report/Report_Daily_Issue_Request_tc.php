<?php
session_start();
require('tcpdf/tcpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
//--------------------------------------------------------------------------
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
//--------------------------------------------------------------------------
$data = $_SESSION['data_send'];
$HptCode = $_SESSION['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$format = $data['Format'];
$DepCode = $data['DepCode'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
$docno = $_GET['Docno'];
//--------------------------------------------------------------------------
$where = '';
$w = array(70, 25, 60, 35);
$check = 0;
$y = 57;
$date = '';
$next_page = 1;
$fisrt_page = 0;
$r = 1;
$status = 0;
//--------------------------------------------------------------------------
$language = $_SESSION['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}
//--------------------------------------------------------------------------
//print_r($data);
//--------------------------------------------------------------------------
class MYPDF extends TCPDF
{
  protected $last_page_flag = false;

  public function Close()
  {
    $this->last_page_flag = true;
    parent::Close();
  }
  //Pag
  //Page header
  public function Header()
  {
    $HptCode = $_SESSION['HptCode'];
    require('connect.php');
    $queryy = "SELECT
              site.private,
              site.government
              FROM
              site
              WHERE site.HptCode = '$HptCode' ";
    $meQuery = mysqli_query($conn, $queryy);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $private = $Result['private'];
      $government = $Result['government'];
    }
    if ($private == 1) {
      $w = array(5, 35, 10, 10, 10, 10, 10, 10);
    } elseif ($government == 1) {
      $w = array(5, 35, 12, 12, 12, 12, 12);
    }
    $datetime = new DatetimeTH();
    $xml = simplexml_load_file('../xml/general_lang.xml');
    $xml2 = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $json2 = json_encode($xml2);
    $array2 = json_decode($json2, TRUE);
    $language = $_SESSION['lang'];
    $header = array($array2['no'][$language], $array2['itemname'][$language], $array2['parqty'][$language], $array2['shelfcount1'][$language], $array2['max'][$language], $array2['issue'][$language], $array2['short'][$language], $array2['over'][$language], $array2['weight'][$language], $array2['price'][$language]);
    if ($language == 'th') {
      $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
    } else {
      $printdate = date('d') . " " . date('F') . " " . date('Y');
    }
    if ($this->page == 1) {
      // Logo
      $image_file = "../images/Nhealth_linen 4.0.png";
      $this->Image($image_file, 10, 10, 33, 12, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      // Set font
      $this->SetFont(' thsarabunnew', '', 9);
      // Title
      $this->Cell(0, 10,  $array2['printdate'][$language] . $printdate, 0, 0, 'R');
    } else {
      $this->SetFont(' thsarabunnew', '', 9);
      $this->Cell(0, 10,  $array2['printdate'][$language] . $printdate, 0, 1, 'R');
      $this->SetFont(' thsarabunnew', '', 12);
      $this->SetY(21);
      //   $html = '<table cellspacing="0" cellpadding="1" border="1" >
      //   <tr style="font-size: 16px;">
      //   <th width="' . $w[0] . '% "  align="center">' . $header[0] . '</th>
      //   <th width="' . $w[1] . '% " align="center">' . $header[1] . '</th>
      //   <th width="' . $w[2] . '% " align="center"style="font-size: 14px;">' . $header[2] . '</th>
      //   <th width="' . $w[3] . '% " align="center"style="font-size: 14px;">' . $header[3] . '</th>
      //   <th width="' . $w[4] . '% " align="center">' . $header[4] . '</th>
      //   <th width="' . $w[5] . '% " align="center">' . $header[5] . '</th>
      //   <th width="' . $w[6] . '% " align="center"style="font-size: 14px;">' . $header[6] . '</th>
      //   <th width="' . $w[7] . '% " align="center">' . $header[7] . '</th>
      //   <th width="' . $w[8] . '% " align="center">' . $header[8] . '</th>';
      //   if ($private == 1) {
      //     $html .=   '<th width="' . $w[9] . '% " align="center">' . $header[9] . '</th>';
      //   }
      //   $html .= '</tr>';
      //   $html .= '</table>';
      //   $this->writeHTML($html, true, false, true, false, '');
    }
  }
  // Page footer
  public function Footer()
  {
    $xml = simplexml_load_file('../xml/general_lang.xml');
    $xml2 = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $json2 = json_encode($xml2);
    $array2 = json_decode($json2, TRUE);
    $language = $_SESSION['lang'];
    $docno = $_GET['Docno'];
    $packing = '';
    $passengertime = '';
    $receiver = '';
    $this->SetFont(' thsarabunnew', '', 8);
    if ($this->last_page_flag) {
      require('connect.php');
      // $head = "SELECT
      //         shelfcount.signStart AS passenger,
      //         shelfcount.signature AS receiver,
      //         shelfcount.signature_web AS packing,
      //         shelfcount.DvStartTime AS passengertime, 
      //         shelfcount.SignEndTime AS receivertime,
      //         shelfcount.PTime AS packingtime
      //           FROM
      //           shelfcount
      //           where shelfcount.DocNo ='$docno'
      // ";

      // $meQuery = mysqli_query($conn, $head);
      // while ($Result = mysqli_fetch_assoc($meQuery)) {
      //   $packing = $Result['packing'];
      //   $passenger = $Result['passenger'];
      //   $receiver = $Result['receiver'];
      //   $packingtime = $Result['packingtime'];
      //   $passengertime = $Result['passengertime'];
      //   $receivertime = $Result['receivertime'];
      // }
      list($date1, $time1) = explode(' ', $packingtime);
      list($date2, $time2) = explode(' ', $passengertime);
      list($date3, $time3) = explode(' ', $receivertime);
      list($y1, $m1, $d1) = explode('-', $date1);
      list($y2, $m2, $d2) = explode('-', $date2);
      list($y3, $m3, $d3) = explode('-', $date3);
      if ($language == 'th') {
        $y1 = $y1 + 543;
        $y2 = $y2 + 543;
        $y3 = $y3 + 543;
      } else {
        $y1 = $y1;
        $y2 = $y2;
        $y3 = $y3;
      }
      $date1 = $d1 . "-" . $m1 . "-" . $y1;
      $date2 = $d2 . "-" . $m2 . "-" . $y2;
      $date3 = $d3 . "-" . $m3 . "-" . $y3;
      if ($date1 == '--543') {
        $date1 = ' ';
      }
      if ($date2 == '--543') {
        $date2 = ' ';
      }
      if ($date3 == '--543') {
        $date3 = ' ';
      }
      $this->SetY(-40);
      // $this->SetFont('  thsarabunnew', 'b', 8);
      if ($packing != null) {
        $this->ImageSVG('@' . $packing, $x = 22, $y = 255, $w = '37', $h = '7', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      if ($passenger != null) {
        $this->ImageSVG('@' . $passenger, $x = 22, $y = 262, $w = '37', $h = '7', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      if ($receiver != null) {
        $this->ImageSVG('@' . $receiver, $x = 22, $y = 269, $w = '37', $h = '7', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }

      $this->SetFont('  thsarabunnew', 'i', 13);
      $this->Cell(90, 8,   $array2['sign'][$language] . "..................................................." . $array2['packing'][$language], 0, 0, 'L');
      $this->Cell(1, 7,  "             " . $date1 . "                           " . $time1, 0, 0, 'L');
      $this->Cell(50, 8,   $array2['date'][$language] . "........................................" . $array2['time'][$language] . ".............................", 0, 1, 'L');

      $this->Cell(90, 8,   $array2['sign'][$language] . "..................................................." . $array2['passenger'][$language], 0, 0, 'L');
      $this->Cell(1, 7,  "             " . $date2 . "                           " . $time2, 0, 0, 'L');
      $this->Cell(50, 8,   $array2['date'][$language] . "........................................" . $array2['time'][$language] . ".............................", 0, 1, 'L');


      $this->Cell(90, 8,   $array2['sign'][$language] . "..................................................." . $array2['receiver'][$language], 0, 0, 'L');
      $this->Cell(1, 7,  "             " . $date3 . "                           " . $time3, 0, 0, 'L');
      $this->Cell(50, 8,   $array2['date'][$language] . "........................................" . $array2['time'][$language] . ".............................", 0, 1, 'L');

      $image1 = "../images/chb.jpg";
      $this->Image($image1, $this->GetX(), $this->GetY(), 5);
      $this->Cell(7);
      $this->Cell(20, 7,   "Check", 0, 0, 'L');
      $image2 = "../images/chb.jpg";
      $this->Image($image2, $this->GetX(), $this->GetY(), 5);
      $this->Cell(7);
      $this->Cell(40, 7,   "Not Check", 0, 0, 'L');
      $this->Ln(7);
    }
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Page number
    $this->SetFont('  thsarabunnew', '', 14);
    $this->Cell(200, 10,  $array2['page'][$language] . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Report_Daily_Issue_Request');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 35);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ------------------------------------------------------------------------------
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$header = array($array2['no']['en'], $array2['itemname']['en'], $array2['parqty']['en'], $array2['shelfcount1']['en'], $array2['max']['en'], $array2['issue']['en'], $array2['short']['en'], $array2['over']['en'], $array2['weight']['en'], $array2['price']['en']);
$count = 1;

// // ------------------------------------------------------------------------------
$head = "SELECT
shelfcount.DocNo,
DATE(shelfcount.DocDate) AS DocDate,
TIME(shelfcount.DocDate) AS DocTime,
department.DepName,
time_sc.TimeName AS CycleTime,
site.$HptName,
site.HptCode,
sc_time_2.TimeName AS TIME , 
time_sc.timename AS ENDTIME
FROM
shelfcount
INNER JOIN department ON shelfcount.DepCode = department.DepCode
INNER JOIN site ON site.HptCode = department.HptCode
INNER JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
INNER JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
WHERE shelfcount.DocNo='$docno'
        AND shelfcount.isStatus<> 9
        ";

$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $TIME = $Result['TIME'];
  $ENDTIME = $Result['ENDTIME'];
  $HptName = $Result[$HptName];
  $HptCode = $Result['HptCode'];
}

$data = "SELECT
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
            AND shelfcount.isStatus<> 9 ";

$queryy = "SELECT
site.private,
site.government
FROM
site
WHERE site.HptCode = '$HptCode' ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $private = $Result['private'];
  $government = $Result['government'];
}

if ($private == 1) {
  $w = array(5, 35, 10, 10, 10, 10, 10, 10);
} elseif ($government == 1) {
  $w = array(5, 35, 12, 12, 12, 12, 12);
}
// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage('P', 'A4');
$pdf->SetFont('  thsarabunnew', 'b', 20);
$pdf->Cell(0, 10,  $array2['r4']['th'], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('  thsarabunnew', 'b', 16);
$pdf->Cell(30, 7,  $array2['docno']['th'] . " : " . $docno, 0, 1, 'L');
$pdf->Cell(30, 7,  $array2['hospital']['th'] . " : " . $HptName, 0, 1, 'L');
$pdf->Cell(30, 7,  $array2['ward']['th'] . " : " . $DeptName, 0, 1, 'L');
$pdf->Cell(30, 7,  $array2['date']['th'] . " : " . $DocDate, 0, 1, 'L');
$pdf->Cell(30, 7,  $array2['shelfcounttime']['th'] . " : " . $TIME, 0, 1, 'L');
$pdf->Cell(30, 7,  $array2['deliverytime']['th'] . " : " . $ENDTIME, 0, 1, 'L');
$pdf->Ln(3);
$html = '<table cellspacing="0" cellpadding="1" border="1" > <thead>
<tr style="font-size: 16px;">
<th width="' . $w[0] . '% "  align="center">' . $header[0] . '</th>
<th width="' . $w[1] . '% " align="center">' . $header[1] . '</th>
<th width="' . $w[2] . '% " align="center">' . $header[2] . '</th>
<th width="' . $w[3] . '% " align="center">' . $header[3] . '</th>
<th width="' . $w[4] . '% " align="center">' . $header[4] . '</th>
<th width="' . $w[5] . '% " align="center">' . $header[5] . '</th>
<th width="' . $w[6] . '% " align="center">' . $header[8] . '</th>';
if ($private == 1) {
  $html .=   '<th width="' . $w[7] . '% " align="center">' . $header[9] . '</th>';
}
$html .= '</tr></thead>';
$pdf->SetFont('  thsarabunnew', '', 12);
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $issue = $Result['ParQty'] - $Result['CcQty'];
  $totalweight = $Result['TotalQty'] * $Result['Weight'];
  $price = $totalweight * $Result['Price'];
  $Total_Weight = $Result['Totalqty'] * $Result['Weight'];
  $html .= '<tr cellpadding="3" style="font-size: 15px;" nobr="true">';
  $html .=   '<td width="' . $w[0] . '% " align="center" height="7">' . $count . '</td>';
  $html .=   '<td width="' . $w[1] . '% " align="left"  >' . trim($Result['ItemName']) . '</td>';
  $html .=   '<td width="' . $w[2] . '% " align="center">' . $Result['ParQty'] . '</td>';
  $html .=   '<td width="' . $w[3] . '% " align="center">' . $Result['CcQty'] . '</td>';
  $html .=   '<td width="' . $w[4] . '% " align="center">' . $issue  . '</td>';
  $html .=   '<td width="' . $w[5] . '% " align="center">' .  $Result['TotalQty']  . '</td>';
  $html .=   '<td width="' . $w[6] . '% " align="center">' . $totalweight  . '</td>';
  if ($private == 1) {
    $html .=   '<td width="' . $w[7] . '% " align="center">' . $price  . '</td>';
  }
  $html .=  '</tr>';
  $totalsum_W += $totalweight;
  $price_W += $price;
  $count++;
}

$html .= ' </table>';
$pdf->writeHTML($html);


$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 6.0);
$pdf->Cell(144, 5, $array2['total_weight']['en'], 1, 0, 'C');
$pdf->Cell(36, 5, NUMBER_FORMAT($totalsum_W, 2), 1, 1, 'C');
if ($private == 1) {
  $pdf->Cell(144, 5, $array2['total_price']['en'], 1, 0, 'C');
  $pdf->Cell(36, 5, number_format($price_W, 2), 1, 0, 'C');
}
// $sum = '<div style="line-height: 100%;">555 </div><table cellspacing="0" cellpadding="1" border="1"    >';
// $sum .= '<tr>' .
//   '<td colspan= "7"  align="center">' .  '<strong>' . $array2['total_weight'][$language] . '</strong>' . '</td>' .
//   '<td colspan= "5" align="center">' . number_format($totalsum_W, 2) . '</td>' .
//   '</tr>';
// if ($private == 1) {
//   $sum .= '<tr>' .
//     '<td colspan= "7" align="center">' .  $array2['total_price'][$language] . '</td>' .
//     '<td colspan= "5" align="center">' . number_format($price_W, 2) . '</td>' .
//     '</tr>';
// }
// $sum .= '</table>';
// $pdf->writeHTML($sum);
// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Shelfcount' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
