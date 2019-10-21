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

    <title><?php echo $array['titlefactory'][$language]; ?></title>

    <link rel="icon" type="image/png" href="../img/pose_favicon.png">
    <!-- Bootstrap core CSS-->
    <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/tbody.css" rel="stylesheet">
    <!-- <link href="../bootstrap/css/myinput.css" rel="stylesheet"> -->

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

    <script type="text/javascript">
      var summary = [];

      $(document).ready(function(e){
        $('#rem1').hide();
        $('#rem2').hide();
        $('#rem3').hide();
        $('#rem4').hide();
        $('#rem5').hide();
        $('#rem6').hide();
        $('#rem7').hide();
        $('#rem8').hide();
        $('#rem9').hide();
        $('.numonly').on('input', function() {
          this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
        });
        $('.charonly').on('input', function() {
          this.value = this.value.replace(/[^a-zA-Z0-9. ]/g, ''); //<-- replace all other than given set of values
        });
        $('.charonlyTH').on('input', function() {
          this.value = this.value.replace(/[^ก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ0-9. ]/g, ''); //<-- replace all other than given set of values
        });
        getFactory();
        $('#addhot').show();
       $('#adduser').hide();
        //On create
        Blankinput();
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

        // var data2 = {
        //   'STATUS'  : 'getSection',
        //   'DEPT'    : dept
        // };
        // console.log(JSON.stringify(data2));
        // senddata(JSON.stringify(data2));

        $('#searchitem').keyup(function(e){
            if(e.keyCode == 13)
            {
                ShowItem();
            }
        });

        $('.editable').click(function() {
          alert('hi');
        });
      }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
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
      function checkblank2(){
          $('.checkblank2').each(function() {
            if($(this).val()==""||$(this).val()==undefined){
              $(this).addClass('border-danger');
            }else{
              $(this).removeClass('border-danger');
            }
          });
        }
        function removeClassBorder1(){
          $('#host').removeClass('border-danger');
        }

      function unCheckDocDetail(){
          // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
          if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length){
              $('input[name="checkAllDetail').prop('checked',true);
          }else {
              $('input[name="checkAllDetail').prop('checked',false);
          }
      }
 function getFactory(){
  var data2 = {
              'STATUS': 'getFactory'
          };
          // console.log(JSON.stringify(data2));
          senddata(JSON.stringify(data2));
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

        var FacCode = $('#FacCodeReal').val();
        var FacName = $('#FacName').val();
        var FacNameTH = $('#FacNameTH').val();
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
            }
          });
          if(FacCode==""){
            swal({
              title: "<?php echo $array['adddata'][$language]; ?>",
              text: "<?php echo $array['adddata1'][$language]; ?>",
              type: "question",
              showCancelButton: true,
              confirmButtonClass: "btn-success",
              confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
              cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
              confirmButtonColor: '#6fc864',
              cancelButtonColor: '#3085d6',
              closeOnConfirm: false,
              closeOnCancel: false,
              showCancelButton: true}).then(result => {
                if (result.value) {

                var data = {
                  'STATUS' : 'AddItem',
                  'FacCode' : FacCode,
                  'FacName' : FacName,
                  'FacNameTH' : FacNameTH,
                  'Price' : Price,
                  'Address' : Address,
                  //'DepCode' : Dept,
                  'Post' : Post,
                  'DiscountPercent' : DiscountPercent,
                  'TaxID' : TaxID
                };

                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
              } else if (result.dismiss === 'cancel') {
            swal.close();
          }
              })

          }else{
            swal({
              title: "<?php echo $array['editdata'][$language] ?>",
              text: "<?php echo $array['editdata1'][$language] ?>",
              type: "question",
              showCancelButton: true,
              confirmButtonClass: "btn-warning",
              confirmButtonText: "<?php echo $array['yes'][$language] ?>",
              cancelButtonText: "<?php  echo $array['isno'][$language] ?>",
              confirmButtonColor: '#6fc864',
              cancelButtonColor: '#3085d6',
              closeOnConfirm: false,
              closeOnCancel: false,
              showCancelButton: true}).then(result => {
                if (result.value) {

                var data = {
                  'STATUS' : 'EditItem',
                  'FacCode' : FacCode,
                  'FacName' : FacName,
                  'FacNameTH' : FacNameTH,
                  'Price' : Price,
                  'Address' : Address,
                  //'DepCode' : Dept,
                  'Post' : Post,
                  'DiscountPercent' : DiscountPercent,
                  'TaxID' : TaxID
                };

                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
              } else if (result.dismiss === 'cancel') {
            swal.close();
          }
              })

          }
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
              if(Price ==""||Price==undefined){
                  $('#rem1').show().css("color","red");
                }
                if(FacName ==""||FacName==undefined){
                  $('#rem2').show().css("color","red");
                }
                if(Address ==""||Address==undefined){
                  $('#rem3').show().css("color","red");
                }
                if(TaxID ==""||TaxID==undefined){
                  $('#rem4').show().css("color","red");
                }
                if(Post ==""||Post==undefined){
                  $('#rem5').show().css("color","red");
                }
                if(FacNameTH ==""||FacNameTH==undefined){
                  $('#rem9').show().css("color","red");
                }
            }
          });
        }
      }
      function Adduser(){
        var count = 0;
        var idcontract = $('#idcontract').val();
        var ContractName = $('#ContractName').val();
        var Position = $('#Position').val();
        var phone = $('#phone').val();
        var host = $('#host').val();


        $(".checkblank3").each(function() {
          if($( this ).val()==""||$(this).val()==undefined){
            count++;
          }
        });

          if(count==0){
            swal({
              title: "<?php echo $array['addoredit'][$language]; ?>",
              text: "<?php echo $array['addoredit'][$language]; ?>",
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

                var data = {
                  'STATUS' : 'Adduser',
                  'ContractName' : ContractName,
                  'Position' : Position,
                  'phone' : phone,
                  'idcontract' : idcontract,
                  'host' : host
                };

                Blankinput();
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
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
          $('.checkblank3').each(function() {
            if($(this).val()==""||$(this).val()==undefined){
              $(this).css('border-color', 'red');
              if(ContractName ==""||ContractName==undefined){
                  $('#rem6').show().css("color","red");
                }
                if(Position ==""||Position==undefined){
                  $('#rem7').show().css("color","red");
                }
                if(phone ==""||phone==undefined){
                  $('#rem8').show().css("color","red");
                }
            }else{
              $(this).css('border-color', '');
            }
          });
        }
      }
    
     function CancelItem() {
        swal({
          title: "<?php echo $array['canceldata'][$language]; ?>",
          text: "<?php echo $array['canceldata1'][$language]; ?>",
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
            var idcontract = $('#idcontract').val();
            var FacCode = $('#FacCodeReal').val();
            var data = {
              'STATUS' : 'CancelItem',
              'FacCode' : FacCode,
              'idcontract' : idcontract 
            }
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }    
          })
      }

      function resetinput(){

        var ContractName = $('#ContractName').val();
        var Position = $('#Position').val();
        var phone = $('#phone').val();
        var FacCode = $('#FacCode').val();
        var FacName = $('#FacName').val();
        var FacNameTH = $('#FacNameTH').val();
        var Price = $('#Price').val();
        var Address = $('#Address').val();
        var Dept = $('#Dept').val();
        var Post = $('#Post').val();
        var DiscountPercent = $('#DiscountPercent').val();
        var TaxID = $('#TaxID').val();

              if(Price !="" && Price!=undefined){
                  $('#rem1').hide();
                  $('#Price').css('border-color', '');
                }
                if(FacName !="" && FacName!=undefined){
                  $('#rem2').hide();
                  $('#FacName').css('border-color', '');
                }
                if(Address !="" && Address !=undefined){
                  $('#rem3').hide();
                  $('#Address').css('border-color', '');
                  }
                if(TaxID !="" && TaxID!=undefined){
                  $('#rem4').hide();
                  $('#TaxID').css('border-color', '');
                }
                if(Post !="" && Post!=undefined){
                  $('#rem5').hide();
                  $('#Post').css('border-color', '');
                }
                if(ContractName !="" && ContractName!=undefined){
                  $('#rem6').hide();
                  $('#ContractName').css('border-color', '');
                }
                if(Position !="" && Position!=undefined){
                  $('#rem7').hide();
                  $('#Position').css('border-color', '');
                }
                if(phone !="" && phone!=undefined){
                  $('#rem8').hide();
                  $('#phone').css('border-color', '');
                }
                if(FacNameTH !="" && FacNameTH!=undefined){
                  $('#rem9').hide();
                  $('#FacNameTH').css('border-color', '');
                }
            
      }

      function Blankinput() {
        $('#profile-tab').attr('hidden',true);
        $('#home-tab').click();
        $('#rem1').hide();
        $('#rem2').hide();
        $('#rem3').hide();
        $('#rem4').hide();
        $('#rem5').hide();
        $('#rem6').hide();
        $('#rem7').hide();
        $('#rem8').hide();
        $('.checkblank').each(function() {
          $(this).val("");
        });
        $('.checkblank').each(function() {
            if($(this).val()==""||$(this).val()==undefined){
              $(this).css('border-color', '');
            }else{
              $(this).css('border-color', '');
            }
          });
          $('.checkblank3').each(function() {
            if($(this).val()==""||$(this).val()==undefined){
              $(this).css('border-color', '');
            }else{
              $(this).css('border-color', '');
            }
          });
          $('#DiscountPercent').val("");
        $('#ContractName').val("");
        $('#Position').val("");
        $('#phone').val("");
        $('#host').val("");
        $('#FacCode').val("");
        $('#FacCodeReal').val("");
        
        ShowItem();
        $('#bCancel').attr('disabled', true);
    $('#delete_icon').addClass('opacity');
    $('#delete1').removeClass('mhee');
      }

      function getdetail(FacCode , row) {
        
        $('#profile-tab').attr('hidden',false);
        var number = parseInt(row)+1;
        var id = $('#id_'+row).data('value');
        var previousValue = $('#checkitem_'+row).attr('previousValue');
        var name = $('#checkitem_'+row).attr('name');
        if (previousValue == 'checked') {
          $('#profile-tab').attr('hidden',true);
          $('#home-tab').click();
          $('#checkitem_'+row).removeAttr('checked');
          $('#checkitem_'+row).attr('previousValue', false);
          $('#checkitem_'+row).prop('checked', false);
          Blankinput();
        } else {
          $("input[name="+name+"]:radio").attr('previousValue', false);
          $('#checkitem_'+row).attr('previousValue', 'checked');
        if(FacCode!=""&&FacCode!=undefined){
          var data = {
            'STATUS'        : 'getdetail',
            'FacCode'       : FacCode , 
            'id'            : id ,
            'number'        : number 
          };

          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        }
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
            title: '<?php echo $array['savesuccess'][$language]; ?>',
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
      function menu_tapShow(){
        $('#addhot').show();
        $('#adduser').hide();    
    }
    function menu_tapHide(){
      $('#addhot').hide();
        $('#adduser').show();  
    }
      function canceldocno(docno) {
          swal({
            title: "<?php echo $array['canceldata'][$language]; ?>",
            text: "<?php echo $array['canceldata2'][$language]; ?> " +docno+ "<?php echo $array['canceldata3'][$language]; ?>",
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
         var URL = '../process/factory.php';
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
                           title: '<?php echo $array['pleasewait'][$language]; ?>',
                           text: '<?php echo $array['processing'][$language]; ?>',
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
                                 var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem' id='checkitem_"+i+"' value='"+temp[i]['FacCode']+"' onclick='getdetail(\""+temp[i]["FacCode"]+"\" , \""+i+"\")'><span class='checkmark'></span></label>";
                                 // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                 StrTR = "<tr id='tr"+temp[i]['FacCode']+"'>"+
                                                "<td style='width: 5%;'>"+chkDoc+"</td>"+
                                                "<td nowrap style='width: 10%;'>"+(i+1)+"</td>"+
                                                "<td nowrap style=' text-overflow: ellipsis;overflow: hidden; width: 16%;' title='"+temp[i]['FacName']+"'>"+temp[i]['FacName']+"</td>"+
                                                "<td nowrap style=' text-overflow: ellipsis;overflow: hidden; width: 18%;' title='"+temp[i]['FacNameTH']+"'>"+temp[i]['FacNameTH']+"</td>"+
                                                "<td nowrap style=' text-overflow: ellipsis;overflow: hidden;width: 11%;'  title='"+temp[i]['DiscountPercent']+"'>"+temp[i]['DiscountPercent']+"</td>"+
                                                "<td nowrap style=' text-overflow: ellipsis;overflow: hidden;width: 14%;'  title='"+temp[i]['contractName']+"'>"+temp[i]['contractName']+"</td>"+
                                                "<td nowrap style=' text-overflow: ellipsis;overflow: hidden;width: 13%;'  title='"+temp[i]['permission']+"'>"+temp[i]['permission']+"</td>"+
                                                "<td nowrap style=' text-overflow: ellipsis;overflow: hidden;width: 11%;'  title='"+temp[i]['Number']+"'>"+temp[i]['Number']+"</td>"+
                                                "<td nowrap style='width: 13%;' hidden id='id_"+i+"' data-value='"+temp[i]['id']+"'></td>"+
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
                                $('#host').val(temp['FacCodeReal']);
                                $('#FacCodeReal').val(temp['FacCodeReal']);
                                $('#FacCode').val(temp['FacCode']);
                                $('#DepCode').val(temp['DepCode']);
                                $('#FacName').val(temp['FacName']);
                                $('#FacNameTH').val(temp['FacNameTH']);
                                $('#DiscountPercent').val(temp['DiscountPercent']);
                                $('#Price').val(temp['Price']);
                                $('#IsCancel').val(temp['IsCancel']);
                                $('#Address').val(temp['Address']);
                                $('#Post').val(temp['Post']);
                                $('#Tel').val(temp['Tel']);
                                $('#TaxID').val(temp['TaxID']);
                                $('#ContractName').val(temp['contractName']);
                                $('#Position').val(temp['permission']);
                                $('#phone').val(temp['Number']);
                                $('#idcontract').val(temp['id']);
                                //$('#Dept').val(temp['DepCode']);
                              }
                              $('#bCancel').attr('disabled', false);
                              $('#delete_icon').removeClass('opacity');
                              $('#delete1').addClass('mhee');

                            }else if ((temp["form"] == 'getFactory')) {
                              $("#host").empty();
                              var StrTr = "<option value='' selected>-</option>";
                              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                   StrTr += "<option value = '" + temp[i]['FacCode'] + "'> " + temp[i]['FacName'] + " </option>";
                              }
                              $("#host").append(StrTr);
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
                                ShowItem();
                                getFactory();
                                Blankinput();
                                $('#profile-tab').attr('hidden',true);
                                $('#home-tab').click();
                              }, function(dismiss) {
                                $('.checkblank').each(function() {
                                  $(this).css('border-color', '');
                                });

                                $('#FacCode').val("");
                                //$('#Dept').val("1");
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
                                ShowItem();
                                Blankinput();

                              }, function(dismiss) {
                                $('.checkblank').each(function() {
                                  $(this).val("");
                                });

                                $('#FacCode').val("");
                                //$('#Dept').val("1");
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
                                ShowItem();
                                Blankinput();
                                getFactory();
                              }, function(dismiss) {
                                $('.checkblank').each(function() {
                                  $(this).val("");
                                });

                                $('#FacCode').val("");
                                //$('#Dept').val("1");
                                ShowItem();
                              })
                            }else if( (temp["form"]=='getSection') ){
                              for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                                var StrTr = "<option value = '"+temp[i]['DepCode']+"'> " + temp[i]['DepName'] + " </option>";
                                $("#Dept").append(StrTr);
                                $("#Deptsel").append(StrTr);
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
                                case "adduserfacfailed":
                                temp['msg'] = "<?php echo $array['adduserfacfailed'][$language]; ?>";
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
    @font-face {
            font-family: myFirstFont;
            src: url("../fonts/DB Helvethaica X.ttf");
            }
        body{
          font-family: myFirstFont;
          font-size:22px;
        }
label{
  margin-bottom: 0rem !important;
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
  button{
      font-size: 24px!important;
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
  font-size: 25px;
  color: #2c3e50;
  background:none;
  box-shadow:none!important;
}

.mhee button:hover {
  color: #2c3e50;
  font-weight:bold;
  font-size:26px;
  outline:none;
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
    padding-left: 33px;
  }
  .opacity{
    opacity:0.5;
  }
  @media (min-width: 992px) and (max-width: 1199.98px) { 

    .icon{
      padding-top: 6px;
      padding-left: 23px;
    }
    .sidenav{
      margin-left:30px;
    }
    .sidenav a {
      font-size: 20px;

    }
  }
    </style>
  </head>

  <body id="page-top">
  <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
          <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][0][$language]; ?></li>
        </ol>
    <div id="wrapper">
    <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
      <i class="fas fa-angle-down"></i>
    </a>
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
                            <div class="col-md-9">
                              <div class="row" style="margin-left:5px;">
                                <input type="text" autocomplete="off" class="form-control" style="width:35%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['SearchLaundry'][$language]; ?>" >
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
                              <th nowrap style='width: 5%;text-overflow: ellipsis;overflow: hidden;'  title>&nbsp;</th>
                              <th nowrap style='width: 10%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['no'][$language]; ?>"><?php echo $array['no'][$language]; ?></th>
                              <th nowrap style='width: 16%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['facname'][$language]; ?>"><?php echo $array['facname'][$language]; ?></th>
                              <th nowrap style='width: 18%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['facnameTH'][$language]; ?>"><?php echo $array['facnameTH'][$language]; ?></th>
                              <th nowrap style='width: 11%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['discount'][$language]; ?>"><?php echo $array['discount'][$language]; ?></th>
                              <th nowrap style='width: 14%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['ContractName'][$language]; ?>"><?php echo $array['ContractName'][$language]; ?></th>
                              <th nowrap style='width: 13%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['Position'][$language]; ?>"><?php echo $array['Position'][$language]; ?></th>
                              <th nowrap style='width: 13%;text-overflow: ellipsis;overflow: hidden;' title="<?php echo $array['phone'][$language]; ?>"><?php echo $array['phone'][$language]; ?></th>

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
                          <div class="menu mhee"  id="addhot" <?php if($PmID == 3 || $PmID == 7) echo 'hidden'; ?>>
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
                          <div class="menu mhee" id="adduser" <?php if($PmID == 3 || $PmID == 7) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle4 d-flex justify-content-center">
                                <button class="btn"  onclick="Adduser()" id="bSave">
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
                              <div class="circle6 d-flex justify-content-center ">
                                <button class="btn" onclick="Blankinput()" id="bDelete">
                                  <i class="fas fa-redo-alt"></i>
                                  <div>
                                    <?php echo $array['clear'][$language]; ?>
                                  </div>       
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu " id="delete1" <?php if($PmID == 3 || $PmID == 7) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center" >
                              <div class="circle3 d-flex justify-content-center " id="delete_icon">
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

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" onclick="menu_tapShow();" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" onclick="menu_tapHide();" hidden data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['adduser'][$language]; ?></a>
                      </li>
                  </ul>
<!-- =============================================================================================================================== -->
<div class="tab-content" id="myTabContent">
  <div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="row">
                        <div class="col-md-12"> <!-- tag column 1 -->
                            <div class="container-fluid">
                              <div class="card-body" style="padding:0px; margin-top:10px;">

                                <div class="row mt-4">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['no'][$language]; ?></label>
                                      <input type="text"  autocomplete="off"  class="form-control col-sm-7" id="FacCode"  <?php echo $array['faccode'][$language]; ?> readonly placeholder="<?php echo $array['no'][$language]; ?>">
                                      <input type="text" hidden class="form-control col-sm-7" id="FacCodeReal"  <?php echo $array['faccode'][$language]; ?> readonly>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['price'][$language]; ?></label>
                                        <input type="text" autocomplete="off" onkeyup="resetinput()"  class="form-control col-sm-7 checkblank numonly" id="Price"  placeholder="<?php echo $array['price'][$language]; ?>" >
                                        <label id="rem1" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                </div>          
     <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['facname'][$language]; ?></label>
                                      <input type="text"  autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank charonly " id="FacName"  <?php echo $array['facname'][$language]; ?>  placeholder="<?php echo $array['facname'][$language]; ?>">
                                      <label id="rem2" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['address'][$language]; ?></label>
                                        <input type="text"  autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank " id="Address"  placeholder="<?php echo $array['address'][$language]; ?>" >
                                        <label id="rem3" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                </div>               
     <!-- =================================================================== -->
                                <div class="row">
                                 <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['facnameTH'][$language]; ?></label>
                                      <input type="text"  autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank charonlyTH" id="FacNameTH"  <?php echo $array['facnameTH'][$language]; ?>  placeholder="<?php echo $array['facnameTH'][$language]; ?>">
                                      <label id="rem9" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['taxid'][$language]; ?></label>
                                      <input type="text"  autocomplete="off"  onkeyup="resetinput()" class="form-control col-sm-7 checkblank numonly" id="TaxID"  <?php echo $array['taxid'][$language]; ?>  placeholder="<?php echo $array['taxid'][$language]; ?>">
                                      <label id="rem4" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                </div> 
  <!-- =================================================================== -->
                                <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['postid'][$language]; ?></label>
                                        <input type="text"  autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank numonly" id="Post"  placeholder="<?php echo $array['postid'][$language]; ?>" >
                                        <label id="rem5" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3  col-form-label "><?php echo $array['discount'][$language]; ?></label>
                                      <input type="text" autocomplete="off"  class="form-control col-sm-7  numonly" id="DiscountPercent"  <?php echo $array['taxid'][$language]; ?>  placeholder="<?php echo $array['discount'][$language]; ?>">
                                    </div>
                                  </div>
                                </div>               
<!-- =================================================================== -->
                    </div>
                  </div>
              </div> <!-- tag column 1 -->
            </div>
          </div>

<!-- =============================================================================================== -->
<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
    <!-- /.content-wrapper -->
            <div class="row  m-2">
              <div class="col-md-12"> <!-- tag column 1 -->
                  <div class="container-fluid">
                    <div class="card-body" style="padding:0px;">
                                <div class="row mt-4">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['ContractName'][$language]; ?></label>
                                      <input type="text" onkeyup="resetinput()"  class="form-control col-sm-7 checkblank3" id="ContractName"    placeholder="<?php echo $array['ContractName'][$language]; ?>">
                                      <label id="rem6" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['Position'][$language]; ?></label>
                                        <input type="text"  onkeyup="resetinput()" class="form-control col-sm-7 checkblank3" id="Position"  placeholder="<?php echo $array['Position'][$language]; ?>" >
                                        <label id="rem7" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                </div> 
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['phone'][$language]; ?></label>
                                      <input type="text" onkeyup="resetinput()"  class="form-control col-sm-7 numonly checkblank3" id="phone"placeholder="<?php echo $array['phone'][$language]; ?>">
                                      <label id="rem8" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6" hidden >
                                    <div class='form-group row'>
                                    <label class="col-sm-4 col-form-label text-right"><?php echo $array['factory'][$language]; ?></label>
                                      <select  class="form-control col-sm-7 checkblank2 checkblank3" id="host"onchange="removeClassBorder1();"></select>
                                    </div>
                                  </div>
                                  <div class="col-md-6" hidden>
                                    <div class='form-group row'>
                                        <input type="text" class="form-control col-sm-7 " id="idcontract">
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div> <!-- tag column 2 -->
                      </div>
                    </div>
                  </div>
<div id="page-down">
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
