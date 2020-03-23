<?php
session_start();
require('../tcpdf/tcpdf.php');
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
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$format = $data['Format'];
$DepCode = $data['DepCode'];
$betweendate1 = $data['betweendate1'];
$betweendate2 = $data['betweendate2'];
$DocNo = $_GET['DocNo'];
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
  //Page header
  public function Header()
  {
    $datetime = new DatetimeTH();
    $xml = simplexml_load_file('../xml/general_lang.xml');
    $xml2 = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $json2 = json_encode($xml2);
    $array2 = json_decode($json2, TRUE);
    $language = $_SESSION['lang'];
    $header = array($array['no'][$language], $array['itemname'][$language], $array['qty'][$language], $array['unit'][$language], $array['weight'][$language] . ' (kg)');
    if ($language == 'th') {
      $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
    } else {
      $printdate = date('d') . " " . date('F') . " " . date('Y');
    }
    if ($this->page == 1) {
      // Logo
      $image_file = "../report_linen/images/Nhealth_linen 4.0.png";
      $this->Image($image_file, 10, 10, 33, 12, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      // Set font
      $this->SetFont('thsarabunnew', '', 9);
      // Title
      $this->Cell(0, 10,  $array2['printdate'][$language] . $printdate, 0, 0, 'R');
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
    $docno = $_GET['DocNo'];
    $packing = '';
    $passengertime = '';
    $receiver = '';
    $this->SetFont(' thsarabunnew', '', 8);
    if ($this->last_page_flag) {
      require('connect.php');
      $head = "SELECT
      return_doc.signature_web AS sign1,
      return_doc.SignHospital AS sign2,
      return_doc.SignNH AS sign3,
      return_doc.signature_webTime AS time1, 
      return_doc.SignHospitalTime AS time2,
      return_doc.SignNHTime AS time3
      FROM
        return_doc
        where return_doc.DocNo ='$docno'
      ";

      $meQuery = mysqli_query($conn, $head);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $packing = $Result['sign1'];
        $passenger = $Result['sign2'];
        $receiver = $Result['sign3'];
        $packingtime = $Result['time1'];
        $passengertime = $Result['time2'];
        $receivertime = $Result['time3'];
      }
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
      if ($date1 == '--543' ||$date1 == '--' ) {
        $date1 = ' ';
      }
      if ($date2 == '--543'||$date2 == '--') {
        $date2 = ' ';
      }
      if ($date3 == '--543'||$date3 == '--') {
        $date3 = ' ';
      }
      $this->SetY(-40);
      // $this->SetFont('  thsarabunnew', 'b', 8);
      if ($packing != null) {
        $this->ImageSVG('@' . $packing, $x = 27, $y = 256, $w = '25', $h = '13', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      if ($passenger != null) {
        $this->ImageSVG('@' . $passenger, $x = 29, $y = 263, $w = '18', $h = '10', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      if ($receiver != null) {
        $this->ImageSVG('@' . $receiver, $x = 29, $y = 273, $w = '18', $h = '10', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }

      $this->SetFont('  thsarabunnew', 'i', 13);
      $this->Cell(90, 10,   $array2['sign'][$language] . "..................................................." . $array2['recorder'][$language], 0, 0, 'L');
      $this->Cell(1, 9,  "             " . $date1 . "                          " . $time1, 0, 0, 'L');
      $this->Cell(50, 10,   $array2['date'][$language] . "........................................" . $array2['time'][$language] . ".............................", 0, 1, 'L');

      $this->Cell(90, 10,   $array2['sign'][$language] . "..................................................." . $array2['returner'][$language], 0, 0, 'L');
      $this->Cell(1, 9,  "             " . $date2 . "                          " . $time2, 0, 0, 'L');
      $this->Cell(50, 10,   $array2['date'][$language] . "........................................" . $array2['time'][$language] . ".............................", 0, 1, 'L');


      $this->Cell(90, 10,   $array2['sign'][$language] . "..................................................." . $array2['receiver'][$language], 0, 0, 'L');
      $this->Cell(1, 9,  "             " . $date3 . "                          " . $time3, 0, 0, 'L');
      $this->Cell(50, 10,   $array2['date'][$language] . "........................................" . $array2['time'][$language] . ".............................", 0, 1, 'L');

      $image1 = "../report_linen/images/chb.jpg";
      $this->Image($image1, $this->GetX(), $this->GetY(), 5);
      if ($packing != null && $passenger != null && $receiver != null) {
        $image = "../report_linen/images/chk1.png";
        $this->Image($image, $this->GetX() + 1, $this->GetY() + 1, 3);
      }

      $this->Cell(7);
      $this->Cell(20, 7,   "Check", 0, 0, 'L');
      $image2 = "../report_linen/images/chb.jpg";
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
$pdf->SetTitle('Report Return');
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
$pdf->SetAutoPageBreak(TRUE, 27);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ------------------------------------------------------------------------------
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
$header = array($array['no'][$language], $array2['itemname'][$language], $array['unit'][$language], $array['qty'][$language], $array['weight'][$language] . ' (kg)');
$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT   site.$HptName,
department.DepName,
return_doc.DocNo,
DATE_FORMAT(return_doc.DocDate,'%d-%m-%Y')AS DocDate,
return_doc.Total,
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
TIME(return_doc.Modify_Date)  AS xTime ,
return_doc.IsStatus
FROM return_doc
INNER JOIN department ON return_doc.DepCodeTo = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON return_doc.Modify_Code = users.ID
WHERE return_doc.DocNo = '$DocNo'";

$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
  $RefDocNo = $Result['RefDocNo'];
  $facname = $Result[$FacName];
  $isStatus = $Result['IsStatus'];
}
if ($isStatus == 0)
{
  $Status = 'On Process';
}
elseif ( $isStatus == 1)
{
  $Status = 'Complete';
}
elseif ($isStatus == 9)
{
  $Status = 'Cancel';
}
list($d, $m, $y) = explode('-', $DocDate);
if ($language == 'th') {
  $y = $y + 543;
} else {
  $y = $y;
}
$DocDate = $d . "-" . $m . "-" . $y;

$data = "SELECT
return_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
sum(return_detail.Qty) as Qty ,
sum(return_detail.Weight) as Weight
FROM item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN return_detail ON return_detail.ItemCode = item.ItemCode
WHERE return_detail.DocNo = '$DocNo'
 GROUP BY item.ItemCode
ORDER BY item.ItemName ASC";


// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 22);
$pdf->Cell(0, 10,  $array2['return'][$language], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 16);
$pdf->Cell(35, 7, $array2['hospital'][$language], 0, 0, 'L');
$pdf->Cell(90, 7, " : " . $HptName, 0, 0, 'L');
$pdf->Cell(28, 7, $array['department'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $DepName, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(35, 7, $array['docno'][$language], 0, 0, 'L');
$pdf->Cell(90, 7, " : " . $DocNo, 0, 0, 'L');
$pdf->Cell(28, 7, $array['time'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $xTime, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(35, 7, $array2['user'][$language], 0, 0, 'L');
$pdf->Cell(90, 7, " : " . $FirstName, 0, 0, 'L');
$pdf->Cell(28, 7, $array['docdate'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $DocDate, 0, 0, 'L');

$pdf->Ln();

$pdf->Cell(35, 7,   $array['status'][$language], 0, 0, 'L');
$pdf->Cell(65, 7,   " : " . $Status, 0, 0, 'L');
$pdf->Ln(10);
$html = '<table cellspacing="0" cellpadding="3" border="1" ><thead>
<tr>
    <th width="15 %" align="center">' . $header[0] . '</th>
    <th width="35 %" align="center">' . $header[1] . '</th>
    <th width="15 %"  align="center">' . $header[2] . '</th>
    <th width="15 %" align="center">' . $header[3] . '</th>
    <th width="20 %" align="center">' . $header[4] . '</th>
</tr></thead>';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Total_Weight = $Result['Qty'] * $Result['Weight'];
  $html .= '<tr nobr="true">';
  $html .=   '<td width="15 %" align="center">' . $count . '</td>';
  $html .=   '<td width="35 %" align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="15 %" align="center"> ' . $Result['UnitName'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['Qty'] . '</td>';
  $html .=   '<td width="20 %" align="center">' . $Result['Weight'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Weight'];
  $count++;
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 9.0);
$pdf->Cell(144, 7,  $array2['total'][$language], 1, 0, 'C');
$pdf->Cell(36, 7,   number_format($totalsum, 2), 1, 0, 'C');

// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_return' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
