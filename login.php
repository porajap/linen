<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
// $language = "en";
header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$xml2 = simplexml_load_file('xml/general_lang.xml');
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
session_destroy();
$language = $_SESSION['lang'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="css/style_login.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-material-design.css">
    <link rel="stylesheet" href="css/docs.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <script src="js/jquery-1.4.2.min.js"></script>
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="dist/css/sweetalert2.min.css">
    <style>
        #password,
        #email:read-only {
            background-color: transparent !important;
        }

        #password,
        #email:-moz-read-only {
            /* For Firefox */
            background-color: transparent !important;
        }

        .glyphicon-remove {
            color: red;
        }

        .glyphicon-ok {
            color: green;
        }
    </style>
    <title>Login | Linen</title>
</head>

<body>
    <input type="hidden" id="chk" value="1">
    <!-- --------------------------------------------------------------------------------------------- -->
    <div id="login_form">
        <div id="fram_white">
            <img src="img/frame1.png">
        </div>
        <div id="logo">
            <img src="img/logo2.png">
        </div>
        <div id="form_input">
            <div id="username_input">
                <div id="icon_user">
                    <i class="fas fa-user" id="click_auto"></i>
                </div>
                <div class="form-group bmd-form-group">
                    <label for="username" id="label_username" class="bmd-label-floating">Username</label>
                    <input type="text" autocomplete="off" class="form-control" onkeyup="make_char()" id="username">
                </div>
            </div>
            <!-- ---------------------- ------------------------------------------------------------ -->
            <div id="password_input">
                <div id="icon_password">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="form-group bmd-form-group">
                    <label for="password" id="label_password" class="bmd-label-floating">Password</label>
                    <input type="password" autocomplete="off" class="form-control" onkeyup="make_char()" id="password" onfocus="this.removeAttribute('readonly');" readonly>
                </div>
            </div>
            <!-- ----------------------------------------------------------------------------------- -->
            <div id="mange_password">
                <div id="reset">
                    <a href="javascript:void(0)" onclick="reset_pass();">Reset password</a>
                </div>
                <div id="change">
                    <a href="javascript:void(0)" onclick="change_pass();">Change password</a>
                </div>
                <div id="setActive">
                    <a href="javascript:void(0)" onclick="setActive();" title="Reset login"><i class="fas fa-key"></i></i></a>
                </div>
            </div>

            <!-- ----------------------------------------------------------------------------------- -->
            <div id="btn_login">
                <button class="btn btn_custom" onclick="chklogin();">
                    LOGIN
                    <i class="fas fa-arrow-right" id="arrow"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- --------------------------------------------------------------------------------------------- -->
    <div id="reset_form" hidden>
        <div id="reset_switch">
        </div>
    </div>
    <!-- --------------------------------------------------------------------------------------------- -->
    <div id="change_form" hidden>
        <div id="change_switch">
            <div id="fram_white">
                <img src="img/frame1.png">
            </div>
            <div id="logo_change">
                <img src="img/logo.png" style="width: 16%;">
            </div>
            <div id="form_inputChange">
                <div id="usernameCh_input">
                    <div class="form-group bmd-form-group">
                        <label for="username2" id="label_usernameCh" class="bmd-label-floating">Username</label>
                        <input type="text" autocomplete="off" class="form-control" onkeyup="make_char()" id="username2">
                    </div>
                </div>
                <div id="oldCh">
                    <div class="form-group bmd-form-group">
                        <label for="oldpassword" id="label_old" class="bmd-label-floating">Old Password</label>
                        <input type="text" autocomplete="off" class="form-control" onkeyup="make_char()" id="oldpassword">
                    </div>
                </div>
                <form id="test">
                    <div id="newCh">
                        <div class="form-group bmd-form-group">
                            <label for="newpassword" id="label_new" class="bmd-label-floating">New Password</label>
                            <input type="password" autocomplete="off" class="form-control" onkeyup="make_char()" id="newpassword" name="newpassword">
                            <div id="see1">
                                <a href="javascript:void(0)" onclick="ShowPassword1()" id="ShowPassword1"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0)" onclick="HidePassword1()" id="HidePassword1" hidden><i class="fas fa-eye-slash"></i></a>
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#policy"><i class="fas fa-globe"></i></a>
                            </div>
                            <small for="newpassword" class="text-danger m-l-6 m-b-6"></small>
                        </div>
                    </div>
                    <div id="confirmCh">
                        <div class="form-group bmd-form-group">
                            <label for="confirmpassword" id="label_confirm" class="bmd-label-floating">Confirm Password</label>
                            <input type="password" autocomplete="off" class="form-control" onkeyup="make_char()" id="confirmpassword" name="confirmpassword">
                            <div id="see2">
                                <a href="javascript:void(0)" onclick="ShowPassword2()" id="ShowPassword2"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0)" onclick="HidePassword2()" id="HidePassword2" hidden><i class="fas fa-eye-slash"></i></a>
                            </div>
                            <small for="confirmpassword" class="text-danger m-l-6 m-b-6"></small>
                        </div>
                    </div>
                </form>
                <!-- ----------------------------------------------------------------------------------- -->
                <!-- ----------------------------------------------------------------------------------- -->
                <div id="btn_change">
                    <button class="btn btn_black" id="black_reset" onclick="back();">
                        Back <i class="fas fa-undo-alt" id="black_save"></i>
                    </button>
                    <button class="btn btn_save" onclick="passwordUpdate();" id="btn_savePass" disabled="true">
                        Save <i class="fas fa-arrow-right" id="arrow_save"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- --------------------------------------------------------------------------------------------- -->

    <div class="modal fade" id="policy" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> <b> Password policy </b> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>- password จะต้องมีความยาว 8digit ขึ้นไป</p>
                    <p>- password จะต้องมีตัวเลข , ตัวอักษรตัวเล็ก , ตัวอักษรตัวใหญ่ , &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; อักขระพิเศษ อย่างน้อยอย่างละ 1 digit </p>
                    <small>( เช่น Sap1234. )</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="dist/js/jquery-3.3.1.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
    <script src="js/bootstrap-material-design.js"></script>
    <script src="js/application.js"></script>
    <script src="validate/jquery.validate.min.js"></script>
    <script src="validate/additional-methods.js"></script>
    <script>
        $(document).ready(function(e) {
            $('#username').focus();
        });

        function ShowPassword1() {
            var x = document.getElementById("newpassword");
            x.type = "text";
            $('#ShowPassword1').attr('hidden', true);
            $('#HidePassword1').attr('hidden', false);

        }

        function HidePassword1() {
            var x = document.getElementById("newpassword");
            x.type = "password";
            $('#ShowPassword1').attr('hidden', false);
            $('#HidePassword1').attr('hidden', true);

        }

        function ShowPassword2() {
            var x = document.getElementById("confirmpassword");
            x.type = "text";
            $('#ShowPassword2').attr('hidden', true);
            $('#HidePassword2').attr('hidden', false);

        }

        function HidePassword2() {
            var x = document.getElementById("confirmpassword");
            x.type = "password";
            $('#ShowPassword2').attr('hidden', false);
            $('#HidePassword2').attr('hidden', true);

        }

        function typePass() {
            $('#oldpassword').attr('type', 'password');
        }
        $(document).keyup(function(e) {

            if (e.keyCode === 13) {
                var chk = $('#chk').val();
                if (chk == 1) {
                    chklogin();
                } else if (chk == 2) {
                    sendmail();
                } else if (chk == 3) {
                    passwordUpdate();
                }
            }
        });

        function reset_pass() {
            $('#chk').val(2);
            var user = document.getElementById("username").value;
            if (user != "") {
                $('#login_form').attr('hidden', true);
                $('#change_form').attr('hidden', true);
                $('#reset_form').attr('hidden', false);
                getEmail(user);

            } else {
                swal({
                    type: 'warning',
                    title: 'Something Wrong',
                    text: 'Please enter username!'
                })
            }
        }

        function getEmail(user) {
            var data = {
                'STATUS': 'rPass',
                'PAGE': 'login',
                'user': user
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function change_pass() {
            $('#chk').val(3);
            $('#login_form').attr('hidden', true);
            $('#change_form').attr('hidden', false);
            $('#reset_form').attr('hidden', true);
            $('#username2').focus();
            typePass();
        }

        function back() {
            $('#chk').val(1);
            $('#login_form').attr('hidden', false);
            $('#change_form').attr('hidden', true);
            $('#reset_form').attr('hidden', true);
            $('#username').focus();

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

        function sendmail() {
            var email = document.getElementById("email").value;
            var user = document.getElementById("username").value;

            if (email != "" && user != "") {
                var data = {
                    'STATUS': 'sendmail',
                    'PAGE': 'sendmail',
                    'user': user,
                    'email': email
                };
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            } else {
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
            if (oldpassword != "" && newpassword != "" && confirmpassword != "") {
                if (newpassword == confirmpassword) {
                    var data = {
                        'STATUS': 'cPassword',
                        'PAGE': 'cPassword',
                        'oldpassword': oldpassword,
                        'newpassword': newpassword,
                        'confirmpassword': confirmpassword,
                        'Username': Username,
                    };
                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                } else {
                    swal({
                        type: 'warning',
                        title: 'Something Wrong',
                        text: "password and confirm password don't match"
                    })
                }
            } else {
                swal({
                    type: 'warning',
                    title: 'Something Wrong',
                    text: 'Please recheck your username and password!'
                })
            }
        }

        function make_char() {
            $('.nonspa').on('input', function() {
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');; //<-- replace all other than given set of values
            });
        }

        function setActive() {
            var Username = $('#username').val();
            var Password = $('#password').val();
            if (Username != '' && Password != '') {
                var data = {
                    'STATUS': 'SetActive',
                    'Username': Username,
                    'Password': Password
                };
                senddata(JSON.stringify(data));
            } else {
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
                success: function(result) {
                    try {
                        var temp = $.parseJSON(result);
                        console.log(result);
                    } catch (e) {
                        console.log('Error#542-decode error');
                    }
                    if (temp["status"] == 'success') {
                        if (temp["form"] == 'chk_login') {
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
                            setTimeout(function() {
                                if(temp["pm"] == 9){
                                    window.location.href = 'http://119.59.116.26:8181/linen-catalog/index.php';
                                }else{
                                    window.location.href = 'main.php';
                                }
                            }, 1000);
                        } else if (temp["form"] == 'change_password') {
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
                            setTimeout(function() {
                                window.location.href = 'main.php';
                            }, 1000);
                        } else if (temp["form"] == 'sendmail') {
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
                            setTimeout(function() {
                                $('#email').val("");
                                swal({
                                    title: '',
                                    text: 'Please check your email - we have sent a confirmation message',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then(function() {
                                    if (temp['countMail'] > 0) {
                                        for (var m = 0; m < temp['countMail']; m++) {
                                            var eamil = temp[m]["email"];
                                            var UserName = temp["UserName"];
                                            var Password = temp["Password"];
                                            var Subject = 'Reset password...';
                                            var FName = temp["FName"];
                                            var HptName = temp["HptName"];
                                            var DepName = temp["DepName"];
                                            // window.location.href = 'sendmail.php?UserName='+UserName+'&Password='+Password;
                                            var data = {
                                                'email': eamil,
                                                'UserName': UserName,
                                                'Password': Password,
                                                'Subject': Subject,
                                                'FName': FName,
                                                'HptName': HptName,
                                                'DepName': DepName
                                            };
                                            sendtomail(JSON.stringify(data), )
                                        }
                                    }
                                }, function(dismiss) {
                                    // dismiss can be 'cancel', 'overlay',
                                    // 'close', and 'timer'
                                    if (dismiss === 'cancel') {

                                    }
                                })
                                back();
                            }, 1000);

                        } else if (temp["form"] == 'rPass') {
                            // if(temp["email"] == ''){
                            //     swal({
                            //         title: '',
                            //         text: temp["msg"],
                            //         type: 'error',
                            //         showCancelButton: false,
                            //         confirmButtonColor: '#3085d6',
                            //         cancelButtonColor: '#d33',
                            //         timer: 1000,
                            //         confirmButtonText: 'Ok',
                            //         showConfirmButton: false
                            //     });
                            //     back();
                            // } else{
                            var email = temp["email"];
                            $.ajax({
                                url: "reset_password.php",
                                method: "POST",
                                data: {
                                    email: email
                                },
                                success: function(data) {
                                    $('#reset_switch').html(data);
                                }
                            });
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
                            // }

                        } else if (temp["form"] == 'SetActive') {
                            if (temp['count'] == 1) {
                                swal({
                                    title: temp["msg"],
                                    text: temp["text"],
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    timer: 2000,
                                    confirmButtonText: 'Ok',
                                    showConfirmButton: false
                                });
                                setTimeout(() => {
                                    var Username = temp['Username'];
                                    var Password = temp['Password'];
                                    var Email = temp['Email'];
                                    var data = {
                                        'Username': Username,
                                        'Password': Password,
                                        'Email': Email
                                    };
                                    mailSetAvtice(JSON.stringify(data), );
                                }, 2000);
                            } else if (temp['count'] == 0) {
                                swal({
                                    title: '',
                                    text: temp["msg"],
                                    type: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    timer: 1000,
                                    confirmButtonText: 'Ok',
                                    showConfirmButton: false
                                });
                            }
                        }
                    } else if (temp["status"] == 'change_pass') {
                        $('#chk').val(3);
                        $('#login_form').attr('hidden', true);
                        $('#reset_form').attr('hidden', true);
                        $('#change_form').attr('hidden', false);
                        $('#username2').focus();
                        var username = temp['username'];
                        var password = temp['password'];
                        $.ajax({
                            url: "change_password.php",
                            method: "POST",
                            data: {
                                username: username,
                                password: password
                            },
                            success: function(data) {
                                $('#change_switch').html(data);
                            }
                        });

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
                        }).then(function() {

                        }, function(dismiss) {
                            // dismiss can be 'cancel', 'overlay',
                            // 'close', and 'timer'
                            if (dismiss === 'cancel') {

                            }
                        })
                        //alert(temp["msg"]);
                    }
                },
                failure: function(result) {
                    alert(result);
                },
                error: function(xhr, status, p3, p4) {
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
                success: function(result) {
                    try {
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
                    }).then(function() {

                    }, function(dismiss) {
                        if (dismiss === 'cancel') {

                        }
                    })

                },
                failure: function(result) {
                    alert(result);
                },
                error: function(xhr, status, p3, p4) {
                    var err = "Error " + " " + status + " " + p3 + " " + p4;
                    if (xhr.responseText && xhr.responseText[0] == "{")
                        err = JSON.parse(xhr.responseText).Message;
                    console.log(err);
                }
            });
        }

        function mailSetAvtice(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = 'mailSetActive.php';
            $.ajax({
                url: URL,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(result) {
                    try {
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
                    }).then(function() {

                    }, function(dismiss) {
                        if (dismiss === 'cancel') {

                        }
                    })

                },
                failure: function(result) {
                    alert(result);
                },
                error: function(xhr, status, p3, p4) {
                    var err = "Error " + " " + status + " " + p3 + " " + p4;
                    if (xhr.responseText && xhr.responseText[0] == "{")
                        err = JSON.parse(xhr.responseText).Message;
                    console.log(err);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {

            $.validator.addMethod("hasLowercase", function(value, element) {
                if (this.optional(element)) {
                    return true;
                } else {
                    $("#btn_savePass").attr('disabled', true);
                }
                return /[a-z]/.test(value);
            }, "Must have atleast 1 lower case character");

            $.validator.addMethod("hasUppercase", function(value, element) {
                if (this.optional(element)) {
                    return true;
                } else {
                    $("#btn_savePass").attr('disabled', true);
                }
                return /[A-Z]/.test(value);
            }, "Must have atleast 1 upper case character");

            $.validator.addMethod("checklower", function(value, element) {
                if (this.optional(element)) {
                    return true;
                } else {
                    $("#btn_savePass").attr('disabled', true);
                }
                return /[0-9]/.test(value);
            }, "Must have atleast 1 numeric character");

            $.validator.addMethod("pwcheck", function(value, element) {
                if (this.optional(element)) {
                    return true;
                } else {
                    $("#btn_savePass").attr('disabled', true);
                }
                return /[=!\-@._*#!$%^&]/.test(value);
            }, "Must have atleast 1 special character");

            $('#test').validate({
                errorPlacement: function(error, element) {
                    // Append error within linked label
                    $(element)
                        .closest("form")
                        .find("small[for='" + element.attr("id") + "']")
                        .append(error);
                },
                rules: {
                    newpassword: {
                        rangelength: [8, 16],
                        hasUppercase: true,
                        hasLowercase: true,
                        checklower: true,
                        pwcheck: true
                    },
                    confirmpassword: {
                        equalTo: "#newpassword",
                        rangelength: [8, 16],
                        hasUppercase: true,
                        hasLowercase: true,
                        checklower: true,
                        pwcheck: true
                    }
                },
                messages: {
                    newpassword: {
                        rangelength: "Must be at least 8 -16 charcters"
                    },
                    confirmpassword: {
                        equalTo: "Passwords do not match"
                    }
                },
                success: function(label) {
                    $("#btn_savePass").attr('disabled', false);
                }
            });


        });
    </script>
</body>

</html>