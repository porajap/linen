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
    <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
        var summary = [];

        $(document).ready(function(e)
        {
            $(".select2").select2();
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            $('#rem4').hide();
            getSection();
            GetGroup();
            //On create
            $('.TagImage').bind('click',
            {
                imgId: $(this).attr('id')
            }, function(evt)
            {
                alert(evt.imgId);
            });
            $('#searchitem').keyup(function(e)
            {
                if (e.keyCode == 13) {
                    ShowItem();
                }
            });
            $('.editable').click(function()
            {
                alert('hi');
            });
            $('.numonly').on('input', function()
            {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            $('.charonly').on('input', function()
            {
                this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
            });

        }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });
        function getSection()
        {
            var lang = '<?php echo $language; ?>';
            var data2 = {
                'STATUS': 'getSection',
                'lang'	: lang
            };
            console.log(JSON.stringify(data2));
            senddata(JSON.stringify(data2));
        }

        dialog = jqui("#dialog").dialog({
            autoOpen: false,
            height: 650,
            width: 1200,
            modal: true,
            buttons:
            {
                "<?php echo $array['close'][$language]; ?>": function()
                {
                    dialog.dialog("close");
                }
            },
            close: function()
            {
                console.log("close");
            }
        });
        jqui("#dialogreq").button().on("click", function() {
            dialog.dialog("open");
        });

        function GetGroup()
        {
            Blankinput();
            ShowItem();
            var hptsel = $('#hptsel').val();
            $('#hptsel').css('border-color', '');
            $('#hptsel2').val(hptsel);

            var data =
            {
                'STATUS': 'GetGroup',
                'HptCode': hptsel
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem(chk)
        {
            if(chk==1)
            {
                var group = $('#group').val();
                $('#group2').val(group);
            }
            else if(chk==2)
            {
                var group = $('#group2').val();
                $('#group').val(group);
                $('#group2').css('border-color', '');
                $('#rem4').hide();
            }
            var group = $('#group').val();
            var HptCode = $('#hptsel').val();
            var keyword = $('#searchitem').val();
            var data =
            {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': keyword,
                'group': group
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function resetinputuser()
        {
            var hptsel = $('#hptsel2').val();
            $('#hptsel').val(hptsel);
            GetGroup();
            $('#DepName').val('');
            $('#DepCode').val('');
            $('#rem1').hide();
            $("#xCenter").prop('checked', false);
            $('#hptsel2').css('border-color', '');
            $('#hptsel').css('border-color', '');
        }

        function resetinput(chk)
        {
            if(chk ==2)
            {
                var DepCode = $('#DepCode').val();
                if(DepCode !="" && DepCode!=undefined)
                {
                    $('#rem3').hide();
                    $('#DepCode').css('border-color', '');
                }
            }

            var DepName = $('#DepName').val();

            if(DepName !="" && DepName!=undefined)
            {
                $('#rem2').hide();
                $('#DepName').css('border-color', '');
            }
        }

        function AddItem()
        {
            var count = 0;
            $(".checkblank").each(function()
            {
                if ($(this).val() == "" || $(this).val() == undefined)
                {
                    count++;
                }
            });
            console.log(count);
            var shipto = $('#shipto').val();
            var DepCode1 = $('#DepCodeReal').val();
            var DepCode = $('#DepCode').val();
            var DepName = $('#DepName').val();
            var HptCode = $('#hptsel2').val();
            var IsActive = $('#IsActive').val();
            var group2 = $('#group2').val();
            var xCenter = 0;
            if ($('#xCenter').is(':checked')) xCenter = 1;
            if (count == 0)
            {
                $('.checkblank').each(function()
                {
                    if ($(this).val() == "" || $(this).val() == undefined)
                    {
                        $(this).css('border-color', 'red');
                    }
                });

                if (DepCode != "")
                {
                    swal({
                        title: "<?php echo $array['addoredit'][$language]; ?>",
                        // text: "<?php echo $array['adddata1'][$language]; ?>",
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
                    }).then(result =>
                    {
                        if (result.value)
                        {
                            var data =
                            {
                                'STATUS': 'AddItem',
                                'HptCode': HptCode,
                                'DepName': DepName,
                                'DepCode': DepCode,
                                'DepCode1': DepCode1,
                                'xCenter': xCenter,
                                'IsActive': IsActive,
                                'group2': group2,
                                'shipto': shipto
                            };
                            console.log(JSON.stringify(data));
                            senddata(JSON.stringify(data));
                        }
                        else if (result.dismiss === 'cancel')
                        {
                            swal.close();
                        }  
                    })

                } 
            }
            else
            {
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
                $('.checkblank').each(function()
                {
                    if ($(this).val() == "" || $(this).val() == undefined)
                    {
                        $(this).css('border-color', 'red');
                        if(HptCode ==""||HptCode==undefined)
                        {
                            $('#rem1').show().css("color","red");
                        }
                        if(DepName ==""||DepName==undefined)
                        {
                            $('#rem2').show().css("color","red");
                        }
                        if(DepCode ==""||DepCode==undefined)
                        {
                            $('#rem3').show().css("color","red");
                        }
                        if(group2 ==""||group2==undefined)
                        {
                            $('#rem4').show().css("color","red");
                        }
                    }
                });
            }
        }

        function CancelItem()
        {
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
            }).then(result =>
            {
                if (result.value)
                {
                    var HptCode = $('#hptsel').val();
                    var DepCode = $('#DepCode').val();
                    var data =
                    {
                        'STATUS': 'CancelItem',
                        'DepCode': DepCode ,
                        'HptCode': HptCode
                    }
                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
                else if (result.dismiss === 'cancel')
                {
                    swal.close();
                }
            })
        }

        function Blankinput()
        {
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            $('#rem4').hide();
            $('#DepCode').attr('disabled' , false);
            $('.checkblank').each(function()
            {
                $(this).val("");
            });
            $('.checkblank').each(function()
            {
            if($(this).val()==""||$(this).val()==undefined)
            {
              $(this).css('border-color', '');
            }
            else
            {
              $(this).css('border-color', '');
            }
          });
            $('#shipto').val("");
            $('#DepCode').val("");
            var pm = '<?php  echo $PmID;   ?>';
                if(pm !=3 && pm !=5  && pm !=7)
                {
                    $('#hptsel2').val("");
                }
            $('#IsActive').val("1");
             $('#DepCodeReal').val("");
             $("#xCenter").prop('checked', false);
            ShowItem();
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
        }

        function getdetail(DepCode, row)
        {
            var HptCode = $('#hptsel').val();
            var number = parseInt(row)+1;
            var previousValue = $('#checkitem_'+row).attr('previousValue');
            var name = $('#checkitem_'+row).attr('name');
            if (previousValue == 'checked')
            {
                $('#DepCode').attr('disabled' , false);
                $('#checkitem_'+row).removeAttr('checked');
                $('#checkitem_'+row).attr('previousValue', false);
                $('#checkitem_'+row).prop('checked', false);
                $("#xCenter").prop('checked', false);
                Blankinput();
            }
            else
            {
                $('#DepCode').attr('disabled' , true);
                $("input[name="+name+"]:radio").attr('previousValue', false);
                $('#checkitem_'+row).attr('previousValue', 'checked');
                if (DepCode != "" && DepCode != undefined)
                {
                    var data =
                    {
                        'STATUS': 'getdetail',
                        'DepCode': DepCode ,
                        'number' : number ,
                        'HptCode' : HptCode 
                    };

                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
            }
        }

        function logoff()
        {
            swal({
                title: '',
                text: '<?php echo $array['logout '][$language]; ?>',
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

        function senddata(data)
        {
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
                beforeSend: function()
                {
                    swal({
                        title: '<?php echo $array['pleasewait '][$language]; ?>',
                        text: '<?php echo $array[' processing '][$language]; ?>',
                        allowOutsideClick: false
                    })
                    swal.showLoading();
                },
                success: function(result)
                {
                    try
                    {
                        var temp = $.parseJSON(result);
                    }
                    catch (e)
                    {
                        console.log('Error#542-decode error');
                    }
                    swal.close();
                    if (temp["status"] == 'success')
                    {
                        if ((temp["form"] == 'ShowItem'))
                        {
                            $("#TableItem tbody").empty();
                            console.log(temp);
                            if(temp['Count']>0)
                            {
                                var Active ;
                                for (var i = 0; i < (Object.keys(temp).length - 2); i++)
                                {
                                    if(temp[i]['IsActive'] == 1)
                                    {
                                    Active = 'active';
                                    }
                                    else
                                    {
                                    Active = 'inactive';
                                    }
                                    if(temp[i]['cnt'] > 0)
                                    {
                                        cnt = 'tied';
                                    }
                                    else
                                    {
                                        cnt = 'Not tied';
                                    }
                                    var rowCount = $('#TableItem >tbody >tr').length;
                                    var DefaultName = temp[i]['DefaultName'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                                    var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem' id='checkitem_"+i+"' value='" + temp[i]['DepCode'] + "' onclick='getdetail(\"" + temp[i]["DepCode"] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                                    StrTR = "<tr id='tr" + temp[i]['DepCode'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                                        "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                        "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                                        "<td style='width: 11%;'>" + temp[i]['DepCode'] + "</td>" +
                                        "<td style='width: 16.5%;'>" +  DefaultName+ "</td>" +
                                        "<td style='width: 26%;'>" +  temp[i]['DepName']  + "</td>" +
                                        "<td style='width: 20%;'>" +  Active  + "</td>" +
                                        "<td style='width: 10%;'>" +  cnt  + "</td>" +
                                        "</tr>";

                                    if (rowCount == 0)
                                    {
                                        $("#TableItem tbody").append(StrTR);
                                    }
                                    else
                                    {
                                        $('#TableItem tbody:last-child').append(StrTR);
                                    }
                                }
                            }
                            else
                            {
                                $('#TableItem tbody').empty();
                                var Str = "<tr width='100%' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                                $('#TableItem tbody:last-child').append(Str);

                            }
                        }
                        else if ((temp["form"] == 'getdetail'))
                        {
                            $("#group").empty();
                            var StrTr = "<option value=''><?php echo $array['selectgroup'][$language]; ?></option>";
                            for (var i = 0; i < temp['row']; i++)
                            {
                                 StrTr += "<option value = '" + temp[i]['GroupCode'] + "'> " + temp[i]['GroupName'] + " </option>";
                                var Str = "<option value = '" + temp[i]['GroupCode'] + "'> " + temp[i]['GroupName'] + " </option>";
                            }
                            $("#group").append(StrTr);
                            
                            if ((Object.keys(temp).length - 2) > 0)
                            {
                                console.log(temp);
                                $('#DepCodeReal').val(temp['DepCodeReal']);
                                $('#DepCode').val(temp['DepCodeReal']);
                                $('#DepName').val(temp['DepName']);
                                $('#hptsel2').val(temp['HptCode']);
                                if(temp['GroupCode'] != '')
                                {
                                    $('#group2').val(temp['GroupCode']);
                                }
                                $('#IsActive').val(temp['IsActive']);
                                $('#shipto').val(temp['shipto']);
								if (temp['IsDefault'] == 1) 
									$('#xCenter').prop( "checked", true );
								else
									$('#xCenter').prop( "checked", false );
                            }
                                $('#bCancel').attr('disabled', false);
                                $('#delete_icon').removeClass('opacity');
                                $('#delete1').addClass('mhee');

                        }

                        else if ((temp["form"] == 'AddItem'))
                        {
                            switch (temp['msg'])
                            {
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
                                    $(this).css('border-color', '');
                                });
                                $('#DepCode').val("");

                                ShowItem();
                            })
                        }

                        else if ((temp["form"] == 'EditItem'))
                        {
                            switch (temp['msg'])
                            {
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
                        }
                        
                        else if ((temp["form"] == 'CancelItem'))
                        {
                            switch (temp['msg'])
                            {
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
                        } 

                        else if ((temp["form"] == 'getSection'))
                        {
                            if(temp[0]['PmID'] != 5 && temp[0]['PmID'] != 7)
                            {
                                var StrTr = "<option value=''><?php echo $array['selecthospital'][$language]; ?></option>";
                            }
                            else
                            {
                                var StrTr = "";
                                $('#hptsel').attr('disabled' , true);
                                $('#hptsel').addClass('icon_select');
                                var Str = "";
                                $('#hptsel2').attr('disabled' , true);
                                $('#hptsel2').addClass('icon_select');
                            }
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++)
                            {
                                 StrTr += "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                var Str = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                            }
                            $("#hptsel").append(StrTr);
                            $("#hptsel2").append(StrTr);
                        } 

                        else if ((temp["form"] == 'GetGroup'))
                        {
                            $("#group").empty();
                            $("#group2").empty();
                            var StrTr = "<option value=''><?php echo $array['selectgroup'][$language]; ?></option>";
                            for (var i = 0; i < temp['row']; i++)
                            {
                                 StrTr += "<option value = '" + temp[i]['GroupCode'] + "'> " + temp[i]['GroupName'] + " </option>";
                                var Str = "<option value = '" + temp[i]['GroupCode'] + "'> " + temp[i]['GroupName'] + " </option>";
                            }
                            $("#group").append(StrTr);
                            $("#group2").append(StrTr);
                        }

                        else if ((temp["form"] == 'confirmdep'))
                        {
                            swal({
                            title: "ยืนยันการบันทึก",
                            text: "รหัสแผนกซํ้า : " + temp['DepCode'] + "  ",
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
                            }).then(result =>
                            {
                                if (result.value)
                                {
                                    var data =
                                    {
                                        'STATUS': 'AddItemConfirm',
                                        'HptCode': temp['HptCode'],
                                        'DepName': temp['DepName'],
                                        'DepCode': temp['DepCode'],
                                        'IsActive': temp['IsActive'],
                                        'group2': temp['group2'],
                                        'shipto': temp['shipto']

                                    };
                                    senddata(JSON.stringify(data));
                                }
                                else if (result.dismiss === 'cancel')
                                {
                                    swal.close();
                                }  
                            })
                        }

                    }
                    else if (temp['status'] == "failed")
                    {
                        switch (temp['msg'])
                        {
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
                                case "editcenterfailedmsg":
                                    temp['msg'] = "<?php echo $array['editcenterfailedmsg'][$language]; ?>";
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

                    }
                    else if (temp['status'] == "notfound")
                    {

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
                                    <div class="row" style="margin-left:5px;width: 130%;">
                                    <!-- <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label> -->
                                        <select class="form-control col-md-8 checkblank66 " id="hptsel" onchange="GetGroup();">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row" style="margin-left:-62px;">
                                    <!-- <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label> -->
                                        <select class="form-control col-md-8 " id="group" onchange="ShowItem(1);">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row" style="margin-left:15px;">
                                        <input type="text" autocomplete="off"  class="form-control" style="width:65%;margin-left: -21.7%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['SearchDepartment'][$language]; ?>">
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
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped " id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                        <th style='width: 5%;'>&nbsp;</th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['departmentcode'][$language]; ?>
                                        </th>
                                        <th style='width: 17%;'>
                                            <?php echo $array['xcenter'][$language]; ?>
                                        </th>
                                        <th style='width: 26%; '>
                                            <?php echo $array['department'][$language]; ?>
                                        </th>
                                        <th style='width: 20%; '>
                                            <?php echo $array['status'][$language]; ?>
                                        </th>
                                        <th style='width: 12%; '>
                                            <?php echo $array['pardep'][$language]; ?>
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
                          <div class="menu mhee" >
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
                          <div class="menu mhee">
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
                          <div class="menu mhee" id="delete1" >
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
                                    <label class="col-sm-3 col-form-label "><?php echo $array['side'][$language]; ?></label>
                                      <select onchange="resetinputuser()"  class="form-control col-sm-7 checkblank" id="hptsel2" >
                                      </select>
                                      <label id="rem1" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['codecode'][$language]; ?></label>
                                      <input type="text" autocomplete="off" onkeyup="resetinput(2)"    class="form-control col-sm-7 checkblank" id="DepCode" placeholder="<?php echo $array['codecode'][$language]; ?>" >
                                      <input type="text" autocomplete="off" hidden  class="form-control col-sm-7 " id="DepCodeReal" placeholder="<?php echo $array['codecode'][$language]; ?>" readonly>
                                      <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['department'][$language]; ?></label>
                                      <input type="text"  autocomplete="off"  onkeyup="resetinput()" class="form-control col-sm-7 checkblank" id="DepName" placeholder="<?php echo $array['department'][$language]; ?>">
                                      <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['group'][$language]; ?></label>
                                      <select onchange="ShowItem(2)"  class="form-control col-sm-7 checkblank" id="group2" >
                                      </select>
                                      <label id="rem4" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                </div>


                                </div> 
   <!-- =================================================================== -->
                            <div class="row" <?php if($PmID != 1 && $PmID != 6) echo 'hidden'; ?>>
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['status'][$language]; ?></label>
                                      <select   class="form-control col-sm-7 " id="IsActive" >
                                      <option value="1">active</option>
                                      <option value="0">inactive</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['xcenter'][$language]; ?></label>
                                      <input type="checkbox"  id="xCenter" style="margin-top: 1.5%;">
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->     
   <div class="row" <?php if($PmID != 1 && $PmID != 6) echo 'hidden'; ?>>
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label ">SHIP_TO</label>
                                    <input type="text" autocomplete="off"  maxlength="11"  class="form-control col-sm-7 " id="shipto" placeholder="SHIP_TO" >
                                    </div>
                                  </div>
                                </div> 
   <!-- =================================================================== -->     

                        </div>
                    </div>
                </div> <!-- tag column 2 -->
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
            <script src="../select2/dist/js/select2.full.min.js" type="text/javascript"></script>

            <!-- Custom scripts for all pages-->
            <script src="../template/js/sb-admin.min.js"></script>

            <!-- Demo scripts for this page-->
            <script src="../template/js/demo/datatables-demo.js"></script>

</body>

</html> 