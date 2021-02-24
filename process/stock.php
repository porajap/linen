<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if ($Userid == "") {
  header("location:../index.html");
}
function OnLoadPage($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $lang = $DATA["lang"];
  if ($lang == 'en') {
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0";
  } else {
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0";
  }
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
  $PmID = $_SESSION['PmID'];
  // $HptCode = $_SESSION['HptCode'];
  $count = 0;
  $boolean = false;
  $HptCode = $DATA["HptCode"] == null ? $_SESSION['HptCode'] : $DATA["HptCode"];

  if ($PmID != 1 && $PmID != 2 && $PmID != 3) {
    $Sql = "SELECT department.DepCode,department.DepName,department.IsDefault
    FROM department
    WHERE department.HptCode = '$HptCode' 
    AND department.IsActive = 1
    AND department.IsStatus = 0
    ORDER BY department.DepName ASC";
  } else {
    $Sql = "SELECT department.DepCode,department.DepName,department.IsDefault
    FROM department
    WHERE department.HptCode = '$HptCode'
    AND department.IsActive = 1
    AND department.IsStatus = 0
    ORDER BY department.DepName ASC";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['IsDefault'] = $Result['IsDefault'];
    $count++;
    $boolean = true;
  }
  $return['sql'] = $Sql;
  $return['Row'] = $count;

  if ($boolean) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function updatepar($conn, $DATA)
{
  $par = $DATA["par"];
  $rowid = $DATA["rowid"];

  $Sql = "UPDATE par_item_stock SET ParQty  = $par 
        WHERE RowID = $rowid";
  mysqli_query($conn, $Sql);
}
function updatetotal($conn, $DATA)
{
  $total = $DATA["total"];
  $rowid = $DATA["rowid"];

  $Sql = "UPDATE par_item_stock SET TotalQty  = $total 
        WHERE RowID = $rowid";
  mysqli_query($conn, $Sql);
}

function ShowDocument($conn, $DATA)
{
  $boolean    = false;
  $count        = 0;
  $dept         = $DATA["dept"];
  $hos          = $DATA["hos"];
  $search     = $DATA["search"];
  $PmID       = $_SESSION['PmID'];

  $Sql = "SELECT
            par_item_stock.ItemCode,
            par_item_stock.RowID,
            item.ItemName,
            department.DepCode,
            department.DepName,
            site.HptName,
            par_item_stock.ParQty,
            par_item_stock.TotalQty,
            item_category.CategoryName
          FROM par_item_stock
          INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
          INNER JOIN department ON par_item_stock.DepCode = department.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode   ";
  if ($dept != null) {
    $Sql .= "WHERE site.HptCode = '$hos' AND par_item_stock.DepCode =  '$dept' AND item.ItemName LIKE '%$search%' ";
  } elseif ($dept == null) {
    $Sql .= "WHERE site.HptCode = '$hos' AND item.ItemName LIKE '%$search%'";
  }
  $return['sql']    = $Sql;
  $meQuery       = mysqli_query($conn, $Sql);
  $Num_Rows   = mysqli_num_rows($meQuery);

  $Per_Page = 200;

  $Page =  $DATA["Page"];
  if (!$DATA["Page"]) {
    $Page = 1;
  }
  $Prev_Page = $Page - 1;
  $Next_Page = $Page + 1;

  $Page_Start = (($Per_Page * $Page) - $Per_Page);

  if ($Num_Rows <= $Per_Page) {
    $Num_Pages = 1;
  } else if (($Num_Rows % $Per_Page) == 0) {
    $Num_Pages = ($Num_Rows / $Per_Page);
  } else {
    $Num_Pages = ($Num_Rows / $Per_Page) + 1;
    $Num_Pages = (int)$Num_Pages;
  }
  $return['Num_Rows']           = $Num_Rows;
  $return['Num_Pages']           = $Num_Pages;
  $return['Prev_Page']            = $Prev_Page;
  $return['Next_Page']            = $Next_Page;
  $return['Page']                       = $Page;
  $return['PmID']                       = $PmID;


  $Sql .= "GROUP BY par_item_stock.ItemCode , par_item_stock.DepCode ORDER BY department.DepCode,item.ItemName  LIMIT $Page_Start , $Per_Page ";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode']           = $Result['ItemCode'];
    $return[$count]['ItemName']           = $Result['ItemName'];
    $return[$count]['CategoryName']   = $Result['CategoryName'];
    $return[$count]['DepCode']             = $Result['DepCode'];
    $return[$count]['DepName']           = $Result['DepName'];
    $return[$count]['Qty']                      = $Result['TotalQty'];
    $return[$count]['ParQty']               = $Result['ParQty'];
    $return[$count]['RowID']                 = $Result['RowID'];

    $boolean = true;
    $count++;
  }


  $return['Row'] = $count;
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
    $return['msg'] = "nodetail";
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
  } elseif ($DATA['STATUS'] == 'ShowDocument') {
    ShowDocument($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'updatetotal') {
    updatetotal($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'updatepar') {
    updatepar($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
