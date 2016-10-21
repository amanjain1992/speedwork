<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

/**
 * JuiWidget class file.
 *
 * This is the base class for all JUI widget classes.
 */
namespace Turbo\Speedwork\Widget\Bootstrap;

use Speedwork\Core\Widget;

class Tokenfield extends Widget
{
    public $defaultOptions = [
        'createTokensOnBlur' => 'true',
    ];

    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('static::bootstrap-tokenfield/dist/css/bootstrap-tokenfield.min.css');
        $this->get('assets')->addStyleSheet('static::bootstrap-tokenfield/dist/css/tokenfield-typeahead.min.css');
        $this->get('assets')->addScript('static::bootstrap-tokenfield/dist/bootstrap-tokenfield.min.js');
    }

    public function run()
    {
        $name      = 'tokenfield';
        $selectors = [];
        $selector  = $this->options['selector'];
        if (empty($selector)) {
            $selector = '.'.str_replace('.', '-', $name);
        }
        $selectors[] = $selector;
        $selectors[] = '[role='.$name.']';

        $js = 'jQuery("'.implode(',', $selectors).'").livequery(function(){';
        $js .= '     var $this = $(this);';
        $js .= '     $this.'.$name.'('.$this->getDecodedOptions().')';
        $js .= ".on('tokenfield:createtoken', function (event) {
                        var existingTokens = $(this).tokenfield('getTokens');
                        $.each(existingTokens, function(index, token) {
                            if (token.value === event.attrs.value)
                                event.preventDefault();
                        });
                    })";
        $js .= '});';

        $this->get('assets')->addScriptDeclaration($js);
    }
}
