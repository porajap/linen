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
    $where =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
    $where_new = "WHERE  DATE (newlinentable.DocDate) LIKE '%$date1%'";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
    $where_new = "WHERE  year (newlinentable.DocDate) LIKE '%$date1%'";

    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where = "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
  $where_new = "WHERE newlinentable.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $year2 = $year2 + 543;
    $year = $year + 543;
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . " " . $array['to'][$language] . " " .
      $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
  } else {
    $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year . " " . $array['to'][$language] . " " .
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " .  $year2;
  }
} elseif ($chk == 'month') {
  $where =   "WHERE month (clean.Docdate) = " . $date1;
  $where_new = "WHERE month (newlinentable.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE date(clean.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_new =  "WHERE date(newlinentable.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
    $header = array($array['no'][$language], $array['item'][$language], $array['qty'][$language], $array['weight'][$language], $array['Sum'][$language]);
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
      <tr >
      <th width="50 px " align="center">' . $header[0] . '</th>
      <th width="150 px " align="center">' . $header[1] . '</th>
      <th width="50 px "  align="center">' . $header[2] . '</th>
      <th align="center">' . $header[3] . '</th>
      <th align="center">' . $header[4] . '</th>
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
      // $this->ImageSVG('@' . $Signature, $x = 30, $y = 185, $w = '10', $h = '10', $link = 'http://www.tcpdf.org', $align = '', $palign = '', $border = 0, $fitonpage = false);
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
$pdf->SetTitle('Report_Cleaned_Linen_Weight');
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
$pdf->SetAutoPageBreak(TRUE, 20);
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
$header = array($array['no'][$language], $array['item'][$language], $array['qty'][$language], $array['weight'][$language], $array['Sum'][$language]);
$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT
factory.$FacName,
site. $HptName
FROM
clean
INNER JOIN factory ON factory.FacCode = clean.FacCode
INNER JOIN department ON department.DepCode = clean.DepCode
INNER JOIN site ON site.HptCode = department.HptCode
AND factory.FacCode = '$FacCode'
GROUP BY
factory.$FacName
        ";
$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $facname = $Result[$FacName];
  $HptName = $Result[$HptName];
}

$data = "SELECT
item.ItemName,
item_unit.UnitName,
SUM(clean_detail.Qty) AS Totalqty,
SUM(clean_detail.Weight) AS Weight,
(category_price.Price) AS Price,
clean_detail.itemcode,
clean_detail.Detail
FROM
clean_detail
LEFT JOIN clean ON clean.DocNo = clean_detail.DocNo
LEFT JOIN factory ON factory.FacCode = clean.FacCode 
INNER JOIN department ON clean.DepCode = department.DepCode
INNER JOIN item ON item.ItemCode = clean_detail.ItemCode
INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
INNER JOIN item_unit ON clean_detail.UnitCode = item_unit.UnitCode
$where
AND clean.RefDocNo NOT LIKE '%RPW%'
AND department.HptCode = '$HptCode'
AND category_price.HptCode ='$HptCode'
AND clean.FacCode = '$FacCode'
AND clean.IsStatus <> 9
GROUP BY
clean_detail.ItemCode";


// set some language-dependent strings (optional)

// --------------------------------------------------------
// set font
// add a page
$pdf->AddPage();
$pdf->SetFont('thsarabunnew', 'b', 20);
$pdf->Cell(0, 10,  $array2['r3'][$language], 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('thsarabunnew', 'b', 12);
$pdf->Cell(0, 10,  $array2['hospital'][$language] . " : " . $HptName, 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 12);
$pdf->Cell(120, 5,   $array2['factory'][$language] . " : " . $facname, 0, 0, 'L');
$pdf->Cell(ุ60, 5,  $date_header, 0, 1, 'R');
$pdf->Ln(3);
$html = '<table cellspacing="0" cellpadding="3" border="1" >
<tr >
    <th width="50 px " align="center">' . $header[0] . '</th>
    <th width="150 px " align="center">' . $header[1] . '</th>
    <th width="50 px "  align="center">' . $header[2] . '</th>
    <th align="center">' . $header[3] . '</th>
    <th align="center">' . $header[4] . '</th>
</tr>';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Total_Weight = $Result['Totalqty'] * $Result['Weight'];
  $html .= '<tr nobr="true">';
  $html .=   '<td align="center">' . $count . '</td>';
  $html .=   '<td align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td align="center">' . $Result['Totalqty'] . '</td>';
  $html .=   '<td align="center">' . $Result['Weight'] . '</td>';
  $html .=   '<td align="center">' . $Total_Weight  . '</td>';
  $html .=  '</tr>';
  $totalsum += $Total_Weight;
  $count++;
}
$html .= '<tr>' .
  '<td colspan= "4" align="center">' .  $array2['total'][$language] . '</td>' .

  '<td align="center">' . number_format($totalsum, 2) . '</td>' .
  '</tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');


// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Dirty_Linen_Weight' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
