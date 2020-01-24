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
      $this->SetFont('thsarabunnew', '', 13);
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
$pdf->SetTitle('Report Stock Receive');
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
cleanstock.DocNo,
DATE_FORMAT(cleanstock.DocDate,'%d-%m-%Y')AS DocDate,
cleanstock.Total,
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
TIME(cleanstock.Modify_Date)  AS xTime,
cleanstock.RefDocNo
FROM cleanstock
INNER JOIN department ON cleanstock.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON cleanstock.Modify_Code = users.ID
WHERE cleanstock.DocNo = '$DocNo'";
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
list($d, $m, $y) = explode('-', $DocDate);
if ($language == 'th') {
  $y = $y + 543;
} else {
  $y = $y;
}
$DocDate = $d . "-" . $m . "-" . $y;
$data = "SELECT
cleanstock_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
sum(cleanstock_detail.Qty) as Qty ,
sum(cleanstock_detail.Weight) as Weight
FROM item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN cleanstock_detail ON cleanstock_detail.ItemCode = item.ItemCode
WHERE cleanstock_detail.DocNo = '$DocNo'
 GROUP BY item.ItemCode
ORDER BY item.ItemName ASC";


// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 22);
$pdf->Cell(0, 10,  $array2['SR'][$language], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 16);

$pdf->Cell(35,7, $array2['hospital'][$language],0,0,'L');
$pdf->Cell(75,7, " : ".$HptName,0,0,'L');
$pdf->Cell(28,7, $array['department'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$DepName,0,0,'L');
$pdf->Ln();

$pdf->Cell(35,7, $array['docno'][$language],0,0,'L');
$pdf->Cell(75,7, " : ".$DocNo,0,0,'L');
$pdf->Cell(28,7, $array['docdate'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$DocDate,0,0,'L');
$pdf->Ln();

$pdf->Cell(35,7, $array['refdocno'][$language],0,0,'L');
$pdf->Cell(75,7, " : ".$RefDocNo,0,0,'L');
$pdf->Cell(28,7, $array['time'][$language],0,0,'L');
$pdf->Cell(55,7, " : ".$xTime,0,0,'L');
$pdf->Ln();

$pdf->Cell(35,7, $array2['user'][$language],0,0,'L');
$pdf->Cell(75,7, " : ".$FirstName,0,0,'L');

$pdf->Ln();
$pdf->Ln(5);

$html = '<table cellspacing="0" cellpadding="3" border="1" ><thead>
<tr>
    <th width="25 %" align="center">' . $header[0] . '</th>
    <th width="50 %" align="center">' . $header[1] . '</th>
    <th width="25 %"  align="center">' . $header[2] . '</th>
</tr></thead>';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Total_Weight = $Result['Qty'] * $Result['Weight'];
  $html .= '<tr nobr="true">';
  $html .=   '<td width="25 %" align="center">' . $count . '</td>';
  $html .=   '<td width="50 %" align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="25 %" align="center">' . $Result['Qty'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Qty'];
  $count++;
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 9.0);
$pdf->Cell(135, 7,  $array2['total'][$language], 1, 0, 'C');
$pdf->Cell(45, 7,   $totalsum, 1, 0, 'C');

// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Stock_Receive' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
