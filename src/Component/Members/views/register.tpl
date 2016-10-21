<section class="form-well-container">
    <div class="row table-layout">
        {if $social.login}
        <div class="col-lg-6 table-cell">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="header">Create an account</h3>
                </div>
                <div class="panel-body">
                    {speed view="members:layout.register"}
                </div>
            </div>
        </div>
        <div class="col-lg-1 table-cell vertical-border">
            <span class="or">Or</span>
        </div>
        <div class="col-lg-5 table-cell middle">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="header">Create an account</h3>
                </div>
                <div class="panel-body">
                    {speed view="members:layout.socialr"}
                </div>
            </div>
            <div class="text-center" style="margin-top:30px;">
                <a href="{speed link="members/login"}" class="btn btn-third">Already have an account? Login</a>
            </div>
        </div>
        {else}
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="header">Create an account</h3>
                </div>
                <div class="panel-body">
                    {speed view="members:layout.register"}
                </div>
            </div>
            <div class="text-center" style="margin-top:30px;">
                <a href="{speed link="members/login"}" class="btn btn-third">Already have an account? Login</a>
            </div>
        </div>
        {/if}
    </div>
</section>
