<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$DepCode = $_SESSION['DepCode'];
$Docnomenu = $_GET['DocNo'];
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requests</title>
  <?php include_once('../assets/import/css.php'); ?>


</head>

<body>

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Create Requests</a></li>
    <li class="breadcrumb-item active">Chatroom</li>
  </ol>

  <div class="col-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tab_head1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab_head1" aria-selected="true">Chatroom</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="tab_head2" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab_head2" aria-selected="false" onclick="showDocument()"><?php echo $array['search'][$language]; ?></a>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane show active fade" id="tab1" role="tabpanel" aria-labelledby="tab1">
        <div class="col-md-12">
          <div class="container-fluid">
            <div class="card-body mt-3">
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['side'][$language]; ?>
                    </label>
                    <select class="form-control col-sm-7 icon_select " style="font-size:22px;" id="selectSite"></select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['department'][$language]; ?>
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectDep"></select>
                    <label id="alert_selectDep" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['docdate'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " disabled="true" id="txtDocDate" placeholder="<?php echo $array['docdate'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['docno'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  only1" disabled="true" id="txtDocNo" placeholder="<?php echo $array['docno'][$language]; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['employee'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1" disabled="true" id="txtCreate" placeholder="<?php echo $array['employee'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                    <?php echo $array['depchat'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  " id="txtTopic" placeholder="<?php echo $array['depchat'][$language]; ?>">
                    <label id="alert_txtTopic" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class=" mt-3  d-flex justify-content-end col-12">
          <div class="mhee">
            <div class="d-flex justify-content-center" style="margin-right: 6rem!important;">
              <div class="circle1 d-flex justify-content-center" id="bCreate2">
                <button class="btn" onclick="createDocument()" id="bCreate">
                  <i class="fas fa-file-medical"></i>
                  <div>
                    <?php echo $array['createdocno'][$language]; ?>
                  </div>
                </button>
              </div>
            </div>
          </div>
          <div class="" id="hover_save">
            <div class="d-flex justify-content-center " style="margin-right: 6rem!important;">
              <div class="circle4 d-flex justify-content-center ">
                <button class="btn" id="btn_save" disabled="true" onclick="saveDocument();">
                  <i class="fas fa-save"></i>
                  <div>
                    <?php echo $array['closeChat'][$language]; ?>
                  </div>

                </button>
              </div>
            </div>
          </div>

          <!-- <div class="" id="hover_cancel">
            <div class="d-flex justify-content-center " style="margin-right: 6rem!important;">
              <div class="circle5 d-flex justify-content-center ">
                <button class="btn" id="btn_cancel" disabled="true" onclick="cancelDocment()">
                  <i class="fas fa-times"></i>
                  <div>
                    <?php echo $array['Canceldoc'][$language]; ?>
                  </div>
                </button>
              </div>
            </div>

          </div> -->

        </div>


        <div class="row">
          <div class="col-md-12 mt-2">
            <div class="card" style="min-height: 350px;max-height: 350px;">
              <div class="card-body">


              </div>
              <div class="message-box" id="message-scroll" style="overflow-y: scroll;">
                <ul class="list-group list-group-flush " id="message-list">
                  <!-- <li class="list-group-item text-right">mhee : สวัสดี </li>
                  <li class="list-group-item ">mhee : สวัสดี </li>
                  <li class="list-group-item text-right">mhee : สวัสดี </li>
                  <li class="list-group-item ">mhee : สวัสดี </li> -->
                </ul>
                <!-- <div class="info"></div> -->
              </div>

              <div class="card-footer">
                <div class="row">
                  <div class="col-md-10 col-sm-3">
                    <input type="text" class="form-control" id="txtPost" placeholder="กรอกข้อความ" autocomplete="off" style="font-size:22px;" />
                  </div>
                  <div class="col-md-2 col-sm-3">
                    <button type="submit" onclick="saveMessage()" class="btn btn-primary w-100" id="btn_submit">
                      ส่ง
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



      </div>

      <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
        <div class="row mt-3">
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" style="font-size:22px;" id="selectSearchSite">
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" style="font-size:22px;" id="selectSearchDep">
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here " id="txtsDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" style="font-size:22px;" id="selectSearchStatus">
                <option value="0"><?php echo $array['processchooce'][$language]; ?></option>
                <option value="1">on process</option>
                <option value="2">compelted</option>
                <option value="3">cancel </option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['search'][$language]; ?>" class="form-control" id="txtSearch">
            </div>
          </div>
          <div class="col-md-2">
            <div class="row">
              <div class="search_custom ">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn" onclick="showDocument();">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>
              <div class="search_custom ">
                <div class="circle11 d-flex justify-content-start">
                  <button class="btn" onclick="selectDocument();">
                    <i class="fas fa-paste mr-2 pt-1"></i>
                    <?php echo $array['show'][$language]; ?>
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <table class="table table-fixed table-condensed table-striped mt-3" id="tableDocument" width="100%" cellspacing="0" role="grid">
              <thead id="theadsum" style="font-size:24px;">
                <tr role="row" id='tr_1'>
                  <th nowrap style="width:2%;">
                    <br>
                  </th>
                  <th nowrap style="width:19%;">
                    <center><?php echo $array['docdate'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:19%;">
                    <center><?php echo $array['docno'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:20%;">
                    <center><?php echo $array['department'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:15%;">
                    <center><?php echo $array['employee'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:15%;">
                    <center><?php echo $array['time'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:10%;">
                    <center><?php echo $array['status'][$language]; ?></center>
                  </th>
                </tr>
              </thead>
              <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:630px;"></tbody>
            </table>
          </div>
        </div>





      </div>


    </div>

  </div>



  <?php include_once('../assets/import/js.php'); ?>

  <script type="text/javascript">
    var intervalId = setInterval(showMessage, 3000);
    var checkSum = 0;

    $(document).ready(function(e) {
      $(".select2").select2();
      $("#alert_txtTopic").hide();
      $("#alert_selectDep").hide();

      $("#message-scroll").scrollTop(10000000);
      // $("#message-scroll").scroll(function() {
      //   // clearInterval(intervalId);
      //   // console.log($("#message-scroll").scrollTop());
      // });

      $("#txtTopic").change(function() {
        $("#txtTopic").removeClass("border-danger");
        $("#alert_txtTopic").hide();
      });

      $("#selectDep").change(function() {
        $("#selectDep").removeClass("border-danger");
        $("#alert_selectDep").hide();
      });

      
      $('#txtPost').keyup(function(e) {
        if (e.keyCode == 13) {
          saveMessage();
        }
      });




      GetSite();
      GetDep();

      setTimeout(() => {
        showDocument();
      }, 500);
      var Docnomenu = "<?php echo $Docnomenu ?>";

      if (Docnomenu != "") {

        parent.chatRoomClick();

        $("#btn_save").attr('disabled', false);
        $('#hover_save').addClass("mhee");
        $('#opacity_save').removeClass("opacity");

        $("#btn_cancel").attr('disabled', false);
        $('#hover_cancel').addClass("mhee");
        $('#opacity_cancel').removeClass("opacity");

        $("#txtDocNo").val(Docnomenu);

        setTimeout(() => {
          selectDocument();
        }, 300);
      }

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });


    function GetSite() {

      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/chatRoom.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetSite',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);

          if (PmID == 1) {
            var option = `<option value="0" selected><?php echo $array['selecthospital'][$language]; ?></option>`;
          } else {
            var option = "";
            $('#selectSite').attr('disabled', true);
            $('#selectSite').addClass('icon_select');
            $('#selectSearchSite').attr('disabled', true);
            $('#selectSearchSite').addClass('icon_select');
          }

          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.HptCode}">${value.HptName}</option>`;
            });
          } else {
            option = `<option value="0">Data not found</option>`;
          }

          $("#selectSite").html(option);
          $("#selectSearchSite").html(option);
        }
      });
    }

    function GetDep() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/chatRoom.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetDep',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);

          if (PmID == 1 || PmID == 2 || PmID == 5 || PmID == 6) {
            var option = `<option value="0" selected><?php echo $array['selectdep'][$language]; ?></option>`;
          } else {
            var option = "";
            $('#selectDep').attr('disabled', true);
            $('#selectDep').addClass('icon_select');
            $('#selectSearchDep').attr('disabled', true);
            $('#selectSearchDep').addClass('icon_select');
          }

          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.DepCode}">${value.DepName}</option>`;
            });
          } else {
            option = `<option value="0">Data not found</option>`;
          }

          $("#selectDep").html(option);
          $("#selectSearchDep").html(option);
        }
      });
    }

    function createDocument() {
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var Dep = $("#selectDep").val();
      var txtName = $("#txtName").val();
      var txtTopic = $("#txtTopic").val();

      if (Dep == "0") {
        swal({
          title: '',
          text: 'กรุณาเลือกแผนก',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#selectDep").addClass("border-danger");
        $("#alert_selectDep").show();
        return;
      }

      if (txtTopic == "") {
        swal({
          title: '',
          text: 'กรุณากรอกหัวข้อแชท',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtTopic").addClass("border-danger");
        $("#alert_txtTopic").show();
        return;
      }


      if (Site == 0) {
        swal({
          title: '',
          text: 'กรุณาเลือกโรงพยาบาล',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
      } else {
        swal({
          title: "<?php echo $array['confirmdoc'][$language]; ?>",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
          cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true
        }).then(result => {
          if (result.value) {

            $.ajax({
              url: "../process/chatRoom.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'createDocument',
                'PmID': PmID,
                'Site': Site,
                'txtTopic': txtTopic,
                'Dep': Dep,
              },
              success: function(result) {



                var ObjData = JSON.parse(result);
                ObjData = ObjData[0];

                swal({
                title: "<?php echo $array['createdocno'][$language]; ?>",
                text: ObjData.txtDocNo + " <?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 1000,
                confirmButtonText: 'Ok',
                showConfirmButton: false
              });


                $('#txtDocNo').val(ObjData.txtDocNo);
                $('#txtDocDate').val(ObjData.txtDocDate);
                $('#txtCreate').val(ObjData.txtCreate);

                $("#btn_save").attr('disabled', false);
                $('#hover_save').addClass("mhee");
                $("#btn_cancel").attr('disabled', false);
                $('#hover_cancel').addClass("mhee");
                setTimeout(() => {}, 1000);
                showDocument();
              }
            });
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }
    }

    function showMessage() {
      var docNo = $('#txtDocNo').val();
      var depSession = '<?php echo $DepCode; ?>';
      var UseridSession = '<?php echo $Userid; ?>';
      var str = "";
      var i_chat = 0;
      clearInterval(intervalId);
      console.log(1);
      if (docNo == "") {

      } else {
        $.ajax({
          url: "../process/chatRoom.php",
          type: 'POST',
          data: {
            'FUNC_NAME': 'showMessage',
            'docNo': docNo,
          },
          success: function(result) {
            var ObjData = JSON.parse(result);

            if (!$.isEmptyObject(ObjData)) {
              $.each(ObjData, function(kay, value) {

                if (depSession == value.depCode) {
                  var right = 'text-right';
                } else {
                  var right = '';
                }

                str += "<li class='list-group-item "+right+" '><span> "+value.DepName+" : "+value.message+"</span></li>";
                // $("#message-list").append(
                //   '<li class="list-group-item  ' + right + ' "> ' + value.DepName + ' : ' + value.message + ' </li>'
                // );
                i_chat ++;
              });
              if(checkSum != i_chat){
                $("#message-list").html("");
                $("#message-list").append(str);
                $("#message-scroll").scrollTop(10000000);
              }
              checkSum = i_chat;

              console.log("checksum =" + checkSum);
              console.log("i_chat =" + i_chat);

            } else {}



          }
        });

        intervalId = setInterval(showMessage, 3000);
      }

    }

    function saveMessage() {
      var post = $("#txtPost").val();
      var docNo = $('#txtDocNo').val();
      // var Dep = $("#selectDep").val();
      var Dep = $('#selectDep option:selected').attr("value");

      if (docNo == "") {
        swal({
          title: '',
          text: 'กรุณาสร้างเอกสาร',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        return;
      }

      $.ajax({
        url: "../process/chatRoom.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'saveMessage',
          'post': post,
          'docNo': docNo,
          'Dep': Dep,
        },
        success: function(result) {
          $("#txtPost").val("");
          showMessage();

        }
      });

    }

    function showDocument() {
      var Site = $("#selectSearchSite").val();
      var Dep = $("#selectSearchDep").val();
      var sDate = $("#txtsDate").val();
      var status = $("#selectSearchStatus").val();
      var lang = '<?php echo $language; ?>';

      if (lang == 'th') {
        sDate = sDate.substring(6, 10) - 543 + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
      } else if (lang == 'en') {
        sDate = sDate.substring(6, 10) + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
      }

      $.ajax({
        url: "../process/chatRoom.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDocument',
          'Site': Site,
          'Dep': Dep,
          'sDate': sDate,
          'status': status,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {
              var chkDoc = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno'  value='" + value.DocNo + "' ><span class='checkmark'></span></label>";
              if (value.isStatus == 3) {
                var txtStatus = 'compelted';
                var txtStyle = 'color: #20B80E;';
              } else if (value.isStatus == 1) {
                var txtStatus = 'Department Save';
                var txtStyle = 'color: #20B80E;';
              } else if (value.isStatus == 2) {
                var txtStatus = 'compelted';
                var txtStyle = 'color: #20B80E;';
              } else if (value.isStatus == 9) {
                var txtStatus = 'cancel';
                var txtStyle = 'color: #ff0000;';
              } else {
                var txtStatus = 'on Process';
                var txtStyle = 'color: #3399ff;';
              }

              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td class='text-center'   style='width:2%;'>" + chkDoc + "</td>" +
                "<td class='text-center'  style='width:19%;'>" + value.DocDate + "</td>" +
                "<td class='text-center'  style='width:19%;'>" + value.DocNo + "</td>" +
                "<td class='text-center'  style='width:20%;'>" + value.DepName + "</td>" +
                "<td class='text-center'  style='width:15%;'>" + value.ThName + "  </td>" +
                "<td class='text-center'  style='width:15%;'>" + value.xTime + "</td>" +
                "<td class='text-center'  style='width:10%; " + txtStyle + " '>" + txtStatus + "</td>" +
                "</tr>";
            });
            $('#tableDocument tbody').html(StrTR);
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function selectDocument() {
      var DocNo = "";
      $("#checkdocno:checked").each(function() {
        DocNo = $(this).val();
      });

      var Docnomenu = "<?php echo $Docnomenu ?>";

      if (Docnomenu != "") {
        DocNo = Docnomenu;
      }

      $.ajax({
        url: "../process/chatRoom.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'selectDocument',
          'DocNo': DocNo,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {
              $('#txtDocNo').val(value.DocNo);
              $('#txtDocDate').val(value.DocDate);
              $('#selectDep').val(value.DepCode);
              $('.select2-selection__rendered').text(value.DepName);
              
              $('#txtCreate').val(value.FName);
              $('#txtTopic').val(value.Topic);

              if (value.IsStatus == 2) {
                $('#txtStatus').val("completed");
                $("#btn_cancel").attr('disabled', true);
                $('#hover_cancel').removeClass("mhee");
                $("#btn_save").attr('disabled', true);
                $('#hover_save').removeClass("mhee");
              } else if (value.IsStatus == 1) {
                $('#txtStatus').val("on Process");
                // ปิดปุ่ม
                $("#btn_save").attr('disabled', false);
                $('#hover_save').addClass("mhee");

                $("#btn_cancel").attr('disabled', false);
                $('#hover_cancel').addClass("mhee");
              } else if (value.IsStatus == 9) {
                $('#txtStatus').val("cancel");
                // ปิดปุ่ม
                $("#btn_save").attr('disabled', true);
                $('#hover_save').removeClass("mhee");
                $("#btn_cancel").attr('disabled', true);
                $('#hover_cancel').removeClass("mhee");
              } else {
                $('#txtStatus').val("on Process");
                // เปิดปุ่ม
                $("#btn_save").attr('disabled', false);
                $('#hover_save').addClass("mhee");
                $("#btn_cancel").attr('disabled', false);
                $('#hover_cancel').addClass("mhee");
              }

              $('#tab_head1').tab('show');
            });

            setInterval(showMessage, 3000);



          }


        }
      });

    }

    function saveDocument() {
      var i = 0;
      var queryUpdate = "";
      var DocNo = $("#txtDocNo").val();
      var Docnomenu = "<?php echo $Docnomenu ?>";
      var PmID = '<?php echo $PmID; ?>';

      swal({
        title: "<?php echo $array['confirmsave'][$language]; ?>",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
        cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        if (result.value) {

            queryUpdate += "UPDATE chat_room SET isStatus = '2'  WHERE  DocNo = '" + DocNo + "'; ";
          

          $.ajax({
            url: "../process/chatRoom.php",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
              'FUNC_NAME': 'saveDocument',
              'queryUpdate': queryUpdate,
            },
            success: function(result) {
              setTimeout(() => {
                swal({
                  title: '',
                  text: '<?php echo $array['savesuccess'][$language]; ?>',
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1500,
                });
                $('#txtDocNo').val("");
                $('#txtDocDate').val("");
                $('#txtCreate').val("");
                $('#txtTopic').val("");
                showDocument();
                $('#tab_head2').tab('show');
              }, 300);

            }
          });


        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }
  </script>



</body>

</html>