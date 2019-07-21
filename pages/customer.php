<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
if($Userid==""){
   header("location:../index.html");
}
 ?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ลูกค้า</title>

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
      //On create
      $('.TagImage').bind('click', { imgId: $(this).attr('id') }, function (evt) { alert(evt.imgId); });
      //On create
      // var userid = '<?php echo $Userid; ?>';
      // if(userid!="" && userid!=null && userid!=undefined){

        var dept = $('#Deptsel').val();
        var keyword = $('#searchitem').val();
        var data = {
          'STATUS'  : 'ShowItem',
          'Dept'    : dept,
          'Keyword' : keyword
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      // }

      var data2 = {
        'STATUS'  : 'getHospital',
        'DEPT'    : dept
      };
      console.log(JSON.stringify(data2));
      senddata(JSON.stringify(data2));

      var data3 = {
        'STATUS'  : 'getSection',
        'HOSP'    : '1'
      };
      console.log(JSON.stringify(data3));
      senddata(JSON.stringify(data3));


      $('#Hosp').on('change', function() {
        var hosp = this.value;
        var data3 = {
          'STATUS'  : 'getSection',
          'HOSP'    : hosp
        };
        console.log(JSON.stringify(data3));
        senddata(JSON.stringify(data3));
      });

      $('#searchitem').keyup(function(e){
          if(e.keyCode == 13)
          {
              ShowItem();
          }
      });

      $('.editable').click(function() {
        alert('hi');
      });

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });
      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
      });

    }).mousemove(function(e) { parent.last_move = new Date();;
    }).keyup(function(e) { parent.last_move = new Date();;
    });

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

    jqui( "#dialogreq" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });

    function unCheckDocDetail(){
        // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
        if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length){
            $('input[name="checkAllDetail').prop('checked',true);
        }else {
            $('input[name="checkAllDetail').prop('checked',false);
        }
    }

    function getDocDetail() {
        // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
          if ($('input[name="checkdocno"]:checked').length == $('input[name="checkdocno"]').length){
            $('input[name="checkAllDoc').prop('checked',true);
          }else {
            $('input[name="checkAllDoc').prop('checked',false);
          }

      /* declare an checkbox array */
      var chkArray = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocno:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var DocNo = chkArray.join(',') ;
// alert( DocNo );
      $('#TableDetail tbody').empty();
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS'  : 'getDocDetail',
        'DEPT'    : dept,
        'DocNo'   : DocNo
      };
        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
    }

var isChecked1 = false;
var isChecked2 = false;
    function getCheckAll(sel){
        if(sel==0){
          isChecked1 = !isChecked1;
          // $( "div #aa" )
          //   .text( "For this isChecked " + isChecked1 + "." )
          //   .css( "color", "red" );

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

    function getSearchDocNo() {
      var dept = '<?php echo $_SESSION['Deptid']; ?>';

        $('#TableDocumentSS tbody').empty();
        var str = $('#searchtxt').val();
        var datepicker = $('#datepicker').val();
        datepicker = datepicker.substring(6,10)+"-"+datepicker.substring(3,5)+"-"+datepicker.substring(0,2);

        var data = {
          'STATUS'      : 'getSearchDocNo',
          'DEPT'        : dept,
          'DocNo'       : str,
          'Datepicker'  : datepicker
        };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function CreateSentSterile() {
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      /* declare an checkbox array */
      var chkArray1 = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocno:checked").each(function() {
        chkArray1.push($(this).val());
      });

      /* we join the array separated by the comma */
      var DocNo = chkArray1.join(',') ;

      /* declare an checkbox array */
      var chkArray2 = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocdetail:checked").each(function() {
        chkArray2.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray2.join(',') ;
      var data = {
        'STATUS'    : 'CreateSentSterile',
        'DEPT'      : dept,
        'DocNo'     : DocNo,
        'UsageCode' : UsageCode,
        'userid'    : userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function setTag(){
      var DocNo = $("#docnofield").val();
      /* declare an checkbox array */
      var chkArray = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#IsTag:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray.join(',') ;
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS'    : 'SSDTag',
        'DEPT'      : dept,
        'userid'    : userid,
        'DocNo'     : DocNo,
        'UsageCode' : UsageCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function CreatePayout(){
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS'    : 'CreatePayout',
        'DEPT'      : dept,
        'userid'    : userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddPayoutDetail(){
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS'    : 'CreatePayout',
        'DEPT'      : dept,
        'userid'    : userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function ShowItem(){
      var dept = $('#Deptsel').val();
      var keyword = $('#searchitem').val();
      var data = {
        'STATUS'  : 'ShowItem',
        'Dept'    : dept,
        'Keyword' : keyword
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddItem(){
      var count = 0;
      $(".checkblank").each(function() {
        if($( this ).val()==""||$(this).val()==undefined){
          count++;
        }
      });
      console.log(count);

      var CusCode = $('#CusCode').val();
      var CusName = $('#CusName').val();
      var Price = $('#Price').val();
      var Address = $('#Address').val();
      var Dept = $('#Dept').val();
      var Post = $('#Post').val();
      var DiscountPercent = $('#DiscountPercent').val();
      var TaxID = $('#TaxID').val();

      if(count==0){
        $('.checkblank').each(function() {
          if($(this).val()==""||$(this).val()==undefined){
            $(this).css('border-color', 'red');
          }else{
            $(this).css('border-color', '');
          }
        });
        if(CusCode==""){
          swal({
            title: "เพิ่มข้อมูล",
            text: "คุณต้องการเพิ่มข้อมูลใหม่ใช่หรือไม่ ?",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "เพิ่ม",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true}).then(result => {
              var data = {
                'STATUS' : 'AddItem',
                'CusCode' : CusCode,
                'CusName' : CusName,
                'Price' : Price,
                'Address' : Address,
                'DepCode' : Dept,
                'Post' : Post,
                'DiscountPercent' : DiscountPercent,
                'TaxID' : TaxID
              };

              console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
            })

        }else{
          swal({
            title: "แก้ไขข้อมูล",
            text: "คุณต้องการแก้ไขข้อมูลใช่หรือไม่ ?",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            confirmButtonText: "แก้ไข",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true}).then(result => {
              var data = {
                'STATUS' : 'EditItem',
                'CusCode' : CusCode,
                'CusName' : CusName,
                'Price' : Price,
                'Address' : Address,
                'DepCode' : Dept,
                'Post' : Post,
                'DiscountPercent' : DiscountPercent,
                'TaxID' : TaxID
              };

              console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
            })

        }
      }else{
        swal({
          title: '',
          text: "กรุณากรอกข้อมูลให้ครบถ้วน",
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
          }else{
            $(this).css('border-color', '');
          }
        });
      }
    }

    function CancelItem() {
      swal({
        title: "ยกเลิกข้อมูล ?",
        text: "คุณต้องการยกเลิกข้อมูลนี้ใช่หรือไม่...!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "ลบ",
        cancelButtonText: "ยกเลิก",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true}).then(result => {
          var CusCode = $('#CusCode').val();
          var data = {
            'STATUS' : 'CancelItem',
            'CusCode' : CusCode
          }
          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        })
    }

    function Blankinput() {
      $('.checkblank').each(function() {
        $(this).val("");
      });
      $('#CusCode').val("");
      $('#Dept').val("1");
      ShowItem();
    }

    function getdetail(CusCode) {
      if(CusCode!=""&&CusCode!=undefined){
        var data = {
          'STATUS'      : 'getdetail',
          'CusCode'       : CusCode
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function SavePY(){
      $('#TableDocumentSS tbody').empty();
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var datepicker = $('#datepicker').val();
      datepicker = datepicker.substring(6,10)+"-"+datepicker.substring(3,5)+"-"+datepicker.substring(0,2);

      var DocNo = $("#docno").val();
      $("#searchtxt").val( DocNo );

      if(DocNo.length>0){
        swal({
          title: 'บันทึกข้อมูล...',
          text: DocNo,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        })
        var data = {
          'STATUS'      : 'SavePY',
          'DocNo'       : DocNo,
          'DEPT'        : dept,
          'Datepicker'  : datepicker
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function DelItem(){
      var DocNo = $("#docno").val();
      /* declare an checkbox array */
      var chkArray = [];
      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkitemdetail:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray.join(',') ;

      // alert(DocNo + " : " + UsageCode);
      var data = {
        'STATUS'      : 'DelItem',
        'DocNo'       : DocNo,
        'UsageCode'   : UsageCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function canceldocno(docno) {
        swal({
          title: "ลบข้อมูล ?",
          text: "คุณต้องการลบข้อมูลนี้ [ " +docno+ " ] ใช่หรือไม่...!",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "ลบ",
          cancelButtonText: "ยกเลิก",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
              var data = {
                'STATUS'      : 'CancelDocNo',
                'DocNo'       : docno
              };

              console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
              getSearchDocNo();
                })
    }

    function addnum(cnt) {
      var add = parseInt($('#qty'+cnt).val())+1;
      if((add>=0) && (add<=500)){
        $('#qty'+cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#qty'+cnt).val())-1;
      if((sub>=0) && (sub<=500)) {
        $('#qty'+cnt).val(sub);
      }
    }

    function logoff() {
      swal({
        title: '',
        text: 'ออกจากระบบสำเร็จ',
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
       var URL = '../process/customer.php';
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
                         title: 'กรุณารอสักครู่',
                         text: 'ระบบกำลังประมวลผล',
                         allowOutsideClick: false
                       })
                       swal.showLoading();
                     },
                     success: function (result) {
                        try {
                         var temp = $.parseJSON(result);
                        } catch (e) {
                            console.log('Error#542-decode error');
                        }
                        swal.close();
                        if(temp["status"]=='success'){
                          if( (temp["form"]=='ShowItem') ){
                            $( "#TableItem tbody" ).empty();
                            console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                               var rowCount = $('#TableItem >tbody >tr').length;
                               var chkDoc = "<input type='radio' name='checkitem' id='checkitem' value='"+temp[i]['CusCode']+"' onclick='getdetail(\""+temp[i]["CusCode"]+"\")'>";
                               // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                               StrTR = "<tr id='tr"+temp[i]['CusCode']+"'>"+
                                              "<td style='width: 5%;'>"+chkDoc+"</td>"+
                                              "<td style='width: 10%;'>"+(i+1)+"</td>"+
                                              "<td style='width: 15%;'>"+temp[i]['CusCode']+"</td>"+
                                              "<td style='width: 40%;'>"+temp[i]['CusName']+"</td>"+
                                              "<td style='width: 15%;'>"+temp[i]['DepName']+"</td>"+
                                              "<td style='width: 15%;'>"+temp[i]['DiscountPercent']+"</td>"+
                                              "</tr>";

                               if(rowCount == 0){
                                 $("#TableItem tbody").append( StrTR );
                               }else{
                                 $('#TableItem tbody:last-child').append( StrTR );
                               }
                            }
                          }else if( (temp["form"]=='getdetail') ){
                            if((Object.keys(temp).length-2)>0){
                              console.log(temp);
                              $('#CusCode').val(temp['CusCode']);
                              $('#DepCode').val(temp['DepCode']);
                              $('#CusName').val(temp['CusName']);
                              $('#DiscountPercent').val(temp['DiscountPercent']);
                              $('#Price').val(temp['Price']);
                              $('#IsCancel').val(temp['IsCancel']);
                              $('#Address').val(temp['Address']);
                              $('#Post').val(temp['Post']);
                              $('#Tel').val(temp['Tel']);
                              $('#TaxID').val(temp['TaxID']);
                              $('#Dept').val(temp['DepCode']);
                            }
                          }else if( (temp["form"]=='AddItem') ){
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
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              showConfirmButton: false,
                              timer: 2000,
                              confirmButtonText: 'Ok'
                            }).then(function() {

                            }, function(dismiss) {
                              $('.checkblank').each(function() {
                                $(this).val("");
                              });

                              $('#CusCode').val("");
                              $('#Dept').val("1");
                              ShowItem();
                            })
                          }else if( (temp["form"]=='EditItem') ){
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
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              showConfirmButton: false,
                              timer: 2000,
                              confirmButtonText: 'Ok'
                            }).then(function() {

                            }, function(dismiss) {
                              $('.checkblank').each(function() {
                                $(this).val("");
                              });

                              $('#CusCode').val("");
                              $('#Dept').val("1");
                              ShowItem();
                            })
                          }else if( (temp["form"]=='CancelItem') ){
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
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              showConfirmButton: false,
                              timer: 2000,
                              confirmButtonText: 'Ok'
                            }).then(function() {

                            }, function(dismiss) {
                              $('.checkblank').each(function() {
                                $(this).val("");
                              });

                              $('#CusCode').val("");
                              $('#Dept').val("1");
                              ShowItem();
                            })
                          }else if( (temp["form"]=='getSection') ){
                            $("#Dept").empty();
                            $("#Deptsel").empty();
                            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                              var StrTr = "<option value = '"+temp[i]['DepCode']+"'> " + temp[i]['DepName'] + " </option>";
                              $("#Dept").append(StrTr);
                              $("#Deptsel").append(StrTr);
                            }

                          }else if( (temp["form"]=='getHospital') ){
                            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                              var StrTr = "<option value = '"+temp[i]['HptCode']+"'> " + temp[i]['HptName'] + " </option>";
                              $("#Hosp").append(StrTr);
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

                        }else if(temp['status']=="notfound"){
                          // swal({
                          //   title: '',
                          //   text: temp['msg'],
                          //   type: 'info',
                          //   showCancelButton: false,
                          //   confirmButtonColor: '#3085d6',
                          //   cancelButtonColor: '#d33',
                          //   showConfirmButton: false,
                          //   timer: 2000,
                          //   confirmButtonText: 'Ok'
                          // })
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
                     }
          });
    }

    </script>
    <style media="screen">
    body{
		   font-family: 'THSarabunNew';
		   font-size:22px;
		}
    input,select{
      font-size:24px!important;
    }
    th,td{
      font-size:24px!important;
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
    button{
      font-size: 24px!important;
    }
      a.nav-link{
        width:auto!important;
      }
      .datepicker{z-index:9999 !important}
      .hidden{visibility: hidden;}
    </style>
  </head>

  <body id="page-top">
    <div id="wrapper">
      <!-- content-wrapper -->
      <div id="content-wrapper">
<!--
          <div class="mycheckbox">
            <input type="checkbox" name='useful' id='useful' onclick='setTag()'/><label for='useful' style='color:#FFFFFF'> </label>
          </div>
-->
          <div class="row">
              <div class="col-md-12"> <!-- tag column 1 -->
                  <div class="container-fluid">
                    <div class="card-body" style="padding:0px; margin-top:-12px;">
                        <div class="row">
                        			  <div class="col-md-4">
                                        <div class="row" style="margin-left:5px;">
                        						<select class="form-control" id="Deptsel">
                                                </select>
                        				</div>
                                      </div>
                                      <div class="col-md-5">
                                        <div class="row" style="margin-left:5px;">
                                          <input type="text" class="form-control" style="width:70%;" name="searchitem" id="searchitem" placeholder="ค้นหารายการ" >
                                          <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem();">ค้นหา</button>
                                        </div>
                                      </div>
                                      <div class="col-md-2">

                                      </div>
                        </div>
                        <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                          <thead id="theadsum" style="font-size:11px;">
                            <tr role="row">
                              <th style='width: 5%;'>&nbsp;</th>
                              <th style='width: 10%;'>ลำดับ</th>
                              <th style='width: 15%;'>รหัสลูกค้า</th>
                              <th style='width: 40%;'>ชื่อลูกค้า</th>
                              <th style='width: 15%;'>แผนก</th>
                              <th style='width: 15%;'>ส่วนลด</th>
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
    <div class="row">
              <div class="col-md-10"> <!-- tag column 1 -->
                  <div class="container-fluid">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">รายละเอียด</a>
                      </li>
                    </ul>
                    <div class="card-body" style="padding:0px; margin-top:10px;">
                        <div class="row">
                                      <div style="margin-left:50px;width:100px;">
												<label>รหัส</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control " style="width:300px;" name="CusCode" id="CusCode" placeholder="รหัส" readonly>
                                      </div>
                                      <div style="margin-left:50px;width:120px;">
												<label>ราคา</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control checkblank numonly" style="width:300px;" name="Price" id="Price" placeholder="ราคา" >
                                      </div>

                        </div>
                        <div class="row" style="margin-top:5px;">
                                      <div style="margin-left:50px;width:100px;">
												<label>ชื่อ</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control checkblank charonly" style="width:300px;" name="CusName" id="CusName" placeholder="ชื่อ" >
                                      </div>
                                      <div style="margin-left:50px;width:120px;">
												<label>ที่อยู่</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control checkblank" style="width:450px;" name="Address" id="Address" placeholder="ที่อยู่" >
                                      </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                                      <div style="margin-left:50px;width:100px;">
												<label>โรงพยาบาล</label>
                                      </div>
                                      <div style="width:300px;">
												<select class="form-control" id="Hosp">
                                                </select>
                                      </div>
                                      <div style="margin-left:50px;width:120px;">
												<label>รหัสไปรษณีย์</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control checkblank numonly" style="width:300px;" name="Post" id="Post" placeholder="รหัสไปรษณีย์" >
                                      </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                                      <div style="margin-left:50px;width:100px;">
                                  <label>แผนก</label>
                                                </div>
                                                <div style="width:300px;">
          												<select class="form-control" id="Dept">
                                                          </select>
                                                </div>
                                      <div style="margin-left:50px;width:120px;">
												<label>เลขที่ผู้เสียภาษี</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control checkblank numonly" style="width:300px;" name="TaxID" id="TaxID" placeholder="เลขที่ผู้เสียภาษี" >
                                      </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                                      <div style="margin-left:50px;width:100px;">
												<label>ส่วนลด</label>
                                      </div>
                                      <div style="width:300px;">
 <input type="text" class="form-control checkblank numonly" style="width:300px;" name="DiscountPercent" id="DiscountPercent" placeholder="ส่วนลด" >
                                      </div>
                        </div>
                    </div>
                  </div>
              </div> <!-- tag column 1 -->


                  <div class="col-md-1"> <!-- tag column 3 -->
                  <div class="container-fluid">
                    <div class="card-body" style="padding:0px; margin-top:50px;">
                        <div class="row" style="margin-top:5px;">
                                      <div class="col-md-1">
                                        <div class="row" style="margin-left:5px;">
											<div class="row" style="margin-left:30px;">
                                          		<button style="width:105px"; type="button" class="btn btn-success" onclick="AddItem()">บันทึก</button>
                                        	</div>
                                        </div>
                                      </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                                      <div class="col-md-1">
                                        <div class="row" style="margin-left:5px;">
											<div class="row" style="margin-left:30px;">
                                          		<button style="width:105px"; type="button" class="btn btn-info" onclick="Blankinput()">ล้าง</button>
                                        	</div>
                                        </div>
                                      </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                                      <div class="col-md-1">
                                        <div class="row" style="margin-left:5px;">
											<div class="row" style="margin-left:30px;">
                                          		<button style="width:105px"; type="button" class="btn btn-danger" onclick="CancelItem()">ยกเลิก</button>
                                        	</div>
                                        </div>
                                      </div>
                        </div>
                    </div>
                  </div>
              </div> <!-- tag column 3 -->
    </div>


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

  </body>

</html>
