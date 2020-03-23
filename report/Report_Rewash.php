<?php
require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
// Date
// $eDate = "2018-06-06";
$eDate = $_GET['eDate'];
$eDate = explode("/", $eDate);
$eDate = $eDate[2] . '-' . $eDate[1] . '-' . $eDate[0];

$dept = $_GET['dept'];

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $language = $_GET['lang'];
    $datetime = new DatetimeTH();

    $eDate = $_GET['eDate'];
    $eDate = explode("/", $eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d-m-Y');
    $edate = $eDate[0] . " " . $datetime->getTHmonthFromnum($eDate[1]) . " พ.ศ. " . $datetime->getTHyear($eDate[2]);

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 12);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", $array['printdate'][$language] . ' ' . $printdate), 0, 0, 'R');
      $this->Ln(10);
      // Title
      $image = "../report_linen/images/Nhealth_linen 4.0.png";
      $this->Image($image, 10, 10, 43, 15);
      $this->Ln(10);
      $this->SetFont('THSarabun', 'b', 21);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['r6'][$language]), 0, 0, 'C');
      // Line break
      $this->Ln(10);
    } else {
      $this->Ln(15);
    }
  }

  // Page footer
  function Footer()
  {
    if ($this->isFinished) {
      $this->SetY(-30);
      $xml = simplexml_load_file('../xml/general_lang.xml');
      $xml2 = simplexml_load_file('../xml/report_lang.xml');
      $json = json_encode($xml);
      $array = json_decode($json, TRUE);
      $json2 = json_encode($xml2);
      $array2 = json_decode($json2, TRUE);
      $language = $_SESSION['lang'];
      $this->SetFont('THSarabun', 'b', 10);
      $this->Cell(5);
      $this->Cell(130, 10, iconv("UTF-8", "TIS-620", $array2['comlinen'][$language] . "..............................................."), 0, 0, 'L');
      $this->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['comlaundry'][$language] . "........................................"), 0, 0, 'L');
      $this->Ln(7);
      $this->Cell(5);
      $this->Cell(130, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . "......................................................................"), 0, 0, 'L');
      $this->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['date'][$language] . ".........................................................."), 0, 0, 'L');
      $this->Ln(7);
    }
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', '', 9);
    // Page number
    $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "") . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $xml = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $language = $_GET['lang'];
    $total = 0;
    $wtotal = 0;
    $y = 92;
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 16);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 7, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $txt = getStrLenTH($inner_array[$field[1]]); // 10
        $round = $txt / 33;
        list($main, $point) = explode(".", $round);
        if ($point > 0) {
          $point = 1;
          $main += $point;
        }
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
        $this->SetX($w[0] + 10);
        $this->MultiCell($w[1], 10 / $main, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 'L');
        $this->SetXY($w[0] + $w[1]  + 10, $y);
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[2]]) . " "), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]] . " "), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]], 2) . " "), 1, 0, 'C');
        $this->Ln();
        $total = $inner_array[$field[3]] + $total;
        $count++;
        $y += 10;
      }
    }

    $this->Cell($w[0] + $w[1] + $w[2] + $w[3], 6, iconv("UTF-8", "TIS-620", $array['total'][$language]), 1, 0, 'C');
    $this->Cell($w[3], 6, iconv("UTF-8", "TIS-620", number_format($total, 2) . " "), 1, 0, 'C');
    $this->Ln();
    if ($count == 18) {
      $this->AddPage();
    }
  }
}
function getMBStrSplit($string, $split_length = 1)
{
  mb_internal_encoding('UTF-8');
  mb_regex_encoding('UTF-8');

  $split_length = ($split_length <= 0) ? 1 : $split_length;
  $mb_strlen = mb_strlen($string, 'utf-8');
  $array = array();
  $i = 0;

  while ($i < $mb_strlen) {
    $array[] = mb_substr($string, $i, $split_length);
    $i = $i + $split_length;
  }

  return $array;
}
// Get string length for Character Thai
function getStrLenTH($string)
{
  $array = getMBStrSplit($string);
  $count = 0;

  foreach ($array as $value) {
    $ascii = ord(iconv("UTF-8", "TIS-620", $value));

    if (!($ascii == 209 || ($ascii >= 212 && $ascii <= 218) || ($ascii >= 231 && $ascii <= 238))) {
      $count += 1;
    }
  }
  return $count;
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

$DocNo = $_GET['DocNo'];

// Using Coding
$pdf->AddPage();

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

$Sql = "SELECT   site.$HptName,
        department.DepName,
        repair_wash.DocNo,
        DATE_FORMAT(repair_wash.DocDate,'%d-%m-%Y')AS DocDate,
        repair_wash.Total,
        CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
        TIME(repair_wash.Modify_Date) AS xTime,
        repair_wash.RefDocNo ,
        repair_wash.IsStatus
        FROM repair_wash
        INNER JOIN department ON repair_wash.DepCode = department.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        INNER JOIN users ON repair_wash.Modify_Code = users.ID
        WHERE repair_wash.DocNo = '$DocNo'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result[$HptName];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
  $RefDocNo = $Result['RefDocNo'];
  $isStatus = $Result['IsStatus'];
}
if ($isStatus == 0)
{
  $Status = 'On Process';
}
elseif ( $isStatus == 1)
{
  $Status = 'Complete';
}
elseif ($isStatus == 9)
{
  $Status = 'Cancel';
}
$pdf->SetFont('THSarabun', 'b', 16);
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $HptName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['department'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DepName), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $DocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docdate'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DocDate), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['refdocno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $RefDocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['time'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $xTime), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['user'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $FirstName), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['status'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $Status), 0, 0, 'L');
$pdf->Ln();
$pdf->Ln(5);
$query = "SELECT
          repair_wash_detail.ItemCode,
          item.ItemName,
          item_unit.UnitName,
          sum(repair_wash_detail.Qty) as Qty ,
          sum(repair_wash_detail.Weight) as Weight
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          INNER JOIN repair_wash_detail ON repair_wash_detail.ItemCode = item.ItemCode
          WHERE repair_wash_detail.DocNo = '$DocNo'
		       GROUP BY item.ItemCode
          ORDER BY item.ItemName ASC
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = "no,ItemName,Qty,Weight,UnitName";
// Table header
$header = array($array['no'][$language], $array['itemname'][$language], $array['qty'][$language], $array['unit'][$language], $array['weight'][$language]);
// width of column table
$width = array(15, 60, 35, 40, 40);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->isFinished = true;
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Rewash_' . $ddate . '.pdf');
