<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn,$DATA)
{
  $count = 0;
  $boolean = false;
  $lang = $_SESSION['lang'];
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
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['HptCode'] = $Result['HptCode'];
    $count++;
	  $boolean = true;
  }
    $return['count'] = $count;
    if($boolean)
    {
      $return[0]['PmID']  = $PmID;
      $return['status'] = "success";
      $return['form'] = "OnLoadPage";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    else
    {
      $return[0]['PmID']  = $PmID;
      $return['status'] = "failed";
      $return['form'] = "OnLoadPage";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
}

function ShowDocument($conn,$DATA)
{
  $boolean = false;
  $count = 0;
  $HptCode = $DATA["HptCode"];
  $DocNo = str_replace(' ', '%', $DATA["xdocno"]);
  $lang = $_SESSION['lang'];
  $HptCode_permission = $_SESSION['HptCode'];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];

  $sl1 = strlen($sDate);
  $sl2 = strlen($eDate);

  $Sql = "SELECT
            RowID,
            FacName,
            HptNameTH,
            FacNameTH,
            StartDate,
            EndDate,
            IFNULL(Detail, '') AS Detail,
            (EndDate - DATE(NOW())) AS LeftDay,
            DATEDIFF(EndDate, DATE(NOW())) AS dateDiff
          FROM
            contract_parties_factory
          INNER JOIN factory ON contract_parties_factory.FacCode = factory.FacCode
          INNER JOIN site ON site.HptCode = factory.HptCode
          WHERE
            contract_parties_factory.IsStatus = 0 AND factory.IsCancel = 0  AND factory.HptCode = '$HptCode' ";
  if(($sl1 > 9) && ($sl2 > 9)) $Sql .= "AND StartDate BETWEEN '$sDate' AND  '$eDate' ";
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
  $return[$count]['HptNameTH'] 		= $Result['HptNameTH'];
    $return[$count]['Detail'] 		= $Result['Detail'];
	$return[$count]['LeftDay'] 		= $Result['dateDiff'];
    $boolean = true;
    $count++;
  }
  $return['Count'] = $count;
  if($boolean){
    $return['sql'] = $Sql;
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
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getRow($conn,$DATA)
{
  $boolean = false;
  $count = 0;
  $RowID = $DATA["RowID"];
  $lang = $_SESSION['lang'];



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

function ShowFactory($conn,$DATA)
{
  $count = 0;
  $lang = $_SESSION['lang'];
  $HptCode = $DATA['HptCode'];

  if($lang == 'en')
  {
    $Sql = "SELECT  factory.FacCode,factory.FacName
    FROM factory WHERE factory.IsCancel = 0 AND HptCode = '$HptCode'  ";
  }
  else
  {
    $Sql = "SELECT  factory.FacCode,factory.FacNameTH AS FacName
    FROM factory WHERE factory.IsCancel = 0 AND HptCode = '$HptCode'  ";
  }
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return[$count]['FacCode'] = $Result['FacCode'];
    $return[$count]['FacName'] = $Result['FacName'];
    $count++;
	  $boolean = true;
  }
    $return['count'] = $count;
    if($boolean)
    {
      $return['status'] = "success";
      $return['form'] = "ShowFactory";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    else
    {
      $return['status'] = "success";
      $return['form'] = "ShowFactory";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
}

function SaveRow($conn,$DATA)
{
  $boolean = false;
  $count = 0;
  $isStatus = $DATA["isStatus"];
  $RowID = $DATA["RowID"];
  $facid = $DATA["facid"];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];
  $Detail = $DATA["Detail"];
  $HptCode = $DATA["HptCode"];



  if($isStatus==0)
  {
  	  $Sql = "INSERT INTO contract_parties_factory
      ( StartDate,EndDate,FacCode,Detail,IsStatus )
      VALUES
      ( '$sDate','$eDate',$facid,'$Detail',0 )";
      mysqli_query($conn,$Sql);
  }
  else
  {
	   $Sql = "UPDATE contract_parties_factory
			SET StartDate = '$sDate',
			EndDate = '$eDate',
			Detail = '$Detail',
      day_30 = 0,
      day_7 = 0
			WHERE RowID = $RowID";
      mysqli_query($conn,$Sql);
  }
  ShowDocument($conn,$DATA);

}

function CancelRow($conn,$DATA)
{
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
	    }elseif($DATA['STATUS']=='ShowDocument'){
        ShowDocument($conn,$DATA);
      }elseif($DATA['STATUS']=='getRow'){
        getRow($conn,$DATA);
      }elseif($DATA['STATUS']=='SaveRow'){
        SaveRow($conn,$DATA);
      }elseif($DATA['STATUS']=='CancelRow'){
        CancelRow($conn,$DATA);
      }elseif($DATA['STATUS']=='ShowFactory'){
        ShowFactory($conn,$DATA);
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
