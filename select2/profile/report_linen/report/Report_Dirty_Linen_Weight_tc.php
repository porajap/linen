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
if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
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
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " . $year2;
  }
} elseif ($chk == 'month') {
  $where =   "WHERE month (dirty.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE DATE(dirty.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    $header = array($array2['itemname'][$language], $array2['amount'][$language], $array2['department1'][$language], $array2['weight_kg'][$language]);
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
      $this->SetY(20.5);
      $html = '<table cellspacing="0" cellpadding="2" border="1" >
      <tr>
      <th width="20 %"align="center">' . $header[0] . '</th>
      <th width="10 %" align="center">' . $header[1] . '</th>
      <th width="50 %" align="center">' . $header[2] . '</th>
      <th width="20 %" align="center">' . $header[3] . '</th>
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
      $this->SetY(-18);

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
$pdf->SetTitle('Report_Dirty_Linen_Weight');
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
$header = array($array2['itemname'][$language], $array2['amount'][$language], $array2['department1'][$language], $array2['weight_kg'][$language]);
// ------------------------------------------------------------------------------
$head = "SELECT
        factory.$FacName,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        AND  factory.FacCode = '$FacCode'
        GROUP BY factory.$FacName
        ";
$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $facname = $Result[$FacName];
}

$data = "SELECT
item.ItemName,
dirty_detail.Weight,
department.DepName,
SUM(dirty_detail.Qty) AS Qty
FROM
dirty
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
INNER JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND dirty.isStatus <> 9
GROUP BY item.ItemName,department.DepName,date(dirty.DocDate)
ORDER BY item.ItemName , department.DepName ASC";

$sum = "SELECT
item.ItemName,
SUM(dirty_detail.Qty) AS Qty
FROM
dirty
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
INNER JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND dirty.isStatus <> 9
GROUP BY item.ItemName
ORDER BY item.ItemName , department.DepName ASC
          ";

// set some language-dependent strings (optional)

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
$html = '<table cellspacing="0" cellpadding="3" border="1"  >
<tr >
    <th width="20 %"align="center">' . $header[0] . '</th>
    <th width="10 %" align="center">' . $header[1] . '</th>
    <th width="50 %" align="center">' . $header[2] . '</th>
    <th width="20 %" align="center">' . $header[3] . '</th>
</tr>';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $html .= '<tr style="font-size: 12px;" nobr="true">';
  if ($Result['ItemName'] == $item) {
    $html .=  '<td>' . " " .                 '</td>' .
      '<td align="center">' . number_format($Result['Qty'])       . '</td>';
  } else {
    $html .=  '<td> ' . $Result['ItemName'] .                 '</td>' .
      '<td align="center">' . number_format($Result['Qty'])       . '</td>';
    $item = $Result['ItemName'];
  }
  $html .=   '<td align="center" style="line-height: 100%;">' . $Result['DepName'] . '</td>';
  $html .=   '<td align="center">' . $Result['Weight']  . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Weight'];
  $totalqty +=  $Result['Qty'];
}
$html .= '<tr style="font-size: 11px;">' .
  '<td align="center">' .  $array2['total'][$language] . '</td>' .
  '<td align="center">' .  number_format($totalqty) . '</td>' .
  '<td align="center">' . " " . '</td>' .
  '<td align="center">' . number_format($totalsum, 2) . '</td>' .
  '</tr>';
$meQuery = mysqli_query($conn, $sum);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $html .= '<tr style="font-size: 12px;">' .
    '<td align="center">' . $Result['ItemName'] . '</td>' .
    '<td align="center">' . number_format($Result['Qty']) . '</td>' .
    '<td align="center">' . " " . '</td>' .
    '<td align="center">' . " " . '</td>' .
    '</tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Dirty_Linen_Weight' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
