<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");

if (!empty($_POST['FUNC_NAME'])) {
  if ($_POST['FUNC_NAME'] == 'saveData') {
    saveData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showData') {
    showData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'showDetail') {
    showDetail($conn);
  } else  if ($_POST['FUNC_NAME'] == 'deleteData') {
    deleteData($conn);
  } else  if ($_POST['FUNC_NAME'] == 'checkTypelinen') {
    checkTypelinen($conn);
  }
}


function saveData($conn)
{
  $txtNumber = $_POST["txtNumber"];
  $txtName = $_POST["txtName"];
  $userid   = $_SESSION["Userid"];
  $return = array();

  if ($txtNumber == "") {
    $Sql = "INSERT INTO thread_count SET    thread_count.name_Thread = '$txtName', 
                                            thread_count.Create_Date = NOW(), 
                                            thread_count.Modify_Date = NOW(), 
                                            thread_count.Modify_Code = $userid  ";
  } else {
    $Sql = "UPDATE thread_count SET        thread_count.name_Thread = '$txtName', 
                                          thread_count.Create_Date = NOW(), 
                                          thread_count.Modify_Date = NOW(), 
                                          thread_count.Modify_Code = $userid  WHERE thread_count.id = $txtNumber ";
  }


  mysqli_query($conn, $Sql);
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showDetail($conn)
{
  $id = $_POST["id"];

  $Sql = "SELECT
            thread_count.id,
            thread_count.name_Thread
          FROM
          thread_count
          WHERE thread_count.id = '$id' ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function showData($conn)
{
  // $selectSite = $_POST["selectSite"];
  $txtSearch = $_POST["txtSearch"];

  $Sql = "SELECT
            thread_count.id,
            thread_count.name_Thread
          FROM
          thread_count
          WHERE  thread_count.name_Thread LIKE '%$txtSearch%' ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[] = $Result;
  }
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function deleteData($conn)
{
  $id = $_POST["txtNumber"];
  $return = array();

  $Sql = "DELETE FROM thread_count WHERE thread_count.id = $id ";

  mysqli_query($conn, $Sql);
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function checkTypelinen($conn)
{
  $txtName   = $_POST["txtName"];
  $txtNumber = $_POST["txtNumber"];

  if ($txtNumber == "") {
    $Sql = "SELECT
    COUNT( thread_count.id ) AS count_name
  FROM
    thread_count 
  WHERE
    name_Thread = '$txtName' ";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $count_name = $Result['count_name'];
    }
  } else {
    $count_name = 0;
  }


  if ($count_name > 0) {
    echo "repeat";
  } else {
    echo "no repeat";
  }

  mysqli_close($conn);
  die;
}
