<?php
  session_start();
  require '../connect/connect.php';
  date_default_timezone_set("Asia/Bangkok");
  $xDate = date('Y-m-d');
  $Userid = $_SESSION['Userid'];
  if($Userid==""){
    header("location:../index.html");
  }

  $Sign   = $_POST['SignSVG'];
  $Table  = $_POST['Table']; 
  $Column = $_POST['Column'];
  $DocNo  = $_POST['DocNo'];

  if($Table == "shelfcount"){
    $Update = "UPDATE $Table SET $Column = '$Sign', PTime = NOW() , chk_sign = 1 WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Update);
  }else if ($Table == "return_doc"){
    $Update = "UPDATE $Table SET $Column = '$Sign'  , signature_webTime = NOW() WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Update);
  }else{
    $Update = "UPDATE $Table SET $Column = '$Sign'  WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Update);
  }
  


