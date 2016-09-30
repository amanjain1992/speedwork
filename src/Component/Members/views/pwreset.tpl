<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Change your password</h3>
    </div>
    <div class="panel-body">
        {if $status eq OK}
        <form name="resetform" class="easySubmit form-horizontal" action="{speed link='index.php?option=com_members&view=pwreset'}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">New password</label>
                <div class="col-sm-9">
                    <input type="password" name="password" class="form-control" placeholder="New Password" data-watermark="New Password" required="required" size="35"/>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Confirm password</label>
                <div class="col-sm-9">
                    <input type="password" name="repassword" data-equals="password" class="form-control" placeholder="Confirm Password"  data-watermark="Confirm Password"  required="required" size="35"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input type="submit" name="reset" class="btn btn-large btn-primary"  value="Change Password" />
                </div>
            </div>
            <input type="hidden" name="t" value="{$smarty.get.t}" />
            <input type="hidden" name="u" value="{$smarty.get.u}" />
            <input type="hidden" name="key" value="{$smarty.get.key}" />
            <input type="hidden" name="do" value="pwdreset" />
        </form>
        {/if}
        {if $status}
        <div class ="alert alert-{$status|lower}  fade in">{$message}</div>
        {/if}
    </div>
</div>
