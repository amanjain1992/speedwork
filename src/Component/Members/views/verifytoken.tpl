<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="header">Token Verification  For Password Change</h3>
    </div>
    <div class="panel-body">
        <form role="easySubmit" action="{speed link='index.php?option=members&view=verifytoken'}" method="post">
            <div class="form-group">
                <h4>We've just sent a verification token to email address : <b>{$row.email}</b> </h4>
                <label class="control-label">Enter Token</label>
                <input type="text" name="token" class="form-control" placeholder="Enter token" required="required" size="35"/></td>
            </div>
            <div class="form-group">
                <input type="submit" name="reset" value="Verify Token" class="btn btn-large btn-block btn-primary" />
                <input type="hidden" name="task" value="verify">
            </div>
        </form>
        <div class="text-center signup-now">
            <form class="form-horizontal" role="easySubmit" action="{speed link='members/verifytoken'}" role="form" method="post">
            <br>
            <div class="text-center text-bold">You haven't recieved the code yet?, <button type="submit" data-task="resend" class="btn btn-third">Resend Code</button></div>
            <br>
            <input type="hidden" name="resend" value="save" />
            <input type="hidden" name="login" value="mobile" />
        </form>
        </div>
    </div>
</div>

