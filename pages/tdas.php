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

        // funtion --------------------------------------------
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
            for(var i = 0; i<=4; i++){
                Qty = 0;
                $(".qty"+i).each(function() {
                    Qty += Number($(this).val());
                });
                $('#totalQty'+i).val(Math.round(Qty));
            }
            Calculate();

        }
        function SaveQty(DepCode, Type, row){
            var Qty = 0;
            if(Type == 1){
               Qty = $('#QtyType1_'+row).val();
               $('#QtyType1_'+row).addClass('border border-dark');
            }else if(Type == 2){
                Qty = $('#QtyType2_'+row).val();
               $('#QtyType2_'+row).addClass('border border-dark');
            }else if(Type == 3){
                Qty = $('#QtyType3_'+row).val();
               $('#QtyType3_'+row).addClass('border border-dark');
            }else if(Type == 4){
                Qty = $('#QtyType4_'+row).val();
               $('#QtyType4_'+row).addClass('border border-dark');
            }

            var data = {
                'STATUS': 'SaveQty',
                'DepCode': DepCode,
                'Type': Type,
                'Qty': Qty
            };
            senddata(JSON.stringify(data));
        }
        function Calculate(){
            var DepCount =   $('#DepCount').val();
            var RowChg = $('#RowChg').val();
            var ItemCode = [];
            var chkItem = [];
            $(".ItemCode").each(function() {
                ItemCode.push($(this).data('itemcode'));
            });
            for (var i = 0; i < ItemCode.length; i++) {
                if ($('#chkItem_'+ItemCode[i]).is(':checked')){
                    chkItem.push(1);
                }else{
                    chkItem.push(0);
                }
            }
            for(var j = 0; j<ItemCode.length; j++){
                for(var i = 0; i<DepCount; i++){
                    Qty = 0;
                    chk = [];
                    var percent = Number($('#percent_'+i).val());
                    var change = Number($('#change_'+j).val());
                    var result = 0;
                    if(chkItem[j] == 0){
                        if(percent>100){
                            $('#percent_'+i).val(100);
                        }else if(percent<0){
                            $('#percent_'+i).val(0);
                        }else{
                            $(".col_"+i).each(function() {
                                var type = $(this).data('type');
                                if(type == 2){
                                    Qty += 0;
                                }else{
                                    Qty += Number($(this).val());
                                }
                            });
                        }
                    }else{
                        if(percent>100){
                            $('#percent_'+i).val(100);
                        }else if(percent<0){
                            $('#percent_'+i).val(0);
                        }else{
                            $(".col_"+i).each(function() {
                                Qty += Number($(this).val());
                            });
                        }
                    }
                    var percent = Number($('#percent_'+i).val())/100;
                    result = (Qty * percent * change) + Qty;
                    $('.result_'+j+i).val(Math.round(result));
                }
                ResultArray = 0;
                $(".SumRow_"+j).each(function() {
                    ResultArray += Number($(this).val());
                });
                $('#SumRow_'+j).val(Math.round(ResultArray));

                var total = ResultArray*$('#total_par2').val();
                $('#CalRow_'+j).val(total.toFixed(2));
            }
        }
        function SaveChange(ItemCode, row){
            var Qty = $('#change_'+row).val();
            var data = {
                'STATUS': 'SaveChange',
                'ItemCode': ItemCode,
                'Qty': Qty
            };
            senddata(JSON.stringify(data));
        }
        function SavePar(){
            var Qty = $('#total_par2').val();
            var data = {
                'STATUS': 'SavePar',
                'Qty': Qty
            };
            senddata(JSON.stringify(data));
        }
        function SavePercent(DepCode, row){
            var Percent = $('#percent_'+row).val();
            var data = {
                'STATUS': 'SavePercent',
                'DepCode': DepCode,
                'Percent': Percent
            };
            senddata(JSON.stringify(data));
        }
        function CreateDocument(){
          var QtyRow1 = [];
          var QtyRow2 = [];
          var QtyRow3 = [];
          var QtyRow4 = [];
          $(".qty1").each(function() {
            QtyRow1.push($(this).val());
          });
          $(".qty2").each(function() {
            QtyRow2.push($(this).val());
          });
          $(".qty3").each(function() {
            QtyRow3.push($(this).val());
          });
          $(".qty4").each(function() {
            QtyRow4.push($(this).val());
          });
          var QtyArray1 = QtyRow1.join(',');
          var QtyArray2 = QtyRow2.join(',');
          var QtyArray3 = QtyRow3.join(',');
          var QtyArray4 = QtyRow4.join(',');
          // ----------------------------------------------
          var ItemCode = [];
          var change = [];
          var chkItem = [];
          var TotalSum = [];
          var CalSum = [];
          $(".ItemCode").each(function() {
            ItemCode.push($(this).data('itemcode'));
          });
          $(".changeSend").each(function() {
            change.push($(this).val());
          });
          for(var i = 0; i<ItemCode.length; i++){
            if ($('#chkItem_'+ItemCode[i]).is(':checked')){
                chkItem.push(1);
            }else{
                chkItem.push(0);
            }
          }
          $(".TotalSum").each(function() {
            TotalSum.push($(this).val());
          });
          $(".CalSum").each(function() {
            CalSum.push($(this).val());
          });
          var ItemCodeArray = ItemCode.join(',');
          var changeArray = change.join(',');
          var AllSum = chkItem.join(',');
          var TotalArray = TotalSum.join(',');
          var CalArray = CalSum.join(',');
          // ----------------------------------------------
          var Percent = [];
          var Total_par2 = $('#total_par2').val();
          $(".percentSend").each(function() {
            Percent.push($(this).val());
          });
          var PercentArray = Percent.join(',');
          // ----------------------------------------------
          swal({
            title: "<?php echo $array['confirmdoc'][$language]; ?>",
            text: "",
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
                    'STATUS': 'CreateDocument',
                    'QtyArray1': QtyArray1,
                    'QtyArray2': QtyArray2,
                    'QtyArray3': QtyArray3,
                    'QtyArray4': QtyArray4,
                    'ItemCodeArray': ItemCodeArray,
                    'AllSum': AllSum,
                    'changeArray': changeArray,
                    'PercentArray': PercentArray,
                    'Total_par2': Total_par2,
                    'TotalArray': TotalArray,
                    'CalArray': CalArray,
                };
                senddata(JSON.stringify(data));
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                } 
            })
        }
        function CalRow(row){
            ResultArray = 0;
            $(".SumRow_"+row).each(function() {
                ResultArray += Number($(this).val());
            });
            $('#SumRow_'+row).val(Math.round(ResultArray));

            var total = ResultArray*$('#total_par2').val();
            // var CalSum = Total*total_par2;
            $('#CalRow_'+row).val(total.toFixed(2));
        }
        function updateStock(){
            var QtyRow1 = [];
            var QtyRow2 = [];
            var QtyRow3 = [];
            var QtyRow4 = [];
            $(".qty1").each(function() {
                QtyRow1.push($(this).val());
            });
            $(".qty2").each(function() {
                QtyRow2.push($(this).val());
            });
            $(".qty3").each(function() {
                QtyRow3.push($(this).val());
            });
            $(".qty4").each(function() {
                QtyRow4.push($(this).val());
            });
            var QtyArray1 = QtyRow1.join(',');
            var QtyArray2 = QtyRow2.join(',');
            var QtyArray3 = QtyRow3.join(',');
            var QtyArray4 = QtyRow4.join(',');
            // ----------------------------------------------
            var ItemCode = [];
            var change = [];
            var chkItem = [];
            var TotalSum = [];
            var CalSum = [];
            $(".ItemCode").each(function() {
                ItemCode.push($(this).data('itemcode'));
            });
            $(".changeSend").each(function() {
                change.push($(this).val());
            });
            for(var i = 0; i<ItemCode.length; i++){
                if ($('#chkItem_'+ItemCode[i]).is(':checked')){
                    chkItem.push(1);
                }else{
                    chkItem.push(0);
                }
            }
            $(".TotalSum").each(function() {
                TotalSum.push($(this).val());
            });
            $(".CalSum").each(function() {
                CalSum.push($(this).val());
            });
            var ItemCodeArray = ItemCode.join(',');
            var changeArray = change.join(',');
            var AllSum = chkItem.join(',');
            var TotalArray = TotalSum.join(',');
            var CalArray = CalSum.join(',');
            // ----------------------------------------------
            var Percent = [];
            var Total_par2 = $('#total_par2').val();
            $(".percentSend").each(function() {
                Percent.push($(this).val());
            });
            var PercentArray = Percent.join(',');
            // ----------------------------------------------
            swal({
                title: "<?php echo $array['updatestock'][$language]; ?>",
                text: "",
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
                    swal({
                    title: '',
                    text: '<?php echo $array['updatesuccess'][$language]; ?>',
                    type: 'success',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 1500,
                    });
                    setTimeout(() => {
                        var data = {
                            'STATUS': 'updateStock',
                            'QtyArray1': QtyArray1,
                            'QtyArray2': QtyArray2,
                            'QtyArray3': QtyArray3,
                            'QtyArray4': QtyArray4,
                            'ItemCodeArray': ItemCodeArray,
                            'AllSum': AllSum,
                            'changeArray': changeArray,
                            'PercentArray': PercentArray,
                            'Total_par2': Total_par2,
                            'TotalArray': TotalArray,
                            'CalArray': CalArray,
                        };
                        senddata(JSON.stringify(data));
                    }, 1500);
                    
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                } 
            })
        }
        function CreateExcel(){
            console.log('Excel');
            var QtyRow1 = [];
            var QtyRow2 = [];
            var QtyRow3 = [];
            var QtyRow4 = [];
            $(".qty1").each(function() {
                QtyRow1.push($(this).val());
            });
            $(".qty2").each(function() {
                QtyRow2.push($(this).val());
            });
            $(".qty3").each(function() {
                QtyRow3.push($(this).val());
            });
            $(".qty4").each(function() {
                QtyRow4.push($(this).val());
            });
            var QtyArray1 = QtyRow1.join(',');
            var QtyArray2 = QtyRow2.join(',');
            var QtyArray3 = QtyRow3.join(',');
            var QtyArray4 = QtyRow4.join(',');
            // ----------------------------------------------
            var ItemCode = [];
            var change = [];
            var chkItem = [];
            var TotalSum = [];
            var CalSum = [];
            $(".ItemCode").each(function() {
                ItemCode.push($(this).data('itemcode'));
            });
            $(".changeSend").each(function() {
                change.push($(this).val());
            });
            for(var i = 0; i<ItemCode.length; i++){
                if ($('#chkItem_'+ItemCode[i]).is(':checked')){
                    chkItem.push(1);
                }else{
                    chkItem.push(0);
                }
            }
            $(".TotalSum").each(function() {
                TotalSum.push($(this).val());
            });
            $(".CalSum").each(function() {
                CalSum.push($(this).val());
            });
            var ItemCodeArray = ItemCode.join(',');
            var changeArray = change.join(',');
            var AllSum = chkItem.join(',');
            var TotalArray = TotalSum.join(',');
            var CalArray = CalSum.join(',');
            // ----------------------------------------------
            var Percent = [];
            var Total_par2 = $('#total_par2').val();
            $(".percentSend").each(function() {
                Percent.push($(this).val());
            });
            var PercentArray = Percent.join(',');
            // ----------------------------------------------
            swal({
                title: "<?php echo $array['excel'][$language]; ?>",
                text: "",
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
                        'STATUS': 'CreateExcel',
                        'QtyArray1': QtyArray1,
                        'QtyArray2': QtyArray2,
                        'QtyArray3': QtyArray3,
                        'QtyArray4': QtyArray4,
                        'ItemCodeArray': ItemCodeArray,
                        'AllSum': AllSum,
                        'changeArray': changeArray,
                        'PercentArray': PercentArray,
                        'Total_par2': Total_par2,
                        'TotalArray': TotalArray,
                        'CalArray': CalArray,
                    };
                    exportExcel(JSON.stringify(data));
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                } 
            })
        }
        // End Function ----------------------------------------
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
                        if ((temp["form"] == 'getSection')) {
                            var HeadTB = "<tr style='height:50px;' >" + 
                                            "<th style='width :5%;'  class='text-left'  rowspan='3'></th>"+
                                            "<th style='width :5%;'  class='text-left'  rowspan='3'></th>"+
                                            "<th style='width :5%;'  class='text-left'  rowspan='3'></th>"+
                                            "<th style='width :15%;'  class='text-left'>Department</th>"+
                                            "<th style='width :13%;' class='text-center'>Change <br>(ความถึ่ในการเปลี่ยน)</th>";
                            for (var i = 0; i < temp['CountRow']; i++) {
                                HeadTB += "<th  class='text-center' style='width :12%;'>" + temp[i]['DepName'] + "</th>" ;
                            }
                            var DepCount = i;
                            var total_par1 = temp['total_par1']==undefined?"placeholder='0'":"value='"+temp['total_par1']+"'";
                            var total_par2 = temp['total_par2']==undefined?"placeholder='0'":"value='"+temp['total_par2']+"'";
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
                                "<th  nowrap  class='text-center'><input type='text' class='form-control text-center' "+total_par1+" id='total_par1'></th>"+
                                "<th  nowrap  class='text-center'><input type='text' class='form-control text-center' "+total_par2+" id='total_par2' onkeyup='if(event.keyCode==13){SavePar()}else{Calculate()}'></th>"+
                            "</tr>";
                            $('#theadsum').html(HeadTB);

                            StrTRx = "<tr style='height:50px;'>"+
                                "<td style='width :5%;'  class='text-left'  rowspan='4'></td>"+
                                "<td style='width :5%;'  class='text-left'  rowspan='4'></td>"+
                                "<td style='width:5%;' class='text-center'>1</td>"+
                                "<td style='width:5%;' class='text-left'>จำนวนเตียงรวม <br>(Total Patient Room)</td>"+
                                "<td></td>";
                                for (var i = 0; i < temp['CountRow']; i++) {
                                    Qty1 = temp[i]['Qty1']==null?"placeholder='0'":"value='"+temp[i]['Qty1']+"'";
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control width_custom text-center qty1 numonly_dot col_"+i+"' id='QtyType1_"+i+"' "+Qty1+" onkeyup='if(event.keyCode==13){SaveQty(\""+temp[i]['DepCode']+"\",\""+1+"\",\""+i+"\")}else{TotalQty()}'></td>" ;
                                }
                                StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center width_custom' id='totalQty1' value='0' disabled></td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                            "</tr>";
                            StrTRx += "<tr style='height:50px;'>"+
                                "<td style='width:5%;'  class='text-center'>2</td>"+
                                "<td style='width:5%;'  class='text-left'>ห้องพักญาติ <br>(Relative Room In VIP)</td>"+
                                "<td></td>";
                                for (var i = 0; i < temp['CountRow']; i++) {
                                    Qty2 = temp[i]['Qty2']==null?"placeholder='0'":"value='"+temp[i]['Qty2']+"'";
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty2 numonly_dot col_"+i+" type2' data-type='2' id='QtyType2_"+i+"'  "+Qty2+" onkeyup='if(event.keyCode==13){SaveQty(\""+temp[i]['DepCode']+"\",\""+2+"\",\""+i+"\")}else{TotalQty()}'></td>" ;
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
                                    Qty3 = temp[i]['Qty3']==null?"placeholder='0'":"value='"+temp[i]['Qty3']+"'";
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty3 numonly_dot col_"+i+"' id='QtyType3_"+i+"' "+Qty3+" onkeyup='if(event.keyCode==13){SaveQty(\""+temp[i]['DepCode']+"\",\""+3+"\",\""+i+"\")}else{Calculate()}'></td>" ;
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
                                    Qty4 = temp[i]['Qty4']==null?"placeholder='0'":"value='"+temp[i]['Qty4']+"'";
                                    StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center qty4 numonly_dot col_"+i+"' id='QtyType4_"+i+"' "+Qty4+" onkeyup='if(event.keyCode==13){SaveQty(\""+temp[i]['DepCode']+"\",\""+4+"\",\""+i+"\")}else{TotalQty()}'></td>" ;
                                }
                                StrTRx += "<td  nowrap  class='text-center'><input type='text' class='form-control text-center' id='totalQty4' value='0' disabled></td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                                "<td  nowrap  class='text-center'> </td>"+
                            "</tr>";
                            StrTRx += "<tr style='height:50px;'>"+
                                "<td style='width:5%;' nowrap  class='text-center'></td>"+
                                "<td nowrap  class='text-center'  style='width:15%;' colspan='2'>"+
                                    "<select name='type_"+i+"' id='type_"+i+"' class='form-control width_custom'>"+
                                        "<option>เลือกทั้งหมด</option>"+
                                        "<option>PPU</option>"+
                                        "<option>NONPPU</option>"+
                                    "</select>"+
                                "</td>"+
                                // "<td style='width:5%;'></td>"+
                                "<td  nowrap  class='text-center'>"+
                                    "<select name='safty_"+i+"' id='safty_"+i+"' class='form-control' style='width: 260px;'>"+
                                        "<option>Total Safty stock</option>"+
                                        "<option>เลือกทั้งหมด</option>"+
                                    "</select>"+
                                "</td>"+
                                "<td></td>";
                                for (var i = 0; i < (temp['CountRow']); i++) {
                                    Hptpercent = temp[i]['Hptpercent']==null?"placeholder='0'":"value='"+temp[i]['Hptpercent']+"'";
                                    StrTRx += "<td  nowrap  class='text-center'>"+
                                        "<div class='input-group'>"+
                                            "<input  "+Hptpercent+" onkeyup='if(event.keyCode==13){SavePercent(\""+temp[i]['DepCode']+"\",\""+i+"\")}else{Calculate()}' class='form-control numonly percentSend text-center' id='percent_"+i+"'>"+
                                            "<div class='labelPrecent'><label for='percent_"+i+"'>%</label></div>"+
                                        "</div>"
                                    "</td>";
                                }
                                StrTRx +=   "<td  nowrap  class='text-center'> </td>"+
                                    "<td  nowrap  class='text-center'> </td>"+
                                    "<td  nowrap  class='text-center'> </td>"+
                                    "</tr>";
                            if(temp['RowCount']>0){
                                for (var j = 0; j < (temp['RowCount']); j++) {
                                    var chkItem = "<input type='checkbox' class='chkItem' name='checkitem' checked  id='chkItem_"+temp[j]['ItemCode']+"'  value='1' onclick='Calculate();'>";
                                    change_value = temp[j]['change_value']==null?"placeholder='0'":"value='"+temp[j]['change_value']+"'";
                                    StrTRx += "<tr style='height:50px;'>"+
                                        "<td style='width :5%;' class='text-center'>"+(j+1)+"</td>"+
                                        "<td  nowrap  class='text-left'>"+temp[j]['mainType']+"</td>"+
                                        "<td style='width :5%;' class='text-center'>"+chkItem+"</td>"+
                                        "<td  nowrap  class='text-left ItemCode' data-itemcode='"+temp[j]['ItemCode']+"'>"+temp[j]['ItemName']+"</td>"+
                                        "<td  nowrap  class='text-left'><input type='text'  "+change_value+" id='change_"+j+"' class='form-control text-center changeSend width_custom change_"+j+"' onkeyup='if(event.keyCode==13){SaveChange(\""+temp[j]['ItemCode']+"\",\""+j+"\")}else{TotalQty()}'></td>";
                                        for (var i = 0; i < temp['CountRow']; i++) {
                                            StrTRx += "<td  class='text-center'><input type='text' class='form-control text-center result_"+j+i+" SumRow_"+j+"' onkeyup='CalRow("+j+")'></td>" ;
                                        }
                                    StrTRx += "<td  class='text-center'></td>"+
                                        "<td  class='text-center'>"+"<input type='text' class='form-control text-center TotalSum TotalSum_"+j+"' style='width:140px;' disabled id='SumRow_"+j+"'></td>"+
                                        "<td  class='text-center'>"+"<input type='text' class='form-control text-center CalSum CalRow_"+j+"' style='width:140px;' disabled id='CalRow_"+j+"'></td>"+
                                    "</tr>";
                                }
                                var RowChg = j;
                            }
                            $('#body_table').html(StrTRx);
                            $('.numonly_dot').on('input', function() {
                              this.value = this.value.replace(/[^0-9]/g, '');
                            });
                            $('.numonly').on('input', function() {
                              this.value = this.value.replace(/[^0-9.]/g, '');
                            });
                            $('#DepCount').val(DepCount);
                            $('#RowChg').val(RowChg);
                            TotalQty();
                            Calculate();
                        }else if(temp['form'] == 'CreateDocument'){
                            swal({
                                title: '',
                                text: '<?php echo $array['savesuccess'][$language]; ?>',
                                type: 'success',
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                            });
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
        function exportExcel(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = 'exportExcel.php';
            $.ajax({
                url: URL,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(result) {
                    window.open('Excel.php', '_blank')
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
        .width_custom{
            width: 140px;
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
        .labelPrecent{
            position: absolute;
            right: 40px;
            top: 5px;
        }
    </style>
</head>

<body id="page-top">
        <input type="hidden" id="DepCount">
        <input type="hidden" id="RowChg">
        <div class="row m-1 my-4 mt-2 d-flex justify-content-start" >
            <div class="menu2">
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
            <div class="menu2">
                <div class="d-flex justify-content-center">
                    <div class="circle4 d-flex justify-content-center">
                        <button class="btn"  id="exportExcel" onclick="CreateExcel();">
                            <i class="fas fa-file-excel"></i>
                            <div>
                                <?php echo $array['excel'][$language]; ?>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="menu2">
                <div class="d-flex justify-content-center">
                    <div class="circle2 d-flex justify-content-center">
                        <button class="btn"  id="bImport" onclick="updateStock();">
                            <i class="fas fa-file-import"></i>
                            <div>
                                <?php echo $array['updatestock'][$language]; ?>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <table style="margin-top:10px;" class="table mt-2 " id="TableItem" cellspacing="0" role="grid" >
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