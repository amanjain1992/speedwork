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

class Jquery extends Widget
{
    public function beforeRun()
    {
        $this->get('assets')->addScript('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js');
    }

    public function run()
    {
    }
}
