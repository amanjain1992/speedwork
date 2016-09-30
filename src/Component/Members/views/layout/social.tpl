{foreach $providers as $link}
    <a href="{speed link="members/social?network={$link.provider}"}" class="btn btn-block btn-social btn-{$link.class}"><i class="fa fa-{$link.class}"></i> &nbsp; Sign in with {$link.title}</a>
{/foreach}