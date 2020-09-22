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
$DepCode = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$dirty_time = $data[11];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$status_group = 1;
$Weight = 0;
$count = 1;
$status_group == 1;
$DepCode = [];
$DepName = [];
$GroupCode = [];
$GroupName = [];
$DateShow = [];
$TimeName = [];
$RequestName = [];
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
$sheet_item = array('', 'Dirty1', 'Dirty2', 'Dirty3', 'Dirty4', 'Dirty5', 'Dirty6');
$sheet_Name = array('Report_Dirty_Linen_Weight', 'RED BAG', 'GREEN BAG', 'GRAY BAG', 'Square Green', 'Square Blue', 'Square Red');
$count_sheet = sizeof($sheet_item);
if ($dirty_time == '0')
{
  $dirty_time = '';
}
else
{
  $dirty_time = " AND time_dirty.ID ='$dirty_time'";
}

$query = "  SELECT time_dirty.TimeName,time_dirty.id 
            FROM time_dirty 
            INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id  
            WHERE round_time_dirty.HptCode = '$HptCode'
            $dirty_time
            Group by time_dirty.TimeName" ;
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $TimeName =   $Result['TimeName'];
}
if ($dirty_time == '') {
  $TimeName = 'รอบซักทุกรอบ';
  $Time_DIR =   $TimeName;
} else {
  $Time_DIR =  ' รอบที่' . $TimeName . '.น';
}


// -----------------------------------------------------------------------------------
if ($chk == 'one')
{
  if ($format == 1)
  {
    $query = "  SELECT
                DISTINCT DATE_FORMAT(dirty.DocDate,'%Y-%m-%d') as DocDate ,
                time_dirty.TimeName,
                dirty.Time_ID
                FROM
                dirty
                INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
                INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id  
                $where
               AND  round_time_dirty.HptCode = '$HptCode'
                $dirty_time 
              ORDER BY dirty.Time_ID ASC ";
    echo $query;
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $date[] = $Result['DocDate'];
      $Time_ID[] = $Result['Time_ID'];
      list($y, $m, $d) = explode('-', $Result['DocDate']);
      if ($language ==  'th') {
        $y = $y + 543;
      }
      $date1 = $d . '-' . $m . '-' . $y;
      $DateShow[] = $date1 . '( รอบที่' . $Result['TimeName'] . '.น)';
    }
    $count = sizeof($date);
  }
  elseif ($format = 3)
  {
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
}
elseif ($chk == 'between')
{
  $query = "  SELECT
  DISTINCT DATE_FORMAT(dirty.DocDate,'%Y-%m-%d') as DocDate ,
  time_dirty.TimeName,
  dirty.Time_ID
  FROM
  dirty
  INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
  INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id  
  $where
  AND  round_time_dirty.HptCode = '$HptCode'
   $dirty_time
   ORDER BY dirty.Time_ID ASC ";
  // echo $query;
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $date[] = $Result['DocDate'];
    $Time_ID[] = $Result['Time_ID'];
    list($y, $m, $d) = explode('-', $Result['DocDate']);
    if ($language ==  'th')
    {
      $y = $y + 543;
    }
    $date1 = $d . '-' . $m . '-' . $y;
    $DateShow[] = $date1 . '( รอบที่' . $Result['TimeName'] . '.น)';
  }
  $count = sizeof($date);
}
elseif ($chk == 'month')
{
  $query = "  SELECT
  DISTINCT DATE_FORMAT(dirty.DocDate,'%Y-%m-%d') as DocDate ,
  time_dirty.TimeName,
  dirty.Time_ID
  FROM
  dirty
  INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
  INNER JOIN round_time_dirty ON round_time_dirty.Time_ID = time_dirty.id  
  $where
  AND  round_time_dirty.HptCode = '$HptCode'
   $dirty_time 
   ORDER BY dirty.Time_ID ASC ";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $date[] = $Result['DocDate'];
    $Time_ID[] = $Result['Time_ID'];
    list($y, $m, $d) = explode('-', $Result['DocDate']);
    if ($language ==  'th') {
      $y = $y + 543;
    }
    $date1 = $d . '-' . $m . '-' . $y;
    $DateShow[] = $date1 . '( รอบที่' . $Result['TimeName'] . '.น)';
  }
  $count = sizeof($date);
}
elseif ($chk == 'monthbetween')
{
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


for ($sheet = 0; $sheet < $count_sheet; $sheet++)
{
  // -----------------------------------------------------------------------------------
  $objPHPExcel->setActiveSheetIndex($sheet)
    ->setCellValue('A8',  $array2['factory'][$language])
    ->setCellValue('B8',  $array2['itemname'][$language]);
  // Write data from MySQL result
  $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A4', $array2['r1'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('A5', $date_header);
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $Time_DIR);
  $objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
  $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
  $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
  $objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
  // -----------------------------------------------------------------------------------
  $query = "  SELECT
              dirty_detail.DepCode,
              department.DepName,
              factory.$FacName
              FROM
              dirty_detail
              INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.FacCode = dirty.FacCode
              INNER JOIN department ON department.DepCode = dirty_detail.DepCode
              INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
              INNER JOIN site ON site.HptCode = department.HptCode
              $where
              AND dirty.isStatus <> 9 AND dirty.isStatus <> 0
              AND dirty.FacCode = '$FacCode'
              AND site.HptCode = '$HptCode' 
               $dirty_time
              GROUP BY dirty_detail.DepCode,department.DepName
            ";
  // echo "<pre>";
  // echo $query;
  // echo "</pre>";
  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    if ($status_group == 1)
    {
      $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result[$FacName]);
    }
    $i++;
    $DepName[] =  $Result["DepName"];
    $DepCode[] =  $Result["DepCode"];
    $status_group = 0;
  }
  // echo "<pre>";
  // print_r($DepName);
  // echo "</pre>";
  // echo "<pre>";
  // print_r($DepCode);
  // echo "</pre>";
  // echo "<pre>";
  // print_r($date);
  // echo "</pre>";
  // -----------------------------------------------------------------------------------
  $r = 2;
  $d = 1;
  $rows = 9;
  for ($row = 0; $row < $count; $row++)
  {
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
  for ($row = 0; $row < $count; $row++)
  {
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



  $COUNT_CODE = SIZEOF($DepCode);
  for ($q = 0; $q < $COUNT_CODE; $q++)
  {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $DepName[$lek]);
    $r++;




    for ($code = 0; $code < $count; $code++)
    {
      $data = "SELECT   COALESCE(SUM(dirty_detail.Qty),'0') AS Totalqty,
                        COALESCE(SUM(dirty_detail.Weight),'0') AS Weight
                    FROM dirty_detail 
                    INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
                    INNER JOIN factory ON factory.Faccode = dirty.Faccode
                    INNER JOIN department ON department.DepCode = dirty_detail.DepCode
                    INNER JOIN site ON site.HptCode = department.HptCode
                    INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID ";
      if ($chk == 'one')
      {
        if ($format == 1)
        {
          $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0   AND dirty.Time_ID = '$Time_ID[$code]' ";
        }
        elseif ($format = 3)
        {
          list($year, $month) = explode('-', $date[$code]);
          $data .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 ";
        }
      }
      elseif ($chk == 'between')
      {
        $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$code]'";
      }
      elseif ($chk == 'month')
      {
        $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$code]'";
      }
      elseif ($chk == 'monthbetween')
      {
        list($year, $month) = explode('-', $date[$code]);
        $data .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0";
      }
      $data .= " AND dirty.Faccode = '$FacCode'
                 AND site.HptCode = '$HptCode'
                 AND dirty_detail.DepCode = '$DepCode[$q]'
                  $dirty_time ";

      if ($sheet <> 0)
      {
        $data .= " AND dirty_detail.ItemCode = '$sheet_item[$sheet]'";
      }
      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
        $r++;
        $sumdayTotalqty += $Result["Totalqty"];
        $sumdayWeight += $Result["Weight"];
      }
      $TotaldayTotalqty += $sumdayTotalqty;
      $TotaldayWeight += $sumdayWeight;
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


  $cnt = 0;
  for ($dayx = 0; $dayx < $count; $dayx++)
  {
    $Totalqtys[$dayx] = 0;
    $Weights[$dayx] = 0;
    $Date_chk[$dayx] = 0;
    $Time_IDs[$dayx] = 0;
  }
    $data2 =       "SELECT
                    COALESCE(SUM(dirty_detail.Qty),'0') AS Totalqtys,
                    COALESCE(SUM(dirty_detail.Weight),'0') AS Weights,
	                  DATE( dirty.DocDate ) AS Date_chk ,
	                  dirty.Time_ID AS Time_IDs
              FROM
              dirty_detail
              INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.Faccode = dirty.Faccode
              INNER JOIN department ON department.DepCode = dirty_detail.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID  
              WHERE  DATE(dirty.DocDate)  IN ( ";
              for ($day = 0; $day < $count; $day++)
              {

                $data2 .= " '$date[$day]' ,";

              }
              $data2 = rtrim($data2, ' ,'); 

              $data2 .= " ) AND dirty.Time_ID IN ( ";

              for ($day = 0; $day < $count; $day++)
              {

                $data2 .= " '$Time_ID[$day]' ,";

              }
              $data2 = rtrim($data2, ' ,'); 
              
              $data2 .= " ) AND dirty.Faccode = '$FacCode'
                            AND site.HptCode = '$HptCode'
                            AND dirty.isStatus <> 9 
                            AND dirty.isStatus <> 0
                            $dirty_time ";
                 if ($sheet <> 0)
                 {
                   $data2 .= " AND dirty_detail.ItemCode = '$sheet_item[$sheet]'";
                 }

                 $data2 .= " GROUP BY dirty.Time_ID , DATE( dirty.DocDate )
                             ORDER BY DATE( dirty.DocDate ) , dirty.Time_ID " ;

       $meQuery = mysqli_query($conn, $data2);
    
      //  echo $data2;
       while ($Result = mysqli_fetch_assoc($meQuery))
       {
        $Totalqtys[$cnt] =  $Result["Totalqtys"];
        $Weights[$cnt] =  $Result["Weights"];
        $Date_chk[$cnt] =  $Result["Date_chk"];
        $Time_IDs[$cnt] =  $Result["Time_IDs"];
        $cnt++;
      }

      $sumdayTotalqty = 0;
      $sumdayWeight = 0;
      $x = 0;
      
      foreach(  $date as $key => $val ) 
      {

          if($Date_chk[$x]  == $val && $Time_IDs[$x] == $Time_ID[$key] )
          {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Totalqtys[$x]);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Weights[$x]) ;
            $r++;
            $sumdayTotalqty += $Totalqtys[$x];
            $sumdayWeight +=  $Weights[$x];
            $x++;
          }
          else
          {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
            $r++;
          }
      }
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);




















  // for ($day = 0; $day < $count; $day++)
  // {
  //   $data2 =       "SELECT
  //                   COALESCE(SUM(dirty_detail.Qty),'0') AS Totalqty,
  //                   COALESCE(SUM(dirty_detail.Weight),'0') AS Weight
  //             FROM
  //             dirty_detail
  //             INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
  //             INNER JOIN factory ON factory.Faccode = dirty.Faccode
  //             INNER JOIN department ON department.DepCode = dirty_detail.DepCode
  //             INNER JOIN site ON site.HptCode = department.HptCode
  //             INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID";

  //   if ($chk == 'one')
  //   {
  //     if ($format == 1)
  //     {
  //       $data2 .=   " WHERE  DATE(dirty.DocDate)  ='$date[$day]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$day]'  ";
  //     }
  //     elseif ($format = 3)
  //     {
  //       list($year, $month) = explode('-', $date[$day]);
  //       $data2 .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0";
  //     }
  //   }
  //   elseif ($chk == 'between')
  //   {
  //     $data2 .=   " WHERE  DATE(dirty.DocDate)  ='$date[$day]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$day]'  ";
  //   }
  //   elseif ($chk == 'month')
  //   {
  //     $data2 .=   " WHERE  DATE(dirty.DocDate)  ='$date[$day]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$day]'  ";
  //   }
  //   elseif ($chk == 'monthbetween')
  //   {
  //     list($year, $month) = explode('-', $date[$day]);
  //     $data2 .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 ";
  //   }
  //   $data2 .= " AND dirty.Faccode = '$FacCode'
  //              AND site.HptCode = '$HptCode'
  //              $dirty_time
  //               ";
  //   if ($sheet <> 0)
  //   {
  //     $data2 .= " AND dirty_detail.ItemCode = '$sheet_item[$sheet]'";
  //   }
  //   // echo "<pre>";
  //   // print_r($data2);
  //   // echo "</pre>";
  //   $meQuery = mysqli_query($conn, $data2);
  //   while ($Result = mysqli_fetch_assoc($meQuery))
  //   {
  //     $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
  //     $r++;
  //     $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
  //     $r++;
  //     $sumdayTotalqty += $Result["Totalqty"];
  //     $sumdayWeight += $Result["Weight"];
  //   }
  // }





  // $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
  // $r++;
  // $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
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
  $objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray($A5);
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
  $objPHPExcel->getActiveSheet()->setTitle($sheet_Name[$sheet]);
  $objPHPExcel->createSheet();
  //ตั้งชื่อไฟล์
  $DepName = [];
  $DepCode = [];
  $sumdayTotalqty = 0;
  $sumdayWeight = 0;
  $status_group = 1;
}


// -----------------------------------------------------------------------------------
$objPHPExcel->setActiveSheetIndex(7)
  ->setCellValue('A8',  $array2['factory'][$language])
  ->setCellValue('B8',  $array2['itemname'][$language])
  ->setCellValue('C8',  $array2['department'][$language]);
// Write data from MySQL result
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A4', $array2['r1'][$language]);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $date_header);
$objPHPExcel->getActiveSheet()->setCellValue('A6', $Time_DIR);
$objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
// -----------------------------------------------------------------------------------

$Depquery = "SELECT
              department.DepName,
              department.DepCode 
            FROM
            dirty_detail
            INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
            INNER JOIN factory ON factory.FacCode = dirty.FacCode
            INNER JOIN department ON department.DepCode = dirty_detail.DepCode
            INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID 
            $where 
            AND dirty.isStatus <> 9 
            AND dirty.isStatus <> 0 
            AND dirty.FacCode = '8' 
            AND dirty_detail.RequestName <> ''
            GROUP BY DepCode ";
        $meQuery = mysqli_query($conn, $Depquery);
        while ($Result = mysqli_fetch_assoc($meQuery))
        {

          $DepName_show[] =  $Result["DepName"];
          $DepCode_show[] =  $Result["DepCode"];

        }

    // -----------------------------------------------------------------------------------
    $r = 3;
    $d = 1;
    $rows = 9;
    for ($row = 0; $row < $count; $row++)
    {
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
    $r = 3;
    $j = 4;
    $d = 1;
    for ($row = 0; $row < $count; $row++)
    {
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
        $j = 4;
        $lek = 0;     
    $COUNT_CODE = SIZEOF($DepCode_show);
    for ($q = 0; $q < $COUNT_CODE; $q++)
    {
      $query = " SELECT
                  dirty_detail.RequestName,
                  factory.$FacName
                FROM
                dirty_detail
                INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
                INNER JOIN factory ON factory.FacCode = dirty.FacCode
                INNER JOIN department ON department.DepCode = dirty_detail.DepCode
                INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID 
                $where 
                AND dirty.isStatus <> 9 
                AND dirty.isStatus <> 0 
                AND dirty.FacCode = '$FacCode' 
                AND dirty_detail.RequestName <> ''
                AND department.DepCode = '$DepCode_show[$q]'
                GROUP BY RequestName ";
      $meQuery = mysqli_query($conn, $query);

      while ($Result = mysqli_fetch_assoc($meQuery))
      {
        if ($status_group == 1)
        {
          $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result[$FacName]);
        }
        $i++;
        if ($Result['RequestName'] <> null)
        {
          $RequestName[] =  $Result["RequestName"];
        }
        $status_group = 0;

        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $RequestName[$lek]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $DepName_show[$q]);
        $r++;
      
      
        for ($code = 0; $code < $count; $code++)
        {
          $data = "SELECT   COALESCE(SUM(dirty_detail.Qty),'0') AS Totalqty,
                            COALESCE(SUM(dirty_detail.Weight),'0') AS Weight
                          FROM dirty_detail 
                          INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
                          INNER JOIN factory ON factory.Faccode = dirty.Faccode
                          INNER JOIN department ON department.DepCode = dirty_detail.DepCode
                          INNER JOIN site ON site.HptCode = department.HptCode
                          INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID ";
          if ($chk == 'one')
          {
            if ($format == 1)
            {
              $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0   AND dirty.Time_ID = '$Time_ID[$code]' ";
            }
            elseif ($format = 3)
            {
              list($year, $month) = explode('-', $date[$code]);
              $data .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 ";
            }
          }
          elseif ($chk == 'between')
          {
            $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$code]'";
          }
          elseif ($chk == 'month')
          {
            $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$code]'";
          }
          elseif ($chk == 'monthbetween')
          {
            list($year, $month) = explode('-', $date[$code]);
            $data .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0";
          }
          $data .= "   AND dirty.Faccode = '$FacCode'
                       AND site.HptCode = '$HptCode'
                       AND dirty_detail.DepCode = '$DepCode_show[$q]'
                       AND dirty_detail.RequestName = '$RequestName[$lek]'
                      $dirty_time";
          $meQueryx = mysqli_query($conn, $data);
              while ($Result = mysqli_fetch_assoc($meQueryx))
              {
                $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
                $r++;
                $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
                $r++;
                $sumdayTotalqty += $Result["Totalqty"];
                $sumdayWeight += $Result["Weight"];
              }
              $TotaldayTotalqty += $sumdayTotalqty;
              $TotaldayWeight += $sumdayWeight;
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

    }

 

// $query = "  SELECT
//               department.DepName,
//               dirty_detail.RequestName,
//               factory.$FacName,
//               department.DepCode
//               FROM
//               dirty_detail
//               INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
//               INNER JOIN factory ON factory.FacCode = dirty.FacCode
//               INNER JOIN department ON department.DepCode = dirty_detail.DepCode
//               INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
//               $where
//               AND dirty.isStatus <> 9 AND dirty.isStatus <> 0
//               AND dirty.FacCode = '$FacCode'
//               AND dirty_detail.RequestName <> '' ";
// $meQuery = mysqli_query($conn, $query);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   if ($status_group == 1) {
//     $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result[$FacName]);
//   }
//   $i++;
//   if ($Result['RequestName'] <> null) {
//     $RequestName[] =  $Result["RequestName"];
//     $DepName[] =  $Result["DepName"];
//     $DepCode[] =  $Result["DepCode"];
//   }
//   $status_group = 0;
// }
// // echo "<pre>";
// // print_r($DepName);
// // echo "</pre>";
// // echo "<pre>";
// // print_r($DepCode);
// // echo "</pre>";
// // echo "<pre>";
// // print_r($date);
// // echo "</pre>";

// $start_row = 9;
// $r = 1;
// $j = 4;
// $lek = 0;
// $COUNT_CODE = SIZEOF($DepCode);
// for ($q = 0; $q < $COUNT_CODE; $q++)
// {
//   $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $RequestName[$lek]);
//   $r++;
//   $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $DepName[$lek]);
//   $r++;
//   for ($code = 0; $code < $count; $code++) {
//     $data = "SELECT   COALESCE(SUM(dirty_detail.Qty),'0') AS Totalqty,
//                       COALESCE(SUM(dirty_detail.Weight),'0') AS Weight
//                     FROM dirty_detail 
//                     INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
//                     INNER JOIN factory ON factory.Faccode = dirty.Faccode
//                     INNER JOIN department ON department.DepCode = dirty_detail.DepCode
//                     INNER JOIN site ON site.HptCode = department.HptCode
//                     INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID
//                     ";
//     if ($chk == 'one') {
//       if ($format == 1) {
//         $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0   AND dirty.Time_ID = '$Time_ID[$code]' ";
//       } elseif ($format = 3) {
//         list($year, $month) = explode('-', $date[$code]);
//         $data .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 ";
//       }
//     } elseif ($chk == 'between') {
//       $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$code]'";
//     } elseif ($chk == 'month') {
//       $data .=   " WHERE  DATE(dirty.DocDate)  ='$date[$code]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$code]'";
//     } elseif ($chk == 'monthbetween') {
//       list($year, $month) = explode('-', $date[$code]);
//       $data .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0";
//     }
//     $data .= "   AND dirty.Faccode = '$FacCode'
//                  AND site.HptCode = '$HptCode'
//                  AND dirty_detail.DepCode = '$DepCode[$q]'
//                  AND dirty_detail.RequestName = '$RequestName[$q]'
//                  $dirty_time";
//     // echo "<pre>";
//     // print_r($data);
//     // echo "</pre>";
//     $meQuery = mysqli_query($conn, $data);
//     while ($Result = mysqli_fetch_assoc($meQuery)) {
//       $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
//       $r++;
//       $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
//       $r++;
//       $sumdayTotalqty += $Result["Totalqty"];
//       $sumdayWeight += $Result["Weight"];
//     }
//     $TotaldayTotalqty += $sumdayTotalqty;
//     $TotaldayWeight += $sumdayWeight;
//   }
//   $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
//   $r++;
//   $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
//   $sumdayTotalqty = 0;
//   $sumdayWeight = 0;
//   $r = 1;
//   $start_row++;
//   $lek++;
// }
// // -----------------------------------------------------------------------------------
$r = 2;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 'total');
$r++;
for ($day = 0; $day < $count; $day++)
{
  $data2 =       "SELECT
              COALESCE(SUM(dirty_detail.Qty),'0') AS Totalqty,
              COALESCE(SUM(dirty_detail.Weight),'0') AS Weight
              FROM
              dirty_detail
              INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
              INNER JOIN factory ON factory.Faccode = dirty.Faccode
              INNER JOIN department ON department.DepCode = dirty_detail.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode
              INNER JOIN time_dirty ON dirty.Time_ID = time_dirty.ID";

  if ($chk == 'one')
  {
    if ($format == 1)
    {
      $data2 .=   " WHERE  DATE(dirty.DocDate)  ='$date[$day]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$day]'  ";
    }
    elseif ($format = 3)
    {
      list($year, $month) = explode('-', $date[$day]);
      $data2 .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0";
    }
  }
  elseif ($chk == 'between')
  {
    $data2 .=   " WHERE  DATE(dirty.DocDate)  ='$date[$day]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$day]'  ";
  }
  elseif ($chk == 'month')
  {
    $data2 .=   " WHERE  DATE(dirty.DocDate)  ='$date[$day]'  AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 AND dirty.Time_ID = '$Time_ID[$day]'  ";
  }
  elseif ($chk == 'monthbetween')
  {
    list($year, $month) = explode('-', $date[$day]);
    $data2 .=   " WHERE  YEAR(dirty.DocDate)  ='$year'  AND MONTH(dirty.DocDate)  ='$month' AND dirty.isStatus <> 9 AND dirty.isStatus <> 0 ";
  }
  $data2 .= " AND dirty.Faccode = '$FacCode'
              AND site.HptCode = '$HptCode' 
              AND dirty_detail.RequestName <> ''
              $dirty_time ";
  $meQuery = mysqli_query($conn, $data2);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
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
$objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray($A5);
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
$objPHPExcel->getActiveSheet()->setTitle('Extra');
$objPHPExcel->createSheet();
$objPHPExcel->getActiveSheet(0);
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_name = $array2['r1']['en'] . "_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
$status_group = 1;
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
