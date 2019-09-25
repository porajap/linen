<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
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

  <title><?php echo $array['titledamage'][$language]; ?></title>

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
  <!-- custome style -->
  <link href="../css/responsive.css" rel="stylesheet">
  <!-- ---------------------------------------------- -->
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>
<link href="../css/menu_custom.css" rel="stylesheet">
<link href="../dist/css/sweetalert2.css" rel="stylesheet">
<script src="../dist/js/sweetalert2.min.js"></script>
<script src="../dist/js/jquery-3.3.1.min.js"></script>


<link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="../datepicker/dist/js/datepicker.min.js"></script>
<!-- Include English language -->
<script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

<script type="text/javascript">
var summary = [];
var xItemcode;
var RowCnt=0;

$(document).ready(function(e){
  $('.only').on('input', function() {
        this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
      });
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
  });

  function OpenDialogItem(){
        var docno = $("#docno").val();
        var RefDocNo = $("#RefDocNo").val();

        // if( docno != "" ) dialogItemCode.dialog( "open" );
        if(RefDocNo==""){
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
          });
          $('#rem1').attr('hidden', false);
          $('#RefDocNo').addClass('border border-danger');
        }else{
        if(docno != ""){
          $('#dialogItemCode').modal('show');
          $('#rem1').attr('hidden', true);
          $('#RefDocNo').removeClass('border border-danger');
        }
        }
        ShowItem();
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

      function resetradio(row){

      var previousValue = $('.checkrow_'+row).attr('previousValue');
        var name = $('.checkrow_'+row).attr('name');
        if (previousValue == 'checked') {
          $('#bDelete').attr('disabled', true);
          $('#bDelete2').addClass('opacity');
          $('#hover3').removeClass('mhee');
          $('.checkrow_'+row).removeAttr('checked');
          $('.checkrow_'+row).attr('previousValue', false);
          $('.checkrow_'+row).prop('checked', false);
          // Blankinput();
        } else {
          $('#bDelete').attr('disabled', false);
          $('#hover3').addClass('mhee');
          $('#bDelete2').removeClass('opacity');
          $("input[name="+name+"]:radio").attr('previousValue', false);
          $('.checkrow_'+row).attr('previousValue', 'checked');
        }
      }

      function DeleteItem(){
        var docno = $("#docno").val();
        var xrow = $("#checkrow:checked").val() ;
        xrow = xrow.split(",");
        swal({
          title: "<?php echo $array['confirmdelete'][$language]; ?>",
          text: "<?php echo $array['confirm1'][$language]; ?>"+xrow[1]+"<?php echo $array['confirm2'][$language]; ?>",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
          cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
            if (result.value) {
            var data = {
              'STATUS'    : 'DeleteItem',
              'rowid'  : xrow[0],
              'DocNo'   : docno
            };
            senddata(JSON.stringify(data));
          } else if (result.dismiss === 'cancel') {
          swal.close();}
          })
        } 

      function CancelDocument(){
        var docno = $("#docno").val();

        if(docno!= ""){
        swal({
          title: "<?php echo $array['confirmcancel'][$language]; ?>",
          text: "<?php echo $array['canceldata4'][$language];?> "+docno+" ?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
          cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
            if (result.value) {
            CancelBill();

            } else if (result.dismiss === 'cancel') {
            swal.close();}
          })
        }
      }

      //======= On create =======
      //console.log(JSON.stringify(data));
      function OnLoadPage(){
        var lang = '<?php echo $language; ?>';
        var data = {
          'STATUS'  : 'OnLoadPage',
          'lang'	: lang 
        };
        senddata(JSON.stringify(data));
        $('#isStatus').val(0)
      }

      function open_claim_doc(){
        // dialogRefDocNo.dialog( "open" );
        $('#dialogRefDocNo').modal('show');
        // get_claim_doc();
      }

      function get_claim_doc(){
        var hptcode = '<?php echo $HptCode ?>';
        var docno = $("#docno").val();
        var data = {
          'STATUS' : 'get_claim_doc',
          'DocNo'  : docno,
          'hptcode'  : hptcode
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
    function dis2(row){
      if($('#checkrow_'+row).prop("checked") == true){
          var countcheck2 = Number($("#countcheck").val())+1;
          $("#countcheck").val(countcheck2);
          $('#bSaveadd').attr('disabled', false);
          $('#bSaveadd2').removeClass('opacity');
          // $('#checkrow_'+row).prop('checked', true);
          $('#checkrow_'+row).attr('previousValue', 'checked');
        }else if($('#checkrow_'+row).prop("checked") == false){
          var countcheck3 = Number($("#countcheck").val())-1;
          $("#countcheck").val(countcheck3);
          if(countcheck3 == 0 ){
          $('#bSaveadd').attr('disabled', true);
          $('#bSaveadd2').addClass('opacity');
          $('.checkrow_'+row).removeAttr('checked');
          // $('#checkrow_'+row).prop('checked', false);
          $("#countcheck").val(countcheck3);
          }
        }
 
    }
      function ShowDocument(selecta){
        var Hotp = $('#hotpital option:selected').attr("value");
        var searchdocument = $('#searchdocument').val();
        if( typeof searchdocument == 'undefined' ) searchdocument = "";
        var deptCode = $('#Dep2 option:selected').attr("value");
        if( typeof deptCode == 'undefined' ) deptCode = "1";
        var data = {
          'STATUS'  	: 'ShowDocument',
          'xdocno'	: searchdocument,
          'selecta' : selecta,
          'deptCode'	: deptCode,
          'Hotp'	: Hotp
        };
        senddata(JSON.stringify(data));
      }

      function ShowItem(){
        var deptCode = $('#department option:selected').attr("value");
        if( typeof deptCode == 'undefined' ) deptCode = "1";
        var searchitem = $('#searchitem').val();
        var data = {
          'STATUS'  : 'ShowItem',
          'xitem'	: searchitem ,
          'deptCode'	: deptCode
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
        ShowDocument();
      }

      function getImport(Sel) {
        var docno2 = $("#RefDocNo").val();
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
          $(".checkitem:checked").each(function() {
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

        var deptCode = $('#department option:selected').attr("value");

        // alert("xrow : "+xrow);

        $('#TableDetail tbody').empty();
        var data = {
          'STATUS'  		: 'getImport',
          'xrow'		  	: xrow,
          'xqty'		  	: xqty,
          'xweight'	  	: xweight,
          'xunit'		  	: xunit,
          'DocNo'   		: docno,
          'Sel'       	: Sel,
          'deptCode'    	: deptCode,
          'RefDocNo'    	: docno2
        };
        senddata(JSON.stringify(data));
        ShowItem();
        // dialogItemCode.dialog( "close" );
        // $('#dialogItemCode').modal('toggle');

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

      function dis(){
              $('.dis').attr('disabled', false);
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
          title: "<?php echo $array['confirmdoc'][$language]; ?>",
          text: "<?php echo $array['side'][$language]; ?> : " +$('#hotpital option:selected').text()+ " <?php echo $array['department'][$language]; ?> : " +$('#department option:selected').text(),
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
          cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
            if (result.value) {
            var data = {
              'STATUS'    : 'CreateDocument',
              'hotpCode'  : hotpCode,
              'deptCode'  : deptCode,
              'userid'	: userid
            };
            senddata(JSON.stringify(data));
            $('#RefDocNo').attr('disabled', false);
          } else if (result.dismiss === 'cancel') {
            swal.close();
          } 
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
        var dept = $("#Dep2").val();
        var ItemCodeArray = [];
        var Item = [];
        var QtyArray = [];
        var Qty = [];
        $('input[name="item_array"]').each(function() {
          if($(this).val()!=""){
              ItemCodeArray.push($(this).val());
          }
            });
        var ItemCode = ItemCodeArray.join(',') ;
        // alert(ItemCode); 

        $('input[name="qtyx"]').each(function() {
                if($(this).val()!=""){
                  QtyArray.push($(this).val());
                }
              });
        var Qty = QtyArray.join(',') ;
        // alert(Qty);
        if(isStatus==1)
        isStatus=0;
        else
        isStatus=1;
        if(isStatus==1){
          if(docno!=""){
        swal({
            title: "<?php echo $array['confirmsave'][$language]; ?>",
            text: "<?php echo $array['docno'][$language]; ?>: "+docno+"",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
            cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true}).then(result => {
              if (result.value) {
          var data = {
            'STATUS'      : 'SaveBill',
            'xdocno'      : docno,
            'xdocno2'     : docno2,
            'isStatus'    : isStatus,
            'deptCode'    : dept,
            'ItemCode'    : ItemCode,
            'Qty'         : Qty
          };
          senddata(JSON.stringify(data));
          $('#profile-tab').tab('show');
          $("#bImport").prop('disabled', true);
          $("#bDelete").prop('disabled', true);
          $("#bSave").prop('disabled', true);
          $("#bCancel").prop('disabled', true);
          ShowDocument();
        } else if (result.dismiss === 'cancel') {
          swal.close();}
        })
        }
        }else{
          $("#bImport2").removeClass('opacity');
          $("#bSave2").removeClass('opacity');
          $("#bCancel2").removeClass('opacity');
          $("#bImport").prop('disabled', false);
          $("#bSave").prop('disabled', false);
          $("#bCancel").prop('disabled', false);
          var word = '<?php echo $array['save'][$language]; ?>';
          var changeBtn = "<i class='fa fa-save'></i>";
            changeBtn += "<div>"+word+"</div>";
            $('#icon_edit').html(changeBtn);            
            $("#IsStatus").val("0");
          $("#docno").prop('disabled', false);
          $("#docdate").prop('disabled', false);
          $("#recorder").prop('disabled', false);
          $("#timerec").prop('disabled', false);
          $("#total").prop('disabled', false);
          var rowCount = $('#TableItemDetail >tbody >tr').length;
          for (var i = 0; i < rowCount; i++) {

            $('#qty1_'+i).prop('disabled', false);
            $('#weight_'+i).prop('disabled', false);
            $('#price_'+i).prop('disabled', false);

            $('#unit'+i).prop('disabled', false);
          }
        }
      }
      function show_btn(DocNo){
              if(DocNo != undefined || DocNo != ''){
                  $(btn_show).attr('disabled',false);
              }
            }
      function UpdateRefDocNo(){
        var hptcode = '<?php echo $HptCode ?>';
        var docno = $("#docno").val();
        var RefDocNo;
        //get value from radio button
        $("#checkitemDirty:checked").each(function() {
          RefDocNo = $(this).val();
        });

        var deptCode = $('#Dep2 option:selected').attr("value");
        var data = {
          'STATUS'      : 'UpdateRefDocNo',
          'xdocno'      : docno,
          'RefDocNo'    : RefDocNo,
          'selecta'     : 0,
          'deptCode'	  : deptCode,
          'hptcode'   : hptcode

        };
        senddata(JSON.stringify(data));
        // dialogRefDocNo.dialog( "close" );
        $('#dialogRefDocNo').modal('toggle')
      }

      // function UpdateQty(row,rowid) {
      //   var docno = $("#docno").val();
      //   var Qty = $("#qty1_"+row).val();
      //     var data = {
      //       'STATUS'      : 'UpdateQty',
      //       'Rowid'       : rowid,
      //       'Qty'      : Qty,
      //       'DocNo'      : docno

      //     };
      //     senddata(JSON.stringify(data));
      // }

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
        var URL = '../process/damage.php';
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
                var Str2 = "<option value=''>-</option>";
                for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                  var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                  Str2 += "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                  $("#department").append(Str);
                  $("#Dep2").append(Str);
                }
                if(PmID != 1){
                  $("#Dep2").val(temp[0]['DepCode']);
                }
              }else if( (temp["form"]=='CreateDocument') ){
                swal({
                  title: "<?php echo $array['createdocno'][$language]; ?>",
                  text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                  type: "success",
                  showCancelButton: false,
                  timer: 1000,
                  confirmButtonText: 'Ok',
                  showConfirmButton: false
                  });
                setTimeout(function () {
                  parent.OnLoadPage();
                }, 1000);
                $( "#TableItemDetail tbody" ).empty();
                $("#wTotal").val(0);
                // $("#bSave").text('<?php echo $array['save'][$language]; ?>');
                $("#docno").val(temp[0]['DocNo']);
                $("#docdate").val(temp[0]['DocDate']);
                $("#recorder").val(temp[0]['Record']);
                $("#timerec").val(temp[0]['RecNow']);
                $("#RefDocNo").val("");
                $('#bCancel').attr('disabled', false);
                $('#bSave').attr('disabled', false);
                $('#bImport').attr('disabled', false);

                $('#hover2').addClass('mhee');
                $('#hover4').addClass('mhee');
                $('#hover5').addClass('mhee');

                $('#bSave2').removeClass('opacity');
                $('#bImport2').removeClass('opacity');
                $('#bCancel2').removeClass('opacity');
              }else if(temp["form"]=='ShowDocument'){

                setTimeout(function () {
                  parent.OnLoadPage();
                }, 500);

                $( "#TableDocument tbody" ).empty();
                $( "#TableItemDetail tbody" ).empty();
                for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                  var rowCount = $('#TableDocument >tbody >tr').length;
                  var chkDoc = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno'onclick='show_btn(\""+temp[i]['DocNo']+"\");' value='"+temp[i]['DocNo']+"' ><span class='checkmark'></span></label>";
                  var Status = "";
                  var Style  = "";
                  if(temp[i]['IsStatus']==1){
                    Status = "<?php echo $array['savesuccess'][$language]; ?>";
                    Style  = "style='width: 10%;color: #20B80E;'";
                  }else{
                    Status = "<?php echo $array['draft'][$language]; ?>";
                    Style  = "style='width: 10%;color: #3399ff;'";
                  }if(temp[i]['IsStatus']==9){
                    Status = "<?php echo $array['Canceldoc'][$language]; ?>";
                    Style  = "style='width: 10%;color: #ff0000;'";
                  }

                  $StrTr="<tr id='tr"+temp[i]['DocNo']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                    "<td style='width: 10%;' nowrap>"+chkDoc+"</td>"+
                    "<td style='width: 15%;' nowrap>"+temp[i]['DocDate']+"</td>"+
                    "<td style='width: 15%;' nowrap>"+temp[i]['DocNo']+"</td>"+
                    "<td style='width: 15%;' nowrap>"+temp[i]['RefDocNo']+"</td>"+
                    "<td style='width: 19%;' nowrap>"+temp[i]['Record']+"</td>"+
                    "<td style='width: 14%;' nowrap>"+temp[i]['RecNow']+"</td>"+
                    "<td " +Style+ "nowrap>"+Status+"</td>"+ 
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
                  var word = '<?php echo $array['save'][$language]; ?>';
                  var changeBtn = "<i class='fa fa-save'></i>";
                  changeBtn += "<div>"+word+"</div>";
                  $('#icon_edit').html(changeBtn);                  
                  $("#bImport").prop('disabled', false);
                  $("#bSave").prop('disabled', false);
                  $("#bCancel").prop('disabled', false);

                  $("#hover2").addClass('mhee');
                  $("#hover4").addClass('mhee');
                  $("#hover5").addClass('mhee');

                  $("#bImport2").removeClass('opacity');
                  $("#bSave2").removeClass('opacity');
                  $("#bCancel2").removeClass('opacity');
                }else if(temp[0]['IsStatus']==1){
                  var word = '<?php echo $array['edit'][$language]; ?>';
                  var changeBtn = "<i class='fas fa-edit'></i>";
                  changeBtn += "<div>"+word+"</div>";
                  $('#icon_edit').html(changeBtn);                  
                  $("#bImport").prop('disabled', true);
                  $("#bDelete").prop('disabled', true);
                  $("#bSave").prop('disabled', false);
                  $("#bCancel").prop('disabled', true);
                  $("#bSave2").removeClass('opacity');
                  $("#hover4").addClass('mhee');
                }else{
                  $("#bImport").prop('disabled', true);
                  $("#bDelete").prop('disabled', true);
                  $("#bSave").prop('disabled', false);
                  $("#bCancel").prop('disabled', true);
                  $("#bImport2").addClass('opacity');
                  $("#bDelete2").addClass('opacity');
                  $("#bSave2").addClass('opacity');
                  $("#bCancel2").addClass('opacity');

                  $("#hover2").removeClass('mhee');
                  $("#hover3").removeClass('mhee');
                  $("#hover4").removeClass('mhee');
                  $("#hover5").removeClass('mhee');

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
                $('#RefDocNo').attr('disabled', false);
                ShowDetail();
              }else if(temp["form"]=='getImport'  || temp["form"]=='ShowDetail'){
                $( "#TableItemDetail tbody" ).empty();
                if(temp["Row"] > 0)
                $("#wTotal").val(temp[0]['Total']);
                else
                $("#wTotal").val(0);
                var st1 = "style='font-size:24px;margin-left:3px;width:153px;'";
                var isStatus = $("#IsStatus").val();
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableItem >tbody >tr').length;

                  var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control ' style='font-size:24px;' id='Unit_"+i+"'>";
                  var nUnit = temp[i]['UnitName'];
                  for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                    if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode']){
                      chkunit += "<option selected value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                    }else{
                      chkunit += "<option value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                    }
                  }
                  chkunit += "</select>";
                  var chkDocx = "<input  name = 'item_array'  value='"+temp[i]['ItemCode']+"'>";
                  // var Qtyx = "<input  class='Qty_array'   value='"+temp[i]['Qty']+"'>";

                  var chkDoc = "<div class='form-inline'><label class='radio'style='margin:0px!important;'><input type='radio' name='checkrow' id='checkrow' class='checkrow_"+i+"' value='"+temp[i]['RowID']+","+temp[i]['ItemName']+"'  onclick='resetradio(\""+i+"\")'><span class='checkmark'></span><label style='margin-left:27px;'> "+(i+1)+"</label></label></div>";

                  var Qty = "<div class='row' style='margin-left:0px;'><input class='form-control numonly' name='qtyx' style=' width:87px;height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='qty1_"+i+"' value='"+temp[i]['Qty']+"' onkeyup='if(this.value >"+temp[i]['QtySum']+"){this.value = "+temp[i]['QtySum']+"}else if(this.value < 0){this.value = 1}' ></div>";

                  var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' style=' width:87px;height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='weight_"+i+"' value='"+temp[i]['Weight']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

                  var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_"+i+"' value='"+temp[i]['Price']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

                  $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                  "<td style='width: 9%;' nowrap>"+chkDoc+"</td>"+
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 18%;' nowrap>"+temp[i]['ItemCode']+"</td>"+
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 29%;' nowrap>"+temp[i]['ItemName']+"</td>"+
                  "<td style='width: 29%;font-size:24px;' nowrap>"+chkunit+"</td>"+
                  "<td style='width: 12%;' nowrap>"+Qty+"</td>"+
                  "<td style='width: 12%;' nowrap hidden>"+chkDocx+"</td>"+
                  "</tr>";


                  if(rowCount == 0){
                    $('#bSaveadd').attr('disabled', true);
                    $('#bSaveadd2').addClass('opacity');
                    $("#countcheck").val("0");
                    $("#TableItemDetail tbody").append( $StrTR );
                  }else{
                    $('#bSaveadd').attr('disabled', true);
                    $('#bSaveadd2').addClass('opacity');
                    $("#countcheck").val("0");
                    $('#TableItemDetail tbody:last-child').append( $StrTR );
                  }
                  if(isStatus==0){
                    // $("#docno").prop('disabled', false);
                    // $("#docdate").prop('disabled', false);
                    // $("#recorder").prop('disabled', false);
                    // $("#timerec").prop('disabled', false);
                    // $("#total").prop('disabled', false);

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
                $('.numonly').on('input', function() {
                        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                        });
              }else if( (temp["form"]=='ShowItem') ){
                var st1 = "style='font-size:24px;margin-left:-10px; width:150px;font-family:THSarabunNew;font-size:24px;'";
                var st2 = "style='font-size: 20px;height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
                $( "#TableItem tbody" ).empty();
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableItem >tbody >tr').length;

                  var chkunit ="<div class='row' style='margin:auto;'><select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' id='iUnit_"+i+"'></div>";

                  for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                    if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode'])
                    chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                    else
                    chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                  }
                  chkunit += "</select>";

                  var chkDoc = "<input type='checkbox' id='checkrow_"+i+"'  name='checkitem' onclick='dis2(\""+i+"\")' class='checkitem' value='"+i+"'><input type='hidden' id='RowID"+i+"' value='"+temp[i]['ItemCode']+"'>";

                  var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger numonly' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control numonly ' "+st2+" id='iqty"+i+"' value='0' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                  var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' style='font-size:20px;height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;;font-family:THSarabunNew;font-size:24px;' id='iweight"+i+"' value='0' ></div>";

                  $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                  "<td style='width: 27%;' nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                  // "<td style='width: 20%;cursor: pointer;' nowrap onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''>"+temp[i]['ItemCode']+"</td>"+
                  "<td style='width: 32%;cursor: pointer;' nowrap onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''>"+temp[i]['ItemName']+"</td>"+
                  "<td style='width: 23%;' nowrap>"+chkunit+"</td>"+
                  "<td style='width: 18%;' nowrap align='center'>"+Qty+"</td>"+
                  "</tr>";
                  if(rowCount == 0){
                    $("#TableItem tbody").append( $StrTR );
                  }else{
                    $('#TableItem tbody:last-child').append( $StrTR );
                  }
                }
                $('.numonly').on('input', function() {
                  this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                  });
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

                  $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                  "<td style='width: 10%;'>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                  "<td style='width: 20%;'>"+temp[i]['UsageCode']+"</td>"+
                  "<td style='width: 40%;'>"+temp[i]['ItemName']+" [ "+temp[i]['RowID']+" ]</td>"+
                  "<td style='width: 15%;'>"+chkunit+"</td>"+
                  "<td style='width: 13%;' align='center'>1</td>"+
                  "</tr>";
                  if(rowCount == 0){
                    $("#TableUsageCode tbody").append( $StrTR );
                  }else{
                    $('#TableUsageCode tbody:last-child').append( $StrTR );
                  }
                }

              }else if(temp['form']=="get_claim_doc"){
                var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-family:THSarabunNew;font-size:24px;'";
                var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-family:THSarabunNew;font-size:32px;'"
                var checkitem = $("#checkitem").val();
                $( "#TableRefDocNo tbody" ).empty();
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableRefDocNo >tbody >tr').length;
                  var chkDoc = "<input type='radio' name='checkitem' id='checkitemDirty' value='"+temp[i]['RefDocNo']+"'><input type='hidden' id='RowId"+i+"' value='"+temp[i]['RefDocNo']+"'>";
                  $StrTR = "<tr id='tr"+temp[i]['RefDocNo']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
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
     /* ======================================== */
      a.nav-link{
        width:auto!important;
      }
      .datepicker{z-index:9999 !important}
      .hidden{visibility: hidden;}

      button,input[id^='qty'],input[id^='order'],input[id^='max'] {
        font-size: 24px!important;
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
      .table th, .table td {
          border-top: none !important;
      }

      .table > thead > tr >th {
        background-color: #1659a2;
      }

      .sidenav {
        height: 100%;
        overflow-x: hidden;
        /* padding-top: 20px; */
        border-left: 2px solid #bdc3c7;
      }
      .mhee a{
        /* padding: 6px 8px 6px 16px; */
        text-decoration: none;
        font-size: 25px;
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
            color: #2c3e50;
            display: block;
            background: none;
            box-shadow: none !important;
            
          }
          .mhee button:hover {
            color: #2c3e50;
            font-weight:bold;
            font-size:26px;
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
      .only1:disabled, .form-control[readonly] {
    background-color: transparent !important;
    opacity: 1;
    }      
    .opacity{
        opacity:0.5;
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
      /* ======================================== */
    </style>

  </head>

  <body id="page-top">

    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
      <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][11][$language]; ?></li>
    </ol>
    <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

    <div id="wrapper">
          <div id="content-wrapper">            
            <div class="row">
              <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab"  data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titledamage'][$language]; ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab"  data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                  </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <!-- /.content-wrapper -->
                    <div class="row">
                    <div class="col-md-11">
                            <!-- tag column 1 -->
                            <div class="container-fluid">
                              <div class="card-body mt-3">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "  style="font-size:24px;"  ><?php echo $array['side'][$language]; ?></label>
                                      <select  class="form-control col-sm-7 icon_select"  style="font-size:22px;"  id="hotpital" onchange="getDepartment();" disabled="true">
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "  style="font-size:24px;" ><?php echo $array['department'][$language]; ?></label>
                                        <select class="form-control col-sm-7 icon_select" style="font-size:22px;" id="department" disabled="true">
                                        </select>
                                    </div>
                                  </div>
                                </div>
                                <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "  style="font-size:24px;" ><?php echo $array['docdate'][$language]; ?></label>
                                      <input type="text" autocomplete="off"  style="font-size:22px;" disabled="true"  class="form-control col-sm-7 only1"  name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "  style="font-size:24px;" ><?php echo $array['docno'][$language]; ?></label>
                                      <input type="text" autocomplete="off" style="font-size:22px;" disabled="true" class="form-control col-sm-7 only1" name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >
                                    </div>
                                  </div>
                                </div>
                                <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label " style="font-size:24px;" ><?php echo $array['refdocno'][$language]; ?></label>
                                      <input class="form-control col-sm-7 only" style="font-size:22px;" disabled="true" autocomplete="off" id='RefDocNo' placeholder="<?php echo $array['refdocno'][$language]; ?>" onclick="open_claim_doc()">
                                      <label id="rem1" hidden class="col-sm-1 " style="font-size: 180%; margin-top: -1%; color: red;"> * </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "  style="font-size:24px;" ><?php echo $array['employee'][$language]; ?></label>
                                      <input type="text" autocomplete="off"  class="form-control col-sm-7 only1" disabled="true"  style="font-size:22px;width:220px;" name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>" >
                                    </div>
                                  </div>
                                </div>
                                <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "><?php echo $array['time'][$language]; ?></label>
                                      <input type="text" autocomplete="off" class="form-control col-sm-7 only1" disabled="true"  class="form-control" style="font-size:24px;width:220px;" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label "><?php echo $array['totalweight'][$language]; ?></label>
                                      <input class="form-control col-sm-7 only1" autocomplete="off" disabled="true"  style="font-size:20px;width:220px;height:40px;padding-top:6px;" id='wTotal' placeholder="0.00">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div> <!-- tag column 1 -->
                                      <!-- row btn -->
                                      <div class="row m-1 mt-4 d-flex justify-content-end col-12" >
                          <div class="menu mhee"  id="hover1">
                            <div class="d-flex justify-content-center">
                              <div class="circle1 d-flex justify-content-center">
                                <button class="btn" onclick="CreateDocument()" id="bCreate" >
                                  <i class="fas fa-file-medical"></i>
                                  <div>
                                    <?php echo $array['createdocno'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu"  id="hover2">
                            <div class="d-flex justify-content-center">
                              <div class="circle2 d-flex justify-content-center opacity" id="bImport2">
                                <button class="btn" onclick="OpenDialogItem()" id="bImport" disabled="true"> 
                                  <i class="fas fa-file-import"></i>
                                  <div>
                                    <?php echo $array['import'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu"  id="hover3">
                            <div class="d-flex justify-content-center">
                              <div class="circle3 d-flex justify-content-center opacity" id="bDelete2">
                                <button class="btn" onclick="DeleteItem()" id="bDelete"disabled="true">
                                  <i class="fas fa-trash-alt"></i>
                                  <div>
                                    <?php echo $array['delitem'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu"  id="hover4">
                            <div class="d-flex justify-content-center">
                              <div  class="circle4 d-flex justify-content-center opacity" id="bSave2">
                                <button class="btn" onclick="SaveBill()" id="bSave"disabled="true">
                                  <div id="icon_edit">
                                    <i class="fas fa-save"></i>
                                    <div>
                                      <?php echo $array['save'][$language]; ?>
                                    </div>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu"  id="hover5">
                            <div class="d-flex justify-content-center">
                              <div class="circle5 d-flex justify-content-center opacity" id="bCancel2">
                                <button class="btn" onclick="CancelDocument()" id="bCancel"disabled="true">
                                  <i class="fas fa-times"></i>
                                  <div>
                                    <?php echo $array['Canceldoc'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                         
                        </div>
                        <!-- end row btn -->
                    </div>

                    <div class="row">
                      <div class="col-md-12"> <!-- tag column 1 -->
                        <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="100%" cellspacing="0" role="grid" style="">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                            <th style="width: 3%;">&nbsp;</th>
                              <th style='width: 6%;' nowrap><?php echo $array['sn'][$language]; ?></th>
                              <th style='width: 18%;' nowrap><?php echo $array['code'][$language]; ?></th>
                              <th style='width: 20%;' nowrap><?php echo $array['item'][$language]; ?></th>
                              <th style='width: 30%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                              <th style='width: 23%;' nowrap><center><?php echo $array['qty'][$language]; ?></center></th>
                            </tr>
                          </thead>
                          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                          </tbody>
                        </table>
                      </div> <!-- tag column 1 -->
                    </div>
                  </div>

                  <!-- search document -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row mt-3">
                          <div class="col-md-4">
                            <div class="row" style="font-size:24px;margin-left:2px;">
                              <select class="form-control" style='font-size:24px;' id="Dep2" disabled='true'>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 mhee">
                          <div class="row" style="margin-left:2px;">
                            <input type="text" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                            <div class="search_custom col-md-2">
                              <div class="search_1 d-flex justify-content-start">
                                <button class="btn"  onclick="ShowDocument(1)" >
                                  <i class="fas fa-search mr-2"></i>
                                  <?php echo $array['search'][$language]; ?>
                                </button>
                              </div>
                            </div>
                            <div class="search_custom col-md-2">
                          <div class="circle11 d-flex justify-content-start">
                            <button class="btn"  onclick="SelectDocument()" id="btn_show" >
                              <i class="fas fa-paste mr-2 pt-1"></i>
                              <?php echo $array['show'][$language]; ?>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                        <div class="row">
                          <div class="col-md-12"> <!-- tag column 1 -->
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                              <thead id="theadsum" style="font-size:24px;">
                                <tr role="row">
                                  <th style='width: 10%;' nowrap>&nbsp;</th>
                                  <th style='width: 15%;'  nowrap><?php echo $array['docdate'][$language]; ?></th>
                                  <th style='width: 15%;'  nowrap><?php echo $array['docno'][$language]; ?></th>
                                  <th style='width: 15%;'  nowrap><?php echo $array['refdocno'][$language]; ?></th>
                                  <th style='width: 18%;'  nowrap><?php echo $array['employee'][$language]; ?></th>
                                  <th style='width: 16%;'  nowrap><?php echo $array['time'][$language]; ?></th>
                                  <th style='width: 11%;'  nowrap><?php echo $array['status'][$language]; ?></th>
                                </tr>
                              </thead>
                              <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                              </tbody>
                            </table>
                          </div> <!-- tag column 1 -->
                        </div>
                      </div> 
                  <!-- end row tab -->
                </div>
              </div>


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
                <label class="col-sm-4 col-form-label text-right pr-5"><?php echo $array['searchplace'][$language]; ?></label>
                <input type="text" class="form-control col-sm-7" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
              </div>
            </div>

              <!-- serach----------------------- -->
              <div class="search_custom col-md-2">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn" onclick="ShowItem()" id="bSave">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>

              <div class="search_custom col-md-2">
                <div class="import_1 d-flex justify-content-start opacity" id="bSaveadd2">
                  <button class="btn dis" onclick="getImport(1)"  id="bSaveadd" disabled="true">
                    <i class="fas fa-file-import mr-2 pt-1"></i>
                    <?php echo $array['import'][$language]; ?>
                  </button>
                </div>
              </div>
              <!-- end serach----------------------- -->
          </div>
          <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
            <thead style="font-size:24px;">
              <tr role="row">
                <input type="text" hidden id="countcheck">
                <th style='width: 24%;' nowrap><?php echo $array['no'][$language]; ?></th>
                <!-- <th style='width: 20%;' nowrap><?php echo $array['code'][$language]; ?></th> -->
                <th style='width: 23%;' nowrap><?php echo $array['item'][$language]; ?></th>
                <th style='width: 39%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                <th style='width: 14%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
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
<div class="modal fade" id="dialogRefDocNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <label class="col-sm-4 col-form-label text-right pr-5"><?php echo $array['serchref'][$language]; ?></label>
                <input type="text" class="form-control col-sm-7" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['serchref'][$language]; ?>" >
              </div>
            </div>
            <div class="search_custom col-md-2">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn" onclick="get_claim_doc()" id="bSave">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>
              <div class="search_custom col-md-2">
                <div class="import_1 d-flex justify-content-start">
                  <button class="btn" onclick="UpdateRefDocNo()" id="bSave">
                  <i class="fas fa-file-import pt-1 mr-2"></i>
                    <?php echo $array['import'][$language]; ?>
                  </button>
                </div>
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
