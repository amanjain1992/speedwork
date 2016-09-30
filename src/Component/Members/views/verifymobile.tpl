<div class="verify-login">
    <h3 class="inviz">Change Phone Number</h3>
    <form class="form-horizontal" data-onsuccess="onLoginVerify" role="easySubmit" action="{speed link='members/verifymobile'}" role="form" method="post">
        <div class="form-group">
            <label class="col-sm-4 control-label">New Phone no.</label>
            <div class="col-sm-8">
                <input type="text" name="login_field" class="form-control" pattern="" message="Enter valid phone number with country code" placeholder="+9190xxxxxx" required="required"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Confirm Phone no.</label>
            <div class="col-sm-8">
                <input type="text" name="relogin_field" data-equals="login_field" class="form-control" value="" placeholder="Confirm phone number" required="required" />
            </div>
        </div>
        <div class="form-group submit-button">
            <label class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary"><i class="fa fa-comment-o"></i>Send Verification Code</button>
            </div>
        </div>
        <input type="hidden" name="task" value="verify" />
        <input type="hidden" name="login" value="mobile" />
    </form>
</div>
<div class="verify-login-code" style="display:none">
    <h3 class="inviz">Verify Phone Number</h3>
    <form class="form-horizontal" data-reset="true" data-reload="true" role="easySubmit" action="{speed link='members/verifymobile'}" role="form" method="post">
        <div class="form-group">
            <label class="col-sm-4 control-label">Verification Code</label>
            <div class="col-sm-8">
                <input type="text" name="code" class="form-control" value="" placeholder="Verification Code" required="required"/>
            </div>
        </div>
        <div class="form-group submit-button">
            <label class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">Verify Code</button>
            </div>
        </div>
        <div class="text-center text-bold">Please enter the verification code sent to your phone number.</div>
        <input type="hidden" name="task" value="save" />
        <input type="hidden" name="login" value="mobile" />
    </form>
    <form class="form-horizontal" role="easySubmit" action="{speed link='members/verifymobile'}" role="form" method="post">
        <br>
        <div class="text-center text-bold">You haven't recieved the code yet?, <button type="submit" data-task="resend" class="btn btn-third">Resend Code</button></div>
        <br>
        <input type="hidden" name="resend" value="save" />
        <input type="hidden" name="login" value="mobile" />
    </form>
</div>
<script type="text/javascript">
function onLoginVerify(res) {
    $('.verify-login').fadeOut();
    $('.verify-login-code').fadeIn();
}
</script>
