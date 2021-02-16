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



if ($language == 'th') {
  $HptName = 'HptNameTH';
  $FacName = 'FacNameTH';
} else {
  $HptName = 'HptName';
  $FacName = 'FacName';
}



if ($chk == 'one') {
  if ($format == 1) {
    list($year, $mouth, $day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $year = $year + 543;
      $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
    } else {
      $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
    }
  } elseif ($format = 3) {
    if ($language == "th") {
      $date1 = $date1 + 543;
      $date_header = $array['year'][$language] . " " . $date1;
    } else {
      $date_header = $array['year'][$language] . $date1;
    }
  }
} elseif ($chk == 'between') {
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
  $datetime = new DatetimeTH();
  if ($language == 'th') {
    $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
  } else {
    $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
  }
} elseif ($chk == 'monthbetween') {
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
// -----------------------------------------------------------------------------------
// if ($chk == 'one') {
//   if ($format == 1) {
//     $count = 1;
//     $date[] = $date1;
//     list($y, $m, $d) = explode('-', $date1);
//     if ($language ==  'th') {
//       $y = $y + 543;
//     }
//     $date1 = $d . '-' . $m . '-' . $y;
//     $DateShow[] = $date1;

//   }

// } elseif ($chk == 'between') {
//   $begin = new DateTime( $date1 );
//   $end = new DateTime( $date2 );
//   $end = $end->modify( '1 day' );

//   $interval = new DateInterval('P1D');
//   $period = new DatePeriod($begin, $interval ,$end);
//   foreach ($period as $key => $value) {
//     $date[] = $value->format('Y-m-d');
//   }
//   $count = count($date);
//   for ($i = 0; $i < $count; $i++) {
//     $date1 = $date[$i];
//     list($y, $m, $d) = explode('-', $date1);
//     if ($language ==  'th') {
//       $y = $y + 543;
//     }
//     $date1 = $d . '-' . $m . '-' . $y;
//     $DateShow[] = $date1;
//   }
// } elseif ($chk == 'month') {
//   $day = 1;
//   if ($language ==  'th') {
//     $y = $year1 + 543;
//   } else {
//     $y = $year1;
//   }
//   $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
//   $datequery =  $year1 . '-' . $date1 . '-';
//   $dateshow = '-' . $date1 . '-' . $y;
//   for ($i = 0; $i < $count; $i++) {
//     if($day < 10)
//     {
//       $day = '0'.$day;
//     }
//     $date[] = $datequery . $day;
//     $DateShow[] = $day . $dateshow;
//     $day++;
    
//   }

// }


$colorfill = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'B9E3E6')
  )
);
$fill = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
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

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Report_Extra_Delivery');
$objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
$objPHPExcel->getActiveSheet()->mergeCells('A5:L5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:L6');

  if($DepCode == 'ALL'){
    $wheredep = "";
  }else{
    $wheredep = "AND department.DepCode = '$DepCode' AND shelfcount.DepCode = '$DepCode' ";
  }

  
  $wheredate = "";
  if($chk =="one"){
    $wheredate = "AND DATE(shelfcount.complete_date) = DATE('$date1')";
  }else if($chk =="between"){
    $wheredate = "AND DATE(shelfcount.complete_date) BETWEEN '$date1' AND '$date2'";
  }else if("month"){
    $wheredate = "AND MONTH(shelfcount.complete_date) = '$date1'";
  }

    $row = 7;
    $AorZDep = 0;
    $AorZHeader = 0;
    $color1 = "";
    $color2 = "";
    $Sqldep = "SELECT
                  department.DepCode,
                  department.DepName 
                FROM
                  shelfcount
                  INNER JOIN department ON shelfcount.DepCode = department.DepCode 
                WHERE
                  shelfcount.ScTime = 0
                  $wheredate
                  AND shelfcount.SiteCode = '$HptCode' 
                  AND department.HptCode = '$HptCode'
                  AND (shelfcount.IsStatus = 3 OR shelfcount.IsStatus = 4)
                  $wheredep
                GROUP BY
                  department.DepCode ";
                  
    $meQueryDep = mysqli_query($conn, $Sqldep);
    while ($Resultdep = mysqli_fetch_assoc($meQueryDep)) {

      $AorZDetail = 0;
      $_DepName = $Resultdep['DepName'];
      $_DepCode = $Resultdep['DepCode'];

      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDep].$row, $_DepName);
      $row++;
      $color1 = $date_cell1[$AorZHeader] . $row;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'วันที่เอกสาร');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'เลขที่เอกสาร');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'Submit Requests');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'Accept');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'Packing Time');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'Complete');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'Dellvery Complete');
      $AorZHeader ++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZHeader] . $row, 'คำนวนเวลาที่ใช้');
      $color2 = $date_cell1[$AorZHeader] . $row;

      // ==============================================

      $objPHPExcel->getActiveSheet()->getStyle($color1.":".$color2)->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle($color1.":".$color2)->applyFromArray($fill);
      // ==============================================
      $row ++;


      $detail1 = "";
      $detail2 = "";

      $SqlDetail = "SELECT
                      shelfcount.DocNo,
                      shelfcount.accept_Time, 
                      shelfcount.PkStartTime, 
                      shelfcount.PkEndTime, 
                      shelfcount.DvEndTime,
                      shelfcount.create_Time,
                      shelfcount.DocDate,
                      TIMEDIFF(shelfcount.DvEndTime,shelfcount.create_Time) AS dateCal
                    FROM
                      shelfcount
                    WHERE shelfcount.DepCode = '$_DepCode'
                    AND shelfcount.ScTime = 0
                    AND (shelfcount.IsStatus = 3 OR shelfcount.IsStatus = 4)
                    $wheredate
                    AND shelfcount.SiteCode = '$HptCode'  ";
      $meQueryDetail = mysqli_query($conn, $SqlDetail);
      while ($ResultDetail = mysqli_fetch_assoc($meQueryDetail)) {

        $_DocNo = $ResultDetail['DocNo'];
        $_DocDate = $ResultDetail['DocDate'];
        $_accept_Time = $ResultDetail['accept_Time'];
        $_PkStartTime = $ResultDetail['PkStartTime'];
        $_PkEndTime = $ResultDetail['PkEndTime'];
        $_DvEndTime = $ResultDetail['DvEndTime'];
        $_dateCal = $ResultDetail['dateCal'];
        $_create_Time = $ResultDetail['create_Time'];


        $detail1 = $date_cell1[$AorZDetail] . $row;




        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_DocDate );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_DocNo );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_create_Time );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_accept_Time );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_PkStartTime );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_PkEndTime );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_DvEndTime );
        $AorZDetail ++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$AorZDetail] . $row, $_dateCal . "  ชั่วโมง" );

        $detail2 = $date_cell1[$AorZDetail] . $row;


        $objPHPExcel->getActiveSheet()->getStyle($detail1.":".$detail2)->applyFromArray($fill);
        $row++;
        $AorZDetail = 0;
      }


      // $styleArray = array(

      //   'borders' => array(
      
      //     'allborders' => array(
      
      //       'style' => PHPExcel_Style_Border::BORDER_THIN
      //     )
      //   )
      // );

      // $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$AorZDep].$row . ":" . $date_cell1[$AorZDetail] . $rowDetail)->applyFromArray($styleArray);

      // $rowDetail ++;

      $objPHPExcel->getActiveSheet()->getStyle($color1.":".$detail2)->applyFromArray($styleArray);


      $row ++;
      $row ++;
      $AorZHeader = 0;
    }

  
  
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
  

 






    $objPHPExcel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($A5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);



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
  $objPHPExcel->getActiveSheet()->setTitle("SAP");
  $objPHPExcel->createSheet();




//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = "Report_Extra_Delivery_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
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
