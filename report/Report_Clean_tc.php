<?php

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
$Dclean = [];
$Wclean = [];
$rowss = 0;
//--------------------------------------------------------------------------
$language = $_GET['lang'];
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
    $language = $_GET['lang'];
    if ($language == "en") {
      $language = "en";
    } else {
      $language = "th";
    }
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
    $language = $_GET['lang'];
    if ($language == "en") {
      $language = "en";
    } else {
      $language = "th";
    }
    $DocNo = $_GET['DocNo'];
    if ($this->last_page_flag) {
      require('connect.php');
      $head = "SELECT
      clean.SignFac,
      clean.SignNH,
      SignFacTime as  SignFacTime ,
      SignNHTime as  SignNHTime
      FROM
      clean
      WHERE clean.DocNo = '$DocNo'
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
      if ($date1 == '--543' || $date1 == '--') {
        $date1 = ' ';
      }
      if ($date2 == '--543' || $date2 == '--') {
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
$pdf->SetTitle('Report Clean');
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
$pdf->SetMargins(15, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
$header = array($array['no'][$language], $array2['itemname'][$language], $array2['detail'][$language], $array['qty'][$language], $array['weight'][$language], $array['unit'][$language]);
$count = 1;
// ------------------------------------------------------------------------------
$head = "SELECT   site.$HptName,
department.DepName,
clean.DocNo,
DATE_FORMAT(clean.DocDate,'%d-%m-%Y')AS DocDate,
clean.Total,
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
TIME(clean.Modify_Date) AS xTime,
clean_ref.RefDocNo,
factory.facname
FROM clean
LEFT JOIN department ON clean.DepCode = department.DepCode
LEFT JOIN site ON department.HptCode = site.HptCode
LEFT JOIN factory ON factory.FacCode = clean.FacCode
LEFT JOIN users ON clean.Modify_Code = users.ID
LEFT JOIN clean_ref ON clean.Docno = clean_ref.Docno
WHERE clean.DocNo = '$DocNo'
ORDER BY clean_ref.RefDocNo ASC  ";

$meQuery = mysqli_query($conn, $head);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Hpt = $Result[$HptName];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
  $RefDocNo[] = $Result['RefDocNo'];
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
clean_detail.ItemCode,
item.ItemName,
item_unit.UnitName,
clean_detail.Qty ,
clean_detail.Weight,
clean_detail.Detail,
clean_detail.RequestName
FROM item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
RIGHT JOIN clean_detail ON clean_detail.ItemCode = item.ItemCode
INNER JOIN item_unit ON clean_detail.UnitCode = item_unit.UnitCode
WHERE clean_detail.DocNo = '$DocNo'
ORDER BY item.ItemName ASC";

$pdf->AddPage('P', 'A4');
$pdf->SetFont('thsarabunnew', 'b', 24);
$pdf->Cell(0, 10,  $array2['r3'][$language], 0, 0, 'C');
$pdf->Ln(15);
$pdf->SetFont('thsarabunnew', 'b', 16);

$pdf->Cell(35, 7, $array2['hospital'][$language], 0, 0, 'L');
$pdf->Cell(75, 7, " : " . $Hpt, 0, 0, 'L');
$pdf->Cell(28, 7, $array['department'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $DepName, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(35, 7, $array['docno'][$language], 0, 0, 'L');
$pdf->Cell(75, 7, " : " . $DocNo, 0, 0, 'L');
$pdf->Cell(28, 7, $array['factory'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $facname, 0, 0, 'L');
$pdf->Ln();

$pdf->Cell(35, 7, $array['docdate'][$language], 0, 0, 'L');
$pdf->Cell(75, 7, " : " . $DocDate, 0, 0, 'L');
$pdf->Cell(28, 7, $array['time'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $xTime, 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(35, 7, $array2['user'][$language], 0, 0, 'L');
$pdf->Cell(75, 7, " : " . $FirstName, 0, 0, 'L');
$pdf->Cell(28, 7, $array2['refdocno'][$language], 0, 0, 'L');
$pdf->Cell(55, 7, " : " . $RefDocNo[$rowss], 0, 0, 'L');
$rowss++;
$pdf->Ln();
$count_docno = sizeof($RefDocNo);
$row1 = ($count_docno - 1) / 2;
$row1 = (int) ($row1);
$row2 = ($count_docno - 1) % 2;
if ($row1 <> 0) {
  for ($ref = 0; $ref < $row1; $ref++) {
    $pdf->Cell(35, 7, $array['refdocno'][$language], 0, 0, 'L');
    $pdf->Cell(75, 7, " : " . $RefDocNo[$rowss], 0, 0, 'L');
    $rowss++;
    $pdf->Cell(28, 7, $array2['refdocno'][$language], 0, 0, 'L');
    $pdf->Cell(55, 7, " : " . $RefDocNo[$rowss], 0, 0, 'L');
    $rowss++;
    $pdf->Ln();
  }
}
if ($row2 == 1) {
  $pdf->Cell(35, 7, $array['refdocno'][$language], 0, 0, 'L');
  $pdf->Cell(75, 7, " : " . $RefDocNo[$rowss], 0, 0, 'L');
}

$pdf->Ln();
$pdf->Ln(5);
$html = '<table cellspacing="0" cellpadding="3" border="1" ><thead> 
<tr style="font-size: 16 px;" >
    <th width="10 % " align="center">' . $header[0] . '</th>
    <th width="35 % " align="center">' . $header[1] . '</th>
    <th width="20 % "  align="center">' . $header[2] . '</th>
    <th width="10 % " align="center">' . $header[3] . '</th>
    <th width="15 % " align="center">' . $header[4] . '</th>
    <th width="15 % " align="center">' . $header[5] . '</th>
</tr></thead> ';
$meQuery = mysqli_query($conn, $data);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result['ItemName'] = $Result['RequestName'];
  }
  $Total_Weight = $Result['Qty'] * $Result['Weight'];
  $html .= '<tr style="font-size: 16 px;" nobr="true">';
  $html .=   '<td width="10 % " align="center">' . $count . '</td>';
  $html .=   '<td width="35 % " align="left"> ' . $Result['ItemName'] . '</td>';
  $html .=   '<td width="20 % " align="center">' . $Result['Detail'] . '</td>';
  $html .=   '<td width="10 % " align="center">' . $Result['Qty'] . '</td>';
  $html .=   '<td width="15 % " align="center">' . $Result['Weight'] . '</td>';
  $html .=   '<td width="15 % " align="center">' . $Result['UnitName'] . '</td>';
  $html .=  '</tr>';
  $totalsum += $Result['Weight'];
  $count++;
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->SetFont('thsarabunnew', 'b', 13);
$pdf->SetLineWidth(0.3);
$pdf->sety($pdf->Gety() - 9.0);
$pdf->Cell(135, 5,  $array2['total'][$language], 1, 0, 'C');
$pdf->Cell(27, 5,   number_format($totalsum, 2), 1, 1, 'C');
$pdf->ln();

$SUM = "SELECT
	dirty.Total AS Wdirty,
	clean.DocNo AS Dclean,
	dirty.DocNo AS Ddirty,
	clean.Total AS Wclean,
	newlinentable.DocNo AS Dnewlinentable,
	newlinentable.Total AS Wnewlinentable
FROM
	clean
LEFT JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
LEFT JOIN dirty ON clean_ref.RefDocNo = dirty.DocNo
LEFT JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.DocNo
WHERE clean_ref.DocNo = '$DocNo' Order by clean_ref.RefDocNo ASC  ";
// echo $SUM;
$meQuery = mysqli_query($conn, $SUM);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Wdirty[] = $Result['Wdirty'];
  $Wclean[] = $Result['Wclean'];
  $Wnewlinentable[] = $Result['Wnewlinentable'];
  $Dclean[] = $Result['Dclean'];
  $Ddirty[] = $Result['Ddirty'];
  $Dnewlinentable[] = $Result['Dnewlinentable'];
}
$pdf->Cell(40, 5,  '', 0, 0, 'C');
$pdf->Cell(50, 5,  $array2['detail'][$language], 1, 0, 'C');
$pdf->Cell(30, 5,  $array2['docno'][$language], 1, 0, 'C');
$pdf->Cell(30, 5,  $array2['weight'][$language], 1, 0, 'C');
$pdf->ln();
$pdf->Cell(40, 5,  '', 0, 0, 'C');
$pdf->Cell(50, 5,  $array2['docclean'][$language], 1, 0, 'C');
$pdf->Cell(30, 5,  $DocNo, 1, 0, 'C');
$pdf->Cell(30, 5,  number_format($totalsum, 2), 1, 0, 'C');
$pdf->ln();

if ($Ddirty <> null ||  $Dnewlinentable <> null) {
  if ($Ddirty[0] <> null && $Ddirty[0] <> '') {
    $pdf->Cell(40, 5,  '', 0, 0, 'C');
    $pdf->Cell(50, 6 * $count_docno,  $array2['docdirty'][$language], 1, 0, 'C');
    for ($i = 0; $i < $count_docno; $i++) {
      if ($i > 0) {
        $pdf->Cell(40, 5,  '', 0, 0, 'C');
        $pdf->Cell(50, 5,  '', 0, 0, 'C');
      }
      $pdf->Cell(30, 5,  $RefDocNo[$i], 1, 0, 'C');
      $pdf->Cell(30, 5,  number_format($Wdirty[$i], 2), 1, 1, 'C');
      $TOTALweight += $Wdirty[$i];
    }
  }

  if ($Dnewlinentable[0] <> null && $Dnewlinentable[0] <> '' ) {
    $pdf->Cell(40, 5,  '', 0, 0, 'C');
    $pdf->Cell(50, 6 * $count_docno,  $array2['newlinendoc'][$language], 1, 0, 'C');
    for ($i = 0; $i < $count_docno; $i++) {
      if ($i > 0) {
        $pdf->Cell(40, 5,  '', 0, 0, 'C');
        $pdf->Cell(50, 5,  '', 0, 0, 'C');
      }
      $pdf->Cell(30, 5,  $RefDocNo[$i], 1, 0, 'C');
      $pdf->Cell(30, 5,  number_format($Wclean[$i], 2), 1, 1, 'C');
      $TOTALweight += $Wclean[$i];
    }
  }

  $total = (($TOTALweight / $totalsum) - 1) * 100;
  $pdf->Cell(40, 5,  '', 0, 0, 'C');
  $pdf->Cell(50, 5,  '', 0, 0, 'C');
  $pdf->Cell(30, 5,  $array2['total'][$language], 1, 0, 'C');
  $pdf->Cell(30, 5,  number_format($total, 2) . " %", 1, 0, 'C');
}
// ---------------------------------------------------------

//Close and output PDF document
$ddate = date('d_m_Y');
$pdf->Output('Report_Clean_' . $date . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
