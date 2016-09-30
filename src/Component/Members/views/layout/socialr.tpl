{foreach $providers as $link}
<a href="{speed link="index.php?option=members&view=social&network={$link.provider}"}"  title="{$link.title}" class="btn btn-block btn-social btn-{$link.class}"><i class="fa fa-2x fa-{$link.class}"></i> &nbsp; Register with {$link.title}</a>
{/foreach}