<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  // $Dept = $DATA['Dept'];
  // if($Dept==""){
  //   $Dept = 1;
  // }
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT
          factory.FacCode,
          factory.FacName,
          factory.DiscountPercent,
          factory.Price,
          CASE factory.IsCancel WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsCancel,
          factory.Address,
          factory.Post,
          factory.Tel,
          factory.TaxID
          FROM
          factory
					WHERE factory.IsCancel = 0
          AND (factory.FacCode LIKE '%$Keyword%' OR
          factory.FacName LIKE '%$Keyword%' OR
          factory.DiscountPercent LIKE '%$Keyword%' OR
          factory.Price LIKE '%$Keyword%' OR
          factory.Address LIKE '%$Keyword%' OR
          factory.Post LIKE '%$Keyword%' OR
          factory.Tel LIKE '%$Keyword%' OR
          factory.TaxID LIKE '%$Keyword%'
          )
        ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacCode'] = $Result['FacCode'];
    //$return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['FacName'] = $Result['FacName'];
    $return[$count]['DiscountPercent'] = $Result['DiscountPercent'];
    $return[$count]['Price'] = $Result['Price'];
    $return[$count]['IsCancel'] = $Result['IsCancel'];
    $return[$count]['Address'] = $Result['Address'];
    $return[$count]['Post'] = $Result['Post'];
    $return[$count]['Tel'] = $Result['Tel'];
    $return[$count]['TaxID'] = $Result['TaxID'];
    //$return[$count]['DepName'] = $Result['DepName'];
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
  $FacCode = $DATA['FacCode'];
  //---------------HERE------------------//
  $Sql = "SELECT
          factory.FacCode,
          factory.FacName,
          factory.DiscountPercent,
          factory.Price,
           CASE factory.IsCancel WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsCancel,
          factory.Address,
          factory.Post,
          factory.Tel,
          factory.TaxID
          FROM
          factory
          WHERE factory.IsCancel = 0
          AND factory.FacCode = $FacCode LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['FacCode'] = $Result['FacCode'];
    //$return['DepCode'] = $Result['DepCode'];
    $return['FacName'] = $Result['FacName'];
    $return['DiscountPercent'] = $Result['DiscountPercent'];
    $return['Price'] = $Result['Price'];
    $return['IsCancel'] = $Result['IsCancel'];
    $return['Address'] = $Result['Address'];
    $return['Post'] = $Result['Post'];
    $return['Tel'] = $Result['Tel'];
    $return['TaxID'] = $Result['TaxID'];
    //$return['DepName'] = $Result['DepName'];
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

// function getSection($conn, $DATA)
// {
//   $count = 0;
//   $Sql = "SELECT
//           department.DepCode,
//           department.HptCode,
//           department.DepName,
//           department.IsStatus
//           FROM
//           department";
//   $meQuery = mysqli_query($conn, $Sql);
//   while ($Result = mysqli_fetch_assoc($meQuery)) {
//     $return[$count]['DepCode']       = $Result['DepCode'];
//     $return[$count]['DepName']  = $Result['DepName'];
//     $count++;
//   }
//
//   $return['status'] = "success";
//   $return['form'] = "getSection";
//   echo json_encode($return);
//   mysqli_close($conn);
//   die;
//
// }

function AddItem($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO factory(
          FacName,
          DiscountPercent,
          Price,
          IsCancel,
          Address,
          Post,
          Tel,
          TaxID)
          VALUES
          (
            '".$DATA['FacName']."',
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
  if($DATA["FacCode"]!=""){
    $Sql = "UPDATE factory SET
            FacName = '".$DATA['FacName']."',
            DiscountPercent = ".$DATA['DiscountPercent'].",
            Price = ".$DATA['Price'].",
            Address = '".$DATA['Address']."',
            Post = '".$DATA['Post']."',
            Tel = '".$DATA['Tel']."',
            TaxID = '".$DATA['TaxID']."'
            WHERE FacCode = ".$DATA['FacCode']."
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
  if($DATA["FacCode"]!=""){
    $Sql = "UPDATE factory SET
            IsCancel = 1
            WHERE FacCode = ".$DATA['FacCode']."
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
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
