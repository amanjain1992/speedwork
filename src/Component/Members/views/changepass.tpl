<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Change Password</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="easySubmit" action="{speed link='index.php?option=members&view=changepass'}" role="form" method="post">
            <div class="form-group">
                <label class="col-sm-4 control-label">Old password</label>
                <div class="col-sm-8">
                    <input type="password" name="oldpassword"  class="form-control"  placeholder="Old password" required="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">New password</label>
                <div class="col-sm-8">
                    <input type="password" pattern="{$config.app.patterns.password}" maxlength="15" message="Password must be between 6 to 15 characters length" name="password"  class="form-control" value="" placeholder="Minimun 6 characters" data-type="password" maxlength="15" required="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Confirm password</label>
                <div class="col-sm-8">
                    <input type="password" name="repassword" data-type="password"  data-equals="password" class="form-control" value="" maxlength="15"  placeholder="Confirm password" required="required" />
                </div>
            </div>
            <div class="form-group submit-button">
                <label  class="col-sm-4 control-label"></label>
                <div class="col-sm-8">
                    <input type="hidden" name="task" value="save" />
                    <input type="submit" class="btn btn-primary" value="Change Password" />
                </div>
            </div>
        </form>
    </div>
</div>
