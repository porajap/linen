<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  // header("location:../index.html");
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

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>
    color
  </title>
  <?php include_once('../assets/import/css.php'); ?>

</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active">color</li>
  </ol>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <div class="row">
        <div class="col-md-12">
          <div class="container-fluid">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
              <div class="row">
                <div class="col-md-8 off-set-10">
                  <div class="row" style="margin-left:5px;">
                    <select class="form-control col-md-4 " id="selectSite" style="font-size:22px;" onchange="changeSite('top')">
                    </select>
                    <input id="txtSearch" type="text" autocomplete="off" class="form-control col-md-4 ml-2" style="font-size:22px;">
                    <div class="search_custom col-md-2">
                      <div class="search_1 d-flex justify-content-start">
                        <button class="btn" onclick="showData()" id="bSave">
                          <i class="fas fa-search mr-2"></i>
                          <?php echo $array['search'][$language]; ?>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-12">
                  <table class="table table-fixed table-condensed table-striped mt-3" id="tableDocument" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:24px;">
                      <tr role="row" id='tr_1'>
                        <th nowrap ><br></th>
                        <th nowrap >ลำดับ</th>
                        <th nowrap >แม่สี</th>
                        <th nowrap >โค๊ดสี</th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
                <div class="menu mhee">
                  <div class="d-flex justify-content-center">
                    <div class="circle4 d-flex justify-content-center">
                      <button class="btn" onclick="saveData()" id="bSave">
                        <i class="fas fa-save"></i>
                        <div>
                          <?php echo $array['save'][$language]; ?>
                        </div>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="menu mhee">
                  <div class="d-flex justify-content-center">
                    <div class="circle6 d-flex justify-content-center">
                      <button class="btn" onclick="cleartxt()" id="bDelete">
                        <i class="fas fa-redo-alt"></i>
                        <div>
                          <?php echo $array['clear'][$language]; ?>
                        </div>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="menu ">
                  <div class="d-flex justify-content-center">
                    <div class="circle3 d-flex justify-content-center opacity" id="cancelIcon">
                      <button class="btn" onclick="deleteData()" id="bCancel" disabled>
                        <i class="fas fa-trash-alt"></i>
                        <div>
                          <?php echo $array['cancel'][$language]; ?>
                        </div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                    <?php echo $array['detail'][$language]; ?></a>
                </li>
              </ul>

              <div class="row mt-4">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label ">ลำดับ</label>
                    <input id="txtNumber" type="text" autocomplete="off" class="form-control col-sm-7 " disabled style="font-size:22px;">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label ">แม่สี</label>
                    <input id="txtColor" type="text" autocomplete="off" class="form-control col-sm-7 enonly" style="font-size:22px;">
                    <label id="alert_txtColor" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
              </div>

              <div class="row ">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label ">โค้ดสี</label>
                    <input id="txtCodeColor" type="text" autocomplete="off" class="form-control col-sm-7 " style="font-size:22px;">
                    <label id="alert_txtCodeColor" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div> <!-- tag column 2 -->



  <?php include_once('../assets/import/js.php'); ?>
  <script type="text/javascript">
    $(document).ready(function(e) {

      $("#alert_txtColor").hide();
      $("#alert_txtCodeColor").hide();



      $("#txtColor").change(function() {
        $("#txtColor").removeClass("border-danger");
        $("#alert_txtColor").hide();
      });

      $("#txtCodeColor").change(function() {
        $("#txtCodeColor").removeClass("border-danger");
        $("#alert_txtCodeColor").hide();
      });


      setTimeout(() => {
        showData();
      }, 200);


      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
      });

      $('.enonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9. ]/g, ''); //<-- replace all other than given set of values
      });

      $('.thonly').on('input', function() {
        this.value = this.value.replace(/[^ก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ0-9. ]/g, ''); //<-- replace all other than given set of values
      });


    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });


    function saveData() {
      var txtNumber = $("#txtNumber").val();
      var selectSiteLow = $("#selectSiteLow").val();
      var txtNameEn = $("#txtNameEn").val();
      var txtNameTh = $("#txtNameTh").val();
      var txtAddress = $("#txtAddress").val();
      var txtPhoneNumber = $("#txtPhoneNumber").val();

      if (selectSiteLow == "0") {
        swal({
          title: '',
          text: 'กรุณาเลือกโรงพยาบาล',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#selectSiteLow").addClass("border-danger");
        $("#alert_selectSiteLow").show();
        return;
      }

      if (txtNameEn == "") {
        swal({
          title: '',
          text: 'กรุณากรอกชื่อบริษัทภาษาอังกฤษ',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtNameEn").addClass("border-danger");
        $("#alert_txtNameEn").show();
        return;
      }

      if (txtAddress == "") {
        swal({
          title: '',
          text: 'กรุณากรอกที่อยู่',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtAddress").addClass("border-danger");
        $("#alert_txtAddress").show();
        return;
      }

      if (txtNameTh == "") {
        swal({
          title: '',
          text: 'กรุณากรอกชื่อบริษัทภาษาไทย',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtNameTh").addClass("border-danger");
        $("#alert_txtNameTh").show();
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



      $.ajax({
        url: "../process/supplier.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'saveData',
          'txtNumber': txtNumber,
          'selectSiteLow': selectSiteLow,
          'txtNameEn': txtNameEn,
          'txtNameTh': txtNameTh,
          'txtAddress': txtAddress,
          'txtPhoneNumber': txtPhoneNumber,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $("#txtNumber").val("");
          $("#txtNameEn").val("");
          $("#txtNameTh").val("");
          $("#txtAddress").val("");
          $("#txtPhoneNumber").val("");
          $('#bCancel').attr('disabled', true);
          $('#cancelIcon').addClass('opacity');

          swal({
            title: '',
            text: '<?php echo $array['savesuccess'][$language]; ?>',
            type: 'success',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          });

          setTimeout(() => {
            showData();
          }, 1700);


        }
      });

    }

    function cleartxt() {
      $("#txtNumber").val("");
      $("#txtNameEn").val("");
      $("#txtNameTh").val("");
      $("#txtAddress").val("");
      $("#txtPhoneNumber").val("");
      $(".classSupplier").prop("checked", false);
      $('#bCancel').attr('disabled', true);
      $('#cancelIcon').addClass('opacity');
    }

    function showData() {
      var txtSearch = $("#txtSearch").val();

      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showData',
          'txtSearch': txtSearch,

        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {

              var chkDoc = "<label class='radio' style='margin-top:7px'><input type='radio' class='classSupplier' name='idSupplier' id='idSupplier_" + key + "' value='" + value.id + "' onclick='showDetail(\"" + value.id + "\" , \"" + key + "\")' ><span class='checkmark'></span></label>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td>" + chkDoc + "</td>" +
                "<td>" + (key + 1) + "</td>" +
                "<td>" + value.colorName + "</td>" +
                "<td> " + value.colorCode + " </td>" +
                "</tr>";
            });
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function deleteData() {
      var txtNumber = $("#txtNumber").val();

      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['canceldata1'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
        cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        if (result.value) {

          $.ajax({
            url: "../process/color.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'deleteData',
              'txtNumber': txtNumber,
            },
            success: function(result) {

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
              })

              setTimeout(() => {
                showData();
                cleartxt();
              }, 1700);

            }
          });



        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })



    }

    function showDetail(id, row) {
      var previousValue = $('#idSupplier_' + row).attr('previousValue');
      var name = $('#idSupplier_' + row).attr('name');
      if (previousValue == 'checked') {
        $('#idSupplier_' + row).removeAttr('checked');
        $('#idSupplier_' + row).attr('previousValue', false);
        $('#idSupplier_' + row).prop('checked', false);
        $('#bCancel').attr('disabled', true);
        $('#cancelIcon').addClass('opacity');

        $("#txtNumber").val("");
        // $("#selectSiteLow").val("");
        $("#txtNameEn").val("");
        $("#txtNameTh").val("");
        $("#txtAddress").val("");
        $("#txtPhoneNumber").val("");
      } else {
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('#idSupplier_' + row).attr('previousValue', 'checked');
        $('#bCancel').attr('disabled', false);
        $('#cancelIcon').removeClass('opacity');

        $.ajax({
          url: "../process/supplier.php",
          type: 'POST',
          data: {
            'FUNC_NAME': 'showDetail',
            'id': id,
          },
          success: function(result) {
            var ObjData = JSON.parse(result);
            if (!$.isEmptyObject(ObjData)) {
              $.each(ObjData, function(key, value) {

                $("#txtNumber").val(value.id);
                $("#selectSiteLow").val(value.site);
                $("#txtNameEn").val(value.name_En);
                $("#txtNameTh").val(value.name_Th);
                $("#txtAddress").val(value.address);
                $("#txtPhoneNumber").val(value.phoneNumber);


              });
            }
          }
        });
      }
    }
  </script>

</body>

</html>