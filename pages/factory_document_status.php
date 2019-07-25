<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$HptCode = $_SESSION['HptCode'];

if ($Userid == "") {
  header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
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

  <title>รับผ้าสะอาด</title>

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
  <link href="../css/responsive.css" rel="stylesheet">

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

  <script type="text/javascript">
    var summary = [];
    var xItemcode;
    $(document).ready(function(e) {
      OnLoadPage();
      //==============================
      $('.TagImage').bind('click', {
        imgId: $(this).attr('id')
      }, function(evt) {
        alert(evt.imgId);
      });
      //On create
      var userid = '<?php echo $Userid; ?>';
      if (userid != "" && userid != null && userid != undefined) {
        var dept = '<?php echo $_SESSION['Deptid']; ?>';
        var data = {
          'STATUS': 'getDocument',
          'DEPT': dept
        };

        // console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }).mousemove(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    jqui(document).ready(function($) {

      // dialogRefDocNo = jqui( "#dialogRefDocNo" ).dialog({
      //   autoOpen: false,
      //   height: 670,
      //   width: 1200,
      //   modal: true,
      //   buttons: {
      //     "<?php echo $array['close'][$language]; ?>": function() {
      //       dialogRefDocNo.dialog( "close" );
      //     }
      //   },
      //   close: function() {
      //     console.log("close");
      //   }
      // });

      // dialogItemCode = jqui( "#dialogItemCode" ).dialog({
      //   autoOpen: false,
      //   height: 670,
      //   width: 1200,
      //   modal: true,
      //   buttons: {
      //     "<?php echo $array['close'][$language]; ?>": function() {
      //       dialogItemCode.dialog( "close" );
      //     }
      //   },
      //   close: function() {
      //     console.log("close");
      //   }
      // });

      // jqui( "#dialogItemCode" ).button().on( "click", function() {
      //   dialogItemCode.dialog( "open" );
      // });

      dialogUsageCode = jqui("#dialogUsageCode").dialog({
        autoOpen: false,
        height: 670,
        width: 1200,
        modal: true,
        buttons: {
          "<?php echo $array['close'][$language]; ?>": function() {
            dialogUsageCode.dialog("close");
          }
        },
        close: function() {
          console.log("close");
        }
      });

      // jqui( "#dialogUsageCode" ).button().on( "click", function() {
      //   dialogUsageCode.dialog( "open" );
      // });

    });


    //======= On create =======
    //console.log(JSON.stringify(data));
    function OnLoadPage() {
      var data = {
        'STATUS': 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
      $('#isStatus').val(0)
    }

    function get_dirty_doc() {
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'get_dirty_doc',
        'DocNo': docno
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function get_hospital() {
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'get_hospital',
        'DocNo': docno
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function get_dep() {
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'get_dep',
        'DocNo': docno
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function logoff() {
      swal({
        title: '',
        text: '<?php echo $array['logout'][$language]; ?>',
        type: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        timer: 1000,
        confirmButtonText: 'Ok'
      }).then(function() {
        window.location.href = "../logoff.php";
      }, function(dismiss) {
        window.location.href = "../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/factory_out.php';
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
          } catch (e) {
            console.log('Error#542-decode error');
          }

          if (temp["status"] == 'success') {
            if (temp["form"] == 'OnLoadPage') {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['HptCode'] + ">" + temp[i]['HptName'] + "</option>";
                $("#hotpital").append(Str);
              }
            } else if (temp["form"] == 'get_dirty_doc') {
              $("#department").empty();
              $("#Dep2").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                $("#TableDocument").append(Str);
              }
            }else if (temp["form"] == 'get_hospital') {
              $("#department").empty();
              $("#Dep2").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                $("#department").append(Str);
              }
            }else if (temp["form"] == 'get_dep') {
              $("#department").empty();
              $("#Dep2").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                $("#department").append(Str);
              }
            }

          } else if (temp['status'] == "failed") {
            switch (temp['msg']) {
              case "notchosen":
                temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
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

            $("#docnofield").val(temp[0]['DocNo']);
            $("#TableDocumentSS tbody").empty();
            $("#TableSendSterileDetail tbody").empty();
            $("#TableUsageCode tbody").empty();
            $("#TableItem tbody").empty();
          } else {
            console.log(temp['msg']);
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
          alert(err);
        }
      });
    }

  </script>
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

    button,
    input[id^='qty'],
    input[id^='order'],
    input[id^='max'] {
      font-size: 24px !important;
    }

    .table th,
    .table td {
      border-top: none !important;
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

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
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
      padding-left: 44px;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav a {
        font-size: 21px;

      }
    }
  </style>
</head>

<body id="page-top">
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['xfactory']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['xfactory']['sub'][2][$language]; ?></li>
  </ol>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="row">
        <!-- start row tab -->
        <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
          <div class="tab-content" id="myTabContent">
            <div class="row" style="margin-top:10px;">
              <div class="col-md-3">
                <div class="row" style="font-size:24px;margin-left:2px;">
                  <select class="form-control" style='font-size:24px;' id="hospital">
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="row" style="font-size:24px;margin-left:2px;">
                  <select class="form-control" style='font-size:24px;' id="Dep">
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row" style="margin-left:2px;">
                  <input type="text" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                  <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(0);"><?php echo $array['search'][$language]; ?></button>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <!-- tag column 1 -->
                <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row">
                      <th style='width: 10%;' nowrap>&nbsp;</th>
                      <th style='width: 15%;' nowrap><?php echo $array['docdate'][$language]; ?></th>
                      <th style='width: 15%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                      <th style='width: 15%;' nowrap><?php echo $array['refdocno'][$language]; ?></th>
                      <th style='width: 15%;' nowrap><?php echo $array['employee'][$language]; ?></th>
                      <th style='width: 10%;' nowrap><?php echo $array['time'][$language]; ?></th>
                      <th style='width: 10%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                      <th style='width: 10%;' nowrap><?php echo $array['status'][$language]; ?></th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                  </tbody>
                </table>
              </div> <!-- tag column 1 -->
            </div>
          </div> <!-- end row tab -->
        </div>
      </div>
    </div>
  </div>

  <!-- /#wrapper -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- /#wrapper -->
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

</body>

</html>