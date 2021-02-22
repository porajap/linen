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
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['detail'][$language]; ?></a>
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
                            </div> <!-- tag column 1 -->
                        </div>

                    </div>
<!-- ====================END Tap 1=========================================================================== -->

                    <!-- search document -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body" style="padding:0px; margin-top:12px;margin-left:2px;">
                            <!-- <div class="row">
                                <div class="col-md-11">
                                    <div class="row">
                                        <select class="form-control" style="margin-left:20px; font-size:22px;width:250px;" id="hptsel2"></select>
                                        <div class="search_custom col-md-2">
                                            <div class="search_1 d-flex justify-content-start">
                                              <button class="btn"  onclick="ShowDoc()" id="bSavex" >
                                                <i class="fas fa-search mr-2"></i>
                                                <?php echo $array['search'][$language]; ?>
                                              </button>
                                            </div>
                                          </div>

                                        <div class="search_custom col-md-2" style="margin-left:-8%;">
                                            <div class="circle6 d-flex justify-content-start">
                                              <button class="btn"  onclick="OpenDialog(1)" id="show_btn" disabled='true'>
                                                <i class="fas fa-paste mr-2 pt-1"></i>
                                                <?php echo $array['show'][$language]; ?>
                                              </button>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            </div>

                            <div  style="margin-top:20px; margin-bottom:20px;"></div>

                            <div class="row">
                                <div class="card-body" style="padding:0px;">
                                    <table class="table table-fixed table-condensed table-striped" id="TableDoc" cellspacing="0" role="grid" style="font-size:24px;width:98%;">
                                        <thead style="font-size:24px;">
                                            <tr role="row">
                                                <th style='width: 5%;' nowrap>&nbsp;</th>
                                                <th style='width: 25%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                                <th style='width: 25%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                                                <th style='width: 45%;' nowrap><?php echo $array['dateP'][$language]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:450px;" />
                                    </table>
                                </div>
                            </div> -->
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



  <?php include_once('../assets/import/js.php'); ?>
  <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>


  <script type="text/javascript">
    $(document).ready(function(e) {

     


    
      $('#color-picker').spectrum({
                type: "component"
      });

      $('#div_bt_edit').hide();


      get_typelinen();
      setTimeout(() => {
        showData();
        
      }, 200);

      // setTimeout(() => {
        
      //   table_date();
        
      // }, 500);
      

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
                "<td style='width:15%;text-align: center;'><div class='row' style='margin-left: 16px;'>"+color+"</center></div></td>" +
                "<td style='width:9%;text-align: center;'>"+suppliep+"</td>" +
                "<td style='width:9%;text-align: center;'>"+site+"</td>" +
                "<td style='width:14%;text-align: center;'> " + IsActive + " </td>" +
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

    // function relode_div(){
    //   // $( "#TableItem_paginate" ).load(window.location.href + " #TableItem_paginate" );

    //   $("#div_table22").load(" #div_table22 > *");
    // }

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