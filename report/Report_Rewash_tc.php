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
    $header = array($array['no'][$language],$array['itemname'][$language],$array['qty'][$language],$array['unit'][$language],$array['weight'][$language].' (kg)');
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
    $DocNo = $_GET['DocNo'];
    if ($this->last_page_flag) {
      require('connect.php');
      $head = "SELECT
      repair_wash.SignFac,
      repair_wash.SignNH,
      repair_wash.SignFacTime,
      repair_wash.SignNHTime
      FROM
      repair_wash
      where repair_wash.docno =  '$DocNo'
      ";
      
      $meQuery = mysqli_query($conn, $head);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $SignFac = $Result['SignFac'];
        $SignNH = $Result['SignNH'];
        $SignFacTime = $Result['SignFacTime'];
        $SignNHTime = $Result['SignNHTime'];
      }
      $this->SetY(-35);
      list($date1, $time1) = explode(' ', $SignFacTime);
      list($date2, $time2) = explode(' ', $SignNHTime);
      list($y1, $m1, $d1) = explode('-', $date1);
      list($y2, $m2, $d2) = explode('-', $date2);
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
      if ($date1 == '--543') {
        $date1 = ' ';
      }
      if ($date2 == '--543') {
        $date2 = ' ';
      }
      $this->SetFont('thsarabunnew', 'b', 13);
      if ($SignNH != null) {
      $this->ImageSVG('@' . $SignNH, $x = 38, $y = 257, $w = '30', $h = '10', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      if ($SignFac != null) {
      $this->ImageSVG('@' . $SignFac, $x = 134, $y = 257, $w = '30', $h = '10', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      $this->Cell(100, 8, $array2['comlinen'][$language]  . "...............................................", 0, 0, 'L');
      $this->Cell(90, 8,  $array2['comlaundry'][$language] . "........................................", 0, 1, 'L');
      $this->Cell(0.1, 7,  "                  $date2", 0, 0, 'L');
      $this->Cell(100, 8, $array2['date'][$language] . "......................................................................", 0, 0, 'L');
      $this->Cell(0.1, 7,  "                   $date1", 0, 0, 'L');
      $this->Cell(90, 8,  $array2['date'][$language] . "..........................................................", 0, 1, 'L');
    }
    // Position at 1.5 cm from bottom
    $this->SetY(-25);
    // Arial italic 8
    $this->SetFont('thsarabunnew', 'i', 12);
    // Page number

    $this->Cell(190, 10,  $array2['page'][$language] . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Report Rewash');
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
$header = array($array['no'][$language],$array2['itemname'][$language],$array['qty'][$language],$array['unit'][$language],$array['weight'][$language].' (kg)');
$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT   site.$HptName,
department.DepName,
repair_wash.DocNo,
DATE_FORMAT(repair_wash.DocDate,'%d-%m-%Y')AS DocDate,
repair_wash.Total,
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
TIME(repair_wash.Modify_Date)  AS xTime,
repair_wash.RefDocNo
FROM repair_wash
INNER JOIN department ON repair_wash.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON repair_wash.Modify_Code = users.ID
WHERE repair_wash.DocNo = '$DocNo'";
$meQuery = mysqli_query($conn,$head);
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

$data = "SELECT
repair_wash_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
sum(repair_wash_detail.Qty) as Qty ,
sum(repair_wash_detail.Weight) as Weight
FROM item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN repair_wash_detail ON repair_wash_detail.ItemCode = item.ItemCode
WHERE repair_wash_detail.DocNo = '$DocNo'
 GROUP BY item.ItemCode
ORDER BY item.ItemName ASC";


// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 22);
$pdf->Cell(0, 10,  $array2['r6'][$language], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 16);

$pdf->Cell(35,7, $array2['hospital'][$language],0,0,'L');
$pdf->Cell(90,7, " : ".$HptName,0,0,'L');
$pdf->Cell(28 ,7, $array['department'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$DepName,0,0,'L');
$pdf->Ln();

$pdf->Cell(35,7, $array['docno'][$language],0,0,'L');
$pdf->Cell(90,7, " : ".$DocNo,0,0,'L');
$pdf->Cell(28,7, $array['factory'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$facname,0,0,'L');
$pdf->Ln();

$pdf->Cell(35,7, $array['refdocno'][$language],0,0,'L');
$pdf->Cell(90,7, " : ".$RefDocNo,0,0,'L');
$pdf->Cell(28,7, $array['time'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$xTime,0,0,'L');
$pdf->Ln();

$pdf->Cell(35,7, $array2['user'][$language],0,0,'L');
$pdf->Cell(90,7, " : ".$FirstName,0,0,'L');
$pdf->Cell(28,7, $array['docdate'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$DocDate,0,0,'L');
$pdf->Ln();
$pdf->Ln(5);
$html = '<table cellspacing="0" cellpadding="3" border="1" ><thead>
<tr>
    <th width="15 %" align="center">' . $header[0] . '</th>
    <th width="50 %" align="center">' . $header[1] . '</th>
    <th width="15 %"  align="center">' . $header[2] . '</th>
    <th width="20 %" align="center">' . $header[4] . '</th>
</tr></thead>';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Total_Weight = $Result['Qty'] * $Result['Weight'];
  $html .= '<tr nobr="true">';
  $html .=   '<td width="15 %" align="center">' . $count . '</td>';
  $html .=   '<td width="50 %" align="left"> ' . $Result['ItemName'] . '</td>';
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
$pdf->Output('Report_repair_wash_Linen_Weight' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
