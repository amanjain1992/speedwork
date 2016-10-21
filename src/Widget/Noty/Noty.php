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
 * Noty is a jQuery plugin that makes it easy to create alert, success, error,
 * information and confirmation messages as an alternative the standard alert dialog.
 * Plugin home page.
 *
 * @link http://needim.github.com/noty/
 */
namespace Turbo\Speedwork\Widget\Noty;

use Speedwork\Core\Widget;

/**
 * @author Sankar <sankar.suda@gmail.com>
 */
class Noty extends Widget
{
    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScript('assets::jquery.noty.min.js');
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
    }
}
