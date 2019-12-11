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
    $header = array($array['no'][$language],  $array2['itemname'][$language], $array['department'][$language], $array['qty'][$language], $array['weight'][$language], $array['unit'][$language]);

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
      dirty.SignFac,
      dirty.SignNH,
      dirty.SignFacTime,
      dirty.SignNHTime
      FROM
      dirty
      where dirty.docno =  '$DocNo'
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
$pdf->SetTitle('Report Dirty');
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
$pdf->SetAutoPageBreak(TRUE, 38);
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
$header = array($array['no'][$language],  $array2['itemname'][$language], $array['department'][$language], $array['qty'][$language], $array['weight'][$language], $array['unit'][$language]);

$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT site.$HptName,
        factory.$FacName,
        DATE_FORMAT(dirty.DocDate,'%d-%m-%Y')AS DocDate,
        TIME(dirty.Modify_Date) AS xTime,
        CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
        time_dirty.TimeName
        FROM dirty
        INNER JOIN site ON dirty.HptCode = site.HptCode
        INNER JOIN factory ON dirty.FacCode = factory.FacCode
        INNER JOIN users ON dirty.Modify_Code = users.ID
        LEFT JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
        WHERE dirty.DocNo = '$DocNo'";
// echo $Sql;
$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $FacName = $Result[$FacName];
  $xTime = $Result['xTime'];
  $FName = $Result['FName'];
  $DocDate = $Result['DocDate'];
  $TimeName = $Result['TimeName'];
}
list($d, $m, $y) = explode('-', $DocDate);
if ($language == 'th') {
  $y = $y + 543;
} else {
  $y = $y;
}
$DocDate = $d . "-" . $m . "-" . $y;


$data = "SELECT
dirty_detail.ItemCode,
item.ItemName,
dirty_detail.RequestName,
COALESCE (item_unit.UnitName,'-') AS  UnitName,
department.DepName,
sum(dirty_detail.Qty) as Qty,
sum(dirty_detail.Weight) as Weight
FROM item
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
RIGHT JOIN dirty_detail ON dirty_detail.ItemCode = item.ItemCode
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
WHERE dirty_detail.DocNo = '$DocNo'
GROUP BY item.ItemCode,department.depname,item_unit.UnitName,dirty_detail.RequestName
ORDER BY
department.Depcode,item.ItemCode ASC";


// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 22);
$pdf->Cell(0, 10,  $array2['r1'][$language], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 16);
$pdf->Cell(35, 7,   $array2['hospital'][$language], 0, 0, 'L');
$pdf->Cell(77, 7,   ": " . $HptName, 0, 0, 'L');
$pdf->Cell(30, 7,   $array['factory'][$language], 0, 0, 'L');
$pdf->Cell(30, 7,   ": " . $FacName, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(35, 7,   $array['docno'][$language], 0, 0, 'L');
$pdf->Cell(77, 7,   ": " . $DocNo, 0, 0, 'L');
$pdf->Cell(30, 7,   $array['docdate'][$language], 0, 0, 'L');
$pdf->Cell(30, 7,   ": " . $DocDate, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(35, 7,   $array2['user'][$language], 0, 0, 'L');
$pdf->Cell(77, 7,   ": " . $FName, 0, 0, 'L');
$pdf->Cell(30, 7,   $array['time'][$language], 0, 0, 'L');
$pdf->Cell(30, 7,   ": " . $xTime, 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(35, 7,   $array['rounddirty'][$language], 0, 0, 'L');
$pdf->Cell(77, 7,   ": " . $TimeName, 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 14);
$html = '<table cellspacing="0" cellpadding="2" border="1" >
<thead><tr >
<th width="10 %" align="center">' . $header[0] . '</th>
<th width="15 %" align="center">' . $header[1] . '</th>
<th width="30 %" align="center">' . $header[2] . '</th>
<th width="15 %"  align="center">' . $header[3] . '</th>
<th width="15 %" align="center">' . $header[4] . '</th>
<th width="15 %"  align="center">' . $header[5] . '</th>
</tr> </thead>';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result['ItemName'] = $Result['RequestName'];
  }
  $html .= '<tr nobr="true">';
  $html .=   '<td width="10 %" align="center">' . $count . '</td>';
  $html .=   '<td width="15 %" align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="30 %" align="center">' . $Result['DepName'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['Qty'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['Weight'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['UnitName'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Weight'];
  $totalqty += $Result['Qty'];
  $count++;
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');

$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 7.0);
$pdf->Cell(99, 7,  $array2['total'][$language], 1, 0, 'C');
$pdf->Cell(27, 7,   number_format($totalqty, 2), 1, 0, 'C');

$pdf->Cell(27, 7,   number_format($totalsum, 2), 1, 0, 'C');
$pdf->Cell(27, 7,   '', 1, 1, 'C');
$pdf->Cell(0, 7,   '', 0, 1, 'C');
$pdf->Cell(0, 7,   '', 0, 1, 'C');
// ---------------------------------------------------------
$queryy = "SELECT
item.ItemName,
SUM(dirty_detail.Qty) AS Qty,
SUM(dirty_detail.Weight) AS Weight,
dirty_detail.RequestName,
COALESCE (item_unit.UnitName,'-') AS  UnitName
FROM item
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
RIGHT JOIN dirty_detail ON dirty_detail.ItemCode = item.ItemCode
INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
WHERE dirty_detail.DocNo = '$DocNo'
AND dirty.isStatus <> 9
GROUP BY item.ItemName,dirty_detail.RequestName
ORDER BY item.ItemCode,dirty_detail.RequestName ASC
          ";
$meQuery = mysqli_query($conn, $queryy);
$html = '<table cellspacing="0" cellpadding="2" border="1" >
<thead><tr >
<th width="55 %" align="center">' . $header[1] . '</th>
<th width="15 %"  align="center">' . $header[3] . '</th>
<th width="15 %" align="center">' . $header[4] . '</th>
<th width="15 %"  align="center">' . $header[5] . '</th>
</tr> </thead>';
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result['ItemName'] = $Result['RequestName'];
  }
  $html .= '<tr nobr="true">';
  $html .=   '<td width="55 %" align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['Qty'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['Weight'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['UnitName'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Weight'];
  $count++;
}
$html .= '</table >';
$pdf->writeHTML($html, true, false, false, false, '');
//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Dirty_Linen_Weight' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
