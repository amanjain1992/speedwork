<form class="form-horizontal" role="easySubmit" data-hide="true" data-reload="true" action="{speed link='members/login'}" method="post">
    <input type="hidden" name="token" value="{$token}">
    <div class="form-group">
        <div class="col-sm-12">
            <input type="text" name="username" class="form-control" required="required" placeholder="Email Address" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12 relative">
            <input type="password" name="password" class="form-control" required="required" placeholder="Password" autocomplete="off" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label><input type="checkbox" name="remember" value="1"> Always sign in automatically</label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i>Login to Account</button>
            <a href="{speed link='members/resetpass'}" class="btn btn-link">Forgot your password?</a>
        </div>
    </div>
</form>
