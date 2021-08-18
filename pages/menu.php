<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$TimeOut = $_SESSION['TimeOut'];
$menu = $_SESSION['menu'];

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
  <?php include_once('../assets/import/css.php'); ?>
</head>

<body id="page-top">
  <input type="hidden" id='countRow'>
  <div id="wrapper">
    <div id="content-wrapper">
      <div style="margin-top:5px;margin-left:15px;width:100%">
        <!-- start row tab -->
        <div class="row" <?php if ($PmID != 1 && $PmID != 2 && $PmID != 3 && $PmID != 6) echo 'hidden'; ?>>
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
      <div class="row" <?php if ($PmID != 1 && $PmID != 3 && $PmID != 6 || $menu != 1) echo 'hidden'; ?>>
        <div class="col-md-12 mb-3">
          <div class="row ml-1">
            <select autocomplete="off" style="font-size:22px;" class="form-control  col-sm-4" id="selectSite"></select>
            <div class="menuMini mhee1 ml-3">
              <div class="search_1 d-flex justify-content-start">
                <button class="btn" onclick="showDocAll();">
                  <i class="fas fa-search mr-2 pl-2"></i>
                  <?php echo $array['search'][$language]; ?>
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>

      <ul class="nav nav-tabs" id="myTab" role="tablist" <?php if ($menu != 1) echo 'hidden'; ?>>
        <li class="nav-item">
          <a class="nav-link active" id="tab_head1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab_head1" aria-selected="true"><?php echo $array2['menu']['general']['sub'][19][$language]; ?> <span class="badge badge-danger badge-counter" id="i_RevealDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head3" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab_head3" aria-selected="false">Par Department<span class="badge badge-danger badge-counter" id="i_RequestPar">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head2" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab_head2" aria-selected="false"><?php echo $array2['menu']['general']['sub'][20][$language]; ?>  <span class="badge badge-danger badge-counter" id="i_CallDirtyDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head4" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab_head4" aria-selected="false"><?php echo $array2['menu']['general']['sub'][21][$language]; ?>  <span class="badge badge-danger badge-counter" id="i_MoveDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head5" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab_head5" aria-selected="false"><?php echo $array2['menu']['general']['sub'][22][$language]; ?> <span class="badge badge-danger badge-counter" id="i_OtherDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head6" data-toggle="tab" href="#tab6" role="tab" aria-controls="tab_head5" aria-selected="false">Chatroom <span class="badge badge-danger badge-counter" id="i_ChatRoom">0</span></a>
        </li>
      </ul>

      <div class="tab-content" id="myTabContent" <?php if ($menu != 1) echo 'hidden'; ?>>

        <div class="tab-pane show active fade" id="tab1" role="tabpanel" aria-labelledby="tab1">
          <div class="row" id="row_RevealDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab3" role="tabpanel" aria-labelledby="tab3">
          <div class="row" id="row_RequestPar">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
          <div class="row" id="row_CallDirtyDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab4" role="tabpanel" aria-labelledby="tab4">
          <div class="row" id="row_MoveDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab5" role="tabpanel" aria-labelledby="tab5">
          <div class="row" id="row_OtherDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab6" role="tabpanel" aria-labelledby="tab6">
          <div class="row" id="row_ChatRoom">
          </div>
        </div>







      </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-body">
            <div id='price'></div>
            <div id='confac'></div>
            <div id='conhos'></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modal_request" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table table-fixed table-condensed table-striped mt-3" id="tableRequest" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row" id='tr_1'>
                      <th nowrap><?php echo $array['sn'][$language]; ?></th>
                      <th nowrap><?php echo $array['item'][$language]; ?></th>
                      <th nowrap>
                        <center><?php echo $array['parsc'][$language]; ?></center>
                      </th>
                      <th nowrap>
                        <center>ขอแก้ไขยอด Par</center>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:200px;"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_reveal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">detailReveal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table table-fixed table-condensed table-striped mt-3" id="tableReveal" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row" id='tr_1'>
                      <th nowrap><?php echo $array['sn'][$language]; ?></th>
                      <th nowrap><?php echo $array['item'][$language]; ?></th>
                      <th nowrap>
                        <center><?php echo $array['parsc'][$language]; ?></center>
                      </th>
                      <th nowrap> <center>issue</center></th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:200px;"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" class="form-control" style="font-size:22px;" disabled id="txtComment">
              <input type="text" class="form-control" style="font-size:22px;" id="txtDocNoHidden" hidden>
            </div>
          </div>
        </div>
      </div>
    </div>


</body>


<?php include_once('../assets/import/js.php'); ?>
<script type="text/javascript">
  var summary = [];
  $(document).ready(function(e) {
    var PmID = '<?php echo $PmID; ?>';
    if(PmID ==8){
      $("#tab_head1").hide();
      $("#tab_head2").hide();
      $("#tab_head3").hide();
      $("#tab_head4").hide();
      $("#tab_head5").hide();
      $("#tab6").addClass("active");
      $("#tab_head6").addClass("active");
      alert_ChatRoom();
    }else{
      alert_SetPrice();
      alert_RevealDep();
      alert_CallDirtyDep();
      alert_RequestPar();
      alert_MoveDep();
      alert_OtherDep();
      alert_ChatRoom();
    }
    
    GetSite();

  }).click(function(e) {
    parent.afk();
    parent.last_move = new Date();
  }).keyup(function(e) {
    parent.last_move = new Date();
  });

  $.ajaxSetup({
    cache: false
  });

  //======= On create =======
  function showDocAll() {
    alert_RevealDep();
    alert_CallDirtyDep();
    alert_RequestPar();
    alert_MoveDep();
    alert_OtherDep();
    alert_ChatRoom();
  }

  function alert_SetPrice() {
    var PmID = '<?php echo $PmID; ?>';
    var Userid = '<?php echo $Userid; ?>';
    var HptCode = '<?php echo $HptCode; ?>';
    var data = {
      'STATUS': 'alert_SetPrice',
      'PmID': PmID,
      'HptCode': HptCode,
      'Userid': Userid
    };
    senddata(JSON.stringify(data));
  }

  function get_last_move() {
    last_move = new Date();
    return last_move;
  }

  function alert_RevealDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_RevealDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_RevealDep = 0;
        $('#row_RevealDep').empty();
        $('#i_RevealDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-end'> <button class='btn btn-info mr-5' onclick='showDetailReveal(\"" + value.DocNo + "\")'><?php echo $array['detail'][$language]; ?></button><button class='btn btn-success btn-block w-50' onclick='blinkShelfcount(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_RevealDep++;
            $('#row_RevealDep').append(StrTR);
          });
        }
        if(i_RevealDep ==0){
          $('#i_RevealDep').hide();
        }
        $('#i_RevealDep').text(i_RevealDep);
      }
    });
  }

  function alert_CallDirtyDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_CallDirtyDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        $('#row_CallDirtyDep').empty();
        var i_CallDirtyDep = 0;
        $('#i_CallDirtyDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-end'> <button class='btn btn-success btn-block w-50' onclick='blinkcallDirtyDep(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_CallDirtyDep++;
            $('#row_CallDirtyDep').append(StrTR);
          });
        }
        if(i_CallDirtyDep ==0){
          $('#i_CallDirtyDep').hide();
        }
        $('#i_CallDirtyDep').text(i_CallDirtyDep);
      }
    });
  }


  function alert_RequestPar() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_RequestPar',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_RequestDep = 0;
        $('#i_RequestPar').show();
        $('#row_RequestPar').empty();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-end'> <button class='btn btn-info mr-5' onclick='showDetailRequest(\"" + value.DocNo + "\")'><?php echo $array['detail'][$language]; ?></button> <button class='btn btn-success btn-block w-50' onclick='blinkparDep(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_RequestDep++;
            $('#row_RequestPar').append(StrTR);
          });
        }
        if(i_RequestDep ==0){
          $('#i_RequestPar').hide();
        }
        $('#i_RequestPar').text(i_RequestDep);
      }
    });
  }

  function alert_MoveDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_MoveDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_MoveDep = 0;
        $('#row_MoveDep').empty();
        $('#i_MoveDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<p class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</p>" +
              "<p class='h4'><?php echo $array['movedep'][$language]; ?> : " + value.DepCodeTo + "</p>" +
              "<span class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</span>" +
              "<div class='text-black-50 d-flex justify-content-end'><button class='btn btn-success btn-block w-50' onclick='blinkmoveDepartment(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_MoveDep++;
            $('#row_MoveDep').append(StrTR);
          });
        }
        if(i_MoveDep ==0){
          $('#i_MoveDep').hide();
        }
        $('#i_MoveDep').text(i_MoveDep);
      }
    });
  }

  function alert_OtherDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_OtherDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_OtherDep = 0;
        $('#row_OtherDep').empty();
        $('#i_OtherDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='row px-3 d-flex justify-content-end'> <button class='btn btn-info mr-5' onclick='showMessage(\"" + value.Message + "\")'><?php echo $array['detail'][$language]; ?></button> <button class='btn btn-success btn-block w-50' onclick='blinkotherDepartment(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_OtherDep++;
            $('#row_OtherDep').append(StrTR);
          });
        }
        if(i_OtherDep ==0){
          $('#i_OtherDep').hide();
        }
        $('#i_OtherDep').text(i_OtherDep);
      }
    });
  }

  function alert_ChatRoom() {
    var site = $("#selectSite").val();
    var PmID = '<?php echo $PmID; ?>';
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_ChatRoom',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        $('#row_ChatRoom').empty();
        $('#i_ChatRoom').show();
        var i_ChatRoom = 0;
        
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            if(value.CheckPm != PmID){
              StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='row px-3 d-flex justify-content-end'> <button class='btn btn-success btn-block w-50' onclick='blinkChatRoom(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

              i_ChatRoom++;

            $('#row_ChatRoom').append(StrTR);
            }


          });
        }

        if(i_ChatRoom ==0){
          $('#i_ChatRoom').hide();
        }
        $('#i_ChatRoom').text(i_ChatRoom);
      }
    });
  }

  function showMessage(Message) {
    $('#txtComment').val(Message);
    $('#modal_message').modal('toggle');


  }

  function showDetailReveal(DocNo) {
    $('#modal_reveal').modal('toggle');
    $.ajax({
      url: "../process/revealDep.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'showDetailDocument',
        'DocNo': DocNo,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {
            if (value.TotalQty == "0.00") {
              value.TotalQty = "";
            }
            var inputPar = "<input type='text' autocomplete='off' style='font-size:22px;' value='" + value.ParQty + "' disabled  class='form-control text-right w-50' id='txtSearch'>";
            var inputissu = "<input type='text' autocomplete='off' disabled style='font-size:22px;' placeholder='0' value='" + value.TotalQty + "' class='numonly form-control text-right w-50'  id='TotalQty_" + key + "' >";
            var inputitemCode = "<input type='text' hidden autocomplete='off' style='font-size:22px;' value='" + value.ItemCode + "'  class='form-control text-right w-50 loopitemcode' id='ItemCode_" + key + "'>";
            StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
              "<td  >" + (key + 1) + "</td>" +
              "<td  >" + value.ItemName + "</td>" +
              "<td  hidden>" + inputitemCode + "</td>" +
              "<td class='d-flex justify-content-center'>" + inputPar + "</td>" +
              "<td class='d-flex justify-content-center'>" + inputissu + "</td>" +
              "</tr>";
          });
          $('#tableReveal tbody').html(StrTR);
        }

        $('#tableReveal tbody').html(StrTR);

        $('.numonly').on('input', function() {
          this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
        });
      }
    });
  }

  function showDetailRequest(DocNo){
    $('#modal_request').modal('toggle');
    $.ajax({
        url: "../process/alert_menu.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDetailParDocument',
          'DocNo': DocNo,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {

              var inputPar = "<input type='text' autocomplete='off' style='font-size:22px;' value='" + value.ParQty + "' disabled  class='form-control text-right w-50' id='txtSearch'>";
              var inputissu = "<input type='text' autocomplete='off' style='font-size:22px;' disabled value='" + value.Qty + "' class='numonly form-control text-right w-50'  id='TotalQty_" + key + "' >";
              var inputitemCode = "<input type='text' hidden autocomplete='off' style='font-size:22px;' value='" + value.ItemCode + "'  class='form-control text-right w-50 loopitemcode' id='ItemCode_" + key + "'>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td  >" + (key + 1) + "</td>" +
                "<td  >" + value.ItemName + "</td>" +
                "<td  hidden>" + inputitemCode + "</td>" +
                "<td class='d-flex justify-content-center'>" + inputPar + "</td>" +
                "<td class='d-flex justify-content-center'>" + inputissu + "</td>" +
                "</tr>";
            });
            $('#tableRequest tbody').html(StrTR);
          }

          $('#tableRequest tbody').html(StrTR);

          $('.numonly').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
          });
        }
      });
    
  }
  

  function blinkotherDepartment(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkotherDepartment',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "otherDep.php?DocNo=" + DocNo + "";
        // alert_OtherDep();
      }
    });
  }

  function blinkmoveDepartment(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkmoveDepartment',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "moveDep.php?DocNo=" + DocNo + "";
        // alert_MoveDep();
      }
    });
  }

  function blinkcallDirtyDep(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkcallDirtyDep',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "callDirtyDep.php?DocNo=" + DocNo + "";
        // alert_CallDirtyDep();
      }
    });
  }

  function blinkShelfcount(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkShelfcount',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "shelfcount.php?DocNo=" + DocNo + "";
      }
    });

  }

  function blinkparDep(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkparDep',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "parDep.php?DocNo=" + DocNo + "";
      }
    });
  }

  function blinkChatRoom(DocNo){
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkChatRoom',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "chatRoom.php?DocNo=" + DocNo + "";
      }
    });
  }

  function GetSite() {

    var lang = '<?php echo $language; ?>';
    var PmID = '<?php echo $PmID; ?>';

    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'GetSite',
        'lang': lang,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);

        var option = `<option value="0" selected><?php echo $array['selecthospital'][$language]; ?></option>`;

        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(kay, value) {
            option += `<option value="${value.HptCode}">${value.HptName}</option>`;
          });
        } else {
          option = `<option value="0">Data not found</option>`;
        }

        $("#selectSite").html(option);
      }
    });
  }

  function senddata(data) {
    var form_data = new FormData();
    form_data.append("DATA", data);
    var URL = '../process/menu.php';
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
          if (temp["form"] == 'OnLoadPage') {} 
          else if (temp["form"] == 'alert_SetPrice') {
            $('#countRow').val(temp['countSetprice']);
            var PmID = <?php echo $PmID; ?>;
            var result = '<h1 class="modal-title" style="font-size:30px;color: rgb(0, 51, 141) "><?php echo $array["set"][$language]; ?></h1>';
            if (temp['countSetprice'] > 0) {
              for (var i = 0; i < temp['countSetprice']; i++) {
                result += '<table class="table table-fixed " cellspacing="0" role="grid">';
                result += '<tr style="background-color:#2980b9;color:#ffffff">' +
                  '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['side'][$language]; ?> ' + temp[i]['set_price']['hptlang'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['datestartcontract'][$language]; ?>: ' + temp[i]['set_price']['StartDate'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['set_price']['EndDate'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['docno'][$language]; ?>: ' + temp[i]['set_price']['DocNo'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['changprice'][$language]; ?>: ' + temp[i]['set_price']['xDate'] + ' <?php echo $array['Timeleft'][$language]; ?>  ' + temp[i]['set_price']['dateDiff'] + ' <?php echo $array['day'][$language]; ?></td>' +
                  '</tr></table><hr>';



                if (temp[i]['set_price']['cntAcive'] == 0) {
                  for (var m = 0; m < temp['countMail']; m++) {
                    var HptName = temp[i]['set_price']['HptName'];
                    var HptNameTH = temp[i]['set_price']['HptNameTH'];
                    var DocNo = temp[i]['set_price']['DocNo'];
                    var StartDate = temp[i]['set_price']['StartDate'];
                    var EndDate = temp[i]['set_price']['EndDate'];
                    var xDate = temp[i]['set_price']['xDate'];
                    var email = temp[m]['set_price']['email'];
                    var dateDiff = temp[i]['set_price']['dateDiff'];
                    var URL = '../process/sendMail_alertPrice.php';
                    $.ajax({
                      url: URL,
                      method: "POST",
                      data: {
                        HptName: HptName,
                        DocNo: DocNo,
                        StartDate: StartDate,
                        EndDate: EndDate,
                        xDate: xDate,
                        email: email,
                        dateDiff: dateDiff,
                        HptNameTH: HptNameTH
                      },
                      success: function(data) {
                        console.log['success'];
                      }
                    });
                  }

                }
              }
              $("#price").html(result);
              $("#alert").modal('show');
            }
            if (temp['countFac'] > 0) {
              var result2 = ' <h1 class="modal-title" style="font-size:30px;color: rgb(0, 51, 141) "><?php echo $array["confac"][$language]; ?></h1>';
              for (var i = 0; i < temp['countFac']; i++) {
                result2 += '<table class="table table-fixed" cellspacing="0" role="grid">';
                result2 += '<tr style="background-color:#2980b9;color:#ffffff">' +
                  '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['factory'][$language]; ?> ' + temp[i]['contract_fac']['hptlang'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['datestartcontract'][$language]; ?>: ' + temp[i]['contract_fac']['StartDate'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['contract_fac']['EndDate'] + '</td>' +
                  '</tr>' +
                  '<tr >' +
                  '<td style="width:18%;border-top:none!important;"></td>' +
                  '<td nowrap style="width:40%;border-top:none!important;" class="text-left"><?php echo $array['Timeleft'][$language]; ?> ' + temp[i]['contract_fac']['dateDiff'] + ' <?php echo $array['day'][$language]; ?></td>' +
                  '</tr></table><hr>';
                if (temp[i]['countMailFac'] > 0) {
                  for (var j = 0; j < temp[i]['countMailFac']; j++) {
                    var FacName = temp[i]['contract_fac']['FacName'];
                    var FacNameTH = temp[i]['contract_fac']['FacNameTH'];
                    var StartDate = temp[i]['contract_fac']['StartDate'];
                    var EndDate = temp[i]['contract_fac']['EndDate'];
                    var email = temp[j]['contract_fac']['email'];
                    var dateDiff = temp[i]['contract_fac']['dateDiff'];
                    var RowID = temp[i]['contract_fac']['RowID'];
                    if (temp[i]['contract_fac']['cntAcive'] == 0) {
                      var URL = '../process/sendMail_conFac.php';
                      $.ajax({
                        url: URL,
                        method: "POST",
                        data: {
                          FacName: FacName,
                          StartDate: StartDate,
                          EndDate: EndDate,
                          email: email,
                          dateDiff: dateDiff,
                          RowID: RowID,
                          FacNameTH: FacNameTH
                        },
                        success: function(data) {
                          console.log['success'];
                        }
                      });
                    }
                  }
                }
              }
              $("#confac").html(result2);
              $("#alert").modal('show');
            }
            if (temp['countHos'] > 0) {
              var result3 = ' <h1 class="modal-title" style="font-size:30px;color: rgb(0, 51, 141) "><?php echo $array["conhos"][$language]; ?></h1>';
              for (var i = 0; i < temp['countHos']; i++) {
                result3 += '<table class="table table-fixed" cellspacing="0" role="grid">';
                result3 += '<tr style="background-color:#2980b9;color:#ffffff">' +
                  '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['side'][$language]; ?> ' + temp[i]['contract_hos']['hptlang'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['datestartcontract'][$language]; ?>: ' + temp[i]['contract_hos']['StartDate'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['contract_hos']['EndDate'] + '</td>' +
                  '</tr>' +
                  '<tr >' +
                  '<td style="width:18%;border-top:none!important;"></td>' +
                  '<td nowrap style="width:40%;border-top:none!important;" class="text-left"><?php echo $array['Timeleft'][$language]; ?> ' + temp[i]['contract_hos']['dateDiff'] + ' <?php echo $array['day'][$language]; ?></td>' +
                  '</tr></table><hr>';

                if (temp[i]['countMailHos'] > 0) {
                  for (var j = 0; j < temp[i]['countMailHos']; j++) {
                    var HptName = temp[i]['contract_hos']['HptName'];
                    var HptNameTH = temp[i]['contract_hos']['HptNameTH'];
                    var StartDate = temp[i]['contract_hos']['StartDate'];
                    var EndDate = temp[i]['contract_hos']['EndDate'];
                    var email = temp[j]['contract_hos']['email'];
                    var dateDiff = temp[i]['contract_hos']['dateDiff'];
                    var RowID = temp[i]['contract_hos']['RowID'];
                    if (temp[i]['contract_hos']['cntAcive'] == 0) {
                      var URL = '../process/sendMail_conHos.php';
                      $.ajax({
                        url: URL,
                        method: "POST",
                        data: {
                          HptName: HptName,
                          StartDate: StartDate,
                          EndDate: EndDate,
                          email: email,
                          dateDiff: dateDiff,
                          RowID: RowID,
                          HptNameTH: HptNameTH
                        },
                        success: function(data) {
                          console.log['success'];
                        }
                      });
                    }
                  }
                }


              }
              $("#conhos").html(result3);
              $("#alert").modal('show');
            }
            if (temp['countpercent'] > 0) {
              for (var i = 0; i < temp['countpercent']; i++) {
                if (temp[i]['countMailpercent'] > 0) {
                  for (var j = 0; j < temp[i]['countMailpercent']; j++) {
                    var HptName = temp[0]['percent']['HptName'];
                    var HptNameTH = temp[0]['percent']['HptNameTH'];
                    var Total1 = temp[i]['percent']['Total1'];
                    var Total2 = temp[i]['percent']['Total2'];
                    var DocNoC = temp[i]['percent']['DocNoC'];
                    var DocNoD = temp[i]['percent']['DocNoD'];
                    var Precent = temp[i]['percent']['Precent'];
                    var email = temp[j]['percent']['email'];

                    var URL = '../process/sendMail_percent.php';
                    $.ajax({
                      url: URL,
                      method: "POST",
                      data: {
                        HptName: HptName,
                        Total1: Total1,
                        Total2: Total2,
                        Precent: Precent,
                        email: email,
                        DocNoC: DocNoC,
                        DocNoD: DocNoD,
                        HptNameTH: HptNameTH
                      },
                      success: function(data) {
                        console.log['success'];
                      }
                    });
                  }
                }


              }
            }
          }
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

</html>