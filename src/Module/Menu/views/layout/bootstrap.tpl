<div class="navbar-collapse collapse navbar-horizontal">
    <ul class="nav navbar-nav navbar-right">
        {foreach $items as $item}
        <li {if $item.childs} class="dropdown" {/if}>
            <a href="{$item.url}" class="dropdown-toggle" data-toggle="dropdown" title="{$item.name}"><i {$item.iattr}></i>{$item.name}</a>
            {if $item.childs}
            <ul class="dropdown-menu">
                {foreach $item.childs as $child}
                    <li><a href="{$child.url}" title="{$child.name}">{$child.name}</a></li>
                {/foreach}
            </ul>
            {/if}
        </li>
        {/foreach}
    </ul>
</div>
