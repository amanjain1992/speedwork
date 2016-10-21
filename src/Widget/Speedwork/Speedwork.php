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
        $all = false;

        if ($all) {
            $source = '{script}.js';
            foreach ($this->scripts as $script) {
                $this->get('assets')->addScript('assets::speedwork/'.str_replace('{script}', $script, $source));
            }
        } else {
            $this->get('assets')->addScript('assets::speedwork.min.js');
        }
    }

    public function run()
    {
    }
}
