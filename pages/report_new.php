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

้
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>report</title>
  <?php include_once('../assets/import/css.php'); ?>
</head>

<body>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)">รายงาน</a></li>
    <li class="breadcrumb-item active">report</li>
  </ol>

  <div class="col-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tab_head1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab_head1" aria-selected="true">รายงาน</a>
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
                      ประเภท
                    </label>
                    <select class="form-control col-sm-7  " style="font-size:22px;" id="selectReport">
                      <option value="0"><?php echo $array['r' . 0][$language]; ?></option>
                      <option value=1><?php echo "1. &nbsp; " . $array['r' . 1][$language]; ?></option>
                      <option value=2><?php echo "2. &nbsp; " . $array['r' . 3][$language]; ?></option>
                      <option value=3><?php echo "3. &nbsp; " . $array['r' . 4][$language]; ?></option>
                      <option value=4><?php echo "4. &nbsp; " . $array['r' . 6][$language]; ?></option>
                      <option value=5><?php echo "5. &nbsp; " . $array['r' . 8][$language]; ?></option>
                      <option value=6><?php echo "6. &nbsp; " . $array['r' . 9][$language]; ?></option>
                      <option value=7><?php echo "7. &nbsp; " . $array['r' . 15][$language]; ?></option>
                      <option value=8><?php echo "8. &nbsp; " . $array['r' . 18][$language]; ?></option>
                      <option value=9><?php echo "9. " . $array['r' . 22][$language]; ?></option>
                      <option value=10><?php echo "10. " . $array['r' . 23][$language]; ?></option>
                      <option value=11><?php echo "11. " . $array['r' . 24][$language]; ?></option>
                      <option value=12><?php echo "12. " . $array['r' . 25][$language]; ?></option>
                      <option value=13><?php echo "13. " . $array['r' . 26][$language]; ?></option>
                      <option value=14><?php echo "14. " . $array['r' . 27][$language]; ?></option>
                      <option value=15><?php echo "15. " . $array['r' . 31][$language]; ?></option>
                      <option value=16><?php echo "16. " . $array['r' . 29][$language]; ?></option>
                      <option value=17><?php echo "17. " . $array['r' . 32][$language]; ?></option>
                      <option value=18><?php echo "18. " . 'Monitoring SAP'             ?></option>
                      <option value=19><?php echo "19. " . 'Usage Detail New'             ?></option>
                      <option <?php if ($PmID != 6 && $PmID  != 1) {
                                echo "hidden";
                              } ?> value=20><?php echo "20. " . $array['r' . 31][$language]; ?></option>
                      <option <?php if ($PmID != 6 && $PmID  != 1) {
                                echo "hidden";
                              } ?> value=21><?php echo "21. " . $array['r' . 1][$language]; ?></option>
                      <option <?php if ($PmID != 6 && $PmID  != 1) {
                                echo "hidden";
                              } ?> value=22><?php echo "22. " . $array['r' . 3][$language]; ?></option>
                      <option <?php if ($PmID != 6 && $PmID  != 1) {
                                echo "hidden";
                              } ?> value=23><?php echo "23. " . 'Usage Detail New'             ?></option>
                      <option value=24><?php echo "24. " . 'Extra Delivery Report'             ?></option>
                      <option value=25><?php echo "25. " . 'รายงานสรุปการปรับแก้ไขยอด Par'             ?></option>
                      <option value=26><?php echo "26. " . 'รายงานสรุปสรุปประวัติการร้องขอเมนูเรียกเก็บผ้าเปื้อน'             ?></option>
                      <option value=27><?php echo "27. " . 'รายงานสรุปสรุปประวัติการร้องขอเมนูย้ายแผนก'             ?></option>
                      <option value=28><?php echo "28. " . 'รายงานสรุปสรุปประวัติการร้องขอเมนูการร้องขออื่นๆ'             ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6" id="colSite">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      โรงพยาบาล
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectSite"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colFac">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      โรงงานซัก
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectFac"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colDep">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      แผนก
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectDep"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colGroup">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      Group
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectGroup"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colItem">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      รายการ
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectItem"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colRound">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      รอบ
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectRound"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colCategory">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      หมวดหมู่
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectCategory"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colRoundLinen">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      รอบส่งผ้า
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectRoundLinen"></select>
                  </div>
                </div>
                <div class="col-md-6" id="colUsageType">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label">
                      Report Type
                    </label>
                    <select class="form-control col-sm-7 select2 custom-select" style="font-size:22px;" id="selectUsageType">
                      <option value="1">Usage Existing</option>
                      <option value="2">Usage Detail</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row" id="rowSearchDate">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-left " style="font-size:24px;"><?php echo $array['format'][$language]; ?></label>
                    <div style="margin-top : 9px;">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="RadioDay" name="radioFormat" value='day' onclick="showSearchDate()" class="custom-control-input radioFormat ">
                        <label class="custom-control-label lableformat " style="margin-bottom:10px" for="RadioDay"> <?php echo $array['day'][$language]; ?></label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="RadioMonth" name="radioFormat" value='month' onclick="showSearchDate()" class="custom-control-input radioFormat">
                        <label class="custom-control-label lableformat" for="RadioMonth"> <?php echo $array['month'][$language]; ?></label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="RadioYear" name="radioFormat" value='year' onclick="showSearchDate()" class="custom-control-input radioFormat">
                        <label class="custom-control-label lableformat" for="RadioYear"> <?php echo $array['year'][$language]; ?></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row' id="rowSearchDay">
                    <label class="col-sm-4	 col-form-label text-left" style=""><?php echo $array['formatdate'][$language]; ?></label>
                    <div style="margin-top : 7px;">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="RadioOneDay" name="formatDay" value='1' onclick="showDay()" class="custom-control-input formatDay" checked>
                        <label class="custom-control-label" for="RadioOneDay"><?php echo $array['oneday'][$language]; ?></label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="RadioSomeDay" name="formatDay" onclick="showDay()" value='2' class="custom-control-input formatDay">
                        <label class="custom-control-label" for="RadioSomeDay"><?php echo $array['manyday'][$language]; ?></label>
                      </div>
                    </div>
                  </div>
                  <div class='form-group row' id="rowSearchMonth">
                    <label class="col-sm-4 col-form-label text-left "><?php echo $array['formatmonth'][$language]; ?></label>
                    <div style="margin-top : 7px;">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="formatMonth" value='1' class="custom-control-input formatDay" checked>
                        <label class="custom-control-label" for="chkonemonth"><?php echo $array['onemonth'][$language]; ?></label>
                      </div>
                    </div>
                  </div>
                  <div class='form-group row' id="rowSearchYear">
                    <div class="col-sm-4 col-form-label text-left">
                      <?php echo $array['year'][$language]; ?>
                    </div>
                    <div class="col-sm-8 p-0">
                      <input style="font-size: 22px;" type="text" class="form-control datepicker-here only " id="year" data-min-view="years" data-view="years" data-date-format="yyyy" autocomplete="off" value="" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleaseyear'][$language]; ?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" id="rowShowDate">
                <div class="col-md-6" id="myDay" style="margin-top : -10px;">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-left"><?php echo $array['choosedate'][$language]; ?></label>
                    <input style="font-size: 22px;" type="text" class="form-control col-sm-7 datepicker-here only" data-language='<?php echo $language ?>' required id="oneday" data-date-format="dd-mm-yyyy" autocomplete="off" value="" placeholder="<?php echo $array['pleaseday'][$language]; ?>">
                    <label class="col-sm-4 col-form-label text-left" id="textto"><?php echo $array['to'][$language]; ?></label>
                    <input style="font-size: 22px;" type="text" class="form-control col-sm-7 datepicker-here only" data-language='<?php echo $language ?>' id="someday" data-date-format="dd-mm-yyyy" autocomplete="off" value="" placeholder="<?php echo $array['pleaseday'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-6" id="myMonth">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label text-left"><?php echo $array['month'][$language]; ?></label>
                    <input style="font-size: 22px;" type="text" class="form-control col-sm-8 datepicker-here only" id="onemonth" data-min-view="months" data-view="months" data-date-format="MM-yyyy" value="" autocomplete="off" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleasemonth'][$language]; ?>">
                  </div>
                </div>
                <script>
                  var someday = $('#someday').datepicker({}).data('datepicker');
                  $('#oneday').datepicker({
                    onSelect: function(fd, date) {
                      someday.update('minDate', date)
                    }
                  })

                  $("#oneday").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
                    onSelect: function(dateText) {
                      $('#oneday').removeClass('border-danger');
                      $('#rem7').hide();
                    }
                  });

                  $("#someday").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
                    onSelect: function(dateText) {
                      $('#someday').removeClass('border-danger');
                      $('#rem8').hide();
                    }
                  });

                  $("#onemonth").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
                    onSelect: function(dateText) {
                      $('#onemonth').removeClass('border-danger');
                      $('#rem9').hide();
                    }
                  });

                  $("#somemonth").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
                    onSelect: function(dateText) {
                      $('#somemonth').removeClass('border-danger');
                      $('#rem10').hide();
                    }
                  });

                  $("#year").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
                    onSelect: function(dateText) {
                      $('#year').removeClass('border-danger');
                      $('#rem11').hide();
                    }
                  });
                </script>
              </div>
            </div>
          </div>
          <div class=" mt-3  d-flex justify-content-end col-12">
            <div class="mhee">
              <div class="d-flex justify-content-center" style="margin-right: 6rem!important;">
                <div class="search_1 d-flex justify-content-center" id="bSeach2">
                  <button class="btn" id="bSeach">
                    <i class="fas fa-search"></i>
                    <div>
                      <?php echo $array['search'][$language]; ?>
                    </div>
                  </button>
                </div>
              </div>
            </div>


          </div>
        </div>

        <div class="col-12 mt-5">
          <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_Dep" width="100%" cellspacing="0" role="grid" style="">
            <thead id="theadsum" style="font-size:24px;">
              <tr role="row" id='tr_1'>
                <th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
                <th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
                <th style='width: 35%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
                <th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
                <th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
              </tr>
            </thead>
            <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
            </tbody>
          </table>
        </div>





      </div>
    </div>
  </div>



  <?php include_once('../assets/import/js.php'); ?>
  <script>
    $(document).ready(function(e) {
      $("#rowSearchDay").hide();
      $("#rowSearchMonth").hide();
      $("#rowSearchYear").hide();
      $("#rowSearchDate").hide();
      $("#rowShowDate").hide();
      $("#myMonth").hide();
      $("#myDay").hide();



      $("#colSite").hide();
      $("#colFac").hide();
      $("#colDep").hide();
      $("#colGroup").hide();
      $("#colItem").hide();
      $("#colRound").hide();
      $("#colRoundLinen").hide();
      $("#colUsageType").hide();
      $("#colCategory").hide();


      $("#selectReport").change(function() {
        switchReport($(this).val());
      });

      $("#selectSite").change(function() {
        GetDep();
        GetFac();
        GetGroup();
        GetItem();
        GetRound();
        GetRoundLinen();
      });




      GetSite();

      setTimeout(() => {
        GetDep();
        GetFac();
        GetGroup();
        GetItem();
        GetRound();
        GetRoundLinen();
        GetCategory();
      }, 300);

    }).mousemove(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    function showDay() {
      var radioValue = $('input[name=formatDay]:checked').val();
      if (radioValue == 2) {
        $("#textto").show();
        $("#someday").show();
      } else {
        $("#textto").hide();
        $("#someday").hide();
      }
    }

    function switchReport(report_id) {
      if (report_id == 0) {
        $("#rowShowDate").hide();
        $("#rowSearchDate").hide();
        $("#colSite").hide();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 1) {
        $("#rowShowDate").show();
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").show();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 2) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 3) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").show();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 4) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 5) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 6) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").show();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 7) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 8) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 9) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 10) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 11) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 12) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 13) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 14) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 15) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").show();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").show();
      } else if (report_id == 16) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").show();
        $("#colItem").show();
        $("#colRound").hide();
        $("#colRoundLinen").show();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 17) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").show();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").show();
      } else if (report_id == 18) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 19) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").hide();
        $("#colItem").show();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").show();
        $("#colCategory").hide();
      } else if (report_id == 20) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").show();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").show();
      } else if (report_id == 21) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 22) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").show();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 23) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").hide();
        $("#colItem").show();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").show();
        $("#colCategory").hide();
      } else if (report_id == 24) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 25) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").hide();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 26) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 27) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      } else if (report_id == 28) {
        $("#rowShowDate").show();
        $("#rowSearchDate").show();
        $("#colSite").show();
        $("#colFac").hide();
        $("#colDep").show();
        $("#colGroup").hide();
        $("#colItem").hide();
        $("#colRound").hide();
        $("#colRoundLinen").hide();
        $("#colUsageType").hide();
        $("#colCategory").hide();
      }
    }

    function showSearchDate() {
      var radioValue = $('input[name=radioFormat]:checked').val();

      if (radioValue == 'day') {
        $("#rowSearchDay").show();
        $("#myDay").show();
        $("#rowShowDate").show();

        $("#rowSearchMonth").hide();
        $("#rowSearchYear").hide();
        showDay();
      } else if (radioValue == 'month') {
        $("#rowSearchDay").hide();
        $("#rowSearchMonth").show();
        $("#myDay").hide();
        $("#rowShowDate").hide();
        $("#rowSearchYear").hide();
      } else if (radioValue == 'year') {
        $("#rowSearchDay").hide();
        $("#rowSearchMonth").hide();
        $("#rowSearchYear").show();
        $("#myDay").hide();
        $("#rowShowDate").hide();
      }
    }

    $("#bSeach").click(function() {
      if ($("#selectReport").val() == '1') {
        if ($("#selectSite").val() == '-') {
          showDialogFailed("กรุณาเลือกโรงพยาบาล");
          return;
        }
        if ($("#selectFac").val() == '0') {
          showDialogFailed("กรุณาเลือกโรงซัก");
          return;
        }
      } else if ($("#selectReport").val() == 'Report_payDepartment') {
        if ($("#selectSite").val() == '') {
          showDialogFailed("กรุณาเลือกโรงพยาบาล");
          return;
        }
      } else if ($("#selectReport").val() == 'Report_Monitoring') {
        if ($("#selectSite").val() == '') {
          showDialogFailed("กรุณาเลือกโรงพยาบาล");
          return;
        }
      } else if ($("#selectReport").val() == 'Report_Stock') {
        if ($("#selectSite").val() == '') {
          showDialogFailed("กรุณาเลือกโรงพยาบาล");
          return;
        }
      }
      searchReport();
    })

    function searchReport() {
      $.ajax({
        url: "process/report.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'searchReport',
          'selectReport': $("#selectReport").val(),
          'selectFac': $("#selectFac").val(),
          'selectSite': $("#selectSite").val(),
          'selectDep': $("#selectDep").val(),
          'selectGroup': $("#selectGroup").val(),
          'selectItem': $("#selectItem").val(),
          'selectRound': $("#selectRound").val(),
          'selectCategory': $("#selectCategory").val(),
          'selectRoundLinen': $("#selectRoundLinen").val(),
          'selectUsageType': $("#selectUsageType").val(),
          'FormatDate': $('input[name=radioFormat]:checked').val(),
          'FormatDay': $('input[name=formatDay]:checked').val(),
          'oneday': $('#oneday').val(),
          'someday': $('#someday').val(),
          'onemonth': $('#onemonth').val(),
          'year': $('#year').val(),
        },
        success: function(result) {
          var ObjData = JSON.parse(result);

        }
      });
    }




    function GetSite() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetSite',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);

          if (PmID == 1 || PmID == 6) {
            var option = `<option value="-" selected><?php echo $array['selecthospital'][$language]; ?></option>`;
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
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }
          $("#selectSite").html(option);
        }
      });
    }

    function GetDep() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetDep',
          'Site': Site,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="ALL" selected><?php echo $array['Alldep'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.DepCode}">${value.DepName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectDep").html(option);
        }
      });
    }

    function GetFac() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetFac',
          'Site': Site,
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="0" selected><?php echo $array['selectfactory'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.FacCode}">${value.FacName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectFac").html(option);
        }
      });
    }

    function GetGroup() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetGroup',
          'Site': Site,
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="0" selected><?php echo $array['Allgroup'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.GroupCode}">${value.GroupName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectGroup").html(option);
        }
      });
    }

    function GetItem() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetItem',
          'Site': Site,
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="0" selected><?php echo $array['Alllist'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.ItemCode}">${value.ItemName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectItem").html(option);
        }
      });
    }

    function GetRound() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetRound',
          'Site': Site,
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="ALL" selected><?php echo $array['Alldirty'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.id}">${value.TimeName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectRound").html(option);
        }
      });
    }

    function GetRoundLinen() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetRoundLinen',
          'Site': Site,
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="ALL" selected><?php echo $array['Alldirty'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.Time_ID}">${value.TimeName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectRoundLinen").html(option);
        }
      });
    }

    function GetCategory() {
      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';
      var Site = $("#selectSite").val();
      var option = "";
      $.ajax({
        url: "../process/report_new.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'GetCategory',
          'Site': Site,
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          option += `<option value="0" selected><?php echo $array['AllCatsub'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.CategoryCode}">${value.CategoryName}</option>`;
            });
          } else {
            option = `<option value="0"><?php echo $array['notfoundmsg'][$language]; ?></option>`;
          }

          $("#selectCategory").html(option);
        }
      });
    }

    function showDialogFailed(text) {
      swal({
        title: '',
        text: text,
        type: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        timer: 1000,
        confirmButtonText: 'Ok'
      });
    }
  </script>
</body>

</html>