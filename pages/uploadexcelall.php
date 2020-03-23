<!DOCTYPE html>
<?php
  session_start();
  $Id = $_SESSION['Userid'];
  $TimeOut = $_SESSION['TimeOut'];



$language = $_SESSION['lang'];


header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2,TRUE);
 ?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
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
  <link rel="stylesheet" href="../dropify/dist/css/dropify.min.css">
    <style>
          @font-face {
            font-family: myFirstFont;
            src: url("../fonts/DB Helvethaica X.ttf");
            }
          body{
            font-family: myFirstFont;
                  font-size:22px;
          }

        .nfont{
          font-family: myFirstFont;
          font-size:22px;
        }
      input[type="text"]{
        text-align: center;
        font-size: 190%;
        height: 70px;
      }

        .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -150px;
            margin-left: -250px;
        }
        /* ----------------- */
        .card{
            height:400px;
            background-color:#e9ecef;
            -webkit-box-shadow: 10px 12px 5px -7px rgba(0,0,0,0.23);
            -moz-box-shadow: 10px 12px 5px -7px rgba(0,0,0,0.23);
            box-shadow: 10px 12px 5px -7px rgba(0,0,0,0.23);
            border-radius:20px;
        }
        h4{
            color:rgb(0, 51, 141) !important;
            font-weight:bold;
        }
        input{
            border-radius:20px!important;
            border:2px solid rgb(0, 51, 141) !important;
            color:rgb(0, 51, 141) !important;
        }
        .btn_customer {
            font-size:24px!important;
            border-radius:15px!important;
            width:200px!important;
            background-color:rgb(0, 51, 141) !important;
            color:#fff;
        }

        #label1{
            position: absolute;
            right: 48px;
            top: 210px;
        }
        #label1 label{
            font-size:30px;
            font-weight:bold;
            color:rgb(0, 51, 141) !important;
        }

        select{
            border-radius:20px!important;
            border:2px solid rgb(0, 51, 141) !important;
            color:rgb(0, 51, 141) !important;
            height: 70px!important;
            font-size:30px!important;
            font-weight:bold;

        }
        .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        select {
            text-align: center;
            text-align-last: center;
            /* webkit*/
        }
        option {
            text-align: left;
            /* reset to left*/
        }
    
    </style>
    <title><?php echo $array['setting'][$language]; ?></title>
  </head>
  <body>

  

  <!-- ------------------------------------------------------------------  -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
        <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][13][$language]; ?></li>
    </ol>
    <div id="content-wrapper" >
        <div class="row d-flex justify-content-center align-items-center" style="height: 774px">
            <div class="col-md-3 mr-4">
                <div class="card">
                    <div class="card-body">
                        <div  class="d-flex justify-content-center mt-3">
                          <i class="fas fa-download" style="font-size: 100px;"></i>
                        </div>
                        <div  class="d-flex justify-content-center mt-3">
                            <h4>Export Excel</h4>
                        </div>
                        <div  class="d-flex justify-content-center mt-5">
                            <button class="btn btn_customer" onclick="window.location.href='../report/excel/Excel-Master.xlsx' ">Export</button>
                      </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 ml-4">
                <div class="card">
                    <div class="card-body">
                        <div  class="d-flex justify-content-center mt-3">
                        <i class="fas fa-upload" style="font-size: 100px;"></i>
                        </div>
                        <div  class="d-flex justify-content-center mt-3">
                            <h4>Import Excel</h4>
                        </div>
                        <div  class="d-flex justify-content-center mt-5">
                            <button class="btn btn_customer" data-toggle="modal" data-target="#modalExcel">Import</button>
                      </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="modal fade" id="modalExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><?php echo $array['upload'][$language]; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body upload-doc">
              <input type="file" class="dropify"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="fileExcel" name="fileExcel" />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary px-2" data-dismiss="modal"><?php echo $array['isno'][$language]; ?></button>
              <button type="button" class="btn btn-primary  px-2" id='comfirm_submit' disabled onclick='uploadExcel()'><?php echo $array['yes'][$language]; ?></button>
            </div>
          </div>
        </div>
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
      <script src="../dropify/dist/js/dropify.min.js"></script>
      <script>
        $(document).ready(function(e) {
          $('.dropify').dropify();

          // Used events
          var drEvent = $('#input-file-events').dropify();

          drEvent.on('dropify.beforeClear', function(event, element) {
              return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
          });

          drEvent.on('dropify.afterClear', function(event, element) {
              alert('File deleted');
          });

          drEvent.on('dropify.errors', function(event, element) {
              console.log('Has Errors');
          });
          checkFileLength();
          $('.upload-doc input[type="file"]').on('change', function () {
              checkFileLength();
          });
        });
        function checkFileLength() {
          let $upload_file_elem = $('.upload-doc input[type="file"]');
          let file_length = $upload_file_elem.length;
          let validation = 0;

          for (i = 0; i < file_length; i++) {
              if ($($upload_file_elem[i]).val() != '') {
                  validation++;
              }
          }

          if (validation >= 1) {
              $('#comfirm_submit').removeAttr('disabled');
          }
        }
        function uploadExcel(){
          var file_data = $('#fileExcel').prop('files')[0];   
          if(file_data!=''){
              swal({
              title: "",
              text: "<?php echo $array['upload'][$language]; ?>",
              type: "question",
              showCancelButton: true,
              confirmButtonClass: "btn-success",
              confirmButtonText:  "<?php echo $array['yes'][$language]; ?>",
              cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
              confirmButtonColor: '#6fc864',
              cancelButtonColor: '#3085d6',
              closeOnConfirm: false,
              closeOnCancel: false,
              showCancelButton: true}).then(result => {
                if (result.value) {
                      var file_data = $('#fileExcel').prop('files')[0];   
                      var form_data = new FormData();                  
                      form_data.append('file', file_data);
                      var URL = '../process/itemExcel.php';
                      $.ajax({
                          url: URL, 
                          dataType: 'text',
                          cache: false,
                          contentType: false,
                          processData: false,
                          data:  form_data,
                          type: 'post',
                          beforeSend: function() {
                        swal({
                            title: 'Please wait..',
                            text: 'Processing',
                            allowOutsideClick: false
                        })
                        swal.showLoading()
                    },
                          success: function(result){
                            swal({
                              title: '',
                              text: '<?php echo $array['uploadsuc'][$language]; ?>',
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              showConfirmButton: false,
                              timer: 1000,
                            });
                            setTimeout(() => {
                              $('#modalExcel').modal('toggle');
                            }, 1000); 
                          }
                      });
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                }
              })
          }else{
            swal({
                title: '',
                text: "<?php echo $array['required'][$language]; ?>",
                type: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
            })
            $('.checkblank').each(function() {
                if($(this).val()==""||$(this).val()==undefined){
                $(this).css('border-color', 'red');
                }
            });
          }
        }
        </script>
  </body>
</html>
