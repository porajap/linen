<!DOCTYPE html>
<?php
  session_start();
  $Id = $_SESSION['Userid'];
  $TimeOut = $_SESSION['TimeOut'];

  $language = $_GET['lang'];
  if($language=="en"){
    $language = "en";
  }else{
    $language = "th";
  }
  
  header ('Content-type: text/html; charset=utf-8');
  $xml = simplexml_load_file('xml/general_lang.xml');
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script  type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="dist/css/sweetalert2.min.css">
    <script src="dist/js/jquery-3.3.1.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function(e) {

        }).mousemove(function(e) { parent.afk();parent.last_move = new Date();
        }).keyup(function(e) { parent.last_move = new Date();
        });

      function timeoutUpdate() {
          var timeout = document.getElementById("timeout").value;

          parent.redirectInSecond = timeout;
          parent.target = parent.redirectInSecond * 1000; // แปลงค่าเป็น microsecond
          parent.target = parent.target * 60;

          var Id = "<?php echo $Id ?>";
          if(timeout!=0){
            var data = {
              'PAGE' : 'cTimeout',
              'timeout': timeout,
              'ID' : Id
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
          }else{
            swal({
                  type: 'warning',
                  title: 'Something Wrong',
                  text: 'Please recheck your username and password!'
                })
          }
    }

      function senddata(data)
      {
         var form_data = new FormData();
         form_data.append("DATA",data);
         var URL = 'process/change_timeout.php';
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

                         if(temp["status"]=='success'){
                           swal.hideLoading()
                               swal({
                                 title: '',
                                 text: temp["msg"],
                                 type: 'success',
                                 showCancelButton: false,
                                 confirmButtonColor: '#3085d6',
                                 cancelButtonColor: '#d33',
                                 timer: 3000,
                                 confirmButtonText: 'Ok',
                                 showConfirmButton: false
                               }).then(function () {
                                   window.location.href = 'pages/menu.php';
                                   //return loadIframe('ifrm', this.href)
                               }, function (dismiss) {
                                   window.location.href = 'pages/menu.php';
                               if (dismiss === 'cancel') {

                               }
                             })
                         }else{
                             swal.hideLoading()
                             swal({
                               title: 'Something Wrong',
                               text: temp["msg"],
                               type: 'error',
                               showCancelButton: false,
                               confirmButtonColor: '#3085d6',
                               cancelButtonColor: '#d33',
                               confirmButtonText: 'Ok'
                             }).then(function () {

                             }, function (dismiss) {
                             // dismiss can be 'cancel', 'overlay',
                             // 'close', and 'timer'
                             if (dismiss === 'cancel') {

                             }
                           })
                           //alert(temp["msg"]);
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
            src: url("fonts/DB Helvethaica X.ttf");
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
        <h1><?php echo $array['changetimeout'][$language]; ?></h1>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
          <div class="dropdown-divider"></div>
          <br>
          <div class="form-group">
            <input type="text" class="form-control" id="timeout" placeholder="set new timeout" value="<?= $TimeOut ?>">
          </div>
          <button style="height: 50px;font-size: 130%;background-color:#009900" type="submit" class="btn" onclick="timeoutUpdate();">
            <label style="color:#009900">___________</label> <label style="color:#FFFFFF"><?php echo $array['save'][$language]; ?></label> <label style="color:#009900">___________</label></button>
        </div>
        <div class="col-md-6"></div>
    </div>
  </body>
</html>
