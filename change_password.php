
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
<div id="text_noti">
                <div id="Length" class="glyphicon glyphicon-remove">Must be at least 8 -16 charcters</div>
                <div id="UpperCase" class="glyphicon glyphicon-remove">Must have atleast 1 upper case character</div>
                <div id="LowerCase" class="glyphicon glyphicon-remove">Must have atleast 1 lower case character</div>
                <div id="Numbers" class="glyphicon glyphicon-remove">Must have atleast 1 numeric character</div>
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
    /*Actual validation function*/
    function ValidatePassword() {
        /*Array of rules and the information target*/
        var rules = [
            {
                Pattern: "[A-Z]",
                Target: "UpperCase"
            },
            {
                Pattern: "[a-z]",
                Target: "LowerCase"
            },
            {
                Pattern: "[0-9]",
                Target: "Numbers"
            }
            // ,
            // {
            // Pattern: "[!@@#$%^&*]",
            // Target: "Symbols"
            // }
        ];

        //Just grab the password once
        var password = $(this).val();

        /*Length Check, add and remove class could be chained*/
        /*I've left them seperate here so you can see what is going on */
        /*Note the Ternary operators ? : to select the classes*/
        // $("#Length").removeClass(password.length > 6 ? "glyphicon-remove" : "glyphicon-ok");
        // $("#Length").addClass(password.length > 6 ? "glyphicon-ok" : "glyphicon-remove");
        if(password.length >= 8 && password.length <= 16){
            $("#Length").removeClass("glyphicon-remove");
        }else{
            $("#Length").removeClass("glyphicon-ok");
        }

        if(password.length >= 8 && password.length <= 16){
            $("#Length").addClass("glyphicon-ok");
        }else{
            $("#Length").addClass("glyphicon-remove");
        }
        /*Iterate our remaining rules. The logic is the same as for Length*/
        for (var i = 0; i < rules.length; i++) {

            $("#" + rules[i].Target).removeClass(new RegExp(rules[i].Pattern).test(password) ? "glyphicon-remove" : "glyphicon-ok"); 
            $("#" + rules[i].Target).addClass(new RegExp(rules[i].Pattern).test(password) ? "glyphicon-ok" : "glyphicon-remove");
        }
    }

    /*Bind our event to key up for the field. It doesn't matter if it's delete or not*/
    $(document).ready(function() {
      $("#newpassword").on('keyup', ValidatePassword)
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
        });
    });
</script>