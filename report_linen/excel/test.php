<?php
include("PHPExcel-1.8/Classes/PHPExcel.php");
require('../report/connect.php');
require('../report/Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
$objPHPExcel = new PHPExcel();


for($i=0;$i<10;$i++){
  // Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex($i);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Something');

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Name of Sheet '.$i);

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();
}


// // Add some data to the second sheet, resembling some different data types
// $objPHPExcel->setActiveSheetIndex(1);
// $objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');

// // Rename 2nd sheet
// $objPHPExcel->getActiveSheet()->setTitle('Second sheet');

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="name_of_file.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');