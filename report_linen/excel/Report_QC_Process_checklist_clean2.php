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
$year1 = $data[9];
$year2 = $data[10];
$where = '';
$i = 4;
$check = '';
$row_question = 4 ;
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
$objPHPExcel->getActiveSheet()->mergeCells('A2:A3');
$objPHPExcel->getActiveSheet()->mergeCells('B2:B3');
$objPHPExcel->getActiveSheet()->mergeCells('C2:C3');
$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'แบบประเมินคุณภาพและขั้นตอนการปฎิบัติงาน');
$objPHPExcel->getActiveSheet()->setCellValue('I1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A2',  'No')
  ->setCellValue('B2',  'ชนิดผ้าที่สุ่ม')
  ->setCellValue('C2',  'วิธีการตรวจสอบ');



  $query = "SELECT
  kpi_clean2_question.Question,
  kpi_clean2_question.Type
  FROM
  kpi_clean2_question
  order by kpi_clean2_question.ID
  ";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $count);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $Result["Type"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $Result["Question"]);
    $i++;
    $count++;
  }
  
  $r1 = $i;
  $r2 = $i+1;
  $r3 = $i+2;

  $now =  $year1.'-'.$date1.'-';
for ($r = 0; $r < 30; $r++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '2',  $date);
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] . '3',  '1');
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
  }else{
    $row1 = $row_question;
    $row2 = $row_question+1;
    $percent = '0';
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] .$r1, '0'); 
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell[$r] .$r2, $percent. ' %'); 
  }
  $row_question = 4;
  $row1 = 0;
  $row2 = 0;
  $percent = 0;
  $Check_Sum = 0;
  $Qty=0;
}

  
  $Total_PerCent=$sum_percent / $count_date;
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $r1, 'สรุปข้อที่ทำได้ตามมาตรฐาน');
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $r2, 'คิดเป็นร้อยละต่อครั้ง');
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $r3, 'คิดเป็นร้อยละต่อเดือน');
  $objPHPExcel->getActiveSheet()->mergeCells('D'.$r3.':AH'.$r3);
  $objPHPExcel->getActiveSheet()->setCellValue('D' . $r3, $Total_PerCent.'%');










$cols = array('A', 'B', 'C', 'D');
$width = array(20, 40, 10, 10);
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
$A2AH2 = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment:: VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'size'  => 11,
    'name'  => 'THSarabun'
  )
);
$A2HEAD = array(
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
$colorfill = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'B9E3E6')
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
$count=$count+2;
$i=$i-1;
 $objPHPExcel->getActiveSheet()->getStyle('A2:AG' . $i)->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->getStyle('C'.$r1.':AG'.$r3)->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->getStyle('A1:AH3')->applyFromArray($A2AH2);
 $objPHPExcel->getActiveSheet()->getStyle('D'.$r3)->applyFromArray($A2AH2);
 $objPHPExcel->getActiveSheet()->getStyle('A2:A'.$count)->applyFromArray($A2AH2);
 $objPHPExcel->getActiveSheet()->getStyle('D'.$row_question.':AG'.$r2)->applyFromArray($A2AH2);
 $objPHPExcel->getActiveSheet()->getStyle('A2:AG3')->applyFromArray($colorfill);
 $objPHPExcel->getActiveSheet()->getStyle('C'.$r1.':AG'.$r3)->applyFromArray($colorfill);
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
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Report_Dirty');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_QC_Process_checklist_clean2" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
