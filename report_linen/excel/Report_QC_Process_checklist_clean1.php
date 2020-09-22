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
$DepCode = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$where = '';
$i = 9;
$check = '';
$row_question = 9;
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

$date_cell = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI');
$count_date = sizeof($date_cell);

// Write data from MySQL result
// $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
$objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
$objPHPExcel->getActiveSheet()->mergeCells('C4:D4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
$objPHPExcel->getActiveSheet()->mergeCells('C5:D5');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'แบบประเมินคุณภาพและขั้นตอนการปฎิบัติงาน');
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Scoring');
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Yes');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 'The answer is "YES" or "Always" to the specific requirements of the questionaire ( ตอบได้ครบถ้วน ครอบคลุม เนื้อหาทั้งหมด ตอบได้ทุกคนที่ถาม)  ');
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No');
$objPHPExcel->getActiveSheet()->setCellValue('C5', 'The answer is "Rarely" or "Never" to the specific requirements of the questionaire ( ตอบได้น้อยกว่า 50%  หรือตอบไม่ได้เลย) ');
$objPHPExcel->getActiveSheet()->mergeCells('A6:A8');
$objPHPExcel->getActiveSheet()->mergeCells('B6:B8');
$objPHPExcel->getActiveSheet()->mergeCells('C6:C8');
$objPHPExcel->getActiveSheet()->mergeCells('D6:D8');
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A6',  'JCI Standard')
  ->setCellValue('B6',  'No.')
  ->setCellValue('C6',  'Standard')
  ->setCellValue('D6',  'Question');

$query = "SELECT
kpi_clean1_question.Question,
kpi_clean1_question.Standard
FROM
kpi_clean1_question
order by kpi_clean1_question.ID
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
$r2 = $i + 1;
$r3 = $i + 2;
$now =  $year1 . '-' . $date1 . '-';
$count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
for ($r = 0; $r < $count; $r++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '6',  $date);
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '7',  'YES');
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '8',  '1');
  $query = "SELECT
  coalesce(kpi_clean1_detail.IsCheck,'-') AS  IsCheck
  FROM
  kpi_clean1_detail
  INNER JOIN kpi_clean1 ON kpi_clean1_detail.DocNo = kpi_clean1.DocNo
  WHERE date(kpi_clean1.DocDate) = '$now$date' AND kpi_clean1.IsStatus = 1 	AND kpi_clean1.HptCode = '$HptCode'
  ORDER BY kpi_clean1_detail.ID";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . $row_question,  $Result["IsCheck"]);
    $Check_Sum +=  $Result["IsCheck"];
    $row_question++;
    $Qty++;
  }
  $date++;
  if ($Qty > 0) {
    $row1 = $row_question;
    $row2 = $row_question + 1;
    $percent = $Check_Sum / $Qty * 100;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . $row1, $Check_Sum);
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . $row2, $percent . ' %');
    $sum_percent += $percent;
  } else {
    $row1 = $row_question;
    $row2 = $row_question + 1;
    $percent = '0';
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . $r1, '0');
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . $r2, $percent . ' %');
  }
  $row_question = 9;
  $row1 = 0;
  $row2 = 0;
  $percent = 0;
  $Check_Sum = 0;
  $Qty = 0;
}
$Total_PerCent = $sum_percent / $count_date;
$lastdate = $count - 1;
$objPHPExcel->getActiveSheet()->setCellValue('D' . $r1, 'สรุปข้อที่ทำได้ตามมาตรฐาน');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $r2, 'คิดเป็นร้อยละต่อครั้ง');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $r3, 'คิดเป็นร้อยละต่อเดือน');
$objPHPExcel->getActiveSheet()->mergeCells('E' . $r3 . ':' . $date_cell[$lastdate] . $r3);
$objPHPExcel->getActiveSheet()->setCellValue('E' . $r3, $Total_PerCent);
$cols = array('A', 'B', 'C', 'D');
$width = array(10, 40, 10, 10);
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
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'size'  => 13,
    'name'  => 'THSarabun'
  )
);
$colorfill = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'B9E3E6')
  )
);
$objPHPExcel->getActiveSheet()->getStyle("B9:C" . $i)->applyFromArray($CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E" . $r1 . ":".$date_cell[$lastdate] . $r3)->applyFromArray($CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A6:".$date_cell[$lastdate]."21")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("D" . $r1 . ":".$date_cell[$lastdate] . $r3)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A6:".$date_cell[$lastdate]."8")->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->applyFromArray($A1H1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("A2:H2")->applyFromArray($A2H2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("E1")->applyFromArray($E1);
$objPHPExcel->getActiveSheet()->getStyle("A6:".$date_cell[$lastdate]."8")->applyFromArray($A6AI8);
$objPHPExcel->getActiveSheet()->getStyle("B6:D6")->applyFromArray($A6AI8);
$objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($A3)->getFont()->setUnderline(true);
$objPHPExcel->getActiveSheet()->getStyle("E21")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
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
$objPHPExcel->getActiveSheet()->setTitle('Report_QC_Process_checklist_clean1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_QC_Process_checklist_clean1" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
