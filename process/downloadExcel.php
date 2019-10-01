<?php 
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$filename = $_GET['filename'];
header("Pragma: public");
header("Expires: 0"); // set expiration time
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");


header("Content-Disposition: attachment; filename=".basename($filename).";");

header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filename));

readfile("$filename");
exit();