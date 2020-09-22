<?php

  require '../connect/connect.php';
  $Sql = "UPDATE users SET Lastmouse_Date = NOW() WHERE ID = '$Userid' ";
  mysqli_query($conn, $Sql);

?>