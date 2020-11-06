<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

$DocnoXXX = $_GET['DocNo'];
if ($Userid == "") {
  header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}
require 'updatemouse.php';

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

  <title>shelfcount</title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <!-- <link href="../fontawesome/css/fontawesome.min.css" rel="stylesheet"> -->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">
  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">
  <link href="../assets-sign/css/jquery.signature.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>
  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="../css/signature-pad2.css">
  <script src="../dist/js/jquery-3.3.1.min.js"></script>
  <link href="../css/responsive.css" rel="stylesheet">
  <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
  <!-- =================================== -->
  <?php if ($language == 'th') { ?>
    <script src="../datepicker/dist/js/datepicker.js"></script>
  <?php } else if ($language == 'en') { ?>
    <script src="../datepicker/dist/js/datepicker-en.js"></script>
  <?php } ?>
  <!-- =================================== -->

  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
  <script src="../datepicker/dist/js/datepicker.th.js"></script>
  <script src="../fontawesome/js/fontawesome.min.js"></script>

  <script type="text/javascript">
    var summary = [];
    var xItemcode;

    $(document).ready(function(e) {
      $('#ModalSign').on('shown.bs.modal', function () {
            resizeCanvas();
            signaturePad.clear();
          });
      $(".select2").select2();
      $('#rem3').hide();
      $('#rem4').hide();
      $('#rem9').hide();
      var PmID = <?php echo $PmID; ?>;
      if (PmID == 1 || PmID == 6) {
        $('#hotpital').removeClass('icon_select');
      }
      $('#searchdocument').keyup(function(e) {
        if (e.keyCode == 13) {
          ShowDocument(1);
        }
      });
      $('#searchitem').keyup(function(e) {
        if (e.keyCode == 13) {
          ShowItem();
        }
      });
      $('#rem1').hide();
      $('#rem2').hide();
      $('.only').on('input', function() {
        this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
      });
      $("#bSend").hide();
      OnLoadPage();
      getDepartment();
      getDepartment2();
      ShowMenu();
      gettime();
      gettimesc();
    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    jqui(document).ready(function($) {


      dialogUsageCode = jqui("#dialogUsageCode").dialog({
        autoOpen: false,
        height: 680,
        width: 1200,
        modal: true,
        buttons: {
          "<?php echo $array['close'][$language]; ?>": function() {
            dialogUsageCode.dialog("close");
          }
        },
        close: function() {
          console.log("close");
        }
      });


    });
    function save_sign(dataURL)
        {
          var DocNo = $('#docno').val();
          $('#chk_sign').val(0);

          $("#ModalSign").modal('hide');
          $.ajax({
            url: "../process/UpdateSign.php",
            method: "POST",
            data: {
              DocNo: DocNo,
              SignSVG: dataURL,
              Table: "shelfcount",
              Column: "signature_web"
            },
            success: function(data) {
              swal({
              title: '',
              text: '<?php echo $array['savesuccess'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
                });
            }
          });
        }

    function find_item() {
      var DocNo = $('#docno').val();
      var DepCode = $('#department').val();
      var itemCode1 = $('#barcode').val();
      var itemCode2 = itemCode1.split(',');
      var itemCode = itemCode2[0];
      var qty = itemCode2[1];

      var data = {
        'STATUS': 'find_item',
        'DepCode': DepCode,
        'itemCode': itemCode,
        'DocNo': DocNo,
        'qty': qty
      };
      senddata(JSON.stringify(data));
      $('#barcode').val("");
    }

    function ShowMenu() {
      var DocnoXXX = "<?php echo $DocnoXXX ?>";
      if (DocnoXXX != "") {

        // alert(DocnoXXX);

        var data = {
          'STATUS': 'ShowMenu',
          'DocnoXXX': DocnoXXX
        }
        senddata(JSON.stringify(data));

      };
    }

    function OpenDialogItem() {
      var docno = $("#docno").val();
      if (docno != "") {
        $("#TableItem tbody").empty();
        // dialogItemCode.dialog( "open" );
        $('#dialogItemCode').modal('show');
      }
      ShowItem();
    }


    function OpenDialogUsageCode(itemcode) {
      xItemcode = itemcode;
      var docno = $("#docno").val();
      if (docno != "") {
        dialogItemCode.dialog("close");
        dialogUsageCode.dialog("open");
        $("#TableItem tbody").empty();
        ShowUsageCode();
      }
    }

    function ShowDetailSub() {
      var docno = $("#docno").val();
      if (docno != "") $('#dialogListDetail').modal('show');
      var data = {
        'STATUS': 'ShowDetailSub',
        'DocNo': docno
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function ShowUsageCode() {
      // var searchitem = $('#searchitem1').val();
      // alert( xItemcode );
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'ShowUsageCode',
        'docno': docno,
        'xitem': xItemcode
      };
      senddata(JSON.stringify(data));
    }
    $('.numonly').on('input', function() {
      this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
    });
    $('.numonly_dot').on('input', function() {
      this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
    });

    function DeleteItem() {
      var docno = $("#docno").val();
      var xrow = $("#checkrow:checked").val();

      xrow = xrow.split(",");
      swal({
        title: "<?php echo $array['confirm'][$language]; ?>",
        // text: "<?php echo $array['confirm1'][$language]; ?>"+xrow[1]+"<?php echo $array['confirm2'][$language]; ?>",
        text: "<?php echo $array['confirm1'][$language]; ?>",
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
          'STATUS': 'DeleteItem',
          'rowid': xrow[0],
          'DocNo': docno
        };
        senddata(JSON.stringify(data));
        $('#bDelete').attr('disabled', true);
        $('#bDelete2').addClass('opacity');
        $('#hover3').removeClass('mhee');
      })
    }

    function showprint() {
      $('#dialogprint').modal('show');
    }

    function CancelDocument() {
      var docno = $("#docno").val();
      $('#chk_sign').val(0);
      if (docno != "") {
        swal({
          title: "<?php echo $array['confirmcancel'][$language]; ?>",
          text: " " + docno + " ",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
          cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true
        }).then(result => {
          if (result.value) {
            CancelBill();
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }

    }
    //======= On create =======
    //console.log(JSON.stringify(data));
    function OnLoadPage() {
      var lang = '<?php echo $language; ?>';
      var data = {
        'STATUS': 'OnLoadPage',
        'lang': lang
      };
      senddata(JSON.stringify(data));
      $('#isStatus').val(0)
    }

    function resetradio(row) {
      var previousValue = $('.checkrow_' + row).attr('previousValue');
      var name = $('.checkrow_' + row).attr('name');
      if (previousValue == 'checked') {
        $('#bDelete').attr('disabled', true);
        $('#bDelete2').addClass('opacity');
        $('.checkrow_' + row).removeAttr('checked');
        $('.checkrow_' + row).attr('previousValue', false);
        $('.checkrow_' + row).prop('checked', false);
        // Blankinput();
      } else {
        $('#bDelete').attr('disabled', false);
        $('#bDelete2').removeClass('opacity');
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('.checkrow_' + row).attr('previousValue', 'checked');
      }
    }

    function getDepartment2() {
      $('#side').css('border-color', '');
      var Hotp = $('#side option:selected').attr("value");
      if (Hotp == '' || Hotp == undefined) {
        Hotp = '<?php echo $HptCode; ?>';
      }
      var data = {
        'STATUS': 'getDepartment2',
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));
    }


    function getDepartment() {
      var Hotp = $('#hotpital option:selected').attr("value");
      $("#side").val(Hotp);
      $('#bCreate').attr('disabled', true);
      if (Hotp == '' || Hotp == undefined) {
        Hotp = '<?php echo $HptCode; ?>';
      }

      var data = {
        'STATUS': 'getDepartment',
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));
      $('#hotpital').removeClass('border-danger');
      $('#rem3').hide();
    }

    function gettime() {
      var Hotp = $('#hotpital option:selected').attr("value");

      var data = {
        'STATUS': 'gettime',
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));

      $('#hotpital').removeClass('border-danger');
      $('#rem3').hide();
    }

    function gettimesc() {
      var Hotp = $('#hotpital option:selected').attr("value");
      var data = {
        'STATUS': 'gettimesc',
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));

      $('#hotpital').removeClass('border-danger');
      $('#rem3').hide();
    }

    function ShowDocument(selecta) {
      var DocNo = $('#docno').val();
      var process = $('#process').val();
      var Hotp = $('#side option:selected').attr("value");
      var searchdocument = $('#searchdocument').val();
      if (typeof searchdocument == 'undefined') searchdocument = "";
      var deptCode = $('#Dep2 option:selected').attr("value");
      var datepicker1 = $('#datepicker1').val();
      var lang = '<?php echo $language; ?>';
      if (Hotp == 'null') {
        Hotp = 'chkhpt';
      }
      if (process == 0) {
        process = 'chkpro';
      } else if (process == 1) {
        process = 'chkpro1';
      } else if (process == 2) {
        process = 'chkpro2';
      } else if (process == 3) {
        process = 'chkpro3';
      }
      if (deptCode == '') {
        deptCode = 'chkdep';
      }
      if (datepicker1 != "") {
        if (lang == 'th') {
          datepicker1 = datepicker1.substring(6, 10) - 543 + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
        } else if (lang == 'en') {
          datepicker1 = datepicker1.substring(6, 10) + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
        }
      } else {
        datepicker1 = "";
      }

      var data = {
        'STATUS': 'ShowDocument',
        'xdocno': searchdocument,
        'selecta': selecta,
        'deptCode': deptCode,
        'Hotp': Hotp,
        'datepicker1': datepicker1,
        'docno': DocNo,
        'process': process
      };
      console.log(data);
      senddata(JSON.stringify(data));
    }

    function ShowDocument_sub() {
      var searchdocument = $('#searchdocument').val();
      if (typeof searchdocument == 'undefined') searchdocument = "";
      var deptCode = $('#Dep2 option:selected').attr("value");
      if (typeof deptCode == 'undefined') deptCode = "1";

      var data = {
        'STATUS': 'ShowDocument_sub',
        'xdocno': searchdocument,
        'deptCode': deptCode
      };
      senddata(JSON.stringify(data));
    }

    function ShowItem() {
      var deptCode = $('#department option:selected').attr("value");
      var searchitem = $('#searchitem').val();
      var data = {
        'STATUS': 'ShowItem',
        'xitem': searchitem,
        'deptCode': deptCode

      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function dis() {
      $('.dis').attr('disabled', false);
    }

    function checkblank3() {
      $('.checkblank3').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem2').show().css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem2').hide();
        }
      });
    }

    function checkblank4() {
      $('.checkblank4').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem3').show().css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem3').hide();
        }
      });
    }

    function checkblank5() {
      $('.checkblank5').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem4').show().css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem4').hide();
        }
      });
    }

    function checkblank2() {
      $('.checkblank2').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem1').show().css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem1').hide();
        }
      });
    }

    function removeClassBorder1() {
      $('#department').removeClass('border-danger');
      $('#rem1').hide();
    }

    function removeClassBorder2() {
      var DocNo = $('#docno').val();
      var settime = $('#settime').val();
      if (settime == 0) {
        $('#setcount').val(settime);
        $('#setcount').removeClass('border-danger');
        $('#rem4').hide();
      }
      $('#settime').removeClass('border-danger');
      $('#rem2').hide();

      var data = {
        'STATUS': 'settime',
        'settime': settime,
        'DocNo': DocNo
      };
      senddata(JSON.stringify(data));
    }

    function removeClassBorder3() {
      var setcount = $('#setcount').val();
      if (setcount == 0) {
        $('#settime').val(setcount);
        $('#settime').removeClass('border-danger');
        $('#rem2').hide();
      }
      $('#setcount').removeClass('border-danger');
      $('#rem4').hide();

    }

    function SelectDocument(chk) {
      if (chk == "click") {
        $('#chk_sign').val(1);
      }
      var selectdocument = "";
      $("#checkdocno:checked").each(function() {
        selectdocument = $(this).val();
      });
      var deptCode = $('#Dep2 option:selected').attr("value");
      if (typeof deptCode == 'undefined') deptCode = "1";

      var data = {
        'STATUS': 'SelectDocument',
        'DocNo': selectdocument
      };
      senddata(JSON.stringify(data));
    }

    function unCheckDocDetail() {
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length) {
        $('input[name="checkAllDetail').prop('checked', true);
      } else {
        $('input[name="checkAllDetail').prop('checked', false);
      }
    }

    function ShowDetail() {
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'ShowDetail',
        'DocNo': docno
      };
      senddata(JSON.stringify(data));
    }

    function ShowDetailNew() {
      var docno = $("#docno").val();
      var hotpCode = $("#hotpital").val();

      var data = {
        'STATUS': 'ShowDetailNew',
        'DocNo': docno,
        'HptCode': hotpCode
      };
      senddata(JSON.stringify(data));
    }

    function CancelBill() {
      var ItemCodeArray = [];
      var Item = [];
      $(".item_array").each(function() {
        ItemCodeArray.push($(this).val());
      });
      for (var j = 0; j < ItemCodeArray.length; j++) {
        Item.push($("#item_array" + ItemCodeArray[j]).val());
      }
      var ItemCode = Item.join(',');
      var docno = $("#docno").val();
      var DepCode = $("#department option:selected").val();
      var data = {
        'STATUS': 'CancelBill',
        'DocNo': docno,
        'ItemCode': ItemCode,
        'DepCode': DepCode
      };
      senddata(JSON.stringify(data));
      $('#profile-tab').tab('show');
      ShowDocument();
      $('#docno').val("");
      $('#docdate').val("");
      $('#recorder').val("");
      $('#timerec').val("");
      $('#wTotal').val("");
      $('#department').attr('disabled', false);
      $('#settime').attr('disabled', false);
      $('#department').val('');
      $('#settime').val('');
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
      var i = 0;

      if (Sel == 1) {
        $(".checkitem:checked").each(function() {
          iArray.push($(this).val());
        });
      } else {
        $(".checkitemSub:checked").each(function() {
          iArray.push($(this).val());
        });
      }

      /* we join the array separated by the comma */

      for (var j = 0; j < iArray.length; j++) {
        if (Sel == 1)
          chkArray.push($("#RowID" + iArray[j]).val());
        else
          chkArray.push($("#RowIDSub" + iArray[j]).val());

        qtyArray.push($("#iqty" + iArray[j]).val());
        weightArray.push($("#iweight" + iArray[j]).val());
        unitArray.push($("#iUnit_" + iArray[j]).val());
      }

      var xrow = chkArray.join(',');
      var xqty = qtyArray.join(',');
      var xweight = weightArray.join(',');
      var xunit = unitArray.join(',');

      var deptCode = $('#department option:selected').attr("value");

      $('#TableDetail tbody').empty();
      var data = {
        'STATUS': 'getImport',
        'xrow': xrow,
        'xqty': xqty,
        'xweight': xweight,
        'xunit': xunit,
        'DocNo': docno,
        'Sel': Sel,
        'deptCode': deptCode
      };
      senddata(JSON.stringify(data));
      ShowItem();
      // $('#dialogItemCode').modal('toggle')
      dialogUsageCode.dialog("close");
    }

    var isChecked1 = false;
    var isChecked2 = false;

    function getCheckAll(sel) {
      if (sel == 0) {
        isChecked1 = !isChecked1;
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

    function convertUnit(rowid, selectObject) {
      var docno = $("#docno").val();
      var data = selectObject.value;
      var chkArray = data.split(",");
      var weight = $('#weight_' + chkArray[0]).val();
      var qty = $('#qty1_' + chkArray[0]).val();
      qty = oleqty * chkArray[2];
      $('#qty1_' + chkArray[0]).val(qty);

      var data = {
        'STATUS': 'updataDetail',
        'Rowid': rowid,
        'DocNo': docno,
        'unitcode': chkArray[1],
        'qty': qty
      };
      senddata(JSON.stringify(data));
    }

    function checkblank() {
      $('.checkblank').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem9').show().css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem9').hide();
        }
      });
    }

    function CreateDocument() {

      var userid = '<?php echo $Userid; ?>';
      var hotpCode = $('#hotpital option:selected').attr("value");
      var deptCode = $('#department option:selected').attr("value");
      var settime = $('#settime option:selected').attr("value");
      var setcount = $('#setcount option:selected').attr("value");
      var cycle = $("#cycle").val();

      if (deptCode == '' || deptCode == 'null' || settime == '' || hotpCode == '' || setcount == '') {
        checkblank2();
        checkblank3();
        checkblank4();
        checkblank5();
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
      } else {
        $("#TableItemDetail tbody").empty();
        swal({
          title: "<?php echo $array['confirmdoc'][$language]; ?>",
          text: "<?php echo $array['side'][$language]; ?> : " + $('#hotpital option:selected').text() + " <?php echo $array['department'][$language]; ?> : " + $('#department option:selected').text(),
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
          cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true
        }).then(result => {
          if (result.value) {
            var data = {
              'STATUS': 'CreateDocument',
              'hotpCode': hotpCode,
              'deptCode': deptCode,
              'userid': userid,
              'cycle': cycle,
              'settime': settime,
              'setcount': setcount
            };
            senddata(JSON.stringify(data));
            var word = '<?php echo $array['save'][$language]; ?>';
            var changeBtn = "<i class='fa fa-save'></i>";
            changeBtn += "<div>" + word + "</div>";
            $('#icon_edit').html(changeBtn);
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }
    }

    function canceldocno(docno) {
      swal({
        title: "<?php echo $array['confirmdelete'][$language]; ?>",
        text: "<?php echo $array['confirmdelete1'][$language]; ?>" + docno + "<?php echo $array['confirmdelete2'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
        cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        if (result.value) {
          var data = {
            'STATUS': 'CancelDocNo',
            'DocNo': docno
          };
          senddata(JSON.stringify(data));
          getSearchDocNo();
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function addnum(cnt) {
      var add = parseInt($('#iqty' + cnt).val()) + 1;
      var max = $('#qty_' + cnt).data('value');
      if (add > max) {
        $('#iqty' + cnt).val(max);
      } else {
        $('#iqty' + cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#iqty' + cnt).val()) - 1;
      if ((sub > 0) && (sub <= 500)) {
        $('#iqty' + cnt).val(sub);
      }
    }

    function keydownupdate(rowid, cnt) {
      var deptCode = $('#department option:selected').attr("value");
      var Dep = $("#Dep_").val();
      var max = $('#max' + cnt).val();
      var docno = $("#docno").val();
      var add = parseInt($('#qty1_' + cnt).val());
      var isStatus = $("#IsStatus").val();
      if (isStatus == 0) {
        if (add > max) {
          $('#qty1_' + cnt).val(max);
        } else {
          $('#qty1_' + cnt).val(add);
          var data = {
            'STATUS': 'UpdateDetailQty',
            'Rowid': rowid,
            'DocNo': docno,
            'CcQty': add,
            'max': max
          };
          senddata(JSON.stringify(data));
        }
      }
    }

    function addnum1(rowid, cnt, unitcode) {
      var deptCode = $('#department option:selected').attr("value");
      var Dep = $("#Dep_").val();
      var max = $('#max' + cnt).val();
      var docno = $("#docno").val();
      var add = parseInt($('#qty1_' + cnt).val()) + 1;
      var isStatus = $("#IsStatus").val();
      if (isStatus == 0) {
        if (add > max) {
          $('#qty1_' + cnt).val(max);
        } else {
          $('#qty1_' + cnt).val(add);
          var data = {
            'STATUS': 'UpdateDetailQty',
            'Rowid': rowid,
            'DocNo': docno,
            'CcQty': add,
            'max': max
          };
          senddata(JSON.stringify(data));
        }
      }
    }

    function subtractnum1(rowid, cnt, unitcode) {
      var deptCode = $('#department option:selected').attr("value");
      var Dep = $("#Dep_").val();
      var max = $('#max' + cnt).val();
      var docno = $("#docno").val();
      var sub = parseInt($('#qty1_' + cnt).val()) - 1;
      var isStatus = $("#IsStatus").val();
      if ((sub >= 0) && (sub <= 500)) {
        if (isStatus == 0) {
          // alert(sub);
          $('#qty1_' + cnt).val(sub);
          var data = {
            'STATUS': 'UpdateDetailQty',
            'Rowid': rowid,
            'DocNo': docno,
            'CcQty': sub,
            'max': max
          };
          senddata(JSON.stringify(data));
        }
      }
    }

    function Blankinput() {
      $('#docno').val("");
      $('#docdate').val("");
      $('#recorder').val("");
      $('#timerec').val("");
      $('#wTotal').val("");
      getDepartment();
      OnLoadPage();
    }

    function updateWeight(row, rowid) {
      var docno = $("#docno").val();
      var weight = $("#weight_" + row).val();
      var price = 0; //$("#price_"+row).val();
      var isStatus = $("#IsStatus").val();
      //alert(rowid+" :: "+docno+" :: "+weight);
      if (isStatus == 0) {
        var data = {
          'STATUS': 'UpdateDetailWeight',
          'Rowid': rowid,
          'DocNo': docno,
          'Weight': weight,
          'Price': price
        };
        senddata(JSON.stringify(data));
      }
    }

    function SaveBill(chk) {
      var docno = $("#docno").val();
      var isStatus = $("#IsStatus").val();
      var dept = $('#department').val();
      var cycle = $('#cycle').val();
      var settime = $('#settime option:selected').val();
      var setcount = $('#setcount').val();
      var input_chk = $('#input_chk').val();
      var hotpCode = $('#hotpital').val();
      var Signature = $('#Signature').val();

      if (isStatus == 1 || isStatus == 3 || isStatus == 4) {
        isStatus = 0;
        $('.inputDis').attr('disabled', false);
      } else {
        isStatus = 1;
      }
      if (settime == '') {
        checkblank3();
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
      } else {
        // if(isStatus==1 ){
        if (docno != "") {
          setTimeout(() => {

            var Item = [];
            var QtyItemArray = [];
            var WeightItemArray = [];
            var ItemCodeArray = [];



            var ScArray = [];
            var IssueArray = [];
            var ShortArray = [];
            var OverArray = [];
            var WeightArray = [];
            var PriceArray = [];


            $('input[name="Price_save"]').each(function() {
              if ($(this).val() != "") {
                PriceArray.push($(this).val());
              } else {
                PriceArray.push('');
              }
            });

            $('input[name="Sc_save"]').each(function() {
              if ($(this).val() != "") {
                ScArray.push($(this).val());
              } else {
                ScArray.push('');
              }
            });

            $('input[name="Issue_save"]').each(function() {
              if ($(this).val() != "") {
                IssueArray.push($(this).val());
              } else {
                IssueArray.push('');
              }
            });

            $('input[name="Short_save"]').each(function() {
              if ($(this).val() != "") {
                ShortArray.push($(this).val());
              } else {
                ShortArray.push('');
              }
            });

            $('input[name="Over_save"]').each(function() {
              if ($(this).val() != "") {
                OverArray.push($(this).val());
              } else {
                OverArray.push('');
              }
            });

            $('input[name="Weight_save"]').each(function() {
              if ($(this).val() != "") {
                WeightArray.push($(this).val());
              } else {
                WeightArray.push('');
              }
            });
            $(".item_array").each(function() {

              ItemCodeArray.push($(this).val());

            });

            var sc = ScArray.join(',');
            var issue = IssueArray.join(',');
            var short = ShortArray.join(',');
            var over = OverArray.join(',');
            var weight_sum = WeightArray.join(',');
            var price = PriceArray.join(',');
            var ItemCode = ItemCodeArray.join(',');



            // alert(ScArray);
            // alert(issue);
            // alert(short);
            // alert(over);
            // alert(weight_sum);



            // for(var j=0;j<ItemCodeArray.length; j++){
            //   Item.push( $("#item_array"+ItemCodeArray[j]).val() );
            // }
            $(".QtyItem").each(function() {
              QtyItemArray.push($(this).val());
            });
            var Qty = QtyItemArray.join(',');

            ////////////////////////////////////
            $(".WeightItem").each(function() {
              WeightItemArray.push($(this).val());
            });

            var Weight = WeightItemArray.join(',');

            ////////////////////////////////////

            swal({
              title: "<?php echo $array['confirmsave'][$language]; ?>",
              text: "<?php echo $array['docno'][$language]; ?>: " + docno + "",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-danger",
              confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
              cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              closeOnConfirm: false,
              closeOnCancel: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              showCancelButton: true
            }).then(result => {
              swal({
                title: '',
                text: '<?php echo $array['savesuccess'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });
              if (result.value) {
                var data = {
                  'STATUS': 'SaveBill',
                  'DocNo': docno,
                  'isStatus': isStatus,
                  'deptCode': dept,
                  'ItemCode': ItemCode,
                  'Qty': Qty,
                  'cycle': cycle,
                  'settime': settime,
                  'setcount': setcount,
                  'Weight': Weight,
                  'hotpCode': hotpCode,
                  'sc': sc,
                  'issue': issue,
                  'short': short,
                  'over': over,
                  'weight_sum': weight_sum,
                  'price': price
                };
                senddata(JSON.stringify(data));
                // $('#profile-tab').tab('show');
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', true);
                $("#bCancel").prop('disabled', true);
                $('#department').attr('disabled', false);
                $('#settime').attr('disabled', false);
                $('#department').val('');
                $('#settime').val('');
                $('#setcount').val('');
                // ShowDocument();
                if (input_chk == 1) {
                  $('#alert_par').modal('toggle');
                }
                if (Signature == 1) {
                  setTimeout(() => {
                    $('#ModalSign').modal("show");
                  }, 1500);
                  $('#ModalSign').modal({
                    backdrop: 'static',
                    keyboard: false
                  })
                }

              } else if (result.dismiss === 'cancel') {
                swal.close();
              }
            })
          }, 500);
          //}
        }
        // }else{
        //   $("#bImport2").removeClass('opacity');
        //   $("#bSave2").removeClass('opacity');
        //   $("#bImport").prop('disabled', false);
        //   $("#bSave").prop('disabled', false);
        //   var word = '<?php echo $array['save'][$language]; ?>';
        //     var changeBtn = "<i class='fa fa-save'></i>";
        //     changeBtn += "<div>"+word+"</div>";
        //     $('#icon_edit').html(changeBtn);          
        //     $("#IsStatus").val("0");
        //     $("#docno").prop('disabled', false);
        //     $("#docdate").prop('disabled', false);
        //     $("#recorder").prop('disabled', false);
        //     $("#timerec").prop('disabled', false);
        //     $("#total").prop('disabled', false);
        //     var rowCount = $('#TableItemDetail >tbody >tr').length;
        //     for (var i = 0; i < rowCount; i++) {

        //         $('#qty1_'+i).prop('disabled', false);
        //         $('#weight_'+i).prop('disabled', false);
        //         $('#price_'+i).prop('disabled', false);

        //         $('#unit'+i).prop('disabled', false);
        //     }
        // }
      }
    }

    function chk_par() {
      var chk_alert = $('#chk_Key').val();
      var ItemCodeArray = [];
      var Item = [];
      var HptCode = $('#hotpital option:selected').attr("value");
      var DepCode = $('#department option:selected').attr("value");
      var DocNo = $('#docno').val();
      $(".item_array").each(function() {
        ItemCodeArray.push($(this).val());
      });

      for (var j = 0; j < ItemCodeArray.length; j++) {
        Item.push($("#item_array" + ItemCodeArray[j]).val());
      }

      var ItemCode = Item.join(',');
      var data = {
        'STATUS': 'chk_par',
        'HptCode': HptCode,
        'DepCode': DepCode,
        'DocNo': DocNo,
        'ItemCode': ItemCode,
        'chk_alert': chk_alert
      };
      senddata(JSON.stringify(data));
    }

    function PrintData() {
      var HptCode = $('#hotpital option:selected').attr("value");
      var settime = $('#settime option:selected').val();
      var setcount = $('#setcount option:selected').val();
      var docno = $('#docno').val();
      var printdata = $('#printdata').val();
      var lang = '<?php echo $language; ?>';
      if (setcount == '' || settime == '') {
        checkblank3();
        checkblank5();
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
      } else {
        if (docno != "" && docno != undefined) {
          if (printdata == 1) {
            swal({
              title: '<?php echo $array['pleasewait'][$language]; ?>',
              text: '<?php echo $array['processing'][$language]; ?>',
              timer: 4000,
              allowOutsideClick: false
            })
            swal.showLoading();
            setTimeout(() => {
              var url = "../report/Report_Shelfcount_tc.php?DocNo=" + docno + "&lang=" + lang + "&HptCode=" + HptCode;
              $('#dialogprint').modal('toggle');
              window.open(url);
            }, 4000);

          } else if (printdata == 2) {
            swal({
              title: '<?php echo $array['pleasewait'][$language]; ?>',
              text: '<?php echo $array['processing'][$language]; ?>',
              timer: 4000,
              allowOutsideClick: false
            })
            swal.showLoading();
            setTimeout(() => {
              var url = "../report/Report_Shelfcount_tc_2.php?DocNo=" + docno + "&lang=" + lang + "&HptCode=" + HptCode;
              $('#dialogprint').modal('toggle');
              window.open(url);
            }, 4000);
          }
        } else {
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
    }

    function SendData() {
      var docno = $('#docno').val();
      swal({
        title: '',
        text: '<?php echo $array['sendlinendep'][$language]; ?>',
        type: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        timer: 2000,
        confirmButtonText: 'Ok'
      }).then(result => {
        var data = {
          'STATUS': 'SendData',
          'DocNo': docno
        };
        senddata(JSON.stringify(data));
        getSearchDocNo();
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
      }).then(function() {
        window.location.href = "../logoff.php";
      }, function(dismiss) {
        window.location.href = "../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }

    function show_btn(DocNo) {
      if (DocNo != undefined || DocNo != '') {
        $(btn_show).attr('disabled', false);
      }
    }

    function userKeyValue(row, i, max, total, cc) {
      var Order = Number($('#order' + i).val());
      $('#chk_userKey_' + i).val(1);
      $('#chk_Key').val(1);
      if (Number(max) > (Order + Number(cc))) {
        var chk = 'short';
        var Qty = Number(max) - (Order + Number(cc));
      } else {
        var chk = 'over';
        var Qty = (Order + Number(cc)) - Number(max);
      }
      var data = {
        'STATUS': 'userKeyValue',
        'Row': row,
        'Max': max,
        'chk': chk,
        'Order': Order,
        'Qty': Qty
      };
      senddata(JSON.stringify(data));
    }

    function setpacking() {
      var DocNo = $('#docno').val();
      swal({
        title: "<?php echo $array['confirmpacking'][$language]; ?>",
        text: "<?php echo $array['adddata9'][$language]; ?>",
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
          var data = {
            'STATUS': 'setpacking',
            'DocNo': DocNo
          };
          $('#bpacking').attr('disabled', true);
          $('#bpacking2').addClass('opacity');
          $('#hover9').removeClass('mhee');

          $("#bSave").prop('disabled', false);
          $("#bSave2").removeClass('opacity');
          $("#hover4").addClass('mhee');

          senddata(JSON.stringify(data));
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function draw() {
      var DocNo = $('#docno').val();
      var HptCode = $('#hotpital').val();

      swal({
        title: "<?php echo $array['confirmjaipar'][$language]; ?>",
        text: "<?php echo $array['adddata8'][$language]; ?>",
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
          var data = {
            'STATUS': 'SaveDraw',
            'DocNo': DocNo,
            'HptCode': HptCode
          };
          senddata(JSON.stringify(data));
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function SaveQty_SC() {
      var DocNo = $('#docno').val();
      var HptCode = $('#hotpital').val();
      var DepCode = $('#department').val();
      var ItemCodeArray = [];
      var QtyArray = [];
      $(".chkItem").each(function() {
        ItemCodeArray.push($(this).val());
        QtyArray.push($(this).data('qty'));
      });
      var ItemCode = ItemCodeArray.join(',');
      var Qty = QtyArray.join(',');
      var data = {
        'STATUS': 'SaveQty_SC',
        'DocNo': DocNo,
        'HptCode': HptCode,
        'DepCode': DepCode,
        'ItemCode': ItemCode,
        'Qty': Qty
      };
      senddata(JSON.stringify(data));
      $('#SaveDrawModal').modal('toggle');

    }

    function PrintstickerModal() {
      var DocNo = $('#docno').val();
      var data = {
        'STATUS': 'PrintstickerModal',
        'DocNo': DocNo
      };
      senddata(JSON.stringify(data));
    }

    function PrintSticker(ItemCode, ItemName, Qty) {
      $('#sp_ItemCode').text(ItemCode);
      $('#ItemCode').val(ItemCode);
      $('#sp_ItemName').text(ItemName);
      $('#numberSticker').val(Qty);
      $('#maxNumSticker').val(Qty);
      $('#numberModal').modal('show');
    }

    function chk_numbrtSticker(qty) {
      var max = Number($('#maxNumSticker').val());
      var Qty = Number(qty);
      if (max >= Qty || Qty <= 0) {
        $('#numberSticker').val(Qty);
      } else if (Qty > max) {
        $('#numberSticker').val(max);
      }
    }

    function dis2(row) {
      if ($('#checkrow_' + row).prop("checked") == true) {
        var countcheck2 = Number($("#countcheck").val()) + 1;
        $("#countcheck").val(countcheck2);
        $('#bSaveadd').attr('disabled', false);
        $('#bSaveadd2').removeClass('opacity');
        // $('.checkrow_'+row).attr('previousValue', 'checked');
      } else if ($('#checkrow_' + row).prop("checked") == false) {
        var countcheck3 = Number($("#countcheck").val()) - 1;
        $("#countcheck").val(countcheck3);
        if (countcheck3 == 0) {
          $('#bSaveadd').attr('disabled', true);
          $('#bSaveadd2').addClass('opacity');
          // $('.checkrow_'+row).removeAttr('checked');
          $("#countcheck").val(countcheck3);
        }
      }
    }

    function StickerPrint() {
      var lang = '<?php echo $language; ?>';
      var DocNo = $('#docno').val();
      var ItemCode = $('#ItemCode').val();
      var TotalQty = $('#maxNumSticker').val();
      var sendQty = $('#numberSticker').val();
      var og = $('#og').val();
      var of = $('#of').val();
      var url = "../report/Sticker_Shelfcount.php?DocNo=" + DocNo + "&ItemCode=" + ItemCode + "&TotalQty=" + TotalQty + "&sendQty=" + sendQty + "&lang=" + lang + "&og=" + og + "&of=" + of ;
      window.open(url);
    }

    function ShowItemAll() {
      var deptCode = $('#department option:selected').attr("value");
      var data = {
        'STATUS': 'ShowItemAll',
        'deptCode': deptCode

      };
      senddata(JSON.stringify(data));
    }

    function addTotalQty(RowID, i, iMax, itemcode) {
      var NewQty = Number($('#qty1_' + i).val()) + 1;
      var Max = Number(iMax);
      var Issue = Number($('#Issue_' + i).val());
      var Result = 0;
      var Weightitem = 0;
      var Weight2 = Number($('#Weight2_' + i).val());

      // ================================================
      Weightitem = parseFloat(Weight2 * Issue).toFixed(5);

      var weight_test = Weightitem.toString();

      var ex = weight_test.split('.');

      var res = ex[1].substr(0, 2);

      var Weightitemx = ex[0] + "." + res;
      if (NewQty <= 0) {
        var Qty = 1;
      } else if (NewQty > Max) {
        var Qty = Max;
      } else {
        var Qty = NewQty;
      }
      $('#qty1_' + i).val(Qty);
      var Max = Number($('#Par_' + i).val()) - Number($('#qty1_' + i).val());
      $('#Max_' + i).val(Max);

      if (Issue != 0) {
        if (Max >= Issue) {
          Result = Max - Issue;
          $('#Short_' + i).val(Result);
          $('#Over_' + i).val("");
          var chk = "Short";
        } else if (Issue > Max) {
          Result = Issue - Max;
          $('#Over_' + i).val(Result);
          $('#Short_' + i).val("");
          var chk = "Over";
        }
      } else if (Issue == 0) {
        $('#Over_' + i).val("");
        $('#Short_' + i).val("");
      }


      var DocNo = $('#docno').val();
      var hotpCode = $('#hotpital').val();
      var dept = $('#department').val();
      var data = {
        'STATUS': 'UpdateNewQty',
        'DocNo': DocNo,
        'RowID': RowID,
        'NewQty': Qty,
        'chk': chk,
        'Issue': Issue,
        'Result': Result,
        'Weightitem': Weightitem,
        'hotpCode': hotpCode,
        'dept': dept,
        'itemcode': itemcode
      };
      senddata(JSON.stringify(data));

    }

    function DelTotalQty(RowID, i, iMax, itemcode) {
      var NewQty = Number($('#qty1_' + i).val()) - 1;
      var Issue = Number($('#Issue_' + i).val());
      var Max = Number(iMax);
      var Result = 0;
      var Weightitem = 0;
      var Weight2 = Number($('#Weight2_' + i).val());

      // ================================================
      Weightitem = parseFloat(Weight2 * Issue).toFixed(5);

      var weight_test = Weightitem.toString();

      var ex = weight_test.split('.');

      var res = ex[1].substr(0, 2);

      var Weightitemx = ex[0] + "." + res;

      if (NewQty <= 0) {
        var Qty = 0;
      } else if (NewQty > Max) {
        var Qty = Max;
      } else {
        var Qty = NewQty;
      }
      $('#qty1_' + i).val(Qty);
      var Max = Number($('#Par_' + i).val()) - Number($('#qty1_' + i).val());
      $('#Max_' + i).val(Max);

      if (Issue != 0) {
        if (Max >= Issue) {
          Result = Max - Issue;
          $('#Short_' + i).val(Result);
          $('#Over_' + i).val("");
          var chk = "Short";
        } else if (Issue > Max) {
          Result = Issue - Max;
          $('#Over_' + i).val(Result);
          $('#Short_' + i).val("");
          var chk = "Over";
        }
      } else if (Issue == 0) {
        $('#Over_' + i).val("");
        $('#Short_' + i).val("");
      }


      var DocNo = $('#docno').val();
      var hotpCode = $('#hotpital').val();
      var dept = $('#department').val();
      var data = {
        'STATUS': 'UpdateNewQty',
        'DocNo': DocNo,
        'RowID': RowID,
        'NewQty': Qty,
        'chk': chk,
        'Issue': Issue,
        'Result': Result,
        'Weightitem': Weightitem,
        'hotpCode': hotpCode,
        'dept': dept,
        'itemcode': itemcode
      };
      senddata(JSON.stringify(data));
    }

    function KeyNewCcQty(RowID, i, iMax, itemcode) {
      var NewQty = Number($('#qty1_' + i).val());
      var Issue = Number($('#Issue_' + i).val());
      var Max = Number(iMax);
      var Result = 0;
      var Weightitem = 0;
      var Weight2 = Number($('#Weight2_' + i).val());

      // ================================================
      Weightitem = parseFloat(Weight2 * Issue).toFixed(5);

      var weight_test = Weightitem.toString();

      var ex = weight_test.split('.');

      var res = ex[1].substr(0, 2);

      var Weightitemx = ex[0] + "." + res;


      if (NewQty <= 0) {
        var Qty = 0;
        $('#qty1_' + i).val("");
      } else if (NewQty > Max) {
        var Qty = NewQty;
        $('#qty1_' + i).val(Qty);
      } else {
        var Qty = NewQty;
        $('#qty1_' + i).val(Qty);
      }
      var Max = Number($('#Par_' + i).val()) - Number($('#qty1_' + i).val());
      $('#Max_' + i).val(Max);

      if (Issue != 0) {
        if (Max >= Issue) {
          Result = Max - Issue;
          $('#Short_' + i).val(Result);
          $('#Over_' + i).val("");
          var chk = "Short";
        } else if (Issue > Max) {
          Result = Issue - Max;
          $('#Over_' + i).val(Result);
          $('#Short_' + i).val("");
          var chk = "Over";
        }
      } else if (Issue == 0) {
        $('#Over_' + i).val("");
        $('#Short_' + i).val("");
      }


      var DocNo = $('#docno').val();
      var hotpCode = $('#hotpital').val();
      var dept = $('#department').val();
      var data = {
        'STATUS': 'UpdateNewQty',
        'DocNo': DocNo,
        'RowID': RowID,
        'NewQty': Qty,
        'chk': chk,
        'Issue': Issue,
        'Result': Result,
        'Weightitem': Weightitem,
        'hotpCode': hotpCode,
        'dept': dept,
        'itemcode': itemcode
      };
      senddata(JSON.stringify(data));
    }

    function KeyNewTotalQty(RowID, i, itemcode) {
      var NewQty = Number($('#qty1_' + i).val());
      var Max = Number($('#Max_' + i).val());
      var Issue = Number($('#Issue_' + i).val());
      var Max = Number($('#Par_' + i).val()) - Number($('#qty1_' + i).val());
      var Result = 0;
      var Weightitem = 0;
      var Weight2 = Number($('#Weight2_' + i).val());

      // ================================================
      Weightitem = parseFloat(Weight2 * Issue).toFixed(5);

      var weight_test = Weightitem.toString();

      var ex = weight_test.split('.');

      var res = ex[1].substr(0, 2);

      var Weightitemx = ex[0] + "." + res;

      $('#Weight_' + i).val(Weightitemx);


      // ================================================
      if (Issue != 0) {
        if (Max >= Issue) {
          Result = Max - Issue;
          $('#Short_' + i).val(Result.toFixed(2));
          $('#Over_' + i).val("");
          var chk = "Short";
        } else if (Issue > Max) {
          Result = Issue - Max;
          $('#Over_' + i).val(Result.toFixed(2));
          $('#Short_' + i).val("");
          var chk = "Over";
        } else if (Issue == 0 || Issue == null || Issue == "") {
          $('#Short_' + i).val("");
          $('#Over_' + i).val("");
        }

      } else if (Issue == 0) {
        $('#Over_' + i).val("");
        $('#Short_' + i).val("");
      }

      var DocNo = $('#docno').val();
      var hotpCode = $('#hotpital').val();
      var dept = $('#department').val();
      var data = {
        'STATUS': 'UpdateNewQty',
        'DocNo': DocNo,
        'RowID': RowID,
        'NewQty': NewQty,
        'chk': chk,
        'Issue': Issue,
        'Result': Result,
        'Weightitem': Weightitem,
        'hotpCode': hotpCode,
        'dept': dept,
        'itemcode': itemcode,
        'i': i
      };
      senddata(JSON.stringify(data));
    }

    function Calculate(loop) {
      for (var i = 0; i < loop; i++) {
        var Max = Number($('#Par_' + i).val()) - Number($('#qty1_' + i).val());
        $('#Max_' + i).val(Max);
      }
    }

    function ChkItemInDep() {
      var DepCode = $('#department option:selected').val();
      var HptCode = $('#hotpital option:selected').val();

      if (DepCode != "null" || HptCode != "null") {
        var data = {
          'STATUS': 'ChkItemInDep',
          'DepCode': DepCode,
          'HptCode': HptCode
        };
        senddata(JSON.stringify(data));
      } else {
        $('#bCreate').attr('disabled', true);
      }

    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/shelfcount.php';
      $.ajax({
        url: URL,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }

          if (temp["status"] == 'success') {
            if (temp["form"] == 'OnLoadPage') {
              // $("button").css("color", "red");
              var PmID = <?php echo $PmID; ?>;
              var HptCode = '<?php echo $HptCode; ?>';
              if (temp[0]['PmID'] != 2 && temp[0]['PmID'] != 3 && temp[0]['PmID'] != 7 && temp[0]['PmID'] != 5) {
                var Str1 = "<option value='null' selected><?php echo $array['selecthospital'][$language]; ?></option>";
              } else {
                var Str1 = "";
                $('#side').attr('disabled', true);
                $('#side').addClass('icon_select');
              }
              for (var i = 0; i < temp["Row"]; i++) {
                var Str = "<option value=" + temp[i]['HptCode'] + " id='getHot_" + i + "'>" + temp[i]['HptName'] + "</option>";
                Str1 += "<option value=" + temp[i]['HptCode1'] + ">" + temp[i]['HptName1'] + "</option>";
              }
              $("#hotpital").append(Str1);
              $("#side").append(Str1);
            } else if (temp["form"] == 'getDepartment') {
              $("#department").empty();
              var Str2 = "<option value='null'><?php echo $array['selectdep'][$language]; ?></option>";
              for (var i = 0; i < temp['row']; i++) {
                Str2 += "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
              }
              $("#department").append(Str2);
              gettime();
              gettimesc();
            } else if (temp["form"] == 'gettime') {
              $("#settime").empty();
              var StrTrX = "<option value='' selected><?php echo $array['selectCycle'][$language]; ?></option>";
              for (var i = 0; i < temp['row']; i++) {
                StrTrX += "<option value=" + temp[i]['ID'] + ">" + temp[i]['time_value'] + "</option>";
              }
              StrTrX += "<option value='0' >Extra</option>";
              $("#settime").append(StrTrX);
            } else if (temp["form"] == 'gettimesc') {
              $("#setcount").empty();
              var StrTrX = "<option value='' selected><?php echo $array['selectsc'][$language]; ?></option>";
              for (var i = 0; i < temp['row']; i++) {
                StrTrX += "<option value=" + temp[i]['ID'] + ">" + temp[i]['time_value'] + "</option>";
              }
              StrTrX += "<option value='0' >Extra</option>";
              $("#setcount").append(StrTrX);
            } else if (temp["form"] == 'getDepartment2') {
              $("#Dep2").empty();
              var Str2 = "<option value=''><?php echo $array['selectdep'][$language]; ?></option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                Str2 += "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
              }
              $("#Dep2").append(Str2);
            } else if (temp["form"] == 'ChkItemInDep') {
              if (temp['Count'] > 0) {
                $('#bCreate').attr('disabled', false);
              } else {
                $('#bCreate').attr('disabled', true);
              }
            } else if ((temp["form"] == 'CreateDocument')) {

              // check depcode null
              if (temp['DepCodeNULL'] == 'NULL') {
                swal({
                  title: '',
                  text: 'เกิดข้อผิดพลาดของระบบ กรุณาสร้างเอกสารใหม่อีกครั้ง',
                  type: 'warning',
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1200,
                });
              } else {
                $('#bCreate').attr('disabled', true);
                $('#hover1').removeClass('mhee');
                $('#bCreate2').addClass('opacity');
                $("#TableItemDetail tbody").empty();
                $("#docno").val(temp[0]['DocNo']);
                $("#docdate").val(temp[0]['DocDate']);
                $("#recorder").val(temp[0]['Record']);
                $("#timerec").val(temp[0]['RecNow']);
                $("#Signature").val(temp[0]['Signature']);
                $("#completed").val('on process');
                $('#bCancel').attr('disabled', false);
                $('#bSave').attr('disabled', false);
                $('#bImport').attr('disabled', false);
                $('#bPrint').attr('disabled', false);
                $('#bPrint2').removeClass('opacity');
                $('#hover7').addClass('mhee');
                $('#barcode').attr('disabled', false);
                $('#department').attr('disabled', true);
                $('#settime').attr('disabled', true);
                $('#department').addClass('icon_select');
                $('#settime').addClass('icon_select');
                $('#setcount').attr('disabled', true);
                $('#setcount').addClass('icon_select');

                if (temp[0]['settime'] == 0) {
                  $('#bpacking').attr('disabled', false);
                  $('#bpacking2').removeClass('opacity');
                  $('#hover9').addClass('mhee');

                  $("#bSave").prop('disabled', true);
                  $("#bSave2").addClass('opacity');
                  $("#hover4").removeClass('mhee');
                } else {
                  $('#bpacking').attr('disabled', true);
                  $('#bpacking2').addClass('opacity');
                  $('#hover9').removeClass('mhee');

                  $("#bSave").prop('disabled', false);
                  $("#bSave2").removeClass('opacity');
                  $("#hover4").addClass('mhee');
                }

                $('#hover2').addClass('mhee');
                $('#hover5').addClass('mhee');
                $('#bImport2').removeClass('opacity');
                $('#bCancel2').removeClass('opacity');
                swal({
                  title: "<?php echo $array['createdocno'][$language]; ?>",
                  text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                  type: "success",
                  showCancelButton: false,
                  timer: 1000,
                  confirmButtonText: 'Ok',
                  showConfirmButton: false
                });
                ShowDetailNew();
                parent.OnLoadPage();
              }
            } else if (temp["form"] == 'ShowDocument') {
              setTimeout(function() {
                parent.OnLoadPage();
              }, 500);
              $("#TableDocument tbody").empty();
              $("#TableItemDetail tbody").empty();
              if (temp['Count'] > 0) {
                $("#docno").val("");
                $("#docdate").val("");
                $("#recorder").val("");
                $("#timerec").val("");
                $("#completed").val("");
                $("#department").val('null');
                $("#department").prop('disabled', false);
                $("#department").removeClass('icon_select');
                $("#settime").val("");
                $("#settime").prop('disabled', false);
                $("#settime").removeClass('icon_select');
                $("#setcount").val("");
                $("#setcount").prop('disabled', false);
                $("#setcount").removeClass('icon_select');
                $("#docno").prop('disabled', false);
                $("#docdate").prop('disabled', false);
                $("#recorder").prop('disabled', false);
                $("#timerec").prop('disabled', false);

                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                  var rowCount = $('#TableDocument >tbody >tr').length;
                  var chkDoc = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno' onclick='show_btn(\"" + temp[i]['DocNo'] + "\");' value='" + temp[i]['DocNo'] + "' ><span class='checkmark'></span></label>";
                  var Status = "";
                  var Style = "";
                  if (temp[i]['IsStatus'] == 1) {
                    Status = "on process";
                    Style = "style='width: 10%;color: #3399ff;'";
                  } else {
                    Status = "on process";
                    Style = "style='width: 10%;color: #3399ff;'";
                  }
                  if (temp[i]['IsStatus'] == 9) {
                    Status = "cancel";
                    Style = "style='width: 10%;color: #ff0000;'";
                  }
                  if (temp[i]['IsStatus'] == 3) {
                    Status = "completed";
                    Style = "style='width: 10%;color: #20B80E;'";
                  } else if (temp[i]['IsStatus'] == 4) {
                    Status = "completed";
                    Style = "style='width: 10%;color: #20B80E;'";
                  }

                  $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%; overflow: hidden; text-overflow: ellipsis;' nowrap>" + chkDoc + "</td>" +
                    "<td style='width: 15%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['DocDate'] + "'>" + temp[i]['DocDate'] + "</td>" +
                    "<td style='width: 15%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['DocNo'] + "'>" + temp[i]['DocNo'] + "</td>" +
                    "<td style='width: 15%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['DepName'] + "'>" + temp[i]['DepName'] + "</td>" +
                    "<td style='width: 15%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['Record'] + "'>" + temp[i]['Record'] + "</td>" +
                    "<td style='width: 10%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['RecNow'] + "'>" + temp[i]['RecNow'] + "</td>" +
                    "<td style='width: 10%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['TimeName'] + "'>" + temp[i]['TimeName'] + "</td>" +
                    "<td " + Style + "nowrap>" + Status + "</td>" +
                    "</tr>";

                  if (rowCount == 0) {
                    $("#TableDocument tbody").append($StrTr);
                  } else {
                    $('#TableDocument tbody:last-child').append($StrTr);
                  }
                }
              } else {
                if (temp[0]['chkhpt'] == "chkhpt") {
                  swal({
                    title: '',
                    text: 'กรุณาเลือกโรงพยาบาล',
                    type: 'warning',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 1200,
                  });

                } else {
                  swal({
                    title: '',
                    text: '<?php echo $array['notfoundmsg'][$language]; ?>',
                    type: 'warning',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 1200,
                  });
                }
                $("#TableDocument tbody").empty();
                var Str = "<tr width='100%'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                $("#TableDocument tbody").append(Str);
              }
            } else if (temp["form"] == 'ShowDocument_sub') {
              $("#TableDocument tbody").empty();
              $("#TableItemDetail tbody").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDocument >tbody >tr').length;
                var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='" + temp[i]['DocNo'] + "' >";
                var Status = "";
                var Style = "";
                if (temp[i]['IsStatus'] == 1) {
                  Status = "<?php echo $array['savesuccess'][$language]; ?>";
                  Style = "style='width: 10%;color: #20B80E;'";
                } else {
                  Status = "<?php echo $array['draft'][$language]; ?>";
                  Style = "style='width: 10%;color: #3399ff;'";
                }
                if (temp[i]['IsStatus'] == 2) {
                  Status = "<?php echo $array['cancelbill'][$language]; ?>";
                  Style = "style='width: 10%;color: #ff0000;'";
                }

                $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "'>" +
                  "<td style='width: 10%;'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['DocDate'] + "</td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['DocNo'] + "</td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['DepName'] + "</td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['Record'] + "</td>" +
                  "<td style='width: 10%;'nowrap>" + temp[i]['RecNow'] + "</td>" +
                  "<td style='width: 10%;'nowrap>" + temp[i]['Total'] + "</td>" +
                  "<td " + Style + ">" + Status + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDocument tbody").append($StrTr);
                } else {
                  $('#TableDocument tbody:last-child').append($StrTr);
                }
              }
            } else if (temp["form"] == 'SelectDocument') {
              // $("#settime").empty();
              var StrTrX = "<option value='' selected><?php echo $array['selectCycle'][$language]; ?></option>";
              for (var i = 0; i < temp['row']; i++) {
                StrTrX += "<option value=" + temp[i]['ID'] + ">" + temp[i]['time_value'] + "</option>";
              }
              StrTrX += "<option value='0' >Extra</option>";
              $("#settime").html(StrTrX);

              var StrTrX2 = "<option value='' selected><?php echo $array['selectCycle'][$language]; ?></option>";
              for (var i = 0; i < temp['row2']; i++) {
                StrTrX2 += "<option value=" + temp[i]['ID2'] + ">" + temp[i]['time_value2'] + "</option>";
              }
              StrTrX2 += "<option value='0' >Extra</option>";
              $("#setcount").html(StrTrX2);

              var StrTrX3 = "<option value='' selected><?php echo $array['selectCycle'][$language]; ?></option>";
              for (var i = 0; i < temp['row3']; i++) {
                StrTrX3 += "<option value=" + temp[i]['DepCode2'] + ">" + temp[i]['DepName2'] + "</option>";
              }
              $("#department").html(StrTrX3);

              // ===========DATE ITEM =======
              var PmID = <?php echo $PmID; ?>;
              var d = new Date();
              var month = d.getMonth() + 1;
              var day = d.getDate();
              var output = d.getFullYear() + '-' +
                (('' + month).length < 2 ? '0' : '') + month + '-' +
                (('' + day).length < 2 ? '0' : '') + day;

              if ((PmID != 1 && PmID != 6)) {
                if (output == temp[0]['DocDateChk']) {
                  $("#hover5").addClass('mhee');
                  $("#bCancel").prop('disabled', false);
                  $("#bCancel2").removeClass('opacity');
                } else {
                  $("#hover5").removeClass('mhee');
                  $("#bCancel").prop('disabled', true);
                  $("#bCancel2").addClass('opacity');
                }
              } else {
                $("#hover5").addClass('mhee');
                $("#bCancel").prop('disabled', false);
                $("#bCancel2").removeClass('opacity');
              }
              // =========================================================
              $('#bCreate').attr('disabled', true);
              $('#hover1').removeClass('mhee');
              $('#bCreate2').addClass('opacity');

              if (temp[0]['IsStatus'] == 4 || temp[0]['IsStatus'] == 3) {
                $("#completed").val('completed');
              } else if (temp[0]['IsStatus'] == 0 || temp[0]['IsStatus'] == 1) {
                $("#completed").val('on process');
              } else if (temp[0]['IsStatus'] == 9) {
                $("#completed").val('cancel');
              }
              $("#hotpital").val(temp[0]['HptName']);
              $("#hotpital").prop('disabled', true);
              $('#hotpital').addClass('icon_select');
              if (temp[0]['HptName'] != "") {}
              $('#home-tab').tab('show');
              $("#TableItemDetail tbody").empty();

              $("#Signature").val(temp[0]['Signature']);
              $("#docno").val(temp[0]['DocNo']);
              $("#docdate").val(temp[0]['DocDate']);
              $("#recorder").val(temp[0]['Record']);
              $("#timerec").val(temp[0]['RecNow']);
              $("#IsStatus").val(temp[0]['IsStatus']);
              $("#department").val(temp[0]['DepCode']);
              $("#cycle").val(temp[0]['CycleTime']);
              $("#settime").val(temp[0]['DeliveryTime']);
              $("#setcount").val(temp[0]['ScTime']);
              $('#department').attr('disabled', true);
              $('#department').addClass('icon_select');
              $('#settime').attr('disabled', true);
              $('#setcount').attr('disabled', true);
              $('#docdate').attr('disabled', true);
              $('#settime').addClass('icon_select');
              $('#setcount').addClass('icon_select');
              if (temp[0]['IsStatus'] == 0) {
                if (temp[0]['ScTime'] == 0) {
                  $('#bpacking').attr('disabled', false);
                  $('#bpacking2').removeClass('opacity');
                  $('#hover9').addClass('mhee');

                  $("#bSave").prop('disabled', true);
                  $("#bSave2").addClass('opacity');
                  $("#hover4").removeClass('mhee');
                } else {
                  $('#bpacking').attr('disabled', true);
                  $('#bpacking2').addClass('opacity');
                  $('#hover9').removeClass('mhee');

                  $("#bSave").prop('disabled', false);
                  $("#bSave2").removeClass('opacity');
                  $("#hover4").addClass('mhee');
                }
                var word = '<?php echo $array['save'][$language]; ?>';
                var changeBtn = "<i class='fa fa-save'></i>";
                changeBtn += "<div>" + word + "</div>";
                $('#icon_edit').html(changeBtn);
                $("#bImport").prop('disabled', false);
                $("#bCancel").prop('disabled', false);
                // $("#bdetail").prop('disabled', true);
                $("#barcode").prop('disabled', false);
                $('#bPrint').attr('disabled', false);
                $('#bPrint2').removeClass('opacity');
                $('#hover7').addClass('mhee');
                $("#hover2").addClass('mhee');
                $("#hover5").addClass('mhee');
                $("#settime").prop('disabled', false);
                $("#setcount").prop('disabled', false);
                $('#settime').removeClass('icon_select');
                $('#setcount').removeClass('icon_select');
                $("#bImport2").removeClass('opacity');
                $("#bCancel2").removeClass('opacity');
                $("#completed").val('on process');
              } else if (temp[0]['IsStatus'] == 1 || temp[0]['IsStatus'] == 2 || temp[0]['IsStatus'] == 3 || temp[0]['IsStatus'] == 4) {

                if (temp[0]['IsStatus'] == 1) {

                  $("#completed").val('on process');
                } else if (temp[0]['IsStatus'] == 2 || temp[0]['IsStatus'] == 3 || temp[0]['IsStatus'] == 4) {
                  $("#completed").val('completed');
                }
                $('#icon_edit').html(changeBtn);
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', true);
                $("#barcode").prop('disabled', false);
                $("#hover4").removeClass('mhee');
                $("#bSave2").addClass('opacity');
                $('#bPrint').attr('disabled', false);
                $('#bPrint2').removeClass('opacity');
                $('#hover7').addClass('mhee');
                $('#bPrintsticker').attr('disabled', false);
                $('#bPrintsticker2').removeClass('opacity');
                $('#hover8').addClass('mhee');
                if (temp[0]['jaipar'] == 1) {
                  $('#bdetail').attr('disabled', true);
                  $("#bdetail2").addClass('opacity');
                  $("#hover6").removeClass('mhee');
                } else if (temp[0]['jaipar'] == 0) {
                  $('#bdetail').attr('disabled', false);
                  $("#bdetail2").removeClass('opacity');
                  $("#hover6").addClass('mhee');
                }

              } else {
                $('#bPrint').attr('disabled', false);
                $('#bPrint2').removeClass('opacity');
                $('#hover6').addClass('mhee');

                $('#bPrintsticker').attr('disabled', true);
                $('#bPrintsticker2').addClass('opacity');
                $('#hover8').removeClass('mhee');

                $('#bpacking').attr('disabled', true);
                $('#bpacking2').addClass('opacity');
                $('#hover9').removeClass('mhee');

                $('#bdetail').attr('disabled', true);
                $("#bdetail2").addClass('opacity');
                $("#hover6").removeClass('mhee');

                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', true);
                $("#bCancel").prop('disabled', true);
                // $("#bdetail").prop('disabled', true);
                $("#hover2").removeClass('mhee');
                $("#hover3").removeClass('mhee');
                $("#hover4").removeClass('mhee');
                $("#hover5").removeClass('mhee');
                // $("#hover6").removeClass('mhee');
                $("#bImport2").addClass('opacity');
                $("#bDelete2").addClass('opacity');
                $("#bSave2").addClass('opacity');
                $("#bCancel2").addClass('opacity');
                // $("#bdetail2").addClass('opacity');

                $("#docno").prop('disabled', true);
                $("#docdate").prop('disabled', true);
                $("#recorder").prop('disabled', true);
                $("#timerec").prop('disabled', true);
                $("#total").prop('disabled', true);

                $('#qty1_' + i).prop('disabled', true);
                $('#weight_' + i).prop('disabled', true);
                $('#price_' + i).prop('disabled', true);

                $('#unit' + i).prop('disabled', true);
              }
              ShowDetailNew();
            } else if (temp["form"] == 'ShowMenu') {
              $('#home-tab').tab('show')
              $("#TableItemDetail tbody").empty();
              $("#docno").val(temp[0]['DocNo']);
              $("#department").val(temp['DepCode']);
              $("#docdate").val(temp[0]['DocDate']);
              $("#recorder").val(temp[0]['Record']);
              $("#timerec").val(temp[0]['RecNow']);
              $("#IsStatus").val(temp[0]['IsStatus']);
              $("#cycle").val(temp[0]['CycleTime']);

              if (temp[0]['IsStatus'] == 0) {
                var word = '<?php echo $array['save'][$language]; ?>';
                var changeBtn = "<i class='fa fa-save'></i>";
                changeBtn += "<div>" + word + "</div>";
                $('#icon_edit').html(changeBtn);
                $("#bImport").prop('disabled', false);
                $("#bSave").prop('disabled', false);
                $("#bCancel").prop('disabled', false);
              } else if (temp[0]['IsStatus'] == 1) {
                var word = '<?php echo $array['edit'][$language]; ?>';
                var changeBtn = "<i class='fas fa-edit'></i>";
                changeBtn += "<div>" + word + "</div>";
                $('#icon_edit').html(changeBtn);
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', false);
                $("#bCancel").prop('disabled', true);
              } else {
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', true);
                $("#bCancel").prop('disabled', true);

                $("#docno").prop('disabled', true);
                $("#docdate").prop('disabled', true);
                $("#recorder").prop('disabled', true);
                $("#timerec").prop('disabled', true);
                $("#total").prop('disabled', true);

                $('#qty1_' + i).prop('disabled', true);
                $('#weight_' + i).prop('disabled', true);
                $('#price_' + i).prop('disabled', true);

                $('#unit' + i).prop('disabled', true);
              }
              ShowDetail();
            } else if (temp["form"] == 'getImport' || temp["form"] == 'ShowDetail') {
              $("#TableItemDetail tbody").empty();
              var isStatus = $("#IsStatus").val();
              var st1 = "style='font-size:24px;margin-left:20px; width:130px;'";
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItemDetail >tbody >tr').length;
                var chkDoc = "<div class='form-inline'><label class='radio' style='margin:0px!important;'><input type='radio' name='checkrow' id='checkrow' class='checkrow_" + i + "' value='" + temp[i]['RowID'] + "," + temp[i]['ItemName'] + "'  onclick='resetradio(\"" + i + "\")'><span class='checkmark'></span><label style='margin-left:27px; '> " + (i + 1) + "</label></label></div>";
                var chkunit = "<select onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control' style='font-size:24px;' id='unit" + i + "'>";

                for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                  if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode2'])
                    chkunit += "<option selected value=" + i + "," + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + "," + temp['Multiply_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  else
                    chkunit += "<option value=" + i + "," + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + "," + temp['Multiply_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                }

                chkunit += "</select>";

                var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn_mhee ' style='height:40px;width:32px;' onclick='subtractnum1(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['UnitCode2'] + "\")'>-</button><input class='form-control numonly QtyItem' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_" + i + "' value='" + temp[i]['CcQty'] + "' onkeyup='if(this.value > " + temp[i]['ParQty'] + "){this.value=" + temp[i]['ParQty'] + "}else if(this.value<0){this.value=0}' onblur='keydownupdate(\"" + temp[i]['RowID'] + "\",\"" + i + "\")' ><button class='btn btn_mheesave' style='height:40px;width:32px;' onclick='addnum1(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['UnitCode2'] + "\")'>+</button></div>";

                var Order = "<input class='form-control numonly' id='order" + i + "' type='text' style='text-align:center;' value='" + (temp[i]['TotalQty']) + "' onkeyup='userKeyValue(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['ParQty'] + "\",\"" + temp[i]['TotalX'] + "\",\"" + temp[i]['CcQty'] + "\")      ;'>";

                var Max = "<input class='form-control' id='max" + i + "' type='text' style='text-align:center;' value='" + (temp[i]['ParQty']) + "' disabled>";

                var Weight = "";

                var Price = "";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                  "<td style='width: 9%;'nowrap>" + chkDoc + "</td>" +
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 18%;'nowrap><input type='hidden' id='item_array" + temp[i]['ItemCode'] + "' value='" + temp[i]['ItemCode'] + "' class='item_array'></input>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 22%;'nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 14%;'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Max + "</td>" +
                  "<td style='width: 15%;'nowrap>" + Qty + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Order + "</td>" +
                  "<td style='width: 9%;'nowrap hidden>" + temp[i]['TotalX'] + "</td>" +
                  "<td hidden><input type='text' value='0' id='chk_userKey_" + i + "'></td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $('#bSaveadd').attr('disabled', true);
                  $('#bSaveadd2').addClass('opacity');
                  $("#countcheck").val("0");

                  $("#TableItemDetail tbody").append($StrTR);
                } else {
                  $('#TableItemDetail tbody:last-child').append($StrTR);
                }
                if (isStatus == 0) {
                  // $("#docno").prop('disabled', false);
                  // $("#docdate").prop('disabled', false);
                  // $("#recorder").prop('disabled', false);
                  // $("#timerec").prop('disabled', false);
                  // $("#total").prop('disabled', false);

                  $('#qty1_' + i).prop('disabled', false);
                  $('#weight_' + i).prop('disabled', false);
                  $('#price_' + i).prop('disabled', false);
                  $('#price_' + i).prop('disabled', false);

                  $('#unit' + i).prop('disabled', false);
                } else {
                  $("#docno").prop('disabled', true);
                  $("#docdate").prop('disabled', true);
                  $("#recorder").prop('disabled', true);
                  $("#timerec").prop('disabled', true);
                  $("#total").prop('disabled', true);

                  $('#qty1_' + i).prop('disabled', true);
                  $('#weight_' + i).prop('disabled', true);
                  $('#price_' + i).prop('disabled', true);

                  $('#unit' + i).prop('disabled', true);
                }
              }
              $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
              });
            } else if ((temp["form"] == 'ShowItem')) {
              var st1 = "style='font-size:24px;margin-right:0px!important; width:150px;'";
              var st2 = "style='height:40px;width:60px; font-size:20px; margin-left:3px; margin-right:3px; text-align:center;'"
              $("#TableItem tbody").empty();
              if (temp["Row"] > 0) {
                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                  var rowCount = $('#TableItem >tbody >tr').length;

                  var chkunit = "<select " + st1 + " class='form-control' id='iUnit_" + i + "'>";
                  var nUnit = "";

                  for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                    if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode']) {
                      chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                      nUnit = temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j];
                    } else {
                      chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                      nUnit = temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j];
                    }
                  }
                  chkunit += "</select>";

                  var chkDoc = "<input type='checkbox' name='checkitem'  id='checkrow_" + i + "' class='checkitem' onclick='dis2(\"" + i + "\")' value='" + i + "'><input type='hidden' id='RowID" + i + "' value='" + temp[i]['RowID'] + "'>";
                  var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger ' style='height:40px;width:32px;' onclick='subtractnum(\"" + i + "\")'>-</button><input class='form-control numonly_dot' " + st2 + " id='iqty" + i + "' value='1' onkeyup='if(this.value>" + temp[i]['Qty'] + "){this.value=" + temp[i]['Qty'] + "}else if(this.value<0){this.value=0}'><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\"" + i + "\")'>+</button></div>";

                  var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' style='font-size:20px;height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" + i + "' value='0' ></div>";

                  $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 25%;'nowrap>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                    // "<td style='width: 20%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''nowrap>"+temp[i]['ItemCode']+"</td>"+
                    "<td style='width: 36%;cursor: pointer;' onclick='OpenDialogUsageCode(\"" + temp[i]['ItemCode'] + "\")''nowrap>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 23%;' nowrap><center>" + chkunit + "</center></td>" +
                    "<td style='width: 15%;' id='qty_" + i + "' data-value='" + temp[i]['Qty'] + "'nowrap>" + Qty + "</td>" +
                    // "<td style='width: 15%;'nowrap>"+Weight+"</td>"+
                    "</tr>";
                  if (rowCount == 0) {
                    $("#TableItem tbody").append($StrTR);
                  } else {
                    $('#TableItem tbody:last-child').append($StrTR);
                  }
                }
                $('.numonly').on('input', function() {
                  this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                });
                $('.numonly_dot').on('input', function() {
                  this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
                });
              } else {
                $('#TableItem tbody').empty();
                var Str = "<tr width='100%'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                $('#TableItem tbody:last-child').append(Str);
              }
            } else if ((temp["form"] == 'ShowUsageCode')) {
              var st1 = "style='font-size:18px;margin-left:3px; width:150px;font-size:24px;'";
              var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-size:32px;'"
              $("#TableUsageCode tbody").empty();
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableUsageCode >tbody >tr').length;

                var chkunit = "<select " + st1 + " onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control' style='font-size:32px;' id='iUnit_" + i + "'>";

                for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                  if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode'])
                    chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  else
                    chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                }
                chkunit += "</select>";

                var chkDoc = "<input type='checkbox' name='checkitemSub' id='checkitemSub' value='" + i + "'><input type='hidden' id='RowIDSub" + i + "' value='" + temp[i]['RowID'] + "'>";

                //var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" + i + "' value='0' ></div>";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                  "<td style='width: 10%;'nowrap>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                  "<td style='width: 20%;'nowrap>" + temp[i]['UsageCode'] + "</td>" +
                  "<td style='width: 40%;'nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 15%;'nowrap>" + chkunit + "</td>" +
                  "<td style='width: 13%;' align='center'nowrap>1</td>" +
                  "</tr>";
                if (rowCount == 0) {
                  $("#TableUsageCode tbody").append($StrTR);
                } else {
                  $('#TableUsageCode tbody:last-child').append($StrTR);
                }
              }
            } else if ((temp["form"] == 'ShowDetailSub')) {
              //console.log(temp);
              var st1 = "style='font-size:18px;margin-left:20px; width:100px;'";
              var st2 = "style='height:40px;width:70px; margin-left:3px; margin-right:3px; text-align:center;'"
              $("#TableItemListDetailSub tbody").empty();

              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItemListDetailSub >tbody >tr').length;
                //console.log(rowCount);
                var Str = "<tr>" +
                  "<td style='width: 10%;'nowrap><label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                  "<td style='width: 25%;'nowrap>" + temp[i]['UsageCode'] + "</td>" +
                  "<td style='width: 50%;'nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "</tr>";
                // console.log(Str);
                if (rowCount == 0) {
                  $("#TableItemListDetailSub tbody").append(Str);
                } else {
                  $('#TableItemListDetailSub tbody:last-child').append(Str);
                }
              }
            } else if ((temp["form"] == 'chk_par')) {
              result = '';
              if (temp["Row"] > 0) {
                for (var i = 0; i < temp['Row']; i++) {
                  result += "<tr>" +
                    '<td nowrap style="width: 5%;">' + (i + 1) + '</td>' +
                    '<td nowrap style="width: 20%;" class="text-left">' + temp[i]['ItemCode'] + '</td>' +
                    '<td nowrap style="width: 25%;" class="text-left">' + temp[i]['ItemName'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['ParQty'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['TotalQty2'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['CcQty'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['TotalQty'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['OverPar'] + '</td>' +
                    "</tr>";
                }
                $("#detail_par").html(result);
                $('#alert_par').modal('show');
                $('#input_chk').val(1);
              } else if (temp["Row"] == "No") {
                SaveBill(1);
                // $('#alert_par').modal('toggle');
              }
            } else if ((temp["form"] == 'SaveDraw')) {
              if (temp['chk'] == "OK") {
                if (temp['jaipar'] == 1) {
                  $('#bdetail').attr('disabled', true);
                  $("#bdetail2").addClass('opacity');
                  $("#hover6").removeClass('mhee');

                } else if (temp['jaipar'] == 0) {
                  $('#bdetail').attr('disabled', false);
                  $("#bdetail2").removeClass('opacity');
                  $("#hover6").addClass('mhee');
                }
                swal({
                  title: '',
                  text: '<?php echo $array['savesuccess'][$language]; ?>',
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1500,
                });
                $('#profile-tab').tab('show');
                ShowDocument();
              } else {
                result = '';
                if (temp["CountRow"] > 0) {
                  for (var i = 0; i < temp['CountRow']; i++) {
                    if (temp[i]['QtyCenter'] == 0) {
                      var chkItem = "<input type='checkbox' disabled title='<?php echo $array['empItem'][$language]; ?>' class='chkItem' value='" + temp[i]['ItemCode'] + "' data-qty='0'>";
                    } else if (temp[i]['QtyCenter'] < temp[i]['TotalQty']) {
                      var chkItem = "<input type='checkbox' name='chkItem' class='chkItem' id='chkItem_" + i + "' value='" + temp[i]['ItemCode'] + "' data-qty='" + temp[i]['QtyCenter'] + "'>";
                    }
                    result += "<tr>" +
                      '<td nowrap style="width: 5%;">' + chkItem + '</td>' +
                      '<td nowrap style="width: 25%;" class="text-left">' + temp[i]['ItemCode'] + '</td>' +
                      '<td nowrap style="width: 30%;" class="text-left">' + temp[i]['ItemName'] + '</td>' +
                      '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['ParQty'] + '</td>' +
                      '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['CcQty'] + '</td>' +
                      '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['TotalQty'] + '</td>' +
                      '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['QtyCenter'] + '</td>' +
                      "</tr>";
                  }
                  $("#detailQty").html(result);
                }
                $('#SaveDrawModal').modal('show');
              }
            } else if ((temp["form"] == 'SaveQty_SC')) {
              swal({
                title: '',
                text: '<?php echo $array['savesuccess'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });
              if (temp['jaipar'] == 1) {
                $('#bdetail').attr('disabled', true);
                $("#bdetail2").addClass('opacity');
                $("#hover6").removeClass('mhee');

              } else if (temp['jaipar'] == 0) {
                $('#bdetail').attr('disabled', false);
                $("#bdetail2").removeClass('opacity');
                $("#hover6").addClass('mhee');
              }
              $('#SaveDrawModal').modal('toggle');
              $('#profile-tab').tab('show');
              ShowDocument();
            } else if ((temp["form"] == 'UpdateNewQty')) {

              if (temp['Issue'] == 0 && temp['Weightitemx'] == 0) {
                $('#Issue_' + temp['i']).removeClass('border border-success');
              } else if (temp['Issue'] != 0 && temp['Weightitemx'] != 0) {
                $('#Issue_' + temp['i']).addClass('border border-success');
              }
            } else if ((temp["form"] == 'PrintstickerModal')) {
              result = '';
              if (temp["RowCount"] > 0) {
                for (var i = 0; i < temp['RowCount']; i++) {
                  if (temp[i]['TotalQty'] == 0) {
                    var btnPrint = "<button class='btn btn-info' style='width:70px;' disabled><?php echo $array['btnPrint'][$language]; ?></button>";
                  } else if (temp[i]['TotalQty'] > 0) {
                    var btnPrint = "<button class='btn btn-info' style='width:70px;' onclick='PrintSticker(\"" + temp[i]['ItemCode'] + "\",\"" + temp[i]['ItemName'] + "\",\"" + temp[i]['TotalQty'] + "\");'><?php echo $array['btnPrint'][$language]; ?></button>";
                  }
                  result += "<tr>" +
                    '<td nowrap style="width: 28%;" class="text-left">' + temp[i]['ItemCode'] + '</td>' +
                    '<td nowrap style="width: 30%;" class="text-left">' + temp[i]['ItemName'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['ParQty'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['CcQty'] + '</td>' +
                    '<td nowrap style="width: 10%;" class="text-right">' + temp[i]['TotalQty'] + '</td>' +
                    '<td nowrap style="width: 12%;" class="text-center">' + btnPrint + '</td>' +
                    "</tr>";
                }
                $("#detailSticker").html(result);
              }
              $('#PrintStickerModal').modal('show');
            } else if ((temp["form"] == 'find_item')) {
              ShowDetailNew();
              // KeyNewTotalQty();
            } else if ((temp["form"] == 'ShowItemAll')) {
              $("#TableItemDetail tbody").empty();
              var isStatus = $("#IsStatus").val();
              var st1 = "style='font-size:24px;margin-left:20px; width:130px;'";
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItemDetail >tbody >tr').length;
                var chkunit = "<select onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control selectCenter' style='font-size:24px;' id='unit" + i + "'>";
                for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                  if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode2']) {
                    chkunit += "<option selected value=" + i + "," + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + "," + temp['Multiply_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  } else {
                    chkunit += "<option value=" + i + "," + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + "," + temp['Multiply_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  }
                }
                chkunit += "</select>";
                var Sc = "<div class='row' style='margin-left:2px;'><button class='btn btn_mhee ' style='height:40px;width:32px;'>-</button><input class='form-control numonly QtyItem' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_" + i + "' value='" + 1 + "'><button class='btn btn_mheesave' style='height:40px;width:32px;'>+</button></div>";
                var Order = "<input class='form-control numonly' id='order" + i + "' type='text' style='text-align:center;'>";
                var Par = "<input class='form-control' id='Par_" + i + "' type='text' style='text-align:center;' disabled value='" + temp[i]['ParQty'] + "'>";
                var Issue = "<input class='form-control' id='Issue_" + i + "'  type='text' style='text-align:center;' disabled>";
                var Max = "<input class='form-control' id='Max_" + i + "'  type='text' style='text-align:center;' disabled>";
                var Short = "<input class='form-control' id='Short_" + i + "'  type='text' style='text-align:center;' disabled>";
                var Over = "<input class='form-control' id='Over_" + i + "'  type='text' style='text-align:center;' disabled>";
                var Weight = "<input class='form-control '  id='Weight_" + i + "' type='text' style='text-align:center;'  disabled value='" + temp[i]['Weight'] + "'>";
                var Price = "";
                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                  "<td style='width: 5%;'nowrap>" + (i + 1) + "</td>" +
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 21%;'nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 12%;'nowrap>" + chkunit + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Par + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Sc + "</td>" +
                  "<td style='width: 8%;'nowrap>" + Max + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Issue + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Short + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Over + "</td>" +
                  "<td style='width: 9%;'nowrap>" + Weight + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $('#bSaveadd').attr('disabled', true);
                  $('#bSaveadd2').addClass('opacity');
                  $("#countcheck").val("0");
                  $("#TableItemDetail tbody").append($StrTR);
                } else {
                  $('#TableItemDetail tbody:last-child').append($StrTR);
                }
              }
              $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
              });
            } else if ((temp["form"] == 'ShowDetailNew')) {
              $("#TableItemDetail tbody").empty();
              var isStatus = $("#IsStatus").val();
              var st1 = "style='font-size:24px;margin-left:20px; width:130px;'";
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItemDetail >tbody >tr').length;
                var Sc = "<div class='row' style='margin-left:2px;'><button class='btn btn_mhee inputDis' style='height:40px;width:32px;' onclick='DelTotalQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['ParQty'] + "\",\"" + temp[i]['ItemCode'] + "\")'>-</button>" +
                  "<input name='Sc_save' autocomplete='off' class='form-control numonly QtyItem inputDis' style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_" + i + "' value='" + temp[i]['CcQty'] + "' onchange='KeyNewCcQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['ParQty'] + "\" ,\"" + temp[i]['ItemCode'] + "\")'>" +
                  "<button class='btn btn_mheesave inputDis' style='height:40px;width:32px;' onclick='addTotalQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['ParQty'] + "\",\"" + temp[i]['ItemCode'] + "\")'>+</button></div>";
                var Order = "<input autocomplete='off' class='form-control numonly' id='order" + i + "' type='text' style='text-align:center;font-size: 24px!important'>";

                var Par = "<input autocomplete='off' class='form-control' id='Par_" + i + "' type='text' style='text-align:center;font-size: 24px!important' disabled value='" + temp[i]['ParQty'] + "'>";

                var Issue = "<input name='Issue_save' autocomplete='off' class='form-control decimal numonly inputDis' id='Issue_" + i + "'  type='text' style='text-align:center;font-size: 24px!important' placeholder='0' value='" + temp[i]['TotalQty'] + "' onchange='KeyNewTotalQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\" ,\"" + temp[i]['ItemCode'] + "\"  )'>";

                var Max = "<input autocomplete='off' class='form-control' id='Max_" + i + "'  type='text' style='text-align:center;font-size: 24px!important' disabled>";

                var Short = "<input name='Short_save'  autocomplete='off' class='form-control' id='Short_" + i + "'  type='text' style='text-align:center;font-size: 24px!important' disabled value='" + temp[i]['Short'] + "'>";

                var Over = "<input name='Over_save' autocomplete='off' class='form-control' id='Over_" + i + "'  type='text' style='text-align:center;font-size: 24px!important' disabled value='" + temp[i]['Over'] + "'>";

                var Weight = "<input name='Weight_save' autocomplete='off' class='form-control WeightItem'  id='Weight_" + i + "' type='text' style='text-align:center;font-size: 24px!important'  disabled value='" + temp[i]['Weight'] + "'>";

                var Weight2 = "<input  autocomplete='off' class='form-control WeightItem'  id='Weight2_" + i + "' type='text' style='text-align:center;font-size: 24px!important'  disabled value='" + temp[i]['Weightitem'] + "'>";

                var Price = "<input hidden name='Price_save'  autocomplete='off' class='form-control PriceItem'  id='Price2_" + i + "' type='text' style='text-align:center;font-size: 24px!important'  disabled value='" + temp[i]['Price'] + "'>";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                  "<td style='width: 5%;'nowrap>" + (i + 1) + "</td>" +
                  "<td style='width: 21%;'>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Par + "</td>" +
                  "<td style='width: 11%;'nowrap>" + Sc + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Max + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Issue + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Short + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Over + "</td>" +
                  "<td style='width: 10%;'nowrap>" + Weight + "</td>" +
                  "<td style='width: 10%;'nowrap hidden>" + Price + "</td>" +
                  "<td hidden style='width: 10%;'nowrap>" + Weight2 + "</td>" +
                  "<td><input type='hidden' id='item_array" + temp[i]['ItemCode'] + "' value='" + temp[i]['ItemCode'] + "' class='item_array'></input></td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $('#bSaveadd').attr('disabled', true);
                  $('#bSaveadd2').addClass('opacity');
                  $("#countcheck").val("0");

                  $("#TableItemDetail tbody").append($StrTR);
                } else {
                  $('#TableItemDetail tbody:last-child').append($StrTR);
                }
              }

              $('.decimal').keypress(function(e) {
                var character = String.fromCharCode(e.keyCode)
                var newValue = this.value + character;
                if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                  e.preventDefault();
                  return false;
                }
              });

              function hasDecimalPlace(value, x) {
                var pointIndex = value.indexOf('.');
                return pointIndex >= 0 && pointIndex < value.length - x;
              }
              Calculate(temp['Row']);
              $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
              });
              if (isStatus != 0) {
                $(".inputDis").attr('disabled', true);
              }
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
            }
            swal({
              title: '',
              text: temp['msg'],
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 1500,
              confirmButtonText: 'Ok'
            })

            $("#docnofield").val(temp[0]['DocNo']);
            $("#TableDocumentSS tbody").empty();
            $("#TableSendSterileDetail tbody").empty();
            $("#TableUsageCode tbody").empty();
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
          alert(err);
        }
      });
    }
  </script>
  <style media="screen">
    .select3 {
      width: 255px !important;
    }

    .select2-container--default .select2-selection--single {
      height: 38px;
      border: 1px solid #aaaaaa85;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 5px;
    }

    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .btn{
      font-family: myFirstFont !important;
      font-size: 22px !important;
    }
    .modal-content1 {
      width: 72% !important;
      right: -15% !important;
      position: relative;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-direction: column;
      flex-direction: column;
      width: 100%;
      pointer-events: auto;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: .3rem;
      outline: 0;
    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    button,
    input[id^='qty'],
    input[id^='order'],
    input[id^='max'] {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
      /* background: #4f88e3!important; */
      background-color: #1659a2;
    }

    .table th,
    .table td {
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

    .btn_mhee {
      background-color: #e83530;
      color: white;
    }

    .btn_mheesave {
      background-color: #ee9726;
      color: white;
    }

    .btn_mheedel {
      background-color: #b12f31;
      color: white;
    }

    .btn_mheeIM {
      background-color: #3e3a8f;
      color: white;
    }

    .btn_mheedetail {
      background-color: #535d55;
      color: white;
    }

    .btn_mheereport {
      background-color: #d8d9db;
      color: white;
    }

    .btn_mheeCREATE {
      background-color: #1458a3;

      color: white;
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
      text-decoration: none;
      font-size: 23px;
      color: #2c3e50;
      display: block;
      background: none;
      box-shadow: none !important;

    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

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
      font-weight: bold;
      font-size: 26px;
    }

    .icon {
      padding-top: 6px;
      padding-left: 44px;
    }

    .opacity {
      opacity: 0.5;
    }

    .only1:disabled,
    .form-control[readonly] {
      background-color: transparent !important;
      opacity: 1;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav a {
        font-size: 21px;

      }
    }

    .kbw-signature {
      width: 100%;
      height: 240px;
    }

    #ModalSign {
      top: 0% !important;
    }
  </style>
</head>

<body id="page-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][3][$language]; ?></li>
  </ol>

  <hr style='width: 98%;height:1px;background-color: #ecf0f1;'>
  <input type="hidden" id='input_chk' value='0'>
  <input type="hidden" id='chk_Key' value='0'>
  <input type="hidden" id='chk_sign' value='0'>



  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <div class="row">
        <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titleshelf'][$language]; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" onclick=" ShowDocument()" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
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
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['side'][$language]; ?></label>
                            <select class="form-control col-sm-7 icon_select checkblank4" style="font-size:22px;" id="hotpital" onchange="getDepartment();" <?php if ($PmID == 2 || $PmID == 3 || $PmID == 4 || $PmID == 5 || $PmID == 7) echo 'disabled="true" '; ?>></select>
                            <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['department'][$language]; ?></label>
                            <select class="form-control col-sm-7 checkblank2 border select2 custom-select" style="font-size:22px;" id="department" onchange="ChkItemInDep();"></select>
                            <label id="rem1" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['docdate'][$language]; ?></label>
                            <!-- <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>"> -->
                            <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  numonly charonly only only1 " disabled="true" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>">
                            <label id="rem9" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['docno'][$language]; ?></label>
                            <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['employee'][$language]; ?></label>
                            <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['time'][$language]; ?></label>
                            <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>">
                            <input type="text" hidden autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="Signature" placeholder="<?php echo $array['time'][$language]; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6" hidden>
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['Cycle'][$language]; ?></label>
                            <select class="form-control col-sm-7" style="font-size:22px;" id="cycle">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['setcount'][$language]; ?></label>
                            <select id="setcount" style="font-size:22px;" class="form-control col-sm-7 checkblank5 border " onchange="removeClassBorder3();" name="searchitem" placeholder="<?php echo $array['setcount'][$language]; ?>"> </select>
                            <label id="rem4" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['settime'][$language]; ?></label>
                            <select id="settime" style="font-size:22px;" class="form-control col-sm-7 checkblank3 border " onchange="removeClassBorder2();" name="searchitem" placeholder="<?php echo $array['settime'][$language]; ?>"> </select>
                            <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['barcode'][$language]; ?></label>
                            <input type="text" autocomplete="off" id="barcode" disabled="true" style="font-size:22px;" class="form-control col-sm-7 " name="searchitem" placeholder="<?php echo $array['barcode'][$language]; ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;">Process</label>
                            <input type="text" id="completed" style="font-size:22px;" class="form-control col-sm-7  border only only1" disabled="true">

                            <!-- <label id="rem2"   class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label> -->
                          </div>
                        </div>
                      </div>
                      <div class="row">

                      </div>
                    </div>
                  </div>
                </div> <!-- tag column 1 -->
                <!-- row btn -->
                <div class="row m-1 mt-4 d-flex justify-content-end col-12">
                  <div class="menu mhee" id="hover1">
                    <div class="d-flex justify-content-center">
                      <div class="circle1 d-flex justify-content-center" id="bCreate2">
                        <button class="btn" onclick="CreateDocument()" id="bCreate" disabled>
                          <i class="fas fa-file-medical"></i>
                          <div>
                            <?php echo $array['createdocno'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="menu" id="hover2" hidden>
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
                  <div class="menu" id="hover3">
                    <div class="d-flex justify-content-center">
                      <div class="circle3 d-flex justify-content-center opacity" id="bDelete2">
                        <button class="btn" onclick="DeleteItem()" id="bDelete" disabled="true">
                          <i class="fas fa-trash-alt"></i>
                          <div>
                            <?php echo $array['delitem'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="menu" id="hover4">
                    <div class="d-flex justify-content-center">
                      <div class="circle4 d-flex justify-content-center opacity" id="bSave2">
                        <button class="btn" onclick="SaveBill()" id="bSave" disabled="true">
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
                  <div class="menu" id="hover5">
                    <div class="d-flex justify-content-center">
                      <div class="circle5 d-flex justify-content-center opacity" id="bCancel2">
                        <button class="btn" onclick="CancelDocument()" id="bCancel" disabled="true">
                          <i class="fas fa-times"></i>
                          <div>
                            <?php echo $array['Canceldoc'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="menu" id="hover9">
                    <div class="d-flex justify-content-center">
                      <div class="circle13  d-flex justify-content-center opacity" id="bpacking2">
                        <button class="btn" onclick="setpacking()" id="bpacking" disabled="true">
                          <i class="fas fa-people-carry"></i>
                          <div>
                            <?php echo $array['Packing'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="menu" id="hover6">
                    <div class="d-flex justify-content-center">
                      <div class="circle7 d-flex justify-content-center opacity" id="bdetail2">
                        <button class="btn" onclick="draw()" id="bdetail" disabled="true">
                          <i class="fas fa-shopping-cart"></i>
                          <div>
                            <?php echo $array['Jaipar'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="menu " id="hover7">
                    <div class="d-flex justify-content-center">
                      <div class="circle9 d-flex justify-content-center opacity" id="bPrint2">
                        <button class="btn" onclick="showprint()" id="bPrint" disabled="true">
                          <i class="fas fa-print"></i>
                          <div>
                            <?php echo $array['print'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="menu " id="hover8">
                    <div class="d-flex justify-content-center">
                      <div class="circle8 d-flex justify-content-center opacity" id="bPrintsticker2">
                        <button class="btn" onclick="PrintstickerModal()" id="bPrintsticker" disabled="true">
                          <i class="fas fa-print"></i>
                          <div>
                            <?php echo $array['Sticker'][$language]; ?>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end row btn -->
              </div>


              <div class="row">
                <div class="col-md-12 pl-1 pr-4">
                  <!-- tag column 1 -->
                  <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="98%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:24px;">
                      <tr role="row" id='tr_1'>
                        <th style='width: 5%;' nowrap><?php echo $array['sn'][$language]; ?></th>
                        <th style='width: 21%;' nowrap><?php echo $array['item'][$language]; ?></th>
                        <!-- <th style='width: 12%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th> -->
                        <th style='width: 10%;' nowrap>
                          <center><?php echo $array['parsc'][$language]; ?></center>
                        </th>
                        <th style='width: 10%;' nowrap>
                          <center><?php echo $array['count'][$language]; ?></center>
                        </th>
                        <th style='width: 10%;' nowrap>
                          <center>Max<center>
                        </th>
                        <th style='width: 10%;' nowrap>
                          <center>Issue<center>
                        </th>
                        <th style='width: 10%;' nowrap>
                          <center>Short<center>
                        </th>
                        <th style='width: 10%;' nowrap>
                          <center>Over<center>
                        </th>
                        <th style='width: 14%;padding-right: 3%;' nowrap>
                          <center><?php echo $array['weightsc'][$language]; ?><center>
                        </th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:630px;">
                    </tbody>
                  </table>
                </div> <!-- tag column 1 -->
              </div>
            </div>
            
            <!-- search document -->
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="row mt-3">
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select onchange="getDepartment2();" class="form-control" style='font-size:24px;' id="side">
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control select2 custom-select select3" style='font-size:24px;' id="Dep2">
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here numonly charonly" id="datepicker1" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                  </div>
                </div>
                <div class="col-md-6 mhee">
                  <div class="row" style="margin-left:2px;">

                    <select class="form-control" autocomplete="off" style="font-size:24px;width:27%;" name="process" id="process">
                      <option value="0"><?php echo $array['processchooce'][$language]; ?></option>
                      <option value="1">on process</option>
                      <option value="2">completed</option>
                      <option value="3">cancel </option>
                    </select>

                    <input type="text" class="form-control" autocomplete="off" style="font-size:24px;width:24%;margin-left: 3%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                    <div class="search_custom col-md-2">
                      <div class="search_1 d-flex justify-content-start">
                        <button class="btn" onclick="ShowDocument(1)">
                          <i class="fas fa-search mr-2"></i>
                          <?php echo $array['search'][$language]; ?>
                        </button>
                      </div>
                    </div>
                    <div class="search_custom col-md-2">
                      <div class="circle11 d-flex justify-content-start">
                        <button class="btn" onclick="SelectDocument('click')" id="btn_show">
                          <i class="fas fa-paste mr-2 pt-1"></i>
                          <?php echo $array['show'][$language]; ?>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <!-- tag column 1 -->
                  <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:24px;">
                      <tr role="row">
                        <th style='width: 10%;' nowrap>&nbsp;</th>
                        <th style='width: 15%;' nowrap><?php echo $array['docdate'][$language]; ?></th>
                        <th style='width: 15%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                        <th style='width: 15%;' nowrap><?php echo $array['department'][$language]; ?>
                        </th>
                        <th style='width: 15%;' nowrap><?php echo $array['employee'][$language]; ?></th>
                        <th style='width: 10%;' nowrap><?php echo $array['time'][$language]; ?></th>
                        <th style='width: 10%;' nowrap><?php echo $array['setcount'][$language]; ?></th>
                        <th style='width: 10%;' nowrap><?php echo $array['status'][$language]; ?></th>
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
                  <label class="col-sm-4 col-form-label text-right pr-5" style="margin-left: -11%;"><?php echo $array['Searchitem2'][$language]; ?></label>
                  <input type="text" autocomplete="off" style="margin-left: -3%;" class="form-control col-sm-7" name="searchitem" id="searchitem" placeholder="<?php echo $array['Searchitem2'][$language]; ?>">
                </div>
              </div>

              <!-- serach----------------------- -->
              <div class="search_custom col-md-2" style="margin-left: -14%;">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn" onclick="ShowItem()" id="bSave">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>

              <div class="search_custom col-md-2">
                <div class="import_1 d-flex justify-content-start opacity" id="bSaveadd2">
                  <button class="btn dis" onclick="getImport(1)" id="bSaveadd" disabled="true">
                    <i class="fas fa-file-import mr-2 pt-1"></i>
                    <?php echo $array['import'][$language]; ?>
                  </button>
                </div>
              </div>
              <!-- end serach----------------------- -->
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px">
              <thead style="font-size:24px">
                <tr role="row">
                  <input type="hidden" id="countcheck">
                  <th style='width: 26%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <!-- <th style='width: 20%;' nowrap><?php echo $array['code'][$language]; ?></th> -->
                  <th style='width: 36%;' nowrap><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 23%;' nowrap>
                    <center><?php echo $array['unit'][$language]; ?></center>
                  </th>
                  <th style='width: 15%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                  <!-- <th style='width: 12%;' nowrap><?php echo $array['weight'][$language]; ?></th> -->
                </tr>
              </thead>
              <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- custom modal2 -->
  <div class="modal" id="dialogListDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TableRefDocNo" cellspacing="0" role="grid">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 10%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <th style='width: 25%;' nowrap><?php echo $array['rfid'][$language]; ?></th>
                  <th style='width: 50%;' nowrap><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 15%;' nowrap><?php echo $array['unit'][$language]; ?></th>
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

  <!-- custom modal3 -->
  <!-- <div class="modal fade" id="alert_par" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title"><?php echo $array['alertPar'][$language]; ?></h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body" style="padding:0px;">
                <div class="row">
                </div>
                <table class="table table-fixed table-condensed table-striped" id="TablePar" cellspacing="0" role="grid">
                  <thead style="font-size:24px;">
                    <tr role="row">
                    <th style='width: 5%;'nowrap ><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 20%;'nowrap class='text-left'><?php echo $array['code'][$language]; ?></th>
                    <th style='width: 25%;'nowrap class='text-left'><?php echo $array['item'][$language]; ?></th>
                    <th style='width: 10%;'nowrap class='text-right'><?php echo $array['par'][$language]; ?></th>
                    <th style='width: 10%;'nowrap class='text-right'><?php echo $array['balance'][$language]; ?></th>
                    <th style='width: 10%;'nowrap class='text-right'><?php echo $array['count'][$language]; ?></th>
                    <th style='width: 10%;'nowrap class='text-right'><?php echo $array['order'][$language]; ?></th>
                    <th style='width: 10%;'nowrap class='text-right'><?php echo $array['over'][$language]; ?></th>
                    </tr>
                  </thead>
                  <tbody id="detail_par" class="nicescrolled" style="font-size:23px;height:auto;">
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" style="width:7%;"onclick="SaveBill(1)" class="btn btn-success"><?php echo $array['confirm'][$language]; ?></button>
              <button type="button" style="width:5%;"class="btn btn-danger" data-dismiss="modal"><?php echo $array['cancel'][$language]; ?></button>
            </div>
          </div>
        </div>
      </div> -->



  <!-- Dialog Modal !-->
  <div class="modal fade" id="SaveDrawModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $array['alertdraw'][$language]; ?></h2>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <div class="row">
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TablePar" cellspacing="0" role="grid">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 5%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <th style='width: 25%;' nowrap class='text-left'><?php echo $array['code'][$language]; ?></th>
                  <th style='width: 30%;' nowrap class='text-left'><?php echo $array['item'][$language]; ?></th>
                  <!-- <th style='width: 10%;'nowrap class='text-right'><?php echo $array['par'][$language]; ?></th> -->
                  <th style='width: 10%;' nowrap class='text-right'><?php echo $array['par'][$language]; ?></th>
                  <th style='width: 10%;' nowrap class='text-right'><?php echo $array['count'][$language]; ?></th>
                  <th style='width: 10%;' nowrap class='text-right'><?php echo $array['order'][$language]; ?></th>
                  <th style='width: 10%;' nowrap class='text-right'><?php echo $array['Mainstock'][$language]; ?></th>
                </tr>
              </thead>
              <tbody id="detailQty" class="nicescrolled" style="font-size:23px;height:auto;">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" style="width:7%;" onclick="SaveQty_SC()" class="btn btn-success"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:5%;" class="btn btn-danger" onclick="SaveQty_SC()"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="PrintStickerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $array['Sticker'][$language]; ?></h2>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <div class="row">
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TablePar" cellspacing="0" role="grid">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 28%;' nowrap class='text-left'><?php echo $array['code'][$language]; ?></th>
                  <th style='width: 30%;' nowrap class='text-left'><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 10%;' nowrap class='text-right'><?php echo $array['par'][$language]; ?></th>
                  <th style='width: 10%;' nowrap class='text-right'><?php echo $array['count'][$language]; ?></th>
                  <th style='width: 10%;' nowrap class='text-right'>Issue</th>
                  <th style='width: 12%;' nowrap class='text-center'><?php echo $array['Sticker'][$language]; ?></th>
                </tr>
              </thead>
              <tbody id="detailSticker" class="nicescrolled" style="font-size:23px;height:auto;">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" style="width:7%;" class="btn btn-success"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:5%;" class="btn btn-danger" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="numberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: rgba(64, 64, 64, 0.75)!important;">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content" style="margin-top: 50px;background-color:#fff;">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $array['pleaseenter'][$language]; ?></h2>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <div class="row">
              <div class="col-3">
                <?php echo $array['code'][$language]; ?>:
              </div>
              <div class="col-9">
                <span id="sp_ItemCode"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <?php echo $array['item'][$language]; ?>:
              </div>
              <div class="col-9">
                <span id="sp_ItemName"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <?php echo $array['qty'][$language]; ?>:
              </div>
              <div class="col-9">
                <input type="hidden" id="maxNumSticker">
                <input type="hidden" id="ItemCode">
                <input type="text" class="form-control numonly_dot" id="numberSticker" autocomplete="off" onkeyup="chk_numbrtSticker(this.value);" style="font-size: 22px;">
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-3">
                <?php echo $array['Organizer'][$language]; ?>:
              </div>
              <div class="col-9">
                <input type="text" class="form-control " id="og" autocomplete="off" style="font-size: 22px;">
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-3">
                <?php echo $array['officer'][$language]; ?>:
              </div>
              <div class="col-9">
                <input type="text" class="form-control " id="of" autocomplete="off" style="font-size: 22px;">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" style="width:10%;" onclick="StickerPrint();" class="btn btn-success"><?php echo $array['btnPrint'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="dialogprint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: rgba(64, 64, 64, 0.75)!important;">
    <div class="modal-dialog" role="document">
      <div class="modal-content1" style="margin-top: 50px;background-color:#fff;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php echo $array['Report555'][$language]; ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <select class="form-control col-sm-12 " style="font-size:22px;" id="printdata">
            <option value="1">Shelfcount</option>
            <option value="2">Short and Over</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="PrintData();" class="btn btn-success" style="width: 50%;"><?php echo $array['ConfirmPrint'][$language]; ?></button>

        </div>
      </div>
    </div>
  </div>

	<div class="modal fade" id="ModalSign" tabindex="-1" role="dialog" aria-hidden='false'>
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="background-color:#fff;">
				<div class="modal-body p-0">

					<div id="maxxx" onselectstart="return false">
						<div id="signature-pad" class="signature-pad">
							<div class="signature-pad--body">
								<canvas></canvas>
							</div>
							<div class="signature-pad--footer">
								<div class="signature-pad--actions">
									<div>
										<!-- <button type="button" class="button clear btn btn-secondary mr-2" data-action="clear" hidden>ล้าง</button> -->
										<button type="button" class="button" data-action="change-color" hidden>Change color</button>
										<button type="button" class="button btn btn-warning" data-action="undo" hidden>ย้อนกลับ</button>

									</div>
									<div>
										<button type="button" class="button save" data-action="save-png" hidden>Save as PNG</button>
										<button type="button" class="button save" data-action="save-jpg" hidden>Save as JPG</button>

										<!-- <button type="button" class="button save btn btn-primary" data-action="save-svg">บันทึก</button> -->
                    <button type="button" style="width: 70px;" class="btn btn-danger" id="clear"  data-action="clear"><?php echo $array['clear'][$language]; ?></button>
                    <button type="button" style="width: 70px;" class="btn btn-success" id="svg" data-action="save-svg"><?php echo $array['confirm'][$language]; ?></button>

									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
        
			</div>
		</div>
	</div>

  <!-- <div class="modal fade" id="ModalSign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: rgba(64, 64, 64, 0.75)!important;">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 50px;background-color:#fff;">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $array['Signature'][$language]; ?></h2>
        </div>
        <div class="modal-body">
          <div id="sig" class="kbw-signature"></div>
        </div>
        <div class="modal-footer">
          <button type="button" style="width:10%;" class="btn btn-success" id="svg"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger" id="clear"><?php echo $array['clear'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div> -->
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
  <!-- Bootstrap core JavaScript-->

  <script src="../assets-sign/js/jquery-ui.min.js"></script>
  <script src="../assets-sign/js/jquery.signature.js"></script>
  <script src="../select2/dist/js/select2.full.min.js" type="text/javascript"></script>


  <script>
    $('#barcode').keydown(function(e) {
      if (e.keyCode == 13) {
        find_item();
      }
    })
  </script>
<script>
		/*!
		 * Signature Pad v3.0.0-beta.3 | https://github.com/szimek/signature_pad
		 * (c) 2018 Szymon Nowak | Released under the MIT license
		 */

		(function(global, factory) {
			typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
				typeof define === 'function' && define.amd ? define(factory) :
				(global.SignaturePad = factory());
		}(this, (function() {
			'use strict';

			var Point = (function() {
				function Point(x, y, time) {
					this.x = x;
					this.y = y;
					this.time = time || Date.now();
				}
				Point.prototype.distanceTo = function(start) {
					return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
				};
				Point.prototype.equals = function(other) {
					return this.x === other.x && this.y === other.y && this.time === other.time;
				};
				Point.prototype.velocityFrom = function(start) {
					return this.time !== start.time ?
						this.distanceTo(start) / (this.time - start.time) :
						0;
				};
				return Point;
			}());

			var Bezier = (function() {
				function Bezier(startPoint, control2, control1, endPoint, startWidth, endWidth) {
					this.startPoint = startPoint;
					this.control2 = control2;
					this.control1 = control1;
					this.endPoint = endPoint;
					this.startWidth = startWidth;
					this.endWidth = endWidth;
				}
				Bezier.fromPoints = function(points, widths) {
					var c2 = this.calculateControlPoints(points[0], points[1], points[2]).c2;
					var c3 = this.calculateControlPoints(points[1], points[2], points[3]).c1;
					return new Bezier(points[1], c2, c3, points[2], widths.start, widths.end);
				};
				Bezier.calculateControlPoints = function(s1, s2, s3) {
					var dx1 = s1.x - s2.x;
					var dy1 = s1.y - s2.y;
					var dx2 = s2.x - s3.x;
					var dy2 = s2.y - s3.y;
					var m1 = {
						x: (s1.x + s2.x) / 2.0,
						y: (s1.y + s2.y) / 2.0
					};
					var m2 = {
						x: (s2.x + s3.x) / 2.0,
						y: (s2.y + s3.y) / 2.0
					};
					var l1 = Math.sqrt(dx1 * dx1 + dy1 * dy1);
					var l2 = Math.sqrt(dx2 * dx2 + dy2 * dy2);
					var dxm = m1.x - m2.x;
					var dym = m1.y - m2.y;
					var k = l2 / (l1 + l2);
					var cm = {
						x: m2.x + dxm * k,
						y: m2.y + dym * k
					};
					var tx = s2.x - cm.x;
					var ty = s2.y - cm.y;
					return {
						c1: new Point(m1.x + tx, m1.y + ty),
						c2: new Point(m2.x + tx, m2.y + ty)
					};
				};
				Bezier.prototype.length = function() {
					var steps = 10;
					var length = 0;
					var px;
					var py;
					for (var i = 0; i <= steps; i += 1) {
						var t = i / steps;
						var cx = this.point(t, this.startPoint.x, this.control1.x, this.control2.x, this.endPoint.x);
						var cy = this.point(t, this.startPoint.y, this.control1.y, this.control2.y, this.endPoint.y);
						if (i > 0) {
							var xdiff = cx - px;
							var ydiff = cy - py;
							length += Math.sqrt(xdiff * xdiff + ydiff * ydiff);
						}
						px = cx;
						py = cy;
					}
					return length;
				};
				Bezier.prototype.point = function(t, start, c1, c2, end) {
					return (start * (1.0 - t) * (1.0 - t) * (1.0 - t)) +
						(3.0 * c1 * (1.0 - t) * (1.0 - t) * t) +
						(3.0 * c2 * (1.0 - t) * t * t) +
						(end * t * t * t);
				};
				return Bezier;
			}());

			function throttle(fn, wait) {
				if (wait === void 0) {
					wait = 250;
				}
				var previous = 0;
				var timeout = null;
				var result;
				var storedContext;
				var storedArgs;
				var later = function() {
					previous = Date.now();
					timeout = null;
					result = fn.apply(storedContext, storedArgs);
					if (!timeout) {
						storedContext = null;
						storedArgs = [];
					}
				};
				return function wrapper() {
					var args = [];
					for (var _i = 0; _i < arguments.length; _i++) {
						args[_i] = arguments[_i];
					}
					var now = Date.now();
					var remaining = wait - (now - previous);
					storedContext = this;
					storedArgs = args;
					if (remaining <= 0 || remaining > wait) {
						if (timeout) {
							clearTimeout(timeout);
							timeout = null;
						}
						previous = now;
						result = fn.apply(storedContext, storedArgs);
						if (!timeout) {
							storedContext = null;
							storedArgs = [];
						}
					} else if (!timeout) {
						timeout = window.setTimeout(later, remaining);
					}
					return result;
				};
			}

			var SignaturePad = (function() {
				function SignaturePad(canvas, options) {
					if (options === void 0) {
						options = {};
					}
					var _this = this;
					this.canvas = canvas;
					this.options = options;
					this._handleMouseDown = function(event) {
						if (event.which === 1) {
							_this._mouseButtonDown = true;
							_this._strokeBegin(event);
						}
					};
					this._handleMouseMove = function(event) {
						if (_this._mouseButtonDown) {
							_this._strokeMoveUpdate(event);
						}
					};
					this._handleMouseUp = function(event) {
						if (event.which === 1 && _this._mouseButtonDown) {
							_this._mouseButtonDown = false;
							_this._strokeEnd(event);
						}
					};
					this._handleTouchStart = function(event) {
						event.preventDefault();
						if (event.targetTouches.length === 1) {
							var touch = event.changedTouches[0];
							_this._strokeBegin(touch);
						}
					};
					this._handleTouchMove = function(event) {
						event.preventDefault();
						var touch = event.targetTouches[0];
						_this._strokeMoveUpdate(touch);
					};
					this._handleTouchEnd = function(event) {
						var wasCanvasTouched = event.target === _this.canvas;
						if (wasCanvasTouched) {
							event.preventDefault();
							var touch = event.changedTouches[0];
							_this._strokeEnd(touch);
						}
					};
					this.velocityFilterWeight = options.velocityFilterWeight || 0.7;
					this.minWidth = options.minWidth || 0.5;
					this.maxWidth = options.maxWidth || 2.5;
					this.throttle = ('throttle' in options ? options.throttle : 16);
					this.minDistance = ('minDistance' in options ?
						options.minDistance :
						5);
					if (this.throttle) {
						this._strokeMoveUpdate = throttle(SignaturePad.prototype._strokeUpdate, this.throttle);
					} else {
						this._strokeMoveUpdate = SignaturePad.prototype._strokeUpdate;
					}
					this.dotSize =
						options.dotSize ||
						function dotSize() {
							return (this.minWidth + this.maxWidth) / 2;
						};
					this.penColor = options.penColor || 'black';
					this.backgroundColor = options.backgroundColor || 'rgba(0,0,0,0)';
					this.onBegin = options.onBegin;
					this.onEnd = options.onEnd;
					this._ctx = canvas.getContext('2d');
					this.clear();
					this.on();
				}
				SignaturePad.prototype.clear = function() {
					var ctx = this._ctx;
					var canvas = this.canvas;
					ctx.fillStyle = this.backgroundColor;
					ctx.clearRect(0, 0, canvas.width, canvas.height);
					ctx.fillRect(0, 0, canvas.width, canvas.height);
					this._data = [];
					this._reset();
					this._isEmpty = true;
				};
				SignaturePad.prototype.fromDataURL = function(dataUrl, options, callback) {
					var _this = this;
					if (options === void 0) {
						options = {};
					}
					var image = new Image();
					var ratio = options.ratio || window.devicePixelRatio || 1;
					var width = options.width || this.canvas.width / ratio;
					var height = options.height || this.canvas.height / ratio;
					this._reset();
					image.onload = function() {
						_this._ctx.drawImage(image, 0, 0, width, height);
						if (callback) {
							callback();
						}
					};
					image.onerror = function(error) {
						if (callback) {
							callback(error);
						}
					};
					image.src = dataUrl;
					this._isEmpty = false;
				};
				SignaturePad.prototype.toDataURL = function(type, encoderOptions) {
					if (type === void 0) {
						type = 'image/png';
					}
					switch (type) {
						case 'image/svg+xml':
							return this._toSVG();
						default:
							return this.canvas.toDataURL(type, encoderOptions);
					}
				};
				SignaturePad.prototype.on = function() {
					this.canvas.style.touchAction = 'none';
					this.canvas.style.msTouchAction = 'none';
					if (window.PointerEvent) {
						this._handlePointerEvents();
					} else {
						this._handleMouseEvents();
						if ('ontouchstart' in window) {
							this._handleTouchEvents();
						}
					}
				};
				SignaturePad.prototype.off = function() {
					this.canvas.style.touchAction = 'auto';
					this.canvas.style.msTouchAction = 'auto';
					this.canvas.removeEventListener('pointerdown', this._handleMouseDown);
					this.canvas.removeEventListener('pointermove', this._handleMouseMove);
					document.removeEventListener('pointerup', this._handleMouseUp);
					this.canvas.removeEventListener('mousedown', this._handleMouseDown);
					this.canvas.removeEventListener('mousemove', this._handleMouseMove);
					document.removeEventListener('mouseup', this._handleMouseUp);
					this.canvas.removeEventListener('touchstart', this._handleTouchStart);
					this.canvas.removeEventListener('touchmove', this._handleTouchMove);
					this.canvas.removeEventListener('touchend', this._handleTouchEnd);
				};
				SignaturePad.prototype.isEmpty = function() {
					return this._isEmpty;
				};
				SignaturePad.prototype.fromData = function(pointGroups) {
					var _this = this;
					this.clear();
					this._fromData(pointGroups, function(_a) {
						var color = _a.color,
							curve = _a.curve;
						return _this._drawCurve({
							color: color,
							curve: curve
						});
					}, function(_a) {
						var color = _a.color,
							point = _a.point;
						return _this._drawDot({
							color: color,
							point: point
						});
					});
					this._data = pointGroups;
				};
				SignaturePad.prototype.toData = function() {
					return this._data;
				};
				SignaturePad.prototype._strokeBegin = function(event) {
					var newPointGroup = {
						color: this.penColor,
						points: []
					};
					if (typeof this.onBegin === 'function') {
						this.onBegin(event);
					}
					this._data.push(newPointGroup);
					this._reset();
					this._strokeUpdate(event);
				};
				SignaturePad.prototype._strokeUpdate = function(event) {
					var x = event.clientX;
					var y = event.clientY;
					var point = this._createPoint(x, y);
					var lastPointGroup = this._data[this._data.length - 1];
					var lastPoints = lastPointGroup.points;
					var lastPoint = lastPoints.length > 0 && lastPoints[lastPoints.length - 1];
					var isLastPointTooClose = lastPoint ?
						point.distanceTo(lastPoint) <= this.minDistance :
						false;
					var color = lastPointGroup.color;
					if (!lastPoint || !(lastPoint && isLastPointTooClose)) {
						var curve = this._addPoint(point);
						if (!lastPoint) {
							this._drawDot({
								color: color,
								point: point
							});
						} else if (curve) {
							this._drawCurve({
								color: color,
								curve: curve
							});
						}
						lastPoints.push({
							time: point.time,
							x: point.x,
							y: point.y
						});
					}
				};
				SignaturePad.prototype._strokeEnd = function(event) {
					this._strokeUpdate(event);
					if (typeof this.onEnd === 'function') {
						this.onEnd(event);
					}
				};
				SignaturePad.prototype._handlePointerEvents = function() {
					this._mouseButtonDown = false;
					this.canvas.addEventListener('pointerdown', this._handleMouseDown);
					this.canvas.addEventListener('pointermove', this._handleMouseMove);
					document.addEventListener('pointerup', this._handleMouseUp);
				};
				SignaturePad.prototype._handleMouseEvents = function() {
					this._mouseButtonDown = false;
					this.canvas.addEventListener('mousedown', this._handleMouseDown);
					this.canvas.addEventListener('mousemove', this._handleMouseMove);
					document.addEventListener('mouseup', this._handleMouseUp);
				};
				SignaturePad.prototype._handleTouchEvents = function() {
					this.canvas.addEventListener('touchstart', this._handleTouchStart);
					this.canvas.addEventListener('touchmove', this._handleTouchMove);
					this.canvas.addEventListener('touchend', this._handleTouchEnd);
				};
				SignaturePad.prototype._reset = function() {
					this._lastPoints = [];
					this._lastVelocity = 0;
					this._lastWidth = (this.minWidth + this.maxWidth) / 2;
					this._ctx.fillStyle = this.penColor;
				};
				SignaturePad.prototype._createPoint = function(x, y) {
					var rect = this.canvas.getBoundingClientRect();
					return new Point(x - rect.left, y - rect.top, new Date().getTime());
				};
				SignaturePad.prototype._addPoint = function(point) {
					var _lastPoints = this._lastPoints;
					_lastPoints.push(point);
					if (_lastPoints.length > 2) {
						if (_lastPoints.length === 3) {
							_lastPoints.unshift(_lastPoints[0]);
						}
						var widths = this._calculateCurveWidths(_lastPoints[1], _lastPoints[2]);
						var curve = Bezier.fromPoints(_lastPoints, widths);
						_lastPoints.shift();
						return curve;
					}
					return null;
				};
				SignaturePad.prototype._calculateCurveWidths = function(startPoint, endPoint) {
					var velocity = this.velocityFilterWeight * endPoint.velocityFrom(startPoint) +
						(1 - this.velocityFilterWeight) * this._lastVelocity;
					var newWidth = this._strokeWidth(velocity);
					var widths = {
						end: newWidth,
						start: this._lastWidth
					};
					this._lastVelocity = velocity;
					this._lastWidth = newWidth;
					return widths;
				};
				SignaturePad.prototype._strokeWidth = function(velocity) {
					return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
				};
				SignaturePad.prototype._drawCurveSegment = function(x, y, width) {
					var ctx = this._ctx;
					ctx.moveTo(x, y);
					ctx.arc(x, y, width, 0, 2 * Math.PI, false);
					this._isEmpty = false;
				};
				SignaturePad.prototype._drawCurve = function(_a) {
					var color = _a.color,
						curve = _a.curve;
					var ctx = this._ctx;
					var widthDelta = curve.endWidth - curve.startWidth;
					var drawSteps = Math.floor(curve.length()) * 2;
					ctx.beginPath();
					ctx.fillStyle = color;
					for (var i = 0; i < drawSteps; i += 1) {
						var t = i / drawSteps;
						var tt = t * t;
						var ttt = tt * t;
						var u = 1 - t;
						var uu = u * u;
						var uuu = uu * u;
						var x = uuu * curve.startPoint.x;
						x += 3 * uu * t * curve.control1.x;
						x += 3 * u * tt * curve.control2.x;
						x += ttt * curve.endPoint.x;
						var y = uuu * curve.startPoint.y;
						y += 3 * uu * t * curve.control1.y;
						y += 3 * u * tt * curve.control2.y;
						y += ttt * curve.endPoint.y;
						var width = curve.startWidth + ttt * widthDelta;
						this._drawCurveSegment(x, y, width);
					}
					ctx.closePath();
					ctx.fill();
				};
				SignaturePad.prototype._drawDot = function(_a) {
					var color = _a.color,
						point = _a.point;
					var ctx = this._ctx;
					var width = typeof this.dotSize === 'function' ? this.dotSize() : this.dotSize;
					ctx.beginPath();
					this._drawCurveSegment(point.x, point.y, width);
					ctx.closePath();
					ctx.fillStyle = color;
					ctx.fill();
				};
				SignaturePad.prototype._fromData = function(pointGroups, drawCurve, drawDot) {
					for (var _i = 0, pointGroups_1 = pointGroups; _i < pointGroups_1.length; _i++) {
						var group = pointGroups_1[_i];
						var color = group.color,
							points = group.points;
						if (points.length > 1) {
							for (var j = 0; j < points.length; j += 1) {
								var basicPoint = points[j];
								var point = new Point(basicPoint.x, basicPoint.y, basicPoint.time);
								this.penColor = color;
								if (j === 0) {
									this._reset();
								}
								var curve = this._addPoint(point);
								if (curve) {
									drawCurve({
										color: color,
										curve: curve
									});
								}
							}
						} else {
							this._reset();
							drawDot({
								color: color,
								point: points[0]
							});
						}
					}
				};
				SignaturePad.prototype._toSVG = function() {
					var _this = this;
					var pointGroups = this._data;
					var ratio = Math.max(window.devicePixelRatio || 1, 1);
					var minX = 0;
					var minY = 0;
					var maxX = this.canvas.width / ratio;
					var maxY = this.canvas.height / ratio;
					var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
					svg.setAttribute('width', this.canvas.width.toString());
					svg.setAttribute('height', this.canvas.height.toString());
					this._fromData(pointGroups, function(_a) {
						var color = _a.color,
							curve = _a.curve;
						var path = document.createElement('path');
						if (!isNaN(curve.control1.x) &&
							!isNaN(curve.control1.y) &&
							!isNaN(curve.control2.x) &&
							!isNaN(curve.control2.y)) {
							var attr = "M " + curve.startPoint.x.toFixed(3) + "," + curve.startPoint.y.toFixed(3) + " " +
								("C " + curve.control1.x.toFixed(3) + "," + curve.control1.y.toFixed(3) + " ") +
								(curve.control2.x.toFixed(3) + "," + curve.control2.y.toFixed(3) + " ") +
								(curve.endPoint.x.toFixed(3) + "," + curve.endPoint.y.toFixed(3));
							path.setAttribute('d', attr);
							path.setAttribute('stroke-width', (curve.endWidth * 2.25).toFixed(3));
							path.setAttribute('stroke', color);
							path.setAttribute('fill', 'none');
							path.setAttribute('stroke-linecap', 'round');
							svg.appendChild(path);
						}
					}, function(_a) {
						var color = _a.color,
							point = _a.point;
						var circle = document.createElement('circle');
						var dotSize = typeof _this.dotSize === 'function' ? _this.dotSize() : _this.dotSize;
						circle.setAttribute('r', dotSize.toString());
						circle.setAttribute('cx', point.x.toString());
						circle.setAttribute('cy', point.y.toString());
						circle.setAttribute('fill', color);
						svg.appendChild(circle);
					});
					var prefix = 'data:image/svg+xml;base64,';
					var header = '<svg' +
						' xmlns="http://www.w3.org/2000/svg"' +
						' xmlns:xlink="http://www.w3.org/1999/xlink"' +
						(" viewBox=\"" + minX + " " + minY + " " + maxX + " " + maxY + "\"") +
						(" width=\"" + maxX + "\"") +
						(" height=\"" + maxY + "\"") +
						'>';
					var body = svg.innerHTML;
					if (body === undefined) {
						var dummy = document.createElement('dummy');
						var nodes = svg.childNodes;
						dummy.innerHTML = '';
						for (var i = 0; i < nodes.length; i += 1) {
							dummy.appendChild(nodes[i].cloneNode(true));
						}
						body = dummy.innerHTML;
					}
					var footer = '</svg>';
					var data = header + body + footer;
					// alert("SVG : "+data);
					save_sign(data);
					return prefix + btoa(data);
				};
				return SignaturePad;
			}());

			return SignaturePad;

		})));
	</script>

	<script>
		var wrapper = document.getElementById("signature-pad");
		var clearButton = wrapper.querySelector("[data-action=clear]");
		var changeColorButton = wrapper.querySelector("[data-action=change-color]");
		var undoButton = wrapper.querySelector("[data-action=undo]");
		var savePNGButton = wrapper.querySelector("[data-action=save-png]");
		var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
		var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
		var canvas = wrapper.querySelector("canvas");
		var signaturePad = new SignaturePad(canvas, {
			// It's Necessary to use an opaque color when saving image as JPEG;
			// this option can be omitted if only saving as PNG or SVG
			backgroundColor: 'rgb(255, 255, 255)'
		});

		// Adjust canvas coordinate space taking into account pixel ratio,
		// to make it look crisp on mobile devices.
		// This also causes canvas to be cleared.
		function resizeCanvas() {
			// When zoomed out to less than 100%, for some very strange reason,
			// some browsers report devicePixelRatio as less than 1
			// and only part of the canvas is cleared then.
			var ratio = Math.max(window.devicePixelRatio || 1, 1);
			// This part causes the canvas to be cleared
			canvas.width = canvas.offsetWidth * ratio;
			canvas.height = canvas.offsetHeight * ratio;
			canvas.getContext("2d").scale(ratio, ratio);

			// This library does not listen for canvas changes, so after the canvas is automatically
			// cleared by the browser, SignaturePad#isEmpty might still return false, even though the
			// canvas looks empty, because the internal data of this library wasn't cleared. To make sure
			// that the state of this library is consistent with visual state of the canvas, you
			// have to clear it manually.
			signaturePad.clear();
		}

		// On mobile devices it might make more sense to listen to orientation change,
		// rather than window resize events.
		window.onresize = resizeCanvas;
		resizeCanvas();

		function download(dataURL, filename) {
			if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) {
				window.open(dataURL);
			} else {
				var blob = dataURLToBlob(dataURL);
				var url = window.URL.createObjectURL(blob);

				var a = document.createElement("a");
				a.style = "display: none";
				a.href = url;
				a.download = filename;
				// document.body.appendChild(a);
				// a.click();

				// window.URL.revokeObjectURL(url);
			}
		}

		// One could simply use Canvas#toBlob method instead, but it's just to show
		// that it can be done using result of SignaturePad#toDataURL.
		function dataURLToBlob(dataURL) {
			// Code taken from https://github.com/ebidel/filer.js
			var parts = dataURL.split(';base64,');
			var contentType = parts[0].split(":")[1];
			var raw = window.atob(parts[1]);
			var rawLength = raw.length;
			var uInt8Array = new Uint8Array(rawLength);
			var Str = "";
			for (var i = 0; i < rawLength; ++i) {
				uInt8Array[i] = raw.charCodeAt(i);
				Str += uInt8Array[i];
			}

			var bbb = new Blob([uInt8Array], {
				type: contentType
			});
			return new Blob([uInt8Array], {
				type: contentType
			});
		}

		clearButton.addEventListener("click", function(event) {
			signaturePad.clear();
		});

		undoButton.addEventListener("click", function(event) {
			var data = signaturePad.toData();

			if (data) {
				data.pop(); // remove the last dot or line
				signaturePad.fromData(data);
			}
		});

		changeColorButton.addEventListener("click", function(event) {
			var r = Math.round(Math.random() * 255);
			var g = Math.round(Math.random() * 255);
			var b = Math.round(Math.random() * 255);
			var color = "rgb(" + r + "," + g + "," + b + ")";

			signaturePad.penColor = color;
		});

		savePNGButton.addEventListener("click", function(event) {
			if (signaturePad.isEmpty()) {
				alert("Please provide a signature first.");
			} else {
				var dataURL = signaturePad.toDataURL();
				download(dataURL, "signature.png");
			}
		});

		saveJPGButton.addEventListener("click", function(event) {
			if (signaturePad.isEmpty()) {
				alert("Please provide a signature first.");
			} else {
				var dataURL = signaturePad.toDataURL("image/jpeg");
				download(dataURL, "signature.jpg");
			}
		});

		saveSVGButton.addEventListener("click", function(event) {
			if (signaturePad.isEmpty()) {
				alert("Please provide a signature first.");
			} else {
				var dataURL = signaturePad.toDataURL('image/svg+xml');
				download(dataURL, "signature.svg");
			}
		});
	</script>



  <!-- <script>
    $(function() {
      var sig = $('#sig').signature();
      $('#clear').click(function() {
        sig.signature('clear');
      });
      $('#svg').click(function() {
        var SignSVG = sig.signature('toSVG');
        var DocNo = $('#docno').val();
        $('#chk_sign').val(0);
        $.ajax({
          url: '../process/UpdateSign.php',
          dataType: 'text',
          cache: false,
          data: {
            SignSVG: SignSVG,
            DocNo: DocNo,
            Table: "shelfcount",
            Column: "signature_web"
          },
          type: 'post',
          success: function(data) {
            swal({
              title: '',
              text: '<?php echo $array['savesuccess'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $('#sig').signature('clear');;
            $('#ModalSign').modal('toggle');
          }
        });
      });
    });
  </script> -->
  <!-- <script>
    $(function() {
      var sig = $('#sig').signature();
      $('#clear').click(function() {
        sig.signature('clear');
      });
      $('#svg').click(function() {
        var SignSVG = sig.signature('toSVG');
        var DocNo = $('#docno').val();
        $('#chk_sign').val(0);
        $.ajax({
          url: '../process/UpdateSign.php',
          dataType: 'text',
          cache: false,
          data: {
            SignSVG: SignSVG,
            DocNo: DocNo,
            Table: "shelfcount",
            Column: "signature_web"
          },
          type: 'post',
          success: function(data) {
            swal({
              title: '',
              text: '<?php echo $array['savesuccess'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $('#sig').signature('clear');;
            $('#ModalSign').modal('toggle');
          }
        });
      });
    });
  </script> -->
</body>

</html>