<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}

function getSection($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_SESSION['lang'];
  $count = 0;
  $count2 = 0;
  if($lang == 'en'){
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }else{
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }     
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $return[0]['PmID']  = $PmID;
    $count++;
  }
  $return[0]['count']  = $count;


  // if($lang == 'en'){
  //   if($PmID == 5 || $PmID == 7){
  //   $Sql2 = "SELECT factory.FacCode,factory.FacName
  //   FROM factory WHERE factory.IsCancel = 0 AND HptCode = '$HptCode1'";
  //   }else{
  //     $Sql2 = "SELECT factory.FacCode,factory.FacName
  //     FROM factory WHERE factory.IsCancel = 0 ";
  //   }
  // }else{
  //   if($PmID == 5 || $PmID == 7){
  //   $Sql2 = "SELECT factory.FacCode,factory.FacNameTH AS FacName
  //   FROM factory WHERE factory.IsCancel = 0 AND HptCode = '$HptCode1'";
  //   }else{
  //     $Sql2 = "SELECT factory.FacCode,factory.FacNameTH AS FacName
  //     FROM factory WHERE factory.IsCancel = 0 ";
  //   }
  // }     
  // $meQuery2 = mysqli_query($conn, $Sql2);
  // while ($Result2 = mysqli_fetch_assoc($meQuery2)) {
  //   $return[$count2]['FacCode']  = $Result2['FacCode'];
  //   $return[$count2]['FacName']  = $Result2['FacName'];
  //   $return[0]['PmID']  = $PmID;
  //   $count2++;
  // }


  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}
function getfactory($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $HptCode = $DATA['HptCode']==null?$HptCode1:$DATA['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $_SESSION['lang'];
  $count2 = 0;
  if($lang == 'en'){
    $Sql2 = "SELECT factory.FacCode,factory.FacName
    FROM factory WHERE factory.IsCancel = 0 AND HptCode = '$HptCode'";
    
  }else{
    $Sql2 = "SELECT factory.FacCode,factory.FacNameTH AS FacName
    FROM factory WHERE factory.IsCancel = 0 AND HptCode = '$HptCode'";
    
  }     
  $meQuery2 = mysqli_query($conn, $Sql2);
  while ($Result2 = mysqli_fetch_assoc($meQuery2)) {
    $return[$count2]['FacCode']  = $Result2['FacCode'];
    $return[$count2]['FacName']  = $Result2['FacName'];
    $return[0]['PmID']  = $PmID;
    $count2++;
  }
  $return[0]['count2']  = $count2;

  $return['status'] = "success";
  $return['form'] = "getfactory";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}
function ShowItem($conn, $DATA)
{
  $HptCode = $DATA['HptCode'];
  $Keyword = $DATA['Keyword'];
  $factory = $DATA['factory'];
  $count = 0;
  $Select = " SELECT dfn.SendTime, dfn.ID  
  FROM delivery_fac_nhealth dfn 
  WHERE dfn.HptCode = '$HptCode'  AND dfn.FacCode = '$factory' ";
    $meQuery = mysqli_query($conn, $Select);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['SendTime']  = $Result['SendTime'];
      $return[$count]['ID']  = $Result['ID'];
      $count++;
    }
    $return['Count'] = $count;
    $return['status'] = "success";
    $return['form'] = 'ShowItem';
    echo json_encode($return);
    mysqli_close($conn);
    die;
    
}
function AddItem($conn, $DATA)
{
  $Userid     = $_SESSION['Userid'];
  $HptCode = $DATA['HptCode'];
  $factory    = $DATA['factory'];
  $Time       = $DATA['Time'];
  $Sql = "INSERT INTO delivery_fac_nhealth (HptCode, FacCode ,SendTime )VALUES('$HptCode',$factory,'$Time' )";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function getDetail($conn, $DATA)
{
  $ID = $DATA['ID'];
  $Sql = "SELECT dfn.ID, dfn.FacCode , dfn.SendTime
  FROM delivery_fac_nhealth dfn WHERE dfn.ID = '$ID'";
  $meQuery = mysqli_query($conn, $Sql);
  $Result = mysqli_fetch_assoc($meQuery);
  $return['ID']  = $Result['ID'];
  $return['FacCode']  = $Result['FacCode'];
  $return['SendTime']  = $Result['SendTime'];
  $return['status'] = "success";
  $return['form'] = 'getDetail';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function EditItem($conn, $DATA)
{
  $Userid = $_SESSION['Userid'];
  $ID = $DATA['TimeID'];
  $HptCode = $DATA['HptCode'];
  $Time = $DATA['Time'];
  $Sql = "UPDATE time_express SET time_value = '$Time' , Modify_Date = NOW() , Modify_Code =  $Userid    WHERE ID = $ID";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
function CancelItem($conn, $DATA)
{
  $TimeID = $DATA['TimeID'];
  $Sql = "DELETE FROM delivery_fac_nhealth WHERE ID = $TimeID";
  mysqli_query($conn, $Sql);
  ShowItem($conn, $DATA);
}
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getDetail') {
        getDetail($conn, $DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getfactory') {
        getfactory($conn, $DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
