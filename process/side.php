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
    $Sql = "SELECT contractsite.contractName , contractsite.permission , contractsite.Number , contractsite.id , site.HptCode ,  site.HptName 
    FROM site
    LEFT JOIN contractsite ON contractsite.HptCode = site.HptCode 
    WHERE site.IsStatus = 0
    AND (site.HptCode LIKE '%$Keyword%' OR site.HptName LIKE '%$Keyword%')      ";
    // var_dump($Sql); die;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['HptCode'] = $Result['HptCode'];
      $return[$count]['HptName'] = $Result['HptName'];
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
  $HptCode = $DATA['HptCode'];
  $id = $DATA['id'];
  //---------------HERE------------------//
  $Sql = "SELECT contractsite.contractName , contractsite.permission , contractsite.Number , contractsite.id , site.HptCode ,  site.HptName ,
          CASE site.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus
          FROM
          site
          LEFT JOIN contractsite ON contractsite.HptCode = site.HptCode 
          WHERE site.IsStatus = 0
          AND site.HptCode = '$HptCode' ";
            if ($id != '') {
              $Sql .= " AND contractsite.id = $id";
            }
            $Sql .= " LIMIT 1";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['id'] = $Result['id'];
    $return['HptCode'] = $Result['HptCode'];
    $return['HptName'] = $Result['HptName'];
    $return['contractName'] = $Result['contractName'];
    $return['permission'] = $Result['permission'];
    $return['Number'] = $Result['Number'];

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
          department.HptCode,
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
  $HptCode = $DATA['HptCode'];
  $HptName = $DATA['HptName'];
  $ContractName = $DATA['ContractName'];
  $Position = $DATA['Position'];
  $phone = $DATA['phone'];
  $idcontract = $DATA['idcontract'];

  // ==============================================
  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          site
          WHERE site.HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }

  $Sqlx = "SELECT COUNT(*) AS Countc
          FROM
          contractsite
          WHERE contractsite.id = '$idcontract'";
  $meQueryx = mysqli_query($conn,$Sqlx);
  while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
    $boolcountc = $Resultx['Countc'];
  }
  // ==============================================

  if($boolcount==0){
    $count = 0;
    $Sql="INSERT INTO site (site.HptCode , site.HptName , site.IsStatus) VALUE ('$HptCode','$HptName',0)";
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
    $Sql="UPDATE site SET  site.HptName = '$HptName' WHERE site.HptCode = '$HptCode'";
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

function EditItem($conn, $DATA)
{
  $count = 0;
  if($DATA["HptCode"]!=""){
    $Sql = "UPDATE site SET
            HptName = '".$DATA['HptName']."'
            WHERE HptCode = ".$DATA['HptCode']."
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
  $HptCode = $DATA["HptCode"];
  $idcontract = $DATA["idcontract"];
  $count = 0;

 

  if('$HptCode'!="" && $idcontract !=""){
    $Sql ="DELETE FROM contractsite WHERE id=$idcontract " ;
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
  }else if ('$HptCode'!=""){
    $Sql = "UPDATE site SET
    IsStatus = 1
    WHERE HptCode = '$HptCode'";
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
