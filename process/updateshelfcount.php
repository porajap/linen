<?php
require '../connect/connect.php';
$sDate = $_GET['sDate'];
$eDate = $_GET['eDate'];
$siteCode = $_GET['siteCode'];

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
WHERE shelfcount.DocDate BETWEEN '$sDate' AND '$eDate'
and  shelfcount_detail.TotalQty > 0 
and  shelfcount_detail.Weight  = 0
and  shelfcount.IsStatus <> 0 
and  shelfcount.IsStatus <> 9 
and  category_price.HptCode = '$siteCode'
and shelfcount.SiteCode = '$siteCode' ";
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


$updatebhq = "SELECT
              SUM(Weight) AS weight,
              SUM(Price) AS price,
              shelfcount.DocNo 
              FROM
              shelfcount_detail
              INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo 
              WHERE
              DATE(shelfcount.complete_date) BETWEEN '$sDate' AND '$eDate' 
              AND shelfcount.IsStatus <> 0
              AND shelfcount.IsStatus <> 9
              AND shelfcount_detail.TotalQty <> 0
              AND shelfcount.SiteCode = '$siteCode'
              GROUP BY shelfcount.DocNo ";
$meQuery = mysqli_query($conn, $updatebhq);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $weight     =  $Result['weight'];
  $price      =  $Result['price'];
  $DocNo      =  $Result['DocNo'];
  $UPDATE_bhq = "UPDATE shelfcount SET Totalw = $weight , Totalp = $price  WHERE DocNo = '$DocNo' ";
  mysqli_query($conn, $UPDATE_bhq);
}


mysqli_close($conn);
die;
