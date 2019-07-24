<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$TimeOut = $_SESSION['TimeOut'];

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
$json = json_encode($xml);
$array = json_decode($json,TRUE);
?>
<!--  -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Daily Request</title>

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
  <script type="text/javascript">
  var summary = [];

  $(document).ready(function(e) {
      OnLoadPage();
      alert_SetPrice();
  }).mousemove(function(e) { parent.afk();parent.last_move = new Date();
  }).keyup(function(e) { parent.last_move = new Date();
  });

  $.ajaxSetup({ cache: false });

  // jqui(document).ready(function($){
  //   dialog = jqui( "#dialog" ).dialog({
  //     autoOpen: false,
  //     height: 650,
  //     width: 1200,
  //     modal: true,
  //     buttons: {
  //       "ปิด": function() {
  //         dialog.dialog( "close" );
  //       }
  //     },
  //     close: function() {
  //       console.log("close");
  //     }
  //   });

  //   jqui( "#dialogItem" ).button().on( "click", function() {
  //     dialog.dialog( "open" );
  //   });

  // });

  // function OpenDialogItem(){
  //   var docno = $("#docno").val();
  //   if( docno != "" ) dialog.dialog( "open" );
  // }

  //======= On create =======
  //console.log(JSON.stringify(data));
  function OnLoadPage(){
    var hptcode = '<?php echo $HptCode ?>';
    var isStatus = $("#IsStatus").val();
    var data = {
      'STATUS'  : 'OnLoadPage',
      'isStatus'    : isStatus,
      'hptcode' : hptcode

    };

    senddata(JSON.stringify(data));
  }
  function alert_SetPrice(){
    var PmID = '<?php echo $PmID; ?>';
    var Userid = '<?php echo $Userid; ?>';
    var HptCode = '<?php echo $HptCode; ?>';
    var data = { 
      'STATUS'  : 'alert_SetPrice',
      'PmID'  : PmID,
      'HptCode'  : HptCode,
      'Userid'  : Userid
    };
    senddata(JSON.stringify(data));
  }
  function get_last_move() {
      last_move = new Date();
      return last_move;
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

  function setCard(text1,text2,text3){
    return " <a href='shelfcount.php?DocNo="+text2+"'><div class='card'>"+
      "<div class='card-header'>"+text1+"</div>"+
      "<div class='card-main'>"+
        "<i class='material-icons'>"+text2+"</i>"+
        "<div class='main-description'>"+text3+"</div>"+
      "</div>"+
    "</div> </a>";
  }


  function senddata(data){
    var form_data = new FormData();
    form_data.append("DATA",data);
    var URL = '../process/menu.php';
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
            $( "#CardView" ).empty();
            $( "#dd" ).empty();
            // $( "#TableDocument tbody" ).empty();
            for (var i = 0; i < temp["Row"]; i++) {
              $StrTr=setCard(temp[i]['DepName'],temp[i]['DocNo'],temp[i]['DocDate']);
              $("#CardView").append( $StrTr );
            }
          } else if(temp["form"]=='alert_SetPrice'){
            $('#countRow').val(temp['countRow']);
            var result = '<table class="table table-fixed ">';
            var PmID = <?php echo $PmID; ?>;
            if(temp['countRow']==1){
                result += '<tr style="background-color:#2980b9;color:#ffffff">'+
                            '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['side'][$language]; ?> '+temp[0]['HptName']+'</td>'+
                          '</tr>' +
                          '<tr>'+
                            '<td style="width:18%"></td>' + 
                            '<td nowrap style="width:40%" class="text-left"><?php echo $array['docno'][$language]; ?>: ' +temp[0]['DocNo']+ '</td>'+
                            '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[0]['xDate']+ '</td>' +
                          '</tr>' +
                          '<tr>'+
                            '<td style="width:18%"></td>' + 
                            '<td nowrap style="width:80%" class="text-left"><?php echo $array['changprice'][$language]; ?>: ' +temp[0]['xDate']+ ' <?php echo $array['Timeleft'][$language]; ?>  ' +temp[0]['DateDiff']+  ' <?php echo $array['day'][$language]; ?></td>'+
                          '</tr>' ;
              
              $("#result_alert1").append(result);
              $("#alert_SetPrice1").modal('show');
            }else if(temp['countRow']>1){
                                                                              
              for (var i = 0; i < temp['countRow']; i++) {
                  result += '<tr style="background-color:#2980b9;color:#ffffff">'+
                              '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;">'+(i+1)+'.'+' <?php echo $array['side'][$language]; ?> '+temp[i]['HptName']+'</td>'+
                            '</tr>' +
                            '<tr>'+
                              '<td style="width:18%"></td>' + 
                              '<td nowrap style="width:40%" class="text-left"><?php echo $array['docno'][$language]; ?>: ' +temp[i]['DocNo']+ '</td>'+
                              '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['xDate']+ '</td>' +
                            '</tr>' +
                            '<tr>'+
                              '<td style="width:18%"></td>' + 
                              '<td nowrap style="width:80%" class="text-left"><?php echo $array['changprice'][$language]; ?>: ' +temp[i]['EndDate']+ ' <?php echo $array['Timeleft'][$language]; ?>  ' +temp[i]['DateDiff']+  ' <?php echo $array['day'][$language]; ?></td>'+
                            '</tr>' ;
              }
              $("#result_alert tbody").append(result);
              $("#alert_SetPrice").modal('show');
            }
          }
        }else{
          console.log(temp['msg']);
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
        alert(err);
      }
    });
  }
  </script>

  <style media="screen">

    body{
      font-family: 'THSarabunNew';
      font-size:22px;
    }

    .nfont{
      font-family: 'THSarabunNew';
      font-size:22px;
    }

    button,input[id^='qty'] {
      font-size: 24px!important;
    }
    .table > thead > tr >th {
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
    a:link{
      text-decoration:none;
    }
    a.nav-link{
      width:auto!important;
    }
    .datepicker{z-index:9999 !important}
    .hidden{visibility: hidden;}

    /*=====================================*/
    .card {
      width: 250px;                 /* Set width of cards */
      display: flex;                /* Children use Flexbox */
      flex-direction: column;       /* Rotate Axis */
      border: 1px solid #1659a2;    /* Set up Border */
      border-radius: 4px;           /* Slightly Curve edges */
      overflow: hidden;             /* Fixes the corners */
      margin: 5px;                  /* Add space between cards */
    }

    .card-header {
      color: #FFFFFF;
      text-align: center;
      font-size: 24px;
      font-weight: 600;
      border-bottom: 1px solid #1659a2;
      background-color: #1659a2;
      padding: 5px 10px;
    }

    .card-main {
      display: flex;              /* Children use Flexbox */
      flex-direction: column;     /* Rotate Axis to Vertical */
      justify-content: center;    /* Group Children in Center */
      align-items: center;        /* Group Children in Center (on cross axis) */
      padding: 15px 0;            /* Add padding to the top/bottom */
    }

    .material-icons {
      font-size: 36px;
      color: #589fe5;
      margin-bottom: 5px;
    }

    .main-description {
      color: #0627F7;
      font-size: 18px;
      text-align: center;
    }

    /* IDs for additional colors*/
    /* Colors from Google Material Design: https://material.io/guidelines/style/color.html*/

    #or-border {
      border-color: #FFE082;
    }

    #or-header {
      background-color: #FFF8E1;
      border-color: #FFE082;
      color: #FFA000;
    }

    #or-color {
      color: #FFA000;
    }

    #red-border {
      border-color: #EF9A9A;
    }

    #red-header {
      background-color: #FFEBEE;
      border-color: #EF9A9A;
      color: #D32F2F;
    }

    #red-color {
      color: #D32F2F;
    }
    /* -------------------------- */
    /* #alert_SetPrice .modal-content {
      width: 130% !important;
      right: 15% !important;
    } */

    #alert_SetPrice1 .modal-content {
        width: 100% !important;
    }

    #alert_SetPrice .modal-body{
        width: 100% !important;
        height: 600px;
        overflow-y: auto;
    }
  </style>
</head>

<body id="page-top">
    <input type="hidden" id='countRow'>
  <div id="wrapper">
    <div id="content-wrapper">
      <div style="margin-top:5px;margin-left:15px;width=100%"> <!-- start row tab -->
        <div class="row"  <?php if($PmID != 1 && $PmID != 2 && $PmID != 3) echo 'hidden'; ?>>
          <div class="col-md-12">
            <div class="row" id="CardView"> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div id="dd"> </div>
          </div>
        </div>

      </div> <!-- end row tab -->
    </div>
  </div>   

  <!-- Modal -->
<div class="modal fade" id="alert_SetPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h1 class="modal-title" style='font-size:30px;color: rgb(0, 51, 141) '><?php echo $array['set'][$language]; ?></h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
      <table style="margin-top:10px;" class="table-borderless" id="result_alert" width="100%" cellspacing="0" role="grid" style="">
        <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:auto">
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="alert_SetPrice1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h1 class="modal-title" style='font-size:30px;color: rgb(0, 51, 141) '><?php echo $array['set'][$language]; ?></h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
      <table style="margin-top:10px;" class="table-borderless" id="result_alert1" width="100%" cellspacing="0" role="grid" style="">
        <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:auto">
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
