<?php
session_start();

$data = $_POST['DATA'];
$DATA = json_decode(str_replace ('\"','"', $data), true);

$_SESSION['Excel']['ItemCodeArray'] = $DATA['ItemCodeArray'];
$_SESSION['Excel']['QtyArray1'] = $DATA['QtyArray1'];
$_SESSION['Excel']['QtyArray2'] = $DATA['QtyArray2'];
$_SESSION['Excel']['QtyArray3'] = $DATA['QtyArray3'];
$_SESSION['Excel']['QtyArray4'] = $DATA['QtyArray4'];
$_SESSION['Excel']['changeArray'] = $DATA['changeArray'];
$_SESSION['Excel']['Total_par2'] = $DATA['Total_par2'];
$_SESSION['Excel']['PercentArray'] = $DATA['PercentArray'];

