<!DOCTYPE html>
<?php
  session_start();
  $Id = $_SESSION['Userid'];
  $TimeOut = $_SESSION['TimeOut'];



$language = $_SESSION['lang'];


header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2,TRUE);
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

            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });

        }).mousemove(function(e) { parent.afk();parent.last_move = new Date();
        }).keyup(function(e) { parent.last_move = new Date();
        });

    function switchlang() {
        var lang = $('#lang').val();
        swal({
        title:'<?php echo $array['changelang'][$language]; ?>',
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
        
        if(lang == "th"){
            $('#lang').val('en');
        }else{
            $('#lang').val('th');
        }

    }

        function timeoutUpdate() {
            var timeout = document.getElementById("timeout").value;
            var Id = "<?php echo $Id ?>";
            if(timeout!=0 && timeout!='' && timeout != null){
                parent.redirectInSecond = timeout;
                parent.target = parent.redirectInSecond * 1000;
                parent.target = parent.target * 60;
                
                var data = {
                    'STATUS' : 'cTimeout',
                    'timeout': timeout,
                    'ID' : Id
                };
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }else{
                swal({
                    type: 'warning',
                    title: 'Something Wrong',
                    text: 'Please recheck your Time out!'
                })
            }

        }

    function senddata(data)
    {
        var form_data = new FormData();
        form_data.append("DATA",data);
        var URL = '../process/setting.php';
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

                            if(temp["page"]=='language') {
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
                                    // window.location.href = 'pages/menu.php';
                                    //return loadIframe('ifrm', this.href)
                                }, function (dismiss) {
                                    // window.location.href = 'pages/menu.php';
                                    if (dismiss === 'cancel') {

                                    }
                                })
                            }else if(temp["page"]=='timeout') {
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
                                    // window.location.href = 'pages/menu.php';
                                    // //return loadIframe('ifrm', this.href)
                                }, function (dismiss) {
                                    // window.location.href = 'pages/menu.php';
                                    if (dismiss === 'cancel') {

                                    }
                                })
                            }
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

        .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -150px;
            margin-left: -250px;
        }
        /* ----------------- */
        .card{
            height:400px;
            background-color:#e9ecef;
            -webkit-box-shadow: 10px 12px 5px -7px rgba(0,0,0,0.23);
            -moz-box-shadow: 10px 12px 5px -7px rgba(0,0,0,0.23);
            box-shadow: 10px 12px 5px -7px rgba(0,0,0,0.23);
            border-radius:20px;
        }
        h4{
            color:rgb(0, 51, 141) !important;
            font-weight:bold;
        }
        input{
            border-radius:20px!important;
            border:2px solid rgb(0, 51, 141) !important;
            color:rgb(0, 51, 141) !important;
        }
        .btn_customer {
            font-size:24px!important;
            border-radius:15px!important;
            width:200px!important;
            background-color:rgb(0, 51, 141) !important;
            color:#fff;
        }

        #label1{
            position: absolute;
            right: 48px;
            top: 210px;
        }
        #label1 label{
            font-size:30px;
            font-weight:bold;
            color:rgb(0, 51, 141) !important;
        }

        select{
            border-radius:20px!important;
            border:2px solid rgb(0, 51, 141) !important;
            color:rgb(0, 51, 141) !important;
            height: 70px!important;
            font-size:30px!important;
            font-weight:bold;

        }
        .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        select {
            text-align: center;
            text-align-last: center;
            /* webkit*/
        }
        option {
            text-align: left;
            /* reset to left*/
        }
    
    </style>
    <title>Login</title>
  </head>
  <body>

  

  <!-- ------------------------------------------------------------------  -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
        <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][13][$language]; ?></li>
    </ol>

    <div class="row mt-5">
        <div class="offset-3 col-md-3 mr-3">
            <div class="card">
                <div class="card-body">
                    <div  class="d-flex justify-content-center mt-3">
                        <img src="../img/icon/clock.png">
                    </div>
                    <div  class="d-flex justify-content-center mt-3">
                        <h4><?php echo $array['changetimeout'][$language]; ?></h4>
                    </div>
                    <div  class="d-flex justify-content-center mt-5">
                        <div class="input-group">
                            <input type="text" class="form-control text-center numonly" id="timeout"  value="<?= $TimeOut ?>" maxlength="10" required onkeyup='if(this.value > 120){this.value=120}'>
                        </div>
                        <div id="label1">
                            <label for="timeout"><?php echo $array['minute'][$language]; ?></label>
                        </div>
                    </div>
                    <div  class="d-flex justify-content-center mt-5">
                        <button class="btn btn_customer" onclick="timeoutUpdate();"><?php echo $array['save'][$language]; ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 ml-3">
            <div class="card">
                <div class="card-body">
                    <div  class="d-flex justify-content-center mt-3">
                        <img src="../img/icon/lang.png">
                    </div>
                    <div  class="d-flex justify-content-center mt-3">
                        <h4><?php echo $array['changelang'][$language]; ?></h4>
                    </div>
                    <div  class="d-flex justify-content-center mt-5">
                        <div class="input-group">
                            <select  class="form-control" id="lang">
                                <?php if($language=='th'){ ?>
                                    <option selected value="th" ><?php echo $array['thai'][$language]; ?></option>
                                    <option value="en"><?php echo $array['eng'][$language]; ?></option>
                                <?php } else { ?>
                                    <option value="th"><?php echo $array['thai'][$language]; ?></option>
                                    <option selected value="en"><?php echo $array['eng'][$language]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div  class="d-flex justify-content-center mt-5" onclick="switchlang()">
                        <button class="btn btn_customer"><?php echo $array['save'][$language]; ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </body>
</html>
