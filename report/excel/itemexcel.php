<?php
require '../../connect/connect.php';
include("../../report_linen/excel/PHPExcel-1.8/Classes/PHPExcel.php");
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
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
require_once '../../report_linen/excel/PHPExcel-1.8/Classes/PHPExcel.php';

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

// ==========================================================
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A1',  'ItemCode')
  ->setCellValue('B1',  'HptCode')
  ->setCellValue('C1',  'CategoryCode')
  ->setCellValue('D1',  'ItemName')
  ->setCellValue('E1',  'UnitCode')
  ->setCellValue('F1',  'SizeCode')
  ->setCellValue('G1',  'Weight')
  ->setCellValue('H1',  'IsActive')
  ->setCellValue('I1',  'QtyPerUnit')
  ->setCellValue('J1',  'UnitCode2')
  ->setCellValue('K1',  'IsDirtyBag')
  ->setCellValue('L1',  'isset')
  ->setCellValue('M1',  'Tdas')
  ->setCellValue('N1',  'IsClean')
  ->setCellValue('O1',  'typeLinen')
  ->setCellValue('P1',  'numPack')



  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'B9E3E6')
    )
  );
  $objPHPExcel->getActiveSheet()->getStyle('A1' . ':P1' )->applyFromArray($colorfill);
foreach (range('A', 'P') as $columnID) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    ->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->setTitle('ITEM_MASTER');
$objPHPExcel->createSheet();




// ===========================================================
$start_row =2;
$objPHPExcel->setActiveSheetIndex(1)
  ->setCellValue('A1',  'CategoryCode')
  ->setCellValue('B1',  'CategoryName');

  $Sql = "SELECT CategoryCode , CategoryName FROM item_category WHERE IsStatus = 0";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
$objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $Result["CategoryCode"]);
$objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["CategoryName"]);
$start_row++;
}

foreach (range('A', 'B') as $columnID) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    ->setAutoSize(true);
}
$colorfill = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'B9E3E6')
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A1' . ':B1' )->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->setTitle('Category');
$objPHPExcel->createSheet();




// ==========================================================
$start_row2 =2;
$objPHPExcel->setActiveSheetIndex(2)
  ->setCellValue('A1',  'UnitCode')
  ->setCellValue('B1',  'UnitName');

  $Sql = "  SELECT item_unit.UnitCode , item_unit.UnitName FROM item_unit WHERE IsStatus = 0  ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row2, $Result["UnitCode"]);
  $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row2, $Result["UnitName"]);
  $start_row2++;
  }

  foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
  }
  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'B9E3E6')
    )
  );
$objPHPExcel->getActiveSheet()->getStyle('A1' . ':B1' )->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->setTitle('Unit');
$objPHPExcel->createSheet();
// ============================================================
$start_row3 =2;
$objPHPExcel->setActiveSheetIndex(3)
  ->setCellValue('A1',  'UnitCode')
  ->setCellValue('B1',  'UnitName');

  $Sql = "  SELECT item_size.SizeCode , item_size.SizeName FROM item_size  ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row3, $Result["SizeCode"]);
  $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row3, $Result["SizeName"]);
  $start_row3++;
  }

  foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
  }
  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'B9E3E6')
    )
  );
$objPHPExcel->getActiveSheet()->getStyle('A1' . ':B1' )->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->setTitle('Size');
$objPHPExcel->createSheet();
// ==========================================================
$objPHPExcel->setActiveSheetIndex(4)
  ->setCellValue('A1',  'TypeLinen')
  ->setCellValue('B1',  'TypeLinenName');

  $objPHPExcel->getActiveSheet()->setCellValue('A2' ,  'P');
  $objPHPExcel->getActiveSheet()->setCellValue('A3' ,  'S');
  $objPHPExcel->getActiveSheet()->setCellValue('A4' ,  'F');
  $objPHPExcel->getActiveSheet()->setCellValue('A5' ,  'T');
  $objPHPExcel->getActiveSheet()->setCellValue('A6' ,  'G');
  $objPHPExcel->getActiveSheet()->setCellValue('A7' ,  'O');

  $objPHPExcel->getActiveSheet()->setCellValue('B2' ,  'Patient Shirt');
  $objPHPExcel->getActiveSheet()->setCellValue('B3' ,  'Staff Uniform');
  $objPHPExcel->getActiveSheet()->setCellValue('B4' ,  'Flat Sheet');
  $objPHPExcel->getActiveSheet()->setCellValue('B5' ,  'Towel');
  $objPHPExcel->getActiveSheet()->setCellValue('B6' ,  'Green Linen');
  $objPHPExcel->getActiveSheet()->setCellValue('B7' ,  'Other');


  foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
  }
  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'B9E3E6')
    )
  );
$objPHPExcel->getActiveSheet()->getStyle('A1' . ':B1' )->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->setTitle('TypeLinen');
$objPHPExcel->createSheet();
// ==========================================================
$objPHPExcel->setActiveSheetIndex(5)
  ->setCellValue('A1',  'numPack')
  ->setCellValue('B1',  'numPackName');

  $objPHPExcel->getActiveSheet()->setCellValue('A2' ,  '1');
  $objPHPExcel->getActiveSheet()->setCellValue('A3' ,  '5');
  $objPHPExcel->getActiveSheet()->setCellValue('A4' ,  '10');
  $objPHPExcel->getActiveSheet()->setCellValue('A5' ,  '15');
  $objPHPExcel->getActiveSheet()->setCellValue('A6' ,  '20');
  $objPHPExcel->getActiveSheet()->setCellValue('A7' ,  '0');

  $objPHPExcel->getActiveSheet()->setCellValue('B2' ,  '1 PCS');
  $objPHPExcel->getActiveSheet()->setCellValue('B3' ,  '5 Pc');
  $objPHPExcel->getActiveSheet()->setCellValue('B4' ,  '10 Pc');
  $objPHPExcel->getActiveSheet()->setCellValue('B5' ,  '15 Pc');
  $objPHPExcel->getActiveSheet()->setCellValue('B6' ,  '20 Pc');
  $objPHPExcel->getActiveSheet()->setCellValue('B7' ,  'None');


  foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
  }
  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'B9E3E6')
    )
  );
$objPHPExcel->getActiveSheet()->getStyle('A1' . ':B1' )->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->setTitle('numPack');
$objPHPExcel->createSheet();
// ==========================================================















$objPHPExcel->removeSheetByIndex(
  $objPHPExcel->getIndex(
    $objPHPExcel->getSheetByName('Worksheet')
  )
);

$objPHPExcel->setActiveSheetIndex(0);
$worksheet = $objPHPExcel->getActiveSheet();
//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "ITEM_MASTER_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
