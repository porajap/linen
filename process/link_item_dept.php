<?php
session_start();
require '../connect/connect.php';

function getHospital($conn, $DATA)
{
  $count = 0;
  $userid = $DATA['Userid'];
  $Sql = "SELECT
          site.HptCode,site.HptName
          FROM site
          WHERE site.IsStatus = 0 
          -- AND site.HptCode 
          -- = (SELECT
					-- users.HptCode
					-- FROM
					-- users
					-- INNER JOIN employee ON users.ID = employee.EmpCode
					-- INNER JOIN department ON employee.DepCode = department.DepCode
					-- INNER JOIN site ON department.HptCode = site.HptCode
          -- WHERE users.ID = $userid) 
          ";
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getHospital";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
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
  // $count = 0;
  // $userid = $DATA['Userid'];
  // $Sql = "SELECT
  //         department.DepCode,
  //         department.DepName
  //         FROM
  //         department
  //         WHERE department.DepCode = (
  //         SELECT
  //         department.DepCode
  //         FROM
  //         users
  //         INNER JOIN employee ON users.ID = employee.EmpCode
  //         INNER JOIN department ON employee.DepCode = department.DepCode
  //         WHERE users.ID = $userid ) AND department.IsStatus = 0
  //         ORDER BY department.DepCode DESC
  //         ";
  // // var_dump($Sql); die;
  // $meQuery = mysqli_query($conn, $Sql);
  // while ($Result = mysqli_fetch_assoc($meQuery)) {
  //   $return[$count]['DepCode'] = $Result['DepCode'];
  //   $return[$count]['DepName'] = $Result['DepName'];
  //   $count++;
  // }

  // if($count>0){
  //   $return['status'] = "success";
  //   $return['form'] = "getDepartment";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }else{
  //   $return['status'] = "notfound";
  //   $return['msg'] = "notfound";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }
}

function ShowItem($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $userid = $DATA['Userid'];
  $Sql = "SELECT
          item.ItemCode,
          item.ItemName
          FROM
          item
          WHERE  item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%'  AND IsActive=1
          ORDER BY item.ItemCode
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getdetail($conn, $DATA)
{
  $count = 0;
  $UnitCode = $DATA['UnitCode'];
  //---------------HERE------------------//
    //---------------HERE------------------//
  //---------------HERE------------------//
  //---------------HERE------------------//

  $Sql = "SELECT
          item_Unit.UnitCode,
          item_Unit.UnitName,
          CASE item_Unit.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus
          FROM
          item_Unit
          WHERE item_Unit.IsStatus = 0
          AND item_Unit.UnitCode = $UnitCode LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['UnitCode'] = $Result['UnitCode'];
    $return['UnitName'] = $Result['UnitName'];
    //$return['IsStatus'] = $Result['IsStatus'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.UnitCode,
          department.DepName,
          department.IsStatus
          FROM
          department";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']       = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO item_Unit(
          UnitName,
          IsStatus
         )
          VALUES
          (
            '".$DATA['UnitName']."',
            0
          )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    $return['msg'] = "addsuccess";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function EditItem($conn, $DATA)
{
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            UnitCode = '".$DATA['UnitCode']."',
            UnitName = '".$DATA['UnitName']."'
            WHERE UnitCode = ".$DATA['UnitCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            IsStatus = 1
            WHERE UnitCode = ".$DATA['UnitCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function additemstock($conn, $DATA)
{
  $boolean = 0;
  $count = 0;
  $HptCode = $_SESSION['HptCode'];
  // $hotpital = $DATA['hotpital'];
  $Deptid = $DATA['DeptID'];
  $ParQty = $DATA['Par'];
  $Itemcode = explode(",",$DATA['ItemCode']);
  $Number = explode(",",$DATA['Number']);


  // var_dump($Number[0]); die;
  for ($i=0; $i < sizeof($Itemcode,0) ; $i++) {

    $SqlCount = "SELECT COUNT(ItemCode) AS countPar, TotalQty, ParQty FROM item_stock WHERE ItemCode = '$Itemcode[$i]' AND DepCode = $Deptid";
    $meQuery = mysqli_query($conn,$SqlCount);

    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $countPar = $Result['countPar'];
      $setPar = $Result['ParQty'] + $ParQty;
      $TotalQty = $Result['TotalQty'];
      $setTotalQty = $TotalQty + $Number[$i];
    }
    $return['TotalQty'] = $TotalQty;
    $return['setTotalQty'] = $setTotalQty;

    for ($j=0; $j < $Number[$i] ; $j++) {
      if($countPar == 0){
        $Sql2 = "INSERT INTO item_stock(ItemCode,DepCode,ParQty,IsStatus,TotalQty,UsageCode,ExpireDate)
        VALUES( '".$Itemcode[$i]."', '$Deptid', $ParQty, 9, $Number[$i],0,NOW())";
        if(mysqli_query($conn,$Sql2)){
          $boolean++;
        }
      }else{
        $update = "UPDATE item_stock SET ParQty = $setPar, TotalQty = $setTotalQty WHERE ItemCode = '$Itemcode[$i]' AND DepCode = $Deptid";
        $return['update'] = $update;
        mysqli_query($conn,$update);

        $Sql3 = "INSERT INTO item_stock(ItemCode,DepCode,ParQty,IsStatus, TotalQty, UsageCode)
        VALUES( '".$Itemcode[$i]."', '$Deptid', $setPar, 9, $setTotalQty,0)";
        $return['Sql3'] = $Sql3;
        if(mysqli_query($conn,$Sql3)){
          $boolean++;
        }
      }
    }

  }
  // ====================================================================================
  for ($i=0; $i < sizeof($Itemcode,0) ; $i++) {

    $Sqlzz = "SELECT
            item.UnitCode
            FROM item
            INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
            INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
            INNER JOIN item_unit AS item_unit2 ON item.SizeCode = item_unit2.UnitCode
            LEFT JOIN item_multiple_unit ON item_multiple_unit.ItemCode = item.ItemCode
            LEFT JOIN item_unit AS U1 ON item_multiple_unit.UnitCode = U1.UnitCode
        LEFT JOIN item_unit AS U2 ON item_multiple_unit.MpCode = U2.UnitCode
            WHERE item.ItemCode = '$Itemcode[$i]'";
      $meQueryx = mysqli_query($conn, $Sqlzz);
      $Resultx = mysqli_fetch_assoc($meQueryx);
      $unitCode = $Resultx['UnitCode'];
      
    // ====================================================================================
    $Sqlz = "SELECT category_price.Price
                FROM    item,item_stock,department,category_price
                WHERE item.ItemCode = '$Itemcode[$i]'
                AND category_price.CategoryCode = item.CategoryCode
                AND item_stock.ItemCode = item.ItemCode
                AND item_stock.DepCode = department.DepCode
                AND department.HptCode = '$HptCode'
                AND category_price.HptCode = '$HptCode'
                GROUP BY item.ItemCode";
    $return['sql'] = $Sqlz;
    // echo json_encode($return);
  
    $meQuery = mysqli_query($conn, $Sqlz);
    $Result = mysqli_fetch_assoc($meQuery);
    $CusPrice = $Result['Price'] == null ? 0 : $Result['Price'];
  
    $countM = "SELECT COUNT(*) as cnt FROM item_multiple_unit WHERE ItemCode = '$Itemcode[$i]' AND  MpCode =$unitCode ";
    $MQuery = mysqli_query($conn, $countM);
    $return['sql'] = $countM;
    while ($MResult = mysqli_fetch_assoc($MQuery)) {
      if ($MResult['cnt'] == 0) {
        $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
                 ($unitCode, $unitCode, 1, '$Itemcode[$i]' , $CusPrice) ";
        mysqli_query($conn, $Sql2);
      } else {
        $Sql2 = "UPDATE item_multiple_unit SET  PriceUnit = $CusPrice 
          WHERE ItemCode =  '$Itemcode[$i]' AND MpCode =$unitCode ";
        mysqli_query($conn, $Sql2);
      }
    }
  }
    // ====================================================================================




  if($boolean>0){
    $return['status'] = "success";
    $return['msg'] = "addsuccess";
    $return['form'] = "additemstock";
    $return['ItemCode'] = $DATA['ItemCode'];
    $return['Number'] = $DATA['Number'];
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SelectItemStock($conn, $DATA)
{
  $boolean = 0;
  $count = 0;
  $DepCode = $DATA['DepCode'];
  $ItemCode = explode(",", $DATA['ItemCode']);
  $Number = explode(",",$DATA['Number']);
  $return['num'] = $Number;

  for ($i=0; $i < sizeof($ItemCode,0) ; $i++) {
    for ($j=0; $j < $Number[$i] ; $j++) {
      $Sql = "SELECT
        item_stock.RowID, 
        item_stock.ItemCode,
        item.ItemName,
        item_stock.ParQty,
        DATE(item_stock.ExpireDate) AS ExpireDate,
        UsageCode
      FROM item_stock
      INNER JOIN item ON item_stock.ItemCode = item.ItemCode
      WHERE item_stock.ItemCode = '$ItemCode[$i]' AND item_stock.UsageCode = 0 AND item_stock.IsStatus = 9 AND item_stock.DepCode = $DepCode
      -- GROUP BY item_stock.ItemCode
      ORDER BY item_stock.RowID DESC";
      $return['sql'] = $Sql;
      $meQuery = mysqli_query($conn,$Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['RowID'] = $Result['RowID'];
        $return[$count]['ItemCode'] = $Result['ItemCode'];
        $return[$count]['ItemName'] = $Result['ItemName'];
        $return[$count]['DepCode'] = $Result['DepCode'];
        $return[$count]['ParQty'] = $Result['ParQty'];
        if($Result['UsageCode']=="" || $Result['UsageCode']==null){
          $return[$count]['UsageCode'] = '';
        }
        $return[$count]['ExpireDate'] = $tempdate;
      }
      $count++;
    }
  }
  $return['count'] = $count;

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "SelectItemStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "refresh";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }


}

function ShowItemStock($conn, $DATA)
{
  $boolean = 0;
  $count = 0;
  $Deptid = $DATA['Deptid'];
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT
          item_stock.RowID,
          item_stock.ItemCode,
          item.ItemName,
          item_stock.ParQty,
          DATE(item_stock.ExpireDate) AS ExpireDate,
          UsageCode
          FROM
          item_stock
          INNER JOIN item ON item_stock.ItemCode = item.ItemCode
          WHERE item_stock.IsStatus = 9 AND item_stock.DepCode = $Deptid AND item_stock.UsageCode = 0 AND item_stock.IsStatus = 9  AND (item_stock.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%')
          ORDER BY item_stock.RowID DESC";
          $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['ParQty'] = $Result['ParQty'];
    if($Result['UsageCode']=="" || $Result['UsageCode']==null){
      $return[$count]['UsageCode'] = '';
    }
    $return[$count]['ExpireDate'] = $tempdate;
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItemStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "refresh";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function setdateitemstock($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $Exp = $DATA['Exp'];
  $Exp = explode("/",$Exp);
  $Exp = $Exp[2]."-".$Exp[1]."-".$Exp[0];
  $RowID = $DATA['RowID'];
  $Sql = "UPDATE item_stock SET ExpireDate = DATE('$Exp') WHERE RowID = $RowID";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "setdateitemstock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}
function SaveUsageCode($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $UsageCode = $DATA['UsageCode'];
  $RowID = $DATA['RowID'];
  $Sql = "UPDATE item_stock SET UsageCode = '$UsageCode' WHERE RowID = $RowID";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "SaveUsageCode";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function Submititemstock($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $Deptid = $DATA['Deptid'];

  $Sqlsearch = "SELECT
                item_stock.RowID,
                item_stock.ItemCode,
                item.ItemName,
                item_stock.ParQty,
                item_stock.ExpireDate
                FROM
                item_stock
                INNER JOIN item ON item_stock.ItemCode = item.ItemCode
                WHERE item_stock.IsStatus = 9 AND item_stock.DepCode = $Deptid AND item_stock.ExpireDate IS NOT NULL
                GROUP BY item_stock.ItemCode
                ORDER BY item_stock.RowID DESC";
  $meQuery2 = mysqli_query($conn,$Sqlsearch);
  while ($row = mysqli_fetch_assoc($meQuery2)) {

    $Sql = "SELECT
            COUNT(*) AS Cnt
            FROM
            item_stock_detail
            WHERE item_stock_detail.DepCode = $Deptid AND item_stock_detail.ItemCode = '".$row['ItemCode']."'";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['Cnt']==0){
        $Sqlinsert = "INSERT INTO item_stock_detail(ItemCode,ExpireDate,DepCode,ParQty,Qty)
          VALUES(
            '".$row['ItemCode']."',
            '".$row['ExpireDate']."',
            $Deptid,
            ".$row['ParQty'].",
            0
          )";
          if(mysqli_query($conn,$Sqlinsert)){
            $count++;
          }
      }
    }
  }

  $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE DepCode = $Deptid AND ExpireDate <> '' AND ExpireDate IS NOT NULL";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['msg'] = "success";
    $return['form'] = "Submititemstock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function DeleteItem($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DepCode = $DATA['DepCode'];
  $ItemCode = explode(',' , $DATA['ItemCode']);
  $limit = sizeof($ItemCode, 0);
  for ($i = 0; $i < $limit; $i++) {
    $Sql = "DELETE FROM item_stock WHERE ItemCode = '$ItemCode[$i]' AND DepCode = $DepCode LIMIT 1";
    mysqli_query($conn,$Sql);

    $Update = "UPDATE item_stock SET TotalQty = (TotalQty - 1) WHERE ItemCode =  '$ItemCode[$i]' AND DepCode = $DepCode";
    mysqli_query($conn,$Update);
    $boolean = true;
    $count ++;
  }

  if($boolean == true){
    $return['sql'] = $Sql;
    $return['Update'] = $Update;
    $return['count'] = $count;
    echo json_encode($return);
  }

}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getDepartment') {
        getDepartment($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getHospital') {
        getHospital($conn,$DATA);
      }else if ($DATA['STATUS'] == 'additemstock') {
        additemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'ShowItemStock') {
        ShowItemStock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'setdateitemstock') {
        setdateitemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'Submititemstock') {
        Submititemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SaveUsageCode') {
        SaveUsageCode($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SelectItemStock') {
        SelectItemStock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'DeleteItem') {
        DeleteItem($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
