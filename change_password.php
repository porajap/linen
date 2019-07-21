<?php
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

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script  type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="dist/css/sweetalert2.min.css">
    <script src="dist/js/jquery-3.3.1.min.js"></script>

    <script type="text/javascript">

      function Back() {
          window.location.href = 'index.html';
      }

      function passwordUpdate() {
          var oldpassword = document.getElementById("oldpassword").value;
          var newpassword = document.getElementById("newpassword").value;
          var confirmpassword = document.getElementById("confirmpassword").value;
          var Username = document.getElementById("username").value;
          if(oldpassword!="" && newpassword!=""&& confirmpassword!=""){
            var data = {
              'PAGE' : 'cPassword',
              'oldpassword': oldpassword,
              'newpassword' : newpassword,
              'confirmpassword' : confirmpassword,
              'Username' : Username,
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
         var URL = 'process/change_password.php';
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
                         if(temp["status"]=='success')
                         {
                           swal.hideLoading()
                           swal({
                             title: '',
                             text: temp["msg"],
                             type: 'success',
                             showCancelButton: false,
                             confirmButtonColor: '#3085d6',
                             cancelButtonColor: '#d33',
                             timer: 1000,
                             confirmButtonText: 'Ok',
                             showConfirmButton: false
                           }).then(function () {
                               window.location.href = 'main.php';
                           }, function (dismiss) {
                             window.location.href = 'main.php';
                           if (dismiss === 'cancel') {

                           }
                         })

                         }
                         else {
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
      body{
        background-color: #4F63DF;
      }
      input[type="text"],input[type="password"]{
        text-align: center;
      }
    </style>
    <title>Login</title>
  </head>
  <body>
    <div align="center" style="margin-top:170px;">
        <div class="col">
        <h2>Pose Intelligence</h2>
        <br>
        <h4>Change Passowrd</h4>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
          <div class="dropdown-divider"></div>
          <br>
          <div class="form-group">
            <input type="text" class="form-control" id="username" placeholder="Username" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="oldpassword" placeholder="Old Password" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="newpassword" placeholder="New Password" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm Password" required>
          </div>
          <div class="form-group">
              <button type="submit" class="btn btn-warning " onclick="Back();"><?php echo $array['back'][$language]; ?></button>
              <label style="color:#4F63DF">----------------------------------------</label>
              <button type="submit" class="btn btn-info " onclick="passwordUpdate();"><?php echo $array['save'][$language]; ?></button>
          </div>

        </div>
        <div class="col-md-6"></div>
    </div>
  </body>
</html>
