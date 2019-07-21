<?php
session_start();
$Userid = $_SESSION['Userid'];
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

    <title>หน้าเมนูหลัก</title>

    <link rel="icon" type="image/png" href="../img/pose_favicon.ico">
    <!-- Bootstrap core CSS-->
    <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../template/css/sb-admin.css" rel="stylesheet">

    <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
    <script src="../dist/js/sweetalert2.min.js"></script>
    <script src="../dist/js/jquery-3.3.1.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){

      // on create
      var userid = '<?php echo $Userid; ?>';
      if(userid!="" && userid!=null && userid!=undefined){
        var data = {
          'PAGE' : 'menu',
          'STATUS' : 'getnotification',
          'DEPT' : '<?php echo $_SESSION["Deptid"] ?>'
        };
        senddata(JSON.stringify(data));
      }

      });

      function senddata(data)
      {
         var form_data = new FormData();
         form_data.append("DATA",data);
         var URL = '../process/menu.php';
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
                           if(temp['form']=='getnotification'){
                             if(parseInt(temp['payout'])>0){
                               $('#payout-detail').html('เอกสารค้างจ่าย <br />('+temp['payout']+' เอกสาร)');
                               $('#payout-card').show();
                             }else{
                               $('#payout-group').remove();
                             }
                           }
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

        <?php if ($Userid=="99"){ ?>
        <li class="nav-item">
          <a class="nav-link" href="revise.php">
            <i class="fas fa-fw fa-table"></i>
            <span style="font-size:20px;">รายงานการแก้ไขหน้าPayout</span></a>
        </li>
        <<?php } ?>



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
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3" id="payout-group">
              <div class="card text-white bg-danger o-hidden h-100" id="payout-card">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-bell"></i>
                  </div>
                  <div class="mr-5" id="payout-detail"></div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="checknotification.php">
                  <span class="float-left">คลิกเพื่อไปหน้าของค้างจ่าย</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>
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
