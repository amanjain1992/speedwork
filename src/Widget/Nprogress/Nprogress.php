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
 * Slim progress bars for Ajax'y applications. Inspired by Google, YouTube, and Medium.
 * ({@link https://github.com/rstacruz/nprogress}).
 */
namespace Turbo\Speedwork\Widget\Nprogress;

use Speedwork\Core\Widget;

class Nprogress extends Widget
{
    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScript('//cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js');
        $this->get('assets')->addScript('assets::nprogress.js');
    }

    public function run()
    {
    }
}
