<?php
  session_start();
  require '../connect/connect.php';
  date_default_timezone_set("Asia/Bangkok");
  $xDate = date('Y-m-d');
  $Userid = $_SESSION['Userid'];
  if($Userid==""){
    header("location:../index.html");
  }

  $Sign = $_POST['SignSVG'];
  $Table = $_POST['Table'];
  $Column = $_POST['Column'];
  $DocNo = $_POST['DocNo'];

  $Update = "UPDATE $Table SET $Column = '$Sign' WHERE DocNo = '$DocNo'";
  mysqli_query($conn, $Update);


