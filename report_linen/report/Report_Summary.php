<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$format = $data['Format'];
$DepCode = $data['DepCode'];
$where = '';
$language = $_SESSION['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
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
  $where =   "WHERE MONTH(shelfcount.DocDate) BETWEEN '$date1' AND '$date2'";
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1)  . " " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2);
  } else {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2);
  }
}


header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  { }



  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 9);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", '') . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $language = $_SESSION['lang'];
    if ($language == "en") {
      $language = "en";
    } else {
      $language = "th";
    }
    $xml = simplexml_load_file('../xml/general_lang.xml');
    $xml2 = simplexml_load_file('../xml/report_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $json2 = json_encode($xml2);
    $array2 = json_decode($json2, TRUE);
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 1;
    $rows = 0;

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $total = $inner_array[$field[3]] * $inner_array[$field[4]];
        $this->SetFont('THSarabun', '', 14);
        for ($i = 0; $i < count($inner_array[$field[0]]); $i++) {
          if ($inner_array[$field[0]] == $date) {
            $this->Cell($w[0], 10, "", 1, 0, 'C');
            $loop++;
          } else {
            $count = 1;
            $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
            $date = $inner_array[$field[0]];
            $loop = 0;
            $check++;
          }
          $sum_loop[] = $loop;
          $sum_check[] = $check;
        }

        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620",  $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[4]], 2)), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($total, 2)), 1, 0, 'C');
        $this->Ln();
        $count++;
        $totalsum += $total;
      }
    }
    $footer = array('', '', '', '', $array2['total'][$language], number_format($totalsum, 2));
    $pdf->SetFont('THSarabun', 'b', 14);
      $pdf->Cell(160, 10, iconv("UTF-8", "TIS-620", $footer[4] ), 1, 0, 'C');
      $pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $footer[5] ), 1, 0, 'C');
    


    // Closing line

  }
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

// Using Coding
$pdf->AddPage("P", "A4");

$Sql = "SELECT
        shelfcount.DocNo,
        department.DepName,
        site.HptName
        FROM
        shelfcount
        INNER JOIN department ON shelfcount.DepCode = department.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        $where
        AND shelfcount.DepCode = $DepCode
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $HptName = $Result['HptName'];
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
// Move to the right
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(5);
// Title


$pdf->SetFont('THSarabun', 'b', 20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r14'][$language]), 0, 0, 'C');
// Line break
$pdf->Ln(12);

$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(95, 7, iconv("UTF-8", "TIS-620", $array2['site'][$language]." : ". $HptName ."     ". $array2['department'][$language] ." : ". $DeptName), 0, 0, 'L');
$pdf->Cell(95, 7, iconv("UTF-8", "TIS-620", $date_header), 0, 1, 'R');
$pdf->Ln(3);




$query = "SELECT
item.ItemName,
  item.ItemCode,
shelfcount_detail.ParQty,
shelfcount_detail.TotalQty,
category_price.Price,
category_price.CategoryCode,
shelfcount_detail.DocNo
FROM
shelfcount_detail
INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
INNER JOIN department ON shelfcount.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
          $where
        AND shelfcount.DepCode =$DepCode
        AND category_price.HptCode = '$HptCode'
        ORDER BY shelfcount_detail.DocNo ASC  ";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "DocNo,ItemName,ParQty,TotalQty,Price,";
// Table header
$header = array($array2['docno'][$language], $array2['itemname'][$language], $array2['parqty'][$language], $array2['qty'][$language], $array2['price1'][$language], $array2['total'][$language]);
// width of column table
$width = array(35, 35, 30, 30, 30, 30);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum



$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Summary_' . $ddate . '.pdf');
