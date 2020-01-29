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

for($chk = 0; $chk <= 12; $chk++){
$objWorksheet = $objPHPExcel->setActiveSheetIndex($chk);
$highestRow = $objWorksheet->getHighestRow();
$highestColumn = $objWorksheet->getHighestColumn();

$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
$headingsArray = $headingsArray[1];

$r = -1;
$namedDataArray = array();
for ($row = 9; $row <= $highestRow; ++$row)
{
    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
    if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > ''))
    {
        ++$r;
        foreach($headingsArray as $columnKey => $columnHeading)
        {
            $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
        }
    }
}

    if ($chk == 0) 
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert Site
            $query = " INSERT INTO site (
                HptCode,
                HptName,
                HptNameTH,
                IsStatus,
                money,
                DocDate,
                Modify_Date,
                Modify_Code,
                payerCode,
                Site_Path,
                LabSiteCode,
                Signature,
                SiteTest,
                stock
            )
            VALUES
                (
                    '".$resx['HptCode']."',
                    '".$resx['HptName']."',
                    '".$resx['HptNameTH']."',
                    '".$resx['IsStatus']."',
                    '".$resx['money']."',
                    '".$resx['DocDate']."',
                    '".$resx['Modify_Date']."',
                    '".$resx['Modify_Code']."',
                    '".$resx['payerCode']."',
                    '".$resx['Site_Path']."',
                    '".$resx['LabSiteCode']."',
                    '".$resx[' Signature ']."',
                    '".$resx[' SiteTest ']."' ,
                    '".$resx[' stock ']."' 
                ) ";
            mysqli_query($conn, $query);
            //
        }
    } 
    else if($chk ==1)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert factory
            $query_test = "INSERT INTO factory (
                FacCode,
                FacName,
                DiscountPercent,
                Price,
                IsCancel,
                Address,
                Post,
                Tel,
                TaxID,
                FacNameTH,
                DocDate,
                Modify_Date,
                Modify_Code,
                HptCode
            )
            VALUES
                (
                    '".$resx['FacCode']."',
                    '".$resx['FacName']."',
                    '".$resx['DiscountPercent']."',
                    '".$resx['Price']."',
                    '".$resx['IsCancel']."',
                    '".$resx['Address']."',
                    '".$resx['Post']."',
                    '".$resx['Tel']."',
                    '".$resx['TaxID']."',
                    '".$resx['FacNameTH']."',
                    '".$resx['DocDate']."',
                    '".$resx['Modify_Date']."',
                    '".$resx['Modify_Code']."',
                    '".$resx['HptCode']."'
                )";
            mysqli_query($conn, $query_test);
            //
        }
    }
    else if($chk ==2)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert grouphpt
            $query = "INSERT INTO grouphpt (
                GroupCode,
                HptCode,
                GroupName,
                IsStatus,
                DocDate,
                Modify_Date,
                Modify_Code
            )
            VALUES
                (
                    '".$resx['GroupCode']."',
                    '".$resx['HptCode']."',
                    '".$resx['GroupName']."',
                    '".$resx['IsStatus']."',
                    '".$resx['DocDate']."',
                    '".$resx['Modify_Date']."',
                    '".$resx['Modify_Code']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==3)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert grouphpt
            $query = "INSERT INTO department (
                DepCode,
                HptCode,
                DepName,
                IsStatus,
                IsDefault,
                DocDate,
                Modify_Date,
                Modify_Code,
                IsActive,
                GroupCode,
                Ship_To
            )
            VALUES
                (
                    '".$resx['DepCode']."',
                    '".$resx['HptCode']."',
                    '".$resx['DepName']."',
                    '".$resx['IsStatus']."',
                    '".$resx['IsDefault']."',
                    '".$resx['DocDate']."',
                    '".$resx['Modify_Date']."',
                    '".$resx['Modify_Code']."',
                    '".$resx['IsActive']."',
                    '".$resx['GroupCode']."',
                    '".$resx['Ship_To']."'
            
                )";

            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==4)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO item (
                ItemCode,
                CategoryCode,
                ItemName,
                UnitCode,
                SizeCode,
                Weight,
                IsActive,
                QtyPerUnit,
                UnitCode2,
                IsDirtyBag,
                isset,
                itemDate,
                Tdas,
                HptCode,
                Modify_Date,
                DocDate,
                Modify_Code,
                IsClean,
                typeLinen,
                numPack,
                isSAP
            )
            VALUES
                (
                    '".$resx['ItemCode']."',
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
                    '".$resx['itemDate']."',
                    '".$resx['Tdas']."',
                    '".$resx['HptCode']."',
                    '".$resx['Modify_Date']."',
                    '".$resx['DocDate']."',
                    '".$resx['Modify_Code']."',
                    '".$resx['IsClean']."',
                    '".$resx['typeLinen']."',
                    '".$resx['numPack']."',
                    '".$resx['isSAP']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==5)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO item_multiple_unit (
                MpCode,
                UnitCode,
                Multiply,
                PriceUnit,
                ItemCode
            )
            VALUES
                (
                    '".$resx['MpCode']."',
                    '".$resx['UnitCode']."',
                    '".$resx['Multiply']."',
                    '".$resx['PriceUnit']."',
                    '".$resx['ItemCode']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==6)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO par_item_stock (
                ItemCode,
                DepCode,
                ParQty,
                TotalQty,
                HptCode
            )
            VALUES
                (
                    '".$resx['ItemCode']."',
                    '".$resx['DepCode']."',
                    '".$resx['ParQty']."',
                    '".$resx['TotalQty']."',
                    '".$resx['HptCode']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==7)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO users (
                HptCode,
                FacCode,
                UserName,
                Password,
                IsCancel,
                PmID,
                Count,
                Modify_Date,
                TimeOut,
                lang,
                email,
                chk_logoff,
                pic,
                Active_mail,
                DepCode,
                DocDate,
                Modify_Code,
                remask,
                EngPerfix,
                EngName,
                EngLName,
                ThPerfix,
                ThName,
                ThLName
            
            )
            VALUES
                (
                    '".$resx['HptCode']."',
                    '".$resx['FacCode']."',
                    '".$resx['UserName']."',
                    '".$resx['Password']."',
                    '".$resx['IsCancel']."',
                    '".$resx['PmID']."',
                    '".$resx['Count']."',
                    '".$resx['Modify_Date']."',
                    '".$resx['TimeOut']."',
                    '".$resx['lang']."',
                    '".$resx['email']."',
                    '".$resx['IsActive']."',
                    '".$resx['chk_logoff']."',
                    '".$resx['pic']."',
                    '".$resx['DepCode']."',
                    '".$resx['DocDate']."',
                    '".$resx['Modify_Code']."',
                    '".$resx['remask']."',
                    '".$resx['EngPerfix']."',
                    '".$resx['EngName']."',
                    '".$resx['EngLName']."',
                    '".$resx['ThPerfix']."',
                    '".$resx['ThName']."',
                    '".$resx['ThLName']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==8)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO time_express (
                HptCode,
                Time_ID,
                DocDate,
                Modify_Date,
                Modify_Code
            )
            VALUES
                (
                '".$resx['HptCode']."',
                '".$resx['Time_ID']."',
                '".$resx['DocDate']."',
                '".$resx['Modify_Date']."',
                '".$resx['Modify_Code']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==9)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO sc_express (
                HptCode,
                Time_ID,
                DocDate,
                Modify_Date,
                Modify_Code
            )
            VALUES
                (
                '".$resx['HptCode']."',
                '".$resx['Time_ID']."',
                '".$resx['DocDate']."',
                '".$resx['Modify_Date']."',
                '".$resx['Modify_Code']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==10)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO round_time_dirty (
                HptCode,
                Time_ID,
                DocDate,
                Modify_Date,
                Modify_Code
            )
            VALUES
                (
                '".$resx['HptCode']."',
                '".$resx['Time_ID']."',
                '".$resx['DocDate']."',
                '".$resx['Modify_Date']."',
                '".$resx['Modify_Code']."'
                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==11)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO round_time_dirty (
                            HptCode,
                            FacCode,
                            SendTime
                            )
                            VALUES
                                (
                                '".$resx['HptCode']."',
                                '".$resx['FacCode']."',
                                '".$resx['SendTime']."'
                                )";
            mysqli_query($conn, $query);
            //
        }
    }
    else if($chk ==12)
    {
        foreach ($namedDataArray as $resx)
        {
            //Insert item
            $query = "INSERT INTO category_price (
                HptCode,
                CategoryCode,
                Price
            )
            VALUES
                (
                    '".$resx['HptCode']."',
                    '".$resx['CategoryCode']."',
                    '".$resx['Price']."'
                )";
            mysqli_query($conn, $query);
            //
        }
            mysqli_close($conn);
    }
}


?>