<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if ($Userid == "") {
  header("location:../index.html");
}
function ShowItem($conn, $DATA)
{
  $lang = $_SESSION['lang'];
  $count = 0;
  $department2 = $DATA['department2'];
  $Dep = $DATA['Dep'];

  $PmID = $_SESSION['PmID'];
  if ($PmID == 3 && $PmID == 7) {
    $xHptCode = $DATA['HptCode'] == null ? $_SESSION['HptCode'] : $DATA['HptCode'];
  } else {
    $xHptCode = $DATA['HptCode'];
  }

  if($Dep == 0){
    $whereDep = "";
  }else{
    $whereDep = "AND users.depCode = '$Dep' ";
  }

  $Keyword = str_replace(' ', '%', $DATA['Keyword']);

  $Sql = "SELECT users.ID, users.EngPerfix, users.EngName, users.EngLName, users.ThPerfix, users.ThName,users.ThLName,
      users.`Password`,users.UserName,users.email,
      permission.Permission, HptName , DepName
      FROM users
      INNER JOIN permission ON users.PmID = permission.PmID
      INNER JOIN site ON site.HptCode = users.HptCode
      LEFT JOIN department ON department.DepCode = users.DepCode
      WHERE users.IsCancel = 0 $whereDep  AND ( ( users.EngName  LIKE '%$Keyword%') OR ( users.ThName  LIKE '%$Keyword%') OR ( users.EngLName  LIKE '%$Keyword%') OR ( users.ThLName  LIKE '%$Keyword%') )";
  if ($PmID == 3 || $PmID == 7) {
    $Sql .= " AND  (Permission ='user' || Permission ='manager' || Permission ='Laundry')";
  }
  if ($PmID == 5) {
    $Sql .= " AND  (Permission ='user' || Permission ='Supervisor' )";
  }

  if ($department2 != "") {
    $Sql .= " AND  site.HptCode ='$xHptCode'  ";
  } else {
    $Sql .= "AND site.HptCode = '$xHptCode'";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ID'] = $Result['ID'];
    if ($lang == "en") {
      $return[$count]['Name'] = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
    } else if ($lang == "th") {
      $return[$count]['Name'] = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
    }
    $return[$count]['Password'] = $Result['Password'];
    $return[$count]['UserName'] = $Result['UserName'];
    $return[$count]['email'] = $Result['email'];
    $return[$count]['Permission'] = $Result['Permission'];
    $return[$count]['HptName'] = $Result['DepName'];
    $count++;
  }
  $return['Count'] = $count;
  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getSection($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $count = 0;
  if ($PmID == 3 && $PmID == 7) {
    $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM
          site
          WHERE IsStatus = 0 AND HptCode = '$HptCode1'";
  } else {
    $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM
          site
          WHERE IsStatus = 0 ";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function getdetail($conn, $DATA)
{
  $ID = $DATA['ID'];

  //    $Sqlx = "INSERT INTO log ( log ) VALUES ('ID : $ID')";
  //    mysqli_query($conn,$Sqlx);

  $Sql = "SELECT
          users.ID,
          users.UserName,
          users.`Password`,
          site.HptName,
          site.HptCode,
          permission.Permission,
          permission.PmID,
          factory.FacCode,
          users.email,
          users.pic,
          users.DepCode,
          department.DepName,
          users.EngPerfix,
          users.EngName,
          users.EngLName,
          users.ThPerfix,
          users.ThName,
          users.ThLName,
          users.remask 
        FROM
          users
          INNER JOIN permission ON users.PmID = permission.PmID
          INNER JOIN site ON users.HptCode = site.HptCode
          INNER JOIN department ON department.DepCode = department.DepCode
          LEFT JOIN factory ON factory.FacCode = users.FacCode 
        WHERE
          users.ID = $ID 
          AND users.IsCancel = 0 ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['ID'] = $Result['ID'];
    $return['UserName'] = $Result['UserName'];
    $return['Password'] = $Result['Password'];
    $return['Permission'] = $Result['Permission'];
    $return['PmID'] = $Result['PmID'];
    $return['HptName'] = $Result['HptName'];
    $return['DepName'] = $Result['DepName'];
    $return['DepCode'] = $Result['DepCode'];
    $return['HptCode'] = $Result['HptCode'];
    $return['FacCode'] = $Result['FacCode'];
    $return['email'] = $Result['email'];
    $return['pic'] = $Result['pic'] == null ? 'default_img.png' : $Result['pic'];

    $return['EngPerfix'] = $Result['EngPerfix'];
    $return['EngName'] = $Result['EngName'];
    $return['EngLName'] = $Result['EngLName'];
    $return['ThPerfix'] = $Result['ThPerfix'];
    $return['ThName'] = $Result['ThName'];
    $return['ThLName'] = $Result['ThLName'];
    $return['remask'] = $Result['remask'];
    $HptCode = $Result['HptCode'];

  }

  $count = 0;
  $Sql = "SELECT permission.PmID,permission.Permission FROM permission";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['Pm' . $count]['xPmID']  = $Result['PmID'];
    $return['Pm' . $count]['xPermission']  = $Result['Permission'];
    $count++;
  }
  $return['PmCnt'] = $count;

  $count = 0;
  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['hos' . $count]['xHptCode']  = $Result['HptCode'];
    $return['hos' . $count]['xHptName']  = $Result['HptName'];
    $count++;
  }
  $return['EmpCnt'] = $count;

  $count = 0;
  $Sql = "SELECT department.DepCode,department.DepName FROM department WHERE IsStatus = 0 AND HptCode = '$HptCode'";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['dep' . $count]['xDepCode']  = $Result['DepCode'];
    $return['dep' . $count]['xDepName']  = $Result['DepName'];
    $count++;
  }
  $return['DepCnt'] = $count;

  if ($count > 0) {
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getHotpital($conn, $DATA)
{
  $lang = $DATA["lang"];
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $count = 0;
  if ($lang == 'en') {
    if ($PmID == 5 || $PmID == 7) {
      $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    } else {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  } else {
    if ($PmID == 5 || $PmID == 7) {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    } else {
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

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function getHotpital_user($conn, $DATA)
{
  $lang = $DATA["lang"];
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $count = 0;
  if ($lang == 'en') {
    if ($PmID == 3 || $PmID == 7) {
      $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    } else {
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  } else {
    if ($PmID == 3 || $PmID == 7) {
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    } else {
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

  $return['status'] = "success";
  $return['form'] = "getHotpital_user";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function getPermission($conn, $DATA)
{
  $PmID = $_SESSION['PmID'];
  $Userid = $_SESSION['Userid'];
  $count = 0;
  $Sql = "SELECT permission.PmID,permission.Permission FROM permission ";

  if ($PmID == 3 || $PmID == 7) {
    $Sql .= " WHERE PmID = 2 || PmID = 3 || PmID = 4  || PmID = 7";
  }

  if ($PmID == 5) {
    $Sql .= " WHERE PmID = 2 || PmID = 5 ";
  }

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['PmID']  = $Result['PmID'];
    $return[$count]['Permission']  = $Result['Permission'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getPermission";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function CancelItem($conn, $DATA)
{

  $UsID = $DATA['UsID'];
  $Sql = "UPDATE users SET IsCancel = 1 WHERE ID = $UsID;";
  mysqli_query($conn, $Sql);
  $return['status'] = "success";
  $return['form'] = "CancelItem";
  $return['msg'] = "cancelsuccess";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function getFactory($conn, $DATA)
{
  $Hotp = $DATA['Hotp'];
  $count = 0;
  $Sql = "SELECT
            factory.FacCode,
            factory.FacName
          FROM factory
          WHERE factory.IsCancel = 0 AND HptCode='$Hotp'";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['FacCode'] = $Result['FacCode'];
    $return[$count]['FacName'] = $Result['FacName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getFactory";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
function getDepartment($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  if ($PmID == 3 || $PmID == 7) {
    $Hotp = $DATA["Hotp"] == null ? $_SESSION['HptCode'] : $DATA["Hotp"];
  } else {
    $Hotp = $DATA["Hotp"];
  }
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
      AND department.IsActive = 1
		  AND department.IsStatus = 0
      AND department.IsDefault = 1
      ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($meQuery = mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    $return['msg'] = "notfound";

    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getDepartment2($conn, $DATA)
{
  $count = 0;
  $boolean = false;
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $Permission = $DATA['Permission'];
  if ($PmID == 3 || $PmID == 7 || $PmID == 5 ) {
    $Hotp = $DATA["Hotp"] == null ? $_SESSION['HptCode'] : $DATA["Hotp"];
    $whereDep = "AND department.IsDefault = 1";
  } else {
    $Hotp = $DATA["Hotp"];
    if ($Permission == 8  || $Permission == 9) {
      $whereDep = "";
    } else {
      $whereDep = "AND department.IsDefault = 1";
    }
  }
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
		  AND department.IsStatus = 0
      AND department.IsActive = 1
      $whereDep 
      ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }
  if ($meQuery = mysqli_query($conn, $Sql)) {
    $return['count'] = $count;
    $return['status'] = "success";
    $return['form'] = "getDepartment2";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment2";
    $return['msg'] = "notfound";

    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
function getDep ($conn, $DATA){
  $site = $DATA['Hotp'];
  $Permission = $DATA['Permission'];
  $count = 0;
  $Sql = "SELECT DepCode , DepName FROM department WHERE HptCode =  '$site' AND IsStatus = 0 AND department.IsActive = 1 ORDER BY DepName  ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
    $boolean = true;
  }

  if ($boolean) {
    $return['count'] = $count;
    $return['status'] = "success";
    $return['form'] = "getDep";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['count'] = $count;
    $return['status'] = "success";
    $return['form'] = "getDep";
    $return['msg'] = "notfound";

    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}
if (isset($_POST['DATA'])) {
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace('\"', '"', $data), true);

  if ($DATA['STATUS'] == 'ShowItem') {
    ShowItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getHotpital') {
    getHotpital($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getPermission') {
    getPermission($conn, $DATA);
  } else if ($DATA['STATUS'] == 'AddItem') {
    AddItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'EditItem') {
    EditItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'CancelItem') {
    CancelItem($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getdetail') {
    getdetail($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getHotpital_user') {
    getHotpital_user($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getFactory') {
    getFactory($conn, $DATA);
  } else if ($DATA['STATUS'] == 'getSection') {
    getSection($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getDepartment') {
    getDepartment($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getDepartment2') {
    getDepartment2($conn, $DATA);
  } elseif ($DATA['STATUS'] == 'getDep') {
    getDep($conn, $DATA);
  }
} else {
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
