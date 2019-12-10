<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];

header('Content-Type: text/html; charset=utf-8');

//File สำหรับ Import
$inputFileName=$filename=$_FILES["file"]["tmp_name"];

/** PHPExcel */
require_once '../PHPExcel/Classes/PHPExcel.php';

/** PHPExcel_IOFactory - Reader */
include '../PHPExcel/Classes/PHPExcel/IOFactory.php';


$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
$objReader->setReadDataOnly(true);  
$objPHPExcel = $objReader->load($inputFileName);  

$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
$highestRow = $objWorksheet->getHighestRow();
$highestColumn = $objWorksheet->getHighestColumn();

$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
$headingsArray = $headingsArray[1];

$r = -1;
$namedDataArray = array();
for ($row = 2; $row <= $highestRow; ++$row) {
    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
    if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
        ++$r;
        foreach($headingsArray as $columnKey => $columnHeading) {
            $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
        }
    }
}


foreach ($namedDataArray as $resx) {
 //Insert
  $query = " INSERT INTO item (ItemCode,HptCode,CategoryCode,ItemName,UnitCode,SizeCode,Weight,IsActive,QtyPerUnit,UnitCode2,IsDirtyBag,isset,Tdas,IsClean,typeLinen,numPack) VALUES
      (
       '".$resx['ItemCode']."',
       '".$resx['HptCode']."',
       '".$resx['CategoryCode']."',
       '".$resx['ItemName']."',
       '".$resx['UnitCode']."',
       '".$resx['SizeCode']."',
       '".$resx['Weight']."',
       '".$resx['IsActive']."',
       '".$resx['QtyPerUnit']."',
       '".$resx['UnitCode2']."',
       '".$resx['IsDirtyBag']."',
       '".$resx['isset']."',
       '".$resx['Tdas']."',
       '".$resx['IsClean']."',
       '".$resx['typeLinen']."',
       '".$resx['numPack']."'
      )";
  mysqli_query($conn, $query);
 //
//  echo $query;
}
// $mysqli->close();
mysqli_close($conn);