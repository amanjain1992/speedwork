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

class Time extends Widget
{
    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('bootstrap-timepicker/css/bootstrap-timepicker.min.css', 'bower');
        $this->get('assets')->addScript('bootstrap-timepicker/js/bootstrap-timepicker.js', 'bower');
    }

    public function run()
    {
        $this->setRun('timepicker');
    }
}
