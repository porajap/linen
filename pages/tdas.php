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
    <script type="text/javascript">
        var summary = [];

        $(document).ready(function(e) {

            var HptCode = $('#hptsel').val();

            var data = {
                'STATUS': 'getSection',
                'HptCode': HptCode
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));

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

        var isChecked1 = false;
        var isChecked2 = false;

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


        function getdetail(DepCode, row) {
                var number = parseInt(row)+1;
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
                        'DepCode': DepCode ,
                        'number' : number 
                    };

                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
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

        function TotalQty1(){
            Qty = 0;
            $(".qty1").each(function() {
                Qty += Number($(this).val());
            });
            $('#totalQty1').val(Qty);
        }
        function TotalQty(){
            Qty1 = 0;
            $(".qty1").each(function() {
                Qty1 += Number($(this).val());
            });
            $('#totalQty1').val(Qty1);

            Qty2 = 0;
            $(".qty2").each(function() {
                Qty2 += Number($(this).val());
            });
            $('#totalQty2').val(Qty2);

            Qty3 = 0;
            $(".qty3").each(function() {
                Qty3 += Number($(this).val());
            });
            $('#totalQty3').val(Qty3);

            Qty4 = 0;
            $(".qty4").each(function() {
                Qty4 += Number($(this).val());
            });
            $('#totalQty4').val(Qty4);
        }
        function SaveQty(){
            alert('Test');
        }
        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/tdas.php';
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
                        if ((temp["form"] == 'getSection')) {
                            var HeadTB = "<tr style='height:50px;'>" + 
                                            "<th style='width :5%;'  class='text-left'  rowspan='3'></th>"+
                                            "<th style='width :5%;'  class='text-left'  rowspan='3'></th>"+
                                            "<th style='width :15%;'  class='text-left'>Department</th>"+
                                            "<th style='width :13%;' class='text-center'>Change <br>(ความถึ่ในการเปลี่ยน)</th>";
                            for (var i = 0; i < temp['CountRow']; i++) {
                                HeadTB += "<th  class='text-center' style='width :12%;'>" + temp[i]['DepName'] + "</th>" ;
                            }
                            HeadTB += "<th class='text-center'nowrap style=width:12%;'>TOTAL</th>"+
                            "<th class='text-center' ' nowrap style='width:12%;'>TOTAL</th>"+
                            "<th class='text-center'nowrap style='width:12%;'>TOTAL</th>"+
                            "</tr>";
                            HeadTB += "<tr style='height:50px;'>"+
                                        "<th style='width:12%;' nowrap  class='text-left'>COST CENTER</th>"+
                                        "<th colspan='"+(i+1)+"'></th>"+
                                        "<th  nowrap  class='text-center' style='padding-left: 20px;padding-right: 20px;'>Ex.STOCK</th>"+
                                        "<th  nowrap  class='text-center' style='padding-left: 20px;padding-right: 20px;'>PAR</th>"+
                                        "<th  nowrap  class='text-center' style='padding-left: 20px;padding-right: 20px;'>PAR</th>"+
                                    "</tr>";
                                HeadTB += "<tr style='height:50px;'>"+
                                "<th style='width:12%;' nowrap  class='text-left'>Name</th>"+
                                "<th colspan='"+(i+2)+"'></th>"+
                                "<th  nowrap  class='text-center'><input type='text' class='form-control text-center' value='0'></th>"+
                                "<th  nowrap  class='text-center'><input type='text' class='form-control text-center' value='0'></th>"+
                            "</tr>";
                            $('#theadsum').html(HeadTB);

                            StrTRx = "<tr style='height:50px;'>"+
                                "<td style='width :5%;'  class='text-left'  rowspan='4'></td>"+
                                "<td style='width:5%;' class='text-center'>1</td>"+
                                "<td style='width:5%;' class='text-left'>จำนวนเตียงรวม <br>(Total Patient Room)</td>"+
                                "<td></td>";
                                for (var i = 0; i < temp['CountRow']; i++) {
                                    Qty1 = temp[i]['Qty1']==null?0:temp[i]['Qty1'];
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty1 numonly_dot' value='"+Qty1+"' onkeyup='if(event.keyCode==13){SaveQty()}else{TotalQty()}'></td>" ;
                                }
                                StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center' id='totalQty1' value='0' disabled></td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                            "</tr>";
                            StrTRx += "<tr style='height:50px;'>"+
                                "<td style='width:5%;'  class='text-center'>2</td>"+
                                "<td style='width:5%;'  class='text-left'>ห้องพักญาติ <br>(Relative Room In VIP)</td>"+
                                "<td></td>";
                                for (var i = 0; i < temp['CountRow']; i++) {
                                    Qty2 = temp[i]['Qty2']==null?0:temp[i]['Qty2'];
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty2 numonly_dot' value='"+Qty2+"' onkeyup='if(event.keyCode==13){SaveQty()}else{TotalQty()}'></td>" ;
                                }
                                StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center' id='totalQty2' value='0' disabled></td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                            "</tr>";
                            StrTRx += "<tr style='height:50px;'>"+
                                "<td style='width:5%;'  class='text-center'>3</td>"+
                                "<td style='width:5%;'  class='text-left'>จำนวนผู้ป่วย <br>(AVG Patient Census)</td>"+
                                "<td></td>";
                                for (var i = 0; i < temp['CountRow']; i++) {
                                    Qty3 = temp[i]['Qty3']==null?0:temp[i]['Qty3'];
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty3 numonly_dot' value='"+Qty3+"' onkeyup='if(event.keyCode==13){SaveQty()}else{TotalQty()}'></td>" ;
                                }
                                StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center totalQty3' id='totalQty3' value='0' disabled></td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                            "</tr>";
                            StrTRx += "<tr style='height:50px;'>"+
                                "<td style='width:5%;'  class='text-center'>4</td>"+
                                "<td style='width:5%;'  class='text-left'>จำนวนผู้ป่วยกลับบ้าน <br>(Dis charge plan)</td>"+
                                "<td></td>";
                                for (var i = 0; i < temp['CountRow']; i++) {
                                    Qty4 = temp[i]['Qty4']==null?0:temp[i]['Qty4'];
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty4 numonly_dot' value='"+Qty4+"' onkeyup='if(event.keyCode==13){SaveQty()}else{TotalQty()}'></td>" ;
                                }
                                StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center' id='totalQty4' value='0' disabled></td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                            "</tr>";
                            StrTRx += "<tr style='height:50px;'>"+
                                "<td style='width:5%;' nowrap  class='text-center'></td>"+
                                "<td nowrap  class='text-center'  style='width:15%;'>"+
                                    "<select name='type_"+i+"' id='type_"+i+"' class='form-control'>"+
                                        "<option>เลือกทั้งหมด</option>"+
                                        "<option>PPU</option>"+
                                        "<option>NONPPU</option>"+
                                    "</select>"+
                                "</td>"+
                                "<td  nowrap  class='text-center'>"+
                                    "<select name='safty_"+i+"' id='safty_"+i+"' class='form-control'>"+
                                        "<option>Total Safty stock</option>"+
                                        "<option>เลือกทั้งหมด</option>"+
                                    "</select>"+
                                "</td>"+
                                "<td></td>";
                                for (var i = 0; i < (temp['CountRow']); i++) {
                                    StrTRx += "<td  nowrap  class='text-center'>"+
                                        "<select name='percent_"+i+"' id='percent_"+i+"' class='form-control'>";
                                        for (var j = 0; j < (temp['CountPercent']); j++) {
                                            if(temp[j]['percent_value']==temp[i]['Hptpercent']){
                                                StrTRx += "<option value='"+temp[j]['percent_value']+"' selected>"+temp[j]['percent_value']+'%'+"</option>";
                                            }else{
                                                StrTRx += "<option value='"+temp[j]['percent_value']+"'>"+temp[j]['percent_value']+'%'+"</option>";
                                            }
                                        }
                                        StrTRx += "</select>"+
                                    "</td>";
                                }
                                StrTRx +=   "<td  nowrap  class='text-center'> </td>"+
                                    "<td  nowrap  class='text-center'> </td>"+
                                    "<td  nowrap  class='text-center'> </td>"+
                                    "</tr>";
                            if(temp['RowCount']>0){
                                for (var j = 0; j < (temp['RowCount']); j++) {
                                change_value = temp[j]['change_value']==null?0:temp[j]['change_value'];
                                StrTRx += "<tr style='height:50px;'>"+
                                    "<td style='width :5%;' class='text-center'>"+(j+1)+"</td>"+
                                    "<td  nowrap  class='text-left'>"+temp[j]['mainType']+"</td>"+
                                    "<td  nowrap  class='text-left'>"+temp[j]['ItemName']+"</td>"+
                                    "<td  nowrap  class='text-left'><input type='text' value='"+change_value+"' class='form-control text-center'></td>";
                                    for (var i = 0; i < temp['CountRow']; i++) {
                                        StrTRx += "<td  class='text-center'><input type='text' class='form-control text-center' disabled></td>" ;
                                    }
                                StrTRx += "<td  class='text-center'>"+"<input type='text' class='form-control text-center' disabled></td>"+
                                    "<td  class='text-center'>"+"<input type='text' class='form-control text-center' disabled></td>"+
                                    "<td  class='text-center'>"+"<input type='text' class='form-control text-center' disabled></td>"+
                                "</tr>";
                            }
                            }
                            $('#body_table').html(StrTRx);
                            $('.numonly_dot').on('input', function() {
                                this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
                            });
                            TotalQty();
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
        input,select{
            font-size:24px!important;
        }
        th,td{
            font-size:22px!important;
        }
        .table > thead > tr >th {
            background-color: #1659a2;
        }
        .table > thead > tr >td {
            background-color: #fff;
            color: #000;
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

        select {
            text-align: center;
            text-align-last: center;
            /* webkit*/
        }

    </style>
</head>

<body id="page-top">

        <!-- content-wrapper -->
                <table style="margin-top:10px;" class="table" id="TableItem" cellspacing="0" role="grid" >
                    <thead id="theadsum" style="font-size:11px;">
                    </thead>
                    <tbody id="body_table">
                    </tbody>
                </table>

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