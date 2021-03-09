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
    <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][23][$language]; ?></li>
  </ol>

  <div class="col-12">
    <div class="row">
      <div class="col-6">
        <div class="card" style="min-height: 750px;">
          <div class="card-body">
            <div class="row">
              <div class="col-md-4" id="colDep">
                <select  style="font-size:22px;"  class="form-control select2 custom-select" id="selectDep" ></select>
              </div>
              <div class="col-md-4">
                <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here " id="txtsDate" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
              </div>
              <div class="col-md-4">
                <div class="menuMini mhee1 ml-3">
                  <div class="search_1 d-flex justify-content-start">
                    <button class="btn" onclick="showDocument();">
                      <i class="fas fa-search mr-2 pl-2"></i>
                      <?php echo $array['search'][$language]; ?>
                    </button>
                  </div>
                </div>
              </div>




            </div>
            <div class="row">
              <div class="col-12">
                <table class="table table-fixed table-condensed table-striped mt-3" id="tableDocument" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row" id='tr_1'>
                      <th nowrap><?php echo $array['creation'][$language]; ?></th>
                      <th nowrap><?php echo $array['docno'][$language]; ?></th>
                      <th nowrap><?php echo $array['remask'][$language]; ?></th>
                      <th nowrap><br></th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:600px;">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-6">
        <div class="card" style="min-height: 750px;">
          <div class="card-header">
            <h3 id="txtDocNo"></h3>
          </div>
          <div class="card-body">
            <div class="timeline">
              <div class="container left">
                <div class="content" id="timeline_submit">
                  <h3 style="font-weight: bold;" class="text-white">Submit Requests</h3>
                  <p id="p_submit" class="text-white"></p>
                </div>
              </div>
              <div class="container right">
                <div class="content" id="timeline_accept">
                  <h3 style="font-weight: bold;" class="text-white">Accept</h3>
                  <p id="p_accept" class="text-white"></p>
                </div>
              </div>
              <div class="container left">
                <div class="content" id="timeline_packing">
                  <h3 style="font-weight: bold;" class="text-white">Packing Time</h3>
                  <p id="p_packing" class="text-white"></p>
                </div>
              </div>
              <div class="container right">
                <div class="content" id="timeline_completed">
                  <h3 style="font-weight: bold;" class="text-white">Complete</h3>
                  <p id="p_completed" class="text-white"></p>
                </div>
              </div>
              <div class="container left">
                <div class="content" id="timeline_delivery">
                  <h3 style="font-weight: bold;" class="text-white">Delivery Complete</h3>
                  <p id="p_delivery" class="text-white"></p>
                </div>
              </div>
              <div class="container right">
                <div class="content" id="timeline_cancel">
                  <h3 style="font-weight: bold;" class="text-white">Cancel</h3>
                  <p id="p_cancel" class="text-white"></p>
                </div>
              </div>
            </div>
            <!-- <img src="" alt="" id="my_image" style="width: 100%;margin-top: -10%;"> -->
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


  <?php include_once('../assets/import/js.php'); ?>
  <script type="text/javascript">
    $(document).ready(function(e) {
      $(".select2").select2();
      var PmID = '<?php echo $PmID; ?>';
      // if (PmID == 8) {
        $("#colDep").attr('hidden', true);
      // }

      showDocument();
      GetDep();
      $("#timeline_submit").css("background-color", "#D4D4D4");
      $("#timeline_accept").css("background-color", "#D4D4D4");
      $("#timeline_packing").css("background-color", "#D4D4D4");
      $("#timeline_completed").css("background-color", "#D4D4D4");
      $("#timeline_delivery").css("background-color", "#D4D4D4");
      $("#timeline_cancel").css("background-color", "#D4D4D4");




    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    function showDocument() {
      var sDate = $("#txtsDate").val();
      var selectDep = $("#selectDep").val();
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      if (lang == 'th') {
        sDate = sDate.substring(6, 10) - 543 + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
      } else if (lang == 'en') {
        sDate = sDate.substring(6, 10) + "-" + sDate.substring(3, 5) + "-" + sDate.substring(0, 2);
      }
      $.ajax({
        url: "../process/traceDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDocument',
          'sDate': sDate,
          'selectDep': selectDep,
          'PmID': PmID,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {

              var btn_Remark = "";
              if (value.remark == null) {
                value.remark = "";
              }
              if (value.remark != "") {
                var btn_Remark = "<button class='btn btn-info w-50' onclick='showRemark(\"" + value.remark + "\")'>Remark</button>";
              }

              var btn = "<button class='btn btn-primary w-50' onclick='showDetailDoc(\"" + value.DocNo + "\")'><i class='fas fa-search mt-2'></i></button>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td >" + value.DocDate + "</td>" +
                "<td >" + value.DocNo + "</td>" +
                "<td >" + btn_Remark + "</td>" +
                "<td >" + btn + "</td>" +
                "</tr>";
            });
            $('#tableDocument tbody').html(StrTR);
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function GetDep() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/traceDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetDep',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);

          var option = `<option value="0" selected><?php echo $array['selectdep'][$language]; ?></option>`;
          
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

    function showRemark(remark) {

      if (remark == "null") {
        remark = "";
      }
      $('#txtRemark').val(remark);
      $('#modal_remark').modal('toggle');

    }

    function showDetailDoc(DocNo) {
      $.ajax({
        url: "../process/traceDep.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showDetailDoc',
          'DocNo': DocNo,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {
              $("#timeline_submit").css("background-color", "#D4D4D4");
              $("#timeline_accept").css("background-color", "#D4D4D4");
              $("#timeline_packing").css("background-color", "#D4D4D4");
              $("#timeline_completed").css("background-color", "#D4D4D4");
              $("#timeline_delivery").css("background-color", "#D4D4D4");
              $("#timeline_cancel").css("background-color", "#D4D4D4");
              $("#p_submit").text("");
              $("#p_accept").text("");
              $("#p_packing").text("");
              $("#p_completed").text("");
              $("#p_delivery").text("");
              $("#p_cancel").text("");
              $("#txtDocNo").text("");

              // if(value.Modify_Date == '--543'){
              //   value.Modify_Date = '';
              // }
              // if(value.accept_Date == "--543"){
              //   value.accept_Date = "";
              // }
              // if(value.PkStart_Date == "--543"){
              //   value.PkStart_Date = "";
              // }
              // if(vvalue.PkEnd_Date == "--543"){
              //   value.PkEnd_Date = "";
              // }
              // if(value.DvStart_Date == "--543"){
              //   value.DvStart_Date = "";
              // }

              if (value.IsStatus == 2 && value.statusDepartment == 0) {
                $("#p_submit").text(value.create_Date);
                $("#timeline_submit").stop().animate({
                  backgroundColor: "#1659A2"
                }, 800);
                $("#timeline_cancel").stop().animate({
                  backgroundColor: "#D4D4D4	"
                }, 800);
                // $("#timeline_submit").css("background-color", "orange");
              }
              if (value.IsStatus == 0 && value.statusDepartment == 1) {
                $("#p_submit").text(value.create_Date);
                $("#p_accept").text(value.accept_Date);
                $("#timeline_submit").stop().animate({
                  backgroundColor: "#1659A2"
                }, 800);
                $("#timeline_accept").stop().animate({
                  backgroundColor: "#7ED86F"
                }, 800);
                $("#timeline_cancel").stop().animate({
                  backgroundColor: "#D4D4D4	"
                }, 800);
                // $("#timeline_submit").css("background-color", "orange");
                // $("#timeline_accept").css("background-color", "lightseagreen");
              }
              if (value.IsStatus == 1 && value.statusDepartment == 1) {
                $("#p_submit").text(value.create_Date);
                $("#p_accept").text(value.accept_Date);
                $("#p_packing").text(value.PkStart_Date);
                $("#timeline_submit").stop().animate({
                  backgroundColor: "#1659A2"
                }, 800);
                $("#timeline_accept").stop().animate({
                  backgroundColor: "#7ED86F"
                }, 800);
                $("#timeline_packing").stop().animate({
                  backgroundColor: "#44697d"
                }, 800);
                $("#timeline_cancel").stop().animate({
                  backgroundColor: "#D4D4D4	"
                }, 800);
                // $("#timeline_submit").css("background-color", "orange");
                // $("#timeline_accept").css("background-color", "lightseagreen");
                // $("#timeline_packing").css("background-color", "dodgerblue");
              }
              if (value.IsStatus == 3 && value.statusDepartment == 1) {
                $("#p_submit").text(value.create_Date);
                $("#p_accept").text(value.accept_Date);
                $("#p_packing").text(value.PkStart_Date);
                $("#p_completed").text(value.PkEnd_Date);
                $("#timeline_submit").stop().animate({
                  backgroundColor: "#1659A2"
                }, 800);
                $("#timeline_accept").stop().animate({
                  backgroundColor: "#7ED86F"
                }, 800);
                $("#timeline_packing").stop().animate({
                  backgroundColor: "#44697d"
                }, 800);
                $("#timeline_completed").stop().animate({
                  backgroundColor: "#7ED86F"
                }, 800);
                $("#timeline_cancel").stop().animate({
                  backgroundColor: "#D4D4D4	"
                }, 800);
                // $("#timeline_submit").css("background-color", "orange");
                // $("#timeline_accept").css("background-color", "lightseagreen");
                // $("#timeline_packing").css("background-color", "dodgerblue");
                // $("#timeline_completed").css("background-color", "orangered");
              }
              if (value.IsStatus == 4 && value.statusDepartment == 1) {
                $("#p_submit").text(value.create_Date);
                $("#p_accept").text(value.accept_Date);
                $("#p_packing").text(value.PkStart_Date);
                $("#p_completed").text(value.PkEnd_Date);
                $("#p_delivery").text(value.DvStart_Date);
                $("#timeline_submit").stop().animate({
                  backgroundColor: "#1659A2"
                }, 800);
                $("#timeline_accept").stop().animate({
                  backgroundColor: "#7ED86F"
                }, 800);
                $("#timeline_packing").stop().animate({
                  backgroundColor: "#44697d"
                }, 800);
                $("#timeline_completed").stop().animate({
                  backgroundColor: "#7ED86F"
                }, 800);
                $("#timeline_delivery").stop().animate({
                  backgroundColor: "#002C77"
                }, 800);
                $("#timeline_cancel").stop().animate({
                  backgroundColor: "#D4D4D4	"
                }, 800);
                // $("#timeline_submit").css("background-color", "orange");
                // $("#timeline_accept").css("background-color", "lightseagreen");
                // $("#timeline_packing").css("background-color", "dodgerblue");
                // $("#timeline_completed").css("background-color", "orangered");
                // $("#timeline_delivery").css("background-color", "darkgoldenrod");
              }

              if (value.IsStatus == 9) {
                $("#txtDocNo").text(DocNo);
                $("#timeline_submit").stop().animate({
                  backgroundColor: "#A9A9A9	"
                }, 800);
                $("#timeline_accept").stop().animate({
                  backgroundColor: "#A9A9A9	"
                }, 800);
                $("#timeline_packing").stop().animate({
                  backgroundColor: "#A9A9A9	"
                }, 800);
                $("#timeline_completed").stop().animate({
                  backgroundColor: "#A9A9A9	"
                }, 800);
                $("#timeline_delivery").stop().animate({
                  backgroundColor: "#A9A9A9	"
                }, 800);
                $("#timeline_cancel").stop().animate({
                  backgroundColor: "#F93835	"
                }, 800);
                $("#p_submit").text("");
                $("#p_accept").text("");
                $("#p_packing").text("");
                $("#p_completed").text("");
                $("#p_delivery").text("");
                $("#p_cancel").text(value.Modify_Date);
              }



            });
          }
        }
      });


    }
  </script>


</body>

</html>