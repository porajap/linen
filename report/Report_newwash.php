<?php
require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
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
    list($day, $mouth, $year) = explode("-", $printdate);
    if ($language == 'th') {
      $year = $year + 543;
      $date_header =  $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 12);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", $array['printdate'][$language] . ' ' . $date_header), 0, 0, 'R');
      $this->Ln(10);
      // Title
      $image = "../report_linen/images/Nhealth_linen 4.0.png";
      $this->Image($image, 10, 10, 43, 15);
      $this->Ln(10);
      $this->SetFont('THSarabun', 'b', 21);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['reportnewlinen'][$language]), 0, 0, 'C');
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
      $xml = simplexml_load_file('../report_linen/xml/report_lang.xml');
      $json = json_encode($xml);
      $array = json_decode($json, TRUE);
      $this->SetFont('THSarabun', '', 10);
      $this->SetY(-27);
      $language = $_GET['lang'];
      $this->SetFont('THSarabun', 'b', 11);

      $this->Cell(120, 10, iconv("UTF-8", "TIS-620", $array['comlinen'][$language] . "............................................"), 0, 0, 'L');
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['comlaundry'][$language] . "........................................"), 0, 0, 'L');
      $this->Ln(7);

      $this->Cell(120, 10, iconv("UTF-8", "TIS-620", $array['date'][$language] . "..................................................................."), 0, 0, 'L');
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", $array['date'][$language] . ".........................................................."), 0, 0, 'L');
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
    $y = 85;
    $next_page = 1;
    $fisrt_page = 0;
    $r = 1;
    $status = 0;
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 16);

    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
    $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 1, 'C');


    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($r > 19) {
          $next_page++;
          if ($status == 0) {
            $this->SetFont('THSarabun', 'b', 14);
            $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
            $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
            $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
            $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
            $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
            $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
            $this->Ln();
            $y = 25;
            $status = 1;
          }
          if ($next_page % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            $this->Ln();
            $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $header[0]), 1, 0, 'C');
            $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $header[1]), 1, 0, 'C');
            $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", $header[5]), 1, 0, 'C');
            $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $header[2]), 1, 0, 'C');
            $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $header[4]), 1, 0, 'C');
            $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $header[3]), 1, 0, 'C');
            $this->Ln();
            $y = 25;
            $next_page = 1;
          }
        }


        $this->SetFont('THSarabun', '', 13);

        $txt = getStrLenTH($inner_array[$field[1]]); // 10
        $round = $txt / 32;
        list($main, $point) = explode(".", $round);
        if ($point > 0) {
          $point = 1;
          $main += $point;
        }
        $txt2 = getStrLenTH(trim($inner_array[$field[5]])); // 10
        $round2 = $txt2 / 30;
        // echo $round2 ."<br>";
        list($main2, $point2) = explode(".", $round2);
        if ($point2 > 0) {
          $point2 = 1;
          $main2 += $point2;
        }
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $count + 1), 1, 0, 'C');
        $this->SetX($w[0] + 15);
        $this->MultiCell($w[1], 10 / $main, iconv("UTF-8", "TIS-620",  $inner_array[$field[1]]), 1,  'L');
        $this->SetXY($w[0] + $w[1]  + 15, $y);
        $this->MultiCell($w[5], 10 / $main2, iconv("UTF-8", "TIS-620", trim($inner_array[$field[5]]) . " "), 1, 'C');
        $this->SetXY($w[0] + $w[1] + $w[5] + 15, $y);
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[2]]) . " "), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[4]] . " "), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]] . " "), 1, 0, 'C');
        $this->Ln();
        $total = $inner_array[$field[3]] + $total;
        $count++;
        $y += 10;
        $r++;
      }
    }


    $this->Cell($w[0] + $w[1] + $w[2] + $w[3] + $w[5], 10, iconv("UTF-8", "TIS-620", $array['total'][$language]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($total, 2) . " "), 1, 0, 'C');
    $this->Ln();
    if ($count == 18 ) {
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

$Sql = "SELECT site.$HptName,
        department.DepName,
        newlinentable.DocNo,
        DATE_FORMAT(newlinentable.DocDate,'%d-%m-%Y')AS DocDate,
        newlinentable.Total,
        CONCAT($Perfix,' ' , $Name,' ' ,$LName)  AS FName,
        TIME(newlinentable.Modify_Date) AS xTime,
        newlinentable.RefDocNo
        FROM newlinentable
INNER JOIN newlinentable_detail ON newlinentable.DocNo = newlinentable_detail.DocNo
INNER JOIN department ON newlinentable_detail.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON newlinentable.Modify_Code = users.ID
        WHERE newlinentable.DocNo = '$DocNo' limit 1 ";
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
}
list($d, $m, $y) = explode('-', $DocDate);
if ($language == 'th') {
  $y = $y + 543;
} else {
  $y = $y;
}
$DocDate = $d . "-" . $m . "-" . $y;
$pdf->SetFont('THSarabun', 'b', 16);

$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['hospital'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $HptName), 0, 0, 'L');
// $pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['department'][$language]),0,0,'L');
// $pdf->Cell(40,10,iconv("UTF-8","TIS-620"," : ".$DepName),0,0,'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docdate'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $DocDate), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['docno'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $DocNo), 0, 0, 'L');
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['time'][$language]), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", " : " . $xTime), 0, 0, 'L');

$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22, 10, iconv("UTF-8", "TIS-620", $array['user'][$language]), 0, 0, 'L');
$pdf->Cell(78, 10, iconv("UTF-8", "TIS-620", " : " . $FirstName), 0, 0, 'L');

$pdf->Ln();
$pdf->Cell(15);



$pdf->SetMargins(15, 0, 0);
$pdf->Ln();
$pdf->Ln(5);
$query = "SELECT
	newlinentable_detail.ItemCode,
	item.ItemName,
	item_unit.UnitName,
department.DepName,
	sum(newlinentable_detail.Qty) AS Qty,
	sum(
		newlinentable_detail.Weight
	) AS Weight
FROM
	item
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN newlinentable_detail ON newlinentable_detail.ItemCode = item.ItemCode
INNER JOIN department ON newlinentable_detail.DepCode = department.DepCode
WHERE
             newlinentable_detail.DocNo = '$DocNo'
		       GROUP BY item.ItemCode,department.DepName
           ORDER BY
	department.Depcode,item.ItemCode ASC
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = "no,ItemName,Qty,Weight,UnitName,DepName";
// Table header
$header = array($array['no'][$language], $array['itemname'][$language], $array['qty'][$language], $array['weight'][$language], $array['unit'][$language], $array['department'][$language]);
// width of column table
$width = array(15, 55, 20, 20, 20, 50);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 12);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->isFinished = true;

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_NewWash_' . $ddate . '.pdf');
