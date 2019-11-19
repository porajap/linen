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
$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
$DepCode = $data[7];
$chk = $data[8];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}

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
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " . $year2;
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
  $where =   "WHERE DATE(repair_wash.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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


// Setting the print area:
// Add some data


$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A8',  $array2['no'][$language])
  ->setCellValue('B8',  $array2['itemname'][$language])
  ->setCellValue('C8',  $array2['amount1'][$language])
  ->setCellValue('D8',  $array2['weight'][$language].' (kg)');

// Write data from MySQL result
$Sql = "SELECT
factory.$FacName,
site.$HptName,
DATE(repair_wash.DocDate) AS DocDate,
TIME(repair_wash.DocDate) AS DocTime
FROM
repair_wash
INNER JOIN factory ON repair_wash.FacCode = factory.FacCode
INNER JOIN department ON department.depcode = repair_wash.depcode
INNER JOIN site ON site.HptCode = repair_wash.HptCode
$where
AND repair_wash.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND repair_wash.isStatus<> 9
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $hptname = $Result[$HptName];
  $facname = $Result[$FacName];
}
$objPHPExcel->getActiveSheet()->setCellValue('D1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r3'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('A5:D5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:D6');
$objPHPExcel->getActiveSheet()->setCellValue('A6', $array['hosname'][$language] . " : " . $hptname);
$objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['factory'][$language] . " : " . $facname);
$objPHPExcel->getActiveSheet()->setCellValue('D7', $date_header);
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
GROUP BY  item.ItemName
ORDER BY repair_wash.DocNo ASC 
";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $count);
  $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $Result["ItemName"]);
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $Result["Qty"]);
  $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $Result["Weight"]);
  $i++;
  $count++;
  $Qty += $Result["Qty"];
  $Weight += $Result["Weight"];
}
$objPHPExcel->getActiveSheet()->mergeCells('A' . $i.':B'.$i);
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $array2['total'][$language]);

$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $Qty );
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $Weight );
$row_sum = $i;
$i += 1;
$count++;

$cols = array('A', 'B', 'C', 'D');
$width = array(10, 40, 20, 20);
for ($j = 0; $j < count($cols); $j++) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
}
$header = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$fill1 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  ),
  'borders' => array(
    'borders' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      'color' => array('rgb' => '000000')
    )
  )
);
$fill2 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$fill3 = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$Weight = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$sum = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
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
$r3 = array(
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
$date = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$Hos = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => FALSE,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 14,
    'name'  => 'THSarabun'
  )
);


$objPHPExcel->getActiveSheet()->getStyle("A8:D8")->applyFromArray($header)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);;
$objPHPExcel->getActiveSheet()->getStyle("A9:A" . $i)->applyFromArray($fill1)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle("B9:B" . $i)->applyFromArray($fill2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
$objPHPExcel->getActiveSheet()->getStyle("C9:C" . $i)->applyFromArray($fill3)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("D9:D" . $i)->applyFromArray($Weight)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("E9:E" . $i)->applyFromArray($Weight)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("D" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("A" . $row_sum . ":B" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$i--;
$objPHPExcel->getActiveSheet()->getStyle("A5:E5")->applyFromArray($r3)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle("A6:E6")->applyFromArray($Hos)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle("E1")->applyFromArray($date)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle("E7")->applyFromArray($date)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle('A8:D' . $i)->applyFromArray($styleArray);
// $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);
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
$objPHPExcel->getActiveSheet()->setTitle('Report_repair_wash');

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
