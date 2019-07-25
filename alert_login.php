<link href="dist/css/sweetalert2.css" rel="stylesheet">
<?php 
    session_start();
    $Username = $_SESSION['Username'];
?>
<div class="m-0 p-0" id="mr_form"> 
    <div class="row"> 
        <div class="col-md-12"> 
        <p>Please login to continue</p> 
        </div> 
    </div> 
    <div class="row"> 
        <div class="col-md-12"> 
            <div class="form-group has-danger"> 
                <label class="sr-only" for="username">Username</label> 
                <div class="input-group mr-sm-2 mb-sm-0"> 
                <input type="text" name="username" class="form-control" id="username" value="<?php echo $Username;?>" required disabled="true"> 
                </div> 
            </div> 
        </div> 
    </div> 
    <div class="row"> 
        <div class="col-md-12"> 
            <div class="form-group"> 
                <label class="sr-only" for="password">Password</label> 
                <div class="input-group mr-sm-2 mb-sm-0"> 
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required autofocus> 
                </div> 
            </div> 
        </div> 
    </div> 
    <div class="row"> 
        <div class="col-md-12"> 
            <a  href="javascript:void(0)" class="swal2-styled btn btn-custom1" onclick="login_again();">Login</a> 
            <a href="javascript:void(0)"  class="swal2-styled btn btn-custom2" onclick="logoff();">Logout</a> 
        </div> 
    </div> 
</div>