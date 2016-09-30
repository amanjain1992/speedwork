<div class="panel panel-default">
    <div class="panel-heading">
        <h2>My Account</h2>
    </div>
    <div class="panel-body">
        <form role="easySubmit" class="form-horizontal" action="{speed link="index.php?option=members&view=me"}" method="POST">
            <div class="form-group">
                <label class="col-sm-4 control-label">First Name</label>
                <div class="col-sm-8">
                    <input type="text" name="user[first_name]" required="required" value="{$row.user.first_name}"  class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Last Name</label>
                <div class="col-sm-8">
                    <input type="text" name="user[last_name]" required="required" value="{$row.user.last_name}"class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Phone number</label>
                <div class="col-sm-8">
                    <input type="text" name="user[mobile]" value="{$row.user.mobile}" data-message="Please enter valid 10 digits mobile number"  data-type="mobile" required="required" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Email Address</label>
                <div class="col-sm-8">
                    <input type="email" name="user[email]" maxlength="100" required="required" class="form-control" value="{$row.user.email}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Alternative Phone number</label>
                <div class="col-sm-8">
                    <input type="text" name="details[phone]" value="{$row.details.phone}" data-message="Please enter 10 digits mobile number"  data-type="mobile" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Alternative Email Address</label>
                <div class="col-sm-8">
                    <input type="email" name="details[alt_email]" value="{$row.details.alt_email}"  class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Address line1</label>
                <div class="col-sm-8">
                    <input type="text" name="details[address_line]" maxlength="100" value="{$row.details.address_line}"  class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Address line2</label>
                <div class="col-sm-8">
                    <input type="text" name="details[address_line2]" maxlength="100" value="{$row.details.address_line}"  class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">City</label>
                <div class="col-sm-8">
                    <input type="text" name="details[city]" maxlength="100" value="{$row.details.city}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">State</label>
                <div class="col-sm-8">
                    <input type="text" name="details[state]" maxlength="100" value="{$row.details.state}" class="form-control" />
                    <input type="hidden" name="details[country]" value="India" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Pin code</label>
                <div class="col-sm-8">
                    <input type="text" pattern="[0-9]*" data-message="Enter correct Pincode" name="details[zipcode]" min="0" maxlength="6" value="{$row.details.zipcode}" class="form-control" />
                </div>
            </div>
            <div class="form-group submit-button">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i>Update Account Details</button>
                    <input type="hidden" name="task" value="save" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2>Last 5 Login Attempts</h2>
    </div>
    <div class="panel-body">
        {speed request="members.history"}
    </div>
</div>
