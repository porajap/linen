<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
   header("location:../index.html");
}

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
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

    <title>
        <?php echo $array['setprice'][$language]; ?>
    </title>

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

    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <script src="../jQuery-ui/jquery-1.12.4.js"></script>
    <script src="../jQuery-ui/jquery-ui.js"></script>
    <link href="../css/responsive.css" rel="stylesheet">

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
            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            $('#rem').hide();
            getHotpital();
            getCategoryMain();
            getCategorySub(1);
            getDate_price();
            var HptCode = $('#hptsel').val();
            var data = {
                'STATUS': 'ShowItem1',
                'HptCode': HptCode
            };

          $('#datepicker').val(twoDigit(d.getDate())+"/"+(twoDigit(d.getMonth()+1))+"/"+d.getFullYear());

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            // }

            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem1();
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

        }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });


      function getHotpital() {
              var data2 = {
                  'STATUS': 'getHotpital'
              };
              // console.log(JSON.stringify(data2));
              senddata(JSON.stringify(data2));
      }

      function getCategoryMain() {
              var data2 = {
                  'STATUS': 'getCategoryMain'
              };
              // console.log(JSON.stringify(data2));
              senddata(JSON.stringify(data2));
      }

      function getCategorySub(Sel) {
        var CgrID;
        if(Sel==1)
          CgrID = $('#Category_Main').val();
        else
          CgrID = $('#Category_Main1').val();

        if( CgrID == null ) CgrID = 1;
              var data2 = {
                  'STATUS': 'getCategorySub',
                  'CgrID' : CgrID
              };
              // console.log(JSON.stringify(data2));
              senddata(JSON.stringify(data2));
      }

      function getItemPrice() {
          var data = {
              'STATUS': 'ShowItemPrice'
          };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
      }

        jqui(document).ready(function($){

            // dialog = jqui( "#dialog" ).dialog({
            //     autoOpen: false,
            //     height: 650,
            //     width: 1200,
            //     modal: true,
            //     buttons: {
            //         "<?php echo $array['close'][$language]; ?>": function() {
            //             // dialog.dialog( "close" );
            //             $('#dialog').modal('toggle');
            //         }
            //     },
            //     close: function() {
            //         console.log("close");
            //         $("#docno").val("");
            //     }
            // });

            jqui( "#dialogItem" ).button().on( "click", function() {
                // dialog.dialog( "open" );
                $('#dialog').modal('show');
            });

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
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'getDocDetail',
                'HptCode': HptCode,
                'DocNo': DepCode
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
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';

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

        function onCreate() {
            var xDate = $('#datepicker').val();
            var HptCode = $("#hptselModal").val();
            if(xDate==""){
                $('#rem').show(5).css("color","red");
            }else{
                $('#rem').hide();
                /* we join the array separated by the comma */
                xDate = xDate.substr(6,4)+"-"+xDate.substr(3,2)+"-"+xDate.substr(0,2);
                var data = {
                    'STATUS' : 'CreateDoc',
                    'Price' : Price,
                    'HptCode' : HptCode,
                    'xDate' : xDate
                };
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }

        function ShowDoc() {
            var HptCode = $('#hptsel2').val();
            var Keyword = $('#search2').val();

            var data = {
                'STATUS': 'ShowDoc',
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            senddata(JSON.stringify(data));
        }

        function ShowItem1(Sel) {
            var HptCode = $('#hptsel').val();
            var CgMainID = $('#Category_Main').val();
            var CgSubID = $('#Category_Sub').val();
            $('#bSave').attr('disabled', true);
            // $('#delete_icon').addClass('opacity');
     

            var data = {
                'STATUS': 'ShowItem1',
                'HptCode': HptCode,
                'CgMainID': CgMainID,
                'CgSubID': CgSubID
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem2() {
            var DocNo = $('#docno').val();
            var HptCode = $('#hptsel1').val();
            var Keyword = $('#search1').val();

            var data = {
                'STATUS': 'ShowItem2',
                'DocNo' : DocNo,
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function SavePrice(Sel) {
            var RowID = $('#RowID').val();
            var Price = $('#Price').val();

                var data = {
                    'STATUS': 'SavePrice',
                    'RowID': RowID,
                    'Price': Price
                };
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
        }

        function SavePriceTime(Sel) {
            var RowID = $('#RowID_'+Sel).val();
            var Price = $('#price_'+Sel).val();
            var DocNo = $('#docno').val();

            var data = {
                'STATUS': 'SavePriceTime',
                'RowID': RowID,
                'Price': Price,
                'Sel' : Sel,
                'DocNo' : DocNo
            };
            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }
        function saveDoc(){
            var DocNo = $('#docno').val();

            var chkArray = [];
            var chkPriceArray = [];

            $(".checkPrice").each(function() {
                chkArray.push($(this).val());
            });

            $(".price_array").each(function() {
                chkPriceArray.push($(this).val());
            });
            var RowId = chkArray.join(',');
            var Price = chkPriceArray.join(',');

            // alert(RowId);
            // alert(Price);
            swal({
                title: "<?php echo $array['confirm'][$language]; ?>",
                text: "<?php echo $array['savedoc'][$language]; ?> : " +$('#hptsel1 option:selected').text(),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true}).then(result => {
                    if (result.value) {

                    swal({
                        title: "<?php echo $array['savedoc'][$language]; ?>",
                        text: DocNo + " <?php echo $array['success'][$language]; ?>",
                        type: "success",
                        showCancelButton: false,
                        timer: 1000,
                        confirmButtonText: 'Ok',
                        showConfirmButton: false
                    });setTimeout(function () {
                        $('#dialog').modal('toggle');
                        var data = {
                            'STATUS'    : 'saveDoc',
                            'DocNo'  : DocNo,
                            'RowId'  : RowId,
                            'Price'	: Price
                        };
                        senddata(JSON.stringify(data));

                    }, 1000);
                } else if (result.dismiss === 'cancel') {
            swal.close();
          }
                });
        }

        function UpdatePrice() {
            var DocNo = $('#docno').val();
            var chkArray = [];
            var chkPriceArray = [];
            var chkCategoryCode = [];

            $(".checkPrice").each(function() {
                chkArray.push($(this).val());
            });

            $(".price_array").each(function() {
                chkPriceArray.push($(this).val());
            });

            $(".chkCategoryCode").each(function() {
                chkCategoryCode.push($(this).val());
            });
            var RowId = chkArray.join(',');
            var Price = chkPriceArray.join(',');
            var CategoryCode = chkCategoryCode.join(',');
            swal({
                title: "<?php echo $array['save'][$language]; ?>",
                text: "<?php echo $array['updateprice'][$language]; ?>",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                confirmButtonColor: '#008000',
                cancelButtonColor: '#e60000',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                if (result.value) {

                var data = {
                    'STATUS': 'UpdatePrice',
                    'DocNo': DocNo,
                    'Price':Price,
                    'CategoryCode':CategoryCode,
                    'RowId':RowId
                };
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            } else if (result.dismiss === 'cancel') {
            swal.close();
          }
            })

        }

        function Blankinput() {
            $('.checkblank').each(function() {
                $(this).val("");
            });
            $('#DepCode').val("");
            $('#hptsel2').val("1");
            $('#Category_Sub2').val("");
            $('#Category_Main2').val("");
            $('#Price').val("");    
            ShowItem();
        }

        function getdetail(RowID , row) {
        var previousValue = $('#checkitem_'+row).attr('previousValue');
        var name = $('#checkitem_'+row).attr('name');
        if (previousValue == 'checked') {
          $('#checkitem_'+row).removeAttr('checked');
          $('#checkitem_'+row).attr('previousValue', false);
          $('#checkitem_'+row).prop('checked', false);
          Blankinput();
        } else {
          $("input[name="+name+"]:radio").attr('previousValue', false);
          $('#checkitem_'+row).attr('previousValue', 'checked');
            if (RowID != "" && RowID != undefined) {
                var data = {
                    'STATUS': 'getdetail',
                    'RowID': RowID
                };

                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }
    }
        function cancelDoc(DocNo,row){
            $('.btn_cancel').each(function() {
                $(".btn_cancel").attr("disabled", true);
            });
            var DocNo = DocNo;
            var row = row;
            $('#cancel').val(DocNo);
            $('#show_btn').attr('disabled', false);
            $('#cancel_btn'+row+'').attr('disabled', false);
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
                if (result.value) {

                swal({
                    title: "<?php echo $array['canceldata'][$language]; ?>",
                    text: " <?php echo $array['success'][$language]; ?>",
                    type: "success",
                    showCancelButton: false,
                    timer: 1000,
                    // confirmButtonText: 'Ok',
                    showConfirmButton: false
                });
                setTimeout(function () {
                    var data = {
                        'STATUS': 'CancelDocNo',
                        'DocNo': docno
                    };
                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                    getSearchDocNo();
                }, 1000);
            } else if (result.dismiss === 'cancel') {
            swal.close();
          } 
            })
        }

        function OpenDialog(Sel){
            var selectdocument = "";

            if(Sel==1) {
                $("#checkdocno:checked").each(function () {
                    selectdocument = $(this).val();
                });
            }

            $("#docno").val("");
            $("#datepicker").val("");

            if(selectdocument!=""){
                var aData = selectdocument.split(",");
                $("#docno").val(aData[0]);
                $("#datepicker").val(aData[1]);
                $("#create1").hide();

                $("#hptsel1").empty();
                var StrTr = "<option selected value = '" + aData[2] + "'> " + aData[3] + " </option>";
                $("#hptsel1").append(StrTr);
                ShowItem2();
            }else{
                getHotpital();
                $("#create1").show();
                $('#btn_save').attr('hidden', true);
                $('#btn_saveDoc').attr('hidden', true);
            }
            $("#search1").hide();
            $("#TableItemPrice tbody").empty();

            // dialog.dialog( "open" );
            $('#dialog').modal('show');
        }

        function CancelDocNo(docno) {
            swal({
                title: "<?php echo $array['cancel'][$language]; ?>",
                text: "<?php echo $array['canceldata4'][$language]; ?> "+ docno,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                confirmButtonColor: '#008000',
                cancelButtonColor: '#e60000',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                var data = {
                    'STATUS'      : 'CancelDocNo',
                    'DocNo'       : docno
                };
                senddata(JSON.stringify(data));
            })
        }

        function getDate_price()
        {
            var HptCode = $('#hptsel1').val();
            var data = 
            {
                'STATUS': 'getDate_price',
                'HptCode': HptCode
            }
            senddata(JSON.stringify(data));

        }

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/set_price.php';
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
                        title: '<?php echo $array['
                        pleasewait '][$language]; ?>',
                        text: '<?php echo $array['
                        processing '][$language]; ?>',
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

                        if ((temp["form"] == 'CreateDoc')) {
                            $("#docno").val( temp["DocNo"] );
                            $("#create1").hide(300);
                            setTimeout(function () {
                                $("#btn_save").attr('hidden', false);
                                $("#btn_saveDoc").attr('hidden', false);
                            }, 200);
                            
                            ShowItem2();
                        }else if ((temp["form"] == 'UpdatePrice')) {
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                // confirmButtonText: 'Ok'
                            });
                            setTimeout(function () {
                                $('#dialog').modal('toggle');
                            }, 2000);
                            
                        }else if ((temp["form"] == 'CancelDocNo')) {
                                ShowDoc();
                        }else if ((temp["form"] == 'ShowDoc')) {
                            $("#TableDoc tbody").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableDoc >tbody >tr').length;
                                var chkDoc = "<input type='radio' class='checkblank' data-value='"+i+"' name='checkdocno' id='checkdocno' " + "value='" + temp[i]['DocNo'] + "," + temp[i]['xDate'] + "," + temp[i]['HptCode'] + "," + temp[i]['HptName'] + "' onclick='cancelDoc(\"" + temp[i]["DocNo"] + "\","+i+")'>";
                                    StrTR = "<tr id='tr"+temp[i]['DocNo']+"'>" +
                                        "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                        "<td style='width: 25%;'>" + temp[i]['HptName'] + "</td>" +
                                        "<td style='width: 26%;'>" + temp[i]['DocNo'] + "</td>" +
                                        "<td style='width: 25%;'>" + temp[i]['xDate'] + "</td>" +
                                        "<td style='width: 19%;'><button class='btn btn_cancel' style='background: none;' onclick='canceldocno(\"" + temp[i]["DocNo"] + "\");' id='cancel_btn"+i+"' disabled='true'><i class='fas fa-trash'></i></button></td>" +
                                        "</tr>";
                                    if (rowCount == 0) {
                                        $("#TableDoc tbody").append(StrTR);
                                    } else {
                                        $('#TableDoc tbody:last-child').append(StrTR);
                                    }
                            }

                        }else if ((temp["form"] == 'ShowItem1')) {
                            $("#TableItem tbody").empty();
                            // console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem'  id='checkitem_"+i+"' style='margin-top: 24%;' value='" + temp[i]['RowID'] + "' onclick='getdetail(\"" + temp[i]["RowID"] + "\",\""+i+"\")'><span class='checkmark'></span></label>";
                                var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:150px; margin-left:3px; margin-right:3px; text-align:center;' id='price_"+i+"' value='"+temp[i]['Price']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

                                StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                                "<td style='width: 5%;' nowrap>" + chkDoc + "</td>" +
                                "<td style='width: 25%;' nowrap>" + temp[i]['HptName'] + "</td>" +
                                "<td style='width: 26%;' nowrap>" + temp[i]['MainCategoryName'] + "</td>" +
								"<td style='width: 25%;' nowrap>" + temp[i]['CategoryName'] + "</td>" +
                                "<td style='width: 19%;' nowrap>" + temp[i]['Price'] + " </td>" +
                                "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                        }else if ((temp["form"] == 'ShowItem2')) {
                            $("#TableItemPrice tbody").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var RowID = "<input type='hidden' name='RowID_"+i+"' id='RowID_"+i+"' value='" + temp[i]['RowID'] +"'>";
                                var Price = "<div class='row' style='margin-left:2px;'><input class='form-control price_array' style='height:40px;width:150px; margin-left:3px; margin-right:3px; text-align:center;' id='price_"+i+"' value='"+temp[i]['Price']+"' onKeyPress='if(event.keyCode==13){SavePriceTime("+i+")}'></div>";
                                var chkPrice = "<input type='radio' name='checkPrice' class='checkPrice' value='"+temp[i]['RowID']+"'>";
                                var chkCategoryCode = "<input type='radio' name='chkCategoryCode' class='chkCategoryCode' value='"+temp[i]['CategoryCode']+"'>";

                                StrTR = "<tr id='tr" + RowID + "'>" +
                                    "<td style='width: 5%;' nowrap>"+ RowID +"</td>" +
                                    "<td hidden>"+ chkPrice +"</td>" +
                                    "<td hidden>"+ chkCategoryCode +"</td>" +
                                    "<td style='width: 25%;' nowrap>" + temp[i]['HptName'] + "</td>" +
                                    "<td style='width: 26%;' nowrap>" + temp[i]['MainCategoryName'] + "</td>" +
                                    "<td style='width: 25%;' nowrap>" + temp[i]['CategoryName'] + "</td>" +
                                    "<td style='width: 19%;' nowrap>" + Price + " </td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItemPrice tbody").append(StrTR);
                                } else {
                                    $('#TableItemPrice tbody:last-child').append(StrTR);
                                }
                                var rowCount = i;
                            }
                            $('#rowCount').val(rowCount+1);
                            $("#hptselModal").empty();
                            for (var i = 0; i < 1; i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#hptselModal").append(StrTr);
                            }

                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                $('#RowID').val(temp['RowID']);
                                $('#HotName').val(temp['HptName']);
                                $('#Category_Main2').val(temp['MainCategoryName']);
                                $('#Category_Sub2').val(temp['CategoryName']);
                                $('#Price').val(temp['Price']);
                            }
                            $('#bSave').attr('disabled', false);
                        } else if ((temp["form"] == 'SavePrice')) {
                            $('#RowID').val("");
                            $('#HotName').val("");
                            $('#CategoryMain').val("");
                            $('#CategorySub').val(temp['CategoryName']);
                            $('#Price').val(temp['Price']);

                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";

                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 1000,
                                confirmButtonText: 'Ok'
                            })

                            ShowItem1();
                        } else if ((temp["form"] == 'SavePriceTime')) {
                            $('#RowID').val("");
                            $('#HotName').val("");
                            $('#CategoryMain').val("");
                            $('#CategorySub').val(temp['CategoryName']);
                            $('#Price').val(temp['Price']);
                            var rowCount = $('#TableDoc >tbody >tr').length;
                            var Sel = temp["Sel"];
                            var cn = temp["Cnt"];
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            var rowCount = $('#rowCount').val();
                            if((Sel+1)==rowCount)
                                $('#price_0').focus().select();
                            else
                                $('#price_'+(Sel+1)).focus().select();

                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 1000,
                                confirmButtonText: 'Ok'
                            })
                        } else if ((temp["form"] == 'getHotpital')) {
                            $("#hptsel").empty();
                            $("#hptsel1").empty();
                            $("#hptsel2").empty();
                            $("#hptselModal").empty();
                            var hotValue0 = '  <?php echo $array['Allside'][$language]; ?>';
                            var StrTr = "<option value=''>"+hotValue0+"</option>";
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
                                var StrTr2 = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
                                $("#hptselModal").append(StrTr2);
                           
                            }
                            $("#hptsel1").append(StrTr);
                            $("#hptsel2").append(StrTr);
                            $("#hptsel").append(StrTr);

                        } else if ((temp["form"] == 'getCategoryMain')) {
                          $("#Category_Main").empty();
                          $("#Category_Main1").empty();
                            var hotValue0 = '<?php echo $array['AllCatmain'][$language]; ?>';
                            var StrTr = "<option value=''>"+hotValue0+"</option>";
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value="+temp[i]['MainCategoryCode']+">"+temp[i]['MainCategoryName']+"</option>";
                            }
                            $("#Category_Main").append(StrTr);
                            $("#Category_Main1").append(StrTr);
                            
                        } else if ((temp["form"] == 'getCategorySub')) {
                          $("#Category_Sub").empty();
                          $("#Category_Sub1").empty();
                            var hotValue0 = '<?php echo $array['AllCatsub'][$language]; ?>';
                            var StrTr = "<option value=''>"+hotValue0+"</option>";
                              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value="+temp[i]['CategoryCode']+">"+temp[i]['CategoryName']+"</option>";
                              }
                              $("#Category_Sub").append(StrTr);
                                  $("#Category_Sub1").append(StrTr);
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
                            timer: 2000,
                            confirmButtonText: 'Ok'
                        })

                    } else if (temp['status'] == "notfound") {
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
                        $("#TableItem tbody").empty();
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
            body{
            font-family: myFirstFont;
            font-size:22px;
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
                background:none;
                 box-shadow:none!important;    
                display: block;
                }
                .mhee button:hover {
                color: #2c3e50;
                font-weight:bold;
                font-size:26px;
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
  <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][11][$language]; ?></li>
</ol>
    <div id="wrapper">
    <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
      <i class="fas fa-angle-down"></i>
    </a>
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <div class="container-fluid">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['setprice'][$language]; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:12px;">
                                        <div class="row col-md-12">
                                          <div class="col-md-4">
                                              <div class="row" style="margin-left:-30px;">
                                              <label class="col-sm-5 col-form-label text-right"style="margin-left: -90px;"><?php echo $array['side'][$language]; ?></label>
                                                  <select class="form-control col-md-7" id="hptsel">
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="row" style="margin-left:5px;">
                                              <label class="col-sm-5 col-form-label text-right"style="margin-left: -100px;"><?php echo $array['categorymain'][$language]; ?></label>
                                                  <select class="form-control col-md-7" id="Category_Main" onchange="getCategorySub(1);"></select>
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="row" style="margin-left:-29px;">
                                              <label class="col-sm-6 col-form-label text-right"style="margin-left: -70px;"><?php echo $array['categorysub'][$language]; ?></label>
                                                  <select class="form-control col-md-7" id="Category_Sub"></select>
                                              </div>
                                          </div>
                                          <div class="col-md-1 text-right">
                                            <div class="row" style="margin-left:5px;">
                                            <div class="search_custom col-md-2">
                                          <div class="search_1 d-flex justify-content-start">
                                            <button class="btn" onclick="ShowItem1()">
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
                                                    <th style='width: 5%;'>&nbsp;</th>
                                                    <th style='width: 25%;' nowrap>
                                                        <?php echo $array['side'][$language]; ?>
                                                    </th>
                                                    <th style='width: 25%;' nowrap>
                                                        <?php echo $array['categorymain'][$language]; ?>
                                                    </th>
                                                    <th style='width: 25%;' nowrap>
                                                        <?php echo $array['categorysub'][$language]; ?>
                                                    </th>
                                                    <th style='width: 20%;' nowrap>
                                                        <?php echo $array['price'][$language]; ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- tag column 1 -->
                        </div>
<!-- =============================================================================================================================== -->

 <!-- /.content-wrapper -->
 <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
                        <div class="menu" <?php if($PmID == 3) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle4 d-flex justify-content-center">
                                <button class="btn"  onclick="SavePrice()" id="bSave" disabled="true">
                                  <i class="fas fa-save"></i>
                                  <div>
                                    <?php echo $array['save'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="menu" <?php if($PmID == 3) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center">
                              <div class="circle3 d-flex justify-content-center">
                                <button class="btn"  onclick="OpenDialog(2)" id="bDelete" >
                                <i class="fas fa-money-check"></i>
                                  <div>
                                    <?php echo $array['setprice'][$language]; ?>
                                  </div>
                                </button>
                              </div>
                            </div>
                          </div>

                            </div>


<!-- =============================================================================================================================== -->                        

<div class="row">
                            <div class="col-md-12">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:10px;">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                                    <?php echo $array['detail'][$language]; ?></a>
                                            </li>
                                        </ul>

                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-md-6" style="margin-left:15px;">
                                                <div class="row">
                                                    <input type="hidden" class="form-control" style="width:90%;" name="RowID" id="RowID" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- =================================================================== -->
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="HotName"    placeholder="<?php echo $array['side'][$language]; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['categorymain'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="Category_Main2"    placeholder="<?php echo $array['categorymain'][$language]; ?>">
                                                </div>
                                            </div>
                                            </div>  
                     
                                        <!-- =================================================================== -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['categorysub'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="Category_Sub2"    placeholder="<?php echo $array['categorysub'][$language]; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['price'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 numonly" id="Price"    placeholder="<?php echo $array['price'][$language]; ?>">
                                                </div>
                                            </div>
                                            </div>  
                     
                              
                                        <!-- =================================================================== -->

                                    </div>
                                </div>
                            </div> <!-- tag column 2 -->

<!-- =============================================================================================== -->


                        </div>
                    </div>
                    <!-- search document -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body" style="padding:0px; margin-top:12px;margin-left:30px;">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="row">
                                        <select class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:250px;" id="hptsel2"></select>
                                        <div class="search_custom col-md-2">
                                            <div class="search_1 d-flex justify-content-start">
                                              <button class="btn"  onclick="ShowDoc()" id="bSavex" >
                                                <i class="fas fa-search mr-2"></i>
                                                <?php echo $array['search'][$language]; ?>
                                              </button>
                                            </div>
                                          </div>

                                        <div class="search_custom col-md-2">
                                            <div class="circle6 d-flex justify-content-start">
                                              <button class="btn"  onclick="OpenDialog(1)" id="show_btn" disabled='true'>
                                                <i class="fas fa-paste mr-2 pt-1"></i>
                                                <?php echo $array['show'][$language]; ?>
                                              </button>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider" style="margin-top:20px; margin-bottom:20px;"></div>

                            <div class="row">
                                <div class="card-body" style="padding:0px;">
                                    <table class="table table-fixed table-condensed table-striped" id="TableDoc" cellspacing="0" role="grid" style="font-size:24px;width:98%;font-family: 'THSarabunNew'">
                                        <thead style="font-size:24px;">
                                            <tr role="row">
                                                <th style='width: 5%;' nowrap>&nbsp;</th>
                                                <th style='width: 25%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                                <th style='width: 25%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                                                <th style='width: 45%;' nowrap><?php echo $array['dateP'][$language]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:450px;" />
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

      

            <!-- -----------------------------Custom1------------------------------------ -->
<div class="modal" id="dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<input type="hidden" id="rowCount">

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
                        <div class="col-md-12 mhee">
                            <div class="row mb-3">
                                <select class="form-control ml-2" style="font-family: 'THSarabunNew';font-size:22px;width:250px;" id="hptselModal" onchange="getDate_price();"></select>

                                <label id="rem" style="margin-left:20px;"> *** </label>
                                <input type="text" autocomplete="off" class="form-control datepicker-here numonly" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:168px;" id="datepicker" data-language='en' data-date-format='dd/mm/yyyy' placeholder="<?php echo $array['datepicker'][$language]; ?>">
                                <!-- <input type="text" class="form-control datepicker-here" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:150px;" id="datepicker"> -->
                                <input type="text" autocomplete="off"  disabled="true" class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:200px;" name="docno" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >


                                <div class="search_custom col-md-2" id="create1">
                                    <div class="circle1 d-flex justify-content-start">
                                        <button class="btn" onclick="onCreate()" >
                                            <i class="fas fa-file-medical mr-2"></i>
                                            <?php echo $array['createdocno'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                                <input type="text" class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:210px;" name="search1"   id="search1" onKeyPress='if(event.keyCode==13){ShowItem2()}' placeholder="<?php echo $array['search'][$language]; ?>" >
                                <div class="search_custom col-md-2" id="btn_save"  hidden="true">
                                    <div class="import_1 d-flex justify-content-start">
                                        <button class="btn" onclick="UpdatePrice()" >
                                            <i class="fas fa-file-import mr-2 pt-1"></i>
                                            <?php echo $array['updateprice'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="search_custom col-md-2" id="btn_saveDoc"  hidden="true">
                                    <div class="circle4 d-flex justify-content-start">
                                        <button class="btn" onclick="saveDoc()" >
                                            <i class="fas fa-save mr-2"></i>
                                            <?php echo $array['savedoc'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                                <!-- <button onclick="UpdatePrice();" class="mr-3 ml-3 btn" style="font-size: 25px !important;background:none ;" id="btn_save" hidden="true"><img src="../img/icon/ic_import.png" style='width:31px; ' class="mr-1"><?php echo $array['updateprice'][$language]; ?></button> -->
                                <!-- <button onclick="saveDoc();" class="mr-3 ml-3 btn" style="font-size: 25px !important;background:none ;" id="btn_saveDoc" hidden="true"><img src="../img/icon/ic_save.png" style='width:31px; ' class="mr-1"><?php echo $array['savedoc'][$language]; ?></button> -->

                                <!-- <button type="button" style="font-size:18px;margin-left:20px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" id="btn_save" disabled="true" onclick="UpdatePrice();"><?php echo $array['saveprice'][$language]; ?></button> -->
                            </div>
                        </div>
                    </div>
                    <table class="table table-fixed table-condensed table-striped" id="TableItemPrice" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                        <thead style="font-size:24px;">
                            <tr role="row">
                            <th style='width: 5%;'>&nbsp;</th>
                                <th style='width: 25%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                <th style='width: 25%;' nowrap><?php echo $array['categorymain'][$language]; ?></th>
                                <th style='width: 25%;' nowrap><?php echo $array['categorysub'][$language]; ?></th>
                                <th style='width: 20%;' nowrap><?php echo $array['price'][$language]; ?></th>
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
<div id="page-down" style="height:50px"></div>
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
