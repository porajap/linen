<?php 
    $email = $_POST['email']
?>
<link rel="stylesheet" href="css/bootstrap-material-design.css">
<link rel="stylesheet" href="css/docs.min.css">

<div id="fram_white">
    <img src="img/frame1.png">
</div>
<div id="logo">
    <img src="img/logo.png">
</div>
<div id="form_inputReset">
    <div id="email_input">
        <div id="icon_email">
            <!-- <i class="fas fa-envelope"></i> -->
        </div>
        <div class="form-group bmd-form-group">
            <!-- <label for="email" id="label_email" class="bmd-label-floating">Email</label> -->
            
            <input type="email" autocomplete="off" class="form-control" id="email" value="รหัสผ่านจะถูกส่งไปที่ Admin /  It" readonly> 
            <!-- <input type="email" autocomplete="off" class="form-control" id="email" value="<?php echo $email?>" readonly> -->
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------- -->
    <!-- ----------------------------------------------------------------------------------- -->
    <div id="btn_reset">
        <button class="btn btn_black" id="black_reset" onclick="back();">
            Back <i class="fas fa-undo-alt" id="arrow_black"></i>
        </button>
        <button class="btn btn_send" onclick="sendmail();">
            Send  <i class="fas fa-arrow-right" id="arrow_send"></i>
        </button>
    </div>
</div>


<script src="dist/js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap-material-design.js"></script>
<script src="js/application.js"></script>