<?php
session_start();
$Userid = $_SESSION['Userid'];
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
  <script type="text/javascript">
  var summary = [];

  $(document).ready(function(e){
    OnLoadPage();
  }).click(function(e) { parent.last_move = new Date();;
  }).keyup(function(e) { parent.last_move = new Date();;
  });


  var refreshId = setInterval(function() {
    OnLoadPage();
  }, 9000);
  $.ajaxSetup({ cache: false });


  jqui(document).ready(function($){
    dialog = jqui( "#dialog" ).dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "ปิด": function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui( "#dialogItem" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });

  });

  function OpenDialogItem(){
    var docno = $("#docno").val();
    if( docno != "" ) dialog.dialog( "open" );
  }

  //======= On create =======
  //console.log(JSON.stringify(data));
  function OnLoadPage(){
    var data = {
      'STATUS'  : 'OnLoadPage'
    };
    senddata(JSON.stringify(data));
    $('#isStatus').val(0)
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

  function senddata(data){
    var form_data = new FormData();
    form_data.append("DATA",data);
    var URL = '../process/daily_request.php';
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
            $( "#TableDocument tbody" ).empty();
            $("#docno").val(temp[0]['DocNo']);
            $("#refdocno").val(temp[0]['RefDocNo']);
            $("#detail").val(temp[0]['Detail']);
            $("#depname").val(temp[0]['DepName']);
            $("#hptname").val(temp[0]['HptName']);
            $("#docdate").val(temp[0]['DocDate']);
            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
              var rowCount = $('#TableDocument >tbody >tr').length;
              $StrTr="<tr id='tr"+temp[i]['DocNo']+"'>"+
              "<td style='width: 10%;'>"+(i+1)+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['DocNo']+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['RefDocNo']+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['Detail']+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['DocDate']+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['DepName']+"</td>"+
              "<td style='width: 15%;'>"+temp[i]['HptName']+"</td>"+
              "</tr>";
              if(rowCount == 0){
                $("#TableDocument tbody").append( $StrTr );
              }else{
                $('#TableDocument tbody:last-child').append(  $StrTr );
              }
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
  </style>
</head>

<body id="page-top">
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">

      <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
        <div class="col-md-12"> <!-- tag column 1 -->
          <!-- /.content-wrapper -->

          <div class="row">
            <div style='width: 98%;'> <!-- tag column 1 -->
              <table style="margin-top:10px;margin-left:15px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid" style="">
                <thead id="theadsum" style="font-size:24px;">
                  <tr role="row">
                    <th style='width: 10%;'><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['dirtydoc'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['cleandoc'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['detail'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['time'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['department'][$language]; ?></th>
                    <th style='width: 15%;'><?php echo $array['hosname'][$language]; ?></th>
                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:600px;">
                </tbody>
              </table>
            </div> <!-- tag column 1 -->
          </div>

        </div>
      </div> <!-- end row tab -->


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
