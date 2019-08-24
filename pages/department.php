<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  // header("location:../index.html");
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

    <title>
        <?php echo $array['department'][$language]; ?>
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
            Blankinput();
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            //On create
            // var userid = '<?php echo $Userid; ?>';
            // if(userid!="" && userid!=null && userid!=undefined){

            var HptCode = $('#hptsel').val();
            var Keyword = $('#searchitem').val();
            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            // }

            var data2 = {
                'STATUS': 'getSection',
                'HptCode': HptCode
            };
            console.log(JSON.stringify(data2));
            senddata(JSON.stringify(data2));

            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
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

        }).click(function(e) { parent.afk();
      }).keyup(function(e) { parent.afk();
      });

        dialog = jqui("#dialog").dialog({
            autoOpen: false,
            height: 650,
            width: 1200,
            modal: true,
            buttons: {
                "<?php echo $array['close'][$language]; ?>": function() {
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
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';

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
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
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
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
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
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
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
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            var data = {
                'STATUS': 'CreatePayout',
                'DEPT': dept,
                'userid': userid
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem() {
            var HptCode = $('#hptsel').val();
            var keyword = $('#searchitem').val();
            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': keyword
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

            var DepCode = $('#DepCode').val();
            var DepName = $('#DepName').val();
            var HptCode = $('#hptsel2').val();
            var xCenter = 0;
            if ($('#xCenter').is(':checked')) xCenter = 1;

            if (count == 0) {
                $('.checkblank').each(function() {
                    if ($(this).val() == "" || $(this).val() == undefined) {
                        $(this).css('border-color', 'red');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
                if (DepCode == "") {
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
                        showCancelButton: true
                    }).then(result => {
                        if (result.value) {

                        var data = {
                            'STATUS': 'AddItem',
                            'HptCode': HptCode,
                            'DepName': DepName,
                            'xCenter': xCenter
                        };

                        console.log(JSON.stringify(data));
                        senddata(JSON.stringify(data));
                    } else if (result.dismiss === 'cancel') {
            swal.close();
          }  
                    })

                } else {
                    swal({
                        title: "<?php echo $array['editdata'][$language]; ?>",
                        text: "<?php echo $array['editdata1'][$language]; ?>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonClass: "btn-warning",
                        confirmButtonText: "<?php echo $array['edit'][$language]; ?>",
                        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                        confirmButtonColor: '#6fc864',
                        cancelButtonColor: '#3085d6',
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showCancelButton: true
                    }).then(result => {
                        if (result.value) {

                        var data = {
                            'STATUS': 'EditItem',
                            'DepCode': DepCode,
                            'DepName': DepName,
                            'HptCode': HptCode,
                            'xCenter': xCenter
                        };

                        console.log(JSON.stringify(data));
                        senddata(JSON.stringify(data));
                    } else if (result.dismiss === 'cancel') {
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

        function CancelItem() {
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
                if (result.value) {

                var DepCode = $('#DepCode').val();
                var data = {
                    'STATUS': 'CancelItem',
                    'DepCode': DepCode
                }
                console.log(JSON.stringify(data));
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
            $('#hptsel2').val("BHQ");
            ShowItem();
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
        }

        function getdetail(DepCode, row) {
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
            if (DepCode != "" && DepCode != undefined) {
                var data = {
                    'STATUS': 'getdetail',
                    'DepCode': DepCode
                };

                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }
        }

        function SavePY() {
            $('#TableDocumentSS tbody').empty();
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';
            var datepicker = $('#datepicker').val();
            datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

            var DocNo = $("#docno").val();
            $("#searchtxt").val(DocNo);

            if (DocNo.length > 0) {
                swal({
                    title: '<?php echo $array['
                    savesuccess '][$language]; ?>',
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
                text: '<?php echo $array['
                logout '][$language]; ?>',
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

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/department.php';
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
                        if ((temp["form"] == 'ShowItem')) {
                            $("#TableItem tbody").empty();
                            console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var chkDoc = "<label class='container'style='margin-top: 20%;'><input type='radio' name='checkitem' id='checkitem_"+i+"' value='" + temp[i]['DepCode'] + "' onclick='getdetail(\"" + temp[i]["DepCode"] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                                // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                StrTR = "<tr id='tr" + temp[i]['DepCode'] + "'>" +
                                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                    "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                                    "<td style='width: 15%;'>" + temp[i]['DepCode'] + "</td>" +
                                    "<td style='width: 50%;'>" + temp[i]['DepName'] + "</td>" +
									"<td style='width: 20%; text-align: center;'>" + temp[i]['DefaultName'] + "</td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                console.log(temp);
                                $('#DepCode').val(temp['DepCode']);
                                $('#DepName').val(temp['DepName']);
                                $('#hptsel2').val(temp['HptCode']);
								
								if (temp['IsDefault'] == 1) 
									$('#xCenter').prop( "checked", true );
								else
									$('#xCenter').prop( "checked", false );
                            }
                                $('#bCancel').attr('disabled', false);
                                $('#delete_icon').removeClass('opacity');
                        } else if ((temp["form"] == 'AddItem')) {
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

                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'EditItem')) {
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

                                $('#DepCode').val("");
                                $('#hptsel2').val("BHQ");
                                ShowItem();
                            })
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
                            }).then(function() {
                                ShowItem();
                                Blankinput();
                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                $('#DepCode').val("");
                                $('#hptsel2').val("BHQ");
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'getSection')) {
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#hptsel").append(StrTr);
                                $("#hptsel2").append(StrTr);
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
  <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][2][$language]; ?></li>
</ol>
    <div id="wrapper">
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <!--
          <div class="mycheckbox">
            <input type="checkbox" name='useful' id='useful' onclick='setTag()'/><label for='useful' style='color:#FFFFFF'> </label>
          </div>
-->

            <div class="row">
                <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:-12px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row" style="margin-left:5px;">
                                        <select class="form-control" id="hptsel">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="row" style="margin-left:5px;">
                                        <input type="text" autocomplete="off"  class="form-control" style="width:70%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                                        <!-- <img src="../img/icon/i_search.png" style="margin-left: 15px;width:36px;"' class='mr-3'>
                                          <a href='javascript:void(0)' onclick="ShowItem()" id="bSave">
                                          <?php echo $array['search'][$language]; ?></a>       -->
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
                                <div class="col-md-2">

                                </div>
                            </div>
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                        <th style='width: 5%;'>&nbsp;</th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 15%;'>
                                            <?php echo $array['codecode'][$language]; ?>
                                        </th>
                                        <th style='width: 48%;'>
                                            <?php echo $array['department'][$language]; ?>
                                        </th>
                                        <th style='width: 22%; text-align: center;'>
                                            <?php echo $array['defaultname'][$language]; ?>
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
            <!-- /.content-wrapper -->
            <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
                          <div class="menu" <?php if($PmID == 3) echo 'hidden'; ?>>
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
                          <div class="menu">
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
                          <div class="menu"<?php if($PmID == 3) echo 'hidden'; ?>>
                            <div class="d-flex justify-content-center" >
                              <div class="circle3 d-flex justify-content-center">
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
   <!-- =================================================================== -->
                                <div class="row mt-4">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                      <select  class="form-control col-sm-8 " id="hptsel2" >
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['department'][$language]; ?></label>
                                      <input type="text"  class="form-control col-sm-8 checkblank" id="DepName" placeholder="<?php echo $array['department'][$language]; ?>">
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['codecode'][$language]; ?></label>
                                      <input type="text"  class="form-control col-sm-8 " id="DepCode" placeholder="<?php echo $array['codecode'][$language]; ?>" readonly>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                      <label class="col-sm-4 col-form-label text-right"><?php echo $array['xcenter'][$language]; ?></label>
                                      <input type="checkbox"  id="xCenter">
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->
                     

                        </div>
                    </div>
                </div> <!-- tag column 2 -->
<!-- <div class="sidenav mhee" style=" margin-left: 200px;margin-top: 73px;">
              <div class="" style="margin-top:5px;">
                <div class="card-body" style="padding:0px; margin-top:10px;">

                                    <div class="row" style="margin-top:0px;">
                                      <div class="col-md-3 icon" >
                                        <img src="../img/icon/ic_save.png" style='width:36px;' class='mr-3'>
                                      </div>
                                      <div class="col-md-9">
                                        <button class="btn" onclick="AddItem()" id="bSave">
                                          <?php echo $array['save'][$language]; ?>
                                        </button>
                                      </div>
                                    </div>
<div class="row" style="margin-top:0px;">
                                      <div class="col-md-3 icon" >
                                        <img src="../img/icon/i_clean.png" style='width:40px;' class='mr-3'>
                                      </div>
                                      <div class="col-md-9">
                                        <button class="btn" onclick="Blankinput()" id="bDelete">
                                          <?php echo $array['clear'][$language]; ?>
                                        </button>
                                      </div>
                                    </div>
          <div class="row" style="margin-top:0px;">
                                      <div class="col-md-3 icon" >
                                        <img src="../img/icon/ic_cancel.png" style='width:34px;' class='mr-3 opacity' id="delete_icon">
                                      </div>
                                      <div class="col-md-9">
                                        <button class="btn" onclick="CancelItem()" id="bCancel" disabled="true">
                                          <?php echo $array['cancel'][$language]; ?>
                                        </button>
                                      </div>
                                    </div>
              </div>
            </div>
          </div>
            </div> -->


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