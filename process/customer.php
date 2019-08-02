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
  $Dept = $DATA['Dept'];
  if($Dept==""){
    $Dept = 1;
  }
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT
          customer.CusCode,
          customer.DepCode,
          customer.CusName,
          customer.DiscountPercent,
          customer.Price,
          CASE customer.IsCancel WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsCancel,
          customer.Address,
          customer.Post,
          customer.Tel,
          customer.TaxID,
	        department.DepName
          FROM
          customer
          INNER JOIN department ON customer.DepCode = department.DepCode
          WHERE customer.IsCancel = 0
          AND customer.DepCode = $Dept
          AND (customer.CusCode LIKE '%$Keyword%' OR
          customer.CusName LIKE '%$Keyword%' OR
          customer.Address LIKE '%$Keyword%' OR
          customer.Tel LIKE '%$Keyword%' OR
          customer.Post LIKE '%$Keyword%' OR
          customer.TaxID LIKE '%$Keyword%' OR
          customer.Price LIKE '%$Keyword%' OR
          customer.DiscountPercent LIKE '%$Keyword%'
          )";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CusCode'] = $Result['CusCode'];
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['CusName'] = $Result['CusName'];
    $return[$count]['DiscountPercent'] = $Result['DiscountPercent'];
    $return[$count]['Price'] = $Result['Price'];
    $return[$count]['IsCancel'] = $Result['IsCancel'];
    $return[$count]['Address'] = $Result['Address'];
    $return[$count]['Post'] = $Result['Post'];
    $return[$count]['Tel'] = $Result['Tel'];
    $return[$count]['TaxID'] = $Result['TaxID'];
    $return[$count]['DepName'] = $Result['DepName'];
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
  $CusCode = $DATA['CusCode'];
  //---------------HERE------------------//
  $Sql = "SELECT
          customer.CusCode,
          customer.DepCode,
          customer.CusName,
          customer.DiscountPercent,
          customer.Price,
          CASE customer.IsCancel WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsCancel,
          customer.Address,
          customer.Post,
          customer.Tel,
          customer.TaxID,
	        department.DepName
          FROM
          customer
          INNER JOIN department ON customer.DepCode = department.DepCode
          WHERE customer.IsCancel = 0
          AND customer.CusCode = $CusCode LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['CusCode'] = $Result['CusCode'];
    $return['DepCode'] = $Result['DepCode'];
    $return['CusName'] = $Result['CusName'];
    $return['DiscountPercent'] = $Result['DiscountPercent'];
    $return['Price'] = $Result['Price'];
    $return['IsCancel'] = $Result['IsCancel'];
    $return['Address'] = $Result['Address'];
    $return['Post'] = $Result['Post'];
    $return['Tel'] = $Result['Tel'];
    $return['TaxID'] = $Result['TaxID'];
    $return['DepName'] = $Result['DepName'];
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
  $Hosp = $DATA['HOSP'];
  $Sql = "SELECT
          department.DepCode,
          department.HptCode,
          department.DepName,
          department.IsStatus
          FROM
          department
          WHERE department.HptCode = $Hosp";
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

function getHospital($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          site.HptCode,
          site.HptName,
          site.IsStatus
          FROM
          site
          WHERE site.IsStatus = 0
          ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']       = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHospital";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO customer(
          DepCode,
          CusName,
          DiscountPercent,
          Price,
          IsCancel,
          Address,
          Post,
          Tel,
          TaxID)
          VALUES
          (
            ".$DATA['DepCode'].",
            '".$DATA['CusName']."',
            ".$DATA['DiscountPercent'].",
            ".$DATA['Price'].",
            0,
            '".$DATA['Address']."',
            '".$DATA['Post']."',
            '".$DATA['Tel']."',
            '".$DATA['TaxID']."'
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
  if($DATA["CusCode"]!=""){
    $Sql = "UPDATE customer SET
            CusName = '".$DATA['CusName']."',
            DiscountPercent = ".$DATA['DiscountPercent'].",
            DepCode = ".$DATA['DepCode'].",
            Price = ".$DATA['Price'].",
            Address = '".$DATA['Address']."',
            Post = '".$DATA['Post']."',
            Tel = '".$DATA['Tel']."',
            TaxID = '".$DATA['TaxID']."'
            WHERE CusCode = ".$DATA['CusCode']."
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
  if($DATA["CusCode"]!=""){
    $Sql = "UPDATE customer SET
            IsCancel = 1
            WHERE CusCode = ".$DATA['CusCode']."
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
      }else if ($DATA['STATUS'] == 'getHospital') {
        getHospital($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'notfound';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
