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
$where = [];
$where = '';
$i = 8;
$check = '';
$Qty = 0;
$Weight = 0;
$mainstatus = 0;
if ($language == 'th') {
  $HptName = HptNameTH;
  $FacName = FacNameTH;
} else {
  $HptName = HptName;
  $FacName = FacName;
}

if ($chk == 'one') {
  if ($format == 1) {
    $where =   "WHERE DATE (dirty.Docdate) = DATE('$date1')";
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    $where = "WHERE  year (dirty.DocDate) LIKE '%$date1%'";
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
  $where =   "WHERE dirty.Docdate BETWEEN '$date1' AND '$date2'";
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
  $where =   "WHERE month (dirty.Docdate) = " . $date1;
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
  $where =   "WHERE DATE(dirty.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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
$objPHPExcel->setActiveSheetIndex()
  ->setCellValue('A7',  $array2['itemname'][$language])
  ->setCellValue('B7',  $array2['amount'][$language])
  ->setCellValue('C7',  $array2['department1'][$language])
  ->setCellValue('D7',  $array2['weight_kg'][$language]);
// Write data from MySQL result
$objPHPExcel->getActiveSheet()->setCellValue('D1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('C5', $array2['r1'][$language]);
$Sql = "SELECT
        factory.$FacName,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
        INNER JOIN department ON dirty_detail.DepCode = department.DepCode
        INNER JOIN site ON site.hptcode =department.hptcode
        $where
        AND  factory.FacCode = '$FacCode'
        AND  site.HptCode = '$HptCode'
        AND dirty.isStatus <> 9
        AND dirty.isStatus <> 0
        GROUP BY factory.$FacName
        ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $facname = $Result[$FacName];
}
$objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['factory'][$language] . " : " . $facname);
$objPHPExcel->getActiveSheet()->setCellValue('D6', $date_header);
$query = "SELECT
item.ItemName,
dirty_detail.Weight,
department.DepName,
SUM(dirty_detail.Qty) AS Qty,
COALESCE(dirty_detail.RequestName,'-') as RequestName
FROM
dirty
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
LEFT JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND dirty.isStatus <> 9
AND dirty.isStatus <> 0
GROUP BY item.ItemName,department.DepName,dirty_detail.RequestName
ORDER BY item.ItemName , department.DepName ASC";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result['ItemName'] = $Result['RequestName'];
  }
  if ($Result["ItemName"] == $check) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "");
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($Result["Qty"]));
  } else {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $Result["ItemName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($Result["Qty"]));
    $check = $Result["ItemName"];
  }
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, trim($Result["DepName"]));
  $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, number_format($Result["Weight"], 2));
  $i++;
  $Qty += $Result["Qty"];
  $Weight += $Result["Weight"];
}
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $array2['total'][$language]);
$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($Qty));
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, " ");
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, number_format($Weight, 2));
$row_sum = $i;
$i += 1;
$queryy = "SELECT
item.ItemName,
SUM(dirty_detail.Qty) AS Qty,
COALESCE(dirty_detail.RequestName,'-') as RequestName
FROM
dirty
INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
INNER JOIN department ON dirty_detail.DepCode = department.DepCode
INNER JOIN factory ON dirty.FacCode = factory.FacCode
LEFT  JOIN item ON item.itemcode = dirty_detail.itemcode
$where
AND factory.FacCode = '$FacCode'
AND department.HptCode = '$HptCode'
AND dirty.isStatus <> 9
AND dirty.isStatus <> 0
GROUP BY item.ItemName,dirty_detail.RequestName
ORDER BY item.ItemName , department.DepName ASC
          ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result['ItemName'] = $Result['RequestName'];
  }
  $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $Result["ItemName"]);
  $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $Result["Qty"]);
  $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, " ");
  $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, "");
  $i++;
}
$cols = array('A', 'B', 'C', 'D');
$width = array(10, 10, 45, 20);
for ($j = 0; $j < count($cols); $j++) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
}
$header = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$item = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  ),
  'borders' => array(
    'borders' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      'color' => array('rgb' => '000000')
    )
  )
);
$Qty = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$department = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$Weight = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    // 'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
    'name'  => 'THSarabun'
  )
);
$sum = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 10,
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
$r3 = array(
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
$datetime = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true,
    // 'color' => array('rgb' => 'FF0000'),
    'size'  => 8,
    'name'  => 'THSarabun'
  )
);
$objPHPExcel->getActiveSheet()->getStyle("A7:D7")->applyFromArray($header)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);;
$objPHPExcel->getActiveSheet()->getStyle("A2:A" . $i)->applyFromArray($item)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("B2:B" . $i)->applyFromArray($Qty)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
$objPHPExcel->getActiveSheet()->getStyle("C2:C" . $i)->applyFromArray($department)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("D2:D" . $i)->applyFromArray($Weight)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("D" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("A" . $row_sum . ":B" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$i--;
$objPHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray($r3)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle("D1")->applyFromArray($datetime)->getNumberFormat();
$objPHPExcel->getActiveSheet()->getStyle('A7:D' . $i)->applyFromArray($styleArray);
// $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);
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
// Rename worksheet
$objPHPExcel->getActiveSheet(0)->setTitle('Report_Dirty_Linen_Weight');
$objPHPExcel->createSheet();
$mainstatus = 1;


// if ($mainstatus == 1) {
//   if ($chk == 'between') {
//     list($year, $month, $day) = explode('-', $date2);
//     $date2 = $year . "-" . $month . "-" . $day;
//     $period = new DatePeriod(
//       new DateTime($date1),
//       new DateInterval('P1D'),
//       new DateTime($date2)
//     );
//     foreach ($period as $key => $value) {
//       $date[] = $value->format('Y-m-d');
//     }
//     $count = count($date);
//     for ($i = 0; $i < $count; $i++) {
//       $date1 = $date[$i];
//       list($y, $m, $d) = explode('-', $date1);
//       if ($language ==  'th') {
//         $y = $y + 543;
//       }
//       $date1 = $d . '-' . $m . '-' . $y;
//       $DateShow[] = $date1;
//     }
//     list($y, $m, $d) = explode('-', $date2);
//     if ($language ==  'th') {
//       $y = $y + 543;
//     }
//     $date1 = $d . '-' . $m . '-' . $y;
//     $DateShow[] = $date1;
//     $date[] = $date2;
//   } elseif ($chk == 'month') {
//     $day = 1;
//     $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
//     $datequery =  $year1 . '-' . $date1 . '-';
//     $dateshow = '-' . $date1 . '-' . $year1;
//     for ($i = 0; $i < $count; $i++) {
//       $date[] = $datequery . $day;
//       $DateShow[] = $day . $dateshow;
//       $day++;
//     }
//   } elseif ($chk == 'monthbetween') {
//     $where =   "WHERE DATE(dirty.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
//   }
//   print_r($date);
//   echo $sheet_count = sizeof($date);
//   $s = 1;
//   for ($sheet = 0; $sheet < $sheet_count; $sheet++) {
//     $Qty = 0;
//     $Weight = 0;
//     $i = 8;
//     $check  = '';
//     $objPHPExcel->setActiveSheetIndex($s)
//       ->setCellValue('A7',  $array2['itemname'][$language])
//       ->setCellValue('B7',  $array2['amount'][$language])
//       ->setCellValue('C7',  $array2['department1'][$language])
//       ->setCellValue('D7',  $array2['weight_kg'][$language]);
//     $objPHPExcel->getActiveSheet()->setCellValue('D1', $array2['printdate'][$language] . $printdate);
//     $objPHPExcel->getActiveSheet()->setCellValue('C5', $array2['r1'][$language]);
//     $Sql = "SELECT
//         factory.$FacName,
//         dirty.DocDate
//         FROM
//         dirty
//         INNER JOIN factory ON factory.FacCode =dirty.FacCode
//         INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
//         INNER JOIN department ON dirty_detail.DepCode = department.DepCode
//         INNER JOIN site ON site.hptcode =department.hptcode
//         WHERE  factory.FacCode = '$FacCode'
//         AND  site.HptCode = '$HptCode'
//         AND dirty.isStatus <> 9
//         AND dirty.isStatus <> 0
//         GROUP BY factory.$FacName
//         ";
//     $meQuery = mysqli_query($conn, $Sql);
//     while ($Result = mysqli_fetch_assoc($meQuery)) {
//       $facname = $Result[$FacName];
//     }
//     $objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['factory'][$language] . " : " . $facname);
//     $objPHPExcel->getActiveSheet()->setCellValue('D6', $date_header);
//     $query = "SELECT
//   item.ItemName,
//   dirty_detail.Weight,
//   department.DepName,
//   SUM(dirty_detail.Qty) AS Qty,
//   COALESCE(dirty_detail.RequestName,'-') as RequestName
//   FROM
//   dirty
//   INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
//   INNER JOIN department ON dirty_detail.DepCode = department.DepCode
//   INNER JOIN factory ON dirty.FacCode = factory.FacCode
//   LEFT JOIN item ON item.itemcode = dirty_detail.itemcode";
//       if ($chk == 'between') {
//         $query .= " WHERE DATE (dirty.Docdate) = '" . $date[$sheet] . "'";
//       } elseif ($chk == 'month') {
//         $query .= " WHERE DATE (dirty.Docdate) = '" . $date[$sheet] . "'";
//       } elseif ($chk == 'monthbetween') {
//         $query .= " WHERE month (dirty.Docdate) = MONTH('" . $date[$sheet] . "')";
//       }
//       $query .= "
//   AND factory.FacCode = '$FacCode'
//   AND department.HptCode = '$HptCode'
//   AND dirty.isStatus <> 9
//   AND dirty.isStatus <> 0
//   GROUP BY item.ItemName,department.DepName,dirty_detail.RequestName
//   ORDER BY item.ItemName , department.DepName ASC";
//     $meQuery = mysqli_query($conn, $query);
//     while ($Result = mysqli_fetch_assoc($meQuery)) {
//       if ($Result['RequestName'] <> null) {
//         $Result['ItemName'] = $Result['RequestName'];
//       }
//       if ($Result["ItemName"] == $check) {
//         $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "");
//         $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($Result["Qty"]));
//       } else {
//         $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $Result["ItemName"]);
//         $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($Result["Qty"]));
//         $check = $Result["ItemName"];
//       }
//       $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, trim($Result["DepName"]));
//       $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, number_format($Result["Weight"], 2));
//       $i++;
//       $Qty += $Result["Qty"];
//       $Weight += $Result["Weight"];
//     }
//     $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $array2['total'][$language]);
//     $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($Qty));
//     $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, " ");
//     $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, number_format($Weight, 2));
//     $row_sum = $i;
//     $i += 1;
//     $queryy = "SELECT
//   item.ItemName,
//   SUM(dirty_detail.Qty) AS Qty,
//   COALESCE(dirty_detail.RequestName,'-') as RequestName
//   FROM
//   dirty
//   INNER JOIN dirty_detail ON dirty.DocNo = dirty_detail.DocNo
//   INNER JOIN department ON dirty_detail.DepCode = department.DepCode
//   INNER JOIN factory ON dirty.FacCode = factory.FacCode
//   LEFT  JOIN item ON item.itemcode = dirty_detail.itemcode";
//       if ($chk == 'between') {
//         $queryy .= " WHERE DATE (dirty.Docdate) = '" . $date[$sheet] . "'";
//       } elseif ($chk == 'month') {
//         $queryy .= " WHERE DATE (dirty.Docdate) = '" . $date[$sheet] . "'";
//       } elseif ($chk == 'monthbetween') {
//         $queryy .= " WHERE month (dirty.Docdate) = MONTH('" . $date[$sheet] . "')";
//       }
//       $queryy .= "
//   AND factory.FacCode = '$FacCode'
//   AND department.HptCode = '$HptCode'
//   AND dirty.isStatus <> 9
//   AND dirty.isStatus <> 0
//   GROUP BY item.ItemName,dirty_detail.RequestName
//   ORDER BY item.ItemName , department.DepName ASC";
//     $meQuery = mysqli_query($conn, $queryy);
//     while ($Result = mysqli_fetch_assoc($meQuery)) {
//       if ($Result['RequestName'] <> null) {
//         $Result['ItemName'] = $Result['RequestName'];
//       }
//       $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $Result["ItemName"]);
//       $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $Result["Qty"]);
//       $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, " ");
//       $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, "");
//       $i++;
//     }
//     $cols = array('A', 'B', 'C', 'D');
//     $width = array(10, 10, 45, 20);
//     for ($j = 0; $j < count($cols); $j++) {
//       $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
//     }
//     $header = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 10,
//         'name'  => 'THSarabun'
//       )
//     );
//     $item = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
//       ),
//       'font'  => array(
//         // 'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 10,
//         'name'  => 'THSarabun'
//       ),
//       'borders' => array(
//         'borders' => array(
//           'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
//           'color' => array('rgb' => '000000')
//         )
//       )
//     );
//     $Qty = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         // 'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 10,
//         'name'  => 'THSarabun'
//       )
//     );
//     $department = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         // 'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 10,
//         'name'  => 'THSarabun'
//       )
//     );
//     $Weight = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         // 'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 10,
//         'name'  => 'THSarabun'
//       )
//     );
//     $sum = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 10,
//         'name'  => 'THSarabun'
//       )
//     );
//     $styleArray = array(

//       'borders' => array(

//         'allborders' => array(

//           'style' => PHPExcel_Style_Border::BORDER_THIN
//         )
//       )
//     );
//     $r3 = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 20,
//         'name'  => 'THSarabun'
//       )
//     );
//     $datetime = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//       ),
//       'font'  => array(
//         'bold'  => true,
//         // 'color' => array('rgb' => 'FF0000'),
//         'size'  => 8,
//         'name'  => 'THSarabun'
//       )
//     );
//     $objPHPExcel->getActiveSheet()->getStyle("A7:D7")->applyFromArray($header)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);;
//     $objPHPExcel->getActiveSheet()->getStyle("A2:A" . $i)->applyFromArray($item)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//     $objPHPExcel->getActiveSheet()->getStyle("B2:B" . $i)->applyFromArray($Qty)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
//     $objPHPExcel->getActiveSheet()->getStyle("C2:C" . $i)->applyFromArray($department)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//     $objPHPExcel->getActiveSheet()->getStyle("D2:D" . $i)->applyFromArray($Weight)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//     $objPHPExcel->getActiveSheet()->getStyle("D" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//     $objPHPExcel->getActiveSheet()->getStyle("A" . $row_sum . ":B" . $row_sum)->applyFromArray($sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

//     $i--;
//     $objPHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray($r3)->getNumberFormat();
//     $objPHPExcel->getActiveSheet()->getStyle("D1")->applyFromArray($datetime)->getNumberFormat();
//     $objPHPExcel->getActiveSheet()->getStyle('A7:D' . $i)->applyFromArray($styleArray);
//     // $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);
//     $objDrawing = new PHPExcel_Worksheet_Drawing();
//     $objDrawing->setName('test_img');
//     $objDrawing->setDescription('test_img');
//     $objDrawing->setPath('Nhealth_linen 4.0.png');
//     $objDrawing->setCoordinates('A1');
//     //setOffsetX works properly
//     $objDrawing->setOffsetX(0);
//     $objDrawing->setOffsetY(0);
//     //set width, height
//     $objDrawing->setWidthAndHeight(150, 75);
//     $objDrawing->setResizeProportional(true);
//     $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//     $objPHPExcel->getActiveSheet($sheet)->setTitle($DateShow[$sheet]);
//     $objPHPExcel->createSheet();
//     $s++;
//   }
// }


$objPHPExcel->removeSheetByIndex(
  $objPHPExcel->getIndex(
    $objPHPExcel->getSheetByName('Worksheet')
  )
);
//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_Dirty_Linen_Weight_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
$objWriter->save('php://output');
exit();
