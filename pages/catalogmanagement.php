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
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>


  <title>
    Catalog Management
  </title>
  <?php include_once('../assets/import/css.php'); ?>
  <style>
    .pagination {
      float: right;
    }

    .dataTables_info {
      margin-left: 2%;
      font-size: 24px;
    }

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

    .tag-inner li a {
      border-radius: 30px;
      border: 1px solid black;
      padding: 5px 25px;
      background: white;
      font-size: 18px;
    }

    .focusSize {
      border: 3px solid black !important;
      font-weight: bold;
    }

    a {
      color: inherit;
    }

    img,
    a,
    input,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      -webkit-transition: all 0.4s ease;
      -moz-transition: all 0.4s ease;
      transition: all 0.4s ease;
    }

    a,
    button,
    input {
      font-weight: 400;
    }

    a {
      text-decoration: none;
    }

    [role=button],
    a,
    area,
    button,
    input:not([type=range]),
    label,
    select,
    summary,
    textarea {
      -ms-touch-action: manipulation;
      touch-action: manipulation;
    }





    ul,
    ul li {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    li {
      text-align: -webkit-match-parent;
    }

    ul {
      list-style-type: disc;
    }

    ul.v_size>li {
      /* กำหนดรูปแบบให้กับเมนูเ */
      display: block;
      text-indent: 5px;
      float: left;
      text-align: center;
    }

    .f_size{
      font-size: 22px;
    }

    .dropify-wrapper {
      height: 100%;
    }

  </style>
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
          <li class="nav-item">
            <a class="nav-link" id="banner-tab" data-toggle="tab" href="#banner" role="tab" aria-controls="banner" aria-selected="false">Banner Header</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="store_location-tab" data-toggle="tab" href="#store_location" role="tab" aria-controls="store_location" aria-selected="false">Store Location</a>
          </li>
        </ul>
        <div id="div_btSave" style="margin-top: -50px;float: right;">
            <button type="button" id="btSave" style="width: 160px;height: 40px;" class="btn btn-outline-success" onclick="saveData_detail();">
              <p style="font-size: 26px;margin-top: 4px;"><?php echo $array['save'][$language]; ?></p>
            </button>
          </div>

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
                          <select class="form-control col-md-12" style="font-size:24px;" id="input_typeline" onchange="showData();">
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
          <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
            <div class="row mt-2">
              <div class="col-6">
                <div class="col-12">
                  <div class="card" style="min-height: 100px;">
                    <div class="form-group px-3">
                        <div style="float: right;margin-top: 1.5%;margin-bottom: 3%;" >
                          <input type="checkbox"  data-toggle="toggle" data-width="70" data-on="TH" data-off="EN" onchange="chk_lang();" id="chk_lang">
                        </div>
                      <h3 for="exampleInputEmail1">Description</h3>
                      <input type="text"  id="txt_Description_TH" class="form-control f_size thonly" aria-describedby="emailHelp" placeholder="Description TH">
                      <input type="text"  id="txt_Description_EN" class="form-control f_size enonly" aria-describedby="emailHelp" placeholder="Description EN">
                      <input type="text" id="txt_ID" class="form-control"  aria-describedby="emailHelp" hidden>
                      <input type="text" id="num_lang" class="form-control"  aria-describedby="emailHelp" hidden>
                    </div>
                  </div>
                </div>
                <div class="col-12 mt-2">
                  <div class="card" style="min-height: 600px;">
                    <div class="card-body">
                      <h3>
                        Product Information
                      </h3>
                      <div class="form-group row" id="div_nameTH">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Product Name TH :</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control f_size thonly" placeholder="Product Name TH" id="txt_NameTh">
                        </div>
                      </div>
                      <div class="form-group row" id="div_nameEH">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Product Name EN :</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control f_size enonly" placeholder="Product Name EN" id="txt_NameEn">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Type Linen :</label>
                        <div class="col-sm-9">
                          <select type="text" class="form-control f_size" id="typelinen_detail"></select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Color/Size :</label>
                        <div class="col-sm-9">
                          <button style="background: none;border: none;" data-toggle="modal" onclick="openModalColor();"><i class="fas fa-plus-square text-info f_size"></i></button>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                          <!-- <select type="text" class="form-control"></select> -->
                          <ul class="tag-inner v_size" id="ul_size"></ul>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9 row" id="div_color" style="margin-left: 0%">
                          <!-- <select type="text" class="form-control"></select> -->
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Supplier :</label>
                        <div class="col-sm-9">
                          <button style="background: none;border: none;" data-toggle="modal" onclick="openModalSupplier();"><i class="fas fa-plus-square text-info"></i></button>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                          <select type="text" class="form-control f_size" id="supplier_detail"></select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Site :</label>
                        <div class="col-sm-9">
                          <button style="background: none;border: none;" data-toggle="modal" onclick="openModalSite();"><i class="fas fa-plus-square text-info"></i></button>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                          <select type="text" class="form-control f_size" id="site_detail"></select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Active Catalog :</label>
                        <div class="col-sm-9">
                          <input type="checkbox" value="" id="activecatalog">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <div class="col-6">
                <div class="card" style="min-height: 848px;">
                  <div class="card-body">
                    <h3>Product Image</h3>
                    <div class="col-12" style="min-height: 515px;">
                      <div id="show_img">
                        <img class="mySlides" id="show_img1" src="" style="width:80%;height: 450px;margin-left: 80px;">
                        <img class="mySlides" id="show_img2" src="" style="width:80%;display:none;height: 450px;margin-left: 80px;">
                        <img class="mySlides" id="show_img3" src="" style="width:80%;display:none;height: 450px;margin-left: 80px;">
                      </div>
                    </div>
                    <h3>Other Image</h3>
                    <div class="row"  style="height: 180px;">
                      <div class="col-4">
                        <input type="file" id="imageOne" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                        <center>
                          <label class="radio" style="margin-top:7px;width: 10%;"><input type="radio" class="classItemName" name="id_img" id="id_img1" onclick="currentDiv(1)"><span class="checkmark"></span></label>
                        </center>
                      </div>
                      <div class="col-4">
                        <input type="file" id="imageTwo" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                        <center>
                          <label class="radio" style="margin-top:7px;width: 10%;"><input type="radio" class="classItemName" name="id_img" id="id_img2" onclick="currentDiv(2)"><span class="checkmark"></span></label>
                        </center>
                      </div>
                      <div class="col-4">
                        <input type="file" id="imageThree" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                        <center>
                          <label class="radio" style="margin-top:7px;width: 10%;"><input type="radio" class="classItemName" name="id_img" id="id_img3" onclick="currentDiv(3)"><span class="checkmark"></span></label>
                        </center>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
 <!-- ==========================END Tab 2=========================================================================================================== -->
          <div class="tab-pane fade" id="banner" role="tabpanel" aria-labelledby="banner-tab">
            <div class="row mt-2">          
              <div class="col">
                <div class="card" style="min-height: 700px;">
                  <div class="card-body">
                    <div style="float: right;width: 165px;">
                      <button type="button" id="btSave_banner" style="width: 100%;height: 55px;"  class="btn btn-outline-success btn-lg"  onclick="save_banner();"> <label class="radio" style="margin-top:1px;width: 10%;font-size:35px;padding-left: 52px;"><?php echo $array['save'][$language]; ?></label></button>
                    </div>
                    
                    <h2 style="font-weight: bold;">Banner Image</h2>
                    <div >
                      <div class="col-11">
                        <label class="radio" style="margin-top:7px;width: 10%;">Banner 1</label>
                        <div style="margin-left:5%;height: 385px;">
                          <input type="file" id="bannerOne" accept="image/x-png,image/gif,image/jpeg" class="dropify" data-height="500" >
                        </div>
                      </div><br>

                      <div class="col-11">
                        <label class="radio" style="margin-top:7px;width: 10%;">Banner 2</label>
                        <div style="margin-left:5%;height: 385px;">
                          <input type="file" id="bannerTwo"  accept="image/x-png,image/gif,image/jpeg" class="dropify" data-height="500">
                        </div >
                      </div><br>

                      <div class="col-11">
                        <label class="radio" style="margin-top:7px;width: 10%;">Banner 3</label>
                        <div style="margin-left:5%;height: 385px;">
                          <input type="file" id="bannerThree"  accept="image/x-png,image/gif,image/jpeg" class="dropify" data-height="500">
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
<!-- ==========================END Tab 3=========================================================================================================== -->
          <div class="tab-pane fade" id="store_location" role="tabpanel" aria-labelledby="store_location-tab">
            <div id="content-wrapper">
              <div class="row">
                <div class="col-md-12">
                  <div class="container-fluid">
                    <div class="card-body" style="padding:0px; margin-top:-12px;">
                      <div class="row">
                        <div class="col-md-12 off-set-10">
                          <div class="row" style="margin-left:5px;margin-top:15px;">
                            <input id="txtSearch_htp" type="text" autocomplete="off" class="form-control col-md-4 ml-2" style="font-size:22px;" placeholder=" <?php echo $array['Searchitem2'][$language]; ?>"  onkeyup="showData_htp();">
                            <input type="text" id="id_store" class="form-control"   hidden>
                            <input type="text" id="key_row_store" class="form-control"   hidden>
                              <!-- <div class="search_custom col-md-2">
                              <div class="search_1 d-flex justify-content-start">
                                <button class="btn" onclick="showData()" id="bSave">
                                  <i class="fas fa-search mr-2"></i>
                                  <?php echo $array['search'][$language]; ?>
                                </button>
                              </div>
                              
                            </div> -->
                            <div  style="margin-left: 41%;">
                           
                            </div>
                          </div>
                        </div>
                       
                      </div>

                      <div class="row mt-2">
                        <div class="col-12">
                          <table class="table table-fixed table-condensed table-striped mt-3" id="tableDocument_hpt" width="100%" cellspacing="0" role="grid">
                            <thead id="theadsum" style="font-size:24px;">
                              <tr role="row" id='tr_1'>
                                <th nowrap style="width:10%;text-align: center;"><br></th>
                                <th nowrap style="width:10%;text-align: center;"><?php echo $array['no'][$language]; ?></th>
                                <th nowrap style="width:34%;text-align: left;"><?php echo $array['side'][$language]; ?></th>
                                <th nowrap style="width:23%;text-align: center;"><?php echo $array['phone'][$language]; ?></th>
                                <th nowrap style="width:23%;text-align: center;">Active</th>
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
                              <button class="btn" onclick="saveData_storeDetail()" id="bSave">
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
                              <button class="btn" onclick="delete_storeDetail()" id="bCancel" disabled>
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
                        <div class="col-6">
                          <div class="col-12">
                            <div class='form-group row'>
                              <label class="col-sm-3 col-form-label "><?php echo $array['side'][$language]; ?></label>
                              <select type="text" class="form-control f_size col-sm-7" id="htp_select"></select>
                            </div>
                          </div>
                          <div class="col-12">
                          <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['image'][$language]; ?></label>
                            <div class="col-md-7" style="height: 400px;">
                            <input type="file" id="imag_htp" accept="image/x-png,image/gif,image/jpeg" class="dropify">
                            </div>
                          </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="col-12">
                              <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['address'][$language]; ?> TH</label>
                                <textarea id="txtaddress"  class="form-control col-sm-7 thonly" rows="3"  style="font-size:22px;">
                                </textarea>
                                <label id="alert_txtaddress" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['address'][$language]; ?> EN</label>
                                <textarea id="txtaddress_EN"  class="form-control col-sm-7 enonly" rows="3"  style="font-size:22px;">
                                </textarea>
                                <label id="alert_txtaddress_EN" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['phone'][$language]; ?></label>
                                <input id="txtphone" type="text" autocomplete="off" class="form-control col-sm-7 numonly" style="font-size:22px;">
                                <label id="alert_txtphone" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk text-danger"></i> </label>
                            
                                <label class="col-sm-3 col-form-label ">Active</label>
                                <div >
                                  <input type="checkbox" value="" id="active_htp">
                                </div>
                              <div>
                            </div>
                            <div class="col-12">
                              <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['officehours'][$language]; ?> :</label>
                                <button style="background: none;border: none;" data-toggle="modal" onclick="openModalTime();" id="bt_addtime"><i class="fas fa-plus-square text-info f_size"></i></button>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class='form-group row'>
                                
                                <label class="col-sm-3 col-form-label ">:</label>
                                 <div id="div_timestore"></div>
                                
                              </div>
    
                            </div>

                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
<!-- ==========================END Tab 4=========================================================================================================== -->
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
            <div class="modal-header" >
              <div class="row px-3" id="modalColor_Header">
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

      <!-- -----------------------------edit_Office_Hours------------------------------------ -->
      <div class="modal fade" id="modal_edit_Timestoce" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Office Hours</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body row">
              <div class="col-12">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label "><?php echo $array['officehours'][$language]; ?> TH :</label>
                  <input id="txtTimestoce_edit" type="text" autocomplete="off" class="form-control col-sm-7 thonly" style="font-size:22px;" placeholder=" <?php echo $array['officehours'][$language]; ?> TH">
                </div>
              </div>

              <div class="col-12">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label "><?php echo $array['officehours'][$language]; ?> EN :</label>
                  <input id="txtTimestoce_edit_EN" type="text" autocomplete="off" class="form-control col-sm-7 enonly" style="font-size:22px;" placeholder=" <?php echo $array['officehours'][$language]; ?> EN">
                </div>
              </div>
      
             <input type="text" id="id_Timestoce" hidden>
            </div>
            <div class="modal-footer">
           
              <button type="button" style="width:12%;" onclick="save_Timestoce_edit()" id="btn_SaveSite" class="btn btn-success px-2"><?php echo $array['confirm'][$language]; ?></button>
              <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
            </div>
          </div>
        </div>
      </div>

      <!-- -----------------------------modal_Timestoce------------------------------------ -->
      <div class="modal fade" id="modal_Timestoce" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Office Hours</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body row">
              <div class="col-12">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label "><?php echo $array['officehours'][$language]; ?> TH :</label>
                  <input id="txtTimestoce" type="text" autocomplete="off" class="form-control col-sm-7 thonly" style="font-size:22px;" placeholder=" <?php echo $array['officehours'][$language]; ?> TH">
                </div>
              </div>
              <div class="col-12">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label "><?php echo $array['officehours'][$language]; ?> EN :</label>
                  <input id="txtTimestoce_EN" type="text" autocomplete="off" class="form-control col-sm-7 enonly" style="font-size:22px;" placeholder=" <?php echo $array['officehours'][$language]; ?> EN" >
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" style="width:12%;" onclick="save_Timestoce();" id="btn_Savetime" class="btn btn-success px-2"><?php echo $array['save'][$language]; ?></button>
              <button type="button" style="width:10%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
            </div>
          </div>
        </div>
      </div>


  




      <?php include_once('../assets/import/js.php'); ?>
      <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
      <script type="text/javascript">
        $(document).ready(function(e) {

          $('.dropify').dropify();
          $('#div_btSave').hide();


          $('#modaltxt_colorDetail').spectrum({
            type: "component"
          });

          var drEvent = $('#imageOne').dropify();
          var drEventTwo = $('#imageTwo').dropify();
          var drEventThree = $('#imageThree').dropify();

          var language = '<?php echo $language; ?>';

          if(language=="th"){  
            $('#chk_lang').prop('checked', true).change();
            var chklang =1;
            $("#num_lang").val(1);
          }else{
            $('#chk_lang').prop('checked', false).change();
            var chklang =0;
            $("#num_lang").val(0);
          }

          $('#color-picker').spectrum({
            type: "component"
          });

          $('#div_bt_edit').hide();
   

          get_typelinen(chklang);
          setTimeout(() => {
            showData();
            showMasterColor();
            showSupplierAdd(chklang);
            showSiteAdd(chklang);
            showSize();
          }, 200);

          $("#detail-tab").click(function() {
            $('#div_btSave').hide();

            $('#txt_Description_TH').val("");
            $('#txt_Description_EN').val("");
            $('#txt_NameTh').val("");
            $('#txt_NameEn').val("");
            
            $('#typelinen_detail').val("");
            $("#activecatalog").prop("checked", false);
            $('#txt_ID').val(0);
            
            document.getElementById("show_img1").src = "../img/icon/no-image.jpg";
            document.getElementById("show_img2").src = "../img/icon/no-image.jpg";
            document.getElementById("show_img3").src = "../img/icon/no-image.jpg";

            show_colorDetail("00");
            show_SizeDetail("00");
            show_supplierDetail("");
            show_siteDetail("");
            showimg("00");


            var drEvent = $('#imageOne').dropify({
              defaultFile: null
            });
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            drEvent.settings.defaultFile = null;
            drEvent.destroy();
            drEvent.init();

            var drEvent = $('#imageTwo').dropify({
              defaultFile: null
            });
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            drEvent.settings.defaultFile = null;
            drEvent.destroy();
            drEvent.init();

            var drEvent = $('#imageThree').dropify({
              defaultFile: null
            });
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            drEvent.settings.defaultFile = null;
            drEvent.destroy();
            drEvent.init();
          });

          $("#home-tab").click(function() {
            $('#div_btSave').hide();
          });

          $("#banner-tab").click(function() {
            $('#div_btSave').hide();
            show_banner();
           
          });

          $("#store_location-tab").click(function() {
            $('#div_btSave').hide();
            showData_htp();
            show_htp();
            cleartxt();
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




          var drEvent = $('#imageOne').dropify();
          var drEventTwo = $('#imageTwo').dropify();
          var drEventThree = $('#imageThree').dropify();

          drEvent.on('dropify.afterClear ', function(event, element) {
            $('#imageOne').data("value", "default");
            document.getElementById("show_img1").src = "../img/icon/no-image.jpg";
          });

          drEventTwo.on('dropify.afterClear ', function(event, element) {
            $('#imageTwo').data("value", "default");
            document.getElementById("show_img2").src = "../img/icon/no-image.jpg";
          });

          drEventThree.on('dropify.afterClear ', function(event, element) {
            $('#imageThree').data("value", "default");
            document.getElementById("show_img3").src = "../img/icon/no-image.jpg";
          });


          //---------------banner-------------------------------------------------------------------------

          var drEventbanner = $('#bannerOne').dropify();
          var drEventbannerTwo = $('#bannerTwo').dropify();
          var drEventbannerThree = $('#bannerThree').dropify();

          drEventbanner.on('dropify.afterClear ', function(event, element) {
            $('#bannerOne').data("value", "default");
          });

          drEventbannerTwo.on('dropify.afterClear ', function(event, element) {
            $('#bannerTwo').data("value", "default");
          });

          drEventbannerThree.on('dropify.afterClear ', function(event, element) {
            $('#bannerThree').data("value", "default");
          });


          var drEventhtp = $('#imag_htp').dropify();
          drEventhtp.on('dropify.afterClear ', function(event, element) {
            $('#imag_htp').data("value", "default");
          });

        }).click(function(e) {
          parent.afk();
        }).keyup(function(e) {
          parent.afk();
        });


        function get_typelinen(chk_lang) {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'get_typelinen',
              'chk_lang':chk_lang
            },
            success: function(result) {
              $("#input_typeline").empty();
              $("#typelinen_detail").empty();
              var ObjData = JSON.parse(result);
              var StrTR = "";


              if (!$.isEmptyObject(ObjData)) {
                // var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
                var Str = "";
                Str += "<option value='00' ><?php echo $array['selecttypelinen'][$language]; ?></option>";
                $.each(ObjData, function(key, value) {
                  Str += "<option value=" + value.id + " >" + value.name + "</option>";
                });

                var Str2 = "";
                
                $.each(ObjData, function(key, value) {
                  Str2 += "<option value=" + value.id + " >" + value.name + "</option>";
                });
              }

              $("#input_typeline").append(Str);
              $("#typelinen_detail").append(Str2);
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
              'Page': Page
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              var StrTR = "<table id='TableItem' class='table table-striped table-bordered' style='width:100%;font-size:24px;'  width='100%' cellspacing='0' role='grid'  data-page-length='10'>" +
                "<thead> <tr>" +
                " <th style='width: 8%;text-align: center;' nowrap>ON.</th>" +
                " <th style='width: 25%;text-align: left;' nowrap>NAME</th>" +
                "<th style='width: 9%;text-align: center;' nowrap>Typelinen</th>" +
                "<th class='nn' style='width: 11%;text-align: center;' nowrap>SIZE</th>" +
                "<th class='nn' style='width: 15%;text-align: center;' nowrap>COLOR</th>" +
                "<th class='nn' style='width: 9%;text-align: center;' nowrap>SUPPLIER</th>" +
                "<th class='nn' style='width: 9%;text-align: center;' nowrap>SITE</th>" +
                "<th class='nn' style='width: 14%;text-align: center;' nowrap>ACTIVE</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='tbody' class='nicescrolled' style='font-size:24px;height:500px;'>";
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData.item, function(key, value) {
                  //----------------color------------------------------
                  var color = ""
                  if (!$.isEmptyObject(ObjData.color_c)) {
                    $.each(ObjData.color_c[value.id], function(key2, value2) {
                      color += "<div class='px-3 ml-1'  style='background-color: " + value2.color_detail + "; border-radius: 70%;  height: 35px; border: 0px solid; width:35px;margin-bottom: 5px;'></div>";
                    });
                  }
                  //------------------size----------------------------
                  var itemsize = ""
                  if (!$.isEmptyObject(ObjData.size)) {
                    $.each(ObjData.size[value.id], function(key3, value3) {
                      itemsize = value3;
                      0
                    });
                  }
                  //----------------------------------------------
                  var suppliep = " <a class='nav-link' id='suppliep'  href='javascript:void(0)' onclick='show_supplier(\"" + value.id + "\" );' > more </a>";
                  var site = " <a class='nav-link' id='site'  href='javascript:void(0)' onclick='show_site(\"" + value.id + "\" );'> more </a>";
                  var edit = " <a class='aButton' href='javascript:void(0)' onclick='edit_Detail(\"" + value.id + "\");'><img src='../img/edit.png' style='width:30px;margin-right: 30px;'></a>";
                  if (value.IsActive == 0) {
                    var IsActive = "<input type='checkbox' id='IsActive' style='argin-top: 1.5%;' disabled>";
                  } else {
                    var IsActive = "<input type='checkbox' id='IsActive' style='argin-top: 1.5%;' checked disabled>";
                  }

                  StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width:8%;text-align: center;'>" + (key + 1) + "</td>" +
                    "<td style='width:25%;text-align: left;'>" + value.itemCategoryName + "</td>" +
                    "<td style='width:9%;text-align: center;'>" + value.typeLinen + "</td>" +
                    "<td style='width:11%;text-align: center;'>" + itemsize + "</td>" +
                    "<td style='width:15%;text-align: center;'><div class='row' style='margin-left: 16px;'>" + color + "</div></td>" +
                    "<td style='width:9%;text-align: center;'>" + suppliep + "</td>" +
                    "<td style='width:9%;text-align: center;'>" + site + "</td>" +
                    "<td style='width:14%;text-align: center;'> " + edit + IsActive + " </td>" +
                    "</tr>";
                });
              }

              StrTR += "</tbody></table>"
              $('#div_table').html(StrTR);

              table_date();

            }


          });

        }

        function table_date() {
          $('#TableItem').DataTable({
            "bFilter": false,
                columnDefs: [{
                    orderable: false,
                    targets: [3,4,5,6,7]
                }, ]
             
          });
          $(".dataTables_length").hide();

        }

        function show_supplier(id) {
          $('#modal_supplier').modal('toggle');

          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_supplier',
              'id': id
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
              } else {
                myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden; margin-left: 35px;'  nowrap> ไม่มีรายการ </div>";
              }

              $("#row_supplier").html(myDATA);
            }
          });

        }

        function show_site(id) {
          $('#modal_site').modal('toggle');

          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_site',
              'id': id
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
              } else {
                myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden; margin-left: 35px;'  nowrap> ไม่มีรายการ </div>";
              }

              $("#row_site").html(myDATA);
            }
          });

        }

        function edit_Detail(id) {
       
         var language = '<?php echo $language; ?>';
          if(language=="th"){
            $('#chk_lang').prop('checked', true).change();
            $("#num_lang").val(1);
          }else{
            $('#chk_lang').prop('checked', false).change();
            $("#num_lang").val(0);
          }

          var num_lang = $("#num_lang").val();
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'edit_Detail',
              'id': id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var myDATA = "";
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {

                  $('#detail-tab').tab('show');
                  $('#div_btSave').show();

                  $("#txt_Description_TH").val(value.discription);
                  $('#txt_Description_EN').val(value.discription_EN);
                  $("#txt_ID").val(value.id);
                  $("#txt_NameTh").val(value.itemCategoryName);
                  $("#txt_NameEn").val(value.itemCategoryNameEn);
                  $("#typelinen_detail").val(value.typeLinen);


                  if (value.IsActive == 0) {
                    $("#activecatalog").prop("checked", false);
                  } else {
                    $("#activecatalog").prop("checked", true);
                  }

                  show_colorDetail(value.id);
                  show_SizeDetail(value.id);
                  show_supplierDetail(value.id,num_lang);
                  show_siteDetail(value.id,num_lang);
                  showimg(value.id);
                 



                });
              }

            }
          });
        }

        function show_colorDetail(id) {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_colorDetail',
              'id': id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var mycolor = "";
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  mycolor += "<div class='px-3 ml-1'  style='background-color: " + value.color_detail + "; border-radius: 70%;  height: 35px; border: 0px solid; width:35px;'></div>";
                });
              } else {

              }

              $("#div_color").html(mycolor);
            }
          });
        }

        function show_SizeDetail(id) {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_SizeDetail',
              'id': id
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              var mySize = "";
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  // mySize += "<div class='px-3 ml-1'  style=' border-radius: 70%;  height: 35px; border: 2px solid; width:2%;text-align: center;'><lable style='text-align: center;margin-left: -4px;'>"+value.itemsize+"</lable></div>";
                  mySize += `<li><a href="javascript:void(0)" class='clearSize'  onclick='showColorDetail_size("${kay}","${value.itemsize}","${id}")'  id='checksite_${kay}' >${value.itemsize}</a></li>`;
                });
              } else {

              }

              $("#ul_size").html(mySize);
              // $('#checksite_0').addClass('focusSize');
            }
          });
        }

        function showColorDetail_size(key_site, sizeName, catalog_id) {
          $(".clearSize").removeClass('focusSize');
          $("#checksite_" + key_site).addClass('focusSize');

          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'showColorDetail_size',
              'sizeName': sizeName,
              'catalog_id': catalog_id,
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              var mycolor = "";
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  mycolor += "<div class='px-3 ml-1'  style='background-color: " + value.color_detail + "; border-radius: 70%;  height: 35px; border: 0px solid; width:35px;'></div>";
                });
              }
              $("#div_color").html(mycolor);
            }
          });
        }

        function show_supplierDetail(id,num_lang) {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_supplierDetail',
              'id': id,
              'num_lang':num_lang
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

        function show_siteDetail(id,num_lang) {
          // alert(num_lang);
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_siteDetail',
              'id': id,
              'num_lang':num_lang
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
          $("#row_color").empty();
          $("#modalSelect_colorMaster").val("0");
          $("#modalSelect_colorMaster").attr("disabled", true);
          $("#modal_color").modal('show');
        }

        function openMasterColor(size) {
          var sizeArray = [];

          $(".loopsize:checked").each(function() {
            sizeArray.push($(this).val());
          });

          var txtItemId = $("#txt_ID").val();
          $('#modalColor_btnDelete').attr('disabled', true);
          $("#modalSelect_colorMaster").attr("disabled", false);
          $("#modalSelect_colorMaster").val("0");
          $('#modaltxt_colorDetail').val("");
          $("#modaltxt_colorDetail").attr("disabled", true);
          $("#modaltxt_colorDetail").spectrum({
            type: "component"
          });

          $("#row_color").empty();
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'openMasterColor',
              'size': size,
              'txtItemId': txtItemId,
              'sizeArray': sizeArray,
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
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
          $('#txtColorId').val(color_detail);
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

        function showSize() {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'showSize',
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              var data = "";
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


        function saveColor() {
          var sizeArray = [];
          $(".loopsize:checked").each(function() {
            sizeArray.push($(this).val());
          });
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
                $("#modal_color").modal('hide');
                $('#txtColorId').val("");
                openMasterColor(radioSize);
                show_colorDetail(txtItemId);
                show_SizeDetail(txtItemId);
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
          var txtItemId = $("#txt_ID").val();
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
                $('#txtColorId').val("");
                openMasterColor(radioSize);
                show_colorDetail(txtItemId);
                show_SizeDetail(txtItemId);
              }, 500);


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

        function showSupplierAdd(chk_lang) {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'showSupplierAdd',
              'chk_lang': chk_lang
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
          var num_lang = $("#num_lang").val();
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

              show_supplierDetail(txtItemId,num_lang);
              $("#modal_supplierAdd").modal('toggle');
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

        function showSiteAdd(chk_lang) {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'showSiteAdd',
              'chk_lang': chk_lang
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
          var num_lang = $("#num_lang").val();
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
              show_siteDetail(txtItemId,num_lang);
              $("#modal_siteAdd").modal('toggle');
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
              'id': id
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  var imageOne = `${"../profile/catalog/"+value.imageOne}`;
                  var imageTwo = `${"../profile/catalog/"+value.imageTwo}`;
                  var imageThree = `${"../profile/catalog/"+value.imageThree}`;

                  //--------------------------------------------------------------------------


                  $("#id_img1").prop("checked", true);
                 
                  //--------------------------------------------------------------------------

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
                    document.getElementById("show_img1").src = imageOne;

                  } else {
                    document.getElementById("show_img1").src = "../img/icon/no-image.jpg";
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
                    document.getElementById("show_img2").src = imageTwo;
                  } else {
                    document.getElementById("show_img2").src = "../img/icon/no-image.jpg";
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
                    document.getElementById("show_img3").src = imageThree;
                  } else {
                    document.getElementById("show_img3").src = "../img/icon/no-image.jpg";
                    // $(".dropify-clear").click();
                  }

                  setTimeout(() => {
                    $('#imageOne').data("value", imageOne);
                    $('#imageTwo').data("value", imageTwo);
                    $('#imageThree').data("value", imageThree);
                     
                  }, 300);
                  currentDiv(1);

                });
              }



            }
          });
        }


        function saveData_detail() {

          var form_data = new FormData();
          var imageOne = $('#imageOne').prop('files')[0];
          var imageTwo = $('#imageTwo').prop('files')[0];
          var imageThree = $('#imageThree').prop('files')[0];
          var data_imageOne = $('#imageOne').data('value');
          var data_imageTwo = $('#imageTwo').data('value');
          var data_imageThree = $('#imageThree').data('value');
          var typelinen_detail = $("#typelinen_detail").val();
          var txtDiscription = $("#txt_Description_TH").val();
          var txtDiscription_EN = $("#txt_Description_EN").val();

          var checkBox = document.getElementById("activecatalog");

          if (checkBox.checked == true) {
            var activecatalog = 1;
          } else {
            var activecatalog = 0;
          }


          var txt_NameTh = $("#txt_NameTh").val();
          var txt_NameEn = $("#txt_NameEn").val();
          var txtItemId = $("#txt_ID").val();
          // var txtItemNameEn = $("#txtItemNameEn").val();

          // alert(typelinen_detail);

          if (txt_NameTh == "") {
            swal({
              title: '',
              text: 'กรุณาระบุรายการไทย',
              type: 'warning',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $("#txt_NameTh").addClass("border-danger");
            // $("#alert_txtItemNameEn").show();
            return;
          } else {
            $("#txt_NameTh").removeClass("border-danger");
          }

          if (txt_NameEn == "") {
            swal({
              title: '',
              text: 'กรุณาระบุรายการอังกฤษ',
              type: 'warning',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $("#txt_NameEn").addClass("border-danger");
            // $("#alert_txtItemName").show();
            return;
          } else {
            $("#txt_NameEn").removeClass("border-danger");
          }

          if (txtDiscription == "") {
            swal({
              title: '',
              text: 'กรุณาระบุรายการ',
              type: 'warning',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $("#txt_Description_TH").addClass("border-danger");
            // $("#alert_txtDiscription").show();
            return;
          } else {
            $("#txt_Description_TH").removeClass("border-danger");
          }

          if (txtDiscription_EN == "") {
            swal({
              title: '',
              text: 'กรุณาระบุรายการ',
              type: 'warning',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $("#txt_Description_EN").addClass("border-danger");
            // $("#alert_txtDiscription").show();
            return;
          } else {
            $("#txt_Description_EN").removeClass("border-danger");
          }


          form_data.append('FUNC_NAME', 'saveData_detail');
          form_data.append('imageOne', imageOne);
          form_data.append('imageTwo', imageTwo);
          form_data.append('imageThree', imageThree);
          form_data.append('data_imageOne', data_imageOne);
          form_data.append('data_imageTwo', data_imageTwo);
          form_data.append('data_imageThree', data_imageThree);

          form_data.append('typelinen_detail', typelinen_detail);
          form_data.append('txtDiscription', txtDiscription);
          form_data.append('txtItemName', txt_NameTh);
          form_data.append('txtItemId', txtItemId);
          form_data.append('activecatalog', activecatalog);
          form_data.append('txtItemNameEn', txt_NameEn);
          form_data.append('txtDiscription_EN', txtDiscription_EN);
          

          $.ajax({
            url: "../process/catalogmanagement.php",
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
                edit_Detail(txtItemId);
                showData();
              }, 700);

            }
          });

        }

        function chk_lang(){
          var chk_lang = $("#chk_lang").prop('checked');
          
          var txt_ID = $("#txt_ID").val();

          if (chk_lang == true) {
            
            $("#num_lang").val(1);
            $("#txt_Description_TH").show();
            $("#txt_Description_EN").hide();

            $("#div_nameTH").show();
            $("#div_nameEH").hide();

            showSupplierAdd(1);
            showSiteAdd(1);
            get_typelinen(1);

            show_supplierDetail(txt_ID,1);
            show_siteDetail(txt_ID,1);
           
          }else{
            $("#num_lang").val(0);
            $("#txt_Description_TH").hide();
            $("#txt_Description_EN").show();

            $("#div_nameTH").hide();
            $("#div_nameEH").show();

            showSupplierAdd(0);
            showSiteAdd(0);
            get_typelinen(0);

            show_supplierDetail(txt_ID,0);
            show_siteDetail(txt_ID,0);
           
          }

        }



        function show_banner() {
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_banner'
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  var bannerOne = `${"../profile/banner/"+value.bannerOne}`;
                  var bannerTwo = `${"../profile/banner/"+value.bannerTwo}`;
                  var bannerThree = `${"../profile/banner/"+value.bannerThree}`;

                  //--------------------------------------------------------------------------



                  //--------------------------------------------------------------------------

                  $(".dropify-clear").click();
                  if (bannerOne != "../profile/banner/null") {

                    var drEvent = $('#bannerOne').dropify({
                      defaultFile: bannerOne
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = bannerOne;
                    drEvent.destroy();
                    drEvent.init();
                  } else {
                    // $(".dropify-clear").click();
                  }

                  if (bannerTwo != "../profile/banner/null") {
                    var drEvent = $('#bannerTwo').dropify({
                      defaultFile: bannerTwo
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = bannerTwo;
                    drEvent.destroy();
                    drEvent.init();
                  } else {
                    // $(".dropify-clear").click();
                  }

                  if (bannerThree != "../profile/banner/null") {
                    var drEvent = $('#bannerThree').dropify({
                      defaultFile: bannerThree
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = bannerThree;
                    drEvent.destroy();
                    drEvent.init();
                  } else {
                    // $(".dropify-clear").click();
                  }

                  setTimeout(() => {
                    $('#bannerOne').data("value", bannerOne);
                    $('#bannerTwo').data("value", bannerTwo);
                    $('#bannerThree').data("value", bannerThree);
                  }, 300);


                });
              }



            }
          });
        }

        function save_banner(){
          
          var form_data = new FormData();
          var bannerOne = $('#bannerOne').prop('files')[0];
          var bannerTwo = $('#bannerTwo').prop('files')[0];
          var bannerThree = $('#bannerThree').prop('files')[0];
          var data_bannerOne = $('#bannerOne').data('value');
          var data_bannerTwo = $('#bannerTwo').data('value');
          var data_bannerThree = $('#bannerThree').data('value');

          form_data.append('FUNC_NAME', 'save_banner');
          form_data.append('bannerOne', bannerOne);
          form_data.append('bannerTwo', bannerTwo);
          form_data.append('bannerThree', bannerThree);
          form_data.append('data_bannerOne', data_bannerOne);
          form_data.append('data_bannerTwo', data_bannerTwo);
          form_data.append('data_bannerThree', data_bannerThree);
       
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(result) {
              var ObjData = JSON.parse(result);
             
              swal({
                title: '',
                text: '<?php echo $array['savesuccess'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });

              setTimeout(() => {
                show_banner();
              }, 500);

            }
          });
        }


        function showData_htp() {
          var txtSearch_htp = $("#txtSearch_htp").val();
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'showData_htp',
              'txtSearch_htp': txtSearch_htp
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              var StrTR = "" ;
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(key, value) {

                  if (value.IsActive == 0) {
                    var IsActive = "<input type='checkbox' id='IsActive_htp' style='argin-top: 1.5%;' disabled>";
                  } else {
                    var IsActive = "<input type='checkbox' id='IsActive_htp' style='argin-top: 1.5%;' checked disabled>";
                  }
                  var chkDoc = "<label class='radio' style='margin-top:7px'><input type='radio' class='classItemName' name='idhtp' id='idhtp_" + key + "' value='" + value.id + "' onclick='show_storeDetail(\"" + value.id + "\",\"" + key + "\")' ><span class='checkmark'></span></label>";
                  StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width:10%;text-align: center;'><center>"+chkDoc+"</center></td>" +
                    "<td style='width:10%;text-align: center;'>" + (key + 1) + "</td>" +
                    "<td style='width:34%;text-align: left;'>" + value.HptName + "</td>" +
                    "<td style='width:23%;text-align: center;'>" + value.phone + "</td>" +
                    "<td style='width:23%;text-align: center;'>" + IsActive + "</td>" +
                    "</tr>";
                });
              }
              $('#tableDocument_hpt tbody').html(StrTR);
            }


          });

        }

        function show_htp(){
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_htp'
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              $("#htp_select").empty();
              var Str = "";


              if (!$.isEmptyObject(ObjData)) {
                // var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";

                $.each(ObjData, function(key, value) {
                  Str += "<option value=" + value.HptCode + " >" + value.name + "</option>";
                });
              }

              $("#htp_select").append(Str);
            }
          });
        }

        function show_htpDetail(htpcode){
          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_htpDetail',
              'htpcode': htpcode
            },
            success: function(result) {

              var ObjData = JSON.parse(result);
              $("#htp_select").empty();
              var Str = "";


              if (!$.isEmptyObject(ObjData)) {
                // var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";

                $.each(ObjData, function(key, value) {
                  Str += "<option value=" + value.HptCode + " >" + value.name + "</option>";
                });
              }

              $("#htp_select").append(Str);
            }
          });
        }

      function show_storeDetail(id,key) {
        
       
        $.ajax({
          url: "../process/catalogmanagement.php",
          type: 'POST',
          data: {
            'FUNC_NAME': 'show_storeDetail',
            'id': id
          },
          success: function(result) {

            var ObjData = JSON.parse(result);
            var myDATA = "";
            if (!$.isEmptyObject(ObjData)) {
              $.each(ObjData, function(kay, value) {

                show_htpDetail(value.HptCode)
                $("#id_store").val(id);
                $("#key_row_store").val(key);
                $("#txtphone").val(value.phone);
                $("#txtaddress").val(value.address);
                $("#txtaddress_EN").val(value.address_En);
                $("#bt_addtime").show();

                $('#bCancel').attr('disabled', false);
                $('#cancelIcon').removeClass('opacity');

                show_Timestoce();

                if (value.IsActive == 0) {
                    $("#active_htp").prop("checked", false);
                  } else {
                    $("#active_htp").prop("checked", true);
                  }
                
                  var imag_htp = `${"../profile/img_store/"+value.image_htp}`;

                  $(".dropify-clear").click();
                  if (imag_htp != "../profile/img_store/null") {

                    var drEvent = $('#imag_htp').dropify({
                      defaultFile: imag_htp
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = imag_htp;
                    drEvent.destroy();
                    drEvent.init();
                  } else {
                    // $(".dropify-clear").click();
                  }

                  setTimeout(() => {
                    $('#imag_htp').data("value", imag_htp);
                  }, 300);

              

              });
            }

          }
        });
      }

      function cleartxt(){

          show_htp();
          
          $("#key_row_store").val("");
          $("#id_store").val("");
          $("#txtphone").val("");
          $("#txtaddress").val("");
          $("#txtaddress_EN").val("");
          $("#active_htp").prop("checked", false);
          $(".classItemName").prop("checked", false);
          $("#div_timestore").empty();
          $("#bt_addtime").hide();

          $('#bCancel').attr('disabled', true);
          $('#cancelIcon').addClass('opacity');

          var drEvent = $('#imag_htp').dropify({
              defaultFile: null
            });
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            drEvent.settings.defaultFile = null;
            drEvent.destroy();
            drEvent.init();

      }

      function saveData_storeDetail() {

        var form_data = new FormData();
        var imag_htp = $('#imag_htp').prop('files')[0];
        var data_imag_htp = $('#imag_htp').data('value');
        var htp_select = $("#htp_select").val();

        var txtaddress = $("#txtaddress").val();
        var txtaddress_EN = $("#txtaddress_EN").val();
        var txtphone = $("#txtphone").val();
        var id_store = $("#id_store").val();
        var key_row_store = $("#key_row_store").val();

        var checkBox = document.getElementById("active_htp");

        if (checkBox.checked == true) {
          var active_htp = 1;
        } else {
          var active_htp = 0;
        }

        if (txtaddress == "") {
          swal({
            title: '',
            text: 'กรุณาระบุที่อยู่ไทย',
            type: 'warning',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          });
          $("#txtaddress").addClass("border-danger");
          // $("#alert_txtItemNameEn").show();
          return;
        } else {
          $("#txtaddress").removeClass("border-danger");
        }

        if (txtaddress_EN == "") {
          swal({
            title: '',
            text: 'กรุณาระบุที่อยู่อังกฤษ',
            type: 'warning',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          });
          $("#txtaddress_EN").addClass("border-danger");
          // $("#alert_txtItemName").show();
          return;
        } else {
          $("#txtaddress_EN").removeClass("border-danger");
        }

        if (txtphone == "") {
          swal({
            title: '',
            text: 'กรุณาระบุเบอร์โทรศัพท์',
            type: 'warning',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          });
          $("#txtphone").addClass("border-danger");
          // $("#alert_txtDiscription").show();
          return;
        } else {
          $("#txtphone").removeClass("border-danger");
        }
        

        form_data.append('FUNC_NAME', 'saveData_storeDetail');
        form_data.append('imag_htp', imag_htp);
        form_data.append('data_imag_htp', data_imag_htp);

        form_data.append('id_store', id_store);
        form_data.append('htp_select', htp_select);
        form_data.append('txtaddress', txtaddress);
        form_data.append('txtaddress_EN', txtaddress_EN);
        form_data.append('txtphone', txtphone);
        form_data.append('active_htp', active_htp);

        $.ajax({
          url: "../process/catalogmanagement.php",
          type: 'POST',
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          success: function(result) {
            var ObjData = JSON.parse(result);

            swal({
              title: '',
              text: '<?php echo $array['savesuccess'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });

            setTimeout(() => {
              if(id_store==""){
                showData_htp();
                cleartxt();
              }else{
                show_storeDetail(id_store);
                showData_htp();
                setTimeout(() => {
                  $("#idhtp_"+key_row_store).prop("checked", true);
                }, 200);
              
              }
            }, 700);

          }
        });

      }

        
      function openModalTime() {
          var id_store = $("id_store").val();
          $("#modal_Timestoce").modal('show');
           $("#txtTimestoce").val("");
           $("#txtTimestoce_EN").val("");
           
      }

      function save_Timestoce() {
          var id_store = $("#id_store").val();
          var txtTimestoce = $("#txtTimestoce").val();
          var txtTimestoce_EN = $("#txtTimestoce_EN").val();

          if (txtTimestoce == "") {
            swal({
              title: '',
              text: 'กรุณาระบุเวลาทำการ',
              type: 'warning',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $("#txtTimestoce").addClass("border-danger");
            // $("#alert_txtDiscription").show();
            return;
          } else {
            $("#txtTimestoce").removeClass("border-danger");
          }

          if (txtTimestoce_EN == "") {
            swal({
              title: '',
              text: 'กรุณาระบุเวลาทำการ',
              type: 'warning',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $("#txtTimestoce_EN").addClass("border-danger");
            // $("#alert_txtDiscription").show();
            return;
          } else {
            $("#txtTimestoce_EN").removeClass("border-danger");
          }

          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'save_Timestoce',
              'id_store': id_store,
              'txtTimestoce': txtTimestoce,
              'txtTimestoce_EN': txtTimestoce_EN,
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
              $("#modal_Timestoce").modal('toggle');
              show_Timestoce();
            }
          });

      }

      function show_Timestoce() {
          var id_store = $("#id_store").val();


          $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_Timestoce',
              'id_store': id_store
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              var myDATA = "";
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  var edit_time =" <a class='aButton ml-4' href='javascript:void(0)' onclick='show_edit_time(\"" + value.id + "\" );'><img src='../img/edit.png' style='width:20px;'></a>";
                  var delete_time =" <a class='aButton ml-3' href='javascript:void(0)' onclick='delete_time(\"" + value.id + "\" );'><img src='../img/icon/ic_cancel.png' style='width:20px;'></a>"
                  var Timestoce = `<span  style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>${value.name}</span>`;
                  myDATA += "<div>" + Timestoce +edit_time+delete_time+ "</div>";
                });
              }

              $("#div_timestore").html(myDATA);
            

            }
          });

      }

      function show_edit_time(id) {
        $("#modal_edit_Timestoce").modal('toggle');
        $("#id_Timestoce").val(id);
        $("#txtTimestoce_edit").val("");
        $("#txtTimestoce_edit_EN").val("");
        // alert(id);

        $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'show_edit_time',
              'id_Timestoce': id
            },
            success: function(result) {
              var ObjData = JSON.parse(result);
              if (!$.isEmptyObject(ObjData)) {
                $.each(ObjData, function(kay, value) {
                  $("#txtTimestoce_edit").val(value.office_hours);
                  $("#txtTimestoce_edit_EN").val(value.office_hours_EN);
                });
              }
              
            }
          });
      }

      function save_Timestoce_edit() {
        var id_Timestoce = $("#id_Timestoce").val();
        var txtTimestoce_edit = $("#txtTimestoce_edit").val();
        var txtTimestoce_edit_EN = $("#txtTimestoce_edit_EN").val();

        $.ajax({
            url: "../process/catalogmanagement.php",
            type: 'POST',
            data: {
              'FUNC_NAME': 'save_Timestoce_edit',
              'id_Timestoce': id_Timestoce,
              'txtTimestoce_edit': txtTimestoce_edit,
              'txtTimestoce_edit_EN': txtTimestoce_edit_EN
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
              $("#modal_edit_Timestoce").modal('toggle');
              show_Timestoce();
              
            }
          });
      }


      function delete_time(id) {
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
                      url: "../process/catalogmanagement.php",
                      type: 'POST',
                      data: {
                        'FUNC_NAME': 'delete_time',
                        'id_Timestoce': id,
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
                          show_Timestoce();
                        }, 200);
                      }
                    });
                  } else if (result.dismiss === 'cancel') {
                    swal.close();
                  }
              });
      }

      function delete_storeDetail() {
        var id_store = $("#id_store").val();
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
                      url: "../process/catalogmanagement.php",
                      type: 'POST',
                      data: {
                        'FUNC_NAME': 'delete_storeDetail',
                        'id_store': id_store,
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
                          showData_htp();
                          cleartxt();
                        }, 200);
                      }
                    });
                  } else if (result.dismiss === 'cancel') {
                    swal.close();
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
          if (n > x.length) {
            slideIndex = 1
          }
          if (n < 1) {
            slideIndex = x.length
          }
          for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
          }
          for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" w3-opacity-off", "");
          }
          x[slideIndex - 1].style.display = "block";
          dots[slideIndex - 1].className += " w3-opacity-off";
        }
      </script>


</body>

</html>