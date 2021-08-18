<?php 
  session_start();
  require '../connect/connect.php';
  date_default_timezone_set("Asia/Bangkok");
  require '../phpmailer/PHPMailerAutoload.php';
  $Userid = $_SESSION['Userid'];
  if($Userid==""){
    header("location:../index.html");
  }
?>

<?php
  header('Content-Type: text/html; charset=utf-8');

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
          AND Time( shelfcount.alertTime ) < Time(NOW())";

          // echo $Sql;
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

    $sqldep = "SELECT
                  department.DepName 
                FROM
                  department 
                WHERE
                  department.HptCode = '$SiteCode' 
                  AND department.DepCode = '$DepCode' ";
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

      // $email = $rowEmail["email"];
      $email = "sarayuth.mhee@gmail.com";


      $mail = new PHPMailer;
      $mail->CharSet = "utf-8";
      $mail->isSMTP();
      // $mail->Host = 'smtp.gmail.com';
      $mail->Host = 'smtp.live.com';
      $mail->Port = 587;
      $mail->SMTPSecure = 'tls';
      $mail->SMTPAuth = true;

      $gmail_username = "mheezaza007@hotmail.com"; // gmail ที่ใช้ส่ง
      $gmail_password = "Mheezaza05"; // รหัสผ่าน gmail

      $sender = "POSE INTELLGENCE"; // ชื่อผู้ส่ง
      $email_sender = "mheezaza007@hotmail.com"; // เมล์ผู้ส่ง 
      $email_receiver = $email; // เมล์ผู้รับ ***

      $subject = "แจ้งเตือน shelfcount"; // หัวข้อเมล์


      $mail->Username = $gmail_username;
      $mail->Password = $gmail_password;
      $mail->setFrom($email_sender, $sender);
      $mail->addAddress($email_receiver);

      $mail->Subject = $subject;

      $email_content = " <!DOCTYPE html>
                          <html>
                            <head>
                              <meta charset=utf-8'/>
                              <title></title>
                            </head>
                            <body>
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
                              ___________________________________________________________________<br> 
                              </body>
                            </html>";

        if($email_receiver){
          $mail->msgHTML($email_content);
          if (!$mail->send()) {  // สั่งให้ส่ง email
            // กรณีส่ง email ไม่สำเร็จ
            echo "<h3 class='text-center'>ระบบมีปัญหา กรุณาลองใหม่อีกครั้ง</h3>";
            echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
          }else{
            // กรณีส่ง email สำเร็จ
            echo "Email Sending.";
          }	
        }

    }

  }











?>