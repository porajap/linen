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
                            <div class="col-md-12">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:12px;margin-left: -2%;">
                                        <div class="row col-md-12">
                                          <div class="col-md-3">
                                              <div class="row" style="margin-left:5px;">
                                                  <select class="form-control col-md-12"  style="font-size:24px;" id="input_typeline" onchange="shownow()">
                                                  <option value="P" >Patient Shirt</option>
                                                  <option value="S" >Staff Uniform</option>
                                                  <option value="F" >Flat Sheet</option>
                                                  <option value="T" >Towel</option>
                                                  <option value="G" >Green Linen</option>
                                                  <option value="O" >Other</option>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="row" style="margin-left: -6px;">
                                              <input id="txtSearch" type="text" autocomplete="off" class="form-control col-md-12" style="font-size:22px;" placeholder="ค้นหา รายการ">
                                              </div>
                                          </div>
                                          <div class="col-md-1 text-right">
                                            <div class="row" style="margin-left:-6px;">
                                            <div class="search_custom col-md-2">
                                          <div class="search_1 d-flex justify-content-start">
                                            <button class="btn" onclick="ShowItem1()">
                                              <i class="fas fa-search mr-2"></i>
                                              <?php echo $array['search'][$language]; ?>
                                            </button>
                                          </div>
                                        </div>
                                        </div>
                                       </div>
                                     </div>
                                        <table style="margin-top:10px; " class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                            <thead id="theadsum" >
                                                <tr role="row" style="font-size:24px;">
                                                    <th style='width: 8%;text-align: center;' nowrap>ON.</th>
                                                    <th style='width: 25%;text-align: left;' nowrap>NAME</th>
                                                    <th style='width: 9%;text-align: center;' nowrap>Typelinen</th>
                                                    <th style='width: 11%;text-align: center;' nowrap>SIZE</th>
                                                    <th style='width: 15%;text-align: center;' nowrap>COLOR</th>
                                                    <th style='width: 9%;text-align: center;' nowrap>SUPPLIEP</th>
                                                    <th style='width: 9%;text-align: center;' nowrap>SITE</th>
                                                    <th style='width: 14%;text-align: center;' nowrap>ACTIVE</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody" class="nicescrolled" style="font-size:24px;height:250px;">
                                            </tbody>
                                        </table>
                                        <div class="pagination">
                                          <a href="#">&laquo;</a>
                                          <a href="#">1</a>
                                          <a href="#" class="active">2</a>
                                          <a href="#">3</a>
                                          <a href="#">4</a>
                                          <a href="#">5</a>
                                          <a href="#">6</a>
                                          <a href="#">&raquo;</a>
                                        </div>
                                    </div>
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

      

            <!-- -----------------------------Custom1------------------------------------ -->
<div class="modal" id="dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" id="rowCount">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body" style="padding:0px;">
                    <div class="row">
                        <div class="col-md-12 mhee">
                            <div class="row mb-3">
                                <select class="form-control ml-2 checkblank" onchange="resetinput()" style=" font-size:22px;width:250px;" id="hptselModal" onchange="getDate_price();"></select>
                                <label id="rem1"  style="margin-left: 1%; font-size: 180%;margin-top: -0.5%;"> * </label>
                                <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control datepicker-here numonly checkblank" style="margin-left:20px; font-size:22px;width:168px;" id="datepicker" data-language=<?php echo $language ?>  data-date-format='dd/mm/yyyy' placeholder="<?php echo $array['datepicker'][$language]; ?>">
                                <label id="rem"  style=" margin-left: 1%; font-size: 180%;margin-top: -0.5%;"> * </label>
                                <!-- <input type="text" class="form-control datepicker-here" style="margin-left:20px; font-size:22px;width:150px;" id="datepicker"> -->
                                <input type="text" autocomplete="off"  disabled="true" class="form-control " style="margin-left:20px; font-size:22px;width:200px;" name="docno" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >


                                <div class="search_custom col-md-2" id="create1">
                                    <div class="circle1 d-flex justify-content-start">
                                        <button class="btn" onclick="onCreate()" >
                                            <i class="fas fa-file-medical mr-2"></i>
                                            <?php echo $array['createdocno'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                                <input type="text" class="form-control" style="margin-left:20px; font-size:22px;width:210px;" name="search1"   id="search1" onKeyPress='if(event.keyCode==13){ShowItem2()}' placeholder="<?php echo $array['search'][$language]; ?>" >
                                <div class="search_custom col-md-2" id="btn_save"  hidden="true">
                                    <div class="import_1 d-flex justify-content-start" id="delete_icon2">
                                        <button class="btn" onclick="UpdatePrice()" id="updateprice">
                                            <i class="fas fa-file-import mr-2 pt-1"></i>
                                            <?php echo $array['updateprice'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="search_custom col-md-2" id="btn_saveDoc"  hidden="true">
                                    <div class="circle4 d-flex justify-content-start">
                                        <button class="btn" onclick="saveDoc()" >
                                            <i class="fas fa-save" style="padding-left: 16%;"></i>
                                            <?php echo $array['savedoc'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-fixed table-condensed table-striped" id="TableItemPrice" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;">
                        <thead style="font-size:24px;">
                            <tr role="row">
                            <th style='width: 5%;'>&nbsp;</th>
                                <th style='width: 35%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                <th style='width: 35%;' nowrap><?php echo $array['categorysub'][$language]; ?></th>
                                <th style='width: 25%;' nowrap><?php echo $array['price'][$language]; ?></th>
                            </tr>
                        </thead>
                        <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                        </tbody>
                    </table>
                </div>
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


      // get_typelinen();
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


    // function get_typelinen(){
    //   $.ajax({
    //         url: "../process/catalogmanagement.php",
    //         type: 'POST',
    //         data: {
    //           'FUNC_NAME': 'get_typelinen'
    //         },
    //         success: function(result) {

    //           var ObjData = JSON.parse(result);
    //           var StrTR = "";


    //           if (!$.isEmptyObject(ObjData)) {
    //             var Str = "<option value=0 >----- กรุณาเลือกกลุ่มสี -----</option>";
    //             $.each(ObjData, function(key, value) {
    //               Str += "<option value=" + value.ID + " >" + value.color_master_name + "</option>";
    //             });
    //           }

    //           $("#input_typeline").append(Str);
    //         }
    //       });
    // }

    function showData() {
      var input_typeline = $("#input_typeline").val();

      $.ajax({
        url: "../process/catalogmanagement.php",
        type: 'POST',
        data: {
          'FUNC_NAME': 'showData',
          'input_typeline': input_typeline,

        },
        success: function(result) {
          var ObjData = JSON.parse(result);
          var StrTR = "";
          if (!$.isEmptyObject(ObjData)) {
            $.each(ObjData.item, function(key, value) {
              
              if(value.typeLinen=="P"){
                var typeLinen ="Patient Shirt";
              }else if(value.typeLinen=="S"){
                var typeLinen ="Staff Uniform";
              }else if(value.typeLinen=="F"){
                var typeLinen ="Flat Sheet";
              }else if(value.typeLinen=="T"){
                var typeLinen ="Towel";
              }else if(value.typeLinen=="G"){
                var typeLinen ="Green Linen";
              }else if(value.typeLinen=="O"){
                var typeLinen ="Other";
              }
              var color =""
              $.each(ObjData.color[value.id], function(key2, value2) {
                color += value2.color_detail;
              });

              var suppliep = " <a class='nav-link' id='suppliep'  href='javascript:void(0)' > more </a>";
              var site = " <a class='nav-link' id='site'  href='javascript:void(0)' > more </a>";
              StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                "<td style='width:8%;text-align: center;'>" + (key + 1) + "</td>" +
                "<td style='width:25%;text-align: left;'>" + value.itemCategoryName + "</td>" +
                "<td style='width:9%;text-align: center;'>" + typeLinen + "</td>" +
                "<td style='width:11%;text-align: center;'></td>" +
                "<td style='width:15%;text-align: center;'></td>" +
                "<td style='width:9%;text-align: center;'>"+suppliep+"</td>" +
                "<td style='width:9%;text-align: center;'>"+site+"</td>" +
                "<td style='width:14%;text-align: center;'> " + value.IsActive + " </td>" +
                "</tr>";
            });
          }
          $('#TableItem tbody').html(StrTR);

          // $("#select_color_master2").val("0");
          // $("#color-picker").spectrum({
          //         color: "transparent"
          // });
          // $('#bCancel').attr('disabled', true);
          // $('#text_id_color_detail').val("");
          // $('#cancelIcon').addClass('opacity');
          // $('#div_bt_edit').hide();
          // $('#div_bt_add').show();
        }
      });
    }



 



    function chk_color_master(){
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


  </script>
  <style>
    .pagination {
      display: inline-block;
      float: right;
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
  </style>

</body>

</html>