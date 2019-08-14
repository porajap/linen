<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$HptName = $_SESSION['HptName'];
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

// var_dump(); die;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['dirty'][$language]; ?></title>

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

  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
  jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>
  <!-- custome style -->
  <link href="../css/responsive.css" rel="stylesheet">
  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <script type="text/javascript">
  var summary = [];
  var xItemcode;
  var RowCnt=0;

  $(document).ready(function(e){

       
  //  console.log(window.parent.location.href);
    OnLoadPage();

    // CreateDocument();
    //==============================
    $('.TagImage').bind('click', {
      imgId: $(this).attr('id') }, function (evt) { alert(evt.imgId); });
      //On create
        // alert(PmID);
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

  }).mousemove(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });

    jqui(document).ready(function($){
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
        dialogItemCode.dialog( "close" );
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
          swal.close();
          }
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
          var data = {
            'STATUS'  : 'OnLoadPage'
          };
          senddata(JSON.stringify(data));
          $('#isStatus').val(0)
        }

        function getDepartment(){
          var Hotp = $('#hotpital option:selected').attr("value");
          if(Hotp == '' || Hotp == undefined){
            Hotp = $('#getHot').val();
          };
            var data = {
              'STATUS'  : 'getDepartment',
              'Hotp'	: Hotp
            };

            senddata(JSON.stringify(data));
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

        function ShowDocument_sub(){
          var searchdocument = $('#searchdocument').val();
          if( typeof searchdocument == 'undefined' ) searchdocument = "";
          var deptCode = $('#Dep2 option:selected').attr("value");
          if( typeof deptCode == 'undefined' ) deptCode = "1";
          var data = {
            'STATUS'  	: 'ShowDocument',
            'xdocno'	: searchdocument,
            'deptCode'	: deptCode
          };
          senddata(JSON.stringify(data));
        }

        function ShowItem(){
          var deptCode = $('#department option:selected').attr("value");
          if( typeof deptCode == 'undefined' ) deptCode = "1";
          var searchitem = $('#searchitem').val();
          var data = {
            'STATUS'  : 'ShowItem',
            'xitem'	: searchitem,
            'deptCode'	: deptCode
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
            'xdocno'		: selectdocument
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
            'DocNo'   : docno,
            'selecta' : '0'
          };
          senddata(JSON.stringify(data));
          $('#profile-tab').tab('show');
          ShowDocument(3);
        }

        function getImport(Sel) {
          //alert(Sel);
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



          var Hotp = $('#hotpital option:selected').attr("value");
          if( typeof Hotp == 'undefined' ) Hotp = "<?php echo $HptCode; ?>";

          // alert("xrow : "+xrow);
          //	  alert("xqty : "+xqty);
          //	  alert("xweight : "+xweight);
          //	  alert("xunit : "+xunit);

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
          console.log(data);
          senddata(JSON.stringify(data));
          $('#dialogItemCode').modal('toggle')
          // dialogUsageCode.dialog( "close" );
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
          // console.log( "UnitCode : "+chkArray[1] );
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
          var FacCode = $('#factory option:selected').attr("value");

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
                'userid'	: userid,
                'FacCode'	: FacCode
              };
              senddata(JSON.stringify(data));
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
              confirmButtonText: "<?php echo $array['deleete'][$language]; ?>",
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
              var isStatus = $("#IsStatus").val();
              var dept = $("#Dep2").val();
              var DepCode = $("#department").val();
              var FacCode = $("#factory").val();

              // alert( isStatus );
              if(isStatus==1 || isStatus==3)
              isStatus=0;
              else
              isStatus=1;
              
              if(isStatus==1){
                if(docno!=""){

                swal({
              title: "<?php echo $array['confirmsave'][$language]; ?>",
              text: "<?php echo $array['docno'][$language]; ?>"+docno+"",
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
                  'docno'      : docno,
                  'isStatus'    : isStatus,
                  'deptCode'    : dept,
                  'FacCode'    : FacCode

                };
                senddata(JSON.stringify(data));
                $('#profile-tab').tab('show');
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', true);
                $("#bCancel").prop('disabled', true);
              } else if (result.dismiss === 'cancel') {
                swal.close();}
              })

              }




                  // ShowDocument();
              }else{
                
                $("#bImport").prop('disabled', false);
                $("#bDelete").prop('disabled', false);
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

            function UpdateRefDocNo(){
              var docno = $("#docno").val();
              var RefDocNo = $("#RefDocNo").val();
              // alert( isStatus );
              //	  if(isStatus==1)
              //	  		isStatus=0;
              //	  else
              //	  		isStatus=1;

              var data = {
                'STATUS'      : 'UpdateRefDocNo',
                'xdocno'      : docno,
                'RefDocNo'    : RefDocNo
              };
              senddata(JSON.stringify(data));
            }

            function show_btn(DocNo){
              if(DocNo != undefined || DocNo != ''){
                  $(btn_show).attr('disabled',false);
              }
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
              var URL = '../process/dirty.php';
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
                      $('#getHot').val(temp[0]['HptCode']);
                      for (var i = 0; i < temp["Row"]; i++) {
                        var Str = "<option value="+temp[i]['HptCode']+" id='getHot_"+i+"'>"+temp[i]['HptName']+"</option>";
                        $("#hotpital").append(Str);
                      }

                      for (var i = 0; i < temp["Rowx"]; i++) {
                        var Str = "<option value="+temp[i]['FacCode']+">"+temp[i]['FacName']+"</option>";
                        $("#factory").append(Str);
                      }
                      // if(PmID != 1){
                      //   $("#hotpital").val(HptCode);
                      // }
                      getDepartment();

                    }else if(temp["form"]=='getDepartment'){
                      $("#department").empty();
                      $("#Dep2").empty();
                      var Str2 = "<option value='' selected>-</option>";
                      for (var i = 0; i < temp["Row"]; i++) {
                        var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                        Str2 += "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                        $("#department").append(Str);
                      }
                      $("#Dep2").append(Str2);
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
                      $("#docno").val(temp[0]['DocNo']);
                      $("#docdate").val(temp[0]['DocDate']);
                      $("#recorder").val(temp[0]['Record']);
                      $("#timerec").val(temp[0]['RecNow']);
                      $( "#TableItemDetail tbody" ).empty();
                      $("#wTotal").val(0);
                      // $("#bSave").text('<?php echo $array['save'][$language]; ?>');
                      $("#bImport").prop('disabled', false);
                      $("#bDelete").prop('disabled', false);
                      $("#bSave").prop('disabled', false);
                      $("#bCancel").prop('disabled', false);
                      $("#docno").prop('disabled', false);
                      $("#docdate").prop('disabled', false);
                      $("#recorder").prop('disabled', false);
                      $("#timerec").prop('disabled', false);
                      $("#total").prop('disabled', false);
                    }else if(temp["form"]=='ShowDocument'){
                      $( "#TableDocument tbody" ).empty();
                      $( "#TableItemDetail tbody" ).empty();
                      $("#Dep2").val(temp[0]['DepCode']);
                      // $("#docdate").val(temp[0]['DocDate']);
                      // $("#recorder").val(temp[0]['Record']);
                      // $("#timerec").val(temp[0]['RecNow']);
                      // $("#total").val(temp[0]['Total']);

                      for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                        var rowCount = $('#TableDocument >tbody >tr').length;
                        var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' onclick='show_btn(\""+temp[i]['DocNo']+"\");' value='"+temp[i]['DocNo']+"' >";
                        var Status = "";
                        var Style  = "";
                        if(temp[i]['IsStatus']==1 || temp[i]['IsStatus']==3){
                          Status = "<?php echo $array['savesuccess'][$language]; ?>";
                          Style  = "style='width: 10%;color: #20B80E;'";
                        }else{
                          Status = "<?php echo $array['draft'][$language]; ?>";
                          Style  = "style='width: 10%;color: #3399ff;'";
                        }if(temp[i]['IsStatus']==2){
                          Status = "<?php echo $array['cancel'][$language]; ?>";
                          Style  = "style='width: 10%;color: #ff0000;'";
                        }

                        $StrTr="<tr id='tr"+temp[i]['DocNo']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                        "<td style='width: 10%;' nowrap>"+chkDoc+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['DocDate']+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['DocNo']+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['DepName']+"</td>"+
                        "<td style='width: 15%; overflow: hidden; text-overflow: ellipsis;' nowrap>"+temp[i]['Record']+"</td>"+
                        "<td style='width: 10%;' nowrap>"+temp[i]['RecNow']+"</td>"+
                        "<td style='width: 10%;' nowrap>"+temp[i]['Total']+"</td>"+
                        "<td "+Style+" nowrap>"+Status+"</td>"+
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
                      $("#factory").val(temp[0]['FacCode']);
// 
                      if(temp[0]['IsStatus']==0){
                        var word = '<?php echo $array['save'][$language]; ?>';
                        var changeBtn = "<i class='fa fa-save'></i>";
                        changeBtn += "<div>"+word+"</div>";
                        $('#icon_edit').html(changeBtn);
                        $("#bImport").prop('disabled', false);
                        $("#bDelete").prop('disabled', false);
                        $("#bSave").prop('disabled', false);
                        $("#bCancel").prop('disabled', false);
                      }else if(temp[0]['IsStatus']==1 || temp[0]['IsStatus']==3){
                        var word = '<?php echo $array['edit'][$language]; ?>';
                        var changeBtn = "<i class='fas fa-edit'></i>";
                        changeBtn += "<div>"+word+"</div>";
                        $('#icon_edit').html(changeBtn);
                        $("#bImport").prop('disabled', true);
                        $("#bDelete").prop('disabled', true);
                        $("#bSave").prop('disabled', false);
                        $("#bCancel").prop('disabled', true);
                      }else{
                        $("#bImport").prop('disabled', true);
                        $("#bDelete").prop('disabled', true);
                        $("#bSave").prop('disabled', true);
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
                        if(temp[i]['IsStatus']==0){
                          Status = "<?php echo $array['draft'][$language]; ?>";
                          Style  = "style='width: 10%;color: #3399ff;'";
                        }else if(temp[i]['IsStatus']==1 ){
                          Status = "<?php echo $array['savesuccess'][$language]; ?>";
                          Style  = "style='width: 10%;color: #20B80E;'";
                        }else if (temp[i]['IsStatus']==2){
                           Status = "<?php echo $array['cancelbill'][$language]; ?>";
                          Style  = "style='width: 10%;color: #ff0000;'";
                        }else if (temp[i]['IsStatus']==3){
                          Status = "<?php echo $array['savesuccess'][$language]; ?>";
                          Style  = "style='width: 10%;color: #20B80E;'";
                        }

                        // if(temp[i]['IsStatus']==1 || temp[i]['IsStatus']==3){
                        //   Status = "<?php echo $array['savesuccess'][$language]; ?>";
                        //   Style  = "style='width: 10%;color: #20B80E;'";
                        // }else if(temp[i]['IsStatus']==3){
                        //   Status = "<?php echo $array['draft'][$language]; ?>";
                        //   Style  = "style='width: 10%;color: #3399ff;'";
                        // } else if(temp[i]['IsStatus']==2){
                        //   Status = "<?php echo $array['cancelbill'][$language]; ?>";
                        //   Style  = "style='width: 10%;color: #ff0000;'";
                        // }

                        $StrTr="<tr id='tr"+temp[i]['DocNo']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                        "<td style='width: 10%;' nowrap>"+chkDoc+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['DocDate']+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['DocNo']+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['DepName']+"</td>"+
                        "<td style='width: 15%;' nowrap>"+temp[i]['Record']+"</td>"+
                        "<td style='width: 10%;' nowrap>"+temp[i]['RecNow']+"</td>"+
                        "<td style='width: 10%;' nowrap>"+temp[i]['Total']+"</td>"+
                        "<td "+Style+">"+Status+"</td>"+
                        "</tr>";

                        if(rowCount == 0){
                          $("#TableDocument tbody").append( $StrTr );
                        }else{
                          $('#TableDocument tbody:last-child').append(  $StrTr );
                        }
                      }

                    }else if(temp["form"]=='getImport'  || temp["form"]=='ShowDetail'){
                      $( "#TableItemDetail tbody" ).empty();
                      if(temp["Row"] > 0)
                      $("#wTotal").val(temp[0]['Total']);
                      else
                      $("#wTotal").val(0);

                      var isStatus = $("#IsStatus").val();
                      var st1 = "style='font-size:24px;margin-left:20px;width:150px;'";
                      for (var i = 0; i < temp["Row"]; i++) {
                        var rowCount = $('#TableItemDetail >tbody >tr').length;

                        var chkunit ="<select "+st1+" disabled='true' onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:24px;' id='Unit_"+i+"'>";
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

                        var Qty = "<div class='row' style='margin-left:0px;'><input class='form-control'  style='width:87px;height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='qty1_"+i+"' value='"+temp[i]['Qty']+"'></div>";
                        //var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode']+"\")'>-</button><input class='form-control' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_"+i+"' value='"+temp[i]['ParQty']+"' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode']+"\")'>+</button></div>";
                        //var OleQty = "<div class='row' style='margin-left:2px;'><input type='hidden' class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='OleQty_"+i+"' value='"+temp[i]['MaxQty']+"' ></div>";

                        var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' style='width:87px;height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='weight_"+i+"' value='"+temp[i]['Weight']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

                        var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_"+i+"' value='"+temp[i]['Price']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

                        $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                        "<td style='width: 6%;' nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                        "<td style='text-overflow: ellipsis;overflow: hidden;width: 20%;' nowrap>"+temp[i]['ItemCode']+"</td>"+
                        "<td style='text-overflow: ellipsis;overflow: hidden;width: 30%;' nowrap>"+temp[i]['ItemName']+"</td>"+
                        "<td style='width: 18%;' nowrap>"+chkunit+"</td>"+
                        "<td style='width: 12%;' nowrap>"+Qty+"</td>"+
                        "<td style='width: 12%;' nowrap>"+Weight+"</td>"+
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
                        $('.numonly').on('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
                        });
                    }else if( (temp["form"]=='ShowItem') ){
                      var st1 = "style='font-size:24px;margin-left: -10px; width:150px;font-family:THSarabunNew'";
                      var st2 = "style='height:40px;width:60px;font-size: 20px;margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
                      $( "#TableItem tbody" ).empty();
                      for (var i = 0; i < temp["Row"]; i++) {
                        var rowCount = $('#TableItem >tbody >tr').length;

                        var chkunit ="<select "+st1+" class='form-control' id='iUnit_"+i+"'>";
                        var nUnit = "";

                        for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                          if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode']){
                            chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                            nUnit = temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j];
                          }else{
                            chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                            nUnit = temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j];
                          }
                        }
                        chkunit += "</select>";

                        var chkDoc = "<input type='checkbox' name='checkitem' id='checkitem' value='"+i+"'><input type='hidden' id='RowID"+i+"' value='"+temp[i]['ItemCode']+"'>";
                        var Qty = "<div  class='row' style='margin-left:2px;'><button class='btn btn-danger numonly' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='0' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                        var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly'  style='font-size: 20px;height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight"+i+"' placeholder='0' ></div>";

                        $StrTR = "<tr id='tr"+temp[i]['RowID']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
                        "<td style='width: 10%;'>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                        "<td style='width: 20%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''>"+temp[i]['ItemCode']+"</td>"+
                        "<td style='width: 25%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''>"+temp[i]['ItemName']+"</td>"+
                        "<td style='width: 15%;'>"+chkunit+"</td>"+
                        "<td style='width: 15%;' >"+Qty+"</td>"+
                        "<td style='width: 15%;'>"+Weight+"</td>"+
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
                        "<td style='width: 10%;'>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                        "<td style='width: 20%;'>"+temp[i]['UsageCode']+"</td>"+
                        "<td style='width: 40%;'>"+temp[i]['ItemName']+"</td>"+
                        "<td style='width: 15%;'>"+chkunit+"</td>"+
                        "<td style='width: 13%;' align='center'>1</td>"+
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
      @font-face {
        font-family: myFirstFont;
        src: url("../fonts/DB Helvethaica X.ttf");
      }
      body{
        font-family: myFirstFont;
        font-size:22px;
        overflow: scroll;
        overflow-x: hidden;
      }
      ::-webkit-scrollbar {
          width: 0px;  /* Remove scrollbar space */
          background: transparent;  /* Optional: just make scrollbar invisible */
      }
      /* Optional: show position indicator in red */
      ::-webkit-scrollbar-thumb {
          background: none;
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
      .opacity{
        opacity:0.5;
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
        .mhee button{
                    /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
            color: #2c3e50;
            display: block;
            background: none;
            box-shadow:none!important;
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
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][1][$language]; ?></li>
  </ol>

<input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
       <input type="hidden" id="getHot">
        <div id="wrapper">
          <div id="content-wrapper">            
            <div class="row">
              <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab"data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titledirty'][$language]; ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
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
                                        <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['side'][$language]; ?></label>
                                        <select  class="form-control form-control col-sm-8"  style="font-size:22px;"  id="hotpital" onchange="getDepartment();" disabled="true"> </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class='form-group row'>
                                        <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['department'][$language]; ?></label>
                                          <select class="form-control col-sm-8"  style="font-size:22px;"  id="department" > </select>
                                      </div>
                                    </div>
                                  </div>

                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['docdate'][$language]; ?></label>
                                          <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-8" name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" >

                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class='form-group row'>
                                            <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['docno'][$language]; ?></label>
                                            <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-8"  name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >
                                        </div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['employee'][$language]; ?></label>
                                          <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-8"  name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>" >
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['time'][$language]; ?></label>
                                            <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-8" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>" >
                                        </div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['totalweight'][$language]; ?></label>
                                          <input class='form-control col-sm-8' autocomplete="off"  style="font-size:22px;"  id='wTotal' placeholder="0.00">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['factory'][$language]; ?></label>
                                          <select  class="form-control form-control col-sm-8"  style="font-size:22px;"  id="factory"  > </select>
                                        </div>
                                      </div>
                                      <div class="col-md-6" hidden>
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label text-right"  style="font-size:24px;" ><?php echo $array['refdocno'][$language]; ?></label>
                                            <input type="text" class="form-control col-sm-9"  style="font-size:22px;"  name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>" >
                                            <input class='form-control col-sm-8"'  style="font-size:22px;"  id='RefDocNo' placeholder="<?php echo $array['refdocno'][$language]; ?>" OnBlur='UpdateRefDocNo()'>
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                              </div>
                          </div> <!-- tag column 1 -->
                           <!-- row btn -->
                        <div class="row m-1 mt-4 d-flex justify-content-end col-12" >
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
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
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle2 d-flex justify-content-center">
                                <button class="btn" onclick="OpenDialogItem()" id="bImport">
                                  <i class="fas fa-file-import"></i>
                                  <div>
                                    <?php echo $array['import'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle3 d-flex justify-content-center">
                                <button class="btn" onclick="DeleteItem()" id="bDelete">
                                  <i class="fas fa-trash-alt"></i>
                                  <div>
                                    <?php echo $array['delitem'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div  class="circle4 d-flex justify-content-center">
                                <button class="btn" onclick="SaveBill()" id="bSave">
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
                          <div class="menu" <?php if($PmID == 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle5 d-flex justify-content-center">
                                <button class="btn" onclick="CancelDocument()" id="bCancel">
                                  <i class="fas fa-times"></i>
                                  <div>
                                    <?php echo $array['cancel'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                         
                        </div>
                        <!-- end row btn -->
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <!-- tag column 1 -->
                              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped"
                                  id="TableItemDetail" width="100%" cellspacing="0"  role="grid" style="">
                                  <thead id="theadsum" style="font-size:24px;">
                            <tr role="row" id='tr_1'>
                              <th style='width: 6%;' nowrap><?php echo $array['no'][$language]; ?></th>
                              <th style='width: 20%;' nowrap><?php echo $array['code'][$language]; ?></th>
                              <th style='width: 21%;' nowrap><?php echo $array['item'][$language]; ?></th>
                              <th style='width: 27%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                              <th style='width: 5%;' nowrap ><?php echo $array['qty'][$language]; ?></th>
                              <th style='width: 21%;' nowrap><center><?php echo $array['weight'][$language]; ?></center></th>
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
                              <select class="form-control" style='font-size:24px;' id="Dep2">
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 mhee">
                          <div class="row" style="margin-left:2px;">
                            <input type="text" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                            <div class="search_custom col-md-3">
                              <div class="search_1 d-flex justify-content-start">
                                <button class="btn"  onclick="ShowDocument(1)" >
                                  <i class="fas fa-search mr-2"></i>
                                  <?php echo $array['search'][$language]; ?>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                          
                        <div class="search_custom col-md-2" style="padding-left:65px;">
                          <div class="circle6 d-flex justify-content-start">
                            <button class="btn"  onclick="SelectDocument()" id="btn_show" >
                              <i class="fas fa-paste mr-2 pt-1"></i>
                              <?php echo $array['show'][$language]; ?>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12"> <!-- tag column 1 -->
                          <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                            <thead id="theadsum" style="font-size:24px;">
                              <tr role="row">
                                <th style='width: 10%;' nowrap>&nbsp;</th>
                                <th style='width: 15%;' nowrap><?php echo $array['docdate'][$language]; ?></th>
                                <th style='width: 15%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                                <th style='width: 15%;' nowrap><?php echo $array['department'][$language]; ?></th>
                                <th style='width: 15%;' nowrap><?php echo $array['employee'][$language]; ?></th>
                                <th style='width: 10%;' nowrap><?php echo $array['time'][$language]; ?></th>
                                <th style='width: 10%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                                <th style='width: 10%;' nowrap><?php echo $array['status'][$language]; ?></th>
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

              <!-- <div class="col-md-2" id='tab2' <?php if($PmID == 1) echo 'hidden'; ?>> -->
                <!-- button----------------------------------------- -->
                <!-- <div class=" mhee">
                    <div class="" style="margin-top:5px;">
                      <div class="card-body" style="padding:0px; margin-top:10px;">
                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_create.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <button  class="btn"   onclick="CreateDocument()" id="bCreate">
                              <?php echo $array['createdocno'][$language]; ?>
                            </button>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_import.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <button  class="btn" onclick="OpenDialogItem()" id="bImport">
                              <?php echo $array['import'][$language]; ?>
                            </button>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon" >
                            <img src="../img/icon/ic_delete.png" style='width:40px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <button class="btn"  onclick="DeleteItem()" id="bDelete">
                              <?php echo $array['delitem'][$language]; ?>
                            </button>
                          </div>
                        </div>

                        <div class="row " style="margin-top:0px;">
                          <div class="col-md-3 icon">
                            <img src="../img/icon/ic_save.png" style='width:36px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <button class="btn"  onclick="SaveBill()" id="bSave">
                              <?php echo $array['save'][$language]; ?>
                            </button>
                          </div>
                        </div>

                        <div class="row" style="margin-top:0px;">
                          <div class="col-md-3 icon">
                            <img src="../img/icon/ic_cancel.png" style='width:34px;' class='mr-3'>
                          </div>
                          <div class="col-md-9">
                            <button class="btn"   onclick="CancelDocument()" id="bCancel">
                              <?php echo $array['cancel'][$language]; ?>
                            </button>
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
                </div> -->
                <!-- end button----------------------------------------- -->
              <!-- </div> -->
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
                  <label class="col-sm-3 col-form-label text-right pr-5"><?php echo $array['searchplace'][$language]; ?></label>
                  <input type="text" autocomplete="off"  class="form-control col-sm-9" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
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
                  <button class="btn" onclick="getImport(1)" id="bSave">
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
