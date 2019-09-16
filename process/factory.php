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
            factory.TaxID ,
            contractfac.contractName , 
            contractfac.permission , 
            contractfac.Number , 
            contractfac.id 
            FROM
            factory
            LEFT JOIN contractfac ON contractfac.FacCode = factory.FacCode 
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
      $return[$count]['id'] = $Result['id']==null?"":$Result['id'];
      $return[$count]['contractName'] = $Result['contractName']==null?"":$Result['contractName'];
      $return[$count]['permission'] = $Result['permission']==null?"":$Result['permission'];
      $return[$count]['Number'] = $Result['Number']==null?"":$Result['Number'];    
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
    $number = $DATA['number'];
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
            factory.TaxID ,
            contractfac.contractName , 
            contractfac.permission , 
            contractfac.Number , 
            contractfac.id
            FROM
            factory
            LEFT JOIN contractfac ON contractfac.FacCode = factory.FacCode 
            WHERE factory.IsCancel = 0
            AND factory.FacCode = $FacCode ";
            if ($id != '') {
              $Sql .= " AND contractsite.id = $id";
            }
            $Sql .= " LIMIT 1";
    // var_dump($Sql); die;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return['FacCode'] = $number;
      $return['FacName'] = $Result['FacName'];
      $return['DiscountPercent'] = $Result['DiscountPercent'];
      $return['Price'] = $Result['Price'];
      $return['IsCancel'] = $Result['IsCancel'];
      $return['Address'] = $Result['Address'];
      $return['Post'] = $Result['Post'];
      $return['Tel'] = $Result['Tel'];
      $return['TaxID'] = $Result['TaxID'];
      $return['contractName'] = $Result['contractName'];
      $return['permission'] = $Result['permission'];
      $return['Number'] = $Result['Number'];
      $return['id'] = $Result['id'];
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


function AddItem($conn, $DATA)
  {
    $discount = $DATA['DiscountPercent']==null?"0":$Result['DiscountPercent'];
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
              $discount,
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
    $idcontract = $DATA["idcontract"];

    if($DATA["FacCode"] !="" && $idcontract !=""){
      $Sql ="DELETE FROM contractfac WHERE id=$idcontract " ;
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



    }else if($DATA["FacCode"]!=""){
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
function getFactory($conn, $DATA)
  {
      $count = 0;
      $Sql = "SELECT factory.FacCode,factory.FacName FROM factory 	WHERE IsCancel = 0";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['FacCode']  = $Result['FacCode'];
        $return[$count]['FacName']  = $Result['FacName'];
        $count++;
      }

      $return['status'] = "success";
      $return['form'] = "getFactory";
      echo json_encode($return);
      mysqli_close($conn);
      die;

  }






  if(isset($_POST['DATA']))



{
function Adduser($conn, $DATA)
  {
  $host = $DATA['host'];
  $ContractName = $DATA['ContractName'];
  $Position = $DATA['Position'];
  $phone = $DATA['phone'];
  $idcontract = $DATA['idcontract'];


//=======================================
$Sqlx = "SELECT COUNT(*) AS Countc
FROM
contractfac
WHERE contractfac.id = '$idcontract'";
$meQueryx = mysqli_query($conn,$Sqlx);
while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
$boolcountc = $Resultx['Countc'];
}
//=======================================

// ==============CHECK FACTORY====================
$Sql="SELECT COUNT(FacCode) AS cnt FROM contractfac WHERE FacCode = '$host'";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $cnt  = $Result['cnt'];
}
// ===============================================


  if($boolcountc ==0){
    if($cnt < 1){
    $Sql="INSERT INTO contractfac (contractfac.FacCode , contractfac.contractName , contractfac.permission , contractfac.Number) 
    VALUE ('$host','$ContractName','$Position','$phone')";
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
}else{
  $return['status'] = "failed";
  $return['msg'] = "adduserfacfailed";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
  }else{
      $Sql="UPDATE contractfac SET   contractfac.FacCode = '$host' , 
                                     contractfac.contractName = '$ContractName' , 
                                     contractfac.permission = '$Position' , 
                                     contractfac.Number = '$phone' 
      WHERE contractfac.id = $idcontract";
      if(mysqli_query($conn, $Sql)){
        $return['status'] = "success";
        $return['form'] = "AddItem";
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


  }

}





















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
      }else if ($DATA['STATUS'] == 'getFactory') {
        getFactory($conn,$DATA);
      }else if ($DATA['STATUS'] == 'Adduser') {
        Adduser($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
