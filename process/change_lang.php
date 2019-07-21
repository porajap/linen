<?php
    session_start();
    require '../connect/connect.php';
    date_default_timezone_set("Asia/Bangkok");

    
    function SETLANG($conn, $DATA){
        $lang = $DATA['lang'];
        $UserID = $DATA['UserID'];
        $Sql = "UPDATE users SET lang = '$lang' WHERE ID = $UserID";
        $meQuery = mysqli_query($conn,$Sql);
        $_SESSION['lang'] = $lang;
    }
  //==========================================================
  //
  //==========================================================
  if(isset($_POST['DATA']))
  {
    $data = $_POST['DATA'];
    $DATA = json_decode(str_replace ('\"','"', $data), true);
        
        if($DATA['STATUS']=='SETLANG'){
          SETLANG($conn,$DATA);
        }
  
  }else{
      $return['status'] = "error";
      $return['msg'] = 'ไม่มีข้อมูลนำเข้า';
      echo json_encode($return);
      mysqli_close($conn);
    die;
  }
?>