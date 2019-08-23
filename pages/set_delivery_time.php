<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  header("location:../index.html");
}

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

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
    <?php echo $array['department'][$language]; ?>
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

  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <link href="../css/responsive.css" rel="stylesheet">

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

  <script type="text/javascript">
    var summary = [];

    $(document).ready(function(e) {
      //On create
      $('.TagImage').bind('click', {
        imgId: $(this).attr('id')
      }, function(evt) {
        alert(evt.imgId);
      });

      $("#AddItemBNT").hide();
      $("#bSave").hide();
      $("#CancelBNT").hide();
      $("#bCancel").hide();

      getHotpital();
      getFactory();

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });
      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
      });

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    function addTime() {
      var hptCode = $('#hptsel2 option:selected').attr("value");
      var FacCode = $('#factory2 option:selected').attr("value");
      var time = $('#shipTime').val();
      var data = {
        'hptCode': hptCode,
        'FacCode': FacCode,
        'time': time,
        'STATUS': 'addTime'
      };
      senddata(JSON.stringify(data));
    }

    function editTime() {
      var id = $('#factory2 option:selected').attr("value");
      var time = $('#shipTime').val();
      var data2 = {
        'id': id,
        'time': time,
        'STATUS': 'editTime'
      };
      senddata(JSON.stringify(data2));
    }

    function deleteTime() {
      var id = $('#factory2 option:selected').attr("value");
      var data2 = {
        'id': id,
        'STATUS': 'deleteTime'
      };
      senddata(JSON.stringify(data2));
    }

    function getHotpital() {
      var data2 = {
        'STATUS': 'getHotpital'
      };
      senddata(JSON.stringify(data2));
    }

    function getFactory() {
      var data2 = {
        'STATUS': 'getFactory'
      };
      senddata(JSON.stringify(data2));
    }

    function getDetail() {
      var hptCode = $('#hptsel option:selected').attr("value");
      var FacCode = $('#factory option:selected').attr("value");
      var data = {
        'hptCode': hptCode,
        'FacCode': FacCode,
        'STATUS': 'getDetail'
      };
      senddata(JSON.stringify(data));
    }

    function loadduoFac() {
      var hptCode = $('#hptsel2 option:selected').attr("value");
      var data = {
        'hptCode': hptCode,
        'STATUS': 'load_site_fac'
      };
      senddata(JSON.stringify(data));
    }

    function Blankinput() {
      $("#shipTime").val("");
      
      $("#factory2").prop( "disabled", false );
      $("#hptsel2").prop( "disabled", false );

      $("#bNewItem").show();
      $("#NewItem").show();

      $("#AddItemBNT").hide();
      $("#bSave").hide();
      $("#CancelBNT").hide();
      $("#bCancel").hide();

      getDetail();
    }

    function getItem(id) {
      var data = {
        'id': id,
        'STATUS': 'getItem'
      };
      senddata(JSON.stringify(data));

      $("#bNewItem").hide();
      $("#NewItem").hide();

      $("#AddItemBNT").show();
      $("#bSave").show();
      
      $("#CancelBNT").show();
      $("#bCancel").show();
    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/set_delivery_time.php';
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
            title: '<?php echo $array['
                        pleasewait '][$language]; ?>',
            text: '<?php echo $array['
                        processing '][$language]; ?>',
            allowOutsideClick: false
          })
          swal.showLoading();
        },
        success: function(result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }
          swal.close();
          if (temp["status"] == 'success') {

            if ((temp["form"] == 'getFactory')) {
              $("#factory").empty();
              var StrTr = "<option value = ''><?php echo $array['AllFac'][$language]; ?></option>";
              $("#factory").append(StrTr);
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['FacCode'] + "'> " + temp[i]['FacName'] + " </option>";
                $("#factory").append(StrTr);
              }
            } else if ((temp["form"] == 'getHotpital')) {
              $("#hptsel").empty();
              $("#hptsel2").empty();
              var StrTr = "<option value = ''><?php echo $array['Allside'][$language]; ?></option>";
              $("#hptsel").append(StrTr);
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                $("#hptsel").append(StrTr);
                $("#hptsel2").append(StrTr);
              }
              loadduoFac();
            } else if ((temp["form"] == 'getDetail')) {
              $("#tbody").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<tr> <td style='width: 5%;'><td style='width: 5%;'><input type='radio' name='checkdocno' id='checkdocno' onclick = 'getItem("+temp[i]['RowID'] +")' value='" + temp[i]['RowID'] + "'></td>" +
                  "<td style='width: 30%;' nowrap>" + temp[i]['HptName'] + "</td>" +
                  "<td style='width: 30%;' nowrap>" + temp[i]['FacName'] + "</td>" +
                  "<td style='width: 20%;' nowrap>" + temp[i]['SendTime'] + "</td>" +
                  "<td style='width: 10%;'>&nbsp;</td></tr>";
                $("#tbody").append(StrTr);
              }
            } else if ((temp["form"] == 'load_site_fac')) {
              console.log(temp["cnt_Fac"])
              $("#factory2").empty();
              $("#shipTime").prop( "disabled", false );
              if(temp["cnt_Fac"] == 0){
                var StrTr = "<option value = '0'><?php echo $array['noFac'][$language]; ?></option>";
                $("#factory2").append(StrTr);
                $("#shipTime").prop( "disabled", true );
              }else{
                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                  var StrTr = "<option value = '" + temp[i]['FacCode'] + "'> " + temp[i]['FacName'] + " </option>";
                  $("#factory2").append(StrTr);
                }
              }
            } else if ((temp["form"] == 'getItem')) {
              
              $("#hptsel2 select").val(temp['HptCode']);
              $("#factory2").empty();
              var StrTr = "<option value = '" +temp["RowID"] + "'> " + temp['FacName'] + " </option>";
              $("#factory2").append(StrTr);
              $("#shipTime").val( temp['SendTime']);
              $("#factory2").prop( "disabled", true );
              $("#hptsel2").prop( "disabled", true );
              $("#shipTime").prop( "disabled", false );
            } else if ((temp["form"] == 'addTime')) {
              swal({
                title: '',
                text: '<?php echo $array['addsuccessmsg'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              });
              setTimeout(function() {
                loadduoFac();
                Blankinput();
              }, 2000);
            } else if ((temp["form"] == 'editTime')) {
              swal({
                title: '',
                text: '<?php echo $array['editsuccessmsg'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              });
              setTimeout(function() {
                loadduoFac();
                Blankinput();
                getDetail();
              }, 2000);
            } else if ((temp["form"] == 'deleteTime')) {
              swal({
                title: '',
                text: '<?php echo $array['cancelsuccessmsg'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              });
              setTimeout(function() {
                loadduoFac();
                Blankinput();
                getDetail();
              }, 2000);
            }

          } else if (temp['status'] == "failed") {
            switch (temp['msg']) {
              case "notchosen":
                temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                break;
              case "cantcreate":
                temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                break;
              case "noinput":
                temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                break;
              case "notfound":
                temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                break;
              case "addsuccess":
                temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                break;
              case "addfailed":
                temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                break;
              case "editsuccess":
                temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                break;
              case "editfailed":
                temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                break;
              case "cancelsuccess":
                temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                break;
              case "cancelfailed":
                temp['msg'] = "<?php echo $array['cancelfailed'][$language]; ?>";
                break;
              case "nodetail":
                temp['msg'] = "<?php echo $array['nodetail'][$language]; ?>";
                break;
            }
            swal({
              title: '',
              text: temp['msg'],
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: 'Ok'
            })

          } else if (temp['status'] == "notfound") {
            // swal({
            //   title: '',
            //   text: temp['msg'],
            //   type: 'info',
            //   showCancelButton: false,
            //   confirmButtonColor: '#3085d6',
            //   cancelButtonColor: '#d33',
            //   showConfirmButton: false,
            //   timer: 2000,
            //   confirmButtonText: 'Ok'
            // })
            $("#TableItem tbody").empty();
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
  </script>

</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][14][$language]; ?></li>
  </ol>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <div class="container-fluid">
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
              <div class="col-md-12">
                <!-- tag column 1 -->
                <div class="container-fluid">
                  <div class="card-body" style="padding:0px; margin-top:12px;">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="row" style="margin-left:5px;">
                          <select class="form-control" id="hptsel"></select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="row" style="margin-left:5px;">
                          <select class="form-control" id="factory"></select>
                        </div>
                      </div>
                      <div class="col-md-4 mhee">
                        <div class="row">
                          <div class="search_custom col-md-4">
                            <div class="d-flex justify-content-start">
                              <div class="search_1 d-flex align-items-center d-flex justify-content-center">
                                <i class="fas fa-search"></i>
                              </div>
                              <button class="btn search" onclick="getDetail()" id="bsearch">
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
                          <th style='width: 10%;'>&nbsp;</th>

                          <th style='width: 30%;' nowrap>
                            <?php echo $array['side'][$language]; ?>
                          </th>
                          <th style='width: 30%;' nowrap>
                            <?php echo $array['factory'][$language]; ?>
                          </th>
                          <th style='width: 20%;' nowrap>
                            <?php echo $array['time2'][$language]; ?>
                          </th>
                          <th style='width: 10%;'>&nbsp;</th>

                        </tr>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div> <!-- tag column 1 -->
            </div>
            <!-- =============================================================================================================================== -->

            <!-- /.content-wrapper -->

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <!-- start row tab -->
    <div class="col-md-12">
      <!-- tag column 1 -->
      <div class="container-fluid">
        <div id="memu_tap1">
          <div class="row m-1 mt-5 d-flex justify-content-end">

            <div class="menu" id="NewItem">
              <div class="d-flex justify-content-center">
                <div class="circle4 d-flex align-items-center d-flex justify-content-center">
                  <i class="fas fa-plus"></i>
                </div>
              </div>
              <div>
                <button class="btn" onclick="addTime()" id="bNewItem">
                  <?php echo $array['itemnew'][$language]; ?>
                </button>
              </div>
            </div>

            <div class="menu" id="AddItemBNT">
              <div class="d-flex justify-content-center">
                <div class="circle4 d-flex align-items-center d-flex justify-content-center">
                  <i class="fas fa-save"></i>
                </div>
              </div>
              <div>
                <button class="btn" onclick="editTime()" id="bSave">
                  <?php echo $array['save'][$language]; ?>
                </button>
              </div>
            </div>

            <div class="menu" id="BlankItemBNT">
              <div class="d-flex justify-content-center">
                <div class="circle6 d-flex align-items-center d-flex justify-content-center">
                  <i class="fas fa-redo-alt"></i>
                </div>
              </div>
              <div>
                <button class="btn" onclick="Blankinput()" id="bDelete">
                  <?php echo $array['clear'][$language]; ?>
                </button>
              </div>
            </div>

            <div class="menu" id="CancelBNT">
              <div class="d-flex justify-content-center">
                <div class="circle3 d-flex align-items-center d-flex justify-content-center">
                  <i class="fas fa-trash-alt"></i>
                </div>
              </div>
              <div>
                <button class="btn" onclick="deleteTime()" id="bCancel">
                  <?php echo $array['cancel'][$language]; ?>
                </button>
              </div>
            </div>

          </div>
        </div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" onclick="menu_tapShow();" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <!-- /.content-wrapper -->
            <div class="row">
              <div class="col-md-12">
                <!-- tag column 1 -->
                <div class="container-fluid">
                  <div class="card-body" style="padding:0px; margin-top:10px;">
                    <!-- =================================================================== -->

                    <div class="row">
                      <div class="col-md-6">
                        <div class='form-group row'>
                          <label class="col-sm-4 col-form-label text-right"><?php echo $array['hosname'][$language]; ?></label>
                          <select class="form-control col-sm-8 checkblank" id="hptsel2" onchange="loadduoFac()"></select>
                        </div>
                        <div class='form-group row'>
                          <label class="col-sm-4 col-form-label text-right"><?php echo $array['factory'][$language]; ?></label>
                          <select class="form-control col-sm-8 checkblank" id="factory2" ></select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class='row'>
                          <label class="col-sm-4 col-form-label text-right"><?php echo $array['time2'][$language]; ?></label>
                          <input class="form-control col-sm-4 checkblank numonly" id="shipTime" "></input>
                          <label class="col-sm-4 col-form-label text"><?php echo $array['minute'][$language]; ?></label>
                        </div>
                      </div>
                    </div>
                    <!-- =================================================================== -->
                  </div>
                </div>
              </div> <!-- tag column 1 -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- =============================================================================================================================== -->


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
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

  <style media="screen">
    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
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

    button {
      font-size: 24px !important;
    }

    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }

    .sidenav {
      height: 100%;
      overflow-x: hidden;
      /* padding-top: 20px; */
      border-left: 2px solid #bdc3c7;
    }

    .sidenav a {
      padding: 6px 8px 6px 16px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .sidenav a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .icon {
      padding-top: 6px;
      padding-left: 33px;
    }

    .mhee a {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 23px;
      color: #818181;
      display: block;
    }

    .mhee a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .mhee button {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 23px;
      color: #818181;
      background: none;
      box-shadow: none !important;
      display: block;
    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .opacity {
      opacity: 0.5;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav {
        margin-left: 30px;
      }

      .sidenav a {
        font-size: 20px;

      }

    }
  </style>

</body>

</html>