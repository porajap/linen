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
$docno = $_GET['Docno'];
$where = '';
$start_row = 14;
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
// Write data from MySQL result

$Sql = "SELECT
shelfcount.DocNo,
DATE(shelfcount.DocDate) AS DocDate,
TIME(shelfcount.DocDate) AS DocTime,
department.DepName,
time_sc.TimeName AS CycleTime,
site.HptName,
sc_time_2.TimeName AS TIME , 
time_sc.timename AS ENDTIME ,
site.HptCode
FROM
shelfcount
LEFT JOIN department ON shelfcount.DepCode = department.DepCode
LEFT JOIN site ON site.HptCode = department.HptCode
LEFT JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
LEFT JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
WHERE shelfcount.DocNo='$docno' AND shelfcount.isStatus<> 9
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DeptName = $Result['DepName'];
  $DocDate = $Result['DocDate'];
  $DocTime = $Result['DocTime'];
  $DocNo = $Result['DocNo'];
  $TIME = $Result['TIME'];
  $ENDTIME = $Result['ENDTIME'];
  $HptName = $Result['HptName'];
  $HptCode = $Result['HptCode'];
}
list($year, $month, $day) = explode('-', $DocDate);
if ($language == 'th') {
  $year = $year + 543;
}
$DocDate = $day . "-" . $month . "-" . $year;
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$queryy = "SELECT
site.private,
site.government
FROM
site
WHERE site.HptCode = '$HptCode' ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $private = $Result['private'];
  $government = $Result['government'];
}

if ($private == 1) {
  $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r4'][$language]);
  $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $array['docno'][$language] . " : " . $docno);
  $objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['hospital'][$language] . " : " . $HptName);
  $objPHPExcel->getActiveSheet()->setCellValue('A8', $array2['ward'][$language] . " : " . $DeptName);
  $objPHPExcel->getActiveSheet()->setCellValue('A9', $array2['date'][$language] . " : " . $DocDate);
  $objPHPExcel->getActiveSheet()->setCellValue('A10', $array2['shelfcounttime'][$language] . " : " . $TIME);
  $objPHPExcel->getActiveSheet()->setCellValue('A11', $array2['deliverytime'][$language] . " : " . $ENDTIME);
  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A13',  $array2['no'][$language])
    ->setCellValue('B13',  $array2['itemname'][$language])
    ->setCellValue('C13',  $array2['parqty'][$language])
    ->setCellValue('D13',  $array2['shelfcount1'][$language])
    ->setCellValue('E13',  $array2['max'][$language])
    ->setCellValue('F13',  $array2['issue'][$language])
    ->setCellValue('G13',  $array2['weight'][$language])
    ->setCellValue('H13',  $array2['price'][$language]);

  $query = "SELECT
  item.ItemName,
  item.weight,
  IFNULL(shelfcount_detail.ParQty, 0) AS ParQty,
  IFNULL(shelfcount_detail.CcQty, 0) AS CcQty,
  IFNULL(
    shelfcount_detail.TotalQty,
    0
  ) AS TotalQty,
  IFNULL(shelfcount_detail.Over, 0) AS OverPar,
  IFNULL(shelfcount_detail.Short, 0) AS Short,
  IFNULL(item.Weight, 0) AS Weight,
  category_price.Price,
  shelfcount_detail.Price as PriceSC
  FROM
  shelfcount
  INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
  INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
  INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
            WHERE shelfcount.DocNo='$docno'
            AND shelfcount_detail.TotalQty <> 0
              AND shelfcount.isStatus<> 9
  ";
  $issue = $Result['ParQty'] - $Result['CcQty'];
  $totalweight = $Result['TotalQty'] * $Result['Weight'];
  $price = $totalweight * $Result['Price'];

  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $issue = $Result['ParQty'] - $Result['CcQty'];
    $totalweight = $Result['TotalQty'] * $Result['Weight'];
    $price = $totalweight * $Result['Price'];
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $count);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["ItemName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["CcQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $issue);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $Result["TotalQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalweight);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $price);
    $start_row++;
    $count++;
    $Weight += $totalweight;
    $totalprice += $price;
    $price_W += $Result['PriceSC'];
  }
  $objPHPExcel->getActiveSheet()->mergeCells('A' . $start_row . ':G' . $start_row);
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total_weight'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $Weight);
  $start_row++;
  $objPHPExcel->getActiveSheet()->mergeCells('A' . $start_row . ':G' . $start_row);
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total_price'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $price_W);



  $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
  $width = array(10, 10, 10, 10, 10, 10, 10, 10, 10, 10);
  for ($j = 0; $j < count($cols); $j++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
  }
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

  $HEAD = array(
    'alignment' => array(
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 16,
      'name'  => 'THSarabun'
    )
  );
  $SUBHEAD = array(

    'font'  => array(
      'size'  => 10,
      'name'  => 'THSarabun'
    )
  );
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
  $styleArray = array(

    'borders' => array(

      'allborders' => array(

        'style' => PHPExcel_Style_Border::BORDER_THIN
      )
    ),
    'font'  => array(
      'size'  => 8,
      'name'  => 'THSarabun'
    )
  );
  $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($HEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A6:A11")->applyFromArray($SUBHEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A13:H" . $start_row)->applyFromArray($styleArray);

  $objPHPExcel->getActiveSheet()->getStyle("A13:J13")->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("A13:A" . $start_row)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("C14:J" . $start_row)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("C14:H" . $start_row);

  $objPHPExcel->getActiveSheet()->getStyle("G14:H" . $start_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
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
}
if ($government == 1) {
  $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r4'][$language]);
  $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $array['docno'][$language] . " : " . $docno);
  $objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['hospital'][$language] . " : " . $HptName);
  $objPHPExcel->getActiveSheet()->setCellValue('A8', $array2['ward'][$language] . " : " . $DeptName);
  $objPHPExcel->getActiveSheet()->setCellValue('A9', $array2['date'][$language] . " : " . $DocDate);
  $objPHPExcel->getActiveSheet()->setCellValue('A10', $array2['shelfcounttime'][$language] . " : " . $TIME);
  $objPHPExcel->getActiveSheet()->setCellValue('A11', $array2['deliverytime'][$language] . " : " . $ENDTIME);
  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A13',  $array2['no'][$language])
    ->setCellValue('B13',  $array2['itemname'][$language])
    ->setCellValue('C13',  $array2['parqty'][$language])
    ->setCellValue('D13',  $array2['shelfcount1'][$language])
    ->setCellValue('E13',  $array2['max'][$language])
    ->setCellValue('F13',  $array2['issue'][$language])
    ->setCellValue('G13',  $array2['short'][$language])
    ->setCellValue('H13',  $array2['over'][$language])
    ->setCellValue('I13',  $array2['weight'][$language]);

  $query = "SELECT
  item.ItemName,
  item.weight,
  IFNULL(shelfcount_detail.ParQty, 0) AS ParQty,
  IFNULL(shelfcount_detail.CcQty, 0) AS CcQty,
  IFNULL(
    shelfcount_detail.TotalQty,
    0
  ) AS TotalQty,
  IFNULL(shelfcount_detail.Over, 0) AS OverPar,
  IFNULL(shelfcount_detail.Short, 0) AS Short,
  IFNULL(item.Weight, 0) AS Weight,
  category_price.Price
  FROM
  shelfcount
  INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
  INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
  LEFT JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
            WHERE shelfcount.DocNo='$docno'
            AND shelfcount_detail.TotalQty <> 0
              AND shelfcount.isStatus<> 9
  ";
  $issue = $Result['ParQty'] - $Result['CcQty'];
  $totalweight = $Result['TotalQty'] * $Result['Weight'];
  $price = $totalweight * $Result['Price'];

  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $issue = $Result['ParQty'] - $Result['CcQty'];
    $totalweight = $Result['TotalQty'] * $Result['Weight'];
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $count);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["ItemName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["CcQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $issue);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $Result["TotalQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $Result["Short"]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $Result["OverPar"]);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalweight);
    $start_row++;
    $count++;
    $Weight += $totalweight;
  }
  $objPHPExcel->getActiveSheet()->mergeCells('A' . $start_row . ':H' . $start_row);
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total_weight'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $Weight);



  $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
  $width = array(10, 10, 10, 10, 10, 10, 10, 10, 10);
  for ($j = 0; $j < count($cols); $j++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
  }
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

  $HEAD = array(
    'alignment' => array(
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 16,
      'name'  => 'THSarabun'
    )
  );
  $SUBHEAD = array(

    'font'  => array(
      'size'  => 10,
      'name'  => 'THSarabun'
    )
  );
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
  $styleArray = array(

    'borders' => array(

      'allborders' => array(

        'style' => PHPExcel_Style_Border::BORDER_THIN
      )
    ),
    'font'  => array(
      'size'  => 8,
      'name'  => 'THSarabun'
    )
  );
  $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($HEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A6:A11")->applyFromArray($SUBHEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A13:I" . $start_row)->applyFromArray($styleArray);

  $objPHPExcel->getActiveSheet()->getStyle("A13:I13")->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("A13:A" . $start_row)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("C14:I" . $start_row)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("C14:H" . $start_row)->getNumberFormat()->setFormatCode('#,##0');

  $objPHPExcel->getActiveSheet()->getStyle("I14:I" . $start_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);;
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
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Report_Daily_issue_Request');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_Daily_issue_Request_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
