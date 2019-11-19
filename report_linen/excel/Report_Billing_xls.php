<?php
include("PHPExcel-1.8/Classes/PHPExcel.php");
require('../report/connect.php');
require('../report/Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}
$language = "en";
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
$data = explode(',', $_GET['data']);
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
$year1 = $data[9];
$year2 = $data[10];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$status_group = 1;
$DepCode = [];
$DepName = [];
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}

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
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " . $year2;
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
  $where =   "WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2011 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2011 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.6, 2011-02-27
 */

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once 'PHPExcel-1.8/Classes/PHPExcel.php';

// Create new PHPExcel object
date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();
// Set properties
date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
  ->setLastModifiedBy("Maarten Balliauw")
  ->setTitle("Office 2007 XLSX Test Document")
  ->setSubject("Office 2007 XLSX Test Document")
  ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
  ->setKeywords("office 2007 openxml php")
  ->setCategory("Test result file");
// Page margins:
$objPHPExcel->getActiveSheet()
  ->getPageSetup()
  ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
$objPHPExcel->getActiveSheet()
  ->getPageSetup()
  ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()
  ->getPageMargins()->setTop(1);
$objPHPExcel->getActiveSheet()
  ->getPageMargins()->setRight(0.75);
$objPHPExcel->getActiveSheet()
  ->getPageMargins()->setLeft(0.75);
$objPHPExcel->getActiveSheet()
  ->getPageMargins()->setBottom(1);
$objPHPExcel->getActiveSheet()
  ->getHeaderFooter()->setOddFooter('&R Page &P / &N');
$objPHPExcel->getActiveSheet()
  ->getHeaderFooter()->setEvenFooter('&R Page &P / &N');
// Headers and Footers:
// $objPHPExcel->getActiveSheet()
//     ->getHeaderFooter()->setEvenFooter('&R&F Page &P / &N');
// Printer page breaks:

// $objPHPExcel->getActiveSheet()
//   ->setBreak('A10', PHPExcel_Worksheet::BREAK_ROW);
// // Showing grid lines:

$objPHPExcel->getActiveSheet()
  ->setShowGridlines(true);
// Setting rows/columns to repeat at the top/left of each page

$date_cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$date_cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$round_AZ1 = sizeof($date_cell1);
$round_AZ2 = sizeof($date_cell2);

for ($a = 0; $a < $round_AZ1; $a++) {
  for ($b = 0; $b < $round_AZ2; $b++) {
    array_push($date_cell1, $date_cell1[$a] . $date_cell2[$b]);
  }
}

// echo "<pre>";
// print_r($date_cell1);
// echo "</pre>"; 


// -----------------------------------------------------------------------------------

$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A8',  'GROUP NAME')
  ->setCellValue('B8',  $array['department'][$language]);
// Write data from MySQL result
$COUNT_DATE = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r28'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:J6');

// -----------------------------------------------------------------------------------
$query = "SELECT
grouphpt.GroupName,
department.DepName,
department.DepCode
FROM
grouphpt
INNER JOIN department ON grouphpt.GroupCode = department.GroupCode
INNER JOIN shelfcount ON shelfcount.DepCode = department.DepCode
GROUP BY  department.DepCode 
-- WHERE grouphpt.GroupCode = $GroupCode
            ";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($status_group == 1) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $Result["GroupName"]);
  }
  $i++;
  $count++;
  $status_group = 0;
  $DepName[] =  $Result["DepName"];
  $DepCode[] =  $Result["DepCode"];
}
// -----------------------------------------------------------------------------------
$now =  $year1 . '-' . $date1 . '-';
$day =    '/' . $date1 . '/' . $year1;
$r = 2;
$d = 1;
$rows = 9;
$count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
for ($row = 0; $row < $count; $row++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'นน.(Kg)');
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'มูล่ค่า(บาท)');
  $r++;
}
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'นน.(Kg)');
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'มูล่ค่า(บาท)');
$r++;
// -----------------------------------------------------------------------------------
$r = 2;
$j = 3;
$d = 1;
for ($row = 0; $row < $count; $row++) {
  $objPHPExcel->getActiveSheet()->mergeCells($date_cell1[$r] . '7:' . $date_cell1[$j] . '7');
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '7', $d . $day);
  $r += 2;
  $j += 2;
  $d++;
}
$objPHPExcel->getActiveSheet()->mergeCells($date_cell1[$r] . '7:' . $date_cell1[$j] . '7');
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '7', "total");
// -----------------------------------------------------------------------------------
$dap_data_row1 = 9;
$dap_data_row2 = 9;
$r = 1;
$j = 3;
$lek = 0;
$COUNT_DEP = SIZEOF($DepCode);
for ($q = 0; $q < $COUNT_DEP; $q++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $DepCode[$lek]);
  $r++;
  for ($day = 1; $day <= $count; $day++) {
    $data = "SELECT SUM(shelfcount_detail.Weight) AS aWeight , SUM(shelfcount_detail.Price ) AS aPrice FROM shelfcount 
                              INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo WHERE  DATE(shelfcount.DocDate)  ='$now$day' 
                              AND shelfcount.DepCode = '$DepCode[$lek]'  ";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $Result["aWeight"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $Result["aPrice"]);
      $r++;
      $sumdayweight += $Result["aWeight"];
      $sumdayprice += $Result["aPrice"];
    }
    $Totaldayweight += $sumdayweight;
    $Totaldayprice += $sumdayprice;
  }
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $sumdayweight);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $sumdayprice);
  $sumdayweight = 0;
  $sumdayprice = 0;
  $r = 1;
  $dap_data_row1++;
  $lek++;
}

$r = 1;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, 'total');
$r++;
for ($day = 1; $day <= $count; $day++) {
  $data = "SELECT SUM(shelfcount_detail.Weight) AS aWeight , SUM(shelfcount_detail.Price ) AS aPrice FROM shelfcount 
                INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo WHERE  DATE(shelfcount.DocDate)  ='$now$day' 
                 ";
  $meQuery = mysqli_query($conn, $data);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $Result["aWeight"]);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $Result["aPrice"]);
    $r++;
    $sumdayweight += $Result["aWeight"];
    $sumdayprice += $Result["aPrice"];
  }
}
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $sumdayweight);
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $dap_data_row1, $sumdayprice);
$A5 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 20,
    'name'  => 'THSarabun'
  )
);
$fill = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'size'  => 13,
    'name'  => 'THSarabun'
  )
);
$objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($A5);

// $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getColumnDimension("B")
  ->setAutoSize(true);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Nhealth_linen');
$objDrawing->setDescription('Nhealth_linen');
$objDrawing->setPath('Nhealth_linen 4.0.png');
$objDrawing->setCoordinates('A1');
//setOffsetX works properly
$objDrawing->setOffsetX(0);
$objDrawing->setOffsetY(0);
//set width, height
$objDrawing->setWidthAndHeight(150, 75);
$objDrawing->setResizeProportional(true);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Report_shelfcount');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Excel_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
//

// Save Excel 2007 file
#echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');
// It will be called file.xls
header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
$objWriter->save('php://output');
exit();
