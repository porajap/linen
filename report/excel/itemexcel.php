<?php
include ("../../PHPExcel/Classes/PHPExcel.php");
 
//ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT name,industry,phone FROM company ";
$res = $mysqli->query($query);
if (!$res) {
    die('<p><strong style="color:#FF0000">!! Invalid query:</strong> ' . $mysqli->error.'</p>');
}
 
// สร้าง object ของ Class  PHPExcel  ขึ้นมาใหม่
$objPHPExcel = new PHPExcel();
 
// กำหนดค่าต่างๆ
$objPHPExcel->getProperties()->setCreator("Company Co., Ltd.");
$objPHPExcel->getProperties()->setLastModifiedBy("Company Co., Ltd.");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX ReportQuery Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX ReportQuery Document");
$objPHPExcel->getProperties()->setDescription("ReportQuery from Company Co., Ltd.");
 
$sheet = $objPHPExcel->getActiveSheet();
$pageMargins = $sheet->getPageMargins();
 
$columnCharacter = array('A','B','C','D','E','F','G','H','I','J','K','L','M');
 
// margin is set in inches (0.5cm)
$margin = 0.5 / 2.54;
 
$pageMargins->setTop($margin);
$pageMargins->setBottom($margin);
$pageMargins->setLeft($margin);
$pageMargins->setRight(0);
 
$styleHeader = array(
		//'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'ffff00')),
		'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		'font'  => array(
		'bold'  => true,
		'size'  => 9,
		'name'  => 'Arial'
	));
 
//Set หัว Column
$rowCell=1;
$c=0; 
while ($f = $res->fetch_field()) { 
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCharacter[$c].$rowCell, $f->name);
	$c++;
}
$c = $c-1;
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$columnCharacter[$c].'1')->applyFromArray($styleHeader);
 
//เขียนข้อมูล
$rowCell=2;
$c=0; 
while($row = $res->fetch_array(MYSQLI_NUM)){ $c++;
	for($i=0; $i < $res->field_count; $i++){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCharacter[$i].$rowCell, $row[$i]);
	}
	$rowCell++;
}
//	
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('ReportQuery');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
 
//ตั้งชื่อไฟล์
$time	= date("H:i:s");
$date	= date("Y-m-d");
list($h,$i,$s) = explode(":",$time);
$file_name = "Excel_".$date."_".$h."_".$i."_".$s.")";
//
 
// Save Excel 2007 file
#echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');
// It will be called file.xls
header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
$objWriter->save('php://output');	
exit();
?>