<?php
$language = $_GET['lang'];
if($language=="en"){
  $language = "en";
}else{
  $language = "th";
}

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['recivedirtycloth'][$language]; ?></title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
  jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
  <style media="screen">
            body{
              font-family: 'THSarabunNew';
              font-size:22px;
            }

            .nfont{
              font-family: 'THSarabunNew';
              font-size:22px;
            }

            button,input[id^='qty'],input[id^='order'],input[id^='max'] {
              font-size: 24px!important;
            }

            .table > thead > tr >th {
              background: #4f88e3!important;
            }

            table tr th,
            table tr td {
              border-right: 0px solid #bbb;
              border-bottom: 0px solid #bbb;
              padding: 5px;
            }
            table tr th:first-child,
            table tr td:first-child {
              border-left: 0px solid #bbb;
            }
            table tr th {
              background: #eee;
              border-top: 0px solid #bbb;
              text-align: left;
            }

            /* top-left border-radius */
            table tr:first-child th:first-child {
              border-top-left-radius: 6px;
            }

            /* top-right border-radius */
            table tr:first-child th:last-child {
              border-top-right-radius: 6px;
            }

            /* bottom-left border-radius */
            table tr:last-child td:first-child {
              border-bottom-left-radius: 6px;
            }

            /* bottom-right border-radius */
            table tr:last-child td:last-child {
              border-bottom-right-radius: 6px;
            }

            a.nav-link{
              width:auto!important;
            }
            .datepicker{z-index:9999 !important}
            .hidden{visibility: hidden;}
span {
  border: 5px solid #BEBEBE;
  padding:  0em 1em;
  background-color: #E6E6FA;
  border-radius: 16px;
  font-size: 24px;
  line-height: 2;
  width: 600px;
}
span.ex1 { 
  -webkit-box-decoration-break: clone;
  -o-box-decoration-break: clone;
  box-decoration-break: clone;
}
div.c {
    font-size: 30px;
    text-align: right;
}

    </style>
          </head>
          <body id="page-top">
            <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

            <div id="wrapper">
              <!-- content-wrapper -->
                <div id="content-wrapper">
                   <div class="row">
                        <div class="col-md-12">
                        <center><h1>กระบวนการทำงาน</h1>
                        <h1>DT201907-00001</h1></center>
                        </div>
                   </div>

                   <div class="row">
                        <div class="col-md-4">
                        </div>
                        <span class="ex1">
                        <div class="col-md-7">
                        <h2>กระบวนการซัก</h2>
                        </div>
                        <div class="c">01:26:45</div>
                        <div class="c">Wait Process</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;<button style="width:105px"; type="button" class="btn btn-info">เริ่ม</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button style="width:105px"; type="button" class="btn btn-danger">หยุด</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button style="width:105px"; type="button" class="btn btn-success">เสร็จสิ้น</button>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        </span>
                        <div class="col-md-1">
                        </div>
                   </div>

                   <div class="row">
                   <br>
                   </div>
                  
                    
                   <div class="row">
                        <div class="col-md-4">
                        </div>
                        <span class="ex1">
                        <div class="col-md-7">
                        <h2>บรรจุห่อ</h2>
                        </div>
                        <div class="c">01:26:45</div>
                        <div class="c">Stop Process</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button style="width:105px"; type="button" class="btn btn-info">เริ่ม</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button style="width:105px"; type="button" class="btn btn-success">เสร็จสิ้น</button>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        </span>
                        <div class="col-md-1">
                        </div>
                   </div>

                   <div class="row">
                   <br>
                   </div>
                   

                   <div class="row">
                        <div class="col-md-4">
                        </div>
                        <span class="ex1">
                        <div class="col-md-7">
                        <h2>ขนส่ง</h2>
                        </div>
                        <div class="c">01:26:45</div>
                        <div class="c">Success Process</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button style="width:105px"; type="button" class="btn btn-info">เริ่ม</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button style="width:105px"; type="button" class="btn btn-success">เสร็จสิ้น</button>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        <div class="row">
                        <div class="col-md-12"></div>
                        </div>
                        </div>
                        </span>
                        <div class="col-md-1">
                        </div>
                   </div>

                </div>
            </div>
                        

                <!-- Bootstrap core JavaScript-->
                <script src="../template/vendor/jquery/jquery.min.js"></script>
                <script src="../template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="../template/vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Page level plugin JavaScript-->
                <script src="../template/vendor/datatables/jquery.dataTables.js"></script>
                <script src="../template/vendor/datatables/dataTables.bootstrap4.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="../template/js/sb-admin.min.js"></script>

                <!-- Demo scripts for this page-->
                <script src="../template/js/demo/datatables-demo.js"></script>

              </body>

              </html>
