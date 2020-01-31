<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  // header("location:../index.html");
}

if(empty($_SESSION['lang'])){
    $language ='th';
}else{
    $language =$_SESSION['lang'];

}

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2,TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        <?php echo $array['DeliveryCycle'][$language]; ?>
    </title>

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
    <link href="../css/jquery.timepicker.css" rel="stylesheet">
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
            Blankinput();
            $('#rem1').hide();
            $('#rem2').hide();
            getSection();
            getfactory();
            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem();
                }
            });

            $('#settime').timepicker({
                timeFormat: 'HH:mm',
                interval: 30,
                minTime: '0',
                // maxTime: '6:00pm',
                // defaultTime: '8',
                startTime: '00:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $('#settime').on('changeTime', function() {
                alert(55);
            });
        }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });

        function getfactory() {
            var HptCode = $('#hptsel').val();
            var data = {
                'STATUS': 'getfactory',               
                'HptCode':HptCode
            };
            senddata(JSON.stringify(data));
            ShowItem();
        }
        function getSection() {
            var data = {
                'STATUS': 'getSection'
            };
            senddata(JSON.stringify(data));
        }
        function removeborder(){
            ShowItem();
            $('#form1').addClass('form-group');
            var HptCode = $('#hptsel').val();
            $('#factory').val(HptCode);
            $('#hptsel').css('border-color', '');

        }
        function ShowItem(chk){
            if(chk ==1)
            {var factory  = $('#factorysel').val();
                                    $('#factory').val(factory);
            }else if(chk ==2)
            {var factory     = $('#factory').val();
                                      $('#factorysel').val(factory);
                                      $('#rem1').hide();
                                      $('#factory').removeClass('border border-danger');
            }
            $('.checkblank66').each(function() {
            if($(this).val()==""||$(this).val()==undefined){
              $(this).css('border-color', 'red');
            }else{
              $(this).css('border-color', '');
            }
          });

            var HptCode  = $('#hptsel').val();
            var Keyword  = $('#searchitem').val();
            var data = {
                'STATUS':'ShowItem',
                'HptCode':HptCode,
                'Keyword':Keyword,
                'factory':factory
            };
            senddata(JSON.stringify(data));
        }
        function AddItem(){
            var factory = $('#factory').val();
            var HptCode = $('#hptsel').val();
            var Time = $('#selectTime').val();
            if(factory=='' && Time != ''){
                $('#factory').addClass('border border-danger');
                $('#rem1').show();
                $('#form1').removeClass('form-group');
            }else if(factory == '' && Time == ''){
                $('#rem1').show();
                $('#rem2').show();
                $('#factory').addClass('border border-danger');
                $('#selectTime').addClass('border border-danger');
                $('#form1').removeClass('form-group');
            }else if(factory != '' && Time == ''){
                $('#rem2').show();
                $('#settime').addClass('border border-danger');
            }else{
                swal({
                    title: "<?php echo $array['confirmsave1'][$language]; ?>",
                    text: "<?php echo $array['adddata1'][$language]; ?>",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
                    cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
                    confirmButtonColor: '#6fc864',
                    cancelButtonColor: '#3085d6',
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    showCancelButton: true
                }).then(result => {
                    if (result.value) {
                        swal({
                            title: '',
                            text: '<?php echo $array['savesuccess'][$language]; ?>',
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        setTimeout(() => {
                            var data = {
                                'STATUS':'AddItem',
                                'HptCode':HptCode,
                                'Time':Time,
                                'factory':factory
                            };
                            senddata(JSON.stringify(data));
                        }, 1500);
                    } else if (result.dismiss === 'cancel') {
                        swal.close();
                    }
                })
                
            }
        }
        function getDetail(ID){
            $('#bCancel').attr('disabled', false);
            $('#delete_icon').removeClass('opacity');
            $('#delete1').addClass('mhee');
            var data = {
                'STATUS':'getDetail',
                'ID':ID
            };
            senddata(JSON.stringify(data));
        }
        function CancelItem() {
            swal({
                title: "<?php echo $array['canceldata'][$language]; ?>",
                text: "<?php echo $array['canceldata1'][$language]; ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    var TimeID = $('#idTime').val();
                    swal({
                        title: '',
                        text: '<?php echo $array['dte'][$language]; ?>',
                        type: 'success',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                        var data = {
                            'STATUS': 'CancelItem',
                            'TimeID': TimeID
                        }
                        senddata(JSON.stringify(data));
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                }
            })
        }
        function Blankinput() {
            $('#factory').removeClass('border border-danger');
            $('#selectTime').removeClass('border border-danger');
            $('#form1').addClass('form-group');
            $('#rem1').hide();
            $('#rem2').hide();
            $('input:checked').each(function() {
                $(this).prop("checked", false);
            });
            var pm = '<?php  echo $PmID;   ?>';
            if(pm !=3 && pm !=5  && pm !=7) 
            {
                $('#hptsel').val("");
                $('#factory').val("");
            }

            $('#settime').val("");
            $('#factorysel').val("");
            $('#selectTime').val("");
            $('#idTime').val("");
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
            ShowItem();
        }
        function logoff() {
            swal({
                title: '',
                text: '<?php echo $array['logout '][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 1000,
                confirmButtonText: 'Ok'
            }).then(function() {
                window.location.href = "../logoff.php";
            }, function(dismiss) {
                window.location.href = "../logoff.php";
                if (dismiss === 'cancel') {

                }
            })
        }
        function resetinput(){
            var factory = $('#factory').val();
            var settime = $('#selectTime').val();


            if(factory !="" && factory!=undefined){
            $('#rem1').hide();
            $('#factory').removeClass('border border-danger');
            }
            if(settime !="" && settime!=undefined){
            $('#rem2').hide();
            $('#selectTime').removeClass('border border-danger');
            }
        }
        function resetinput2(){
            var factory = $('#factory').val();
            $('#rem1').hide();
            $('#factory').removeClass('border border-danger');
            $('#hptsel').val(factory);
            $('#form1').addClass('form-group');
            ShowItem();
        }
        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/round_fac.php';
            $.ajax({
                url: URL,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                beforeSend: function() {
                    swal({
                        title: '<?php echo $array[' pleasewait '][$language]; ?>',
                        text: '<?php echo $array['processing '][$language]; ?>',
                        allowOutsideClick: false
                    })
                    swal.showLoading();
                },
                success: function(result) {
                    try {
                        var temp = $.parseJSON(result);
                    } catch (e) {
                        console.log('Error#542-decode error');
                    }
                    swal.close();
                    if (temp["status"] == 'success') {
                        if ((temp["form"] == 'getSection')) {
                            if(temp[0]['PmID'] != 5 && temp[0]['PmID'] != 7){
                                var StrTr1 = "<option value=''><?php echo $array['selecthospital'][$language]; ?></option>";                            
                            }else{
                                var StrTr = "";
                                $('#hptsel').attr('disabled' , true);
                                $('#hptsel').addClass('icon_select');
                            }
                            for (var i = 0; i < temp[0]['count']; i++) {
                                StrTr += "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                 StrTr1 += "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                            }
                            $("#hptsel").append(StrTr1);
                        }else if ((temp["form"] == 'getfactory')) {
                                var StrTr1 = "<option value=''><?php echo $array['selectfactory'][$language]; ?></option>";       
                                var StrTr = "<option value=''><?php echo $array['selectfactory'][$language]; ?></option>";                                                 
  
                            for (var i = 0; i < temp[0]['count2']; i++) {
                                StrTr += "<option value = '" + temp[i]['FacCode'] + "'> " + temp[i]['FacName'] + " </option>";
                                 StrTr1 += "<option value = '" + temp[i]['FacCode'] + "'> " + temp[i]['FacName'] + " </option>";
                            }
                            $("#factorysel").html(StrTr);
                            $("#factory").html(StrTr1);
                        }else if ((temp["form"] == 'ShowItem')) {
                            $('#TableItem tbody').empty();
                            if(temp['Count']>0){
                                // $('#hptsel').val(temp[0]['HptCode']);
                                // $('#factory').val(temp[0]['HptCode']);
                                for (var i = 0; i < temp['Count']; i++) {
                                    var chkItem = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno' onclick='getDetail("+temp[i]['ID']+");'><span class='checkmark'></span></label>";
                                    var Str = "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'><td style='width:5%' class='text-center'>"+chkItem+"</td>"+
                                        "<td  style='width:10%'>"+(i+1)+"</td>"+
                                        "<td  style='width:85%'>"+temp[i]['SendTime']+"</td>"+
                                    "</tr>";
                                    $("#TableItem tbody").append(Str);
                                }
                            }else{
                                $('#TableItem tbody').empty();
                                var Str = "<tr width='100%' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                                $("#TableItem tbody").append(Str);
                            }
                        }else if((temp["form"] == 'getDetail')){
                            $('#factory').val("");
                            $('#settime').val("");
                            $('#idTime').val("");
                            $('#factory').val(temp['FacCode']);
                            $('#selectTime').val(temp['SendTime']);
                            $('#idTime').val(temp['ID']);
                        }
                    } else if (temp['status'] == "failed") {
                        switch (temp['msg']) {
                            case "notchosen":
                                temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                                break;
                            case "cantcreate":
                                temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                                break;
                            case "noinput":
                                temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                                break;
                            case "notfound":
                                temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                                break;
                            case "addsuccess":
                                temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                                break;
                            case "addfailed":
                                temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                                break;
                            case "editsuccess":
                                temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                                break;
                            case "editfailed":
                                temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                                break;
                            case "cancelsuccess":
                                temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                                break;
                            case "cancelfailed":
                                temp['msg'] = "<?php echo $array['cancelfailed'][$language]; ?>";
                                break;
                            case "nodetail":
                                temp['msg'] = "<?php echo $array['nodetail'][$language]; ?>";
                                break;
                                case "editcenterfailedmsg":
                                    temp['msg'] = "<?php echo $array['editcenterfailedmsg'][$language]; ?>";
                                    break;
                        }
                        swal({
                            title: '',
                            text: temp['msg'],
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            showConfirmButton: false,
                            timer: 2000,
                            confirmButtonText: 'Ok'
                        })

                    } else if (temp['status'] == "notfound") {
                        $("#TableItem tbody").empty();
                    }
                },
                failure: function(result) {
                    alert(result);
                },
                error: function(xhr, status, p3, p4) {
                    var err = "Error " + " " + status + " " + p3 + " " + p4;
                    if (xhr.responseText && xhr.responseText[0] == "{")
                        err = JSON.parse(xhr.responseText).Message;
                    console.log(err);
                }
            });
        }
    </script>
     <style media="screen">
        @font-face {
            font-family: myFirstFont;
            src: url("../fonts/DB Helvethaica X.ttf");
            }
        body{
          font-family: myFirstFont;
          font-size:22px;
        }
        .opacity{
            opacity:0.5;
        }
        .nfont{
          font-family: myFirstFont;
          font-size:22px;
        }
        input,select{
        font-size:24px!important;
        }
        th,td{
        font-size:24px!important;
        }
        .table > thead > tr >th {
        background-color: #1659a2;
        }

        table tr th,
        table tr td {
        border-right: 0px solid #bbb;
        border-bottom: 0px solid #bbb;
        padding: 5px;
        }
        table tr th:first-child,
        table tr td:first-child {
        border-left: 0px solid #bbb;
        }
        table tr th {
        background: #eee;
        border-top: 0px solid #bbb;
        text-align: left;
        }

        /* top-left border-radius */
        table tr:first-child th:first-child {
        border-top-left-radius: 15px;
        }
        table tr:first-child th:first-child {
            border-bottom-left-radius: 15px;
        }

        /* top-right border-radius */
        table tr:first-child th:last-child {
            border-top-right-radius: 15px;
        }
        table tr:first-child th:last-child {
            border-bottom-right-radius: 15px;
        }

        /* bottom-left border-radius */
        table tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
        }

        /* bottom-right border-radius */
        table tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
     }
    </style>
</head>

<body id="page-top">
<ol class="breadcrumb">
  
  <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
  <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][20][$language]; ?></li>
</ol>
    <div id="wrapper">
        <!-- content-wrapper -->
        <div id="content-wrapper">

            <div class="row">
                <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:-12px;">
                            <div class="row">
                                <div class="col-md-7 off-set-5">
                                    <div class="row" style="margin-left:5px;" >
                                        <select class="form-control col-md-4 " id="hptsel" onchange="getfactory();">    </select>

                                        <select class="form-control col-md-4 ml-3" id="factorysel" onchange="ShowItem(1);"> </select>

                                        <div class="search_custom col-md-2">
                                          <div class="search_1 d-flex justify-content-start">
                                            <button class="btn" onclick="ShowItem()" id="bSave">
                                              <i class="fas fa-search mr-2"></i>
                                              <?php echo $array['search'][$language]; ?>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                        <th style='width: 5%;'>&nbsp;</th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 85%;'>
                                            <?php echo $array['Roundfactory'][$language]; ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- tag column 1 -->
            </div>
            <!-- /.content-wrapper -->
            <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
                          <div class="menu mhee" >
                            <div class="d-flex justify-content-center">
                              <div class="circle4 d-flex justify-content-center">
                                <button class="btn"  onclick="AddItem()" id="bSave">
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
                                <button class="btn" onclick="Blankinput()" id="bDelete">
                                  <i class="fas fa-redo-alt"></i>
                                  <div>
                                    <?php echo $array['clear'][$language]; ?>
                                  </div>       
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" id="delete1" >
                            <div class="d-flex justify-content-center" >
                              <div class="circle3 d-flex justify-content-center" id="delete_icon">
                                <button class="btn" onclick="CancelItem()" id="bCancel" disabled="true">
                                  <i class="fas fa-trash-alt"></i>
                                  <div>
                                    <?php echo $array['cancel'][$language]; ?>
                                  </div>  
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:10px;">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        <?php echo $array['detail'][$language]; ?></a>
                                </li>
                            </ul>
   <!-- =================================================================== -->
                                <div class="row mt-4">
                                  <div class="col-md-6">
                                    <div class='form-group row' id="form1">
                                    <label class="col-sm-3 col-form-label "><?php echo $array['factory'][$language]; ?></label>
                                      <select  class="form-control col-sm-7 checkblank" id="factory" onchange="ShowItem(2)">
                                      </select>
                                      <label id="rem1" class="col-sm-1 text-danger" style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['Roundfactory'][$language]; ?></label>
                                        <input type="time" class="form-control col-sm-7 checkblank" id="selectTime" onkeyup="resetinput()">
                                        <label id="rem2" class="col-sm-1 text-danger" style="font-size: 180%;margin-top: -1%;"> * </label>
                                        <input type="hidden" id='idTime'>
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->
                     

                        </div>
                    </div>
                </div> <!-- tag column 2 -->
            <!-- /#wrapper -->
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>


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
            <script src="../js/jquery.timepicker.js"></script>

</body>

</html> 