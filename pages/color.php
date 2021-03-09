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

  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">


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
                    <select class="form-control col-md-4 " id="select_color_master" style="font-size:22px;" onchange="showDataColor();">
                    </select>
                  </div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-12">
                  <table class="table table-fixed table-condensed table-striped mt-3" id="table_color" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:24px;">
                      <tr role="row" id='tr_1'>
                        <th style="width:8%"><br></th>
                        <th style="width:20%">ลำดับสี</th>
                        <th style="width:22%">กลุ่มสี</th>
                        <th style="width:20%;text-align: center;">สี</th>
                        <th style="width:30%">รหัสสี</th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
                <div class="menu mhee" id="div_bt_add">
                  <div class="d-flex justify-content-center">
                    <div class="circle4 d-flex justify-content-center">
                      <button class="btn" onclick="save_add_color();" id="bSave">
                        <i class="fas fa-save"></i>
                        <div>
                          <?php echo $array['save'][$language]; ?>
                        </div>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="menu mhee" id="div_bt_edit">
                  <div class="d-flex justify-content-center">
                    <div class="circle4 d-flex justify-content-center">
                      <button class="btn" onclick="save_edit_color();" id="bSave">
                        <i class="fas fa-save"></i>
                        <div>
                          <?php echo $array['save_edit'][$language]; ?>
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
                    <label class="col-sm-2 col-form-label ">กลุ่มสี</label>
                    <select class="form-control col-md-7 " id="select_color_master2" style="font-size:22px;" onchange="chk_color_master();">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-2 col-form-label ">รหัสสี</label>
                    <input class='form-control mt-2 ' id="color-picker" style="font-size:22px;" />
                    <input class='form-control mt-2 ' id="text_id_color_detail" style="font-size:22px;" hidden />
                    <label id="alert_txtColor" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
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
  <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(e) {

      $('#color-picker').spectrum({
        type: "component"
      });

      $('#div_bt_edit').hide();


      get_color_master();
      setTimeout(() => {
        showDataColor();
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


    function save_add_color() {
      var color_master = $("#select_color_master2").val();
      var color_code = $("#color-picker").val();



      if (color_master == "0") {
        swal({
          title: '',
          text: 'กรุณาเลือกกลุ่มสี',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });

        return;
      }



      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'save_add_color',
          'color_master': color_master,
          'color_code': color_code
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $("#select_color_master2").val("0");
          $("#color-picker").spectrum({
            color: "transparent"
          });

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
            showDataColor();
          }, 500);
          $('#bCancel').attr('disabled', true);
          $('#text_id_color_detail').val("");
          $('#cancelIcon').addClass('opacity');
          $('#div_bt_edit').hide();
          $('#div_bt_add').show();
        }
      });

    }

    function save_edit_color() {
      var color_master = $("#select_color_master2").val();
      var color_code = $("#color-picker").val();
      var text_id_color_detail = $('#text_id_color_detail').val();

      if (color_master == "0") {
        swal({
          title: '',
          text: 'กรุณาเลือกกลุ่มสี',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });

        return;
      }



      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'save_edit_color',
          'color_master': color_master,
          'color_code': color_code,
          'text_id_color_detail': text_id_color_detail
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $("#select_color_master2").val("0");
          $("#color-picker").spectrum({
            color: "transparent"
          });

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
            showDataColor();
          }, 500);
          $('#bCancel').attr('disabled', true);
          $('#text_id_color_detail').val("");
          $('#cancelIcon').addClass('opacity');
          $('#div_bt_edit').hide();
          $('#div_bt_add').show();
        }
      });

    }

    function cleartxt() {
      $("#select_color_master2").val("0");
      $("#color-picker").spectrum({
        color: "transparent"
      });
      $(".classSupplier").prop("checked", false);
      $('#bCancel').attr('disabled', true);
      $('#cancelIcon').addClass('opacity');
      $('#text_id_color_detail').val("");
      $('#div_bt_edit').hide();
      $('#div_bt_add').show();
    }

    function showDataColor() {
      var color_master = $("#select_color_master").val();

      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDataColor',
          'color_master': color_master,

        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {

              var chkDoc = "<label class='radio' style='margin-top:7px'><input type='radio' class='classSupplier' name='idSupplier' id='idcolor_detail_" + key + "' value='" + value.ID + "' onclick='getDetail_color(\"" + value.ID + "\");' ><span class='checkmark'></span></label>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td style='width:8%'>" + chkDoc + "</td>" +
                "<td style='width:20%'>" + (key + 1) + "</td>" +
                "<td style='width:22%'>" + value.color_master_name + "</td>" +
                "<td style='width:20%;'><center><div style='width:50%;background-color:" + value.color_code_detail + ";padding:20px;margin:0px;'> </div></center></td>" +
                "<td style='width:30%'> " + value.color_code_detail + " </td>" +
                "</tr>";
            });
          }
          $('#table_color tbody').html(StrTR);

          $("#select_color_master2").val("0");
          $("#color-picker").spectrum({
            color: "transparent"
          });
          $('#bCancel').attr('disabled', true);
          $('#text_id_color_detail').val("");
          $('#cancelIcon').addClass('opacity');
          $('#div_bt_edit').hide();
          $('#div_bt_add').show();
        }
      });
    }

    function deleteData() {
      var text_id_color_detail = $("#text_id_color_detail").val();

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
              'text_id_color_detail': text_id_color_detail,
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
                showDataColor();
                cleartxt();
              }, 800);

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

    function get_color_master() {
      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'get_color_master'
        },
        success: function(result) {

          var ObjData = JSON.parse(result);
          var StrTR = "";


          if (!$.isEmptyObject(ObjData)) {
            var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
            $.each(ObjData, function(key, value) {
              Str += "<option value=" + value.ID + " >" + value.color_master_name + "</option>";
            });
          }

          $("#select_color_master").append(Str);
          $("#select_color_master2").append(Str);

        }
      });
    }

    function chk_color_master() {
      var id_color_master = $('#select_color_master2').val();
      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'chk_color_master',
          'id_color_master': id_color_master
        },
        success: function(result) {

          var ObjData = JSON.parse(result);
          var StrTR = "";


          if (!$.isEmptyObject(ObjData)) {
            var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
            $.each(ObjData, function(key, value) {
              // $('#color-picker').val(value.color_master_code);
              $("#color-picker").spectrum({
                color: value.color_master_code,
                palette: [
                  [value.color_master_code]
                ]
              });
            });
          }



        }
      });
    }

    function getDetail_color(id) {
      $.ajax({
        url: "../process/color.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'getDetail_color',
          'id_color_detail': id
        },
        success: function(result) {

          var ObjData = JSON.parse(result);
          var StrTR = "";


          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {
              $('#select_color_master2').val(value.ID_color_master);
              $('#text_id_color_detail').val(value.ID);

              $("#color-picker").spectrum({
                color: value.color_code_detail,
                palette: [
                  [value.color_master_code]
                ]
              });
            });
          }
          $('#bCancel').attr('disabled', false);
          // $("#bCancel").removeClass('opacity');
          $('#cancelIcon').removeClass('opacity');
          $('#div_bt_edit').show();
          $('#div_bt_add').hide();
        }
      });
    }
  </script>

</body>

</html>