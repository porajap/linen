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
$ppu = $data['ppu'];
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
    $where =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
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
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (clean.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE MONTH(clean.DocDate) BETWEEN '$date1' AND '$date2'";
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1)  . " " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2);
  } else {
    $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2);
  }
}


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
    $this->SetFont('THSarabun', 'b', 13);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $rows = 1;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 22) {
          $count++;
          if ($count % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 12);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $sum = ($inner_array[$field[1]] + $inner_array[$field[2]]) * $inner_array[$field[3]];
        $tax = (($inner_array[$field[1]] + $inner_array[$field[2]]) * $inner_array[$field[3]]) * 0.07;
        $total = $sum;
        $pdf->SetFont('THSarabun', '', 12);
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[0]]), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[1]]), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[2]]), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $inner_array[$field[3]]), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", number_format($total, 2)), 1, 0, 'C');
        $this->Ln();
        $rows++;
        $totalsum1 += $inner_array[$field[1]];
        $totalsum2 += $inner_array[$field[2]];
        $totalsum3 += $total;
        $totalsum4 = $totalsum3 * 107 / 100;
        $tax = $totalsum3 * 7 / 100;
      }
    }
    $this->SetFont('THSarabun', 'b', 12);
    $footer = array('VAT 7%', '', '', '', number_format($tax, 2));
    for ($i = 0; $i < count($footer); $i++)
    $pdf->Cell($width[$i], 10, iconv("UTF-8", "TIS-620", $footer[$i] . " "), 1, 0, 'C');
    $pdf->Ln();

    $footer = array($array2['total'][$language], number_format($totalsum1, 2), number_format($totalsum2, 2), '', number_format($totalsum4, 2));
    $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", $footer[0]), 1, 0, 'C');
    $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", $footer[1]), 1, 0, 'C');
    $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", $footer[2]), 1, 0, 'C');
    $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", $footer[3]), 1, 0, 'C');
    $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", $footer[4]), 1, 1, 'C');


    if ($count % 25 >= 22) {
      $pdf->AddPage("P", "A4");
    }
    $pdf->SetFont('THSarabun', 'b', 12);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "จำนวนกิโลกรัม = Clean Linen (After inspection) + Repair Convert by item to weight and sum"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "ราคาต่อกิโลกรัม = Price per kg. Depend on outsource Laundry"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "รวมเป็นเงิน = Total amount exclude vat 7%"), 0, 0, 'L');
    $pdf->Ln(10);
    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
    $pdf->Ln(8);
    $pdf->SetFont('THSarabun', 'b', 11);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่ห้องผ้า..................................................."), 0, 0, 'L');
    $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "เจ้าหน้าที่โรงซัก........................................"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(5);
    $pdf->Cell(130, 10, iconv("UTF-8", "TIS-620", "วันที่......................................................................"), 0, 0, 'L');
    $pdf->Cell(40, 10, iconv("UTF-8", "TIS-620", "วันที่.........................................................."), 0, 0, 'L');
    $pdf->Ln(7);
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
DATE(clean.DocDate) AS DocDate,
site.Hptname,
item_main_category.maincategoryname
FROM
clean
INNER JOIN dirty ON dirty.DocNo = clean.RefDocNo
INNER JOIN department ON dirty.Depcode=department.Depcode
INNER JOIN site ON site.Hptcode=department.Hptcode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
INNER JOIN clean_detail ON clean.DocNo = clean_detail.DocNo
INNER JOIN item ON clean_detail.itemcode = item.itemcode
INNER JOIN item_category ON item_category.categorycode = item.categorycode
INNER JOIN item_main_category ON item_category.maincategorycode = item_main_category.maincategorycode
        $where
      AND  site.HptCode = '$HptCode'
      AND  item_main_category.maincategorycode = '$ppu'
      ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $factory = $Result['FacName'];
  $Hptname = $Result['Hptname'];
  $catname = $Result['maincategoryname'];
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
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['r13'][$language] . "  " . $catname), 0, 0, 'C');
// Line break
$pdf->Ln(7);
$pdf->Cell(80);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(30, 10, iconv("UTF-8", "TIS-620", $array2['site'][$language] . " : " .  $Hptname), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('THSarabun', 'b', 14);
$pdf->Cell(1);
$pdf->Cell(160, 10, iconv("UTF-8", "TIS-620", $array2['factory'][$language] . " : " . $factory), 0, 0, 'L');
$pdf->Cell(ุ60, 10, iconv("UTF-8", "TIS-620", $date_header), 0, 0, 'R');
$pdf->Ln(12);

$query = "SELECT 
  IFNULL(clean_weight, 0) AS clean_weight,
IFNULL(repair_Weight, 0) AS repair_Weight,
Price,
ItemName
FROM(
SELECT
COALESCE (sum(clean_detail.Weight),'0') AS clean_weight, category_price.Price as  Price,
item.ItemName as ItemName
FROM 
clean
INNER JOIN clean_detail ON clean_detail.DocNo = clean.DocNo
INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
INNER JOIN item ON clean_detail.ItemCode=item.ItemCode
INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
$where
AND dirty.FacCode= $FacCode
AND item_main_category.MainCategoryCode = '$ppu'
GROUP BY item.ItemCode  )a,
(
SELECT 
COALESCE (sum(repair_detail.Weight),'0') AS  repair_Weight
FROM
repair
INNER JOIN repair_detail ON repair.DocNo= repair_detail.DocNo
INNER JOIN claim ON claim.DocNo=repair.DocNo
INNER JOIN clean ON claim.RefDocNo=clean.DocNo
INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
INNER JOIN factory ON factory.FacCode = dirty.FacCode
INNER JOIN item ON repair_detail.ItemCode=item.ItemCode
INNER JOIN item_category ON item.CategoryCode=item_category.CategoryCode
INNER JOIN item_main_category ON item_main_category.MainCategoryCode=item_category.MainCategoryCode
$where
AND dirty.FacCode=$FacCode
AND item_main_category.MainCategoryCode = '$ppu')b

            ";
// var_dump($query); die;
// Number of column
$numfield = 5;
// Field data (Must match with Query)
$field = "ItemName,clean_weight,repair_Weight,Price,TOTAL";
// Table header
$header = array($array2['itemname'][$language], $array2['weightclean'][$language], $array2['weightrepair'][$language], $array2['priceperkilo'][$language], $array2['total'][$language]);
// width of column table
$width = array(60, 32.5, 32.5, 32.5, 32.5);
// Get Data and store in Result
$result = $data->getdata($conn, $query, $numfield, $field);
// Set Table
$pdf->SetFont('THSarabun', 'b', 10);
$pdf->setTable($pdf, $header, $result, $width, $numfield, $field);
$pdf->Ln();
// Get $totalsum
$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_Summary_Dirty_' . $ddate . '.pdf');
