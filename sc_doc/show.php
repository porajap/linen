<?php
    date_default_timezone_set("Asia/Bangkok");

    header ('Content-type: text/html; charset=utf-8');
    $xml = simplexml_load_file('xml/general_lang.xml');
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet">
    <title>Shelfcount Document</title>
    <style>
              @font-face {
        font-family: myFirstFont;
        src: url("../fonts/DB Helvethaica X.ttf");
      }
        body,html{
            font-family: myFirstFont;
            font-size: 25px;
            /* overflow: scroll;
            overflow-x: hidden;         */
        }
        .BG{
            background-color : #1659a2;
        }
        .font-color{
            color:#fff;
        }
    </style>
</head>

<body class="fix-header card-no-border fix-sidebar">
    <div id="fullscreen" style="min-height:100%">
        <table class="table table-striped">
            <thead class="BG">
                <tr class="font-color">
                    <th scope="col" class="text-center">No</th>
                    <th scope="col" class="text-left">DocNo</th>
                    <th scope="col" class="text-left">DepName</th>
                    <th colspan="2" scope="col" class="text-left" style="padding-left: 4%;">ShelfCount</th>
                    <th colspan="2" scope="col" class="text-left" style="padding-left: 5%;">Packing</th>
                    <th colspan="2" scope="col" class="text-left" style="padding-left: 5%;">Delivery</th>
                    <th scope="col" class="text-left" style="width:3%">
                        <a href="#" onclick="openFullscreen();" id="FullScreen" title="Open Fullscreen"><i class="far fa-window-maximize text-white"></i></a>
                        <a href="#" onclick="closeFullscreen();" id="ExitFull" title="Exit Fullscreen" hidden><i class="fas fa-window-restore text-white"></i></a>
                    </th>
                </tr>
                <tr class="font-color">
                    <th scope="col" class="text-center"></th>
                    <th scope="col" class="text-left"></th>
                    <th scope="col" class="text-left"></th>
                    <th scope="col" class="text-left">START</th>
                    <th scope="col" class="text-left">END</th>
                    <th scope="col" class="text-left">START</th>
                    <th scope="col" class="text-left">END</th>
                    <th scope="col" class="text-left">START</th>
                    <th scope="col" class="text-left">END</th>
                    <th scope="col" class="text-left" style="width:3%"></th>
                </tr>
            </thead>
            <tbody id="TableData" class="table-striped ">

            </tbody>
        </table>
    </div>




    <script src="assets/js/jquery-3.4.1.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/fontawesome/js/all.min.js"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            getDataFromDb();
        });
        // <!-- ================================================ --!>
        setInterval(getDataFromDb, 1000);   // 1000 = 1 second
        function getDataFromDb()
        {
            $.ajax({ 
                url: "process/getData.php" ,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                success:function(result) { 
                    try {
                        var temp = $.parseJSON(result);
                    } catch (e) {
                        console.log('Error#542-decode error');
                    }
                    var row = "";
                    if(temp['count'] > 0)   
                    {
                        $.each(temp['Sc'], function(key, val) {
                            var Style  = "";
                            var EngNamePkStart      = '';
                            var EngPerfixPkStart    = '';
                            var EngNamePkEnd        = '';
                            var EngPerfixPkEnd      = '';

                            if(val.PkStartTime !='')
                            {
                                var EngNamePkStart      = val.EngName;
                                var EngPerfixPkStart    = val.EngPerfix;
                            }

                            if(val.PkEndTime !='')
                            {
                                var EngNamePkEnd        = val.EngName;
                                var EngPerfixPkEnd      = val.EngPerfix;
                            }

                            var EngNameDvStart      = '';
                            var EngPerfixDvStart    = '';
                            var EngNameDvEnd        = '';
                            var EngPerfixDvEnd      = '';

                            if(val.DvStartTime !='')
                            {
                                var EngNameDvStart      = val.EngName2;
                                var EngPerfixDvStart    = val.EngPerfix2;
                            }

                            if(val.DvEndTime !='')
                            {
                                var EngNameDvEnd        = val.EngName3;
                                var EngPerfixDvEnd      = val.EngPerfix3;
                            }

                            if(val.revealName != '')
                            {
                              Style  = "style='color: #F19D19;' ";
                            }


                            if( val.IsStatus ==4)
                            {
                              Style  = "style='color: #009900;' ";
                            }
                            
                            row += '<tr '+Style+'>'+
                                    '<td class="text-center">'+(key+1)+'        </td>'+
                                    '<td class="text-left">'+val.DocNo+'       </td>'+
                                    '<td class="text-left">'+val.DepName+'      </td>'+
                                    '<td class="text-left">'+val.ScStartTime+'<br>'+val.EngPerfix+val.EngName+'   </td>'+
                                    '<td class="text-left">'+val.ScEndTime+'<br>'+val.EngPerfix+val.EngName+'     </td>'+
                                    '<td class="text-left">'+val.PkStartTime+'<br>'+EngPerfixPkStart+EngNamePkStart+'   </td>'+
                                    '<td class="text-left">'+val.PkEndTime+'<br>'+EngPerfixPkEnd+EngNamePkEnd+'     </td>'+
                                    '<td class="text-left">'+val.DvStartTime+'<br>'+val.EngPerfix2+val.EngName2+'   </td>'+
                                    '<td class="text-left">'+val.DvEndTime+'<br>'+val.EngPerfix3+val.EngName3+'      </td>'+
                                    // '<td class="text-left">'+val.ScStartTime+'|'+val.ScEndTime+'</td>'+
                                    // '<td class="text-left">'+val.PkStartTime+'|'+val.PkEndTime+'</td>'+
                                    // '<td class="text-left">'+val.DvStartTime+'|'+val.DvEndTime+'</td>'+
                                    '<td></td>'+
                                '</tr>';
                                // row += '<tr '+Style+'>'+
                                //     '<td colspan="2" class="text-center">'+val.EngPerfix+val.EngName+' '+val.EngLName+'</td>'+
                                //     '<td></td>'+
                                // '</tr>';
                                
                        });
                    }else {
                        row += '<tr><td class="text-center" colspan="12" style="font-size:24px;">Document is Empty</td></tr>';
                    }
                    $('#TableData').html(row);
                }
            });

        }
        <!-- ================================================ --!>
        var elem = document.documentElement;
        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
                showFull();
            } else if (elem.mozRequestFullScreen) { /* Firefox */
                elem.mozRequestFullScreen();
                showFull();
            } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                elem.webkitRequestFullscreen();
                showFull();
            } else if (elem.msRequestFullscreen) { /* IE/Edge */
                elem.msRequestFullscreen();
                showFull();
            }
        }

        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                showExit()
            } else if (document.mozCancelFullScreen) { /* Firefox */
                document.mozCancelFullScreen();
                showExit();
            } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
                document.webkitExitFullscreen();
                showExit();
            } else if (document.msExitFullscreen) { /* IE/Edge */
                document.msExitFullscreen();
                showExit();
            }
        }
        function showFull(){
            $('#FullScreen').attr('hidden', true);
            $('#ExitFull').attr('hidden', false);
        }
        function showExit(){
            $('#FullScreen').attr('hidden', false);
            $('#ExitFull').attr('hidden', true);
        }
        <!-- ================================================ --!>
    </script>
</body>
</html>