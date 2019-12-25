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
$DepCode = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$GroupCodeCome = $data[11];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$status_group == 1;
$DepCode = [];
$DepName = [];
$GroupCode = [];
$GroupName = [];
$DateShow = [];
$sumdayTotalqty = 0;
$sumdayWeight = 0;
$TotaldayTotalqty = 0;
$TotaldayWeight = 0;
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}

if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (damage.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (damage.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE damage.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (damage.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE DATE(damage.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
$datetime = new DatetimeTH();
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
// echo "<pre>";
// print_r($date_cell1);
// echo "</pre>"; 
// -----------------------------------------------------------------------------------
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
  } elseif ($format = 3) {
    if ($language == 'th') {
      $date1 = $date1 - 543;
    }
    $year = $date1;
    $monthh = 12;
    for ($i = 1; $i <= $monthh; $i++) {
      $count = 12;
      $datequery =  $year . '-' . $i;
      $dateshow = '-' . $i . '-' . $year;
      $date[] = $datequery;
      $datetime = new DatetimeTH();
      if ($language == 'th') {
        $date_header1 = $datetime->getTHmonthFromnum($i);
      } else {
        $date_header1 =  $datetime->getmonthFromnum($i);
      }
      $DateShow[] = $date_header1;
    }
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
  if ($language ==  'th') {
    $y = $year1 + 543;
  } else {
    $y = $year1;
  }
  $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
  $datequery =  $year1 . '-' . $date1 . '-';
  $dateshow = '-' . $date1 . '-' . $y;
  for ($i = 0; $i < $count; $i++) {
    $date[] = $datequery . $day;
    $DateShow[] = $day . $dateshow;
    $day++;
  }
} elseif ($chk == 'monthbetween') {
  list($year, $month, $day) = explode('-', $betweendate2);
  $betweendate2 = $year . "-" . $month . "-" . $day;
  $period = new DatePeriod(
    new DateTime($betweendate1),
    new DateInterval('P1M'),
    new DateTime($betweendate2)
  );
  foreach ($period as $key => $value) {
    $date[] = $value->format('Y-m');
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $value->format('Y') + 543;
      $date_header1 = $datetime->getTHmonthFromnum($value->format('m')) . "  ($year)  ";
    } else {
      $year = $value->format('Y');
      $date_header1 = $datetime->getTHmonthFromnum($value->format('m')) . "  ($year)  ";
    }
    $DateShow[] = $date_header1;
  }
  $count = sizeof($date);
}
// echo "<pre>";
// print_r($date);
// echo "</pre>";
// -----------------------------------------------------------------------------------
$status_group = 1;
// -----------------------------------------------------------------------------------
$objPHPExcel->setActiveSheetIndex()
  ->setCellValue('A8',  $array2['factory'][$language])
  ->setCellValue('B8',  $array2['itemname'][$language]);
// Write data from MySQL result
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r24'][$language]);
$objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'รายละเอียด');
$objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
// -----------------------------------------------------------------------------------
$query = "  SELECT
              item.ItemCode,
              item.ItemName,
              factory.$FacName
              FROM
              damage
              INNER JOIN damage_detail ON damage.DocNo = damage_detail.DocNo
              LEFT JOIN item ON damage_detail.ItemCode = item.ItemCode
              INNER JOIN factory ON factory.FacCode = damage.FacCode
              $where
              AND damage.isStatus <> 9 AND damage.isStatus <> 0
              AND damage.FacCode = '$FacCode'
              GROUP BY damage_detail.ItemCode
            ";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($status_group == 1) {
    $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result[$FacName]);
  }
  $i++;
  $ItemName[] =  $Result["ItemName"];
  $ItemCode[] =  $Result["ItemCode"];
  $status_group = 0;
}
// echo "<pre>";
// print_r($ItemName);
// echo "</pre>";
// echo "<pre>";
// print_r($ItemCode);
// echo "</pre>";
// -----------------------------------------------------------------------------------
$r = 2;
$d = 1;
$rows = 9;
for ($row = 0; $row < $count; $row++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'จำนวนชิ้น');
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'นน.(Kg)');
  $r++;
}
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'จำนวนชิ้น');
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'นน.(Kg)');
$r++;
// -----------------------------------------------------------------------------------
$r = 2;
$j = 3;
$d = 1;
for ($row = 0; $row < $count; $row++) {
  $objPHPExcel->getActiveSheet()->mergeCells($date_cell1[$r] . '7:' . $date_cell1[$j] . '7');
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '7', $DateShow[$row]);
  $r += 2;
  $j += 2;
  $d++;
}
$objPHPExcel->getActiveSheet()->mergeCells($date_cell1[$r] . '7:' . $date_cell1[$j] . '7');
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '7', "total");
// -----------------------------------------------------------------------------------
$start_row = 9;
$r = 1;
$j = 3;
$lek = 0;
$COUNT_item = SIZEOF($ItemName);
for ($q = 0; $q < $COUNT_item; $q++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ItemName[$lek]);
  $r++;
  for ($day = 0; $day < $count; $day++) {
    $data = "SELECT   COALESCE(SUM(damage_detail.Qty),'0') AS Totalqty,
                      COALESCE(SUM(damage_detail.Weight),'0') AS Weight
                    FROM damage_detail 
                    INNER JOIN damage ON damage.DocNo = damage_detail.DocNo
                    INNER JOIN factory ON factory.Faccode = damage.Faccode
                    INNER JOIN department ON department.DepCode = damage.DepCode
                    INNER JOIN site ON site.HptCode = department.HptCode";
    if ($chk == 'one') {
      if ($format == 1) {
        $data .=   " WHERE  DATE(damage.DocDate)  ='$date[$day]'  AND damage.isStatus <> 9 AND damage.isStatus <> 0";
      } elseif ($format = 3) {
        list($year, $month) = explode('-', $date[$day]);
        $data .=   " WHERE  YEAR(damage.DocDate)  ='$year'  AND MONTH(damage.DocDate)  ='$month' AND damage.isStatus <> 9 AND damage.isStatus <> 0";
      }
    } elseif ($chk == 'between') {
      $data .=   " WHERE  DATE(damage.DocDate)  ='$date[$day]'  AND damage.isStatus <> 9 AND damage.isStatus <> 0";
    } elseif ($chk == 'month') {
      $data .=   " WHERE  DATE(damage.DocDate)  ='$date[$day]'  AND damage.isStatus <> 9 AND damage.isStatus <> 0";
    } elseif ($chk == 'monthbetween') {
      list($year, $month) = explode('-', $date[$day]);
      $data .=   " WHERE  YEAR(damage.DocDate)  ='$year'  AND MONTH(damage.DocDate)  ='$month' AND damage.isStatus <> 9 AND damage.isStatus <> 0";
    }

    $data .= " AND damage.Faccode = '$FacCode'
              AND site.HptCode = '$HptCode' ";

    $data .= " AND damage_detail.ItemCode = '$ItemCode[$lek]' ";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
      $r++;
      $sumdayTotalqty += $Result["Totalqty"];
      $sumdayWeight += $Result["Weight"];
    }
  }
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
  $sumdayTotalqty = 0;
  $sumdayWeight = 0;
  $r = 1;
  $start_row++;
  $lek++;
}
// -----------------------------------------------------------------------------------
$r = 1;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 'total');
$r++;
for ($day = 0; $day < $count; $day++) {
  $data =       "SELECT
              COALESCE(SUM(damage_detail.Qty),'0') AS Totalqty,
                      COALESCE(SUM(damage_detail.Weight),'0') AS Weight
              FROM
              damage_detail
              INNER JOIN damage ON damage.DocNo = damage_detail.DocNo
                    INNER JOIN factory ON factory.Faccode = damage.Faccode
                    INNER JOIN department ON department.DepCode = damage.DepCode
                    INNER JOIN site ON site.HptCode = department.HptCode
                    LEFT JOIN item ON item.itemcode = damage_detail.itemcode";

  if ($chk == 'one') {
    if ($format == 1) {
      $data .=   " WHERE  DATE(damage.DocDate)  ='$date[$day]'  AND damage.isStatus <> 9 AND damage.isStatus <> 0";
    } elseif ($format = 3) {
      list($year, $month) = explode('-', $date[$day]);
      $data .=   " WHERE  YEAR(damage.DocDate)  ='$year'  AND MONTH(damage.DocDate)  ='$month' AND damage.isStatus <> 9 AND damage.isStatus <> 0";
    }
  } elseif ($chk == 'between') {
    $data .=   " WHERE  DATE(damage.DocDate)  ='$date[$day]'  AND damage.isStatus <> 9 AND damage.isStatus <> 0";
  } elseif ($chk == 'month') {
    $data .=   " WHERE  DATE(damage.DocDate)  ='$date[$day]'  AND damage.isStatus <> 9 AND damage.isStatus <> 0";
  } elseif ($chk == 'monthbetween') {
    list($year, $month) = explode('-', $date[$day]);
    $data .=   " WHERE  YEAR(damage.DocDate)  ='$year'  AND MONTH(damage.DocDate)  ='$month' AND damage.isStatus <> 9 AND damage.isStatus <> 0";
  }
  $data .= " AND damage.Faccode = '$FacCode'
             AND site.HptCode = '$HptCode' ";
  $meQuery = mysqli_query($conn, $data);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
    $r++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
    $r++;
    $sumdayTotalqty += $Result["Totalqty"];
    $sumdayWeight += $Result["Weight"];
  }
}
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
// -----------------------------------------------------------------------------------
$A5 = array(
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
    'color' => array('rgb' => 'B9E3E6')
  )
);
$r1 = $r - 1;
$objPHPExcel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($A5);
$objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($fill);
$objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
$objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);
// $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);
foreach (range('A', 'B') as $columnID) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    ->setAutoSize(true);
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
$objPHPExcel->getActiveSheet()->setTitle($array2['r24']['en']);
$objPHPExcel->createSheet();
//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = $array2['r24']['en'] . "_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
