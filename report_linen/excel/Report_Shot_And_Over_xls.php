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
echo "<pre>";
print_r($data);
echo "</pre>";
$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
$DepCode[] = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$itemfromweb = $data[11];
if ($DepCode[0] == 0) {
  $DepCode = explode(',', $_GET['Dep10']);
  echo "<pre>";
  print_r($DepCode);
  echo "</pre>";
}




$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$date = [];
$itemCode = [];
$itemName = [];
$DateShow = [];
$par = [];
$date_header1 = '';
$date_header2 = '';
$date_header3 = '';
$TotalISSUE = 0;
$TotalShort = 0;
$TotalOver = 0;
$ISSUE = 0;
$Short = 0;
$Over = 0;
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
      $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " . $year2;
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
  $where =   "WHERE DATE(shelfcount.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
// Setting rows/columns to repeat at the top/left of each page

$date_cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$date_cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$round_AZ1 = sizeof($date_cell1);
$round_AZ2 = sizeof($date_cell2);
for ($a = 0; $a < $round_AZ1; $a++) {
  for ($b = 0; $b < $round_AZ2; $b++) {
    array_push($date_cell1, $date_cell1[$a] . $date_cell2[$b]);
  }
}

if ($chk == 'one') {
  if ($format == 1) {
    $count = 1;
    $date[] = $date1;
    list($y, $m, $d) = explode('-', $date1);
    if ($language ==  'th') {
      $y = $y + 543;
    }
    $date1 = $d . '-' . $m . '-' . $y;
    $DateShow[] = $date1;
  }
} elseif ($chk == 'between') {
  list($year, $month, $day) = explode('-', $date2);
  if ($day <> 31) {
    $day = $day + 1;
  }
  $date2 = $year . "-" . $month . "-" . $day;
  $period = new DatePeriod(
    new DateTime($date1),
    new DateInterval('P1D'),
    new DateTime($date2)
  );
  foreach ($period as $key => $value) {
    $date[] = $value->format('Y-m-d');
  }
  $count = count($date);
  for ($i = 0; $i < $count; $i++) {
    $date1 = $date[$i];
    list($y, $m, $d) = explode('-', $date1);
    if ($language ==  'th') {
      $y = $y + 543;
    }
    $date1 = $d . '-' . $m . '-' . $y;
    $DateShow[] = $date1;
  }
} elseif ($chk == 'month') {
  $day = 1;
  $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
  $datequery =  $year1 . '-' . $date1 . '-';
  $dateshow = '-' . $date1 . '-' . $year1;
  for ($i = 0; $i < $count; $i++) {
    $date[] = $datequery . $day;
    $DateShow[] = $day . $dateshow;
    $day++;
  }
}
$sheet_count = sizeof($DepCode);
for ($sheet = 0; $sheet < $sheet_count; $sheet++) {
  $objPHPExcel->setActiveSheetIndex($sheet)
    ->setCellValue('A7',  'Department')
    ->setCellValue('B7',  'ItemName')
    ->setCellValue('C7',  'PAR');
  // -----------------------------------------------------------------------------------
  $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A4', $array2['r7']['en']);
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
  $objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
  $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
  $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
  $objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
  $objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
  $objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
  // -----------------------------------------------------------------------------------

  $query = "SELECT
  department.DepName
  FROM
  department
  WHERE
  department.DepCode = '$DepCode[$sheet]'";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A5', $Result["DepName"]);
    $DepName = $Result["DepName"];
    $DepName = str_replace("/", " ", $DepName);
  }
  // -----------------------------------------------------------------------------------
  $item = "SELECT
  shelfcount_detail.itemname,
  shelfcount_detail.itemcode,
	sum(shelfcount_detail.ParQty) as ParQty
  FROM
  shelfcount_detail
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  INNER JOIN time_sc  ON shelfcount.sctime = time_sc.id
  WHERE
  shelfcount_detail.isStatus <> 9
  AND shelfcount_detail.DepCode = '$DepCode[$sheet]'
  AND (shelfcount_detail.Over <> 0 OR shelfcount_detail.Short <> 0 )
  AND   time_sc.TimeName <>'Extra'
  GROUP BY  shelfcount_detail.itemcode
  ORDER BY  shelfcount_detail.itemname ASC ";
  $meQuery = mysqli_query($conn, $item);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $itemName[] =  $Result["itemname"];
    $itemCode[] =  $Result["itemcode"];
    $par[] = $Result["ParQty"];
  }

  // -----------------------------------------------------------------------------------

  $countitem = sizeof($itemCode);
  $start_row = 9;
  $start_col = 3;
  $start_date = 1;
  $start_itemcode = 1;
  // -----------------------------------------------------------------------------------

  for ($j = 0; $j < $count; $j++) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'SHORT QTY');
    $date_header1 = $date_cell1[$start_col];
    $start_col++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'OVER QTY');
    $date_header2 = $date_cell1[$start_col];
    $start_col++;
    $objPHPExcel->getActiveSheet()->mergeCells($date_header1 . '7:' . $date_header2 . '7');
    $objPHPExcel->getActiveSheet()->setCellValue($date_header1 . "7", $DateShow[$j]);
    $date_header1 = '';
    $date_header2 = '';
    $date_header3 = '';
  }
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'SHORT QTY');
  $date_header1 = $date_cell1[$start_col];
  $start_col++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'OVER QTY');
  $date_header2 = $date_cell1[$start_col];
  $start_col++;
  $objPHPExcel->getActiveSheet()->mergeCells($date_header1 . '7:' . $date_header2 . '7');
  $objPHPExcel->getActiveSheet()->setCellValue($date_header1 . "7", 'Total');

  // -----------------------------------------------------------------------------------
  $start_col = 0;
  $start_row = 9;
  // for ($q = 0; $q < $countitem; $q++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $DepName);
  //   $start_row++;
  // }
  $start_col = 1;
  $start_row = 9;
  for ($q = 0; $q < $countitem; $q++) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $itemName[$q]);
    $start_row++;
  }
  $start_col = 2;
  $start_row = 9;
  for ($q = 0; $q < $countitem; $q++) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $par[$q]);
    $start_row++;
  }

  $start_row = 9;
  $r = 3;
  for ($q = 0; $q < $countitem; $q++) {
    for ($day = 0; $day < $count; $day++) {
      $data = "SELECT 
   COALESCE( SUM(shelfcount_detail.Short),'0') as  Short, 
   COALESCE(SUM(shelfcount_detail.Over),'0') as  Over 
    FROM shelfcount 
    INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo 
    INNER JOIN time_sc  ON shelfcount.sctime = time_sc.id
    WHERE  DATE(shelfcount_detail.DocDate)  ='$date[$day]'  
    AND shelfcount_detail.isStatus <> 9
    AND shelfcount_detail.DepCode = '$DepCode[$sheet]'  
    AND time_sc.TimeName <>'Extra'
    AND shelfcount_detail.itemcode = '$itemCode[$q]' ";
      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Short"]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Over"]);
        $r++;
        $Short += $Result["Short"];
        $Over += $Result["Over"];
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over);
    $Short = 0;
    $Over = 0;
    $r = 3;
    $start_row++;
  }
  $r = 3;
  for ($day = 0; $day < $count; $day++) {
    $data = "SELECT COALESCE(SUM(shelfcount_detail.TotalQty),'0') as  ISSUE,
                    COALESCE(SUM(shelfcount_detail.Short),'0') as  Short, 
                    COALESCE(SUM(shelfcount_detail.Over),'0') as  Over 
            FROM shelfcount 
            INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo 
            INNER JOIN time_sc  ON shelfcount.sctime = time_sc.id
            WHERE  DATE(shelfcount_detail.DocDate)  ='$date[$day]'  
            AND shelfcount_detail.isStatus <> 9
            AND shelfcount_detail.DepCode = '$DepCode[$sheet]'  
            AND time_sc.TimeName <>'Extra'
            AND (shelfcount_detail.Over <> 0 OR shelfcount_detailS.Short <> 0 )";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Short"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Over"]);
      $r++;
      $TotalShort += $Result["Short"];
      $TotalOver += $Result["Over"];
    }
  }
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalShort);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalOver);
  $r++;
  $rrrr = 0;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$rrrr] . $start_row, 'Total');

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
      'size'  => 8,
      'name'  => 'THSarabun'
    )
  );
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
  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'B9E3E6')
    )
  );
  $r2 = $r - 2;
  $r1 = $r - 1;
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r1] . $start_row)->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r1] . "8")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r1] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r2] . "9:" . $date_cell1[$r1] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("A5:" . $date_cell1[$r] . "8")->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle($date_cell1[2] . $start_row . ":" . $date_cell1[$r] . $start_row);
  $objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray($HEAD);
  $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode('#,##0');


  $cols = array('A', 'B');
  $width = array(40, 40);
  for ($j = 0; $j < count($cols); $j++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
  }
  // foreach(range('A','ZZZ') as $columnID) {
  //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
  //       ->setAutoSize(true);
  // }

  $objDrawing = new PHPExcel_Worksheet_Drawing();
  $objDrawing->setName('Nhealth_linen');
  $objDrawing->setDescription('Nhealth_linen');
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
  $objPHPExcel->getActiveSheet()->setTitle($DepName);
  $objPHPExcel->createSheet();
  $itemName = [];
  $itemCode = [];
  $par = [];
  $TotalISSUE = 0;
  $TotalShort = 0;
  $TotalOver = 0;
  $ISSUE = 0;
  $Short = 0;
  $Over = 0;
  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
}



$objPHPExcel->removeSheetByIndex(
  $objPHPExcel->getIndex(
    $objPHPExcel->getSheetByName('Worksheet')
  )
);
//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_Shot_And_Over_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
