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
                        if ((temp["form"] == 'ShowItem')) {
                            $("#TableItem tbody").empty();
                            console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var DefaultName = temp[i]['DefaultName'] == 1 ?'<i class="fas fa-check fa-sm"></i>':'';
                                var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem' id='checkitem_"+i+"' value='" + temp[i]['DepCode'] + "' onclick='getdetail(\"" + temp[i]["DepCode"] + "\", \""+i+"\")'><span class='checkmark'></span></label>";
                                // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                StrTR = "<tr id='tr" + temp[i]['DepCode'] + "'>" +
                                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                    "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                                    "<td style='width: 19.5%;'>" + temp[i]['DepName'] + "</td>" +
									"<td style='width: 65%;'>" +  DefaultName  + "</td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                        }else if ((temp["form"] == 'getSection')) {
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTRx = "<th style='width :12%;'>" + temp[i]['DepName'] + "</th>" +
                                $('#TableItem > thead tr').append(StrTRx);
                            }
                            $('#TableItem > thead tr').append('<th style="width:6%;"> TOTAL</th>');
                            $('#TableItem > thead tr').append('<th style="width:6%;"> TOTAL</th>');
                            $('#TableItem > thead tr').append('<th style="width:6%;"> TOTAL</th>');

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

    <div id="wrapper">
        <!-- content-wrapper -->
        <div id="content-wrapper">

          
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                                <th style="width:10%;">DEPARTMENT</th>
                                                <!-- <th style="width:10%;">TOTAL</th>
                                                <th style="width:10%;">TOTAL</th>
                                                <th style="width:10%;">TOTAL</th> -->
                                    </tr>
                                </thead>
                                <!-- <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                                </tbody> -->
                            </table>

                        </div>
                    </div>
                </div> <!-- tag column 1 -->
            </div>


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