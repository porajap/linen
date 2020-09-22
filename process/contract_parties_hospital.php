<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn,$DATA){
  $count = 0;
  $boolean = false;
  $lang = $DATA["lang"];
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];

  if($lang == 'en')
  {
    if($PmID == 5 || $PmID == 7)
    {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }
    else
    {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }
  else
  {
    if($PmID == 5 || $PmID == 7)
    {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }
    else
    {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }  
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
	$boolean = true;
  }
  $boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    $return[0]['PmID']  = $PmID;
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    $return[0]['PmID']  = $PmID;
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowDocument($conn,$DATA){
  $boolean = false;
  $count = 0;
  $HptCode = $DATA["HptCode"]==NULL?'':$DATA["HptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $lang = $_SESSION['lang'];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];
  $HptCode_permission = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  if($PmID == 5 )
  {
    $hpt = "AND site.HptCode ='$HptCode' ";
  }
  else
  {
    $hpt = "AND site.HptCode ='$HptCode' ";
  }
  $sl1 = strlen($sDate);
  $sl2 = strlen($eDate);

  $Sql = "SELECT
    contract_parties_hospital.RowID,
    contract_parties_hospital.StartDate, 
    contract_parties_hospital.EndDate, 
    IFNULL(Detail,'') AS Detail, 
    (EndDate-DATE(NOW())) AS LeftDay, 
    DATEDIFF(EndDate, DATE(NOW())) AS dateDiff ,
    site.HptName ,
    site.HptNameTH
  FROM  contract_parties_hospital 
  INNER JOIN site ON contract_parties_hospital.HptCode = site.HptCode
  WHERE contract_parties_hospital.IsStatus = 0 AND site.IsStatus = 0 $hpt";
  if(($sl1 > 9) && ($sl2 > 9)) $Sql .= "AND StartDate BETWEEN '$sDate' AND  '$eDate' ";
  $Sql .= "ORDER BY (EndDate-DATE(NOW())) ASC";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    
    if($lang =='en'){
      $sitePage = $Result['HptName'];
      $date = explode("-", $Result['StartDate']);
      $newdate = $date[2].'-'.$date[1].'-'.$date[0];
  
      $date2 = explode("-", $Result['EndDate']);
      $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];
  
      }else if ($lang == 'th'){
      $sitePage = $Result['HptNameTH'];
      $date = explode("-", $Result['StartDate']);
      $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);
  
      $date2 = explode("-", $Result['EndDate']);
      $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0] +543);
      }

	$return[$count]['RowID'] 		= $Result['RowID'];
	$return[$count]['HptName'] 		= $sitePage;
	$return[$count]['StartDate'] 	= $newdate;
  $return[$count]['EndDate2'] 		= $newdate2;
  $return[$count]['EndDate'] 		= $Result['EndDate'];
  $return[$count]['Detail'] 		= $Result['Detail'];
  $return[$count]['LeftDay'] 		= $Result['dateDiff'];
    $boolean = true;
    $count++;
  }
  $return['Count'] = $count;
  if($boolean){
    $return['HptCode'] = $HptCode;
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['HptCode'] = $HptCode;
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
	$return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getRow($conn,$DATA){
  $boolean = false;
  $count = 0;
  $RowID = $DATA["RowID"];
  $lang = $_SESSION['lang'];
//	 $Sql = "INSERT INTO log ( log ) VALUES ('$sl1  :  $sl2')";
//     mysqli_query($conn,$Sql);

  $Sql = "SELECT
  contract_parties_hospital.RowID,
  contract_parties_hospital.StartDate,
  contract_parties_hospital.EndDate,
  IFNULL(Detail,'') AS Detail,
  (EndDate-DATE(NOW())) AS LeftDay,
  site.HptCode,
  site.HptName
  FROM contract_parties_hospital
  INNER JOIN site ON contract_parties_hospital.HptCode = site.HptCode
  WHERE contract_parties_hospital.IsStatus = 0
  AND RowID = $RowID";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($lang =='en'){

      $date = explode("-", $Result['StartDate']);
      $newdate = $date[2].'-'.$date[1].'-'.$date[0];
  
      $date2 = explode("-", $Result['EndDate']);
      $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];
  
      }else if ($lang == 'th'){
  
      $date = explode("-", $Result['StartDate']);
      $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);
  
      $date2 = explode("-", $Result['EndDate']);
      $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0] +543);
  
      }
	$return[$count]['RowID'] 		= $Result['RowID'];
	$return[$count]['HptCode'] 		= $Result['HptCode'];
	$return[$count]['HptName'] 		= $Result['HptName'];
	$return[$count]['StartDate'] 	= $newdate;
    $return[$count]['EndDate'] 		= $newdate2;
    $return[$count]['Detail'] 		= $Result['Detail'];
	$return[$count]['LeftDay'] 		= $Result['LeftDay'];
	$Hosp 							= $Result['HptCode'];
    $boolean = true;
    $count++;
  }

  $count = 0;
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hosp'
		  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
	$Dep_ = 'Dep_'.$count;
    $return[$Dep_]['DepCode'] = $Result['DepCode'];
    $return[$Dep_]['DepName'] = $Result['DepName'];
    $count++;
  }

  $return['Dep_Cnt'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getRow";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "getRow";
	$return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SaveRow($conn,$DATA){
  $boolean 	= false;
  $count 	= 0;
  $isStatus = $DATA["isStatus"];
  $RowID 	= $DATA["RowID"];
  $hotid 	= $DATA["hotid"];
  $depid 	= $DATA["depid"];
  $sDate 	= $DATA["sDate"];
  $eDate 	= $DATA["eDate"];
  $Detail 	= $DATA["Detail"];
  $HptCode = $DATA["HptCode"];
  
  if($isStatus==0){
  	  $Sql = "INSERT INTO contract_parties_hospital
      ( StartDate,EndDate,HptCode,Detail,IsStatus )
      VALUES
      ( '$sDate','$eDate','$hotid','$Detail',0 )";
      mysqli_query($conn,$Sql);
  }else{
	  $Sql = "UPDATE contract_parties_hospital
			SET StartDate = '$sDate',
			EndDate = '$eDate',
			day_30 = 0,
			day_7 = 0,
			Detail = '$Detail'
			WHERE RowID = $RowID";
      mysqli_query($conn,$Sql);
  }

//	 $Sql = "INSERT INTO log ( log ) VALUES ('$isStatus / $sDate  :  $eDate :: $depid ::: $Detail')";
//     mysqli_query($conn,$Sql);

	ShowDocument($conn,$DATA);
}

function CancelRow($conn,$DATA){
  $boolean = false;
  $count = 0;
  $isStatus = $DATA["isStatus"];
  $RowID = $DATA["RowID"];

  $Sql = "UPDATE contract_parties_hospital SET IsStatus = 1 WHERE RowID = $RowID";
  mysqli_query($conn,$Sql);

	 $Sql = "INSERT INTO log ( log ) VALUES ('RowID :: $RowID')";
     mysqli_query($conn,$Sql);

	ShowDocument($conn,$DATA);
}

//==========================================================
//
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if($DATA['STATUS']=='OnLoadPage'){
        OnLoadPage($conn,$DATA);
	    }elseif($DATA['STATUS']=='ShowDocument'){
        ShowDocument($conn,$DATA);
      }elseif($DATA['STATUS']=='getRow'){
        getRow($conn,$DATA);
      }elseif($DATA['STATUS']=='SaveRow'){
        SaveRow($conn,$DATA);
      }elseif($DATA['STATUS']=='CancelRow'){
        CancelRow($conn,$DATA);
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
