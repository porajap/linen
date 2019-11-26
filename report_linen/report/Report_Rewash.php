<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data =explode( ',',$_GET['data']);
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
$year = $data['year'];
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
      $this->Cell(5);
      $this->Cell(130, 10, iconv("UTF-8", "TIS-620", "FL-NLP-BMC-00-00514 แก้ไขครั้งที่ 00 "), 0, 0, 'L');
      $this->Cell(40, 10, iconv("UTF-8", "TIS-620", $array2['Enforcementdate'][$language] . "  20/02/2562"), 0, 0, 'L');
    }
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 10);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", '') .$array2['page'][$language]. $this->PageNo() . '/{nb}', 0, 0, 'R');
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
    $this->SetFont('THSarabun', 'b', 16);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $rows = 1;
    $loop = 0;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $date = "";

    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        $this->SetFont('THSarabun', 'b', 16);
        if ($rows > 21) {
          $loop++;
          if ($loop % 25 == 1) {
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $this->SetFont('THSarabun', '', 14);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $rows), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'L');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[2]])), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($inner_array[$field[3]],2)), 1, 0, 'C');
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[2]];
        $totalsum2 += $inner_array[$field[3]];
      }
    }
    // Footer Table
    $this->SetFont('THSarabun', 'B', 14);
    $this->Cell($w[0]+$w[1], 10, iconv("UTF-8", "TIS-620", $array2['total'][$language]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", number_format($totalsum1)), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", number_format($totalsum2,2)), 1, 0, 'C');
    $pdf->Ln(10);

    // $footer_nextpage = $loop % 24;
    // if ($footer_nextpage >= 23) {
    //   $pdf->AddPage("P", "A4");
    // }
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
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
$Sql = "SELECT
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
        AND repair_wash.isStatus<> 9";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $factory = $Result[$FacName];
}
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
// Move to the right
$pdf->SetFont('THSarabun', '', 10);
$image = "../images/Nhealth_linen 4.0.png";
$pdf->Image($image, 10, 10, 43, 15);
$pdf->SetFont('THSarabun', '', 10);
$pdf->Cell(190, 10, iconv("UTF-8", "TIS-620", $array2['printdate'][$language] . $printdate), 0, 0, 'R');
$pdf->Ln(18);
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
          GROUP BY  item.ItemCode
          ORDER BY repair_wash.DocNo ASC ";
// .$Sql;
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "DocNo,ItemName,Qty,Weight";
// Table header
$header = array($array2['no'][$language], $array2['itemname'][$language], $array2['amount1'][$language],$array2['weight'][$language].' (kg)');
// width of column table
$width = array(20, 80, 50,40);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->SetFont('THSarabun', 'b', 11);

// $pdf->isFinished = true;

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Rewash_' . $ddate . '.pdf');
