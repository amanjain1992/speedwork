{if $ajax.disable}
{$ajax.fm.start}
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="header">Notifications (<span class="ac-ajax-total">{$rows.total}</span>)</h3>
    </div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
        <tbody  class="ac-ajax-content">
            {/if}
            <tr>
                <th width="10">SI</th>
                <th>Type</th>
                <th>Message</th>
                <th width="170">Posted</th>
                <th>Read</th>
            </tr>
            {foreach $rows.data as $row}
            <tr>
                <td>{$row.serial}</td>
                <td>{$row.meta.type}</td>
                <td>{if $row.meta.link}
                    <a href="{$row.meta.link}" class="noty-list-link">{$row.message}</a>
                    {else}
                    {$row.message}
                {/if}</td>
                <td>{$row.created|todate}</td>
                <td>{$row.status|status}</td>
            </tr>
            {/foreach}
            <tr class="ac-load-more-remove"><td colspan="4" align="center">{$rows.paging.html}</td></tr>
            {if $ajax.disable}
        </tbody>
    </table>
</div>
{$ajax.fm.end}
{/if}
