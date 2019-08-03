<?php
    session_start();
    $dataArray = $_SESSION['data_send'];
    echo $dataArray['HptCode'];
?>