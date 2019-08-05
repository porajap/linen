<?php
    session_start();
    $dataArray = $_SESSION['data_send'];
    $DocNo = $_GET['DocNo'];
    echo $DocNo;
?>