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
    $this->SetFont(' thsarabunnew', '', 8);
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
$header = array($array2['no']['en'], $array2['itemname']['en'], $array2['short']['en'], $array2['over']['en']);
$count = 1;

// // ------------------------------------------------------------------------------
$head = "SELECT
department.DepName,
shelfcount.isStatus
FROM
shelfcount
INNER JOIN department ON shelfcount.DepCode = department.DepCode
WHERE shelfcount.DocNo='$docno'
        ";

$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $isStatus = $Result['isStatus'];
}
if ($isStatus == 1) {
  $Status = 'On Process';
} elseif ($isStatus == 3 || $isStatus == 4) {
  $Status = 'Complete';
}
$data = "SELECT
item.ItemName,
IFNULL(shelfcount_detail.Over, 0) AS OverPar,
IFNULL(shelfcount_detail.Short, 0) AS Short
FROM
shelfcount
INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
INNER JOIN department ON shelfcount.DepCode = department.DepCode
 WHERE shelfcount.DocNo='$docno'
AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
ORDER BY item.itemCode ASC ";

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
$pdf->Cell(150, 7,  $array2['ward']['th'] . " : " . $DeptName, 0, 0, 'L');
$pdf->Cell(30, 7,   "Status : " . $Status, 0, 1, 'L');
$pdf->Ln(3);
$html = '<table cellspacing="0" cellpadding="1" border="1" > <thead>
<tr style="font-size: 16px;">
<th width="' . 20 . '% "  align="center">' . $header[0] . '</th>
<th width="' . 40 . '% " align="center">' . $header[1] . '</th>
<th width="' . 20 . '% " align="center">' . $header[2] . '</th>
<th width="' . 20 . '% " align="center">' . $header[3] . '</th>' .
  '</tr></thead>';
$pdf->SetFont('  thsarabunnew', '', 14);
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $html .= '<tr cellpadding="3" style="font-size: 15px;" nobr="true">';
  $html .=   '<td width="' . 20 . '% " align="center">' . $count . '</td>';
  $html .=   '<td width="' . 40 . '% " align="left"  >' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="' . 20 . '% " align="center">' . $Result['Short'] . '</td>';
  $html .=   '<td width="' . 20 . '% " align="center">' . $Result['OverPar'] . '</td>';
  $html .=  '</tr>';
  $count++;
}

$html .= ' </table>';
$pdf->writeHTML($html);
// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Shelfcount' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
