<?php
ini_set ('memory_limit',' -1 ');
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
$HptCode = $_GET['HptCode'];
$DocNo = $_GET['DocNo'];



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
$date_cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG');
$date_cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG');
$round_AZ1 = sizeof($date_cell1);
$round_AZ2 = sizeof($date_cell2);
for ($a = 0; $a < $round_AZ1; $a++) {
  for ($b = 0; $b < $round_AZ2; $b++) {
    array_push($date_cell1, $date_cell1[$a] . $date_cell2[$b]);
  }
}
// -----------------------------------------------------------------------------------

  // -----------------------------------------------------------------------------------
  $objPHPExcel->setActiveSheetIndex(0);
  // $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Linen_Stock_Count');
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
  // $objPHPExcel->getActiveSheet()->mergeCells('A5:L5');

  $objPHPExcel->getActiveSheet()->mergeCells('A6:H6');
  $objPHPExcel->getActiveSheet()->setCellValue('A6', 'Linen Stock Count');
  $objPHPExcel->getActiveSheet()->setCellValue('A7', '');
  $objPHPExcel->getActiveSheet()->setCellValue('B7', 'รายละเอียดไทย');
  $objPHPExcel->getActiveSheet()->setCellValue('C7', 'ราคาต่อชิ้น');
  $objPHPExcel->getActiveSheet()->setCellValue('D7', 'ยอดยกมา');
  $objPHPExcel->getActiveSheet()->setCellValue('E7', 'New Stock ผ้าใหม่ยกมา');
  $objPHPExcel->getActiveSheet()->setCellValue('F7', 'ซื้อเพิ่มในเดือน');
  $objPHPExcel->getActiveSheet()->setCellValue('G7', '');
  $objPHPExcel->getActiveSheet()->setCellValue('H7', 'มูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('I6:J6');
  $objPHPExcel->getActiveSheet()->setCellValue('I6', 'New Linens in Month');
  $objPHPExcel->getActiveSheet()->setCellValue('I7', 'จำนวน');
  $objPHPExcel->getActiveSheet()->setCellValue('J7', 'มูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('K6:L6');
  $objPHPExcel->getActiveSheet()->setCellValue('K6', 'Old Bal + New Purchase');
  $objPHPExcel->getActiveSheet()->setCellValue('K7', 'รวมยอด');
  $objPHPExcel->getActiveSheet()->setCellValue('L7', 'รวมมูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('M6:T6');
  $objPHPExcel->getActiveSheet()->setCellValue('M6', 'Linen stock count');
  $objPHPExcel->getActiveSheet()->setCellValue('M7', 'บนวอร์ด');
  $objPHPExcel->getActiveSheet()->setCellValue('N7', 'ห้องคนไข้');
  $objPHPExcel->getActiveSheet()->setCellValue('O7', 'ห้องผ้า');
  $objPHPExcel->getActiveSheet()->setCellValue('P7', 'ผ้าเปื้อน');
  $objPHPExcel->getActiveSheet()->setCellValue('Q7', 'ผ้าสะอาด');
  $objPHPExcel->getActiveSheet()->setCellValue('R7', 'OPD');
  $objPHPExcel->getActiveSheet()->setCellValue('S7', 'ยอดตรวจนับ');
  $objPHPExcel->getActiveSheet()->setCellValue('T7', 'มูลค่าตรวจนับ');

  $objPHPExcel->getActiveSheet()->mergeCells('U6:V6');
  $objPHPExcel->getActiveSheet()->setCellValue('U6', 'Sale to Patient');
  $objPHPExcel->getActiveSheet()->setCellValue('U7', 'ยอดขายผู้ป่วย');
  $objPHPExcel->getActiveSheet()->setCellValue('V7', 'มูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('W6:X6');
  $objPHPExcel->getActiveSheet()->setCellValue('W6', 'Damage');
  $objPHPExcel->getActiveSheet()->setCellValue('W7', 'ยอดชำรุด');
  $objPHPExcel->getActiveSheet()->setCellValue('X7', 'มูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('Y6:Z6');
  $objPHPExcel->getActiveSheet()->setCellValue('Y6', 'New Bal + Sale + Damage');
  $objPHPExcel->getActiveSheet()->setCellValue('Y7', 'รวมยอด');
  $objPHPExcel->getActiveSheet()->setCellValue('Z7', 'รวมมูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('AA6:AC6');
  $objPHPExcel->getActiveSheet()->mergeCells('AA7:AC7');
  $objPHPExcel->getActiveSheet()->setCellValue('AA7', 'Linen Loss / Month');


  $objPHPExcel->getActiveSheet()->mergeCells('AD6:AE6');
  $objPHPExcel->getActiveSheet()->setCellValue('AD6', 'New Bal + Sale + Damage');
  $objPHPExcel->getActiveSheet()->setCellValue('AD7', 'รวมยอด');
  $objPHPExcel->getActiveSheet()->setCellValue('AE7', 'รวมมูลค่า');

  $objPHPExcel->getActiveSheet()->mergeCells('AF6:AG6');
  $objPHPExcel->getActiveSheet()->setCellValue('AF6', 'New Stock-เบิกเพิ่มในเดือน');
  $objPHPExcel->getActiveSheet()->setCellValue('AF7', 'รวมยอด');
  $objPHPExcel->getActiveSheet()->setCellValue('AG7', 'รวมมูลค่า');


  $objPHPExcel->getActiveSheet()->setCellValue('A8', 'No.');
  $objPHPExcel->getActiveSheet()->setCellValue('B8', 'Thai Description');
  $objPHPExcel->getActiveSheet()->setCellValue('C8', 'Price/ pcs');
  $objPHPExcel->getActiveSheet()->setCellValue('D8', 'LCS');
  $objPHPExcel->getActiveSheet()->setCellValue('E8', 'NLS');
  $objPHPExcel->getActiveSheet()->setCellValue('F8', 'NLS ซื้อเพิ่ม');
  $objPHPExcel->getActiveSheet()->setCellValue('G8', 'Total NLS');
  $objPHPExcel->getActiveSheet()->setCellValue('H8', 'Balance Amount');

  $objPHPExcel->getActiveSheet()->setCellValue('I8', 'New Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('J8', 'Amount');

  $objPHPExcel->getActiveSheet()->setCellValue('K8', 'Total Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('L8', 'Total Amount');

  $objPHPExcel->getActiveSheet()->setCellValue('M8', 'Shelf (Ward)');
  $objPHPExcel->getActiveSheet()->setCellValue('N8', 'Pt. Room');
  $objPHPExcel->getActiveSheet()->setCellValue('O8', 'Shelf (Linens Room)');
  $objPHPExcel->getActiveSheet()->setCellValue('P8', 'Dirty');
  $objPHPExcel->getActiveSheet()->setCellValue('Q8', 'Clean');
  $objPHPExcel->getActiveSheet()->setCellValue('R8', 'OPD');
  $objPHPExcel->getActiveSheet()->setCellValue('S8', 'Stock Count Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('T8', 'Stock Count Amount');

  $objPHPExcel->getActiveSheet()->setCellValue('U8', 'Sale Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('V8', 'Sale Amount');
  $objPHPExcel->getActiveSheet()->setCellValue('W8', 'Damage Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('X8', 'Damage Amount');

  $objPHPExcel->getActiveSheet()->setCellValue('Y8', 'Total Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('Z8', 'Total Amount');

  $objPHPExcel->getActiveSheet()->setCellValue('AA8', 'Gain/Loss');
  $objPHPExcel->getActiveSheet()->setCellValue('AB8', 'Amount');
  $objPHPExcel->getActiveSheet()->setCellValue('AC8', '% Loss');

  $objPHPExcel->getActiveSheet()->setCellValue('AD8', 'Total Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('AE8', 'Total Amount');
  $objPHPExcel->getActiveSheet()->setCellValue('AF8', 'Total Qty');
  $objPHPExcel->getActiveSheet()->setCellValue('AG8', 'Total Amount');




  // ----------------------------------------------------------------------------------------
    $r = 0;
    $start_row = 9;
    $i = 1;
    $query = "SELECT
                item.ItemName,
                item.ItemCode, 
                calexcel_detail.Input1, 
                calexcel_detail.Input2, 
                calexcel_detail.Input3, 
                calexcel_detail.Input4, 
                calexcel_detail.Input5, 
                calexcel_detail.Input6, 
                calexcel_detail.Input8, 
                calexcel_detail.Input7, 
                calexcel_detail.Input9, 
                calexcel_detail.Input10, 
                calexcel_detail.Input11, 
                calexcel_detail.Input12, 
                calexcel_detail.Input15, 
                calexcel_detail.Input13, 
                calexcel_detail.Input14, 
                calexcel_detail.Input16, 
                calexcel_detail.Input17, 
                calexcel_detail.Input18, 
                calexcel_detail.Input19, 
                calexcel_detail.Input20, 
                calexcel_detail.Input21, 
                calexcel_detail.Input22, 
                calexcel_detail.Input23, 
                calexcel_detail.Input24, 
                calexcel_detail.Input26, 
                calexcel_detail.Input25, 
                calexcel_detail.Input27, 
                calexcel_detail.Input28, 
                calexcel_detail.Input29, 
                calexcel_detail.Input30, 
                calexcel_detail.Input31, 
                calexcel_detail.DocNo
              FROM
              calexcel_detail
              LEFT JOIN item ON calexcel_detail.ItemCode = item.ItemCode 
              WHERE DocNo  = '$DocNo' ";

    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery))
    {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $i);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["ItemName"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input1"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input2"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input3"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input4"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input5"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input6"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input7"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input8"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input9"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input10"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input11"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input12"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input13"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input14"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input15"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input16"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input17"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input18"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input19"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input20"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input21"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input22"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input23"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input24"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input25"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input26"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input27"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input28"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input29"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input30"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Input31"]);
      $r++;
      $start_row ++;
      $r = 0;
      $i ++;
    }

    $Sql2 = "SELECT
    SUM(calexcel_detail.Input1) AS sum_Input1,
    SUM(calexcel_detail.Input2) AS sum_Input2,
    SUM(calexcel_detail.Input3) AS sum_Input3,
    SUM(calexcel_detail.Input4) AS sum_Input4,
    SUM(calexcel_detail.Input5) AS sum_Input5,
    SUM(calexcel_detail.Input6) AS sum_Input6,
    SUM(calexcel_detail.Input7) AS sum_Input7,
    SUM(calexcel_detail.Input8) AS sum_Input8,
    SUM(calexcel_detail.Input9) AS sum_Input9,
    SUM(calexcel_detail.Input10) AS sum_Input10,
    SUM(calexcel_detail.Input11) AS sum_Input11,
    SUM(calexcel_detail.Input12) AS sum_Input12,
    SUM(calexcel_detail.Input13) AS sum_Input13,
    SUM(calexcel_detail.Input14) AS sum_Input14,
    SUM(calexcel_detail.Input15) AS sum_Input15,
    SUM(calexcel_detail.Input16) AS sum_Input16,
    SUM(calexcel_detail.Input17) AS sum_Input17,
    SUM(calexcel_detail.Input18) AS sum_Input18,
    SUM(calexcel_detail.Input19) AS sum_Input19,
    SUM(calexcel_detail.Input20) AS sum_Input20,
    SUM(calexcel_detail.Input21) AS sum_Input21,
    SUM(calexcel_detail.Input22) AS sum_Input22,
    SUM(calexcel_detail.Input23) AS sum_Input23,
    SUM(calexcel_detail.Input24) AS sum_Input24,
    SUM(calexcel_detail.Input25) AS sum_Input25,
    SUM(calexcel_detail.Input26) AS sum_Input26,
    SUM(calexcel_detail.Input27) AS sum_Input27,
    SUM(calexcel_detail.Input28) AS sum_Input28,
    SUM(calexcel_detail.Input29) AS sum_Input29,
    SUM(calexcel_detail.Input30) AS sum_Input30,
    SUM(calexcel_detail.Input31) AS sum_Input31

  FROM
    calexcel_detail
  WHERE DocNo = '$DocNo' ";

$meQuery = mysqli_query($conn, $Sql2);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, "");
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, "รวม");
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input1"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input2"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input3"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input4"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input5"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input6"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input7"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input8"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input9"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input10"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input11"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input12"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input13"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input14"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input15"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input16"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input17"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input18"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input19"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input20"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input21"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input22"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input23"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input24"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input25"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input26"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input27"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input28"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input29"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input30"]);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["sum_Input31"]);
  $r++;
  $start_row ++;
  $r = 0;
  $i ++;
}






    $AA = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
  
      'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'name'  => 'THSarabun'
      )
    );

  $A5 = array(
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),

    'font'  => array(
      'bold'  => true,
      'size'  => 20,
      'name'  => 'THSarabun'
    )
  );

  $A7 = array(
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),

    'font'  => array(
      'bold'  => true,
      'size'  => 10,
      'name'  => 'THSarabun'
    )
  );

  $fill = array(
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
    )
  );
  $colorfill = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => '669933')
    )
  );
  $colorfill2 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => '99CC00')
    )
  );
  $colorfill3 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'FFFF66')
    )
  );
  $colorfill4 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'FFDAB9')
    )
  );
  $colorfill5 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'CC9933')
    )
  );
  $colorfill6 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'FFCC66')
    )
  );
  $colorfill7 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => '669966')
    )
  );
  $colorfill8 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'CCFF66')
    )
  );
  $colorfill9 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'FFFF66')
    )
  );
  $colorfill10 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'CC6666')
    )
  );
  $colorfilltop1 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'FFFF00')
    )
  );
  $colorfilltop2 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => '778899')
    )
  );
  $colorfilltop3 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => '66CC99')
    )
  );
  $colorfilltop4 = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'DCDCDC')
    )
  );

  $objPHPExcel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($A5);
  $objPHPExcel->getActiveSheet()->getStyle("A6:AG6")->applyFromArray($AA);

  

  $objPHPExcel->getActiveSheet()->getStyle('A9:AG' . $start_row)->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle('A6:AG6')->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle('A7:AG7')->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle('A8:AG8')->applyFromArray($styleArray);

  $objPHPExcel->getActiveSheet()->getStyle('D9:F' . $start_row)->applyFromArray($colorfilltop1);
  $objPHPExcel->getActiveSheet()->getStyle('I9:I' . $start_row)->applyFromArray($colorfilltop2);
  $objPHPExcel->getActiveSheet()->getStyle('M9:R' . $start_row)->applyFromArray($colorfilltop2);
  $objPHPExcel->getActiveSheet()->getStyle('U9:U' . $start_row)->applyFromArray($colorfilltop2);
  $objPHPExcel->getActiveSheet()->getStyle('W9:W' . $start_row)->applyFromArray($colorfilltop2);
  $objPHPExcel->getActiveSheet()->getStyle('Y9:Z' . $start_row)->applyFromArray($colorfilltop3);
  $objPHPExcel->getActiveSheet()->getStyle('AD9:AG' . $start_row)->applyFromArray($colorfilltop4);

  $objPHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("I6")->applyFromArray($colorfill2);
  $objPHPExcel->getActiveSheet()->getStyle("K6")->applyFromArray($colorfill3);
  $objPHPExcel->getActiveSheet()->getStyle("M6")->applyFromArray($colorfill4);

  $objPHPExcel->getActiveSheet()->getStyle("U6")->applyFromArray($colorfill5);
  $objPHPExcel->getActiveSheet()->getStyle("W6")->applyFromArray($colorfill6);
  $objPHPExcel->getActiveSheet()->getStyle("Y6")->applyFromArray($colorfill7);

  $objPHPExcel->getActiveSheet()->getStyle("AA6")->applyFromArray($colorfill8);
  $objPHPExcel->getActiveSheet()->getStyle("AD6")->applyFromArray($colorfill9);
  $objPHPExcel->getActiveSheet()->getStyle("AF6")->applyFromArray($colorfill10);

  $objPHPExcel->getActiveSheet()->getStyle("A7:H7")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("I7:J7")->applyFromArray($colorfill2);
  $objPHPExcel->getActiveSheet()->getStyle("K7:L7")->applyFromArray($colorfill3);
  $objPHPExcel->getActiveSheet()->getStyle("M7:T7")->applyFromArray($colorfill4);

  $objPHPExcel->getActiveSheet()->getStyle("U7:V7")->applyFromArray($colorfill5);
  $objPHPExcel->getActiveSheet()->getStyle("W7:X7")->applyFromArray($colorfill6);
  $objPHPExcel->getActiveSheet()->getStyle("Y7:Z7")->applyFromArray($colorfill7);

  $objPHPExcel->getActiveSheet()->getStyle("AA7")->applyFromArray($colorfill8);
  $objPHPExcel->getActiveSheet()->getStyle("AD7:AE7")->applyFromArray($colorfill9);
  $objPHPExcel->getActiveSheet()->getStyle("AF7:AG7")->applyFromArray($colorfill10);



  $objPHPExcel->getActiveSheet()->getStyle("A8:H8")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("I8:J8")->applyFromArray($colorfill2);
  $objPHPExcel->getActiveSheet()->getStyle("K8:L8")->applyFromArray($colorfill3);
  $objPHPExcel->getActiveSheet()->getStyle("M8:T8")->applyFromArray($colorfill4);
  $objPHPExcel->getActiveSheet()->getStyle("U8:V8")->applyFromArray($colorfill5);
  $objPHPExcel->getActiveSheet()->getStyle("W8:X8")->applyFromArray($colorfill6);
  $objPHPExcel->getActiveSheet()->getStyle("Y8:Z8")->applyFromArray($colorfill7);
  $objPHPExcel->getActiveSheet()->getStyle("AA8:AC8")->applyFromArray($colorfill8);
  $objPHPExcel->getActiveSheet()->getStyle("AD8:AE8")->applyFromArray($colorfill9);
  $objPHPExcel->getActiveSheet()->getStyle("AF8:AG8")->applyFromArray($colorfill10);

  $objPHPExcel->getActiveSheet()->getStyle("A7:" . "AG" . $start_row)->applyFromArray($fill);
  $objPHPExcel->getActiveSheet()->getStyle("A7:AG7")->applyFromArray($A7);
  $objPHPExcel->getActiveSheet()->getStyle("A8:AG8")->applyFromArray($A7);
  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(30);



  // foreach (range('A', 'D') as $columnID) {
  //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
  //     ->setAutoSize(true);
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
  $objDrawing->setWidthAndHeight(170, 90);
  $objDrawing->setResizeProportional(true);
  $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

  // Rename worksheet
  $objPHPExcel->getActiveSheet()->setTitle("Linen Stock Count");
  $objPHPExcel->createSheet();




//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_Linen_Stock_Count_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
