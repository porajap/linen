<?php
include("PHPExcel-1.8/Classes/PHPExcel.php");
require('../report/connect.php');
require('../report/Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
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
$date_query1 = $data[2];
$date_query2 = $data[3];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$start_data = 9 ;
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}
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


// Setting the print area:
// Add some data

$header = array($array2['docdate'][$language], $array2['receive_time'][$language], $array2['washing_time'][$language], $array2['packing_time'][$language], $array2['distribute_time'][$language], $array2['total'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
$objPHPExcel->getActiveSheet()->mergeCells('D8:E8');
$objPHPExcel->getActiveSheet()->mergeCells('F8:G8');
$objPHPExcel->getActiveSheet()->mergeCells('H8:I8');
$objPHPExcel->getActiveSheet()->mergeCells('A8:A9');
$objPHPExcel->getActiveSheet()->mergeCells('J8:J9');
$objPHPExcel->setActiveSheetIndex()
  ->setCellValue('A8',  $array2['docdate'][$language])
  ->setCellValue('B8',  $array2['receive_time'][$language])
  ->setCellValue('D8',  $array2['washing_time'][$language])
  ->setCellValue('F8',  $array2['packing_time'][$language])
  ->setCellValue('H8',  $array2['distribute_time'][$language])
  ->setCellValue('J8',  $array2['total'][$language]);
  $objPHPExcel->setActiveSheetIndex()
  ->setCellValue('B9',  $array2['start'][$language])
  ->setCellValue('C9',  $array2['finish'][$language])
  ->setCellValue('D9',  $array2['start'][$language])
  ->setCellValue('E9',  $array2['finish'][$language])
  ->setCellValue('F9',  $array2['start'][$language])
  ->setCellValue('G9',  $array2['finish'][$language])
  ->setCellValue('H9',  $array2['start'][$language])
  ->setCellValue('I9',  $array2['finish'][$language]);
// Write data from MySQL result
$Sql = "SELECT
factory.$FacName
FROM
process
INNER JOIN factory ON factory.FacCode = process.FacCode
where process.FacCode = $FacCode
       ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $Facname = $Result[$FacName];
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$objPHPExcel->getActiveSheet()->setCellValue('J1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r15'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
$objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['factory'][$language] . " : " . $Facname);
$objPHPExcel->getActiveSheet()->setCellValue('J7', $date_header);
$doc = array(dirty, repair_wash, newlinentable);
$j = 0;
for ($i = 0; $i < 3; $i++) {
  if ($chk == 'one') {
    if ($format == 1) {
      $where =   "WHERE DATE (" . $doc[$i] . ".Docdate) = DATE('$date_query1')";
    } elseif ($format = 3) {
      $where = "WHERE  year (" . $doc[$i] . ".Docdate) LIKE '%$date_query1%'";
    }
  } elseif ($chk == 'between') {
    $where =   "WHERE " . $doc[$i] . ".Docdate BETWEEN '$date_query1' AND '$date_query2'";
  } elseif ($chk == 'month') {
    $where =   "WHERE month (" . $doc[$i] . ".Docdate) = " . $date_query1;
  } elseif ($chk == 'monthbetween') {
    $where =   "WHERE date(" . $doc[$i] . ".Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  }
  $query = "SELECT
TIME (process.WashStartTime) AS WashStartTime ,
TIME (process.WashEndTime) AS WashEndTime,
TIME (process.PackStartTime)AS PackStartTime,
TIME (process.PackEndTime)AS PackEndTime,
TIME (process.SendStartTime)AS SendStartTime,
TIME (process.SendEndTime)AS SendEndTime,
$doc[$i].FacCode,
process.DocNo AS  DocNo1 ,
TIME ($doc[$i].ReceiveDate)AS ReceiveDate1
FROM
process
LEFT JOIN $doc[$i] ON process.DocNo = $doc[$i].DocNo
$where AND $FacCode in ($doc[$i].FacCode)
AND process.isStatus <> 9
";
echo $query ; 
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $start_data++;
    if ($language == 'th') {
      $hour_show = " ชั่วโมง";
      $min_show = " นาที";
    } else {
      if ($total_hours <= 1) {
        $hour_show = " hour ";
        $min_show = " min ";
      } else {
        $hour_show = " hours ";
        $min_show = " mins ";
      }
    }
    list($hours, $min, $secord) = explode(":", $Result["ReceiveDate1"]);
    list($hours2, $min2, $secord2) = explode(":", $Result["SendEndTime"]);
    $total_hours = $hours -  $hours2;
    $total_min = $min - $min2;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$start_data, $Result["DocNo1"]);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$start_data,substr($Result["ReceiveDate1"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$start_data,substr($Result["WashStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$start_data,substr($Result["WashStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$start_data,substr($Result["WashEndTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$start_data,substr($Result["PackStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$start_data,substr($Result["PackEndTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$start_data,substr($Result["SendStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$start_data,substr($Result["SendEndTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$start_data,abs($total_hours) . $hour_show . " " . abs($total_min) . $min_show);

  }
}

$styleArray = array(

  'borders' => array(

    'allborders' => array(

      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A8:j' . $start_data)->applyFromArray($styleArray);


$CENTER = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'size'  => 8,
    'name'  => 'THSarabun'
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A8:j' . $start_data)->applyFromArray($CENTER);


$r = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 16,
    'name'  => 'THSarabun'
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($r);
$cols = array('A', 'B', 'C', 'D', 'E','F','G','H','I','J');
$width = array(20, 8, 8, 8, 8,8,8,8,8,20);
for ($j = 0; $j < count($cols); $j++) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
}

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('test_img');
$objDrawing->setDescription('test_img');
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
$objPHPExcel->getActiveSheet()->setTitle('Report_Tracking_status_for_laundry_plant');

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
