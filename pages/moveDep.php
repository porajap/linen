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
    <li class="breadcrumb-item"><a href="javascript:void(0)">Create status</a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][21][$language]; ?></li>
  </ol>

  <div class="col-12">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    <?php echo $array['side'][$language]; ?>
                  </label>
                  <select class="form-control col-sm-7 icon_select " id="selectSite" style="font-size:22px;"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    <?php echo $array['department'][$language]; ?>
                  </label>
                  <select class="form-control col-sm-7 icon_select " id="selectDep" style="font-size:22px;"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['movedep'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  " id="txtDepCodeto" placeholder="<?php echo $array['plsmovedep'][$language]; ?>">
                  <label id="alert_DepCodeTo" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['signdep'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  " id="txtName" placeholder="<?php echo $array['signdep'][$language]; ?>">
                  <label id="alert_txtName" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['phone'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 numonly " id="txtPhoneNumber" placeholder="<?php echo $array['phone'][$language]; ?>" maxlength="10">
                  <label id="alert_txtPhoneNumber" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                </div>
              </div>
            </div>
            <!-- <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['remask'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 " id="txtRemark" placeholder="<?php echo $array['remask'][$language]; ?>" >
                </div>
              </div>
            </div> -->
            <div class="row" id="btn_save">
              <div class="col-md-12 d-flex justify-content-end pr-5">
                <div class="menuMini mhee1">
                  <div class="circle4 d-flex justify-content-start">
                    <button class="btn" onclick="createDocument()">
                      <i class="fas fa-save mr-2 pl-2"></i>
                      <?php echo $array['save'][$language]; ?>
                    </button>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['datestart'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here col-sm-7" id="txtsDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['dateend'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here col-sm-7" id="txteDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                  <?php echo $array['searchplace'][$language]; ?>
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " id="txtSearchDoc" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 d-flex justify-content-end pr-5">
                <div class="menuMini mhee1">
                  <div class="search_1 d-flex justify-content-start">
                    <button class="btn" onclick="showDocument();">
                      <i class="fas fa-search mr-2 pl-2"></i>
                      <?php echo $array['search'][$language]; ?>
                    </button>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <table class="table table-fixed table-condensed table-striped mt-3" id="tableDocument" width="100%" cellspacing="0" role="grid">
          <thead id="theadsum" style="font-size:24px;">
            <tr role="row" id='tr_1'>
              <th nowrap style="width:10%;"><?php echo $array['creation'][$language]; ?></th>
              <th nowrap style="width:13%;"><?php echo $array['docno'][$language]; ?></th>
              <th nowrap style="width:10%;"><?php echo $array['userdep'][$language]; ?></th>
              <th nowrap style="width:10%;"><?php echo $array['dep'][$language]; ?></th>
              <th nowrap style="width:10%;"><?php echo $array['Destinationdep'][$language]; ?></th>
              <th nowrap style="width:10%;"><?php echo $array['timedep'][$language]; ?></th>
              <th nowrap style="width:10%;"><?php echo $array['userReply'][$language]; ?></th>
              <th nowrap style="width:10%;"><?php echo $array['userLinen'][$language]; ?></th>
              <th nowrap style="width:7%;"><?php echo $array['status'][$language]; ?></th>
              <!-- <th nowrap style="width:5%;"><?php echo $array['save'][$language]; ?></th> -->
              <th nowrap style="width:10%;"><?php echo $array['isno'][$language]; ?></th>

            </tr>
          </thead>
          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
          </tbody>
        </table>
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
        <div class="modal-footer" id="row_btn_comment">
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

  <div class="modal fade" id="modal_remark" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">หมายเหตุ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" style="font-size:22px;" id="txtModalRemark" disabled>
          </div>
        </div>
      </div>
    </div>
  </div>



  <?php include_once('../assets/import/js.php'); ?>

  <script type="text/javascript">
    $(document).ready(function(e) {
      var PmID = '<?php echo $PmID; ?>';
      if(PmID != 8){
        $("#txtPhoneNumber").attr('disabled',true);
        $("#txtDepCodeto").attr('disabled',true);
        $("#txtName").attr('disabled',true);
        $("#txtRemark").attr('disabled',true);
        $("#btn_save").attr('hidden', true);
      }
      GetSite();
      GetDep();
      showDocument();
      $("#txtName").change(function() {
        $("#txtName").removeClass("border-danger");
        $("#alert_txtName").hide();
      });

      $("#txtPhoneNumber").change(function() {
        $("#txtPhoneNumber").removeClass("border-danger");
        $("#alert_txtPhoneNumber").hide();
      });

      $("#txtDepCodeto").change(function() {
        $("#txtDepCodeto").removeClass("border-danger");
        $("#alert_DepCodeTo").hide();
      });

      $("#alert_txtName").hide();
      $("#alert_DepCodeTo").hide();
      $("#alert_txtPhoneNumber").hide();

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
        url: "../process/moveDep.php",
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
          }

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

    function GetDep() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/moveDep.php",
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
          }

          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.DepCode}">${value.DepName}</option>`;
            });
          } else {
            option = `<option value="0">Data not found</option>`;
          }

          $("#selectDep").html(option);
        }
      });
    }

    function createDocument() {
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var txtName = $("#txtName").val();
      var txtDepTo = $("#txtDepCodeto").val();
      var txtPhoneNumber = $("#txtPhoneNumber").val();
      var txtRemark = $("#txtRemark").val();

      if (txtDepTo == "") {
        swal({
          title: '',
          text: 'กรุณากรอกแผนกปลายทาง',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtDepCodeto").addClass("border-danger");
        $("#alert_DepCodeTo").show();
        return;
      }
      if (txtName == "") {
        swal({
          title: '',
          text: 'กรุณากรอกชื่อ',
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
              url: "../process/moveDep.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'createDocument',
                'PmID': PmID,
                'Site': Site,
                'txtName': txtName,
                'txtDepTo': txtDepTo,
                'txtPhoneNumber': txtPhoneNumber,
                'txtRemark': txtRemark,
              },
              success: function(result) {

                swal({
                  title: '',
                  text: '<?php echo $array['savesuccess'][$language]; ?>',
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1500,
                });
                $("#txtName").val("");
                $("#txtPhoneNumber").val("");
                $("#txtDepCodeto").val("");
                $("#txtRemark").val("");
                showDocument();


              }
            });
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }
    }

    function saveDocument(DocNo){
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

          $.ajax({
            url: "../process/moveDep.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'saveDocument',
              'DocNo': DocNo,
            },
            success: function(result) {

              swal({
                title: '',
                text: 'บันทึกเอกสารสำเร็จ',
                type: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });

              setTimeout(() => {
                showDocument();
              }, 1000);


            }
          });
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function showDocument() {
      var sDate = $("#txtsDate").val();
      var eDate = $("#txteDate").val();
      var txtSearchDoc = $("#txtSearchDoc").val();
      var lang = '<?php echo $language; ?>';

      if (lang == 'th') {
        sDate = sDate.substring(6, 10) - 543 + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
        eDate = eDate.substring(6, 10) - 543 + "-" + eDate.substring(3, 5) + "-" + eDate.substring(0, 2);
      } else if (lang == 'en') {
        sDate = sDate.substring(6, 10) + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
        eDate = eDate.substring(6, 10) + "-" + eDate.substring(3, 5) + "-" + eDate.substring(0, 2);
      }

      $.ajax({
        url: "../process/moveDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDocument',
          'sDate': sDate,
          'eDate': eDate,
          'txtSearchDoc': txtSearchDoc,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {

              if (value.approveName == null) {
                value.approveName = "";
              }

              if (value.approveDate == null) {
                value.approveDate = "";
              }

              var disabled = "";
              var btnhidden = "";
              if (value.IsStatus == 0) {
                var txtStyle = 'color: #3399ff;';
                value.IsStatus = "Department Save";
                var btn_color = "btn-danger";
                var btnhidden = "hidden";
              }else if (value.IsStatus == 1) {
                var txtStyle = 'color: #3399ff;';
                value.IsStatus = "on process";
                var btn_color = "btn-danger";
              } else if (value.IsStatus == 2) {
                var txtStyle = 'color: #20B80E;';
                value.IsStatus = "completed";
                var btn_color = "btn-danger";
                var btnhidden = "hidden";
              } else if (value.IsStatus == 9) {
                var txtStyle = 'color: #ff0000;';
                value.IsStatus = "cancel";
                var btn_color = "btn-danger";
                var btnhidden = "hidden";
              }
              var btn = "<button  class='btn " + btn_color + " btn-block' onclick='cancelDocument(\"" + value.DocNo + "\",\"" + value.commentDelete + "\",\"" + value.IsStatus + "\")'><i class='fas fa-trash-alt mt-2'></i></button>";
              var btnSave = "<button "+btnhidden+"  class='btn btn-success  btn-block' onclick='saveDocument(\"" + value.DocNo + "\")'><i class='fas fa-save mt-2'></i></button>";

              if (value.approveDate == '0000-00-00 00:00:00') {
                value.approveDate = "";
              }

              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td  style='width:10%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.DocDate + "'>" + value.DocDate + "</td>" +
                // "<td class='text-center'  style='width:2%;'> <i class='fas fa-envelope text-left' style='cursor: pointer;'onclick='showRemark(\"" + value.remark + "\")'></i></td>" +
                "<td   style='width:11%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.DocNo + "'>" + value.DocNo + "</td>" +
                "<td class='text-center'  style='width:4%;'> <i class='fas fa-phone-square text-left' style='cursor: pointer;'onclick='showPhone(\"" + value.phoneNumber + "\")'></i></td>" +
                "<td  style='width:8%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.revealName + "'> " + value.revealName + " </td>" +
                "<td  style='width:10%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.DepName + "'>" + value.DepName + "</td>" +
                "<td style='width:10%;text-overflow: ellipsis;overflow: hidden;' nowrap title='" + value.DepCodeTo + "'>" + value.DepCodeTo + "</td>" +
                "<td  style='width:10%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.revealDate + "'>" + value.revealDate + "</td>" +
                "<td  style='width:10%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.approveName + "'>" + value.approveName + "</td>" +
                "<td  style='width:10%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + value.approveDate + "'>" + value.approveDate + "</td>" +
                "<td  style='width:7%;overflow: hidden; text-overflow: ellipsis;" + txtStyle + "'  nowrap title='" + value.IsStatus + "'>" + value.IsStatus + "</td>" +
                // "<td  style='width:5%;'>" + btnSave + "</td>" +
                "<td  style='width:10%;'>" + btn + "</td>" +
                "</tr>";
            });
            $('#tableDocument tbody').html(StrTR);
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function showRemark(remark){
      if(remark == 'null'){
        remark  = "";
      }
      $('#txtModalRemark').val(remark);
      $('#modal_remark').modal('show');
    }

    function cancelDocument(DocNo, commentDelete, IsStatus) {
      if (commentDelete == "null") {
        commentDelete = "";
      }

      if (IsStatus == "cancel") {

        $('#row_btn_comment').attr('hidden', true)
        $('#txtComment').attr('disabled', true);

      } else {
        $('#row_btn_comment').attr('hidden', false);
        $('#txtComment').attr('disabled', false);
      }

      $('#txtComment').val(commentDelete);
      $('#txtDocNoHidden').val(DocNo);
      $('#modal_comment').modal('show');
    }

    function saveComment() {
      var DocNo = $("#txtDocNoHidden").val();
      var comment = $("#txtComment").val();
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

          $.ajax({
            url: "../process/moveDep.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'saveComment',
              'DocNo': DocNo,
              'comment': comment,
            },
            success: function(result) {

              swal({
                title: '',
                text: 'ยกเลิกเอกสารสำเร็จ',
                type: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });

              setTimeout(() => {
                $('#modal_comment').modal('toggle');
                showDocument();
              }, 1000);


            }
          });
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function showPhone(phone) {

      $('#txtModalPhone').val(phone);
      $('#modal_phone').modal('show');


    }
  </script>


</body>

</html>