<!DOCTYPE html>
<?php
  session_start();
  $Id = $_SESSION['Userid'];
  $TimeOut = $_SESSION['TimeOut'];



$language = $_SESSION['lang'];

require 'updatemouse.php';

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script  type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
    <link href="../fontawesome/css/fontawesome.min.css" rel="stylesheet"> <!--load all styles -->
    <script src="../dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/css/sweetalert2.min.css">
    <script src="../dist/js/jquery-3.3.1.min.js"></script>
    <script src="../fontawesome/js/all.js"></script>

    <script type="text/javascript">

        $(document).ready(function(e) {

        }).mousemove(function(e) { parent.afk();parent.last_move = new Date();
        }).keyup(function(e) { parent.last_move = new Date();
        });

    function switchlang(lang) {
        swal({
        title: '<?php echo $array['changelang'][$language]; ?>',
        // text: "You won't be able to revert this!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<?php echo $array['confirm'][$language]; ?>',
        cancelButtonText: '<?php echo $array['cancel'][$language]; ?>'
        }).then((result) => {
            var data = {
                'STATUS' : 'SETLANG',
                'lang' : lang,
                'UserID' : <?php echo $Id ?>
            }
            senddata(JSON.stringify(data));
            swal({
            title: "<?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 1000,
                confirmButtonText: 'Ok',
                showConfirmButton: false
            });
            setTimeout(function () {
                parent.location.reload();
            }, 1000);
        })

    }
    function senddata(data)
    {
        var form_data = new FormData();
        form_data.append("DATA",data);
        var URL = '../process/change_lang.php';
        $.ajax({
                    url: URL,
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    beforeSend: function() {
                        swal({
                            title: 'Please wait..',
                            text: 'Processing',
                            allowOutsideClick: false
                        })
                        swal.showLoading()
                    },
                    success: function (result) {
                        try {
                        var temp = $.parseJSON(result);
                        console.log(result);
                        } catch (e) {
                            console.log('Error#542-decode error');
                        }
                    },
                    failure: function (result) {
                        alert(result);
                    },
                    error: function (xhr, status, p3, p4) {
                        var err = "Error " + " " + status + " " + p3 + " " + p4;
                        if (xhr.responseText && xhr.responseText[0] == "{")
                                err = JSON.parse(xhr.responseText).Message;
                        console.log(err);
                    }
        });
    }
    </script>
    <style>
          @font-face {
            font-family: myFirstFont;
            src: url("../fonts/DB Helvethaica X.ttf");
            }
          body{
            font-family: myFirstFont;
                  font-size:22px;
          }

        .nfont{
          font-family: myFirstFont;
          font-size:22px;
        }
      input[type="text"]{
        text-align: center;
        font-size: 190%;
        height: 70px;
      }
    </style>
    <title>Login</title>
  </head>
  <body>
    <div align="center" style="margin-top:170px;">
        <div class="col">
            <h1><?php echo $array['changelang'][$language]; ?></h1>
        </div>
        <div class="row mt-5">
            <div class="offset-3 col-sm-3">
                <button class="btn btn-block btn-info btn-lg"  <?php if($language=='th'){echo 'disabled';}?> onclick="switchlang('th');"><span class="btn-label mr-5" <?php if($language=='en'){echo 'hidden';}?>><i class="fa fa-check"></i></span><?php echo $array['thai'][$language]; ?></button>
            </div>
            <div class="col-sm-3">
                <button class='btn btn-block btn-warning btn-lg'  <?php if($language=='en'){echo 'disabled';}?> onclick="switchlang('en');"><span class="btn-label mr-5"><i class="fa fa-check" <?php if($language=='th'){echo 'hidden';}?>></i></span><?php echo $array['eng'][$language]; ?></button>
            </div>
        </div>
    </div>
  </body>
</html>
