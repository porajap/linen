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

        $return['status'] = "success";
        $return['page'] = "language";
        $return['msg'] = "Chang language success...";
    }

    function cTimeout($conn,$DATA)
    {
        if (isset($DATA)) {
            $Cnt = 0;
            $ID = $DATA['ID'];
            $Timeout = $DATA['timeout'];
            $boolean = false;

            $Sql = "UPDATE users SET TimeOut = $Timeout WHERE ID=$ID";
            $Chk = mysqli_query($conn,$Sql);
            if($Chk){
                $_SESSION['TimeOut'] = $Timeout;
                $boolean = true;
            }

            if($boolean){
                $return['status'] = "success";
                $return['page'] = "timeout";
                $return['Count'] = $Count;
                $return['msg'] = "Chang timeout success...";
                echo json_encode($return);
                mysqli_close($conn);
                die;
            }else{
                $return['status'] = "failed";

                $return['msg'] = "Not found chang password !";
                echo json_encode($return);
                mysqli_close($conn);
                die;
            }
        }
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
        }elseif($DATA['STATUS']=='cTimeout'){
          cTimeout($conn,$DATA);
        }
  
  }else{
      $return['status'] = "error";
      $return['msg'] = 'ไม่มีข้อมูลนำเข้า';
      echo json_encode($return);
      mysqli_close($conn);
    die;
  }
?>