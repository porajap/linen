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
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">

  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

 
  <title>
  Catalog Management
  </title>
  <?php include_once('../assets/import/css.php'); ?>

</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active">Catalog Management</li>
  </ol>
  <div id="wrapper">
    <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
      <i class="fas fa-angle-down"></i>
    </a>
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <div class="container-fluid">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Catalog Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false"><?php echo $array['detail'][$language]; ?></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12" id="div_table22">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:12px;margin-left: -2%;">
                                        <div class="row col-md-12">
                                          <div class="col-md-3">
                                              <div class="row" style="margin-left:5px;">
                                                  <select class="form-control col-md-12"  style="font-size:24px;" id="input_typeline" onchange="showData();">
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="row" style="margin-left: -6px;">
                                              <input id="txtSearch" type="text" autocomplete="off" class="form-control col-md-12" style="font-size:22px;" placeholder="ค้นหา รายการ" onkeyup="showData();">
                                              </div>
                                          </div>
                                          <div class="col-md-1 text-right">
                                            <div class="row" style="margin-left:-6px;">
                                            <div class="search_custom col-md-2">
                                          <div class="search_1 d-flex justify-content-start">
                                            <button class="btn" onclick="showData();">
                                              <i class="fas fa-search mr-2"></i>
                                              <?php echo $array['search'][$language]; ?>
                                            </button>
                                          </div>
                                        </div>
                                        </div>
                                       </div>
                                     </div>
                                  <!-- -------------------table----------------------    -->
                                      <div id="div_table">   
                                      </div>  
                                  
                                  <!-- -------------------------------------------- -->
                                  </div>  
                                </div>
                            </div> <!-- tag column 1 -->
                        </div>

                    </div>
<!-- ====================END Tap 1=========================================================================== -->

                    <!-- search document -->
                    <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                    
                        <div class="card-body" style="background-color: whitesmoke;height: 1000px;">
                          <div style="background-color: white; float: left;width: 45%; height: 12%;">
                            <div class="row" style="margin-left: 15px">
                              <div class="col">
                               <lable style="font-size:35px;">Description</lable>
                              </div>
                            </div>
                            <div class="row" >
                              <div class="col">
                                 <input id="txt_Description" type="text" autocomplete="off" class="form-control" style="font-size:24px;width: 70%;margin-left: 30px;" placeholder="รายละเอียด" >
                                 <input id="txt_ID" type="text" autocomplete="off" class="form-control" style="font-size:24px;width: 20%;margin-left: 30px;"  hidden>
                              </div>
                            </div>
                          </div>

                          <div  style="background-color: white; float: left;width: 50%;margin-left: 15px;height: 85%;">
                            <div class="row" style="margin-left: 15px">
                              <div class="col">
                               <lable style="font-size:35px;">Product Image</lable>
                              </div>
                            </div>
                            <div class="w3-content" style="max-width:1200px">
                            <div>
                              <img class="mySlides" src="../img/img_nature_wide.jpg" style="width:95%;height: 450px;margin-left: 20px;">
                              <img class="mySlides" src="../img/img_snow_wide.jpg" style="width:95%;display:none;height: 450px;margin-left: 20px;">
                              <img class="mySlides" src="../img/img_mountains_wide.jpg" style="width:95%;display:none;height: 450px;margin-left: 20px;">
                            </div>
                            <div class="col">
                               <lable style="font-size:35px;margin-top: 1%;">Other Image</lable>
                              </div>
                              <div class="row" style="margin-top: 0%;width: 98%;margin-left: 1%;">
                                <div class="col">
                                <input type="file" id="imageOne" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                                  <div><center>
                                    <label class="radio" style="margin-top:7px;width: 10%;"><input type="radio" class="classItemName" name="id_img" id="id_img1"   onclick="currentDiv(1)"><span class="checkmark" ></span></label>
                                    </center></div>
                                 
                                </div>
                                <div class="col">
                                <input type="file" id="imageTwo" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                                  <div><center>
                                    <label class="radio" style="margin-top:7px;width: 10%;"><input type="radio" class="classItemName" name="id_img" id="id_img2"  onclick="currentDiv(2)" ><span class="checkmark" ></span></label>
                                    </center></div>
                                 
                                </div>
                                <div class="col">
                                <input type="file" id="imageThree" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                                  <div><center>
                                    <label class="radio" style="margin-top:7px;width: 10%;"><input type="radio" class="classItemName" name="id_img" id="id_img3"  onclick="currentDiv(3)" ><span class="checkmark"></span></label>
                                    </center></div>
                                  
                                </div>
                              </div>
                            </div>
                          </div>


                          <div style="background-color: white; float: left;width: 45%;margin-top: -42%;height: 70.5%;">
                            <div class="row" style="margin-left: 15px">
                              <div class="col">
                               <lable style="font-size:35px;">Product Informailon</lable>
                              </div>
                            </div>
                            <div class="row" style="margin-left: 15px">
                              <div class="col-3">
                              <lable style="font-size:26px;">Product Name :</lable>
                              </div>
                              <div class="col" style="margin-left: -60px;">
                              <input id="txt_Name" type="text" autocomplete="off" class="form-control" style="font-size:24px;width: 70%;margin-left: 30px;" placeholder="ชื่อรายการ" >
                              </div>
                            </div>

                            <div class="row" style="margin-left: 15px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">Type Linen :</lable>
                              </div>
                              <div class="col" style="margin-left: -60px;">
                              <select id="typelinen_detail" class="form-control " style="font-size:24px;width: 70%;margin-left: 30px;"><option value="0" selected="">กรุณาเลือกประเภท</option><option value="12">หมี</option><option value="13">หมีหมี</option><option value="14">หมีหมีหมี</option><option value="15">54545</option></select>
                              </div>
                            </div>

                            <div class="row" style="margin-left: 15px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">Color /Size :</lable>
                              </div>
                              <div class="col" style="margin-left: -45px;">
                              <button style="background: none;border: none;" data-toggle="modal" onclick="openModalColor();"><i class="fas fa-plus-square text-info"></i></button>
                              </div>
                            </div>
                            <div class="row" style="margin-left: 105px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">:</lable>
                              </div>
                              <div id="div_Size" class="row" style="margin-left: -100px;">   
                              </div>
                            </div>
                            <div class="row" style="margin-left: 105px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">:</lable>
                              </div>
                              <div id="div_color" class="row" style="margin-left: -100px;">
                             
                              </div>
                            </div>

                            <div class="row" style="margin-left: 35px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">Supplier :</lable>
                              </div>
                              <div class="col" style="margin-left: -60px;">
                              <button style="background: none;border: none;" onclick="openModalSupplier();"><i class="fas fa-plus-square text-info"></i></button>
                              </div>
                            </div>
                            <div class="row" style="margin-left: 105px;margin-top: 10px;">
                              <div class="col-3">
                                <lable style="font-size:26px;">:</lable>
                              </div>
                              <div class="col" style="margin-left: -125px;">
                              <select id="supplier_detail" class="form-control " style="font-size:24px;width: 70%;margin-left: 30px;"></select>
                              </div>
                            </div>

                            <div class="row" style="margin-left: 70px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">Site :</lable>
                              </div>
                              <div class="col" style="margin-left: -85px;">
                              <button style="background: none;border: none;" data-toggle="modal" onclick="openModalSite();"><i class="fas fa-plus-square text-info"></i></button>
                              </div>
                            </div>
                            <div class="row" style="margin-left: 105px;margin-top: 10px;">
                              <div class="col-3">
                                <lable style="font-size:26px;">:</lable>
                              </div>
                              <div class="col" style="margin-left: -125px;">
                              <select id="site_detail" class="form-control " style="font-size:24px;width: 70%;margin-left: 30px;"></select>
                              </div>
                            </div>

                            <div class="row" style="margin-left: 15px;margin-top: 10px;">
                              <div class="col-3">
                              <lable style="font-size:26px;">Active Catalog :</lable>
                              </div>
                              <div class="col" style="margin-left: -20px;">
                              <input class="form-check-input" type="checkbox" value="" id="activecatalog" >
                              </div>
                            </div>

                          </div>

                          

                      


                        </div>
                      </div>

      <!-- ==========================END Tab 2=========================================================================================================== -->
                </div>
            </div>

      

  <!-- -----------------------------modal_supplier------------------------------------ -->
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
          <span style="font-size:30px;font-weight: bold; " class="ml-4 "><?php echo $array['items'][$language]; ?></span>
          <div id='row_supplier' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnSupplier" value="0" hidden>
          <!-- <button type="button" style="width:12%;" onclick="checkSupplier()" id="btn_SaveSupplier" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button> -->
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

 <!-- -----------------------------modal_site------------------------------------ -->
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
        <span style="font-size:30px;font-weight: bold; " class="ml-4 "><?php echo $array['items'][$language]; ?></span>
          <div id='row_site' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnSite" value="0" hidden>
          <!-- <button type="button" style="width:12%;" onclick="checkSite()" id="btn_SaveSite" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button> -->
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

 <!-- -----------------------------modal_color------------------------------------ -->
  <div class="modal fade" id="modal_color" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="form-check form-check-inline">
            <label class='radio '>
              <input type='radio' name='radio_size' id="size_s" value="S" class="form-check-input" onclick="openMasterColor('S');">
              <span class='checkmark'></span>
            </label>
            <label class="form-check-label ml-2" for="size_s">S</label>
          </div>

          <div class="form-check form-check-inline">
            <label class='radio '>
              <input type='radio' name='radio_size' id="size_m" value="M" class="form-check-input" onclick="openMasterColor('M');">
              <span class='checkmark'></span>
            </label>
            <label class="form-check-label ml-2" for="size_m">M</label>
          </div>

          <div class="form-check form-check-inline">
            <label class='radio '>
              <input type='radio' name='radio_size' id="size_l" value="L" class="form-check-input" onclick="openMasterColor('L');">
              <span class='checkmark'></span>
            </label>
            <label class="form-check-label ml-2" for="size_l">L</label>
          </div>

          <div class="form-check form-check-inline">
            <label class='radio '>
              <input type='radio' name='radio_size' id="size_xl" value="XL" class="form-check-input" onclick="openMasterColor('XL');">
              <span class='checkmark'></span>
            </label>
            <label class="form-check-label ml-2" for="size_xl">XL</label>
          </div>

          <div class="form-check form-check-inline">
            <label class='radio '>
              <input type='radio' name='radio_size' id="size_xxl" value="XXL" class="form-check-input" onclick="openMasterColor('XXL');">
              <span class='checkmark'></span>
            </label>
            <label class="form-check-label ml-2" for="size_xxl">XXL</label>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <select id="modalSelect_colorMaster" disabled class="form-control" style="font-size: 22px;" onchange="switchMasterColor()"></select>
              <input disabled class='form-control mt-2 ' id="modaltxt_colorDetail" class="form-control mt-3" style="font-size:22px;" />
              <div class="row px-3 mt-3">
                <button class="btn btn-success" style="width: 15%;border-radius: 50%;" onclick="saveColor()"><i class="fas fa-check mt-2"></i></button>
                <button class="btn btn-danger ml-2" id="modalColor_btnDelete" disabled onclick="deleteColor()" style="width: 15%;border-radius: 50%;"><i class="fas fa-times mt-2"></i></button>
              </div>
            </div>
            <div class="col-6" id="row_colorDetail">
              <input hidden id="txtColorId" type="text" autocomplete="off" class="form-control col-sm-7 " disabled style="font-size:22px;">
              <div id="row_color" style="display: -webkit-box;">
                <!-- <div class="px-3 ml-1" style="background-color:blueviolet;border-radius: 70%;cursor: pointer;"><br></div>
                <div class="px-3 ml-1" style="background-color:red;border-radius: 70%;cursor: pointer;"><br></div>
                <div class="px-3 ml-1" style="background-color:green;border-radius: 70%;cursor: pointer;"><br></div>
                <div class="px-3 ml-1" style="background-color:pink;border-radius: 70%;cursor: pointer;"><br></div>
                <div class="px-3 ml-1" style="background-color:yellow;border-radius: 70%;cursor: pointer;"><br></div> -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- -----------------------------modal_supplierAdd------------------------------------ -->
  <div class="modal fade" id="modal_supplierAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <div id='row_supplierAdd' class='row'></div>
        </div>
        <div class="modal-footer">
          <input type="text" id="countbtnSupplier" value="0" hidden>
          <button type="button" style="width:12%;" onclick="checkSupplier()" id="btn_SaveSupplier" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

<!-- -----------------------------modal_siteAdd------------------------------------ -->
  <div class="modal fade" id="modal_siteAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <div id='row_siteAdd' style="overflow-y: auto;height: 400px;" class='row'></div>
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

      $('.dropify').dropify();

      var drEvent = $('#imageOne').dropify();
      var drEventTwo = $('#imageTwo').dropify();
      var drEventThree = $('#imageThree').dropify();


    
      $('#color-picker').spectrum({
                type: "component"
      });

      $('#div_bt_edit').hide();


      get_typelinen();
      setTimeout(() => {
        showData();
        showMasterColor();
        showSupplierAdd();
        showSiteAdd();
      }, 200);

        $("#detail-tab").click(function(){
            
           
            
         });

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


    function get_typelinen(){
      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'get_typelinen'
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var StrTR = "";


              if (!$.isEmptyObject(ObjData)) {
                // var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
                var Str = "";
                $.each(ObjData, function(key, value) {
                  Str += "<option value=" + value.id + " >" + value.name_En + "</option>";
                });
              }

              $("#input_typeline").append(Str);
              $("#typelinen_detail").append(Str);
            }
          });
    }


    function showData() {
      var input_typeline = $("#input_typeline").val();
      var txtSearch = $("#txtSearch").val();
      var Page = "";
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showData',
          'input_typeline': input_typeline,
          'txtSearch': txtSearch,
          'Page':Page
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "<table id='TableItem' class='table table-striped table-bordered' style='width:100%;font-size:24px;'  width='100%' cellspacing='0' role='grid'  data-page-length='10'>"+
                        "<thead> <tr>"+
                         " <th style='width: 8%;text-align: center;' nowrap>ON.</th>"+
                         " <th style='width: 25%;text-align: left;' nowrap>NAME</th>"+
                          "<th style='width: 9%;text-align: center;' nowrap>Typelinen</th>"+
                          "<th style='width: 11%;text-align: center;' nowrap>SIZE</th>"+
                          "<th style='width: 15%;text-align: center;' nowrap>COLOR</th>"+
                          "<th style='width: 9%;text-align: center;' nowrap>SUPPLIER</th>"+
                          "<th style='width: 9%;text-align: center;' nowrap>SITE</th>"+
                          "<th style='width: 14%;text-align: center;' nowrap>ACTIVE</th>"+
                          "</tr>"+
                        "</thead>"+
                        "<tbody id='tbody' class='nicescrolled' style='font-size:24px;height:500px;'>" ;
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData.item, function(key, value) {
              //----------------color------------------------------
              var color =""
                if (!$.isEmptyObject(ObjData.color_c)) {
                  $.each(ObjData.color_c[value.id], function(key2, value2) {
                    color += "<div class='px-3 ml-1'  style='background-color: "+value2.color_detail+"; border-radius: 70%;  height: 35px; border: 2px solid; width:2%;'></div>";
                  });
                }
              //------------------size----------------------------
              var itemsize =""
                if (!$.isEmptyObject(ObjData.size)) {
                  $.each(ObjData.size[value.id], function(key3, value3) {
                    itemsize = value3;0
                  });
                }
              //----------------------------------------------
              var suppliep = " <a class='nav-link' id='suppliep'  href='javascript:void(0)' onclick='show_supplier(\"" + value.id + "\" );' > more </a>";
              var site = " <a class='nav-link' id='site'  href='javascript:void(0)' onclick='show_site(\"" + value.id + "\" );'> more </a>";
              var edit = " <a class='aButton' href='javascript:void(0)' onclick='edit_Detail(\"" + value.id + "\");'><img src='../img/edit.png' style='width:30px;margin-right: 30px;'></a>";
              if(value.IsActive==0){
                var IsActive ="<input type='checkbox' id='IsActive' style='argin-top: 1.5%;' disabled>";
              }else{
                var IsActive ="<input type='checkbox' id='IsActive' style='argin-top: 1.5%;' checked disabled>";
              }
              
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td style='width:8%;text-align: center;'>" + (key + 1) + "</td>" +
                "<td style='width:25%;text-align: left;'>" + value.itemCategoryName + "</td>" +
                "<td style='width:9%;text-align: center;'>" + value.typeLinen + "</td>" +
                "<td style='width:11%;text-align: center;'>"+itemsize+"</td>" +
                "<td style='width:15%;text-align: center;'><div class='row' style='margin-left: 16px;'>"+color+"</div></td>" +
                "<td style='width:9%;text-align: center;'>"+suppliep+"</td>" +
                "<td style='width:9%;text-align: center;'>"+site+"</td>" +
                "<td style='width:14%;text-align: center;'> " +edit + IsActive + " </td>" +
                "</tr>";
            });
          }

          StrTR += "</tbody></table>" 
          $('#div_table').html(StrTR);

          table_date();
         
        }
       
      
      });
      
    }

    function table_date(){
      $('#TableItem').DataTable({
              "bFilter": false
          });
          $(".dataTables_length").hide();
    }

    function show_supplier(id){
      $('#modal_supplier').modal('toggle');

      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_supplier',
              'id':id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var myDATA = "";
              if (!$.isEmptyObject(ObjData)) {
                  $.each(ObjData, function(kay, value) {
                    var supplierName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name}</span>`;
                    // var chksupplier = `<input type='checkbox' onclick='switchSupplier()' id='checkSupplier_${value.id}' value='${value.id}' class='mySupplier' style='top:-10%;' data-id='${value.id}' >`;
                    myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden; margin-left: 35px;'  nowrap>" + supplierName + "</div>";
                  });
              }else{
                    myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden; margin-left: 35px;'  nowrap> ไม่มีรายการ </div>";
              }

                $("#row_supplier").html(myDATA);
            }
          });

    }

    function show_site(id){
      $('#modal_site').modal('toggle');

      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_site',
              'id':id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var myDATA = "";
              if (!$.isEmptyObject(ObjData)) {
                  $.each(ObjData, function(kay, value) {
                    var supplierName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name}</span>`;
                    // var chksupplier = `<input type='checkbox' onclick='switchSupplier()' id='checkSupplier_${value.id}' value='${value.id}' class='mySupplier' style='top:-10%;' data-id='${value.id}' >`;
                    myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden; margin-left: 35px;'  nowrap>" + supplierName + "</div>";
                  });
              }else{
                    myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden; margin-left: 35px;'  nowrap> ไม่มีรายการ </div>";
              }

                $("#row_site").html(myDATA);
            }
          });

    }

    function edit_Detail(id){
      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'edit_Detail',
              'id':id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var myDATA = "";
              if (!$.isEmptyObject(ObjData)) {
                  $.each(ObjData, function(kay, value) {
                    $('#detail-tab').tab('show');

                    $("#txt_Description").val(value.discription);
                    $("#txt_ID").val(value.id);
                    $("#txt_Name").val(value.name);
                    $("#typelinen_detail").val(value.typeLinen);
                   

                    if(value.IsActive==0){
                      $("#activecatalog").prop("checked", false);
                    }else{
                      $("#activecatalog").prop("checked", true);
                    }
                    
                    show_colorDetail(value.id);
                    show_SizeDetail(value.id);
                    show_supplierDetail(value.id);
                    show_siteDetail(value.id);
                    $("#id_img1").prop("checked", true);
                   var imageOne= `${"../img/img_nature_wide.jpg"}`;
                   
                   var drEvent = $('#imageOne').dropify({
                    defaultFile: imageOne
                  });
                  drEvent = drEvent.data('dropify');
                  drEvent.resetPreview();
                  drEvent.clearElement();
                  drEvent.settings.defaultFile = imageOne;
                  drEvent.destroy();
                  drEvent.init();


                  });
              }

            }
      });
    }

    function show_colorDetail(id){
      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_colorDetail',
              'id':id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var mycolor = "";
              if (!$.isEmptyObject(ObjData)) {
                  $.each(ObjData, function(kay, value) {
                    mycolor += "<div class='px-3 ml-1'  style='background-color: "+value.color_detail+"; border-radius: 70%;  height: 35px; border: 2px solid; width:2%;'></div>";
                  });
              }else{
                   
              }

                $("#div_color").html(mycolor);
            }
          });
    }

    function show_SizeDetail(id){
      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_SizeDetail',
              'id':id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var mySize = "";
              if (!$.isEmptyObject(ObjData)) {
                  $.each(ObjData, function(kay, value) {
                    mySize += "<div class='px-3 ml-1'  style=' border-radius: 70%;  height: 35px; border: 2px solid; width:2%;text-align: center;'><lable style='text-align: center;margin-left: -4px;'>"+value.itemsize+"</lable></div>";
                  });
              }else{
                   
              }

                $("#div_Size").html(mySize);
            }
          });
    }
    
    function show_supplierDetail(id){
      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_supplierDetail',
              'id':id
            },
            success: function(result) {
            
              var ObjData = JSON.parse(result);
              $("#supplier_detail").empty();
              var Str = "";


              if (!$.isEmptyObject(ObjData)) {
                // var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
                
                $.each(ObjData, function(key, value) {
                  Str += "<option value=" + value.codeSupplier + " >" + value.name + "</option>";
                });
              }

              $("#supplier_detail").append(Str);
            }
          });
    }

    function show_siteDetail(id){
      $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_siteDetail',
              'id':id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              $("#site_detail").empty();
              var Str = "";


              if (!$.isEmptyObject(ObjData)) {
                // var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
              
                $.each(ObjData, function(key, value) {
                  Str += "<option value=" + value.id + " >" + value.name + "</option>";
                });
              }

              $("#site_detail").append(Str);
            }
          });
    }

    function showMasterColor() {
      $.ajax({
        url: "../process/catalogmanagement.php",
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
      $('input[name="radio_size"]').prop('checked', false);
      $("#modalSelect_colorMaster").val("0");
      $("#modalSelect_colorMaster").attr("disabled", true);
      $("#modal_color").modal('show');
    }

    function openMasterColor(size) {
      var txtItemId = $("#txt_ID").val();
      $('#modalColor_btnDelete').attr('disabled', true);
      $("#modalSelect_colorMaster").attr("disabled", false);
      $("#modalSelect_colorMaster").val("0");
      $('#modaltxt_colorDetail').val("");
      $("#modaltxt_colorDetail").attr("disabled", true);
      $("#modaltxt_colorDetail").spectrum({
        type: "component"
      });
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'openMasterColor',
          'size': size,
          'txtItemId': txtItemId,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          $("#row_color").empty();
          if (!$.isEmptyObject(ObjData)) {
            var option = ``;
            $.each(ObjData, function(kay, value) {

              option += `<div class="px-3 ml-1 classColorDetail" id="colorDetail_` + value.id + `"  onclick="showColorDetail('${value.color_detail}','${value.color_master}','${value.id}')" style="background-color:${value.color_detail};border-radius: 70%;cursor: pointer;height: 35px;"><br></div>`;

            });
          }
          $("#row_color").append(option);
        }
      });
    }

    function switchMasterColor() {
      var colorMaster = $("#modalSelect_colorMaster").val();
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'switchMasterColor',
          'colorMaster': colorMaster,
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var masterColor = "";
          var detailColor = [];
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData, function(kay, value) {

              $('#modaltxt_colorDetail').val(value.color_master_code);
              masterColor = value.color_master_code;
              detailColor.push(value.color_code_detail);
            });

            setTimeout(() => {
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

    function showColorDetail(color_detail, color_master, id) {
      $('#modalSelect_colorMaster').val(color_master);
      $(".classColorDetail").css('border', 'none');
      $("#colorDetail_" + id).css('border', '2px solid');
      $('#txtColorId').val(id);
      $('#modalColor_btnDelete').attr('disabled', false);

      setTimeout(() => {
        $.ajax({
          url: "../process/catalogmanagement.php",
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

    function saveColor() {
      var colorMaster = $("#modalSelect_colorMaster").val();
      var colorDetail = $("#modaltxt_colorDetail").val();
      var txtItemId = $("#txt_ID").val();
      var radioSize = $('input[name="radio_size"]:checked').val();
      var txtColorId = $('#txtColorId').val();
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'saveColor',
          'colorMaster': colorMaster,
          'colorDetail': colorDetail,
          'txtItemId': txtItemId,
          'radioSize': radioSize,
          'txtColorId': txtColorId,
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
            $('#txtColorId').val("");
            openMasterColor(radioSize);
            show_colorDetail(txtItemId);
            show_SizeDetail(txtItemId);
          }, 1700);


        }
      });

    }

    function deleteColor() {
      var radioSize = $('input[name="radio_size"]:checked').val();
      var txtColorId = $('#txtColorId').val();
      var txtItemId = $("#txt_ID").val();
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'deleteColor',
          'txtColorId': txtColorId,
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
            $('#txtColorId').val("");
            openMasterColor(radioSize);
            show_colorDetail(txtItemId);
            show_SizeDetail(txtItemId);
          }, 1700);


        }
      });
    }

    function openModalSupplier() {
      // var txtItemName = $("txtItemName").val();
      $("#modal_supplierAdd").modal('show');
      var txtItemId = $("#txt_ID").val();

      $.ajax({
        url: "../process/catalogmanagement.php",
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

    function showSupplierAdd() {
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showSupplierAdd',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var supplierName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name}</span>`;
              var chksupplier = `<input type='checkbox' onclick='switchSupplier()' id='checkSupplier_${value.id}' value='${value.id}' class='mySupplier' style='top:-10%;' data-id='${value.id}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chksupplier + supplierName + "</div>";
            });
          }

          $("#row_supplierAdd").html(myDATA);


        }
      });
    }

    function checkSupplier() {
      var SupplierArray = [];
      var txtItemId = $("#txt_ID").val();
      $(".mySupplier:checked").each(function() {
        SupplierArray.push($(this).val());
      });
      $.ajax({
        url: "../process/catalogmanagement.php",
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

          show_supplierDetail(txtItemId);
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

      // var numRow = $("#countbtnSupplier").val();
      // if (numRow == i) {
      //   $("#countbtnSupplier").val(0);
      //   $('#btn_SaveSupplier').attr('disabled', true);
      // } else {
      //   $("#countbtnSupplier").val(i);
      //   $('#btn_SaveSupplier').attr('disabled', false);
      // }
    }

    function openModalSite() {
      var txtItemName = $("txtItemName").val();
      $("#modal_siteAdd").modal('show');
      var txtItemId = $("#txt_ID").val();

      $.ajax({
        url: "../process/catalogmanagement.php",
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

    function showSiteAdd() {
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showSiteAdd',
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var siteName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name}</span>`;
              var chksite = `<input type='checkbox' onclick='switchSite()' id='checkSite_${value.HptCode}' value='${value.HptCode}' class='mySite' style='top:-10%;' data-id='${value.HptCode}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chksite + siteName + "</div>";
            });
          }

          $("#row_siteAdd").html(myDATA);


        }
      });
    }

    function checkSite() {
      var SiteArray = [];
      var txtItemId = $("#txt_ID").val();
      $(".mySite:checked").each(function() {
        SiteArray.push($(this).val());
      });
      $.ajax({
        url: "../process/catalogmanagement.php",
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
          show_siteDetail(txtItemId);
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

    function showimg(id) {
      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showimg',
          'id':id
        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          if (!$.isEmptyObject(ObjData)) {
            var myDATA = "";
            $.each(ObjData, function(kay, value) {
              var supplierName = `<span class='ml-4' style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name}</span>`;
              var chksupplier = `<input type='checkbox' onclick='switchSupplier()' id='checkSupplier_${value.id}' value='${value.id}' class='mySupplier' style='top:-10%;' data-id='${value.id}' >`;
              myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;'  nowrap>" + chksupplier + supplierName + "</div>";
            });
          }

          $("#row_supplierAdd").html(myDATA);


        }
      });
    }

//------------------------------------------------------------------------------------------------
    function currentDiv(n) {
      showDivs(slideIndex = n);
    }

    function showDivs(n) {
      var i;
      var x = document.getElementsByClassName("mySlides");
      var dots = document.getElementsByClassName("demo");
      if (n > x.length) {slideIndex = 1}
      if (n < 1) {slideIndex = x.length}
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" w3-opacity-off", "");
      }
      x[slideIndex-1].style.display = "block";
      dots[slideIndex-1].className += " w3-opacity-off";
    }
    


  </script>
  <style>
    .pagination {
      float: right;
    }

    .dataTables_info{
      margin-left: 2%;
      font-size:24px;
    }
    /* .dataTables_length{
      margin-left: 70%;
    } */
    .pagination a {
      color: black;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
    }

    .pagination a.active {
      background-color: #1659a2;
      color: white;
      border-radius: 5px;
    }

    .pagination a:hover:not(.active) {
      background-color: #ddd;
      border-radius: 5px;
    }
  </style>

</body>

</html>