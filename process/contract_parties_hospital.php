<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Sql = "SELECT site.HptCode,site.HptName
FROM site WHERE site.IsStatus = 0";
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
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
		  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
	$boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function ShowDocument($conn,$DATA){
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);

  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];

  $sl1 = strlen($sDate);
  $sl2 = strlen($eDate);

//	 $Sql = "INSERT INTO log ( log ) VALUES ('$sl1  :  $sl2')";
//     mysqli_query($conn,$Sql);

  $Sql = "SELECT
    contract_parties_hospital.RowID,
    contract_parties_hospital.StartDate, 
    contract_parties_hospital.EndDate, 
    IFNULL(Detail,'') AS Detail, 
    (EndDate-DATE(NOW())) AS LeftDay, 
    site.HptName
  FROM  contract_parties_hospital 
  INNER JOIN site ON contract_parties_hospital.HptCode = site.HptCode
  WHERE contract_parties_hospital.IsStatus = 0 ";
  if(($sl1 > 9) && ($sl2 > 9)) $Sql .= "AND EndDate BETWEEN '$sDate' AND '$eDate' ";
  $Sql .= "ORDER BY (EndDate-DATE(NOW())) ASC";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
	$return[$count]['RowID'] 		= $Result['RowID'];
	$return[$count]['HptName'] 		= $Result['HptName'];
	$return[$count]['StartDate'] 	= $Result['StartDate'];
  $return[$count]['EndDate'] 		= $Result['EndDate'];
  $return[$count]['Detail'] 		= $Result['Detail'];
  $return[$count]['LeftDay'] 		= $Result['LeftDay'];
    $boolean = true;
    $count++;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
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

function getRow($conn,$DATA){
  $boolean = false;
  $count = 0;
  $RowID = $DATA["RowID"];

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
	$return[$count]['RowID'] 		= $Result['RowID'];
	$return[$count]['HptCode'] 		= $Result['HptCode'];
	$return[$count]['HptName'] 		= $Result['HptName'];
	$return[$count]['StartDate'] 	= $Result['StartDate'];
    $return[$count]['EndDate'] 		= $Result['EndDate'];
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
	  }elseif ($DATA['STATUS']=='getDepartment') {
        getDepartment($conn, $DATA);
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
