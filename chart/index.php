<?php
session_start();
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
?>