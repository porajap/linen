<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
if($Userid==""){
	header("location:../index.html");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>รายงานการแก้ไขหน้าPayout</title>

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
	$(document).ready(function(e){

		// on create
    }).mousemove(function(e) { parent.last_move = new Date();;
    }).keyup(function(e) { parent.last_move = new Date();;
    });

	function Searchdocument(){
		var date = $('#datepicker') . val();
		var data = {
			'PAGE' :'revise' ,
			'STATUS'  :'Searchdocument' ,
			'DATE'  : date,
			'DEPT'  : '<?php echo $_SESSION["Deptid"]; ?>'
		};
		console.log(JSON . stringify(data));
		senddata(JSON . stringify(data));
	}

	function gotoreport(date){
		var date = $("#datepicker").val();
		//alert(machines);
		var link = "../report/Report_Revise.php?eDate="+date;
		window.location.href=link;
	}


	function senddata(data)
	{
		var form_data = new FormData();
		form_data.append("DATA",data);
		 var URL = '../process/revise.php';
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
					title: 'กรุณารอสักครู่',
					text: 'ระบบกำลังประมวลผล',
					allowOutsideClick: false
				})
				swal.showLoading();
			},
			success: function (result) {
				try {
					var temp = $.parseJSON(result);
					console.log(result);
				} catch (e) {
					console.log('Error#542-decode error');
				}
				swal.close();
				if(temp["status"]=='success')
				{

				}
				else {
					console.log('ไม่มีข้อมูลการแจ้งเตือน');
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
			}
		});
	}

	function logoff() {
		swal({
			title: '',
			text: 'ออกจากระบบสำเร็จ',
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
			if (dismiss === 'cancel') {

			}
		})
	}
	</script>
	<style media="screen">
	a.nav-link{
		width:auto!important;
	}
	#btnreport,.ui-dialog-titlebar,.ui-corner-all,.ui-widget-header,.ui-helper-clearfix,.ui-draggable-handle{
		font-family: 'THSarabunNew'!important;
		font-size:20px!important;
	}
	body,ol,input,label.navbar-brand,.swal2,select{
		font-family: 'THSarabunNew'!important;
		font-size:23px!important;
	}

	span{
		font-family: 'THSarabunNew';
		font-size:22px!important;
	}

	a.nav-brand,.mr-1{
		font-family: 'THSarabunNew';
		font-size:30px!important;
	}

	button{
		font-size:20px!important;
	}

	th{
		font-size:17px!important;
	}

	td{
		font-size:20px!important;
	}

	a.nav-link{
		width:auto!important;
	}
	.card-body {
		display: block;
		max-height: 450px;
		overflow-y: auto;
		overflow-x: hidden;
		-ms-overflow-style: -ms-autohiding-scrollbar;
	}
	.line {
  width: auto;
  padding: 10px;
  border: 0px solid gray;
  margin: 0;
}
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.floatClear {
  clear: both;
}
h1 {
  overflow: hidden;
  text-align: center;
}

h1:before,
h1:after {
  background-color: #000;
  content: "";
  display: inline-block;
  height: 1px;
  position: relative;
  vertical-align: middle;
  width: 50%;
}

h1:before {
  right: 0.5em;
  margin-left: -50%;
}

h1:after {
  left: 0.5em;
  margin-right: -50%;
}
	</style>
</head>

<body id="page-top">

	<nav class="navbar navbar-expand static-top" style="background-color:#223bac;">

		<a class="navbar-brand mr-1" href="menu.php" style="color: White;">Pose Intelligence</a>

		<!-- <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
		<i class="fas fa-bars"></i>
	</button> -->

	<!-- Navbar Search -->
	<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">

	</form>

	<!-- Navbar -->
	<ul class="navbar-nav ml-auto ml-md-0" >
		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-user-circle fa-fw"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
				<a class="dropdown-item" href="#" data-toggle="modal" onclick="logoff();">Logout</a>
			</div>
		</li>
	</ul>

</nav>

<div id="wrapper">

	<!-- Sidebar -->
	<ul class="sidebar navbar-nav toggled" style="background-color:#223bac; width:240px!important;">

		<li class="nav-item active">
			<a class="nav-link" href="menu.php">
				<i class="fas fa-fw fa-tachometer-alt"></i>
				<span style="font-size:20px;">หน้าเมนูหลัก</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="sendsterile.php">
				<i class="fas fa-fw fa-table"></i>
				<span style="font-size:20px;">ใบส่งล้าง</span></a>
			</li>
			<!-- <li class="nav-item">
			<a class="nav-link" href="sendsterile_except.php">
			<i class="fas fa-fw fa-table"></i>
			<span style="font-size:20px;">ใบส่งล้าง<br />(ไม่มีรหัสใช้งาน)</span></a>
		</li> -->
		<li class="nav-item">
			<a class="nav-link" href="payout.php">
				<i class="fas fa-fw fa-table"></i>
				<span style="font-size:20px;">ใบเบิกของจ่ายกลาง</span></a>
			</li>
			<li class="nav-item ">
				<a class="nav-link" href="payout_section.php">
					<i class="fas fa-fw fa-table"></i>
					<span style="font-size:20px;">โอนของระหว่างแผนก</span></a>
				</li>
				<!-- <li class="nav-item">
				<a class="nav-link" href="payout_adding.php">
				<i class="fas fa-fw fa-table"></i>
				<span style="font-size:20px;">เพิ่มของเข้าแผนก</span></a>
			</li> -->

			<li class="nav-item">
				<a class="nav-link" href="revise.php">
					<i class="fas fa-fw fa-table"></i>
					<span style="font-size:20px;">รายงานการแก้ไขหน้าPayout</span></a>
				</li>
		

				<li class="nav-item ">
					<a class="nav-link" href="pattern.php">
						<i class="fas fa-fw fa-table"></i>
						<span style="font-size:20px;">รูปแบบการนำเข้าข้อมูล</span></a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="delusagecode.php">
							<i class="fas fa-fw fa-table"></i>
							<span style="font-size:20px;">ลบรหัสใช้งาน</span></a>
						</li>
						<!-- <li class="nav-item ">
						<a class="nav-link" href="checknotification.php">
						<i class="fas fa-fw fa-table"></i>
						<span style="font-size:20px;">ข้อมูลเอกสารค้างจ่าย</span></a>
					</li> -->
				</ul>

				<div id="content-wrapper">

					<div class="container-fluid">
						<!-- Icon Cards-->
						<div class="line">
							<div class="row">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<h1>รายงานการแก้ไขหน้าPayout</h1>
								</div>
								<div class="col-md-3">
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
								</div>
								<div class="col-md-5">
									<div class="input-group" style="width:70%">
										<input type="text" style="margin-left:15px;" class="form-control datepicker-here" id="datepicker" data-language='en' data-date-format='yyyy-mm-dd' value="<?php echo date('Y-m-d'); ?>">
										<div class="input-group-append">
											<button class="btn btn-primary" type="button" >
												<i class="fas fa-calendar"></i>
											</button>
										</div>
										<!-- <button type="button" style="margin-left:15px; width:100px;" class="btn btn-primary" name="button" onclick="Searchdate();">ค้นหา</button> -->
									</div>
								</div>
								<div class="col-md-3">
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
								</div>
								<div class="col-md-5">
									<button type="button" style="margin-left:15px; width:100px;" class="btn btn-success" name="button" onclick="gotoreport();">ดูรายงาน</button>
								</div>
								<div class="col-md-3">
								</div>
							</div>
						</div>>
					</div>
					<!-- /.content-wrapper -->

				</div>
				<!-- /#wrapper -->

				<!-- Scroll to Top Button-->
				<a class="scroll-to-top rounded" href="#page-top">
					<i class="fas fa-angle-up"></i>
				</a>

				<!-- Logout Modal-->
				<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
								<button class="close" type="button" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
								<a class="btn btn-primary" href="login.html">Logout</a>
							</div>
						</div>
					</div>
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
