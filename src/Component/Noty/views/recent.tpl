<section class="noty-list" style="min-width:600px;">
    {foreach $rows as $row}
        <ul>
            <li class="noty-list-date"><span>{$row.created|todate:"M d"}</span></li>
            <li class="noty-list-icon"><i class="ion-android-notifications-none ion ion-lg"></i></li>
            <li>
            {if $row.meta.link}
                <a href="{$row.meta.link}" class="noty-list-link">{$row.message}</a></li>
            {else}
                {$row.message}
            {/if}
        </ul>
    {foreachelse}
    <div class="noty-read-more text-center">
        No unread notifications.
    </div>
    {/foreach}
    <div class="text-center noty-read-more"><a href="{speed link="noty"}">View all notifications</a></div>
</section>
