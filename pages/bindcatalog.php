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
  <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="refresh" content="300">
  <title>
    bindcatalog
  </title>
  <?php include_once('../assets/import/css.php'); ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">

</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active">bindcatalog</li>
  </ol>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <div class="row">
        <div class="col-md-12">
          <div class="container-fluid">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
              <div class="row">
                <div class="col-md-10">
                  <div class="row" style="margin-left:5px;">
                    <select class="form-control col-md-3 ml-2" id="selectMainCategoryTop" style="font-size:22px;" onchange="changeMainCategory('top')">
                      <option value="">กรุณาเลือกหมวดหมู่หลัก</option>
                      <option value="ALL">ALL Category</option>
                      <option value="DIS">DisPose</option>
                    </select>
                    <select class="form-control col-md-3 ml-2" id="selectCategoryTop" style="font-size:22px;" onchange="changeCategory('top')"></select>
                    <input id="txtSearch" type="text" autocomplete="off" class="form-control col-md-3 ml-2" style="font-size:22px;" placeholder="<?php echo $array['Searchitem'][$language]; ?>">
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
                        <th nowrap style="width:10%"><?php echo $array['no'][$language]; ?></th>
                        <th nowrap style="width:29.13%"><?php echo $array['bind-list_th'][$language]; ?></th>
                        <th nowrap style="width:29.13%"><?php echo $array['bind-list_en'][$language]; ?></th>
                        <th nowrap style="width:21.65%"><?php echo $array['bind-category'][$language]; ?></th>
                        <!-- <th nowrap style="width:21.65%">รายละเอียด</th> -->
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

              <div class="row mt-3">

                <div class="col-md-4">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['bind-category'][$language]; ?></label>
                    <select id="selectcategory" class="form-control col-sm-7" style="font-size:22px;" onchange="changeCategory()">
                      <!-- <option value="P">Patient Shirt</option>
                      <option value="S">Staff Uniform</option>
                      <option value="F">Flat Sheet</option>
                      <option value="T">Towel</option>
                      <option value="G">Green Linen</option>
                      <option value="O">Other</option> -->
                    </select>
                    <label id="alert_selectcategory" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['bind-thailist'][$language]; ?></label>
                    <input id="txtItemName" type="text" autocomplete="off" class="form-control col-sm-7 thonly" style="font-size:22px;">
                    <input hidden id="txtItemId" type="text" autocomplete="off" class="form-control col-sm-7 " disabled style="font-size:22px;">
                    <label id="alert_txtItemName" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['bind-thaidetail'][$language]; ?></label>
                    <input id="txtDiscription" type="text" autocomplete="off" class="form-control col-sm-7 thonly" style="font-size:22px;">
                    <label id="alert_txtDiscription" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
              </div>

              <div class="row mt-1">
                <div class="col-md-4">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['bind-category'][$language]; ?></label>
                    <select id="selectmaincategory" class="form-control col-sm-7" style="font-size:22px;" onchange="changeMainCategory()">
                      <option value="">กรุณาเลือกหมวดหมู่หลัก</option>
                      <option value="ALL">ALL Category</option>
                      <option value="DIS">DisPose</option>
                    </select>
                    <label id="alert_selectmaincategory" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['bind-englishlist'][$language]; ?></label>
                    <input id="txtItemNameEn" type="text" autocomplete="off" class="form-control col-sm-7 enonly" style="font-size:22px;">
                    <label id="alert_txtItemNameEn" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>

                <div class="col-md-4">

                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['bind-englishdetail'][$language]; ?></label>
                    <input id="txtDiscriptionEn" type="text" autocomplete="off" class="form-control col-sm-7 enonly" style="font-size:22px;">
                    <label id="alert_txtDiscriptionEn" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                  </div>
                </div>
              </div>



              <div class="row mt-3">
                <div class="col-4">
                  <input type="file" id="imageOne" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                </div>
                <div class="col-4">
                  <input type="file" id="imageTwo" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                </div>
                <div class="col-4">
                  <input type="file" id="imageThree" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                </div>
              </div>

              <div class="row mt-3" id="row_DropDown">

                <div class="col-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $array['bind-size_Color'][$language]; ?></label>
                    <button style="background: none;border: none;" data-toggle="modal" onclick="openModalColor();"><i class="fas fa-plus-square text-info"></i></button>
                  </div>

                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Supplier</label>
                    <button style="background: none;border: none;" onclick="openModalSupplier();"><i class="fas fa-plus-square text-info"></i></button>
                  </div>
                </div>

                <div class="col-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $array['side'][$language]; ?></label>
                    <button style="background: none;border: none;" onclick="openModalSite();"><i class="fas fa-plus-square text-info"></i></button>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $array['fabric'][$language]; ?></label>
                    <button style="background: none;border: none;" onclick="openModalFabric();"><i class="fas fa-plus-square text-info"></i></button>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $array['thread_count'][$language]; ?></label>
                    <button style="background: none;border: none;" onclick="openModalThread_count();"><i class="fas fa-plus-square text-info"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div> <!-- tag column 2 -->

  <div class="modal fade" id="modal_fabric" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Fabric</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type='checkbox' style="top:-4px;" id="checkallFabric" onclick='checkallFabric()'><span style="font-size:30px; " class="ml-4 "><?php echo $array['selectall'][$language]; ?></span>
          <div id='row_fabric' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnFabric" value="0" hidden>
          <button type="button" style="width:12%;" onclick="checkFabric()" id="btn_SaveFabric" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_thread_count" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">thread count</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type='checkbox' style="top:-4px;" id="checkallThread_count" onclick='checkallThread_count()'><span style="font-size:30px; " class="ml-4 "><?php echo $array['selectall'][$language]; ?></span>
          <div id='row_thread_count' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnThread_count" value="0" hidden>
          <button type="button" style="width:12%;" onclick="checkThread_count()" id="btn_SaveThread_count" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_color" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5>color</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="overflow-y: auto;max-height: 335px;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">ADD</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">DELETE</a>
            </li>
          </ul>

          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="row px-3" id="modalColor_Header">
              </div>
              <div class="row mt-3">
                <div class="col-6">
                  <select id="modalSelect_colorMaster" disabled class="form-control" style="font-size: 22px;" onchange="switchMasterColor()"></select>
                  <select id="modalSelect_supplier" disabled class="form-control mt-2" style="font-size: 22px;" onchange="switchMasterColor()"></select>
                  <input disabled class='form-control mt-2 ' id="modaltxt_colorDetail" class="form-control mt-3" style="font-size:22px;" onchange="switchColorShowRemark()"/>
                  <input disabled class='form-control mt-2 ' id="modaltxt_remarkDetail" class="form-control mt-3" style="font-size:22px;" />
                  <div class="row px-3 mt-3">
                    <button class="btn btn-success" id="modalColor_btnSave" style="width: 20%;" onclick="saveColor()">บันทึก</button>
                    <button class="btn btn-danger ml-2" id="modalColor_btnDelete" disabled onclick="deleteColor()" style="width: 20%;">ลบ</button>
                    <button class="btn btn-warning ml-2" id="modalColor_btnResume" disabled onclick="resumeColor()" style="width: 20%;color:white;">ทำใหม่</button>
                  </div>
                </div>
                <div class="col-6" id="row_colorDetail">
                  <input hidden id="txtColorId" type="text" autocomplete="off" class="form-control col-sm-7 " disabled style="font-size:22px;">

                  <div id="row_color" style="display: -webkit-box;" class="row">
                    <!-- <div class="px-3 ml-1" style="background-color:blueviolet;border-radius: 70%;cursor: pointer;"><br></div>
                    <div class="px-3 ml-1" style="background-color:red;border-radius: 70%;cursor: pointer;"><br></div>
                    <div class="px-3 ml-1" style="background-color:green;border-radius: 70%;cursor: pointer;"><br></div>
                    <div class="px-3 ml-1" style="background-color:pink;border-radius: 70%;cursor: pointer;"><br></div>
                    <div class="px-3 ml-1" style="background-color:yellow;border-radius: 70%;cursor: pointer;"><br></div> -->
                  </div>
                </div>
              </div>


            </div>
            <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
              <div class="row mt-3 px-3">
                <div id="row_size" style="display: contents;">
                  <!-- <div style="border: 1px solid;width: 50px;text-align: center;border-radius: 25px;cursor: pointer;" class="ml-2">S</div>
                  <div style="border: 1px solid;width: 50px;text-align: center;border-radius: 25px;cursor: pointer;" class="ml-2">L</div> -->
                </div>
              </div>
              <div class="row mt-3 px-3">
                <button class="btn btn-danger ml-2" id="modalSize_btnDelete" disabled onclick="deleteSize()" style="width: 20%;">ลบ</button>
                <button class="btn btn-warning ml-2" id="modalSize_btnResume" disabled onclick="resumeSize()" style="width: 20%;color:white;">ทำใหม่</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_supplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Supplier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type='checkbox' style="top:-4px;" id="checkallSupplier" onclick='checkallSupplier()'><span style="font-size:30px; " class="ml-4 "><?php echo $array['selectall'][$language]; ?></span>
          <div id='row_supplier' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnSupplier" value="0" hidden>
          <button type="button" style="width:12%;" onclick="checkSupplier()" id="btn_SaveSupplier" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_site" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Site</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type='checkbox' style="top:-4px;" id="checkallSite" onclick='checkallSite()'><span style="font-size:30px; " class="ml-4 "><?php echo $array['selectall'][$language]; ?></span>
          <div id='row_site' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnSite" value="0" hidden>
          <button type="button" style="width:12%;" onclick="checkSite()" id="btn_SaveSite" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>




  <?php include_once('../assets/import/js.php'); ?>
  <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(e) {
      $("#row_DropDown").hide();
      $("#alert_txtItemName").hide();
      $("#alert_selectcategory").hide();
      $("#alert_selectmaincategory").hide();
      $("#alert_txtDiscription").hide();
      $("#alert_txtItemNameEn").hide();
      $("#alert_txtDiscriptionEn").hide();

      $('#modaltxt_colorDetail').spectrum({
        type: "component"
      });

      $('.dropify').dropify();

      var drEvent = $('#imageOne').dropify();
      var drEventTwo = $('#imageTwo').dropify();
      var drEventThree = $('#imageThree').dropify();

      $("#txtItemNameEn").change(function() {
        $("#txtItemNameEn").removeClass("border-danger");
        $("#alert_txtItemNameEn").hide();
      });

      $("#txtItemName").change(function() {
        $("#txtItemName").removeClass("border-danger");
        $("#alert_txtItemName").hide();
      });

      $("#selectcategory").change(function() {
        $("#selectcategory").removeClass("border-danger");
        $("#alert_selectcategory").hide();
      });

      $("#txtDiscription").change(function() {
        $("#txtDiscription").removeClass("border-danger");
        $("#alert_txtDiscription").hide();
      });

      $("#txtDiscriptionEn").change(function() {
        $("#txtDiscriptionEn").removeClass("border-danger");
        $("#alert_txtDiscriptionEn").hide();
      });


      setTimeout(() => {
        showMasterColor();
        showSupplier();
        showSite();
        showSize();
        showFacbric();
        showThread_count();
        getTypeLinen();
        getSupplier();
      }, 200);

      setTimeout(() => {
        showData();
      }, 400);



      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
      });

      $('.enonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9. ]/g, ''); //<-- replace all other than given set of values
      });

      $('.thonly').on('input', function() {
        this.value = this.value.replace(/[^ก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ0-9. ]/g, ''); //<-- replace all other than given set of values
      });

      $('#txtSearch').keyup(function(e) {
        if (e.keyCode == 13) {
          showData();
        }
      });


    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    var drEvent = $('#imageOne').dropify();
    var drEventTwo = $('#imageTwo').dropify();
    var drEventThree = $('#imageThree').dropify();

    drEvent.on('dropify.afterClear ', function(event, element) {
      $('#imageOne').data("value", "default");
    });

    drEventTwo.on('dropify.afterClear ', function(event, element) {
      $('#imageTwo').data("value", "default");
    });

    drEventThree.on('dropify.afterClear ', function(event, element) {
      $('#imageThree').data("value", "default");
    });

    function changeCategory(text) {
      var categoryTop = $("#selectCategoryTop").val();
      var categoryLow = $("#selectcategory").val();

      if (text == 'top') {
        $("#selectcategory").val(categoryTop);
        showData();
      } else {
        $("#selectCategoryTop").val(categoryLow);
        showData();
      }
    }

    function changeMainCategory(text) {
      var categoryMainTop = $("#selectMainCategoryTop").val();
      var categoryMainLow = $("#selectmaincategory").val();

      if (text == 'top') {
        $("#selectmaincategory").val(categoryMainTop);
        showData();
        // clearSwitchCategory();
      } else {
        $("#selectMainCategoryTop").val(categoryMainLow);
        showData();
        // clearSwitchCategory();
      }
    }


    // function clearSwitchCategory(){

    //   $("#txtItemName").val("");
    //   $("#txtItemName").removeClass("border-danger");
    //   $("#alert_txtItemName").hide();

    //   $("#txtItemNameEn").val("");
    //   $("#txtItemNameEn").removeClass("border-danger");
    //   $("#alert_txtItemNameEn").hide();

    //   $("#txtDiscription").val("");
    //   $("#txtDiscription").removeClass("border-danger");
    //   $("#alert_txtDiscription").hide();

    //   $("#txtDiscriptionEn").val("");
    //   $("#txtDiscriptionEn").removeClass("border-danger");
    //   $("#alert_txtDiscriptionEn").hide();

    //   $("#txtItemId").val("");
    //   $(".dropify-clear").click();
    //   $("#row_DropDown").hide(300);
    //   $("#selectcategory").val("0");
    //   $("#selectCategoryTop").val("0");

    // }

    function cleartxt() {
      $("#txtItemName").val("");
      $("#selectcategory").val("0");
      $("#selectCategoryTop").val("0");
      $("#selectmaincategory").val("");
      $("#selectMainCategoryTop").val("");
      $("#txtDiscription").val("");
      $("#txtDiscriptionEn").val("");
      $("#txtItemId").val("");
      $("#txtItemNameEn").val("");
      $("#txtItemNameEn").removeClass("border-danger");
      $("#alert_txtItemNameEn").hide();
      $(".classItemName").prop("checked", false);
      $('#bCancel').attr('disabled', true);
      $('#cancelIcon').addClass('opacity');
      $(".dropify-clear").click();
      $("#row_DropDown").hide(300);
      $("#txtDiscription").removeClass("border-danger");
      $("#alert_txtDiscription").hide();
      $("#txtDiscriptionEn").removeClass("border-danger");
      $("#alert_txtDiscriptionEn").hide();
      $("#txtItemName").removeClass("border-danger");
      $("#alert_txtItemName").hide();
    }

    function getTypeLinen() {

      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'getTypeLinen',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var option = `<option value="0" selected><?php echo $array['bind-selectCaregory'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.id}">${value.nametype}</option>`;
            });
          } else {
            option = `<option value="0">Data not found</option>`;
          }

          $("#selectcategory").html(option);
          $("#selectCategoryTop").html(option);


        }
      });
    }

    function getSupplier() {

      var lang = '<?php echo $language; ?>';
      var PmID = '<?php echo $PmID; ?>';

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'getSupplier',
          'lang': lang,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var option = `<option value="0" selected><?php echo $array['supplier-selectcompany'][$language]; ?></option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.id}">${value.name_supplier}</option>`;
            });
          } else {
            option = `<option value="0">Data not found</option>`;
          }

          $("#modalSelect_supplier").html(option);
        }
      });
    }

    function showSize() {
      var txtItemId = $("#txtItemId").val();
      // alert(txtItemId);
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showSize',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var data = "";
          var data_size = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              data += `<div class="form-check form-check-inline mt-3" style="align-items: end;">` +
                `<input type='checkbox' name='radio_size' id="size_${value.SizeName}" value="${value.SizeName}" class="form-check-input loopsize" onclick="openMasterColor('${value.SizeName}');">` +
                `<label class="form-check-label ml-2" for="size_${value.SizeName}">${value.SizeName}</label>` +
                `</div>`;


            });
          }

          $("#modalColor_Header").html(data);


        }
      });
    }

    function showData() {
      var selectcategory = $("#selectcategory").val();
      var txtSearch = $("#txtSearch").val();
      var selectmaincategory = $("#selectmaincategory").val();

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showData',
          'selectcategory': selectcategory,
          'selectmaincategory': selectmaincategory,
          'txtSearch': txtSearch,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(key, value) {
              if (value.discription == null) {
                value.discription = "";
              }
              if (value.itemCategoryNameEn == null) {
                value.itemCategoryNameEn = "";
              }
              var chkDoc = "<label class='radio' style='margin-top:7px'><input type='radio' class='classItemName' name='idSupplier' id='idItemName_" + key + "' value='" + value.id + "' onclick='showDetail(\"" + value.id + "\" , \"" + key + "\")' ><span class='checkmark'></span></label>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td style='width:10%;'>" + chkDoc + "</td>" +
                "<td style='width:10%;'>" + (key + 1) + "</td>" +
                "<td style='width:29.13%;'>" + value.itemCategoryName + "</td>" +
                "<td style='width:29.13%;'>" + value.itemCategoryNameEn + "</td>" +
                "<td style='width:21.65%;'> " + value.nameType + " </td>" +
                // "<td style='width:21.65%;'> " + value.discription + " </td>" +
                "</tr>";
            });
          }
          $('#tableDocument tbody').html(StrTR);
        }
      });
    }

    function deleteData() {
      var txtItemId = $("#txtItemId").val();

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
            url: "../process/bindcatalog.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'deleteData',
              'txtItemId': txtItemId,
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
      var previousValue = $('#idItemName_' + row).attr('previousValue');
      var name = $('#idItemName_' + row).attr('name');
      if (previousValue == 'checked') {
        $('#idItemName_' + row).removeAttr('checked');
        $('#idItemName_' + row).attr('previousValue', false);
        $('#idItemName_' + row).prop('checked', false);
        $('#bCancel').attr('disabled', true);
        $('#cancelIcon').addClass('opacity');
        $("#txtItemName").val("");
        $("#selectcategory").val("0");
        $("#selectmaincategory").val("");
        $("#txtDiscription").val("");
        $("#txtItemId").val("");
        $("#txtItemNameEn").val("");
        $("#txtDiscriptionEn").val("");
        $("#row_DropDown").hide(300);
        $(".dropify-clear").click();
      } else {
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('#idItemName_' + row).attr('previousValue', 'checked');
        $('#bCancel').attr('disabled', false);
        $('#cancelIcon').removeClass('opacity');
        $("#row_DropDown").show(300);
        $.ajax({
          url: "../process/bindcatalog.php",
          type: 'POST',
          data: {
            'FUNC_NAME': 'showDetail',
            'id': id,
          },
          success: function(result) {
            var ObjData = JSON.parse(result);
            if (!$.isEmptyObject(ObjData)) {
              $.each(ObjData.data, function(key, value) {
                if (value.discription == null) {
                  value.discription = "";
                }
                if (value.discriptionEn == null) {
                  value.discriptionEn = "";
                }
                $("#selectcategory").val(value.typeLinen);
                $("#selectmaincategory").val(value.mainCategory);
                $("#txtDiscription").val(value.discription);
                $("#txtDiscriptionEn").val(value.discriptionEn);
                $("#txtItemName").val(value.itemCategoryName);
                $("#txtItemNameEn").val(value.itemCategoryNameEn);

                $("#txtItemId").val(value.id);

                var imageOne = `${"../profile/catalog/"+value.imageOne}`;
                var imageTwo = `${"../profile/catalog/"+value.imageTwo}`;
                var imageThree = `${"../profile/catalog/"+value.imageThree}`;
                $(".dropify-clear").click();
                if (imageOne != "../profile/catalog/null") {

                  var drEvent = $('#imageOne').dropify({
                    defaultFile: imageOne
                  });
                  drEvent = drEvent.data('dropify');
                  drEvent.resetPreview();
                  drEvent.clearElement();
                  drEvent.settings.defaultFile = imageOne;
                  drEvent.destroy();
                  drEvent.init();
                } else {
                  // $(".dropify-clear").click();
                }

                if (imageTwo != "../profile/catalog/null") {
                  var drEvent = $('#imageTwo').dropify({
                    defaultFile: imageTwo
                  });
                  drEvent = drEvent.data('dropify');
                  drEvent.resetPreview();
                  drEvent.clearElement();
                  drEvent.settings.defaultFile = imageTwo;
                  drEvent.destroy();
                  drEvent.init();
                } else {
                  // $(".dropify-clear").click();
                }

                if (imageThree != "../profile/catalog/null") {
                  var drEvent = $('#imageThree').dropify({
                    defaultFile: imageThree
                  });
                  drEvent = drEvent.data('dropify');
                  drEvent.resetPreview();
                  drEvent.clearElement();
                  drEvent.settings.defaultFile = imageThree;
                  drEvent.destroy();
                  drEvent.init();
                } else {
                  // $(".dropify-clear").click();
                }

                setTimeout(() => {
                  $('#imageOne').data("value", imageOne);
                  $('#imageTwo').data("value", imageOne);
                  $('#imageThree').data("value", imageOne);
                }, 300);


              });

              showSizeDetail();
              // $.each(ObjData.size, function(key_size, value_size) {
              //   data_size += `<div  onclick="checkSizeDetail('${value_size.itemsize}')"  id="sizeDetail_` + value_size.itemsize + `" style="border: 1px solid;width: 70px;text-align: center;border-radius: 25px;cursor: pointer;" class="ml-2 mt-2 classSizeDetail">${value_size.itemsize}</div>`;
              // });
              // $("#row_size").html(data_size);
            }
          }
        });
      }
    }

    function saveData() {


      swal({
        title: '<?php echo $array['pleasewait'][$language]; ?>',
        text: '<?php echo $array['processing'][$language]; ?>',
        allowOutsideClick: false
      })
      swal.showLoading();
      var form_data = new FormData();
      var imageOne = $('#imageOne').prop('files')[0];
      var imageTwo = $('#imageTwo').prop('files')[0];
      var imageThree = $('#imageThree').prop('files')[0];
      var data_imageOne = $('#imageOne').data('value');
      var data_imageTwo = $('#imageTwo').data('value');
      var data_imageThree = $('#imageThree').data('value');
      var selectcategory = $("#selectcategory").val();
      var selectmaincategory = $("#selectmaincategory").val();
      var txtDiscription = $("#txtDiscription").val();
      var txtItemName = $("#txtItemName").val();
      var txtItemId = $("#txtItemId").val();
      var txtItemNameEn = $("#txtItemNameEn").val();
      var txtDiscriptionEn = $("#txtDiscriptionEn").val();




      if (selectcategory == "0") {
        swal({
          title: '',
          text: 'กรุณาระบุประเภท',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#selectcategory").addClass("border-danger");
        $("#alert_selectcategory").show();
        return;
      }

      if (txtItemNameEn == "") {
        swal({
          title: '',
          text: 'กรุณาระบุรายการอังกฤษ',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtItemNameEn").addClass("border-danger");
        $("#alert_txtItemNameEn").show();
        return;
      }

      if (txtItemName == "") {
        swal({
          title: '',
          text: 'กรุณาระบุรายการไทย',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtItemName").addClass("border-danger");
        $("#alert_txtItemName").show();
        return;
      }

      if (selectmaincategory == "") {
        swal({
          title: '',
          text: 'กรุณาระบุหมวดหมู่หลัก',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#selectmaincategory").addClass("border-danger");
        $("#alert_selectmaincategory").show();
        return;
      }

      if (txtDiscription == "") {
        swal({
          title: '',
          text: 'กรุณาระบุรายละเอียดไทย',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtDiscription").addClass("border-danger");
        $("#alert_txtDiscription").show();
        return;
      }

      if (txtDiscriptionEn == "") {
        swal({
          title: '',
          text: 'กรุณาระบุรายละเอียดอังกฤษ',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
        $("#txtDiscriptionEn").addClass("border-danger");
        $("#alert_txtDiscriptionEn").show();
        return;
      }

      form_data.append('FUNC_NAME', 'saveData');
      form_data.append('imageOne', imageOne);
      form_data.append('imageTwo', imageTwo);
      form_data.append('imageThree', imageThree);
      form_data.append('data_imageOne', data_imageOne);
      form_data.append('data_imageTwo', data_imageTwo);
      form_data.append('data_imageThree', data_imageThree);
      form_data.append('selectcategory', selectcategory);
      form_data.append('selectmaincategory', selectmaincategory);
      form_data.append('txtDiscription', txtDiscription);
      form_data.append('txtItemName', txtItemName);
      form_data.append('txtItemId', txtItemId);
      form_data.append('txtItemNameEn', txtItemNameEn);
      form_data.append('txtDiscriptionEn', txtDiscriptionEn);
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'checkName',
          'txtItemName': txtItemName,
          'txtItemNameEn': txtItemNameEn,
          'txtItemId': txtItemId,
        },
        success: function(result) {

          if (result == 'repeat') {
            swal({
              title: '',
              text: '<?php echo $array['Duplicatename'][$language]; ?>',
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
              url: "../process/bindcatalog.php",
              type: 'POST',
              dataType: 'text',
              cache: false,
              contentType: false,
              processData: false,
              data: form_data,
              success: function(result) {
                var ObjData = JSON.parse(result);
                $("#txtItemId").val(ObjData);
                swal({
                  title: '',
                  text: '<?php echo $array['savesuccess'][$language]; ?>',
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1500,
                });

                setTimeout(() => {
                  $("#row_DropDown").show(300);
                  // cleartxt();
                  showData();
                }, 1700);

              }
            });
          }

        }
      });


    }

    // color
    function showMasterColor() {
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showMasterColor',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var option = `<option value="0" selected>กรุณาเลือกสี</option>`;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              option += `<option value="${value.id}">${value.color_master_name}</option>`;
            });
          }

          $("#modalSelect_colorMaster").html(option);


        }
      });
    }

    function openModalColor() {
      // $('input[name="radio_size"]').prop('checked', false);
      // $("#row_color").empty();
      $("#modalSelect_colorMaster").val("0");
      $("#modalSelect_supplier").val("0");
      $('#modaltxt_colorDetail').val("");
      $('#modaltxt_remarkDetail').val("");
      $("#modaltxt_colorDetail").attr("disabled", true);
      $("#modaltxt_colorDetail").spectrum({
        type: "component"
      });
      // $("#modalSelect_colorMaster").attr("disabled", true);
      // $("#modalSelect_supplier").attr("disabled", true);

      $("#modalSelect_colorMaster").attr("disabled", false);
      $("#modalSelect_supplier").attr("disabled", false);
      $("#modal_color").modal('show');
      openMasterColor();
    }

    function openMasterColor(size) {
      var sizeArray = [];

      $(".loopsize:checked").each(function() {
        sizeArray.push($(this).val());
      });

      var txtItemId = $("#txtItemId").val();
      var modalSelect_colorMaster = $("#modalSelect_colorMaster").val();
      var supplier = $("#modalSelect_supplier").val();
      $('#modalColor_btnDelete').attr('disabled', true);
      $("#modalSelect_colorMaster").attr("disabled", false);
      $("#modalSelect_supplier").attr("disabled", false);
      $('#txtColorId').val("");
      // $("#modalSelect_colorMaster").val("0");
      // $("#modalSelect_supplier").val("0");
      // $('#modaltxt_colorDetail').val("");
      // $("#modaltxt_colorDetail").attr("disabled", true);
      // $("#modaltxt_colorDetail").spectrum({
      //   type: "component"
      // });

      $("#row_color").empty();
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'openMasterColor',
          'size': size,
          'txtItemId': txtItemId,
          'modalSelect_colorMaster': modalSelect_colorMaster,
          'supplier': supplier,
          'sizeArray': sizeArray,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var option = ``;
            $.each(ObjData, function(kay, value) {

              option += `<div class="row col-12 mt-1"><div class="classColorDetail col-md-2" id="colorDetail_` + value.id + `"  onclick="showColorDetail('${value.color_detail}','${value.color_master}','${value.id}')" style="background-color:${value.color_detail};border-radius: 70%;cursor: pointer;height: 35px;    border: 2px solid gray;"><br></div>` +
                `<div class="col-md-10" style="cursor: pointer;height: 35px;">${value.remark}</div></div>`;

            });
          }
          $("#row_color").html(option);
        }
      });
    }

    function switchMasterColor() {
      var colorMaster = $("#modalSelect_colorMaster").val();
      var supplier = $("#modalSelect_supplier").val();
      openMasterColor();
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'switchMasterColor',
          'colorMaster': colorMaster,
          'supplier': supplier,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var masterColor = "";
          var detailColor = [];
          $('#modaltxt_colorDetail').val("");
          $('#modaltxt_remarkDetail').val("");
          $("#modaltxt_colorDetail").attr("disabled", true);
          $("#modaltxt_colorDetail").spectrum({
            type: "component"
          });
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {

              $('#modaltxt_colorDetail').val(value.color_code_detail);
              $('#modaltxt_remarkDetail').val(value.remark);
              
              masterColor = value.color_code_detail;
              detailColor.push(value.color_code_detail);
            });

            setTimeout(() => {
              $("#modaltxt_colorDetail").attr("disabled", false);
              $("#modaltxt_colorDetail").spectrum({
                color: masterColor,
                type: "component",
                showPaletteOnly: "true",
                hideAfterPaletteSelect: "true",
                palette: [
                  detailColor
                ]
              });
            }, 300);

            $("#modaltxt_colorDetail").attr('disabled', false);
          }
        }
      });
    }

    function switchColorShowRemark(){
      var colorMaster = $("#modalSelect_colorMaster").val();
      var modaltxt_colorDetail = $("#modaltxt_colorDetail").val();
      var supplier = $("#modalSelect_supplier").val();

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'switchColorShowRemark',
          'colorMaster': colorMaster,
          'supplier': supplier,
          'modaltxt_colorDetail': modaltxt_colorDetail,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {

              $('#modaltxt_remarkDetail').val(value.remark);
              
            });
          }



        }
      });
      
    }
    
    function showColorDetail(color_detail, color_master, id) {
      // $('#modalSelect_colorMaster').val(color_master);
      $(".classColorDetail").css('border', '2px solid gray');
      $("#colorDetail_" + id).css('border', '2px solid');
      $('#txtColorId').val(color_detail);
      $('#modalColor_btnDelete').attr('disabled', false);
      $('#modalColor_btnResume').attr('disabled', false);
      $('#modalColor_btnSave').attr('disabled', true);

      setTimeout(() => {
        $.ajax({
          url: "../process/bindcatalog.php",
          type: 'POST',
          data: {
            'FUNC_NAME': 'switchMasterColor',
            'colorMaster': color_master,
          },
          success: function(result) {
            var ObjData = JSON.parse(result);
            var masterColor = "";
            var detailColor = [];
            if (!$.isEmptyObject(ObjData)) {
              $.each(ObjData, function(kay, value) {

                $('#modaltxt_colorDetail').val(color_detail);
                masterColor = value.color_master_code;
                detailColor.push(value.color_code_detail);
              });

              setTimeout(() => {
                $("#modaltxt_colorDetail").spectrum({
                  color: color_detail,
                  type: "component",
                  showPaletteOnly: "true",
                  hideAfterPaletteSelect: "true",
                  palette: [
                    detailColor
                  ]
                });
              }, 300);


              $("#modaltxt_colorDetail").attr('disabled', false);
            }



          }
        });
      }, 300);

    }
    function showSizeDetail() {

      var txtItemId = $("#txtItemId").val();
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showSizeDetail',
          'txtItemId': txtItemId,
        },
        success: function(result) {
          var data_size = "";
          $('input[name="radio_size"]').prop('checked', false);
          var ObjData = JSON.parse(result);
          var masterColor = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData.size, function(key_size, value_size) {
              data_size += `<div data-value='${value_size.itemsize}' onclick="checkSizeDetail('${value_size.itemsize}')"  id="sizeDetail_` + value_size.itemsize + `" style="border: 1px solid;width: 70px;text-align: center;border-radius: 25px;cursor: pointer;" class="ml-2 mt-2 classSizeDetail">${value_size.itemsize}</div>`;
              $(`#size_` + `${value_size.itemsize}`).prop("checked", true);

            });
          }
          $("#row_color").empty();
          $("#row_size").html(data_size);
          openMasterColor();
        }
      });
    }

    function checkSizeDetail(size) {

      $(".classSizeDetail").css('border', '2px solid gray');
      $("#sizeDetail_" + size).css('border', '2px solid');
      $(".classSizeDetail").removeClass('checkSize');
      $("#sizeDetail_" + size).addClass('checkSize');

      $('#modalSize_btnDelete').attr('disabled', false);
      $('#modalSize_btnResume').attr('disabled', false);
    }

    function resumeSize() {
      $('#modalSize_btnDelete').attr('disabled', true);
      $('#modalSize_btnResume').attr('disabled', true);
      $(".classSizeDetail").css('border', '2px solid gray');
      $(".classSizeDetail").removeClass('checkSize');
      $("#txtSizeId").val("");

    }

    function deleteSize() {
      var txtSizeId = $("div").find(".checkSize").data('value');
      var txtItemId = $("#txtItemId").val();

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
            url: "../process/bindcatalog.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'deleteSize',
              'txtItemId': txtItemId,
              'txtSizeId': txtSizeId,
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              if (!$.isEmptyObject(ObjData)) {
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
                  showSizeDetail();
                  $('#modalSize_btnDelete').attr('disabled', true);
                  $('#modalSize_btnResume').attr('disabled', true);
                }, 2000);
              }
            }
          });



        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })





    }

    function saveColor() {
      var sizeArray = [];
      $(".loopsize:checked").each(function() {
        sizeArray.push($(this).val());
      });
      var colorMaster = $("#modalSelect_colorMaster").val();
      var colorDetail = $("#modaltxt_colorDetail").val();
      var txtItemId = $("#txtItemId").val();
      var radioSize = $('input[name="radio_size"]:checked').val();
      var txtColorId = $('#txtColorId').val();

      if (sizeArray == "") {
        swal({
          title: '',
          text: 'กรุณาระบุ size',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });

        return;
      }

      if (colorDetail == "") {
        swal({
          title: '',
          text: 'กรุณาระบุสี',
          type: 'warning',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });

        return;
      }


      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'saveColor',
          'colorMaster': colorMaster,
          'colorDetail': colorDetail,
          'txtItemId': txtItemId,
          'radioSize': radioSize,
          'txtColorId': txtColorId,
          'sizeArray': sizeArray,
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

          setTimeout(() => {
            // $("#modal_color").modal('hide');
            $('#txtColorId').val("");
            openMasterColor(radioSize);
            showSizeDetail();
          }, 1700);


        }
      });

    }

    function deleteColor() {
      var sizeArray = [];
      $(".loopsize:checked").each(function() {
        sizeArray.push($(this).val());
      });
      var radioSize = $('input[name="radio_size"]:checked').val();
      var txtColorId = $('#txtColorId').val();
      var txtItemId = $("#txtItemId").val();
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'deleteColor',
          'txtColorId': txtColorId,
          'txtItemId': txtItemId,
          'sizeArray': sizeArray,
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
            $('#modalColor_btnDelete').attr('disabled', true);
            $('#modalColor_btnResume').attr('disabled', true);
            $('#modalColor_btnSave').attr('disabled', false);
            $('#txtColorId').val("");
            openMasterColor(radioSize);
          }, 1700);


        }
      });
    }

    function resumeColor() {
      setTimeout(() => {
        $('#modalColor_btnDelete').attr('disabled', true);
        $('#modalColor_btnResume').attr('disabled', true);
        $('#modalColor_btnSave').attr('disabled', false);
        $('#txtColorId').val("");
        openMasterColor();
      }, 300);
    }
    // 

    // supplier
    function openModalSupplier() {
      var txtItemName = $("txtItemName").val();
      $("#modal_supplier").modal('show');
      var txtItemId = $("#txtItemId").val();

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'openModalSupplier',
          'txtItemId': txtItemId,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $(".mySupplier").prop('checked', false);
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              $("#checkSupplier_" + value.codeSupplier).prop('checked', true);
            });
          }
          var count = 0;
          $(".mySupplier:checked").each(function() {
            count++;
          });

          if (count == $('.mySupplier').length) {
            $("#checkallSupplier").prop('checked', true);
          } else {
            $("#checkallSupplier").prop('checked', false);
          }
        }
      });
    }

    function showSupplier() {
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showSupplier',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var supplierName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name_Th}</span>`;
              var chksupplier = `<input type='checkbox' onclick='switchSupplier()' id='checkSupplier_${value.id}' value='${value.id}' class='mySupplier' style='top:-10%;' data-id='${value.id}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chksupplier + supplierName + "</div>";
            });
          }

          $("#row_supplier").html(myDATA);


        }
      });
    }

    function switchSupplier() {
      var select_all = document.getElementById('checkallSupplier'); //select all checkbox
      var checkboxes = document.getElementsByClassName("mySupplier"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.mySupplier:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
      // var count = 0;
      // $(".mySupplier:checked").each(function() {
      //   count++;
      // });
      // if (count == 0) {
      //   $('#btn_SaveSupplier').attr('disabled', true);
      // } else {
      //   $('#btn_SaveSupplier').attr('disabled', false);
      // }
    }

    function checkSupplier() {
      var SupplierArray = [];
      var txtItemId = $("#txtItemId").val();
      $(".mySupplier:checked").each(function() {
        SupplierArray.push($(this).val());
      });
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'checkSupplier',
          'SupplierArray': SupplierArray,
          'txtItemId': txtItemId,
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

          setTimeout(() => {
            $("#modal_supplier").modal('hide');
          }, 1500);
        }
      });

    }

    function checkallSupplier() {
      var select_all = document.getElementById('checkallSupplier'); //select all checkbox
      var checkboxes = document.getElementsByClassName("mySupplier"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.mySupplier:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
    }
    //



    function openModalFabric() {
      var txtItemName = $("txtItemName").val();
      $("#modal_fabric").modal('show');
      var txtItemId = $("#txtItemId").val();

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'openModalFabric',
          'txtItemId': txtItemId,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $(".myFabric").prop('checked', false);
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              $("#checkFabric_" + value.codeFabric).prop('checked', true);
            });
          }
          var count = 0;
          $(".myFabric:checked").each(function() {
            count++;
          });

          if (count == $('.myFabric').length) {
            $("#checkallFabric").prop('checked', true);
          } else {
            $("#checkallFabric").prop('checked', false);
          }
        }
      });
    }

    function showFacbric() {
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showFacbric',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var name_Fabric = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name_Fabric}</span>`;
              var chkfabric = `<input type='checkbox' onclick='switchFabric()' id='checkFabric_${value.id}' value='${value.id}' class='myFabric' style='top:-10%;' data-id='${value.id}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chkfabric + name_Fabric + "</div>";
            });
          }

          $("#row_fabric").html(myDATA);


        }
      });
    }

    function switchFabric() {
      var select_all = document.getElementById('checkallFabric'); //select all checkbox
      var checkboxes = document.getElementsByClassName("myFabric"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.myFabric:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
      // var count = 0;
      // $(".mySupplier:checked").each(function() {
      //   count++;
      // });
      // if (count == 0) {
      //   $('#btn_SaveSupplier').attr('disabled', true);
      // } else {
      //   $('#btn_SaveSupplier').attr('disabled', false);
      // }
    }

    function checkFabric() {
      var FabricArray = [];
      var txtItemId = $("#txtItemId").val();
      $(".myFabric:checked").each(function() {
        FabricArray.push($(this).val());
      });
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'checkFabric',
          'FabricArray': FabricArray,
          'txtItemId': txtItemId,
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

          setTimeout(() => {
            $("#modal_fabric").modal('hide');
          }, 1500);
        }
      });

    }

    function checkallFabric() {
      var select_all = document.getElementById('checkallFabric'); //select all checkbox
      var checkboxes = document.getElementsByClassName("myFabric"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.myFabric:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
    }
    //



    function openModalThread_count() {
      var txtItemName = $("txtItemName").val();
      $("#modal_thread_count").modal('show');
      var txtItemId = $("#txtItemId").val();

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'openModalThread_count',
          'txtItemId': txtItemId,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $(".myThread_count").prop('checked', false);
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              $("#checkThread_count_" + value.codeThread_count).prop('checked', true);
            });
          }
          var count = 0;
          $(".myThread_count:checked").each(function() {
            count++;
          });

          if (count == $('.myThread_count').length) {
            $("#checkallThread_count").prop('checked', true);
          } else {
            $("#checkallThread_count").prop('checked', false);
          }
        }
      });
    }

    function showThread_count() {
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showThread_count',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var name_Thread_count = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name_Thread}</span>`;
              var chkThread_count = `<input type='checkbox' onclick='switchThread_count()' id='checkThread_count_${value.id}' value='${value.id}' class='myThread_count' style='top:-10%;' data-id='${value.id}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chkThread_count + name_Thread_count + "</div>";
            });
          }

          $("#row_thread_count").html(myDATA);


        }
      });
    }

    function switchThread_count() {
      var select_all = document.getElementById('checkallThread_count'); //select all checkbox
      var checkboxes = document.getElementsByClassName("myThread_count"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.myThread_count:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
      // var count = 0;
      // $(".mySupplier:checked").each(function() {
      //   count++;
      // });
      // if (count == 0) {
      //   $('#btn_SaveSupplier').attr('disabled', true);
      // } else {
      //   $('#btn_SaveSupplier').attr('disabled', false);
      // }
    }

    function checkThread_count() {
      var Thread_countArray = [];
      var txtItemId = $("#txtItemId").val();
      $(".myThread_count:checked").each(function() {
        Thread_countArray.push($(this).val());
      });
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'checkThread_count',
          'Thread_countArray': Thread_countArray,
          'txtItemId': txtItemId,
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

          setTimeout(() => {
            $("#modal_thread_count").modal('hide');
          }, 1500);
        }
      });

    }

    function checkallThread_count() {
      var select_all = document.getElementById('checkallThread_count'); //select all checkbox
      var checkboxes = document.getElementsByClassName("myThread_count"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.myThread_count:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
    }
    //




    function openModalSite() {
      var txtItemName = $("txtItemName").val();
      $("#modal_site").modal('show');
      var txtItemId = $("#txtItemId").val();

      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'openModalSite',
          'txtItemId': txtItemId,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $(".mySite").prop('checked', false);
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {
              $("#checkSite_" + value.site).prop('checked', true);
            });
          }
          var count = 0;
          $(".mySite:checked").each(function() {
            count++;
          });

          if (count == $('.mySite').length) {
            $("#checkallSite").prop('checked', true);
          } else {
            $("#checkallSite").prop('checked', false);
          }
        }
      });
    }

    function showSite() {
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showSite',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var siteName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.HptName}</span>`;
              var chksite = `<input type='checkbox' onclick='switchSite()' id='checkSite_${value.HptCode}' value='${value.HptCode}' class='mySite' style='top:-10%;' data-id='${value.HptCode}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chksite + siteName + "</div>";
            });
          }

          $("#row_site").html(myDATA);


        }
      });
    }

    function switchSite() {
      var select_all = document.getElementById('checkallSite'); //select all checkbox
      var checkboxes = document.getElementsByClassName("mySite"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.mySite:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
      // var count = 0;
      // $(".mySupplier:checked").each(function() {
      //   count++;
      // });
      // if (count == 0) {
      //   $('#btn_SaveSupplier').attr('disabled', true);
      // } else {
      //   $('#btn_SaveSupplier').attr('disabled', false);
      // }
    }

    function checkSite() {
      var SiteArray = [];
      var txtItemId = $("#txtItemId").val();
      $(".mySite:checked").each(function() {
        SiteArray.push($(this).val());
      });
      $.ajax({
        url: "../process/bindcatalog.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'checkSite',
          'SiteArray': SiteArray,
          'txtItemId': txtItemId,
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
          setTimeout(() => {
            $("#modal_site").modal('hide');
          }, 1500);
        }
      });

    }

    function checkallSite() {
      var select_all = document.getElementById('checkallSite'); //select all checkbox
      var checkboxes = document.getElementsByClassName("mySite"); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) {
          if (this.checked == false) {
            select_all.checked = false;
          }
          if (document.querySelectorAll('.mySite:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }

      // var numRow = $("#countbtnSupplier").val();
      // if (numRow == i) {
      //   $("#countbtnSupplier").val(0);
      //   $('#btn_SaveSupplier').attr('disabled', true);
      // } else {
      //   $("#countbtnSupplier").val(i);
      //   $('#btn_SaveSupplier').attr('disabled', false);
      // }
    }
  </script>

</body>

</html>