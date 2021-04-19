<?php
ini_set('memory_limit', ' -1 ');
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
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$itemfromweb = $data[11];
$type = $data[12];
if ($type == 1) {
  $type = 'Existing';
} else {
  $type = 'Detail';
}

if ($data[7] == "0") {
  $DepCode = explode(',', $_GET['Dep10']);
} else {
  $DepCode = $data[7] == null ? '0' : $data[7];
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
$PAR = [];
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
  $HptName = 'HptNameTH';
  $FacName = 'FacNameTH';
} else {
  $HptName = 'HptName';
  $FacName = 'FacName';
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

  $begin = new DateTime($date1);
  $end = new DateTime($date2);
  $end = $end->modify('1 day');

  $interval = new DateInterval('P1D');
  $period = new DatePeriod($begin, $interval, $end);

  foreach ($period as $key => $value) {
    $date[] = $value->format('Y-m-d');
  }

  $count = 0;
  foreach ($date as $key => $value) {
    $date1 = $date[$i];
    list($y, $m, $d) = explode('-', $date1);
    if ($language ==  'th') {
      $y = $y + 543;
    }
    $date1 = $d . '-' . $m . '-' . $y;
    $DateShow[] = $date1;
    $count++;
  }
} elseif ($chk == 'month') {
  $day = 1;
  $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
  $datequery =  $year1 . '-' . $date1 . '-';
  $dateshow = '-' . $date1 . '-' . $year1;
  for ($i = 0; $i < $count; $i++) {
    if ($day < 10) {
      $day = '0' . $day;
    }
    $date[] = $datequery . $day;
    $DateShow[] = $day . $dateshow;
    $day++;
  }
}



if ($type == 'Existing') {
  $DepName = 'ทุกแผนก';
  $query = "SELECT
      department.DepName
      FROM
      department
      WHERE
      department.DepCode = '$DepCode'
      AND department.HptCode = '$HptCode' ";

  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepName = $Result["DepName"];
  }
  $sheet_count = sizeof($HptCode);
  for ($sheet = 0; $sheet < $sheet_count; $sheet++) {
    $objPHPExcel->setActiveSheetIndex($sheet)
      ->setCellValue('A7',  'BU NAME')
      ->setCellValue('B7',  'ITEM NAME');
    // -----------------------------------------------------------------------------------
    $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
    $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Report Usage Existing' . '  ' . '(' . $DepName . ')');
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
    $objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
    $objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
    $objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
    // -----------------------------------------------------------------------------------

    $query = "SELECT
                      site.HptName
                      FROM
                      site
                      WHERE
                      site.HptCode = '$HptCode'  ";

    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('A5', $Result["HptName"]);
      $HptName = $Result["HptName"];
      $HptName = str_replace("/", " ", $HptName);
    }
    // -----------------------------------------------------------------------------------
    if ($DepCode <> 'ALL') {
      $search_dep = " AND report_sc.DepCode = '$DepCode' ";
      $search_item = " AND par_item_stock.DepCode = '$DepCode' ";
    } else {
      $search_dep = "";
      $search_item = "";
    }
    if ($itemfromweb == '0') {
      $search_itemfromweb = " ";
      $search_itemfromweb_itemcode = " ";
    } else {
      $search_itemfromweb = " AND report_sc.ItemCode = '$itemfromweb' ";
      $search_itemfromweb_itemcode = " AND item.ItemCode = '$itemfromweb' ";
    }
    $item = "SELECT
                item.ItemCode,
                item.ItemName 
              FROM
                item 
              INNER JOIN par_item_stock ON par_item_stock.ItemCode = item.ItemCode
              WHERE
              par_item_stock.HptCode = '$HptCode'
              AND item.IsClean = 0
              AND item.IsDirtyBag = 0
              $search_itemfromweb_itemcode
              $search_item
              AND item.IsActive = 1 
              GROUP BY item.ItemCode
              ORDER BY ItemName ";
    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $itemName[] =  $Result["ItemName"];
      $itemCode[] =  $Result["ItemCode"];
    }

    // -----------------------------------------------------------------------------------
    $countitem = sizeof($itemCode);
    $start_row = 9;
    $start_col = 2;
    $start_date = 1;
    $start_itemcode = 1;
    // -----------------------------------------------------------------------------------

    for ($j = 0; $j < $count; $j++) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'ISSUE QTY');
      $date_header1 = $date_cell1[$start_col];
      $start_col++;
      // $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'SHORT QTY');
      // $date_header2 = $date_cell1[$start_col];
      // $start_col++;
      // $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'OVER QTY');
      // $date_header3 = $date_cell1[$start_col];
      // $start_col++;
      // $objPHPExcel->getActiveSheet()->mergeCells($date_header1 . '7:' . $date_header3 . '7');
      $objPHPExcel->getActiveSheet()->setCellValue($date_header1 . "7", $DateShow[$j]);
      $date_header1 = '';
      $date_header2 = '';
      $date_header3 = '';
    }

    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'ISSUE QTY');
    $date_header1 = $date_cell1[$start_col];
    $start_col++;
    // $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'SHORT QTY');
    // $date_header2 = $date_cell1[$start_col];
    // $start_col++;
    // $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'OVER QTY');
    // $date_header3 = $date_cell1[$start_col];
    // $start_col++;
    // $objPHPExcel->getActiveSheet()->mergeCells($date_header1 . '7:' . $date_header3 . '7');
    $objPHPExcel->getActiveSheet()->setCellValue($date_header1 . "7", 'Total');

    // -----------------------------------------------------------------------------------
    $start_col = 0;
    $start_row = 9;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $HptName);
    $start_col = 1;
    $start_row = 9;
    for ($q = 0; $q < $countitem; $q++) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $itemName[$q]);
      $start_row++;
    }
    $start_row = 9;
    $r = 2;
    // ถึงตรงนี้
    for ($q = 0; $q < $countitem; $q++) {

      $cnt = 0;

      // ทำค่าเป็น 0
      for ($dayx = 0; $dayx < $count; $dayx++) {
        $ISSUE_loop[$dayx] = 0;
        $Date_chk[$dayx] = 0;
      }
      $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE,
                                      DATE(sc.complete_date) AS Date_chk
                        FROM report_sc 
                        INNER JOIN department dpm ON dpm.DepCode = report_sc.DepCode
                        INNER JOIN shelfcount sc ON sc.DocNo = report_sc.DocNo
                        WHERE  DATE(sc.complete_date) IN (";
      for ($day = 0; $day < $count; $day++) {
        $data .= " '$date[$day]' ,";
      }
      $data = rtrim($data, ' ,');
      $data .= " )
                        AND sc.isStatus <> 9 
                        AND sc.isStatus <> 1 
                        AND sc.isStatus <> 0
                        AND dpm.HptCode = '$HptCode' 
                        AND sc.SiteCode = '$HptCode' 
                        $search_dep
                        AND report_sc.itemcode = '$itemCode[$q]' 
                        AND report_sc.TotalQty <> 0 
                        GROUP BY DATE(sc.complete_date) ";

      $meQuery = mysqli_query($conn, $data);

      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ISSUE_loop[$cnt] =  $Result["ISSUE"];
        $Date_chk[$cnt] =  $Result["Date_chk"];
        $cnt++;
      }

      $ISSUE_SUM = 0;
      $x = 0;

      //  Loop นำค่าใส่ช่อง
      foreach ($date as $key => $val) {
        if ($Date_chk[$x]  == $val) {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_loop[$x]);
          $r++;
          $ISSUE_SUM += $ISSUE_loop[$x];
          $x++;
        } else {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
          $r++;
        }
      }

      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_SUM);
      $ISSUE_SUM = 0;
      $r = 2;
      $start_row++;
    }

    $r = 2;
    // =========================
    $cnt = 0;
    for ($dayx = 0; $dayx < $count; $dayx++) {
      $ISSUE_loop[$dayx] = 0;
      $Date_chk[$dayx] = 0;
    }

    $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE,
                                    DATE(sc.complete_date) AS Date_chk
                      FROM report_sc 
                      INNER JOIN department dpm ON dpm.DepCode = report_sc.DepCode
                      INNER JOIN shelfcount sc ON sc.DocNo = report_sc.DocNo
                      WHERE  DATE(sc.complete_date) IN (";
    for ($day = 0; $day < $count; $day++) {

      $data .= " '$date[$day]' ,";
    }
    $data = rtrim($data, ' ,');
    $data .= " )
                      AND sc.isStatus <> 9 
                      AND sc.isStatus <> 1 
                      AND sc.isStatus <> 0
                      AND dpm.HptCode = '$HptCode' 
                      AND sc.SiteCode = '$HptCode' 
                      $search_dep 
                      $search_itemfromweb
                      AND report_sc.TotalQty <> 0 
                    GROUP BY DATE(sc.complete_date)";
    echo $data;
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ISSUE_loop[$cnt] =  $Result["ISSUE"];
      $Date_chk[$cnt] =  $Result["Date_chk"];
      $cnt++;
    }

    $TotalISSUE = 0;
    $x = 0;

    //  Loop นำค่าใส่ช่อง Total
    foreach ($date as $key => $val) {
      if ($Date_chk[$x]  == $val) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_loop[$x]);
        $r++;
        $TotalISSUE += $ISSUE_loop[$x];
        $x++;
      } else {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
        $r++;
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalISSUE);
    $rrrr = 1;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$rrrr] . $start_row, 'Total');
    $TotalISSUE = 0;

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

    $r1 = $r;
    $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle("A5:" . $date_cell1[$r] . "8")->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($date_cell1[2] . $start_row . ":" . $date_cell1[$r] . $start_row);
    $objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray($HEAD);
    // $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode('#,##0');


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
    // Rename worksheet4

    $start_row = $start_row - 1;

    foreach (range('9', $start_row) as $column) {
      $objPHPExcel->getActiveSheet()->getRowDimension($column)->setOutlineLevel(1);
      $objPHPExcel->getActiveSheet()->getRowDimension($column)->setVisible(false);
      $objPHPExcel->getActiveSheet()->getRowDimension($column)->setCollapsed(true);
    }


    $objPHPExcel->getActiveSheet()->setTitle($HptName);
    $objPHPExcel->createSheet();
    $itemName = [];
    $itemCode = [];
    $PAR = [];
    $TotalISSUE = 0;
    $TotalShort = 0;
    $TotalOver = 0;
    $ISSUE = 0;
    $Short = 0;
    $Over = 0;
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  }
} else if ($type == 'Detail') {
  $DepName = 'ทุกแผนก';
  $query = "SELECT
      department.DepName
      FROM
      department
      WHERE
      department.DepCode = '$DepCode'
      AND department.HptCode = '$HptCode' ";

  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepName = $Result["DepName"];
  }
  $sheet_count = sizeof($HptCode);
  for ($sheet = 0; $sheet < $sheet_count; $sheet++) {
    $objPHPExcel->setActiveSheetIndex($sheet)
      ->setCellValue('A7',  'BU NAME')
      ->setCellValue('B7',  'ITEM NAME');
    // -----------------------------------------------------------------------------------
    $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
    $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Report Usage Detail' . '  ' . '(' . $DepName . ')');
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
    $objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
    $objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
    $objPHPExcel->getActiveSheet()->mergeCells('B7:B8');

    // -----------------------------------------------------------------------------------

    $query = "SELECT
       site.HptName
       FROM
       site
       WHERE
       site.HptCode = '$HptCode'  ";
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('A5', $Result["HptName"]);
      $HptName = $Result["HptName"];
      $HptName = str_replace("/", " ", $HptName);
    }

    // -----------------------------------------------------------------------------------

    if ($DepCode <> 'ALL') {
      $search_dep = " AND report_sc.DepCode = '$DepCode' ";
      $search_item = " AND par_item_stock.DepCode = '$DepCode' ";
    } else {
      $search_dep = "";
      $search_item = "";
    }
    if ($itemfromweb == '0') {
      $search_itemfromweb = " ";
      $search_itemfromweb_itemcode = " ";
    } else {
      $search_itemfromweb = " AND report_sc.ItemCode = '$itemfromweb' ";
      $search_itemfromweb_itemcode = " AND item.ItemCode = '$itemfromweb' ";
    }
    $item = "SELECT
              item.ItemCode,
              item.ItemName 
            FROM
              item 
            INNER JOIN par_item_stock ON par_item_stock.ItemCode = item.ItemCode
            WHERE
            par_item_stock.HptCode = '$HptCode'
            AND item.IsClean = 0
            AND item.IsDirtyBag = 0
            $search_itemfromweb_itemcode
            $search_item
            AND item.IsActive = 1 
            GROUP BY item.ItemCode
            ORDER BY ItemName ";

    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $itemName[] =  $Result["ItemName"];
      $itemCode[] =  $Result["ItemCode"];
    }


    $start_row = 9;
    $start_col = 2;
    $start_date = 1;
    $start_itemcode = 1;
    // -----------------------------------------------------------------------------------

    foreach ($date as $key => $value) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'ISSUE QTY');
      $date_header1 = $date_cell1[$start_col];
      $start_col++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'SHORT QTY');
      $date_header2 = $date_cell1[$start_col];
      $start_col++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'OVER QTY');
      $date_header3 = $date_cell1[$start_col];
      $start_col++;
      $objPHPExcel->getActiveSheet()->mergeCells($date_header1 . '7:' . $date_header3 . '7');
      $objPHPExcel->getActiveSheet()->setCellValue($date_header1 . "7", $DateShow[$key]);
      $date_header1 = '';
      $date_header2 = '';
      $date_header3 = '';
    }


    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'ISSUE QTY');
    $date_header1 = $date_cell1[$start_col];
    $start_col++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'SHORT QTY');
    $date_header2 = $date_cell1[$start_col];
    $start_col++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'OVER QTY');
    $date_header3 = $date_cell1[$start_col];
    $start_col++;
    $objPHPExcel->getActiveSheet()->mergeCells($date_header1 . '7:' . $date_header3 . '7');
    $objPHPExcel->getActiveSheet()->setCellValue($date_header1 . "7", 'Total');

    // -----------------------------------------------------------------------------------

    $start_col = 0;
    $start_row = 9;
    $start_merge = 9;
    $start_col_dep = 1;
    foreach ($itemCode as $keyItem => $valueItem) {
      // ========
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $itemName[$keyItem]);
      // ========
      $Select_Dep = "SELECT department.DepName , department.DepCode
                       FROM department
                       INNER JOIN par_item_stock ON par_item_stock.DepCode = department.DepCode 
                       WHERE department.HptCode = '$HptCode'
                       AND par_item_stock.HptCode = '$HptCode'
                      --  AND department.IsDefault = 0
                       AND par_item_stock.ItemCode = '$valueItem'
                       $search_item ";
      $meQuery = mysqli_query($conn, $Select_Dep);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DepCode_show = $Result["DepCode"];
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col_dep] . $start_row, $Result["DepName"]);
        $cnt = 0;


        $r = 2;
        // // ทำค่าเป็น 0
        for ($dayx = 0; $dayx < $count; $dayx++) {
          $ISSUE_loop[$dayx] = 0;
          $Short_loop[$dayx] = 0;
          $Over_loop[$dayx] = 0;
          $Date_chk[$dayx] = 0;
        }
        $data = " SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE,
                                 COALESCE( SUM(report_sc.Short),'0') as  Short, 
                                 COALESCE(SUM(report_sc.Over),'0') as  Over ,
                                DATE(sc.complete_date) AS Date_chk
                              FROM report_sc 
                              INNER JOIN (SELECT DepCode , HptCode  FROM department) AS dpm  ON dpm.DepCode = report_sc.DepCode
                              INNER JOIN (SELECT isStatus , complete_date , SiteCode , DocNo  FROM shelfcount) AS sc ON sc.DocNo = report_sc.DocNo
                              WHERE  DATE(sc.complete_date) IN (";
        for ($day = 0; $day < $count; $day++) {
          $data .= " '$date[$day]' ,";
        }
        $data = rtrim($data, ' ,');
        $data .= " )
                              AND sc.isStatus <> 9 
                              AND sc.isStatus <> 1 
                              AND sc.isStatus <> 0
                              AND sc.SiteCode = '$HptCode' 
                              AND dpm.HptCode = '$HptCode' 
                              AND report_sc.DepCode = '$DepCode_show'
                              AND report_sc.itemcode = '$valueItem' 
                              AND report_sc.TotalQty <> 0 
                              GROUP BY DATE(sc.complete_date) ";
        $meQuery_dep = mysqli_query($conn, $data);

        while ($Result = mysqli_fetch_assoc($meQuery_dep)) {
          $ISSUE_loop[$cnt] =  $Result["ISSUE"];
          $Short_loop[$cnt] =  $Result["Short"];
          $Over_loop[$cnt]  =  $Result["Over"];
          $Date_chk[$cnt]   =  $Result["Date_chk"];
          $cnt++;
        }

        $ISSUE_SUM   = 0;
        $Short_SUM   = 0;
        $Over_SUM    = 0;
        $x = 0;

        //  Loop นำค่าใส่ช่อง
        foreach ($date as $key => $val) {
          if ($Date_chk[$x]  == $val) {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_loop[$x]);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short_loop[$x]);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over_loop[$x]);
            $r++;
            $ISSUE_SUM += $ISSUE_loop[$x];
            $Short_SUM += $Short_loop[$x];
            $Over_SUM += $Over_loop[$x];
            $x++;
          } else {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
            $r++;
          }
        }

        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_SUM);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short_SUM);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over_SUM);
        $ISSUE_SUM = 0;
        $Short_SUM   = 0;
        $Over_SUM    = 0;
        $start_row++;
      }
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col_dep] . $start_row, 'Total');


      // ===========================================================================================
      foreach ($date as $key => $value) {
        $ISSUE_total_loop[$key] = 0;
        $Short_total_loop[$key] = 0;
        $Over_total_loop[$key] = 0;
        $Date_total_chk[$key] = 0;
      }

      $cnt = 0;

      $data_total = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE,
                                  COALESCE( SUM(report_sc.Short),'0') as  Short, 
                                  COALESCE(SUM(report_sc.Over),'0') as  Over ,
                                  DATE(sc.complete_date) AS Date_chk
                    FROM report_sc 
                    INNER JOIN (SELECT DepCode , HptCode  FROM department) AS dpm  ON dpm.DepCode = report_sc.DepCode
                    INNER JOIN (SELECT isStatus , complete_date , SiteCode , DocNo  FROM shelfcount) AS sc ON sc.DocNo = report_sc.DocNo
                    WHERE  DATE(sc.complete_date) IN (";
      for ($day = 0; $day < $count; $day++) {
        $data_total .= " '$date[$day]' ,";
      }
      $data_total = rtrim($data_total, ' ,');
      $data_total .= " )
                    AND sc.isStatus <> 9 
                    AND sc.isStatus <> 1 
                    AND sc.isStatus <> 0
                    AND sc.SiteCode = '$HptCode' 
                    AND dpm.HptCode = '$HptCode' 
                    $search_dep
                    AND report_sc.itemcode = '$valueItem' 
                    AND report_sc.TotalQty <> 0 
                    GROUP BY DATE(sc.complete_date) ";

      $meQuery_dep_total = mysqli_query($conn, $data_total);
      while ($Result = mysqli_fetch_assoc($meQuery_dep_total)) {
        $ISSUE_total_loop[$cnt] =  $Result["ISSUE"];
        $Short_total_loop[$cnt] =  $Result["Short"];
        $Over_total_loop[$cnt] =  $Result["Over"];
        $Date_total_chk[$cnt] =  $Result["Date_chk"];
        $cnt++;
      }

      $ISSUE_SUM_total   = 0;
      $Short_SUM_total   = 0;
      $Over_SUM_total    = 0;
      $x = 0;
      $r = 2;
      //  Loop นำค่าใส่ช่อง
      foreach ($date as $key => $val) {
        if ($Date_total_chk[$x]  == $val) {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_total_loop[$x]);
          $r++;
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short_total_loop[$x]);
          $r++;
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over_total_loop[$x]);
          $r++;
          $ISSUE_SUM_total += $ISSUE_total_loop[$x];
          $Short_SUM_total += $Short_total_loop[$x];
          $Over_SUM_total += $Over_total_loop[$x];
          $x++;
        } else {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
          $r++;
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
          $r++;
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
          $r++;
        }
      }

      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_SUM_total);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short_SUM_total);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over_SUM_total);
      $ISSUE_SUM_total = 0;
      $Short_SUM_total   = 0;
      $Over_SUM_total    = 0;

      // ===========================================================================================
      $styleNormal2 = array(
        'alignment' => array(
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
        )
      );
      $objPHPExcel->getActiveSheet()->getStyle("A" . $start_merge . ":" .  "A" . $start_row)->applyFromArray($styleNormal2);
      $objPHPExcel->getActiveSheet()->mergeCells("A" . $start_merge . ":" .  "A" . $start_row);
      $start_rowx = $start_row - 1;
      foreach (range($start_merge, $start_rowx) as $column) {
        $objPHPExcel->getActiveSheet()->getRowDimension($column)->setOutlineLevel(1);
        $objPHPExcel->getActiveSheet()->getRowDimension($column)->setVisible(false);
        $objPHPExcel->getActiveSheet()->getRowDimension($column)->setCollapsed(true);
      }

      // echo 'เรึ่ม' . $start_merge . 'จบ' . $start_row . "<br>" ;
      $start_row++;
      $start_merge =  $start_row;
    }

    //  =============================================================







    // total

    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col_dep] . $start_row, 'Total');

    for ($dayx = 0; $dayx < $count; $dayx++) {
      $ISSUE_total_sum_loop[$dayx] = 0;
      $Short_total_sum_loop[$dayx] = 0;
      $Over_total_sum_loop[$dayx] = 0;
      $Date_total_sum_chk[$dayx] = 0;
    }
    $cnt = 0;

    $data_total = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE,
                          COALESCE( SUM(report_sc.Short),'0') as  Short, 
                          COALESCE(SUM(report_sc.Over),'0') as  Over ,
                          DATE(sc.complete_date) AS Date_chk
            FROM report_sc 
            INNER JOIN department dpm ON dpm.DepCode = report_sc.DepCode
            INNER JOIN shelfcount sc ON sc.DocNo = report_sc.DocNo
            WHERE  DATE(sc.complete_date) IN (";
    for ($day = 0; $day < $count; $day++) {
      $data_total .= " '$date[$day]' ,";
    }
    $data_total = rtrim($data_total, ' ,');
    $data_total .= " )
            AND sc.isStatus <> 9 
            AND sc.isStatus <> 1 
            AND sc.isStatus <> 0
            AND sc.SiteCode = '$HptCode' 
            AND dpm.HptCode = '$HptCode' 
            $search_dep
            $search_itemfromweb
            AND report_sc.TotalQty <> 0 
            GROUP BY DATE(sc.complete_date) ";

    echo $data_total;
    $meQuery_dep_total = mysqli_query($conn, $data_total);
    while ($Result = mysqli_fetch_assoc($meQuery_dep_total)) {
      $ISSUE_total_sum_loop[$cnt] =  $Result["ISSUE"];
      $Short_total_sum_loop[$cnt] =  $Result["Short"];
      $Over_total_sum_loop[$cnt] =  $Result["Over"];
      $Date_total_sum_chk[$cnt] =  $Result["Date_chk"];
      $cnt++;
    }

    $ISSUE_SUM_total   = 0;
    $Short_SUM_total   = 0;
    $Over_SUM_total    = 0;
    $x = 0;
    $r = 2;
    //  Loop นำค่าใส่ช่อง
    foreach ($date as $key => $val) {
      if ($Date_total_sum_chk[$x]  == $val) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_total_sum_loop[$x]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short_total_sum_loop[$x]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over_total_sum_loop[$x]);
        $r++;
        $ISSUE_SUM_total += $ISSUE_total_sum_loop[$x];
        $Short_SUM_total += $Short_total_sum_loop[$x];
        $Over_SUM_total += $Over_total_sum_loop[$x];
        $x++;
      } else {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
        $r++;
      }
    }

    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE_SUM_total);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Short_SUM_total);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Over_SUM_total);
    $ISSUE_SUM_total = 0;
    $Short_SUM_total   = 0;
    $Over_SUM_total    = 0;

    // ===========================================================================================

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
    $r1 = $r - 2;
    $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
    $objPHPExcel->getActiveSheet()->getStyle("A5:" . $date_cell1[$r] . "8")->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($date_cell1[2] . $start_row . ":" . $date_cell1[$r] . $start_row);
    $objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray($HEAD);
    // $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode('#,##0');


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
    // Rename worksheet4

    $start_row = $start_row - 1;

    // foreach (range('9', $start_row) as $column) 
    // {
    //   $objPHPExcel->getActiveSheet()->getRowDimension($column)->setOutlineLevel(1);
    //   $objPHPExcel->getActiveSheet()->getRowDimension($column)->setVisible(false);
    //   $objPHPExcel->getActiveSheet()->getRowDimension($column)->setCollapsed(true);
    // }


    $objPHPExcel->getActiveSheet()->setTitle($HptName);
    $objPHPExcel->createSheet();
    $itemName = [];
    $itemCode = [];
    $PAR = [];
    $TotalISSUE = 0;
    $TotalShort = 0;
    $TotalOver = 0;
    $ISSUE = 0;
    $Short = 0;
    $Over = 0;
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  }
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
$file_name = "Report_Usage_" . $type . "_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
