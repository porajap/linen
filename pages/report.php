<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

$DocnoXXX = $_GET['DocNo'];
if($Userid==""){
  header("location:../index.html");
}

if(empty($_SESSION['lang'])){
  $language ='th';
}else{
  $language =$_SESSION['lang'];

}

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2,TRUE);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>shelfcount</title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <!-- <link href="../fontawesome/css/fontawesome.min.css" rel="stylesheet"> -->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">
  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">
  <!-- -----------------------------------------------  -->
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
  jqui = jQuery.noConflict(true);
  </script>
  <link href="../css/menu_custom.css" rel="stylesheet"> 
  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>
  <link href="../css/responsive.css" rel="stylesheet">
  <!-- <script src="dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script> -->
  <!-- <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css"> -->
  <link rel="stylesheet" type="text/css" href="../daterangepicker/daterangepicker.css" />
  <!-- <script src="../datepicker/dist/js/datepicker.min.js"></script> -->
  <!-- Include English language -->
  <!-- <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script> -->
  <script src="../fontawesome/js/fontawesome.min.js"></script>


  <script type="text/javascript">
    var summary = [];
    var xItemcode;

    $(document).ready(function(e){
      $('#showday').hide();
      $('#showmonth').hide();
      $('#showyear').hide();
      OnLoadPage();
      // getDepartment();
      // ShowMenu();


    }).mousemove(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });

    jqui(document).ready(function($){


      dialogUsageCode = jqui( "#dialogUsageCode" ).dialog({
        autoOpen: false,
        height: 680,
        width: 1200,
        modal: true,
        buttons: {
          "<?php echo $array['close'][$language]; ?>": function() {
            dialogUsageCode.dialog( "close" );
          }
        },
        close: function() {
          console.log("close");
        }
      });


    });


  

    //======= On create =======
    //console.log(JSON.stringify(data));
    function OnLoadPage(){
      var data = {
        'STATUS'  : 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
      $('#isStatus').val(0)
    }
    function ShowDocument(selecta){
      var searchdocument = $('#searchdocument').val();
      if( typeof searchdocument == 'undefined' ) searchdocument = "";
      var hosCode = $('#side option:selected').attr("value");
      if( typeof hosCode == 'undefined' ) hosCode = "1";
      var deptCode = $('#Dep2 option:selected').attr("value");
      if( typeof deptCode == 'undefined' ) deptCode = "1";

      var data = {
        'STATUS'  	: 'ShowDocument',
        'xdocno'	: searchdocument,
        'selecta' : selecta,
        'deptCode'	: deptCode,
        'hosCode' :   hosCode

      };
      console.log(data);
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
      }).then(function () {
        window.location.href="../logoff.php";
      }, function (dismiss) {
        window.location.href="../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }
function showdate(){
  var chkday = $('#chkday:checked').val();
  var chkmonth = $('#chkmonth:checked').val();
  var chkyear = $('#chkyear:checked').val();
  if(chkday ==1){
    $('#showday').show();
    $('#showmonth').hide();
    $('#showyear').hide();
  }else if (chkmonth ==2){
    $('#showday').hide();
    $('#showmonth').show();
    $('#showyear').hide();  
    }else if (chkyear ==3){
    $('#showday').hide();
    $('#showmonth').hide();
    $('#showyear').show();  
    }
}
    function senddata(data){
      var form_data = new FormData();
      form_data.append("DATA",data);
      var URL = '../process/report.php';
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
          } catch (e) {
            console.log('Error#542-decode error');
          }

          if(temp["status"]=='success'){
            if(temp["form"]=='OnLoadPage'){
              var PmID = <?php echo $PmID;?>;
              var HptCode = '<?php echo $HptCode;?>';
              for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                if(PmID != 1 && HptCode == temp[i]['HptCode']){
                  var Str = "<option value="+temp[i]['HptCode']+" selected>"+temp[i]['HptName']+"</option>";
                  $("#side").append(Str);
                  $("#side").attr('disabled', true);
                  $("#hotpital").append(Str);
                }else{
                  var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
                  $("#side").append(Str);
                  $("#hotpital").append(Str);
                }
                
              }
            }else if(temp["form"]=='getDepartment'){
              $("#department").empty();
              $("#Dep2").empty();
              for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                $("#department").append(Str);
                $("#Dep2").append(Str);
              }
            }
          }else if (temp['status']=="failed") {
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
              timer: 1500,
              confirmButtonText: 'Ok'
            })

            $( "#docnofield" ).val( temp[0]['DocNo'] );
            $( "#TableDocumentSS tbody" ).empty();
            $( "#TableSendSterileDetail tbody" ).empty();
            $( "#TableUsageCode tbody" ).empty();
            $( "#TableItem tbody" ).empty();

          }else{
            console.log(temp['msg']);
          }
        },failure: function (result) {
          alert(result);
        },error: function (xhr, status, p3, p4) {
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

    body{
      font-family: myFirstFont;
      font-size:22px;
    }

    .nfont{
      font-family: myFirstFont;
      font-size:22px;
    }

    button,input[id^='qty'],input[id^='order'],input[id^='max'] {
      font-size: 24px!important;
    }

    .table > thead > tr >th {
      /* background: #4f88e3!important; */
      background-color: #1659a2;
    }
    .table th, .table td {
        border-top: none !important;
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
      /* border-top: 0px solid #bbb; */
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
    .btn_mhee{
      background-color: #e83530;
      color:white;
    }

    .btn_mheesave{
      background-color:#ee9726;
      color:white;
    }
    .btn_mheedel{
      background-color:#b12f31;
      color:white;
    }
    .btn_mheeIM{
      background-color:#3e3a8f;
      color:white;
    }
    .btn_mheedetail{
      background-color:#535d55;
      color:white;
    }
    .btn_mheereport{
      background-color:#d8d9db;
      color:white;
    }
    .btn_mheeCREATE{
      background-color:#1458a3; 

      color:white;
    }
    a.nav-link{
      width:auto!important;
    }
    .datepicker{z-index:9999 !important}
    .hidden{visibility: hidden;}
    .mhee a{
  /* padding: 6px 8px 6px 16px; */
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
}
.mhee a:hover {
  color: #2c3e50;
  font-weight:bold;
  font-size:26px;
}
.mhee button{
            /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
            color: #2c3e50;
            display: block;
            background: none;
            box-shadow:none !important;

            }
            .mhee button:hover {
            color: #2c3e50;
            font-weight:bold;
            font-size:26px;
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
      font-weight:bold;
      font-size:26px;
    }
    .icon{
        padding-top: 6px;
        padding-left: 44px;
      }
      @media (min-width: 992px) and (max-width: 1199.98px) { 

        .icon{
          padding-top: 6px;
          padding-left: 23px;
        }
        .sidenav a {
          font-size: 21px;

        }
       }

       /* ------------------- */
      .daterangepicker .drp-buttons .btn {
        font-size: 12px!important;
        padding: 4px 8px!important;
      }
      .daterangepicker table tr th {
            background: none!important;
      }
  </style>
</head>

<body id="page-top">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['report']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['report']['title'][$language]; ?></li>
  </ol>
  <hr style='width: 98%;height:1px;background-color: #ecf0f1;'>
  <input type="hidden" id='input_chk' value='0'>
    <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
      <div id="wrapper">
          <!-- content-wrapper -->
        <div id="content-wrapper">
          <div class="row">
            <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab"  data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['report'][$language]; ?></a>
                </li>
              </ul>

              <div class="tab-content" id="myTabContent">
                <div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <!-- /.content-wrapper -->
                    <div class="row">
                        <div class="col-md-10">
                            <!-- tag column 1 -->
                            <div class="container-fluid">
                                <div class="card-body mt-3">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['factory'][$language]; ?></label>
                                                <select class="form-control col-sm-8" id="department"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                                <select class="form-control col-sm-8" id="hotpital" ></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['type'][$language]; ?></label>
                                                    <select class="form-control col-sm-8" id="department"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class='form-group row'>
                                                <div class="col-sm-4"></div>
                                                    <div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="chkday" name="customRadioInline1" value='1' onclick="showdate()" class="custom-control-input">
                                                            <label class="custom-control-label" for="chkday"> วัน</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="chkmonth" name="customRadioInline1" value='2'onclick="showdate()" class="custom-control-input">
                                                            <label class="custom-control-label" for="chkmonth"> เดือน</label>
                                                        </div>

                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="chkyear" name="customRadioInline1" value='3' onclick="showdate()" class="custom-control-input">
                                                            <label class="custom-control-label" for="chkyear"> ปี</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>         
                                        </div>

                                      <div class="row">
                                        <div class="col-md-6" id="showday">
                                          <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['choosedate'][$language]; ?></label>
                                                <input type="text" class="form-control col-sm-8" name="datefilter" value="" style="font-size:24px;">
                                          </div>
                                        </div>
                                      <div class="col-md-6">
                                        <div class='form-group row' id="showmonth">
                                            <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                            <input type="text" class="form-control col-sm-8 datepicker-here" id="department">
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-6">
                                        <div class='form-group row' id="showyear">
                                            <label class="col-sm-4 col-form-label text-right"><?php echo $array['type'][$language]; ?></label>
                                            <input type="text" class="form-control col-sm-8 datepicker-here" id="department">
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div> 
                            </div> <!-- tag column 1 -->
                                                              <!-- row btn -->
                        <div class="row m-1  d-flex justify-content-end col-12" >
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="search_1 d-flex align-items-center d-flex justify-content-center">
                                  <i class="fas fa-search"></i>
                              </div>
                              <!-- <img src="../img/icon/ic_create.png"> -->
                            </div>
                            <div>
                              <button class="btn">
                                <?php echo $array['search'][$language]; ?>
                              </button>
                            </div>
                          </div>
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle2 d-flex align-items-center d-flex justify-content-center">
                              <i class="fab fa-searchengin"></i>
                              </div>
                              <!-- <img src="../img/icon/ic_create.png"> -->
                            </div>
                            <div>
                              <button class="btn">
                                <?php echo $array['searchalldep'][$language]; ?>
                              </button>
                            </div>
                          </div>
               
                        </div>
                        <!-- end row btn -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- tag column 1 -->
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped"
                                id="TableItemDetail" width="98%" cellspacing="0" role="grid" style="">
                                <thead id="theadsum" style="font-size:24px;">
                                    <tr role="row" id='tr_1'>
                                        <th style='width: 6%;' nowrap><?php echo $array['no'][$language]; ?></th>
                                        <th style='width: 18%;' nowrap><?php echo $array['code'][$language]; ?></th>
                                        <th style='width: 23%;' nowrap><?php echo $array['item'][$language]; ?></th>
                                        <th style='width: 12%;' nowrap><?php echo $array['unit'][$language]; ?></th>
                                        <th style='width: 11%;' nowrap>
                                            <center><?php echo $array['parsc'][$language]; ?></center>
                                        </th>
                                        <th style='width: 13%;' nowrap>
                                            <center><?php echo $array['leftsc'][$language]; ?></center>
                                        </th>
                                        <th style='width: 17%;' nowrap>
                                            <center><?php echo $array['order'][$language]; ?><center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                                </tbody>
                            </table>
                        </div> <!-- tag column 1 -->
                    </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- Dialog Modal
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
    <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="../daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="../daterangepicker/daterangepicker.min.js"></script>
    <script>
      $(function() {
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
      });
    </script>

</body>

</html>
