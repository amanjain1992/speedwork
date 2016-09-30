{if $ajax.disable}
{$ajax.fm.start}
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="left"><h4>Menu Items (<span class="ac-ajax-total">{$rows.total}</span>)</h4></td>
        <td align="right" width="70%">
            <div class="input-group">
                <span class="input-group-addon">Filter by</span>
                <input type="text" class="form-control" name="lfilter[name]" placeholder="Name" size="25" >
                <div class='input-group-field'>
                    <input type="text" class="form-control" name="lfilter[parent_menu]" placeholder="Parent" size="15" />
                </div>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">Go</button>
                    <button type="reset" class="ac-filter-reset btn btn-default">Reset</button>
                    <a href="{speed link="menu/item?menu_type={$menu_type}"}" title="Add New" class="qtipmodal btn btn-info" role="button"><i class="fa fa-plus"></i> New</a>
                    <a href="{speed link="menu"}" title="Go Back" class="btn btn-info" role="button"><i class="fa fa-undo"></i> Back</a>
                </span>
            </div>
        </td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ui-data-table table table-bordered ac-ui-sortable">
    <thead>
        <tr>
            <th>#</th>
            <th width="5%">SI</th>
            <th data-order="name">Title</th>
            <th>Link</th>
            <th><center>Parent Menu</center></th>
            <th><center>Access</center></th>
            <th><center>Ordering</center></th>
            <th><center>Status</center></th>
            <th><center>Option</center></th>
        </tr>
    </thead>
    <tbody  class="ac-ajax-content table_sortable_body">
        {/if}
        {foreach $rows.data as $row}
        <tr>
            <td class="griddragdrop" align="center">
                <i class="fa fa-arrows-v fa-lg"></i>
                <input type="hidden" name="sorting[{$row.parent_id}][{$row.menu_id}]" value ="{$row.ordering}">
            </td>
            
            <td>{$row.serial}</td>
            <td>{$row.name}</td>
            <td>{$row.link}</td>
            <td align="center">{$row.parent_menu}</td>
            <td align="center">{$row.access}</td>
            <td align="center">{$row.ordering}</td>
            <td align="center">
                <div class="ac-action-status" data-link="{speed link="menu/items?task=status&id={$row.menu_id}"}">
                    {$row.status|status:1}
                </div>
            </td>
            <td align="center">
                <a href="{speed link="index.php?option=menu&view=item&menu_type={$menu_type}&menu_id={$row.menu_id}"}" title="Edit" class="qtipmodal" >
                    <i class="fa fa-edit fa-lg help-tip" title="Edit in Menu"></i>
                </a>
                &nbsp;
                <a href="{speed link="index.php?option=menu&view=items&task=delete&id={$row.menu_id}"}" class="ac-action-delete">
                    <i class="fa fa-trash-o fa-lg help-tip" title="Delete Menu {$row.title}"></i>
                </a>
            </td>
        </tr>
        {/foreach}
        <tr class="ac-load-more-remove"><td colspan="9" align="center">{$rows.paging.html}</td></tr>
        {if $ajax.disable}
    </tbody>
</table>
{$ajax.fm.end}
{/if}