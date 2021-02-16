<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if ($Userid == "") {
  header("location:../index.html");
}
?>



<html>

<head>
  <!-- <title>Pose Inttelligence</title> -->
</head>

<body>
  <?php

  $Sql = "SELECT
            shelfcount.DocNo,
            shelfcount.SiteCode,
            site.HptName,
            shelfcount.DepCode,
            DATE(shelfcount.Modify_Date) as datesc ,
            TIME(shelfcount.Modify_Date) as timesc 

        FROM
          shelfcount
        INNER JOIN site ON shelfcount.SiteCode = site.HptCode
        WHERE
          shelfcount.checkAlert = 0 
        AND Time( shelfcount.alertTime ) < Time(NOW());";
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $row["DocNo"];
    $SiteCode = $row["SiteCode"];
    $HptName = $row["HptName"];
    $DepCode = $row["DepCode"];
    $timesc = $row["timesc"];
    $datesc = explode("-", $row['datesc']);
    $dateen = $datesc[2] . '-' . $datesc[1] . '-' . $datesc[0];
    $dateth = $datesc[2] . '-' . $datesc[1] . '-' . ($datesc[0] + 543);
    $dateen =$dateen." " .$timesc;
    $dateth =$dateth. " " .$timesc;

    // $Modify_Date = $row["Modify_Date"];

    $sqldep = "SELECT
                  department.DepName
                FROM
                department
                WHERE  department.HptCode = '$SiteCode' AND department.DepCode = '$DepCode' ";
              $meQuerydep = mysqli_query($conn, $sqldep);
              while ($rowdep = mysqli_fetch_assoc($meQuerydep)) {
                $DepName = $rowdep["DepName"];
              }
    $sqlEmail = "SELECT
                  users.email
                FROM
                  users
                WHERE  users.HptCode = '$SiteCode' AND  users.PmID = 5

                UNION
	
                SELECT
                  users.email 
                FROM
                  users 
                WHERE
                  users.PmID = 3 ";
    $meQueryEmail = mysqli_query($conn, $sqlEmail);
    while ($rowEmail = mysqli_fetch_assoc($meQueryEmail)) {
      $email = $rowEmail["email"];

      // $email = "may.leelanoi@gmail.com";

      #-------------------------------------------------------------------------------------

      $strTo = $email;
      $strSubject = "=?UTF-8?B?" . base64_encode("Notification accept document") . "?=";
      // $strHeader .= "MIME-Version: 1.0'\r\n";
      $strHeader .= "Content-type: text/html; charset=utf-8\r";
      $strHeader .= "From: poseinttelligence@gmail.com>";
      $strMessage = "
      <br>
      ___________________________________________________________________<br>
      <h3>กรุณาตอบรับ</h3>
      <b>โรงพยาบาล : </b> &nbsp; $HptName<br>
      <b>แผนก : </b> $DepName<br>
      <b>เลขที่เอกสาร : </b> $DocNo<br>
      <b>เวลาที่ บันทึก : </b> $dateth<br>
      ___________________________________________________________________<br>
      <h3>Please reply</h3>
      <b>Hospital : </b> &nbsp; $HptName<br>
      <b>Department : </b> $DepName<br>
      <b>DocumentNo : </b> $DocNo<br>
      <b>Date Time : </b> $dateen<br>
      ___________________________________________________________________<br>";

      $flgSend = @mail($strTo, $strSubject, $strMessage, $strHeader);  // @ = No Show Error //
      if ($flgSend) {
        echo "Email Sending.";
      } else {
        echo "Email Can Not Send.";
      }
    }
    $sqlUpdate = "UPDATE shelfcount  SET shelfcount.checkAlert = 1 WHERE DocNo = '$DocNo' ";
    mysqli_query($conn, $sqlUpdate);
  }

  ?>
</body>

</html>