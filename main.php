<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
$Userid = $_SESSION['Userid'];
$PmID= $_SESSION['PmID'];
$TimeOut = $_SESSION['TimeOut'];
$last_move = $_GET["last_move"];
$logoff = $_SESSION['chk_logoff'];
$Username = $_SESSION['Username'];
if($Userid==""){
   header("location:index.html");
}

$language = $_SESSION['lang'];

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$xml2 = simplexml_load_file('xml/general_lang.xml');
$json2 = json_encode($xml2);
$array2 = json_decode($json2,TRUE);

switch ($PmID) {
    case "1":
        //genneral
        $gen_head=1;
        $gen_s1=0;
        $gen_s2=0;
        $gen_s3=0;
        $gen_s4=0;
        $gen_s5=0;
        $gen_s6=1;
        $gen_s7=0;
        $gen_s8=0;
        $gen_s9=0;
        $gen_s10=0;
        //account
        $ac_head=1;
        $ac_s1=0;
        $ac_s2=0;
        $ac_s3=1;
        $ac_s4=0;
        $ac_s5=1;
        //factory
        $fac_head=1;
        $fac_s1=0;
        $fac_s2=0;
        $fac_s3=1;
        //report
        $re_head=0;
        $re_s1=0;
        //system
        $sys_head=1;
        $sys_s1=1;
        $sys_s2=1;
        $sys_s3=1;
        $sys_s4=1;
        $sys_s5=1;
        $sys_s6=1;
        $sys_s7=1;
        $sys_s8=1;
        $sys_s9=0;
        $sys_s10=1;
        $sys_s11=1;
        $sys_s12=1;
        $sys_s13=0;
        $sys_s14=1;
        break;
    case "2":
        //genneral
        $gen_head=1;
        $gen_s1=0;
        $gen_s2=0;
        $gen_s3=0;
        $gen_s4=0;
        $gen_s5=0;
        $gen_s6=1;
        $gen_s7=0;
        $gen_s8=0;
        $gen_s9=0;
        $gen_s10=0;
        //account
        $ac_head=1;
        $ac_s1=0;
        $ac_s2=0;
        $ac_s3=1;
        $ac_s4=0;
        $ac_s5=1;
        //factory
        $fac_head=0;
        $fac_s1=0;
        $fac_s2=0;
        $fac_s3=0;
        //report
        $re_s1=0;
        $re_head=0;
        //system
        $sys_head=1;
        $sys_s1=0;
        $sys_s2=0;
        $sys_s3=0;
        $sys_s4=0;
        $sys_s5=0;
        $sys_s6=0;
        $sys_s7=0;
        $sys_s8=0;
        $sys_s9=0;
        $sys_s10=0;
        $sys_s11=0;
        $sys_s12=0;
        $sys_s13=0;
        $sys_s14=1;
        break;
    case "3":
        //genneral
        $gen_head=1;
        $gen_s1=0;
        $gen_s2=0;
        $gen_s3=0;
        $gen_s4=0;
        $gen_s5=0;
        $gen_s6=1;
        $gen_s7=0;
        $gen_s8=0;
        $gen_s9=0;
        $gen_s10=0;
        //account
        $ac_head=1;
        $ac_s1=0;
        $ac_s2=0;
        $ac_s3=1;
        $ac_s4=0;
        $ac_s5=1;
        //factory
        $fac_head=0;
        $fac_s1=0;
        $fac_s2=0;
        $fac_s3=0;
        //report
        $re_head=0;
        $re_s1=0;
        //system
        $sys_head=1;
        $sys_s1=1;
        $sys_s2=1;
        $sys_s3=1;
        $sys_s4=0;
        $sys_s5=1;
        $sys_s6=1;
        $sys_s7=1;
        $sys_s8=0;
        $sys_s9=0;
        $sys_s10=0;
        $sys_s11=0;
        $sys_s12=1;
        $sys_s13=0;
        $sys_s14=1;
        break;
    case "4":
        //genneral
        $gen_head=0;
        $gen_s1=0;
        $gen_s2=0;
        $gen_s3=0;
        $gen_s4=0;
        $gen_s5=0;
        $gen_s6=0;
        $gen_s7=0;
        $gen_s8=0;
        $gen_s9=0;
        $gen_s10=0;
        //account
        $ac_head=1;
        $ac_s1=0;
        $ac_s2=0;
        $ac_s3=0;
        $ac_s4=1;
        $ac_s5=0;
        //factory
        $fac_head=0;
        $fac_s1=0;
        $fac_s2=0;
        $fac_s3=0;
        //report
        $re_head=0;
        $re_s1=0;
        //system
        $sys_head=1;
        $sys_s1=0;
        $sys_s2=0;
        $sys_s3=0;
        $sys_s4=0;
        $sys_s5=0;
        $sys_s6=0;
        $sys_s7=0;
        $sys_s8=0;
        $sys_s9=0;
        $sys_s10=0;
        $sys_s11=0;
        $sys_s12=0;
        $sys_s13=0;
        $sys_s14=1;
        break;
    case "5":
        //genneral
        $gen_head=0;
        $gen_s1=0;
        $gen_s2=0;
        $gen_s3=0;
        $gen_s4=0;
        $gen_s5=0;
        $gen_s6=0;
        $gen_s7=0;
        $gen_s8=0;
        $gen_s9=0;
        $gen_s10=0;
        //account
        $ac_head=1;
        $ac_s1=0;
        $ac_s2=0;
        $ac_s3=0;
        $ac_s4=0;
        $ac_s5=1;
        //factory
        $fac_head=0;
        $fac_s1=0;
        $fac_s2=0;
        $fac_s3=0;
        //report
        $re_head=0;
        $re_s1=0;
        //system
        $sys_head=0;
        $sys_s1=0;
        $sys_s2=0;
        $sys_s3=0;
        $sys_s4=0;
        $sys_s5=0;
        $sys_s6=0;
        $sys_s7=0;
        $sys_s8=0;
        $sys_s9=0;
        $sys_s10=0;
        $sys_s11=0;
        $sys_s12=0;
        $sys_s13=0;
        $sys_s14=1;
        break;
    case "6":
        //genneral
        $gen_head=0;
        $gen_s1=0;
        $gen_s2=0;
        $gen_s3=0;
        $gen_s4=0;
        $gen_s5=0;
        $gen_s6=0;
        $gen_s7=0;
        $gen_s8=0;
        $gen_s9=0;
        $gen_s10=0;
        //account
        $ac_head=1;
        $ac_s1=0;
        $ac_s2=0;
        $ac_s3=0;
        $ac_s4=1;
        $ac_s5=1;
        //factory
        $fac_head=0;
        $fac_s1=0;
        $fac_s2=0;
        $fac_s3=0;
        //report
        $re_head=0;
        $re_s1=0;
        //system
        $sys_head=1;
        $sys_s1=0;
        $sys_s2=0;
        $sys_s3=0;
        $sys_s4=0;
        $sys_s5=0;
        $sys_s6=0;
        $sys_s7=0;
        $sys_s8=1;
        $sys_s9=0;
        $sys_s10=0;
        $sys_s11=0;
        $sys_s12=0;
        $sys_s13=0;
        $sys_s14=1;
        break;
}

?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Bootstrap core CSS -->

  <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
  <link href="css/accordionmenu.css" type="text/css" media="screen" rel="stylesheet" />
  <link href="bootstrap/css/tbody.css" rel="stylesheet">
  <link href="bootstrap/css/myinput.css" rel="stylesheet">
  <link href="dist/css/sweetalert2.min.css" rel="stylesheet">
  <link href="datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <link href="template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="template/css/sb-admin.css" rel="stylesheet">

  <script src="template/vendor/jquery/jquery.min.js"></script>

  <script type="text/javascript" src="js/functions.js"></script>
  <script src="dist/js/sweetalert2.min.js"></script>

  <script src="datepicker/dist/js/datepicker.min.js"></script>
  <script src="datepicker/dist/js/i18n/datepicker.en.js"></script>
  <title>Linen</title>
  <script src="template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="template/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="template/vendor/datatables/jquery.dataTables.js"></script>
  <script src="template/vendor/datatables/dataTables.bootstrap4.js"></script>
  <script src="template/js/sb-admin.min.js"></script>
  <script src="template/js/demo/datatables-demo.js"></script>

  <script type="text/javascript">
    var summary = [];

    // // ===================================================================
    var last_move, cur_date, target;
    var redirectInSecond = <?php echo $TimeOut ?>; // กำหนดเวลา redirect เป็นวินาที
    var chk_logoff = <?php echo $logoff ?>; 
    var redirect_url = 'http://poseintelligence.dyndns.biz:8181/linen-test/login.php'; // กำหนด url ที่ต้องการเมื่อครบเวลาที่กำหนด
    $(document).ready(function (e) {
      if(chk_logoff == 1 ){
        setActive();
      }
      OnLoadPage();
      target = redirectInSecond * 1000; // แปลงค่าเป็น microsecond
      target = target * 60;
      last_move = new Date() // กำหนดค่าเริ่มต้นให้ last_move
      setTimeout( 'chk_last_move()', 1000 ); // กำหนดเวลาตรวจเช็คเริ่มต้น

      //li active
      $('.current_page').click(function () {
        $("a").removeClass("active_li");
        $(this).attr("class", "active_li");
        var href = $(this).attr('href');
        var sub = href.split("?");
        $('#url_page').val(sub[0]);
      });



    }).mousemove(function (e) {
      last_move = afk();
    }).keyup(function (e) {
      last_move = afk();
    });

    <!-- ============================================================================ -->
    function afk() {
      $("#ShowTime").attr('hidden',true);
      last_move = new Date();
      if(redirectInSecond>=60)
          hms = "00:00:00";
      else
          hms = "00:00";
      $('#ShowTime').val( redirectInSecond + ' / Timeout : ' + hms );
      return last_move;
    }

    function chk_last_move(){
      cur_date = new Date(); // อ่านเวลาปัจจุบันไว้ใน cur_date
      if( cur_date>last_move){ // ตรวจสอบเวลา
        var micro = parseInt(cur_date.getTime() - last_move.getTime());
        var differ = target-micro;
        var ms = differ % 1000;
        differ = (differ - ms) / 1000;
        var secs = differ % 60;
        differ = (differ - secs) / 60;
        var mins = differ % 60;
        var hrs = (differ - mins) / 60;

        if(secs < 10){secs = "0" + secs;}
        if(mins < 10){mins = "0" + mins;}
        if(hrs < 10){hrs = "0" + hrs;}
        var hms = hrs + ':' + mins + ':' + secs;

        $('#ShowTime').val( 'Timeout : ' + hms );

        if( micro > target ) {
          update_logoff();
          setActive();
        }else {
            $("#ShowTime").attr('hidden',false);
            var new_time = target - micro;
            setTimeout('chk_last_move()', 1000 ); //new_time
        }
      }else{
        setTimeout('chk_last_move()', 1000 );
      }
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    <!-- ======================================================================== -->
    function OnLoadPage() {
      var data = {
        'STATUS': 'OnLoadPage'
      };
      senddata(JSON.stringify(data));

    }

    // window.addEventListener("beforeunload", function (e) {
    //     setActive();

    // });

    function logoff(chk) {
      if(chk == 1){
        swal({
          text: '<?php echo $array2['logout'][$language]; ?>',
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: '<?php echo $array2['yes'][$language]; ?>',
          cancelButtonText: '<?php echo $array2['isno'][$language]; ?>'
        })
        .then(function (result) {
          if (result.value) {
            swal({
              text: '<?php echo $array2['logoutfinish'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000
            })
            setTimeout(function(){ 
              var Userid = <?= $Userid ?>;
              var data = {
                'STATUS' : 'logoff',
                'Userid' : Userid
              }
              senddata(JSON.stringify(data));
              window.location.href = "index.html";
            }, 2000);
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }else{
        swal({
          text: '<?php echo $array2['logout'][$language]; ?>',
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: '<?php echo $array2['yes'][$language]; ?>',
          cancelButtonText: '<?php echo $array2['isno'][$language]; ?>'
        })
        .then(function (result) {
          if (result.value) {
            swal({
              text: '<?php echo $array2['logoutfinish'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000
            })
            setTimeout(function(){ 
              var Userid = <?= $Userid ?>;
              var data = {
                'STATUS' : 'logoff',
                'Userid' : Userid
              }
              senddata(JSON.stringify(data));
              window.location.href = "index.html";
            }, 2000);
          } else if (result.dismiss === 'cancel') {
            setActive();
          }
        })
      }
    }

    function update_logoff(){
        var Userid = <?= $Userid ?>;
        var data = {
            'STATUS' : 'update_logoff',
            'Userid' : Userid
        }
        senddata(JSON.stringify(data));
    }

    function setActive(){
      $.ajax({
        url: "alert_login.php",
        success: function (data) {
          swal({
            title: '<h1>Login</h1>',
            type: 'info',
            html:data,
            showCloseButton: false,
            showConfirmButton: false,
            allowOutsideClick: false
          });
        },
      });
      $('#password').val('');
    }

    function login_again(){
      var Userid = <?= $Userid ?>;
      var username = $('#username').val();
      var password = $('#password').val();
      var data = {
        'STATUS' : 'login_again',
        'Userid' : Userid,
        'Username' : username,
        'Password' : password
      }
      senddata(JSON.stringify(data));
    }

    function showPopup(messageid, subject) {

      var mypicture = 'https://www.thaicreate.com/upload/icon-topic/communication.jpg';
      var titletext = 'You have new messages.';
      var bodytext = subject;
      var popup = window.webkitNotifications.createNotification(mypicture, titletext, bodytext);
      popup.show();
      jQuery(popup).css('cursor', 'pointer');
      jQuery(popup).click(function () {
        window.location = "view.php?MessageID=" + messageid;
      });
      setTimeout(function () {
        popup.cancel();
      }, '5000');


    }

    function setlang(){
      var data = {
          'STATUS' : 'SETLANG',
          'lang':'th'
        }
        senddata(JSON.stringify(data));
    }

    function switchlang(lang) {
      
      if (document.URL.indexOf('#') >= 0) {
        var data = {
          'STATUS' : 'SETLANG',
          'lang':lang
        }
        senddata(JSON.stringify(data));
        var url = document.URL.split("#");
        if (url[1] == "") {
          var href = $('#url_page').val() + '?lang=' + lang;
          // window.location.href = "main.php?lang=" + lang + "#" + url[1];
          loadIframe('ifrm', href);
          OnLoadPage();
        } else {
          var href = $('#url_page').val()  + '?lang=' + lang + url[1];
          // window.location.href = "main.php?lang=" + lang + "#" + url[1];
          loadIframe('ifrm', href);
          OnLoadPage();

        }
      } else {
        window.location.href = "main.php?lang=" + lang;
      }
      OnLoadPage();

    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = 'process/main.php';
      $.ajax({
        url: URL,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }
          if (temp["status"] == 'success') {
              if (temp["form"] == 'OnLoadPage') {
                  $("#CPF_Cnt").text(temp["CPF_Cnt"]);
                  $("#HOS_Cnt").text(temp["HOS_Cnt"]);
                  $("#fac_out_Cnt").text(temp["fac_out_Cnt"]);
                  $("#shelfcount_Cnt").text(temp["shelfcount_Cnt"]);
                  $("#clean_Cnt").text(temp["clean_Cnt"]);
                  $("#Item_Cnt").text(temp["Item_Cnt"]);

                  var a1 = parseInt(temp["CPF_Cnt"]);
                  var a2 = parseInt(temp["HOS_Cnt"]);
                  var a3 = parseInt(temp["fac_out_Cnt"]);
                  var a4 = parseInt(temp["shelfcount_Cnt"]);
                  var a5 = parseInt(temp["clean_Cnt"]);
                  $("#main_Cnt").text(a5 + a4);
              }else if(temp['form'] == "Active") {
                setActive();
              }else if(temp['form'] == "login_again"){
              if(temp['cnt'] == 1){
                swal({
                  title: '',
                  text: temp["msg"],
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  timer: 1000,
                  confirmButtonText: 'Ok',
                  showConfirmButton: false
                }).catch(function(timeout) { });
                setTimeout(function(){ 
                    location.reload();
                }, 1000);
              }else{
                swal({
                  title: '',
                  text: temp["msg"],
                  type: 'error',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  timer: 1500,
                  confirmButtonText: 'Ok',
                  showConfirmButton: false
                }).catch(function(timeout) { });
                setTimeout(function(){ 
                    setActive();
                }, 1500);
              }
            }
          } else if (temp['status'] == "failed") {
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

          } else {
            console.log(temp['msg']);
          }
        },
        failure: function (result) {
          alert(result);
        },
        error: function (xhr, status, p3, p4) {
          var err = "Error " + " " + status + " " + p3 + " " + p4;
          if (xhr.responseText && xhr.responseText[0] == "{")
            err = JSON.parse(xhr.responseText).Message;
          console.log(err);
          alert(err);
        }
      });
    }
  </script>

  <style>
      @font-face {
            font-family: myFirstFont;
            src: url("../fonts/DB Helvethaica X.ttf");
            }
     
    @font-face {
      font-family: 'DB Helvethaica X';
      src: url('fonts/DB Helvethaica X.ttf');
    }

    @font-face {
      font-family: 'THSarabunB';
      src: url('fonts/DB Helvethaica X Bold.ttf');
    }

    @font-face {
      font-family: 'THSarabunI';
      src: url('fonts/DB Helvethaica X Italic.ttf');
    }

    @font-face {
      font-family: 'THSarabunBI';
      src: url('fonts/DB Helvethaica X BoldItalic.ttf');
    }

    body {
      display: grid;
      grid-template-areas:
        "header header header"
        "nav article ads"
        "footer footer footer";
      grid-template-rows: 35px 1fr 0px;
      grid-template-columns: 15% 1fr 0;
      grid-gap: 0px;
      height: 100vh;
      margin: 0;
    }

    #pageHeader {
      grid-area: header;
    }

    #pageFooter {
      grid-area: footer;
    }

    #mainArticle {
      grid-area: article;
    }

    #mainNav {
      grid-area: nav;
    }

    #siteAds {
      grid-area: ads;
    }

    header,
    footer,
    article,
    nav,
    div {
      padding: 10px;
      background: #FFF;
    }

    nav ul {
      list-style: none;
      overflow: hidden;
      position: relative;
    }

    .bluebg {
      background: rgb(0, 51, 141) !important;
      color: white !important;
    }

    .bluebg:hover {
      color: white !important;
    }

    #mainArticle {
      border: 2px solid #ececec;
      border-radius: 8px;
      margin-right: 10px;
      margin-bottom: 10px;
    }

    #navmenu {
      border: 2px solid #ececec;
      border-radius: 8px;
    }

    .sub-menu li a {
      color: #797979;
      text-shadow: 1px 1px 0px rgba(255, 255, 255, .2);
      background: #f2f2f2 !important;
      border-bottom: 1px solid #c9c9c9;
      -webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, .1), 0px 1px 0px 0px rgba(0, 0, 0, .1);
      -moz-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, .1), 0px 1px 0px 0px rgba(0, 0, 0, .1);
      box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, .1), 0px 1px 0px 0px rgba(0, 0, 0, .1);
    }

    .active_li {
      color: #24246A !important;
      font-size: 24px !important;
      font-weight: bold !important;
    }

    #mr_form{
      width: 400px;
      margin: 30px !important;
    }
    #mr_form div{
      padding: 2px;
    }
  </style>

<body>
  <header id="pageHeader" class="navbar navbar-expand static-top">

    <img src="img/logo.jpg" style="width: 100px;margin-top:55px;margin-bottom:20px;" alt="">

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">

    </form>
    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0" >
      <div style="padding-top:15px;" hidden><a href="#" onclick="switchlang('th');">TH</a> / <a href="#" onclick="switchlang('en');">EN</a></div>
      <li class="nav-item dropdown no-arrow" style="padding-top:12px;">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw" style="font-size: 25px;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#" data-toggle="modal" onclick="logoff(1);"><?php echo $array['menu']['logout'][$language]; ?></a>
        </div>
      </li>
    </ul>
  </header>

  <article id="mainArticle" style="margin-top:25px;">
    <input type="hidden" id=last_move>
    <iframe name="ifrm" id="ifrm" src="pages/menu.php?lang=<?php echo $language; ?>" class="current_page"
      frameborder="0" style="height:100%; width:100%; "></iframe>
  </article>

  <nav id="mainNav" style="margin-top:25px;">
  <input type="hidden" id='url_page'>
    <ul class="accordion" id="navmenu">
      <?php if($gen_head== 1){ ?>
      <li id="general">

        <a class="bluebg" style="font-family: 'DB Helvethaica X'; font-size:20px;"
          href="#general"><?php echo $array['menu']['general']['title'][$language]; ?><span id='main_Cnt'>0</span></a>

        <ul class="sub-menu">
          <?php if($gen_s1== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;" href="pages/menu.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][0][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($gen_s2== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/dirty.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][1][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($gen_s3== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/clean.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][2][$language]; ?><span style='color: #ff0000;'
                id='clean_Cnt'>0</span></a>
          </li>
          <?php } ?>
          <?php if($gen_s4== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/shelfcount.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][3][$language]; ?><span style='color: #ff0000;'
                id='shelfcount_Cnt'>0</span></a>
          </li>
          <?php } ?>
          <?php if($gen_s5== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/percent.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][4][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($gen_s6== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/stock.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][5][$language]; ?></a>
          </li>
          <?php } ?>


          <?php if($gen_s7== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/stock_in.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][6][$language]; ?></a>
          </li>
          <?php } ?>

          <?php if($gen_s8== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/stock_in.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][7][$language]; ?></a>
          </li>
          <?php } ?>

          <?php if($gen_s9== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/draw.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][8][$language]; ?></a>
          </li>
          <?php } ?>
          <!-- <?php if($gen_s10== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/datechangeprice.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['general']['sub'][9][$language]; ?></a>
          </li>
          <?php } ?> -->
        </ul>

      </li>
      <?php } ?>
      <?php if($ac_head== 1){ ?>
      <li id="account">

        <a class="bluebg" style="font-family: 'DB Helvethaica X'; font-size:20px;"
          href="#account"><?php echo $array['menu']['account']['title'][$language]; ?></a>

        <ul class="sub-menu">
          <?php if($ac_s1== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;" href="pages/claim.php?lang=<?php echo $language; ?>" class="current_page" onclick="return loadIframe('ifrm', this.href)"> <em></em><?php echo $array['menu']['account']['sub'][0][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($ac_s2== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/billcustomer.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['account']['sub'][1][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($ac_s3== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/billwash.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['account']['sub'][2][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($ac_s4== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/contract_parties_factory.php?lang=<?php echo $language; ?>"class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['account']['sub'][3][$language]; ?><span style='color: #ff0000;'
                id='CPF_Cnt'>0</span></a>
          </li>
          <?php } ?>
          <?php if($ac_s5== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/contract_parties_hospital.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['account']['sub'][4][$language]; ?><span style='color: #ff0000;'
                id='HOS_Cnt'>0</span></a>
          </li>
          <?php } ?>

        </ul>

      </li>

      <?php } ?>
      <?php if($fac_head== 1){ ?>
      <li id="factory">

        <a class="bluebg" style="font-family: 'DB Helvethaica X'; font-size:20px;"
          href="#factory"><?php echo $array['menu']['xfactory']['title'][$language]; ?></a>

        <ul class="sub-menu">
          <?php if($fac_s1== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/factory_in.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['xfactory']['sub'][0][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($fac_s2== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/factory_out.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['xfactory']['sub'][1][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($fac_s3== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/factory_document_status.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['xfactory']['sub'][2][$language]; ?></a>
          </li>
          <?php } ?>

        </ul>

      </li>
      <?php } ?>
      <?php if($re_head== 1){ ?>
      <li id="report">

        <a class="bluebg" style="font-family: 'DB Helvethaica X'; font-size:20px;"
          href="#report"><?php echo $array['menu']['report']['title'][$language]; ?></a>

        <ul class="sub-menu">
          <?php if($re_s1== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="#"><em></em><?php echo $array['menu']['report']['sub'][$language]; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($sys_head== 1){ ?>
      <li id="system">

        <a class="bluebg" style="font-family: 'DB Helvethaica X'; font-size:20px;"
          href="#system"><?php echo $array['menu']['system']['title'][$language]; ?></a>

        <ul class="sub-menu">
          <?php if($sys_s1== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/factory.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][0][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($sys_s2== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/side.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][1][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($sys_s3== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/department.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][2][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($sys_s4== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/item.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][3][$language]; ?>
              <!-- <span style='color: #ff0000;' id='Item_Cnt'>0</span> -->
              </a>
          </li>
          <?php } ?>
          <?php if($sys_s5== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/category_main.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][4][$language]; ?></span></a>
          </li>
          <?php } ?>
          <?php if($sys_s6== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/category_sub.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][5][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($sys_s7== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/item_unit.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][6][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($sys_s8== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/link_item_dept.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][7][$language]; ?></a>
          </li>
          <?php } ?>
          <?php if($sys_s9== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="change_timeout.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][8][$language]; ?></a>
          </li>
          <?php } ?>
        
          <?php if($sys_s11== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/user.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][10][$language]; ?></a>
          </li>
          <?php } ?>

          <?php if($sys_s12== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/set_price.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][11][$language]; ?></a>
          </li>
          <?php } ?>

          <?php if($sys_s13== 1){ ?>
          <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
              href="pages/change_lang.php?lang=<?php echo $language; ?>" class="current_page"
              onclick="return loadIframe('ifrm', this.href)">
              <em></em><?php echo $array['menu']['system']['sub'][12][$language]; ?></a>
          </li>
          <?php } ?>

            <?php if($sys_s14== 1){ ?>
                <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;"
                       href="pages/setting.php?lang=<?php echo $language; ?>" class="current_page"
                       onclick="return loadIframe('ifrm', this.href)">
                        <em></em><?php echo $array['menu']['system']['sub'][13][$language]; ?></a>
                </li>
            <?php } ?>
            <!-- <li><a style="font-family: 'DB Helvethaica X'; font-size:20px;" href="pages/item_multiple_unit.php" onclick="return loadIframe('ifrm', this.href)">
                          <em></em>หลายหน่วยนับ</a>
                      </li> -->

        </ul>

      </li>
      <?php } ?>

    </ul>
    <div class='row col-12'>
        <input class='form-control ' style='border:none;width:90%'  id='ShowTime' >
    </div>
  </nav>
  <!-- div id="siteAds">Ads</div -->
  <!-- footer id="pageFooter">Footer</footer -->
</body>

</html>