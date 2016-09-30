<ul>
    {foreach $items as $item}
    <li>
        <a href="{$item.url}" data-toggle="collapse" title="{$item.name}"><i {$item.iattr}></i>{$item.name}</a>
        {if $item.childs}
            <ul>
                {foreach $item.childs as $child}
                    <li><a href="{$child.url}" title="{$child.name}">{$child.name}</a></li>
                {/foreach}
            </ul>
        {/if}
    </li>
    {/foreach}
</ul>