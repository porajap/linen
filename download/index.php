<?php
    date_default_timezone_set("Asia/Bangkok");

    header ('Content-type: text/html; charset=utf-8');
    $xml = simplexml_load_file('xml/general_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet">
    <title>Download</title>

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

        .btn_customer:hover{
            color:#fff;
            background-color:rgb(6, 41, 103) !important
        }

        .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        @media only screen and (max-width: 500px) {
            .buttom1 {
                margin-bottom: 6%;
            }
}
    
    </style>
</head>

<body class="fix-header card-no-border fix-sidebar">

<div class="row d-flex justify-content-center align-items-center" style="height: 774px">
            <div class="col-md-8  col-sm-12  mt-sm-5 buttom1">
                <div class="card">
                    <div class="card-body">
                        <div  class="d-flex justify-content-center mt-3">
                            <i class="fab fa-android" style="font-size:124px;color: #2c762c;"></i>
                        </div>
                        <div  class="d-flex justify-content-center mt-1">
                            <h4><?php echo $array['changetimeout'][$language]; ?></h4>
                        </div>
                        <div  class="d-flex justify-content-center mt-2">
                            <div style="font-size:40px;">
                                Download for Android
                            </div>
                        </div>
                        <div  class="d-flex justify-content-center mt-5">
                            <button class="btn btn_customer" onclick="window.location.href='file/linen0204.apk'"'"><i class="fas fa-download"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-8  col-sm-12 mt-sm-5">
                <div class="card">
                    <div class="card-body">
                        <div  class="d-flex justify-content-center mt-3">
                            <i class="fab fa-apple" style="font-size:124px;color:#7b7d7b;"></i>
                        </div>
                        <div  class="d-flex justify-content-center mt-1">
                            <h4><?php echo $array['changetimeout'][$language]; ?></h4>
                        </div>
                        <div  class="d-flex justify-content-center mt-2">
                            <div style="font-size:40px;">
                                Download for IOS
                            </div>
                        </div>
                        <div  class="d-flex justify-content-center mt-5">
                        <button class="btn btn_customer" onclick="window.location.href='https://poseintelligence.com/linen/download/nLinen40.ipa'"'"><i class="fas fa-download"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->



    <script src="assets/js/jquery-3.4.1.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/fontawesome/js/all.min.js"></script>

</body>
</html>