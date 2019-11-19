
<?php 
    $username = $_POST['username'];
    $password = $_POST['password'];
?>
<link rel="stylesheet" href="css/bootstrap-material-design.css">
<link rel="stylesheet" href="css/docs.min.css">
<style>

    .glyphicon-remove {
        color: red;
    }

    .glyphicon-ok {
        color: green;
    }
</style>
<div id="fram_white">
    <img src="img/frame1.png">
</div>
<div id="logo_change">
    <img src="img/logo.png">
</div>
<div id="form_inputChange">
    <div id="usernameCh_input">
        <div class="form-group bmd-form-group">
            <label for="username2" id="label_usernameCh" class="bmd-label-floating">Username</label>
            <input type="text" autocomplete="off" class="form-control" onkeyup="make_char()" id="username2" autofocus value="<?php echo $username;?>" required>
        </div>
    </div>
    <div id="oldCh">
        <div class="form-group bmd-form-group">
            <label for="oldpassword" id="label_old" class="bmd-label-floating">Old Password</label>
            <input type="password" autocomplete="off" class="form-control" onkeyup="make_char()" id="oldpassword"  required> 
        </div>
    </div>
    <form id="test">
        <div id="newCh">
            <div class="form-group bmd-form-group">
                <label for="newpassword" id="label_new" class="bmd-label-floating">New Password</label>
                <input type="password" autocomplete="off" class="form-control" onkeyup="make_char()" id="newpassword" required>
            </div>
        </div>
        <div id="confirmCh">
            <div class="form-group bmd-form-group">
                <label for="confirmpassword" id="label_confirm" class="bmd-label-floating">Confirm Password</label>
                <input type="password" autocomplete="off" class="form-control" onkeyup="make_char()" id="confirmpassword" required>
            </div>
        </div>
    </form>

    <!-- ----------------------------------------------------------------------------------- -->
    <!-- ----------------------------------------------------------------------------------- -->
    <div id="btn_change">
        <button class="btn btn_black" id="black_reset" onclick="back();">
            Back <i class="fas fa-undo-alt" id="black_save"></i>
        </button>
        <button class="btn btn_save" onclick="passwordUpdate();">
            Save  <i class="fas fa-arrow-right" id="arrow_save"></i>
        </button>
    </div>
</div>

<script src="dist/js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap-material-design.js"></script>
<script src="js/application.js"></script>
<script>
        $(document).ready(function() {
        $.validator.addMethod("hasUppercase", function(value, element) {
            if (this.optional(element)) {
                return true;
            }
            return /[A-Z]/.test(value);
        }, "Must have atleast 1 upper case character");

        $.validator.addMethod("hasLowercase", function(value, element) {
            if (this.optional(element)) {
                return true;
            }
            return /[a-z]/.test(value);
        }, "Must have atleast 1 lower case character");
        
        $.validator.addMethod("hasLowercase", function(value, element) {
            if (this.optional(element)) {
                return true;
            }
            return /[0-9]/.test(value);
        }, "Must have atleast 1 numeric character");

        $('#test').validate({
            errorPlacement: function(error, element) {
            // Append error within linked label
            $( element )
                .closest( "form" )
                    .find( "small[for='" + element.attr( "id" ) + "']" )
                        .append( error );
            },
            rules:
            {
                newpassword:
                {
                    rangelength: [8, 16],
                    hasUppercase: true,
                    hasLowercase: true
                },
                confirmpassword:
                {
                    equalTo: "#newpassword"
                }
            },
            messages:
            {	
                newpassword:
                {
                    rangelength: "Must be at least 8 -16 charcters"
                },
                confirmpassword:
                {
                    equalTo: "Passwords do not match"
                }
            }
            // ,
            // success: function(label) {
            //     label.addClass("valid").text("Ok!")
            // }
        });
    });
</script>