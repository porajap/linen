<?php
session_start();
$_SESSION['href']=$_POST['href'];
$_SESSION['id_h']=$_POST['id'];
$_SESSION['active_li']=$_POST['active_li'];
return $_SESSION['href'];

?>