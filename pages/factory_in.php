<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$HptCode = $_SESSION['HptCode'];

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

    <title>รับผ้าสะอาด</title>

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
  <link href="../css/responsive.css" rel="stylesheet">

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
      var xItemcode;
    $(document).ready(function(e){
		OnLoadPage();
		getDepartment();
	  	// CreateDocument();
	//==============================
	$('.TagImage').bind('click', {
		imgId: $(this).attr('id') }, function (evt) { alert(evt.imgId); });
      //On create
		  var userid = '<?php echo $Userid; ?>';
		  if(userid!="" && userid!=null && userid!=undefined){
			var dept = '<?php echo $_SESSION['Deptid']; ?>';
			var data = {
			  'STATUS'  : 'getDocument',
			  'DEPT'    : dept
			};

			// console.log(JSON.stringify(data));
			senddata(JSON.stringify(data));
		  }
    }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });

  jqui(document).ready(function($){

    // dialogRefDocNo = jqui( "#dialogRefDocNo" ).dialog({
    //   autoOpen: false,
    //   height: 670,
    //   width: 1200,
    //   modal: true,
    //   buttons: {
    //     "<?php echo $array['close'][$language]; ?>": function() {
    //       dialogRefDocNo.dialog( "close" );
    //     }
    //   },
    //   close: function() {
    //     console.log("close");
    //   }
    // });

    // dialogItemCode = jqui( "#dialogItemCode" ).dialog({
    //   autoOpen: false,
    //   height: 670,
    //   width: 1200,
    //   modal: true,
    //   buttons: {
    //     "<?php echo $array['close'][$language]; ?>": function() {
    //       dialogItemCode.dialog( "close" );
    //     }
    //   },
    //   close: function() {
    //     console.log("close");
    //   }
    // });

    // jqui( "#dialogItemCode" ).button().on( "click", function() {
    //   dialogItemCode.dialog( "open" );
    // });

    dialogUsageCode = jqui( "#dialogUsageCode" ).dialog({
        autoOpen: false,
        height: 670,
        width: 1200,
        modal: true,
        buttons: {
          "<?php echo $array['close'][$language]; ?>": function() {
            dialogUsageCode.dialog( "close" );
          }
        },
        close: function() {
          console.log("close");
        }
    });

    // jqui( "#dialogUsageCode" ).button().on( "click", function() {
    //   dialogUsageCode.dialog( "open" );
    // });

 });

 function OpenDialogItem(){
        var docno = $("#docno").val();
        // if( docno != "" ) dialogItemCode.dialog( "open" );
        if(docno != ""){
          $('#dialogItemCode').modal('show');
        }
      }

  function OpenDialogUsageCode(itemcode){
    xItemcode = itemcode;
    var docno = $("#docno").val();
		if( docno != "" ){
      dialogItemCode.dialog( "close" );
      dialogUsageCode.dialog( "open" );
      ShowUsageCode();
    }
  }

	function DeleteItem(){
	  var docno = $("#docno").val();
	  var xrow = $("#checkrow:checked").val() ;
	  xrow = xrow.split(",");
	  swal({
          title: "<?php echo $array['confirm'][$language]; ?>",
          text: "<?php echo $array['confirm1'][$language]; ?>"+xrow[1]+"<?php echo $array['confirm2'][$language]; ?>",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
          cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
			  var data = {
				'STATUS'    : 'DeleteItem',
				'rowid'  : xrow[0],
				'DocNo'   : docno
			  };
			  senddata(JSON.stringify(data));
		})
	}

	//======= On create =======
	//console.log(JSON.stringify(data));
	function OnLoadPage(){
      var data = {
        'STATUS'  : 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
	  $('#isStatus').val(0)
	}

  function open_dirty_doc(){
        // dialogRefDocNo.dialog( "open" );
        $('#dialogRefDocNo').modal('show');
        get_dirty_doc();
      }

  function get_dirty_doc(){
    var docno = $("#docno").val();
    var data = {
      'STATUS' : 'get_dirty_doc',
      'DocNo'  : docno
    };
    console.log(JSON.stringify(data));
    senddata(JSON.stringify(data));
  }

  const dirtydoc = async function(dept,cart) {
    const dirty = await swal({
        title: '<?php echo $array['dirtydocs'][$language]; ?>',
        input: 'select',
        inputOptions: cart,
        inputPlaceholder: '<?php echo $array['dirtydoc1'][$language]; ?>',
        showCancelButton: true,
        allowOutsideClick: false
      }).then(function (docno) {
        if(docno==""){
          dirtydoc(dept,cart);
        }else{
          // console.log(occid);
          $('#RefDocNo').val(docno);
        }
      }, function (dismiss) {
      if (dismiss === 'cancel') {

      }
    })

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

	function ShowDocument(selecta){
	  var searchdocument = $('#searchdocument').val();
	  if( typeof searchdocument == 'undefined' ) searchdocument = "";
	  var deptCode = $('#Dep2 option:selected').attr("value");
	  if( typeof deptCode == 'undefined' ) deptCode = "1";
      var data = {
        'STATUS'  	: 'ShowDocument',
        'xdocno'	: searchdocument,
        'selecta' : selecta,
        'deptCode'	: deptCode
      };
      senddata(JSON.stringify(data));
	}

	function ShowItem(){
	  var searchitem = $('#searchitem').val();
      var data = {
        'STATUS'  : 'ShowItem',
		'xitem'	: searchitem
      };
      senddata(JSON.stringify(data));
	}

  function ShowUsageCode(){
    // var searchitem = $('#searchitem1').val();
    var docno = $("#docno").val();
      var data = {
        'STATUS'  : 'ShowUsageCode',
		    'docno'	: docno,
        'xitem'	: xItemcode
      };
      senddata(JSON.stringify(data));
  }

	function SelectDocument(){
    $('#tab2').attr('hidden',false);
          $('#switch_col').removeClass('col-md-12');
          $('#switch_col').addClass('col-md-10');
	  var selectdocument = "";
	  $("#checkdocno:checked").each(function() {
        selectdocument = $(this).val();
      });
	  var deptCode = $('#Dep2 option:selected').attr("value");
	  if( typeof deptCode == 'undefined' ) deptCode = "1";

      var data = {
        'STATUS'  	: 'SelectDocument',
		'xdocno'	: selectdocument
      };
      senddata(JSON.stringify(data));
	}

	function unCheckDocDetail(){
        // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
        if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length){
            $('input[name="checkAllDetail').prop('checked',true);
        }else {
            $('input[name="checkAllDetail').prop('checked',false);
        }
    }

	function ShowDetail() {
	  var docno = $("#docno").val();
	  var data = {
        'STATUS'  : 'ShowDetail',
		'DocNo'   : docno
      };
      senddata(JSON.stringify(data));
	}

	function CancelBill() {
	  var docno = $("#docno").val();
	  var data = {
        'STATUS'  : 'CancelBill',
		'DocNo'   : docno
      };
      senddata(JSON.stringify(data));
	  $('#profile-tab').tab('show');
    $('#tab2').attr('hidden',true);
              $('#switch_col').removeClass('col-md-10');
              $('#switch_col').addClass('col-md-12');
	}

    function getImport(Sel) {
	  var docno = $("#docno").val();
      /* declare an checkbox array */
    var iArray = [];
	  var qtyArray = [];
	  var chkArray = [];
	  var weightArray = [];
	  var unitArray = [];

	  var i=0;
//  alert("Sel : "+Sel);
    if(Sel==1){
      $("#checkitem:checked").each(function() {
        iArray.push($(this).val());
      });
    }else{
      $("#checkitemSub:checked").each(function() {
        iArray.push($(this).val());
      });
    }

      /* we join the array separated by the comma */

	  for(var j=0;j<iArray.length; j++){
		  if(Sel==1)
            chkArray.push( $("#RowID"+iArray[j]).val() );
      else
          chkArray.push( $("#RowIDSub"+iArray[j]).val() );

		  qtyArray.push( $("#iqty"+iArray[j]).val() );
		  weightArray.push( $("#iweight"+iArray[j]).val() );
		  unitArray.push( $("#iUnit_"+iArray[j]).val() );
	  }

	  var xrow = chkArray.join(',') ;
	  var xqty = qtyArray.join(',') ;
	  var xweight = weightArray.join(',') ;
	  var xunit = unitArray.join(',') ;

	    // alert("xrow : "+xrow);

      $('#TableDetail tbody').empty();
      var data = {
        'STATUS'  	: 'getImport',
        'xrow'		  : xrow,
        'xqty'		  : xqty,
        'xweight'	  : xweight,
        'xunit'		  : xunit,
        'DocNo'   	: docno,
        'Sel'       : Sel
      };
      senddata(JSON.stringify(data));
      $('#dialogItemCode').modal('toggle')
      dialogUsageCode.dialog( "close" );
    }

var isChecked1 = false;
var isChecked2 = false;
    function getCheckAll(sel){
        if(sel==0){
          isChecked1 = !isChecked1;
            $('input[name="checkdocno"]').each(function(){
                this.checked = isChecked1;
            });
            getDocDetail();
        }else{
            isChecked2 = !isChecked2;
            $('input[name="checkdocdetail"]').each(function(){
                  this.checked = isChecked2;
            });
        }
    }

	function convertUnit(rowid,selectObject){
		var docno = $("#docno").val();
		var data = selectObject.value;
	    var chkArray = data.split(",");
		var weight = $('#weight_'+chkArray[0]).val();
		var qty = $('#qty1_'+chkArray[0]).val();
		var oleqty = $('#OleQty_'+chkArray[0]).val();
		qty = oleqty*chkArray[2];
		$('#qty1_'+chkArray[0]).val(qty);
// console.log( chkArray[1] );
		var data = {
			'STATUS'  	: 'updataDetail',
			'Rowid'     : rowid,
			'DocNo'   	: docno,
			'unitcode'	: chkArray[1],
			'qty'		: qty
		};
		senddata(JSON.stringify(data));
	}

    function CreateDocument(){
      var userid = '<?php echo $Userid; ?>';
      var hotpCode = $('#hotpital option:selected').attr("value");
	  var deptCode = $('#department option:selected').attr("value");
	  $('#TableDetail tbody').empty();
	  swal({
          title: "<?php echo $array['confirm'][$language]; ?>",
          text: "<?php echo $array['side'][$language]; ?> : " +$('#hotpital option:selected').text()+ " <?php echo $array['department'][$language]; ?> : " +$('#department option:selected').text(),
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
				'STATUS'    : 'CreateDocument',
				'hotpCode'  : hotpCode,
				'deptCode'  : deptCode,
				'userid'	: userid
			  };
			  senddata(JSON.stringify(data));
		})
    }

    function canceldocno(docno) {
        swal({
          title: "<?php echo $array['confirmdelete'][$language]; ?>",
          text: "<?php echo $array['confirmdelete1'][$language]; ?>" +docno+ "<?php echo $array['confirmdelete2'][$language]; ?>",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['delete'][$language]; ?>",
          cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
              var data = {
                'STATUS'      : 'CancelDocNo',
                'DocNo'       : docno
              };
              senddata(JSON.stringify(data));
              getSearchDocNo();
                })
    }

    function addnum(cnt) {
      var add = parseInt($('#iqty'+cnt).val())+1;
      if((add>0) && (add<=500)){
        $('#iqty'+cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#iqty'+cnt).val())-1;
      if((sub>0) && (sub<=500)) {
        $('#iqty'+cnt).val(sub);
      }
    }

	function addnum1(rowid,cnt,unitcode) {
	  var Dep = $("#Dep_").val();
	  var docno = $("#docno").val();
      var add = parseInt($('#qty1_'+cnt).val())+1;
	  var newQty = parseInt($('#OleQty_'+cnt).val())+1;
	  var isStatus = $("#IsStatus").val();
	  if(isStatus==0){
      	  if((add>=0) && (add<=500)){
			$('#qty1_'+cnt).val(add);
			$('#OleQty_'+cnt).val(newQty);
		  }
		  var data = {
			  'STATUS'      : 'UpdateDetailQty',
			  'Rowid'       : rowid,
			  'DocNo'       : docno,
			  'Qty'			: add,
			  'OleQty'		: newQty,
			  'unitcode'	: unitcode
		  };
		  senddata(JSON.stringify(data));
	  }
    }

    function subtractnum1(rowid,cnt,unitcode) {
	  var Dep = $("#Dep_").val();
	  var docno = $("#docno").val();
      var sub = parseInt($('#qty1_'+cnt).val())-1;
	  var newQty = parseInt($('#OleQty_'+cnt).val())-1;
	  var isStatus = $("#IsStatus").val();
	  if(isStatus==0){
		  if((sub>=0) && (sub<=500)) {
			$('#qty1_'+cnt).val(sub);
			$('#OleQty_'+cnt).val(newQty);
		  }
		  var data = {
			  'STATUS'      : 'UpdateDetailQty',
			  'Rowid'       : rowid,
			  'DocNo'       : docno,
			  'Qty'			: sub,
			  'OleQty'		: newQty,
			  'unitcode'	: unitcode
		  };
		  senddata(JSON.stringify(data));
	  }
  }

	function updateWeight(row,rowid) {
	  var docno = $("#docno").val();
	  var weight = $("#weight_"+row).val();
	  var price = 0; //$("#price_"+row).val();
	  var isStatus = $("#IsStatus").val();
	  //alert(rowid+" :: "+docno+" :: "+weight);
	  if(isStatus==0){
		  var data = {
			  'STATUS'      : 'UpdateDetailWeight',
			  'Rowid'       : rowid,
			  'DocNo'       : docno,
			  'Weight'      : weight,
			  'Price'      : price
		  };
		  senddata(JSON.stringify(data));
	  }
  }

	function SaveBill(){
    var docno = $("#docno").val();
	  var docno2 = $("#RefDocNo").val();
	  var isStatus = $("#IsStatus").val();
	  // alert( isStatus );
	  if(isStatus==1)
	  		isStatus=0;
	  else
	  		isStatus=1;

	  var data = {

          'STATUS'      : 'SaveBill',
      'xdocno'      : docno,
		  'xdocno2'      : docno2,
		  'isStatus'    : isStatus
      };
      senddata(JSON.stringify(data));
	  if(isStatus==1){
      $('#tab2').attr('hidden',true);
              $('#switch_col').removeClass('col-md-10');
              $('#switch_col').addClass('col-md-12');
			$('#profile-tab').tab('show');
			ShowDocument();
	  }else{
			$("#bImport").prop('disabled', false);
			$("#bDelete").prop('disabled', false);
			$("#bSave").prop('disabled', false);
			$("#bCancel").prop('disabled', false);
			$("#bSave").text('<?php echo $array['save'][$language]; ?>');
			$("#IsStatus").val("0");
	  }
	}

	function UpdateRefDocNo(){
	  var docno = $("#docno").val();
    var RefDocNo;
      $("#checkitem:checked").each(function() {
        RefDocNo = $(this).val();
      });
    var deptCode = $('#Dep2 option:selected').attr("value");
	  var data = {
      'STATUS'      : 'UpdateRefDocNo',
		  'xdocno'      : docno,
		  'RefDocNo'    : RefDocNo,
      'selecta'     : 0,
      'deptCode'	  : deptCode
    };
    senddata(JSON.stringify(data));
    $('#dialogRefDocNo').modal('toggle')
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
       var URL = '../process/factory_in.php';
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
												$("#hotpital").append(Str);
											}
										  }else if(temp["form"]=='getDepartment'){
											$("#department").empty();
											$("#Dep2").empty();
											for (var i = 0; i < (Object.keys(temp).length-2); i++) {
												var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
												$("#department").append(Str);
												$("#Dep2").append(Str);
											}
										  }else if( (temp["form"]=='CreateDocument') ){
											  swal({
												title: "<?php echo $array['createdocno'][$language]; ?>",
												text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
												type: "success",
												showCancelButton: false,
												timer: 5000,
												confirmButtonText: 'Ok',
												closeOnConfirm: false
											  });
											  $( "#TableItemDetail tbody" ).empty();
											  $("#wTotal").val(0);
											  $("#bSave").text('<?php echo $array['save'][$language]; ?>');
												$("#bImport").prop('disabled', false);
												$("#bDelete").prop('disabled', false);
												$("#bSave").prop('disabled', false);
												$("#bCancel").prop('disabled', false);
												$("#docno").prop('disabled', false);
												$("#docdate").prop('disabled', false);
												$("#recorder").prop('disabled', false);
												$("#timerec").prop('disabled', false);
												$("#total").prop('disabled', false);
											  $("#docno").val(temp[0]['DocNo']);
											  $("#docdate").val(temp[0]['DocDate']);
											  $("#recorder").val(temp[0]['Record']);
											  $("#timerec").val(temp[0]['RecNow']);
											  $("#RefDocNo").val("");
										  }else if(temp["form"]=='ShowDocument'){
				                  $( "#TableDocument tbody" ).empty();
				                  //$("#docno").val(temp[0]['DocNo']);
//											  $("#docdate").val(temp[0]['DocDate']);
//											  $("#recorder").val(temp[0]['Record']);
//											  $("#timerec").val(temp[0]['RecNow']);
//											  $("#wTotal").val(temp[0]['Total']);
				                              for (var i = 0; i < (Object.keys(temp).length-2); i++) {
												   var rowCount = $('#TableDocument >tbody >tr').length;
												   var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' >";
												   var Status = "";
												   var Style  = "";
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

												   $StrTr="<tr id='tr"+temp[i]['DocNo']+"'>"+
															  "<td style='width: 10%;'nowrap>"+chkDoc+"</td>"+
															  "<td style='width: 15%;'nowrap>"+temp[i]['DocDate']+"</td>"+
															  "<td style='width: 15%;'nowrap>"+temp[i]['DocNo']+"</td>"+
															  "<td style='width: 15%;'nowrap>"+temp[i]['RefDocNo']+"</td>"+
															  "<td style='width: 15%;'nowrap>"+temp[i]['Record']+"</td>"+
															  "<td style='width: 10%;'nowrap>"+temp[i]['RecNow']+"</td>"+
															  "<td style='width: 10%;'nowrap>"+temp[i]['Total']+"</td>"+
															  "<td "+Style+"nowrap>"+Status+"</td>"+
														  "</tr>";

					                               if(rowCount == 0){
					                                 $("#TableDocument tbody").append( $StrTr );
					                               }else{
					                                 $('#TableDocument tbody:last-child').append(  $StrTr );
					                               }
												}

											}else if(temp["form"]=='SelectDocument'){
											  $('#home-tab').tab('show')
				                              $( "#TableItemDetail tbody" ).empty();
				                              $("#docno").val(temp[0]['DocNo']);
											  $("#docdate").val(temp[0]['DocDate']);
											  $("#recorder").val(temp[0]['Record']);
											  $("#timerec").val(temp[0]['RecNow']);
											  $("#wTotal").val(temp[0]['Total']);
											  $("#IsStatus").val(temp[0]['IsStatus']);
											  $("#RefDocNo").val(temp[0]['RefDocNo']);

											  if(temp[0]['IsStatus']==0){
											  	$("#bSave").text('<?php echo $array['save'][$language]; ?>');
												$("#bImport").prop('disabled', false);
												$("#bDelete").prop('disabled', false);
												$("#bSave").prop('disabled', false);
												$("#bCancel").prop('disabled', false);
											  }else if(temp[0]['IsStatus']==1){
											  	$("#bSave").text('<?php echo $array['edit'][$language]; ?>');
												$("#bImport").prop('disabled', true);
												$("#bDelete").prop('disabled', true);
												//$("#bSave").prop('disabled', true);
												$("#bCancel").prop('disabled', true);
											  }else{
											  	$("#bImport").prop('disabled', true);
												$("#bDelete").prop('disabled', true);
												$("#bSave").prop('disabled', false);
												$("#bCancel").prop('disabled', true);

											    $("#docno").prop('disabled', true);
												$("#docdate").prop('disabled', true);
												$("#recorder").prop('disabled', true);
												$("#timerec").prop('disabled', true);
												$("#total").prop('disabled', true);

												$('#qty1_'+i).prop('disabled', true);
												$('#weight_'+i).prop('disabled', true);
												$('#price_'+i).prop('disabled', true);

												$('#unit'+i).prop('disabled', true);
											  }
											  ShowDetail();
											}else if(temp["form"]=='getImport'  || temp["form"]=='ShowDetail'){
												$( "#TableItemDetail tbody" ).empty();
												if(temp["Row"] > 0)
													$("#wTotal").val(temp[0]['Total']);
												else
													$("#wTotal").val(0);

												var isStatus = $("#IsStatus").val();
												for (var i = 0; i < temp["Row"]; i++) {
												   var rowCount = $('#TableItem >tbody >tr').length;

												   var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:24px;' id='Unit_"+i+"'>";
												   var nUnit = temp[i]['UnitName'];
                              for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                                if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode']){
                                  chkunit += "<option selected value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                                }else{
                                  chkunit += "<option value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                                }
                              }
												   chkunit += "</select>";

												   var chkDoc = "<input type='radio' name='checkrow' id='checkrow' value='"+temp[i]['RowID']+","+temp[i]['ItemName']+"'>";

                           var Qty = "<div class='row' style='margin-left:0px;'><input class='form-control' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='qty1_"+i+"' value='"+temp[i]['Qty']+"' ></div>";
                           // var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode']+"\")'>-</button><input class='form-control' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_"+i+"' value='"+temp[i]['ParQty']+"' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode']+"\")'>+</button></div>";
												   //var OleQty = "<div class='row' style='margin-left:2px;'><input type='hidden' class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='OleQty_"+i+"' value='"+temp[i]['MaxQty']+"' ></div>";

												   var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew;font-size:24px;' id='weight_"+i+"' value='"+temp[i]['Weight']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

												   var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_"+i+"' value='"+temp[i]['Price']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

												   $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
																  "<td style='width: 10%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
																  "<td style='width: 21%;'nowrap>"+temp[i]['ItemCode']+"</td>"+
																  "<td style='width: 24%;'nowrap>"+temp[i]['ItemName']+"</td>"+
                                  "<td style='width: 15%;font-size:24px;'nowrap>"+nUnit+"</td>"+
                                  "<td style='width: 15%;'nowrap>"+Qty+"</td>"+
																  "<td style='width: 13%;'nowrap>"+Weight+"</td>"+
																  "</tr>";


												   if(rowCount == 0){
													 $("#TableItemDetail tbody").append( $StrTR );
												   }else{
													 $('#TableItemDetail tbody:last-child').append( $StrTR );
												   }
												   if(isStatus==0){
													   $("#docno").prop('disabled', false);
													   $("#docdate").prop('disabled', false);
													   $("#recorder").prop('disabled', false);
													   $("#timerec").prop('disabled', false);
													   $("#total").prop('disabled', false);

														$('#qty1_'+i).prop('disabled', false);
														$('#weight_'+i).prop('disabled', false);
														$('#price_'+i).prop('disabled', false);
														$('#price_'+i).prop('disabled', false);

														$('#unit'+i).prop('disabled', false);
													}else{
														$("#docno").prop('disabled', true);
													    $("#docdate").prop('disabled', true);
													    $("#recorder").prop('disabled', true);
													    $("#timerec").prop('disabled', true);
													    $("#total").prop('disabled', true);

														$('#qty1_'+i).prop('disabled', true);
														$('#weight_'+i).prop('disabled', true);
														$('#price_'+i).prop('disabled', true);

														$('#unit'+i).prop('disabled', true);
													}
											    }
											}else if( (temp["form"]=='ShowItem') ){
												var st1 = "style='font-size:24px;margin-left:3px; width:160px;font-family:THSarabunNew;font-size:24px;'";
												var st2 = "style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
												$( "#TableItem tbody" ).empty();
												for (var i = 0; i < temp["Row"]; i++) {
												   var rowCount = $('#TableItem >tbody >tr').length;

												   var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:24px;' id='iUnit_"+i+"'>";

	for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
		if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode'])
			chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
		else
			chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
	}
												   chkunit += "</select>";

												   var chkDoc = "<input type='checkbox' name='checkitem' id='checkitem' value='"+i+"'><input type='hidden' id='RowID"+i+"' value='"+temp[i]['RowID']+"'>";

												   var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

												   var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;;font-family:THSarabunNew;font-size:24px;' id='iweight"+i+"' value='0' ></div>";

												   $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
																  "<td style='width: 10%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
																  "<td style='width: 10%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''nowrap>"+temp[i]['ItemCode']+"</td>"+
																  "<td style='width: 30%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''nowrap>"+temp[i]['ItemName']+"</td>"+
																  "<td style='width: 20%;'nowrap>"+chkunit+"</td>"+
																  "<td style='width: 15%;' align='center'nowrap>"+Qty+"</td>"+
																  "<td style='width: 13%;' align='center'nowrap>"+Weight+"</td>"+
																  "</tr>";
												   if(rowCount == 0){
													 $("#TableItem tbody").append( $StrTR );
												   }else{
													 $('#TableItem tbody:last-child').append( $StrTR );
												   }
                        }
                      }else if( (temp["form"]=='ShowUsageCode') ){
												var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-family:THSarabunNew;font-size:24px;'";
												var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-family:THSarabunNew;font-size:32px;'"
                        $( "#TableUsageCode tbody" ).empty();
												for (var i = 0; i < temp["Row"]; i++) {
												   var rowCount = $('#TableUsageCode >tbody >tr').length;

												   var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:32px;' id='iUnit_"+i+"'>";

	for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
		if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode'])
			chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
		else
			chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
	}
												   chkunit += "</select>";

												   var chkDoc = "<input type='checkbox' name='checkitemSub' id='checkitemSub' value='"+i+"'><input type='hidden' id='RowIDSub"+i+"' value='"+temp[i]['RowID']+"'>";

												   //var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

												   var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight"+i+"' value='0' ></div>";

												   $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
																  "<td style='width: 10%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
																  "<td style='width: 20%;'nowrap>"+temp[i]['UsageCode']+"</td>"+
																  "<td style='width: 40%;'nowrap>"+temp[i]['ItemName']+" [ "+temp[i]['RowID']+" ]</td>"+
																  "<td style='width: 15%;'nowrap>"+chkunit+"</td>"+
																  "<td style='width: 13%;' align='center'nowrap>1</td>"+
																  "</tr>";
												   if(rowCount == 0){
													 $("#TableUsageCode tbody").append( $StrTR );
												   }else{
													 $('#TableUsageCode tbody:last-child').append( $StrTR );
												   }
                        }

										  }else if(temp['form']=="get_dirty_doc"){
												var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-family:THSarabunNew;font-size:24px;'";
												var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-family:THSarabunNew;font-size:32px;'"
                        $( "#TableRefDocNo tbody" ).empty();
												for (var i = 0; i < temp["Row"]; i++) {
												   var rowCount = $('#TableRefDocNo >tbody >tr').length;
												   var chkDoc = "<input type='radio' name='checkitem' id='checkitem' value='"+temp[i]['RefDocNo']+"'><input type='hidden' id='RowId"+i+"' value='"+temp[i]['RefDocNo']+"'>";
												      $StrTR = "<tr id='tr"+temp[i]['RefDocNo']+"'>"+
																  "<td style='width: 15%;'>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
																  "<td style='width: 85%;'>"+temp[i]['RefDocNo']+"</td>"+
																  "</tr>";
												   if(rowCount == 0){
													    $("#TableRefDocNo tbody").append( $StrTR );
												   }else{
													    $('#TableRefDocNo tbody:last-child').append( $StrTR );
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
                  $( "#TableUsageCode tbody" ).empty();
                  $( "#TableItem tbody" ).empty();
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
            //===============================================
            function switch_tap1(){
              $('#tab2').attr('hidden',false);
              $('#switch_col').removeClass('col-md-12');
              $('#switch_col').addClass('col-md-10');
            }
            function switch_tap2(){
              $('#tab2').attr('hidden',true);
              $('#switch_col').removeClass('col-md-10');
              $('#switch_col').addClass('col-md-12');
            }
            //===============================================
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

      button,input[id^='qty'],input[id^='order'],input[id^='max'] {
        font-size: 24px!important;
      }

      .table th, .table td {
          border-top: none !important;
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
      /* bottom-right border-radius */
      table tr:last-child td:last-child {
        border-bottom-right-radius: 6px;
      }

      a.nav-link{
        width:auto!important;
      }
      .datepicker{z-index:9999 !important}
      .hidden{visibility: hidden;}

      .sidenav {
        height: 100%;
        overflow-x: hidden;
        /* padding-top: 20px; */
        border-left: 2px solid #bdc3c7;
      }

      .sidenav a {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
      }

      .sidenav a:hover {
        color: #2c3e50;
        font-weight:bold;
        font-size:26px;
      }
      .icon{
        padding-top: 6px;
        padding-left: 44px;
      }
      @media (min-width: 992px) and (max-width: 1199.98px) { 

        .icon{
          padding-top: 6px;
          padding-left: 23px;
        }
        .sidenav a {
          font-size: 21px;

        }
       }

    </style>
  </head>

  <body id="page-top">
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['xfactory']['title'][$language]; ?></a></li>
        <li class="breadcrumb-item active"><?php echo $array2['menu']['xfactory']['sub'][0][$language]; ?></li>
      </ol>
    <div id="wrapper">
      <div id="content-wrapper">
        <div class="row"> <!-- start row tab -->
          <div class="col-md-10" style='padding-left: 26px;' id='switch_col'> <!-- tag column 1 -->
                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="home-tab" onclick="switch_tap1()" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titlefactoryin'][$language]; ?></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab" onclick="switch_tap2()" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                        </li>
                      </ul>

                      <div class="tab-content" id="myTabContent">
                            <div class="tab-pane  show active" id="home" role="tabpanel" aria-labelledby="home-tab">
<!-- /.content-wrapper -->
<div class="row">
              <div class="col-md-11"> <!-- tag column 1 -->
                  <div class="container-fluid">
                    <div class="card-body" style="padding:0px; margin-top:10px;">

                       <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                      <select  class="form-control col-sm-8" id="hotpital" onchange="getDepartment();" disabled="true">
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['department'][$language]; ?></label>
                                        <select class="form-control col-sm-8" id="department" disabled="true">
                                        </select>
                                    </div>
                                  </div>
                                </div>
                                <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['docdate'][$language]; ?></label>
                                      <input type="text" class="form-control col-sm-8"  name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['docno'][$language]; ?></label>
                                      <input type="text" class="form-control col-sm-8" name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >
                                    </div>
                                  </div>
                                </div>
                                <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['refdocno'][$language]; ?></label>
                                      <input class="form-control col-sm-8" id='RefDocNo' placeholder="<?php echo $array['refdocno'][$language]; ?>" onclick="open_dirty_doc()">
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['employee'][$language]; ?></label>
                                      <input type="text" class="form-control col-sm-8" style="font-size:24px;width:220px;" name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>" >
                                    </div>
                                  </div>
                                </div>
                                <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['time'][$language]; ?></label>
                                      <input type="text" class="form-control col-sm-8" class="form-control" style="font-size:24px;width:220px;" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['totalweight'][$language]; ?></label>
                                      <input class="form-control col-sm-8" style="font-size:20px;width:220px;height:40px;padding-top:6px;" id='wTotal' placeholder="0.00">
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </div>
                       </div> <!-- tag column 1 -->
                     </div>

<div class="row">
              <div class="col-md-12"> <!-- tag column 1 -->
              		<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="100%" cellspacing="0" role="grid" style="">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 10%;'nowrap><?php echo $array['no'][$language]; ?></th>
                              <th style='width: 21%;'nowrap><?php echo $array['code'][$language]; ?></th>
                              <th style='width: 24%;'nowrap><?php echo $array['item'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['unit'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['qty'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['weight'][$language]; ?></th>
                            </tr>
                          </thead>
                          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                          </tbody>
					            </table>
                    </div> <!-- tag column 1 -->
                  </div>
                </div>             
                      <!-- search document -->
                      <div class="tab-pane " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row" style="margin-top:10px;">
                        	<div class="col-md-4">
                            <div class="row" style="font-size:24px;margin-left:2px;">
                        			<select class="form-control" style='font-size:24px;' id="Dep2">
                              </select>
                        				</div>
                              </div>
                                <div class="col-md-6">
                                  <div class="row" style="margin-left:2px;">
                                    <input type="text" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                                    <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(0);"><?php echo $array['search'][$language]; ?></button>
                                    <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(1);"><?php echo $array['searchalldep'][$language]; ?></button>
                                  </div>
                                </div>
                                  <div class="col-md-2 text-right">
                                    <button type="button"  class="btn btn-warning" name="button" onclick="SelectDocument();"><?php echo $array['show'][$language]; ?></button>
                                </div>
                              </div>

 			<div class="row">
              <div class="col-md-12"> <!-- tag column 1 -->
              		<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 10%;'nowrap>&nbsp;</th>
                              <th style='width: 15%;'nowrap><?php echo $array['docdate'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['docno'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['refdocno'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['employee'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['time'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['weight'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['status'][$language]; ?></th>
                            </tr>
                          </thead>
                          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                          </tbody>
					              </table>
                      </div> <!-- tag column 1 -->
			             </div>
                 </div> <!-- end row tab -->
               </div>
            </div>
            <div class="col-md-2" id='tab2' <?php if($PmID == 1) echo 'hidden'; ?>>
                <!-- button----------------------------------------- -->
                <div class="sidenav">
                    <div class="" style="margin-top:5px;">
                      <div class="card-body" style="padding:0px; margin-top:10px;">
                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_create.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a  href='javascript:void(0)' onclick="CreateDocument()" id="bCreate">
                              <?php echo $array['createdocno'][$language]; ?>
                            </a>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_import.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="OpenDialogItem()" id="bImport">
                              <?php echo $array['import'][$language]; ?>
                            </a>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_delete.png" style='width:40px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="DeleteItem()" id="bDelete">
                              <?php echo $array['delitem'][$language]; ?>
                            </a>
                          </div>
                        </div>

                        <div class="row " style="margin-top:0px;">
                          <div class="col-md-3 icon">
                            <img src="../img/icon/ic_save.png" style='width:36px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="SaveBill()" id="bSave">
                              <?php echo $array['save'][$language]; ?>
                            </a>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon">
                            <img src="../img/icon/ic_cancel.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="CancelBill()" id="bCancel">
                              <?php echo $array['cancel'][$language]; ?>
                            </a>
                          </div>
                        </div>
              
                        <div class="row" style="margin-top:0px;" hidden>
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_detail.png" style='width:40px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="ShowDetailSub()" id="bShowDetailSub">
                              <?php echo $array['detail'][$language]; ?>
                            </a>
                          </div>
                        </div>
          
                        <div class="row" style="margin-top:0px;" hidden>
                          <div class="col-md-3 icon">
                            <img src="../img/icon/ic_print.png" style='width:40px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="PrintData()" id="bPrint">
                              <?php echo $array['print'][$language]; ?>
                            </a>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;" hidden>
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_detail.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <a href='javascript:void(0)' onclick="SendData()" id="bSend">
                              <?php echo $array['sendlinen'][$language]; ?>
                            </a>
                          </div>
                        </div>
                   
                      </div>
                    </div>
                </div>
                <!-- end button----------------------------------------- -->
              </div>
            </div>
          </div>
        </div>

<!-- /#wrapper -->
<!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

<!-- /#wrapper -->
<!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Dialog Modal
    <div id="dialogItemCode" title="<?php echo $array['import'][$language]; ?>"  style="z-index:999998 !important;font-family: 'THSarabunNew';font-size:24px;">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <div class="row">
			        <label><?php echo $array['searchplace'][$language]; ?></label>
              <div class="row" style="font-size:16px;margin-left:20px;width:350px;">
                  <input type="text" class="form-control" style="font-size:24px;width:100%;font-family: 'THSarabunNew'" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
              </div>
              <button type="button" style="font-size:18px;margin-left:30px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="ShowItem();"><?php echo $array['search'][$language]; ?></button>
            </div>
          </div>
          <div class="col-md-1">
            <button type="button" style="font-size:18px;margin-left:70px; width:100px;font-family: 'THSarabunNew'" class="btn btn-warning" name="button" onclick="getImport(1);"><?php echo $array['import'][$language]; ?></button>
          </div>
        </div>

        <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>

        <div class="row">
            <div class="card-body" style="padding:0px;">
                <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                  <thead style="font-size:24px;">
                    <tr role="row">
                      <th style='width: 10%;'><?php echo $array['no'][$language]; ?></th>
                      <th style='width: 10%;'><?php echo $array['code'][$language]; ?></th>
                      <th style='width: 30%;'><?php echo $array['item'][$language]; ?></th>
                      <th style='width: 20%;'><?php echo $array['unit'][$language]; ?></th>
                      <th style='width: 15%;'><?php echo $array['numofpiece'][$language]; ?></th>
                      <th style='width: 15%;'><?php echo $array['weight'][$language]; ?></th>
                    </tr>
                  </thead>
                  <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                  </tbody>
                </table>
            </div>
        </div>
      </div>
    </div> -->

    <!-- Dialog Modal-->
    <div id="dialogUsageCode" title="<?php echo $array['import'][$language]; ?>"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
<!--
            <div class="row">
			        <label><?php echo $array['searchplace'][$language]; ?></label>
              <div class="row" style="font-size:16px;margin-left:20px;width:350px;">
                  <input type="text" class="form-control" style="font-size:24px;width:100%;font-family: 'THSarabunNew'" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
              </div>
              <button type="button" style="font-size:18px;margin-left:30px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="ShowUsageCode();"><?php echo $array['search'][$language]; ?></button>
            </div>
  -->
          </div>
          <div class="col-md-1">
            <button type="button" style="font-size:18px;margin-left:70px; width:100px;font-family: 'THSarabunNew'" class="btn btn-warning" name="button" onclick="getImport(2);"><?php echo $array['import'][$language]; ?></button>
          </div>
        </div>

        <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>

        <div class="row">
            <div class="card-body" style="padding:0px;">
                <table class="table table-fixed table-condensed table-striped" id="TableUsageCode" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                  <thead style="font-size:24px;">
                    <tr role="row">
                      <th style='width: 10%;'nowrap><?php echo $array['no'][$language]; ?></th>
                      <th style='width: 20%;'nowrap><?php echo $array['rfid'][$language]; ?></th>
                      <th style='width: 40%;'nowrap><?php echo $array['item'][$language]; ?></th>
                      <th style='width: 15%;'nowrap><?php echo $array['unit'][$language]; ?></th>
                      <th style='width: 15%;'nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                    </tr>
                  </thead>
                  <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                  </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>

    <!-- Dialog Modal RefDocNo
    <div id="dialogRefDocNo" title="<?php echo $array['refdocno'][$language]; ?>"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
      <div class="container">
        <div class="row">
          <div class="col-md-10">

            <div class="row">
			        <label><?php echo $array['searchplace'][$language]; ?></label>
              <div class="row" style="font-size:16px;margin-left:20px;width:350px;">
                  <input type="text" class="form-control" style="font-size:24px;width:100%;font-family: 'THSarabunNew'" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
              </div>
              <button type="button" style="font-size:18px;margin-left:30px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="get_dirty_doc();"><?php echo $array['search'][$language]; ?></button>
            </div>

          </div>
          <div class="col-md-1">
            <button type="button" style="font-size:18px;margin-left:70px; width:100px;font-family: 'THSarabunNew'" class="btn btn-warning" name="button" onclick="UpdateRefDocNo()"><?php echo $array['import'][$language]; ?></button>
          </div>
        </div>

        <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>

        <div class="row">
            <div class="card-body" style="padding:0px;">
                <table class="table table-fixed table-condensed table-striped" id="TableRefDocNo" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                  <thead style="font-size:24px;">
                    <tr role="row">
                      <th style='width: 15%;'><?php echo $array['no'][$language]; ?></th>
                      <th style='width: 85%;'><?php echo $array['refdocno'][$language]; ?></th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                  </tbody>
                </table>
            </div>
        </div>
      </div>
    </div> -->

        <!-- -----------------------------Custome1------------------------------------ -->
        <div class="modal" id="dialogItemCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="col-md-8">
                                  <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label text-right pr-5"><?php echo $array['searchplace'][$language]; ?></label>
                                    <input type="text" class="form-control col-sm-9" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                                  </div>
                                </div>
                                <div class="col-md-2">
                                <!-- ssssssssssssssssssssss -->
                                  <button type="button" class="btn btn-primary btn-block" name="button" onclick="ShowItem();"><?php echo $array['search'][$language]; ?></button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-warning  btn-block" name="button" onclick="getImport(1);"><?php echo $array['import'][$language]; ?></button>
                                </div>
                              </div>
                              <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                                <thead style="font-size:24px;">
                                  <tr role="row">
                                    <th style='width: 10%;' nowrap><?php echo $array['no'][$language]; ?></th>
                                    <th style='width: 20%;' nowrap><?php echo $array['code'][$language]; ?></th>
                                    <th style='width: 25%;' nowrap><?php echo $array['item'][$language]; ?></th>
                                    <th style='width: 15%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                                    <th style='width: 15%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                                    <th style='width: 15%;' nowrap><?php echo $array['weight'][$language]; ?></th>
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

                    <!-- custom modal2 -->
          <div class="modal" id="dialogRefDocNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <?php echo $array['refdocno'][$language]; ?>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="card-body" style="padding:0px;">
                    <div class="row">
                      <div class="col-md-8">
                        <div class='form-group row'>
                          <label class="col-sm-4 col-form-label text-right pr-5"><?php echo $array['searchplace'][$language]; ?></label>
                          <input type="text" class="form-control col-sm-9" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-primary  btn-block" name="button" onclick="get_dirty_doc();"><?php echo $array['search'][$language]; ?></button>
                      </div>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-warning btn-block" name="button" onclick="UpdateRefDocNo()"><?php echo $array['import'][$language]; ?></button>
                      </div>
                    </div>
                    <table class="table table-fixed table-condensed table-striped" id="TableRefDocNo" cellspacing="0" role="grid">
                      <thead style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 15%;' nowrap><?php echo $array['no'][$language]; ?></th>
                          <th style='width: 85%;' nowrap><?php echo $array['refdocno'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                      </tbody>
                    </table>
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

  </body>

</html>
