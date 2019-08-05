<?php
    session_start();
    $dataArray = $_SESSION['data_send'];
    echo '<pre>';
    print_r($dataArray);
    echo '</pre>';
    $DocNo = $_GET['DocNo'];
    echo 'DocNo = '.$DocNo;
?>