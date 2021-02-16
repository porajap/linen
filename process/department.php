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
  $group = $DATA['group'];
  $Keyword = $DATA['Keyword'];
  $column = $DATA['column']==null?'DepCode':$DATA['column'];
  $sort = $DATA['sort']==null?'DESC':$DATA['sort'];

  $Sql = "SELECT site.HptCode,
          CASE site.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus,
          department.DepCode,TRIM(department.DepName) AS DepName,department.IsDefault,
          department.IsActive,
		  CASE department.IsDefault WHEN 0 THEN '0' WHEN 1 THEN '1' END AS DefaultName
          FROM site
          INNER JOIN department ON site.HptCode = department.HptCode ";

        if ($Keyword == '' && $xHptCode != '')
        {
          if ( $xHptCode != '' && $group =='')
          {
            $Sql .= " WHERE  department.HptCode = '$xHptCode' AND department.IsStatus = 0 AND  department.DepName LIKE '%$Keyword%'"; 
          }
          else if($xHptCode != '' && $group !='')
          {
            $Sql .= " WHERE  department.HptCode = '$xHptCode' AND GroupCode = $group AND department.IsStatus = 0 AND  department.DepName LIKE '%$Keyword%'"; 
          }
        }
        else
        {
            $Sql .= " WHERE   site.HptCode = '$xHptCode' AND ( department.DepCode LIKE '%$Keyword%' OR department.DepName LIKE '%$Keyword%') "; 
        }
          $Sql .= "   ORDER BY department.$column $sort  ";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return[$count]['IsActive'] = $Result['IsActive'];
    $return[$count]['HptCode'] = $Result['HptCode'];
    $cnt                       = $Result['DepCode'];
    $hpt                       = $Result['HptCode'];
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
	  $return[$count]['IsDefault'] = $Result['IsDefault'];
    $return[$count]['DefaultName'] = $Result['DefaultName'];
    // 
    $countDep="SELECT COUNT(DepCode) AS cnt FROM par_item_stock WHERE DepCode = '$cnt' AND HptCode = '$hpt' ";
    $meQueryDep = mysqli_query($conn, $countDep);
    $Result = mysqli_fetch_assoc($meQueryDep);
    $return[$count]['cnt'] = $Result['cnt'];
    // 
    $count++;
  }

  $return['Count'] = $count;
  if($column == 'RowID' && $sort=='ASC'){
    $return['down'] = 1;
  }
  if($count>0)
  {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  else
  {
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
  $count = 0;
  $countgroup =0;
  $HptCode = $DATA['HptCode'];
  $DepCode = $DATA['DepCode'];
  $number = $DATA['number'];
  //---------------HERE------------------//
  $Sql = "SELECT
          department.DepCode,
          department.HptCode,
          TRIM(department.DepName) AS DepName,
          department.IsStatus,
          department.IsDefault,
          department.IsActive ,
          department.GroupCode,
          department.Ship_To
          FROM department
          WHERE department.IsStatus = 0
          AND department.DepCode = '$DepCode' AND department.HptCode = '$HptCode' LIMIT 1";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return['DepCode'] 		    = $number;
    $return['DepCodeReal'] 		= $Result['DepCode'];
    $return['IsActive'] 		  = $Result['IsActive'];
    $return['HptCode'] 		    = $Result['HptCode'];
    $return['GroupCode'] 		  = $Result['GroupCode']==null?'':$Result['GroupCode'];
    $return['DepName'] 		    = $Result['DepName'];
    $return['IsStatus'] 	    = $Result['IsStatus'];
    $return['IsDefault'] 	    = $Result['IsDefault'];
    $return['shipto'] 	      = $Result['Ship_To'];
    $HptCode		              = $Result['HptCode'];
    $count++;
  }

  $Sql = "SELECT grouphpt.GroupCode,grouphpt.GroupName
  FROM grouphpt WHERE grouphpt.IsStatus = 0 AND grouphpt.HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return[$countgroup]['GroupCode']  = $Result['GroupCode'];
    $return[$countgroup]['GroupName']  = $Result['GroupName'];
    $countgroup++;
  }

  $return['row'] = $countgroup ;
  
  if($count>0)
  {
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  else
  {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function GetGroup($conn , $DATA)
{
  $count = 0;
  $PmID = $_SESSION['PmID'];
  if($PmID == 5 || $PmID == 7)
  {
    $HptCode = $_SESSION["HptCode"];
  }
  else
  {
    $HptCode = $DATA["HptCode"];
  }

  $Sql = "SELECT grouphpt.GroupCode,grouphpt.GroupName
  FROM grouphpt WHERE grouphpt.IsStatus = 0 AND grouphpt.HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return[$count]['GroupCode']  = $Result['GroupCode'];
    $return[$count]['GroupName']  = $Result['GroupName'];
    $count++;
  }

  $return['row'] = $count;
  $return['status'] = "success";
  $return['form'] = "GetGroup";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function getSection($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $DATA["lang"];
  $count = 0;
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
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
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
  $count = 0;
  $chkinsert = 0;
  $shipto = $DATA['shipto'];
  $HptCode = $DATA['HptCode'];
  $DepCode1 = trim($DATA['DepCode1']);
  $DepCode = trim($DATA['DepCode']);
  $DepName = trim($DATA['DepName']);
  $group2 = trim($DATA['group2']);
  $xCenter = $DATA['xCenter'];
  $Userid = $_SESSION['Userid'];
  $PmID = $_SESSION['PmID'];

  if($DepCode1 == "")
  {
        $Sql =  "SELECT
                  COUNT(*) AS Cnt,
                  DepCode 
                FROM
                  department 
                WHERE
                  department.DepCode = '$DepCode' 
                  AND department.IsStatus = 0 ";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery))
        {
          $count = $Result['Cnt'];
          $DepCodechk = $Result['DepCode'];
        }
        if($count > 0)
        {
          $return['DepCode'] = $DepCodechk;
          $return['group2'] = $group2;
          $return['DepName'] = $DepName;
          $return['IsActive'] = $DATA['IsActive'];
          $return['HptCode'] = $HptCode;
          $return['shipto'] = $shipto;
          $return['status'] = "success";
          $return['form'] = "confirmdep";
          echo json_encode($return);
          mysqli_close($conn);
          die;
        }
    
    // =================================
    if($PmID ==1 || $PmID ==6)
    {
      $IsActive = $DATA['IsActive'];
    }
    else
    {
      $IsActive = 0 ;
    }

      $Sql = "INSERT INTO department
              (
              DepCode,
              HptCode,
              DepName,
              IsStatus,
              IsDefault, 
              DocDate ,
              Modify_Code ,
              Modify_Date,
              IsActive,
              GroupCode,
              Ship_To
              )
              VALUES
              (
                '$DepCode',
                '$HptCode',
                '$DepName',
                0,
                $xCenter,
                NOW(),
                $Userid,
                NOW(),
                $IsActive,
                '$group2',
                '$shipto'
              )
      ";
      mysqli_query($conn, $Sql);
      $chkinsert = 1;
    if($chkinsert ==1)
    {
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "addsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    else
    {
      $return['status'] = "failed";
      $return['msg'] = "addfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }
  else
  {
      $Sql =  "SELECT COUNT(*) as Cnt, DepCode FROM department
      WHERE department.IsStatus = 0   AND department.IsDefault = 1 AND HptCode = '$HptCode'   " ;
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery))
      {
        $countx = $Result[ 'Cnt'];
        $xDepCode = $Result[ 'DepCode'];
      }

      if($xCenter == 1 && $countx > 0 && $DepCode != $xDepCode)
      {
        $return['status'] = "failed";
        $return['msg'] = "editcenterfailedmsg";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }

      if($PmID ==1 || $PmID ==6)
      {
        $IsActive = $DATA['IsActive'];
      }
      else
      {
        $IsActive = 0 ;
      }

      $Sql = "UPDATE department SET
      DepCode =  '$DepCode',
      HptCode =  '$HptCode',
      DepName = '$DepName',
      IsDefault =  $xCenter ,
      Modify_Date = NOW() ,
      Modify_Code =  $Userid ,
      IsActive = $IsActive,
      GroupCode = '$group2',
      Ship_To = '$shipto'
      WHERE DepCode = '".$DATA['DepCode1']."' AND HptCode = '$HptCode' ";

      if(mysqli_query($conn, $Sql))
      {
        $return['status'] = "success";
        $return['form'] = "EditItem";
        $return['msg'] = "editsuccess";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
      else
      {
        $return['status'] = "failed";
        $return['msg'] = "editfailed :  $xCenter";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
  }
}
function AddItemConfirm($conn, $DATA)
{

    $count = 0;
    $chkinsert = 0;
    $shipto = $DATA['shipto'];
    $HptCode = $DATA['HptCode'];
    $DepCode = trim($DATA['DepCode']);
    $DepName = trim($DATA['DepName']);
    $group2 = trim($DATA['group2']);
    $Userid = $_SESSION['Userid'];
    $PmID = $_SESSION['PmID'];
    // =================================
    if($PmID ==1 || $PmID ==6)
    {
      $IsActive = $DATA['IsActive'];
    }
    else
    {
      $IsActive = 0 ;
    }

      $Sql = "INSERT INTO department
              (
              DepCode,
              HptCode,
              DepName,
              IsStatus,
              DocDate ,
              Modify_Code ,
              Modify_Date,
              IsActive,
              GroupCode,
              Ship_To
              )
              VALUES
              (
                '$DepCode',
                '$HptCode',
                '$DepName',
                0,
                NOW(),
                $Userid,
                NOW(),
                $IsActive,
                '$group2',
                '$shipto'
              )
      ";
      mysqli_query($conn, $Sql);
      $chkinsert = 1;
    if($chkinsert ==1)
    {
      $return['status'] = "success";
      $return['form'] = "AddItem";
      $return['msg'] = "addsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    else
    {
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

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["DepCode"]!=""){
    $Sql = "UPDATE department SET
            IsStatus = 1
            WHERE DepCode = '".$DATA['DepCode']."' AND HptCode = '".$DATA['HptCode']."' ";
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
      }else if ($DATA['STATUS'] == 'GetGroup') {
        GetGroup($conn,$DATA);
      }else if ($DATA['STATUS'] == 'AddItemConfirm') {
        AddItemConfirm($conn,$DATA);
      }
      
}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
