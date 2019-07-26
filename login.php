<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$language = "en";
header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="dist/css/sweetalert2.min.css">
    <script src="dist/js/jquery-3.3.1.min.js"></script>

    <title>Login | Linen</title>
</head>
<body>
    <!-- ====================== form Login======================= -->
        <div id="form_white">
            <div class="row">
                <!-- logo -->
                <div id="logo_top">
                    <img src="img/logo.png">
                </div>
                <!-- end logo -->
                <!-- input username -->
                <div id="username_div">
                    <div id="label1">
                        <label for="username">Username</label>
                    </div>
                    <div class="input-group color1">
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class='icon_username'>
                        <img src="img/icon1.png">
                    </div>
                </div>
                <!-- endinput username -->
                <!-- input password -->
                <div id="password_div">
                    <div id="label2">
                        <label for="password">Password</label>
                    </div>
                    <div class="input-group color1">
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class='icon_password'>
                        <img src="img/icon2.png">
                    </div>
                </div>
                <!-- endinput username -->
                <div id="reset_pass">
                    <a href="javascript:void(0)" onclick="reset_pass();">Reset Password</a>
                </div>
                <div id="change_pass">
                    <a href="javascript:void(0)" onclick="change_pass();">Change Password</a>
                </div>
                <div id='btn_submit'>
                    <div class="col-md-12">
                        <a class='btn btn-block' onclick="chklogin();">LOGIN</a>
                    </div>
                </div>
            </div>
        </div>
    <!-- ==================== End form ========================== -->

    <!-- ====================== form Sendmail======================= -->
        <div id="form_sendmail" hidden="true">
            <div class="row">
                <div id="logo_top2">
                    <img src="img/logo.png">
                </div>
                <div id="title_change_1">
                    <h3>Send password to email</h3>
                </div>
                <!-- ------------------------------------------- -->
                <div id="send_email_div">
                    <div id="label_email">
                        <label for="send_email">E-mail</label>
                    </div>
                    <div class="input-group color2">
                        <input type="text" class="form-control change_input" id="email" required>
                    </div>
                </div>
                <!-- ------------------------------------------- -->
                <div class="row" id="back1_row">
                    <a class='btn btn-back' onclick="back();">Back</a>
                </div>
                <div class="row" id="send_row">
                    <a class='btn btn-save' onclick="sendmail();">Sendmail</a>
                </div>
            </div>
        </div>
    <!-- ==================== End form ========================== -->

    <!-- ====================== form change======================= -->
      <div id="form_change" hidden="true">
            <div class="row">
                <div id="logo_top3">
                    <img src="img/logo.png">
                </div>
                <div id="title_change">
                    <h3>Change Password</h3>
                </div>
                <!-- ------------------------------------------- -->
                <div id="username_div2">
                    <div id="label1">
                        <label for="username">Username</label>
                    </div>
                    <div class="input-group color2">
                        <input type="text" class="form-control change_input" id="username2" required>
                    </div>
                </div>
                <!-- ------------------------------------------- -->
                <div id="oldpassword_div">
                    <div id="label_old">
                        <label for="old_password">Old Password</label>
                    </div>
                    <div class="input-group color2">
                        <input type="password" class="form-control change_input" id="oldpassword" required>
                    </div>
                </div>
                <!-- ------------------------------------------- -->
                <div id="newpassword_div">
                    <div id="new_label">
                        <label for="new_password">New Password</label>
                    </div>
                    <div class="input-group color2">
                        <input type="password" class="form-control change_input" id="newpassword" required>
                    </div>
                </div>
                <!-- ------------------------------------------- -->
                <div id="confirm_div">
                    <div id="confirm_label">
                        <label for="confirm_password">Confirm Password</label>
                    </div>
                    <div class="input-group color2">
                        <input type="password" class="form-control change_input" id="confirmpassword" required>
                    </div>
                </div>
                <!-- ------------------------------------------- -->
                <div class="row" id="back_row">
                    <a class='btn btn-back' onclick="back();">Back</a>
                </div>
                <div class="row" id="save_row">
                    <a class='btn btn-save' onclick="passwordUpdate();">Save</a>
                </div>
            </div>
        </div>
    <!-- ==================== End form ==================== -->
    <script>
        function reset_pass(){
            var user = document.getElementById("username").value;
            // if( user != "" ){
                $('#form_white').attr('hidden', true);
                $('#form_change').attr('hidden', true);
                $('#form_sendmail').attr('hidden', false);
            // }else{
            //     swal({
            //         type: 'warning',
            //         title: 'Something Wrong',
            //         text: 'Please enter username!'
            //     })
            // }
        }

        function change_pass()
        {
            $('#form_white').attr('hidden', true);
            $('#form_change').attr('hidden', false);
            $('#form_sendmail').attr('hidden', true);
            $('#oldpassword').val('');
            
        }

        function back()
        {
            $('#form_white').attr('hidden', false);
            $('#form_change').attr('hidden', true);
            $('#form_sendmail').attr('hidden', true);
        }

        function chklogin() {
            var user = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            if (user != "" && password != "") {
                var data = {
                    'STATUS': 'checklogin',
                    'PAGE': 'login',
                    'USERNAME': user,
                    'PASSWORD': password
                };
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            } else {
            swal({
                type: 'warning',
                title: 'Something Wrong',
                text: 'Please recheck your username and password!'
            })
            }
        }

        function sendmail(){
            var email = document.getElementById("email").value;
            var user = document.getElementById("username").value;

            if( (email!=""){
                var data = {
                    'STATUS' : 'sendmail',
                    'PAGE' : 'sendmail',
                    'email': email,
                };
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }else{
                swal({
                    type: 'warning',
                    title: 'Something Wrong',
                    text: 'Please recheck your username and email!'
                })
            }
        }

        function passwordUpdate() {
            var oldpassword = document.getElementById("oldpassword").value;
            var newpassword = document.getElementById("newpassword").value;
            var confirmpassword = document.getElementById("confirmpassword").value;
            var Username = document.getElementById("username2").value;
            if(oldpassword!="" && newpassword!=""&& confirmpassword!=""){
                var data = {
                'STATUS' : 'cPassword',
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

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = 'process/login.php';
            $.ajax({
            url: URL,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (result) {
                try {
                    var temp = $.parseJSON(result);
                    console.log(result);
                } catch (e) {
                    console.log('Error#542-decode error');
                }
                if (temp["status"] == 'success') {
                    if(temp["form"] == 'chk_login'){
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
                        });
                        setTimeout(function(){ 
                            window.location.href = 'indexlogin.html';
                        }, 1000);
                    }else if(temp["form"] == 'change_password'){
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
                        });
                        setTimeout(function(){ 
                            window.location.href = 'main.php';
                        }, 1000);
                    }else if(temp["form"] == 'sendmail'){
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
                        });
                        setTimeout(function(){
                            $('#email').val("");
                            swal({
                                title: '',
                                text: 'Please check your email - we have sent a confirmation message',showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then(function () {

                            var eamil    = temp["email"];
                            var UserName = temp["UserName"];
                            var Password = temp["Password"];
                            var Subject  = 'Reset password...';
                            var FName    = temp["FName"];
                            // window.location.href = 'sendmail.php?UserName='+UserName+'&Password='+Password;
                            var data = {
                                    'email'     : eamil,
                                    'UserName'  : UserName,
                                    'Password'  : Password,
                                    'Subject'   : Subject,
                                    'FName'     : FName,
                                };
                                sendtomail(JSON.stringify(data),)

                            }, function (dismiss) {
                                // dismiss can be 'cancel', 'overlay',
                                // 'close', and 'timer'
                                if (dismiss === 'cancel') {

                                }
                            })
                            back();
                        }, 1000);

                    }
                } else if (temp["status"] == 'change_pass') {
                    $('#username2').val(temp['username']);
                    $('#oldpassword').val(temp['password']);
                    $('#form_white').attr('hidden', true);
                    $('#form_change').attr('hidden', false);
                    $('#form_sendmail').attr('hidden', true);
                } else {
                    // swal.hideLoading()
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


    function sendtomail(data) {
        var form_data = new FormData();
        form_data.append("DATA", data);
            var URL = 'sendmail.php';
            $.ajax({
                url: URL,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (result) {
                try{
                    var temp = $.parseJSON(result);
                    console.log(result);
                } catch (e) {
                    console.log('Error#542-decode error');
                }
                swal({
                         title: 'Something Wrong',
                         text: temp["msg"],
                         type: 'success',
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
</body>
</html>