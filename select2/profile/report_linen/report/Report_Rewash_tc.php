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
    $where =   "WHERE DATE (repair_wash.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (repair_wash.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE repair_wash.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (repair_wash.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(repair_wash.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    $header = array($array2['docno'][$language], $array2['itemname'][$language], $array2['amount1'][$language],);
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
    } else {
      $this->SetFont('thsarabunnew', '', 9);
      $this->Cell(0, 10,  $array2['printdate'][$language] . $printdate, 0, 1, 'R');
      $this->SetFont('thsarabunnew', '', 12);
      $this->SetY(21);
      $html = '<table cellspacing="0" cellpadding="1" border="1" >
      <tr>
          <th align="center">' . $header[0] . '</th>
          <th align="center">' . $header[1] . '</th>
          <th align="center">' . $header[2] . '</th>
      </tr>
      </table>';
      $this->writeHTML($html, true, false, false, false);
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
    if ($this->last_page_flag) {
      require('connect.php');
      $head = "SELECT process.Signature FROM process
      ";
      $meQuery = mysqli_query($conn, $head);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Signature = $Result['Signature'];
      }
      $this->SetY(-20);

      $this->SetFont('thsarabunnew', 'b', 8);
      // $this->ImageSVG('@' . $Signature, $x = 30, $y = 181, $w = '10', $h = '10', $link = 'http://www.tcpdf.org', $align = '', $palign = '', $border = 0, $fitonpage = false);
      $this->Cell(80, 5, $array2['comlinen'][$language]  . "...............................................", 0, 0, 'L');
      $this->Cell(40, 5,  $array2['comlaundry'][$language] . "........................................", 0, 1, 'L');
      $this->Cell(80, 5, $array2['date'][$language] . "......................................................................", 0, 0, 'L');
      $this->Cell(40, 5,  $array2['date'][$language] . "..........................................................", 0, 1, 'L');
    }
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
$pdf->SetAutoPageBreak(TRUE, 15);
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
$header = array($array2['docno'][$language], $array2['itemname'][$language], $array2['amount1'][$language],);
// ------------------------------------------------------------------------------
$head = "SELECT
factory.$FacName,
DATE(repair_wash.DocDate) AS DocDate,
TIME(repair_wash.DocDate) AS DocTime
FROM
repair_wash
INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
INNER JOIN department ON department.depcode = repair_wash.depcode
$where
AND repair_wash.FacCode = $FacCode
AND department.HptCode = '$HptCode'
AND repair_wash.isStatus<> 9
        ";
$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $facname = $Result[$FacName];
}

$data = "SELECT
item.ItemName,
repair_wash.DocNo,
repair_wash_detail.Qty
FROM
repair_wash_detail
INNER JOIN item ON repair_wash_detail.ItemCode = item.ItemCode
INNER JOIN repair_wash ON repair_wash_detail.DocNo = repair_wash.DocNo
INNER JOIN department ON department.depcode = repair_wash.depcode
$where
AND repair_wash.FacCode = $FacCode
AND department.HptCode = '$HptCode'
AND repair_wash.isStatus<> 9
ORDER BY repair_wash.DocNo ASC";


// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage();
$pdf->SetFont('thsarabunnew', 'b', 20);
$pdf->Cell(0, 10,  $array2['r1'][$language], 0, 0, 'C');
$pdf->SetFont('thsarabunnew', 'b', 12);
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 12);
$pdf->Cell(120, 5,   $array2['factory'][$language] . " : " . $facname, 0, 0, 'L');
$pdf->Cell(ุ60, 5,  $date_header, 0, 1, 'R');
$pdf->Ln(3);
$html = '<table cellpadding="4" cellspacing="0"  border="1"   >
<tr >
    <th align="center">' . $header[0] . '</th>
    <th align="center">' . $header[1] . '</th>
    <th align="center">' . $header[2] . '</th>
</tr>';

$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $html .= '<tr nobr="true">';
  if ($Result['DocNo'] == $item) {
    $html .=  '<td>' . " " .                 '</td>' .
      '<td align="left">' . $Result['ItemName']    . '</td>';
  } else {
    $html .=  '<td> ' . $Result['DocNo'] .                 '</td>' .
      '<td align="left" stlye="padding: 5 ">' . $Result['ItemName']      . '</td>';
    $item = $Result['DocNo'];
  }
  $html .=   '<td text-align="center">' . $Result['Qty'] . '</td>';

  $html .=  '</tr>';
  $totalqty +=  $Result['Qty'];
}
$html .= '<tr>' .
  '<td align="center" colspan="2">' .  $array2['total'][$language] . '</td>' .
  '<td align="center">' . number_format($totalqty, 2) . '</td>' .
  '</tr>';

$html .= '</table>';
$pdf->writeHTML($html, $ln = false, $fill = true, $reseth = true, $cell = false, $align = 'C');


// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Rewash'.$date.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
