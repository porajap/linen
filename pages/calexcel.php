<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$TimeOut = $_SESSION['TimeOut'];

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
$json = json_encode($xml);
$array = json_decode($json, TRUE);
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

  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <link href="../css/menu_custom.css" rel="stylesheet">
  <script type="text/javascript">
    var summary = [];

    $(document).ready(function(e) {
      ShowDocument();
      GetSite();



    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    function GetSite() {

      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/calexcel.php",
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
            $('#Site').attr('disabled', true);
            $('#Site').addClass('icon_select');
          }

          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.HptCode}">${value.HptName}</option>`;
            });
          } else {
              option = `<option value="0">Data not found</option>`;
          }

          $("#Site").html(option);
        }
      });
    }


    function ShowDocument() {

      $.ajax({
        url: "../process/calexcel.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'ShowDocument',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              var chkDoc = "<label class='radio'style='margin-top: 1%;'><input type='radio' class='checkblank' data-value='" + value.HptCode + "' name='checkdocno' id='checkdocno' value='" + value.DocNo + "'  onclick='cancelDoc(\"" + value.DocNo + "\"," + kay + ")'><span class='checkmark'></span></label>";
              StrTR += "<tr>" +
                "<td  class='text-center'>" + chkDoc + "</td>" +
                "<td  >" + value.DocNo + "</td>" +
                "<td >" + value.DocDate + "</td>" +
                "</tr>";
            });


            $('#TableDocument_body').html(StrTR);
          }
          $('#TableDocument_body').html(StrTR);

        }
      });
    }

    function cancelDoc(DocNo, row) {
      $('.btn_cancel').each(function() {
        $(".btn_cancel").attr("disabled", true);
      });
      var DocNo = DocNo;
      var row = row;
      $('#cancel').val(DocNo);
      $('#show_btn').attr('disabled', false);
      $('#cancel_btn' + row + '').attr('disabled', false);
      $('#btn_show').attr('disabled', false);
    }

    function get_last_move() {
      last_move = new Date();
      return last_move;
    }

    function getitemExcel() {


      var Site = $("#Site").val();

      $.ajax({
        url: "../process/calexcel.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'getitemExcel',
          'Site': Site,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {



              StrTR += "<tr>" +
                "<td  class='text-center'>" + (kay + 1) + "</td>" +
                "<td >" + value.ItemName + "</td>" +
                "<td hidden><input id='input0_" + kay + "' autocomplete='off' type='text' class='form-control width_custom loopitemcode' value=" + value.ItemCode + " ></td>" +
                "<td ><input id='input1_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input1' onkeyup='Calinput2(\"" + kay + "\")'></td>" +
                "<td ><input id='input2_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input2' onkeyup='Calinput2(\"" + kay + "\")'></td>" +
                "<td ><input id='input3_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input3' onkeyup='Calinput1(\"" + kay + "\")'></td>" +
                "<td ><input id='input4_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input4' onkeyup='Calinput1(\"" + kay + "\")'></td>" +
                "<td ><input id='input5_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input5' disabled></td>" +
                "<td ><input id='input6_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input6' disabled></td>" +
                "<td ><input id='input7_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input7' onkeyup='Calinput3(\"" + kay + "\")'></td>" +
                "<td ><input id='input8_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input8' disabled></td>" +
                "<td ><input id='input9_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input9' disabled></td>" +
                "<td ><input id='input10_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input10' disabled></td>" +
                "<td ><input id='input11_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input11' onkeyup='Calinput4(\"" + kay + "\")'></td>" +
                "<td ><input id='input12_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input12' onkeyup='Calinput4(\"" + kay + "\")'></td>" +
                "<td ><input id='input13_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input13' onkeyup='Calinput4(\"" + kay + "\")'></td>" +
                "<td ><input id='input14_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input14' onkeyup='Calinput4(\"" + kay + "\")'></td>" +
                "<td ><input id='input15_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input15' onkeyup='Calinput4(\"" + kay + "\")'></td>" +
                "<td ><input id='input16_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input16' onkeyup='Calinput4(\"" + kay + "\")'></td>" +
                "<td ><input id='input17_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input17' disabled></td>" +
                "<td ><input id='input18_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input18' disabled></td>" +
                "<td ><input id='input19_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input19' onkeyup='Calinput5(\"" + kay + "\")'></td>" +
                "<td ><input id='input20_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input20' disabled></td>" +
                "<td ><input id='input21_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input21' onkeyup='Calinput6(\"" + kay + "\")'></td>" +
                "<td ><input id='input22_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input22' disabled></td>" +
                "<td ><input id='input23_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input23' disabled></td>" +
                "<td ><input id='input24_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input24' disabled></td>" +
                "<td ><input id='input25_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input25' disabled></td>" +
                "<td ><input id='input26_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input26' disabled></td>" +
                "<td ><input id='input27_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input27' disabled></td>" +
                "<td ><input id='input28_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input28' disabled></td>" +
                "<td ><input id='input29_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input29' disabled></td>" +
                "<td ><input id='input30_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input30' disabled></td>" +
                "<td ><input id='input31_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input31' disabled></td>" +
                "</tr>";

            });

            StrTR += "<tr>" +
              "<td ><input  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input1'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input2'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input3'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input4'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input5'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input6'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input7'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input8'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input9'   autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input10'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input11'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input12'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input13'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input14'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input15'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input16'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input17'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input18'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input19'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input20'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input21'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input22'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input23'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input24'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input25'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input26'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input27'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input28'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input29'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input30'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
              "<td ><input id='sum_input31'  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +

              "</tr>";


            $('#body_table').html(StrTR);
          }

        }
      });
    }

    function Calinput1(kay) {
      input_3 = parseFloat($('#input3_' + kay).val());
      input_1 = parseFloat($('#input1_' + kay).val());
      input_4 = parseFloat($('#input4_' + kay).val());
      input_7 = parseFloat($('#input7_' + kay).val());





      if (isNaN(input_3)) {
        input_3 = 0;
      }
      if (isNaN(input_4)) {
        input_4 = 0;
      }
      if (isNaN(input_7)) {
        input_7 = 0;
      }
      if (isNaN(input_1)) {
        input_1 = 0;
      }

      var sum_3_4 = parseFloat(input_3 + input_4);
      $('#input5_' + kay).val(sum_3_4.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_5_7 = parseFloat(sum_3_4 - input_7);
      $('#input30_' + kay).val(sum_5_7.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_30 = parseFloat(sum_5_7 * input_1);
      $('#input31_' + kay).val(sum_1_30.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      Calinput2(kay);


      // ====================================================================================================================
      var Totalsum_input3 = 0;
      $('.input3').each(function() {
        if ($(this).val() != "") {
          Totalsum_input3 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input3 += 0;
        }
      });

      $('#sum_input3').val(Totalsum_input3.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input4 = 0;
      $('.input4').each(function() {
        if ($(this).val() != "") {
          Totalsum_input4 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input4 += 0;
        }
      });

      $('#sum_input4').val(Totalsum_input4.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input5 = 0;
      $('.input5').each(function() {
        if ($(this).val() != "") {
          Totalsum_input5 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input5 += 0;
        }
      });

      $('#sum_input5').val(Totalsum_input5.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input30 = 0;
      $('.input30').each(function() {
        if ($(this).val() != "") {
          Totalsum_input30 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input30 += 0;
        }
      });

      $('#sum_input30').val(Totalsum_input30.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input31 = 0;
      $('.input31').each(function() {
        if ($(this).val() != "") {
          Totalsum_input31 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input31 += 0;
        }
      });

      $('#sum_input31').val(Totalsum_input31.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      // ====================================================================================================================

    }

    function Calinput2(kay) {
      input_1 = parseFloat($('#input1_' + kay).val());
      input_2 = parseFloat($('#input2_' + kay).val());
      input_3 = parseFloat($('#input3_' + kay).val());
      input_5 = parseFloat($('#input5_' + kay).val());

      if (isNaN(input_1)) {
        input_1 = 0;
      }
      if (isNaN(input_2)) {
        input_2 = 0;
      }
      if (isNaN(input_3)) {
        input_3 = 0;
      }
      if (isNaN(input_5)) {
        input_5 = 0;
      }

      var sum_1_2_5 = parseFloat(input_1 * (input_2 + input_5));

      $('#input6_' + kay).val(sum_1_2_5.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      Calinput3(kay);
      Calinput4(kay);
      Calinput5(kay);
      Calinput6(kay);



      var Totalsum_input1 = 0;
      $('.input1').each(function() {
        if ($(this).val() != "") {
          Totalsum_input1 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input1 += 0;
        }
      });

      $('#sum_input1').val(Totalsum_input1.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input2 = 0;
      $('.input2').each(function() {
        if ($(this).val() != "") {
          Totalsum_input2 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input2 += 0;
        }
      });

      $('#sum_input2').val(Totalsum_input2.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input6 = 0;
      $('.input6').each(function() {
        if ($(this).val() != "") {
          Totalsum_input6 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input6 += 0;
        }
      });

      $('#sum_input6').val(Totalsum_input6.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

    }

    function Calinput3(kay) {
      input_1 = parseFloat($('#input1_' + kay).val());
      input_2 = parseFloat($('#input2_' + kay).val());
      input_5 = parseFloat($('#input5_' + kay).val());
      input_7 = parseFloat($('#input7_' + kay).val());
      input_9 = parseFloat($('#input9_' + kay).val());
      input_23 = parseFloat($('#input23_' + kay).val());

      if (isNaN(input_1)) {
        input_1 = 0;
      }
      if (isNaN(input_2)) {
        input_2 = 0;
      }
      if (isNaN(input_5)) {
        input_5 = 0;
      }
      if (isNaN(input_7)) {
        input_7 = 0;
      }
      if (isNaN(input_9)) {
        input_9 = 0;
      }
      if (isNaN(input_23)) {
        input_23 = 0;
      }

      var sum_2_7 = parseFloat(input_2 + input_7);

      $('#input9_' + kay).val(sum_2_7.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_1_7 = parseFloat(input_1 * input_7);

      $('#input8_' + kay).val(sum_1_7.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_1_9 = parseFloat(input_1 * sum_2_7);
      $('#input10_' + kay).val(sum_1_9.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_9_23 = parseFloat(input_23 - sum_2_7);
      $('#input25_' + kay).val(sum_9_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_1_25 = parseFloat(input_1 * sum_9_23);
      $('#input26_' + kay).val(sum_1_25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_9_25 = parseFloat((sum_9_23 / sum_2_7) * 100);
      $('#input27_' + kay).val(sum_9_25.toFixed(0));

      var sum_5_7 = parseFloat(input_5 - input_7);
      $('#input30_' + kay).val(sum_5_7.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_30 = parseFloat(sum_5_7 * input_1);
      $('#input31_' + kay).val(sum_1_30.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input7 = 0;
      $('.input7').each(function() {
        if ($(this).val() != "") {
          Totalsum_input7 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input7 += 0;
        }
      });

      $('#sum_input7').val(Totalsum_input7.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input8 = 0;
      $('.input8').each(function() {
        if ($(this).val() != "") {
          Totalsum_input8 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input8 += 0;
        }
      });

      $('#sum_input8').val(Totalsum_input8.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input9 = 0;
      $('.input9').each(function() {
        if ($(this).val() != "") {
          Totalsum_input9 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input9 += 0;
        }
      });

      $('#sum_input9').val(Totalsum_input9.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input10 = 0;
      $('.input10').each(function() {
        if ($(this).val() != "") {
          Totalsum_input10 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input10 += 0;
        }
      });

      $('#sum_input10').val(Totalsum_input10.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input25 = 0;
      $('.input25').each(function() {
        if ($(this).val() != "") {
          Totalsum_input25 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input25 += 0;
        }
      });

      $('#sum_input25').val(Totalsum_input25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input26 = 0;
      $('.input26').each(function() {
        if ($(this).val() != "") {
          Totalsum_input26 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input26 += 0;
        }
      });

      $('#sum_input26').val(Totalsum_input26.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input27 = 0;
      $('.input27').each(function() {
        if ($(this).val() != "") {
          Totalsum_input27 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input27 += 0;
        }
      });

      $('#sum_input27').val(Totalsum_input27.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input30 = 0;
      $('.input30').each(function() {
        if ($(this).val() != "") {
          Totalsum_input30 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input30 += 0;
        }
      });

      $('#sum_input30').val(Totalsum_input30.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input31 = 0;
      $('.input31').each(function() {
        if ($(this).val() != "") {
          Totalsum_input31 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input31 += 0;
        }
      });

      $('#sum_input31').val(Totalsum_input31.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

    }

    function Calinput4(kay) {
      input_1 = parseFloat($('#input1_' + kay).val());
      input_9 = parseFloat($('#input9_' + kay).val());
      input_11 = parseFloat($('#input11_' + kay).val());
      input_12 = parseFloat($('#input12_' + kay).val());
      input_13 = parseFloat($('#input13_' + kay).val());
      input_14 = parseFloat($('#input14_' + kay).val());
      input_15 = parseFloat($('#input15_' + kay).val());
      input_16 = parseFloat($('#input16_' + kay).val());
      input_17 = parseFloat($('#input17_' + kay).val());
      input_19 = parseFloat($('#input19_' + kay).val());
      input_21 = parseFloat($('#input21_' + kay).val());

      if (isNaN(input_1)) {
        input_1 = 0;
      }
      if (isNaN(input_9)) {
        input_9 = 0;
      }
      if (isNaN(input_11)) {
        input_11 = 0;
      }
      if (isNaN(input_12)) {
        input_12 = 0;
      }
      if (isNaN(input_13)) {
        input_13 = 0;
      }
      if (isNaN(input_14)) {
        input_14 = 0;
      }
      if (isNaN(input_15)) {
        input_15 = 0;
      }
      if (isNaN(input_16)) {
        input_16 = 0;
      }
      if (isNaN(input_17)) {
        input_17 = 0;
      }
      if (isNaN(input_19)) {
        input_19 = 0;

      }
      if (isNaN(input_21)) {
        input_21 = 0;
      }

      var sum_11_12_13_14_15_16 = parseFloat(input_11 + input_12 + input_13 + input_14 + input_15 + input_16);
      $('#input17_' + kay).val(sum_11_12_13_14_15_16.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_1_17 = parseFloat(input_1 * sum_11_12_13_14_15_16);
      $('#input18_' + kay).val(sum_1_17.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_17_19_21 = parseFloat(sum_11_12_13_14_15_16 + input_19 + input_21);
      $('#input23_' + kay).val(sum_17_19_21.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_1_23 = parseFloat(input_1 * sum_17_19_21);
      $('#input24_' + kay).val(sum_1_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));



      var sum_9_23 = parseFloat(sum_17_19_21 - input_9);
      $('#input25_' + kay).val(sum_9_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_25 = parseFloat(input_1 * sum_9_23);
      $('#input26_' + kay).val(sum_1_25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_9_25 = parseFloat((sum_9_23 / input_9) * 100);
      $('#input27_' + kay).val(sum_9_25.toFixed(0) + '%');

      var sum_17_19 = parseFloat(sum_11_12_13_14_15_16 - input_19);
      $('#input28_' + kay).val(sum_17_19.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_28 = parseFloat(input_1 * sum_17_19);
      $('#input29_' + kay).val(sum_1_28.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));



      var Totalsum_input11 = 0;
      $('.input11').each(function() {
        if ($(this).val() != "") {
          Totalsum_input11 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input11 += 0;
        }
      });

      $('#sum_input11').val(Totalsum_input11.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input12 = 0;
      $('.input12').each(function() {
        if ($(this).val() != "") {
          Totalsum_input12 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input12 += 0;
        }
      });

      $('#sum_input12').val(Totalsum_input12.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input13 = 0;
      $('.input13').each(function() {
        if ($(this).val() != "") {
          Totalsum_input13 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input13 += 0;
        }
      });

      $('#sum_input13').val(Totalsum_input13.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input14 = 0;
      $('.input14').each(function() {
        if ($(this).val() != "") {
          Totalsum_input14 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input14 += 0;
        }
      });

      $('#sum_input14').val(Totalsum_input14.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input15 = 0;
      $('.input15').each(function() {
        if ($(this).val() != "") {
          Totalsum_input15 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input15 += 0;
        }
      });

      $('#sum_input15').val(Totalsum_input15.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input16 = 0;
      $('.input16').each(function() {
        if ($(this).val() != "") {
          Totalsum_input16 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input16 += 0;
        }
      });

      $('#sum_input16').val(Totalsum_input16.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));



      var Totalsum_input17 = 0;
      $('.input17').each(function() {
        if ($(this).val() != "") {
          Totalsum_input17 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input17 += 0;
        }
      });

      $('#sum_input17').val(Totalsum_input17.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input18 = 0;
      $('.input18').each(function() {
        if ($(this).val() != "") {
          Totalsum_input18 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input18 += 0;
        }
      });

      $('#sum_input18').val(Totalsum_input18.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input23 = 0;
      $('.input23').each(function() {
        if ($(this).val() != "") {
          Totalsum_input23 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input23 += 0;
        }
      });

      $('#sum_input23').val(Totalsum_input23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input24 = 0;
      $('.input24').each(function() {
        if ($(this).val() != "") {
          Totalsum_input24 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input24 += 0;
        }
      });

      $('#sum_input24').val(Totalsum_input24.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input25 = 0;
      $('.input25').each(function() {
        if ($(this).val() != "") {
          Totalsum_input25 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input25 += 0;
        }
      });

      $('#sum_input25').val(Totalsum_input25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input26 = 0;
      $('.input26').each(function() {
        if ($(this).val() != "") {
          Totalsum_input26 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input26 += 0;
        }
      });

      $('#sum_input26').val(Totalsum_input26.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input27 = 0;
      $('.input27').each(function() {
        if ($(this).val() != "") {
          Totalsum_input27 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input27 += 0;
        }
      });

      $('#sum_input27').val(Totalsum_input27.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input28 = 0;
      $('.input28').each(function() {
        if ($(this).val() != "") {
          Totalsum_input28 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input28 += 0;
        }
      });

      $('#sum_input28').val(Totalsum_input28.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input29 = 0;
      $('.input29').each(function() {
        if ($(this).val() != "") {
          Totalsum_input29 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input29 += 0;
        }
      });

      $('#sum_input29').val(Totalsum_input29.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    }

    function Calinput5(kay) {
      input_1 = parseFloat($('#input1_' + kay).val());
      input_19 = parseFloat($('#input19_' + kay).val());
      input_17 = parseFloat($('#input17_' + kay).val());
      input_21 = parseFloat($('#input21_' + kay).val());
      input_9 = parseFloat($('#input9_' + kay).val());

      if (isNaN(input_9)) {
        input_9 = 0;
      }
      if (isNaN(input_1)) {
        input_1 = 0;
      }
      if (isNaN(input_19)) {
        input_19 = 0;
      }
      if (isNaN(input_17)) {
        input_17 = 0;
      }
      if (isNaN(input_21)) {
        input_21 = 0;
      }



      var sum_1_19 = parseFloat(input_1 * input_19);
      $('#input20_' + kay).val(sum_1_19.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_17_19_21 = parseFloat(input_17 + input_19 + input_21);
      $('#input23_' + kay).val(sum_17_19_21.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_23 = parseFloat(input_1 * sum_17_19_21);
      $('#input24_' + kay).val(sum_1_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_9_23 = parseFloat(sum_17_19_21 - input_9);
      $('#input25_' + kay).val(sum_9_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_25 = parseFloat(input_1 * sum_9_23);
      $('#input26_' + kay).val(sum_1_25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_9_25 = parseFloat((sum_9_23 / input_9) * 100);
      $('#input27_' + kay).val(sum_9_25.toFixed(0) + '%');


      var sum_17_19 = parseFloat(input_17 - input_19);
      $('#input28_' + kay).val(sum_17_19.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_28 = parseFloat(input_1 * sum_17_19);
      $('#input29_' + kay).val(sum_1_28.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var Totalsum_input19 = 0;
      $('.input19').each(function() {
        if ($(this).val() != "") {
          Totalsum_input19 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input19 += 0;
        }
      });

      $('#sum_input19').val(Totalsum_input19.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input20 = 0;
      $('.input20').each(function() {
        if ($(this).val() != "") {
          Totalsum_input20 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input20 += 0;
        }
      });

      $('#sum_input20').val(Totalsum_input20.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input19 = 0;
      $('.input19').each(function() {
        if ($(this).val() != "") {
          Totalsum_input19 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input19 += 0;
        }
      });

      $('#sum_input23').val(Totalsum_input19.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input23 = 0;
      $('.input23').each(function() {
        if ($(this).val() != "") {
          Totalsum_input23 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input23 += 0;
        }
      });

      $('#sum_input23').val(Totalsum_input23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input24 = 0;
      $('.input24').each(function() {
        if ($(this).val() != "") {
          Totalsum_input24 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input24 += 0;
        }
      });

      $('#sum_input24').val(Totalsum_input24.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input25 = 0;
      $('.input25').each(function() {
        if ($(this).val() != "") {
          Totalsum_input25 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input25 += 0;
        }
      });

      $('#sum_input25').val(Totalsum_input25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input26 = 0;
      $('.input26').each(function() {
        if ($(this).val() != "") {
          Totalsum_input26 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input26 += 0;
        }
      });

      $('#sum_input26').val(Totalsum_input26.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input27 = 0;
      $('.input27').each(function() {
        if ($(this).val() != "") {
          Totalsum_input27 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input27 += 0;
        }
      });

      $('#sum_input27').val(Totalsum_input27.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input28 = 0;
      $('.input28').each(function() {
        if ($(this).val() != "") {
          Totalsum_input28 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input28 += 0;
        }
      });

      $('#sum_input28').val(Totalsum_input28.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input29 = 0;
      $('.input29').each(function() {
        if ($(this).val() != "") {
          Totalsum_input29 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input29 += 0;
        }
      });

      $('#sum_input29').val(Totalsum_input29.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

    }

    function Calinput6(kay) {
      input_1 = parseFloat($('#input1_' + kay).val());
      input_19 = parseFloat($('#input19_' + kay).val());
      input_17 = parseFloat($('#input17_' + kay).val());
      input_21 = parseFloat($('#input21_' + kay).val());
      input_9 = parseFloat($('#input9_' + kay).val());

      if (isNaN(input_9)) {
        input_9 = 0;
      }
      if (isNaN(input_1)) {
        input_1 = 0;
      }
      if (isNaN(input_19)) {
        input_19 = 0;
      }
      if (isNaN(input_17)) {
        input_17 = 0;
      }
      if (isNaN(input_21)) {
        input_21 = 0;
      }



      var sum_1_21 = parseFloat(input_1 * input_21);
      $('#input22_' + kay).val(sum_1_21.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));


      var sum_17_19_21 = parseFloat(input_17 + input_19 + input_21);
      $('#input23_' + kay).val(sum_17_19_21.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_23 = parseFloat(input_1 * sum_17_19_21);
      $('#input24_' + kay).val(sum_1_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_9_23 = parseFloat(sum_17_19_21 - input_9);
      $('#input25_' + kay).val(sum_9_23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_1_25 = parseFloat(input_1 * sum_9_23);
      $('#input26_' + kay).val(sum_1_25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var sum_9_25 = parseFloat((sum_9_23 / input_9) * 100);
      $('#input27_' + kay).val(sum_9_25.toFixed(0) + '%');


      var Totalsum_input21 = 0;
      $('.input21').each(function() {
        if ($(this).val() != "") {
          Totalsum_input21 += parseFloat($(this).val());
        } else {
          Totalsum_input21 += 0;
        }
      });

      $('#sum_input21').val(Totalsum_input21.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input22 = 0;
      $('.input22').each(function() {
        if ($(this).val() != "") {
          Totalsum_input22 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input22 += 0;
        }
      });

      $('#sum_input22').val(Totalsum_input22.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input23 = 0;
      $('.input23').each(function() {
        if ($(this).val() != "") {
          Totalsum_input23 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input23 += 0;
        }
      });

      $('#sum_input23').val(Totalsum_input23.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input24 = 0;
      $('.input24').each(function() {
        if ($(this).val() != "") {
          Totalsum_input24 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input24 += 0;
        }
      });

      $('#sum_input24').val(Totalsum_input24.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input25 = 0;
      $('.input25').each(function() {
        if ($(this).val() != "") {
          Totalsum_input25 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input25 += 0;
        }
      });

      $('#sum_input25').val(Totalsum_input25.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input26 = 0;
      $('.input26').each(function() {
        if ($(this).val() != "") {
          Totalsum_input26 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input26 += 0;
        }
      });

      $('#sum_input26').val(Totalsum_input26.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

      var Totalsum_input27 = 0;
      $('.input27').each(function() {
        if ($(this).val() != "") {
          Totalsum_input27 += parseFloat($(this).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
        } else {
          Totalsum_input27 += 0;
        }
      });

      $('#sum_input27').val(Totalsum_input27.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));

    }

    function CreateDocument() {

      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#Site").val();

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
              url: "../process/calexcel.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'CreateDocument',
                'PmID': PmID,
                'Site': Site,
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

                var ObjData = JSON.parse(result);
                ObjData = ObjData[0];
                $('#DocNo').val(ObjData.DocNo);
                ShowDocument();
                setTimeout(() => {
                  getitemExcel();
                }, 1000);
              }
            });
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })

      }



    }

    function SaveDocument() {
      var DocNo = $("#DocNo").val();
      var i = 0;

      if (DocNo == '') {
        swal({
          title: '',
          text: 'กรุณาสร้างเอกสารก่อนบันทึก',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
      } else {
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

              var objItem = {};
              objItem.itemcode = [];
              objItem.itemname = [];
              objItem.itemQty = [];
              var qtyArray = [];

              objItem.itemcode.push($('#input0_' + i).val());
              objItem.itemname.push($('#input01_' + i).val());


              for (var x = 1; x <= 31; x++) {
                qtyArray.push($('#input' + x + '_' + i).val().toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ""));
              }

              objItem.itemQty.push(qtyArray);


              $.ajax({
                url: "../process/calexcel.php",
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                  'FUNC_NAME': 'SaveDocument',
                  'DocNo': DocNo,
                  'itemcode': objItem.itemcode,
                  'itemQty': objItem.itemQty,
                  'itemname': objItem.itemname,
                },
                success: function(result) {

                }
              });






              i++;



              // var qtyArray = [];

              // objItem.itemcode.push($(this).val());


              // for(var x=1; x<=31;x++){

              //   qtyArray.push($('#input'+ x +'_'+ i).val());

              // }

              // objItem.itemQty[key] = qtyArray;

              // i++;

            });

            swal({
              title: '<?php echo $array['pleasewait'][$language]; ?>',
              text: '<?php echo $array['processing'][$language]; ?>',
              allowOutsideClick: false
            })
            swal.showLoading();

            setTimeout(() => {
              swal({
              title: '',
              text: '<?php echo $array['savesuccess'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            }, 10000);


          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })

      }

      // $.ajax({
      //   url: "../process/calexcel.php",
      //   type: 'POST',
      //   dataType: 'JSON',
      //   cache: false,
      //   data: {
      //     'FUNC_NAME': 'SaveDocument',
      //     'DocNo': DocNo,
      //     'itemcode': objItem.itemcode,
      //     'itemQty': objItem.itemQty,
      //   },
      //   success: function(result) {


      //   }
      // });


    }

    function SelectDocument() {
      var DocNo = $('input[name="checkdocno"]:checked').val();
      var HptCode = $('input[name="checkdocno"]:checked').data('value');

      $.ajax({
        url: "../process/calexcel.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'SelectDocument',
          'DocNo': DocNo,
          'HptCode': HptCode,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          var chk = 0;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData.data, function(kay, value) {

              StrTR += "<tr>" +
                "<td  class='text-center'>" + (kay + 1) + "</td>" +
                "<td >" + value.ItemName + "</td>" +
                "<td hidden><input id='input01_" + kay + "' autocomplete='off' type='text' class='form-control width_custom ' value=" + value.ItemCode + " ></td>" +
                "<td hidden><input id='input0_" + kay + "' autocomplete='off' type='text' class='form-control width_custom loopitemcode' value=" + value.ItemName + " ></td>" +
                "<td ><input id='input1_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input1' onkeyup='Calinput2(\"" + kay + "\")' value=" + value.Input1 + "></td>" +
                "<td ><input id='input2_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input2' onkeyup='Calinput2(\"" + kay + "\")' value=" + value.Input2 + "></td>" +
                "<td ><input id='input3_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input3' onkeyup='Calinput1(\"" + kay + "\")' value=" + value.Input3 + "></td>" +
                "<td ><input id='input4_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input4' onkeyup='Calinput1(\"" + kay + "\")' value=" + value.Input4 + "></td>" +
                "<td ><input id='input5_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input5' disabled value=" + value.Input5 + "></td>" +
                "<td ><input id='input6_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input6' disabled value=" + value.Input6 + "></td>" +
                "<td ><input id='input7_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input7' onkeyup='Calinput3(\"" + kay + "\")' value=" + value.Input7 + "></td>" +
                "<td ><input id='input8_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input8' disabled value=" + value.Input8 + "></td>" +
                "<td ><input id='input9_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input9' disabled value=" + value.Input9 + "></td>" +
                "<td ><input id='input10_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input10' disabled value=" + value.Input10 + "></td>" +
                "<td ><input id='input11_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input11' onkeyup='Calinput4(\"" + kay + "\")' value=" + value.Input11 + "></td>" +
                "<td ><input id='input12_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input12' onkeyup='Calinput4(\"" + kay + "\")' value=" + value.Input12 + "></td>" +
                "<td ><input id='input13_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input13' onkeyup='Calinput4(\"" + kay + "\")' value=" + value.Input13 + "></td>" +
                "<td ><input id='input14_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input14' onkeyup='Calinput4(\"" + kay + "\")' value=" + value.Input14 + "></td>" +
                "<td ><input id='input15_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input15' onkeyup='Calinput4(\"" + kay + "\")' value=" + value.Input15 + "></td>" +
                "<td ><input id='input16_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input16' onkeyup='Calinput4(\"" + kay + "\")' value=" + value.Input16 + "></td>" +
                "<td ><input id='input17_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input17' disabled value=" + value.Input17 + "></td>" +
                "<td ><input id='input18_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input18' disabled value=" + value.Input18 + "></td>" +
                "<td ><input id='input19_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input19' onkeyup='Calinput5(\"" + kay + "\")' value=" + value.Input19 + "></td>" +
                "<td ><input id='input20_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input20' disabled value=" + value.Input20 + "></td>" +
                "<td ><input id='input21_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input21' onkeyup='Calinput6(\"" + kay + "\")' value=" + value.Input21 + "></td>" +
                "<td ><input id='input22_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input22' disabled value=" + value.Input22 + "></td>" +
                "<td ><input id='input23_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input23' disabled value=" + value.Input23 + "></td>" +
                "<td ><input id='input24_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input24' disabled value=" + value.Input24 + "></td>" +
                "<td ><input id='input25_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input25' disabled value=" + value.Input25 + "></td>" +
                "<td ><input id='input26_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input26' disabled value=" + value.Input26 + "></td>" +
                "<td ><input id='input27_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input27' disabled value=" + value.Input27 + "></td>" +
                "<td ><input id='input28_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input28' disabled value=" + value.Input28 + "></td>" +
                "<td ><input id='input29_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input29' disabled value=" + value.Input29 + "></td>" +
                "<td ><input id='input30_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input30' disabled value=" + value.Input30 + "></td>" +
                "<td ><input id='input31_" + kay + "' autocomplete='off' type='text' class='form-control width_custom input31' disabled value=" + value.Input31 + "></td>" +
                "</tr>";

              chk++;
            });

            $.each(ObjData.datasum, function(kay1, value1) {
              StrTR += "<tr>" +
                "<td ><input  autocomplete='off' type='text' class='form-control width_custom' disabled  ></td>" +
                "<td ><input  autocomplete='off' type='text' class='form-control width_custom' disabled></td>" +
                "<td ><input id='sum_input1'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input1 + "></td>" +
                "<td ><input id='sum_input2'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input2 + "></td>" +
                "<td ><input id='sum_input3'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input3 + "></td>" +
                "<td ><input id='sum_input4'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input4 + "></td>" +
                "<td ><input id='sum_input5'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input5 + "></td>" +
                "<td ><input id='sum_input6'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input6 + "></td>" +
                "<td ><input id='sum_input7'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input7 + "></td>" +
                "<td ><input id='sum_input8'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input8 + "></td>" +
                "<td ><input id='sum_input9'   autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input9 + "></td>" +
                "<td ><input id='sum_input10'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input10 + "></td>" +
                "<td ><input id='sum_input11'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input11 + "></td>" +
                "<td ><input id='sum_input12'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input12 + "></td>" +
                "<td ><input id='sum_input13'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input13 + "></td>" +
                "<td ><input id='sum_input14'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input14 + "></td>" +
                "<td ><input id='sum_input15'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input15 + "></td>" +
                "<td ><input id='sum_input16'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input16 + "></td>" +
                "<td ><input id='sum_input17'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input17 + "></td>" +
                "<td ><input id='sum_input18'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input18 + "></td>" +
                "<td ><input id='sum_input19'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input19 + "></td>" +
                "<td ><input id='sum_input20'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input20 + "></td>" +
                "<td ><input id='sum_input21'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input21 + "></td>" +
                "<td ><input id='sum_input22'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input22 + "></td>" +
                "<td ><input id='sum_input23'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input23 + "></td>" +
                "<td ><input id='sum_input24'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input24 + "></td>" +
                "<td ><input id='sum_input25'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input25 + "></td>" +
                "<td ><input id='sum_input26'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input27 + "></td>" +
                "<td ><input id='sum_input27'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input27 + "></td>" +
                "<td ><input id='sum_input28'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input28 + "></td>" +
                "<td ><input id='sum_input29'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input29 + "></td>" +
                "<td ><input id='sum_input30'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input30 + "></td>" +
                "<td ><input id='sum_input31'  autocomplete='off' type='text' class='form-control width_custom' disabled value=" + value1.sum_Input31 + "></td>" +

                "</tr>";






            });



            $("#DocNo").val(ObjData.docno);
            $("#Site").val(ObjData.HptCode);
            $('#home-tab').tab('show');
            $('#body_table').html(StrTR);


            if (chk == 0) {
              getitemExcel();
            }
          }



        }
      });
    }

    function DeleteDocument() {
      var DocNo = $("#DocNo").val();
      var i = 0;

      if (DocNo == '') {
        swal({
          title: '',
          text: 'กรุณาระบุเอกสารที่ต้องการยกเลิก',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
      } else {
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
              url: "../process/calexcel.php",
              type: 'POST',
              data: {
                'FUNC_NAME': 'DeleteDocument',
                'DocNo': DocNo,
              },
              success: function(result) {

                swal({
                  title: '',
                  text: '<?php echo $array['cancelsuccessmsg'][$language]; ?>',
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1500,
                });

                $('#DocNo').val('');
                $('#body_table').empty();
                ShowDocument();
              }
            });


          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })

      }

    }
  </script>

  <style media="screen">
    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .width_custom {
      width: 165px;
    }

    input,
    select {
      font-size: 24px !important;
    }

    th,
    td {
      font-size: 22px !important;
    }

    .table>thead>tr>th {
      background-color: #1659a2;
    }

    .table>thead>tr>td {
      background-color: #fff;
      color: #000;
    }

    table tr th,
    table tr td {
      border-right: 0px solid #bbb;
      border-bottom: 0px solid #bbb;
      padding: 5px;
    }



    table tr th {
      background: #eee;
      border-top: 0px solid #bbb;
      text-align: left;
    }


    select {
      text-align: center;
      text-align-last: center;
      /* webkit*/
    }

    .labelPrecent {
      position: absolute;
      right: 40px;
      top: 5px;
    }
  </style>

</head>

<body id="page-top" style="  overflow-x: auto;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['tdas'][$language]; ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="row m-1 my-4 mt-2 d-flex justify-content-start">
        <div class="menu2">
          <div class="d-flex justify-content-center">
            <div class="circle1 d-flex justify-content-center">
              <button class="btn" onclick="CreateDocument()" id="bCreate">
                <i class="fas fa-file-medical"></i>
                <div>
                  <?php echo $array['createdocno'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
        <div class="menu2">
          <div class="d-flex justify-content-center">
            <div class="circle4 d-flex justify-content-center">
              <button class="btn" id="bImport" onclick="SaveDocument();">
                <i class="fas fa-save"></i>
                <div>
                  <?php echo $array['save'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
        <div class="menu2">
          <div class="d-flex justify-content-center">
            <div class="circle10 d-flex justify-content-center">
              <button class="btn" id="bImport" onclick="DeleteDocument()">
                <i class="fas fa-eraser mr-2"></i>
                <div>
                  <?php echo $array['isno'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
        <div class="menu2">
          <div class="d-flex justify-content-center">
            <div class="circle4 d-flex justify-content-center">
              <button class="btn" id="exportExcel" onclick="CreateExcel();">
                <i class="fas fa-file-excel"></i>
                <div>
                  <?php echo $array['excel'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <input type="text" id="DocNo" class="form-control ml-4" style="font-size:24px;width:25%;" placeholder="<?php echo $array['docno'][$language]; ?>" disabled>
        <select id="Site" class="form-control ml-4" style="font-size:24px;width:25%;"> </select>
      </div>
      <table style="margin-top:10px;" class="table mt-2 table-bordered " id="TableItem" cellspacing="0" role="grid">
        <thead id="theadsum" style="font-size:11px;">
          <tr role="row">
            <th class='text-center' nowrap colspan="8">Linen Stock Count</th>
            <th class='text-center' nowrap colspan="2">New Linens in Month</th>
            <th class='text-center' nowrap colspan="2">Old Bal + New Purchase</th>
            <th class='text-center' nowrap colspan="8">Linen stock count 02/2019</th>
            <th class='text-center' nowrap colspan="2">Sale to Patient</th>
            <th class='text-center' nowrap colspan="2">Damage</th>
            <th class='text-center' nowrap colspan="2">New Bal + Sale + Damage</th>
            <th class='text-center' nowrap colspan="3">&nbsp;</th>
            <th class='text-center' nowrap colspan="2">New Bal + Sale + Damage</th>
            <th class='text-center' nowrap colspan="2">New Stock-เบิกเพิ่มในเดือน</th>
          </tr>
          <tr role="row">
            <th class='text-center' nowrap>&nbsp;</th>
            <th class='text-center' nowrap>รายละเอียดไทย</th>
            <th class='text-center' nowrap>ราคาต่อชิ้น</th>
            <th class='text-center' nowrap>ยอดยกมา</th>
            <th class='text-center' nowrap>New Stock ผ้าใหม่ยกมา</th>
            <th class='text-center' nowrap>ซื้อเพิ่มในเดือน</th>
            <th class='text-center' nowrap>&nbsp;</th>
            <th class='text-center' nowrap>มูลค่า</th>
            <th class='text-center' nowrap>จำนวน</th>
            <th class='text-center' nowrap>มูลค่า</th>
            <th class='text-center' nowrap>รวมยอด</th>
            <th class='text-center' nowrap>รวมมูลค่า</th>
            <th class='text-center' nowrap>บนวอร์ด</th>
            <th class='text-center' nowrap>ห้องคนไข้</th>
            <th class='text-center' nowrap>ห้องผ้า</th>
            <th class='text-center' nowrap>ผ้าเปื้อน</th>
            <th class='text-center' nowrap>ผ้าสะอาด</th>
            <th class='text-center' nowrap>OPD</th>
            <th class='text-center' nowrap>ยอดตรวจนับ</th>
            <th class='text-center' nowrap>มูลค่าตรวจนับ</th>
            <th class='text-center' nowrap>ยอดขายผู้ป่วย</th>
            <th class='text-center' nowrap>มูลค่า</th>
            <th class='text-center' nowrap>ยอดชำรุด</th>
            <th class='text-center' nowrap>มูลค่า</th>
            <th class='text-center' nowrap>รวมยอด</th>
            <th class='text-center' nowrap>รวมมูลค่า</th>
            <th class='text-center' nowrap colspan="3">Linen Loss / Month</th>
            <th class='text-center' nowrap>รวมยอด</th>
            <th class='text-center' nowrap>รวมมูลค่า</th>
            <th class='text-center' nowrap>รวมยอด</th>
            <th class='text-center' nowrap>รวมมูลค่า</th>

          </tr>
          <tr role="row">
            <th class='text-center' nowrap>No.</th>
            <th class='text-center' nowrap>Thai Description</th>
            <th class='text-center' nowrap>Price/ pcs</th>
            <th class='text-center' nowrap>LCS</th>
            <th class='text-center' nowrap>NLS</th>
            <th class='text-center' nowrap>NLS ซื้อเพิ่ม</th>
            <th class='text-center' nowrap>Total NLS</th>
            <th class='text-center' nowrap>Balance Amount</th>
            <th class='text-center' nowrap>New Qty</th>
            <th class='text-center' nowrap>Amount</th>
            <th class='text-center' nowrap>Total Qty</th>
            <th class='text-center' nowrap>Total Amount</th>
            <th class='text-center' nowrap>Shelf (Ward)</th>
            <th class='text-center' nowrap>Pt. Room</th>
            <th class='text-center' nowrap>Shelf (Linens Room)</th>
            <th class='text-center' nowrap>Dirty</th>
            <th class='text-center' nowrap>Clean</th>
            <th class='text-center' nowrap>OPD</th>
            <th class='text-center' nowrap>Stock Count Qty</th>
            <th class='text-center' nowrap>Stock Count Amount</th>
            <th class='text-center' nowrap>Sale Qty</th>
            <th class='text-center' nowrap>Sale Amount</th>
            <th class='text-center' nowrap>Sale Qty</th>
            <th class='text-center' nowrap>Sale Amount</th>
            <th class='text-center' nowrap>Total Qty</th>
            <th class='text-center' nowrap>Total Amount</th>
            <th class='text-center' nowrap>Gain/Loss</th>
            <th class='text-center' nowrap>Amount</th>
            <th class='text-center' nowrap>% Loss</th>
            <th class='text-center' nowrap>Total Qty</th>
            <th class='text-center' nowrap>Total Amount</th>
            <th class='text-center' nowrap>Total Qty</th>
            <th class='text-center' nowrap>Total Amount</th>
          </tr>
        </thead>
        <tbody id="body_table">
        </tbody>
      </table>


    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <div class="row mt-4 mb-2" style="width: 80%;margin-left:2px;">
        <input type="text" class="form-control" style="font-size:24px;width:29%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
        <div class="search_custom ml-3">
          <div class="search_1 d-flex justify-content-start">
            <button class="btn" onclick="ShowDocument()">
              <i class="fas fa-search mr-2"></i>
              <?php echo $array['search'][$language]; ?>
            </button>
          </div>
        </div>
        <div class="search_custom ml-2">
          <div class="circle11 d-flex justify-content-start">
            <button class="btn" onclick="SelectDocument()" id="btn_show" disabled='true'>
              <i class="fas fa-paste mr-2 pt-1"></i>
              <?php echo $array['show'][$language]; ?>
            </button>
          </div>
        </div>
      </div>
      <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" cellspacing="0" role="grid">
        <thead id="theadsum" style="font-size:11px;">
          <tr role="row">
            <th nowrap>&nbsp;</th>
            <th nowrap><?php echo $array['docno'][$language]; ?></th>
            <th nowrap><?php echo $array['date'][$language]; ?></th>
            <th nowrap>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="TableDocument_body"> </tbody>
      </table>
    </div>
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