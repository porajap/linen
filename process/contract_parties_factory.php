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
  $lang = $_SESSION['lang'];

  if($lang == 'en'){
    $Sql = "SELECT factory.FacCode,factory.FacName
    FROM factory WHERE factory.IsCancel = 0";
  }else{
    $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName
    FROM factory WHERE factory.IsCancel = 0";
  }

  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacCode'] = $Result['FacCode'];
    $return[$count]['FacName'] = $Result['FacName'];
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
  $Sql = "SELECT factory.FacCode,factory.FacName
  FROM factory
  WHERE factory.FacCode = '$Hotp'";
      $return['sql'] = $Sql;
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
  $lang = $_SESSION['lang'];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];

  $sl1 = strlen($sDate);
  $sl2 = strlen($eDate);

//	 $Sql = "INSERT INTO log ( log ) VALUES ('$sl1  :  $sl2')";
//     mysqli_query($conn,$Sql);

  $Sql = "SELECT RowID,FacName,FacNameTH,StartDate,EndDate,IFNULL(Detail,'') AS Detail,(EndDate-DATE(NOW())) AS LeftDay
  FROM contract_parties_factory
  INNER JOIN factory ON contract_parties_factory.FacCode = factory.FacCode
  WHERE contract_parties_factory.IsStatus = 0 ";
  if(($sl1 > 9) && ($sl2 > 9)) $Sql .= "AND EndDate BETWEEN '$sDate' AND '$eDate' ";
  $Sql .= "ORDER BY (EndDate-DATE(NOW())) ASC";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($lang =='en'){
    $FacNamePage = $Result['FacName'];
    $date = explode("-", $Result['StartDate']);
    $newdate = $date[2].'-'.$date[1].'-'.$date[0];

    $date2 = explode("-", $Result['EndDate']);
    $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];

    }else if ($lang == 'th'){
      $FacNamePage = $Result['FacNameTH'];
    $date = explode("-", $Result['StartDate']);
    $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);

    $date2 = explode("-", $Result['EndDate']);
    $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0] +543);

    }
	$return[$count]['RowID'] 		= $Result['RowID'];
	$return[$count]['FacName'] 		= $FacNamePage;
	$return[$count]['StartDate'] 	= $newdate;
  $return[$count]['EndDate2'] 		= $newdate2;    
  $return[$count]['EndDate'] 		= $Result['EndDate'];
    $return[$count]['Detail'] 		= $Result['Detail'];
	$return[$count]['LeftDay'] 		= $Result['LeftDay'];
    $boolean = true;
    $count++;
  }
  $return['Count'] = $count;
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
//  DATE_FORMAT(StartDate,'%d/%m/%Y')

  $Sql = "SELECT RowID,factory.FacCode,FacName,StartDate,EndDate,IFNULL(Detail,'') AS Detail,(EndDate-DATE(NOW())) AS LeftDay
  FROM contract_parties_factory
  INNER JOIN factory ON contract_parties_factory.FacCode = factory.FacCode
  WHERE contract_parties_factory.IsStatus = 0
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
	$return[$count]['FacCode'] 		= $Result['FacCode'];
	$return[$count]['FacName'] 		= $Result['FacName'];
	$return[$count]['StartDate'] 	= $newdate;
    $return[$count]['EndDate'] 		= $newdate2;
    $return[$count]['Detail'] 		= $Result['Detail'];
	$return[$count]['LeftDay'] 		= $Result['LeftDay'];
    $boolean = true;
    $count++;
  }

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
  $boolean = false;
  $count = 0;
  $isStatus = $DATA["isStatus"];
  $RowID = $DATA["RowID"];
  $facid = $DATA["facid"];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];
  $Detail = $DATA["Detail"];



  if($isStatus==0){
  	  $Sql = "INSERT INTO contract_parties_factory
      ( StartDate,EndDate,FacCode,Detail,IsStatus )
      VALUES
      ( '$sDate','$eDate',$facid,'$Detail',0 )";
      mysqli_query($conn,$Sql);
  }else{
	  $Sql = "UPDATE contract_parties_factory
			SET StartDate = '$sDate',
			EndDate = '$eDate',
			Detail = '$Detail'
			WHERE RowID = $RowID";
      mysqli_query($conn,$Sql);
  }
  ShowDocument($conn,$DATA);

}

function CancelRow($conn,$DATA){
  $boolean = false;
  $count = 0;
  $isStatus = $DATA["isStatus"];
  $RowID = $DATA["RowID"];

  $Sql = "UPDATE contract_parties_factory SET IsStatus = 1 WHERE RowID = $RowID";
  mysqli_query($conn,$Sql);

	 $Sql = "INSERT INTO log ( log ) VALUES ('RowID :: $RowID')";
     mysqli_query($conn,$Sql);

  ShowDocument($conn,$DATA);
  $return['status'] = "success";
  $return['form'] = "CancelRow";
  $return['msg'] = "addsuccess";
  
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
