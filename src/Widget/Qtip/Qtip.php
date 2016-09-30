<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Widget\Qtip;

use Speedwork\Core\Widget;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Qtip extends Widget
{
    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScript(__DIR__.'/assets/jquery.qtip.min.js');
        $this->get('assets')->addScript(__DIR__.'/assets/easytip1.js');
        $this->get('assets')->addScript(__DIR__.'/assets/tooltip.js');
        $this->get('assets')->addStyleSheet(__DIR__.'/assets/jquery.qtip.min.css');
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        $this->setRun('qtip');
    }
}
