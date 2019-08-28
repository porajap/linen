<?php
session_start();
$_SESSION['href']=$_POST['href'];

return $_SESSION['href'];

?>