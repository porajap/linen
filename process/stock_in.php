<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

/**
 * @param $conn
 * @param $DATA
 */
function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
      AND department.IsDefault = 1
		  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function CreateDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $hotpCode = $DATA["hotpCode"];
  $deptCode = $DATA["deptCode"];
  $userid   = $DATA["userid"];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT CONCAT('SI',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
CURRENT_TIME() AS RecNow
FROM stock_in
INNER JOIN department on stock_in.DepCode = department.DepCode
WHERE DocNo Like CONCAT('SI',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
AND department.HptCode = '$hotpCode'
ORDER BY DocNo DESC LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $Result['DocNo'];
    $return[0]['DocNo']   = $Result['DocNo'];
    $return[0]['DocDate'] = $Result['DocDate'];
    $return[0]['RecNow']  = $Result['RecNow'];
    $count = 1;
    $Sql = "INSERT INTO log ( log ) VALUES ('" . $Result['DocDate'] . " : " . $Result['DocNo'] . " :: $hotpCode :: $deptCode')";
    mysqli_query($conn, $Sql);
  }

  if ($count == 1) {
    $Sql = "INSERT INTO stock_in
      ( DocNo,DocDate,DepCode,RefDocNo,
		TaxNo,TaxDate,DiscountPercent,DiscountBath,
		Total,IsCancel,Detail,
		stock_in.Modify_Code,stock_in.Modify_Date )
      VALUES
      ( '$DocNo',DATE(NOW()),$deptCode,'',
		0,NOW(),0,0,
		0,0,'',
		$userid,NOW() )";
    mysqli_query($conn,$Sql);

    $Sql = "INSERT INTO daily_request
(DocNo,DocDate,DepCode,RefDocNo,Detail,Modify_Code,Modify_Date)
VALUES
('$DocNo',DATE(NOW()),$deptCode,'','stock_in',$userid,DATE(NOW()))";
    mysqli_query($conn, $Sql);

    $Sql = "SELECT users.FName
	  FROM users
	  WHERE users.ID = $userid";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $DocNo = $Result['DocNo'];
      $return[0]['Record']   = $Result['FName'];
    }

    $boolean = true;
  } else {
    $boolean = false;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "CreateDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "CreateDocument";
    $return['msg'] = 'cantcreate';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $Hotp = $DATA["Hotp"];
  $deptCode = $DATA["deptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $Datepicker = $DATA["Datepicker"];
  $selecta = $DATA["selecta"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "SELECT site.HptName,department.DepName,stock_in.DocNo,stock_in.DocDate,stock_in.Total,users.FName,TIME(stock_in.Modify_Date) AS xTime,stock_in.IsStatus
FROM stock_in
INNER JOIN department ON stock_in.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON stock_in.Modify_Code = users.ID ";
  if ($selecta == 0) {
  $Sql .= "WHERE stock_in.DepCode = $deptCode AND stock_in.DocNo LIKE '%$DocNo%'";
  }elseif($selecta==1){
    $Sql.="WHERE site.HptCode = '$Hotp'";
  }
  $Sql .= "ORDER BY stock_in.DocNo DESC LIMIT 500";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['Record']   = $Result['FName'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDocument";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SelectDocument($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DocNo = $DATA["xdocno"];
  $Datepicker = $DATA["Datepicker"];
  $Sql = "SELECT   site.HptName,department.DepName,stock_in.DocNo,stock_in.DocDate,stock_in.Total,users.FName,TIME(stock_in.Modify_Date) AS xTime,stock_in.IsStatus
FROM stock_in
INNER JOIN department ON stock_in.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON stock_in.Modify_Code = users.ID
WHERE stock_in.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $Result['DocDate'];
    $return[$count]['Record']   = $Result['FName'];
    $return[$count]['RecNow']   = $Result['xTime'];
    $return[$count]['Total']   = $Result['Total'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $boolean = true;
    $count++;
  }

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "SelectDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['HptName']   = "";
    $return[$count]['DepName']   = "";
    $return[$count]['DocNo']   = "";
    $return[$count]['DocDate']   = "";
    $return[$count]['Record']   = "";
    $return[$count]['RecNow']   = "";
    $return[$count]['Total']   = "0.00";
    $return['status'] = "failed";
    $return['form'] = "SelectDocument";
    $return['msg'] = "notchosen";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = str_replace(' ', '%', $DATA["xitem"]);
  $deptCode = $DATA["deptCode"];

  // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
  // mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
  	item_stock.RowID,
		site.HptName,
		department.DepName,
		item_category.CategoryName,
		item_stock.UsageCode,
		item.ItemCode,
		item.ItemName,
		item.UnitCode,
		item_unit.UnitName,
		item_stock.ParQty,
		item_stock.CcQty,
		item_stock.TotalQty
		FROM site
		INNER JOIN department ON site.HptCode = department.HptCode
		INNER JOIN item_stock ON department.DepCode = item_stock.DepCode
		INNER JOIN item ON item_stock.ItemCode = item.ItemCode
		INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
		INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
    WHERE item_stock.DepCode = $deptCode AND  item.ItemName LIKE '%$searchitem%'
    GROUP BY item.ItemCode
    ORDER BY item.ItemCode ASC LImit 100";
    $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $ParQtyx = $Result['ParQty'] - $Result['TotalQty'];

    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['UsageCode'] = $Result['UsageCode'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['ParQty'] =   $ParQtyx;
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;
    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      $return['sql'] = $countM;
      if($MResult['cnt']!=0){
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
      }else{
        $xSql = "SELECT 
          item.UnitCode,
          item_unit.UnitName
        FROM item
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }
    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowItem";
    $return[$count]['RowID'] = "";
    $return[$count]['UsageCode'] = "";
    $return[$count]['itemname'] = "";
    $return[$count]['UnitName'] = "";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowUsageCode($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $searchitem = $DATA["xitem"]; //str_replace(' ', '%', $DATA["xitem"]);

  // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
  // mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
  		item_stock.RowID,
		site.HptName,
		department.DepName,
		item_category.CategoryName,
		item_stock.UsageCode,
		item.ItemCode,
		item.ItemName,
		item.UnitCode,
		item_unit.UnitName,
		item_stock.ParQty,
		item_stock.CcQty,
		item_stock.TotalQty
		FROM site
		INNER JOIN department ON site.HptCode = department.HptCode
		INNER JOIN item_stock ON department.DepCode = item_stock.DepCode
		INNER JOIN item ON item_stock.ItemCode = item.ItemCode
		INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
		INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
		WHERE item.ItemCode = '$searchitem'
        AND item_stock.IsStatus = 7
        LImit 100";
  // (item_stock.IsStatus = 1 OR
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['UsageCode'] = $Result['UsageCode'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;
    $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
	  FROM item_multiple_unit
	  INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
	  WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $xQuery = mysqli_query($conn, $xSql);
    while ($xResult = mysqli_fetch_assoc($xQuery)) {
      $m1 = "MpCode_" . $ItemCode . "_" . $count;
      $m2 = "UnitCode_" . $ItemCode . "_" . $count;
      $m3 = "UnitName_" . $ItemCode . "_" . $count;
      $m4 = "Multiply_" . $ItemCode . "_" . $count;
      $m5 = "Cnt_" . $ItemCode;

      $return[$m1][$count2] = $xResult['MpCode'];
      $return[$m2][$count2] = $xResult['UnitCode'];
      $return[$m3][$count2] = $xResult['UnitName'];
      $return[$m4][$count2] = $xResult['Multiply'];
      $count2++;
    }
    $return[$m5][$count] = $count2;
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowUsageCode";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowUsageCode";
    $return[$count]['RowID'] = "";
    $return[$count]['UsageCode'] = "";
    $return[$count]['itemname'] = "";
    $return[$count]['UnitName'] = "";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getImport($conn, $DATA)
{
  $count = 0;
  $count2 = 0;
  $boolean = false;
  $Sel = $DATA["Sel"];
  $Hotp = $DATA["Hotp"];
  $DocNo = $DATA["DocNo"];
  $xItemStockId = $DATA["xrow"];
  $ItemStockId = explode(",", $xItemStockId);
  $xqty = $DATA["xqty"];
  $nqty = explode(",", $xqty);
  $xweight = $DATA["xweight"];
  $nweight = explode(",", $xweight);
  $xunit = $DATA["xunit"];
  $nunit = explode(",", $xunit);

  $max = sizeof($ItemStockId, 0);

  for ($i = 0; $i < $max; $i++) {
    $iItemStockId = $ItemStockId[$i];
    $iqty = $nqty[$i];
    $iweight = $nweight[$i];
    $iunit1 = 0;
    $iunit2 = $nunit[$i];

    $Sql = "SELECT item_stock.ItemCode,item_stock.UsageCode,item.UnitCode,item_stock.ParQty,item_stock.TotalQty
		  FROM item_stock
		  INNER JOIN item ON item_stock.ItemCode = item.ItemCode
		  WHERE RowID = $iItemStockId";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode  = $Result['ItemCode'];
      $UsageCode = $Result['UsageCode'];
      $iunit1    = $Result['UnitCode'];
      $ParQty    = $Result['ParQty'];
      
    }

    $Sql = "SELECT COUNT(*) as Cnt
		  FROM stock_in_detail
		  INNER JOIN item  ON stock_in_detail.ItemCode = item.ItemCode
		  INNER JOIN stock_in ON stock_in.DocNo = stock_in_detail.DocNo
		  WHERE stock_in.DocNo = '$DocNo'
		  AND item.ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $chkUpdate = $Result['Cnt'];
    }
    $iqty2 = $iqty;
    if ($iunit1 != $iunit2) {
      $Sql = "SELECT item_multiple_unit.Multiply
			  FROM item_multiple_unit
			  WHERE item_multiple_unit.UnitCode = $iunit1
			  AND item_multiple_unit.MpCode = $iunit2";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Multiply = $Result['Multiply'];
        $iqty2 = $iqty / $Multiply;
      }
    }

    if ($chkUpdate == 0) {
      if ($Sel == 1) {
        $Sql = "INSERT INTO stock_in_detail
            (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
            VALUES
            ('$DocNo','$ItemCode',$iunit2,$iqty2,$iweight,0)";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO stock_in_detail_sub
            (DocNo,ItemCode,UsageCode)
            VALUES
            ('$DocNo','$ItemCode','$UsageCode')";
        mysqli_query($conn, $Sql);
        $Sql = "UPDATE item_stock SET IsStatus = 3
            WHERE UsageCode = '$UsageCode'";
        mysqli_query($conn, $Sql);
      }
    } else {
      if ($Sel == 1) {
        $Sql = "UPDATE stock_in_detail
          SET Weight = $iweight,Qty = (Qty+$iqty2)
          WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
        mysqli_query($conn, $Sql);
      } else {
        $Sql = "INSERT INTO stock_in_detail_sub
              (DocNo,ItemCode,UsageCode)
              VALUES
              ('$DocNo','$ItemCode','$UsageCode')";
        mysqli_query($conn, $Sql);
        $Sql = "UPDATE item_stock SET IsStatus = 3
              WHERE UsageCode = '$UsageCode'";
        mysqli_query($conn, $Sql);
      }
    }
  }
  if ($Sel == 2) {
    $n = 0;
    $Sql = "SELECT COUNT(*) AS Qty FROM stock_in_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $Qty[$n] = $Result['Qty'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xQty = $Qty[$i];
      // mysqli_query($conn,"INSERT INTO log ( log ) VALUES ('$n :: $xQty :: $chkUpdate :: $iweight')");
      if ($chkUpdate == 0) {
        $Sql = "INSERT INTO stock_in_detail
              (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
              VALUES
              ('$DocNo','$ItemCode',$iunit2,$xQty,0,0)";
      } else {
        $Sql = "UPDATE stock_in_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      }
      mysqli_query($conn, $Sql);
    }
  }

    $n = 0;
    $Sql = "SELECT UsageCode FROM stock_in_detail_sub WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $zUsageCode[$n] = $Result['UsageCode'];
      $n++;
    }
	$DepCode = 1;
	$Sql = "SELECT DepCode FROM department
	WHERE department.HptCode = '$Hotp' AND department.IsDefault = 1
	ORDER BY DepCode ASC LIMIT 1";
	$meQuery = mysqli_query($conn, $Sql);
	while ($Result = mysqli_fetch_assoc($meQuery)) {
	  $DepCode = $Result['DepCode'];
	}
    for ($i = 0; $i < $n; $i++) {
      $xUsageCode = $zUsageCode[$i];
	  $Sql = "UPDATE item_stock SET DepCode = $DepCode WHERE UsageCode = '$xUsageCode'";
      $meQuery = mysqli_query($conn, $Sql);
    }



    $Sql = "SELECT ItemCode,Qty FROM stock_in_detail WHERE stock_in_detail.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = getCnt($conn,$DepCode,$Result['ItemCode']);
        //mysqli_query($conn,"INSERT INTO log ( log ) VALUES ('$Cnt :: ".$Result['ItemCode']." :: ".$Result['Qty']."')");
        if( $Cnt == 0 ){
            $xSql = "INSERT INTO item_stock_detail
            (ItemCode,DepCode,ParQty,Qty)
            VALUES
            ('".$Result['ItemCode']."',$DepCode,$ParQty,".$Result['Qty'].")";
        }else{
            $xSql = "UPDATE item_stock_detail SET Qty = (Qty + ".$Result['Qty'].") WHERE ItemCode = '".$Result['ItemCode']."' AND DepCode = $DepCode";
        }
        mysqli_query($conn, $xSql);
    }

    $SqlX = "SELECT ItemCode,Qty FROM stock_in_detail WHERE stock_in_detail.DocNo = '$DocNo'";
    $meQueryX = mysqli_query($conn, $SqlX);

    while ($ResultX = mysqli_fetch_assoc($meQueryX)) {
      $ItemCodeX = $ResultX['ItemCode'];
      $QtyX = $ResultX['Qty'];
      $updateSql = "UPDATE item_stock SET TotalQty = (TotalQty + $QtyX) WHERE ItemCode = '$ItemCodeX' AND DepCode = $DepCode";
      mysqli_query($conn, $updateSql);
    }

    ShowDetail($conn, $DATA);

}

function getCnt($conn,$deptcode,$itemcode){
    $Cnt = 0;
    $Sql = "SELECT COUNT(*) AS Cnt
    FROM item_stock_detail
    WHERE item_stock_detail.ItemCode = '$itemcode'
    AND item_stock_detail.DepCode = $deptcode";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }
    return $Cnt;
}


function UpdateDetailQty($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Qty  =  $DATA["Qty"];
  $OleQty =  $DATA["OleQty"];
  $UnitCode =  $DATA["unitcode"];
  $Sql = "UPDATE stock_in_detail
	SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
	WHERE stock_in_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function UpdateDetailWeight($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Weight  =  $DATA["Weight"];
  $Price  =  $DATA["Price"];
  $isStatus = $DATA["isStatus"];
  $DocNo = $DATA["DocNo"];

  //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
  //	mysqli_query($conn,$Sqlx);

  $Sql = "UPDATE stock_in_detail
	SET Weight = $Weight
	WHERE stock_in_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $UnitCode =  $DATA["unitcode"];
  $qty =  $DATA["qty"];
  $Sql = "UPDATE stock_in_detail
	SET UnitCode = $UnitCode
	WHERE stock_in_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function DeleteItem($conn, $DATA)
{
  $RowID    = $DATA["rowid"];
  $DocNo    = $DATA["DocNo"];
  $DepCode  = $DATA["DepCode"];
  $ItemCode = "";
    $Sql = "SELECT ItemCode,Qty FROM stock_in_detail WHERE stock_in_detail.Id = $RowID";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ItemCode = $Result['ItemCode'];
        $Cnt = getCnt($conn,$DepCode,$ItemCode);
        //mysqli_query($conn,"INSERT INTO log ( log ) VALUES ('$Cnt / $DepCode / $ItemCode')");
        if( $Cnt == 0 ){
            $xSql = "INSERT INTO item_stock_detail
            (ItemCode,DepCode,Qty)
            VALUES
            ('$ItemCode',$DepCode,".$Result['Qty'].")";
        }else{
            $xSql = "UPDATE item_stock_detail SET Qty = (Qty - ".$Result['Qty'].") WHERE ItemCode = '$ItemCode' AND DepCode = $DepCode";
        }
        mysqli_query($conn, $xSql);
    }

  $n = 0;
  $Sql = "SELECT stock_in_detail_sub.UsageCode,stock_in_detail.ItemCode
  FROM stock_in_detail
  INNER JOIN stock_in_detail_sub ON stock_in_detail.DocNo = stock_in_detail_sub.DocNo
  WHERE  stock_in_detail.Id = $RowID";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $ItemCode = $Result['ItemCode'];
    $UsageCode[$n] = $Result['UsageCode'];
    $n++;
  }

  for ($i = 0; $i < $n; $i++) {
    $xUsageCode = $UsageCode[$i];
    $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE UsageCode = '$xUsageCode'";
    mysqli_query($conn, $Sql);
  }

    $Sql = "DELETE FROM stock_in_detail_sub
	WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
  mysqli_query($conn, $Sql);

  $Sql = "DELETE FROM stock_in_detail WHERE stock_in_detail.Id = $RowID";
  mysqli_query($conn, $Sql);

  ShowDetail($conn, $DATA);
}

function SaveBill($conn, $DATA)
{
  $DocNo = $DATA["xdocno"];
  $isStatus = $DATA["isStatus"];
  $Sql = "UPDATE stock_in SET IsStatus = $isStatus WHERE stock_in.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);

  ShowDocument($conn, $DATA);
}


function ShowDetail($conn, $DATA)
{
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  //==========================================================
  $Sql = "SELECT
  stock_in_detail.Id,
  stock_in_detail.ItemCode,
  item.ItemName,
  item.UnitCode AS UnitCode1,
  item_unit.UnitName,
  stock_in_detail.UnitCode AS UnitCode2,
  stock_in_detail.Weight,
  stock_in_detail.Qty,
  item.UnitCode
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN stock_in_detail ON stock_in_detail.ItemCode = item.ItemCode
  INNER JOIN item_unit ON stock_in_detail.UnitCode = item_unit.UnitCode
  WHERE stock_in_detail.DocNo = '$DocNo'
  ORDER BY stock_in_detail.Id DESC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID']      = $Result['Id'];
    $return[$count]['ItemCode']   = $Result['ItemCode'];
    $return[$count]['ItemName']   = $Result['ItemName'];
    $return[$count]['UnitCode']   = $Result['UnitCode2'];
    $return[$count]['UnitName']   = $Result['UnitName'];
    $return[$count]['Weight']     = $Result['Weight'];
    $return[$count]['Qty']         = $Result['Qty'];
    $UnitCode                     = $Result['UnitCode1'];
    $ItemCode                     = $Result['ItemCode'];
    $count2 = 0;
    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      $return['sql'] = $countM;
      if($MResult['cnt']!=0){
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
      }else{
        $xSql = "SELECT 
          item.UnitCode,
          item_unit.UnitName
        FROM item
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = 1;
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = 1;
          $count2++;
        }
      }
    }
    $return[$m5][$count] = $count2;
    //================================================================
    $Total += $Result['Weight'];
    //================================================================
    $count++;
    $boolean = true;
  }

  if ($count == 0) $Total = 0;

  $Sql = "UPDATE stock_in SET Total = $Total WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  $return[0]['Total']    = round($Total, 2);

  $return['Row'] = $count;
  //==========================================================

  $boolean = true;
  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "ShowDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "ShowDetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function CancelBill($conn, $DATA){
  $DocNo = $DATA["DocNo"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "UPDATE stock_in SET IsStatus = 2  WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  // =============================================================================
  $SqlX = "SELECT ItemCode, Qty, DepCode
  FROM stock_in_detail 
  INNER JOIN stock_in ON stock_in.DocNo = stock_in_detail.DocNo
  WHERE stock_in_detail.DocNo = '$DocNo'";
  $meQueryX = mysqli_query($conn, $SqlX);

  while ($ResultX = mysqli_fetch_assoc($meQueryX)) {
    $ItemCodeX = $ResultX['ItemCode'];
    $QtyX = $ResultX['Qty'];
    $DepCodeX = $ResultX['DepCode'];
    $updateSql = "UPDATE item_stock SET TotalQty = (TotalQty - $QtyX) WHERE ItemCode = '$ItemCodeX' AND DepCode = $DepCodeX";
    mysqli_query($conn, $updateSql);
  }

}
//==========================================================
//
//==========================================================
if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'OnLoadPage') {
    OnLoadPage($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getDepartment') {
    getDepartment($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowUsageCode') {
    ShowUsageCode($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDocument') {
    ShowDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SelectDocument') {
    SelectDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CreateDocument') {
    CreateDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CancelDocNo') {
    CancelDocNo($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getImport') {
    getImport($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDetail') {
    ShowDetail($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateDetailQty') {
    UpdateDetailQty($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'updataDetail') {
    updataDetail($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateDetailWeight') {
    UpdateDetailWeight($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'DeleteItem') {
    DeleteItem($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'SaveBill') {
    SaveBill($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'CancelBill') {
    CancelBill($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'UpdateRefDocNo') {
    UpdateRefDocNo($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
