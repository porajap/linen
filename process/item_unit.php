<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if ($Userid == "") {
  header("location:../index.html");
}
function ShowItem($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          item_unit.IsStatus
          FROM
          item_unit
           WHERE item_unit.IsStatus = 0
                    AND (item_unit.UnitCode LIKE '%$Keyword%' OR
                    item_unit.UnitName LIKE '%$Keyword%'
          )";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
    $count++;
  }
  $updatebhq = "SELECT
                    SUM(Weight) AS weight,
                    SUM(Price) AS price,
                    shelfcount.DocNo 
                  FROM
                    shelfcount_detail
                    INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo 
                  WHERE
                    DATE(shelfcount.complete_date) BETWEEN '2020-03-26' AND '2020-04-24' 
                  AND shelfcount.IsStatus <> 0
                  AND shelfcount.IsStatus <> 9
                  AND shelfcount_detail.TotalQty <> 0
                  GROUP BY shelfcount.DocNo ";
  $meQuery = mysqli_query($conn, $updatebhq);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $weight     =  $Result['weight'];
    $price      =  $Result['price'];
    $DocNo      =  $Result['DocNo'];
    $UPDATE_bhq = "UPDATE shelfcount SET Totalw = $weight , Totalp = $price  WHERE DocNo = '$DocNo' ";
    mysqli_query($conn, $UPDATE_bhq);
  }

  //   $sapGRI = "SELECT
  //             shelfcount_detail.DocNo,
  //             shelfcount_detail.ItemCode,
  //             shelfcount_detail.TotalQty,
  //             item.Weight,
  //             item.CategoryCode ,
  //             category_price.Price

  //             FROM
  //             shelfcount_detail
  //             INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
  //             INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  //             INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  //             WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
  //             and  shelfcount_detail.TotalQty > 0 
  //             and  shelfcount_detail.Weight  = 0
  //             and  shelfcount.IsStatus <> 0 
  //             and  shelfcount.IsStatus <> 9 
  //             and  category_price.HptCode = 'GRI'
  //             and shelfcount.SiteCode = 'GRI' ";
  //   $meQuery = mysqli_query($conn, $sapGRI);
  //   while ($Result = mysqli_fetch_assoc($meQuery))
  //   {
  //     $DocNo_GRI        =  $Result['DocNo'];
  //     $ItemCodeGRI      =  $Result['ItemCode'];
  //     $TOTAL            =  $Result['TotalQty'];
  //     $Priceitem         =  $Result['Price'];
  //     $WeightItem       =  $Result['Weight'];
  //     $Weight_gri        = ($TOTAL * $WeightItem) ;
  //     $Price_gri        = ($Weight_gri * $Priceitem) ;
  //     $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //     mysqli_query($conn, $UPDATE_sapGRI);
  //     $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //     mysqli_query($conn, $UPDATE_sapGRI_report);
  //   }

  //   $sapGRI = "SELECT
  //             shelfcount_detail.DocNo,
  //             shelfcount_detail.ItemCode,
  //             shelfcount_detail.TotalQty,
  //             item.Weight,
  //             item.CategoryCode ,
  //             category_price.Price

  //             FROM
  //             shelfcount_detail
  //             INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
  //             INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  //             INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  //             WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
  //             and  shelfcount_detail.TotalQty > 0 
  //             and  shelfcount_detail.Weight  = 0
  //             and  shelfcount.IsStatus <> 0 
  //             and  shelfcount.IsStatus <> 9 
  //             and  category_price.HptCode = 'BCH'
  //             and shelfcount.SiteCode = 'BCH' ";
  //   $meQuery = mysqli_query($conn, $sapGRI);
  //   while ($Result = mysqli_fetch_assoc($meQuery))
  //   {
  //     $DocNo_GRI        =  $Result['DocNo'];
  //     $ItemCodeGRI      =  $Result['ItemCode'];
  //     $TOTAL            =  $Result['TotalQty'];
  //     $Priceitem         =  $Result['Price'];
  //     $WeightItem       =  $Result['Weight'];
  //     $Weight_gri        = ($TOTAL * $WeightItem) ;
  //     $Price_gri        = ($Weight_gri * $Priceitem) ;
  //     $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //     mysqli_query($conn, $UPDATE_sapGRI);
  //     $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //     mysqli_query($conn, $UPDATE_sapGRI_report);
  //   }

  //   $sapGRI = "SELECT
  //             shelfcount_detail.DocNo,
  //             shelfcount_detail.ItemCode,
  //             shelfcount_detail.TotalQty,
  //             item.Weight,
  //             item.CategoryCode ,
  //             category_price.Price

  //             FROM
  //             shelfcount_detail
  //             INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
  //             INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  //             INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  //             WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
  //             and  shelfcount_detail.TotalQty > 0 
  //             and  shelfcount_detail.Weight  = 0
  //             and  shelfcount.IsStatus <> 0 
  //             and  shelfcount.IsStatus <> 9 
  //             and  category_price.HptCode = 'BHQ'
  //             and shelfcount.SiteCode = 'BHQ' ";
  //   $meQuery = mysqli_query($conn, $sapGRI);
  //   while ($Result = mysqli_fetch_assoc($meQuery))
  //   {
  //     $DocNo_GRI        =  $Result['DocNo'];
  //     $ItemCodeGRI      =  $Result['ItemCode'];
  //     $TOTAL            =  $Result['TotalQty'];
  //     $Priceitem         =  $Result['Price'];
  //     $WeightItem       =  $Result['Weight'];
  //     $Weight_gri        = ($TOTAL * $WeightItem) ;
  //     $Price_gri        = ($Weight_gri * $Priceitem) ;
  //     $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //     mysqli_query($conn, $UPDATE_sapGRI);
  //     $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //     mysqli_query($conn, $UPDATE_sapGRI_report);
  //   }

  //   $sapGRI = "SELECT
  //   shelfcount_detail.DocNo,
  //   shelfcount_detail.ItemCode,
  //   shelfcount_detail.TotalQty,
  //   item.Weight,
  //   item.CategoryCode ,
  //   category_price.Price

  //   FROM
  //   shelfcount_detail
  //   INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
  //   INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  //   INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  //   WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
  //   and  shelfcount_detail.TotalQty > 0 
  //   and  shelfcount_detail.Weight  = 0
  //   and  shelfcount.IsStatus <> 0 
  //   and  shelfcount.IsStatus <> 9 
  //   and  category_price.HptCode = 'LCB'
  //   and shelfcount.SiteCode = 'LCB' ";
  // $meQuery = mysqli_query($conn, $sapGRI);
  // while ($Result = mysqli_fetch_assoc($meQuery))
  // {
  //   $DocNo_GRI        =  $Result['DocNo'];
  //   $ItemCodeGRI      =  $Result['ItemCode'];
  //   $TOTAL            =  $Result['TotalQty'];
  //   $Priceitem         =  $Result['Price'];
  //   $WeightItem       =  $Result['Weight'];
  //   $Weight_gri        = ($TOTAL * $WeightItem) ;
  //   $Price_gri        = ($Weight_gri * $Priceitem) ;
  //   $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //   mysqli_query($conn, $UPDATE_sapGRI);
  //   $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //   mysqli_query($conn, $UPDATE_sapGRI_report);
  // }

  // $sapGRI = "SELECT
  // shelfcount_detail.DocNo,
  // shelfcount_detail.ItemCode,
  // shelfcount_detail.TotalQty,
  // item.Weight,
  // item.CategoryCode ,
  // category_price.Price

  // FROM
  // shelfcount_detail
  // INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
  // INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  // INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  // WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
  // and  shelfcount_detail.TotalQty > 0 
  // and  shelfcount_detail.Weight  = 0
  // and  shelfcount.IsStatus <> 0 
  // and  shelfcount.IsStatus <> 9 
  // and  category_price.HptCode = 'SRH'
  // and shelfcount.SiteCode = 'SRH' ";
  // $meQuery = mysqli_query($conn, $sapGRI);
  // while ($Result = mysqli_fetch_assoc($meQuery))
  // {
  //   $DocNo_GRI        =  $Result['DocNo'];
  //   $ItemCodeGRI      =  $Result['ItemCode'];
  //   $TOTAL            =  $Result['TotalQty'];
  //   $Priceitem         =  $Result['Price'];
  //   $WeightItem       =  $Result['Weight'];
  //   $Weight_gri        = ($TOTAL * $WeightItem) ;
  //   $Price_gri        = ($Weight_gri * $Priceitem) ;
  //   $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //   mysqli_query($conn, $UPDATE_sapGRI);
  //   $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //   mysqli_query($conn, $UPDATE_sapGRI_report);
  // }


  // $sapGRI = "SELECT
  // shelfcount_detail.DocNo,
  // shelfcount_detail.ItemCode,
  // shelfcount_detail.TotalQty,
  // item.Weight,
  // item.CategoryCode ,
  // category_price.Price

  // FROM
  // shelfcount_detail
  // INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
  // INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
  // INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
  // WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
  // and  shelfcount_detail.TotalQty > 0 
  // and  shelfcount_detail.Weight  = 0
  // and  shelfcount.IsStatus <> 0 
  // and  shelfcount.IsStatus <> 9 
  // and  category_price.HptCode = 'VGH'
  // and shelfcount.SiteCode = 'VGH' ";
  // $meQuery = mysqli_query($conn, $sapGRI);
  // while ($Result = mysqli_fetch_assoc($meQuery))
  // {
  //   $DocNo_GRI        =  $Result['DocNo'];
  //   $ItemCodeGRI      =  $Result['ItemCode'];
  //   $TOTAL            =  $Result['TotalQty'];
  //   $Priceitem         =  $Result['Price'];
  //   $WeightItem       =  $Result['Weight'];
  //   $Weight_gri        = ($TOTAL * $WeightItem) ;
  //   $Price_gri        = ($Weight_gri * $Priceitem) ;
  //   $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //   mysqli_query($conn, $UPDATE_sapGRI);
  //   $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
  //   mysqli_query($conn, $UPDATE_sapGRI_report);
  // }

  $sapGRI = "SELECT
    shelfcount_detail.DocNo,
    shelfcount_detail.ItemCode,
    shelfcount_detail.TotalQty,
    item.Weight,
    item.CategoryCode ,
    category_price.Price

    FROM
    shelfcount_detail
    INNER JOIN item ON item.ItemCode = shelfcount_detail.ItemCode
    INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
    INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
    WHERE shelfcount.DocDate BETWEEN '2020-02-26' AND '2020-03-24'
    and  shelfcount_detail.TotalQty > 0 
    and  shelfcount_detail.Weight  = 0
    and  shelfcount.IsStatus <> 0 
    and  shelfcount.IsStatus <> 9 
    and  category_price.HptCode = 'RPH'
    and shelfcount.SiteCode = 'RPH' ";
  $meQuery = mysqli_query($conn, $sapGRI);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DocNo_GRI        =  $Result['DocNo'];
    $ItemCodeGRI      =  $Result['ItemCode'];
    $TOTAL            =  $Result['TotalQty'];
    $Priceitem         =  $Result['Price'];
    $WeightItem       =  $Result['Weight'];
    $Weight_gri        = ($TOTAL * $WeightItem);
    $Price_gri        = ($Weight_gri * $Priceitem);
    $UPDATE_sapGRI = "UPDATE shelfcount_detail SET Weight = $Weight_gri , Price = $Price_gri , UPSAP = 1 WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
    mysqli_query($conn, $UPDATE_sapGRI);
    $UPDATE_sapGRI_report = "UPDATE report_sc SET Weight = $Weight_gri , Price = $Price_gri  WHERE DocNo = '$DocNo_GRI' AND ItemCode = '$ItemCodeGRI' ";
    mysqli_query($conn, $UPDATE_sapGRI_report);
  }
















  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
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
  $count = 0;
  $UnitCode = $DATA['UnitCode'];
  $number = $DATA['number'];
  //---------------HERE------------------//
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          CASE item_unit.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus
          FROM
          item_unit
          WHERE item_unit.IsStatus = 0
          AND item_unit.UnitCode = $UnitCode LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['UnitCode'] = $number;
    $return['UnitCodeReal'] = $Result['UnitCode'];
    $return['UnitName'] = $Result['UnitName'];
    //$return['IsStatus'] = $Result['IsStatus'];
    $count++;
  }

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
  $Userid = $_SESSION['Userid'];
  $count = 0;
  $Sql = "INSERT INTO item_unit(
          UnitName,
          IsStatus,
          DocDate ,
          Modify_Code ,
          Modify_Date
         )
          VALUES
          (
            '" . $DATA['UnitName'] . "',
            0,
            NOW(),
            $Userid,
            NOW()
          )
  ";
  // var_dump($Sql); die;
  if (mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "AddItem";
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
  $Userid = $_SESSION['Userid'];
  $count = 0;
  if ($DATA["UnitCode"] != "") {
    $Sql = "UPDATE item_unit SET
            UnitCode = '" . $DATA['UnitCode'] . "',
            UnitName = '" . $DATA['UnitName'] . "',
            Modify_Date = NOW() ,
            Modify_Code =  $Userid   
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
  if ($DATA["UnitCode"] != "") {
    $Sql = "UPDATE item_unit SET
            IsStatus = 1
            WHERE UnitCode = " . $DATA['UnitCode'] . "
    ";
    // var_dump($Sql); die;
    if (mysqli_query($conn, $Sql)) {
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

if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getSection') {
    getSection($conn, $DATA);
  } else if ($DATA['STATUS'] == 'AddItem') {
    AddItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'EditItem') {
    EditItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'CancelItem') {
    CancelItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getdetail') {
    getdetail($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
