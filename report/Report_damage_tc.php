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
    $header = array($array['no'][$language], $array2['itemname'][$language], $array['qty'][$language], $array['unit'][$language], $array['weight'][$language]);
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
    // else {
    //   $this->SetFont('thsarabunnew', '', 9);
    //   $this->Cell(0, 10,  $array2['printdate'][$language] . $printdate, 0, 1, 'R');
    //   $this->SetFont('thsarabunnew', '', 12);
    //   $this->SetY(19);
    //   $html = '<table cellspacing="0" cellpadding="1" border="1" >
    //   <tr style="font-size: 22px;">
    //   <th width="50 px " align="center">' . $header[0] . '</th>
    //   <th width="270 px " align="center">' . $header[1] . '</th>
    //   <th width="50 px "  align="center">' . $header[2] . '</th>
    //   <th align="center">' . $header[3] . '</th>
    //   <th align="center">' . $header[4] . '</th>
    //   </tr>
    //   </table>';
    //   $this->writeHTML($html, true, false, false, false);
    // }
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
      damage.SignFac,
      damage.SignNH,
      SignFacTime as  SignFacTime ,
      SignNHTime as  SignNHTime
      FROM
      damage
      WHERE damage.DocNo = '$DocNo'
      ";
      $meQuery = mysqli_query($conn, $head);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $SignFac = $Result['SignFac'];
        $SignNH = $Result['SignNH'];
        $SignFacTime = $Result['SignFacTime'];
        $SignNHTime = $Result['SignNHTime'];
      }
      list($date1, $time1) = explode(' ', $SignFacTime);
      list($date2, $time2) = explode(' ', $SignNHTime);
      list($y1, $m1, $d1) = explode('-', $date1);
      list($y2, $m2, $d2) = explode('-', $date2);
      if ($language == 'th') {
        $y1 = $y1 + 543;
        $y2 = $y2 + 543;
      } else {
        $y1 = $y1;
        $y2 = $y2;
      }
      $date1 = $d1 . "-" . $m1 . "-" . $y1;
      $date2 = $d2 . "-" . $m2 . "-" . $y2;
      if ($date1 == '--543') {
        $date1 = ' ';
      }
      if ($date2 == '--543') {
        $date2 = ' ';
      }


      $this->SetY(-35);
      if ($SignNH != null) {
        $this->ImageSVG('@' . $SignNH, $x = 45, $y = 259, $w = '18', $h = '7', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      if ($SignFac != null) {
        $this->ImageSVG('@' . $SignFac, $x = 159, $y = 259, $w = '18', $h = '7', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
      }
      $this->SetFont('thsarabunnew', 'b', 13);
      $this->Cell(120, 7, $array2['comlinen'][$language]  . "...............................................", 0, 0, 'L');
      $this->Cell(40, 7,  $array2['comlaundry'][$language] . "........................................", 0, 1, 'L');
      $this->Cell(1, 6,  "                 " . $date2, 0, 0, 'L');
      $this->Cell(120, 7, $array2['date'][$language] . "......................................................................", 0, 0, 'L');
      $this->Cell(1, 6,  "                 " . $date1, 0, 0, 'L');
      $this->Cell(40, 7,  $array2['date'][$language] . "..........................................................", 0, 0, 'L');
    }
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('thsarabunnew', 'i', 13);
    // Page number

    $this->Cell(190, 7,  $array2['page'][$language] . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Report damage');
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
$pdf->SetMargins(15 , PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
$header = array($array['no'][$language], $array2['itemname'][$language],$array2['unit'][$language], $array['qty'][$language]);
$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT   site.$HptName,
department.DepName,
damage.DocNo,
DATE_FORMAT(damage.DocDate,'%d-%m-%Y')AS DocDate,
damage.Total,
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
TIME(damage.Modify_Date) AS xTime,
damage.RefDocNo,
factory.facname
FROM damage
INNER JOIN department ON damage.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN factory ON factory.FacCode = damage.FacCode
INNER JOIN users ON damage.Modify_Code = users.ID
WHERE damage.DocNo = '$DocNo'";
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
  $facname = $Result['facname'];
}
list($d, $m, $y) = explode('-', $DocDate);
if ($language == 'th') {
  $y = $y + 543;
} else {
  $y = $y;
}
$DocDate = $d . "-" . $m . "-" . $y;


$data = "SELECT
damage_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
sum(damage_detail.Qty) as Qty 
FROM item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN damage_detail ON damage_detail.ItemCode = item.ItemCode
WHERE damage_detail.DocNo = '$DocNo'
GROUP BY item.ItemCode
ORDER BY item.ItemName ASC";


// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 24);
$pdf->Cell(0, 10,  $array2['r24'][$language], 0, 0, 'C');
$pdf->Ln(15);
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
<tr style="font-size: 16 px;" >
    <th width="15 % " align="center">' . $header[0] . '</th>
    <th width="45 % " align="center">' . $header[1] . '</th>
    <th width="25 % "  align="center">' . $header[2] . '</th>
    <th width="15 % " align="center">' . $header[3] . '</th>
</tr></thead> ';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {  
  $Total_Weight = $Result['Qty'] * $Result['Weight'];
  $html .= '<tr style="font-size: 16 px;" nobr="true">';
  $html .=   '<td width="15 % " align="center">' . $count . '</td>';
  $html .=   '<td width="45 % " align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="25 % " align="center">' . $Result['UnitName'] . '</td>';
  $html .=   '<td width="15 % " align="center">' . $Result['Qty'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Qty'];
  $count++;
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->SetFont('thsarabunnew', 'b', 13);
$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 9.0);
$pdf->Cell(153, 5,  $array2['total'][$language], 1, 0, 'C');
$pdf->Cell(27, 5,   $totalsum, 1, 0, 'C');

// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Dirty_Linen_Weight' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
