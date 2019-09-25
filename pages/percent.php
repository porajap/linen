<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$HptCode = $_SESSION['HptCode'];
$PmID = $_SESSION['PmID'];

if($Userid==""){
   header("location:../index.html");
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

    <title><?php echo $array['percent1'][$language]; ?></title>

    <link rel="icon" type="image/png" href="../img/pose_favicon.png">
    <!-- Bootstrap core CSS-->
    <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/tbody.css" rel="stylesheet">
    <link href="../bootstrap/css/myinput.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../css/menu_custom.css" rel="stylesheet">

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

     <?php if ($language =='th'){ ?>
      <script src="../datepicker/dist/js/datepicker.js"></script>
    <?php }else if($language =='en'){ ?>
        <script src="../datepicker/dist/js/datepicker-en.js"></script>
    <?php } ?>

    <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
    <script src="../datepicker/dist/js/i18n/datepicker.th.js"></script>


    <script type="text/javascript">
      var summary = [];

      function twoDigit(s){
          var sNum = s.toString();
          if(sNum.length==1) sNum = "0"+s;
          return sNum;
        }

    $(document).ready(function(e){
      $('.only').on('input', function() {
        this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
      });
      var lang = '<?php echo $language; ?>';
      var d = new Date();

      if(lang =='th'){
        $('#datepicker1').val(twoDigit(d.getDate())+"-"+(twoDigit(d.getMonth()+1))+"-"+(d.getFullYear()+543));
        $('#datepicker2').val(twoDigit(d.getDate())+"-"+(twoDigit(d.getMonth()+1))+"-"+(d.getFullYear()+543));
      }
        
      else if(lang =='en'){
        $('#datepicker1').val(twoDigit(d.getDate())+"-"+(twoDigit(d.getMonth()+1))+"-"+d.getFullYear());
        $('#datepicker2').val(twoDigit(d.getDate())+"-"+(twoDigit(d.getMonth()+1))+"-"+d.getFullYear());
      }

        OnLoadPage();
		    getDepartment();
    }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });

    jqui(document).ready(function($){

    dialog = jqui( "#dialog" ).dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "ปิด": function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui( "#dialogItem" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });

    });

	function OpenDialogItem(){
		var docno = $("#docno").val();
		if( docno != "" ) dialog.dialog( "open" );
	}
	//======= On create =======
	//console.log(JSON.stringify(data));
	function OnLoadPage(){
      var lang = '<?php echo $language; ?>';
      var data = {
        'STATUS'  : 'OnLoadPage' ,
        'lang'	: lang 
      };
      senddata(JSON.stringify(data));
	  $('#isStatus').val(0)
	}

  function getDepartment(){
      var Hotp = $('#hotpital option:selected').attr("value");
      if( typeof Hotp == 'undefined' ) 
      {
        Hotp = '<?php echo $HptCode; ?>';
      var data = {
        'STATUS'  : 'getDepartment',
        'Hotp'	: Hotp
      };

      senddata(JSON.stringify(data));
      }
    }

	function ShowDocument(){
	  var datepicker1 = $('#datepicker1').val();
	  var datepicker2 = $('#datepicker2').val();
    var lang = '<?php echo $language; ?>';
    if(lang =='th'){
	  datepicker1 = datepicker1.substring(6, 10)-543+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
	  datepicker2 = datepicker2.substring(6, 10)-543+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
    }else if(lang =='en'){
    datepicker1 = datepicker1.substring(6, 10)+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
	  datepicker2 = datepicker2.substring(6, 10)+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
    }

	  var deptCode = $('#department option:selected').attr("value");
	  if( typeof deptCode == 'undefined' ) deptCode = "1";
	  //alert( deptCode );
      var data = {
        'STATUS'  	: 'ShowDocument',
		'deptCode'	: deptCode,
		'sDate'	: datepicker1,
		'eDate'	: datepicker2
      };
      senddata(JSON.stringify(data));
	}

    function logoff() {
      swal({
        title: '',
        text: '<?php echo $array['logout'][$language]; ?>',
        type: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        timer: 1000,
        confirmButtonText: 'Ok'
      }).then(function () {
        window.location.href="../logoff.php";
      }, function (dismiss) {
        window.location.href="../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }


    function senddata(data){
       var form_data = new FormData();
       form_data.append("DATA",data);
       var URL = '../process/percent.php';
       $.ajax({
                     url: URL,
                     dataType: 'text',
                     cache: false,
                     contentType: false,
                     processData: false,
                     data: form_data,
                     type: 'post',
                     success: function (result) {
							try {
							 var temp = $.parseJSON(result);
							} catch (e) {
								console.log('Error#542-decode error');
							}
              if(temp["status"]=='success'){
                if(temp["form"]=='OnLoadPage'){
                  var PmID = <?php echo $PmID;?>;
                  var HptCode = '<?php echo $HptCode;?>';
                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
                    $("#hotpital").append(Str);
                  }
                  if(PmID != 1){
                      $("#hotpital").val(HptCode);
                    }
                }else if(temp["form"]=='getDepartment'){
                  $("#department").empty();
                  $("#Dep2").empty();
                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                    $("#department").append(Str);
                    $("#Dep2").append(Str);
                  }
										  }else if(temp["form"]=='ShowDocument'){
				                              $( "#TableDocument tbody" ).empty();
				                              $("#docno").val(temp[0]['DocNo']);
											  $("#docdate").val(temp[0]['DocDate']);
											  $("#recorder").val(temp[0]['Record']);
											  $("#timerec").val(temp[0]['RecNow']);
											  $("#wTotal").val(temp[0]['Total']);

				                              for (var i = 0; i < (Object.keys(temp).length-2); i++) {
												   var rowCount = $('#TableDocument >tbody >tr').length;
												   var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' >";
												   var Status = "";
												   var Style  = "";
												   var textColor  = "";
												   if(temp[i]['IsStatus']==1){
												   		Status = "<?php echo $array['savesuccess'][$language]; ?>";
														Style  = "style='width: 10%;color: #20B80E;'";
												   }else{
												   		Status = "<?php echo $array['draft'][$language]; ?>";
														Style  = "style='width: 10%;color: #3399ff;'";
												   }if(temp[i]['IsStatus']==2){
												   		Status = "<?php echo $array['cancelbill'][$language]; ?>";
														  Style  = "style='width: 10%;color: #ff0000;'";
												   }

                            if(Number(temp[i]['Precent'])>8){
                              textColor = "'text-color:#ff0000;'";
                            }

												   $StrTr="<tr id='tr"+temp[i]['DocNo']+"'>"+
															  "<td style='width: 5%;'nowrap>"+(i+1)+"</td>"+
															  "<td style='width: 15%;'nowrap>"+temp[i]['DocNo1']+"</td>"+
															  "<td style='width: 10%;'nowrap>"+temp[i]['DocDate1']+"</td>"+
															  "<td style='width: 10%;'nowrap>"+temp[i]['Total1']+"</td>"+
															  "<td style='width: 15%;'nowrap>"+temp[i]['DocNo2']+"</td>"+
															  "<td style='width: 18%;'nowrap>"+temp[i]['DocDate2']+"</td>"+
															  "<td style='width: 10%;'nowrap>"+temp[i]['Total2']+"</td>"+
															  "<td style='width: 11%;text-align:center;"+textColor+"' nowrap>"+temp[i]['Precent']+ " %" +"</td>"+
														  "</tr>";

					                               if(rowCount == 0){
					                                 $("#TableDocument tbody").append( $StrTr );
					                               }else{
					                                 $('#TableDocument tbody:last-child').append(  $StrTr );
					                               }
												}

											}

                        	}else if (temp['status']=="failed") {
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
                                temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
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

								  $( "#docnofield" ).val( temp[0]['DocNo'] );
								  $( "#TableDocumentSS tbody" ).empty();
								  $( "#TableSendSterileDetail tbody" ).empty();

							}else{
							  console.log(temp['msg']);
							}
                     },
                     failure: function (result) {
                          alert(result);
                     },
                     error: function (xhr, status, p3, p4) {
                          var err = "Error " + " " + status + " " + p3 + " " + p4;
                          if (xhr.responseText && xhr.responseText[0] == "{")
                               err = JSON.parse(xhr.responseText).Message;
                          console.log(err);
						  alert(err);
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

        .nfont{
          font-family: myFirstFont;
          font-size:22px;
        }

    button,input[id^='qty'] {
      font-size: 24px!important;
    }
    .table > thead > tr >th {
      background-color: #1659a2;
    }
    .table th, .table td {
        border-top: none !important;
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
		a.nav-link{
			width:auto!important;
		}
    .mhee a{
            /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
            color: #818181;
            display: block;
            }
            .mhee a:hover {
            color: #2c3e50;
            font-weight:bold;
            font-size:26px;
        }
        .mhee button{
            /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
            color: #818181;
            display: block;
            }
            .mhee button:hover {
            color: #2c3e50;
            font-weight:bold;
            font-size:26px;
        }
       .datepicker{z-index:9999 !important}
       .hidden{visibility: hidden;}
    </style>
  </head>

  <body id="page-top">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
      <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][4][$language]; ?></li>
    </ol>
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

    <div id="wrapper">
      <!-- content-wrapper -->
      <div id="content-wrapper">

    <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
<div class="col-md-12"> <!-- tag column 1 -->
<!-- /.content-wrapper -->
          

                      <div class="row">
                        <div class="col-md-11"> <!-- tag column 1 -->
                          <div class="container-fluid">
                            <div class="card-body mt-3" >

                            <div class="row">
                              <div class="col-md-5">
                                <div class='form-group row'>
                                  <label class="col-sm-4 col-form-label text-right" style="font-size:24px;"><?php echo $array['side'][$language]; ?></label>
                                  <select class="form-control col-sm-8" id="hotpital" style="font-size:22px;" onchange="getDepartment();"  <?php if($PmID != 2) {echo "disabled='true'" ;} ?> ></select>
                                </div>
                              </div>
                              <div class="col-md-5">
                                <div class='form-group row'>
                                  <label class="col-sm-4 col-form-label text-right" style="font-size:24px;"><?php echo $array['department'][$language]; ?></label>
                                    <select  class="form-control col-sm-7" style="font-size:22px;" id="department"  <?php if($PmID != 2) {echo "disabled='true'" ;} ?> ></select>
                                </div>
                              </div>
                            </div>




                            <div class="row">
                              <div class="col-md-5">
                                <div class='form-group row'>
                                  <label class="col-sm-4 col-form-label text-right" style="font-size:24px;"><?php echo $array['datestart'][$language]; ?></label>
                                  <input type="text" autocomplete="off" class="form-control col-sm-8 datepicker-here only" style="font-size:22px;" id="datepicker1"  data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' >
                                </div>
                              </div>
                              <div class="col-md-5">
                                <div class='form-group row'>
                                  <label class="col-sm-4 col-form-label text-right" style="font-size:24px;"><?php echo $array['dateend'][$language]; ?></label>
                                  <input type="text" autocomplete="off" class="form-control col-sm-7 datepicker-here only" style="font-size:22px;"  id="datepicker2"  data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' >
                                </div>
                              </div>
                              <div class="col-md-2 mhee">
                                <div class='form-group row'>
                                <div class="search_custom col-md-3">
                              <div class="search_1 d-flex justify-content-start">
                                <button class="btn"  onclick="ShowDocument()" >
                                  <i class="fas fa-search mr-2"></i> <?php echo $array['search'][$language]; ?>
                                </button>
                              </div>
                            </div>
                            <!--  -->
                                <!-- <button  type="button" class="btn btn-info col-sm-9" onclick="ShowDocument()" id="bSearch"><?php echo $array['search'][$language]; ?></button> -->
                                </div>
                              </div>
                            </div>
                    </div>
                  </div>
              </div> <!-- tag column 1 -->
    </div>

          <div class="row">
              <div style='width: 98%;'> <!-- tag column 1 -->
              		<table style="margin-top:-15px;margin-left:15px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid" style="">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 5%;'nowrap><?php echo $array['sn'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['dirtydoc'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['date'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['weight'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['cleandoc'][$language]; ?></th>
                              <th style='width: 17%;'nowrap><?php echo $array['date'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['weight'][$language]; ?></th>
                              <th style='width: 13%;'><?php echo $array['percent'][$language]; ?></th>
                            </tr>
                          </thead>
                          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:360px;">
                          </tbody>
					</table>
              </div> <!-- tag column 1 -->
    </div>

</div>
</div> <!-- end row tab -->

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
