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
$DepCodeCome = $data[7];
// $DepCodeCome = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$itemCode = [];
$itemName = [];
$DepCode = [];
$Weight = [];
$DateShow = [];
$ISSUE = 0;
$TOTAL_LASTWEIGHT = 0;
$TotalISSUE = 0;
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
echo "<pre>";
print_r($DepCode);
echo "</pre>";
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
if ($DepCodeCome == '0') {
  $query = "SELECT
    department.DepCode
    FROM
    department
    INNER JOIN shelfcount ON shelfcount.DepCode = department.DepCode
    $where AND shelfcount.isStatus <> 9 AND department.HptCode  = '$HptCode'
    GROUP BY shelfcount.DepCode ORDER BY shelfcount.DepCode  ASC
                ";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCode[] = $Result["DepCode"];
  }
} else {
  $DepCode[] = $DepCodeCome;
}

$sheet_count = sizeof($DepCode);
for ($sheet = 0; $sheet < $sheet_count; $sheet++) {
  $objPHPExcel->setActiveSheetIndex($sheet)
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
  $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
  $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
  // -----------------------------------------------------------------------------------

  // -----------------------------------------------------------------------------------
  $query = "SELECT
  department.DepName
  FROM
  department
  WHERE
  department.DepCode = '$DepCode[$sheet]'
            ";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $Result["DepName"]);
    $DepName = $Result["DepName"];
  }
  // -----------------------------------------------------------------------------------
  $item = "SELECT
  item.itemname,
  item.itemcode
  FROM
  shelfcount_detail
  INNER JOIN item ON item.itemcode = shelfcount_detail.itemcode
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  WHERE
    shelfcount.isStatus <> 9
    AND shelfcount.DepCode = '$DepCode[$sheet]'
    AND shelfcount_detail.TotalQty <> 0
    GROUP BY  item.itemcode ORDER BY item.ItemName
              ";
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
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Weight ( นนไอเทม * จำนวน qty total )');
  // -----------------------------------------------------------------------------------
  for ($i = 0; $i < $countitem; $i++) {
    $item = "SELECT
    shelfcount_detail.ParQty AS  ParQty
    FROM
    shelfcount_detail
    INNER JOIN  shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo 
    INNER JOIN  item ON item.itemcode = shelfcount_detail.itemcode 
                    WHERE
                    shelfcount_detail.itemcode = '$itemCode[$i]'
                    AND shelfcount.DepCode = '$DepCode[$sheet]'
                    AND shelfcount.isStatus <> 9
                    GROUP BY  shelfcount_detail.itemcode  ";

    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $DepName);
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $itemName[$i]);
      $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
    } $item = "SELECT
    item.Weight AS Weight 
    FROM
    shelfcount_detail
    INNER JOIN  shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo 
    INNER JOIN  item ON item.itemcode = shelfcount_detail.itemcode 
                    WHERE
                    shelfcount_detail.itemcode = '$itemCode[$i]'
                    AND shelfcount.DepCode = '$DepCode[$sheet]'
                    AND shelfcount.isStatus <> 9
                    GROUP BY  shelfcount_detail.itemcode  ";

    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["Weight"]);
    } $item = "SELECT
    category_price.Price AS Price
    FROM
    shelfcount_detail
    INNER JOIN  shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo 
    INNER JOIN  item ON item.itemcode = shelfcount_detail.itemcode 
    LEFT JOIN  category_price ON category_price.CategoryCode = item.CategoryCode
                    WHERE
                    shelfcount_detail.itemcode = '$itemCode[$i]'
                    AND shelfcount.DepCode = '$DepCode[$sheet]'
                    AND shelfcount.isStatus <> 9
                    GROUP BY  shelfcount_detail.itemcode  ";

    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $Result["Price"]);
      $start_row++;
      $Weight[] = $Result["Weight"];
    }
  }
  $start_row = 9;
  $start_col = 5;
  for ($i = 0; $i < $countitem; $i++) {
    for ($j = 0; $j < $count; $j++) {
      $item = "SELECT
            COALESCE(sum(shelfcount_detail.TotalQty),'0') as  TotalQty
            FROM
            shelfcount_detail
            INNER JOIN shelfcount ON shelfcount.DocNo  = shelfcount_detail.DocNo
            WHERE
            shelfcount_detail.itemcode = '$itemCode[$i]'
            AND shelfcount.DocDate = '$date[$j]'
            AND shelfcount.DepCode = '$DepCode[$sheet]'
            AND shelfcount.isStatus <> 9
            GROUP BY  shelfcount_detail.itemcode 
                        ";
      //  echo $item;
      $meQuery = mysqli_query($conn, $item);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . $start_row, $Result["TotalQty"]);
      }
      $start_col++;
    }
    $lastcell = $start_col;
    $start_col = 5;
    $start_row++;
  }
  $start_row = 9;
  $r = 5;
  $w = 0;
  for ($q = 0; $q < $countitem; $q++) {
    for ($day = 0; $day < $count; $day++) {
      $data = "SELECT COALESCE(SUM(shelfcount_detail.TotalQty),'0') as  ISSUE,
     COALESCE(sum(shelfcount_detail.Weight),'0') as  Weight
    FROM shelfcount 
    INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo 
    WHERE  DATE(shelfcount.DocDate)  ='$date[$day]'  
    AND shelfcount.isStatus <> 9
    AND shelfcount.DepCode = '$DepCode[$sheet]'  
    AND shelfcount_detail.itemcode = '$itemCode[$q]'  ";
      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["ISSUE"]);
        $r++;
        $ISSUE += $Result["ISSUE"];
      }
    }
    $TOTAL_WEIGHT = $ISSUE * $Weight[$w];
    $TOTAL_LASTWEIGHT += $TOTAL_WEIGHT;

    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_WEIGHT);
    $ISSUE = 0;
    $TOTAL_WEIGHT = 0;
    $r = 5;
    $w++;
    $start_row++;
  }

  $r = 5;
  for ($day = 0; $day < $count; $day++) {
    $data = "SELECT COALESCE(SUM(shelfcount_detail.TotalQty),'0') as  ISSUE
  FROM shelfcount 
  INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo 
  WHERE  DATE(shelfcount.DocDate)  ='$date[$day]'  
  AND shelfcount.isStatus <> 9
  AND shelfcount.DepCode = '$DepCode[$sheet]'  
  ";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["ISSUE"]);
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
  $objPHPExcel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($HEAD);
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
  // Rename worksheet
  // $objPHPExcel->getActiveSheet()->setTitle('5');
  $objPHPExcel->getActiveSheet()->setTitle($DepCode[$sheet]);
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
