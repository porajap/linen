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
$data =explode( ',',$_GET['data']);
$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
$DepCode = $data[7];
$chk = $data[8];
$year = $data['year'];
$where = '';
$language = $_SESSION['lang'];
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
if ($chk == 'one') 
{
  if ($format == 1) 
  {
    $where =   "WHERE DATE (repair_wash.Docdate) = DATE('$date1')";
    $where_new = "WHERE  DATE (newlinentable.DocDate) LIKE '%$date1%'";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') 
    {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    }
    else
    {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  }
  elseif ($format = 3)
  {
    $where = "WHERE  year (repair_wash.DocDate) LIKE '%$date1%'";
    $where_new = "WHERE  year (newlinentable.DocDate) LIKE '%$date1%'";

    if ($language == "th")
    {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    }
    else
    {
      $date_header = $array['year'][$language] . $date1;
    }
  }
}
elseif ($chk == 'between')
{
  $where = "WHERE repair_wash.Docdate BETWEEN '$date1' AND '$date2'";
  $where_new = "WHERE newlinentable.Docdate BETWEEN '$date1' AND '$date2'";
  list($year, $mouth, $day) = explode("-", $date1);
  list($year2, $mouth2, $day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  if ($language == 'th')
  {
    $year2 = $year2 + 543;
    $year = $year + 543;
    $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . " " . $array['to'][$language] . " " .
      $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
  }
  else
  {
    $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year . " " . $array['to'][$language] . " " .
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " .  $year2;
  }
}
elseif ($chk == 'month')
{
  $where =   "WHERE month (repair_wash.Docdate) = " . $date1;
  $where_new = "WHERE month (newlinentable.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th')
  {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  }
  else
  {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
}
elseif ($chk == 'monthbetween')
{
  $where =   "WHERE date(repair_wash.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $where_new =  "WHERE date(newlinentable.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  list($year, $mouth, $day) = explode("-", $betweendate1);
  list($year2, $mouth2, $day2) = explode("-", $betweendate2);
  $datetime = new DatetimeTH();
  if ($language == 'th')
  {
    $year = $year + 543;
    $year2 = $year2 + 543;
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2) . " $year2 ";
  }
  else
  {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2) . " $year2 ";
  }
}
//--------------------------------------------------------------------------//--------------------------------------------------------------------------
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
    $header = array($array['no'][$language],$array['itemname'][$language],$array['qty'][$language],$array['unit'][$language].' (ชิ้น)',$array['weight'][$language].' (kg)');
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
if ($language == 'th') 
{
  $HptName = 'HptNameTH';
  $FacName = 'FacNameTH';
  $Perfix = 'THPerfix';
  $Name = 'THName';
  $LName = 'THLName';
}
else
{
  $HptName = 'HptName';
  $FacName = 'FacName';
  $Perfix = 'EngPerfix';
  $Name = 'EngName';
  $LName = 'EngLName';
}
$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT
          factory.$FacName,
          site.$HptName
        FROM
        repair_wash
        INNER JOIN factory ON factory.FacCode = repair_wash.FacCode
        INNER JOIN department ON department.DepCode = repair_wash.DepCode
        INNER JOIN site ON site.HptCode = department.HptCode
        AND factory.FacCode = '$FacCode'
        GROUP BY
        factory.$FacName
        ";
$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) 
{
  $facname = $Result[$FacName];
  $HptName = $Result[$HptName];
}

$detail = "SELECT
            item.ItemName,
            repair_wash.DocNo,
            sum(repair_wash_detail.Qty) as Qty ,
            sum(repair_wash_detail.Weight) as Weight
          FROM
            repair_wash_detail
          INNER JOIN item ON repair_wash_detail.ItemCode = item.ItemCode
          INNER JOIN repair_wash ON repair_wash_detail.DocNo = repair_wash.DocNo
          INNER JOIN department ON department.depcode = repair_wash.depcode
          $where
          AND repair_wash.FacCode = $FacCode
          AND department.HptCode = '$HptCode'
          AND repair_wash.isStatus<> 9
          AND repair_wash.isStatus<> 0
          GROUP BY  item.ItemCode
          ORDER BY repair_wash.DocNo ASC ";
// ==============================================================================================
// หัวตาราง
$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 22);
$pdf->Cell(0, 10,  $array2['r6'][$language], 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 14);
$pdf->Cell(0, 10,  $array2['hospital'][$language] . " : " . $HptName, 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('thsarabunnew', 'b', 12);
$pdf->Cell(120, 5,   $array2['factory'][$language] . " : " . $facname, 0, 0, 'L');
$pdf->Cell(60, 5,  $date_header, 0, 1, 'R');
$pdf->Ln(3);
// หัวตาราง

// ==============================================================================================
// TH 
$header = array($array['no'][$language],
                $array2['itemname'][$language],
                $array['qty'][$language],
                $array['unit'][$language] .' (ชิ้น)',
                $array['weight'][$language].' (kg)' );

$html = '<table cellspacing="0" cellpadding="3" border="1" ><thead>
<tr>
    <th width="15 %" align="center">' . $header[0] . '</th>
    <th width="50 %" align="center">' . $header[1] . '</th>
    <th width="15 %"  align="center">' . $header[2] . '</th>
    <th width="20 %" align="center">' . $header[4] . '</th>
</tr></thead>';
// TH 
// ==============================================================================================

// Detail
$meQuery = mysqli_query($conn, $detail);
while ($Result = mysqli_fetch_assoc($meQuery))
{
  $Total_Weight = $Result['Qty'] * $Result['Weight'];
  $html .= '<tr nobr="true">';
  $html .=   '<td width="15 %" align="center">' . $count . '</td>';
  $html .=   '<td width="50 %" align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="15 %" align="center">' . $Result['Qty'] . '</td>';
  $html .=   '<td width="20 %" align="center">' . $Result['Weight'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Weight'];
  $totalqty += $Result['Qty'];
  $count++;
}
$html .= '</table>';

// Total
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 7.0);
$pdf->Cell(117, 7,  $array2['total'][$language], 1, 0, 'C');
$pdf->Cell(27, 7,   number_format($totalqty, 2), 1, 0, 'C');
$pdf->Cell(36, 7,   number_format($totalsum, 2), 1, 0, 'C');

// ---------------------------------------------------------
//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_repair_wash_Linen_Weight' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
