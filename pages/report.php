<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$HptName = $_SESSION['HptName'];
$FacCode = $_SESSION['FacCode'];
$DepCode = $_SESSION['DepCode'];
$GroupCode = $_SESSION['GroupCode'];
$DocnoXXX = $_GET['DocNo'];
if ($Userid == "") {
	header("location:../index.html");
}


$language = $_SESSION['lang'];


header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>shelfcount</title>

	<link rel="icon" type="image/png" href="../img/pose_favicon.png">
	<!-- Bootstrap core CSS-->
	<!-- <link href="../fontawesome/css/fontawesome.min.css" rel="stylesheet"> -->
	<link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

	<link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../bootstrap/css/tbody.css" rel="stylesheet">
	<link href="../bootstrap/css/myinput.css" rel="stylesheet">
	<!-- Page level plugin CSS-->
	<link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="../template/css/sb-admin.css" rel="stylesheet">
	<link href="../css/xfont.css" rel="stylesheet">
	<!-- -----------------------------------------------  -->
	<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
	<script src="../jQuery-ui/jquery-1.12.4.js"></script>
	<script src="../jQuery-ui/jquery-ui.js"></script>
	<script type="text/javascript">
		jqui = jQuery.noConflict(true);
	</script>
	<link href="../css/menu_custom.css" rel="stylesheet">
	<link href="../dist/css/sweetalert2.css" rel="stylesheet">
	<script src="../dist/js/sweetalert2.min.js"></script>
	<script src="../dist/js/jquery-3.3.1.min.js"></script>
	<link href="../css/responsive.css" rel="stylesheet">
	<!-- =================================== -->
	<?php if ($language == 'th') { ?>
		<script src="../datepicker/dist/js/datepicker.js"></script>
	<?php } else if ($language == 'en') { ?>
		<script src="../datepicker/dist/js/datepicker-en.js"></script>
	<?php } ?>
	<!-- =================================== -->

	<link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
	<!-- Include English language -->
	<script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
	<script src="../datepicker/dist/js/datepicker.th.js"></script>
	<link href="../css/report.css" rel="stylesheet">
	<script src="../fontawesome/js/fontawesome.min.js"></script>
	<script type="text/javascript">
		var summary = [];
		var xItemcode;
		var currentDate = '<?php echo date('Y-m-d'); ?>';
		var currentDate2 = '<?php echo date('Y/m/d'); ?>';
		var m = '';
		var day = '';
		var many_day = '';
		var month = '';
		var many_month = '';
		var show_year = '';
		var PmID = '<?PHP echo  $PmID ?>';
		if (PmID == 1 || PmID == 6) {
			var tf = false;
		} else {
			var tf = true;
		}
		$(document).ready(function(e) 
		{
			$('#showday').hide();
			$('#showmonth').hide();
			$('#showyear').hide();
			$('#someday').hide();
			$('#somemonth').hide();
			$('#myDay').hide();
			$('#myMonth').hide();
			$('#textto').hide();
			$('#textto2').hide();
			$('#chk').show();
			$('#rem1').hide();
			$('#rem2').hide();
			$('#rem3').hide();
			$('#rem4').hide();
			$('#rem5').hide();
			$('#rem6').hide();
			$('#rem7').hide();
			$('#rem8').hide();
			$('#rem9').hide();
			$('#rem10').hide();
			$('#rem11').hide();
			$('#rem12').hide();
			OnLoadPage();
			$('#hotpital').attr('disabled', tf);
			$('#department').attr('disabled', true);
			$('#cycle').attr('disabled', true);
			$('#factory').attr('disabled', true);
			$('#PPU').attr('disabled', true);
			$('#grouphpt').attr('disabled', true);
			$('#item').attr('disabled', true);
			$(".select2").select2();
			$('#table_Fac').attr('hidden', false);
			$('#table_Dep').attr('hidden', true);
			$('#table_DepDoc').attr('hidden', true);
			$('#table_Group').attr('hidden', true);
			$('#table_usage_detail').attr('hidden', true);
			$('#table_category').attr('hidden', true);
			$('#table_NoFacDep').attr('hidden', true);
		}).mousemove(function(e) {
			parent.afk();
		}).keyup(function(e) {
			parent.afk();
		});

		jqui(document).ready(function($) 
		{
			$('.only').on('input', function() {
				this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
			});

			dialogUsageCode = jqui("#dialogUsageCode").dialog({
				autoOpen: false,
				height: 680,
				width: 1200,
				modal: true,
				buttons: {
					"<?php echo $array['close'][$language]; ?>": function() {
						dialogUsageCode.dialog("close");
					}
				},
				close: function() {
					console.log("close");
				}
			});


		});

		//======= function========================================================================================================
		function OnLoadPage() 
		{
			var data = 
			{
				'STATUS': 'OnLoadPage'
			};
			senddata(JSON.stringify(data));
			$('#isStatus').val(0)
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
			}).then(function() 
			{
				window.location.href = "../logoff.php";
			}, 
			function(dismiss) 
			{
				window.location.href = "../logoff.php";
				if (dismiss === 'cancel') {}
			})
		}

		function find_indexMonth(year) 
		{
			var monthArray = new Array();
			var language = '<?php echo $language ?>';
			if (language == 'en') 
			{
				monthArray[0] = "January";
				monthArray[1] = "February";
				monthArray[2] = "March";
				monthArray[3] = "April";
				monthArray[4] = "May";
				monthArray[5] = "June";
				monthArray[6] = "July";
				monthArray[7] = "August";
				monthArray[8] = "September";
				monthArray[9] = "October";
				monthArray[10] = "November";
				monthArray[11] = "December";
			}
			else
			{
				monthArray[0] = "มกราคม";
				monthArray[1] = "กุมภาพันธ์";
				monthArray[2] = "มีนาคม";
				monthArray[3] = "เมษายน";
				monthArray[4] = "พฤษภาคม";
				monthArray[5] = "มิถุนายน";
				monthArray[6] = "กรกฎาคม";
				monthArray[7] = "สิงหาคม";
				monthArray[8] = "กันยายน";
				monthArray[9] = "ตุลาคม";
				monthArray[10] = "พฤศจิกายน";
				monthArray[11] = "ธันวาคม";
			}
			var d = new Date();
			var n = monthArray[d.getMonth()];
			if (language == 'th') 
			{
				var year = parseInt(year) + 543;
			}
			var onemonth = n + '-' + year;
			m = n;
		}

		function find_indexMonth2(year) 
		{
			var monthArray = new Array();
			var language = '<?php echo $language ?>';
			if (language == 'en') 
			{
				monthArray[0] = "January";
				monthArray[1] = "February";
				monthArray[2] = "March";
				monthArray[3] = "April";
				monthArray[4] = "May";
				monthArray[5] = "June";
				monthArray[6] = "July";
				monthArray[7] = "August";
				monthArray[8] = "September";
				monthArray[9] = "October";
				monthArray[10] = "November";
				monthArray[11] = "December";
			}
			else
			{
				monthArray[0] = "มกราคม";
				monthArray[1] = "กุมภาพันธ์";
				monthArray[2] = "มีนาคม";
				monthArray[3] = "เมษายน";
				monthArray[4] = "พฤษภาคม";
				monthArray[5] = "มิถุนายน";
				monthArray[6] = "กรกฎาคม";
				monthArray[7] = "สิงหาคม";
				monthArray[8] = "กันยายน";
				monthArray[9] = "ตุลาคม";
				monthArray[10] = "พฤศจิกายน";
				monthArray[11] = "ธันวาคม";
			}
			var date = new Date();
			var nowMonth = monthArray[date.getMonth()];
			var nextMonth = monthArray[date.getMonth() + 1];
			if (language == 'th') 
			{
				var year = parseInt(year) + 543;
			}
		}

		function showdate() 
		{
			var language = '<?php echo $language ?>';
			var chkday = $('#chkday:checked').val();
			var chkmonth = $('#chkmonth:checked').val();
			var chkyear = $('#chkyear:checked').val();
			var typeReport = $('#typereport').val();
			if (chkday == 1) 
			{
				$('#showday').show();
				$('#myDay').show();
				$('#showmonth').hide();
				$('#showyear').hide();
				$('#myMonth').hide();
			}
			else if (chkmonth == 2)
			{
				var Date = currentDate.split('-');
				find_indexMonth(Date[0]);
				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').show();
				$('#myMonth').show();
				$('#showyear').hide();
			}
			else if (chkyear == 3)
			{
				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').hide();
				$('#myMonth').hide();
				$('#showyear').show();
			}
		}

		function formatdate(chk) 
		{
			var language = '<?php echo $language ?>';
			if (chk == 1) 
			{
				$('#oneday').show();
				$('#someday').hide();
				$('#textto').hide();
			}
			else if (chk == 2)
			{
				$('#oneday').show();
				$('#someday').show();
				$('#textto').show();
			}
		}

		function formatmonth(chk) 
		{
			if (chk == 1) 
			{
				$('#onemonth').show();
				$('#somemonth').hide();
				$('#textto2').hide();
			}
			else if (chk == 2)
			{
				$('#onemonth').show();
				$('#somemonth').show();
				$('#textto2').show();
				var date = currentDate.split('-');
				find_indexMonth2(date[0]);
			}
		}

		function Blankinput() 
		{
			$('.checkblank').each(function() 
			{
				$(this).val("");
			});

			var typeReport = $('#typereport').val();
			var factory = $('#factory').val();
			var department = $('#department').val();
			var hotpital = $('#hotpital').val();
			var cycle = $('#cycle').val();
			var ppu = $('#PPU').val();
			var Format = $("input[name='radioFormat']:checked").val();
			var oneday = $('#oneday').val();
			var someday = $('#someday').val();
			var onemonth = $('#onemonth').val();
			var somemonth = $('#somemonth').val();
			var year = $('#year').val();
			var FormatDay = $("input[name='formatDay']:checked").val();
			var FormatMonth = $("input[name='formatMonth']:checked").val();
			var chkday = $('#chkday:checked').val();
			if (Format == 1)
			{
				if (FormatDay == 1) 
				{
					if (oneday == null || oneday == '' || oneday == undefined) 
					{
						$('#oneday').addClass('border-danger');
						$('#rem7').show();
						$('#rem7').css('color', 'red');
					}
				}
				else if (FormatDay == 2)
				{
					if (oneday == null || oneday == '' || oneday == undefined)
					{
						$('#oneday').addClass('border-danger');
						$('#rem7').show();
						$('#rem7').css('color', 'red');
					}
					if (someday == '' || someday == undefined || someday == undefined)
					{
						$('#someday').addClass('border-danger');
						$('#rem8').show();
						$('#rem8').css('color', 'red');

					}
				}
			}
			if (Format == 2)
			{
				if (FormatMonth == 1)
				{
					if (onemonth == null || onemonth == '' || onemonth == undefined)
					{
						$('#onemonth').addClass('border-danger');
						$('#rem9').show();
						$('#rem9').css('color', 'red');

					}
				}
				else if (FormatMonth == 2)
				{
					if (onemonth == null || onemonth == '' || onemonth == undefined)
					{
						$('#onemonth').addClass('border-danger');
						$('#rem9').show();
						$('#rem9').css('color', 'red');
					}
					if (somemonth == '' || someday == undefined || someday == undefined)
					{
						$('#somemonth').addClass('border-danger');
						$('#rem10').show();
						$('#rem10').css('color', 'red');

					}
				}
			}
			if (Format == 3)
			{
				if (year == null || year == '' || year == undefined)
				{
					$('#year').addClass('border-danger');
					$('#rem11').show();
					$('#rem11').css('color', 'red');

				}
			}
			if (typeReport == 4)
			{
				if (cycle == '0')
				{
					$('#cycle').addClass('border-danger');
					$('#rem5').show();
					$('#rem5').css('color', 'red');
				}
			}
			if (typeReport == 13)
			{
				if (cycle == '0')
				{
					$('#PPU').addClass('border-danger');
					$('#rem6').show();
					$('#rem6').css('color', 'red');
				}
			}
			if (typeReport == '0')
			{
				$('#typereport').addClass('border-danger');
				$('#rem1').show();
				$('#rem1').css('color', 'red');
			}

			if (typeReport == 1 || typeReport == 2 || typeReport == 3 || typeReport == 5 || typeReport == 6 || typeReport == 8 || typeReport == 13 || typeReport == 15) 
			{
				if (factory == '' || factory == undefined || factory == '0')
				{
					$('#factory').addClass('border-danger');
					$('#rem2').show();
					$('#rem2').css('color', 'red');
				}
			}
			if (typeReport == 4 || typeReport == 9 || typeReport == 14 || typeReport == 16 || typeReport == 10 || typeReport == 11 || typeReport == 12 || typeReport == 29 || typeReport == 30)
			{
				if (department == '' || department == undefined || department == '0') 
				{
					$('#department').addClass('border-danger');
					$('#rem4').show();
					$('#rem4').css('color', 'red');
				}
			}
			if (typeReport == '0' || typeReport == 2 || typeReport == 3 || typeReport == 4 || typeReport == 5 || typeReport == 7 || typeReport == 9 || typeReport == 10 || typeReport == 11 || typeReport == 12 || typeReport == 13 || typeReport == 14 || typeReport == 16 || typeReport == 29 || typeReport == 30)
			{
				if (hotpital == '' || hotpital == undefined || hotpital == '0')
				{
					$('#hotpital').addClass('border-danger');
					$('#rem3').show();
					$('#rem3').css('color', 'red');
				}
			}
		}
		
		function blank_fac() 
		{
			$('#factory').removeClass('border-danger');
			$('#rem2').hide();
		}
		function blank_dep() 
		{
			$('#department').removeClass('border-danger');
			$('#rem4').hide();
			var DepCode = $('#department').val();
			var hotpital = $('#hotpital').val();
			var typereport = $('#typereport').val();
			if (typereport == '29' || typereport == '30'|| typereport == '34') {
					$('#item').attr('disabled', false);
			}
			if(DepCode == null)
			{
				DepCode = 'ALL';
			}
			var data = {
					'STATUS': 'find_item',
					'DepCode': DepCode,
					'hotpital': hotpital
				};
			senddata(JSON.stringify(data));
		}
		function blank_report() 
		{
			$('#typereport').removeClass('border-danger');
			$('#rem1').hide();
		}
		function blank_hot() 
		{
			$('#hotpital').removeClass('border-danger');
			$('#rem3').hide();
		}
		function blank_PPU() 
		{
			$('#PPU').removeClass('border-danger');
			$('#rem6').hide();
		}
		function oneday() 
		{
			$('#oneday').removeClass('border-danger');
			$('#rem7').hide();
		}
		function someday() 
		{
			$('#someday').removeClass('border-danger');
			$('#rem8').hide();
		}
		function onemonth() 
		{
			$('#onemonth').removeClass('border-danger');
			$('#rem9').hide();
		}
		function somemonth() 
		{
			$('#somemonth').removeClass('border-danger');
			$('#rem10').hide();
		}
		function year() 
		{
			$('#year').removeClass('border-danger');
			$('#rem11').hide();
		}
		function show_item() 
		{
			var DepCode = $('#department').val();
			if (DepCode == ALL) {
				$('#item').attr('disabled', FALSE);
			} else {
				$('#item').attr('disabled', TRUE);
			}
		}
		function search_fillter() 
		{
			swal({
								title: '<?php echo $array['pleasewait'][$language]; ?>',
								text: '<?php echo $array['processing'][$language]; ?>',
								allowOutsideClick: false
							})
							swal.showLoading();
			var time_express = $('#time_express').val();
			var factory = $('#factory').val();
			var HptCode = $('#hotpital').val();
			var typeReport = $('#typereport').val();
			var DepCode = $('#department').val();
			var category = $('#category').val();
			var cycle = $('#cycle').val();
			var ppu = $('#PPU').val();
			var oneday = $('#oneday').val();
			var someday = $('#someday').val();
			var onemonth = $('#onemonth').val();
			var type_usage_detail = $('#type_usage_detail').val();
			var somemonth = $('#somemonth').val();
			var GroupCode = $('#grouphpt').val();
			var Item = $('#item').val();
			var time_dirty = $('#time_dirty').val();
			var year = $('#year').val();
			var Format = $("input[name='radioFormat']:checked").val();
			var FormatDay = $("input[name='formatDay']:checked").val();
			var FormatMonth = $("input[name='formatMonth']:checked").val();
			var chkday = $('#chkday:checked').val();
			if (Format == 1) 
			{
				if (FormatDay == 1) 
				{
					if (oneday == null || oneday == '' || oneday == undefined) 
					{
						swal({
							title: '',
							text: '<?php echo $array['insert_date'][$language]; ?>',
							type: 'info',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							showConfirmButton: false,
							timer: 1000,
							confirmButtonText: 'Ok'
						});
						Blankinput()
					}
				} 
				else if (FormatDay == 2) 
				{
					if (oneday == null || oneday == '' || oneday == undefined || someday == '' || someday == undefined || someday == undefined) {
						swal({
							title: '',
							text: '<?php echo $array['insert_date'][$language]; ?>',
							type: 'info',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							showConfirmButton: false,
							timer: 1000,
							confirmButtonText: 'Ok'
						});
						Blankinput()
					}
				}
			}
			if (Format == 2) 
			{
				if (FormatMonth == 1) 
				{
					if (onemonth == null || onemonth == '' || onemonth == undefined) 
					{
						swal({
							title: '',
							text: '<?php echo $array['insert_date'][$language]; ?>',
							type: 'info',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							showConfirmButton: false,
							timer: 1000,
							confirmButtonText: 'Ok'
						});
						Blankinput()
					}
				} else if (FormatMonth == 2) 
				{
					if (onemonth == null || onemonth == '' || onemonth == undefined || somemonth == '' || someday == undefined || someday == undefined) 
					{
						swal({
							title: '',
							text: '<?php echo $array['insert_date'][$language]; ?>',
							type: 'info',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							showConfirmButton: false,
							timer: 1000,
							confirmButtonText: 'Ok'
						});
						Blankinput()
					}
				}
			}
			if (Format == 3) 
			{
				if (year == null || year == '' || year == undefined) 
				{
					swal({
						title: '',
						text: '<?php echo $array['insert_date'][$language]; ?>',
						type: 'info',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
					Blankinput()
				}
			}
			if (typeReport != 9 && typeReport != 20) 
			{
				if (Format == '' || Format == undefined) 
				{
					swal({
						title: '',
						text: '<?php echo $array['insert_date'][$language]; ?>',
						type: 'info',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
					Blankinput()
				}
			}
			if (typeReport == 0) 
			{
				swal({
					title: '',
					text: '<?php echo $array['insert_form'][$language]; ?>',
					type: 'info',
					showCancelButton: false,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					showConfirmButton: false,
					timer: 1000,
					confirmButtonText: 'Ok'
				});

			}
			if (typeReport == 1 || typeReport == 2 || typeReport == 3 || typeReport == 6 || typeReport == 8 || typeReport == 13 || typeReport == 15) 
			{
				if (factory == '' || factory == undefined || factory == 0) 
				{
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'info',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
					Blankinput()
				}
			}
			if (typeReport == 13) 
			{
				if (ppu == '' || ppu == undefined || ppu == 0) 
				{
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'info',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
					Blankinput()
				}
			}
			if (typeReport == 4 || typeReport == 14 || typeReport == 9 || typeReport == 16 || typeReport == 10 || typeReport == 11 || typeReport == 12 || typeReport == 29 || typeReport == 30) 
			{
				if (DepCode == '' || DepCode == undefined || DepCode == 0) 
				{
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'info',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
					Blankinput()
				}
			}
			if (typeReport == 2 || typeReport == 3 || typeReport == 4 || typeReport == 5 || typeReport == 7 || typeReport == 9 || typeReport == 10 || typeReport == 11 || typeReport == 12 || typeReport == 13 || typeReport == 14 || typeReport == 16 || typeReport == 29 || typeReport == 30) 
			{
				if (HptCode == '' || HptCode == undefined) 
				{
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'info',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
					Blankinput()
				}
			}

			if (Format == 1) 
			{
				var FormatDay = $("input[name='formatDay']:checked").val();
				var language = '<?php echo $language ?>';

				if (FormatDay == 1) 
				{
					var date = $('#oneday').val();
					day = date;
					var dmy = date.split('-');
					if (language == 'th') 
					{
						var year1 = parseInt(dmy[2]) - 543;
						date = year1 + "-" + dmy[1] + "-" + dmy[0];
					} 
					else 
					{
						date = dmy[2] + "-" + dmy[1] + "-" + dmy[0];
					}

				} 
				else 
				{
					var one = $('#oneday').val();
					var some = $('#someday').val();
					if (language == 'th') 
					{
						var day1 = one.split('-');
						var day2 = some.split('-');
						var year1 = parseInt(day1[2]) - 543;
						var year2 = parseInt(day2[2]) - 543;
						many_day = day1[0] + "/" + day1[1] + "/" + day1[2] + "-" + day2[0] + "/" + day2[1] + "/" + day2[2];
						date = year1 + "/" + day1[1] + "/" + day1[0] + "-" + year2 + "/" + day2[1] + "/" + day2[0];
					} 
					else 
					{
						var day1 = one.split('-');
						var day2 = some.split('-');
						var year1 = parseInt(day1[2]);
						var year2 = parseInt(day2[2]);
						date = year1 + "/" + day1[1] + "/" + day1[0] + "-" + year2 + "/" + day2[1] + "/" + day2[0];
						many_day = day1[2] + "/" + day1[1] + "/" + day1[0] + "-" + day2[2] + "/" + day2[1] + "/" + day2[0];
					}
					var chkDateRang = date.split('-');
					if (chkDateRang[0] == null || chkDateRang[0] == undefined || chkDateRang[1] == null || chkDateRang[1] == undefined) 
					{
						swal({
							title: '',
							text: '<?php echo $array['insert_form'][$language]; ?>',
							type: 'info',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							showConfirmButton: false,
							timer: 1000,
							confirmButtonText: 'Ok'
						});
						setTimeout(function() {
							$('#someday').focus();
						}, 1000);
					}

				}
				
				var data =
				{
					'STATUS': 'find_report',
					'factory': factory,
					'HptCode': HptCode,
					'DepCode': DepCode,
					'typeReport': typeReport,
					'category': category,
					'Format': Format,
					'FormatDay': FormatDay,
					'cycle': cycle,
					'ppu': ppu,
					'date': date,
					'GroupCode': GroupCode,
					'Item': Item,
					'time_dirty': time_dirty,
					'time_express': time_express,
					'type_usage_detail': type_usage_detail

					
				};
			} 
			else if (Format == 2) 
			{
				var language = '<?php echo $language ?>';
				var FormatMonth = $("input[name='formatMonth']:checked").val();
				if (FormatMonth == 1) 
				{
					var date = $('#onemonth').val();
					month = date;
					var date1 = date.split('-');
					if (language == "th") 
					{
						var year = parseInt(date1[1]) - 543;
						date = date1[0] + "-" + year;
					}
				}
				else 
				{
					var one = $('#onemonth').val();
					var some = $('#somemonth').val();
					many_month = one + "/" + some;
					if (language == "th") 
					{
						var month1 = one.split('-');
						var month2 = some.split('-');
						var m1 = parseInt(month1[1]) - 543;
						var m2 = parseInt(month2[1]) - 543;
						date = month1[0] + "/" + m1 + "-" + month2[0] + "/" + m2;
					}
					else
					{
						var month1 = one.split('-');
						var month2 = some.split('-');
						var m1 = parseInt(month1[1]);
						var m2 = parseInt(month2[1]);
						date = month1[0] + "/" + m1 + "-" + month2[0] + "/" + m2;
					}

				}

				var data = 
				{
					'STATUS': 'find_report',
					'factory': factory,
					'HptCode': HptCode,
					'typeReport': typeReport,
					'category': category,
					'FormatMonth': FormatMonth,
					'DepCode': DepCode,
					'Format': Format,
					'cycle': cycle,
					'ppu': ppu,
					'date': date,
					'GroupCode': GroupCode,
					'Item': Item,
					'time_dirty': time_dirty,
					'time_express': time_express,
					'type_usage_detail': type_usage_detail
				}
			} 
			else 
			{
				var language = '<?php echo $language ?>';
				var date = $('#year').val();
				show_year = date;
				if (language == 'th') 
				{
					date = Number(date) - 543;
				}
				var data = 
				{
					'STATUS': 'find_report',
					'factory': factory,
					'HptCode': HptCode,
					'DepCode': DepCode,
					'category': category,
					'typeReport': typeReport,
					'Format': Format,
					'cycle': cycle,
					'ppu': ppu,
					'date': date,
					'GroupCode': GroupCode,
					'Item': Item,
					'time_dirty': time_dirty,
					'time_express': time_express,
					'type_usage_detail': type_usage_detail
				};
			}

			senddata(JSON.stringify(data));
		}
		function departmentWhere() 
		{
			$('#PPU').val(0);
			var HptCode = $('#hotpital').val();
			var GroupCode = $('#grouphpt').val();
			var data = {
				'STATUS': 'departmentWhere',
				'HptCode': HptCode,
				'GroupCode': GroupCode
			};
			senddata(JSON.stringify(data));
		}
		function send_data(data) 
		{
			var dataSend = $('#data').val();
			var URL = data + '?data=' + dataSend; //your url send_from process process/report.php
			window.open(URL);
		}
		function send_data2(data) 
		{
			var dataSend = $('#data').val();
			var URL = data //your url send_from process process/report.php
			window.open(URL);
		}
		function send_data3(data) 
		{
			var dataSend = $('#data').val();
			var URL = data + '&data=' + dataSend; //your url send_from process process/report.php
			window.open(URL);
		}
		//============================================ Close Function ======================================================

		//============================================ Display  ======================================================
		function senddata(data) 
		{
			var form_data = new FormData();
			form_data.append("DATA", data);
			var URL = '../process/report.php';
			$.ajax({
				url: URL,
				dataType: 'text',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function(result) {
					try {
						var temp = $.parseJSON(result);
					} catch (e) {
						console.log('Error#542-decode error');
					}

					if (temp["status"] == 'success') {
						if (temp["form"] == 'OnLoadPage') {
							var PmID = '<?php echo $PmID; ?>';
							var HptCode = '<?php echo $HptCode ?>';
							var FacCode = '<?php echo $FacCode ?>';
							var DepCode = '<?php echo $DepCode ?>';
							var HptName = '<?php echo $HptName ?>';
							var GroupCode = '<?php echo $GroupCode ?>';
							var typereport = $('#typereport').val();

							$("#factory").empty();
							$("#department").empty();
							$("#category").empty();
							$("#hotpital").empty();
							$("#item").empty();



							var facValue0 = '-';
							var fac = "<option value='0'>" + facValue0 + "</option>";
							for (var i = 0; i < temp['Rowx']; i++) {
								fac += "<option value=" + temp[i]['FacCode'] + ">" + temp[i]['FacName'] + "</option>";
							}
							$("#factory").append(fac);


							$("#hotpital").empty();
							var hotValue0 = '<?php echo $array['pleasehot'][$language]; ?>';
							var hot = "<option value='' id='hotpitalX'>" + hotValue0 + "</option>";
							for (var i = 0; i < temp['Row']; i++) {
								hot += "<option value=" + temp[i]['HptCode'] + " id='selectHpt" + i + "'>" + temp[i]['HptName'] + "</option>";
							}
							$("#hotpital").append(hot);
							$("#hotpital").val(HptCode);

							$("#department").empty();

							// var depValue0 = '-';
							// var dep1 = "<option value='0'>" + depValue0 + "</option>";
							// dep1 += "<option value='all'>" + 'ทั้งหมด' + "</option>";
							var depValue0 = '<?php echo $array['department'][$language]; ?>';
							var depValueAlldep = '<?php echo $array['Alldep'][$language]; ?>';
							var dep1 = "<option value='ALL'>" + depValueAlldep + "</option>";
							for (var i = 0; i < temp['RowDep']; i++) {
								dep1 += "<option value=" + temp[i]['DepCode'] + " id='select_" + i + "'>" + temp[i]['DepName'] + "</option>";
							}
							$("#department").html(dep1);

							$("#grouphpt").empty();
							var groupValue0 = '<?php echo $array['Allgroup'][$language]; ?>';
							var grouphpt = "<option value='0'>" + groupValue0 + "</option>";
							for (var i = 0; i < temp['RowG']; i++) {
								grouphpt += "<option value=" + temp[i]['GroupCode'] + " id='select_" + i + "'>" + temp[i]['GroupName'] + "</option>";
							}
							$("#grouphpt").append(grouphpt);
							$("#grouphpt").val(0);

							var time_dirty_Value0 = '<?php echo $array['Alldirty'][$language]; ?>';
							var time_dirty = "<option value='0'>" + time_dirty_Value0 + "</option>";
							for (var i = 0; i < temp['count_time_dirty']; i++) {
								time_dirty += "<option value=" + temp[i]['id'] + ">" + temp[i]['TimeName'] + "</option>";
							}
							$("#time_dirty").append(time_dirty);
							
							var categoryx = '<?php echo $array['AllCatsub'][$language]; ?>';
							var category_value = "<option value='0'>" + categoryx + "</option>";
							for (var i = 0; i < temp['count_category']; i++) {
								category_value += "<option value=" + temp[i]['CategoryCode'] + ">" + temp[i]['CategoryName'] + "</option>";
							}
							$("#category").append(category_value);
						} else if (temp["form"] == 'departmentWhere') {
							var typereport = $('#typereport').val();
							$("#department").empty();
							$("#factory").empty();
							$("#time_dirty").empty();
							$("#time_express").empty();
							
							var time_ex = "<option value='ALL' selected>ทุกรอบ</option>";
                      for (var i = 0; i <  temp['Rowtimeex'];  i++) {
												time_ex += "<option value="+temp[i]['ID']+">"+temp[i]['time_value']+"</option>";
                      }
                      time_ex += "<option value='0' >Extra</option>";
                      $("#time_express").append(time_ex);

							var depValueAlldep = '<?php echo $array['Alldep'][$language]; ?>';
							// var depValue0 = '<?php echo $array['department'][$language]; ?>';
							// var dep2 = "<option value='0'>" + depValue0 + "</option>";
							var dep2 = "<option value='ALL'>" + depValueAlldep + "</option>";
							for (var i = 0; i < temp['Row']; i++) {
								dep2 += "<option value=" + temp[i]['DepCode'] + " id='select_" + i + "'>" + temp[i]['DepName'] + "</option>";
							}
							$("#department").html(dep2);


							var time_dirty_Value0 = '<?php echo $array['Alldirty'][$language]; ?>';
							var time_dirty = "<option value='0'>" + time_dirty_Value0 + "</option>";
							for (var i = 0; i < temp['count_time_dirty']; i++) {
								time_dirty += "<option value=" + temp[i]['id'] + ">" + temp[i]['TimeName'] + "</option>";
							}
							$("#time_dirty").append(time_dirty);

							var facValue0 = '-';
							var fac = "<option value='0'>" + facValue0 + "</option>";
							for (var i = 0; i < temp['Rowfac']; i++) {
								fac += "<option value=" + temp[i]['FacCode'] + ">" + temp[i]['FacName'] + "</option>";
							}
							$("#factory").append(fac);
							var DocDate = temp[i]['DocDate'];
							blank_dep() ;
						} else if (temp["form"] == 'Fac') {
							swal.close();
							var hot = $("#hotpital option:selected").text();
							$('#data').val(Object.values(temp['data_send']));
							// alert('fac');
							var print = '<?php echo $array['prin'][$language]; ?>';
							var show_date = '';
							var format = temp['Format'];
							var chk = temp['chk'];
							var Excel = 'XLS';
							var Pdf = 'PDF';
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_Fac tbody').empty();
							$('#table_Fac').attr('hidden', false);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_category').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							if (temp['r'] == 'r23') {
								for (var i = 0; i < temp['countRow']; i++) {
									var dataRow = "<tr>" +
										"<td style='width:5%'>" + (i + 1) + "</td>" +
										"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
										"<td class='text-center pl-4' style='width:35%'>" + temp[i]['FacName'] + "</td>" +
										"<td class='text-center' style='width:20%'>" + show_date + "</td>";
									dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data3(\"" + temp['url'] + "?Fac=" + temp[i]['FacCode'] + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>" +
										"<td class='text-center' style='width:7.5%'><button  onclick='send_data3(\"" + temp['urlxls'] + "?Fac=" + temp[i]['FacCode'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									"</tr>";
									$("#table_Fac tbody").append(dataRow);
								}
							} else {
								for (var i = 0; i < temp['countRow']; i++) {
									var dataRow = "<tr>" +
										"<td style='width:5%'>" + (i + 1) + "</td>" +
										"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
										"<td class='text-center pl-4' style='width:35%'>" + temp[i]['FacName'] + "</td>" +
										"<td class='text-center' style='width:20%'>" + show_date + "</td>";
									dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>" +
										"<td class='text-center' style='width:7.5%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									"</tr>";
									$("#table_Fac tbody").append(dataRow);
								}
							}

						} else if (temp["form"] == 'Dep') {
							swal.close();
							$('#data').val(Object.values(temp['data_send']));
							var hot = $("#hotpital option:selected").text();
							var department = $("#department option:selected").text();
							if (temp["item"] != '0') {
								var grouphpt = $("#grouphpt option:selected").text();
								var grouphpt = '( ' + grouphpt + ' )';
							} else {
								var grouphpt = '';
							}
							var print = '<?php echo $array['prin'][$language]; ?>';
							var Excel = 'XLS';
							var Pdf = 'PDF';
							var show_date = '-';
							var format = temp['Format'];
							var chk = temp['chk'];
							var Dep10 = '';
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_Dep tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', false);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_category').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							if (temp['statusDep'] == 'somedepartment') {
								for (var i = 0; i < temp['countRow']; i++) {
									var dataRow = "<tr>" +
										"<td style='width:5%'>" + (i + 1) + "</td>" +
										"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
										"<td class='text-center pl-4' style='width:35%'>" + department + grouphpt + "</td>" +
										"<td class='text-center' style='width:20%'>" + show_date + "</td>";
									if (temp['r'] == 'r29' || temp['r'] == 'r30' || temp['r'] == '9') {
										dataRow += "<td class='text-center' style='width:15%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									} else {
										dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>" +
											"<td class='text-center' style='width:7.5%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									}
									"</tr>";
									$("#table_Dep tbody").append(dataRow);
								}
							}
							if (temp['statusDep'] == 'alldepartment') {
								var rows = temp['department'].length;

								var r1 = rows / 10;
								r1 = parseInt(r1);
								var r2 = rows % 10;
								var round = 0;
								// console.log(rows);
								// console.log(r1);
								// console.log(r2);

								for (var i = 0; i < r1; i++) {
									var dataRow = "<tr>" +
										"<td style='width:5%'>" + (i + 1) + "</td>" +
										"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>";
									dataRow += "<td class='text-center pl-4' style='width:35%'>"
									for (var j = 1; j < 11; j++) {
										dataRow += j + '  ' + temp['department'][round]['DepName'] + ' <br> ';
										Dep10 += temp['department'][round]['DepCode'];
										if (j != 10) {
											Dep10 += ',';
										}
										round++;

									}

									dataRow += "</td>";
									dataRow += "<td class='text-center' style='width:20%'>" + show_date + "</td>";
									if (temp['r'] == 'r7') {
										dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data3(\"" + temp['url'] + "?Dep10=" + Dep10 + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>";
										dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data3(\"" + temp['urlxls'] + "?Dep10=" + Dep10 + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									} else {
										dataRow += "<td class='text-center' style='width:15%'><button  onclick='send_data3(\"" + temp['urlxls'] + "?Dep10=" + Dep10 + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									}
									dataRow += "</tr>";
									$("#table_Dep tbody").append(dataRow);
									Dep10 = '';
								}
								// round--;
								console.log(round);
								if (r2 > 0) {
									if (round == -1) {
										round = 0;
									}
									console.log(r2);

									var dataRow = "<tr>" +
										"<td style='width:5%'>" + (i + 1) + "</td>" +
										"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>";
									dataRow += "<td class='text-center pl-4' style='width:35%'>"
									for (var j = 1; j <= r2; j++) {
										dataRow += j + '  ' + temp['department'][round]['DepName'] + ' <br> ';
										Dep10 += temp['department'][round]['DepCode'];
										if (j != r2) {
											Dep10 += ',';
										}
										round++;
									}
									dataRow += "</td>";
									dataRow += "<td class='text-center' style='width:20%'>" + show_date + "</td>";
									if (temp['r'] == 'r7') {
										dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data3(\"" + temp['url'] + "?Dep10=" + Dep10 + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>";
										dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data3(\"" + temp['urlxls'] + "?Dep10=" + Dep10 + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									} else {
										dataRow += "<td class='text-center' style='width:15%'><button  onclick='send_data3(\"" + temp['urlxls'] + "?Dep10=" + Dep10 + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
									}
									dataRow += "</tr>";
									$("#table_Dep tbody").append(dataRow);
									Dep10 = '';
								}
							}
						} else if (temp["form"] == 'DepDoc') {
							swal.close();
							$('#data').val(Object.values(temp['data_send']));
							var hot = $("#hotpital option:selected").text();
							// alert('DepDoc');
							var print = '<?php echo $array['prin'][$language]; ?>';
							var show_date = '';
							var format = temp['Format'];
							var chk = temp['chk'];
							var Excel = 'XLS';
							var Pdf = 'PDF';
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_DepDoc tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', false);
							$('#table_Group').attr('hidden', true);
							$('#table_category').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
									"<td class='text-center pl-4' style='width:20%'>" + temp[i]['DepName'] + "</td>" +
									"<td class='text-center pl-4' style='width:15%'>" + temp[i]['DocNo'] + "</td>" +
									"<td class='text-center' style='width:20%'>" + temp[i]['DocDate'] + "</td>" +
									"<td class='text-center text-center' style='width:7.5%'><button onclick='send_data2(\"" + temp['url'] + "?Docno=" + temp[i]['DocNo'] + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'; width : 10 px ;><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>" +
									"<td class='text-center text-center' style='width:7.5%'><button onclick='send_data2(\"" + temp['urlxls'] + "?Docno=" + temp[i]['DocNo'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px; width : 10 px ;'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>" +
									"</tr>";
								$("#table_DepDoc tbody").append(dataRow);
							}
						} else if (temp["form"] == 'Group') {
							swal.close();
							$('#data').val(Object.values(temp['data_send']));
							var hot = $("#hotpital option:selected").text();
							var Group = $("#grouphpt option:selected").text();
							// alert('Group');
							var print = '<?php echo $array['prin'][$language]; ?>';
							var show_date = '';
							var format = temp['Format'];
							var Excel = 'XLS';
							var Pdf = 'PDF';
							var chk = temp['chk'];
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_Group tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', false);
							$('#table_category').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
									"<td class='text-center pl-4' style='width:35%'>" + Group + "</td>" +
									"<td class='text-center' style='width:20%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:15%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>" +
									"</tr>";
								$("#table_Group tbody").append(dataRow);
							}
						} else if (temp["form"] == 'usage_detail') {
							swal.close();
							$('#data').val(Object.values(temp['data_send']));
							var hot = $("#hotpital option:selected").text();
							var department = $("#department option:selected").text();
							// alert('Group');
							var print = '<?php echo $array['prin'][$language]; ?>';
							var show_date = '';
							var format = temp['Format'];
							var Excel = 'XLS';
							var Pdf = 'PDF';
							var chk = temp['chk'];
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_usage_detail tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', false);
							$('#table_Group').attr('hidden', true);
							$('#table_category').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
									"<td class='text-center pl-4' style='width:35%'>" + department + "</td>" +
									"<td class='text-center' style='width:20%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:15%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>" +
									"</tr>";
								$("#table_usage_detail tbody").append(dataRow);
							}
						}  else if (temp["form"] == 'category') {
							swal.close();
							$('#data').val(Object.values(temp['data_send']));
							var hot = $("#hotpital option:selected").text();
							var category = $("#category option:selected").text();
							var print = '<?php echo $array['prin'][$language]; ?>';
							var show_date = '';
							var format = temp['Format'];
							var Excel = 'XLS';
							var Pdf = 'PDF';
							var chk = temp['chk'];
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_Group tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_category').attr('hidden', false);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:25%'>" + hot + "</td>" +
									"<td class='text-center pl-4' style='width:35%'>" + category + "</td>" +
									"<td class='text-center' style='width:20%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:15%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>" +
									"</tr>";
								$("#table_category tbody").html(dataRow);
							}
						} else if (temp["form"] == 'NoFacDep') {
							swal.close();
							$('#data').val(Object.values(temp['data_send']));
							// alert('NoFacDep');
							var hot = $("#hotpital option:selected").text();
							var print = '<?php echo $array['prin'][$language]; ?>';
							var show_date = '';
							var Excel = 'XLS';
							var Pdf = 'PDF';
							var format = temp['Format'];
							var chk = temp['chk'];
							if (format == 1) {
								if (chk == 'one') {
									show_date = day;
								} else {
									show_date = many_day;
								}
							} else if (format == 2) {
								if (chk == 'month') {
									show_date = month;
								} else {
									show_date = many_month;
								}
							} else if (format == 3) {
								show_date = show_year;
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_NoFacDep tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', false);
							$('#table_category').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:60%'>" + hot + "</td>" +
									"<td class='text-center' style='width:20%'>" + show_date + "</td>";
								if (temp['r'] == 'r23' || temp['r'] == 'r24' || temp['r'] == 'r7') {
									dataRow += "<td class='text-center' style='width:7.5%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>" +
										"<td class='text-center' style='width:7.5%'><button  onclick='send_data(\"" + temp['urlxls'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
								} else if (temp['r'] == 'r18') {
									dataRow += "<td class='text-center' style='width:15%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Pdf + "</button></td>";
								} else {
									dataRow += "<td class='text-center' style='width:15%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-success btn-sm' style='font-size:20px!important;padding : 4px'><i class='fas fa-print mr-2'></i>" + Excel + "</button></td>";
								}
								"</tr>";
								$("#table_NoFacDep tbody").append(dataRow);
							}
						}else if (temp["form"] == 'find_item') {
							var itemValue0 = '-';
							$("#item").empty();
							var item = "<option value='0'>" + itemValue0 + "</option>";
							for (var i = 0; i < temp['count_item_sc']; i++) {
								item += "<option value=" + temp[i]['itemcode'] + ">" + temp[i]['itemname'] + "</option>";
							}
							$("#item").append(item);
						}
					} else if (temp['status'] == "notfound") {
						$('#type_report').text(temp['typeReport']);
						if (temp["form"] == 'Fac') {
								swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_Fac tbody').empty();
							$('#table_category').attr('hidden', true);
							$('#table_Fac').attr('hidden', false);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_Fac tbody").append(dataRow);
						} else if (temp["form"] == 'Dep') {
							swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_Dep tbody').empty();
							$('#table_category').attr('hidden', true);
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', false);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_Dep tbody").append(dataRow);
						} else if (temp["form"] == 'Depdoc') {
							swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_DepDoc tbody').empty();
							$('#table_category').attr('hidden', true);
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', false);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_DepDoc tbody").append(dataRow);
						} else if (temp["form"] == 'Group') {
							swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_Group tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', false);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_category').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_Group tbody").append(dataRow);
						} else if (temp["form"] == 'category') {
							swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_category tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_category').attr('hidden', false);
							$('#table_usage_detail').attr('hidden', true);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_category tbody").append(dataRow);
						}  else if (temp["form"] == 'NoFacDep') {
							swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_NoFacDep tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', false);
							$('#table_category').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', true);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_NoFacDep tbody").append(dataRow);
						} else if (temp["form"] == 'usage_detail') {
							swal({
										title: '',
										text: 'ไม่พบข้อมูล : '+ temp['typeReport'] +' ',
										type: 'warning',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										cancelButtonColor: '#d33',
										showConfirmButton: false,
										timer: 2000,
										confirmButtonText: 'Ok'
									});
							$('#table_NoFacDep tbody').empty();
							$('#table_Fac').attr('hidden', true);
							$('#table_Dep').attr('hidden', true);
							$('#table_DepDoc').attr('hidden', true);
							$('#table_Group').attr('hidden', true);
							$('#table_NoFacDep').attr('hidden', true);
							$('#table_category').attr('hidden', true);
							$('#table_usage_detail').attr('hidden', false);
							var dataRow = "<tr><td style='width:100%' class='text-center'><?php echo $array['notfoundDoc'][$language]; ?></td></tr>";
							$("#table_NoFacDep tbody").append(dataRow);
						}

					} else {
						console.log(temp['msg']);
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
					alert(err);
				}
			});
		}
		function disabled_fill() 
		{
			departmentWhere();
			var typeReport = $('#typereport').val();
			var PmID = '<?PHP echo  $PmID ?>';
			if (PmID == 1 || PmID == 6) {
				var tf = false;
			} else {
				var tf = true;
			}
			if (typeReport == 1 || typeReport == 3 || typeReport == 5 || typeReport == 6 || typeReport == 15 || typeReport == 8 || typeReport == 22 || typeReport == 24) {
				$('#hotpital').attr('disabled', tf);
				$('#department').attr('disabled', true);
				$('#factory').attr('disabled', false);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().show();
				$('#chkyear').parent().show();
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			} else if (typeReport == 14 || typeReport == 16) {
				$('#department').attr('disabled', false);
				$('#factory').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#hidden_usage_detail').attr('hidden', true);
				$('#time_express').attr('disabled', true);
			} else if (typeReport == 9) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', false);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			} else if (typeReport == 7 || typeReport == 10 || typeReport == 11 || typeReport == 12 || typeReport == 19 || typeReport == 18 || typeReport == 20 || typeReport == 21 || typeReport == 23 || typeReport == 25 || typeReport == 26 || typeReport == 27) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			} else if (typeReport == 2) {
				$('#factory').attr('disabled', false);
				$('#department').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#category').attr('disabled', true);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			}else if (typeReport == 29 ) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', false);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', false);
				$('#item').attr('disabled', false);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().hide();
				$('#chkyear').parent().hide();
				$('#time_express').attr('disabled', false);
				$('#hidden_usage_detail').attr('hidden', true);
				blank_dep();
			} else if ( typeReport == 30) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', false);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', false);
				$('#item').attr('disabled', false);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().hide();
				$('#chkyear').parent().hide();
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
				blank_dep();
			} else if ( typeReport == 34) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', false);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', false);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().hide();
				$('#chkyear').parent().hide();
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', false);
				blank_dep();
			}  else if (typeReport == 28 ) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', false);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().hide();
				$('#chkyear').parent().hide();
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			} else if (typeReport == 33 ) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().hide();
				$('#chkyear').parent().hide();
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			}   else if (typeReport == 31 || typeReport == 32) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', false);
				$('#category').attr('disabled', false);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().hide();
				$('#chkyear').parent().hide();
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			}else if (typeReport == 13) {
				$('#factory').attr('disabled', false);
				$('#department').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			} else if (typeReport == 17) {
				$('#factory').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#time_express').attr('disabled', true);
				$('#hidden_usage_detail').attr('hidden', true);
			}
			if (typeReport == 9) {
				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').hide();
				$('#showyear').hide();
				$('#myMonth').hide();
				$('#chk').hide();
				$('#grouphpt').attr('disabled', false);
				$('#category').attr('disabled', true);
			}
			if (typeReport != 9 && typeReport != 20) {
				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').hide();
				$('#showyear').hide();
				$('#myMonth').hide();
				$('#chk').show();
				$('#chkmonth').prop('checked', false)
				$('#chkday').prop('checked', false)
				$('#chkyear').prop('checked', false)
			}
			if (typeReport == 20 || typeReport == 25 || typeReport == 26 || typeReport == 27) {
				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').hide();
				$('#showyear').hide();
				$('#myMonth').show();
				$('#onemonth').show();
				$('#somemonth').hide();
				$('#textto2').hide();
				$('#chk').hide();
				$('#chkmonth').prop('checked', true);
				$('#chkonemonth').prop('checked', true);
				$('#chkday').prop('checked', false)
				$('#chkyear').prop('checked', false)
				find_indexMonth(new Date().getFullYear())
				// $('#oneMonth').attr('value', onemonth);
			} else if (typeReport == 4) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', false);
				$('#hotpital').attr('disabled', tf);
				$('#cycle').attr('disabled', false);
				$('#grouphpt').attr('disabled', false);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', true);
				$('#category').attr('disabled', true);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
			}
			// else if (typeReport == 28) {
			// 	$('#department').attr('disabled', true);
			// 	$('#factory').attr('disabled', true);
			// 	$('#hotpital').attr('disabled', tf);
			// 	$('#cycle').attr('disabled', true);
			// 	$('#grouphpt').attr('disabled', false);
			// 	$('#grouphpt').val(0);
			// 	$('#factory').val(0);
			// 	$('#department').val(0);
			// 	$('#cycle').val(0);
			// 	$('#showday').hide();
			// 	$('#myDay').hide();
			// 	$('#showmonth').hide();
			// 	$('#showyear').hide();
			// 	$('#myMonth').show();
			// 	$('#onemonth').show();
			// 	$('#somemonth').hide();
			// 	$('#textto2').hide();
			// 	$('#chk').hide();
			// 	$('#chkmonth').prop('checked', true);
			// 	$('#chkonemonth').prop('checked', true);
			// 	$('#chkday').prop('checked', false)
			// 	$('#chkyear').prop('checked', false)
			// 	find_indexMonth(new Date().getFullYear())
			// }
			if (typeReport == 1) {
				$('#hotpital').attr('disabled', tf);
				$('#department').attr('disabled', true);
				$('#factory').attr('disabled', false);
				$('#cycle').attr('disabled', true);
				$('#grouphpt').attr('disabled', true);
				$('#item').attr('disabled', true);
				$('#time_dirty').attr('disabled', false);
				$('#grouphpt').val(0);
				$('#factory').val(0);
				$('#department').val(0);
				$('#cycle').val(0);
				$('#item').val(0);
				$('#time_dirty').val(0);
				$('#chksomemonth').parent().show();
				$('#chkyear').parent().show();
			}
			$('#typereport').removeClass('border-danger');
			$('#factory').removeClass('border-danger');
			$('#department').removeClass('border-danger');
			$('#cycle').removeClass('border-danger');
			$('#PPU').removeClass('border-danger');
			$('#rem1').hide();
			$('#rem2').hide();
			$('#rem3').hide();
			$('#rem4').hide();
			$('#rem5').hide();
			$('#rem6').hide();
			$('#rem7').hide();
			$('#rem8').hide();
			$('#rem9').hide();
			$('#rem10').hide();
			$('#rem11').hide();
			$('#rem12').hide();
			// $('#department').attr('disabled', true);

		}
	</script>
	<style media="screen">
		@font-face {
			font-family: myFirstFont;
			src: url("../fonts/DB Helvethaica X.ttf");
		}

		body {
			font-family: myFirstFont;
			font-size: 22px;
		}

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

		.nfont {
			font-family: myFirstFont;
			font-size: 22px;
		}

		label {
			margin-bottom: 0 rem !important;
		}

		button,
		input[id^='qty'],
		input[id^='order'],
		input[id^='max'] {
			font-size: 24px !important;
		}

		.table>thead>tr>th {
			/* background: #4f88e3!important; */
			background-color: #1659a2;
		}

		.table th,
		.table td {
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

		/* bottom-right border-radius */
		table tr:last-child td:last-child {
			border-bottom-right-radius: 6px;
		}

		.btn_mhee {
			background-color: #e83530;
			color: white;
		}

		.pad {
			padding: 0px 15px;
		}

		.padformat {
			padding: 7px 37px;
		}

		.mj2 {
			margin-right: -20px;
			margin-left: 15px;
			margin-top: -11px;
		}

		.mj3 {
			margin-left: -10px;
			margin-top: -8px;
		}

		.mj4 {
			margin-top: -7px;
		}

		.mj5 {
			margin-right: 11px;
			margin-left: 15px;
			margin-top: -8px;
		}

		.mj6 {
			margin-right: -19px;
			margin-left: 15px;
			margin-top: -8px
		}

		.btn_mheesave {
			background-color: #ee9726;
			color: white;
		}

		.lableformat {
			padding-bottom: 10px
		}

		.btn_mheedel {
			background-color: #b12f31;
			color: white;
		}

		.btn_mheeIM {
			background-color: #3e3a8f;
			color: white;
		}

		.btn_mheedetail {
			background-color: #535d55;
			color: white;
		}

		.btn_mheereport {
			background-color: #d8d9db;
			color: white;
		}

		.btn_mheeCREATE {
			background-color: #1458a3;

			color: white;
		}

		a.nav-link {
			width: auto !important;
		}

		.datepicker {
			z-index: 9999 !important
		}

		.hidden {
			visibility: hidden;
		}

		.mhee a {
			/* padding: 6px 8px 6px 16px; */
			text-decoration: none;
			font-size: 25px;
			color: #818181;
			display: block;
		}

		.mhee a:hover {
			color: #2c3e50;
			font-weight: bold;
			font-size: 26px;
		}

		.mhee button {
			/* padding: 6px 8px 6px 16px; */
			text-decoration: none;
			font-size: 23px;
			color: #2c3e50;
			display: block;
			background: none;
			box-shadow: none !important;

		}

		.mhee button:hover {
			color: #2c3e50;
			font-weight: bold;
			font-size: 26px;
		}

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
			font-weight: bold;
			font-size: 26px;
		}

		.icon {
			padding-top: 6px;
			padding-left: 44px;
		}

		@media (min-width: 992px) and (max-width: 1199.98px) {

			.icon {
				padding-top: 6px;
				padding-left: 23px;
			}

			.sidenav a {
				font-size: 21px;

			}
		}

		/* ------------------- */
		.datepicker-here {
			font-size: 24px !important;
		}

		.custom-control-label::after {
			top: 7px !important;
		}

		.custom-control-label::before {
			top: 7px !important;
		}
	</style>
</head>

<body id="page-top">
	<input type="hidden" id='data'>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['report']['title'][$language]; ?></a></li>
		<li class="breadcrumb-item active"><?php echo $array2['menu']['report']['title'][$language]; ?></li>
	</ol>
	<hr style='width: 98%;height:1px;background-color: #ecf0f1;'>
	<input type="hidden" id='input_chk' value='0'>
	<input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
	<div id="wrapper">
		<!-- content-wrapper -->
		<div id="content-wrapper">
			<div class="row">
				<div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['report'][$language]; ?></a>
						</li>
					</ul>

					<div class="tab-content" id="myTabContent">
						<div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
							<div class="row">
								<div class="col-md-11">
									<div class="container-fluid">
										<div class="card-body mt-3">
											<div class="row">
												<div class="col-md-6">
													<div class='form-group row'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['type'][$language]; ?></label>
														<select class="form-control col-sm-8 " id="typereport" style="font-size:22px;" onchange="disabled_fill();">
															<option value="0"><?php echo $array['r' . 0][$language]; ?></option>
															<option value=1><?php echo "1. &nbsp; " . $array['r' . 1][$language]; ?></option>
															<option value=3><?php echo "2. &nbsp; " . $array['r' . 3][$language]; ?></option>
															<option value=4><?php echo "3. &nbsp; " . $array['r' . 4][$language]; ?></option>
															<option value=6><?php echo "4. &nbsp; " . $array['r' . 6][$language]; ?></option>
															<!-- <option value=7><?php echo "5. &nbsp; " . $array['r' . 7]['en']; ?></option> -->
															<option value=8><?php echo "5. &nbsp; " . $array['r' . 8][$language]; ?></option>
															<option value=9><?php echo "6. &nbsp; " . $array['r' . 9][$language]; ?></option>
															<option value=15><?php echo "7. &nbsp; " . $array['r' . 15][$language]; ?></option>
															<option value=18><?php echo "8. &nbsp; " . $array['r' . 18][$language]; ?></option>
															<option value=22><?php echo "9. " . $array['r' . 22][$language]; ?></option>
															<option value=23><?php echo "10. " . $array['r' . 23][$language]; ?></option>
															<option value=24><?php echo "11. " . $array['r' . 24][$language]; ?></option>
															<option value=25><?php echo "12. " . $array['r' . 25][$language]; ?></option>
															<option value=26><?php echo "13. " . $array['r' . 26][$language]; ?></option>
															<option value=27><?php echo "14. " . $array['r' . 27][$language]; ?></option>
															<option value=28><?php echo "15. " . $array['r' . 28][$language]; ?></option>
															<option value=31><?php echo "16. " . $array['r' . 31][$language]; ?></option>
															<option value=29><?php echo "17. " . $array['r' . 29][$language]; ?></option>
															<!-- <option value=30><?php echo "18. " . $array['r' . 30][$language]; ?></option> -->
															<option value=32><?php echo "19. " . $array['r' . 32][$language]; ?></option>
															<option value=33><?php echo "20. " . 'Monitoring SAP' 		        ?></option>
															<option value=34><?php echo "21. " . 'Usage Detail New' 		        ?></option>
														</select>
														<label id="rem1" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['factory'][$language]; ?></label>
														<select class="form-control col-sm-8 bo" id="factory" style="font-size:22px;" onchange="blank_fac();"></select>
														<label id="rem2" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['side'][$language]; ?></label>
														<select class="form-control col-sm-8" id="hotpital" style="font-size:22px;" onchange="departmentWhere();"></select>
														<label id="rem3" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
												<div class="col-md-6 ">
													<div class='form-group row checkblank '>
														<label class="col-sm-3 col-form-label text-left " style="font-size:24px;"><?php echo $array['department'][$language]; ?></label>
														<select class="form-control col-sm-8  " style="font-size:22px;" id="department" onchange="blank_dep();">
														</select>
														<label id="rem4" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
												<!-- <div class="col-md-6 ">
													<div class='form-group row checkblank '>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;">OPD</label>
														<select class="form-control col-sm-8" style="font-size:22px;" id="grouphpt" onchange="blank_dep();">
														</select>
														<label id="rem4" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div> -->
											</div>
											<div class="row">
												<!-- <div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;">Cycle</label>
														<select class="form-control col-sm-8" id="cycle" onchange="blank_cycle();" style=" font-size:22px;">
														</select><label id="rem5" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div> -->
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;">Group</label>
														<select class="form-control col-sm-8" id="grouphpt" onchange="departmentWhere();" style=" font-size:22px;">
														</select><label id="rem6" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
												<div class="col-md-6 ">
													<div class='form-group row checkblank '>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['item'][$language]; ?></label>
														<select class="form-control col-sm-8 select2" style="font-size:22px;" id="item" onchange="show_item();">
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;">รอบ</label>
														<select class="form-control col-sm-8" id="time_dirty" style=" font-size:22px;">
														</select>
														<label id="rem12" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;"><?php echo $array['category'][$language]; ?></label>
														<select class="form-control col-sm-8" id="category" style=" font-size:22px;" disabled="true">
														</select>
														<!-- <label id="rem12" style="margin-top: -8%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label> -->
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;">รอบส่งผ้า</label>
														<select class="form-control col-sm-8" id="time_express" style=" font-size:22px;" disabled="true">
														</select>
													</div>
												</div>
												<div class="col-md-6" id= 'hidden_usage_detail' hidden>
													<div class='form-group row checkblank'>
														<label class="col-sm-3 col-form-label text-left" style="font-size:24px;">Report type</label>
														<select class="form-control col-sm-8" id="type_usage_detail" style=" font-size:22px;">
															<option value="1">Usage Existing</option>
															<option value="2">Usage Detail</option>
														</select>
													</div>
												</div>
											</div>


											<div class="row">
												<div class="col-md-6">
													<div class='form-group row' id="chk">
														<label class="col-sm-3 col-form-label text-left " style="font-size:24px;"><?php echo $array['format'][$language]; ?></label>
														<div style="margin-top : 9px;">
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkday" name="radioFormat" value='1' onclick="showdate()" class="custom-control-input radioFormat ">
																<label class="custom-control-label lableformat " style="margin-bottom:10px" for="chkday"> <?php echo $array['day'][$language]; ?></label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkmonth" name="radioFormat" value='2' onclick="showdate()" class="custom-control-input radioFormat">
																<label class="custom-control-label lableformat" for="chkmonth"> <?php echo $array['month'][$language]; ?></label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkyear" name="radioFormat" value='3' onclick="showdate()" class="custom-control-input radioFormat">
																<label class="custom-control-label lableformat" for="chkyear"> <?php echo $array['year'][$language]; ?></label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<p id="text1" onchange="blank_format();"></p>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">

													<div class='form-group row ' id="showday">
														<label class="col-sm-3	 col-form-label text-left" style=""><?php echo $array['formatdate'][$language]; ?></label>
														<div style="margin-top : 7px;">
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkoneday" name="formatDay" value='1' onclick="formatdate(1)" class="custom-control-input formatDay" checked>
																<label class="custom-control-label" for="chkoneday"><?php echo $array['oneday'][$language]; ?></label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chksomeday" name="formatDay" value='2' onclick="formatdate(2)" class="custom-control-input formatDay">
																<label class="custom-control-label" for="chksomeday"><?php echo $array['manyday'][$language]; ?></label>
															</div>
														</div>
													</div>
													<div class='form-group row' id="showmonth">
														<label class="col-sm-3 col-form-label text-left "><?php echo $array['formatmonth'][$language]; ?></label>
														<div style="margin-top : 7px;">
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkonemonth" name="formatMonth" value='1' onclick="formatmonth(1)" class="custom-control-input formatDay" checked>
																<label class="custom-control-label" for="chkonemonth"><?php echo $array['onemonth'][$language]; ?></label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chksomemonth" name="formatMonth" value='2' onclick="formatmonth(2)" class="custom-control-input formatDay">
																<label class="custom-control-label" for="chksomemonth"><?php echo $array['manymonth'][$language]; ?></label>
															</div>
														</div>
													</div>
													<div class='form-group row' id="showyear">
														<div class="col-sm-3 col-form-label text-left">
															<?php echo $array['year'][$language]; ?>
														</div>
														<div class="col-sm-8 p-0">
															<input type="text" class="form-control datepicker-here only " id="year" data-min-view="years" data-view="years" data-date-format="yyyy" autocomplete="off" value="" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleaseyear'][$language]; ?>">
														</div>
														<label id="rem11" style="margin-top: -7.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6" id="myDay" style="margin-top : -10px;">
													<div class='form-group row'>
														<label class="col-sm-3 col-form-label text-left"><?php echo $array['choosedate'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here only" data-language='<?php echo $language ?>' required id="oneday" data-date-format="dd-mm-yyyy" autocomplete="off" value="" placeholder="<?php echo $array['pleaseday'][$language]; ?>">
														<label id="rem7" style="margin-top: -7.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
														<label class="col-sm-3 col-form-label text-left" id="textto"><?php echo $array['to'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here only" data-language='<?php echo $language ?>' id="someday" data-date-format="dd-mm-yyyy" autocomplete="off" value="" placeholder="<?php echo $array['pleaseday'][$language]; ?>">
														<label id="rem8" style="margin-top: -6.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>
												<div class="col-md-6" id="myMonth">
													<div class='form-group row'>
														<label class="col-sm-3 col-form-label text-left"><?php echo $array['month'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here only" id="onemonth" data-min-view="months" data-view="months" data-date-format="MM-yyyy" value="" autocomplete="off" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleasemonth'][$language]; ?>">
														<label id="rem9" style="margin-top: -7.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
														<label class="col-sm-3 col-form-label text-left" id="textto2"><?php echo $array['to'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here only" id="somemonth" data-min-view="months" data-view="months" data-date-format="MM-yyyy" value="" autocomplete="off" data-language='<?php echo $language ?>' placeholder="<?php echo $array['pleasemonth'][$language]; ?>">
														<label id="rem10" style="margin-top: -6.5%;margin-bottom: -13%;margin-left: 94%;font-size:180%"> * </label>
													</div>
												</div>

												<script>
													var someday = $('#someday').datepicker({}).data('datepicker');
													$('#oneday').datepicker({
														onSelect: function(fd, date) {
															someday.update('minDate', date)
														}
													})

													$("#oneday").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
														onSelect: function(dateText) {
															$('#oneday').removeClass('border-danger');
															$('#rem7').hide();
														}
													});

													$("#someday").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
														onSelect: function(dateText) {
															$('#someday').removeClass('border-danger');
															$('#rem8').hide();
														}
													});

													$("#onemonth").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
														onSelect: function(dateText) {
															$('#onemonth').removeClass('border-danger');
															$('#rem9').hide();
														}
													});

													$("#somemonth").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
														onSelect: function(dateText) {
															$('#somemonth').removeClass('border-danger');
															$('#rem10').hide();
														}
													});

													$("#year").datepicker({ //---ฟังชั่นตรวจสอบค่าtextdate--
														onSelect: function(dateText) {
															$('#year').removeClass('border-danger');
															$('#rem11').hide();
														}
													});
												</script>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>


						<div class="row m-1  d-flex justify-content-end col-12">
							<div class="menu">
								<div class="d-flex justify-content-center">
									<div class="search_1 d-flex align-items-center d-flex justify-content-center">
										<i class="fas fa-search" style="cursor:hand;cursor: pointer;" onclick="search_fillter();"></i>
									</div>
								</div>
								<div>
									<button class="btn" onclick="search_fillter();">
										<?php echo $array['search'][$language]; ?>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row ml-5"><?php echo $array['typereport'][$language]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id='type_report'></span></div>

					<div class="row mx-2">
						<div class="col-md-12">
							<!-- ---------------------------------FACTORY--------------------------------------- -->
							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_Fac" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 35%;' nowrap class='text-center'><?php echo $array['facname'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							<!-- ---------------------------------DEPARTMENT--------------------------------------- -->
							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_Dep" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 35%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							<!-- ---------------------------------DEPARTMENTDOC--------------------------------------- -->
							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_DepDoc" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['facname'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['docno'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							<!-- ---------------------------------GROUP--------------------------------------- -->
							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_Group" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 35%;' nowrap class='text-center'><?php echo $array['group'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							<!-- ---------------------------------CATEGORY--------------------------------------- -->
							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_usage_detail" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 35%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							<!-- ---------------------------------CATEGORY--------------------------------------- -->

							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_category" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 35%;' nowrap class='text-center'><?php echo $array['category'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							<!-- ---------------------------------NOFACDEP--------------------------------------- -->
							<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_NoFacDep" width="100%" cellspacing="0" role="grid" style="">
								<thead id="theadsum" style="font-size:24px;">
									<tr role="row" id='tr_1'>
										<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
										<th style='width: 60%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
										<th style='width: 20%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
										<th style='width: 15%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
									</tr>
								</thead>
								<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
								</tbody>
							</table>
							</thead>
							<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
							</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	</div>
	</div>

	<!-- Dialog Modal
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
	<!-- Bootstrap core JavaScript-->

	<script src="../select2/dist/js/select2.full.min.js" type="text/javascript"></script>

</body>

</html>