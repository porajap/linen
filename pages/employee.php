<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
if ($Userid == "") {
   header("location:../index.html");
}

if(empty($_SESSION['lang'])){
    $language ='th';
}else{
    $language =$_SESSION['lang'];

}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, true);
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

        $(document).ready(function(e) {
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            //On create
            // var userid = '<?php echo $Userid; ?>';
            // if(userid!="" && userid!=null && userid!=undefined){
            getHotpital();

            ShowItem();

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

        }).mousemove(function(e) { parent.last_move = new Date();;
        }).keyup(function(e) { parent.last_move = new Date();;
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

            getHotpital();

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
            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function getHotpital() {
          var data = {
              'STATUS': 'getHotpital'
          };
          // console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        }

        function getSection() {
            var HptCode = $('#hptsel2').val();
            $('#TableDocumentSS tbody').empty();
            var data = {
                'STATUS': 'getSection',
                'HptCode': HptCode
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function CreateSentSterile() {
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
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

            // console.log(JSON.stringify(data));
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
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'SSDTag',
                'DEPT': dept,
                'userid': userid,
                'DocNo': DocNo,
                'UsageCode': UsageCode
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function CreatePayout() {
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'CreatePayout',
                'DEPT': dept,
                'userid': userid
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function AddPayoutDetail() {
            var userid = '<?php echo $Userid; ?>';
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'CreatePayout',
                'DEPT': dept,
                'userid': userid
            };

            // console.log(JSON.stringify(data));
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

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function AddItem() {
            var HptSel = $("#hptsel2").val();
            var DepSel = $("#depsel").val();
            var EmpCode = $('#EmpCode').val();
            var EmpFName = $('#EmpFName').val();
            var EmpLName = $('#EmpLName').val();

            // console.log(HptSel+" : "+DepSel+" : "+EmpCode+" : "+EmpFName+" : "+EmpLName);
            // alert(HptSel+" : "+DepSel+" : "+EmpCode+" : "+EmpFName+" : "+EmpLName);
            var data = {
                'STATUS'  : 'AddItem',
                'HptSel'  : HptSel,
                'DepSel'  : DepSel,
                'EmpCode' : EmpCode,
                'EmpFName': EmpFName,
                'EmpLName': EmpLName
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
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
                var EmpCode = $('#EmpCode').val();
                var data = {
                    'STATUS': 'CancelItem',
                    'EmpCode': EmpCode
                }
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            })
        }

        function Blankinput() {
          $("#hptsel2").empty();
          $("#depsel").empty();
          $('#EmpCode').val('');
          $('#EmpFName').val('')
          $('#EmpLName').val('')
          ShowItem();
          getHotpital();
        }

        function getdetail(EmpCode) {
            if (EmpCode != "" && EmpCode != undefined) {
                var data = {
                    'STATUS': 'getdetail',
                    'EmpCode': EmpCode
                };

                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
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

                // console.log(JSON.stringify(data));
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

            // console.log(JSON.stringify(data));
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

                // console.log(JSON.stringify(data));
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
            var URL = '../process/employee.php';
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
                    // alert("Status : "+temp["status"]);
                    if (temp["status"] == 'success') {
                      // alert("Form : "+temp["form"]);
                        if ((temp["form"] == 'ShowItem')) {
                            $("#TableItem tbody").empty();
                            // console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var chkDoc = "<input type='radio' name='checkitem' id='checkitem' value='" + temp[i]['EmpCode'] + "' onclick='getdetail(\"" + temp[i]["EmpCode"] + "\")'>";
                                // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                StrTR = "<tr id='tr" + temp[i]['DepCode'] + "'>" +
                                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                    "<td style='width: 10%;'>" + temp[i]['EmpCode'] + "</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['FirstName'] + "</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['LastName'] + "</td>" +
                                    "<td style='width: 17%;'>" + temp[i]['DepName'] + "</td>" +
                                    "<td style='width: 17%;'>" + temp[i]['HptName'] + "</td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                            // Blankinput();
                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                // console.log(temp);

                                $('#EmpCode').val(temp['EmpCode']);
                                $('#EmpFName').val(temp['FirstName']);
                                $('#EmpLName').val(temp['LastName']);

                                var HptCode = temp['HptCode'];
                                var DepCode = temp['DepCode'];

                                var StrTr="";
                                $("#hptsel2").empty();
                                for (var i = 0; i < temp['HptCnt']; i++) {
                                    if(temp['Hpt'+i]['HptCode']==HptCode){
                                        StrTr = "<option selected value = '" + temp['Hpt'+i]['HptCode'] + "'> " + temp['Hpt'+i]['HptName'] + " </option>";
                                    }else{
                                        StrTr = "<option value = '" + temp['Hpt'+i]['HptCode'] + "'> " + temp['Hpt'+i]['HptName'] + " </option>";
                                    }
                                    $("#hptsel2").append(StrTr);
                                }
                                $("#depsel").empty();
                                for (var i = 0; i < temp['DepCnt']; i++) {
                                    if(temp['Dep'+i]['DepCode']==DepCode){
                                        StrTr = "<option selected value = '" + temp['Dep'+i]['DepCode'] + "'> " + temp['Dep'+i]['DepName'] + " </option>";
                                    }else{
                                      StrTr = "<option value = '" + temp['Dep'+i]['DepCode'] + "'> " + temp['Dep'+i]['DepName'] + " </option>";
                                    }
                                    $("#depsel").append(StrTr);
                                }
                            }
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

                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });
                                ShowItem();
                                Blankinput();
                            });

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

                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
                                ShowItem();
                            });
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

                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
                                ShowItem();
                            });
                        } else if ((temp["form"] == 'getHotpital')) {
                            $("#hptsel").empty();
                            $("#hptsel2").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#hptsel").append(StrTr);
                                $("#hptsel2").append(StrTr);
                            }
                            getSection();
                        }else if ((temp["form"] == 'getSection')) {
                              $("#depsel").empty();
                                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                    var StrTr = "<option value = '" + temp[i]['DepCode'] + "'> " + temp[i]['DepName'] + " </option>";
                                    $("#depsel").append(StrTr);
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
                            timer: 5000,
                            confirmButtonText: 'Ok'
                        });
                    } else if (temp['status'] == "notfound") {
                        swal({
                            title: '',
                            text: temp['msg'],
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            showConfirmButton: false,
                            timer: 5000,
                            confirmButtonText: 'Ok'
                        });
                        $("#TableItem tbody").empty();
                    }else{
                      swal({
                          title: '',
                          text: temp['msg'],
                          type: 'warning',
                          showCancelButton: false,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          showConfirmButton: false,
                          timer: 5000,
                          confirmButtonText: 'Ok'
                      });
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
                    swal({
                        title: 'Error...!',
                        text: err,
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        showConfirmButton: false,
                        timer: 5000,
                        confirmButtonText: 'Ok'
                    });
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

                                <div class="col-md-6">
                                    <div class="row" style="margin-left:5px;">
                                        <input type="text" class="form-control" style="width:70%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                                        <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem();">
                                            <?php echo $array['search'][$language]; ?></button>
                                    </div>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">

                                        <th style='width: 5%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['empcode'][$language]; ?>
                                        </th>
                                        <th style='width: 25%;'>
                                            <?php echo $array['empfname'][$language]; ?>
                                        </th>
                                        <th style='width: 25%;'>
                                            <?php echo $array['emplname'][$language]; ?>
                                        </th>
                                        <th style='width: 18%;'>
                                            <?php echo $array['department'][$language]; ?>
                                        </th>
                                        <th style='width: 17%;'>
                                            <?php echo $array['side'][$language]; ?>
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
            <div class="row">
                <div class="col-md-8">
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
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['side'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="hptsel2" onchange="getSection()">
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['department'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="depsel">
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['codecode'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control" style="width:90%;" name="EmpCode" id="EmpCode" placeholder="<?php echo $array['empcode'][$language]; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['empfname'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control checkblank" style="width:90%;" name="EmpFName" id="EmpFName" placeholder="<?php echo $array['empfname'][$language]; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['emplname'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control checkblank " style="width:90%;" name="EmpLName" id="EmpLName" placeholder="<?php echo $array['emplname'][$language]; ?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> <!-- tag column 2 -->
                <div class="col-md-4">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:50px;">
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-success" onclick="AddItem()">
                                                <?php echo $array['save'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-info" onclick="Blankinput()">
                                                <?php echo $array['clear'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-danger" onclick="CancelItem()">
                                                <?php echo $array['cancel'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- tag column 1 -->
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
