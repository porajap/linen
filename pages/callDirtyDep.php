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
    <li class="breadcrumb-item active">เรียกเก็บผ้าสกปรก</li>
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
                  <select class="form-control col-sm-7 icon_select " style="font-size:22px;" id="selectSite"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    <?php echo $array['department'][$language]; ?>
                  </label>
                  <select class="form-control col-sm-7 icon_select " style="font-size:22px;" id="selectDep"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    เรียกเก็บผ้าสกปรก
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " id="txtName" placeholder="กรุณากรอกชื่อ">
                  <label id="alert_txtName" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 d-flex justify-content-end pr-5">
                <div class="menuMini mhee1">
                  <div class="circle4 d-flex justify-content-start">
                    <button class="btn" onclick="createDocument();">
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
                    วันที่เริ่มต้น
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here col-sm-7" id="txtsDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    วันที่สิ้นสุด
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here col-sm-7" id="txteDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    ค้นหาเลขที่เอกสาร
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " id="txtDocDate" placeholder="ค้นหาเลขที่เอกสาร">
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
              <th nowrap style="width:15%;">วันที่สร้างเอกสาร</th>
              <th nowrap style="width:15%;">เลขที่เอกสาร</th>
              <th nowrap style="width:10%;">ผู้เรียกเก็บผ้า</th>
              <th nowrap style="width:10%;">แผนกต้นทาง</th>
              <th nowrap style="width:10%;">เวลาเรียกเก็บ</th>
              <th nowrap style="width:10%;">ผู้ตอบรับ</th>
              <th nowrap style="width:10%;">ห้องผ้าตอบรับ</th>
              <th nowrap style="width:10%;">สถานะ</th>
              <th nowrap style="width:10%;">ยกเลิก</th>

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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">ปิด</button>
          <button type="button" id="btnCancelComment" class="btn btn-danger w-100" onclick="saveComment();">ยกเลิกเอกสาร</button>
        </div>
      </div>
    </div>
  </div>


  <?php include_once('../assets/import/js.php'); ?>
  <script type="text/javascript">
    $(document).ready(function(e) {
      GetSite();
      GetDep();
      $("#alert_txtName").hide();

      $("#txtName").change(function() {
        $("#txtName").removeClass("border-danger");
        $("#alert_txtName").hide();
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
        url: "../process/callDirtyDep.php",
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
        url: "../process/callDirtyDep.php",
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
      if (txtName == "") {
        swal({
          title: '',
          text: 'กรุณากรอกชื่อผู้เรียกเก็บผ้าสกปรก',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtName").addClass("border-danger");
        $("#alert_txtName").show();
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
              url: "../process/callDirtyDep.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'createDocument',
                'PmID': PmID,
                'Site': Site,
                'txtName': txtName,
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
                showDocument();


              }
            });
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }
    }

    function showDocument() {
      var sDate = $("#txtsDate").val();
      var eDate = $("#txteDate").val();
      var lang = '<?php echo $language; ?>';

      if (lang == 'th') {
        sDate = sDate.substring(6, 10) - 543 + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
        eDate = eDate.substring(6, 10) - 543 + "-" + eDate.substring(3, 5) + "-" + eDate.substring(0, 2);
      } else if (lang == 'en') {
        sDate = sDate.substring(6, 10) + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
        eDate = eDate.substring(6, 10) - 543 + "-" + eDate.substring(3, 5) + "-" + eDate.substring(0, 2);
      }

      $.ajax({
        url: "../process/callDirtyDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDocument',
          'sDate': sDate,
          'eDate': eDate,
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
              var btn = "<button class='btn btn-danger btn-block' onclick='cancelDocument(\"" + value.DocNo + "\",\"" + value.commentDelete + "\",\"" + value.IsStatus + "\")'><i class='fas fa-trash-alt mt-2'></i></button>";

              if (value.IsStatus == 0) {
                value.IsStatus = "on process";
              } else if(value.IsStatus == 9) {
                value.IsStatus = "cancel";
              }else{
                value.IsStatus = "completed";
              }

              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td  style='width:15%;'>" + value.DocDate + "</td>" +
                "<td   style='width:15%;'>" + value.DocNo + "</td>" +
                "<td  style='width:10%;'>" + value.revealName + "</td>" +
                "<td  style='width:10%;'>" + value.DepName + "</td>" +
                "<td style='width:10%;'>" + value.revealDate + "</td>" +
                "<td  style='width:10%;'>" + value.approveName + "</td>" +
                "<td  style='width:10%;'>" + value.approveDate + "</td>" +
                "<td  style='width:10%;'>" + value.IsStatus + "</td>" +
                "<td  style='width:10%;'>" + btn + "</td>" +
                "</tr>";
            });
            $('#tableDocument tbody').html(StrTR);
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function cancelDocument(DocNo,commentDelete,IsStatus) {
      if(commentDelete == "null"){
        commentDelete = "";
      }

      if(IsStatus == 9){
        $('#btnCancelComment').attr('disabled', true);
      }else{
        $('#btnCancelComment').attr('disabled', false);
      }

      $('#txtComment').val(commentDelete);
      $('#txtDocNoHidden').val(DocNo);     
      $('#modal_comment').modal('show');
    }

    function saveComment(){
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
            url: "../process/callDirtyDep.php",
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

  </script>
</body>

</html>