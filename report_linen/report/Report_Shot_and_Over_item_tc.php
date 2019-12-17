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
$data = explode(',', $_GET['data']);
// echo "<pre>";
// print_r($data);
// echo "</pre>"; 
$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
$DepCode = $data[7];
$chk = $data[8];
$DepCode = array();
$DepName=[];
$old_code = '';
//--------------------------------------------------------------------------
$where = '';
$w = array(60, 80, 50);
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
if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (shelfcount.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (shelfcount.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE shelfcount.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year2 = $year2 + 543;
    $year = $year + 543;
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . $array['to'][$language] .
      $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
  } else {
    $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year . " " . $array['to'][$language] . " " .
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . $year2;
  }
} elseif ($chk == 'month') {
  $where =   "WHERE month (shelfcount.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    $header = array($array2['no'][$language], $array2['itemname'][$language], $array2['shot'][$language], $array2['over'][$language]);
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

    // Position at 1.5 cm from bottom
    $this->SetY(-12);
    // Arial italic 8
    $this->SetFont('thsarabunnew', 'i', 9);
    // Page number

    $this->Cell(130, 10,  $array2['page'][$language] . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Report Shot and Over item');
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ------------------------------------------------------------------------------
$count = 1;
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$header = array($array2['no'][$language], $array2['itemname'][$language], $array2['shot'][$language], $array2['over'][$language]);
// ------------------------------------------------------------------------------
if ($DepCode[0] == 0) {
  $DepCode = explode(',', $_GET['Dep10']);
}
$Count_Dep = sizeof($DepCode);
for ($i = 0; $i < $Count_Dep; $i++) {
  $Sql = "SELECT 
department.DepCode, department.DepName 
FROM department 
INNER JOIN shelfcount ON department.DepCode = shelfcount.DepCode 
INNER JOIN shelfcount_detail ON shelfcount_detail.DocNo = shelfcount.DocNo 
$where 
AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
AND shelfcount.isStatus <> 9
AND shelfcount.isStatus <> 0
AND department.HptCode = '$HptCode'
AND department.DepCode = '$DepCode[$i]'
GROUP BY department.DepCode
 ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepName[] = $Result['DepName'];
  }
}
// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage();
$pdf->SetFont('thsarabunnew', 'b', 18);
$pdf->Ln(10);
$pdf->Cell(0, 10,  $array2['r7'][$language], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 12);
$pdf->Cell(ุ0, 5,  $date_header, 0, 1, 'R');
$pdf->Ln(3);

for ($i = 0; $i < $Count_Dep; $i++) {
  $data = "SELECT
IFNULL(SUM(shelfcount_detail.Over),0) AS OverPar,
IFNULL(SUM(shelfcount_detail.Short),0) AS Short ,
item.itemName,
department.DepName
FROM
shelfcount_detail
INNER JOIN shelfcount ON shelfcount.DocNo =  shelfcount_detail.DocNo
INNER JOIN item ON item.itemCode = shelfcount_detail.ItemCode
INNER JOIN department ON department.DepCode = shelfcount.DepCode
$where 
AND  department.DepCode = '$DepCode[$i]'
AND department.HptCode = '$HptCode'
AND shelfcount.isStatus <> 9
AND shelfcount.isStatus <> 0
AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
GROUP BY
	item.itemName,
  department.DepCode";

  if ($old_code <> $DepCode[$i]) {
    $h5 = '<h5 align="left">' . $array2['department'][$language] . ' : ' .  $DepName[$i]  . '</h5>';
    $pdf->writeHTML($h5, true, false, false, false, '');
    $html = '<table cellspacing="0" cellpadding="3" border="1" ><thead> 
    <tr>
    <th width="10 %" align="center">' . $header[0] . '</th>
    <th width="50 %" align="center">' . $header[1] . '</th>
    <th width="20 %" align="center">' . $header[2] . '</th>
    <th width="20 %" align="center">' . $header[3] . '</th>
    </tr></thead>';
    $old_code = $DepCode[$i];
    $count = 1;
  }
  $meQuery = mysqli_query($conn, $data);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $html .= '<tr style="font-size: 12px;" nobr="true">';
    $html .=   '<td width="10 %" align="center">' . $count . '</td>';
    $html .=   '<td width="50 %" align="left"> ' . $Result['itemName'] . '</td>';
    $html .=   '<td width="20 %" align="center"> ' . $Result['Short'] . '</td>';
    $html .=   '<td width="20 %" align="center"> ' . $Result['OverPar'] . '</td>';
    $html .=  '</tr>';
    $count++;
  }
  $html .= '</table>';
  $pdf->writeHTML($html, true, false, false, false, '');
}


// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Shot_and_Over_item' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
