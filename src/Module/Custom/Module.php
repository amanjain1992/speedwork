<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Module\Custom;

use Speedwork\Core\Module as BaseModule;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Module extends BaseModule
{
    public function index(&$options)
    {
        if ($options['custom_content']) {
            //if found content send to content helper to replace tags
            $content                   = $this->get('resolver')->helper('content.content');
            $options['custom_content'] = $content->index($options['custom_content']);
        }

        return [
            'custom_content' => $options['custom_content'],
        ];
    }
}
