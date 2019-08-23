<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$FacCode = $_SESSION['FacCode'];
$DepCode = $_SESSION['DepCode'];
$DocnoXXX = $_GET['DocNo'];
if ($Userid == "") {
	header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
	$language = 'th';
} else {
	$language = $_SESSION['lang'];
}

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

	<!-- <script src="../dist/locales/bootstrap-datepicker.th.min.js" charset="UTF-8"></script> -->
	<link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
	<script src="../datepicker/dist/js/datepicker.min.js"></script>
	<!-- Include English language -->
	<script src="../datepicker/dist/js/datepicker.en.js"></script>
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

		$(document).ready(function(e) {
			$('#showday').hide();
			$('#showmonth').hide();
			$('#showyear').hide();
			$('#someday').hide();
			$('#somemonth').hide();
			$('#myDay').hide();
			$('#myMonth').hide();
			OnLoadPage();
			$('#hotpital').attr('disabled', true);
			$('#department').attr('disabled', true);

		}).click(function(e) {
			parent.afk();
		}).keyup(function(e) {
			parent.afk();
		});

		jqui(document).ready(function($) {


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
		//console.log(JSON.stringify(data));
		function OnLoadPage() {
			var data = {
				'STATUS': 'OnLoadPage'
			};
			senddata(JSON.stringify(data));
			$('#isStatus').val(0)
		}

		function logoff() {
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
			}).then(function() {
				window.location.href = "../logoff.php";
			}, function(dismiss) {
				window.location.href = "../logoff.php";
				if (dismiss === 'cancel') {}
			})
		}

		function find_indexMonth(year) {
			var monthArray = new Array();
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
			var d = new Date();
			var n = monthArray[d.getMonth()];
			$('#onemonth').attr('value', n + '/' + year);
			m = n;
		}

		function find_indexMonth2(year) {
			var monthArray = new Array();
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

			var date = new Date();
			var nowMonth = monthArray[date.getMonth()];
			var nextMonth = monthArray[date.getMonth() + 1];

			$('#somemonth').attr('value', nowMonth + '/' + year + ' - ' + nextMonth + '/' + year);
		}

		function showdate() {
			var chkday = $('#chkday:checked').val();
			var chkmonth = $('#chkmonth:checked').val();
			var chkyear = $('#chkyear:checked').val();
			if (chkday == 1) {
				$('#showday').show();
				$('#myDay').show();
				$('#showmonth').hide();
				$('#showyear').hide();
				$('#myMonth').hide();
			} else if (chkmonth == 2) {
				var Date = currentDate.split('-');
				find_indexMonth(Date[0]);
				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').show();
				$('#myMonth').show();
				$('#showyear').hide();
			} else if (chkyear == 3) {
				var Date = currentDate.split('-');
				$('#year').attr('value', Date[0]);

				$('#showday').hide();
				$('#myDay').hide();
				$('#showmonth').hide();
				$('#myMonth').hide();
				$('#showyear').show();
			}
		}

		function formatdate(chk) {
			if (chk == 1) {
				$('#oneday').show();
				$('#someday').hide();
			} else if (chk == 2) {
				$('#oneday').hide();
				$('#someday').show();
				var currentDay = currentDate2;
				var today = new Date();
				var tomorrow = new Date(today);
				tomorrow.setDate(today.getDate() + 1);
				// tomorrow.toLocaleDateString();
				var year = tomorrow.getFullYear();
				var month = tomorrow.getMonth() + 1;
				var day = tomorrow.getDate();
				if (month < 10) month = '0' + month;
				if (day < 10) day = '0' + day;
				var myDate = currentDay + ' - ' + year + '/' + month + '/' + day;
				$('#someday').attr('value', myDate);
			}
		}

		function formatmonth(chk) {
			if (chk == 1) {
				$('#onemonth').show();
				$('#somemonth').hide();
			} else if (chk == 2) {
				$('#onemonth').hide();
				$('#somemonth').show();

				var year = currentDate.split('-');
				find_indexMonth2(year[0]);
			}
		}

		function search_fillter() {
			var factory = $('#factory').val();
			var HptCode = $('#hotpital').val();
			var typeReport = $('#typereport').val();
			var DepCode = $('#department').val();
			var Format = $("input[name='radioFormat']:checked").val();
			if (Format == '' || Format == undefined) {
				swal({
					title: '',
					text: '<?php echo $array['insert_form'][$language]; ?>',
					type: 'warning',
					showCancelButton: false,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					showConfirmButton: false,
					timer: 1000,
					confirmButtonText: 'Ok'
				});
			} else if (typeReport == 1 || typeReport == 3 || typeReport == 6 || typeReport == 8 || typeReport == 13 || typeReport == 15) {
				if (factory == '' || factory == undefined || factory == '0') {
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'warning',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
				}
			} else if (typeReport == 2 || typeReport == 4 || typeReport == 5 || typeReport == 8 || typeReport == 7 || typeReport == 9 || typeReport == 14 || typeReport == 16) {
				if (DepCode == '' || DepCode == undefined || DepCode == '0') {
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'warning',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
				}
			} else if (typeReport == 2 || typeReport == 3 || typeReport == 4 || typeReport == 5 || typeReport == 7 || typeReport == 9 || typeReport == 10 || typeReport == 11 || typeReport == 12 || typeReport == 13 || typeReport == 14 || typeReport == 16) {
				if (HptCode == '' || HptCode == undefined || HptCode == '0') {
					swal({
						title: '',
						text: '<?php echo $array['insert_form'][$language]; ?>',
						type: 'warning',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						showConfirmButton: false,
						timer: 1000,
						confirmButtonText: 'Ok'
					});
				}
			}
			if (Format == 1) {
				var FormatDay = $("input[name='formatDay']:checked").val();
				if (FormatDay == 1) {
					var date = $('#oneday').val();
					day = date;
				} else {
					var date = $('#someday').val();
					var chkDateRang = date.split('-');
					if (chkDateRang[1] == null || chkDateRang[1] == undefined) {
						swal({
							title: '',
							text: '<?php echo $array['insert_form'][$language]; ?>',
							type: 'warning',
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
					many_day = date;
				}
				var data = {
					'STATUS': 'find_report',
					'factory': factory,
					'HptCode': HptCode,
					'DepCode': DepCode,
					'typeReport': typeReport,
					'Format': Format,
					'FormatDay': FormatDay,
					'date': date
				};
			} else if (Format == 2) {
				var FormatMonth = $("input[name='formatMonth']:checked").val();
				if (FormatMonth == 1) {
					var date = $('#onemonth').val();
					month = date;
				} else {
					var date = $('#somemonth').val();
					var chkDateRang = date.split('-');
					if (chkDateRang[1] == null || chkDateRang[1] == undefined) {
						swal({
							title: '',
							text: '<?php echo $array['insert_form'][$language]; ?>',
							type: 'warning',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							showConfirmButton: false,
							timer: 1000,
							confirmButtonText: 'Ok'
						});
						setTimeout(function() {
							$('#somemonth').focus();
						}, 1000);
					}
					many_month = date;
				}
				// var date = $('#month').val();
				var data = {
					'STATUS': 'find_report',
					'factory': factory,
					'HptCode': HptCode,
					'typeReport': typeReport,
					'FormatMonth': FormatMonth,
					'DepCode': DepCode,
					'Format': Format,
					'date': date
				};
			} else {
				var date = $('#year').val();
				var data = {
					'STATUS': 'find_report',
					'factory': factory,
					'HptCode': HptCode,
					'DepCode': DepCode,
					'typeReport': typeReport,
					'Format': Format,
					'date': date
				};
			}
			senddata(JSON.stringify(data));
		}

		function departmentWhere() {
			var HptCode = $('#hotpital').val();
			var data = {
				'STATUS': 'departmentWhere',
				'HptCode': HptCode
			};
			senddata(JSON.stringify(data));
		}

		function send_data(data) {
			var URL = data; //your url send_from process process/report.php
			window.open(URL);
		}

		function send_data2(data) {
			var myData = data.split(',');
			var URL = myData[0]; //your url send_from process process/report.php
			window.open(URL + myData[1]);
		}
		//============================================ Close Function ======================================================

		//============================================ Display  ======================================================
		function senddata(data) {
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
							var PmID = <?php echo $PmID; ?>;
							var HptCode = '<?php echo $HptCode ?>';
							var FacCode = '<?php echo $FacCode ?>';
							var DepCode = '<?php echo $DepCode ?>';
							$("#factory").empty();
							$("#department").empty();
							$("#hotpital").empty();

							var facValue0 = '<?php echo $array['factory'][$language]; ?>';
							var fac = "<option value='0'>" + facValue0 + "</option>";
							for (var i = 0; i < temp['Rowx']; i++) {
								fac += "<option value=" + temp[i]['FacCode'] + ">" + temp[i]['FacName'] + "</option>";
							}
							$("#factory").append(fac);


							$("#hotpital").empty();
							var hotValue0 = '<?php echo $array['side'][$language]; ?>';
							var hot = "<option value='' id='hotpitalX'>" + hotValue0 + "</option>";
							for (var i = 0; i < temp['Row']; i++) {
								hot += "<option value=" + temp[i]['HptCode'] + " id='selectHpt" + i + "'>" + temp[i]['HptName'] + "</option>";
							}
							$("#hotpital").append(hot);
							$("#hotpital").val(HptCode);

							$("#department").empty();
							var depValue0 = '<?php echo $array['department'][$language]; ?>';
							var dep1 = "<option value='0'>" + depValue0 + "</option>";
							for (var i = 0; i < temp['RowDep']; i++) {
								dep1 += "<option value=" + temp[i]['DepCode'] + " id='select_" + i + "'>" + temp[i]['DepName'] + "</option>";
							}
							$("#department").append(dep1);
							$("#department").val(DepCode);

						} else if (temp["form"] == 'departmentWhere') {
							$("#department").empty();
							var depValue0 = '<?php echo $array['department'][$language]; ?>';
							var dep2 = "<option value='0'>" + depValue0 + "</option>";
							for (var i = 0; i < temp['Row']; i++) {
								dep2 += "<option value=" + temp[i]['DepCode'] + " id='select_" + i + "'>" + temp[i]['DepName'] + "</option>";
							}
							$("#department").append(dep2);
							$("#select_0").attr('selected', true);
							var DocDate = temp[i]['DocDate'];
						} else if (temp["form"] == 'r1') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R1 tbody').empty();
							$('#table_R1').attr('hidden', false);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:25%'>" + temp[i]['hptname'] + "</td>" +
									"<td class='text-center pl-4' style='width:35%'>" + temp[i]['facname'] + "</td>" +
									"<td class='text-center' style='width:22%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:13%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R1 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r2') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R2 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', false);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:20%'>" + temp[i]['facname'] + "</td>" +
									"<td class='text-center' style='width:20%'>" + temp[i]['Hptname'] + "</td>" +
									"<td class='text-center' style='width:20%'>" + temp[i]['DepName'] + "</td>" +
									"<td class='text-center' style='width:25%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:10%'><button onclick='send_data(\"" + temp['url'] + "\");' class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R2 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r3') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R3 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', false);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['HptName'] + "</td>" +
									"<td class='text-center pl-4' style='width:35%'>" + temp[i]['FacName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:12%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R3 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r4') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R4 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', false);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['DocNo'] + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['DepName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:11%'><button onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R4 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r5') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R5 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', false);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['DocNo'] + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['DepName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:11%'><button onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R5 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r6') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R6 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', false);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:30%'>" + temp[i]['hptname'] + "</td>" +
									"<td class='text-center pl-4' style='width:39%'>" + temp[i]['FacName'] + "</td>" +
									"<td class='text-center' style='width:16%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:10%'><button onclick='send_data(\"" + temp['url'] + "\");' class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R6 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r7') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R7 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', false);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:69%'>" +[i]+temp['depcode'] + "</td>" +
									"<td class='text-center' style='width:16%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:10%'><button onclick='send_data(\"" + temp['url'] + "\");' class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R7 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r8') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R8 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', false);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:20%'>" + temp[i]['FacName'] + "</td>" +
									"<td class='text-center' style='width:20%'>" + temp[i]['HptName'] + "</td>" +
									"<td class='text-center' style='width:20%'>" + temp[i]['DepName'] + "</td>" +
									"<td class='text-center' style='width:25%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:10%'><button onclick='send_data(\"" + temp['url'] + "\");' class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R8 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r9') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R9 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', false);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:70%'>" + temp[i]['DepName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:12%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R9 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r10' || temp["form"] == 'r11' || temp["form"] == 'r12') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R10 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', false);
							$('#table_R11').attr('hidden', false);
							$('#table_R12').attr('hidden', false);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center pl-4' style='width:70%'>" + temp[i]['HptName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:12%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R10 tbody").append(dataRow);
							}
						} else if (temp["form"] == 'r13') {
							var show_date = '';
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R13 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', false);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['FacName'] + "</td>" +
									"<td class='text-center pl-4' style='width:35%'>" + temp[i]['HptName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center' style='width:12%'><button  onclick='send_data(\"" + temp['url'] + "\");'  class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R13 tbody").append(dataRow);
							}
						}else if (temp["form"] == 'r15' || temp["form"] == 'r16') {
							var show_date = ''; 
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R15 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', false);
							$('#table_R16').attr('hidden', false);
							$('#table_R17').attr('hidden', true);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['DocNo'] + "</td>" +
									"<td class='text-center' style='width:36%'>" + temp[i]['FacName'] + "</td>" +
									"<td class='text-center' style='width:12%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:11%'><button onclick='send_data(\"" + temp['url'] + "\");' class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R15 tbody").append(dataRow);
							}
						}
						else if (temp["form"] == 'r17') {
							var show_date = ''; 
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
								show_date = temp["date1"];
							}
							$('#type_report').text(temp['typeReport']);
							$('#table_R17 tbody').empty();
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', false);
							for (var i = 0; i < temp['countRow']; i++) {
								var dataRow = "<tr>" +
									"<td style='width:5%'>" + (i + 1) + "</td>" +
									"<td class='text-center' style='width:40%'>" + temp[i]['HptName'] + "</td>" +
									"<td class='text-center' style='width:45%'>" + show_date + "</td>" +
									"<td class='text-center text-center' style='width:10%'><button onclick='send_data(\"" + temp['url'] + "\");' class='btn btn-info btn-sm' style='font-size:18px!important;'><i class='fas fa-print mr-2'></i>พิมพ์</button></td>" +
									"</tr>";
								$("#table_R17 tbody").append(dataRow);
							}
						}
					} else if (temp['status'] == "notfound") {
						$('#type_report').text(temp['typeReport']);
						if (temp["form"] == 'r1') {
							$('#table_R1').attr('hidden', false);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R1 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R1 tbody").append(dataRow);
						} else if (temp["form"] == 'r2') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', false);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R2 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R2 tbody").append(dataRow);
						} else if (temp["form"] == 'r3') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', false);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R3 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R3 tbody").append(dataRow);
						} else if (temp["form"] == 'r4') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', false);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R4 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R4 tbody").append(dataRow);
						} else if (temp["form"] == 'r5') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', false);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R4 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R5 tbody").append(dataRow);
						} else if (temp["form"] == 'r6') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', false);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R6 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R6 tbody").append(dataRow);
						} else if (temp["form"] == 'r7') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', false);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R7 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R7 tbody").append(dataRow);
						} else if (temp["form"] == 'r8') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', false);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R8 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R8 tbody").append(dataRow);
						} else if (temp["form"] == 'r9') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', false);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R9 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R9 tbody").append(dataRow);
						} else if (temp["form"] == 'r10' || temp["form"] == 'r11' || temp["form"] == 'r12') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', false);
							$('#table_R11').attr('hidden', false);
							$('#table_R12').attr('hidden', false);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R10 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R10 tbody").append(dataRow);
						} else if (temp["form"] == 'r13') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', false);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', true);
							$('#table_R13 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R13 tbody").append(dataRow);
						} else if (temp["form"] == 'r15' || temp["form"] == 'r16') {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', false);
							$('#table_R16').attr('hidden', false);
							$('#table_R17').attr('hidden', true);
							$('#table_R15 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R15 tbody").append(dataRow);
						}
						else if (temp["form"] == 'r17' ) {
							$('#table_R1').attr('hidden', true);
							$('#table_R2').attr('hidden', true);
							$('#table_R3').attr('hidden', true);
							$('#table_R4').attr('hidden', true);
							$('#table_R5').attr('hidden', true);
							$('#table_R6').attr('hidden', true);
							$('#table_R7').attr('hidden', true);
							$('#table_R8').attr('hidden', true);
							$('#table_R9').attr('hidden', true);
							$('#table_R10').attr('hidden', true);
							$('#table_R11').attr('hidden', true);
							$('#table_R12').attr('hidden', true);
							$('#table_R13').attr('hidden', true);
							$('#table_R14').attr('hidden', true);
							$('#table_R15').attr('hidden', true);
							$('#table_R16').attr('hidden', true);
							$('#table_R17').attr('hidden', false);
							$('#table_R17 tbody').empty();
							var dataRow = "<tr><td style='width:100%' class='text-center'>ไม่พบเอกสาร</td></tr>";
							$("#table_R17 tbody").append(dataRow);
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
		//============================================  Close Display  ======================================================
		function disabled_fill() {
			var typeReport = $('#typereport').val();
			if (typeReport == 1 || typeReport == 3 || typeReport == 5 || typeReport == 6 || typeReport == 13 || typeReport == 15) {
				$('#hotpital').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#factory').attr('disabled', false);

			} else if (typeReport == 4 || typeReport == 14 || typeReport == 16) {
				$('#department').attr('disabled', true);
				$('#factory').attr('disabled', true);
				$('#hotpital').attr('disabled', true);

			} else if (typeReport == 2 || typeReport == 8 || typeReport == 7) {
				$('#factory').attr('disabled', false);
				$('#department').attr('disabled', true);
				$('#hotpital').attr('disabled', true);

			} else if (typeReport == 10 || typeReport == 11 || typeReport == 12) {
				$('#factory').attr('disabled', true);
				$('#department').attr('disabled', true);
				$('#hotpital').attr('disabled', true);
				$('#factory').val(0);

			}
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

		.nfont {
			font-family: myFirstFont;
			font-size: 22px;
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

		.btn_mheesave {
			background-color: #ee9726;
			color: white;
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
			/* padding: 6px 8px 6px 16px;*/
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
	</style>
</head>

<body id="page-top">
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
								<div class="col-md-10">
									<div class="container-fluid">
										<div class="card-body mt-3">

											<div class="row">
												<div class="col-md-6">
													<div class='form-group row'>
														<label class="col-sm-4 col-form-label text-right" style="font-size:24px;"><?php echo $array['type'][$language]; ?></label>
														<select class="form-control col-sm-8 " id="typereport" style="font-size:22px;" onchange="disabled_fill();">
															<?php //for ($i = 1; $i <= 16; $i++) { ?>
															<option value="<?php echo 1 ?>"><?php echo $array['r' . 1][$language]; ?></option>
															<option value="<?php echo 2 ?>"><?php echo $array['r' . 2][$language]; ?></option>
															<option value="<?php echo 3 ?>"><?php echo $array['r' . 3][$language]; ?></option>
															<option value="<?php echo 6 ?>"><?php echo $array['r' . 6][$language]; ?></option>
															<option value="<?php echo 8 ?>"><?php echo $array['r' . 8][$language]; ?></option>
															<option value="<?php echo 17 ?>"><?php echo $array['r' . 17][$language]; ?></option>

															<?php //} ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class='form-group row checkblank'>
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['factory'][$language]; ?></label>
														<select class="form-control col-sm-8" id="factory" style="font-size:22px;"></select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class='form-group row'>
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
														<select class="form-control col-sm-8" id="hotpital" style="font-size:22px;" onchange="departmentWhere();"></select>
													</div>
												</div>
												<div class="col-md-6 ">
													<div class='form-group row'>
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['department'][$language]; ?></label>
														<select class="form-control col-sm-8" style="font-size:22px;" id="department">
														</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6 ">
													<div class='form-group row'>
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['format'][$language]; ?></label>
														<div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkday" name="radioFormat" value='1' onclick="showdate()" class="custom-control-input radioFormat">
																<label class="custom-control-label" for="chkday"> วัน</label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkmonth" name="radioFormat" value='2' onclick="showdate()" class="custom-control-input radioFormat">
																<label class="custom-control-label" for="chkmonth"> เดือน</label>
															</div>

															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkyear" name="radioFormat" value='3' onclick="showdate()" class="custom-control-input radioFormat">
																<label class="custom-control-label" for="chkyear"> ปี</label>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6 ">
													<div class='form-group row' id="showday">
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['formatdate'][$language]; ?></label>
														<div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkoneday" name="formatDay" value='1' onclick="formatdate(1)" class="custom-control-input formatDay" checked>
																<label class="custom-control-label" for="chkoneday">หนึ่งวัน</label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chksomeday" name="formatDay" value='2' onclick="formatdate(2)" class="custom-control-input formatDay">
																<label class="custom-control-label" for="chksomeday">หลายวัน</label>
															</div>
														</div>
													</div>
													<div class='form-group row' id="showmonth">
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['formatmonth'][$language]; ?></label>
														<div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chkonemonth" name="formatMonth" value='1' onclick="formatmonth(1)" class="custom-control-input formatDay" checked>
																<label class="custom-control-label" for="chkonemonth">หนึ่งเดือน</label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" id="chksomemonth" name="formatMonth" value='2' onclick="formatmonth(2)" class="custom-control-input formatDay">
																<label class="custom-control-label" for="chksomemonth">หลายเดือน</label>
															</div>
														</div>
													</div>
													<div class='form-group row' id="showyear">
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['year'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here" id="year" data-min-view="years" data-view="years" data-date-format="yyyy" data-language='en'>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6" id="myDay">
													<div class='form-group row'>
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['choosedate'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here" data-language='en' id="oneday" data-date-format="yyyy-mm-dd" autocomplete="off" value="<?php echo  date('Y-m-d'); ?>">
														<input type="text" class="form-control col-sm-8 datepicker-here" data-language='en' data-range="true" data-multiple-dates-separator=" - " id="someday" data-date-format="yyyy/mm/dd">
													</div>
												</div>
												<div class="col-md-6" id="myMonth">
													<div class='form-group row'>
														<label class="col-sm-4 col-form-label text-right"><?php echo $array['month'][$language]; ?></label>
														<input type="text" class="form-control col-sm-8 datepicker-here" id="onemonth" data-min-view="months" data-view="months" data-date-format="MM/yyyy" data-language='en'>
														<input type="text" class="form-control col-sm-8 datepicker-here" id="somemonth" data-min-view="months" data-view="months" data-date-format="MM/yyyy" data-language='en' data-range="true" data-multiple-dates-separator=" - ">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row m-1  d-flex justify-content-end col-12">
									<div class="menu" <?php if ($PmID == 1) echo 'hidden'; ?>>
										<div class="d-flex justify-content-center">
											<div class="search_1 d-flex justify-content-center">
												<button class="btn" onclick="search_fillter();">
													<i class="fas fa-search"></i>
													<div>
														<?php echo $array['search'][$language]; ?>
													</div>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row ml-5"><?php echo $array['typereport'][$language]; ?>&nbsp;<span id='type_report'></span></div>

							<div class="row mx-2">
								<div class="col-md-12">
									<!-- ---------------------------------Report 1 AND Report 3--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R1" width="100%" cellspacing="0" role="grid" style="">
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 25%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 35%;' nowrap class='text-center'><?php echo $array['facname'][$language]; ?></th>
												<th style='width: 22%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 13%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									
									<!-- ---------------------------------Report 2--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R2" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 20%;' nowrap class='text-center'><?php echo $array['facname'][$language]; ?></th>
												<th style='width: 20%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 20%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
												<th style='width: 25%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
											
										</thead>
									
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- --------------------------------- Report 3--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R3" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 35%;' nowrap class='text-center'><?php echo $array['facname'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									
									<!-- ---------------------------------Report 4--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R4" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['docno'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 11%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 5--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R4" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['docno'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 11%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 6--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R6" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 30%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 39%;' nowrap class='text-center'><?php echo $array['factory'][$language]; ?></th>
												<th style='width: 16%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
						
									<!-- ---------------------------------Report 7--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R7" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 69%;' nowrap class='text-center'><?php echo $array['factory'][$language]; ?></th>
												<th style='width: 16%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>

												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 8--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R8" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 20%;' nowrap class='text-center'><?php echo $array['factory'][$language]; ?></th>
												<th style='width: 20%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 20%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
												<th style='width: 25%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 9--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R9" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 74%;' nowrap class='text-center'><?php echo $array['department'][$language]; ?></th>
												<th style='width: 11%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 10--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R10" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 74%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 11%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 13--------------------------------------- -->
									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R13" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 59%;' nowrap class='text-center'><?php echo $array['factory'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 15--------------------------------------- -->

									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R15" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['docno'][$language]; ?></th>
												<th style='width: 36%;' nowrap class='text-center'><?php echo $array['factory'][$language]; ?></th>
												<th style='width: 12%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 11%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
										</thead>
										<tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
										</tbody>
									</table>
									<!-- ---------------------------------Report 17--------------------------------------- -->

									<table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="table_R17" width="100%" cellspacing="0" role="grid" hidden>
										<thead id="theadsum" style="font-size:24px;">
											<tr role="row" id='tr_1'>
												<th style='width: 5%;' nowrap class='text-center'><?php echo $array['no'][$language]; ?></th>
												<th style='width: 40%;' nowrap class='text-center'><?php echo $array['side'][$language]; ?></th>
												<th style='width: 45%;' nowrap class='text-center'><?php echo $array['docdate'][$language]; ?></th>
												<th style='width: 10%;' nowrap class='text-center'><?php echo $array['show'][$language]; ?></th>
											</tr>
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


</body>

</html>