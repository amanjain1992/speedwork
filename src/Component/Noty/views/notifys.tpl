<div class="list-group">
    {if $rows.count eq 0}
    <div class="list-group-item">
        No new notifications.
    </div>
    {else}
    {foreach $rows.list as $row}
    <div class="list-group-item {if $row.status eq 0}list-group-item-info{/if}">
        <a href="{speed link="index.php?option=noty&view=allnotes"}">
        {$row.message}
        <span class="badge">{$row.posted}</span>
        </a>
    </div>
    {/foreach}
    {/if}
</div>