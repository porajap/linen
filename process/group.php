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
  $PmID = $_SESSION['PmID'];
  $xHptCode = $DATA['HptCode'];
  // if($xHptCode==""){
  //   $xHptCode = 'BHQ';
  // }
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT site.HptCode,
          CASE site.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus,
          grouphpt.GroupCode,TRIM(grouphpt.GroupName) AS GroupName
          FROM site
          INNER JOIN grouphpt ON site.HptCode = grouphpt.HptCode
          WHERE grouphpt.IsStatus = 0
          AND site.HptCode = '$xHptCode'
          AND ( grouphpt.GroupCode LIKE '%$Keyword%' OR
          grouphpt.GroupName LIKE '%$Keyword%')
          ORDER BY grouphpt.GroupName ASC
          ";
  
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['DepCode'] = $Result['GroupCode'];
    $return[$count]['DepName'] = $Result['GroupName'];
    $count++;
  }
  $return['Count'] = $count;
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['form'] = "ShowItem";

    $return['status'] = "success";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getdetail($conn, $DATA)
{
  $count    = 0;
  $HptCode  = $DATA['HptCode'];
  $DepCode  = $DATA['DepCode'];
  $number   = $DATA['number'];
  //---------------HERE------------------//
  $Sql = "SELECT
          grouphpt.GroupCode,
          grouphpt.HptCode,
          TRIM(grouphpt.GroupName) AS DepName,
          grouphpt.IsStatus
          FROM grouphpt
          WHERE grouphpt.IsStatus = 0
          AND grouphpt.GroupCode = $DepCode  AND grouphpt.HptCode = '$HptCode'";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['DepCode'] 		    = $number;
    $return['DepCodeReal'] 		= $Result['GroupCode'];
    $return['HptCode'] 		    = $Result['HptCode'];
    $return['DepName'] 		    = $Result['DepName'];
    $return['IsStatus'] 	    = $Result['IsStatus'];
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
  $PmID = $_SESSION['PmID'];
  $HptCode1 = $_SESSION['HptCode'];
  $lang = $DATA["lang"];
  $count = 0;


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
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  $return[0]['PmID']  = $PmID;
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $count    = 0;
  $HptCode  = $DATA['HptCode'];
  $DepCode1 = trim($DATA['DepCode1']);
  $DepCode  = trim($DATA['DepCode']);
  $DepName  = trim($DATA['DepName']);
  $Userid   = $_SESSION['Userid'];
  $PmID     = $_SESSION['PmID'];

if($DepCode1 == ""){
  $Sql = "INSERT INTO grouphpt
          (
          GroupCode,
          HptCode,
          GroupName,
          IsStatus,
          DocDate ,
          Modify_Code ,
          Modify_Date
          )
          VALUES
          (
            '$DepCode',
            '$HptCode',
            '$DepName',
            0,
            NOW(),
            $Userid,
            NOW()
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
    $return['msg'] = "Repeatmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  }else{

    $Sql = "UPDATE grouphpt SET
    GroupCode =  '$DepCode',
    HptCode =  '$HptCode',
    GroupName = '$DepName',
    Modify_Date = NOW() ,
    Modify_Code =  $Userid
    WHERE GroupCode = ".$DATA['DepCode1']."
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
    $return['msg'] = "editfailed :  $xCenter";
    echo json_encode($return);
    mysqli_close($conn);
    die;
    }


  }


}

function EditItem($conn, $DATA)
{
  // var_dump($DATA); die;
  $count = 0;
  $HptCode = $DATA['HptCode'];
  $DepName = trim($DATA['DepName']);
  $xCenter = $DATA['xCenter'];
  $DepCode = $DATA['DepCode'];
  $Userid = $_SESSION['Userid'];
  $Sql =  "SELECT COUNT(*) as Cnt, DepCode FROM department
  WHERE department.HptCode =  '$HptCode' and department.IsStatus = 0   AND department.IsDefault = 1";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $count = $Result[ 'Cnt'];
    $xDepCode = $Result[ 'DepCode'];
  }
  if($xCenter == 1 && $count > 0 && $DepCode != $xDepCode){
    $return['status'] = "failed";
    $return['msg'] = "editcenterfailedmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

  $Sql = "UPDATE department SET
          HptCode =  '$HptCode',
          DepName = '$DepName',
          IsDefault =  $xCenter ,
          Modify_Date = NOW() ,
          Modify_Code =  $Userid   
          WHERE DepCode = ".$DATA['DepCode']."
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
    $return['msg'] = "editfailed :  $xCenter";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

  // if($DATA["DepCode"]!=""){
  //   $Sql = "UPDATE department SET
  //           HptCode =  '$HptCode',
  //           DepName = ' $DepName',
  //           IsDefault =  $xCenter 
  //           WHERE DepCode = ".$DATA['DepCode']."
  //   ";
  //   // var_dump($Sql); die;
  //   if(mysqli_query($conn, $Sql)){
  //     $return['status'] = "success";
  //     $return['form'] = "EditItem";
  //     $return['msg'] = "editsuccess";
  //     echo json_encode($return);
  //     mysqli_close($conn);
  //     die;
  //   }else{
  //     $return['status'] = "failed";
  //     $return['msg'] = "editfailed :  $xCenter";
  //     echo json_encode($return);
  //     mysqli_close($conn);
  //     die;
  //   }
  // }else{
  //   $return['status'] = "failed";
  //   $return['msg'] = "editfailed";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["DepCode"]!=""){
    $Sql = "UPDATE grouphpt SET
            IsStatus = 1
            WHERE GroupCode = ".$DATA['DepCode']."
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
