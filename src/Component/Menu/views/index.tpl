{if $ajax.disable}
{$ajax.fm.start}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="header">Menu (<span class="ac-ajax-total">{$rows.total}</span>)</h3>
    </div>
    <div class="panel-body">
        <div class="row panel-body-filters">
            <div class="col-md-10 col-md-offset-2">
                <div class="input-group">
                    <span class="input-group-addon">Filter by</span>
                    <input type="text" class="form-control" name="lfilter[title]" placeholder="Title" size="25" >
                    <div class='input-group-field'>
                        <input type="text" class="form-control" name="lfilter[menu_type]" placeholder="Menu Type" size="15" />
                    </div>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success">Go</button>
                        <button type="reset" class="ac-filter-reset btn btn-default">Reset</button>
                        <a href="{speed link="menu/add"}" class="qtipmodal btn btn-info" role="button"><i class="fa fa-plus"></i> New</a>
                    </span>
                </div>
            </div>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">SI</th>
                    <th>Title</th>
                    <th>Menu Type</th>
                    <th width="15%"><center>Option</center></th>
                </tr>
            </thead>
            <tbody  class="ac-ajax-content">
                {/if}
                {foreach $rows.data as $row}
                <tr id="number{$row.id}">
                    <td>{$row.serial}</td>
                    <td>{$row.title}</td>
                    <td>{$row.menu_type}</td>
                    <td align="center">
                        <a href="{speed link="index.php?option=menu&view=add&id={$row.id}"}" class="qtipmodal">
                            <i class="fa fa-edit fa-lg help-tip" title="Edit Menu"></i>
                        </a>
                        &nbsp;
                        <a href="{speed link="index.php?option=menu&view=delete&id={$row.id}"}" class="ac-action-delete">
                            <i class="fa fa-trash-o fa-lg help-tip" title="Delete Menu {$row.title}"></i>
                        </a>
                        &nbsp;
                        <a href="{speed link="index.php?option=menu&view=items&t={$row.menu_type}"}">
                            <i class="fa fa-lg fa-bars help-tip" title="List of menus"></i>
                        </a>
                    </td>
                </tr>
                {/foreach}
                <tr class="ac-load-more-remove"><td colspan="9" align="center">{$rows.paging.html}</td></tr>
                {if $ajax.disable}
            </tbody>
        </table>
    </div>
</div>
{$ajax.fm.end}
{/if}
