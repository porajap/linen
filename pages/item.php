<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  // header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['item'][$language]; ?></title>

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
      $('#delete_icon').addClass('opacity');
      $('#delete1').removeClass('mhee');
        $('#rem1').hide();
        $('#rem2').hide();
        $('#rem3').hide();
        $('#rem4').hide();
        $('#rem5').hide();
        $('#rem6').hide();
        
      $('#NewItem').show();
      $('#BlankItemBNT').show();
      $('#ActiveBNT').hide();
      $('#AddItemBNT').hide();
      $('#xPrice').hide();
      $('#searchitem').keyup(function(e){
            if(e.keyCode == 13)
            {
                ShowItem();
            }
        });
      ShowItem();
      GetHospital();
      GetmainCat();
      getCatagory();
      //On create
      $('.TagImage').bind('click', {
        imgId: $(this).attr('id')
      }, function(evt) {
        alert(evt.imgId);
      });
      //On create
      var userid = '<?php echo $Userid; ?>';
      if (userid != "" && userid != null && userid != undefined) {
        var dept = '<?php echo $_SESSION['Deptid']; ?>';
        var data = {
          'STATUS': 'getDocument',
          'DEPT': dept
        };

        // console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }

      var data = {
        'STATUS': 'getUnit'
      };
      // console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));

      $('#UnitName').on('change', function() {
        $('#Unitshows').val(this.value);
      });

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });

      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
      });

			$('.activeSort').click(function () {
        $("a").removeClass("white");
        $(this).attr("class", "white");
      });

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });
   
		//<!-- --------------------Function--------------------- --!>
    dialog = jqui("#dialog").dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "ปิด": function() {
          dialog.dialog("close");
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui("#dialogreq").button().on("click", function() {
      dialog.dialog("open");
    });

    function changeHptCode(){
      // Blankinput();
      ShowItem();
      $('#Hos2').css('border-color', '');
      var Hos2 = $('#Hos2').val();
      $('#hospital').val(Hos2);
         CreateItemCode();

    }

    function unCheckDocDetail() {
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length) {
        $('input[name="checkAllDetail').prop('checked', true);
      } else {
        $('input[name="checkAllDetail').prop('checked', false);
      }
    }

    function getDocDetail() {
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocno"]:checked').length == $('input[name="checkdocno"]').length) {
        $('input[name="checkAllDoc').prop('checked', true);
      } else {
        $('input[name="checkAllDoc').prop('checked', false);
      }

      /* declare an checkbox array */
      var chkArray = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocno:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var DocNo = chkArray.join(',');
      // alert( DocNo );
      $('#TableDetail tbody').empty();
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'getDocDetail',
        'DEPT': dept,
        'DocNo': DocNo
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    var isChecked1 = false;
    var isChecked2 = false;

    function getCheckAll(sel) {
      if (sel == 0) {
        isChecked1 = !isChecked1;
        // $( "div #aa" )
        //   .text( "For this isChecked " + isChecked1 + "." )
        //   .css( "color", "red" );

        $('input[name="checkdocno"]').each(function() {
          this.checked = isChecked1;
        });
        getDocDetail();
      } else {
        isChecked2 = !isChecked2;
        $('input[name="checkdocdetail"]').each(function() {
          this.checked = isChecked2;
        });
      }
    }

    function getSearchDocNo() {
      var dept = '<?php echo $_SESSION['Deptid']; ?>';

      $('#TableDocumentSS tbody').empty();
      var str = $('#searchtxt').val();
      var datepicker = $('#datepicker').val();
      datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

      var data = {
        'STATUS': 'getSearchDocNo',
        'DEPT': dept,
        'DocNo': str,
        'Datepicker': datepicker
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
      var DocNo = chkArray1.join(',');

      /* declare an checkbox array */
      var chkArray2 = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocdetail:checked").each(function() {
        chkArray2.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray2.join(',');
      var data = {
        'STATUS': 'CreateSentSterile',
        'DEPT': dept,
        'DocNo': DocNo,
        'UsageCode': UsageCode,
        'userid': userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function setTag() {
      var DocNo = $("#docnofield").val();
      /* declare an checkbox array */
      var chkArray = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#IsTag:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray.join(',');
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'SSDTag',
        'DEPT': dept,
        'userid': userid,
        'DocNo': DocNo,
        'UsageCode': UsageCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function CreatePayout() {
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'CreatePayout',
        'DEPT': dept,
        'userid': userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddPayoutDetail() {
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'CreatePayout',
        'DEPT': dept,
        'userid': userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function GetmainCat() {
      var maincatagory = $("#maincatagory").val();
      var data = {
        'STATUS': 'GetmainCat',
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function getCatagory() {
      var maincatagory = $('#maincatagory option:selected').attr("value");
      if (typeof maincatagory == 'undefined') maincatagory = "1";
      // $('#maincatagory2').val(maincatagory);
      console.log($('#maincatagory2 option:selected').attr("value"));
      // var catagory1 = $("#catagory1").val();
      var data = {
        'STATUS': 'getCatagory',
        'maincatagory': maincatagory

      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function GetHospital() {
      var lang = '<?php echo $language; ?>';
      var data = {
        'STATUS': 'GetHospital',
        'lang'	: lang
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function getCatagory2() {
      var maincatagory = $('#maincatagory2 option:selected').attr("value");
      if (typeof maincatagory == 'undefined') maincatagory = "1";
      $('#maincatagory').val(maincatagory);
      var catagory1 = $("#catagory1").val();
      var data = {
        'STATUS': 'getCatagory',
        'maincatagory': maincatagory

      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }


    function ShowItem(column, sort) {
      // var count = 0;
      // $(".checkblank66").each(function() {
      //   if ($(this).val() == "" || $(this).val() == undefined) {
      //     count++;
      //   }
      // });
      // if (count != 0) {
      //   $('.checkblank66').each(function() {
      //     if ($(this).val() == "" || $(this).val() == undefined) {
      //       $(this).css('border-color', 'red');
      //     } else {
      //       $(this).css('border-color', '');
      //     }
      //   });
      // }else{
      var maincatagory = $("#maincatagory").val();
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      var Hos2 = $("#Hos2").val();
      var active = '0';
      var data = {
        'STATUS': 'ShowItem',
        'Catagory': catagory,
        'Keyword': item,
        'active': active,
        'column': column,
        'sort': sort,
        'maincatagory': maincatagory,
        'HptCode': Hos2
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
      // }
    }
    function ShowItem2(mItemCode) {
      var maincatagory = $("#maincatagory").val();
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      var active = '0';
      var data = {
        'STATUS': 'ShowItem2',
        'Catagory': catagory,
        'Keyword': item,
        'mItemCode': mItemCode,
        'maincatagory': maincatagory
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }
    function ShowItemMaster(column, sort) {
      $("#TableItemMaster tbody").empty();
      var maincatagory = $("#maincatagory").val();
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      var active = '0';
      var data = {
        'STATUS': 'ShowItemMaster',
        'Catagory': catagory,
        'Keyword': item,
        'active': active,
        'column': column,
        'sort': sort,
        'maincatagory': maincatagory
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }
    function ShowItemMaster2(ItemCode) {
      var maincatagory = $("#maincatagory").val();
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      var data = {
        'STATUS': 'ShowItemMaster2',
        'Catagory': catagory,
        'Keyword': item,
        'ItemCode': ItemCode,
        'maincatagory': maincatagory
      };
      senddata(JSON.stringify(data));
    }

    function ShowItem_Active_0() {
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      // alert(item);
      var active = '0';
      var data = {
        'STATUS': 'ShowItem_Active_0',
        'Catagory': catagory,
        'Keyword': item,
        'active': active
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddItem() {
      var count = 0;
      $(".checkblank").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });
      console.log(count);

      var Catagory = $('#catagory2').val();
      var ItemCode = $('#ItemCode').val();
      var ItemName = $('#ItemName').val();
      var CusPrice = $('#CusPrice').val();
      var FacPrice = $('#FacPrice').val();
      var hospital = $('#hospital').val();
      var UnitName = $('#UnitName').val();
      var SizeCode = $('#SizeCode').val();
      var Weight = $('#Weight').val();
      var qpu = $('#QtyPerUnit').val();
      var sUnit = $('#sUnitName').val();
      var xCenter = 0;
      var xItemnew = 0;
      var tdas = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xItemnew').is(':checked')) xItemnew = 1;
      if ($('#tdas').is(':checked')) tdas = 1;
      if ($('#masterItem').is(':checked')) {
        masterItem = 1;
      }else{
        masterItem = 0;
      }
      if (count == 0) {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
        if (ItemCode != "") {
          swal({
            title: "<?php echo $array['addoredit'][$language]; ?>",
            text: "<?php echo $array['addoredit1'][$language]; ?>",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
            cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true
          }).then(result => {
            if (result.value) {
              var data = {
                'STATUS': 'AddItem',
                'Catagory': Catagory,
                'ItemCode': ItemCode,
                'ItemName': ItemName,
                'CusPrice': CusPrice,
                'FacPrice': FacPrice,
                'UnitName': UnitName,
                'SizeCode': SizeCode,
                'Weight': Weight,
                'qpu': qpu,
                'sUnit': sUnit,
                'xCenter': xCenter,
                'xItemnew': xItemnew,
                'masterItem': masterItem,
                'tdas': tdas,
                'hospital': hospital
              };
              // console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
            } else if (result.dismiss == 'cancel') {
              swal.close();
            }
          })
        }
      } else {
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
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
      }
    }
    function resetinput(){

      var mainCatagory = $('#maincatagory2').val();
      var Catagory = $('#catagory2').val();
      var ItemCode = $('#ItemCode').val();
      var ItemName = $('#ItemName').val();
      var CusPrice = $('#CusPrice').val();
      var FacPrice = $('#FacPrice').val();
      var UnitName = $('#UnitName').val();
      var SizeCode = $('#SizeCode').val();
      var Weight = $('#Weight').val();
      var qpu = $('#QtyPerUnit').val();
      var sUnit = $('#sUnitName').val();
      var numPack = $('#numPack').val();
      var typeLinen = $('#typeLinen').val();
      
      
  if(ItemCode !="" && ItemCode!=undefined){
    $('#rem1').hide();
    $('#ItemCode').css('border-color', '');
  }  if(mainCatagory !="" && mainCatagory!=undefined){
    $('#rem2').hide();
    $('#maincatagory2').css('border-color', '');
  }
  if(Catagory !="" && Catagory!=undefined){
    $('#rem3').hide();
    $('#catagory2').css('border-color', '');
  }
  if(ItemName !="" && ItemName!=undefined){
    $('#rem4').hide();
    $('#ItemName').css('border-color', '');
  }
  if(Weight !="" && Weight!=undefined){
    $('#rem5').hide();
    $('#Weight').css('border-color', '');
  }
  if(qpu !="" && qpu!=undefined){
    $('#rem6').hide();
    $('#QtyPerUnit').css('border-color', '');
  }
  if(SizeCode !="" && SizeCode!=undefined){
    // $('#rem6').hide();
    $('#SizeCode').css('border-color', '');
  }
  if(numPack !="" && numPack!=undefined){
    // $('#rem6').hide();
    $('#numPack').css('border-color', '');
  }
  if(typeLinen !="" && typeLinen!=undefined){
    // $('#rem6').hide();
    $('#typeLinen').css('border-color', '');
  }
}
    function NewItem() {
      var count = 0;
      $(".checkblank").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });
      console.log(count);
      var mainCatagory = $('#maincatagory2').val();
      var Hos2 = $('#hospital').val();
      var Catagory = $('#catagory2').val();
      var ItemCode = $('#ItemCode').val();
      var ItemName = $('#ItemName').val();
      var CusPrice = $('#CusPrice').val();
      var FacPrice = $('#FacPrice').val();
      var UnitName = $('#UnitName').val();
      var SizeCode = $('#SizeCode').val();
      var Weight = $('#Weight').val();
      var qpu = $('#QtyPerUnit').val();
      var sUnit = $('#sUnitName').val();
      var xCenter = 0;
      var xItemnew = 0;
      var tdas = 0;
      var masterItem = 0;

      if ($('#masterItem').is(':checked')) masterItem = 1;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xItemnew').is(':checked')) xItemnew = 1;
      if ($('#tdas').is(':checked')) tdas = 1;
      if (count == 0) {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          }
        });
        if (ItemCode != "") {
          console.log("New Item : " + $('#ItemCode').data("status"));
          if ($('#ItemCode').data("status")) {
            swal({
              title: "<?php echo $array['addoredit'][$language]; ?>",
              text: "<?php echo $array['addoredit1'][$language]; ?>",
              type: "question",
              showCancelButton: true,
              confirmButtonClass: "btn-success",
              confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
              cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
              confirmButtonColor: '#6fc864',
              cancelButtonColor: '#3085d6',
              closeOnConfirm: false,
              closeOnCancel: false,
              showCancelButton: true
            }).then(result => {
              if (result.value) {
                var data = {
                  'STATUS': 'NewItem',
                  'Catagory': Catagory,
                  'ItemCode': ItemCode,
                  'ItemName': ItemName,
                  'CusPrice': CusPrice,
                  'FacPrice': FacPrice,
                  'UnitName': UnitName,
                  'SizeCode': SizeCode,
                  'Weight': Weight,
                  'qpu': qpu,
                  'sUnit': sUnit,
                  'xCenter': xCenter,
                  'xItemnew': xItemnew,
                  'tdas': tdas ,
                  'masterItem': masterItem,
                  'HptCode': Hos2  
                  
                };
                senddata(JSON.stringify(data));
              } else if (result.dismiss == 'cancel') {
                swal.close();
              }
              setTimeout(() => {
                CreateItemCode();
              }, 700);
            })
          } else {
            swal({
              title: '',
              text: "<?php echo $array['DuplicateItemcode'][$language]; ?>",
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
      } else {
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
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
            if(ItemCode ==""||ItemCode==undefined){
                  $('#rem1').show().css("color","red");
                }
                if(mainCatagory ==""||mainCatagory==undefined){
                  $('#rem2').show().css("color","red");
                }
                if(Catagory ==""||Catagory==undefined){
                  $('#rem3').show().css("color","red");
                }
                if(ItemName ==""||ItemName==undefined){
                  $('#rem4').show().css("color","red");
                }
                if(Weight ==""||Weight==undefined){
                  $('#rem5').show().css("color","red");
                }
                if(qpu ==""||qpu==undefined){
                  $('#rem6').show().css("color","red");
                }
          }
        });
      }
    }

    function CreateItemCode() {
      var Catagory = $('#catagory2').val();
      var modeCode = $('#formatitem:checked').val();
      var modeCheck = $('#checkitem:checked').val();
      console.log(modeCode);
      if (typeof modeCheck == 'undefined') {
        if (modeCode == 3) {
          $('#oldCodetype').hide();
          var hospitalCode = "";
          var typeCode = "";
          var packCode = "";
          $('#ItemCode').attr("disabled", false);
          $('#typeLinen').addClass('checkblank');
          $('#numPack').addClass('checkblank');
          $('#typeLinen').removeClass('checkblank');
          $('#numPack').removeClass('checkblank');
        } else {
          if (modeCode == 1) {
            $('#ItemCode').attr("disabled", true);
            $('#oldCodetype').show();
            var hospitalCode = $('#hospital').val();
            var typeCode = $('#typeLinen').val();
            var packCode = $('#numPack').val();
            $('#Hos2').val(hospitalCode);
            ShowItem();
            if(hospitalCode == ""){
            $('#ItemCode').val("");
            }
            $('#typeLinen').addClass('checkblank');
            $('#numPack').addClass('checkblank');
          } else {
            $('#ItemCode').attr("disabled", true);
            $('#oldCodetype').hide();
            var hospitalCode = "";
            var typeCode = "1";
            var packCode = "1";
            $('#ItemCode').val("");
            $('#typeLinen').removeClass('checkblank');
            $('#numPack').removeClass('checkblank');
          }
          var data = {
            'STATUS': 'CreateItemCode',
            'Catagory': Catagory,
            'modeCode': modeCode,
            'hospitalCode': hospitalCode,
            'typeCode': typeCode,
            'packCode': packCode
          };
          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        }
      }
    }

    function AddUnit() {
      var priceunit = $('#priceunit').val();
      var mul = $('#mulinput').val();
      var u1 = $('#Unitshows').val();
      var u2 = $('#subUnit').val();


      var itemcode = $('#ItemCode').val();

      if (mul != "" && priceunit != "") {
        var data = {
          'STATUS': 'AddUnit',
          'ItemCode': itemcode,
          'MpCode': u2,
          'UnitCode': u1,
          'Multiply': mul,
          'priceunit': priceunit
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      } else {
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
      }
    }

    function CancelItem() {
      var itemcode = $("#ItemCode").val();
      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['confirm1'][$language]; ?>" + itemcode + "<?php echo $array['confirm2'][$language]; ?>",
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
        var ItemCode = $('#ItemCode').val();

        if (result.value) {
          var data = {
          'STATUS': 'CancelItem',
          'ItemCode': itemcode
          };
          senddata(JSON.stringify(data));
        } else if (result.dismiss == 'cancel') {
          swal.close();
        }

      })
      $('#NewItem').show();
      $('#AddItemBNT').hide();
      $("input[name=formatitem][value=1]").prop('checked', true);
    }
    function uncheckAll2() {
      $('input[type=radio]').each(function() 
          { 
                  this.checked = false; 
          });
    }
    function Blankinput() {
      $('#hospital').attr('disabled', false);
      $('#maincatagory2').attr('disabled', false);
      $('#typeLinen').attr('disabled', false);
      $('#numPack').attr('disabled', false);
        $('#rem1').hide();
        $('#rem2').hide();
        $('#rem3').hide();
        $('#rem4').hide();
        $('#rem5').hide();
        $('#rem6').hide();
      $(".radio-c :input").attr("disabled", false);
      $("input[name=formatitem][value=3]").prop('checked', true);
      $('#oldCodetype').hide();
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
      $('#bSave_chk').attr('disabled', false);
      $('#ItemCode').attr('disabled', false);
      $('#ItemCode').val("");
      $('#maincatagory2').val("1");
      $('#catagory2').val("1");
      $('#UnitName').val("");
      $('#SizeCode').val("1");
      $('#hospital').val("");
      $('#Hos2').val("");
      $('#maincatagory').val("");
      $('#catagory1').val("");
  
      $('#typeLinen').val("P");
      $('#numPack').val("01");
      $('#sUnitName').val("1");
      // ShowItem();
      $('#bCancel').attr('disabled', true);
      $('#delete_icon').addClass('opacity');
      $('#delete1').removeClass('mhee');
      $('#NewItem').show();
      $('#AddItemBNT').hide();
      // CreateItemCode();
      uncheckAll2();
      ShowItem();
      $('#btn_importMaster').attr('disabled', true);
      $('#btn_del').attr('disabled', true);

    }

    function getdetail(ItemCode,row) {
      $('#bSave_chk').attr('disabled', false);
      $('#btn_importMaster').attr('disabled', false);
      if (ItemCode.length > 9) {
        $("input[name=formatitem][value=1]").prop('checked', true);
        $('#oldCodetype').show();
      } else if (ItemCode.length == 9) {
        $("input[name=formatitem][value=2]").prop('checked', true);
        $('#oldCodetype').hide();
      } else {
        $("input[name=formatitem][value=3]").prop('checked', true);
        $('#oldCodetype').hide();
      }
      $('#ItemCode').attr("disabled", true);
      var previousValue = $('#checkitem_'+row).attr('previousValue');
        var name = $('#checkitem_'+row).attr('name');
        if (previousValue == 'checked') {
          $('#hospital').attr('disabled', false);
          $('#maincatagory2').attr('disabled', false);
          $('#typeLinen').attr('disabled', false);
          $('#numPack').attr('disabled', false);

          $('#hospital').removeClass('icon_select');
          $('#maincatagory2').removeClass('icon_select');
          $('#typeLinen').removeClass('icon_select');
          $('#numPack').removeClass('icon_select');
          $('#checkitem_'+row).removeAttr('checked');
          $('#checkitem_'+row).attr('previousValue', false);
          $('#checkitem_'+row).prop('checked', false);
          Blankinput();
        } else {
          $('#hospital').addClass('icon_select');
          $('#maincatagory2').addClass('icon_select');
          $('#typeLinen').addClass('icon_select');
          $('#numPack').addClass('icon_select');

          $('#hospital').attr('disabled', true);
          $('#maincatagory2').attr('disabled', true);
          $('#typeLinen').attr('disabled', true);
          $('#numPack').attr('disabled', true);

          $("input[name="+name+"]:radio").attr('previousValue', false);
          $('#checkitem_'+row).attr('previousValue', 'checked');
      if (ItemCode != "" && ItemCode != undefined) {
        var data = {
          'STATUS': 'getdetail',
          'ItemCode': ItemCode
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
      $('#NewItem').hide();
      $('#AddItemBNT').show();
      $(".radio-c :input").attr("disabled", true);
      }
    }

    function getdetailMaster(ItemCode,row) {
      $('#btn_importMaster').attr('disabled', false);
      if (ItemCode != "" && ItemCode != undefined) {
        var data = {
          'STATUS': 'getdetailMaster',
          'ItemCode': ItemCode
        };
        senddata(JSON.stringify(data));
      }
    }
    function ActiveItem() {
      var ItemCode = $('#ItemCode').val();
      if (ItemCode != "" && ItemCode != undefined) {
        var data = {
          'STATUS': 'ActiveItem',
          'ItemCode': ItemCode
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }
    function uncheckAll2() {
                $('input[type=checkbox]').each(function() 
                    { 
                            this.checked = false; 
                    });
                }
    function DeleteUnit() {
      var RowID = $("#checkitem2:checked").val();
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
        var ItemCode = $('#ItemCode').val();
        $('#btn_del').attr('disabled', true);
        if (result.value) {
          var data = {
          'STATUS': 'DeleteUnit',
          'RowID': RowID
          }
          senddata(JSON.stringify(data));
          
        } else if (result.dismiss == 'cancel') {
          swal.close();
        }
      })
    }

    function SavePY() {
      $('#TableDocumentSS tbody').empty();
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var datepicker = $('#datepicker').val();
      datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

      var DocNo = $("#docno").val();
      $("#searchtxt").val(DocNo);

      if (DocNo.length > 0) {
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
          'STATUS': 'SavePY',
          'DocNo': DocNo,
          'DEPT': dept,
          'Datepicker': datepicker
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function DelItem() {
      var DocNo = $("#docno").val();
      /* declare an checkbox array */
      var chkArray = [];
      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkitemdetail:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray.join(',');

      // alert(DocNo + " : " + UsageCode);
      var data = {
        'STATUS': 'DelItem',
        'DocNo': DocNo,
        'UsageCode': UsageCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function canceldocno(docno) {
      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['canceldata2'][$language]; ?>" + docno + "<?php echo $array['canceldata3'][$language]; ?>",
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
        var data = {
          'STATUS': 'CancelDocNo',
          'DocNo': docno
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
        getSearchDocNo();
      })
    }

    function addnum(cnt) {
      var add = parseInt($('#qty' + cnt).val()) + 1;
      if ((add >= 0) && (add <= 500)) {
        $('#qty' + cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#qty' + cnt).val()) - 1;
      if ((sub >= 0) && (sub <= 500)) {
        $('#qty' + cnt).val(sub);
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
      }).then(function() {
        window.location.href = "../logoff.php";
      }, function(dismiss) {
        window.location.href = "../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }

    function menu_tapShow(){
      $('#TableItem').attr("hidden", false);
      $('#TableItemMaster').attr("hidden", true);
      $('#memu_tap1').attr('hidden', false);
      $('#myTab').removeClass('mt-5');
      $('#searchItem_1').attr('hidden', false);
      $('#searchItem_2').attr('hidden', true);
      $('#scroll555').addClass('table-scroll');
    }
    function menu_tapHide(chk){
      if(chk != 2){
        $('#scroll555').addClass('table-scroll');
        $('#TableItem').attr("hidden", false);
        $('#TableItemMaster').attr("hidden", true);
        $('#memu_tap1').attr('hidden', true);
        $('#myTab').addClass('mt-5');
        $('#searchItem_1').attr('hidden', false);
        $('#searchItem_2').attr('hidden', true);

      }else if(chk == 2){
        $('#scroll555').removeClass('table-scroll');
        $('#TableItem').attr("hidden", true);
        $('#TableItemMaster').attr("hidden", false);
        $('#memu_tap1').attr('hidden', true);
        $('#myTab').addClass('mt-5');
        $('#searchItem_1').attr('hidden', true);
        $('#searchItem_2').attr('hidden', false);
      }

    }
		function getplaceholder(){
			var sUnitName = $('#sUnitName option:selected').attr("value");

			if(sUnitName ==2){
				$('#QtyPerUnit').removeAttr("placeholder");     
				$('#QtyPerUnit').attr("placeholder" , "<?php echo $array['Weightitem'][$language]; ?>");      
			}else if(sUnitName ==4){
				$('#QtyPerUnit').removeAttr("placeholder");     
				$('#QtyPerUnit').attr("placeholder" , "<?php echo $array['Sizeitem'][$language]; ?>");    
			}else{
				$('#QtyPerUnit').removeAttr("placeholder");     
				$('#QtyPerUnit').attr("placeholder" , "<?php echo $array['Quality'][$language]; ?>");    
			}
		}
    function undisableDel(){
      $('#btn_deleteMaster').attr('disabled', false);
    }
    function deleteMaster(){
      var chkArrayRow=[];
      var maincatagory = $("#maincatagory").val();
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      var ItemCode = $("#ItemCodeM_chk").val();
      $(".masterChk:checked").each(function() {
        chkArrayRow.push($(this).val());
      });
      var ArraychkArrayRow = chkArrayRow.join(',');
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
        // var ItemCode = $('#ItemCode').val();
        $('#btn_deleteMaster').attr('disabled', true);
        if (result.value) {
          for (i = 0; i < chkArrayRow.length; ++i) {
                  $('#tr'+chkArrayRow[i]).remove();
              }
          var data = {
            'STATUS' : 'deleteMaster',
            'ArraychkArrayRow' : ArraychkArrayRow,
            'ItemCode' : ItemCode,
            'maincatagory' : maincatagory,
            'item' : item,
            'Catagory' : catagory
          };
          senddata(JSON.stringify(data));
        } else if (result.dismiss == 'cancel') {
          $('#btn_deleteMaster').attr('disabled', false);
          swal.close();
        }
      })
    }
    function SaveQtyMaster(RowID, row){
      swal({
        title: '<?php echo $array['pleasewait'][$language]; ?>',
        text: '<?php echo $array['processing'][$language]; ?>',
        allowOutsideClick: false
      });
      swal.showLoading();
      var NewQty = $('#masterQty_'+row).val();
      var data = {
        'STATUS':'SaveQtyMaster',
        'RowID':RowID,
        'Qty':NewQty
      };
      senddata(JSON.stringify(data));
    }
    function openModal(){
      $('#masterModal').modal('show');
    }
    function ShowItemModal() {
      var maincatagory = $("#maincatagoryModal").val();
      var catagory = $("#catagoryModal").val();
      var ItemCode = $("#ItemCodeM_chk").val();
      var item = $("#searchitemModal").val();
      var data = {
        'STATUS': 'ShowItemModal',
        'Catagory': catagory,
        'ItemCode': ItemCode,
        'Keyword': item,
        'maincatagory': maincatagory
      };
      senddata(JSON.stringify(data));
    }
    function AddItemMaster(){
      var chkArrayRow = [];
      var ItemCodeArray = [];
      var QtyArray = [];
      var ItemCode = $('#ItemCodeM_chk').val();
      $(".addMasterChk:checked").each(function() {
        chkArrayRow.push($(this).val());
      });
      for (var i = 0; i < chkArrayRow.length; i++) {
        ItemCodeArray.push($('#addItemCodeM_'+chkArrayRow[i]).data('value'));
        QtyArray.push($('#addMaster_'+chkArrayRow[i]).val());
      }
      var ArrayItemCode = ItemCodeArray.join(',');
      var Qty = QtyArray.join(',');
      swal({
        title: "<?php echo $array['adddata'][$language]; ?>",
        text: "<?php echo $array['adddata1'][$language]; ?>",
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
          var sv = "<?php echo $array['addsuccessmsg'][$language]; ?>";
          swal({
              title: sv,
              text: '',
              type: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 1000
          });
          setTimeout(() => {
            var data = {
              'STATUS' : 'AddItemMaster',
              'ItemCode' : ItemCode,
              'ArrayItemCode' : ArrayItemCode,
              'ArrayQty' : Qty
            };
            $('#masterModal').modal('toggle');
            $('#tbody_modal').empty();
            senddata(JSON.stringify(data));
          }, 1000);
          
        } else if (result.dismiss == 'cancel') {
          swal.close();
        }
      })
    }
    function disabledDel(){
      $('#btn_del').attr('disabled', false);
    }

  
		//<!-- --------------------Function--------------------- --!>
		//<!-- --------------------desplay--------------------- --!>
    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/item.php';
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
        success: function(result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }
          swal.close();

          if (temp["status"] == 'success') {
            if (temp["form"] == 'getDocument') {
              $("#TableDocument tbody").empty();
              $("#TableDetail tbody").empty();

              $("#TableDocumentSS tbody").empty();
              $("#TableSendSterileDetail tbody").empty();

              $("#docno").val("");

              $("input[type='checkbox']").prop('checked', false);

              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDocument >tbody >tr').length;
                var chkDoc = "<input type='radio' class='form-check-input' name='checkdocno' id='checkdocno' value='" + temp[i]['DocNo'] + "' onclick='getDocDetail()'>";
                var StrTr = "<tr id='tr" + temp[i]['DocNo'] + "'>" +
                  "<td style='width: 5%;'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 5%;'nowrap><label>" + (i + 1) + "</label></td>" +
                  "<td style='width: 20%;'nowrap>" + temp[i]['DocNo'] + "</td>" +
                  "<td style='width: 20%;' align='center'nowrap>" + temp[i]['DocDate'] + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + temp[i]['Qty'] + "</td>" +
                  "<td style='width: 30%;'nowrap>" + temp[i]['Elc'] + "</td>" +
                  "<td style='width: 10%;'nowrap><img src='../img/delete-32.png' onclick='canceldocno(\"" + temp[i]['DocNo'] + "\")'></td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDocument tbody").append(StrTr);
                } else {
                  $('#TableDocument tbody:last-child').append(StrTr);
                }
                if (temp[0]['DocNo'].length == 0) {
                  $("#TableDocument tbody").empty();
                  $("#TableDetail tbody").empty();
                  $("#TableDocumentSS tbody").empty();
                  $("#TableSendSterileDetail tbody").empty();
                  $("#docno").val("");
                }
              }
            } else if ((temp["form"] == 'CreatePayout')) {
              swal({
                title: "<?php echo $array['createdocno'][$language]; ?>",
                text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 5000,
                confirmButtonText: 'Ok',
                closeOnConfirm: false
              });
              getSearchDocNo();
            } else if ((temp["form"] == 'getCatagory')) {
              $("#catagory1").empty();
              $("#catagoryModal").empty();
              $("#catagory2").empty();
              var hotValue0 = '<?php echo $array['Pleaseselectasubcategory'][$language]; ?>';
              var StrTr = "<option value=''>"+hotValue0+"</option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
 
                 StrTr += "<option value = '" + temp[i]['CategoryCode'] + "'> " + temp[i]['CategoryName'] + " </option>";
                var Str = "<option value = '" + temp[i]['CategoryCode'] + "'> " + temp[i]['CategoryName'] + " </option>";
                $("#catagory2").append(Str);
              }
              $("#catagory1").append(StrTr);
              $("#catagoryModal").append(StrTr);
              CreateItemCode();
            } else if ((temp["form"] == 'GetHospital')) {
              var hotValue0 = '<?php echo $array['selecthospital'][$language]; ?>';
              var StrTr1 = "<option value=''>"+hotValue0+"</option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                 StrTr1 += "<option value = '" + temp[i]['HospitalCode'] + "'> " + temp[i]['HospitalName'] + " </option>";
                var StrTr = "<option value = '" + temp[i]['HospitalCode'] + "'> " + temp[i]['HospitalName'] + " </option>";
              }
              $("#hospital").append(StrTr1);
              $("#Hos2").append(StrTr1);
            } else if ((temp["form"] == 'GetmainCat')) {
              var hotValue0 = '<?php echo $array['Pleasechoosemaincategory'][$language]; ?>';
              var StrTr1 = "<option value=''>"+hotValue0+"</option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                 StrTr1 += "<option value = '" + temp[i]['MainCategoryCode'] + "'> " + temp[i]['MainCategoryName'] + " </option>";
                 var  Str = "<option value = '" + temp[i]['MainCategoryCode'] + "'> " + temp[i]['MainCategoryName'] + " </option>";
                $("#maincatagory2").append(Str);
              }
              $("#maincatagory").append(StrTr1);
              $("#maincatagoryModal").append(StrTr1);
            } else if ((temp["form"] == 'getUnit')) {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
               var StrTr1 = "<option value = '" + temp[i]['UnitCode'] + "'> " + temp[i]['UnitName'] + " </option>";
                var StrTr = "<option value = '" + temp[i]['UnitCode'] + "'> " + temp[i]['UnitName'] + " </option>";

                $("#subUnit").append(StrTr);
                $("#Unitshows").append(StrTr);
                $("#UnitName").append(StrTr1);
              $("#sUnitName").append(StrTr1);
              }


            } else if (temp["form"] == 'getDocDetail') {
              $("#TableDetail tbody").empty();
              $("#TableUnit tbody").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDetail >tbody >tr').length;
                var chkDoc = "<input type='checkbox' name='checkitemdetail' id='checkitemdetail' value='" + temp[i]['ID'] + ":" + temp[i]['UsageCode'] + "' onclick='unCheckDocDetail()'>";
                console.log(temp);
                $StrTr = "<tr id='tr" + temp[i]['UsageCode'] + "'>" +
                  "<td style='width: 5%;'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 5%;'nowrap><label> " + (i + 1) + "</label></td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['UsageCode'] + "</td>" +
                  "<td style='width: 50%;'nowrap>" + temp[i]['itemname'] + "</td>" +
                  "<td style='width: 15%;' align='center'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + temp[i]['Qty'] + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDetail tbody").append($StrTr);
                } else {
                  $('#TableDetail tbody:last-child').append($StrTr);
                }

                ShowItem();

              }
            } else if ((temp["form"] == 'ShowItem') || (temp["form"] == 'ShowItem_Active_0')) {
              $('#TableItem').attr("hidden", false);
              $('#TableItemMaster').attr("hidden", true);
              $("#TableItem tbody").empty();
              $("#TableUnit tbody").empty();
// ======================================================================
              for (var j = 0; j < temp["countx"]; j++) {
               "+ (j + 1) +"
              }
              if( temp['down'] ==1){
                var top     = "hidden";
                var down     = "";
              }else{
                var top     = "";
                var down     = "hidden";
              }
// ======================================================================
              $("#TableItem tbody").empty();
              if(temp['countx']>0){
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var IsDirtyBag = temp[i]['IsDirtyBag'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var ItemNew = temp[i]['Itemnew'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var isset = temp[i]['isset'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var Tdas = temp[i]['Tdas'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var rowCount = $('#TableItem >tbody >tr').length;
  
                var chkDoc = "<label class='radio' title='" + temp[i]['ItemName'] + "' style='margin-top: 20%;'><input type='radio'  name='checkitem' id='checkitem_"+i+"' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetail(\"" + temp[i]['ItemCode'] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                $StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                  "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 8%;padding-right: 5%;' "+top+"   align='center'nowrap><label> " + (i+1) + "</label></td>" +
                  "<td style='width: 8%;padding-right: 5%;' "+down+"  align='center'nowrap><label> " + j-- + "</label></td>" +
                  "<td style='width: 16%;' align='left'nowrap>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='text-overflow: ellipsis;overflow: hidden; width: 16%;' align='left' title='" + temp[i]['ItemName'] + "' nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 9%;' align='left'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "<td style='width: 11%;' align='left'nowrap>&nbsp;&nbsp;" + temp[i]['SizeCode'] + "</td>" +
                  "<td style='width: 13%;'nowrap>" + temp[i]['Weight'] + "</td>" +
                  "<td style='width: 8%;' nowrap>" + IsDirtyBag + "</td>" +
                  "<td style='width: 6%;' nowrap>" + isset + "</td>" +
                  "<td style='width: 6%;' align='center'nowrap>" + Tdas + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableItem tbody").append($StrTR);
                } else {
                  $('#TableItem tbody:last-child').append($StrTR);
                }
              }
            }else{
              $('#TableItem tbody').empty();
              var Str = "<tr width='100%'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
              $('#TableItem tbody:last-child').append(Str);
            }
              // $('.checkblank').each(function() {
              //   $(this).val("");
              // });
              $('#UnitName').val("1");
              $('#SizeCode').val("1");
              // $('#hospital').val("BHQ");
              $('#typeLinen').val("P");
              $('#numPack').val("01");

            } else if ((temp["form"] == 'ShowItem2')) {
              // $('#TableItemMaster').attr("hidden", true);
              $("#TableItem tbody").empty();
              $("#TableUnit tbody").empty();
              for (var i = 0; i < temp['RowCount']; i++) {
                var IsDirtyBag = temp[i]['IsDirtyBag'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var ItemNew = temp[i]['Itemnew'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var isset = temp[i]['isset'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                var rowCount = $('#TableItem >tbody >tr').length;
                if(temp['mItemCode'] == temp[i]['ItemCode']){
                  var chkDoc = "<label class='radio'style='margin-top: 20%;' title='" + temp[i]['ItemName'] + "'><input checked='true' type='radio' name='checkitem' id='checkitem_"+i+"' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetail(\"" + temp[i]['ItemCode'] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                  getdetail(temp['mItemCode'], i);
                }else{
                  var chkDoc = "<label class='radio'style='margin-top: 20%;' title='" + temp[i]['ItemName'] + "'><input type='radio' name='checkitem' id='checkitem_"+i+"' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetail(\"" + temp[i]['ItemCode'] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                }
                $StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                  "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 5%;' align='center'nowrap><label> " + (i + 1) + "</label></td>" +
                  "<td style='width: 19%;' align='left'nowrap>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='width: 12%;text-overflow: ellipsis;overflow: hidden;' title='"+temp[i]['ItemName']+"' align='left' nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 11%;' align='left'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "<td style='width: 9%;' align='left'nowrap>&nbsp;&nbsp;" + temp[i]['SizeCode'] + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + temp[i]['Weight'] + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + IsDirtyBag + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + ItemNew + "</td>" +
                  "<td style='width: 8%;' align='center'nowrap>" + isset + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableItem tbody").append($StrTR);
                } else {
                  $('#TableItem tbody:last-child').append($StrTR);
                }
              }
              $('.checkblank').each(function() {
                $(this).val("");
              });
              $('#maincatagory2').val("1");
              $('#catagory2').val("1");
              $('#UnitName').val("1");
              $('#SizeCode').val("1");
              $('#hospital').val("BHQ");
              $('#typeLinen').val("P");
              $('#numPack').val("01");

            } else if ((temp["form"] == 'ShowItemMaster')) {
              if(temp['CountRow']>0){
                $('#TableItem').attr("hidden", true);
                $('#TableItemMaster').attr("hidden", false);
                $("#TableItemMaster tbody").empty();
                for (var i = 0; i < temp['CountRow']; i++) {
                  var IsDirtyBag = temp[i]['IsDirtyBag'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                  var ItemNew = temp[i]['Itemnew'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                  var rowCount = $('#TableItemMaster >tbody >tr').length;
                  var chkDoc = "<label class='radio'style='margin-top: 20%;' title='" + temp[i]['ItemName'] + "'><input type='radio' name='checkitemM' id='checkitem_M"+i+"' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetailMaster(\"" + temp[i]['ItemCode'] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                  $StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                    "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                    "<td style='width: 6%;' align='center'nowrap><label> " + (i + 1) + "</label></td>" +
                    "<td style='width: 19%;' align='left'nowrap>" + temp[i]['ItemCode'] + "</td>" +
                    "<td style='width: 15%;text-overflow: ellipsis;overflow: hidden;' title='"+temp[i]['ItemName']+"' align='left'nowrap>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 11%;' align='left'nowrap>" + temp[i]['UnitName'] + "</td>" +
                    "<td style='width: 9%;' align='left'nowrap>&nbsp;&nbsp;" + temp[i]['SizeCode'] + "</td>" +
                    "<td style='width: 14%;' align='center'nowrap>" + temp[i]['Weight'] + "</td>" +
                    "<td style='width: 11%;' align='center'nowrap>" + IsDirtyBag + "</td>" +
                    "<td style='width: 10%;' align='center'nowrap>" + ItemNew + "</td>" +
                    "</tr>";

                  if (rowCount == 0) {
                    $("#TableItemMaster tbody").append($StrTR);
                  } else {
                    $('#TableItemMaster tbody:last-child').append($StrTR);
                  }
                }
              }else{
                $("#TableItemMaster tbody").empty();
                StrTR = "<tr><td style='width: 100%;' align='center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                $("#TableItemMaster tbody").append(StrTR);
              }
                
            } else if ((temp["form"] == 'ShowItemMaster2')) {
                $('#TableItem').attr("hidden", true);
                $('#TableItemMaster').attr("hidden", false);
                $("#TableItemMaster tbody").empty();
                for (var i = 0; i < temp['CountRow']; i++) {
                  var IsDirtyBag = temp[i]['IsDirtyBag'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                  var ItemNew = temp[i]['Itemnew'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                  var rowCount = $('#TableItemMaster >tbody >tr').length;
                  if(temp['mItemCode'] == temp[i]['ItemCode']){
                    var chkDoc = "<label class='radio'style='margin-top: 20%;' title='" + temp[i]['ItemName'] + "'><input type='radio' checked='true' name='checkitemM' id='checkitem_M"+i+"' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetailMaster(\"" + temp[i]['ItemCode'] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                  }else{
                    var chkDoc = "<label class='radio'style='margin-top: 20%;' title='" + temp[i]['ItemName'] + "'><input type='radio' name='checkitemM' id='checkitem_M"+i+"' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetailMaster(\"" + temp[i]['ItemCode'] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                  }
                  $StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                    "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                    "<td style='width: 6%;' align='center'nowrap><label> " + (i + 1) + "</label></td>" +
                    "<td style='width: 19%;' align='left'nowrap>" + temp[i]['ItemCode'] + "</td>" +
                    "<td style='width: 15%;' align='left'nowrap>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 11%;' align='left'nowrap>" + temp[i]['UnitName'] + "</td>" +
                    "<td style='width: 9%;' align='left'nowrap>&nbsp;&nbsp;" + temp[i]['SizeCode'] + "</td>" +
                    "<td style='width: 14%;' align='center'nowrap>" + temp[i]['Weight'] + "</td>" +
                    "<td style='width: 11%;' align='center'nowrap>" + IsDirtyBag + "</td>" +
                    "<td style='width: 10%;' align='center'nowrap>" + ItemNew + "</td>" +
                    "</tr>";

                  if (rowCount == 0) {
                    $("#TableItemMaster tbody").append($StrTR);
                  } else {
                    $('#TableItemMaster tbody:last-child').append($StrTR);
                  }
                }
                ShowItem2(temp['mItemCode']);
            } else if ((temp["form"] == 'getdetail')) {
              if ((Object.keys(temp).length - 2) > 0) {
                $("#TableUnit tbody").empty();
                $('#maincatagory2').val(temp[0]['MainCategoryCode']);
                $('#catagory2').val(temp[0]['CategoryCode']);
                $('#ItemCode').val(temp[0]['ItemCode']);
                $('#ItemCodeM_chk').val(temp[0]['ItemCode']);
                console.log(temp[0]['ItemCode']);
                console.log($('#ItemCode').val());
                $('#hospital').val(temp[0]['HptCode']);
                $('#ItemName').val(temp[0]['ItemName']);
                $('#CusPrice').val(temp[0]['CusPrice']);
                $('#FacPrice').val(temp[0]['FacPrice']);
                $('#UnitName').val(temp[0]['UnitCode']);
                $('#Unitshows').val(temp[0]['UnitCode']);
                $('#SizeCode').val(temp[0]['SizeCode']);
                $('#Weight').val(temp[0]['Weight']);
                $('#QtyPerUnit').val(temp[0]['QtyPerUnit']);
                $('#sUnitName').val(temp[0]['sUnitName']);
                $('#bCancel').attr('disabled', false);
                $('#delete_icon').removeClass('opacity');
                $('#delete1').addClass('mhee');

                if (temp[0]['IsDirtyBag'] == 1)  {
                  $('#xCenter').prop('checked', true);
                }else{
                  $('#xCenter').prop('checked', false);
                }

                if(temp[0]['Itemnew'] == 1){
                  $('#xItemnew').prop('checked', true);
                }else{
                  $('#xItemnew').prop('checked', false);
                }

                if(temp[0]['tdas'] == 1){
                  $('#tdas').prop('checked', true);
                }else{
                  $('#tdas').prop('checked', false);
                }

                if(temp[0]['isset'] == 1){
                  $('#masterItem').prop('checked', true);
                }else{
                  $('#masterItem').prop('checked', false);
                }

                if (temp['RowCount']!=0) {
                  for (var i = 0; i < temp['RowCount']; i++) {
                    // var PriceUnit = temp[i]['PriceUnit'] == null ? '' : temp[i]['PriceUnit'];
                    var rowCount = $('#TableUnit >tbody >tr').length;
                    var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem2' id='checkitem2' value='" + temp[i]['RowID'] + "' onclick='disabledDel();'><span class='checkmark'></span></label>";
                    StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                      "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                      "<td style='width: 5%;' align='center'nowrap><label> " + (i + 1) + "</label></td>" +
                      "<td style='width: 28%;' align='left'nowrap>" + temp[i]['ItemName'] + "</td>" +
                      "<td style='width: 15%;' align='left'nowrap>" + temp[i]['MpCode'] + "</td>" +
                      "<td style='width: 17%;' align='left'nowrap>" + temp[i]['UnitName2'] + "</td>" +
                      "<td style='width: 15%;' align='left'nowrap>" + temp[i]['Multiply'] + "</td>" +
                      "<td style='width: 15%;' align='left'nowrap>" + temp[i]['PriceUnit'] + "</td>" +
                      "</tr>";

                    if (rowCount == 0) {
                      $("#TableUnit tbody").append(StrTR);
                    } else {
                      $('#TableUnit tbody:last-child').append(StrTR);
                    }
                  }
                }
              }
            } else if ((temp["form"] == 'getdetailMaster')) {
              $('#ItemCodeM_chk').val(temp['mItemCode']);
              if (temp['RowMaster'] != 0) {
                // $("#TableMaster tbody").empty();
                $("#empty_data").remove();
                var StrTR = "";
                for (var i = 0; i < temp['RowMaster']; i++) {
                  var rowCount = $('#TableMaster >tbody >tr').length;
                  var chkItem = "<label class='radio' style='margin-top: 20%;'><input type='checkbox' value='" + temp[i]['RowID'] + "' name='masterChk' class='masterChk' id='masterRow_"+i+"' onclick='undisableDel();'><span class='checkmark'></span></label>";
                  var QtyInput = "<input class='form-control text-center' style='width:200px;' id='masterQty_"+i+"' value='"+temp[i]['Qty']+"' onKeyPress='if(event.keyCode==13){SaveQtyMaster(\""+temp[i]['RowID']+"\",\""+i+"\")}'>"
                  StrTR += "<tr id='tr" + temp[i]['RowID'] + "'>" +
                    "<td style='width: 5%;' align='center' nowrap>" + chkItem + "</td>" +
                    "<td style='width: 5%;' nowrap><label> " + (i + 1) + "</label></td>" +
                    "<td style='width: 45%;text-align:left;'  nowrap>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 45%;' align='center' nowrap>" + QtyInput + "</td>" +
                    "</tr>";
                }
                $("#TableMaster tbody").html(StrTR);

              }else{
                $("#TableMaster tbody").empty();
                StrTR = "<td id='empty_data' style='width: 100%;text-align:center;'><?php echo $array['notfoundmsg'][$language]; ?></td>"
                $("#TableMaster tbody").append(StrTR);
              }
            } else if ((temp["form"] == 'AddItem')) {
              $('#NewItem').show();
              $('#AddItemBNT').hide();
              $(".radio-c :input").attr("disabled", false);

              //$('#ItemCode').val("");
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
                  // ShowItem();
                  Blankinput();
              }, function(dismiss) {
                  $('.checkblank').each(function() {
                      $(this).css('border-color', '');
                  });
              })

            } else if ((temp["form"] == 'AddUnit')) {
              var itemcode = $('#ItemCode').val();
              $('#subUnit').val("1");
              $('#mulinput').val("");
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
                timer: 1500,
                confirmButtonText: 'Ok'
              });
              setTimeout(function() {
                var itemcode = $('#ItemCode').val();
                getdetail(itemcode);
                $('#subUnit').val("1");
                $('#mulinput').val("");
                $('#priceunit').val("");
              }, 1500);
            } else if ((temp["form"] == 'CancelUnit')) {
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
              });
              setTimeout(function() {
                var itemcode = $('#ItemCode').val();
                getdetail(itemcode);
                $('#subUnit').val("1");
                $('#mulinput').val("");
                $('#priceunit').val("");
              }, 2000);
            } else if ((temp["form"] == 'CancelItem')) {
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
              });
              setTimeout(function() {
                Blankinput();
                ShowItem();
              }, 2000);
            } else if (temp['form'] == 'CreateItemCode') {
              $('#ItemCode').val(temp['ItemCode']);
            } else if (temp['form'] == 'ActiveItem') {
              temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
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
              })
            } else if ((temp["form"] == 'ShowItemModal')) {
              $('#TableItemModal tbody').empty();
              for (var i = 0; i < temp['RowMaster']; i++) {
                var chkDoc = "<input type='checkbox' name='addMasterChk' class='addMasterChk' value='"+i+ "'>";
                var Qty = "<input type='text' class='text-center numberonly' value='1' id='addMaster_"+i+"' >";
                $StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                  "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 31%;' align='left'nowrap id='addItemCodeM_"+i+"' data-value='"+temp[i]['ItemCode']+"'>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='width: 31%;' align='ledt'nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 31%;text-align:center;' align='center'nowrap>"+Qty+"</td>" +
                  "</tr>";
                  $("#TableItemModal tbody").append($StrTR);
              }
              $('.numberonly').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
              });
            } else if ((temp["form"] == 'AddItemMaster')) {
              getdetailMaster(temp['ItemCodeMaster']);
              ShowItemMaster2(temp['ItemCodeMaster']);
            } 
          } else if (temp['status'] == "failed") {
            $("#TableItem tbody").empty();
            $("#TableUnit tbody").empty();
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
                temp['msg'] = "<?php echo $array['DuplicateItemcode'][$language]; ?>";
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
            $("#docnofield").val(temp[0]['DocNo']);

            $("#TableDocumentSS tbody").empty();
            $("#TableSendSterileDetail tbody").empty();

          } else if (temp['status'] == "notfound") {
            $("#TableItem tbody").empty();
          } else {
            console.log(temp['msg']);
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

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    input,
    select {
      font-size: 24px !important;
    }

    th,
    td {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
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

    button {
      font-size: 24px !important;
    }

    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }

    .sidenav {
      height: 100%;
      overflow-x: hidden;
      /* padding-top: 20px; */
      border-left: 2px solid #bdc3c7;
    }

    .search {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .mhee a {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .mhee a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .mhee button {
      /* padding: 6px 8px 6px 16px; */
      font-size: 25px;
      color: #2c3e50;
      background: none;
      box-shadow: none !important;
    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
      outline: none;
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
      font-weight: bold;
      font-size: 26px;
    }

    .icon {
      padding-top: 6px;
      padding-left: 33px;
    }

    .opacity {
      opacity: 0.5;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav {
        margin-left: 30px;
      }

      .sidenav a {
        font-size: 20px;

      }
    }
    .table-scroll {
      overflow:auto;  
      height:355px;
      margin-top:5px;
    }

  </style>

</head>

<body id="page-top">
  <!-- iii -->
  <!-- Scroll to Top Button-->
 
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][3][$language]; ?></li>
  </ol>
   
  <div id="wrapper">
    <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
      <i class="fas fa-angle-down"></i>
    </a>
    <!-- content-wrapper -->
    <div id="content-wrapper" >
  <input type="hidden" id="ItemCodeM_chk">

      <div class="row">
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
              <div class="row">
              <div class="col-md-3">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control checkblank66" style="font-size:24px;width: 80%;" id="Hos2" onchange="changeHptCode()"></select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control" style="font-size:24px;margin-left: -23%;width: 80%;" id="maincatagory" onchange="getCatagory();"></select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control" style="font-size:24px;width: 80%;margin-left: -46%;" id="catagory1"> </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row " style="margin-left:2px;">
                    <input type="text" autocomplete="off" class="form-control" style="font-size:24px;margin-left: -106%;width: 155%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['Searchitem'][$language]; ?>">
                  </div>
                </div>
                <div class="col-md-1" style="margin-left: -9%;">
                  <div class="search_custom col-md-2" id="searchItem_1">
										<div class="search_1 d-flex justify-content-start">
											<button class="btn" onclick="ShowItem()">
												<i class="fas fa-search mr-2"></i>
												<?php echo $array['search'][$language]; ?>
											</button>
										</div>
                  </div>
                  <div class="search_custom col-md-2" id="searchItem_2" hidden>
										<div class="search_1 d-flex justify-content-start">
											<button class="btn" onclick="ShowItemMaster()">
												<i class="fas fa-search mr-2"></i>
												<?php echo $array['search'][$language]; ?>
											</button>
										</div>
									</div>
                </div>
              </div>

                  <table style="margin-top:10px;" class="table table-condensed table-striped table-fixed" id="TableItem"  cellspacing="0" role="grid">
                    <thead id="theadsum">
                      <tr role="row" id="tableSort">
                        <th style='width: 5%; font-size:13px;'>&nbsp;</th>
                        <th style='width: 8%;' nowrap><?php echo $array['sn'][$language]; ?>
                        <a href="javascript:void(0)" style="padding-left: 5px;" class="activeSort white"  onclick="ShowItem('itemDate','DESC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-up"></i></a>  
                        <a href="javascript:void(0)" class="activeSort "  onclick="ShowItem('itemDate','ASC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-down"></i></a>
                        </th>
                        <th style='width: 16%;' nowrap><?php echo $array['code'][$language]; ?>
                          <a href="javascript:void(0)" style="padding-left: 5px;" class="activeSort "  onclick="ShowItem('ItemCode','ASC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-up"></i></a>  
                          <a href="javascript:void(0)" class="activeSort "  onclick="ShowItem('ItemCode','DESC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-down"></i></a>
                        </th>
                        <th style='width: 12%;' nowrap><?php echo $array['item'][$language]; ?>
                        <a href="javascript:void(0)" style="padding-left: 5px;" class="activeSort "  onclick="ShowItem('ItemName','ASC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-up"></i></a>  
                          <a href="javascript:void(0)" class="activeSort "  onclick="ShowItem('ItemName','DESC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-down"></i></a>
                        </th>
                        <th style='padding-left: 4%;width: 11%;' nowrap><?php echo $array['unit2'][$language]; ?></th>
                        <th style='padding-left: 2%;width: 11.5%;' nowrap><?php echo $array['size'][$language]; ?></th>
                        <th style='width: 10%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                        <th style='width: 9%;' nowrap><?php echo $array['spacial'][$language]; ?></th>
                        <th style='width: 9%;' nowrap><?php echo $array['itemmas'][$language]; ?></th>
                        <th style='width: 8%;' nowrap><?php echo $array['tdas'][$language]; ?></th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:250px;">
                    </tbody>
                  </table>
              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemMaster" width="100%" cellspacing="0" role="grid" hidden>
                <thead id="theadsum">
                  <tr role="row" id="tableSort">
                    <th style='width: 5%; font-size:13px;'>&nbsp;</th>
                    <th style='width: 6%;' nowrap><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 19%;' nowrap><?php echo $array['codecode'][$language]; ?>
                      <a href="javascript:void(0)" style="padding-left: 5px;" class="activeSort "  onclick="ShowItemMaster('ItemCode','ASC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-up"></i></a>  
                      <a href="javascript:void(0)" class="activeSort white"  onclick="ShowItemMaster('ItemCode','DESC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-down"></i></a>
                    </th>
                    <th style='width: 15%;' nowrap><?php echo $array['item'][$language]; ?>
                    <a href="javascript:void(0)" style="padding-left: 5px;" class="activeSort "  onclick="ShowItemMaster('ItemName','ASC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-up"></i></a>  
                      <a href="javascript:void(0)" class="activeSort "  onclick="ShowItemMaster('ItemName','DESC')"><i style="font-size: 15px;" class="fas fa-long-arrow-alt-down"></i></a>
                     </th>
										<th style='width: 11%;' nowrap><?php echo $array['unit2'][$language]; ?></th>
										<th style='width: 9%;' nowrap><?php echo $array['size'][$language]; ?></th>
                    <th style='width: 14%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                    <th style='width: 11%;' nowrap><?php echo $array['spacial'][$language]; ?></th>
                    <th style='width: 10%;' nowrap><?php echo $array['newitem'][$language]; ?></th>
                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:250px;">
                </tbody>
              </table>
            </div>
          </div>
        </div> <!-- tag column 1 -->
      </div>

      <div class="row">
        <!-- start row tab -->
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <div id="memu_tap1">

              <div class="row m-1 mt-5 d-flex justify-content-end" >
                <div class="menu mhee" id="ActiveBNT" <?php if($PmID != 6) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle4 d-flex justify-content-center">
                                <button class="btn"  onclick="NewItem()" id="bSave">
                                <i class="fas fa-check"></i>
                                  <div>
                                    <?php echo $array['activeItem'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>

                <div class="menu mhee" id="NewItem" <?php if($PmID != 6 && $PmID != 1) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle4 d-flex justify-content-center">
                                <button class="btn"  onclick="NewItem()" id="bSave">
                                <i class="fas fa-plus"></i>                    
                                  <div>
                                    <?php echo $array['itemnew'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>

                <div class="menu mhee" id="AddItemBNT" <?php if($PmID != 6 && $PmID != 1) echo 'hidden'; ?>>
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
                          <div class="menu mhee" id="BlankItemBNT">
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
                          <div class="menu mhee" id="delete1" id="CancelBNT" <?php if($PmID != 6  && $PmID != 1) echo 'hidden'; ?> >
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
            </div>
              
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" onclick="menu_tapShow();" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab"  onclick="menu_tapHide();" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['mulmultiply'][$language]; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="itemset-tab"  onclick="menu_tapHide(2);" data-toggle="tab" href="#itemset" role="tab" aria-controls="itemset" aria-selected="false"><?php echo $array['itemset'][$language]; ?></a>
              </li>
            </ul>

            <div class="tab-content" id="myTabContent">
              <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <!-- /.content-wrapper -->
                <div class="row">
                  <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                      <div class="card-body" style="padding:0px; margin-top:10px;">
                        <!-- =================================================================== -->
                        <div class="row mt-4">
                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['code'][$language]; ?></label>
                              <input type="text" onkeyup="resetinput()" class="form-control col-sm-7 checkblank" id="ItemCode" data-status="true" placeholder="<?php echo $array['code'][$language]; ?>" disabled>
                              <label id="rem1" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                          </div>
                          <div class="col-md-0">
                          </div>
                          <div class="col-md-5">
                            <div class="row ">
                              <div class="col-md-4">
                                <div class='form-group row'>
                                  <div class='radio-c'>
                                    <label class="radio" style="margin-top: 20%;">
                                      <input type='radio' name='formatitem'  id='formatitem' value='3' onclick="CreateItemCode()" checked="checked">
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                                  <label class="col-sm-10 col-form-label text-left" style="margin-top: -7px;"><?php echo $array['custom'][$language]; ?></label>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class='form-group row'>
                                  <div class='radio-c' style="align-content:center">
                                    <label class="radio" style="margin-top: 20%;">
                                      <input type='radio' name='formatitem' autocomplete="off" id='formatitem' value='1' onclick="CreateItemCode()">
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                                  <label class="col-sm-10 col-form-label text-left" style="margin-top: -7px;"><?php echo $array['oldFormatItemCode'][$language]; ?></label>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class='form-group row'>
                                  <div class='radio-c' style="align-content:center">
                                    <label class="radio" style="margin-top: 20%;">
                                      <input type='radio' name='formatitem'  autocomplete="off" id='formatitem' value='2' onclick="CreateItemCode()">
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                                  <label class="col-sm-10 col-form-label text-left" style="margin-top: -7px;"><?php echo $array['newFormatItemCode'][$language]; ?></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        <div class="row" id="oldCodetype">
                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['hosname'][$language]; ?></label>
                              <select   class="form-control col-sm-7 checkblank" id="hospital" onchange="CreateItemCode()" ></select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                            <label class="col-sm-3 col-form-label "><?php echo $array['type'][$language]; ?></label>
                              <div class="col-md-8">
                                <div class='form-group row'>
                                  <select  onchange="CreateItemCode()" class="form-control col-sm-4 " id="typeLinen" onchange="CreateItemCode()" >
                                    <option value="P">Patient Shirt</option>
                                    <option value="S">Staff Uniform</option>
                                    <option value="F">Flat Sheet</option>
                                    <option value="T">Towel</option>
                                    <option value="G">Green Linen</option>
                                    <option value="O">Other</option>
                                  </select>

                                  <label class="col-sm-3 col-form-label text-right" style="margin-left: -22px;"><?php echo $array['pack'][$language]; ?></label>
                                  <select onchange="CreateItemCode()"  class="form-control col-sm-4  numonly" id="numPack" onchange="CreateItemCode()" >
                                    <option value="01">1 PCS</option>
                                    <option value="05">5 Pc</option>
                                    <option value="10">10 Pc</option>
                                    <option value="15">15 Pc</option>
                                    <option value="20">20 Pc</option>
                                    <option value="00">None</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        <div class="row">

                          <div class="col-md-6" >
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['categorymain'][$language]; ?></label>
                              <select   class="form-control col-sm-7 checkblank" id="maincatagory2" onchange="getCatagory2()" ></select>
                              <label id="rem2" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['categorysub'][$language]; ?></label>
                              <select  onchange="resetinput()"  class="form-control col-sm-7 checkblank" id="catagory2" onchange="CreateItemCode()" ></select>
                              <label id="rem3" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                          </div>

                        </div>

                        <!-- =================================================================== -->
                        <div class="row" id="xPrice">

                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['pricecus'][$language]; ?></label>
                              <input onkeyup="resetinput()"  type="text" autocomplete="off" class="form-control col-sm-7 numonly" id="CusPrice" placeholder="<?php echo $array['pricecus'][$language]; ?>">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['pricefac'][$language]; ?></label>
                              <input onkeyup="resetinput()"  type="text" autocomplete="off" class="form-control col-sm-7 numonly" id="FacPrice" placeholder="<?php echo $array['pricefac'][$language]; ?>">
                            </div>
                          </div>

                        </div>
                        <!-- =================================================================== -->
                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['item'][$language]; ?></label>
                              <input onkeyup="resetinput()"  autocomplete="off" type="text" class="form-control col-sm-7 checkblank" id="ItemName" placeholder="<?php echo $array['item'][$language]; ?>">
                              <label id="rem4" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['weight'][$language]; ?></label>
                              <input onkeyup="resetinput()"  autocomplete="off" type="text" class="form-control col-sm-7 checkblank numonly" id="Weight" placeholder="<?php echo $array['weight'][$language]; ?>">
                              <label id="rem5" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['unit'][$language]; ?></label>
                              <select onchange="resetinput()"  class="form-control col-sm-7" id="UnitName"></select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                            <label class="col-sm-3 col-form-label "><?php echo $array['sizeunit'][$language]; ?></label>
                              <select onchange="resetinput()" class="form-control col-sm-7 checkblank numonly" id="SizeCode">
                                <option value="1">SS</option>
                                <option value="2">S</option>
                                <option value="3">M</option>
                                <option value="4">L</option>
                                <option value="5">XL</option>
                                <option value="6">XXL</option>
                              </select>
                              
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row offset-3'>

                              <label class="radio " style="margin-top:2px !important; margin-left:-87px;">
                              <input type="checkbox"  id="xCenter">
                              <span class="checkmark"></span>
                              </label>
                              <label style="top: -9px;" class="col col-form-label text-left"><?php echo $array['spacial'][$language]; ?></label>
                              <label class="radio" style="margin:0px !important;">
                              
                              <input type="checkbox"  id="xItemnew">
                              <span class="checkmark"></span>
                              </label>

                              <!-- <label style="top: -9px;" class="col col-form-label text-left"><?php echo $array['newitem'][$language]; ?></label>

                              <label class="radio" style="margin:0px !important;">
                              <input type="checkbox"  id="masterItem" >
                              <span class="checkmark"></span>
                              </label> -->
                              <label style="top: -9px;" class="col col-form-label text-left"><?php echo $array['itemmas'][$language]; ?></label>

                              <label class="radio" style="margin:0px !important;">
                              <input type="checkbox"  id="tdas" >
                              <span class="checkmark"></span>
                              </label>
                              <label style="top: -9px;" class="col col-form-label text-left"><?php echo $array['tdas'][$language]; ?></label>
                            </div>
                          </div>
                          
                          <div class="col-md-6">
                            <div class='form-group row'>

                            <label class="col-sm-3 col-form-label "><?php echo $array['widthunit'][$language]; ?></label>

                              <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-3 checkblank numonly" id="QtyPerUnit" placeholder="<?php echo $array['Quality'][$language]; ?>">
                              <select class="form-control col-sm-4"   id="sUnitName" onchange="getplaceholder();"></select>
                              <label id="rem6" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                      </div>
                    </div>
                  </div> <!-- tag column 1 -->
                </div>
              </div>

              <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  <div class="container-fluid mhee">
                    <div class="card-body" style="padding:0px; margin-top:10px;">
                      <div class="row">
                        <div style="margin-left:20px;width:117px;">
                          <label><?php echo $array['unit'][$language]; ?></label>
                        </div>
                        <div style="width:160px;">
                          <div style="font-size:24px;width:155px;">
                            <select class="form-control" style="font-size:24px;" id="Unitshows" disabled>
                            </select>
                          </div>
                        </div>
                        <div style="margin-left:20px;width:90px;">
                          <label><?php echo $array['secunit'][$language]; ?></label>
                        </div>
                        <div style="width:200px;">
                          <div style="font-size:24px;width:150px;">
                            <select class="form-control" style="font-size:24px;" id="subUnit">
                            </select>
                          </div>
                        </div>
                        <div style="margin-left:20px;width:100px;">
                          <label><?php echo $array['multiply_unit'][$language]; ?></label>
                        </div>
                        <input type="text" class="form-control numonly" style="font-size:24px;width:59px;" name="mulinput" id="mulinput" placeholder="0.00">
                        <div style="margin-left:20px;width:100px;">
                          <label><?php echo $array['multiply_price'][$language]; ?></label>
                        </div>
                        <input type="text" class="form-control numonly" style="font-size:24px;width:59px;" name="priceunit" id="priceunit" placeholder="0.00">
                       
                        <div class="search_custom  col-md-1" <?php if($PmID == 3) echo 'hidden'; ?>>
                              <div class="circle4 d-flex justify-content-start">
                                <button class="btn"  onclick="AddUnit()" id="bSave_chk" disabled>
                                <i class="fas fa-save mr-3"></i><?php echo $array['save'][$language]; ?>
                                </button>
                              </div>
                        </div>

                        <div class="search_custom  col-md-2" <?php if($PmID == 3) echo 'hidden'; ?>>
                          <div class="circle3 d-flex justify-content-start">
                            <button class="btn"  onclick="DeleteUnit()" disabled id="btn_del">
                                <i class="fas fa-trash-alt mr-3"></i><?php echo $array['delete'][$language]; ?>
                            </button>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top:5px;">
                  <table class="table table-fixed table-condensed table-striped" id="TableUnit" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:18px;">
                      <tr role="row">
                        <th style='width: 5%;'>&nbsp;</th>
                        <th style='width: 5%;'><?php echo $array['no'][$language]; ?></th>
                        <th style='width: 28%;'><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 12%;'><?php echo $array['unit'][$language]; ?></th>
                        <th align='center' style='width: 19%;'><?php echo $array['secunit'][$language]; ?></th>
                        <th align='center' style='width: 14%;'><?php echo $array['multiply_unit'][$language]; ?></th>
                        <th align='center' style='width: 17%;'><?php echo $array['multiply_price'][$language]; ?></th>
                        
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:200px;">
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane" id="itemset" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  <div class="container-fluid">
                    <div class="card-body" style="padding:0px; margin-top:10px;">
                      <div class="form-inline row p-4">
                        <div class="offset-8 col-4">
                          <div class="row d-flex justify-content-end">
                            <div class="search_custom  col-3">
                              <div class="circle4 d-flex justify-content-start">
                                <button class="btn" id="btn_importMaster" onclick="openModal()"  disabled>
                                <i class="fas fa-file-import mr-3"></i><?php echo $array['import'][$language]; ?>
                                </button>
                              </div>
                            </div>
                            <div class="search_custom  col-3">
                              <div class="circle3 d-flex justify-content-start">
                                <button class="btn" id="btn_deleteMaster" onclick="deleteMaster()" disabled>
                                    <i class="fas fa-trash-alt mr-3"></i><?php echo $array['delete'][$language]; ?>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row" style="margin-top:5px;">
                  <table class="table table-fixed table-condensed table-striped" id="TableMaster" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:18px;">
                      <tr role="row">
                        <th style='width: 5%;'>&nbsp;</th>
                        <th style='width: 5%;text-align:left;'><?php echo $array['no'][$language]; ?></th>
                        <th style='width: 45%;text-align:center;'><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 45%;text-align:center;'><?php echo $array['qty'][$language]; ?></th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:200px;">
                    </tbody>
                  </table>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>

<div class="modal" id="masterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                      <label class="col-sm-4 col-form-label"><?php echo $array['categorymain'][$language]; ?></label>
                      <select class="col-sm-7 form-control" style="font-size:24px;" id="maincatagoryModal" onchange="getCatagory();"></select>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label"><?php echo $array['categorysub'][$language]; ?></label>
                      <select class="col-sm-7 form-control" style="font-size:24px;" id="catagoryModal"></select>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label"><?php echo $array['search'][$language]; ?></label>
                      <input type="text" autocomplete="off" class="form-control col-sm-7" style="font-size:24px;" name="searchitem" id="searchitemModal" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                    </div>
                    <div class="row my-2 d-flex justify-content-end">
                      <div class="col-md-2">
                        <div class="search_custom col-md-2" id="searchItem_1">
                          <div class="search_1 d-flex justify-content-start">
                            <button class="btn" onclick="ShowItemModal()">
                              <i class="fas fa-search mr-2"></i>
                              <?php echo $array['search'][$language]; ?>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="search_custom col-md-2" id="searchItem_1">
                          <div class="circle6 d-flex justify-content-start">
                            <button class="btn" onclick="AddItemMaster()">
                              <i class="fas fa-file-import mr-2"></i>
                              <?php echo $array['import'][$language]; ?>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <table class="table table-fixed table-condensed table-striped" id="TableItemModal" width="100%" cellspacing="0" role="grid" style="font-size:24px;font-family: 'THSarabunNew'">
                      <thead style="font-size:24px;">
                          <tr role="row">
                          <th style='width: 5%;'>&nbsp;</th>
                              <th style='width: 31%;' nowrap><?php echo $array['code'][$language]; ?></th>
                              <th style='width: 31%;' nowrap><?php echo $array['item'][$language]; ?></th>
                              <th style='width: 31%;text-align:center' nowrap><?php echo $array['qty'][$language]; ?></th>
                          </tr>
                      </thead>
                      <tbody id="tbody_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                      </tbody>
                  </table>
              </div>
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