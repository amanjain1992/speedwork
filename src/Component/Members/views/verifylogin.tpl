<div class="verify-login">
    <h3 class="inviz">Change Email Address</h3>
    <form class="form-horizontal" data-onsuccess="onLoginVerify" role="easySubmit" action="{speed link='members/verifylogin'}" role="form" method="post">
        <div class="form-group">
            <label class="col-sm-4 control-label">New Email Address</label>
            <div class="col-sm-8">
                <input type="email" name="login_field" class="form-control" value="" placeholder="Valid email address" required="required"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Confirm Email</label>
            <div class="col-sm-8">
                <input type="email" name="relogin_field" data-equals="login_field" class="form-control" value="" placeholder="Confirm email" required="required" />
            </div>
        </div>
        <div class="form-group submit-button">
            <label class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i>Send Verification Code</button>
            </div>
        </div>
        <input type="hidden" name="task" value="verify" />
        <input type="hidden" name="login" value="email" />
    </form>
</div>
<div class="verify-login-code" style="display:none">
    <h3 class="inviz">Verify Email Address</h3>
    <form class="form-horizontal" role="easySubmit" data-reset="true" data-reload="true" action="{speed link='members/verifylogin'}" role="form" method="post">
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
        <div class="text-center text-bold">Please enter the verification code sent to your email address</div>
        <input type="hidden" name="task" value="save" />
        <input type="hidden" name="login" value="email" />
    </form>
    <form class="form-horizontal" role="easySubmit" action="{speed link='members/verifylogin'}" role="form" method="post">
        <br>
        <div class="text-center text-bold">You haven't recieved the code yet?, <button type="submit" data-task="resend" class="btn btn-third">Resend Code</button></div>
        <br>
        <input type="hidden" name="resend" value="save" />
        <input type="hidden" name="login" value="email" />
    </form>
</div>
<script type="text/javascript">
function onLoginVerify(res) {
    $('.verify-login').fadeOut();
    $('.verify-login-code').fadeIn();
}
</script>
