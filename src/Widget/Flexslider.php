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
namespace Turbo\Speedwork\Widget;

use Speedwork\Core\Widget;

class Flexslider extends Widget
{
    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('static::flexslider/flexslider.css');
        $this->get('assets')->addScript('static::flexslider/jquery.flexslider-min.js');
    }

    public function run()
    {
        $this->setRun('flexslider');
    }
}
