<?php
session_start();
require '../connect/connect.php';

if (!empty($_POST['FUNC_NAME'])) {
    if ($_POST['FUNC_NAME'] == 'feedHospitalSelection') {
      feedHospitalSelection($conn);
    } else if ($_POST['FUNC_NAME'] == 'addMenu') {
      addMenu($conn);
    }else if ($_POST['FUNC_NAME'] == 'showMenu') {
      showMenu($conn);
    }
}

function showMenu($conn)
{
    $return = array();

    $hptsel = $_POST['hptsel'];
    
    $Sql = "SELECT
              numberstandard.menuCode, 
              numberstandard.siteCode, 
              numberstandard.textLeft, 
              numberstandard.textRight
            FROM
              numberstandard WHERE SiteCode = '$hptsel' ";
    $meQuery = mysqli_query($conn, $Sql);
    while ($row = mysqli_fetch_assoc($meQuery)) {

        $return[] = $row;
    }
    echo json_encode($return);
    unset($conn);
    die;
}


function addMenu($conn)
{
    $return = array();

    $number = $_POST['number'];
    $text = $_POST['text'];
    $direct = $_POST['direct'];
    $hptsel = $_POST['hptsel'];


      $sql1 = "SELECT COUNT(rowID) as cnt FROM numberstandard WHERE menuCode = $number AND siteCode = '$hptsel'  ";

      $meQuery1 = mysqli_query($conn, $sql1);
      $Result1 = mysqli_fetch_assoc($meQuery1);
      $cnt  = $Result1['cnt'];

      if($cnt > 0){
        $update1 = "UPDATE numberstandard SET $direct = '$text' WHERE menuCode = $number AND siteCode = '$hptsel'";
        mysqli_query($conn, $update1);

        $return["number"] = $number;
        $return["direct"] = $direct;

      }else{
        $update1 = "INSERT INTO numberstandard SET $direct = '$text' , menuCode = $number , siteCode = '$hptsel' ";
        mysqli_query($conn, $update1);

        $return["number"] = $number;
        $return["direct"] = $direct;

      }


    echo json_encode($return);
    unset($conn);
    die;
}


function feedHospitalSelection($conn)
{
    $HptCode1 = $_SESSION['HptCode'];
    $PmID = $_SESSION['PmID'];
    $lang = $_SESSION['lang'];
    $return = array();

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
    while ($row = mysqli_fetch_assoc($meQuery)) {

        $return[] = $row;
    }

    echo json_encode($return);
    unset($conn);
    die;
}