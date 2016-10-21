<form class="easySubmit" action="{speed link="index.php?option=members&view=register"}" method="post">
    <div class="form-group">
        <label class="control-label">Full Name</label>
        <input type="text" name="data[first_name]" class="form-control" required="required" placeholder="Name">
    </div>
    <div class="form-group">
        <label class="control-label">Mobile No</label>
        <input type="text" name="data[mobile]" pattern='{speed config="auth.account.patterns.mobile"}' message="Enter valid mobile number with country code" class="form-control" placeholder="+9190199xxxx">
    </div>
    <div class="form-group">
        <label class="control-label">Login Email <small class="text-danger ac-username-message bold"></small></label>
        <input type="text" name="data[email]" required="required"  data-validate-username="email" class="ac-username-validate form-control" placeholder="ex:name@domain.com">
    </div>
    <div class="form-group">
        <label class="control-label">Password</label>
        <input class="form-control" type="password" name="password" pattern='{speed config="auth.account.patterns.password"}' maxlength="15" placeholder="Password between 6 to 15 characters" message="Password must be between 6 to 15 characters length" required="required">
    </div>
    <div class="form-group">
        <label class="control-label">Confirm Password</label>
        <input type="password" name="repassword" class="form-control" data-equals="password" required="required" placeholder="Re-type password">
    </div>
    <div class="form-group blocked">
        <button type="submit" class="btn btn-primary">Create a user account</button>
    </div>
    <input type="hidden" id="return_url" name="return_url" value="{$return_url}" />
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="token" value="{$token}">
</form>
