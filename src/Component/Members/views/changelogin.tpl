<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Change Username</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" data-reload="true" role="easySubmit" action="{speed link='index.php?option=members&view=changelogin'}" role="form" method="post">
            <div class="form-group">
                <label class="col-sm-4 control-label">New Username</label>
                <div class="col-sm-8">
                    <input type="text" name="login_field" message="Please enter a valid username" pattern="{$config.app.patterns.username}" maxlength="15" class="form-control" value="" placeholder="Minimun 6 characters" required="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Confirm Username</label>
                <div class="col-sm-8">
                    <input type="text" name="relogin_field" data-equals="login_field" class="form-control" value="" placeholder="Confirm username" required="required" />
                </div>
            </div>
            <div class="form-group submit-button">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-8">
                    <input type="submit" class="btn btn-primary" value="Change Username" />
                </div>
            </div>
            <input type="hidden" name="task" value="save" />
            <input type="hidden" name="login" value="username" />
        </form>
    </div>
</div>
