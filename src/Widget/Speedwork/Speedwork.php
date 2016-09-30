<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Widget\Speedwork;

use Speedwork\Core\Widget;

class Speedwork extends Widget
{
    protected $scripts = [
        'speedwork',
        'metadata',
        'form',
        'livequery',
        'validator',
        'validate',
        'easysubmit',
        'speedrender',
        'core',
        'system',
    ];

    public function beforeRun()
    {
        $source = 'min/{script}.min.js';
        $source = '{script}.js';
        foreach ($this->scripts as $script) {
            $this->get('assets')->addScript(__DIR__.'/assets/'.str_replace('{script}', $script, $source));
        }
    }

    public function run()
    {
    }
}
