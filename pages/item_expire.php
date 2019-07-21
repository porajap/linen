<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
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
$json = json_encode($xml);
$array = json_decode($json,TRUE);
 ?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $array['titlecontracthos'][$language]; ?></title>

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

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="../jQuery-ui/jquery-1.12.4.js"></script>
    <script src="../jQuery-ui/jquery-ui.js"></script>
    <script type="text/javascript">
      jqui = jQuery.noConflict(true);
    </script>

    <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
    <script src="../dist/js/sweetalert2.min.js"></script>
    <script src="../dist/js/jquery-3.3.1.min.js"></script>


    <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="../datepicker/dist/js/datepicker.min.js"></script>
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

    <script type="text/javascript">
      var summary = [];

    $(document).ready(function(e){
		$("#IsStatus").val('0');
		OnLoadPage();
	  getDepartment();
    }).mousemove(function(e) { parent.last_move = new Date();;
    }).keyup(function(e) { parent.last_move = new Date();;
    });

    jqui(document).ready(function($){

		dialog = jqui( "#dialog" ).dialog({
		  autoOpen: false,
		  height: 650,
		  width: 1200,
		  modal: true,
		  buttons: {
			"<?php echo $array['close'][$language]; ?>": function() {
			  dialog.dialog( "close" );
			}
		  },
		  close: function() {
			console.log("close");
		  }
		});

    });


	//======= On create =======
	//console.log(JSON.stringify(data));
	function OnLoadPage(){
      var data = {
        'STATUS'  : 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
	  $('#IsStatus').val(0)
	}

	function getDepartment(){
	  var Hotp = $('#side option:selected').attr("value");
	  if( typeof Hotp == 'undefined' ) Hotp = "1";
      var data = {
        'STATUS'  : 'getDepartment',
		      'Hotp'	: Hotp
      };
      senddata(JSON.stringify(data));
	}

	function CreateDocument(){
		 dialog.dialog( "open" );
    }

	function getRow(id){
		 var data = {
        'STATUS'  : 'getRow',
		'RowID'	: id
      };
      senddata(JSON.stringify(data));
    }

	function ClearRow(){
		$("#IsStatus").val('0');
		$("#datepicker3").val('');
		$("#datepicker4").val('');
		$("#xDetail").val('');
		$('#side option[value="1"]').prop("selected", true);
	}

	function CancelRow(){
		var id = $("#xRowID").val();

		swal({
          title: "<?php echo $array['confirm'][$language]; ?>",
          text: "<?php echo $array['factory'][$language]; ?> : " +$('#side option:selected').text(),
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
          cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
			  var data = {
				'STATUS'  : 'CancelRow',
				'RowID'	: id
			  };
			  senddata(JSON.stringify(data));
		})
    }

	function SaveRow(){
		var isStatus = $("#IsStatus").val();
		var id = $("#xRowID").val();
		var hotid = $('#side option:selected').attr("value");
	    if( typeof hotid == 'undefined' ) hotid = "1";
		var depid = $('#department option:selected').attr("value");
	    if( typeof depid == 'undefined' ) depid = "1";

		var datepicker1 = $('#datepicker3').val();
	    var datepicker2 = $('#datepicker4').val();
		var xDetail = $("#xDetail").val();

	    //datepicker1 = datepicker1.substring(6, 10)+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
	    //datepicker2 = datepicker2.substring(6, 10)+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);

		var data = {
			'STATUS'  	: 'SaveRow',
			'isStatus'	: isStatus,
			'RowID'		: id,
			'hotid'		: hotid,
			'depid'		: depid,
			'sDate'		: datepicker1,
			'eDate'		: datepicker2,
			'Detail'	: xDetail
		};
		senddata(JSON.stringify(data));
	}

	function ShowDocument(){
	  var datepicker1 = $('#datepicker1').val();
	  var datepicker2 = $('#datepicker2').val();
	  //datepicker1 = datepicker1.substring(6, 10)+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
	  //datepicker2 = datepicker2.substring(6, 10)+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
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
       var URL = '../process/item_expire.php';
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
											for (var i = 0; i < (Object.keys(temp).length-2); i++) {
												var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
												$("#side").append(Str);
											}
										  }else if(temp["form"]=='getDepartment'){
											$("#department").empty();
											for (var i = 0; i < (Object.keys(temp).length-2); i++) {
												var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
												$("#department").append(Str);
											}
										  }else if(temp["form"]=='ShowDocument'){
                        $( "#TableDocument tbody" ).empty();
											  var Style  = "";
				                for (var i = 0; i < (Object.keys(temp).length-2); i++) {
												   var rowCount = $('#TableDocument >tbody >tr').length;
												   var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' >";

                           var sDate = new Date();
                           var eDate = new Date( temp[i]['EndDate'] );
                           var diff  = new Date(eDate - sDate);

                           var days = Math.round(diff/1000/60/60/24);

												   if( (days >=16) && (days <= 30) ){
													   Style  = "style='font-weight: bold;color:#ff6600;'";
												   }else if(days <= 15){
													   Style  = "style='font-weight: bold;color: #ff0000;'";
												   }else{
													   Style  = "style='color: #1e1e2f;'";
												   }

												   $StrTr="<tr "+Style+" id='tr"+temp[i]['RowID']+"' onclick='getRow( "+temp[i]['RowID']+" )'>"+
															  "<td style='width: 15%;'>"+(i+1)+"</td>"+
															  "<td style='width: 25%;'>"+temp[i]['ItemCode']+"</td>"+
															  "<td style='width: 30%;'>"+temp[i]['ItemName']+"</td>"+
															  "<td style='width: 30%;'>"+temp[i]['ExpireDate']+"</td>"+
														  "</tr>";
                              if(rowCount == 0){
                                $("#TableDocument tbody").append( $StrTr );
                              }else{
                                $('#TableDocument tbody:last-child').append(  $StrTr );
                              }
                            }
										  }else if(temp["form"]=='getRow'){
											  $("#IsStatus").val('1');
											  $("#xRowID").val(temp[0]['RowID']);
												$("#datepicker3").val(temp[0]['ItemCode']);
												$("#datepicker4").val(temp[0]['ItemName']);
												$("#xDetail").val(temp[0]['ExpireDate']);

												var hosCode = temp[0]['HptCode'];
												var hos_length = $('#side > option').length;


												for(var i=0;i<hos_length;i++){
													if(hosCode == i) $('#side option[value="'+i+'"]').prop("selected", true);
												}
												//alert(temp['Dep_Cnt']);
												$("#department").empty();
												for (var i = 0; i < temp['Dep_Cnt']; i++) {
													var Str = "<option value="+temp['Dep_'+i]['DepCode']+">"+temp['Dep_'+i]['DepName']+"</option>";
													$("#department").append(Str);
													if(temp[0]['DepCode'] == temp['Dep_'+i]['DepCode']) $('#department option[value="'+temp[0]['DepCode']+'"]').prop("selected", true);
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
                                temp['msg'] = "<?php echo $array['nodetail'][$language]; ?>";
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

		body{
		   font-family: 'THSarabunNew';
		   font-size:22px;
		}

		.nfont{
		   font-family: 'THSarabunNew';
		   font-size:22px;
		}
    button,input[id^='qty'],input[id^='weight'],input[id^='price']{
      font-size: 24px!important;
    }
    .table > thead > tr >th {
      background: #4f88e3!important;
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
      border-top-left-radius: 6px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 6px;
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
       .datepicker{z-index:9999 !important}
       .hidden{visibility: hidden;}
    </style>
  </head>

  <body id="page-top">
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
                    <div class="card-body" style="padding:0px; margin-top:10px;">
                        <div class="row" style="margin-top:5px">

<!-- Default switch

<div style="width:20px; float: right; margin: 0; padding: 0;  ">
  <div style="margin: 5px 5px 5px 0; vertical-align:bottom;">
    <div style="display: flex; align-items: flex-end; transform: rotate(90deg); vertical-align: bottom;margin-bottom: 0;left:0; font-size: 8px; width: 56px ; height: 55px">
      TESTING NOW
    </div>
  </div>
</div>

<input type="radio" id="r1" name="rr" />
-->

                                      <div style="margin-left:20px;width:100px;">
												<label><?php echo $array['side'][$language]; ?></label>
                                      </div>
                                      <div style="width:220px;">
                                        <select style="font-size:24px;width:220px;font-family: 'THSarabunNew'" class="form-control" id="side" onchange="getDepartment();" disabled="true">
                                        </select>
                                      </div>
                                      <div style="margin-left:30px;width:120px;">
												<label><?php echo $array['department'][$language]; ?></label>
                                      </div>
                                      <div style="width:220px;">
                                        <select style="font-size:24px;width:220px;font-family: 'THSarabunNew'" class="form-control" id="department" disabled="true">
                                        </select>
                                      </div>
                                      <div style="margin-left:50px;width:305px;">
 <button style="width:105px"; type="button" class="btn btn-primary" onclick="ShowDocument()" id="bSearch"><?php echo $array['search'][$language]; ?></button>
                                      </div>
                        </div>
                    </div>
                  </div>
              </div> <!-- tag column 1 -->
    </div>

<div class="row">
              <div style='width: 98%;'> <!-- tag column 1 -->
              		<table style="margin-top:10px;margin-left:15px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid" style="">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 15%;'><?php echo $array['no'][$language]; ?></th>
                              <th style='width: 25%;'><?php echo $array['code'][$language]; ?></th>
                              <th style='width: 30%;'><?php echo $array['item'][$language]; ?></th>
                              <th style='width: 30%;'><?php echo $array['numbercontract'][$language]; ?></th>
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
<!-- Dialog Modal
    <div id="dialog" title="คู่สัญญา"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
      <div class="container">
        <div class="row">
          <div class="col-md-5">


          </div>
        </div>
        <div class="dropdown-divider" style="margin-top:20px; margin-bottom:20px;"></div>
        <div class="row">

        </div>

      </div>
-->

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
