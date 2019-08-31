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

function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
		  AND department.IsStatus = 0
      ORDER BY department.DepCode DESC";
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
  $lang = $_SESSION['lang'];

  //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
  //     mysqli_query($conn,$Sql);

  $Sql = "SELECT CONCAT('CM',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
CURRENT_TIME() AS RecNow
FROM claim
WHERE DocNo Like CONCAT('CM',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
AND HptCode = '$hotpCode'
ORDER BY DocNo DESC LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($lang =='en'){
    $date2 = explode("-", $Result['DocDate']);
    $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
  }else if ($lang == 'th'){
    $date2 = explode("-", $Result['DocDate']);
    $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
  }

    $DocNo = $Result['DocNo'];
    $return[0]['DocNo']   = $Result['DocNo'];
    $return[0]['DocDate'] = $newdate;
    $return[0]['RecNow']  = $Result['RecNow'];
    $count = 1;
    //	  $Sql = "INSERT INTO log ( log ) VALUES ('".$Result['DocDate']." : ".$Result['DocNo']."')";
    //      mysqli_query($conn,$Sql);
  }

  if ($count == 1) {
    $Sql = "INSERT INTO claim
      ( HptCode,DepCode,DocNo,DocDate,
		RefDocNo,TaxNo,TaxDate,
		DiscountPercent,DiscountBath,
		Total,IsCancel,Detail,
		Modify_Code,Modify_Date )
      VALUES
      ('$hotpCode',$deptCode,'$DocNo',DATE(NOW()),
		'',null,DATE(NOW()),
		0,0,
		0,0,'',
		$userid,NOW() )";
    mysqli_query($conn, $Sql);

    $Sql = "INSERT INTO daily_request
(DocNo,DocDate,DepCode,RefDocNo,Detail,Modify_Code,Modify_Date)
VALUES
('$DocNo',DATE(NOW()),$deptCode,'','Bill Wash',$userid,DATE(NOW()))";
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
  $deptCode = $DATA["deptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $Datepicker = $DATA["Datepicker"];
  $selecta = $DATA["selecta"];
  $lang = $_SESSION['lang'];
  // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "SELECT site.HptName,department.DepName,claim.DocNo,claim.DocDate,claim.Total,users.FName,TIME(claim.Modify_Date) AS xTime,claim.IsStatus
FROM claim
INNER JOIN department ON claim.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON claim.Modify_Code = users.ID ";
  if ($deptCode != null) {
    $Sql .= "WHERE claim.DepCode = $deptCode AND claim.DocNo LIKE '%$DocNo%' ";
  }
  $Sql .= "ORDER BY claim.DocNo DESC LIMIT 500 ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    
    if($lang =='en'){
    $date2 = explode("-", $Result['DocDate']);
    $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
  }else if ($lang == 'th'){
    $date2 = explode("-", $Result['DocDate']);
    $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
  }


    $return[$count]['HptName']   = $Result['HptName'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $newdate;
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
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
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
  $lang = $_SESSION['lang'];
  $Sql = "SELECT   site.HptName,department.DepCode,department.DepName,claim.DocNo,DATE(claim.DocDate) AS DocDate ,claim.Total,users.FName,TIME(claim.Modify_Date) AS xTime,claim.IsStatus
FROM claim
INNER JOIN department ON claim.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON claim.Modify_Code = users.ID
WHERE claim.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    
    if($lang =='en'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
    }else if ($lang == 'th'){
      $date2 = explode("-", $Result['DocDate']);
      $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
    }


    $return[$count]['HptName']   = $Result['HptName'];
      $return[$count]['DepCode']   = $Result['DepCode'];
    $return[$count]['DepName']   = $Result['DepName'];
    $return[$count]['DocNo']   = $Result['DocNo'];
    $return[$count]['DocDate']   = $newdate;
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
    $return[$count]['DepCode']   = "";
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
    item.QtyPerUnit,
    item_unit.UnitName,
    item_stock.ParQty,
    item_stock.CcQty,
    item_stock.TotalQty,
    item.UnitCode2,
    Un2.UnitName2 
FROM site
LEFT JOIN department ON site.HptCode = department.HptCode
LEFT JOIN item_stock ON department.DepCode = item_stock.DepCode
INNER JOIN item ON item_stock.ItemCode = item.ItemCode
INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
LEFT JOIN item_unit AS Un2 ON item.UnitCode2 = Un2.UnitCode
WHERE item_stock.ItemCode LIKE '%$searchitem%' OR item.ItemName LIKE '%$searchitem%'
  GROUP BY item.ItemCode
  ORDER BY item.ItemCode ASC LImit 100";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['UsageCode'] = $Result['UsageCode'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
      $return[$count]['QtyPerUnit'] = $Result['QtyPerUnit'];
      $return[$count]['UnitCode2'] = $Result['UnitCode2'];
      $return[$count]['UnitName2'] = $Result['UnitName2'];
    $ItemCode = $Result['ItemCode'];
    $UnitCode = $Result['UnitCode'];
    $count2 = 0;
    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      $return['sqls'] = $countM;
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
    $return['msg'] = "notfound";
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

  //	$Sqlx = "INSERT INTO log ( log ) VALUES ('Row : $max')";
  //	mysqli_query($conn,$Sqlx);

  for ($i = 0; $i < $max; $i++) {
    $iItemStockId = $ItemStockId[$i];
    $iqty = $nqty[$i];
    $iweight = $nweight[$i];
    $iunit1 = 0;;
    $iunit2 = $nunit[$i];

    $Sql = "SELECT item_stock.ItemCode,item_stock.UsageCode,item.UnitCode
		  FROM item_stock
		  INNER JOIN item ON item_stock.ItemCode = item.ItemCode
		  WHERE RowID = $iItemStockId";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode = $Result['ItemCode'];
      $iunit1   = $Result['UnitCode'];
    }

    $Sqlx = "SELECT item.UnitCode2 FROM item WHERE ItemCode ='$ItemCode'";
    $meQueryx = mysqli_query($conn, $Sqlx);
    while ($Result = mysqli_fetch_assoc($meQueryx)) {
      $UnitCode2 = $Result['UnitCode2'];
    }

    $Sql = "SELECT COUNT(*) as Cnt
		  FROM claim_detail
		  INNER JOIN item  ON claim_detail.ItemCode = item.ItemCode
		  INNER JOIN claim ON claim.DocNo = claim_detail.DocNo
		  WHERE claim.DocNo = '$DocNo'
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


    /*	$Sqlx = "INSERT INTO log ( log ) VALUES ('$chkUpdate / $DocNo / $ItemCode : $iqty :: $iweight ::: [$iunit1,$iunit2]')";
	mysqli_query($conn,$Sqlx);*/

    if ($chkUpdate == 0) {
      $Sql = "INSERT INTO claim_detail
			(DocNo,ItemCode,UnitCode1,UnitCode2,Qty1,Qty2,Weight,IsCancel,Price,Total,UnitCode3)
			VALUES
			('$DocNo','$ItemCode',$iunit1,$iunit2,$iqty2,$iqty,$iweight,0,0,0,$UnitCode2)";
      mysqli_query($conn, $Sql);
    } else {
      $Sql = "UPDATE claim_detail
			SET Qty2 = (Qty2 + $iqty),Weight = $iweight
			WHERE DocNo = '$DocNo'
		    AND ItemCode = '$ItemCode'";
      mysqli_query($conn, $Sql);
    }
  }
  ShowDetail($conn, $DATA);
}

function UpdateDetailQty($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $Qty  =  $DATA["Qty"];
  $OleQty =  $DATA["OleQty"];
  $UnitCode =  $DATA["unitcode"];
  $Sql = "UPDATE claim_detail
	SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
	WHERE claim_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function UpdateDetailWeight($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $Weight  =  $DATA["Weight"];
    // $Price  =  $DATA["Price"];
    $isStatus = $DATA["isStatus"];
    $Sql = "UPDATE claim_detail
    SET Weight = $Weight
    -- ,Total = $Price
    WHERE claim_detail.Id = $RowID";
    $return['sql']=$Sql;
    echo json_encode($return);

    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

function updataDetail($conn, $DATA)
{
  $RowID  = $DATA["Rowid"];
  $UnitCode =  $DATA["unitcode"];
  $qty =  $DATA["qty"];
  $Sql = "UPDATE claim_detail
	SET UnitCode2 = $UnitCode,Qty2 = $qty
	WHERE claim_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function DeleteItem($conn, $DATA)
{
  $RowID  = $DATA["rowid"];
  $Sql = "DELETE FROM claim_detail
	WHERE claim_detail.Id = $RowID";
  mysqli_query($conn, $Sql);
  ShowDetail($conn, $DATA);
}

function SaveBill($conn, $DATA)
{
  $total = $DATA["total"];
  $DocNo = $DATA["xdocno"];

  $isStatus = $DATA["isStatus"];
  $Sql = "UPDATE claim SET IsStatus = $isStatus , Total = $total WHERE claim.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  $return['sql'] = $total;
  $return['sql1'] = $Sql;

   echo json_encode($return);
  $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  if ($isStatus == 1) {
    ShowDocument_sub($conn, $DATA);
  } else {
    SelectDocument($conn, $DATA);
  }
}


function ShowDetail($conn, $DATA)
{
  session_start();
  $count = 0;
  $Total = 0;
  $boolean = false;
  $DocNo = $DATA["DocNo"];
  $HptCode = $_SESSION['HptCode'];
  //==========================================================
  $Sql = "SELECT
  claim_detail.Id,
  claim_detail.ItemCode,
  item.ItemName,
  item_unit.UnitName,
  (SELECT UnitName2 FROM item_unit WHERE UnitCode = UnitCode3) AS UnitName2,
  claim_detail.UnitCode1,
  claim_detail.Qty1,
  claim_detail.UnitCode2,
  claim_detail.Qty2,
  claim_detail.Weight,
  claim_detail.Total , 
  item.CategoryCode , 
	category_price.Price
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  INNER JOIN claim_detail ON claim_detail.ItemCode = item.ItemCode
  INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  WHERE claim_detail.DocNo = '$DocNo' AND HptCode = '$HptCode'
  ORDER BY claim_detail.Id DESC";
  $return['sql']=$Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID']        = $Result['Id'];
    $return[$count]['ItemCode']     = $Result['ItemCode'];
    $return[$count]['ItemName']     = $Result['ItemName'];
    $return[$count]['UnitName']     = $Result['UnitName'];
    $return[$count]['UnitCode1']    = $Result['UnitCode1'];
    $return[$count]['UnitCode2']    = $Result['UnitCode2'];
    $return[$count]['UnitName2']    = $Result['UnitName2'];
    $return[$count]['Qty1']         = $Result['Qty1'];
    $return[$count]['Qty2']         = $Result['Qty2'];
    $return[$count]['Weight']       = $Result['Weight'];
    $return[$count]['Price']        = $Result['Total'];
    $UnitCode                       = $Result['UnitCode1'];
    $ItemCode                       = $Result['ItemCode'];
    $UniCode2 					            = $Result['UnitCode2'];
    $Qty                            = $Result['Qty2'];
    $weight                         = $Result['Weight'];
    $CategoryCode                   = $Result['CategoryCode'];
    $PriceCategory                  = $Result['Price'];

    
    $count2 = 0;


    $P_Unit1 = "SELECT item_multiple_unit.PriceUnit, item_multiple_unit.Multiply FROM item_multiple_unit 
      WHERE item_multiple_unit.ItemCode = '$ItemCode' AND item_multiple_unit.MpCode = $UniCode2 ";
      $P_Query1 = mysqli_query($conn, $P_Unit1);
      while ($P_Result1 = mysqli_fetch_assoc($P_Query1)) {
        $Price1  = $P_Result1['PriceUnit'];
        $Multiply1  = $P_Result1['Multiply'];
      }
         $cal =  ($PriceCategory*$Price1) / $Multiply1;
         $return[$count]['cal']   = $cal;
         for ($i = 0; $i < $Qty; $i++) {
          $total = $total + $cal;
                    // ============================================================
                    $insert = "UPDATE claim_detail SET  Price = $cal WHERE ItemCode = '$ItemCode ' AND DocNo = '$DocNo'";
                    $INQuery = mysqli_query($conn, $insert);
                    // ============================================================
      }
          


  if( $UniCode2 >0){
    $PriceUnit = "SELECT item_multiple_unit.PriceUnit FROM item_multiple_unit 
      WHERE item_multiple_unit.ItemCode = '$ItemCode' AND item_multiple_unit.MpCode = $UniCode2 ";
      $PUQuery = mysqli_query($conn, $PriceUnit);
      while ($PUResult = mysqli_fetch_assoc($PUQuery)) {
        $return[$count]['CusPrice']   = ($cal * $Result['Qty2']) * $Result['Weight'] ;
        $return['TotalPrice']  += $return[$count]['CusPrice'];
        $return[$count]['PriceUnit']   = $PriceCategory;
        // ============================================================
        $CusPrice = $return[$count]['CusPrice'];
        $insert = "UPDATE claim_detail SET Total = $CusPrice  WHERE ItemCode = '$ItemCode' AND DocNo = '$DocNo'";
        $INQuery = mysqli_query($conn, $insert);
        // ============================================================
      }
  }else if ($weight==0 ){
    $Price = "SELECT item_multiple_unit.PriceUnit FROM item_multiple_unit 
    WHERE item_multiple_unit.ItemCode = '$ItemCode' AND item_multiple_unit.MpCode = $UniCode2 ";
    $PQuery = mysqli_query($conn, $Price);
    while ($PResult = mysqli_fetch_assoc($PQuery)) {
      $return[$count]['CusPrice']   = $Result['Qty2'] * $cal ;
      $return['TotalPrice']  += $return[$count]['CusPrice'];
        // ============================================================
        $CusPrice = $return[$count]['CusPrice'];
        $insert = "UPDATE claim_detail SET Total = $CusPrice  WHERE ItemCode = '$ItemCode' AND DocNo = '$DocNo'";
        $INQuery = mysqli_query($conn, $insert);
        // ============================================================
    }
  }






    $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode 
    AND item_multiple_unit.ItemCode = '$ItemCode'";
    $return['sqlxxx'] = $countM;
    $MQuery = mysqli_query($conn, $countM);
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
    if($MResult['cnt'] !=0){
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

    $return[$m1][$count2]   = $xResult['MpCode'];
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
    $Total += $Result['Total'];
    $count++;
    $boolean = true;
  }

  $return['Row'] = $count;
  //==========================================================
  if ($count == 0) $Total = 0;
  $Sql = "UPDATE claim SET Total = $Total WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Sql);
  $return[0]['Total']    = round($Total, 2);
  //================================================================
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

function CancelBill($conn, $DATA)
{
  $DocNo = $DATA["DocNo"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "UPDATE claim SET IsStatus = 2  WHERE DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  ShowDocument_sub($conn, $DATA);
}


function ShowDocument_sub($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $DocNo = $DATA["xdocno"];
  $DocNo = "";
  $Datepicker = $DATA["Datepicker"];
  // $Sql = "INSERT INTO log ( log ) VALUES ('$max : $DocNo')";
  // mysqli_query($conn,$Sql);
  $Sql = "SELECT site.HptName,department.DepName,claim.DocNo,claim.DocDate,claim.Total,users.FName,TIME(claim.Modify_Date) AS xTime,claim.IsStatus
FROM claim
INNER JOIN department ON claim.DepCode = department.DepCode
INNER JOIN site ON department.HptCode = site.HptCode
INNER JOIN users ON claim.Modify_Code = users.ID
WHERE claim.DepCode = $deptCode
AND claim.DocNo LIKE '%$DocNo%'
ORDER BY claim.DocNo DESC LIMIT 500";
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
    $return['form'] = "ShowDocument_sub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "ShowDocument_sub";
    echo json_encode($return);
    mysqli_close($conn);
    die;
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
  } elseif ($DATA['STATUS'] == 'ShowDocument') {
    ShowDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'ShowDocument_sub') {
    ShowDocument_sub($conn, $DATA);
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
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
