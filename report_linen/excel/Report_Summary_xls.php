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
// $DepCode[] = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$itemfromweb = $data[11];
if ($data[7] == "0") {
  $ss = "$data[7]";
  $DepCode = explode(',', $_GET['Dep10']);
  // echo "<pre>";
  // print_r($DepCode);
  // echo "</pre>";
} else {
  $ss = "2";
  $DepCode[0] = $data[7];
}

$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$itemCode = [];
$itemName = [];
$Weight = [];
$DateShow = [];
$ISSUE = 0;
$TOTAL_LASTWEIGHT = 0;
$TotalISSUE = 0;
$status = '1';
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}

if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (report_sc.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (report_sc.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE report_sc.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (report_sc.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE DATE(report_sc.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
// -----------------------------------------------------------------------------------
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
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
// echo "<pre>";
// print_r($DepCode);
// echo "</pre>";
if ($chk == 'one') {
  if ($format == 1) {
    $count = 1;
    $date[] = $date1;
    list($y, $m, $d) = explode('-', $date1);
    if ($language ==  'th') {
      $y = $y + 543;
    } else {
      $y = $y;
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
$date_cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$date_cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$round_AZ1 = sizeof($date_cell1);
$round_AZ2 = sizeof($date_cell2);
for ($a = 0; $a < $round_AZ1; $a++) {
  for ($b = 0; $b < $round_AZ2; $b++) {
    array_push($date_cell1, $date_cell1[$a] . $date_cell2[$b]);
  }
}
if ($itemfromweb == '0') {
  $sheet_count = sizeof($DepCode);
  for ($sheet = 0; $sheet < $sheet_count; $sheet++) {
    $objPHPExcel->setActiveSheetIndex($sheet)
      ->setCellValue('A8',  'CusName')
      ->setCellValue('B8',  'ItemName')
      ->setCellValue('C8',  'ParQty')
      ->setCellValue('D8',  'ItemWeight')
      ->setCellValue('E8',  'Price');
    // -----------------------------------------------------------------------------------

    // -----------------------------------------------------------------------------------
    $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
    $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r29'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['department'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('A7', $date_header);
    $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
    $objPHPExcel->getActiveSheet()->mergeCells('A7:J7');
    // -----------------------------------------------------------------------------------

    // -----------------------------------------------------------------------------------
    $query = "SELECT
    department.DepName
    FROM
    department
    WHERE
    department.DepCode = '$DepCode[$sheet]'  ";
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('A6', $Result["DepName"]);
      $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result["DepName"]);
      $DepName = $Result["DepName"];
      $DepName = str_replace("/", " ", $DepName);
    }
    // -----------------------------------------------------------------------------------
    $item = "SELECT
    report_sc.itemname,
    report_sc.itemcode
    FROM
    report_sc
    $where
      AND report_sc.isStatus <> 9
      AND report_sc.isStatus <> 0
      AND report_sc.DepCode = '$DepCode[$sheet]'
      AND report_sc.TotalQty <> 0
      GROUP BY  report_sc.itemcode ORDER BY report_sc.ItemName ASC ";
    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $itemName[] =  $Result["itemname"];
      $itemCode[] =  $Result["itemcode"];
    }
    // -----------------------------------------------------------------------------------
    $countitem = sizeof($itemCode);
    $start_row = 9;
    $start_col = 5;
    // -----------------------------------------------------------------------------------

    for ($j = 0; $j < $count; $j++) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", $DateShow[$j]);
      $start_col++;
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Qty');
    $start_col++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Weight ');
    // -----------------------------------------------------------------------------------
    for ($i = 0; $i < $countitem; $i++) {
      $item = "SELECT
      report_sc.ParQty AS  ParQty,
      report_sc.WeightPerQty AS Weight,
      category_price.Price AS Price
      FROM
      report_sc
      LEFT JOIN category_price ON category_price.CategoryCode = report_sc.CategoryCode
      $where
      AND report_sc.itemcode = '$itemCode[$i]'
      AND report_sc.DepCode = '$DepCode[$sheet]'
      GROUP BY  report_sc.itemcode  ";

      $meQuery = mysqli_query($conn, $item);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $itemName[$i]);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["Weight"]);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $Result["Price"]);
        $start_row++;
        $Weight[] = $Result["Weight"];
        
      }
    }

    $start_row = 9;
    $r = 5;
    $w = 0;
    for ($q = 0; $q < $countitem; $q++) {
      for ($day = 0; $day < $count; $day++) {
        $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE,
       COALESCE(sum(report_sc.Weight),'0') as  Weight
      FROM report_sc 
      WHERE  DATE(report_sc.DocDate)  ='$date[$day]'  
      AND report_sc.DepCode = '$DepCode[$sheet]'  
      AND report_sc.itemcode = '$itemCode[$q]'  ";
        $meQuery = mysqli_query($conn, $data);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
          $r++;
          $ISSUE += $Result["ISSUE"];
          $TOTAL_WEIGHT += $Result["Weight"];
        }
      }
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_WEIGHT);
      $ISSUE = 0;
      $TOTAL_WEIGHT = 0;
      $r = 5;
      $w++;
      $start_row++;
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[0] . $start_row, $DepName);
    $r = 5;
    for ($day = 0; $day < $count; $day++) {
      $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
                      COALESCE(sum(report_sc.Weight),'0') as  Weight
                      FROM report_sc 
              WHERE  DATE(report_sc.DocDate)  ='$date[$day]'  
              AND report_sc.DepCode = '$DepCode[$sheet]'  
    ";
      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
        $r++;
        $TotalISSUE += $Result["ISSUE"];
        $TOTAL_LASTWEIGHT  += $Result["Weight"];
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalISSUE);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_LASTWEIGHT);

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
    $r1 = $r - 1;
    $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle("A5:A7")->applyFromArray($HEAD);
    $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle("D9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->applyFromArray($CENTER)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);

    $r2 = $r + 2;
    $countcell = sizeof($date_cell1);
    for ($i = 0; $i < $r2; $i++) {

      if ($i == 4) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
          ->setAutoSize(false);
      } else {
        $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
          ->setAutoSize(true);
      }
    }
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
    $start_row = $start_row - 1;
    foreach (range('9', $start_row) as $column) {
      $objPHPExcel->getActiveSheet()->getRowDimension($column)->setOutlineLevel(1);
      $objPHPExcel->getActiveSheet()->getRowDimension($column)->setVisible(false);
      $objPHPExcel->getActiveSheet()->getRowDimension($column)->setCollapsed(true);
    }
    // Rename worksheet
    // $objPHPExcel->getActiveSheet()->setTitle('5');
    $objPHPExcel->getActiveSheet()->setTitle($DepName);
    $start_row = 9;
    $start_col = 5;
    $itemName = [];
    $itemCode = [];
    // Create a new worksheet, after the default sheet
    $objPHPExcel->createSheet();
    $ISSUE = 0;
    $TOTAL_LASTWEIGHT = 0;
    $TotalISSUE = 0;
  }
}
if ($itemfromweb <> '0') {
  $DepCode = [];
  $Sql = "  SELECT   department.DepName ,department.DepCode
               FROM
               report_sc  
              INNER JOIN department ON department.DepCode = report_sc.DepCode  
              $where
              AND report_sc.isStatus <> 9
              AND report_sc.itemCode = '$itemfromweb'
              GROUP BY department.DepCode ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCode[] = $Result['DepCode'];
    $DepName[] = $Result['DepName'];
  }
  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A8',  'CusName')
    ->setCellValue('B8',  'ItemName')
    ->setCellValue('C8',  'ParQty')
    ->setCellValue('D8',  'ItemWeight')
    ->setCellValue('E8',  'Price');
  // -----------------------------------------------------------------------------------
  $date_cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
  $date_cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
  $round_AZ1 = sizeof($date_cell1);
  $round_AZ2 = sizeof($date_cell2);
  for ($a = 0; $a < $round_AZ1; $a++) {
    for ($b = 0; $b < $round_AZ2; $b++) {
      array_push($date_cell1, $date_cell1[$a] . $date_cell2[$b]);
    }
  }
  // -----------------------------------------------------------------------------------
  $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r29'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['department'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('A7', $date_header);
  $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
  $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
  $objPHPExcel->getActiveSheet()->mergeCells('A7:J7');
  // -----------------------------------------------------------------------------------

  // -----------------------------------------------------------------------------------

  // -----------------------------------------------------------------------------------
  // -----------------------------------------------------------------------------------
  $countDep = sizeof($DepCode);
  $start_row = 9;
  $start_col = 5;
  // -----------------------------------------------------------------------------------

  for ($j = 0; $j < $count; $j++) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", $DateShow[$j]);
    $start_col++;
  }
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Qty');
  $start_col++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Weight ');
  // -----------------------------------------------------------------------------------
  for ($i = 0; $i < $countDep; $i++) {
    $item = "SELECT
      report_sc.ParQty AS  ParQty,
      report_sc.WeightPerQty AS Weight ,
      category_price.Price AS Price,
      department.DepName AS DepName,
      report_sc.itemname AS itemname 
      FROM
      report_sc
      INNER JOIN department ON department.DepCode = report_sc.DepCode  
      LEFT JOIN  category_price ON category_price.CategoryCode = report_sc.CategoryCode
                      WHERE
                      report_sc.itemcode = '$itemfromweb'
                      AND report_sc.DepCode = '$DepCode[$i]'
                      AND report_sc.isStatus <> 9
                      GROUP BY  report_sc.itemcode 
                      ";
    // echo "<pre>";
    // ECHO $item;
    // echo "</pre>";
    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('A6', $Result["itemname"]);
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $Result["DepName"]);
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["itemname"]);
      $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
      $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["Weight"]);
      $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $Result["Price"]);
      $start_row++;
      $iname = $Result["itemname"];
    }
  }
  $start_row = 9;
  $r = 5;
  $w = 0;
  for ($q = 0; $q < $countDep; $q++) {
    for ($day = 0; $day < $count; $day++) {
      $data = "SELECT  COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
      COALESCE(sum(report_sc.Weight),'0') as  Weight
      FROM report_sc 
      WHERE  DATE(report_sc.DocDate)  ='$date[$day]'  
      AND report_sc.isStatus <> 9
      AND report_sc.DepCode = '$DepCode[$q]'  
      AND report_sc.itemcode = '$itemfromweb'  ";
      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
        $r++;
        $ISSUE += $Result["ISSUE"];
        $TOTAL_WEIGHT += $Result["Weight"];
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_WEIGHT);
    $TOTAL_LASTWEIGHT += $TOTAL_WEIGHT;
    $ISSUE = 0;
    $TOTAL_WEIGHT = 0;
    $r = 5;
    $w++;
    $start_row++;
  }

  $r = 5;
  for ($day = 0; $day < $count; $day++) {
    $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
    COALESCE(sum(report_sc.Weight),'0') as  Weight
    FROM report_sc 
    WHERE  DATE(report_sc.DocDate)  ='$date[$day]'  
    AND report_sc.isStatus <> 9
    AND report_sc.itemcode = '$itemfromweb'
    ";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
      $r++;
      $TotalISSUE += $Result["ISSUE"];
    }
  }
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalISSUE);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row,  $TOTAL_LASTWEIGHT);

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
  $r1 = $r - 1;
  $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle("A5:A7")->applyFromArray($HEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("D9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->applyFromArray($CENTER)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  $objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);

  $r2 = $r + 2;
  $countcell = sizeof($date_cell1);
  for ($i = 0; $i < $r2; $i++) {

    if ($i == 4) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
        ->setAutoSize(false);
    } else {
      $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
        ->setAutoSize(true);
    }
  }
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
  $start_row = $start_row - 1;
  foreach (range('9', $start_row) as $column) {
    $objPHPExcel->getActiveSheet()->getRowDimension($column)->setOutlineLevel(1);
    $objPHPExcel->getActiveSheet()->getRowDimension($column)->setVisible(true);
    $objPHPExcel->getActiveSheet()->getRowDimension($column)->setCollapsed(true);
  }
  // Rename worksheet
  // $objPHPExcel->getActiveSheet()->setTitle('5');
  $objPHPExcel->getActiveSheet()->setTitle($iname);
  $start_row = 9;
  $start_col = 5;
  $itemName = [];
  $itemCode = [];
  // Create a new worksheet, after the default sheet
  $objPHPExcel->createSheet();
  $ISSUE = 0;
  $TOTAL_LASTWEIGHT = 0;
  $TotalISSUE = 0;
}


//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_Summary_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
//
$objPHPExcel->removeSheetByIndex(
  $objPHPExcel->getIndex(
    $objPHPExcel->getSheetByName('Worksheet')
  )
);
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
