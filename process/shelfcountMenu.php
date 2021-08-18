<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'ShowDoc_Menu') {
    ShowDoc_Menu($conn);
  } else if ($_POST['FUNC_NAME'] == 'ShowDocDetail_Menu') {
    ShowDocDetail_Menu($conn);
  } else if ($_POST['FUNC_NAME'] == 'updateWeightAndPrice') {
    updateWeightAndPrice($conn);
  }
}


function ShowDoc_Menu($conn)
{

  $hotpCode = $_SESSION['HptCode'];
  $lang = $_SESSION['lang'];
  $PmID = $_SESSION['PmID'];
  $DepCode = $_SESSION['DepCode'];
  $userid   = $_SESSION["Userid"];
  $DocNo = $_POST['DocNo'];
  $return = array();

  $Sql = "SELECT
            shelfcount.SiteCode,
            shelfcount.DepCode,
            shelfcount.DocDate,
            shelfcount.DocNo,
            users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix ,
            users.FName,
            TIME(shelfcount.Modify_Date) Modify_Date,
            department.DepName,
            shelfcount.phoneNumber
          FROM
            shelfcount
            INNER JOIN users ON shelfcount.Modify_Code = users.ID 
            INNER JOIN department ON shelfcount.DepCode = department.DepCode 
          WHERE
            shelfcount.DocNo = '$DocNo'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($lang == 'en') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
      $Result['ThName'] = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
    } else if ($lang == 'th') {
      $date2 = explode("-", $Result['DocDate']);
      $Result['DocDate'] = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
      $Result['ThName']   = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
    }
    $return[] = $Result;
  }

  // ==============================



  // ================================
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function ShowDocDetail_Menu($conn)
{
  $DocNo = $_POST['DocNo'];
  $SiteCode = $_POST['SiteCode'];
  $return = array();

  $Sql = "SELECT
            shelfcount_detail.Id,
            shelfcount_detail.ItemCode,
            item.ItemName,
            item_unit.UnitName,
            item_unit.UnitCode,
            shelfcount_detail.ParQty,
            shelfcount_detail.CcQty,
            shelfcount_detail.TotalQty,
            shelfcount_detail.Over,
            shelfcount_detail.Short,
            item.Weight,
            shelfcount_detail.Weight AS WeightShow,
            category_price.Price,
            ( SELECT chk_sign FROM shelfcount WHERE DocNo = '$DocNo' ) AS chk_sign 
          FROM
            item
            INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
            INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
            INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
            INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo 
          WHERE
            shelfcount_detail.DocNo = '$DocNo' 
            AND category_price.HptCode = '$SiteCode' 
          GROUP BY
            item.ItemName 
          ORDER BY
            item.ItemName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function updateWeightAndPrice($conn)
{
  $queryUpdate = $_POST['queryUpdate'];
  $Sql = $queryUpdate;

  if (mysqli_multi_query($conn, $Sql)) {

    echo "1";
  }



  exit();
  mysqli_close($conn);
  die;
}
