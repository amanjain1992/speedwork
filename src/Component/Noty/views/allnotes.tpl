{if $ajax.disable}
{$ajax.form}
<h4>Notifications (<span class="ac-ajax-total">{$rows.total}</span>)</h4>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
    <tbody  class="ac-ajax-content">
        {/if}
        {foreach $rows.data as $row}
        <tr>
            <td>{$row.serial}</td>
            <td>{$row.message}</td>
            <td>{$row.posted|todate}</td>
        </tr>
        {/foreach}
        <tr class="ac-load-more-remove"><td colspan="4" style="text-align: center">{$rows.paging.html}</td></tr>
        {if $ajax.disable}
    </tbody>
</table>
{/if}