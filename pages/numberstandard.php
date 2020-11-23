<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  header("location:../index.html");
}
if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}
require 'updatemouse.php';

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>
    <?php echo $array['DeliveryCycle'][$language]; ?>
  </title>

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
  <link href="../css/jquery.timepicker.css" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <link href="../css/menu_custom.css" rel="stylesheet">

  <style media="screen">
    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }
    .bordergn{
      border-color: green;
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .opacity {
      opacity: 0.5;
    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    input,
    select {
      font-size: 24px !important;
    }

    th,
    td {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
      background-color: #1659a2;
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
      border-top-left-radius: 15px;
    }

    table tr:first-child th:first-child {
      border-bottom-left-radius: 15px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 15px;
    }

    table tr:first-child th:last-child {
      border-bottom-right-radius: 15px;
    }

    /* bottom-left border-radius */
    table tr:last-child td:first-child {
      border-bottom-left-radius: 6px;
    }

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
    }
  </style>
</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][22][$language]; ?></li>
  </ol>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">

      <div class="row">
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
              <div class="row">
                <div class="col-md-4 off-set-10">
                  <div class="row" style="margin-left:5px;">
                    <select class="form-control col-md-8 " id="hptsel">
                    </select>
                    <div class="search_custom col-md-2">
                      <div class="search_1 d-flex justify-content-start">
                        <button class="btn" onclick="ShowItem()" id="bSave">
                          <i class="fas fa-search mr-2"></i>
                          <?php echo $array['search'][$language]; ?>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>


              </div>
              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                <thead id="theadsum" style="font-size:11px;">
                  <tr role="row">
                    <th style='width: 5%;'>&nbsp;</th>
                    <th style='width: 10%;'> <?php echo $array['no'][$language]; ?> </th>
                    <th style='width: 35%;'>ชื่อเมนู</th>
                    <th style='width: 25%;'>ล่างซ้าย</th>
                    <th style='width: 25%;'>ล่างขวา</th>
                  </tr>

                </thead>
                <tbody id="tbody" class="nicescrolled" style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>
                  <tr>
                    <td style='width: 5%;'>&nbsp;</td>
                    <td style='width: 10%;' id="menu1">1</td>
                    <td style='width: 35%;'><?php echo $array['ReportDirry'][$language]; ?></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear" autocomplete="off" id="textLeftMenu1"></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textRightMenu1"></td>
                  </tr>
                  <tr>
                    <td style='width: 5%;'>&nbsp;</td>
                    <td style='width: 10%;' id="menu2">2</td>
                    <td style='width: 35%;'><?php echo $array['ReportNewlinen'][$language]; ?></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textLeftMenu2"></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textRightMenu2"></td>
                  </tr>
                  <tr>
                    <td style='width: 5%;'>&nbsp;</td>
                    <td style='width: 10%;' id="menu3">3</td>
                    <td style='width: 35%;'><?php echo $array['ReportRewash'][$language]; ?></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textLeftMenu3"></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textRightMenu3"></td>
                  </tr>
                  <tr>
                    <td style='width: 5%;'>&nbsp;</td>
                    <td style='width: 10%;' id="menu4">4</td>
                    <td style='width: 35%;'><?php echo $array['ReportDamage'][$language]; ?></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textLeftMenu4"></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textRightMenu4"></td>
                  </tr>
                  <tr>
                    <td style='width: 5%;'>&nbsp;</td>
                    <td style='width: 10%;' id="menu5">5</td>
                    <td style='width: 35%;'><?php echo $array['ReportClean'][$language]; ?></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textLeftMenu5"></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textRightMenu5"></td>
                  </tr>
                  <tr>
                    <td style='width: 5%;'>&nbsp;</td>
                    <td style='width: 10%;' id="menu6">6</td>
                    <td style='width: 35%;'><?php echo $array['ReportShelfcount'][$language]; ?></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textLeftMenu6"></td>
                    <td style='width: 25%;'><input type="text" class="form-control clear " autocomplete="off" id="textRightMenu6"></td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
        </div> <!-- tag column 1 -->
      </div>



      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>


      <script type="text/javascript">
        var summary = [];
        $(document).ready(function(e) {
          feedHospitalSelection();


          $('#searchitem').keyup(function(e) {
            if (e.keyCode == 13) {
              ShowItem();
            }
          });

          $('#textLeftMenu1').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu1').val();
              addMenu("1", "textLeft", text);
            }
          });


          $('#textRightMenu1').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu1').val();
              addMenu("1", "textRight", text);
            }
          });

          $('#textLeftMenu2').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu2').val();
              addMenu("2", "textLeft", text);
            }
          });


          $('#textRightMenu2').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu2').val();
              addMenu("2", "textRight", text);
            }
          });

          $('#textLeftMenu3').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu3').val();
              addMenu("3", "textLeft", text);
            }
          });


          $('#textRightMenu3').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu3').val();
              addMenu("3", "textRight", text);
            }
          });

          $('#textLeftMenu4').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu4').val();
              addMenu("4", "textLeft", text);
            }
          });


          $('#textRightMenu4').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu4').val();
              addMenu("4", "textRight", text);
            }
          });

          $('#textLeftMenu5').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu5').val();
              addMenu("5", "textLeft", text);
            }
          });


          $('#textRightMenu5').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu5').val();
              addMenu("5", "textRight", text);
            }
          });

          $('#textLeftMenu6').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu6').val();
              addMenu("6", "textLeft", text);
            }
          });


          $('#textRightMenu6').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu6').val();
              addMenu("6", "textRight", text);
            }
          });

          $('#textLeftMenu7').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textLeftMenu7').val();
              addMenu("7", "textLeft", text);
            }
          });


          $('#textRightMenu7').keyup(function(e) {
            if (e.keyCode == 13) {
              var text = $('#textRightMenu7').val();
              addMenu("7", "textRight", text);
            }
          });

          $("#hptsel").change(function() {
            var hptsel = $("#hptsel").val();
            showMenu(hptsel);
          });

        }).click(function(e) {
          parent.afk();
        }).keyup(function(e) {
          parent.afk();
        });

        function feedHospitalSelection() {
          $.ajax({
            url: "../process/numberstandard.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'feedHospitalSelection',
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              var option = `<option value="" selected><?php echo $array['selecthospital'][$language]; ?></option>`;
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  option += `<option value="${value.HptCode}">${value.HptName}</option>`;
                });
              } else {
                option = `<option value="0">Data not found</option>`;
              }

              $("#hptsel").html(option);
            }
          });
        }

        function addMenu(number, direct, text) {

          var hptsel = $('#hptsel').val();

          if (hptsel == "") {
            swal({
              title: '',
              text: 'กรุณาเลือกโรงพยาบาล',
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: 'Ok'
            })
          } else {
            $.ajax({
              url: "../process/numberstandard.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'addMenu',
                'number': number,
                'text': text,
                'direct': direct,
                'hptsel': hptsel
              },
              success: function(result) {
                var ObjData = JSON.parse(result);
                if(ObjData.direct == "textLeft"){
                  $("#textLeftMenu"+number).addClass("bordergn");
                }else{
                  $("#textRightMenu"+number).addClass("bordergn");
                }
              }
            });
          }
        }

        function showMenu(hptsel) {

          $(".clear").val("");
          $.ajax({
            url: "../process/numberstandard.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'showMenu',
              'hptsel': hptsel
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {

                  switch (value.menuCode) {
                    case "1":
                      $("#textLeftMenu1").val(value.textLeft);
                      $("#textRightMenu1").val(value.textRight);
                      break;
                    case "2":
                      $("#textLeftMenu2").val(value.textLeft);
                      $("#textRightMenu2").val(value.textRight);
                      break;
                    case "3":
                      $("#textLeftMenu3").val(value.textLeft);
                      $("#textRightMenu3").val(value.textRight);
                      break;
                    case "4":
                      $("#textLeftMenu4").val(value.textLeft);
                      $("#textRightMenu4").val(value.textRight);
                      break;
                    case "5":
                      $("#textLeftMenu5").val(value.textLeft);
                      $("#textRightMenu5").val(value.textRight);
                      break;
                    case "6":
                      $("#textLeftMenu6").val(value.textLeft);
                      $("#textRightMenu6").val(value.textRight);
                      break;

                    default:
                      break;
                  }
                });
              }
            }
          });
        }
      </script>













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
      <script src="../js/jquery.timepicker.js"></script>







</body>

</html>