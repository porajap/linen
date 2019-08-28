<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function ShowItem($conn, $DATA)
{
  
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $active = $DATA['active'];
  $column = $DATA['column']==null?'ItemCode':$DATA['column'];
  $sort = $DATA['sort']==null?'DESC':$DATA['sort'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.CusPrice,
            item.FacPrice,
            item.Weight,
            item.Picture,
            item.IsDirtyBag,
            item.Itemnew,
            item.isset
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory AND item_main_category.MainCategoryCode =$maincatagory ";
  } else {
    $Sql .= " WHERE item_main_category.MainCategoryCode =$maincatagory AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') ";
  }
  $Sql .= " ORDER BY item.$column $sort";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['CusPrice'] = $Result['CusPrice'];
    $return[$count]['FacPrice'] = $Result['FacPrice'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Picture'] = $Result['Picture'];
    $return[$count]['IsDirtyBag'] = $Result['IsDirtyBag'];
    $return[$count]['Itemnew'] = $Result['Itemnew'];
    $return[$count]['isset'] = $Result['isset'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItem";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem_Active_0($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $active = $DATA['active'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.CusPrice,
            item.FacPrice,
            item.Weight,
            item.Picture
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory AND IsActive = 0 ORDER BY item.ItemCode ASC";
  } else {
    $Sql .= " WHERE item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
                OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%' AND IsActive = 0 ";
  }
  $return['sql'] = $Sql;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['CusPrice'] = $Result['CusPrice'];
    $return[$count]['FacPrice'] = $Result['FacPrice'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Picture'] = $Result['Picture'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem_Active_0";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItem_Active_0";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getUnit($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          item_unit.IsStatus
          FROM
          item_unit
          WHERE item_unit.IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getUnit";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getCatagory($conn, $DATA)
{
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  // var_dump($Maincat); die;
  $Sql = "SELECT
          item_category.CategoryCode,
          item_category.CategoryName,
          item_category.IsStatus
          FROM
          item_category
          INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
          WHERE item_category.MainCategoryCode = $maincatagory AND item_category.IsStatus = 0
          ";
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getCatagory";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function GetHospital($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT    *
			    FROM      site
          WHERE     IsStatus = 0";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HospitalName'] = $Result['HptName'];
    $return[$count]['HospitalCode'] = $Result['HptCode'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "GetHospital";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getdetail($conn, $DATA)
{
  $HptCode = $_SESSION['HptCode'];
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  // ====================================================================================

  $Sql = "SELECT
          item.ItemCode,
          item.ItemName,
          item.CategoryCode,
          item_main_category.MainCategoryCode,
          item.UnitCode,
          item_unit.UnitName,
          item.SizeCode,
          item.CusPrice,
          item.FacPrice,
          item.Weight,
          item.Picture,
          item_multiple_unit.RowID,
          U1.UnitName AS MpCode,
          U2.UnitName AS UnitName2,
          Multiply,PriceUnit,
          item.QtyPerUnit,
          item.UnitCode2,
          IsDirtyBag,
          Itemnew,
          isset
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          LEFT JOIN item_unit AS item_unit2 ON item.SizeCode = item_unit2.UnitCode
          LEFT JOIN item_multiple_unit ON item_multiple_unit.ItemCode = item.ItemCode
          LEFT JOIN item_unit AS U1 ON item_multiple_unit.UnitCode = U1.UnitCode
		  LEFT JOIN item_unit AS U2 ON item_multiple_unit.MpCode = U2.UnitCode
          WHERE item.ItemCode = '$ItemCode'";

  // var_dump($Sql); die;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['MainCategoryCode'] = $Result['MainCategoryCode'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['CusPrice'] = $Result['CusPrice'];
    $return[$count]['FacPrice'] = $Result['FacPrice'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Picture'] = $Result['Picture'];
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['MpCode'] = $Result['MpCode'];
    $return[$count]['UnitName2'] = $Result['UnitName2'];
    $return[$count]['Multiply'] = $Result['Multiply'];
    $return[$count]['PriceUnit'] = $Result['PriceUnit']==null?0:$Result['PriceUnit'];
    $return[$count]['QtyPerUnit'] = $Result['QtyPerUnit'];
    $return[$count]['sUnitName'] = $Result['UnitCode2'];
    $return[0]['IsDirtyBag'] = $Result['IsDirtyBag'];
    $return[0]['Itemnew'] = $Result['Itemnew'];
    $return[0]['isset'] = $Result['isset'];
    $count++;
  }
  $return['RowCount'] = $count;

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function GetmainCat($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_main_category.MainCategoryCode,
          item_main_category.MainCategoryName,
          item_main_category.IsStatus
          FROM
          item_main_category
          WHERE item_main_category.IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['MainCategoryCode'] = $Result['MainCategoryCode'];
    $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
    $count++;
  }
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "GetmainCat";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
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
  if($DATA['masterItem'] == 1){
    $Del = "DELETE FROM item_set WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
    mysqli_query($conn, $Del);
  }
  $return['ItemName'] = $DATA['ItemName'];
  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          item
          WHERE item.ItemCode = '" . $DATA["ItemCode"] . "'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }
  if ($boolcount != 0) {
    $Sql = "UPDATE item SET
            CategoryCode = '" . $DATA['Catagory'] . "',
            ItemName = '" . $DATA['ItemName'] . "',
            UnitCode = '" . $DATA['UnitName'] . "',
            SizeCode = '" . $DATA['SizeCode'] . "',
            CusPrice = '" . $DATA['CusPrice'] . "',
            FacPrice = '" . $DATA['FacPrice'] . "',
            Weight = '" . $DATA['Weight'] . "',
            QtyPerUnit = '" . $DATA['qpu'] . "',
            UnitCode2 = '" . $DATA['sUnit'] . "',
            IsDirtyBag = '" . $DATA['xCenter'] . "',  
            Itemnew = '" . $DATA['xItemnew'] . "',
            isset = ". $DATA['masterItem']."
            WHERE ItemCode = '" . $DATA['ItemCode'] . "' ";

            $Select = "SELECT MpCode FROM item_multiple_unit WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
            $meQueryMp = mysqli_query($conn, $Select);
            while($ResultMp = mysqli_fetch_assoc($meQueryMp)) {
              $MpCode = $ResultMp['MpCode'];
              if($MpCode == $DATA['UnitName']){
                $Sql2 = "UPDATE item_multiple_unit  SET UnitCode = '" . $DATA['UnitName'] . "', PriceUnit = 1 WHERE ItemCode = '" . $DATA['ItemCode'] . "' AND MpCode = $MpCode";
              }else{
                $Sql2 = "UPDATE item_multiple_unit  SET UnitCode = '" . $DATA['UnitName'] . "' WHERE ItemCode = '" . $DATA['ItemCode'] . "' ";
              }
              mysqli_query($conn, $Sql2);
            }
            $return['sql'] = $Sql;
    if (mysqli_query($conn, $Sql) && mysqli_query($conn, $Sql2)) {
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function CreateItemCode($conn, $DATA)
{
  $ItemCode = "";
  $boolcount = 0;

  $Sql = "    SELECT 		item_main_category.MainCategoryCode,SUBSTRING(MainCategoryName,1,3) AS MainCategoryName
              FROM 		item_category,item_main_category
              WHERE		item_category.CategoryCode=" . $DATA['Catagory'] . "
              AND     item_main_category.MainCategoryCode = item_category.MainCategoryCode";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $MainCatagory = $Result['MainCategoryCode'];
    $MainCategoryName = $Result['MainCategoryName'];
  }

  if ($DATA['modeCode'] == '2') {
    $Sql = "  SELECT 		CONCAT( LPAD('$MainCatagory', 2, 0),
                                LPAD('" . $DATA['Catagory'] . "', 2, 0),
                                LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(ItemCode,5,5),UNSIGNED INTEGER)),0)+1) ,5,0))
                        AS  ItemCode
              FROM 			item
              WHERE 		ItemCode Like CONCAT( LPAD('$MainCatagory', 2, 0),
                                              LPAD('" . $DATA['Catagory'] . "', 2, 0),
                                            '%')
              ORDER BY  ItemCode DESC LIMIT 1";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $ItemCode = $Result['ItemCode'];
    }

    if ($ItemCode != "") {
      $boolcount = 1;
    } else {
      $boolcount = 0;
    }
  } else if ($DATA['modeCode'] == '1') {
    $preCode = $DATA['hospitalCode'] . "LP" . $MainCategoryName . $DATA['typeCode'] . $DATA['packCode'];
    $Sql = "  SELECT 		CONCAT(LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(ItemCode,12,4),UNSIGNED INTEGER)),0)+1) ,4,0))
              AS        ItemCode
              FROM 			item
              WHERE 		ItemCode Like CONCAT('$preCode',
                                            '%')
              ORDER BY  ItemCode DESC LIMIT 1";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $postCode = $Result['ItemCode'];
    }
    $ItemCode = $preCode . $postCode;
    if ($postCode != "") {
      $boolcount = 1;
    } else {
      $boolcount = 0;
    }
  } else {
    $ItemCode = "";
    $boolcount = 1;
  }

  if ($boolcount == 1) {
    $return['status'] = "success";
    $return['form'] = "CreateItemCode";
    $return['ItemCode'] = $ItemCode;

    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function NewItem($conn, $DATA)
{
  // var_dump($DATA); die;
  $count = 0;
  $HptCode = $_SESSION['HptCode'];
  $return['$HptCode'] = $HptCode;
  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          item
          WHERE item.ItemCode = '" . $DATA["ItemCode"] . "'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }

  $Sqlz = "SELECT category_price.Price
              FROM    item,item_stock,department,category_price
              WHERE item.ItemCode = '" . $DATA['ItemCode'] . "'
              AND category_price.CategoryCode = item.CategoryCode
              AND item_stock.ItemCode = item.ItemCode
              AND item_stock.DepCode = department.DepCode
              AND department.HptCode = '$HptCode'
              AND category_price.HptCode = '$HptCode'
              GROUP BY item.ItemCode";
  //$return['sql'] = $Sqlz;

  $meQuery = mysqli_query($conn, $Sqlz);
  $Result = mysqli_fetch_assoc($meQuery);
  $CusPrice = $Result['Price'] == null ? 0 : $Result['Price'];


  if ($boolcount == 0) {
    $count = 0;
    $Sql = "INSERT INTO item(
            ItemCode,
            CategoryCode,
            ItemName,
            UnitCode,
            SizeCode,
            CusPrice,
            FacPrice,
            Weight,
            IsActive,
            QtyPerUnit,
            UnitCode2,
            IsDirtyBag,
            Itemnew
           )
            VALUES
            (
              '" . $DATA['ItemCode'] . "',
              '" . $DATA['Catagory'] . "',
              '" . $DATA['ItemName'] . "',
              '" . $DATA['UnitName'] . "',
              '" . $DATA['SizeCode'] . "',
              '" . $DATA['CusPrice'] . "',
              '" . $DATA['FacPrice'] . "',
              '" . $DATA['Weight'] . "',
              1,
              '" . $DATA['qpu'] . "',
              '" . $DATA['sUnit'] . "',
              '" . $DATA['xCenter'] . "',
              '" . $DATA['xItemnew'] . "'

            )
    ";

    $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
    ( '" . $DATA['UnitName'] . "',  '" . $DATA['UnitName'] . "', 1, '" . $DATA['ItemCode'] . "', 1) ";

    if (mysqli_query($conn, $Sql) && mysqli_query($conn, $Sql2)) {
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "addsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "addfailed1";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "addfailed2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ActiveItem($conn, $DATA)
{
  if ($DATA["ItemCode"] != "") {
    $Sql = "UPDATE item SET IsActive = 1 WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
    if (mysqli_query($conn, $Sql)) {
      $return['status'] = "success";
      $return['form'] = "ActiveItem";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function AddUnit($conn, $DATA)
{
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  $MpCode = $DATA['MpCode'];
  $UnitCode = $DATA['UnitCode'];
  $Multiply = $DATA['Multiply'];
  $priceunit = $DATA['priceunit'];

  $countM = "SELECT COUNT(*) as cnt FROM item_multiple_unit WHERE MpCode = $MpCode AND UnitCode = $UnitCode AND ItemCode = '$ItemCode'";
  $MQuery = mysqli_query($conn, $countM);
  $return['sql'] = $countM;
  while ($MResult = mysqli_fetch_assoc($MQuery)) {

    if ($MResult['cnt'] == 0) {
      if($UnitCode == $MpCode){
        $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
        ($MpCode, $UnitCode, $Multiply, '$ItemCode' , 1) ";
      }else{
          $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
            ($MpCode, $UnitCode, $Multiply, '$ItemCode' , $priceunit) ";
      }
      mysqli_query($conn, $Sql2);
    } else {
      if($UnitCode == $MpCode){
        $Sql1 = "UPDATE item_multiple_unit SET  MpCode = $MpCode , UnitCode = $UnitCode , Multiply = $Multiply , ItemCode = '$ItemCode' , PriceUnit = 1
                WHERE ItemCode = '$ItemCode' AND MpCode = $MpCode AND UnitCode  = $UnitCode  ";
      }else{
        $Sql1 = "UPDATE item_multiple_unit SET  MpCode = $MpCode , UnitCode = $UnitCode , Multiply = $Multiply , ItemCode = '$ItemCode' , PriceUnit = $priceunit
                WHERE ItemCode = '$ItemCode' AND MpCode = $MpCode AND UnitCode  = $UnitCode  ";
      }
      mysqli_query($conn, $Sql1);
    }
    $count++;
  }


  // ==================================================================================================================================

  // $countM = "SELECT COUNT(*) as cnt FROM item_multiple_unit WHERE MpCode = 1 AND UnitCode = 1 AND ItemCode = '$ItemCode'";
  // $MQuery = mysqli_query($conn,$countM);
  // while ($MResult = mysqli_fetch_assoc($MQuery)) {

  //   $return['sql'] = $countM;

  //   if($MResult['cnt']==0){
  //     if($MpCode == 1 && $Multiply == 1){
  //       $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
  //       ($MpCode, $UnitCode, $Multiply, '$ItemCode' , $priceunit) ";
  //       mysqli_query($conn,$Sql2);
  //       $return['have'] = 1;

  //     }else{
  //       $Sql1 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit) VALUES
  //       (1,1,1,'$ItemCode',1) ";
  //       mysqli_query($conn,$Sql1);

  //       $return['have'] = 2;
  //       $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
  //       ($MpCode, $UnitCode, $Multiply, '$ItemCode' , $priceunit) ";
  //       mysqli_query($conn,$Sql2);
  //     }
  //     $count++;
  //   }else{
  //     $return['have'] = 3;
  //     $return['UnitCode'] = $UnitCode;
  //     $return['Multiply'] = $Multiply;
  //     if($MpCode != 1 && $Multiply != 1){
  //       $Sql3 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit) VALUES 
  //       ($MpCode, $UnitCode, $Multiply, '$ItemCode' , $priceunit) ";
  //       mysqli_query($conn,$Sql3);
  //       $count++;
  //     }
  //   }
  // }

  // var_dump($Sql); die;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "AddUnit";
    $return['msg'] = "addsuccess";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
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
  if ($DATA["UnitCode"] != "") {
    $Sql = "UPDATE item_Unit SET
            UnitCode = '" . $DATA['UnitCode'] . "',
            UnitName = '" . $DATA['UnitName'] . "'
            WHERE UnitCode = " . $DATA['UnitCode'] . "
    ";
    // var_dump($Sql); die;
    if (mysqli_query($conn, $Sql)) {
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
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
  if ($DATA["ItemCode"] != "") {
    $Sql1 = "DELETE FROM item
            WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
    $Sql2 = "DELETE FROM item_multiple_unit
            WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
    //$return['Sql'] = $Sql;
    if (mysqli_query($conn, $Sql1)&&mysqli_query($conn, $Sql2)) {
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function DeleteUnit($conn, $DATA)
{
  $count = 0;
  if ($DATA["RowID"] != "") {
    $Sql = "DELETE FROM item_multiple_unit
            WHERE RowID = " . $DATA['RowID'] . "
    ";
    // var_dump($Sql); die;
    if (mysqli_query($conn, $Sql)) {
      $return['status'] = "success";
      $return['form'] = "CancelUnit";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    } else {
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  } else {
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

// function checkItemCode($conn, $DATA)
// {
//   $count = 0;
//   $Sql = "SELECT Count(*) AS cnt FROM item WHERE ItemCode = '" . $DATA['ItemCode'] . "'";
//   $MQuery = mysqli_query($conn, $Sql);
//   $MResult = mysqli_fetch_assoc($MQuery);
//   $return['sql'] = $Sql;
//   $count=$MResult['cnt'];
//   $return['status'] = "success";
//   $return['form'] = "checkItemCode";
//   if ($count == 0) {
//     $return['msg'] = "T";
//     echo json_encode($return);
//     mysqli_close($conn);
//     die;
//   } else {
//     $return['msg'] = "F";
//     echo json_encode($return);
//     mysqli_close($conn);
//     die;
//   }
// }
function ShowItemMaster($conn, $DATA)
{
  
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $active = $DATA['active'];
  $column = $DATA['column']==null?'ItemCode':$DATA['column'];
  $sort = $DATA['sort']==null?'DESC':$DATA['sort'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.CusPrice,
            item.FacPrice,
            item.Weight,
            item.Picture,
            item.IsDirtyBag,
            item.Itemnew
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory AND item_main_category.MainCategoryCode = $maincatagory AND item.isset = 1";
  } else {
    $Sql .= " WHERE item_main_category.MainCategoryCode = $maincatagory AND item.isset = 1 AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') ";
  }
  $Sql .= " ORDER BY item.$column $sort";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['CusPrice'] = $Result['CusPrice'];
    $return[$count]['FacPrice'] = $Result['FacPrice'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Picture'] = $Result['Picture'];
    $return[$count]['IsDirtyBag'] = $Result['IsDirtyBag'];
    $return[$count]['Itemnew'] = $Result['Itemnew'];
    $count++;
  }

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItemMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItemMaster";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getdetailMaster($conn, $DATA)
{
  $count = 0;
  $ItemCode = $DATA['ItemCode'];
  // ====================================================================================

  $countMaster = 0;
  $SqlMaster = "SELECT 
      item_set.RowID, item_set.mItemCode, item_set.ItemCode, item.ItemName, item_set.Qty
    FROM item_set 
    INNER JOIN item ON item.ItemCode = item_set.ItemCode
    WHERE item_set.mItemCode = '$ItemCode' OR item_set.ItemCode = '$ItemCode'";
  $masterQuery = mysqli_query($conn, $SqlMaster);
  while ($Result = mysqli_fetch_assoc($masterQuery)) {
    $return[$countMaster]['RowID'] = $Result['RowID'];
    $return[$countMaster]['ItemCode'] = $Result['ItemCode'];
    $mItemCode = $Result['mItemCode'];
    $return[$countMaster]['ItemName'] = $Result['ItemName'];
    $return[$countMaster]['Qty'] = $Result['Qty'];
    $countMaster++;

  }
  $return['RowMaster'] = $countMaster;
  $return['mItemCode'] = $mItemCode==null?$ItemCode:$mItemCode;

  if ($countMaster > 0) {
    $return['status'] = "success";
    $return['form'] = "getdetailMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "getdetailMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function deleteMaster($conn, $DATA){
  $RowID = $DATA['RowID'];
  $Sql = "DELETE FROM item_set WHERE RowID = $RowID";
  mysqli_query($conn, $Sql);
}
function SaveQtyMaster($conn, $DATA){
  $RowID = $DATA['RowID'];
  $Qty = $DATA['Qty'];
  $Sql = "UPDATE item_set SET Qty = $Qty  WHERE RowID = $RowID";
  mysqli_query($conn, $Sql);
}
function ShowItemModal($conn, $DATA)
{
  
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

  if ($Keyword == '') {
    $Sql .= " WHERE item.CategoryCode = $Catagory AND item_main_category.MainCategoryCode =$maincatagory AND item.isset = 0";
  } else {
    $Sql .= " WHERE item_main_category.MainCategoryCode = $maincatagory  AND item.isset = 0 AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
    OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%') ";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $count++;
  }
  $return['RowMaster'] = $count;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItemModal";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['form'] = "ShowItemModal";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function AddItemMaster($conn, $DATA){
  $ItemCodeMaster = $DATA['ItemCode'];
  $ItemCode = explode(',', $DATA['ArrayItemCode']);
  $Qty = explode(',', $DATA['ArrayQty']);
  $limit = sizeof($ItemCode, 0);
  $count = 0;
  $test = 0;
  for($i=0; $i < $limit; $i++)
  {
    // $selectMas = "SELECT mItemCode FROM item_set WHERE ItemCode = '$ItemCode[$i]' LIMIT 1";
    // $return[$i]['SELECT'] = $selectMas;
    // $MasmeQuery = mysqli_query($conn, $selectMas);
    // while ($SeResult = mysqli_fetch_assoc($MasmeQuery)) {
    //   $mItemCode = $SeResult['mItemCode'];
    // }
    $cntMom = "SELECT COUNT(mItemCode) AS cntMom FROM item_set WHERE ItemCode = '$ItemCode[$i]'";
    $MomQuery = mysqli_query($conn, $cntMom);
    while ($MomResult = mysqli_fetch_assoc($MomQuery)) {
      $cntMom = $MomResult['cntMom'];
    }
    if($cntMom == 0){
      $insert = "INSERT INTO item_set(mItemCode, ItemCode, Qty) VALUES ('$ItemCodeMaster', '$ItemCode[$i]', $Qty[$i])";
      mysqli_query($conn, $insert);
      $return['insert'] = $insert;
    }else{
      $SeMaster = "SELECT mItemCode, ItemName FROM item_set 
      INNER JOIN item ON item.ItemCode = item_set.ItemCode
      WHERE item_set.ItemCode = '$ItemCode[$i]' LIMIT 1";
      $SeMQuery = mysqli_query($conn, $SeMaster);
      while ($SeMResutl = mysqli_fetch_assoc($SeMQuery)) {
        if($SeMResutl['mItemCode'] == $ItemCodeMaster){
          $Sql = "UPDATE item_set SET Qty = $Qty[$i] WHERE mItemCode = '$ItemCodeMaster' AND ItemCode = '$ItemCode[$i]'";
          mysqli_query($conn, $Sql);
        }else{
          $return[$count]['doublyItemCode'] = $ItemCode[$i];
          $return[$count]['doublyItemName'] = $SeMResutl['ItemName'];
        }
        $count ++;
      }
    }
  }
      $return['status'] = "success";
      $return['ItemCodeMaster'] = $DATA['ItemCode'];
      $return['form'] = "AddItemMaster";
      $return['count'] = $count;
      echo json_encode($return);
      mysqli_close($conn);
      die;
    // $countMaster = "SELECT COUNT(ItemCode) AS cnt FROM item_set WHERE mItemCode = '$ItemCodeMaster' AND ItemCode = '$ItemCode[$i]'";
    // $meQuery = mysqli_query($conn, $countMaster);
    // while ($Result = mysqli_fetch_assoc($meQuery)) {
    //   if($Result['cnt'] == 0){
    //     $Sql = "INSERT INTO item_set(mItemCode, ItemCode, Qty) VALUES ('$ItemCodeMaster', '$ItemCode[$i]', $Qty[$i])";
    //   }else{
    //     $Sql = "UPDATE item_set SET Qty = $Qty[$i] WHERE mItemCode = '$ItemCodeMaster' AND ItemCode = '$ItemCode[$i]'";
    //   }
    //   // $return['sql'] = $Sql; 
    //   // echo json_encode($return);
    //   mysqli_query($conn, $Sql);
    // }
}
  // getdetailMaster($conn, $DATA);
function chkItemMaster($conn, $DATA){
  $ItemCode = $DATA['ItemCode'];
  $masterItem = $DATA['masterItem'];
  if($masterItem == 1){
    $Sql = "SELECT COUNT(ItemCode) AS cnt FROM item_set WHERE ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['cnt'] > 0){
        $return['cnt'] = 1;
        $return['chk'] = 'chk_addMaster';
      }else{
        $return['cnt'] = 0;
        $return['chk'] = 'chk_addMaster';
      }
    }
  }else{
    $Sql = "SELECT COUNT(mItemCode) AS cnt FROM item_set WHERE ItemCode = '$ItemCode'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['cnt'] > 0){
        $return['cnt'] = 1;
        $return['chk'] = 'chk_delItem';
        $return['mItemCode'] = $ItemCode;
      }else{
        $return['cnt'] = 0;
        $return['chk'] = 'chk_delItem';
      }
    }
  }
    
    $return['status'] = "success";
    $return['form'] = "chkItemMaster";
    echo json_encode($return);
    mysqli_close($conn);
    die;
}
function DelMaster($conn, $DATA){
  $mItemCode = "DELETE FROM item_set WHERE mItemCode = '".$DATA['mItemCode']."'";
  $mysqli_query($conn, $Sql);
}

if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'ShowItem_Active_0') {
    ShowItem_Active_0($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getCatagory') {
    getCatagory($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getUnit') {
    getUnit($conn, $DATA);
  } else if ($DATA['STATUS'] == 'AddItem') {
    AddItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'AddUnit') {
    AddUnit($conn, $DATA);
  } else if ($DATA['STATUS'] == 'EditItem') {
    EditItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'CancelItem') {
    CancelItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'DeleteUnit') {
    DeleteUnit($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getdetail') {
    getdetail($conn, $DATA);
  } else if ($DATA['STATUS'] == 'GetHospital') {
    GetHospital($conn, $DATA);
  } else if ($DATA['STATUS'] == 'CreateItemCode') {
    CreateItemCode($conn, $DATA);
  } else if ($DATA['STATUS'] == 'NewItem') {
    NewItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'GetmainCat') {
    GetmainCat($conn, $DATA);
  } else if ($DATA['STATUS'] == 'ActiveItem') {
    ActiveItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'checkItemCode') {
    checkItemCode($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ShowItemMaster') {
    ShowItemMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'getdetailMaster') {
    getdetailMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'deleteMaster') {
    deleteMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'SaveQtyMaster') {
    SaveQtyMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'ShowItemModal') {
    ShowItemModal($conn, $DATA);
  }else if ($DATA['STATUS'] == 'AddItemMaster') {
    AddItemMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'chkItemMaster') {
    chkItemMaster($conn, $DATA);
  }else if ($DATA['STATUS'] == 'DelMaster') {
    DelMaster($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
