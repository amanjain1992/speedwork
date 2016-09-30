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

class Touchspin extends Widget
{
    public function beforeRun()
    {
        $this->get('assets')->addScript('bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js', 'bower');
        $this->get('assets')->addStyleSheet('bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css', 'bower');
    }

    public function run()
    {
        $this->setRun('TouchSpin');
    }
}
