{if $set_password}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Set your password</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="easySubmit" action="{speed link="members/activate"}" method="POST" autocomplete="off">
            <div class="form-group">
                <label class="col-sm-4 control-label">Password</label>
                <div class="col-sm-8">
                    <input type="password" value="" maxlength="100" class="form-control" name="password" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Confirm Password</label>
                <div class="col-sm-8">
                    <input type="password" value="" maxlength="100" class="form-control" name="repassword"  data-equals="password" required="required">
                    <small>Your password must be at least 8 characters long </small>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-9">
                    <input type="hidden" name ="task" value="setpass">
                    <input type="hidden" name="t" value="{$time}" />
                    <input type="hidden" name="u" value="{$userid}" />
                    <input type="hidden" name="key" value="{$key}" />
                    <input type="submit" value="SAVE AND GO TO DASHBOARD" class="btn btn-large btn-primary" data-loading-text="Please wait ...">
                </div>
            </div>
        </form>
    </div>
</div>
{/if}
{if $do == 'activate'}
<div class="panel panel-default">
    <div class="panel-body">
        <div class="signup-success">
            <h3>{$message}</h3>
            {if $status eq 'OK'}
            <div class="text-center">
                <p>Thanks for signing up on <b>{$sitename}.</b></p>
            </div>
            {/if}
        </div>
        <div class="text-center" style="margin-top:5px;">
            <a href="{speed link='index.php'}"> Go back to Login </a>
        </div>
    </div>
</div>
{/if}
{if $do == 'success'}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Hey {$user.name}, We're almost ready!</h3>
    </div>
    <div class="panel-body">
        <div class="text-center" style="margin-top:10px;">
            <p class="text-center"><b>Thanks for signing up on {$sitename}.</b></p>
            <p>We've just sent a verification email to <b>{$user.email}</b></p>
            <p>Click on the activation link in the emailer to complete your sign up process</p>
            <p>If you do not receive the confirmation message within a few minutes, please check your <b>Junk E-mail folder and add it to your address book</b> just in case the confirmation email got delivered there instead of your inbox</p>
        </div>
        <div class="text-center" style="margin:15px;">
            <form method="GET" role="easySubmit" data-reload="true" action="{speed link="members/activate"}">
                <input type="hidden" name="do" value="success" />
                <input type="hidden" name="task" value="resend" />
                <input type="hidden" name="u" value="{$u}" />
                <input type="hidden" name="t" value="{$t}" />
                <button type="submit" name="task" class="btn btn-primary"><i class="fa fa-refresh"></i>Resend activation link</button>
            </form>
        </div>
        <div class="text-center" style="margin-top:5px;">
            <a href="{speed link='index.php'}"> Go back to home </a>
        </div>
    </div>
</div>
{/if}
{if empty($do)}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Hey {$user.name}, We're almost ready!</h3>
    </div>
    <div class="panel-body">
        <div class="text-center" style="margin-top:10px;">
            <form method="GET" role="easySubmit" data-reload="true" action="{speed link="index.php?option=members&view=activate"}">
                <p><b>Thanks for signing up on {$sitename}.</b></p>
                <p>We've just sent a activation code to your registered mobile number</p>
                <p>Enter the activation code sent to you to complete your sign up process</p>
                <p>Login details are sent to your email address.</p>
                <p>If you do not receive the activation key within a few minutes click <a href="#" onclick="$(this).closest('form').submit();">Resend code</a></p>
                <input type="hidden" name="do" value="verify" />
                <input type="hidden" name="task" value="resend" />
                <input type="hidden" name="u" value="{$userid}" />
                <input type="hidden" name="t" value="{$time}" />
            </form>
        </div>
        <div class="m-t-lg">
            <form role="easySubmit" class="form-inline" action="{speed link="index.php?option=members&view=activate"}" method="post">
                <div class="form-group">
                    <label class="control-label">Activation key</label>
                    <input type="text" name="k" class="form-control" size="35"  required="true"/>
                </div>
                <button type="submit" name="reset" class="btn btn-large btn-primary" data-loading-text="Please wait ...">Verify Account</button>
                <input type="hidden" name="do" value="verify" />
                <input type="hidden" name="u" value="{$userid}" />
                <input type="hidden" name="t" value="{$time}" />
            </form>
        </div>
    </div>
</div>
{/if}
