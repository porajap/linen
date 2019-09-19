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
    $where =   "WHERE DATE (rewash.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (rewash.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE rewash.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (rewash.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(rewash.Docdate) BETWEEN $date1 AND $date2";
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
    $this->SetFont('THSarabun', 'b', 12);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $rows = 1;
    $loop = 0;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $date = "";
    $this->SetFont('THSarabun', '', 14);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 23) {
          $loop++;
          if ($loop % 24 == 1) {
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
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
        $this->SetFont('THSarabun', '', 16);

        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[2]])), 1, 0, 'C');
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[2]];
      }
    }
    // Footer Table
    $this->SetFont('THSarabun', 'B', 14);
    $this->Cell(140, 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    $this->Cell(50, 10, iconv("UTF-8", "TIS-620", number_format($totalsum1)), 1, 0, 'C');
    $pdf->Ln(10);

    $footer_nextpage = $loop % 24;
    if ($footer_nextpage >= 23) {
      $pdf->AddPage("P", "A4");
    }
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
        factory.FacName,
        DATE(rewash.DocDate) AS DocDate,
        TIME(rewash.DocDate) AS DocTime
        FROM
        rewash
        INNER JOIN factory ON rewash.FacCode = factory.FacCode
        INNER JOIN department ON department.depcode = rewash.depcode
        $where
        AND rewash.FacCode = $FacCode
        AND department.HptCode = '$HptCode'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $factory = $Result['FacName'];
}
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
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r6'][$language]), 0, 0, 'C');
// Line break
$pdf->Ln(10);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(140, 10, iconv("UTF-8", "TIS-620", $array2['factory'][$language] . " : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);

$query = "SELECT
          item.ItemName,
          rewash.DocNo,
          rewash_detail.Qty
          FROM
          rewash_detail
          INNER JOIN item ON rewash_detail.ItemCode = item.ItemCode
          INNER JOIN rewash ON rewash_detail.DocNo = rewash.DocNo
          INNER JOIN department ON department.depcode = rewash.depcode
          $where
          AND rewash.FacCode = $FacCode
          AND department.HptCode = '$HptCode'";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "DocNo,ItemName,Qty";
// Table header
$header = array($array2['docno'][$language], $array2['itemname'][$language], $array2['amount1'][$language],);
// width of column table
$width = array(60, 80, 50);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);


$pdf->SetFont('THSarabun', 'b', 11);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่ห้องผ้า..................................................."), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่โรงซัก........................................"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(5);
$pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "วันที่......................................................................"), 0, 0, 'L');
$pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่.........................................................."), 0, 0, 'L');
$pdf->Ln(12);
$pdf->Cell(190, 0, '', 'T');
$pdf->Ln(10);


$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Rewash_' . $ddate . '.pdf');
