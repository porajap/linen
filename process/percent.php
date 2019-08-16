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
  $HptCode = $_SESSION['HptCode'];
  $count = 0;
  $boolean = false;
  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE site.IsStatus = 0 AND site.HptCode = '$HptCode'";
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
      AND department.IsDefault = 1
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
  $Datepicker = $DATA["Datepicker"];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];

//	 $Sql = "INSERT INTO log ( log ) VALUES ('$deptCode :: $sDate : $eDate')";
//     mysqli_query($conn,$Sql);

  $Sql = "SELECT
  site.HptName,
  department.DepName,
  dirty.DocNo AS DocNo1,
  DATE(dirty.DocDate) AS DocDate1,
  dirty.Total AS Total1,
  clean.DocNo AS DocNo2,
  DATE(clean.DocDate) AS DocDate2,
  clean.Total AS Total2,
  ROUND( (((clean.Total - dirty.Total )/dirty.Total)*100), 2)  AS Precent
  FROM clean
  INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
  INNER JOIN department ON clean.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  WHERE DATE(dirty.DocDate) BETWEEN '$sDate' AND '$eDate'
  AND department.DepCode = $deptCode
  ORDER BY clean.DocNo DESC LIMIT 100";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
	$return[$count]['HptName'] 	= $Result['HptName'];
	$return[$count]['DepName'] 	= $Result['DepName'];
    $return[$count]['DocNo1'] 	= $Result['DocNo1'];
    $return[$count]['DocDate1'] = $Result['DocDate1'];
	$return[$count]['Total1'] 	= $Result['Total1'];
	$return[$count]['DocNo2'] 	= $Result['DocNo2'];
    $return[$count]['DocDate2'] = $Result['DocDate2'];
	$return[$count]['Total2'] 	= $Result['Total2'];
	$return[$count]['Precent'] 	= abs($Result['Precent']);
	$DepName = $Result['DepName'];
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
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
