<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$HptName = $_SESSION['HptName'];
$FacCode = $_SESSION['FacCode'];
$DepCode = $_SESSION['DepCode'];
$GroupCode = $_SESSION['GroupCode'];
$DocnoXXX = $_GET['DocNo'];
if ($Userid == "") {
  header("location:../index.html");
}


$language = $_SESSION['lang'];

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

  <title>shelfcount</title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">
  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <link href="../css/report.css" rel="stylesheet">
  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <link href="../css/responsive.css" rel="stylesheet">

  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>

  <?php if ($language == 'th') { ?>
    <script src="../datepicker/dist/js/datepicker.js"></script>
  <?php } else if ($language == 'en') { ?>
    <script src="../datepicker/dist/js/datepicker-en.js"></script>
  <?php } ?>
  <!-- =================================== -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
  <script src="../datepicker/dist/js/datepicker.th.js"></script>
  <script src="../fontawesome/js/fontawesome.min.js"></script>

</head>

<body id="page-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['report']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['report']['title'][$language]; ?></li>
  </ol>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="row px-3">
        <div class="col-12">
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['type'][$language]; ?></label>
                  <select class="form-control col-sm-8 " id="typereport" style="font-size:22px;" onchange="disabled_fill();">
                    <option value="0"><?php echo $array['r' . 0][$language]; ?></option>
                    <option value=1><?php echo "1. &nbsp; " . $array['r' . 1][$language]; ?></option>
                    <option value=3><?php echo "2. &nbsp; " . $array['r' . 3][$language]; ?></option>
                    <option value=4><?php echo "3. &nbsp; " . $array['r' . 4][$language]; ?></option>
                    <option value=6><?php echo "4. &nbsp; " . $array['r' . 6][$language]; ?></option>
                    <!-- <option value=7><?php echo "5. &nbsp; " . $array['r' . 7]['en']; ?></option> -->
                    <option value=8><?php echo "5. &nbsp; " . $array['r' . 8][$language]; ?></option>
                    <option value=9><?php echo "6. &nbsp; " . $array['r' . 9][$language]; ?></option>
                    <option value=15><?php echo "7. &nbsp; " . $array['r' . 15][$language]; ?></option>
                    <option value=18><?php echo "8. &nbsp; " . $array['r' . 18][$language]; ?></option>
                    <option value=22><?php echo "9. " . $array['r' . 22][$language]; ?></option>
                    <option value=23><?php echo "10. " . $array['r' . 23][$language]; ?></option>
                    <option value=24><?php echo "11. " . $array['r' . 24][$language]; ?></option>
                    <option value=25><?php echo "12. " . $array['r' . 25][$language]; ?></option>
                    <option value=26><?php echo "13. " . $array['r' . 26][$language]; ?></option>
                    <option value=27><?php echo "14. " . $array['r' . 27][$language]; ?></option>
                    <!-- <option value=28><?php echo "15. " . $array['r' . 28][$language]; ?></option> -->
                    <option value=31><?php echo "15. " . $array['r' . 31][$language]; ?></option>
                    <option value=29><?php echo "16. " . $array['r' . 29][$language]; ?></option>
                    <!-- <option value=30><?php echo "17. " . $array['r' . 30][$language]; ?></option> -->
                    <option value=32><?php echo "18. " . $array['r' . 32][$language]; ?></option>
                    <option value=33><?php echo "19. " . 'Monitoring SAP'             ?></option>
                    <option value=34><?php echo "20. " . 'Usage Detail New'           ?></option>
                  </select>
                  <label id="alertReport" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['factory'][$language]; ?></label>
                  <select class="form-control col-sm-8 bo" id="factory" style="font-size:22px;"></select>
                  <label id="alertFactory" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class='form-group row '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['side'][$language]; ?></label>
                  <select class="form-control col-sm-8" id="hotpital" style="font-size:22px;"></select>
                  <label id="alertSite" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
              <div class="col-6 ">
                <div class='form-group row  '>
                  <label class="col-sm-3 col-form-label text-left " style="font-size:24px;"><?php echo $array['department'][$language]; ?></label>
                  <select class="form-control col-sm-8  " style="font-size:22px;" id="department">
                  </select>
                  <label id="alertDepartment" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class='form-group row '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;">Group</label>
                  <select class="form-control col-sm-8" id="grouphpt" onchange="departmentWhere();" style=" font-size:22px;">
                  </select><label id="alertGroup" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
              <div class="col-6 ">
                <div class='form-group row  '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['item'][$language]; ?></label>
                  <select class="form-control col-sm-8 select2" style="font-size:22px;" id="item" onchange="show_item();">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class='form-group row '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;">รอบ</label>
                  <select class="form-control col-sm-8" id="time_dirty" style=" font-size:22px;">
                  </select>
                  <label id="alertRound" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
              <div class="col-6 ">
                <div class='form-group row  '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['category'][$language]; ?></label>
                  <select class="form-control col-sm-8" id="category" style=" font-size:22px;" disabled="true">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class='form-group row '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;">รอบส่งผ้า</label>
                  <select class="form-control col-sm-8" id="time_express" style=" font-size:22px;" disabled="true">
                  </select>
                </div>
              </div>
              <div class="col-6 ">
                <div class='form-group row  '>
                  <label class="col-sm-3 col-form-label text-left" style="font-size:24px;">Report type</label>
                  <select class="form-control col-sm-8" id="type_usage_detail" style=" font-size:22px;">
                    <option value="1">Usage Existing</option>
                    <option value="2">Usage Detail</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class='form-group row' id="chk">
                  <label class="col-sm-3 col-form-label text-left " style="font-size:24px;"><?php echo $array['format'][$language]; ?></label>
                  <div style="margin-top : 9px;">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chkday" name="radioFormat" value='1' onclick="showdate()" class="custom-control-input radioFormat ">
                      <label class="custom-control-label lableformat " style="margin-bottom:10px" for="chkday"> <?php echo $array['day'][$language]; ?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chkmonth" name="radioFormat" value='2' onclick="showdate()" class="custom-control-input radioFormat">
                      <label class="custom-control-label lableformat" for="chkmonth"> <?php echo $array['month'][$language]; ?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chkyear" name="radioFormat" value='3' onclick="showdate()" class="custom-control-input radioFormat">
                      <label class="custom-control-label lableformat" for="chkyear"> <?php echo $array['year'][$language]; ?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <p id="text1" onchange="blank_format();"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class='form-group row ' id="showday">
                  <label class="col-sm-3	 col-form-label text-left" style=""><?php echo $array['formatdate'][$language]; ?></label>
                  <div class="mt-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chkoneday" name="formatDay" value='1'  class="custom-control-input formatDay" checked>
                      <label class="custom-control-label" for="chkoneday"><?php echo $array['oneday'][$language]; ?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chksomeday" name="formatDay" value='2'  class="custom-control-input formatDay">
                      <label class="custom-control-label" for="chksomeday"><?php echo $array['manyday'][$language]; ?></label>
                    </div>
                  </div>
                </div>
                <div class='form-group row' id="showmonth">
                  <label class="col-sm-3 col-form-label text-left "><?php echo $array['formatmonth'][$language]; ?></label>
                  <div class="mt-2">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chkonemonth" name="formatMonth" value='1'  class="custom-control-input formatDay" checked>
                      <label class="custom-control-label" for="chkonemonth"><?php echo $array['onemonth'][$language]; ?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="chksomemonth" name="formatMonth" value='2'  class="custom-control-input formatDay">
                      <label class="custom-control-label" for="chksomemonth"><?php echo $array['manymonth'][$language]; ?></label>
                    </div>
                  </div>
                </div>
                <div class='form-group row' id="showyear">
                  <div class="col-sm-3 col-form-label text-left">
                    <?php echo $array['year'][$language]; ?>
                  </div>
                  <div class="col-sm-8 p-0">
                    <input type="text" class="form-control datepicker-here only " id="year" data-min-view="years" data-view="years" data-date-format="yyyy" autocomplete="off" value="" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleaseyear'][$language]; ?>">
                  </div>
                  <label id="alertYear" style="margin-top: -7.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6" id="myDay" style="margin-top : -10px;">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label text-left"><?php echo $array['choosedate'][$language]; ?></label>
                  <input type="text" class="form-control col-sm-8 datepicker-here only" data-language='<?php echo $language ?>' required id="oneday" data-date-format="dd-mm-yyyy" autocomplete="off" value="" placeholder="<?php echo $array['pleaseday'][$language]; ?>">
                  <label id="rem7" style="margin-top: -7.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                  <label class="col-sm-3 col-form-label text-left" id="textto"><?php echo $array['to'][$language]; ?></label>
                  <input type="text" class="form-control col-sm-8 datepicker-here only" data-language='<?php echo $language ?>' id="someday" data-date-format="dd-mm-yyyy" autocomplete="off" value="" placeholder="<?php echo $array['pleaseday'][$language]; ?>">
                  <label id="rem8" style="margin-top: -6.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>
              <div class="col-md-6" id="myMonth">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label text-left"><?php echo $array['month'][$language]; ?></label>
                  <input type="text" class="form-control col-sm-8 datepicker-here only" id="onemonth" data-min-view="months" data-view="months" data-date-format="MM-yyyy" value="" autocomplete="off" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleasemonth'][$language]; ?>">
                  <label id="rem9" style="margin-top: -7.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                  <label class="col-sm-3 col-form-label text-left" id="textto2"><?php echo $array['to'][$language]; ?></label>
                  <input type="text" class="form-control col-sm-8 datepicker-here only" id="somemonth" data-min-view="months" data-view="months" data-date-format="MM-yyyy" value="" autocomplete="off" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleasemonth'][$language]; ?>">
                  <label id="rem10" style="margin-top: -6.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</body>