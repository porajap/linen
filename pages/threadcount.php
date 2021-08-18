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
    thread_count
  </title>
  <?php include_once('../assets/import/css.php'); ?>

</head>

<body id="page-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active">thread_count</li>
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
                    <!-- <select class="form-control col-md-4 " id="selectSite" style="font-size:22px;" onchange="changeSite('top')">
                    </select> -->
                    <input id="txtSearch" type="text" autocomplete="off" class="form-control col-md-4 ml-2" style="font-size:22px;" placeholder="<?php echo $array['Searchitem'][$language]; ?>">
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
                        <th nowrap style="width:10%"><br></th>
                        <th nowrap style="width:45%"><?php echo $array['no'][$language]; ?></th>
                        <th nowrap style="width:45%"><?php echo $array['thread_count-name'][$language]; ?></th>
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

              <div class="row mt-4" hidden>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label ">ลำดับ</label>
                    <input id="txtNumber" type="text" autocomplete="off" class="form-control col-sm-7 " disabled style="font-size:22px;">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['thread_count-name'][$language]; ?>
                    </label>
                    <input id="txtName" type="text" autocomplete="off" class="form-control col-sm-7 " style="font-size:22px;">
                    <label id="alert_txtName" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
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

      $("#alert_txtName").hide();



      $("#txtName").change(function() {
        $("#txtName").removeClass("border-danger");
        $("#alert_txtName").hide();
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
      var txtName = $("#txtName").val();



      if (txtName == "") {
        swal({
          title: '',
          text: 'กรุณากรอกเส้นด้าย',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtName").addClass("border-danger");
        $("#alert_txtName").show();
        return;
      }

      $.ajax({
        url: "../process/threadcount.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'checkTypelinen',
          'txtName': txtName,
          'txtNumber': txtNumber,
        },
        success: function(result) {

          if (result == 'repeat') {
            swal({
              title: '',
              text: '<?php echo $array['Duplicatetype'][$language]; ?>',
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: 'Ok'
            })
          } else {
            $.ajax({
              url: "../process/threadcount.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'saveData',
                'txtNumber': txtNumber,
                'txtName': txtName,
              },
              success: function(result) {
                var ObjData = JSON.parse(result);
                $("#txtNumber").val("");
                $("#txtName").val("");
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



        }
      });




    }

    function cleartxt() {
      $("#txtNumber").val("");
      $("#txtName").val("");
      $(".classFabric").prop("checked", false);
      $('#bCancel').attr('disabled', true);
      $('#cancelIcon').addClass('opacity');
    }

    function showData() {
      var txtSearch = $("#txtSearch").val();

      $.ajax({
        url: "../process/threadcount.php",
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

              var chkDoc = "<label class='radio' style='margin-top:7px'><input type='radio' class='classFabric' name='idTypelinen' id='idTypelinen_" + key + "' value='" + value.id + "' onclick='showDetail(\"" + value.id + "\" , \"" + key + "\")' ><span class='checkmark'></span></label>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td style='width:10%;'>" + chkDoc + "</td>" +
                "<td style='width:45%;'>" + (key + 1) + "</td>" +
                "<td style='width:45%;'>" + value.name_Thread + "</td>" +
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
            url: "../process/threadcount.php",
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
      var previousValue = $('#idTypelinen_' + row).attr('previousValue');
      var name = $('#idTypelinen_' + row).attr('name');
      if (previousValue == 'checked') {
        $('#idTypelinen_' + row).removeAttr('checked');
        $('#idTypelinen_' + row).attr('previousValue', false);
        $('#idTypelinen_' + row).prop('checked', false);
        $('#bCancel').attr('disabled', true);
        $('#cancelIcon').addClass('opacity');

        $("#txtNumber").val("");
        $("#txtName").val("");
      } else {
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('#idTypelinen_' + row).attr('previousValue', 'checked');
        $('#bCancel').attr('disabled', false);
        $('#cancelIcon').removeClass('opacity');

        $.ajax({
          url: "../process/threadcount.php",
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
                $("#txtName").val(value.name_Thread);
              });
            }
          }
        });
      }
    }
  </script>

</body>

</html>