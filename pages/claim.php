<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
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

  <title><?php echo $array['titlewash'][$language]; ?></title>

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

    $(document).ready(function(e){
      $('#rem2').hide();
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

      // dialog = jqui( "#dialog" ).dialog({
      //   autoOpen: false,
      //   height: 650,
      //   width: 1200,
      //   modal: true,
      //   buttons: {
      //     "<?php echo $array['close'][$language]; ?>": function() {
      //       dialog.dialog( "close" );
      //     }
      //   },
      //   close: function() {
      //     console.log("close");
      //   }
      // });

      // jqui( "#dialogItem" ).button().on( "click", function() {
      //   dialog.dialog( "open" );
      // });

      dialogUsageCode = jqui( "#dialogUsageCode" ).dialog({
        autoOpen: false,
        height: 680,
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
      if( docno != "" ){
        $( "#TableItem tbody" ).empty();
        // dialogItemCode.dialog( "open" );
        $('#dialogItemCode').modal('show');

      }
      ShowItem();

    }


    function OpenDialogUsageCode(itemcode){
      xItemcode = itemcode;
      var docno = $("#docno").val();
      if( docno != "" ){
        dialog.dialog( "close" );
        dialogUsageCode.dialog( "open" );
        $( "#TableItem tbody" ).empty();
        ShowUsageCode();
      }
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

      function DeleteItem(){
        var docno = $("#docno").val();
        var xrow = $("#checkrow:checked").val() ;
        xrow = xrow.split(",");
        swal({
          title: "<?php echo $array['confirm'][$language]; ?>",
          text: "<?php echo $array['confirm1'][$language]; ?>"+xrow[1]+"<?php echo $array['confirm2'][$language]; ?>่",
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
            if (result.value) {
              var data = {
                'STATUS'    : 'DeleteItem',
                'rowid'  : xrow[0],
                'DocNo'   : docno
              };
              senddata(JSON.stringify(data));
            }else if (result.dismiss === 'cancel') {
              swal.close();
            }
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
// 
      function getDepartment(){
        var Hotp = $('#hotpital option:selected').attr("value");
        if( typeof Hotp == 'undefined' ) Hotp = "BHQ";
        var data = {
          'STATUS'  : 'getDepartment',
          'Hotp'	: Hotp
        };
        senddata(JSON.stringify(data));
      }

      function checkblank2(){
          $('.checkblank2').each(function() {
            if($(this).val()==""||$(this).val()==undefined){
              $(this).addClass('border-danger');
              $('#rem2').show().css("color","red");
            }else{
              $(this).removeClass('border-danger');
              $('#rem2').hide();
            }
          });
        }
        function removeClassBorder1(){
          $('#department').removeClass('border-danger');
          $('#rem2').hide();
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
        var dept = $('#Dep2').val();
        var data = {
          'STATUS'  : 'CancelBill',
          'DocNo'   : docno,
          'deptCode' : dept
        };
        senddata(JSON.stringify(data));
        $('#profile-tab').tab('show');
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

        //	  alert("xrow : "+xrow);
        //	  alert("xqty : "+xqty);
        //	  alert("xweight : "+xweight);
        //	  alert("xunit : "+xunit);

        var Hotp = $('#hotpital option:selected').attr("value");
        if( typeof Hotp == 'undefined' ) Hotp = "1";

        $('#TableDetail tbody').empty();
        var data = {
          'STATUS'  	: 'getImport',
          'xrow'		: xrow,
          'xqty'		: xqty,
          'xweight'	: xweight,
          'xunit'		: xunit,
          'DocNo'   	: docno,
          'Sel'     	: Sel,
          'Hotp'		: Hotp
        };
        senddata(JSON.stringify(data));
        $('#dialogItemCode').modal('toggle');
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
              $('.disx').attr('disabled', false);
            }

      function resetradio(row){
        var previousValue = $('.checkitem_'+row).attr('previousValue');
        var name = $('.checkitem_'+row).attr('name');
        if (previousValue == 'checked') {
          $('#bDelete').attr('disabled', true);
          $('#bDelete2').addClass('opacity');
          $('.checkitem_'+row).removeAttr('checked');
          $('.checkitem_'+row).attr('previousValue', false);
          $('.checkitem_'+row).prop('checked', false);
        } else {
          $('#bDelete').attr('disabled', false);
          $('#bDelete2').removeClass('opacity');
          $("input[name="+name+"]:radio").attr('previousValue', false);
          $('.checkitem_'+row).attr('previousValue', 'checked');
        }
    }

      function convertUnit(rowid,selectObject){
        var docno = $("#docno").val();
        var data = selectObject.value;
        var chkArray = data.split(",");
        var weight = $('#weight_'+chkArray[0]).val();
        var qty = $('#qty1_'+chkArray[0]).val();
        var oleqty = $('#OleQty_'+chkArray[0]).val();

        var unit = chkArray[1];
        var PriceUnit = $('#PriceUnit'+rowid).data('value');

       
        qty = oleqty*chkArray[2];
        $('#qty1_'+chkArray[0]).val(qty);

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
        if(deptCode==''){
          checkblank2();
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
      }else{
        $('#TableDetail tbody').empty();
        swal({
          title: "<?php echo $array['confirm'][$language]; ?>",
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
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
          })
      }
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
              if (result.value) {
                var data = {
                  'STATUS'      : 'CancelDocNo',
                  'DocNo'       : docno
                };
                senddata(JSON.stringify(data));
                getSearchDocNo();
              }else if (result.dismiss === 'cancel') {
                swal.close();
              }
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

 

            if((sub>=0) && (sub<=500)) {
            if(isStatus==0){
              // alert(sub);
              $('#qty1_'+cnt).val(sub);
              $('#OleQty_'+cnt).val(newQty);
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
          }

          function updateWeight(row,rowid) {
            var docno = $("#docno").val();
            var weight = $("#weight_"+row).val();
            // var price = $("#price_"+row).val();
            var isStatus = $("#IsStatus").val();
            if(isStatus==0){
              var data = {
                'STATUS'      : 'UpdateDetailWeight',
                'Rowid'       : rowid,
                'DocNo'       : docno,
                'Weight'      : weight
                // 'Price'      : price
              };
              senddata(JSON.stringify(data));
            }
          }

          function PrintData(){
            var docno = $('#docno').val();
            var lang = '<?php echo $language; ?>';
            if(docno!=""&&docno!=undefined){
              var url  = "../report/Report_Bill_Claim.php?DocNo="+docno+"&lang="+lang;
              window.open(url);
            }else{
              swal({
                title: '',
                text: '<?php echo $array['docfirst'][$language]; ?>',
                type: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              })
            }
          }

          function SaveBill(){
            var total = $("#totalprice").data("value");
            var docno = $("#docno").val();
            var dept = $('#Dep2').val();
            var isStatus_chk = $("#IsStatus").val();
            var isStatus = $("#IsStatus").val();
            if(isStatus==1)
            isStatus=0;
            else
            isStatus=1;
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
              'isStatus'    : isStatus,
              'deptCode' : dept,
              'total' : total
            };
            senddata(JSON.stringify(data));
            if(isStatus_chk==0){
              $('#profile-tab').tab('show');
            }
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
            ShowDocument();
          } else if (result.dismiss === 'cancel') {
          swal.close();}
        })
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

          function currencyFormat(num) {
            var price =  num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            $("#total").val(price);
          }

          function senddata(data){
            var form_data = new FormData();
            form_data.append("DATA",data);
            var URL = '../process/claim.php';
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
                    var Str2 = "<option value=''>-</option>";
                    for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                      Str2 += "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                      var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                    }
                    $("#department").append(Str2);
                    $("#Dep2").append(Str2);
                  }else if( (temp["form"]=='CreateDocument') ){
                    swal({
                      title: "<?php echo $array['createdocno'][$language]; ?>",
                      text: temp[0]['DocNo'] + "   <?php echo $array['success'][$language]; ?>",
                      type: "success",
                      showCancelButton: false,
                      timer: 5000,
                      confirmButtonText: 'Ok',
                      closeOnConfirm: false
                    });
                    $('.dis').attr('disabled', false);
                    $('#bDelete').attr('disabled', true);
                    $('#bSave2').removeClass('opacity');
                    $('#bImport2').removeClass('opacity');
                    $('#bCancel2').removeClass('opacity');
                    $( "#TableItemDetail tbody" ).empty();
                    $("#total").val("0.00");
                    $("#docno").val(temp[0]['DocNo']);
                    $("#docdate").val(temp[0]['DocDate']);
                    $("#recorder").val(temp[0]['Record']);
                    $("#timerec").val(temp[0]['RecNow']);
    

                  }else if(temp["form"]=='ShowDocument'){
                    $( "#TableDocument tbody" ).empty();
                    //               $("#docno").val(temp[0]['DocNo']);
                    // $("#docdate").val(temp[0]['DocDate']);
                    // $("#recorder").val(temp[0]['Record']);
                    // $("#timerec").val(temp[0]['RecNow']);
                    // $("#total").val(temp[0]['Total']);

                    for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                      var rowCount = $('#TableDocument >tbody >tr').length;
                      var chkDoc = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' ><span class='checkmark'></span></label>";
                      var Status = "";
                      var Style  = "";
                      if(temp[i]['IsStatus']==1){
                        Status = "<?php echo $array['savesuccess'][$language]; ?>";
                        Style  = "style='width: 10%;color: #20B80E;'";
                      }else{
                        Status = "<?php echo $array['draft'][$language]; ?>";
                        Style  = "style='width: 10%;color: #3399ff;'";
                      }if(temp[i]['IsStatus']==2){
                        Status = "<?php echo $array['Canceldoc'][$language]; ?>";
                        Style  = "style='width: 10%;color: #ff0000;'";
                      }

                      $StrTr="<tr id='tr"+temp[i]['DocNo']+"'>"+
                      "<td style='width: 10%;'nowrap>"+chkDoc+"</td>"+
                      "<td style='width: 15%;'nowrap>"+temp[i]['DocDate']+"</td>"+
                      "<td style='width: 15%;'nowrap>"+temp[i]['DocNo']+"</td>"+
                      "<td style='width: 15%;'nowrap>"+temp[i]['DepName']+"</td>"+
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



                  }else if(temp["form"]=='ShowDocument_sub'){
                    $( "#TableDocument tbody" ).empty();
                    //               $("#docno").val(temp[0]['DocNo']);
                    // $("#docdate").val(temp[0]['DocDate']);
                    // $("#recorder").val(temp[0]['Record']);
                    // $("#timerec").val(temp[0]['RecNow']);
                    // $("#total").val(temp[0]['Total']);
                    $( "#TableItemDetail tbody" ).empty();
                    $("#hotpital").val("1");
                    $("#department").val("");
                    $("#docdate").val("");
                    $("#docno").val("");
                    $("#recorder").val("");
                    $("#timerec").val("");
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
                      "<td style='width: 15%;'nowrap>"+temp[i]['DepName']+"</td>"+
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
                    $("#department").val(temp[0]['DepCode']);
                    $("#timerec").val(temp[0]['RecNow']);
                    $("#total").val(temp[0]['Total']);
                    $("#IsStatus").val(temp[0]['IsStatus']);
                    if(temp[0]['IsStatus']==0){
                      var word = '<?php echo $array['save'][$language]; ?>';
                      var changeBtn = "<i class='fa fa-save'></i>";
                      changeBtn += "<div>"+word+"</div>";
                      $('#icon_edit').html(changeBtn);
                      $("#bImport").prop('disabled', false);
                      $("#bSave").prop('disabled', false);
                      $("#bCancel").prop('disabled', false);
                      $("#bPrint").prop('disabled', false);
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
                    }else{
                      $("#bImport2").removeClass('opacity');
                    $("#bSave2").removeClass('opacity');
                    $("#bCancel2").removeClass('opacity');
                      $("#bImport").prop('disabled', true);
                      $("#bDelete").prop('disabled', true);
                      $("#bSave").prop('disabled', true);
                      $("#bCancel").prop('disabled', true);
                      $("#bImport2").addClass('opacity');
                      $("#bDelete2").addClass('opacity');
                      $("#bSave2").addClass('opacity');
                      $("#bCancel2").addClass('opacity');

                      $("#docno").prop('disabled', true);
                      $("#docdate").prop('disabled', true);
                      $("#recorder").prop('disabled', true);
                      $("#department").prop('disabled', true);
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
                    $("#total").val("0.00");

                    // $("#total").val(temp['TotalPrice']);
                    currencyFormat(temp['TotalPrice']);

                    var isStatus = $("#IsStatus").val();

                    var st1 = "style='font-size:24px;margin-left:30px; width:153px;'";
                    for (var i = 0; i < temp["Row"]; i++) {
                      var rowCount = $('#TableItemDetail >tbody >tr').length;

                      var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:24px;' id='unit"+i+"'>";

                      for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                        if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode2'])
                        chkunit += "<option selected value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                        else
                        chkunit += "<option value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                      }
                      
                      if( temp[i]['Qty2'] ==0){
                          var hidden     = "hidden";
                        }else{
                          var hidden     = "";
                        }
                      chkunit += "</select>";
                      var CusPrice = temp[i]['CusPrice'].toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
                      var chkDoc = "<div class='form-inline'><label class='radio' style='margin:0px!important;'><input type='radio' name='checkrow' id='checkrow' class='checkitem_"+i+"' value='"+temp[i]['RowID']+","+temp[i]['ItemName']+"'onclick='resetradio(\""+i+"\")'><span class='checkmark'></span><label style='margin-left:27px; '> "+(i+1)+"</label></label></div>";
                      var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode2']+"\")'>-</button><input class='form-control numonly' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_"+i+"' value='"+temp[i]['Qty2']+"' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode2']+"\")'>+</button></div>";
                      var OleQty = "<div class='row' style='margin-left:2px;'><input type='hidden' class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='OleQty_"+i+"' value='"+temp[i]['Qty1']+"' ></div>";
                      // var hidden = temp[i]['hidden'];
                      var UnitName2 =  "<lable id='unitname2_"+temp[i]['RowID']+"' "+hidden+"  > "+ temp[i]['UnitName2'] +"<lable>";
                      var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' readonly style='height:40px;width:69px; margin-left:3px; margin-right:3px; text-align:center;' id='weight_"+i+"' value='"+temp[i]['Weight']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";
                      // var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:150px; margin-left:30px; margin-right:3px; text-align:center;' id='price_"+i+"' value='"+temp[i]['Price']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";
                      var PriceUnit = temp[i]['cal']==undefined?'-':Math.round(temp[i]['cal']);
                      $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                      "<td style='width: 9%;'nowrap>"+chkDoc+" </td>"+
                      "<td style='text-overflow: ellipsis;overflow: hidden;width: 16%;'nowrap>"+temp[i]['ItemCode']+"</td>"+
                      "<td style='text-overflow: ellipsis;overflow: hidden;width: 15%;'nowrap>"+temp[i]['ItemName']+"</td>"+
                      "<td style='width: 11%;' align='center'nowrap >"+Qty+OleQty+"</td>"+
                      "<td style='width: 21%;' align='center'nowrap>"+chkunit+"</td>"+
                      "<td style='width: 6%;' align='center'nowrap>"+Weight+"</td>"+
                      "<td style='width: 6%;'>                     "+UnitName2+"</td>"+
                      "<td style='width: 8%;' align='center'nowrap >"+PriceUnit+"</td>"+
                      "<td style='width: 7%;' align='right'nowrap >"+CusPrice+"</td>"+
                      "<td style='width: 7%;' align='right'nowrap hidden id='totalprice' data-value='"+temp['TotalPrice']+"'></td>"+

                      "</tr>";
                      if(rowCount == 0){
                        $("#TableItemDetail tbody").append( $StrTR );
                      }else{
                        $('#TableItemDetail tbody:last-child').append( $StrTR );
                      }
                      if(isStatus==0){
                        $("#bPrint").prop('disabled', false);
                        $('#qty1_'+i).prop('disabled', false);
                        $('#weight_'+i).prop('disabled', false);
                        $('#price_'+i).prop('disabled', false);
                        $('#price_'+i).prop('disabled', false);
                        $('#unit'+i).prop('disabled', false);
                      }else{
                        $("#docno").prop('disabled', true);
                        $("#docdate").prop('disabled', true);
                        $("#recorder").prop('disabled', true);
                        $("#department").prop('disabled', true);
                        $("#timerec").prop('disabled', true);
                        $("#total").prop('disabled', true);
                        $('#qty1_'+i).prop('disabled', true);
                        $('#weight_'+i).prop('disabled', true);
                        $('#price_'+i).prop('disabled', true);
                        $("#bPrint").prop('disabled', false);
                        $('#unit'+i).prop('disabled', true);
                      }
                    }
                    $('.numonly').on('input', function() {
                        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                        });
                  }else if( (temp["form"]=='ShowItem') ){
                    var st1 = "style='font-size:24px;margin-left:30px; width:160px;font-family:THSarabunNew'";
                    var st2 = "style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
                    $( "#TableItem tbody" ).empty();
                    for (var i = 0; i < temp["Row"]; i++) {
                      var rowCount = $('#TableItem >tbody >tr').length;

                      var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' id='iUnit_"+i+"'>";

                      for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                        if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode2'])
                        chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                        else
                        chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                      }
                      chkunit += "</select>";

                      var chkDoc = "<input type='checkbox' name='checkitem' id='checkitem' onclick='dis()' value='"+i+"'><input type='hidden' id='RowID"+i+"' value='"+temp[i]['RowID']+"'>";
                      var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control numonly' "+st2+" id='iqty"+i+"' value='0' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                      var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' readonly style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight"+i+"' value='"+temp[i]['QtyPerUnit']+"' ></div>";

                      $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                      "<td style='width: 25%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                      "<td style='width: 20%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''nowrap>"+temp[i]['ItemName']+"</td>"+
                      "<td style='width: 23%;'nowrap>"+chkunit+"</td>"+
                      "<td style='width: 15%;'nowrap>"+Qty+"</td>"+
                      "<td style='width: 15%;'nowrap>"+Weight+"</td>"+
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

                      $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
                      "<td style='width: 10%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                      "<td style='width: 20%;'nowrap>"+temp[i]['UsageCode']+"</td>"+
                      "<td style='width: 40%;'nowrap>"+temp[i]['ItemName']+"</td>"+
                      "<td style='width: 15%;'nowrap>"+chkunit+"</td>"+
                      "<td style='width: 13%;' align='center'nowrap>1</td>"+
                      "<td style='width: 40%;'nowrap>"+temp[i]['PriceUnit']+"</td>"+

                      "</tr>";
                      if(rowCount == 0){
                        $("#TableUsageCode tbody").append( $StrTR );
                      }else{
                        $('#TableUsageCode tbody:last-child').append( $StrTR );
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
    button,input[id^='qty'],input[id^='weight'],input[id^='price']{
      font-size: 24px!important;
    }
    .table > thead > tr >th {
      /* background: #4f88e3!important; */
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
      /* border-top: 0px solid #bbb; */
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
    .datepicker{z-index:9999 !important}
      .hidden{visibility: hidden;}
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
      .sidenav a {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
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
      .sidenav a:hover {
        color: #2c3e50;
        font-weight:bold;
        font-size:26px;
      }
      .icon{
          padding-top: 6px;
          padding-left: 42px;
        }
        .opacity{
        opacity:0.5;
      }
        .only1:disabled, .form-control[readonly] {
    background-color: transparent !important;
    opacity: 1;
}
        @media (min-width: 992px) and (max-width: 1199.98px) { 

          .icon{
            padding-top: 6px;
            padding-left: 23px;
          }
          .sidenav a {
            font-size: 20px;

          }
        }
  </style>
        </head>

        <body id="page-top">
          <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
          <ol class="breadcrumb">
  
  <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['account']['title'][$language]; ?></a></li>
  <li class="breadcrumb-item active"><?php echo $array2['menu']['account']['sub'][0][$language]; ?></li>
</ol>
          <div id="wrapper">
            <!-- content-wrapper -->
            <div id="content-wrapper">

              <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
                <div class="col-md-12"> <!-- tag column 1 -->
                  <div class="container-fluid">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titleclaim'][$language]; ?></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                      </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <!-- /.content-wrapper -->
                        <div class="row mt-3">
                          <div class="col-md-9"> <!-- tag column 1 -->
                            <div class="container-fluid">
                              <div class="card-body" style="padding:0px; margin-top:10px;">

                              <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right" style="font-size:24px;" ><?php echo $array['side'][$language]; ?></label>
                                      <select  class="form-control col-sm-7" style="font-size:22px;"  id="hotpital" onchange="getDepartment();" >
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right" style="font-size:24px;" ><?php echo $array['department'][$language]; ?></label>
                                        <select class="form-control col-sm-7 checkblank2 border" style="font-size:22px;"  id="department" onchange="removeClassBorder1();">
                                        </select>
                                        <label id="rem2" style="margin-left: 93%;margin-top: -9%;"> * </label>
                                    </div>
                                  </div>
                                </div>
                    <!-- =================================================================== -->
                    <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right" style="font-size:24px;" ><?php echo $array['docdate'][$language]; ?></label>
                                      <input type="text"  autocomplete="off" style="font-size:22px;"  class="form-control col-sm-7 only only1" disabled="true"  name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right" style="font-size:24px;" ><?php echo $array['docno'][$language]; ?></label>
                                      <input type="text" autocomplete="off" style="font-size:22px;"   class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >
                                    </div>
                                  </div>
                                </div>
                    <!-- =================================================================== -->


                            <div class="row">
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['employee'][$language]; ?></label>
                                  <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-7 only only1" disabled="true"  name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>" >
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['time'][$language]; ?></label>
                                    <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>" >
                                </div>
                              </div>
                            </div>
                    <!-- =================================================================== -->

                             
                    </div>
                            </div>
                          </div> <!-- tag column 1 -->
                          <div class="col-md-3" > <!-- tag column 2 -->
                            <div class='row' style='margin-left:2px;'>
                              <input class='form-control'autocomplete="off"   style="margin-left:-48px;margin-top:10px;font-size:60px;width:100%;height:162px;text-align:right;padding-top: 15px;" id='total' placeholder="0.00" readonly>
                            </div>

                          </div> <!-- tag column 2 -->
                        </div>

                        <!-- row btn -->
                        <div class="row m-1 mt-4 d-flex justify-content-end" >
                          <div class="menu" >
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
                          <div class="menu" >
                            <div class="d-flex justify-content-center">
                              <div class="circle2 d-flex justify-content-center opacity" id="bImport2">
                                <button class="btn dis" onclick="OpenDialogItem()" id="bImport" disabled="true">
                                  <i class="fas fa-file-import"></i>
                                  <div>
                                    <?php echo $array['import'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" >
                            <div class="d-flex justify-content-center">
                              <div class="circle3 d-flex justify-content-center opacity" id="bDelete2">
                                <button class="btn dis" onclick="DeleteItem()" id="bDelete" disabled="true">
                                  <i class="fas fa-trash-alt"></i>
                                  <div>
                                    <?php echo $array['delitem'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" >
                            <div class="d-flex justify-content-center">
                              <div class="circle4 d-flex justify-content-center opacity" id="bSave2">
                                <button class="btn dis" onclick="SaveBill()" id="bSave" disabled="true">
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
                          <div class="menu" >
                            <div class="d-flex justify-content-center">
                              <div class="circle5 d-flex justify-content-center opacity" id="bCancel2">
                                <button class="btn dis" onclick="CancelBill()" id="bCancel" disabled="true">
                                  <i class="fas fa-times"></i>
                                  <div>
                                    <?php echo $array['Canceldoc'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" >
                            <div class="d-flex justify-content-center">
                              <div class="circle9 d-flex justify-content-center">
                                <button class="btn dis" onclick="PrintData()" id="bPrint">
                                  <i class="fas fa-print"></i>
                                  <div>
                                    <?php echo $array['print'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- end row btn -->

                        <div class="row">
                          <div class="col-md-12"> <!-- tag column 1 -->
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="100%" cellspacing="0" role="grid" style="">
                              <thead id="theadsum" style="font-size:24px;">
                                <tr role="row">
                                  <th style="width: 3%;">&nbsp;</th>
                                  <th style='width: 6%;'nowrap><?php echo $array['sn'][$language]; ?></th>
                                  <th style='width: 17%;'nowrap><?php echo $array['code'][$language]; ?></th>
                                  <th style='width: 12%;'nowrap><?php echo $array['item'][$language]; ?></th>
                                  <th style='width: 14%;'nowrap><center><?php echo $array['total'][$language]; ?></center></th>
                                  <th style='width: 17%;'nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                                  <th style='width: 12%;'nowrap><center><?php echo $array['perunit'][$language]; ?></center></th>
                                  <th style='width: 12%;'nowrap><center><?php echo $array['priceunit'][$language]; ?></center></th>
                                  <th style='width: 7%;'nowrap><center><?php echo $array['money'][$language]; ?></center></th>
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
                        <div class="row" style="margin-top:10px;">
                          <div class="col-md-4">
                            <div class="row" style="font-size:24px;margin-left:2px;">
                              <select class="form-control" style='font-size:24px;' id="Dep2"></select>
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
                                  <th style='width: 10%;'nowrap>&nbsp;</th>
                                  <th style='width: 15%;'nowrap><?php echo $array['docdate'][$language]; ?></th>
                                  <th style='width: 15%;'nowrap><?php echo $array['docno'][$language]; ?></th>
                                  <th style='width: 15%;'nowrap><?php echo $array['department'][$language]; ?></th>
                                  <th style='width: 15%;'nowrap><?php echo $array['employee'][$language]; ?></th>
                                  <th style='width: 10%;'nowrap><?php echo $array['time'][$language]; ?></th>
                                  <th style='width: 10%;'nowrap><?php echo $array['money'][$language]; ?></th>
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
                    <!-- -----------------------------Custom1------------------------------------ -->
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
                  <input type="text"  autocomplete="off" class="form-control col-sm-8" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
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
                <div class="import_1 d-flex justify-content-start">
                  <button class="btn disx" onclick="getImport(1)" id="bSave"disabled="true">
                      <i class="fas fa-file-import mr-2 pt-1"></i>
                      <?php echo $array['import'][$language]; ?>
                  </button>
                </div>
              </div>
              <!-- end serach----------------------- -->

              <!-- <div class="col-md-1 ">
                <img src="../img/icon/ic_import.png" style="margin-left: 2px;width:36px;" class='mr-3'>
              </div>
              <div class="col-md-1 mhee">
                  <a href='javascript:void(0)' onclick="getImport(1)" id="bSave" style="margin-left: -33px;">
                <?php echo $array['import'][$language]; ?></a>   
              </div> -->
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 25%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <!-- <th style='width: 20%;' nowrap><?php echo $array['code'][$language]; ?></th> -->
                  <th style='width: 20%;' nowrap><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 26%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                  <th style='width: 14%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                  <th style='width: 15%;' nowrap><?php echo $array['perunit'][$language]; ?></th>
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
