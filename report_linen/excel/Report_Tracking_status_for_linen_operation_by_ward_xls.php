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
$DepCode = [];
$chk = $data[8];
$date_query1 = $data[2];
$date_query2 = $data[3];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$start_row = 7;
$old_code = '';
if ($language == 'th') {

  $Perfix = THPerfix;
  $Name = THName;
  $LName = THLName;
} else {

  $Perfix = EngPerfix;
  $Name = EngName;
  $LName = EngLName;
}
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
  $where =   "WHERE date(shelfcount.Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  $datetime = new DatetimeTH();
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

$header = array(
  $array2['docno'][$language], $array2['Cycle'][$language],
  $array2['shelfcount'][$language], $array2['packing_time'][$language], $array2['delivery_time'][$language],
  $array2['total'][$language], $array2['user'][$language], $array2['receivecycle'][$language]
);



// Write data from MySQL result\\
$Sql = "SELECT
department.DepCode
FROM
department
WHERE department.HptCode  = '$HptCode'
 ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DepCode[] = $Result['DepCode'];
}
$Count_Dep = sizeof($DepCode);

$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$objPHPExcel->getActiveSheet()->setCellValue('J1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r18'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('A5:N5');
$objPHPExcel->getActiveSheet()->setCellValue('N7', $date_header);
for ($i = 0; $i <= $Count_Dep; $i++) {
  $query = "SELECT
department.DepCode,
department.DepName,
shelfcount.docno,
time_sc.TimeName AS CycleTime,
COALESCE(TIME(shelfcount.ScStartTime),'-') AS ScStartTime ,
COALESCE(TIME(shelfcount.ScEndTime),'-') AS ScEndTime ,  
COALESCE(TIME(shelfcount.PkEndTime),'-') AS PkEndTime ,
COALESCE(TIME(shelfcount.PkStartTime),'-') AS PkStartTime ,
COALESCE(TIME(shelfcount.DvStartTime),'-') AS DvStartTime ,
COALESCE(TIME(shelfcount.DvEndTime),'-') AS DvEndTime ,
TIMEDIFF(shelfcount.ScStartTime,shelfcount.ScEndTime)AS SC ,
TIMEDIFF(shelfcount.PkStartTime,shelfcount.PkEndTime)AS PK ,
TIMEDIFF(shelfcount.DvStartTime,shelfcount.DvEndTime)AS DV,
CONCAT($Perfix,' ' , $Name,' ' ,$LName)  as USER,
sc_time_2.TimeName 
FROM
shelfcount
INNER JOIN department on department.DepCode = shelfcount.DepCode
INNER JOIN users ON users.ID = shelfcount.Modify_Code
LEFT JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
LEFT JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
$where
AND  department.DepCode = '$DepCode[$i]'
AND shelfcount.isStatus <> 9 ";

  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    $code =  $Result['DepCode'];
    $name =  $Result['DepName'];
    $start_row++;
    $start_row1 = $start_row + 1;
    if ($Result['docno'] <> null) {
      if ($code <> $old_code) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['department'][$language] . " : " . $name);
        $start_row++;
        $objPHPExcel->setActiveSheetIndex()
          ->setCellValue('A' . $start_row,  $array2['docno'][$language])
          ->setCellValue('B' . $start_row,  $array2['receivecycle'][$language])
          ->setCellValue('C' . $start_row,  $array2['Cycle'][$language])
          ->setCellValue('D' . $start_row,  $array2['shelfcount'][$language])
          ->setCellValue('G' . $start_row,  $array2['packing_time'][$language])
          ->setCellValue('J' . $start_row,  $array2['delivery_time'][$language])
          ->setCellValue('M' . $start_row,  $array2['total'][$language])
          ->setCellValue('N' . $start_row,  $array2['user'][$language]);
        $start_row++;
        $objPHPExcel->setActiveSheetIndex()
          ->setCellValue('D' . $start_row,  $array2['start'][$language])
          ->setCellValue('E' . $start_row,  $array2['finish'][$language])
          ->setCellValue('F' . $start_row,  $array2['total'][$language])
          ->setCellValue('G' . $start_row,  $array2['start'][$language])
          ->setCellValue('H' . $start_row,  $array2['finish'][$language])
          ->setCellValue('I' . $start_row,  $array2['total'][$language])
          ->setCellValue('J' . $start_row,  $array2['start'][$language])
          ->setCellValue('K' . $start_row,  $array2['finish'][$language])
          ->setCellValue('L' . $start_row,  $array2['total'][$language]);
        $start_row++;
        $old_code = $code;
      }
      if ($Result['CycleTime'] == 'Extra') {
        $sc1 = substr($Result['ScStartTime'], 0, 5);
        $sc2 = substr($Result['ScStartTime'], 0, 5);
      } else {
        $sc1 = substr($Result['ScStartTime'], 0, 5);
        $sc2 = substr($Result['ScEndTime'], 0, 5);
      }
      list($hoursSS, $minSS, $secordSS) = explode(":",  $sc1);
      list($hoursSF, $minSF, $secordSF) = explode(":",  $sc2);
      list($hoursPS, $minPS, $secordPS) = explode(":", $Result['PkStartTime']);
      list($hoursPF, $minPF, $secordPF) = explode(":", $Result['PkEndTime']);
      list($hoursDS, $minDS, $secordDS) = explode(":", $Result['DvStartTime']);
      list($hoursDF, $minDF, $secordDF) = explode(":", $Result['DvEndTime']);
      $h1 = $hoursSS - $hoursSF;
      $m1 = $minSS - $minSF;
      if ($Result['CycleTime'] == 'Extra') {
        $h2 = $hoursPS - $hoursPF;
        $m2 = $minPS - $minPF;
      }
      $h3 = $hoursDS - $hoursDF;
      $m3 = $minDS - $minDF;
      $m1 = abs($m1);
      $m2 = abs($m2);
      $m3 = abs($m3);
      $h1 = abs($h1);
      $h2 = abs($h2);
      $totalhour = $h1 + $h2 + $h3;
      $totalmin = $m1 + $m2 + $m3;
      if ($totalmin >= 60) {
        $totalhouradd = ($totalmin / 60);
        $totalhour += $totalhouradd;
        $totalmin = $totalmin % (60 * $totalhouradd);
      }

      for ($i = 0; $i < 10; $i++) {
        if ($m1 == $i) {
          $m1 = "0" . $m1;
        }
        if ($h1 == $i) {
          $h1 =  "0" . $h1;
        }
        if ($m2 == $i) {
          $m2 = "0" . $m2;
        }
        if ($h2 == $i) {
          $h2 = "0" . $h2;
        }
        if ($m3 == $i) {
          $m3 = "0" . $m3;
        }
        if ($h3 == $i) {
          $h3 = "0" . $h3;
        }
      }
      if ($language == 'th') {
        $hour_show = " ชั่วโมง";
        $min_show = " นาที";
      } else {
        if ($totalhour <= 1) {
          $hour_show = " hour ";
        } else {
          $hour_show = " hours ";
        }
        if ($totalmin <= 1) {
          $min_show = " min ";
        } else {
          $min_show = " mins ";
        }
      }

      $total1 = $h1 . ":" . $m1;
      $total2 = $h2 . ":" . $m2;
      $total3 = $h3 . ":" . $m3;
      if ($Result['CycleTime'] == 'Extra') {
        $pack1 = substr($Result['PkStartTime'], 0, 5);
        $pack2 = substr($Result['PkEndTime'], 0, 5);
        $total2 = $h2 . ":" . $m2;
      } else {
        $pack1 = '-';
        $pack2 = '-';
        $total2 = '-';
      }
      $objPHPExcel->getActiveSheet()->setCellValue('A'. $start_row,  $Result['docno']);
      $objPHPExcel->getActiveSheet()->setCellValue('B'. $start_row,  $Result['TimeName']);
      $objPHPExcel->getActiveSheet()->setCellValue('C'. $start_row,  $Result['CycleTime']);
      $objPHPExcel->getActiveSheet()->setCellValue('D'. $start_row,  $sc1);
      $objPHPExcel->getActiveSheet()->setCellValue('E'. $start_row,  $sc2);
      $objPHPExcel->getActiveSheet()->setCellValue('F'. $start_row,  $total1);
      $objPHPExcel->getActiveSheet()->setCellValue('G'. $start_row,  $pack1);
      $objPHPExcel->getActiveSheet()->setCellValue('H'. $start_row,  $pack2);
      $objPHPExcel->getActiveSheet()->setCellValue('I'. $start_row,  $total2);
      $objPHPExcel->getActiveSheet()->setCellValue('J'. $start_row,  substr($Result['DvStartTime'], 0, 5));
      $objPHPExcel->getActiveSheet()->setCellValue('K'. $start_row,  substr($Result['DvEndTime'], 0, 5));
      $objPHPExcel->getActiveSheet()->setCellValue('L'. $start_row,  $total3);
      $objPHPExcel->getActiveSheet()->setCellValue('M'. $start_row,  number_format($totalhour) . $hour_show . " " . $totalmin .  $min_show);
      $objPHPExcel->getActiveSheet()->setCellValue('N'. $start_row, $Result['USER']);
      $start_row++;
    }
    $start_row++;
  }
}

$styleArray = array(

  'borders' => array(

    'allborders' => array(

      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A8:N' . $start_row)->applyFromArray($styleArray);


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
$objPHPExcel->getActiveSheet()->getStyle('A8:N' . $start_row)->applyFromArray($CENTER);


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
// $cols = array('A', 'B', 'C', 'D', 'E','F','G','H','I','J');
// $width = array(20, 8, 8, 8, 8,8,8,8,8,20);
// for ($j = 0; $j < count($cols); $j++) {
//   $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
// }

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
$objPHPExcel->getActiveSheet()->setTitle('Report_Dirty');

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
