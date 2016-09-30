{if $ajax.disable}
{$ajax.form}
<form class="ac-filter-form">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left"><h4>Announcements (<span class="ac-ajax-total">{$rows.total}</span>)</h4></td>
            <td align="right" width="50%">
                <div class="input-group">
                    <span class="input-group-addon">Filter by</span>
                    <input type="text" class="form-control" name="lfilter[message]" placeholder="Announcement" size="20" >
                    <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">Go</button>
                    <button type="reset" class="ac-filter-reset btn btn-default"> Reset</button>
                    <a href="{speed link="index.php?option=noty&view=add"}" title="Add Announcement" class="qtipbox btn btn-info" role="button"><i class="fa fa-plus"></i> New</a>
                    </span>
                </div>
            </td>
        </tr>
    </table>
</form>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ui-data-table table table-bordered">
    <thead>
        <tr>
            <th width="4%">SI</th>
            <th><span>Message</span></th>
            <th width="4%">Status</th>
            <th data-order="created" width="160"><span>Posted On</span></th>
            <th width="60">Action</th>
        </tr>
    </thead>
    <tbody  class="ac-ajax-content">
        {/if}
        {foreach $rows.data as $row}
        <tr id="number{$row.id}">
            <td>{$row.serial}</td>
            <td>{$row.message}</td>
            <td>{$row.status|status}</td>
            <td>{$row.created|todate}</td>
            <td>
                <a href="{speed link="index.php?option=noty&view=add&id={$row.id}"}" class="qtipbox" title="Edit Details">
                <i class="fa fa-edit fa-lg"></i>
                </a>
                &nbsp;
                <a href="{speed link="index.php?option=noty&view=delete&id={$row.id}"}" class="ac-action-delete">
                <i class="fa fa-trash-o fa-lg"></i>
                </a>
            </td>
        </tr>
        {/foreach}
        <tr class="ac-load-more-remove"><td colspan="9" align="center">{$rows.paging.html}</td></tr>
        {if $ajax.disable}
    </tbody>
</table>
{/if}