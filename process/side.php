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
    $Sql = "SELECT contractsite.contractName , contractsite.permission , contractsite.Number , contractsite.id , site.HptCode ,  site.HptName  , site.HptNameTH , site.Site_Path
    FROM site
    LEFT JOIN contractsite ON contractsite.HptCode = site.HptCode 
    WHERE site.IsStatus = 0
    AND (site.HptCode LIKE '%$Keyword%' OR site.HptName LIKE '%$Keyword%' OR site.HptNameTH LIKE '%$Keyword%') ORDER BY site.HptCode     ";
    // var_dump($Sql); die;
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return[$count]['HptCode'] = $Result['HptCode'];
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['sitepath'] = $Result['Site_Path'];
      $return[$count]['HptNameTH'] = $Result['HptNameTH'];
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
            CASE site.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus , site.HptNameTH , site.private , site.government , site.Site_Path ,  site.PayerCode , 
            (SELECT COUNT(*) FROM contractsite WHERE HptCode = '$HptCode')  AS cnt 
            FROM site
            LEFT JOIN contractsite ON contractsite.HptCode = site.HptCode 
            WHERE site.IsStatus = 0
            AND site.HptCode = '$HptCode' ";
            if ($id != '') {  $Sql .= " AND contractsite.id = $id"; }
            $Sql .= " LIMIT 1";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return['id']           = $Result['id'];
      $HptCode                = $Result['HptCode'];
      $return['HptCode']      = $Result['HptCode'];
      $return['HptName']      = $Result['HptName'];
      $return['Site_Path']    = $Result['Site_Path'];
      $return['contractName'] = $Result['contractName'];
      $return['permission']   = $Result['permission'];
      $return['Number']       = $Result['Number'];
      $return['HptNameTH']    = $Result['HptNameTH'];
      $return['private']      = $Result['private'];
      $return['government']   = $Result['government'];
      $return['PayerCode']    = $Result['PayerCode'];
      $PayerCode              = $Result['PayerCode'];
      $return['cnt']          = $Result['cnt'];

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
  // ==============================================
  $HptCode1     = $DATA['HptCode1'];
  $HptCode      = $DATA['HptCode'];
  $HptName      = $DATA['HptName'];
  $HptNameTH    = $DATA['HptNameTH'];
  $ContractName = $DATA['ContractName'];
  $Position     = $DATA['Position'];
  $phone        = $DATA['phone'];
  $idcontract   = $DATA['idcontract'];
  $sitepath     = $DATA['sitepath'];
  $PayerCode    = $DATA['PayerCode'];
  $xcenter1     = $DATA['xcenter1']==null?0:$DATA['xcenter1'];
  $xcenter2     = $DATA['xcenter2']==null?0:$DATA['xcenter2'];
  $Userid       = $_SESSION['Userid'];

  $Sql2="UPDATE department SET Ship_To = '$PayerCode' WHERE HptCode = '$HptCode' ";
  mysqli_query($conn, $Sql2);
  // ==============================================
  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          site
          WHERE site.HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }
  // ==============================================
  if($HptCode1== ""){
    $count = 0;
    $Sql="INSERT INTO site (site.HptCode , site.HptName , site.IsStatus , site.HptNameTH , site.private , site.government , site.DocDate ,site.Modify_Code ,site.Modify_Date,site.Site_Path,site.PayerCode) 
                            VALUE ('$HptCode','$HptName',0 ,'$HptNameTH' ,  $xcenter1 ,  $xcenter2 ,NOW() ,$Userid , NOW() , '$sitepath' , '$PayerCode')";
    $return['sss'] = $Sql;
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
      $Sql="UPDATE site SET site.HptCode = '$HptCode' , site.HptName = '$HptName' , site.HptNameTH = '$HptNameTH' , site.private = $xcenter1 , site.government= $xcenter2 ,site.Modify_Date = NOW() , Modify_Code = $Userid , Site_Path = '$sitepath' , PayerCode= '$PayerCode'  WHERE site.HptCode = '$HptCode1'";
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

function Adduser($conn, $DATA)
  {
  $host = $DATA['host'];
  $ContractName = $DATA['ContractName'];
  $Position = $DATA['Position'];
  $phone = $DATA['phone'];
  $idcontract = $DATA['idcontract'];
  $hosdetail = $DATA['hosdetail'];
//=======================================
  $Sqlx = "SELECT COUNT(*) AS Countc
  FROM
  contractsite
  WHERE contractsite.id = '$idcontract'";
$meQueryx = mysqli_query($conn,$Sqlx);
while ($Resultx = mysqli_fetch_assoc($meQueryx)) {
$boolcountc = $Resultx['Countc'];
}
//=======================================

  // ==============CHECK HOSPITAL====================
  $Sql="SELECT COUNT(HptCode) AS cnt FROM contractsite WHERE HptCode = '$host'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $cnt  = $Result['cnt'];
  }
  // ===============================================
  if($boolcountc ==0){
    if($cnt < 2){
    $Sql="INSERT INTO contractsite (contractsite.HptCode , contractsite.contractName , contractsite.permission , contractsite.Number) 
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
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "adduserfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  }else{
      $Sql="SELECT site.HptCode FROM site WHERE HptName ='$hosdetail'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $HptCodeupdate  = $Result['HptCode'];
      }
      $Sql="UPDATE contractsite SET  contractsite.HptCode = '$HptCodeupdate' , 
                                     contractsite.contractName = '$ContractName' , 
                                     contractsite.permission = '$Position' , 
                                     contractsite.Number = '$phone' 
      WHERE contractsite.id = $idcontract";
      if(mysqli_query($conn, $Sql)){
        $return['status'] = "success";
        $return['form'] = "AddItem";
        $return['msg'] = "editsuccess";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }else{
        $return['status'] = "failed";
        $return['msg'] = "adduserfailed";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }


  }

}
function getHotpital($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $count = 0;
  $Sql = "SELECT site.HptCode, site.HptName, site.HptNameTH , cs.contractName, COUNT(site.HptCode)
  FROM site 	
  LEFT JOIN contractsite cs ON cs.HptCode = site.HptCode
  WHERE IsStatus = 0
  GROUP BY site.HptCode
  HAVING COUNT(site.HptCode) < 2";
  $meQuery = mysqli_query($conn, $Sql);

  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($lang == 'en'){
      $HptNamePage = $Result['HptName'];
    }else{
      $HptNamePage = $Result['HptNameTH'];
    }
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $HptNamePage;
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  echo json_encode($return);
  mysqli_close($conn);
  die;

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

  

  if($HptCode!="" && $idcontract !=""){
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
  }else if ($HptCode!=""){
    $Sql = "UPDATE site SET
    IsStatus = 1
    WHERE HptCode = '$HptCode'";

    $Sql1 ="UPDATE department SET IsStatus = 1 WHERE HptCode = '$HptCode' ";
    mysqli_query($conn, $Sql);
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
      }else if ($DATA['STATUS'] == 'getHotpital') {
        getHotpital($conn,$DATA);
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
