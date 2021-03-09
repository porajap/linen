<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

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
    <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][19][$language]; ?></li>
  </ol>

  <div class="col-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tab_head1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab_head1" aria-selected="true"><?php echo $array2['menu']['general']['sub'][19][$language]; ?> </a>
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
                    <select class="form-control col-sm-7 icon_select" style="font-size:22px;" id="selectDep"></select>
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
                      <?php echo $array['time'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  only1" disabled="true" id="txtTime" placeholder="<?php echo $array['time'][$language]; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['setcount'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1" disabled="true" id="txtCount" value="Extra" placeholder="<?php echo $array['employee'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['settime'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1" disabled="true" id="txtExpress" value="Extra" placeholder="<?php echo $array['employee'][$language]; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      <?php echo $array['barcode'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" id="txtBarCode" disabled="true" style="font-size:22px;" class="form-control col-sm-7 " placeholder="<?php echo $array['barcode'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      Process
                    </label>
                    <input type="text" id="txtStatus" style="font-size:22px;" class="form-control col-sm-7   only1" disabled="true">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                    <?php echo $array['deprequester'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" id="txtName" style="font-size:22px;" class="form-control col-sm-7 " placeholder="<?php echo $array['deprequester'][$language]; ?>">
                    <label id="alert_txtName" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                    <?php echo $array['phone'][$language]; ?>
                    </label>
                    <input type="text" autocomplete="off" id="txtPhoneNumber" style="font-size:22px;" class="form-control col-sm-7 numonly" placeholder="<?php echo $array['phone'][$language]; ?>" maxlength="10">
                    <label id="alert_txtPhoneNumber" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
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
                    <?php echo $array['save'][$language]; ?>
                  </div>

                </button>
              </div>
            </div>
          </div>

          <div class="" id="hover_cancel">
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

          </div>

        </div>


        <div class="row">
          <div class="col-md-12">
            <table class="table table-fixed table-condensed table-striped mt-3" id="tableItem" width="100%" cellspacing="0" role="grid">
              <thead id="theadsum" style="font-size:24px;">
                <tr role="row" id='tr_1'>
                  <th nowrap style="width:10%"><?php echo $array['sn'][$language]; ?></th>
                  <th nowrap style="width:30%"><?php echo $array['item'][$language]; ?></th>
                  <th nowrap style="width:20%"> <center><?php echo $array['parsc'][$language]; ?></center> </th>
                  <th nowrap style="width:20%"><center>Shelfcount</center> </th>
                  <th nowrap style="width:20%"><center>Issue</center> </th>
                </tr>
              </thead>
              <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:630px;"></tbody>
            </table>
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
                <option value="2">Department Save</option>
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
                  <th nowrap style="width:14%;">
                    <center><?php echo $array['docdate'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:14%;">
                    <center><?php echo $array['docno'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:15%;">
                    <center><?php echo $array['department'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:15%;">
                    <center><?php echo $array['employee'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:10%;">
                    <center><?php echo $array['time'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:10%;">
                    <center><?php echo $array['setcount'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:10%;">
                    <center><?php echo $array['status'][$language]; ?></center>
                  </th>
                  <th nowrap style="width:10%;">
                    <center><?php echo $array['remask'][$language]; ?></center>
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

  
  <div class="modal fade" id="modal_remark" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Remark</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" class="form-control" style="font-size:22px;" disabled id="txtRemark">
              <input type="text" class="form-control" style="font-size:22px;" id="txtDocNoHidden" hidden>
            </div>
          </div>
        </div>
      </div>
    </div>


  <div class="modal fade" id="modal_comment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" style="font-size:22px;" id="txtComment">
            <input type="text" class="form-control" style="font-size:22px;" id="txtDocNoHidden" hidden>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">ปิด</button>
          <button type="button" id="btnCancelComment" class="btn btn-danger w-100" onclick="saveComment();">ยกเลิกเอกสาร</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_phone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">เบอร์โทรศัพท์</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" style="font-size:22px;" id="txtModalPhone" disabled>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php include_once('../assets/import/js.php'); ?>

  <script type="text/javascript">

    var alertTime = 0;
    $(document).ready(function(e) {
      GetSite();
      GetDep();
      showDocument();
      $("#alert_txtName").hide();
      $("#alert_txtLastName").hide();
      $("#alert_txtPhoneNumber").hide();

      $("#txtPhoneNumber").change(function() {
        $("#txtPhoneNumber").removeClass("border-danger");
        $("#alert_txtPhoneNumber").hide();
      });

      $("#txtName").change(function() {
        $("#txtName").removeClass("border-danger");
        $("#alert_txtName").hide();
      });

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
      });

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    function GetSite() {

      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/revealDep.php",
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
        url: "../process/revealDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetDep',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);

          if (PmID == 1) {
            var option = `<option value="0" selected><?php echo $array['selectdepartment'][$language]; ?></option>`;
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
      var txtName = $("#txtName").val();
      var txtPhoneNumber = $("#txtPhoneNumber").val();

      if (txtName == "") {
        swal({
          title: '',
          text: 'กรุณากรอกชื่อผู้ขอเบิก',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtName").addClass("border-danger");
        $("#alert_txtName").show();
        return;
      }
      if (txtPhoneNumber == "") {
        swal({
          title: '',
          text: 'กรุณากรอกเบอร์โทรศัพท์',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtPhoneNumber").addClass("border-danger");
        $("#alert_txtPhoneNumber").show();
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
              url: "../process/revealDep.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'createDocument',
                'PmID': PmID,
                'Site': Site,
                'txtName': txtName,
                'txtPhoneNumber': txtPhoneNumber,
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
                $('#txtTime').val(ObjData.txtTime);
                $('#txtStatus').val("on Process");
                alertTime = ObjData.alertTime;

                $("#btn_save").attr('disabled', false);
                $('#hover_save').addClass("mhee");
                $("#btn_cancel").attr('disabled', false);
                $('#hover_cancel').addClass("mhee");
                setTimeout(() => {}, 1000);
                showDocument();
                insertDocDetail();
              }
            });
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }
    }

    function insertDocDetail() {
      var Site = $("#selectSite").val();
      var DocNo = $("#txtDocNo").val();
      var PmID = '<?php echo $PmID; ?>';
      $.ajax({
        url: "../process/revealDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'insertDocDetail',
          'PmID': PmID,
          'Site': Site,
          'DocNo': DocNo,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {

              var inputPar = "<input type='text' autocomplete='off' style='font-size:22px;' value='" + value.ParQty + "' disabled  class='form-control text-right w-50' id='txtSearch'>";
              var inputShelfcount = "<input type='text' autocomplete='off' style='font-size:22px;' placeholder='0' class='numonly form-control text-right w-50' id='CcQty_" + key + "'>";
              var inputissu = "<input type='text' disabled autocomplete='off' style='font-size:22px;' placeholder='0' class='numonly form-control text-right w-50' id='TotalQty_" + key + "'>";
              var inputitemCode = "<input type='text' hidden autocomplete='off' style='font-size:22px;' value='" + value.ItemCode + "'  class='form-control text-right w-50 loopitemcode' id='ItemCode_" + key + "'>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td  style='width:10%;'>" + (key + 1) + "</td>" +
                "<td  style='width:30%;'>" + value.ItemName + "</td>" +
                "<td  hidden>" + inputitemCode + "</td>" +
                "<td  style='width:20%;' class='d-flex justify-content-center'>" + inputPar + "</td>" +
                "<td  style='width:20%;' class='d-flex justify-content-center'>" + inputShelfcount + "</td>" +
                "<td  style='width:20%;' class='d-flex justify-content-center'>" + inputissu + "</td>" +
                "</tr>";


            });


            $('#tableItem tbody').html(StrTR);

          }
          $('#tableItem tbody').html(StrTR);

          $('.numonly').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
          });
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
        url: "../process/revealDep.php",
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
              if (value.isStatus == 3 || value.isStatus == 4) {
                var txtStatus = 'compelted';
                var txtStyle = 'color: #20B80E;';
              } else if (value.isStatus == 2 || (value.isStatus == 0 && value.statusDepartment == 1)) {
                var txtStatus = 'Department Save';
                var txtStyle = 'color: #3399ff;';
              } else if (value.isStatus == 9) {
                var txtStatus = 'cancel';
                var txtStyle = 'color: #ff0000;';
              } else {
                var txtStatus = 'on Process';
                var txtStyle = 'color: #3399ff;';
              }
              var btn_Remark = "";
              if (value.remark == null) {
                value.remark = "";
              }
              if(value.remark != ""){
                var btn_Remark = "<button class='btn btn-info btn-block ' onclick='showRemark(\"" + value.remark + "\")'>Remark</button>";
              }

              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td class='text-center'   style='width:2%;'>" + chkDoc + "</td>" +
                "<td class='text-center'  style='width:14%;'>" + value.DocDate + "</td>" +
                "<td class='text-center'  style='width:14%;'>" + value.DocNo + "</td>" +
                "<td class='text-center'  style='width:15%;'>" + value.DepName + "</td>" +
                "<td class='text-center'  style='width:2%;'> <i class='fas fa-phone-square text-left' style='cursor: pointer;'onclick='showPhone(\"" + value.phoneNumber + "\")'></i></td>" +
                "<td class='text-center'  style='width:13%;'>"+ value.ThName + "  </td>" +
                "<td class='text-center'  style='width:10%;'>" + value.xTime + "</td>" +
                "<td class='text-center'  style='width:10%;'>Extra</td>" +
                "<td class='text-center'  style='width:10%; " + txtStyle + " '>" + txtStatus + "</td>" +
                "<td class='text-center'  style='width:10%;'>"+btn_Remark+"</td>" +
                "</tr>";
            });
            $('#tableDocument tbody').html(StrTR);
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function showRemark(remark){
      if(remark == "null"){
        remark = "";
      }
      $('#txtRemark').val(remark);
      $('#modal_remark').modal('toggle');
      
    }

    function showPhone(phone) {

      $('#txtModalPhone').val(phone);
      $('#modal_phone').modal('show');


    }

    function saveDocument() {
      var i = 0;
      var itemcode = "";
      var itemQty = "";
      var queryUpdate = "";
      var DocNo = $("#txtDocNo").val();
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
          $(".loopitemcode").each(function(key, value) {


            itemcode = ($('#ItemCode_' + i).val());
            itemQty = ($('#CcQty_' + i).val());

            i++;

            if(itemQty == ""){
              itemQty = 0;
            }
            
            queryUpdate += "UPDATE shelfcount_detail SET CcQty = '" + itemQty + "' WHERE ItemCode = '" + itemcode + "' AND DocNo = '" + DocNo + "'; ";
          });
          queryUpdate += "UPDATE shelfcount SET isStatus = '2' , alertTime = NOW() WHERE  DocNo = '" + DocNo + "'; ";
          queryUpdate += "UPDATE shelfcount SET alertTime = DATE_ADD( shelfcount.alertTime, INTERVAL "+alertTime+" MINUTE ) WHERE  DocNo = '" + DocNo + "'; ";

          console.log(queryUpdate);
          $.ajax({
            url: "../process/revealDep.php",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
              'FUNC_NAME': 'saveDocument',
              'queryUpdate': queryUpdate,
              'DocNo': DocNo,
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
                $('#txtTime').val("");
                $('#txtName').val("");
                $('#txtPhoneNumber').val("");
                $('#tableItem tbody').html("");
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

    function selectDocument() {
      var DocNo = "";
      $("#checkdocno:checked").each(function() {
        DocNo = $(this).val();
      });

      $.ajax({
        url: "../process/revealDep.php",
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
              $('#txtCreate').val(value.FName);
              $('#txtTime').val(value.xTime);
              $('#txtName').val(value.revealName);
              $("#txtPhoneNumber").val(value.phoneNumber);



              if (value.IsStatus == 3) {
                $('#txtStatus').val("completed");
                $("#btn_cancel").attr('disabled', true);
                $('#hover_cancel').removeClass("mhee");
                $("#btn_save").attr('disabled', true);
                $('#hover_save').removeClass("mhee");
              } else if (value.IsStatus == 2 || value.IsStatus == 0 ) {
                $('#txtStatus').val("Department Save");
                // ปิดปุ่ม
                if(value.statusDepartment == 0){
                  $("#btn_save").attr('disabled', false);
                  $('#hover_save').addClass("mhee");
                }else{
                  $("#btn_save").attr('disabled', true);
                  $('#hover_save').removeClass("mhee");
                }


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

            showDetailDocument(DocNo);


          }


        }
      });

    }

    function showDetailDocument(DocNo) {
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
              var inputShelfcount = "<input type='text' autocomplete='off' style='font-size:22px;' value='" + value.CcQty + "' placeholder='0' class='numonly form-control text-right w-50' id='CcQty_" + key + "'>";
              var inputissu = "<input type='text' disabled autocomplete='off' style='font-size:22px;' value='" + value.TotalQty + "' placeholder='0'  class='numonly form-control text-right w-50'  id='TotalQty_" + key + "' >";
              var inputitemCode = "<input type='text' hidden autocomplete='off' style='font-size:22px;' value='" + value.ItemCode + "'  class='form-control text-right w-50 loopitemcode' id='ItemCode_" + key + "'>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td  style='width:10%;'>" + (key + 1) + "</td>" +
                "<td  style='width:30%;'>" + value.ItemName + "</td>" +
                "<td  hidden>" + inputitemCode + "</td>" +
                "<td  style='width:20%;' class='d-flex justify-content-center'>" + inputPar + "</td>" +
                "<td  style='width:20%;' class='d-flex justify-content-center'>" + inputShelfcount + "</td>" +
                "<td  style='width:20%;' class='d-flex justify-content-center'>" + inputissu + "</td>" +
                "</tr>";
            });
            $('#tableItem tbody').html(StrTR);
          }

            $('#tableItem tbody').html(StrTR);

          $('.numonly').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
          });
        }
      });
    }

    function cancelDocment() {
      var DocNo = $("#txtDocNo").val();
      swal({
        title: "<?php echo $array['confirmcancel'][$language]; ?>",
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

          $('#modal_comment').modal('show');

        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function saveComment() {
      $('#modal_comment').modal('toggle');
      var comment = $("#txtComment").val();
      var DocNo = $("#txtDocNo").val();
      $.ajax({
        url: "../process/revealDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'cancelDocment',
          'DocNo': DocNo,
          'comment': comment,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $.each(ObjData, function(key, value) {
            if (value.statusDepartment == 1) {
              swal({
                title: '',
                text: 'ไม่สามารถยกเลิกเอกสารได้ เนื่องจากห้องผ้ากดรับเอกสารแล้ว',
                type: 'warning',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });
            } else {
              $('#tableItem tbody').empty();
              $('#txtDocNo').val("");
              $('#txtDocDate').val("");
              $('#txtCreate').val("");
              $('#txtTime').val("");
              $('#txtStatus').val("");
              $('#txtName').val("");
              showDocument();
              $('#tab_head2').tab('show');
            }
          });


        }
      });
    }
  </script>



</body>

</html>