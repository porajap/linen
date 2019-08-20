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
  -- AND department.IsDefault = 1
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

  $Sql = "SELECT CONCAT('DW',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
  LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
  CURRENT_TIME() AS RecNow
  FROM draw
  INNER JOIN department on draw.DepCode = department.DepCode
  WHERE DocNo Like CONCAT('DW',lpad('$hotpCode', 3, 0),SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
  AND department.HptCode = '$hotpCode'
  ORDER BY DocNo DESC LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $Result['DocNo'];
    $return[0]['DocNo']   = $Result['DocNo'];
    $return[0]['DocDate'] = $Result['DocDate'];
    $return[0]['RecNow']  = $Result['RecNow'];
    $count = 1;
  }

  if ($count == 1) {
    $Sql = "INSERT INTO draw
    ( DocNo,DocDate,DepCode,RefDocNo,
      TaxNo,TaxDate,DiscountPercent,DiscountBath,
      Total,IsCancel,Detail,
      draw.Modify_Code,draw.Modify_Date )
      VALUES
      ( '$DocNo',NOW(),$deptCode,'',
      0,DATE(NOW()),0,0,
      0,0,'',
      $userid,NOW() )";
      mysqli_query($conn, $Sql);

      $Sql = "INSERT INTO daily_request
      (DocNo,DocDate,DepCode,RefDocNo,Detail,Modify_Code,Modify_Date)
      VALUES
      ('$DocNo',DATE(NOW()),$deptCode,'','Shelf Count',$userid,DATE(NOW()))";
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
    $selecta = $DATA["selecta"];
    //$Datepicker = $DATA["Datepicker"];

    //	 $Sql = "INSERT INTO log ( log ) VALUES ('$deptCode : $DocNo')";
    //     mysqli_query($conn,$Sql);

    $Sql = "SELECT site.HptName,
    department.DepName,
    draw.DocNo,
    DATE(draw.DocDate) AS DocDate,
    draw.Total,
    users.FName,
    TIME(draw.Modify_Date)
    AS xTime,draw.IsStatus,
    site.HptCode
    FROM draw
    INNER JOIN department ON draw.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    INNER JOIN users ON draw.Modify_Code = users.ID ";
      if ($deptCode != null) {
        $Sql .= "WHERE draw.DepCode = $deptCode AND draw.DocNo LIKE '%$DocNo%' ";
      }else{
        $Sql.="WHERE site.HptCode = '$Hotp'";

      }
    // if ($selecta == 0) {
    //   $Sql.= "WHERE draw.DepCode = $deptCode AND draw.DocNo LIKE '%$DocNo%' ";
    // }elseif($selecta==1){
    //   $Sql.="WHERE site.HptCode = '$Hotp'";
    // }
    $Sql.= "ORDER BY draw.DocNo DESC LIMIT 500 ";
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
    $Sql = "SELECT   site.HptName,department.DepName,draw.DocNo,draw.DocDate,draw.Total,users.FName,TIME(draw.Modify_Date) AS xTime,draw.IsStatus,draw.RefDocNo
    FROM draw
    INNER JOIN department ON draw.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    INNER JOIN users ON draw.Modify_Code = users.ID
    WHERE draw.DocNo = '$DocNo'";
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
      $return[$count]['RefDocNo'] = $Result['RefDocNo'];
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
//
  function ShowItem($conn, $DATA)
  {
    $count = 0;
    $boolean = false;
    $searchitem = str_replace(' ', '%', $DATA["xitem"]);
  $deptCode = $DATA["deptCode"];
  $Hotp = $DATA["Hotp"];
    // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
    // mysqli_query($conn,$Sqlx);
    $Sql = "SELECT department.DepCode,department.DepName
    FROM department
    WHERE department.HptCode = '$Hotp'
    AND department.IsDefault = 1
    AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCodex = $Result['DepCode'];
  }
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
    WHERE item_stock.DepCode = $DepCodex AND  item.ItemName LIKE '%$searchitem%'
    GROUP BY item.ItemCode
    ORDER BY item.ItemName ASC LImit 100";
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
    AND item_stock.IsStatus = 1
    ORDER BY item.ItemName ASC LImit 100";
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
    $DocNo = $DATA["DocNo"];
    $DeptCode = $DATA["deptCode"];
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

      $Sql = "SELECT item_stock.ItemCode,item_stock.UsageCode,item.UnitCode,item_stock.ParQty,item_stock.CcQty,item_stock.TotalQty
      FROM item_stock
      INNER JOIN item ON item_stock.ItemCode = item.ItemCode
      WHERE RowID = $iItemStockId";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ItemCode   = $Result['ItemCode'];
        $UsageCode  = $Result['UsageCode'];
        $iunit1     = $Result['UnitCode'];
        $ParQty     = $Result['ParQty'];
        $CcQty      = $Result['CcQty'];
        $TotalQty   = $Result['TotalQty'];
      }

      $Sql = "SELECT COUNT(*) as Cnt
      FROM draw_detail
      INNER JOIN item  ON draw_detail.ItemCode = item.ItemCode
      INNER JOIN dirty ON dirty.DocNo = draw_detail.DocNo
      WHERE dirty.DocNo = '$DocNo'
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
          $Sql = "INSERT INTO draw_detail
          (DocNo,ItemCode,UnitCode,ParQty,CcQty,TotalQty,IsCancel)
          VALUES
          ('$DocNo','$ItemCode',$iunit2,$TotalQty,$iqty2,$iqty,0)";
          mysqli_query($conn, $Sql);
        } else {
          $Sql = "INSERT INTO draw_detail_sub
          (DocNo,ItemCode,UsageCode)
          VALUES
          ('$DocNo','$ItemCode','$UsageCode')";
          mysqli_query($conn, $Sql);
          $Sql = "UPDATE item_stock SET IsStatus = 2
          WHERE UsageCode = '$UsageCode'";
          mysqli_query($conn, $Sql);
        }
      } else {
        if ($Sel == 1) {
          $Sql = "UPDATE draw_detail
          SET TotalQty = (Qty+$iqty2),CcQty = $iqty2,ParQty = $ParQty
          WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
          mysqli_query($conn, $Sql);
        } else {
          $Sql = "INSERT INTO draw_detail_sub
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
      $Sql = "SELECT COUNT(*) AS Qty FROM draw_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Qty[$n] = $Result['Qty'];
        $n++;
      }
      for ($i = 0; $i < $n; $i++) {
        $xQty = $Qty[$i];
        // $Sqlx = "INSERT INTO log ( log ) VALUES ('$n :: $xQty :: $ParQty')";
        // mysqli_query($conn, $Sqlx);
        if ($chkUpdate == 0) {
          $Sql = "INSERT INTO draw_detail
          (DocNo,ItemCode,UnitCode,ParQty,CcQty,TotalQty,IsCancel)
          VALUES
          ('$DocNo','$ItemCode',$iunit2,$ParQty,$xQty,($ParQty-$xQty),0)";
        } else {
          $Sql = "UPDATE draw_detail
          SET TotalQty = ($ParQty+$xQty),CcQty = $xQty,ParQty = $ParQty
          WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
        }
        mysqli_query($conn, $Sql);
      }
    }
    $sql_update =  "SELECT
    draw_detail.ItemCode,
    draw_detail.ParQty,
    draw_detail.CcQty,
    draw_detail.TotalQty
    FROM draw_detail
    WHERE draw_detail.DocNo = '$DocNo'";
    $result = mysqli_query( $conn, $sql_update);
    while ($row = mysqli_fetch_array($result)) {
      $xItemCode[$n] 	= $row["ItemCode"];
      $xParQty[$n] 	= $row["ParQty"];
      $xCcQty[$n] 	= $row["CcQty"];
      $xTotalQty[$n] 	= $row["TotalQty"];
      $n++;
    }
    for($i=0;$i<$n;$i++){
      $ItemCode = $xItemCode[$i];
      $ParQty = $xParQty[$i];
      $CcQty = $xCcQty[$i];
      $TotalQty = $xTotalQty[$i];
      mysqli_query($conn, "UPDATE item_stock SET ParQty=$ParQty,CcQty=$CcQty,TotalQty=$TotalQty WHERE ItemCode = '$ItemCode'");
    }

    $n = 0;
    $Sql = "SELECT UsageCode FROM draw_detail_sub WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $zUsageCode[$n] = $Result['UsageCode'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $xUsageCode = $zUsageCode[$i];
      $Sql = "UPDATE item_stock SET DepCode = $DeptCode WHERE UsageCode = '$xUsageCode'";
      $meQuery = mysqli_query($conn, $Sql);
    }

    $Sql = "SELECT SUM( draw_detail.TotalQty ) AS Qty
    FROM draw_detail
    WHERE draw_detail.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ttQty = $Result['Qty'];
    }
    $Sql = "UPDATE draw SET Total = $ttQty WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);

    ShowDetail($conn, $DATA);
  }

  function UpdateDetailQty($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $CcQty  =  $DATA["CcQty"];
    $UnitCode =  $DATA["unitcode"];
    $Sql = "UPDATE draw_detail
    INNER JOIN item ON item.ItemCode = draw_detail.ItemCode
    SET draw_detail.ParQty = item.ParQty,
    CcQty = $CcQty,TotalQty = CcQty
    WHERE draw_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function UpdateDetailQty_key($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $CcQty  =  $DATA["CcQty"];
    $TotalQty  =  $DATA["TotalQty"];
    $UnitCode =  $DATA["unitcode"];
    $Sql = "UPDATE draw_detail
    SET CcQty = $CcQty,TotalQty = $TotalQty
    WHERE draw_detail.Id = $RowID";
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

    $Sql = "UPDATE draw_detail
    SET Weight = $Weight
    WHERE draw_detail.Id = $RowID";
    mysqli_query($conn, $Sql);

    //	$wTotal = 0;
    //	$Sql = "SELECT SUM(Weight) AS wTotal
    //	FROM draw_detail
    //	WHERE DocNo = '$DocNo'";
    //	$meQuery = mysqli_query($conn,$Sql);
    //	while ($Result = mysqli_fetch_assoc($meQuery)) {
    //		$wTotal  	= $Result['wTotal'];
    //	}
    //    $Sql = "UPDATE draw SET Total = $wTotal WHERE DocNo = '$DocNo'";
    //  	mysqli_query($conn,$Sql);

    ShowDetail($conn, $DATA);
  }

  function updataDetail($conn, $DATA)
  {
    $RowID  = $DATA["Rowid"];
    $UnitCode =  $DATA["unitcode"];
    $qty =  $DATA["qty"];
    $Sql = "UPDATE draw_detail
    SET UnitCode2 = $UnitCode,Qty2 = $qty
    WHERE draw_detail.Id = $RowID";
    mysqli_query($conn, $Sql);
    ShowDetail($conn, $DATA);
  }

  function DeleteItem($conn, $DATA)
  {
    $RowID = $DATA["rowid"];
    $DocNo = $DATA["DocNo"];
    $n = 0;
    $Sql = "SELECT draw_detail_sub.UsageCode,draw_detail.ItemCode
    FROM draw_detail
    INNER JOIN draw_detail_sub ON draw_detail.DocNo = draw_detail.DocNo
    WHERE  draw_detail.Id = $RowID";
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

    $Sql = "DELETE FROM draw_detail_sub
    WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
    mysqli_query($conn, $Sql);

    $Sql = "DELETE FROM draw_detail
    WHERE draw_detail.Id = $RowID";
    mysqli_query($conn, $Sql);

    ShowDetail($conn, $DATA);
  }

  function SaveBill($conn, $DATA)
  {
    $DocNo = $DATA["xdocno"];
    $DocNo2 = $DATA["xdocno2"];
    $hotpCode = $DATA["hotpCode"];
    $Sql = "UPDATE shelfcount SET IsRef = 1 , Delivery = DATE(NOW()) WHERE shelfcount.DocNo = '$DocNo2'";
    mysqli_query($conn, $Sql);

    $Sql = "SELECT
    SUM(draw_detail.ParQty) AS Summ
    FROM
    draw_detail
    WHERE draw_detail.DocNo = '$DocNo'";
    $meQ = mysqli_query($conn, $Sql);
    while ($Res = mysqli_fetch_assoc($meQ)) {
      $Sum = $Res['Summ'];
    }
    $isStatus = $DATA["isStatus"];
    $Sql = "UPDATE draw SET IsStatus = $isStatus,Total = $Sum WHERE draw.DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);

    $isStatus = $DATA["isStatus"];
    $Sql = "UPDATE daily_request SET IsStatus = $isStatus WHERE daily_request.DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
    $DATA['xdocno'] = "";
    $n = 0;
    
    $Sql = "SELECT draw_detail_sub.UsageCode,draw_detail.ItemCode
    FROM draw_detail
    INNER JOIN draw_detail_sub ON draw_detail.DocNo = draw_detail_sub.DocNo
    WHERE  draw_detail.DocNo = '$DocNo'";
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

      // =======================================================================================
      // Set Stock Qty
      // =======================================================================================
      $zDepCode = "";
      $zHotp    = "";
      $zDept    = "";

      $sql_update = "SELECT draw.DepCode,department.HptCode
                    FROM draw
                    INNER JOIN department ON draw.DepCode = department.DepCode
                    WHERE draw.DocNo = '$DocNo'";
      $result = mysqli_query( $conn, $sql_update);
      while ($row = mysqli_fetch_array($result)) {
          $zDepCode = $row["DepCode"];
          $zHotp    = $row["HptCode"];
      }
      $sql_update =  "SELECT DepCode FROM department WHERE IsDefault = 1 AND HptCode = '$zHotp'";
      $result = mysqli_query( $conn, $sql_update);
      while ($row = mysqli_fetch_array($result)) {
          $zDept = $row["DepCode"];
      }

      // =======================================================================================

        $sqlSC = "SELECT shelfcount.DepCode FROM shelfcount WHERE DocNo = '$DocNo2'";
        $resultSC = mysqli_query( $conn, $sqlSC);
            while ($rowXX = mysqli_fetch_array($resultSC)) {
              $DepCodeSC = $rowXX["DepCode"];
            }

            $sqlSCX = "SELECT shelfcount_detail.ItemCode FROM shelfcount_detail WHERE DocNo = '$DocNo2'";
            $resultSCX = mysqli_query( $conn, $sqlSCX);
                while ($rowX = mysqli_fetch_array($resultSCX)) {
                  $ItemCodeSC = $rowX["ItemCode"];
                } 
      // =======================================================================================

      $sqlSCS = "SELECT department.DepCode,department.DepName
      FROM department
      WHERE department.HptCode = '$hotpCode' 
      AND department.IsDefault = 1 
      AND department.IsStatus = 0";
      $resultSCS = mysqli_query( $conn, $sqlSCS);
          while ($rowXXX = mysqli_fetch_array($resultSCS)) {
            $DepCodeDraw = $rowXXX["DepCode"];
          }
  // =======================================================================================
          $loop = 0;
      $sql_update =  "SELECT
			draw_detail.ItemCode,
			draw_detail.ParQty,
			draw_detail.CcQty,
			draw_detail.TotalQty
			FROM draw_detail
			WHERE draw_detail.DocNo = '$DocNo'";
      $result = mysqli_query( $conn, $sql_update);
      while ($row = mysqli_fetch_array($result)) {
          $xItemCode[$n] 	= $row["ItemCode"];
          $xParQty[$n] 	= $row["ParQty"];
          $xCcQty[$n] 	= $row["CcQty"];
          $xTotalQty[$n] 	= $row["TotalQty"];
          $n++;
      }
      for($i=0;$i<$n;$i++){
          $ItemCode = $xItemCode[$i];
          $ParQty = $xParQty[$i];
          $CcQty = $xCcQty[$i];
          $TotalQty = $xTotalQty[$i];

          mysqli_query($conn, "UPDATE item_stock SET CcQty=$CcQty,TotalQty= (TotalQty-$TotalQty)  WHERE DepCode = $DepCodeDraw AND ItemCode = '$ItemCode'");

          mysqli_query($conn, "UPDATE item_stock SET CcQty=$CcQty,TotalQty= (TotalQty+$TotalQty)  WHERE DepCode = $DepCodeSC   AND ItemCode ='$ItemCode'");
      }

      //==========================================================================================================//
        $Sqlselect="SELECT item_stock.ItemCode,item_stock.DepCode,item_stock.ParQty,item_stock.CcQty,item_stock.TotalQty,item_stock.IsStatus
                    FROM item_stock WHERE  DepCode = $DepCodeSC   AND ItemCode ='$ItemCodeSC'
                    LIMIT 1";
                    $return['sql']="$Sqlselect";
        $meQuery = mysqli_query($conn,$Sqlselect);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $ItemCode = $Result['ItemCode'];
          $DepCodexx	= $Result['DepCode'];
          $ParQtyx 	= $Result['ParQty'];
          $CcQtyx 	  = $Result['CcQty'];
          $TotalQtyx = $Result['TotalQty'];
          $IsStatus = $Result['IsStatus'];
        }
      //==========================================================================================================//
      $Sqlselect="SELECT item_stock.ItemCode,item_stock.DepCode,item_stock.ParQty,item_stock.CcQty,item_stock.TotalQty,item_stock.IsStatus
      FROM item_stock WHERE  DepCode = $DepCodeDraw   AND ItemCode ='$ItemCode'
      LIMIT 1";
      $return['sql']="$Sqlselect";
      $meQuery = mysqli_query($conn,$Sqlselect);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCodeZ = $Result['ItemCode'];
      $DepCodeZ	= $Result['DepCode'];
      $ParQtyx 	= $Result['ParQty'];
      $CcQtyx 	  = $Result['CcQty'];
      $TotalQtyxz = $Result['TotalQty'];
      $IsStatus = $Result['IsStatus'];
}
//==========================================================================================================//

        $Sql_INSERT = "UPDATE item_stock SET TotalQty =$TotalQtyx , DepCode=$DepCodexx WHERE ItemCode='$ItemCodeZ' AND DepCode= $DepCodeZ LIMIT $TotalQty"; 
                       

        mysqli_query($conn,$Sql_INSERT);
    



//==========================================================================================================//
    $Sql = "DELETE FROM draw_detail_sub  WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);

    $Sql = "UPDATE draw SET IsRequest = 1 WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
  }

  function SendData($conn, $DATA)
  {
    $DocNo = $DATA["xdocno"];
    $n = 0;
    $Sql = "SELECT draw_detail_sub.UsageCode,draw_detail.ItemCode
    FROM draw_detail
    INNER JOIN draw_detail_sub ON draw_detail.DocNo = draw_detail_sub.DocNo
    WHERE  draw_detail.DocNo = '$DocNo'";
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

    ShowDocument($conn, $DATA);
  }

  function UpdateRefDocNo($conn, $DATA)
  {
    $DocNo = $DATA["xdocno"];
    $RefDocNo = $DATA["RefDocNo"];
    $checkitem = $DATA["checkitem"];
    // $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo / $RefDocNo')";
    // mysqli_query($conn,$Sqlx);
    $Sql = "UPDATE draw SET RefDocNo = '$RefDocNo' WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
    // $Sql = "UPDATE daily_request SET RefDocNo = '$RefDocNo' WHERE DocNo = '$DocNo'";
    // mysqli_query($conn, $Sql);

    $n = 0;
    $Sql = "SELECT
    shelfcount_detail.ItemCode,
    shelfcount_detail.UnitCode,
    shelfcount_detail.ParQty,
    shelfcount_detail.CcQty,
    shelfcount_detail.TotalQty,
    shelfcount_detail.IsCancel
    FROM shelfcount_detail
    WHERE shelfcount_detail.DocNo = '$RefDocNo'";
     $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $zItemCode[$n] = $Result['ItemCode'];
      $zUnitCode[$n] = $Result['UnitCode'];
      $zParQty[$n]      = $Result['ParQty'];
      $zCcQty[$n]      = $Result['CcQty'];
      $zTotalQty[$n]      = $Result['TotalQty'];
      $zIsCancel[$n] = $Result['IsCancel'];
      $n++;
    }
    for ($i = 0; $i < $n; $i++) {
      $ItemCode   = $zItemCode[$i];
      $UnitCode   = $zUnitCode[$i];
      $ParQty     = $zParQty[$i];
      $CcQty      = $zCcQty[$i];
      $TotalQty   = $zTotalQty[$i];
      $IsCancel   = $zIsCancel[$i];
      $Sql = "INSERT INTO draw_detail
      (DocNo,ItemCode,UnitCode,ParQty,CcQty,TotalQty,IsCancel)
      VALUES
      ('$DocNo','$ItemCode',$UnitCode,$ParQty,$CcQty,$TotalQty,$IsCancel)";
      mysqli_query($conn, $Sql);
    }

    // $n = 0;
    // $Sql = "SELECT factory_out_detail_sub.UsageCode,factory_out_detail.ItemCode
    // FROM factory_out_detail
    // INNER JOIN factory_out_detail_sub ON factory_out_detail.DocNo = factory_out_detail_sub.DocNo
    // WHERE  factory_out_detail_sub.DocNo = '$RefDocNo'";
    // $meQuery = mysqli_query($conn, $Sql);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   $ItemCode = $Result['ItemCode'];
    //   $UsageCode[$n] = $Result['UsageCode'];
    //   $n++;
    // }
    // for ($i = 0; $i < $n; $i++) {
    //   $xUsageCode = $UsageCode[$i];
    //   $Sql = " INSERT INTO clean_detail_sub(DocNo, ItemCode, UsageCode)
    //   VALUES('$DocNo', '$ItemCode', '$xUsageCode') ";
    //   mysqli_query($conn, $Sql);

    //   $Sql = "UPDATE item_stock SET IsStatus = 0 WHERE UsageCode = '$xUsageCode'";
    //   mysqli_query($conn, $Sql);
    // }

    SelectDocument($conn, $DATA);
  }

  function ShowDetail($conn, $DATA)
  {
    $count = 0;
    $Total = 0;
    $boolean = false;
    $DocNo = $DATA["DocNo"];
    $deptCode2 = $DATA["deptCode"];
    //==========================================================
      $Sql = "SELECT department.HptCode FROM draw INNER JOIN department ON draw.DepCode = department.DepCode WHERE draw.DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
          $HptCode = $Result['HptCode'];
      }
      $Sql = "SELECT department.DepCode 
              FROM department
              WHERE department.HptCode = '$HptCode'
              AND department.IsDefault = 1
              AND department.IsStatus =0";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
          $DepCode = $Result['DepCode'];
      }
    //==========================================================
    $Sql = "SELECT
    draw_detail.Id,
    draw_detail.ItemCode,
    item.ItemName,
    item_unit.UnitName,
    item_unit.UnitCode,
    (
        SELECT item_stock.TotalQty 
        FROM item_stock 
        WHERE item_stock.ItemCode = draw_detail.ItemCode 
        AND item_stock.DepCode = $DepCode
        GROUP BY item_stock.ItemCode , item_stock.DepCode
    ) AS ParQty,
    draw_detail.CcQty,
    draw_detail.TotalQty
    FROM item
    INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
    INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
    INNER JOIN draw_detail ON draw_detail.ItemCode = item.ItemCode
    INNER JOIN draw ON draw.DocNo = draw_detail.DocNo
    WHERE draw_detail.DocNo = '$DocNo'
    ORDER BY draw_detail.Id DESC";
    $return['sql']=$Sql;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$count :: ".$Result['Id']." / ".$Result['Weight']."')";
      //	mysqli_query($conn,$Sqlx);
      if($Result['TotalQty'] > $Result['ParQty']){
        $return[$count]['TotalQty'] = $Result['ParQty']==null?0:$Result['ParQty'] ;
      }else{
        $return[$count]['TotalQty']   = $Result['TotalQty']==null?0:$Result['TotalQty'];
      }
      $return[$count]['RowID']      = $Result['Id'];
      $return[$count]['ItemCode']   = $Result['ItemCode'];
      $return[$count]['ItemName']   = $Result['ItemName'];
      $return[$count]['UnitName']   = $Result['UnitName'];
      $return[$count]['ParQty']     = $Result['ParQty']==null?0:$Result['ParQty'];
      $return[$count]['CcQty']       = $Result['CcQty'];
      // $return[$count]['TotalQty']   = $Result['TotalQty']==null?0:$Result['TotalQty'];
      $UnitCode                     = $Result['UnitCode'];
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
      // $Sql = "UPDATE draw SET Total = $Total WHERE DocNo = '$DocNo'";
      //   mysqli_query($conn,$Sql);
      $return[0]['Total']    = round($Total, 2);
      //================================================================
      $count++;
      $boolean = true;
    }

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

  function ShowDetailSub($conn, $DATA)
  {
    $count = 0;
    $Total = 0;
    $boolean = false;
    $DocNo = $DATA["DocNo"];
    //==========================================================
    $Sql = "SELECT
    draw_detail_sub.UsageCode,
    item.ItemName,
    item_unit.UnitName
    FROM draw_detail
    INNER JOIN draw_detail_sub ON draw_detail.Id = draw_detail_sub.Id
    INNER JOIN item ON draw_detail.ItemCode = item.ItemCode
    INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
    WHERE draw_detail.DocNo = '$DocNo'";
    // var_dump($Sql); die;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$count :: ".$Result['Id']." / ".$Result['Weight']."')";
      //	mysqli_query($conn,$Sqlx);
      $return[$count]['UsageCode']  = $Result['UsageCode'];
      $return[$count]['ItemName']   = $Result['ItemName'];
      $return[$count]['UnitName']   = $Result['UnitName'];
      $count++;
      $boolean = true;
    }
    $return['Row'] = $count;
    //==========================================================

    $boolean = true;
    if ($boolean) {
      $return['status'] = "success";
      $return['form'] = "ShowDetailSub";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "notfound";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }

  function CancelBill($conn, $DATA)
  {
    $docno2 = $DATA["docno2"];
    $DocNo = $DATA["DocNo"];
    $Hotp = $DATA["Hotp"];
    // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
    // mysqli_query($conn,$Sql);
    $Sql = "UPDATE draw SET IsStatus = 2  WHERE DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);

    $Sqlx = "SELECT department.DepCode,department.DepName
    FROM department
    WHERE department.HptCode = '$Hotp'
    AND department.IsStatus = 0";
    $return['sql'] = $Sql;
    $meQuery = mysqli_query($conn, $Sqlx);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $DepCodex = $Result['DepCode'];
    }
    // =======================================================================================

    $sqlSC = "SELECT shelfcount.DepCode FROM shelfcount WHERE DocNo = '$docno2'";
    $return['sqla']=$sqlSC;

    $resultSC = mysqli_query( $conn, $sqlSC);
        while ($rowXX = mysqli_fetch_array($resultSC)) {

          $DepCodeSC = $rowXX["DepCode"];

        }

        $sqlSCX = "SELECT shelfcount_detail.ItemCode FROM shelfcount_detail WHERE DocNo = '$docno2'";
        $return['sqls']=$sqlSCX;

        $resultSCX = mysqli_query( $conn, $sqlSCX);
            while ($rowX = mysqli_fetch_array($resultSCX)) {

              $ItemCodeSC = $rowX["ItemCode"];

            } 

  // =======================================================================================

  $sqlSCS = "SELECT Draw.DepCode FROM Draw WHERE DocNo = '$DocNo'";
  $return['sqlx']=$sqlSCS;

  $resultSCS = mysqli_query( $conn, $sqlSCS);
      while ($rowXXX = mysqli_fetch_array($resultSCS)) {

        $DepCodeDraw = $rowXXX["DepCode"];

      }
// =======================================================================================

  $sql_update =  "SELECT
  draw_detail.ItemCode,
  draw_detail.ParQty,
  draw_detail.CcQty,
  draw_detail.TotalQty
  FROM draw_detail
  WHERE draw_detail.DocNo = '$DocNo'";
  $return['sql']=$sql_update;

  $result = mysqli_query( $conn, $sql_update);
  while ($row = mysqli_fetch_array($result)) {
      $xItemCode 	= $row["ItemCode"];
      $xParQty 	= $row["ParQty"];
      $xCcQty 	= $row["CcQty"];
      $xTotalQty 	= $row["TotalQty"];

      $update1 = "UPDATE item_stock SET CcQty=$xCcQty,TotalQty= (TotalQty+$xTotalQty)  WHERE DepCode = $DepCodex AND ItemCode = '$xItemCode'";
      mysqli_query($conn, $update1);

      $update2 = "UPDATE item_stock SET CcQty=$xCcQty,TotalQty= (TotalQty-$xTotalQty)  WHERE DepCode = $DepCodeSC   AND ItemCode ='$xItemCode'";
      mysqli_query($conn, $update2);
      
  }

    $return['up1'] = $update1;
    $return['up2'] = $update2;
    echo json_encode($return);

  }




  function ShowDocument_sub($conn, $DATA)
  {
    $boolean = false;
    $count = 0;
    $deptCode = $DATA["deptCode"];
    $DocNo = str_replace(' ', '%', $DATA["xdocno"]);

    $Sql = "SELECT site.HptName,department.DepName,draw.DocNo,draw.DocDate,draw.Total,
    users.FName,TIME(draw.Modify_Date) AS xTime,draw.IsStatus
    FROM draw
    INNER JOIN department ON draw.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    INNER JOIN users ON draw.Modify_Code = users.ID
    WHERE draw.DepCode = $deptCode
    AND draw.DocNo LIKE '%$DocNo%'
    ORDER BY draw.DocNo DESC LIMIT 500";
    $return['sql'] = $Sql;
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
  function get_dirty_doc($conn, $DATA)
  {
    $hptcode = $DATA["hptcode"];
    $boolean = false;
    $count = 0;
    $Sql = "SELECT shelfcount.DocNo
    FROM shelfcount
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
    INNER JOIN site ON department.HptCode = site.HptCode
    WHERE shelfcount.IsCancel = 0
    AND shelfcount.IsStatus = 1
    AND shelfcount.IsRef = 0
    AND site.HptCode = '$hptcode' 
    ORDER BY shelfcount.Modify_Date DESC
    LIMIT 100";
    // var_dump($Sql); die;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['RefDocNo'] = $Result['DocNo'];
      $boolean = true;
      $count++;
    }
    $return['Row'] = $count;
    // $return['form'] = "get_dirty_doc";
    // echo json_encode($return);
    // mysqli_close($conn);
    // die;
    if ($boolean) {
      $return['status'] = "success";
      $return['form'] = "get_dirty_doc";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "notfound";
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
    } elseif ($DATA['STATUS'] == 'ShowUsageCode') {
      ShowUsageCode($conn, $DATA);
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
    } elseif ($DATA['STATUS'] == 'UpdateDetailQty_key') {
      UpdateDetailQty_key($conn, $DATA);
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
    } elseif ($DATA['STATUS'] == 'ShowDetailSub') {
      ShowDetailSub($conn, $DATA);
    }elseif ($DATA['STATUS'] == 'get_dirty_doc') {
      get_dirty_doc($conn, $DATA);
    }
  } else {
    $return['status'] = "error";
    $return['msg'] = 'noinput';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
