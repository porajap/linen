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
$data = $_SESSION['data_send'];
$HptCode = $data['HptCode'];
$FacCode = $data['FacCode'];
$date1 = $data['date1'];
$date2 = $data['date2'];
$chk = $data['chk'];
$year = $data['year'];
$DepCode = $data['DepCode'];
$format = $data['Format'];
$where = '';
$i = 9;
$check = '';
$row_question = 9 ;
$Qty = 0;
$Weight = 0;
$count = 1;
$date = 1;
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}

if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (dirty.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE DATE(dirty.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
$objPHPExcel->getActiveSheet()
  ->setShowGridlines(true);

$date_cell = array('D','E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH');
$count_date = sizeof($date_cell);

// Write data from MySQL result
// $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'แบบประเมินคุณภาพและขั้นตอนการปฎิบัติงาน');
$objPHPExcel->getActiveSheet()->setCellValue('I1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A2',  'no.')
  ->setCellValue('B2',  'ชนิดผ้าที่สุ่ม')
  ->setCellValue('C2',  'วิธีการตรวจสอบ');
$now = '2019-11-';
for ($r = 0; $r < 30; $r++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '6',  $date);
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '7',  'YES');
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '8',  '1');
  $query = "SELECT
  coalesce(kpi_clean2_detail.IsCheck,'-') AS  IsCheck
  FROM
  kpi_clean2_detail
  INNER JOIN kpi_clean2 ON kpi_clean2_detail.DocNo = kpi_clean2.DocNo
  WHERE date(kpi_clean2.DocDate) = '$now$date' AND kpi_clean2.IsStatus = 1
  ORDER BY kpi_clean2_detail.ID";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] .$row_question,  $Result["IsCheck"]); 
    $Check_Sum +=  $Result["IsCheck"];
   $row_question++;
   $Qty++;
}
  $date++;
  if($Qty > 0){
  $row1 = $row_question;
  $row2 = $row_question+1;
  $percent = $Check_Sum/$Qty*100;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] .$row1, $Check_Sum); 
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] .$row2, $percent. ' %'); 
  $sum_percent += $percent;
  }
  $row_question = 9;
  $row1 = 0;
  $row2 = 0;
  $percent = 0;
  $Check_Sum = 0;
  $Qty=0;
}

$query = "SELECT
kpi_clean2_question.Question,
kpi_clean2_question.Standard
FROM
kpi_clean2_question
order by kpi_clean2_question.ID
";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $count);
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $Result["Standard"]);
  $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $Result["Question"]);
  $i++;
  $count++;
}

$r1 = $i;
$r2 = $i+1;
$r3 = $i+2;
$Total_PerCent=$sum_percent / $count_date;
$objPHPExcel->getActiveSheet()->setCellValue('D' . $r1, 'สรุปข้อที่ทำได้ตามมาตรฐาน');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $r2, 'คิดเป็นร้อยละต่อครั้ง');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $r3, 'คิดเป็นร้อยละต่อเดือน');
$objPHPExcel->getActiveSheet()->mergeCells('E'.$r3.':AI'.$r3);

$objPHPExcel->getActiveSheet()->setCellValue('E' . $r3, $Total_PerCent);
$cols = array('A', 'B', 'C', 'D', 'E');
$width = array(10, 40, 10, 10, 15);
for ($j = 0; $j < count($cols); $j++) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
}
$A1H1 = array(
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
$A2H2 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 11,
    'name'  => 'THSarabun'
  )
);
$A3 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 11,
    'name'  => 'THSarabun'
  )
);
$E1 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 13,
    'name'  => 'THSarabun'
  )
);
$A6AI8 = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment:: VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'size'  => 11,
    'name'  => 'THSarabun'
  )
);
$styleArray = array(
  'borders' => array(
      'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
      )
  )
);
$CENTER = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment:: VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'size'  => 13,
    'name'  => 'THSarabun'
  )
);


// $objPHPExcel->getActiveSheet()->getStyle("A9:A" . $i)->applyFromArray($fill1)->getNumberFormat();
// $objPHPExcel->getActiveSheet()->getStyle("B9:B" . $i)->applyFromArray($fill2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
// $objPHPExcel->getActiveSheet()->getStyle("C9:C" . $i)->applyFromArray($fill3)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
// $objPHPExcel->getActiveSheet()->getStyle("D9:D" . $i)->applyFromArray($Weight)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
// $objPHPExcel->getActiveSheet()->getStyle("E9:E" . $i)->applyFromArray($Weight)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
// $objPHPExcel->getActiveSheet()->getStyle("D" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
// $objPHPExcel->getActiveSheet()->getStyle("A" . $row_sum . ":B" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

// $i--;
// $objPHPExcel->getActiveSheet()->getStyle("A5:E5")->applyFromArray($r3)->getNumberFormat();
// $objPHPExcel->getActiveSheet()->getStyle("A6:E6")->applyFromArray($Hos)->getNumberFormat();
// $objPHPExcel->getActiveSheet()->getStyle("E1")->applyFromArray($date)->getNumberFormat();
// $objPHPExcel->getActiveSheet()->getStyle("E7")->applyFromArray($date)->getNumberFormat();
// $objPHPExcel->getActiveSheet()->getStyle('A8:E' . $i)->applyFromArray($styleArray);
// $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);
// $objDrawing = new PHPExcel_Worksheet_Drawing();
// $objDrawing->setName('test_img');
// $objDrawing->setDescription('test_img');
// $objDrawing->setPath('Nhealth_linen 4.0.png');
// $objDrawing->setCoordinates('A1');
//setOffsetX works properly
// $objDrawing->setOffsetX(0);
// $objDrawing->setOffsetY(0);
// //set width, height
// $objDrawing->setWidthAndHeight(150, 75);
// $objDrawing->setResizeProportional(true);
// $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objPHPExcel->getActiveSheet()->getStyle('A1:A6')
    ->getAlignment()->setWrapText(true); 
    $objPHPExcel->getActiveSheet()->getColumnDimension("A")
    ->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension("B")
    ->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension("C")
    ->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension("D")
    ->setAutoSize(true);
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
