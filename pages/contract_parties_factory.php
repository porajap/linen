<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];

if($Userid==""){
   header("location:../index.html");
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

    <title><?php echo $array['titlecontractfac'][$language]; ?></title>

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
    <link href="../css/menu_custom.css" rel="stylesheet">
    

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="../jQuery-ui/jquery-1.12.4.js"></script>
    <script src="../jQuery-ui/jquery-ui.js"></script>
    <script type="text/javascript">
      jqui = jQuery.noConflict(true);
    </script>

    <link href="../dist/css/sweetalert2.css" rel="stylesheet">
    <script src="../dist/js/sweetalert2.min.js"></script>
    <script src="../dist/js/jquery-3.3.1.min.js"></script>
<!-- =================================== -->
    <?php if ($language =='th'){ ?>
      <script src="../datepicker/dist/js/datepicker.js"></script>
    <?php }else if($language =='en'){ ?>
        <script src="../datepicker/dist/js/datepicker-en.js"></script>
    <?php } ?>
<!-- =================================== -->

    <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
    <script src="../datepicker/dist/js/datepicker.th.js"></script>

    <script type="text/javascript">
      var summary = [];
      function twoDigit(s)
      {
          var sNum = s.toString();
          if(sNum.length==1) sNum = "0"+s;
          return sNum;
      }
        // ---------------------------------------------------
        (function ($)
        {
            $(document).ready(function ()
            {
                $("#datepicker3").datepicker({
                    onSelect: function (date, el)
                    {
                        resetinput();
                    }
                });
                $("#datepicker4").datepicker({
                    onSelect: function (date, el)
                    {
                        resetinput();
                    }
                });
            });
        })(jQuery);
        // ---------------------------------------------------
    $(document).ready(function(e)
    {
      $('#rem1').hide();
      $('#rem2').hide();
      $('#rem3').hide();
      OnLoadPage();
      setTimeout(() => {
        ShowDocument();
        ShowFactory();
      }, 500);
      $('.only').on('input', function()
      {
        this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
      });
      $('.numonly').on('input', function()
      {
        this.value = this.value.replace(/[^0-9./]/g, ''); //<-- replace all other than given set of values
      });
      $('.charonly').on('input', function()
      {
        this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
      });
      $("#IsStatus").val('0');
    }).click(function(e) { parent.afk();
    }).keyup(function(e) { parent.afk();
    });

    jqui(document).ready(function($)
    {
		  dialog = jqui( "#dialog" ).dialog({
		  autoOpen: false,
		  height: 650,
		  width: 1200,
		  modal: true,
		  buttons:
      {
        "ปิด": function()
        {
          dialog.dialog( "close" );
        }
		  },
		  close: function()
      {
			  console.log("close");
		  }
		  });

    });

	//======= On create =======
	//console.log(JSON.stringify(data));
	function OnLoadPage()
  {
      var data =
      {
        'STATUS'  : 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
	    $('#IsStatus').val(0)
	}
	function ShowFactory()
  {
    var HptCode = $('#ShowSite').val();
      var data =
      {
        'STATUS'  : 'ShowFactory',
        'HptCode'	: HptCode
      };
      senddata(JSON.stringify(data));
	    $('#IsStatus').val(0)
	}
  function checkblank2()
  {
    var factory = $('#factory').val();
    var datepicker1 = $('#datepicker3').val();
    var datepicker2 = $('#datepicker4').val();
      $('.checkblank2').each(function()
      {
        if($(this).val()==""||$(this).val()==undefined)
        {
          $(this).addClass('border-danger');
              if(factory ==""||factory==undefined)
              {
                $('#rem1').show().css("color","red");
              }
              if(datepicker1 ==""||datepicker1==undefined)
              {
                $('#rem2').show().css("color","red");
              }
              if(datepicker2 ==""||datepicker2==undefined)
              {
                $('#rem3').show().css("color","red");
              }
        }
        else
        {
          $(this).removeClass('border-danger');
        }
      });
  }

  function removeClassBorder1()
  {
    $('#factory').removeClass('border-danger');
  }

	function CreateDocument()
  {
		 dialog.dialog( "open" );
  }

	function getRow(id , row)
  {
    var previousValue = $('#checkitem_'+row).attr('previousValue');
        var name = $('#checkitem_'+row).attr('name');
        if (previousValue == 'checked')
        {
          $('#checkitem_'+row).removeAttr('checked');
          $('#checkitem_'+row).attr('previousValue', false);
          $('#checkitem_'+row).prop('checked', false);
          ClearRow();
        }
        else
        {
          $("input[name="+name+"]:radio").attr('previousValue', false);
          $('#checkitem_'+row).attr('previousValue', 'checked');
		        var data =
            {
              'STATUS'  : 'getRow',
		          'RowID'	: id
            };
          senddata(JSON.stringify(data));
        }
  }

	function ClearRow()
  {
    $('#rem1').hide();
    $('#rem2').hide();
    $('#rem3').hide();
		$("#IsStatus").val('0');
		$("#datepicker3").val('');
		$("#datepicker4").val('');
		$("#xDetail").val('');
    $("#factory").val('');
    $('.checkblank2').each(function()
    {
      if($(this).val()==""||$(this).val()==undefined)
      {
        $(this).removeClass('border-danger');
      }
      else
      {
        $(this).removeClass('border-color', '');
      }
    });
    $('#delete_icon').addClass('opacity');
    $('#bCancel').attr('disabled', true);
    $('#delete_icon').addClass('opacity');
    $(".checkitem").each(function(){
        $(".checkitem").prop("checked", false);
    });
	}

	function CancelRow()
  {
      var id = $("#xRowID").val();
      setTimeout(function ()
      {
        parent.OnLoadPage();
      }, 1000);
      swal({
      title: "<?php echo $array['canceldata'][$language]; ?>",
      text: "<?php echo $array['factory'][$language]; ?> : " +$('#factory option:selected').text(),
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
      cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      closeOnConfirm: false,
      closeOnCancel: false,
      showCancelButton: true}).then(result =>
      {
        if (result.value)
        {
          swal({
            title: '',
            text: "<?php echo $array['cancelsuccessmsg'][$language]; ?>",
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            timer: 2000,
            confirmButtonText: 'Ok'
          });
          setTimeout(() =>{
          var data = {
            'STATUS'  : 'CancelRow',
            'RowID'	: id
          };
          senddata(JSON.stringify(data));
          ClearRow();
          ShowDocument();
          }, 2000);
        }
        else if(result.dismiss === 'cancel')
        {
          swal.close();
        } 
      })
  }

  function resetinput()
  {
    var facid = $('#factory option:selected').attr("value");
    var datepicker3 = $('#datepicker3').val();
    var datepicker4 = $('#datepicker4').val();
    if(facid !="" && facid!=undefined)
    {
      $('#rem1').hide();
      $('#factory').removeClass('border border-danger');
    }
    if(datepicker3 !="" && datepicker3!=undefined)
    {
      $('#rem2').hide();
      $('#datepicker3').removeClass('border border-danger');
    }
    if(datepicker4 !="" && datepicker4 !=undefined)
    {
      $('#rem3').hide();
      $('#datepicker4').removeClass('border border-danger');
    }
  }

  function SaveRow()
  {
      var HptCode = $('#ShowSite').val();
      var isStatus = $("#IsStatus").val();
      var id = $("#xRowID").val();
      var facid = $('#factory option:selected').attr("value");
      var datepicker3 = $('#datepicker3').val();
      var datepicker4 = $('#datepicker4').val();
      var datepicker1 = $('#datepicker3').val();
      var datepicker2 = $('#datepicker4').val();
      var lang = '<?php echo $language; ?>';
      if(lang =='th')
      {
        datepicker1 = datepicker1.substring(6, 10)-543+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
        datepicker2 = datepicker2.substring(6, 10)-543+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
      }
      else if(lang =='en')
      {
        datepicker1 = datepicker1.substring(6, 10)+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
        datepicker2 = datepicker2.substring(6, 10)+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
      }
      var chk1 = new Date(datepicker1);
      var chk2 = new Date(datepicker2);

      if(chk1>chk2)
      {
        swal({
          title: "",
          text: "<?php echo $array['invalid'][$language]; ?>",
          type: "warning",
          showConfirmButton: false,
          showCancelButton: false,
          timer:2000
        });
        setTimeout(() =>
        {
          $('#datepicker4').addClass('border border-danger');
        }, 2000);
      }
      else
      {
        $('#datepicker4').removeClass('border border-danger');
        var xDetail = $("#xDetail").val();
        if(facid=='' || datepicker3=='' || datepicker4=='')
        {
            checkblank2();
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
        }
        else
        {
            swal({
            title: "",
            text: "<?php echo $array['confirmsave1'][$language]; ?>",
            type: "question",
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
                swal({
                title: '',
                text: "<?php echo $array['savesuccess'][$language]; ?>",
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
                });
              setTimeout(() =>
              {
                var data =
                {
                  'STATUS'  	: 'SaveRow',
                  'isStatus'	: isStatus,
                  'RowID'		: id,
                  'facid'		: facid,
                  'sDate'		: datepicker1,
                  'eDate'		: datepicker2,
                  'Detail'	: xDetail,
                  'HptCode'	: HptCode
                };
                ClearRow();
                senddata(JSON.stringify(data));
              }, 2000);
            }
            else if (result.dismiss === 'cancel')
            {
                swal.close();
            }     
          })
        }	
      }
  }

	function ShowDocument()
  {
    var HptCode = $('#ShowSite').val();
	  var datepicker1 = $('#datepicker1').val();
    var datepicker2 = $('#datepicker2').val();
    var lang = '<?php echo $language; ?>';
    if(lang =='th')
    {
	    datepicker1 = datepicker1.substring(6, 10)-543+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
	    datepicker2 = datepicker2.substring(6, 10)-543+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
    }
    else if(lang =='en')
    {
      datepicker1 = datepicker1.substring(6, 10)+"-"+datepicker1.substring(3, 5)+"-"+datepicker1.substring(0, 2);
	    datepicker2 = datepicker2.substring(6, 10)+"-"+datepicker2.substring(3, 5)+"-"+datepicker2.substring(0, 2);
    }
	  var deptCode = $('#department option:selected').attr("value");
	  if( typeof deptCode == 'undefined' ) deptCode = "1";
	  //alert( deptCode );
      var data =
      {
        'STATUS'  	: 'ShowDocument',
        'deptCode'	: deptCode,
        'sDate'	: datepicker1,
        'eDate'	: datepicker2,
        'HptCode'	: HptCode
      };
      // เรียก function โชว์ โรงซัก
      ShowFactory();
      senddata(JSON.stringify(data));
	}

  function logoff()
  {
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
    }).then(function () {
      window.location.href="../logoff.php";
    }, function (dismiss) {
      window.location.href="../logoff.php";
      if (dismiss === 'cancel')
      {

      }
    })
  }

    function senddata(data){
       var form_data = new FormData();
       form_data.append("DATA",data);
       var URL = '../process/contract_parties_factory.php';
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
                  if(temp["status"]=='success')
                  {
										if(temp["form"]=='OnLoadPage')
                    {
                      $("#ShowSite").empty();
                      var Str = "<option value='' selected><?php echo $array['selecthospital'][$language]; ?></option>";
											for (var i = 0; i < temp['count']; i++)
                      {
												 Str += "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
											}
                      $("#ShowSite").append(Str);
										}
                    else if(temp["form"]=='ShowFactory')
                    {
                      $("#factory").empty();
                      var Str = "<option value='' selected><?php echo $array['selectfactory'][$language]; ?></option>";
											for (var i = 0; i < temp['count']; i++) {
												 Str += "<option value="+temp[i]['FacCode']+">"+temp[i]['FacName']+"</option>";
											}
                      $("#factory").append(Str);
										}
                    else if(temp["form"]=='CancelRow')
                    {
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
                                ClearRow();
                              }, function(dismiss) {
                                $('.checkblank').each(function() {
                                  $(this).val("");
                                });
										
                      })
                    }
                    else if(temp["form"]=='ShowDocument')
                    {
                      setTimeout(function () {
                        parent.OnLoadPage();
                      }, 1000);
                          $("#tbody").empty();
                          if(temp['Count']>0){
                            var Style  = "";
                            for (var i = 0; i < temp['Count']; i++) {
                              var rowCount = $('#TableDocument >tbody >tr').length;
                              var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' >";

                              var sDate = new Date();
                              var eDate = new Date( temp[i]['EndDate'] );
                              var diff  = new Date(eDate - sDate);

                              // var days = Math.round(diff/1000/60/60/24);
                              var days = temp[i]['LeftDay'];
                              var chkDetail = "<label class='radio'style='margin-top: 27%;'><input type='radio' name='checkitem' class='checkitem' id='checkitem_"+i+"' value='" + temp[i]['RowID'] + "'  onclick='getRow(\"" + temp[i]["RowID"] + "\",\""+i+"\")'><span class='checkmark'></span></label>";

                              if(days <= 30){
                                Style  = "style='font-weight: bold;color: #000000	;border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;''";
                              }else{
                                Style  = "style='font-weight: bold;color: #000000;border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;''";
                              }
                              var daytext = days <= 0 ? "หมดสัญญา" : days ;

                              $StrTr="<tr "+Style+" id='tr"+temp[i]['RowID']+"' >"+
                                    "<td style='width: 3%;'>"+chkDetail+"</td>"+
                                    "<td style='width: 5%;'>"+(i+1)+"</td>"+
                                    "<td style='width: 25%;'>"+temp[i]['FacName']+"</td>"+
                                    "<td style='width: 13%;'>"+temp[i]['StartDate']+"</td>"+
                                    "<td style='width: 8%;'>"+temp[i]['EndDate2']+"</td>"+
                                    "<td style='width: 15%; text-align: center;'>"+daytext+"</td>"+
                                    "<td style='width: 15%;'>"+temp[i]['Detail']+"</td>"+
                                    "<td style='width: 16%;'>"+temp[i]['HptNameTH']+"</td>"+
                                  "</tr>";

                                if(rowCount == 0){
                                  $("#TableDocument tbody").append( $StrTr );
                                }else{
                                  $('#TableDocument tbody:last-child').append(  $StrTr );
                                }
                            }
                          }
                          else
                          {
                            if(temp['HptCode'] == "")
                            {
                              swal({
                                title: '',
                                text: 'กรุณาเลือกโรงพยาบาล',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                confirmButtonText: 'Ok'
                              })
                            }
                            else
                            {
                              swal({
                                title: '',
                                text: "<?php echo $array['ndc'][$language]; ?>" + " " + temp['HptCode']  ,
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                confirmButtonText: 'Ok'
                              })
                            }
                          }

										  }else if(temp["form"]=='getRow'){
											    $("#IsStatus").val('1');
											    $("#xRowID").val(temp[0]['RowID']);
												$("#datepicker3").val(temp[0]['StartDate']);
												$("#datepicker4").val(temp[0]['EndDate']);
												$("#xDetail").val(temp[0]['Detail']);
												$("#factory").val(temp[0]['FacCode']);
												// var fac_code = temp[0]['FacCode'];
												// var fac_length = $('#factory > option').length;


												// for(var i=0;i<fac_length;i++){
												// 	if(fac_code == i) $('#factory option[value="'+i+'"]').prop("selected", true);
												// }
                        $('#bCancel').attr('disabled', false);
                        $('#delete_icon').removeClass('opacity');
										  }
                  }
                  else if (temp['status']=="failed")
                  {
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
                                temp['msg'] = "<?php echo $array['ndc'][$language]; ?>";
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

                    $( "#docnofield" ).val( temp[0]['DocNo'] );
                    $( "#TableDocumentSS tbody" ).empty();
                    $( "#TableSendSterileDetail tbody" ).empty();

							    }
                  else
                  {
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
  button,input[id^='qty'],input[id^='weight'],input[id^='price']{
    font-size: 24px!important;
  }
  .table > thead > tr >th {
    /* background: #4f88e3!important; */
    background-color: #1659a2;
  }
  .table th, .table td {
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
  /* bottom-right border-radius */
  table tr:last-child td:last-child {
    border-bottom-right-radius: 6px;
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
.mhee button{
  /* padding: 6px 8px 6px 16px; */
  text-decoration: none;
  font-size: 25px;
  color: #2c3e50;
  display: block;
  background:none;
  box-shadow:none !important;
}
.mhee button:hover {
  color: #2c3e50;
  font-weight:bold;
  font-size:26px;
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
  .opacity{
    opacity:0.5;
  }
  @media (min-width: 992px) and (max-width: 1199.98px) { 

    .icon{
      padding-top: 6px;
      padding-left: 23px;
    }
    .sidenav a {
      font-size: 20px;

    }
  }
</style>
  </head>

  <body id="page-top">
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
  <ol class="breadcrumb">
  
          <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['account']['title'][$language]; ?></a></li>
          <li class="breadcrumb-item active"><?php echo $array2['menu']['account']['sub'][3][$language]; ?></li>
        </ol>
    <div id="wrapper">
      <!-- content-wrapper -->
      <div id="content-wrapper">

    <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
<div class="col-md-12"> <!-- tag column 1 -->
<!-- /.content-wrapper -->
            <div class="row mt-3">
                  <div class="container-fluid">
                    <div class="card-body">
                      <div class="row col-12">
                       <div class="col-md-3 ml-3">
                         <div class='form-group row'>
                          <select  style="font-size:22px;" class="form-control " id="ShowSite" onchange="ShowDocument()"> </select>
                        </div>
                       </div>    
                        <div class="col-md-3 ml-3">
                            <div class='form-group row'>
                              <input type="text" autocomplete="off" style="font-size:22px;" class="form-control  datepicker-here numonly charonly" id="datepicker1" data-language=<?php echo $language ?>  data-date-format='dd-mm-yyyy' placeholder="วันที่ทำสัญญา">
                            </div>
                          </div>
                        <div class="col-md-3 ml-3">
                          <div class='form-group row'>
                            <input type="text" autocomplete="off" style="font-size:22px;" class="form-control c datepicker-here numonly charonly" id="datepicker2" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' placeholder="วันที่สิ้นสุดสัญญา">
                          </div>
                        </div>
                      <div class="search_custom col-md-2">
                        <div class="search_1 d-flex justify-content-start">
                          <button class="btn" onclick="ShowDocument()" id="bSave">
                            <i class="fas fa-search mr-2"></i><?php echo $array['search'][$language]; ?>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
           </div>
<div class="row">
              <div style='width: 98%;margin-top: -25px;'> <!-- tag column 1 -->
              		<table style="margin-top:10px;margin-left:15px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid" style="">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 3%;' nowrap>&nbsp;</th>
                              <th style='width: 5%;'><?php echo $array['no'][$language]; ?></th>
                              <th style='width: 25%;'><?php echo $array['factory'][$language]; ?></th>
                              <th style='width: 12%;'><?php echo $array['datestartcontract'][$language]; ?></th>
                              <th style='width: 9%;'><?php echo $array['dateendcontract'][$language]; ?></th>
                              <th style='width: 15%;'><center><?php echo $array['numbercontract'][$language]; ?></center></th>
                              <th style='width: 14%;'><?php echo $array['detail'][$language]; ?></th>
                              <th style='width: 17%;'><?php echo $array['side'][$language]; ?></th>
                            </tr>
                          </thead>
                          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:360px;">
                          </tbody>
					</table>
              </div> <!-- tag column 1 -->
    </div>
    <!-- <?php if($PmID == 1) echo 'hidden'; ?> -->
</div>
<div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end" >
  <div class="menu" <?php if($PmID == 2) echo 'hidden'; ?>>
    <div class="d-flex justify-content-center">
      <div class="circle4 d-flex justify-content-center">
        <button class="btn"  onclick="SaveRow()" id="bSave">
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
        <button class="btn" onclick="ClearRow()" id="bDelete">
          <i class="fas fa-redo-alt"></i>
          <div>
            <?php echo $array['clear'][$language]; ?>
          </div>       
        </button>
      </div>
    </div>
  </div>
  <div class="menu" <?php if($PmID == 2) echo 'hidden'; ?>>
    <div class="d-flex justify-content-center">
      <div class="circle3 d-flex justify-content-center opacity" id="delete_icon">
        <button class="btn" onclick="CancelRow()" id="bCancel" disabled="true">
          <i class="fas fa-trash-alt"></i>
          <div>
            <?php echo $array['cancel'][$language]; ?>
          </div>  
        </button>
      </div>
    </div>
  </div>
</div>

<div class=" col-md-12 p-5">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
                        </li>
                      </ul>
     <!-- =================================================================== -->
                      <div class="row mt-4">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['factory'][$language]; ?></label>
                                      <select  style="font-size:22px;" onchange="resetinput()" class="form-control col-sm-7 checkblank2 border" id="factory" onchange="removeClassBorder1();" ></select>
                                      <label id="rem1" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['detail'][$language]; ?></label></label><input type="hidden" id="xRowID" >
                                        <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 " id="xDetail" placeholder="<?php echo $array['detail'][$language]; ?>" >
                                    </div>
                                  </div>
                                </div>  
      <!-- =================================================================== -->
                              <div class="row ">
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['datestartcontract'][$language]; ?></label>
                                        <input type="text" autocomplete="off" class="form-control col-sm-7 datepicker-here only checkblank2" style="font-size:22px;" id="datepicker3"  data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' placeholder="<?php echo $array['selectstartdate'][$language]; ?>">
                                        <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label "><?php echo $array['dateendcontract'][$language]; ?></label>
                                      <input type="text" autocomplete="off" class="form-control col-sm-7 datepicker-here only checkblank2" style="font-size:22px;" id="datepicker4" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' placeholder="<?php echo $array['selectenddate'][$language]; ?>">
                                      <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                  </div>
                                </div> 
                              </div>
                            </div> <!-- end row tab -->
                          </div>
<!-- Dialog Modal
    <div id="dialog" title="คู่สัญญา"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
      <div class="container">
        <div class="row">
          <div class="col-md-5">


          </div>
        </div>
        <div class="dropdown-divider" style="margin-top:20px; margin-bottom:20px;"></div>
        <div class="row">

        </div>

      </div>
-->

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
