<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="header">{if $row.id}Edit Menu {else}Add Menu{/if}</h3>
    </div>
    <div class="panel-body">
        <form role="easySubmit" class="form-general" data-reload="true" action="{speed link="index.php?option=menu&view=add"}" method="POST">
            <div class="form-group">
                <label class="col-sm-4 control-label">Title</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="Title" name="data[title]" value="{$row.title}" size="40" required="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Menu Type</label>
                <div class="col-sm-8">
                    <input type="text"  class="form-control" placeholder="Menu Type" name="data[menu_type]" value="{$row.menu_type}" required="required" />
                </div>
            </div>
            <div class="form-group submit-button">
                <label class="col-sm-4"></label>
                <div class="col-sm-8">
                    <input type="hidden"  name="task" value="save" />
                    <input type="submit" align="center" class="btn btn-primary" value="Save" />
                </div>
            </div>
            <input type="hidden" name="id" value="{$row.id}" />
        </form>
    </div>
</div>